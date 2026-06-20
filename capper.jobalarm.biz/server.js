#!/usr/bin/env node
'use strict';

const http  = require('http');
const https = require('https');
const fs    = require('fs');
const path  = require('path');
const { execSync, exec } = require('child_process');

const db = require('./db');

// Claude API key from environment
const CLAUDE_API_KEY = process.env.CLAUDE_API_KEY || '';
const CLAUDE_MODEL   = 'claude-sonnet-4-6';

const DATA_DIR    = path.join(__dirname, 'data');
const PORT        = 3000;
const TARGET_DATE = ''; // set to 'YYYYMMDD' to pin a specific date; '' = use today or ?date= param

// ── TRACK NAMES ──────────────────────────────────────────────────────────────
const TRACK_NAMES = {
  'CD':'Churchill Downs','CDX':'Churchill Downs',
  'SA':'Santa Anita','SAX':'Santa Anita',
  'GP':'Gulfstream Park','GPX':'Gulfstream Park',
  'OP':'Oaklawn Park','OPX':'Oaklawn Park',
  'AQU':'Aqueduct','KEE':'Keeneland','BEL':'Belmont Park',
  'DMR':'Del Mar','WO':'Woodbine',
  'TAM':'Tampa Bay Downs','LRL':'Laurel Park','MVR':'Mahoning Valley',
  'EVD':'Evangeline Downs','FG':'Fair Grounds','HAW':'Hawthorne',
  'TUP':'Turf Paradise','SUN':'Sunland Park','GG':'Golden Gate Fields',
  'PIM':'Pimlico','PRX':'Parx Racing','MTH':'Monmouth Park',
  'SAR':'Saratoga','YNK':'Yonkers','IND':'Indiana Grand',
  'TDN':'Thistledown','CBY':'Canterbury Park','RUI':'Ruidoso Downs',
  'LAD':'Louisiana Downs','AP':'Arlington Park','BTP':'Belterra Park',
  'DED':'Delta Downs',
};

function getTrackName(code) {
  return TRACK_NAMES[code.trim()] || code.trim();
}

// ── DATE ─────────────────────────────────────────────────────────────────────
function todayYMD() {
  const d = new Date();
  return d.getFullYear().toString()
    + String(d.getMonth() + 1).padStart(2, '0')
    + String(d.getDate()).padStart(2, '0');
}

function formatDate(yyyymmdd) {
  const MONTHS = ['','January','February','March','April','May','June',
    'July','August','September','October','November','December'];
  const y = yyyymmdd.slice(0, 4);
  const m = +yyyymmdd.slice(4, 6);
  const d = +yyyymmdd.slice(6, 8);
  return `${MONTHS[m]} ${d}, ${y}`;
}

// ── CSV PARSER ────────────────────────────────────────────────────────────────
function parseCSV(text) {
  const rows = [];
  let inQ = false, field = '', row = [];

  for (let i = 0; i < text.length; i++) {
    const c = text[i];
    if (c === '"') {
      // Escaped quote inside a quoted field: ""
      if (inQ && text[i + 1] === '"') { field += '"'; i++; }
      else inQ = !inQ;
    } else if (c === ',' && !inQ) {
      row.push(field); field = '';
    } else if ((c === '\n' || c === '\r') && !inQ) {
      row.push(field);
      if (row.some(f => f.trim())) rows.push(row);
      row = []; field = '';
      if (c === '\r' && text[i + 1] === '\n') i++;
    } else {
      field += c;
    }
  }
  if (row.length) { row.push(field); if (row.some(f => f.trim())) rows.push(row); }
  return rows;
}

// ── DRF PARSER ────────────────────────────────────────────────────────────────
const SURF_MAP = { D: 'Dirt', T: 'Turf', A: 'All Weather' };

function parseDRF(filepath) {
  // DRF files are commonly latin-1 encoded
  const text = fs.readFileSync(filepath, 'latin1');
  const rows = parseCSV(text);
  if (!rows.length) return null;

  const trackCode = (rows[0][0] || '').trim();
  const dateRaw   = (rows[0][1] || '').trim();
  if (!trackCode || !dateRaw) return null;

  const raceYear = parseInt(dateRaw.slice(0, 4)) || new Date().getFullYear();

  // Group entries by race number
  const raceMap = new Map();
  for (const row of rows) {
    if (row.length < 50) continue;
    const rNum = parseInt(row[2]);
    if (isNaN(rNum) || rNum < 1 || rNum > 30) continue;
    if (!raceMap.has(rNum)) raceMap.set(rNum, []);
    raceMap.get(rNum).push(row);
  }

  const races = [...raceMap.entries()]
    .sort(([a], [b]) => a - b)
    .map(([rNum, entries]) => {
      const first = entries[0];
      const distY = parseInt(first[5]) || 0;
      const distF = Math.round((distY / 220) * 10) / 10;

      const horses = entries
        .map(e => {
          // Helper: extract a block of N consecutive cols, strip empty/blank
          const block = (start, len) =>
            Array.from({ length: len }, (_, i) => (e[start + i] || '').trim())
                 .map(v => v === '' ? null : v);

          // Past performance: 10 most recent races
          const ppDates    = block(255, 10);
          const ppTracks   = block(275, 10);
          const ppSurface  = block(325, 10);
          const ppCond     = block(305, 10);
          const ppClass    = block(535, 10);
          const ppFinish   = block(355, 10).map(v => v ? parseInt(v) || null : null);
          const ppSpeedFig = block(765, 10).map(v => v ? parseFloat(v) || null : null);
          const ppLatePace = block(815, 10).map(v => v ? parseFloat(v) || null : null);
          const ppJockeys  = block(1065, 10);
          // Beaten lengths: 2nd call and finish (positive = leading, negative = behind)
          const pp2ndCallBL = block(675, 10).map(v => v ? parseFloat(v) || null : null);
          const ppFinishBL  = block(735, 10).map(v => v ? parseFloat(v) || null : null);
          const ppDistYards = block(315, 10).map(v => v ? parseInt(v) || null : null);

          // Build structured PP array (only races with a date)
          const pp = ppDates
            .map((date, i) => !date ? null : {
              date:     date,
              track:    ppTracks[i],
              surface:  ppSurface[i],
              cond:     ppCond[i],
              cls:      ppClass[i],
              finish:   ppFinish[i],
              speedFig: ppSpeedFig[i],
              latePace: ppLatePace[i],
              jockey:   ppJockeys[i],
              bl2nd:     pp2ndCallBL[i],   // beaten lengths at 2nd call
              blFinish:  ppFinishBL[i],   // beaten lengths at finish
              distYards: ppDistYards[i],  // race distance in yards
            })
            .filter(Boolean);

          return {
            post:         parseInt(e[42]) || 0,
            name:         (e[44] || '').trim(),
            age:          parseInt(e[45]) || 0,
            actual_age:   raceYear - (2000 + (parseInt(e[45]) || 0)),
            sex:          (e[48] || '').trim(),
            color:        (e[49] || '').trim(),
            weight:       parseInt(e[50]) || 0,
            sire:         (e[51] || '').trim(),
            dam:          (e[53] || '').trim(),
            jockey:        (e[32] || '').trim(),
            trainer:       (e[27] || '').trim(),   // field 28
            owner:         (e[38] || '').trim(),   // field 39
            morning_line:  parseFloat(e[43]) || 0,
            silks:         (e[39] || '').trim(),
            days_since:    parseInt(e[223])  || 0, // days since last race (0 = first-timer)
            prime_power:   parseFloat(e[250])|| 0, // BRIS Prime Power Rating
            run_style:     (e[209] || '').trim(),   // E/P/S/C (early/presser/sustained/closer)
            tj_starts:     parseInt(e[218])  || 0, // T/J combo starts (365d)
            tj_wins:       parseInt(e[219])  || 0, // T/J combo wins (365d)
            tj_roi:        parseFloat(e[222])|| 0, // T/J combo net profit per $2
            trainer_starts:parseInt(e[1146]) || 0, // trainer starts current year
            trainer_roi:   parseFloat(e[1150])|| 0,// trainer net profit per $2
            jockey_starts: parseInt(e[1156]) || 0, // jockey starts current year
            jockey_roi:    parseFloat(e[1160])|| 0,// jockey net profit per $2
            pp,
            workouts: Array.from({ length: 12 }, (_, i) => {
              const date = (e[102 + i] || '').trim();
              if (!date || date.length < 8) return null;
              const time  = parseFloat(e[114 + i]) || 0;  // negative = bullet
              const dist  = parseInt(e[138 + i])   || 0;  // yards
              const rank  = parseInt(e[198 + i])   || 0;
              const count = parseInt(e[186 + i])   || 0;
              const cond  = (e[149 + i] || '').trim(); // track condition: ft/gd/my/wf/sy/sl
              return { date, time, dist, rank, count, bullet: time < 0, cond };
            }).filter(Boolean),
          };
        })
        .filter(h => h.post > 0 && h.name)
        .sort((a, b) => a.post - b.post);

      return {
        number:            rNum,
        dateRaw,
        distance_yards:    distY,
        distance_furlongs: distF,
        surface:           SURF_MAP[first[6]?.trim()] || (first[6] || '').trim(),
        race_class:        (first[10] || '').trim(),
        purse:             parseInt(first[11]) || 0,
        description:       (first[15] || '').trim(),
        horses,
      };
    });

  return {
    trackCode,
    track:    getTrackName(trackCode),
    dateRaw,
    date:     dateRaw.length === 8 ? formatDate(dateRaw) : dateRaw,
    races,
  };
}

// ── HRN RESULTS SCRAPER ───────────────────────────────────────────────────────
const HRN_SLUGS = {
  'CD':'churchill-downs','CDX':'churchill-downs',
  'SA':'santa-anita','SAX':'santa-anita',
  'GP':'gulfstream-park','GPX':'gulfstream-park',
  'OP':'oaklawn-park','OPX':'oaklawn-park',
  'KEE':'keeneland','SAR':'saratoga',
  'BEL':'belmont-park','AQU':'aqueduct','DMR':'del-mar',
  'TAM':'tampa-bay-downs','LRL':'laurel-park','FG':'fair-grounds',
  'HAW':'hawthorne','PIM':'pimlico','PRX':'parx-racing','MTH':'monmouth-park',
  'WO':'woodbine','MVR':'mahoning-valley','IND':'indiana-grand','CBY':'canterbury-park',
  'TDN':'thistledown','EVD':'evangeline-downs','LAD':'louisiana-downs',
  'MNR':'mountaineer','TUP':'turf-paradise','DED':'delta-downs',
};

function httpsGet(url) {
  return new Promise((resolve, reject) => {
    try {
      const u = new URL(url);
      https.get({
        hostname: u.hostname, path: u.pathname + u.search,
        headers: {
          'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
          'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
          'Accept-Language': 'en-US,en;q=0.5',
        },
      }, res => {
        if (res.statusCode === 301 || res.statusCode === 302)
          return resolve(httpsGet(res.headers.location));
        let data = '';
        res.on('data', c => data += c);
        res.on('end', () => resolve({ status: res.statusCode, body: data }));
      }).on('error', reject);
    } catch (e) { reject(e); }
  });
}

function stripTags(html) {
  return html.replace(/<[^>]+>/g,' ').replace(/&amp;/g,'&').replace(/&lt;/g,'<')
             .replace(/&gt;/g,'>').replace(/&#39;/g,"'").replace(/&nbsp;/g,' ')
             .replace(/\s+/g,' ').trim();
}

// Extract rows from the first table whose class contains `classFragment`
function parseTable(html, classFragment) {
  const idx = html.indexOf(classFragment);
  if (idx === -1) return [];
  const tableStart = html.lastIndexOf('<table', idx);
  if (tableStart === -1) return [];
  const tableEnd = html.indexOf('</table>', tableStart);
  if (tableEnd === -1) return [];
  const tableHtml = html.slice(tableStart, tableEnd);

  const rows = [];
  let pos = tableHtml.indexOf('<tbody>');
  if (pos === -1) pos = 0;
  while (true) {
    const trStart = tableHtml.indexOf('<tr', pos);
    if (trStart === -1) break;
    const trEnd = tableHtml.indexOf('</tr>', trStart);
    if (trEnd === -1) break;
    const trHtml = tableHtml.slice(trStart, trEnd);
    const cells = [];
    let tdPos = 0;
    while (true) {
      const tdStart = trHtml.indexOf('<td', tdPos);
      if (tdStart === -1) break;
      const tdClose = trHtml.indexOf('>', tdStart);
      if (tdClose === -1) break;
      const tdEnd = trHtml.indexOf('</td>', tdClose);
      if (tdEnd === -1) break;
      const cellHtml = trHtml.slice(tdClose + 1, tdEnd);
      const imgAlt = cellHtml.match(/alt="(\d+)"/)?.[1];
      cells.push({ text: stripTags(cellHtml), imgAlt });
      tdPos = tdEnd + 5;
    }
    if (cells.length > 0) rows.push(cells);
    pos = trEnd + 5;
  }
  return rows;
}

function extractDivContent(html, className) {
  const marker = `class="mb-3 ${className}"`;
  let idx = html.indexOf(marker);
  if (idx === -1) { idx = html.indexOf(`class="${className}"`); }
  if (idx === -1) return '';
  const start = html.lastIndexOf('<div', idx);
  if (start === -1) return '';
  const end = html.indexOf('</div>', start);
  return end === -1 ? '' : stripTags(html.slice(start, end));
}

function parseHRNHtml(html, trackCode, dateYMD) {
  const races = {};
  const parts = html.split(/<a\s+class="race-header"\s+id="race-(\d+)"/);
  for (let i = 1; i < parts.length; i += 2) {
    const raceNum = parseInt(parts[i]);
    const block = parts[i + 1] || '';

    // Top-4 finishers from payouts table
    const finishers = [];
    for (const cells of parseTable(block, 'table-payouts')) {
      if (cells.length < 3 || !cells[1].imgAlt) continue; // skip header/footer
      const nameCell = cells[0].text;
      const nameMatch = nameCell.match(/^(.+?)\s*\((\d+)\*?\)\s*$/);
      const name = nameMatch ? nameMatch[1].trim() : nameCell.trim();
      const speedFig = nameMatch ? parseInt(nameMatch[2]) : null;
      const parsePayout = s => { const m = s?.match(/\$([\d,]+\.?\d*)/); return m ? parseFloat(m[1].replace(',','')) : null; };
      finishers.push({
        pos: finishers.length + 1,
        name, pp: parseInt(cells[1].imgAlt), speedFig,
        win:   parsePayout(cells[2]?.text),
        place: parsePayout(cells[3]?.text),
        show:  parsePayout(cells[4]?.text),
      });
    }

    // Also-rans
    const alsoRansRaw = extractDivContent(block, 'race-also-rans').replace(/^Also rans?:\s*/i,'');
    const alsoRans = alsoRansRaw ? alsoRansRaw.split(',').map(s=>s.trim()).filter(Boolean) : [];

    // Exotic payouts — extract base bet from column header (e.g. "$2 Payout", "$1 Payout")
    const exotics = {};
    let payoutBase = 2; // default
    const exoticTableIdx = block.indexOf('table-exotic-payouts');
    if (exoticTableIdx !== -1) {
      const tableStart = block.lastIndexOf('<table', exoticTableIdx);
      const theadEnd = block.indexOf('</thead>', tableStart);
      if (tableStart !== -1 && theadEnd !== -1) {
        const thead = block.slice(tableStart, theadEnd);
        const baseM = stripTags(thead).match(/\$(\d+(?:\.\d+)?)\s*Payout/i);
        if (baseM) payoutBase = parseFloat(baseM[1]);
      }
    }
    for (const cells of parseTable(block, 'table-exotic-payouts')) {
      if (cells.length < 3) continue;
      const pool = cells[0].text.trim();
      const m = cells[2].text.match(/\$([\d,]+\.?\d*)/);
      if (m) exotics[pool] = { finish: cells[1].text.trim(), payout: parseFloat(m[1].replace(',','')) };
    }

    // Fractions & final time
    const fractions = extractDivContent(block, 'race-fractions').replace(/^Fractions and final time:\s*/i,'').trim();
    const fracParts = fractions.split(',').map(s=>s.trim()).filter(Boolean);
    const finalTime = fracParts[fracParts.length - 1] || null;

    if (finishers.length > 0) races[raceNum] = { raceNum, finishers, alsoRans, exotics, payoutBase, fractions, finalTime };
  }
  return { trackCode, dateYMD, fetched: new Date().toISOString(), races };
}

const RESULTS_FILE = path.join(DATA_DIR, 'results.json');
function loadResultsDb() { try { return JSON.parse(fs.readFileSync(RESULTS_FILE,'utf8')); } catch { return {}; } }
function saveResultsDb(db) { try { fs.writeFileSync(RESULTS_FILE, JSON.stringify(db, null, 2)); } catch (e) { console.error('Results save error:', e.message); } }

async function fetchAndCacheResults(trackCode, dateYMD, force) {
  const db = loadResultsDb();
  const key = `${trackCode}_${dateYMD}`;
  if (!force && db[key]?.races && Object.keys(db[key].races).length > 0) return db[key];

  const slug = HRN_SLUGS[trackCode] || trackCode.toLowerCase().replace(/\s+/g,'-');
  const d = dateYMD;
  const url = `https://entries.horseracingnation.com/entries-results/${slug}/${d.slice(0,4)}-${d.slice(4,6)}-${d.slice(6,8)}`;
  console.log(`  Fetching results: ${url}`);
  const { status, body } = await httpsGet(url);
  if (status !== 200) throw new Error(`HRN returned ${status}`);

  const results = parseHRNHtml(body, trackCode, dateYMD);
  if (!Object.keys(results.races).length) throw new Error('No results found — races may not have run yet');

  db[key] = results;
  saveResultsDb(db);
  console.log(`  Cached: ${trackCode} ${dateYMD} — ${Object.keys(results.races).length} races`);
  return results;
}

// ── PARSE CACHE ───────────────────────────────────────────────────────────────
// Keyed by filepath; invalidated when file mtime changes.
const parseCache = new Map();

function parseDRFCached(filepath) {
  const mtime = fs.statSync(filepath).mtimeMs;
  const cached = parseCache.get(filepath);
  if (cached && cached.mtime === mtime) return cached.data;
  const data = parseDRF(filepath);
  parseCache.set(filepath, { mtime, data });
  return data;
}

// ── ZIP EXTRACTION ────────────────────────────────────────────────────────────
// Runs async in background after server starts listening — does not block startup.
function extractPendingZips() {
  if (!fs.existsSync(DATA_DIR)) return;
  const zips = fs.readdirSync(DATA_DIR).filter(f => /\.zip$/i.test(f));
  for (const zip of zips) {
    const zipPath = path.join(DATA_DIR, zip);
    exec(
      `powershell -Command "Expand-Archive -Force -Path '${zipPath}' -DestinationPath '${DATA_DIR}'"`,
      (err) => {
        if (err) { console.error(`  Zip error: ${zip} — ${err.message}`); return; }
        console.log(`  Extracted: ${zip}`);
        // Bust the parse cache so next request re-reads newly extracted DRFs
        parseCache.clear();
      }
    );
  }
}

// ── REQUEST HANDLER ───────────────────────────────────────────────────────────
const MIME = {
  '.html': 'text/html; charset=utf-8',
  '.js':   'application/javascript',
  '.css':  'text/css',
  '.json': 'application/json',
  '.ico':  'image/x-icon',
};

function serveStatic(res, filePath) {
  // Basic path traversal guard
  const safe = path.join(__dirname, path.normalize(filePath).replace(/^(\.\.(\/|\\|$))+/, ''));
  try {
    const content = fs.readFileSync(safe);
    const ext = path.extname(safe).toLowerCase();
    res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream', 'Cache-Control': 'no-store' });
    res.end(content);
  } catch {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('Not found');
  }
}

// Helper: extract all unique dates from DRF files (fast — reads only first row of each file)
function getAvailableDates() {
  if (!fs.existsSync(DATA_DIR)) return [];
  const files = fs.readdirSync(DATA_DIR).filter(f => /\.drf$/i.test(f));
  const dates = new Set();
  for (const file of files) {
    try {
      // Read just enough to get the date field from the first row
      const fd = fs.openSync(path.join(DATA_DIR, file), 'r');
      const buf = Buffer.alloc(512);
      fs.readSync(fd, buf, 0, 512, 0);
      fs.closeSync(fd);
      const line = buf.toString('latin1').split(/[\r\n]/)[0];
      const parts = line.split(',');
      const dateRaw = (parts[1] || '').trim().replace(/^"/, '').replace(/"$/, '');
      if (/^\d{8}$/.test(dateRaw)) dates.add(dateRaw);
    } catch {}
  }
  return [...dates].sort();
}

function corsHeaders() {
  return {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': 'GET,POST,DELETE,OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type,Authorization',
    'Cache-Control': 'no-store',
  };
}

function deepMerge(target, source) {
  const out = { ...target };
  for (const [k, v] of Object.entries(source)) {
    if (v && typeof v === 'object' && !Array.isArray(v) && typeof target[k] === 'object') {
      out[k] = deepMerge(target[k], v);
    } else {
      out[k] = v;
    }
  }
  return out;
}

process.on('uncaughtException', err => {
  console.error('[uncaughtException]', err.message, err.stack);
});
process.on('unhandledRejection', (reason) => {
  console.error('[unhandledRejection]', reason);
});

const server = http.createServer((req, res) => {
  const url = new URL(req.url, `http://localhost:${PORT}`);
  try {

  // ── GET /api/version — mtime of index.html for change detection ──
  if (url.pathname === '/api/version') {
    const mtime = fs.statSync(path.join(__dirname, 'index.html')).mtimeMs;
    res.writeHead(200, { 'Content-Type': 'application/json', 'Cache-Control': 'no-store', 'Access-Control-Allow-Origin': '*' });
    res.end(JSON.stringify({ mtime }));
    return;
  }

  // ── GET /api/dates — all dates that have DRF data ───────────
  if (url.pathname === '/api/dates') {
    res.writeHead(200, {
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*',
      'Cache-Control': 'no-store',
    });
    res.end(JSON.stringify(getAvailableDates()));
    return;
  }

  // ── GET /api/results/all ────────────────────────────────────
  if (url.pathname === '/api/results/all') {
    res.writeHead(200, { 'Content-Type':'application/json','Access-Control-Allow-Origin':'*','Cache-Control':'no-store' });
    res.end(JSON.stringify(loadResultsDb()));
    return;
  }

  // ── POST /api/predictions ────────────────────────────────────
  if (url.pathname === '/api/predictions' && req.method === 'POST') {
    let body = '';
    req.on('data', c => body += c);
    req.on('end', () => {
      try {
        const { trackCode, date, races } = JSON.parse(body);
        const db = loadResultsDb();
        const key = `${trackCode}_${date}`;
        if (db[key]) {
          for (const [raceNum, picks] of Object.entries(races)) {
            if (db[key].races[raceNum]) db[key].races[raceNum].predictions = picks;
          }
          saveResultsDb(db);
        }
        res.writeHead(200, { 'Content-Type':'application/json','Access-Control-Allow-Origin':'*' });
        res.end(JSON.stringify({ ok: true }));
      } catch (e) {
        res.writeHead(400, { 'Content-Type':'application/json','Access-Control-Allow-Origin':'*' });
        res.end(JSON.stringify({ error: e.message }));
      }
    });
    return;
  }

  // ── GET /api/results ────────────────────────────────────────
  if (url.pathname === '/api/results') {
    const trackCode = url.searchParams.get('trackCode') || 'CD';
    const date      = url.searchParams.get('date') || todayYMD();
    const force     = url.searchParams.get('force') === '1';
    (async () => {
      try {
        const results = await fetchAndCacheResults(trackCode, date, force);
        res.writeHead(200, { 'Content-Type':'application/json','Access-Control-Allow-Origin':'*','Cache-Control':'no-store' });
        res.end(JSON.stringify(results));
      } catch (e) {
        res.writeHead(404, { 'Content-Type':'application/json','Access-Control-Allow-Origin':'*' });
        res.end(JSON.stringify({ error: e.message }));
      }
    })();
    return;
  }

  // ── GET /api/races ──────────────────────────────────────────
  if (url.pathname === '/api/races') {
    // Date priority: ?date= param > TARGET_DATE override > today
    const requestedDate = url.searchParams.get('date');
    const today = requestedDate || TARGET_DATE || todayYMD();

    try {
      if (!fs.existsSync(DATA_DIR)) {
        fs.mkdirSync(DATA_DIR, { recursive: true });
      }

      const files = fs.readdirSync(DATA_DIR).filter(f => /\.drf$/i.test(f)).sort();
      const tracks = [];

      for (const file of files) {
        try {
          const data = parseDRFCached(path.join(DATA_DIR, file));
          if (!data) continue;

          if (data.dateRaw !== today) {
            console.log(`  Skipped: ${file} (date ${data.dateRaw} ≠ ${today})`);
            continue;
          }

          tracks.push({ ...data, filename: file });
          console.log(`  Loaded:  ${file} → ${data.track} — ${data.races.length} races`);
        } catch (e) {
          console.error(`  Error:   ${file} — ${e.message}`);
        }
      }

      res.writeHead(200, {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*',
        'Cache-Control': 'no-store',
      });
      res.end(JSON.stringify(tracks));
    } catch (e) {
      res.writeHead(500, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ error: e.message }));
    }
    return;
  }

  // ── CORS preflight ──────────────────────────────────────────
  if (req.method === 'OPTIONS') {
    res.writeHead(204, corsHeaders());
    res.end();
    return;
  }

  // ── Auth helpers ─────────────────────────────────────────────
  function getToken() {
    const auth = req.headers['authorization'] || '';
    return auth.startsWith('Bearer ') ? auth.slice(7) : null;
  }

  function requireAuth(res) {
    const user = db.validateSession(getToken());
    if (!user) {
      res.writeHead(401, { 'Content-Type': 'application/json', ...corsHeaders() });
      res.end(JSON.stringify({ error: 'Unauthorized' }));
      return null;
    }
    return user;
  }

  function readBody() {
    return new Promise(resolve => {
      let body = '';
      req.on('data', c => body += c);
      req.on('end', () => { try { resolve(JSON.parse(body)); } catch { resolve({}); } });
    });
  }

  function jsonOk(data) {
    res.writeHead(200, { 'Content-Type': 'application/json', ...corsHeaders() });
    res.end(JSON.stringify(data));
  }

  function jsonErr(status, msg) {
    res.writeHead(status, { 'Content-Type': 'application/json', ...corsHeaders() });
    res.end(JSON.stringify({ error: msg }));
  }

  // ── POST /api/auth/login ─────────────────────────────────────
  if (url.pathname === '/api/auth/login' && req.method === 'POST') {
    (async () => {
      const { username, password } = await readBody();
      if (!username || !password) return jsonErr(400, 'Username and password required');
      const user = db.getUserByUsername(username);
      if (!user || !db.verifyPassword(user, password)) return jsonErr(401, 'Invalid credentials');
      const token = db.createSession(user.id);
      jsonOk({ token, username: user.username, isAdmin: !!user.is_admin });
    })();
    return;
  }

  // ── POST /api/auth/logout ────────────────────────────────────
  if (url.pathname === '/api/auth/logout' && req.method === 'POST') {
    db.deleteSession(getToken());
    jsonOk({ ok: true });
    return;
  }

  // ── GET /api/auth/me ─────────────────────────────────────────
  if (url.pathname === '/api/auth/me' && req.method === 'GET') {
    const user = db.validateSession(getToken());
    if (!user) return jsonErr(401, 'Unauthorized');
    jsonOk({ id: user.id, username: user.username, isAdmin: !!user.is_admin });
    return;
  }

  // ── POST /api/auth/register (admin-only; first user auto-admin) ──
  if (url.pathname === '/api/auth/register' && req.method === 'POST') {
    (async () => {
      const { username, password, isAdmin, firstName, lastName, email, mobile } = await readBody();
      if (!username || !password) return jsonErr(400, 'Username and password required');
      if (password.length < 6) return jsonErr(400, 'Password must be at least 6 characters');

      const isFirst = db.userCount() === 0;
      if (!isFirst) {
        const caller = db.validateSession(getToken());
        if (!caller || !caller.is_admin) return jsonErr(403, 'Admin only');
      }

      try {
        db.createUser(username, password, isFirst || !!isAdmin, { firstName, lastName, email, mobile });
        jsonOk({ ok: true, username, isAdmin: isFirst || !!isAdmin });
      } catch (e) {
        jsonErr(409, 'Username already taken');
      }
    })();
    return;
  }

  // ── Admin: GET /api/admin/users ──────────────────────────────
  if (url.pathname === '/api/admin/users' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    if (!user.is_admin) return jsonErr(403, 'Admin only');
    jsonOk(db.getAllUsers());
    return;
  }

  // ── Admin: DELETE /api/admin/users/:id ──────────────────────
  if (url.pathname.startsWith('/api/admin/users/') && req.method === 'DELETE') {
    const user = requireAuth(res);
    if (!user) return;
    if (!user.is_admin) return jsonErr(403, 'Admin only');
    const targetId = parseInt(url.pathname.split('/').pop());
    if (targetId === user.id) return jsonErr(400, 'Cannot delete yourself');
    db.deleteUser(targetId);
    jsonOk({ ok: true });
    return;
  }

  // ── Admin: POST /api/admin/users/:id/password ────────────────
  if (url.pathname.match(/^\/api\/admin\/users\/\d+\/password$/) && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      if (!user.is_admin) return jsonErr(403, 'Admin only');
      const targetId = parseInt(url.pathname.split('/')[4]);
      const { password } = await readBody();
      if (!password || password.length < 6) return jsonErr(400, 'Password must be at least 6 characters');
      db.updatePassword(targetId, password);
      db.deleteUserSessions(targetId);
      jsonOk({ ok: true });
    })();
    return;
  }

  // ── PUT /api/user/profile ────────────────────────────────────
  if (url.pathname === '/api/user/profile' && req.method === 'PUT') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const { firstName, lastName, email, mobile } = await readBody();
      db.updateUserProfile(user.id, { firstName, lastName, email, mobile });
      jsonOk(db.getUserById(user.id));
    })();
    return;
  }

  // ── GET /api/user/picks ──────────────────────────────────────
  if (url.pathname === '/api/user/picks' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    const trackCode = url.searchParams.get('trackCode');
    const dateRaw   = url.searchParams.get('date');
    const raceNum   = parseInt(url.searchParams.get('race'));
    if (!trackCode || !dateRaw || !raceNum) return jsonErr(400, 'trackCode, date, race required');
    jsonOk({ picks: db.getUserPicks(user.id, trackCode, dateRaw, raceNum) });
    return;
  }

  // ── POST /api/user/picks ─────────────────────────────────────
  if (url.pathname === '/api/user/picks' && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const { trackCode, date, race, pickOrder } = await readBody();
      if (!trackCode || !date || !race || !Array.isArray(pickOrder)) return jsonErr(400, 'Invalid payload');
      db.setUserPicks(user.id, trackCode, date, race, pickOrder);
      jsonOk({ ok: true });
    })();
    return;
  }

  // ── GET /api/user/picks/date ─────────────────────────────────
  if (url.pathname === '/api/user/picks/date' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    const trackCode = url.searchParams.get('trackCode');
    const dateRaw   = url.searchParams.get('date');
    if (!trackCode || !dateRaw) return jsonErr(400, 'trackCode and date required');
    jsonOk(db.getUserPicksForDate(user.id, trackCode, dateRaw));
    return;
  }

  // ── GET /api/user/algos ─────────────────────────────────────
  if (url.pathname === '/api/user/algos' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    jsonOk(db.getUserAlgos(user.id));
    return;
  }

  // ── POST /api/user/algos ─────────────────────────────────────
  if (url.pathname === '/api/user/algos' && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const { name } = await readBody();
      if (!name?.trim()) return jsonErr(400, 'Name required');
      const id = db.createAlgo(user.id, name.trim(), db.DEFAULT_ALGO_PARAMS);
      jsonOk({ id, name: name.trim() });
    })();
    return;
  }

  // ── GET /api/user/algos/active ───────────────────────────────
  if (url.pathname === '/api/user/algos/active' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    const algo = db.getActiveAlgo(user.id);
    jsonOk(algo || { id: null, name: 'Base', params: db.DEFAULT_ALGO_PARAMS });
    return;
  }

  // ── POST /api/user/algos/:id/activate ────────────────────────
  if (url.pathname.match(/^\/api\/user\/algos\/(\d+)\/activate$/) && req.method === 'POST') {
    const user = requireAuth(res);
    if (!user) return;
    const algoId = parseInt(url.pathname.split('/')[4]);
    db.setActiveAlgo(algoId, user.id);
    const algo = db.getAlgoById(algoId, user.id);
    if (!algo) return jsonErr(404, 'Algo not found');
    jsonOk(algo);
    return;
  }

  // ── POST /api/user/algos/deactivate ──────────────────────────
  if (url.pathname === '/api/user/algos/deactivate' && req.method === 'POST') {
    const user = requireAuth(res);
    if (!user) return;
    db.deactivateAllAlgos(user.id);
    jsonOk({ id: null, name: 'Base', params: db.DEFAULT_ALGO_PARAMS });
    return;
  }

  // ── PUT /api/user/algos/:id ───────────────────────────────────
  if (url.pathname.match(/^\/api\/user\/algos\/\d+$/) && req.method === 'PUT') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const algoId = parseInt(url.pathname.split('/').pop());
      const { params, note, name, bet_type } = await readBody();
      if (name) db.renameAlgo(algoId, user.id, name);
      if (params) db.updateAlgoParams(algoId, user.id, params, note);
      if (bet_type) db.updateAlgoBetType(algoId, user.id, bet_type);
      jsonOk({ ok: true });
    })();
    return;
  }

  // ── DELETE /api/user/algos/:id ────────────────────────────────
  if (url.pathname.match(/^\/api\/user\/algos\/\d+$/) && req.method === 'DELETE') {
    const user = requireAuth(res);
    if (!user) return;
    const algoId = parseInt(url.pathname.split('/').pop());
    const ok = db.deleteAlgo(algoId, user.id);
    if (!ok) return jsonErr(404, 'Algo not found');
    jsonOk({ ok: true });
    return;
  }

  // ── GET /api/user/algos/:id/history ──────────────────────────
  if (url.pathname.match(/^\/api\/user\/algos\/\d+\/history$/) && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    const algoId = parseInt(url.pathname.split('/')[4]);
    const history = db.getAlgoHistory(algoId, user.id);
    if (!history) return jsonErr(404, 'Algo not found');
    jsonOk(history);
    return;
  }

  // ── POST /api/user/algos/:id/history/:hid/restore ─────────────
  if (url.pathname.match(/^\/api\/user\/algos\/\d+\/history\/\d+\/restore$/) && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const parts = url.pathname.split('/');
      const algoId = parseInt(parts[4]);
      const histId = parseInt(parts[6]);
      const snap = db.getAlgoHistorySnapshot(histId, algoId, user.id);
      if (!snap) return jsonErr(404, 'Snapshot not found');
      db.updateAlgoParams(algoId, user.id, snap.params, 'Restored from ' + snap.created_at.slice(0,16));
      jsonOk({ ok: true, params: snap.params });
    })();
    return;
  }

  // ── GET /api/chat/history?algoId=X ──────────────────────────
  if (url.pathname === '/api/chat/history' && req.method === 'GET') {
    const user = requireAuth(res);
    if (!user) return;
    const algoId = parseInt(url.searchParams.get('algoId'));
    if (!algoId) return jsonErr(400, 'algoId required');
    // Verify ownership
    if (!db.getAlgoById(algoId, user.id)) return jsonErr(404, 'Algo not found');
    jsonOk(db.getChatHistory(algoId));
    return;
  }

  // ── POST /api/chat ───────────────────────────────────────────
  if (url.pathname === '/api/chat' && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      if (!CLAUDE_API_KEY) return jsonErr(503, 'Agent not configured — add CLAUDE_API_KEY to environment');

      const { message, algoId } = await readBody();
      if (!message?.trim()) return jsonErr(400, 'Message required');
      if (!algoId) return jsonErr(400, 'algoId required');

      const algo = db.getAlgoById(algoId, user.id);
      if (!algo) return jsonErr(404, 'Algo not found');

      const history = db.getChatHistory(algoId);
      db.appendChatMessage(algoId, 'user', message);

      const systemPrompt = `You are an expert horse racing handicapping assistant inside CapperAI. You help users understand, tune, and improve their personal Pick 3 scoring algorithm through conversation.

## User's Current Algo

Name: "${algo.name}"
Parameters:
${JSON.stringify(algo.params, null, 2)}

---

## Scoring Architecture

The algo scores each horse in three stages:

**Stage 1 — Linear score** (per-race-type weighted sum):
Eight factors are normalized 0–100 across the field (nulls land at midpoint 62.5). The score is the weighted sum × 100.

- nML: morning line implied probability — public consensus signal
- nFig: average recent speed figure (BRIS)
- nBest: best speed figure over sample — ceiling, not average
- nClose: closing ability + jockey closing score — how fast horse finishes
- nLatePace: average BRIS late pace rating
- nForm: figure consistency score — is horse improving or declining?
- nTrend: short-term figure trend (last 2–3 races)
- nLongTrend: longer-term improvement arc

**Three weight sets:**
- "cheap" — budget tracks (TDN, MNR, TUP, etc.) or claiming price ≤ $10k. nML=30% and nLatePace=24% are dominant because market and pace are the clearest signals at this level.
- "stakes" — graded stakes and stakes races. nBest=20% dominates because ceiling matters most at elite level. nML=16% (public is sophisticated but not overwhelming).
- Standard (allowance, MSW, opt claimer) — fixed weights: nFig×18, nBest×8, nTrend×4, nLongTrend×4, nForm×26, nClose×13, nLatePace×11, nML×6. Not user-tunable.

**IMPORTANT:** Each weight set (cheap, stakes) must sum exactly to 1.0 (excluding mlPower). Always recalculate when changing any weight.

**Stage 2 — Multiplicative adjustments** (applied after linear score):
- jMult: jockey ROI multiplier (live data from DRF)
- tMult: trainer ROI multiplier
- classMult: class drop/rise bonus (horse entering below its typical level)
- layoffMult: layoff quality (good trainers freshening horses get a reward)
- dropoutMult: comeback boost for horses that were in contention but faded (see below)
- fitnessMult: sharpFitness adjustments for cheap claiming only
- Plus: blowoutMult, surfSwitch, paceMult, fieldMult, condMult, and others

**Stage 3 — Benter combined model:**
  combinedRaw = (modelProb) x (mlProb ^ mlPower)
Renormalized across the field. This is the Benter (1994) approach — eliminates systematic bias from pure fundamental models by anchoring to public implied probability. mlPower controls how aggressively the morning line anchors the output.

---

## Tunable Multipliers

### Dropout Multiplier
Boosts horses that were in contention at the 2nd call but faded badly. Public remembers the bad finish, not the hidden effort — creating a misprice.

Qualification: pp[0].bl2nd between 0 and dropoutBl2ndMax (was within N lengths at 2nd call), finish >= 5 (clearly faded), days_since between dropoutDaysMin and dropoutDaysMax, jockey_roi > 0.

This single factor drove backtested P&L from $775 to $2,823 on Apr 28–30 2026 data (CD/TDN/MNR/TUP).

### Sharp Fitness (cheap claiming only)
- sharpFitness.oneStart25d (default 1.60): one start in last 25 days — horse is sharp and fresh
- sharpFitness.twoStarts45d (default 0.88): 2 starts in 45 days — slightly over-raced
- sharpFitness.threeStarts45d (default 0.82): 3+ starts in 45 days — over-raced, penalize

### ROI Multipliers
- roiScale (default 0.06): formula is mult = 1 + roi x roiScale. ROI=2.0 → +0.12, ROI=-1.0 → -0.06
- roiFloor (default 0.95): losing jockey/trainer can't hurt more than 5%
- roiCeiling (default 1.20): best jockey/trainer adds up to 20%
- Requires minimum 10 starts to avoid small-sample noise

---

## Race Type Framework

**Cheap claiming:** Form consistency is noise. Pace and market are king. Late pace is the #1 miss factor (39% of losses at cheap tracks). Winners often have poor recent form — the crowd overlooks them.

**Stakes:** Best figure ceiling > average figure (one tactical setup run shouldn't bury a horse's ceiling). Morning line is strong but not dominant — sophisticated public already prices connections.

**Maiden races (any level):** ML anchor is weakened (mlPower 0.5). Trainer, sire affinity, and debut angle matter most — limited public form knowledge.

**Key handicapping principles:**
- Morning line is an aggregate of insider information, workout reports, and connections' confidence. Don't just re-sort by ML — find where it's wrong.
- Class movement matters: a horse entering below its recent level is a positive signal.
- Pace scenario: a race with 3+ early-speed horses benefits closers significantly.
- Dropout pattern: horses close at the 2nd call that fade are systematically undervalued next time out.

---

## Responding to Users

When the user asks to change weights or parameters, give a brief handicapping explanation (1–2 sentences on why it makes sense), then output a JSON block:

\`\`\`json
{"action":"update_algo","params":{...},"note":"short description of what changed"}
\`\`\`

Only include the fields that are changing. The system will deep-merge with existing params.
Always verify cheap/stakes weights still sum to 1.0 after any change.

Changes are shown to the user as numbered proposals (Change #1, #2, etc.) that they must Save or Cancel before they apply. The user may refer back to a change by number (e.g. "take out #2") — if so, restore the algo to the state before that change by proposing new params that undo it.

Be concise and direct. Focus on handicapping logic and Pick 3 performance. When explaining a factor, connect it to actual race dynamics — not abstract math.`;

      const messages = [
        ...history.map(h => ({ role: h.role, content: h.content })),
        { role: 'user', content: message },
      ];

      const body = JSON.stringify({
        model: CLAUDE_MODEL,
        max_tokens: 1024,
        system: systemPrompt,
        messages,
      });

      try {
        const response = await new Promise((resolve, reject) => {
          const req2 = https.request({
            hostname: 'api.anthropic.com',
            path: '/v1/messages',
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'x-api-key': CLAUDE_API_KEY,
              'anthropic-version': '2023-06-01',
              'Content-Length': Buffer.byteLength(body),
            },
          }, res2 => {
            let data = '';
            res2.on('data', c => data += c);
            res2.on('end', () => resolve({ status: res2.statusCode, body: data }));
          });
          req2.on('error', reject);
          req2.write(body);
          req2.end();
        });

        const parsed = JSON.parse(response.body);
        if (response.status !== 200) throw new Error(parsed.error?.message || 'Claude API error');

        const reply = parsed.content?.[0]?.text || '';
        db.appendChatMessage(algoId, 'assistant', reply);

        // Extract any param changes Claude suggests — return as pending (user must confirm)
        const jsonMatch = reply.match(/```json\s*([\s\S]*?)\s*```/);
        let pendingParams = null, pendingNote = null;
        if (jsonMatch) {
          try {
            const action = JSON.parse(jsonMatch[1]);
            if (action.action === 'update_algo' && action.params) {
              pendingParams = deepMerge(algo.params, action.params);
              pendingNote   = action.note || 'Agent update';
            }
          } catch {}
        }

        jsonOk({ reply, pendingParams, pendingNote });
      } catch (e) {
        jsonErr(500, 'Agent error: ' + e.message);
      }
    })();
    return;
  }

  // ── POST /api/user/algos/:id/apply ───────────────────────────
  if (url.pathname.match(/^\/api\/user\/algos\/\d+\/apply$/) && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const algoId = parseInt(url.pathname.split('/')[4]);
      const { params, note } = await readBody();
      if (!params) return jsonErr(400, 'params required');
      if (!db.getAlgoById(algoId, user.id)) return jsonErr(404, 'Algo not found');
      db.updateAlgoParams(algoId, user.id, params, note || 'Agent update');
      jsonOk({ ok: true });
    })();
    return;
  }

  // ── POST /api/chat/clear ─────────────────────────────────────
  if (url.pathname === '/api/chat/clear' && req.method === 'POST') {
    (async () => {
      const user = requireAuth(res);
      if (!user) return;
      const { algoId } = await readBody();
      if (!algoId) return jsonErr(400, 'algoId required');
      if (!db.getAlgoById(algoId, user.id)) return jsonErr(404, 'Algo not found');
      db.clearChatHistory(algoId);
      jsonOk({ ok: true });
    })();
    return;
  }

  // ── Static files ────────────────────────────────────────────
  const filePath = url.pathname === '/' ? '/index.html' : url.pathname;
  serveStatic(res, filePath);

  } catch (err) {
    console.error('[request error]', req.method, req.url, err.message, err.stack);
    try {
      res.writeHead(500, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' });
      res.end(JSON.stringify({ error: 'Internal server error' }));
    } catch {}
  }
});

// Ensure data dir exists
if (!fs.existsSync(DATA_DIR)) {
  fs.mkdirSync(DATA_DIR, { recursive: true });
  console.log(`Created data directory: ${DATA_DIR}`);
}

// Start listening immediately so the browser can connect right away,
// then kick off zip extraction in the background.
server.listen(PORT, '0.0.0.0', () => {
  console.log('\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log(`  🏇  TRIFECTA  —  http://localhost:${PORT}`);
  console.log(`  Data:  ${DATA_DIR}`);
  console.log(`  Date:  ${TARGET_DATE || todayYMD()}${TARGET_DATE ? ' (override)' : ''}`);
  console.log('  Drop .DRF files into data/ then refresh.\n');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');
  extractPendingZips();
});
