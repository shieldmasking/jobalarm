const fs = require('fs');
const DATA = 'C:/Users/rstre/Projects/Trifecta/data';

function parseCSV(text) {
  const rows = [];
  let inQ = false, field = '', row = [];
  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (c === '"') { if (inQ && text[i+1]==='"'){field+='"';i++;}else inQ=!inQ; }
    else if (c===',' && !inQ){row.push(field);field='';}
    else if ((c==='\n'||c==='\r')&&!inQ){row.push(field);if(row.some(f=>f.trim()))rows.push(row);row=[];field='';if(c==='\r'&&text[i+1]==='\n')i++;}
    else field+=c;
  }
  if(row.length){row.push(field);if(row.some(f=>f.trim()))rows.push(row);}
  return rows;
}

const results = JSON.parse(fs.readFileSync(DATA+'/results.json','utf8'));

const misses = [
  {key:'MNR_20260428', race:'1', winner:'Revocata'},
  {key:'MNR_20260428', race:'2', winner:'Pace'},
  {key:'MNR_20260428', race:'3', winner:'Fernando\'s Gold'},
  {key:'TDN_20260428', race:'4', winner:'Boaz'},
  {key:'TDN_20260429', race:'1', winner:'Jasper Maximus'},
  {key:'TDN_20260429', race:'8', winner:'Elliot the Dragon'},
  {key:'TUP_20260428', race:'5', winner:'Chelsiesdanziglite'},
];

const drfFiles = fs.readdirSync(DATA).filter(f=>f.endsWith('.DRF'));

for (const m of misses) {
  const [trackCode, dateRaw] = m.key.split('_');
  let winnerRow = null;
  let allRows = [];

  for (const file of drfFiles) {
    const text = fs.readFileSync(DATA+'/'+file, 'latin1');
    const rows = parseCSV(text);
    if (!rows.length) continue;
    if (rows[0][0].trim()!==trackCode || rows[0][1].trim()!==dateRaw) continue;
    for (const row of rows) {
      if (row.length < 50) continue;
      const rNum = parseInt(row[2]);
      if (String(rNum) !== m.race) continue;
      allRows.push(row);
      const name = (row[44]||'').trim().toUpperCase();
      if (name === m.winner.toUpperCase()) winnerRow = row;
    }
  }

  if (!winnerRow) { console.log(m.key+' R'+m.race+' - winner row not found'); continue; }

  const raceResult = results[m.key].races[m.race];
  const preds = raceResult.predictions||[];
  const ourRank = preds.findIndex(p=>p.name.toUpperCase()===m.winner.toUpperCase());
  const ourPred = preds[ourRank];

  const ml = parseFloat(winnerRow[43])||0;
  const daysSince = parseInt(winnerRow[223])||0;
  const primePower = parseFloat(winnerRow[250])||0;
  const runStyle = (winnerRow[209]||'').trim();
  const jockeyRoi = parseFloat(winnerRow[1160])||0;
  const trainerRoi = parseFloat(winnerRow[1150])||0;
  const tjStarts = parseInt(winnerRow[218])||0;
  const tjWins = parseInt(winnerRow[219])||0;

  const ppFinish = Array.from({length:3},(_,i)=>parseInt(winnerRow[355+i])||null);
  const ppSpeedFig = Array.from({length:3},(_,i)=>parseFloat(winnerRow[765+i])||null);
  const ppLatePace = Array.from({length:3},(_,i)=>parseFloat(winnerRow[815+i])||null);
  const pp2ndBL = Array.from({length:3},(_,i)=>parseFloat(winnerRow[675+i])||null);

  const ourTop = preds[0];
  const ourTopRow = allRows.find(r=>(r[44]||'').trim().toUpperCase()===ourTop.name.toUpperCase());
  const ourTopML = ourTopRow ? parseFloat(ourTopRow[43])||0 : '?';

  console.log('\n=== '+m.key+' R'+m.race+' ===');
  console.log('Winner: '+m.winner+' (our rank: '+(ourRank+1)+') ML:'+ml);
  console.log('  days_since:'+daysSince+' prime_power:'+primePower+' run_style:'+runStyle);
  console.log('  jockey_roi:'+jockeyRoi+' trainer_roi:'+trainerRoi+' tj:'+tjWins+'/'+tjStarts);
  console.log('  last 3 finishes:', ppFinish);
  console.log('  last 3 speed figs:', ppSpeedFig);
  console.log('  last 3 late pace:', ppLatePace);
  console.log('  last 3 2nd call BL:', pp2ndBL);
  if (ourPred) console.log('  our scores -> nFig:'+ourPred.nFig+' nBest:'+ourPred.nBest+' nForm:'+ourPred.nForm+' nClose:'+ourPred.nClose+' nLatePace:'+ourPred.nLatePace+' nML:'+ourPred.nML);
  console.log('Our #1: '+ourTop.name+' ML:'+ourTopML);
  console.log('  our scores -> nFig:'+preds[0].nFig+' nBest:'+preds[0].nBest+' nForm:'+preds[0].nForm+' nClose:'+preds[0].nClose+' nLatePace:'+preds[0].nLatePace+' nML:'+preds[0].nML);
}
