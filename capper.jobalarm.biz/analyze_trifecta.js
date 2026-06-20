const fs = require('fs');

function parseDRF(filepath) {
  const buf = fs.readFileSync(filepath);
  const text = buf.toString('latin1');
  const lines = text.split('\n').map(l => l.trim()).filter(l => l.length > 0);
  return lines.map(line => line.split(',').map(f => f.trim().replace(/^"|"$/g,'').trim()));
}

const drfFiles = ['CDX0428.DRF','CDX0429.DRF','CDX0430.DRF','MNR0428.DRF','TDN0428.DRF','TDN0429.DRF','TUP0428.DRF'];
const basePath = 'C:/Users/rstre/Projects/Trifecta/data/';

const allHorses = [];
for(const fname of drfFiles) {
  const rows = parseDRF(basePath + fname);
  for(const row of rows) {
    if(row.length < 300) continue;
    allHorses.push({
      trackCode: row[0], dateRaw: row[1], raceNum: parseInt(row[2]),
      race_class: row[10], purse: parseInt(row[11])||0,
      distYards: parseInt(row[5])||0, surface: row[6],
      post: parseInt(row[42]), name: (row[44]||'').trim().toUpperCase(),
      sex: row[48], morning_line: parseFloat(row[43]),
      jockey: (row[32]||'').trim(), trainer: (row[27]||'').trim(),
      days_since: parseInt(row[223])||null, run_style: (row[209]||'').trim(),
      prime_power: parseFloat(row[250])||null,
      pp0_speedFig: parseInt(row[765])||null, pp0_finish: parseInt(row[355])||null,
      pp0_bl2nd: row[675]?parseFloat(row[675]):null,
      pp0_blFinish: row[735]?parseFloat(row[735]):null,
      pp0_surface: (row[325]||'').trim(), pp0_cond: (row[305]||'').trim(),
      pp1_speedFig: parseInt(row[766])||null, pp1_finish: parseInt(row[356])||null,
      pp2_speedFig: parseInt(row[767])||null, pp2_finish: parseInt(row[357])||null,
    });
  }
}

const results = JSON.parse(fs.readFileSync('C:/Users/rstre/Projects/Trifecta/data/results.json','utf8'));
const hitWinners = [];
const missWinners = [];

for(const [cardKey, card] of Object.entries(results)) {
  const trackCode = card.trackCode;
  const dateYMD = card.dateYMD;
  for(const [raceNum, race] of Object.entries(card.races)) {
    const winner = race.finishers && race.finishers[0];
    if(!winner || !race.predictions || !race.predictions.length) continue;
    const sorted = [...race.predictions].sort((a,b) => b.score - a.score);
    const top3Names = sorted.slice(0,3).map(h => h.name.toUpperCase());
    const winnerName = winner.name.toUpperCase();
    const isHit = top3Names.includes(winnerName);

    const drfHorse = allHorses.find(h => h.name === winnerName && h.raceNum === parseInt(raceNum) && h.dateRaw === dateYMD);
    if(!drfHorse) continue;

    const winnerPred = sorted.find(h => h.name.toUpperCase() === winnerName);
    const fieldSize = allHorses.filter(h => h.raceNum === parseInt(raceNum) && h.dateRaw === dateYMD && h.trackCode === drfHorse.trackCode).length;

    const rec = {
      trackCode, dateYMD, raceNum, winnerName,
      winPayout: winner.win,
      race_class: drfHorse.race_class,
      purse: drfHorse.purse,
      surface: drfHorse.surface,
      distFurlongs: drfHorse.distYards / 220,
      ML: drfHorse.morning_line,
      run_style: drfHorse.run_style,
      days_since: drfHorse.days_since,
      sex: drfHorse.sex,
      pp0_finish: drfHorse.pp0_finish,
      pp0_speedFig: drfHorse.pp0_speedFig,
      pp0_bl2nd: drfHorse.pp0_bl2nd,
      pp0_blFinish: drfHorse.pp0_blFinish,
      pp0_surface: drfHorse.pp0_surface,
      pp0_cond: drfHorse.pp0_cond,
      prime_power: drfHorse.prime_power,
      fieldSize: fieldSize,
      nFig: winnerPred ? winnerPred.nFig : null,
      nBest: winnerPred ? winnerPred.nBest : null,
      nForm: winnerPred ? winnerPred.nForm : null,
      nClose: winnerPred ? winnerPred.nClose : null,
      nLatePace: winnerPred ? winnerPred.nLatePace : null,
      nML: winnerPred ? winnerPred.nML : null,
      jMult: winnerPred ? winnerPred.jMult : null,
      tMult: winnerPred ? winnerPred.tMult : null,
      score: winnerPred ? winnerPred.score : null,
      winPct: winnerPred ? winnerPred.winPct : null,
      algoRank: winnerPred ? sorted.indexOf(winnerPred)+1 : null,
    };
    if(isHit) hitWinners.push(rec);
    else missWinners.push(rec);
  }
}

function avg(arr, fn) {
  const vals = arr.map(fn).filter(v => v !== null && v !== undefined && !isNaN(v));
  return vals.length ? (vals.reduce((a,b)=>a+b,0)/vals.length).toFixed(2) : 'N/A';
}
function cw(arr, fn) { return arr.filter(fn).length; }
function pct(count, total) { return ((count/total)*100).toFixed(1) + '%'; }

const H = hitWinners;
const M = missWinners;

console.log('=== HIT WINNERS: ' + H.length + ' | MISS WINNERS: ' + M.length + ' ===');
console.log('Total races analyzed: ' + (H.length + M.length));

console.log('\n--- Morning Line ---');
console.log('Hits avg ML: ' + avg(H, h=>h.ML));
console.log('Misses avg ML: ' + avg(M, h=>h.ML));
console.log('Hits ML<=5: ' + cw(H,h=>h.ML<=5) + ' (' + pct(cw(H,h=>h.ML<=5), H.length) + ')');
console.log('Misses ML<=5: ' + cw(M,h=>h.ML<=5) + ' (' + pct(cw(M,h=>h.ML<=5), M.length) + ')');
console.log('Hits ML 6-8: ' + cw(H,h=>h.ML>5&&h.ML<=8) + ' (' + pct(cw(H,h=>h.ML>5&&h.ML<=8), H.length) + ')');
console.log('Misses ML 6-8: ' + cw(M,h=>h.ML>5&&h.ML<=8) + ' (' + pct(cw(M,h=>h.ML>5&&h.ML<=8), M.length) + ')');
console.log('Hits ML 9-15: ' + cw(H,h=>h.ML>8&&h.ML<=15) + ' (' + pct(cw(H,h=>h.ML>8&&h.ML<=15), H.length) + ')');
console.log('Misses ML 9-15: ' + cw(M,h=>h.ML>8&&h.ML<=15) + ' (' + pct(cw(M,h=>h.ML>8&&h.ML<=15), M.length) + ')');
console.log('Hits ML>15: ' + cw(H,h=>h.ML>15) + ' (' + pct(cw(H,h=>h.ML>15), H.length) + ')');
console.log('Misses ML>15: ' + cw(M,h=>h.ML>15) + ' (' + pct(cw(M,h=>h.ML>15), M.length) + ')');

console.log('\n--- Run Style ---');
['E','E/P','P','S','C','NA',''].forEach(style => {
  const hc = cw(H,h=>h.run_style===style);
  const mc = cw(M,h=>h.run_style===style);
  if(hc+mc > 0) console.log('"'+style+'": hits='+hc+'('+pct(hc,H.length)+') misses='+mc+'('+pct(mc,M.length)+')');
});

console.log('\n--- Days Since Last Race ---');
console.log('Hits avg: ' + avg(H, h=>h.days_since));
console.log('Misses avg: ' + avg(M, h=>h.days_since));
console.log('Hits <=30: ' + pct(cw(H,h=>h.days_since&&h.days_since<=30), H.length));
console.log('Misses <=30: ' + pct(cw(M,h=>h.days_since&&h.days_since<=30), M.length));
console.log('Hits 31-60: ' + pct(cw(H,h=>h.days_since>30&&h.days_since<=60), H.length));
console.log('Misses 31-60: ' + pct(cw(M,h=>h.days_since>30&&h.days_since<=60), M.length));
console.log('Hits >60 layoff: ' + pct(cw(H,h=>h.days_since>60), H.length));
console.log('Misses >60 layoff: ' + pct(cw(M,h=>h.days_since>60), M.length));
console.log('Hits >180: ' + pct(cw(H,h=>h.days_since>180), H.length));
console.log('Misses >180: ' + pct(cw(M,h=>h.days_since>180), M.length));

console.log('\n--- Last Race Finish Position (PP0) ---');
console.log('Hits avg: ' + avg(H, h=>h.pp0_finish));
console.log('Misses avg: ' + avg(M, h=>h.pp0_finish));
console.log('Hits last=1: ' + pct(cw(H,h=>h.pp0_finish===1), H.length));
console.log('Misses last=1: ' + pct(cw(M,h=>h.pp0_finish===1), M.length));
console.log('Hits last<=3: ' + pct(cw(H,h=>h.pp0_finish&&h.pp0_finish<=3), H.length));
console.log('Misses last<=3: ' + pct(cw(M,h=>h.pp0_finish&&h.pp0_finish<=3), M.length));
console.log('Hits last>=7: ' + pct(cw(H,h=>h.pp0_finish&&h.pp0_finish>=7), H.length));
console.log('Misses last>=7: ' + pct(cw(M,h=>h.pp0_finish&&h.pp0_finish>=7), M.length));

console.log('\n--- Last Race Beaten Lengths at 2nd Call ---');
console.log('Hits avg pp0_bl2nd: ' + avg(H, h=>h.pp0_bl2nd));
console.log('Misses avg pp0_bl2nd: ' + avg(M, h=>h.pp0_bl2nd));
console.log('Misses bl2nd<=1: ' + pct(cw(M,h=>h.pp0_bl2nd!==null&&h.pp0_bl2nd<=1), M.length));
console.log('Misses bl2nd<=2: ' + pct(cw(M,h=>h.pp0_bl2nd!==null&&h.pp0_bl2nd<=2), M.length));

console.log('\n--- nClose (algo normalized closing score) ---');
console.log('Hits avg nClose: ' + avg(H, h=>h.nClose));
console.log('Misses avg nClose: ' + avg(M, h=>h.nClose));
console.log('Misses nClose<=30: ' + pct(cw(M,h=>h.nClose<=30), M.length));
console.log('Misses nClose>=70: ' + pct(cw(M,h=>h.nClose>=70), M.length));

console.log('\n--- Surface ---');
['D','T','A'].forEach(s => {
  const hc = cw(H,h=>h.surface===s);
  const mc = cw(M,h=>h.surface===s);
  console.log(s+': hits='+hc+'('+pct(hc,H.length)+') misses='+mc+'('+pct(mc,M.length)+')');
});

console.log('\n--- Surface Switch (last race vs today) ---');
const switchHits = cw(H, h=>h.pp0_surface&&h.surface&&h.pp0_surface!==h.surface);
const switchMiss = cw(M, h=>h.pp0_surface&&h.surface&&h.pp0_surface!==h.surface);
console.log('Hits with surface switch: ' + switchHits + '(' + pct(switchHits,H.length) + ')');
console.log('Misses with surface switch: ' + switchMiss + '(' + pct(switchMiss,M.length) + ')');
M.forEach(h => {
  if(h.pp0_surface && h.surface && h.pp0_surface !== h.surface) {
    console.log('  ' + h.winnerName + ': last=' + h.pp0_surface + ' today=' + h.surface);
  }
});

console.log('\n--- Race Class ---');
function classifyRace(rc) {
  if(/md sp wt/i.test(rc)) return 'MSW';
  if(/^md\s+\d/i.test(rc)) return 'MdnClm';
  if(/^clm/i.test(rc)) return 'Claiming';
  if(/^alw/i.test(rc)) return 'Allowance';
  if(/^oclm/i.test(rc)) return 'OC';
  return 'Other';
}
['MSW','MdnClm','Claiming','Allowance','OC','Other'].forEach(cat => {
  const hc = cw(H, h=>classifyRace(h.race_class)===cat);
  const mc = cw(M, h=>classifyRace(h.race_class)===cat);
  if(hc+mc>0) {
    const total = hc+mc;
    console.log(cat+': hits='+hc+'('+pct(hc,H.length)+') misses='+mc+'('+pct(mc,M.length)+') miss_rate='+pct(mc,total));
  }
});

console.log('\n--- Track ---');
['CD','TDN','MNR','TUP'].forEach(t => {
  const hc = cw(H,h=>h.trackCode===t);
  const mc = cw(M,h=>h.trackCode===t);
  const total = hc+mc;
  const mr = total>0 ? pct(mc,total) : 'N/A';
  console.log(t+': hits='+hc+' misses='+mc+' total='+total+' miss_rate='+mr);
});

console.log('\n--- Field Size ---');
console.log('Hits avg: ' + avg(H, h=>h.fieldSize));
console.log('Misses avg: ' + avg(M, h=>h.fieldSize));
console.log('Hits field>=10: ' + pct(cw(H,h=>h.fieldSize>=10), H.length));
console.log('Misses field>=10: ' + pct(cw(M,h=>h.fieldSize>=10), M.length));
console.log('Hits field>=12: ' + pct(cw(H,h=>h.fieldSize>=12), H.length));
console.log('Misses field>=12: ' + pct(cw(M,h=>h.fieldSize>=12), M.length));

console.log('\n--- nML (algo normalized ML) ---');
console.log('Hits avg nML: ' + avg(H, h=>h.nML));
console.log('Misses avg nML: ' + avg(M, h=>h.nML));
console.log('Misses nML>=50 (above-median ML): ' + pct(cw(M,h=>h.nML>=50), M.length));
console.log('Misses nML>=60: ' + pct(cw(M,h=>h.nML>=60), M.length));

console.log('\n--- Prime Power ---');
console.log('Hits avg: ' + avg(H, h=>h.prime_power));
console.log('Misses avg: ' + avg(M, h=>h.prime_power));

console.log('\n--- Last Race Wet Track ---');
const wetConds = new Set(['MY','WF','SY','SL','GS','SF','HY','my','wf','sy','sl','gs','sf','hy','FM','fm']);
const hWetLast = cw(H, h=>wetConds.has(h.pp0_cond));
const mWetLast = cw(M, h=>wetConds.has(h.pp0_cond));
console.log('Hits last race was wet: ' + hWetLast + '(' + pct(hWetLast,H.length) + ')');
console.log('Misses last race was wet: ' + mWetLast + '(' + pct(mWetLast,M.length) + ')');
M.forEach(h => {
  if(wetConds.has(h.pp0_cond)) console.log('  ' + h.winnerName + ' last cond: ' + h.pp0_cond);
});

console.log('\n=== MISS WINNERS - DETAILED TABLE ===');
M.forEach(h => {
  const cls = classifyRace(h.race_class);
  const surfSwitch = (h.pp0_surface && h.surface && h.pp0_surface !== h.surface) ? 'SURFSWITCH' : '';
  const layoff = h.days_since > 60 ? 'LAYOFF' : '';
  const badLast = h.pp0_finish >= 7 ? 'BADLAST' : '';
  const flags = [surfSwitch, layoff, badLast].filter(f=>f).join(',');
  console.log(h.winnerName + '\n  ' + h.trackCode + ' R' + h.raceNum + ' | ' + cls + '(' + h.race_class + ') | surf:' + h.surface + ' | ML:' + h.ML + ' | style:' + h.run_style + ' | days:' + h.days_since + ' | algoRank:#' + h.algoRank + '/' + h.fieldSize + ' | flags:[' + flags + ']');
  console.log('  pp0: finish=' + h.pp0_finish + ' fig=' + h.pp0_speedFig + ' bl2nd=' + h.pp0_bl2nd + ' blFin=' + h.pp0_blFinish + ' cond=' + h.pp0_cond + ' surf=' + h.pp0_surface);
  console.log('  algo: nFig=' + h.nFig + ' nBest=' + h.nBest + ' nForm=' + h.nForm + ' nClose=' + h.nClose + ' nLatePace=' + h.nLatePace + ' nML=' + h.nML);
});
