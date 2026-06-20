const fs = require('fs');
const text = fs.readFileSync('data/CDX0428.DRF', 'latin1');

function parseCSV(text) {
  const rows = []; let inQ = false, field = '', row = [];
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (c === '"') { if (inQ && text[i+1] === '"') { field += '"'; i++; } else inQ = !inQ; }
    else if (c === ',' && !inQ) { row.push(field); field = ''; }
    else if ((c === '\n' || c === '\r') && !inQ) {
      row.push(field); if (row.some(f => f.trim())) rows.push(row);
      row = []; field = ''; if (c === '\r' && text[i+1] === '\n') i++;
    } else { field += c; }
  }
  if (row.length) { row.push(field); if (row.some(f => f.trim())) rows.push(row); }
  return rows;
}

const JOCKEY_TIER1 = new Set(['ORTIZ IRAD JR','GAFFALIONE TYLER','ORTIZ JOSE L','SAEZ LUIS','PRAT FLAVIEN','VELAZQUEZ J R','ROSARIO JOEL','ALVARADO JUNIOR']);
const JOCKEY_TIER2 = new Set(['GEROUX FLORENT','HERNANDEZ B J JR','SAEZ GABRIEL','CANNON DECLAN','LEPAROUX JULIEN R','BESCHIZZA ADAM','MORALES EDGAR','CONCEPCION AXEL']);
const JOCKEY_TURF_PREFERRED = new Set(['PRAT FLAVIEN','VELAZQUEZ J R','ROSARIO JOEL','CANNON DECLAN','LEPAROUX JULIEN R','BESCHIZZA ADAM','HUSBANDS MICAH J']);
const TURF_SIRES = new Set(["KITTEN'S JOY","WAR FRONT","GALILEO","FRANKEL","DUBAWI","OSCAR PERFORMANCE","GIFT BOX","KARAKONTIE","RECOLETOS","ENGLISH CHANNEL","CONGRATS","MENDELSSOHN","GET STORMY","TORONADO","TEOFILO","CAPE CROSS","DANSILI","SHAMARDAL","LOPE DE VEGA","SEA THE STARS","MEDAGLIA D'ORO","NO NAY NEVER","SHANCELOT"]);
const DIRT_SIRES = new Set(["INTO MISCHIEF","GUN RUNNER","CURLIN","QUALITY ROAD","TAPIT","UNCLE MO","AMERICAN PHAROAH","JUSTIFY","FROSTED","SPEIGHTSTOWN","MUNNINGS","KANTHAROS","CITY OF LIGHT","CONSTITUTION","HONOR A. P.","IMPROBABLE","ARMY MULE","VINO ROSSO","AUTHENTIC","VOLATILE","LIAM'S MAP","MAXIMUM SECURITY","GIRVIN","COAL FRONT","CLASSIC EMPIRE","STAY THIRSTY","NOBLE BIRD","OMAHA BEACH","MO TOWN","OUTWORK","CANDY RIDE","STREET SENSE"]);
const TRAINER_PROFILES = {
  'PLETCHER TODD A':{ msw:1.22, allowance:1.18, stakes:1.15, default:1.12 },
  'BAFFERT BOB':{ msw:1.22, stakes:1.20, default:1.15 },
  'BROWN CHAD':{ turf:1.24, default:1.10 },
  'COX BRAD H':{ default:1.18 },
  'ASMUSSEN STEVEN M':{ default:1.12 },
  'MOTT WILLIAM I':{ turf:1.18, stakes:1.15, default:1.10 },
  'MOTION H GRAHAM':{ turf:1.22, default:1.08 },
  'CASSE MARK':{ turf:1.18, default:1.08 },
  'MAKER MICHAEL J':{ default:1.10 },
  'MCPEEK KENNETH G':{ default:1.08 },
  'STALL ALBERT M JR':{ default:1.07 },
  'DESORMEAUX J KEITH':{ claiming:1.10, default:1.05 },
  'WARD WESLEY A':{ msw:1.20, default:1.10 },
  'SHARP JOE':{ default:1.05 },
};
const HOT_COMBOS = {
  'PLETCHER TODD A':['ORTIZ IRAD JR','VELAZQUEZ J R'],
  'COX BRAD H':['GAFFALIONE TYLER'],
  'ASMUSSEN STEVEN M':['GEROUX FLORENT'],
  'MAKER MICHAEL J':['PRAT FLAVIEN'],
  'MOTT WILLIAM I':['VELAZQUEZ J R'],
  'BROWN CHAD':['IRAD JR','PRAT FLAVIEN','ROSARIO JOEL'],
};

function parseClassValue(cls) {
  if (!cls) return null;
  const s = cls.toUpperCase().replace(/[^A-Z0-9]/g, '');
  if (/G1/.test(s)) return 500000; if (/G2/.test(s)) return 300000; if (/G3/.test(s)) return 200000;
  const nums = [...s.matchAll(/(\d{4,6})/g)].map(m => parseInt(m[1]));
  const amount = nums.length ? Math.max(...nums) : 0;
  if (/STK|OAKS|DERBY|CUP|HCP|HANDICAP/.test(s) && !/^MD/.test(s)) return amount > 0 ? amount : 150000;
  if (/AOC|OCLM|OC\d/.test(s)) return amount > 0 ? amount * 1.2 : 75000;
  if (/ALW|ALLOW/.test(s)) return amount > 0 ? amount : 75000;
  if (/MDSPWT|MDSP|MSW/.test(s)) return 55000;
  if (/^MD/.test(s)) return amount > 0 ? amount * 0.7 : 25000;
  if (/CLM|CLAIM/.test(s)) return amount > 0 ? amount : 30000;
  return amount > 0 ? amount : null;
}

function classAdjustedFig(rawFig, ppClassVal, todayClassVal) {
  if (!rawFig || !ppClassVal || !todayClassVal) return rawFig;
  return rawFig + Math.max(-10, Math.min(10, Math.log2(ppClassVal / todayClassVal) * 4));
}

function ppClassAdjFigs(pp, surface, todayClassVal) {
  const code = surface === 'Turf' ? 'T' : 'D';
  const onSurf = pp.filter(p => p.surface === code && p.speedFig > 0);
  const pool = onSurf.length >= 2 ? onSurf : pp.filter(p => p.speedFig > 0);
  const figs = todayClassVal
    ? pool.map(p => classAdjustedFig(p.speedFig, parseClassValue(p.cls), todayClassVal))
    : pool.map(p => p.speedFig);
  if (figs.length >= 4) {
    const minVal = Math.min(...figs);
    const idx = figs.indexOf(minVal);
    const rest = figs.filter((_, i) => i !== idx);
    const restAvg = rest.reduce((s, f) => s + f, 0) / rest.length;
    if (restAvg - minVal >= 10) return rest;
  }
  return figs;
}

// Workout score: 0–25 additive pts based on recent work quality.
// Percentile rank = 1 - (rank-1)/(count-1), higher is better.
// Weighted toward most recent 3 works. Bullets get a bonus.
// For no-fig horses, scaled up 1.5× (workouts are the only signal).
function workoutScore(h, raceDate, hasPP) {
  if (!h.workouts || !h.workouts.length) return 0;
  const rY = parseInt(raceDate.slice(0,4)), rM = parseInt(raceDate.slice(4,6)), rD = parseInt(raceDate.slice(6,8));
  const raceDays = rY*365 + rM*30 + rD;
  const recent = h.workouts.filter(w => {
    const wY=parseInt(w.date.slice(0,4)), wM=parseInt(w.date.slice(4,6)), wD=parseInt(w.date.slice(6,8));
    return (raceDays - (wY*365+wM*30+wD)) <= 90;
  });
  if (!recent.length) return 0;
  const scored = recent.map(w => {
    const pct = w.count > 1 ? 1 - (w.rank - 1) / (w.count - 1) : 1.0;
    const bullet = w.bullet ? 0.15 : 0;
    return Math.min(1.0, pct + bullet);
  }).slice(0, 3);
  const w = scored.length >= 3 ? scored[0]*0.5 + scored[1]*0.3 + scored[2]*0.2
          : scored.length === 2 ? scored[0]*0.6 + scored[1]*0.4
          : scored[0];
  const base = w * 20;
  return hasPP ? base : base * 1.5;  // no-fig horses: workouts carry more weight
}

// In maiden claiming races the longshot penalty is too harsh —
// these fields are full of chronic non-winners, so ML spread is wider and less predictive.
function longShotMultiplierForClass(morningLine, isMaidenClaiming) {
  if (isMaidenClaiming) {
    // Softer curve: penalize but don't bury a longshot that might be training well
    if (morningLine <= 15) return 1.00;
    if (morningLine <= 20) return 0.94;
    if (morningLine <= 30) return 0.88;
    return 0.82;
  }
  if (morningLine <= 10) return 1.00;
  if (morningLine <= 15) return 0.92;
  if (morningLine <= 20) return 0.84;
  if (morningLine <= 30) return 0.76;
  return 0.68;
}

function sampleConfidenceMultiplier(adjFigs) {
  const n = adjFigs.length;
  if (n <= 1) return 0.88;
  if (n <= 2) return 0.93;
  if (n <= 3) return 0.97;
  return 1.00;
}

function lastRaceBlowoutMultiplier(h) {
  if (!h.pp || !h.pp.length) return 1.0;
  const last = h.pp[0];
  if (!last || last.finish === 1 || last.blFinish == null) return 1.0;
  const bl = Math.abs(last.blFinish);
  if (bl <= 10) return 1.00;
  if (bl <= 20) return 0.97;
  if (bl <= 35) return 0.93;
  return 0.88;
}

function figConsistencyScore(pp, surface, todayClassVal) {
  const figs = ppClassAdjFigs(pp, surface, todayClassVal).slice(0, 5);
  if (!figs.length) return null;
  const avg = figs.reduce((s, f) => s + f, 0) / figs.length;
  const variance = figs.reduce((s, f) => s + (f - avg) ** 2, 0) / figs.length;
  return avg - Math.sqrt(variance) * 0.5;
}

function avgRecentFig(pp, surface, todayClassVal, n=3) {
  const figs = ppClassAdjFigs(pp, surface, todayClassVal).slice(0, n);
  return figs.length ? figs.reduce((s, f) => s + f, 0) / figs.length : null;
}

function bestSpeedFig(pp, surface, todayClassVal) {
  const figs = ppClassAdjFigs(pp, surface, todayClassVal).slice(0, 5);
  return figs.length ? Math.max(...figs) : null;
}

function figTrend(pp, surface, todayClassVal) {
  const figs = ppClassAdjFigs(pp, surface, todayClassVal);
  if (figs.length < 3) return 0;
  return figs[0] - figs[Math.min(2, figs.length - 1)];
}

function closingDelta(p) {
  if (p.bl2nd == null || p.blFinish == null) return null;
  return p.bl2nd - p.blFinish;
}

function horseClosingScore(pp, surface, furlongs, n=5) {
  const surfCode = surface === 'Turf' ? 'T' : 'D';
  const distCat = furlongs <= 7 ? 'sprint' : 'route';
  const qualifying = pp.filter(p => {
    if (p.surface !== surfCode || !p.distYards) return false;
    return (p.distYards / 220 <= 7 ? 'sprint' : 'route') === distCat;
  });
  const deltas = qualifying.slice(0, n).map(closingDelta).filter(d => d !== null);
  return deltas.length >= 2 ? deltas.reduce((s, d) => s + d, 0) / deltas.length : null;
}

function longShotMultiplier(ml) {
  if (ml <= 10) return 1.00;
  if (ml <= 15) return 0.92;
  if (ml <= 20) return 0.84;
  if (ml <= 30) return 0.76;
  return 0.68;
}

function winRateMultiplier(h, surface) {
  if (!h.pp || !h.pp.length) return 1.0;
  const surfCode = surface === 'Turf' ? 'T' : 'D';
  const onSurf = h.pp.filter(p => p.surface === surfCode && p.finish);
  if (onSurf.some(p => p.finish === 1)) return 1.0;
  const pool   = onSurf.length >= 3 ? onSurf : h.pp.filter(p => p.finish);
  const recent = pool.slice(0, 5);
  if (recent.length < 3) return 1.0;
  const wins = recent.filter(p => p.finish === 1).length;
  if (wins > 0) return 1.0;
  return recent.length >= 5 ? 0.90 : 0.94;
}

function layoffAdjustment(daysSince, trainerROI, trainerStarts, classMult) {
  if (!daysSince || daysSince <= 60) return 1.0;
  const severity = daysSince <= 90 ? 0.5 : daysSince <= 180 ? 1.0 : 1.5;
  const trainerQ = (trainerStarts >= 10 && trainerROI != null) ? trainerROI : 0;
  const mult = Math.max(0.92, Math.min(1.08, 1.0 + (trainerQ - 0.3) * severity * 0.2));
  if (classMult > 1.05 && mult < 1.0) return 1.0;
  return mult;
}

function surfaceWinBonus(pp, surface) {
  const code = surface === 'Turf' ? 'T' : 'D';
  const races = pp.filter(p => p.surface === code && p.finish);
  if (races.length < 2) return 0;
  const wins = races.filter(p => p.finish === 1).length;
  const places = races.filter(p => p.finish <= 3).length;
  return wins * 12 + (places - wins) * 4;
}

function roiMultiplier(roi, starts, minStarts=10) {
  if (roi == null || !starts || starts < minStarts) return null;
  return Math.max(0.93, Math.min(1.20, 1.00 + roi * 0.06));
}

function tjRoiMultiplier(roi, starts) {
  if (roi == null || !starts || starts < 3) return null;
  return Math.max(0.92, Math.min(1.18, 1.00 + roi * 0.08));
}


function getJockeyMultiplier(jockey, surface) {
  const isTurf = surface === 'Turf';
  const turfPref = JOCKEY_TURF_PREFERRED.has(jockey);
  const onPref = isTurf ? turfPref : !turfPref;
  if (JOCKEY_TIER1.has(jockey)) return onPref ? 1.22 : 1.15;
  if (JOCKEY_TIER2.has(jockey)) return onPref ? 1.12 : 1.07;
  return 1.00;
}

function getTrainerMultiplier(trainer, surface) {
  const p = TRAINER_PROFILES[trainer]; if (!p) return 1.00;
  if (surface === 'Turf' && p.turf) return p.turf;
  return p.allowance || p.default || 1.05;
}

function getPostBonus(post, furlongs, surface, fieldSize) {
  let min, max;
  if (surface === 'Turf') { min = fieldSize >= 12 ? 4 : 3; max = fieldSize >= 12 ? 9 : 8; }
  else { min = 1; max = furlongs <= 6 ? 4 : furlongs <= 7 ? 6 : 7; }
  if (post >= min && post <= max) return 8;
  if (post > max + 3) return -6;
  return 0;
}

function getSireBonus(sire, surface) {
  if (surface === 'Turf' && TURF_SIRES.has(sire)) return 8;
  if (surface === 'Dirt' && DIRT_SIRES.has(sire)) return 5;
  if (surface === 'Turf' && DIRT_SIRES.has(sire)) return -5;
  if (surface === 'Dirt' && TURF_SIRES.has(sire)) return -3;
  return 0;
}

function getWeightPenalty(weight, avgWeight, furlongs) {
  const diff = weight - avgWeight;
  if (Math.abs(diff) < 3) return 0;
  return diff * 0.4 * (furlongs <= 7 ? 1.5 : 1.0);
}

function classDropMultiplier(pp, todayClassVal) {
  if (!todayClassVal) return 1.0;
  const ppData = pp.slice(0, 3).map(p => ({ classVal: parseClassValue(p.cls), finish: p.finish, blFinish: p.blFinish })).filter(d => d.classVal != null);
  if (!ppData.length) return 1.0;
  const avg = ppData.reduce((a, d) => a + d.classVal, 0) / ppData.length;
  const ratio = avg / todayClassVal;
  // Extreme drops (3×+): even a horse losing badly at that level is overqualified here.
  // Raise both the base and the competition-quality floor so poor finishes at much higher
  // class can't erase the bonus entirely.
  const base  = ratio >= 3.0 ? 1.25 : ratio >= 2.5 ? 1.20 : ratio >= 2.0 ? 1.17
              : ratio >= 1.5 ? 1.15 : ratio >= 1.25 ? 1.10 : ratio >= 1.1 ? 1.05
              : ratio >= 0.95 ? 1.00 : ratio >= 0.80 ? 0.96 : 0.92;
  const floor = ratio >= 3.0 ? 1.15 : ratio >= 2.5 ? 1.10 : 0.95;
  if (ratio < 0.95) {
    const last = ppData[0];
    if (last && last.finish === 1) {
      const margin = last.blFinish != null ? Math.abs(last.blFinish) : 0;
      return margin >= 3 ? 1.05 : 1.00;
    }
    return base;
  }
  if (ratio < 1.1) return base;
  const higher = ppData.filter(d => d.classVal > todayClassVal * 1.05);
  if (!higher.length) return base;
  const comp = d => {
    if (d.blFinish != null) { if (d.blFinish <= 2) return 1.00; if (d.blFinish <= 5) return 0.75; if (d.blFinish <= 10) return 0.45; return 0.10; }
    if (d.finish <= 2) return 1.00; if (d.finish <= 3) return 0.80; if (d.finish <= 5) return 0.50; return 0.20;
  };
  const avgComp = higher.map(comp).reduce((a, b) => a + b, 0) / higher.length;
  return Math.max(floor, 1.0 + (base - 1.0) * Math.max(0, avgComp));
}

// ── PARSE & SCORE ───────────────────────────────────────────────
const rows = parseCSV(text);
const RACENUM = parseInt(process.argv[2]) || 1;
const SCRATCHES = new Set((process.argv[3]||'').split(',').map(Number).filter(Boolean));
const race3rows = rows.filter(r => parseInt(r[2]) === RACENUM);
const raceClass = (race3rows[0][10]||'').trim();
const surfCode0 = (race3rows[0][6]||'').trim();
const surface = surfCode0 === 'T' ? 'Turf' : 'Dirt';
const furlongs = parseInt(race3rows[0][5]) / 220;
const todayClassVal = parseClassValue(raceClass);

const horses = race3rows.map(e => {
  const post = parseInt(e[42]);
  if (SCRATCHES.has(post)) return null;
  const block = (start, len) => Array.from({length:len}, (_,i) => (e[start+i]||'').trim()).map(v => v===''?null:v);
  const ppDates = block(255,10), ppSurfaces = block(325,10), ppClass = block(535,10);
  const ppFinish = block(355,10).map(v => v ? parseInt(v)||null : null);
  const ppFigs   = block(765,10).map(v => v ? parseFloat(v)||null : null);
  const ppBl2nd  = block(675,10).map(v => v ? parseFloat(v)||null : null);
  const ppBlFin  = block(735,10).map(v => v ? parseFloat(v)||null : null);
  const ppDist   = block(315,10).map(v => v ? parseInt(v)||null : null);
  const pp = ppDates.map((date,i) => !date ? null : {
    date, surface: ppSurfaces[i], cls: ppClass[i], finish: ppFinish[i],
    speedFig: ppFigs[i], bl2nd: ppBl2nd[i], blFinish: ppBlFin[i], distYards: ppDist[i]
  }).filter(Boolean);
  return {
    post, name:(e[44]||'').trim(), jockey:(e[32]||'').trim(), trainer:(e[27]||'').trim(),
    sire:(e[51]||'').trim(), weight:parseInt(e[50])||0, morning_line:parseFloat(e[43])||99,
    days_since:parseInt(e[223])||0,
    tj_starts:parseInt(e[218])||0, tj_roi:parseFloat(e[222])||0,
    trainer_roi:parseFloat(e[1150])||0, trainer_starts:parseInt(e[1146])||0,
    jockey_roi:parseFloat(e[1160])||0, jockey_starts:parseInt(e[1156])||0,
    pp,
    workouts: Array.from({length:12},(_,i)=>{
      const date = (e[102+i]||'').trim();
      if (!date || date.length < 8) return null;
      const time  = parseFloat(e[114+i]) || 0;   // negative = bullet
      const dist  = parseInt(e[138+i])   || 0;   // yards
      const rank  = parseInt(e[198+i])   || 0;
      const count = parseInt(e[186+i])   || 0;
      return { date, time, dist, rank, count, bullet: time < 0 };
    }).filter(Boolean),
  };
}).filter(Boolean);

const fieldSize = horses.length;
const avgWeight = horses.reduce((s,h) => s+h.weight, 0) / fieldSize;
const isMaidenClaiming = /^Md\s*\d/i.test(raceClass) && /claim|clm/i.test(raceClass.replace(/Md\s*/i,''));
const raceDate = (race3rows[0][1]||'').trim();

const raw = horses.map(h => {
  const hasPP = h.pp.length > 0;
  const adjFigs = ppClassAdjFigs(h.pp, surface, todayClassVal);
  const avgFig  = hasPP ? avgRecentFig(h.pp, surface, todayClassVal) : null;
  const bestFig = hasPP ? bestSpeedFig(h.pp, surface, todayClassVal) : null;
  const trend   = hasPP ? figTrend(h.pp, surface, todayClassVal) : 0;
  const form    = hasPP ? figConsistencyScore(h.pp, surface, todayClassVal) : null;
  const close   = hasPP ? horseClosingScore(h.pp, surface, furlongs) : null;
  const sw      = hasPP ? surfaceWinBonus(h.pp, surface) : 0;
  const wkScore = workoutScore(h, raceDate, hasPP);
  const mlProb  = 1 / ((h.morning_line||99) + 1);
  const jMult   = roiMultiplier(h.jockey_roi, h.jockey_starts) ?? getJockeyMultiplier(h.jockey, surface);
  const tMult   = roiMultiplier(h.trainer_roi, h.trainer_starts) ?? getTrainerMultiplier(h.trainer, surface);
  const tjMult  = tjRoiMultiplier(h.tj_roi, h.tj_starts) ?? ((HOT_COMBOS[h.trainer]||[]).includes(h.jockey) ? 1.06 : 1.00);
  const classMult   = classDropMultiplier(h.pp, todayClassVal);
  const winMult      = winRateMultiplier(h, surface);
  const mlMult       = longShotMultiplierForClass(h.morning_line, isMaidenClaiming);
  const blowoutMult  = lastRaceBlowoutMultiplier(h);
  const sampleMult   = sampleConfidenceMultiplier(adjFigs);
  const layoffMult  = layoffAdjustment(h.days_since, h.trainer_roi, h.trainer_starts, classMult);
  const postBonus   = getPostBonus(h.post, furlongs, surface, fieldSize);
  const sireBonus   = getSireBonus(h.sire, surface);
  const weightPen   = getWeightPenalty(h.weight, avgWeight, furlongs);
  const closingScore = close;

  // Class ratio for display
  const ppClassVals = h.pp.slice(0,3).map(p => parseClassValue(p.cls)).filter(Boolean);
  const avgPpClass = ppClassVals.length ? ppClassVals.reduce((a,b)=>a+b,0)/ppClassVals.length : null;
  const classRatio = avgPpClass && todayClassVal ? avgPpClass/todayClassVal : null;

  return { h, hasPP, adjFigs, avgFig, bestFig, trend, form, closingScore, sw, wkScore, mlProb,
           jMult, tMult, tjMult, classMult, winMult, mlMult, blowoutMult, sampleMult, layoffMult, postBonus, sireBonus, weightPen, classRatio };
});

const norm = key => {
  const vals = raw.map(d => d[key] !== null && d[key] !== undefined ? d[key] : null);
  const defined = vals.filter(v => v !== null);
  if (!defined.length) return raw.map(() => 62.5);
  const min = Math.min(...defined), max = Math.max(...defined), range = max - min;
  return vals.map(v => v === null ? 62.5 : range > 0.001 ? Math.max(25, ((v-min)/range)*100) : 50);
};

const nFig=norm('avgFig'), nBest=norm('bestFig'), nTrend=norm('trend');
const nForm=norm('form'), nClose=norm('closingScore'), nML=norm('mlProb');

const results = raw.map((d,i) => {
  const base = d.hasPP
    ? nFig[i]*20 + nBest[i]*8 + nTrend[i]*8 + nForm[i]*32 + nClose[i]*14 + nML[i]*8 + d.sw + d.wkScore
    : nML[i]*60;
  // sampleMult only applies when there are figs to be uncertain about
  const effectiveSampleMult = d.adjFigs.length === 0 ? 1.0 : d.sampleMult;
  const afterMults = base * d.jMult * d.tMult * d.tjMult * d.classMult * d.winMult * d.mlMult * d.blowoutMult * effectiveSampleMult * d.layoffMult;
  // For no-fig horses, add workout score post-mults so it isn't compressed by multipliers
  const wkBonus = d.hasPP ? 0 : d.wkScore;
  const score = afterMults + d.postBonus + d.sireBonus - d.weightPen + wkBonus;
  return { ...d, base, afterMults, score,
    nFig:nFig[i], nBest:nBest[i], nTrend:nTrend[i], nForm:nForm[i], nClose:nClose[i], nML:nML[i] };
}).sort((a,b) => b.score - a.score);

// ── PRINT ───────────────────────────────────────────────────────
console.log('\nRace ' + RACENUM + ' — ' + raceClass + ' | ' + furlongs + 'f ' + surface + ' | todayClass=' + todayClassVal);
console.log('Scratches: ' + (SCRATCHES.size ? [...SCRATCHES].join(', ') : 'none') + '\n');

results.forEach((d, rank) => {
  const h = d.h;
  const f1 = v => v != null ? v.toFixed(1).padStart(5) : ' null';
  const f2 = v => v.toFixed(3);
  const actual = '   ';
  console.log(
    `#${rank+1} PP${h.post} ${h.name.padEnd(20)} Score:${d.score.toFixed(0).padStart(6)}  Base:${d.base.toFixed(0).padStart(5)}  [${actual}]`
  );
  console.log(
    `   Norm:  avgFig=${f1(d.nFig)} bestFig=${f1(d.nBest)} trend=${f1(d.nTrend)} consist=${f1(d.nForm)} close=${f1(d.nClose)} ML=${f1(d.nML)}`
  );
  console.log(
    `   Raw:   avgFig=${d.avgFig?d.avgFig.toFixed(1):'null'} bestFig=${d.bestFig?d.bestFig.toFixed(1):'null'} trend=${d.trend?d.trend.toFixed(1):'0'} consist=${d.form?d.form.toFixed(1):'null'} close=${d.closingScore?d.closingScore.toFixed(1):'null'}`
  );
  console.log(
    `   Mults: j=${f2(d.jMult)} t=${f2(d.tMult)} tj=${f2(d.tjMult)} cls=${f2(d.classMult)}(ratio=${d.classRatio?d.classRatio.toFixed(2):'n/a'}) loff=${f2(d.layoffMult)} win=${f2(d.winMult)} ml=${f2(d.mlMult)} blow=${f2(d.blowoutMult)} samp=${f2(d.sampleMult)}(n=${d.adjFigs.length})`
  );
  console.log(
    `   Add:   post=${d.postBonus>=0?'+':''}${d.postBonus} sire=${d.sireBonus>=0?'+':''}${d.sireBonus} wt=${(-d.weightPen)>=0?'+':''}${(-d.weightPen).toFixed(1)}`
  );
  const figStr = d.adjFigs.slice(0,5).map(f=>f.toFixed(1)).join(', ');
  console.log(`   AdjFigs(surf): [${figStr}]  surfWinBonus=${d.sw}  workoutScore=${d.wkScore.toFixed(1)}`);
  console.log('');
});
