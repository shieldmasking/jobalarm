'use strict';

const Database = require('better-sqlite3');
const bcrypt   = require('bcryptjs');
const crypto   = require('crypto');
const path     = require('path');

const DB_PATH = path.join(__dirname, 'data', 'trifecta.db');

let _db;
function getDb() {
  if (!_db) {
    _db = new Database(DB_PATH);
    _db.pragma('journal_mode = WAL');
    _db.pragma('foreign_keys = ON');
    initSchema(_db);
  }
  return _db;
}

function initSchema(db) {
  db.exec(`
    CREATE TABLE IF NOT EXISTS users (
      id            INTEGER PRIMARY KEY AUTOINCREMENT,
      username      TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      is_admin      INTEGER DEFAULT 0,
      first_name    TEXT DEFAULT '',
      last_name     TEXT DEFAULT '',
      email         TEXT DEFAULT '',
      mobile        TEXT DEFAULT '',
      created_at    TEXT DEFAULT (datetime('now'))
    );

    CREATE TABLE IF NOT EXISTS sessions (
      token      TEXT PRIMARY KEY,
      user_id    INTEGER NOT NULL,
      expires_at TEXT NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS user_picks (
      id         INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id    INTEGER NOT NULL,
      track_code TEXT NOT NULL,
      date_raw   TEXT NOT NULL,
      race_num   INTEGER NOT NULL,
      pick_order TEXT NOT NULL,
      updated_at TEXT DEFAULT (datetime('now')),
      UNIQUE(user_id, track_code, date_raw, race_num),
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS user_algos (
      id         INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id    INTEGER NOT NULL,
      name       TEXT NOT NULL,
      params     TEXT NOT NULL,
      is_active  INTEGER DEFAULT 0,
      created_at TEXT DEFAULT (datetime('now')),
      updated_at TEXT DEFAULT (datetime('now')),
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS algo_history (
      id         INTEGER PRIMARY KEY AUTOINCREMENT,
      algo_id    INTEGER NOT NULL,
      params     TEXT NOT NULL,
      note       TEXT,
      created_at TEXT DEFAULT (datetime('now')),
      FOREIGN KEY (algo_id) REFERENCES user_algos(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS chat_history (
      id         INTEGER PRIMARY KEY AUTOINCREMENT,
      algo_id    INTEGER NOT NULL,
      role       TEXT NOT NULL,
      content    TEXT NOT NULL,
      created_at TEXT DEFAULT (datetime('now')),
      FOREIGN KEY (algo_id) REFERENCES user_algos(id) ON DELETE CASCADE
    );
  `);

  // Migrations for existing DBs

  // users table — add profile columns if missing
  const userCols = db.prepare("PRAGMA table_info(users)").all().map(c => c.name);
  for (const [col, def] of [
    ['first_name', "TEXT DEFAULT ''"],
    ['last_name',  "TEXT DEFAULT ''"],
    ['email',      "TEXT DEFAULT ''"],
    ['mobile',     "TEXT DEFAULT ''"],
  ]) {
    if (!userCols.includes(col)) db.exec(`ALTER TABLE users ADD COLUMN ${col} ${def}`);
  }

  // user_algos — add bet_type column if missing
  const algoCols = db.prepare("PRAGMA table_info(user_algos)").all().map(c => c.name);
  if (!algoCols.includes('bet_type')) {
    db.exec("ALTER TABLE user_algos ADD COLUMN bet_type TEXT DEFAULT 'pick3'");
  }

  // chat_history — old schema used user_id; new schema uses algo_id. Recreate if wrong.
  const chatCols = db.prepare("PRAGMA table_info(chat_history)").all().map(c => c.name);
  if (!chatCols.includes('algo_id')) {
    db.exec(`
      DROP TABLE IF EXISTS chat_history;
      CREATE TABLE chat_history (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        algo_id    INTEGER NOT NULL,
        role       TEXT NOT NULL,
        content    TEXT NOT NULL,
        created_at TEXT DEFAULT (datetime('now')),
        FOREIGN KEY (algo_id) REFERENCES user_algos(id) ON DELETE CASCADE
      );
    `);
  }
}

// ── USERS ─────────────────────────────────────────────────────────────────────

function createUser(username, password, isAdmin = false, { firstName = '', lastName = '', email = '', mobile = '' } = {}) {
  const db = getDb();
  const hash = bcrypt.hashSync(password, 10);
  return db.prepare(
    'INSERT INTO users (username, password_hash, is_admin, first_name, last_name, email, mobile) VALUES (?, ?, ?, ?, ?, ?, ?)'
  ).run(username, hash, isAdmin ? 1 : 0, firstName, lastName, email, mobile);
}

function getUserByUsername(username) {
  return getDb().prepare('SELECT * FROM users WHERE username = ?').get(username);
}

function getUserById(id) {
  return getDb().prepare('SELECT id, username, is_admin, first_name, last_name, email, mobile, created_at FROM users WHERE id = ?').get(id);
}

function getAllUsers() {
  return getDb().prepare('SELECT id, username, is_admin, first_name, last_name, email, mobile, created_at FROM users ORDER BY created_at').all();
}

function updateUserProfile(userId, { firstName, lastName, email, mobile }) {
  getDb().prepare(
    'UPDATE users SET first_name=?, last_name=?, email=?, mobile=? WHERE id=?'
  ).run(firstName ?? '', lastName ?? '', email ?? '', mobile ?? '', userId);
}

function userCount() {
  return getDb().prepare('SELECT COUNT(*) as n FROM users').get().n;
}

function verifyPassword(user, password) {
  return bcrypt.compareSync(password, user.password_hash);
}

function updatePassword(userId, newPassword) {
  const hash = bcrypt.hashSync(newPassword, 10);
  getDb().prepare('UPDATE users SET password_hash = ? WHERE id = ?').run(hash, userId);
}

function deleteUser(userId) {
  getDb().prepare('DELETE FROM users WHERE id = ?').run(userId);
}

// ── SESSIONS ──────────────────────────────────────────────────────────────────

const SESSION_TTL_DAYS = 30;

function createSession(userId) {
  const db = getDb();
  const token = crypto.randomBytes(32).toString('hex');
  const expires = new Date(Date.now() + SESSION_TTL_DAYS * 86400000).toISOString();
  db.prepare('INSERT INTO sessions (token, user_id, expires_at) VALUES (?, ?, ?)').run(token, userId, expires);
  return token;
}

function validateSession(token) {
  if (!token) return null;
  const db = getDb();
  const session = db.prepare('SELECT * FROM sessions WHERE token = ?').get(token);
  if (!session) return null;
  if (new Date(session.expires_at) < new Date()) {
    db.prepare('DELETE FROM sessions WHERE token = ?').run(token);
    return null;
  }
  return getUserById(session.user_id);
}

function deleteSession(token) {
  getDb().prepare('DELETE FROM sessions WHERE token = ?').run(token);
}

function deleteUserSessions(userId) {
  getDb().prepare('DELETE FROM sessions WHERE user_id = ?').run(userId);
}

// ── USER PICKS ────────────────────────────────────────────────────────────────

function getUserPicks(userId, trackCode, dateRaw, raceNum) {
  const row = getDb()
    .prepare('SELECT pick_order FROM user_picks WHERE user_id=? AND track_code=? AND date_raw=? AND race_num=?')
    .get(userId, trackCode, dateRaw, raceNum);
  return row ? JSON.parse(row.pick_order) : null;
}

function setUserPicks(userId, trackCode, dateRaw, raceNum, pickOrder) {
  getDb().prepare(`
    INSERT INTO user_picks (user_id, track_code, date_raw, race_num, pick_order, updated_at)
    VALUES (?, ?, ?, ?, ?, datetime('now'))
    ON CONFLICT(user_id, track_code, date_raw, race_num)
    DO UPDATE SET pick_order=excluded.pick_order, updated_at=excluded.updated_at
  `).run(userId, trackCode, dateRaw, raceNum, JSON.stringify(pickOrder));
}

function getUserPicksForDate(userId, trackCode, dateRaw) {
  const rows = getDb()
    .prepare('SELECT race_num, pick_order FROM user_picks WHERE user_id=? AND track_code=? AND date_raw=?')
    .all(userId, trackCode, dateRaw);
  const result = {};
  for (const row of rows) result[row.race_num] = JSON.parse(row.pick_order);
  return result;
}

// ── USER ALGOS ────────────────────────────────────────────────────────────────

// Default algo params — mirrors the current global formula in index.html
const DEFAULT_ALGO_PARAMS = {
  cheap: {
    nML: 0.30, nLatePace: 0.24, nClose: 0.18, nForm: 0.08,
    nFig: 0.06, nBest: 0.04, nTrend: 0.02, nLongTrend: 0.02,
    mlPower: 1.3,
  },
  stakes: {
    nML: 0.16, nBest: 0.20, nFig: 0.10, nForm: 0.14,
    nClose: 0.14, nLatePace: 0.10, nTrend: 0.02, nLongTrend: 0.04,
    mlPower: 1.3,
  },
  dropoutMult: 1.30,
  dropoutBl2ndMax: 5,
  dropoutDaysMin: 20,
  dropoutDaysMax: 90,
  sharpFitness: {
    oneStart25d: 1.60,
    twoStarts45d: 0.88,
    threeStarts45d: 0.82,
  },
  roiFloor: 0.95,
  roiCeiling: 1.20,
  roiScale: 0.06,
};

function getUserAlgos(userId) {
  return getDb()
    .prepare('SELECT id, name, is_active, created_at, updated_at FROM user_algos WHERE user_id = ? ORDER BY created_at')
    .all(userId);
}

function getActiveAlgo(userId) {
  const db = getDb();
  const row = db.prepare('SELECT * FROM user_algos WHERE user_id = ? AND is_active = 1').get(userId);
  if (row) return { ...row, params: JSON.parse(row.params) };
  return null; // null = use base algo
}

function getAlgoById(algoId, userId) {
  const row = getDb().prepare('SELECT * FROM user_algos WHERE id = ? AND user_id = ?').get(algoId, userId);
  if (!row) return null;
  return { ...row, params: JSON.parse(row.params) };
}

function createAlgo(userId, name, params) {
  const db = getDb();
  // Deactivate all others first
  db.prepare('UPDATE user_algos SET is_active = 0 WHERE user_id = ?').run(userId);
  const result = db.prepare(`
    INSERT INTO user_algos (user_id, name, params, is_active, created_at, updated_at)
    VALUES (?, ?, ?, 1, datetime('now'), datetime('now'))
  `).run(userId, name, JSON.stringify(params || DEFAULT_ALGO_PARAMS));
  // Save initial history snapshot
  db.prepare('INSERT INTO algo_history (algo_id, params, note) VALUES (?, ?, ?)')
    .run(result.lastInsertRowid, JSON.stringify(params || DEFAULT_ALGO_PARAMS), 'Initial');
  return result.lastInsertRowid;
}

function updateAlgoParams(algoId, userId, params, note) {
  const db = getDb();
  db.prepare(`
    UPDATE user_algos SET params = ?, updated_at = datetime('now') WHERE id = ? AND user_id = ?
  `).run(JSON.stringify(params), algoId, userId);
  // Save history snapshot
  db.prepare('INSERT INTO algo_history (algo_id, params, note) VALUES (?, ?, ?)')
    .run(algoId, JSON.stringify(params), note || null);
}

function updateAlgoBetType(algoId, userId, betType) {
  getDb().prepare("UPDATE user_algos SET bet_type = ?, updated_at = datetime('now') WHERE id = ? AND user_id = ?")
    .run(betType, algoId, userId);
}

function setActiveAlgo(algoId, userId) {
  const db = getDb();
  db.prepare('UPDATE user_algos SET is_active = 0 WHERE user_id = ?').run(userId);
  db.prepare('UPDATE user_algos SET is_active = 1 WHERE id = ? AND user_id = ?').run(algoId, userId);
}

function deactivateAllAlgos(userId) {
  getDb().prepare('UPDATE user_algos SET is_active = 0 WHERE user_id = ?').run(userId);
}

function deleteAlgo(algoId, userId) {
  const db = getDb();
  const algo = db.prepare('SELECT * FROM user_algos WHERE id = ? AND user_id = ?').get(algoId, userId);
  if (!algo) return false;
  db.prepare('DELETE FROM user_algos WHERE id = ?').run(algoId);
  // If deleted algo was active, nothing else becomes active (falls back to base)
  return true;
}

function renameAlgo(algoId, userId, name) {
  getDb().prepare('UPDATE user_algos SET name = ? WHERE id = ? AND user_id = ?').run(name, algoId, userId);
}

function getAlgoHistory(algoId, userId) {
  // Verify ownership
  const algo = getDb().prepare('SELECT id FROM user_algos WHERE id = ? AND user_id = ?').get(algoId, userId);
  if (!algo) return null;
  return getDb()
    .prepare('SELECT id, note, created_at FROM algo_history WHERE algo_id = ? ORDER BY created_at DESC LIMIT 30')
    .all(algoId);
}

function getAlgoHistorySnapshot(historyId, algoId, userId) {
  const algo = getDb().prepare('SELECT id FROM user_algos WHERE id = ? AND user_id = ?').get(algoId, userId);
  if (!algo) return null;
  const row = getDb().prepare('SELECT * FROM algo_history WHERE id = ? AND algo_id = ?').get(historyId, algoId);
  if (!row) return null;
  return { ...row, params: JSON.parse(row.params) };
}

// ── CHAT HISTORY ──────────────────────────────────────────────────────────────

const CHAT_HISTORY_LIMIT = 50;

function getChatHistory(algoId) {
  return getDb()
    .prepare('SELECT role, content, created_at FROM chat_history WHERE algo_id = ? ORDER BY created_at DESC LIMIT ?')
    .all(algoId, CHAT_HISTORY_LIMIT)
    .reverse();
}

function appendChatMessage(algoId, role, content) {
  const db = getDb();
  db.prepare('INSERT INTO chat_history (algo_id, role, content) VALUES (?, ?, ?)').run(algoId, role, content);
  db.prepare(`
    DELETE FROM chat_history WHERE algo_id = ? AND id NOT IN (
      SELECT id FROM chat_history WHERE algo_id = ? ORDER BY created_at DESC LIMIT ?
    )
  `).run(algoId, algoId, CHAT_HISTORY_LIMIT);
}

function clearChatHistory(algoId) {
  getDb().prepare('DELETE FROM chat_history WHERE algo_id = ?').run(algoId);
}

module.exports = {
  getDb,
  // Users
  createUser, getUserByUsername, getUserById, getAllUsers, userCount,
  verifyPassword, updatePassword, deleteUser, updateUserProfile,
  // Sessions
  createSession, validateSession, deleteSession, deleteUserSessions,
  // Picks
  getUserPicks, setUserPicks, getUserPicksForDate,
  // Algos
  DEFAULT_ALGO_PARAMS,
  getUserAlgos, getActiveAlgo, getAlgoById,
  createAlgo, updateAlgoParams, updateAlgoBetType, setActiveAlgo, deactivateAllAlgos,
  deleteAlgo, renameAlgo,
  getAlgoHistory, getAlgoHistorySnapshot,
  // Chat
  getChatHistory, appendChatMessage, clearChatHistory,
};
