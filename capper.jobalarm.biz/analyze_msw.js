const fs = require('fs');
const results = JSON.parse(fs.readFileSync('C:/Users/rstre/Projects/Trifecta/data/results.json', 'utf8'));

function parseDRF(filepath) {
  const raw = fs.readFileSync(filepath);
  const text = raw.toString('latin1');
  const lines = text.split('\n').filter(l => l.trim());
  return lines.map(line => {
    const cols = [];
    let inQuote = false, cur = '';
    for (let i = 0; i < line.length; i++) {
      const c = line[i];
      if (c === '"') { inQuote = !inQuote; }
      else if (c === ',' && !inQuote) { cols.push(cur.trim()); cur = ''; }
      else cur += c;
    }
    cols.push(cur.trim());
    return cols;
  });
}

function cleanNum(v) {
  v = (v || '').replace(/"/g, '').trim();
  return v === '' || v === 'N/A' ? null : parseFloat(v);
}
function cleanStr(v) { return (v || '').replace(/"/g, '').trim(); }

const drfFiles = ['CDX0428.DRF','CDX0429.DRF','CDX0430.DRF','MNR0428.DRF','TDN0428.DRF','TDN0429.DRF','TUP0428.DRF'];
let allRows = [];
drfFiles.forEach(f => {
  allRows = allRows.concat(parseDRF('C:/Users/rstre/Projects/Trifecta/data/' + f));
});

const msw = allRows.filter(r => {
  const cls = cleanStr(r[10]);
  return cls.includes('Md Sp Wt') || cls.includes('MSW') || cls.includes('Maiden Special');
});

const winnerMap = {};
Object.keys(results).forEach(key => {
  const card = results[key];
  if (!card.races) return;
  Object.keys(card.races).forEach(rn => {
    const race = card.races[rn];
    if (!race.finishers || !race.finishers[0]) return;
    winnerMap[key + '_R' + rn] = {
      winner: race.finishers[0].name.toUpperCase(),
      finishers: race.finishers.map(f => f.name.toUpperCase())
    };
  });
});

function getResultKey(track, date, raceNum) {
  return track.trim() + '_' + date + '_R' + raceNum;
}

const raceSurface = {
  'CD_20260428_R2': 'D',
  'CD_20260429_R3': 'D',
  'CD_20260430_R7': 'T',
  'TDN_20260428_R4': 'D',
  'TDN_20260428_R7': 'D',
  'TDN_20260429_R3': 'D',
  'TDN_20260429_R6': 'D'
};

const horses = msw.map(r => {
  const name = cleanStr(r[44]);
  const track = cleanStr(r[0]);
  const date = cleanStr(r[1]);
  const raceNum = parseInt(r[2]);
  const post = cleanNum(r[42]);
  const ml = cleanNum(r[43]);
  const jockey = cleanStr(r[32]);
  const trainer = cleanStr(r[27]);

  const pps = [];
  for (let i = 0; i < 10; i++) {
    const ppDate = cleanStr(r[255 + i]);
    if (!ppDate) continue;
    pps.push({
      date: ppDate,
      condition: cleanStr(r[305 + i]),
      surface: cleanStr(r[325 + i]),
      dist: cleanNum(r[315 + i]),
      finish: cleanNum(r[355 + i]),
      speedFig: cleanNum(r[765 + i]),
      bl2nd: cleanNum(r[675 + i]),
      blFinish: cleanNum(r[735 + i])
    });
  }

  const resKey = getResultKey(track, date, raceNum);
  const raceResult = winnerMap[resKey] || null;
  const won = raceResult ? raceResult.winner === name : null;

  return { name, track, date, raceNum, post, ml, jockey, trainer, pps, won, resKey };
});

console.log('Total MSW entrants:', horses.length);
console.log('With PP history:', horses.filter(h => h.pps.length > 0).length);
console.log('Debuts:', horses.filter(h => h.pps.length === 0).length);
console.log('Results available:', horses.filter(h => h.won !== null).length);
console.log('Winners identified:', horses.filter(h => h.won === true).length);

// ANGLE 1: Closing Move
console.log('\n========== ANGLE 1: CLOSING MOVE (3+ lengths gained in stretch) ==========');
const angle1 = horses.filter(h => {
  if (!h.pps.length) return false;
  const pp = h.pps[0];
  if (pp.bl2nd === null || pp.blFinish === null) return false;
  const move = pp.bl2nd - pp.blFinish;
  return move >= 3;
});
const a1Win = angle1.filter(h => h.won === true);
const a1nonWin = horses.filter(h => h.pps.length > 0 && h.won !== null).filter(h => {
  const pp = h.pps[0];
  if (pp.bl2nd === null || pp.blFinish === null) return false;
  return (pp.bl2nd - pp.blFinish) < 3;
});
console.log('Qualifiers: ' + angle1.length + ' | Winners: ' + a1Win.length + ' (' + (a1Win.length/Math.max(angle1.length,1)*100).toFixed(0) + '%)');
console.log('Non-qualifiers with result: ' + a1nonWin.length + ' | Winners from that group: ' + a1nonWin.filter(h=>h.won).length);
const a1AvgML = angle1.reduce((s,h)=>s+(h.ml||0),0)/Math.max(angle1.length,1);
const a1WinML = a1Win.reduce((s,h)=>s+(h.ml||0),0)/Math.max(a1Win.length,1);
console.log('Avg ML qualifiers: ' + a1AvgML.toFixed(2) + ' | Avg ML winners: ' + a1WinML.toFixed(2));
const a1Dirt = angle1.filter(h => raceSurface[h.resKey]==='D');
const a1Turf = angle1.filter(h => raceSurface[h.resKey]==='T');
console.log('By surface - Dirt qualifiers: ' + a1Dirt.length + ' winners: ' + a1Dirt.filter(h=>h.won).length);
console.log('By surface - Turf qualifiers: ' + a1Turf.length + ' winners: ' + a1Turf.filter(h=>h.won).length);
angle1.forEach(h => {
  const pp = h.pps[0];
  const move = (pp.bl2nd - pp.blFinish).toFixed(1);
  console.log('  ' + h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' last: fin' + pp.finish + ' move+' + move + 'L ' + pp.condition + '/' + pp.surface +
    ' | TODAY: ' + (h.won===true?'WON':h.won===false?'did not win':'no result'));
});

// ANGLE 2: Close But No Cigar
console.log('\n========== ANGLE 2: CLOSE BUT NO CIGAR (2nd/3rd within 2L last race) ==========');
const angle2 = horses.filter(h => {
  if (!h.pps.length) return false;
  const pp = h.pps[0];
  if (pp.finish === null || pp.blFinish === null) return false;
  return (pp.finish === 2 || pp.finish === 3) && pp.blFinish >= 0 && pp.blFinish <= 2;
});
const a2Win = angle2.filter(h => h.won === true);
console.log('Qualifiers: ' + angle2.length + ' | Winners: ' + a2Win.length + ' (' + (a2Win.length/Math.max(angle2.length,1)*100).toFixed(0) + '%)');
const a2AvgML = angle2.reduce((s,h)=>s+(h.ml||0),0)/Math.max(angle2.length,1);
const a2WinML = a2Win.reduce((s,h)=>s+(h.ml||0),0)/Math.max(a2Win.length,1);
console.log('Avg ML qualifiers: ' + a2AvgML.toFixed(2) + ' | Avg ML winners: ' + a2WinML.toFixed(2));
const a2Dirt = angle2.filter(h => raceSurface[h.resKey]==='D');
const a2Turf = angle2.filter(h => raceSurface[h.resKey]==='T');
console.log('Dirt: ' + a2Dirt.length + ' qualifiers, ' + a2Dirt.filter(h=>h.won).length + ' won');
console.log('Turf: ' + a2Turf.length + ' qualifiers, ' + a2Turf.filter(h=>h.won).length + ' won');
angle2.forEach(h => {
  const pp = h.pps[0];
  console.log('  ' + h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' last: ' + pp.finish + 'nd/rd by ' + pp.blFinish + 'L (' + pp.condition + ')' +
    ' | TODAY: ' + (h.won===true?'WON':h.won===false?'did not win':'no result'));
});

// ANGLE 3: Figure Spike
console.log('\n========== ANGLE 3: FIGURE SPIKE (last race career best fig) ==========');
const angle3 = horses.filter(h => {
  if (h.pps.length < 2) return false;
  const figs = h.pps.map(p => p.speedFig).filter(f => f !== null);
  if (figs.length < 2) return false;
  return figs[0] > Math.max(...figs.slice(1));
});
const a3Win = angle3.filter(h => h.won === true);
console.log('Qualifiers: ' + angle3.length + ' | Winners: ' + a3Win.length + ' (' + (a3Win.length/Math.max(angle3.length,1)*100).toFixed(0) + '%)');
const a3AvgML = angle3.reduce((s,h)=>s+(h.ml||0),0)/Math.max(angle3.length,1);
const a3WinML = a3Win.reduce((s,h)=>s+(h.ml||0),0)/Math.max(a3Win.length,1);
console.log('Avg ML qualifiers: ' + a3AvgML.toFixed(2) + ' | Avg ML winners: ' + a3WinML.toFixed(2));
const a3Dirt = angle3.filter(h => raceSurface[h.resKey]==='D');
const a3Turf = angle3.filter(h => raceSurface[h.resKey]==='T');
console.log('Dirt: ' + a3Dirt.length + ' qual, ' + a3Dirt.filter(h=>h.won).length + ' won');
console.log('Turf: ' + a3Turf.length + ' qual, ' + a3Turf.filter(h=>h.won).length + ' won');
angle3.forEach(h => {
  const figs = h.pps.map(p => p.speedFig).filter(f => f !== null);
  console.log('  ' + h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' figs:[' + figs.join(',') + '] spike:' + figs[0] + '>prev best ' + Math.max(...figs.slice(1)) +
    ' | TODAY: ' + (h.won===true?'WON':h.won===false?'did not win':'no result'));
});

// ANGLE 4: Surface Switch Affinity
console.log('\n========== ANGLE 4: SURFACE SWITCH TO AFFINITY ==========');
const angle4 = horses.filter(h => {
  if (!h.pps.length) return false;
  const todaySurf = raceSurface[h.resKey] || null;
  if (!todaySurf) return false;
  const lastSurf = (h.pps[0].surface || '').charAt(0).toUpperCase();
  if (lastSurf === todaySurf) return false;
  return h.pps.some(pp => {
    const surf = (pp.surface || '').charAt(0).toUpperCase();
    return surf === todaySurf && pp.finish !== null && pp.finish <= 3;
  });
});
const a4Win = angle4.filter(h => h.won === true);
console.log('Qualifiers: ' + angle4.length + ' | Winners: ' + a4Win.length + ' (' + (a4Win.length/Math.max(angle4.length,1)*100).toFixed(0) + '%)');
const a4AvgML = angle4.reduce((s,h)=>s+(h.ml||0),0)/Math.max(angle4.length,1);
const a4WinML = a4Win.reduce((s,h)=>s+(h.ml||0),0)/Math.max(a4Win.length,1);
console.log('Avg ML qualifiers: ' + a4AvgML.toFixed(2) + ' | Avg ML winners: ' + a4WinML.toFixed(2));
angle4.forEach(h => {
  const todaySurf = raceSurface[h.resKey];
  const lastSurf = (h.pps[0].surface || '').charAt(0).toUpperCase();
  const goodRaces = h.pps.filter(pp => (pp.surface||'').charAt(0).toUpperCase()===todaySurf && pp.finish <= 3);
  console.log('  ' + h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' today:' + todaySurf + ' last:' + lastSurf + ' good on ' + todaySurf + ': [' + goodRaces.map(p=>p.finish+'('+p.date+')').join(',') + ']' +
    ' | TODAY: ' + (h.won===true?'WON':h.won===false?'did not win':'no result'));
});

// ANGLE 5: Elite Connections Debut
console.log('\n========== ANGLE 5: ELITE CONNECTIONS DEBUT ==========');
const eliteTrainers = ['ASMUSSEN','PLETCHER','WARD','BROWN','CASSE','LYNCH','WALSH','BAFFERT','MOTION','MOTT','MCGAUGHEY','STALL','DEVAUX','SHARP','DAMATO'];
const eliteJockeys = ['VELAZQUEZ','ORTIZ','GAFFALIONE','PRAT','HERNANDEZ B J','HERNANDEZ JUAN','SAEZ','ROSARIO','GEROUX'];

const debutHorses = horses.filter(h => h.pps.length === 0);
console.log('Total debuts: ' + debutHorses.length);

const angle5 = debutHorses.filter(h => {
  const trainerElite = eliteTrainers.some(t => h.trainer.toUpperCase().includes(t));
  const jockeyElite = eliteJockeys.some(j => h.jockey.toUpperCase().includes(j));
  return trainerElite || jockeyElite;
});
const a5Win = angle5.filter(h => h.won === true);
console.log('Elite connection debuts: ' + angle5.length + ' | Winners: ' + a5Win.length + ' (' + (a5Win.length/Math.max(angle5.length,1)*100).toFixed(0) + '%)');
const nonEliteDebut = debutHorses.filter(h => !angle5.includes(h));
const nonEliteWin = nonEliteDebut.filter(h => h.won === true);
console.log('Non-elite debuts: ' + nonEliteDebut.length + ' | Winners: ' + nonEliteWin.length + ' (' + (nonEliteWin.length/Math.max(nonEliteDebut.length,1)*100).toFixed(0) + '%)');
const a5AvgML = angle5.reduce((s,h)=>s+(h.ml||0),0)/Math.max(angle5.length,1);
const a5WinML = a5Win.reduce((s,h)=>s+(h.ml||0),0)/Math.max(a5Win.length,1);
console.log('Avg ML elite debuts: ' + a5AvgML.toFixed(2) + ' | Avg ML elite winners: ' + a5WinML.toFixed(2));
angle5.forEach(h => {
  const trainerElite = eliteTrainers.some(t => h.trainer.toUpperCase().includes(t));
  const jockeyElite = eliteJockeys.some(j => h.jockey.toUpperCase().includes(j));
  console.log('  ' + h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' T:' + h.trainer + (trainerElite?' [E-T]':'') + ' J:' + h.jockey + (jockeyElite?' [E-J]':'') +
    ' | TODAY: ' + (h.won===true?'WON':h.won===false?'did not win':'no result'));
});

// Winner Retrospective
console.log('\n========== MSW WINNER RETROSPECTIVE ==========');
const winners = horses.filter(h => h.won === true);
winners.forEach(h => {
  const isDebut = h.pps.length === 0;
  if (isDebut) {
    console.log(h.name + ' ML:' + h.ml + ' | DEBUT | T:' + h.trainer + ' J:' + h.jockey);
    return;
  }
  const pp = h.pps[0];
  const figs = h.pps.map(p => p.speedFig).filter(f => f !== null);
  const move = (pp.bl2nd !== null && pp.blFinish !== null) ? (pp.bl2nd - pp.blFinish) : null;
  const figSpike = figs.length >= 2 && figs[0] > Math.max(...figs.slice(1));
  const frontRan = pp.bl2nd !== null && pp.bl2nd <= 1;
  const closed = move !== null && move >= 3;
  const closeNoC = (pp.finish===2||pp.finish===3) && pp.blFinish!==null && pp.blFinish<=2;
  const patterns = [];
  if (frontRan) patterns.push('FRONT-RAN (bl2nd<=1)');
  if (closed) patterns.push('CLOSED (+' + move.toFixed(1) + 'L)');
  if (closeNoC) patterns.push('CLOSE-NO-CIGAR ('+pp.finish+'nd/rd by '+pp.blFinish+'L)');
  if (figSpike) patterns.push('FIG-SPIKE ('+figs[0]+'>'+Math.max(...figs.slice(1))+')');
  if (!patterns.length) patterns.push('EVEN/other (fin:'+pp.finish+' move:'+(move!==null?move.toFixed(1):'N/A')+')');
  const todaySurf = raceSurface[h.resKey];
  const lastSurf = (pp.surface||'').charAt(0).toUpperCase();
  const surfSwitch = lastSurf !== todaySurf ? ' SURF-SWITCH('+lastSurf+'->'+todaySurf+')' : '';
  console.log(h.name + ' (' + h.track + ' R' + h.raceNum + ') ML:' + h.ml +
    ' | ' + patterns.join(' + ') + surfSwitch +
    ' | fig:' + pp.speedFig + ' | T:' + h.trainer + ' J:' + h.jockey);
});

// Summary stats
console.log('\n========== SUMMARY ==========');
const totalWithResult = horses.filter(h => h.won !== null).length;
const totalWon = horses.filter(h => h.won === true).length;
console.log('Total MSW horses with results: ' + totalWithResult);
console.log('Baseline win rate: 1/' + (totalWithResult/totalWon).toFixed(1) + ' = ' + (totalWon/totalWithResult*100).toFixed(0) + '%');

// Combined angle: Closing Move + Figure Spike
const combo = horses.filter(h => angle1.includes(h) && angle3.includes(h));
const comboWin = combo.filter(h => h.won);
console.log('\nCombo (Closing Move + Fig Spike): ' + combo.length + ' | Winners: ' + comboWin.length);
combo.forEach(h => {
  console.log('  ' + h.name + ' ML:' + h.ml + ' | ' + (h.won?'WON':'no win'));
});
