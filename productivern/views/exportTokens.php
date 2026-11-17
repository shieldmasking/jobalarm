<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
$role = intval($_SESSION['account']['role']);
if ($role < 5) { echo '<p class="text-danger">Access denied.</p>'; exit; }
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
?>
<div class="m-subheader">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title">Google Sheets Export</h3>
        </div>
    </div>
</div>

<div class="m-content">
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">API Tokens</h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <button class="btn btn-success btn-sm" id="btnCreateToken">Generate New Token</button>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group" id="createTokenForm" style="display:none;">
                        <label>Token Label</label>
                        <div class="input-group" style="max-width:400px;">
                            <input type="text" id="tokenLabel" class="form-control" placeholder="e.g. Main Hospital Sheet">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btnSaveToken">Create</button>
                                <button class="btn btn-secondary" id="btnCancelToken">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped" id="tokenTable">
                        <thead><tr>
                            <th>Label</th>
                            <th>Token</th>
                            <th>Created</th>
                            <th></th>
                        </tr></thead>
                        <tbody id="tokenRows"><tr><td colspan="4">Loading...</td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="m-portlet" id="scriptPanel" style="display:none;">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">Google Apps Script Setup</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <ol style="margin-bottom:16px;">
                        <li>Open your Google Sheet &rarr; <strong>Extensions &rarr; Apps Script</strong></li>
                        <li>Delete any existing code and paste the script below</li>
                        <li>Click <strong>Save</strong>, then <strong>Run &rarr; runExport</strong> to test</li>
                        <li>Add a trigger: <strong>Triggers &rarr; Add Trigger &rarr; runExport &rarr; Time-driven &rarr; Day timer &rarr; 6am–7am</strong> and repeat for <strong>6pm–7pm</strong></li>
                    </ol>
                    <div style="position:relative;">
                        <button class="btn btn-sm btn-primary" id="btnCopyScript" style="position:absolute;top:8px;right:8px;z-index:1;">Copy Script</button>
                        <pre id="appsScriptCode" style="background:#f8f9fa;padding:16px;border-radius:4px;font-size:11px;max-height:400px;overflow:auto;"></pre>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function(){
    var baseUrl = <?php echo json_encode($baseUrl); ?>;
    var activeToken = null;

    function loadTokens() {
        $.get('export/token-mgmt.php', {action:'list'}, function(res) {
            var tbody = $('#tokenRows');
            if (!res.tokens || res.tokens.length === 0) {
                tbody.html('<tr><td colspan="4">No tokens yet. Generate one above.</td></tr>');
                $('#scriptPanel').hide();
                return;
            }
            var html = '';
            res.tokens.forEach(function(t) {
                html += '<tr>' +
                    '<td>' + $('<span>').text(t.label).html() + '</td>' +
                    '<td><code style="font-size:11px;">' + t.token + '</code></td>' +
                    '<td>' + t.created_at + '</td>' +
                    '<td><button class="btn btn-xs btn-danger btn-revoke" data-id="' + t.id + '">Revoke</button>' +
                    ' <button class="btn btn-xs btn-info btn-script" data-token="' + t.token + '">View Script</button></td>' +
                    '</tr>';
            });
            tbody.html(html);
            // Show script for first token by default
            showScript(res.tokens[0].token);
        });
    }

    function showScript(token) {
        activeToken = token;
        var script = buildAppsScript(baseUrl, token);
        $('#appsScriptCode').text(script);
        $('#scriptPanel').show();
    }

    function buildAppsScript(baseUrl, token) {
        return `// ProductivRN — Google Sheets Export
// Generated: ${new Date().toLocaleDateString()}
//
// SETUP: Run setupTriggers() once from the Apps Script editor (Run menu).

var CONFIG = {
  baseUrl: "${baseUrl}",
  token:   "${token}"
};

function runExport() {
  var lock = LockService.getDocumentLock();
  if (!lock.tryLock(0)) return; // another export is running, skip
  try {
  var ss    = SpreadsheetApp.getActiveSpreadsheet();
  var today = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd");
  var url   = CONFIG.baseUrl + "/export/";

  var from = getDetailLastDate(ss) || today;
  var staffData  = fetchCsv(url + "staff-csv.php?token=" + CONFIG.token + "&from=" + from + "&to=" + today);
  var escData    = fetchCsv(url + "escalations-csv.php?token=" + CONFIG.token);
  var safetyData = fetchCsv(url + "safety-csv.php?token=" + CONFIG.token);

  var loading = ss.getSheetByName("_loading") || ss.insertSheet("_loading");
  ss.setActiveSheet(loading);

  buildDetailTab(ss, staffData, from);
  buildEscalationsTab(ss, escData);
  buildSafetyTab(ss, safetyData);
  buildDashboardTab(ss, staffData, escData, safetyData);

  ss.setActiveSheet(ss.getSheetByName("Dashboard"));
  ss.deleteSheet(loading);
  } finally { lock.releaseLock(); }
}

function runBackfill() {
  var lock = LockService.getDocumentLock();
  lock.waitLock(30000);
  try {
  var ss   = SpreadsheetApp.getActiveSpreadsheet();
  var from = "2026-01-01";
  var to   = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd");
  var base = CONFIG.baseUrl + "/export/";
  var qs   = "token=" + CONFIG.token + "&from=" + from + "&to=" + to;

  buildDetailTab(ss,      fetchCsv(base + "staff-csv.php?"       + qs), from);
  buildEscalationsTab(ss, fetchCsv(base + "escalations-csv.php?" + qs));
  buildSafetyTab(ss,      fetchCsv(base + "safety-csv.php?"      + qs));
  SpreadsheetApp.getUi().alert("Backfill complete.");
  } finally { lock.releaseLock(); }
}

function getDetailLastDate(ss) {
  var sheet = ss.getSheetByName("Detail");
  if (!sheet || sheet.getLastRow() < 2) return null;
  var headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];
  var dateCol = headers.indexOf("Sort Date");
  if (dateCol < 0) return null;
  var vals = sheet.getRange(2, dateCol + 1, sheet.getLastRow() - 1, 1).getValues()
    .map(function(r) {
      var v = r[0];
      return (v instanceof Date)
        ? Utilities.formatDate(v, Session.getScriptTimeZone(), "yyyy-MM-dd")
        : String(v).trim();
    })
    .filter(function(d) { return /^\d{4}-\d{2}-\d{2}/.test(d); });
  if (!vals.length) return null;
  vals.sort();
  return vals[vals.length - 1];
}

function fetchCsv(url) {
  try {
    var resp = UrlFetchApp.fetch(url, {muteHttpExceptions: true});
    if (resp.getResponseCode() !== 200) return [];
    return Utilities.parseCsv(resp.getContentText());
  } catch(e) { return []; }
}

function getOrCreate(ss, name) {
  var sheet = ss.getSheetByName(name);
  if (!sheet) sheet = ss.insertSheet(name);
  sheet.clearContents();
  sheet.clearFormats();
  sheet.getRange(1, 1, sheet.getMaxRows(), sheet.getMaxColumns()).clearDataValidations();
  return sheet;
}

function getSheet(ss, name) {
  var sheet = ss.getSheetByName(name);
  if (!sheet) sheet = ss.insertSheet(name);
  var lastRow = sheet.getLastRow();
  return {sheet: sheet, startRow: lastRow + 1, isNew: lastRow === 0};
}

function headerStyle(range) {
  range.setBackground("#1a4a8a").setFontColor("#ffffff").setFontWeight("bold");
}

function buildDetailTab(ss, rows, from) {
  var info = getSheet(ss, "Detail");
  var sheet = info.sheet;
  if (rows.length < 2) { if (info.isNew) sheet.getRange("A1").setValue("No data for today."); return; }
  var dataRows = rows.slice(1);

  // Filter to only the columns shown in the Detail tab
  var detailCols  = ["Date","Unit","Shift","Variance","RN Count","Staff Count","Pt Count","Notes","Sort Date","Shift Time"];
  var colIdx      = detailCols.map(function(n) { return rows[0].indexOf(n); });
  var numCols     = detailCols.length;
  var sortDateCol = detailCols.indexOf("Sort Date") + 1; // col 9 (1-indexed)
  var statusIdx   = rows[0].indexOf("Status");
  var blankCols   = {"Variance":1,"RN Count":1,"Staff Count":1,"Pt Count":1};
  var filteredRows = dataRows.map(function(r) {
    var isNoData = statusIdx >= 0 && String(r[statusIdx]).trim() === "No Report";
    return colIdx.map(function(i, ci) {
      if (i < 0) return "";
      if (isNoData && blankCols[detailCols[ci]]) return "";
      return r[i];
    });
  });

  // Batch dedup: keep existing rows outside the incoming date range, drop the rest
  var keepRows = [];
  if (!info.isNew && sheet.getLastRow() > 1) {
    var lastCol  = sheet.getLastColumn();
    var sheetHdr = sheet.getRange(1, 1, 1, lastCol).getValues()[0];
    var sdIdx    = sheetHdr.indexOf("Sort Date");
    if (sdIdx >= 0 && lastCol === numCols) {
      var existing = sheet.getRange(2, 1, sheet.getLastRow() - 1, lastCol).getValues();
      keepRows = existing.filter(function(r) {
        var v = r[sdIdx];
        var d = (v instanceof Date)
          ? Utilities.formatDate(v, Session.getScriptTimeZone(), "yyyy-MM-dd")
          : String(v).trim();
        return d < from;
      });
    }
    // If column layout changed, drop all old data (user should re-backfill)
  }

  // Rewrite sheet in one batch
  sheet.clearContents();
  sheet.getRange(1, 1, 1, numCols).setValues([detailCols]);
  headerStyle(sheet.getRange(1, 1, 1, numCols));
  sheet.setFrozenRows(1);

  var allRows = keepRows.concat(filteredRows);
  if (allRows.length > 0) {
    sheet.getRange(2, 1, allRows.length, numCols).setValues(allRows);
    sheet.getRange(2, 1,           allRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, sortDateCol, allRows.length, 1).setNumberFormat("@");
  }

  var lastRow = sheet.getLastRow();
  if (lastRow > 2) {
    sheet.getRange(2, 1, lastRow - 1, numCols).sort([
      {column: sortDateCol, ascending: false},
      {column: 2,           ascending: true},
      {column: numCols,     ascending: false}
    ]);
  }
}

function buildEscalationsTab(ss, rows) {
  var info = getSheet(ss, "Escalations");
  var sheet = info.sheet;
  if (rows.length < 2) { if (info.isNew) sheet.getRange("A1").setValue("No open escalations."); return; }
  var dataCols = rows[0].length;
  var closeCol = dataCols + 1;
  var dataRows = rows.slice(1);
  if (!info.isNew && sheet.getLastRow() > 1) {
    var existingData = sheet.getRange(2, 1, sheet.getLastRow() - 1, dataCols).getValues();
    var existingIds = {};
    existingData.forEach(function(r) { existingIds[String(r[8]).trim()] = true; });
    dataRows = dataRows.filter(function(r) { return !existingIds[String(r[8]).trim()]; });
    if (dataRows.length === 0) return;
  }
  if (info.isNew) {
    sheet.getRange(1, 1, 1, dataCols).setValues([rows[0]]);
    headerStyle(sheet.getRange(1, 1, 1, dataCols));
    sheet.getRange(1, closeCol).setValue("Close");
    headerStyle(sheet.getRange(1, closeCol));
    [85, 130, 130, 280, 120, 100, 180, 1, 1, 55].forEach(function(w, i) { sheet.setColumnWidth(i + 1, w); });
    sheet.setFrozenRows(1);
    sheet.getRange(2, 1, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 1, dataRows.length, dataCols).setValues(dataRows);
    sheet.getRange(2, closeCol, dataRows.length, 1).insertCheckboxes();
  } else {
    sheet.insertRowsAfter(1, dataRows.length);
    sheet.getRange(2, 1, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 1, dataRows.length, dataCols).setValues(dataRows);
    sheet.getRange(2, closeCol, dataRows.length, 1).insertCheckboxes();
  }
  var lastRow = sheet.getLastRow();
  if (lastRow > 2) {
    sheet.getRange(2, 1, lastRow - 1, dataCols).sort({column: 9, ascending: false});
  }
}

function buildSafetyTab(ss, rows) {
  var info = getSheet(ss, "Safety");
  var sheet = info.sheet;
  if (rows.length < 2) { if (info.isNew) sheet.getRange("A1").setValue("No open safety issues."); return; }
  var dataCols = rows[0].length;
  var closeCol = dataCols + 1;
  var dataRows = rows.slice(1);
  if (!info.isNew && sheet.getLastRow() > 1) {
    var existingData = sheet.getRange(2, 1, sheet.getLastRow() - 1, dataCols).getValues();
    var existingIds = {};
    existingData.forEach(function(r) { existingIds[String(r[9]).trim()] = true; });
    dataRows = dataRows.filter(function(r) { return !existingIds[String(r[9]).trim()]; });
    if (dataRows.length === 0) return;
  }
  if (info.isNew) {
    sheet.getRange(1, 1, 1, dataCols).setValues([rows[0]]);
    headerStyle(sheet.getRange(1, 1, 1, dataCols));
    sheet.getRange(1, closeCol).setValue("Close");
    headerStyle(sheet.getRange(1, closeCol));
    [85, 130, 160, 250, 100, 120, 75, 85, 1, 1, 55].forEach(function(w, i) { sheet.setColumnWidth(i + 1, w); });
    sheet.setFrozenRows(1);
    sheet.getRange(2, 1, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 8, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 1, dataRows.length, dataCols).setValues(dataRows);
    for (var i = 0; i < dataRows.length; i++) {
      if (dataRows[i][6] === "High") {
        sheet.getRange(2 + i, 1, 1, dataCols).setBackground("#fce4e4");
      }
    }
    sheet.getRange(2, closeCol, dataRows.length, 1).insertCheckboxes();
  } else {
    sheet.insertRowsAfter(1, dataRows.length);
    sheet.getRange(2, 1, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 8, dataRows.length, 1).setNumberFormat("@");
    sheet.getRange(2, 1, dataRows.length, dataCols).setValues(dataRows);
    for (var i = 0; i < dataRows.length; i++) {
      if (dataRows[i][6] === "High") {
        sheet.getRange(2 + i, 1, 1, dataCols).setBackground("#fce4e4");
      }
    }
    sheet.getRange(2, closeCol, dataRows.length, 1).insertCheckboxes();
  }
  var lastRow = sheet.getLastRow();
  if (lastRow > 2) {
    sheet.getRange(2, 1, lastRow - 1, dataCols).sort({column: 10, ascending: false});
  }
}

function fmtTime(t) {
  if (!t) return "";
  var parts = String(t).split(":");
  var h = parseInt(parts[0], 10), m = parts[1] || "00";
  return (h % 12 || 12) + ":" + m + (h >= 12 ? "pm" : "am");
}

function buildDashboardTab(ss, staffRows, escRows, safetyRows) {
  var sheet = getOrCreate(ss, "Dashboard");

  if (staffRows.length < 2) {
    sheet.getRange("A1").setValue("No staff reports for today.");
    return;
  }

  var sHdr = staffRows[0];
  var sCol = {};
  sHdr.forEach(function(h, i) { sCol[h] = i; });

  var escByUnit = {}, eCol = {};
  if (escRows.length > 1) {
    escRows[0].forEach(function(h, i) { eCol[h] = i; });
    for (var i = 1; i < escRows.length; i++) {
      var u = escRows[i][eCol["Unit"]] || "";
      if (!escByUnit[u]) escByUnit[u] = [];
      escByUnit[u].push(escRows[i]);
    }
  }

  var safeByUnit = {}, sfCol = {};
  if (safetyRows.length > 1) {
    safetyRows[0].forEach(function(h, i) { sfCol[h] = i; });
    for (var i = 1; i < safetyRows.length; i++) {
      var u = safetyRows[i][sfCol["Unit"]] || "";
      if (!safeByUnit[u]) safeByUnit[u] = [];
      safeByUnit[u].push(safetyRows[i]);
    }
  }

  var dashHdr  = ["Type","Unit","Date","Submitted (Scheduled)","Variance","RN Count","Staff Count","Pt Count","Notes","Item ID","Close"];
  var NUM_COLS  = dashHdr.length;
  var UNIT_COL  = 2;
  var CLOSE_COL = 11;

  // Pick the most recently started shift for each unit that has already begun
  var today   = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd");
  var nowTime = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "HH:mm");

  var currentByUnit = {};
  for (var i = 1; i < staffRows.length; i++) {
    var r         = staffRows[i];
    var sortDate  = String(r[sCol["Sort Date"]] || "").trim();
    if (sortDate !== today) continue;
    var u         = r[sCol["Unit"]] || "";
    var shiftTime = String(r[sCol["Shift Time"]] || "00:00").trim();
    if (shiftTime > nowTime) continue; // shift hasn't started yet
    var existing  = currentByUnit[u];
    if (!existing || shiftTime > String(existing[sCol["Shift Time"]] || "00:00").trim()) {
      currentByUnit[u] = r;
    }
  }
  var dedupedStaff = Object.keys(currentByUnit).sort().map(function(u) { return currentByUnit[u]; });

  var data = [dashHdr], unitRowMap = [], rowTypes = ["header"], checkboxRows = [], coveredUnits = {};

  for (var i = 0; i < dedupedStaff.length; i++) {
    var r        = dedupedStaff[i];
    var unit     = r[sCol["Unit"]] || "";
    var status   = (sCol["Status"] !== undefined ? r[sCol["Status"]] : "") || "";
    var reportId = r[sCol["Report ID"]] || "";
    var isNoReport = (status === "No Report" || !reportId);
    var isLate     = (status === "Late");

    coveredUnits[unit] = true;

    var submitTime = r[sCol["Submit Time"]] || "";
    var shiftName  = r[sCol["Shift"]] || "";
    var shiftTime  = r[sCol["Shift Time"]] || "";
    var submitted  = submitTime ? submitTime + (shiftName ? " (" + shiftName + ")" : "") : shiftName;
    var scheduled  = shiftTime ? "no report (" + fmtTime(shiftTime) + ")" : "no report";

    data.push([
      isNoReport ? "NO REPORT" : "REPORT", unit, r[sCol["Date"]] || "",
      isNoReport ? scheduled : submitted,
      isNoReport ? "" : (r[sCol["Variance"]] || ""),
      isNoReport ? "" : (r[sCol["RN Count"]] || ""),
      isNoReport ? "" : (r[sCol["Staff Count"]] || ""),
      isNoReport ? "" : (r[sCol["Pt Count"]] || ""),
      isNoReport ? "No report submitted" : (r[sCol["Notes"]] || ""),
      "", ""
    ]);
    unitRowMap.push({dashRow: data.length, reportId: reportId, unit: unit});
    rowTypes.push(isNoReport ? "noreport" : (isLate ? "late" : "report"));

    (escByUnit[unit] || []).forEach(function(e) {
      data.push(["ESC", unit, e[eCol["Type"]]||"", e[eCol["Date"]]||"", "", "", "", "",
                 e[eCol["Notes"]]||"", e[eCol["Escalation ID"]]||"", ""]);
      checkboxRows.push(data.length);
      rowTypes.push("esc");
    });

    (safeByUnit[unit] || []).forEach(function(s) {
      var label = (s[sfCol["Issue Type"]]||"") + (s[sfCol["Priority"]]==="High" ? "  ⚠" : "");
      data.push(["SAFETY", unit, label, s[sfCol["Date"]]||"", "", "", "", "",
                 s[sfCol["Description"]]||"", s[sfCol["Safety ID"]]||"", ""]);
      checkboxRows.push(data.length);
      rowTypes.push("safety");
    });
  }

  var allUnits = {};
  Object.keys(escByUnit).concat(Object.keys(safeByUnit)).forEach(function(u) { allUnits[u] = true; });
  Object.keys(allUnits).forEach(function(unit) {
    if (coveredUnits[unit]) return;
    (escByUnit[unit] || []).forEach(function(e) {
      data.push(["ESC", unit, e[eCol["Type"]]||"", e[eCol["Date"]]||"", "", "", "", "",
                 e[eCol["Notes"]]||"", e[eCol["Escalation ID"]]||"", ""]);
      checkboxRows.push(data.length);
      rowTypes.push("esc");
    });
    (safeByUnit[unit] || []).forEach(function(s) {
      var label = (s[sfCol["Issue Type"]]||"") + (s[sfCol["Priority"]]==="High" ? "  ⚠" : "");
      data.push(["SAFETY", unit, label, s[sfCol["Date"]]||"", "", "", "", "",
                 s[sfCol["Description"]]||"", s[sfCol["Safety ID"]]||"", ""]);
      checkboxRows.push(data.length);
      rowTypes.push("safety");
    });
  });

  sheet.getRange(2, 3, data.length - 1, 1).setNumberFormat("@");
  sheet.getRange(1, 1, data.length, NUM_COLS).setValues(data);

  checkboxRows.forEach(function(row) {
    sheet.getRange(row, CLOSE_COL).insertCheckboxes();
  });

  unitRowMap.forEach(function(v) {
    if (!v.reportId) return;
    var url = CONFIG.baseUrl + "/export/shift-detail.php?token=" + CONFIG.token + "&id=" + v.reportId;
    sheet.getRange(v.dashRow, UNIT_COL).setFormula('=HYPERLINK("' + url + '","' + v.unit + '")');
  });

  headerStyle(sheet.getRange(1, 1, 1, NUM_COLS));
  sheet.setFrozenRows(1);

  for (var row = 2; row <= data.length; row++) {
    var type  = rowTypes[row - 1];
    var range = sheet.getRange(row, 1, 1, NUM_COLS);
    if      (type === "report")   { range.setBackground("#e8f0fe").setFontWeight("bold"); }
    else if (type === "late")     { range.setBackground("#fff3e0").setFontWeight("bold"); sheet.getRange(row,1).setFontColor("#e65100"); }
    else if (type === "noreport") { range.setBackground("#f5f5f5").setFontWeight("bold"); sheet.getRange(row,1).setFontColor("#999999"); }
    else if (type === "esc")      { range.setBackground("#fff8e1"); sheet.getRange(row,1).setFontColor("#e65100"); }
    else if (type === "safety")   { range.setBackground("#fce4e4"); sheet.getRange(row,1).setFontColor("#b71c1c"); }
  }

  sheet.getRange(2, 4, data.length - 1, 1).setHorizontalAlignment("right");
  var dashWidths = [90, 130, 85, 160, 75, 75, 80, 75, 280, 1, 55];
  dashWidths.forEach(function(w, i) { sheet.setColumnWidth(i + 1, w); });
}

function handleEdit(e) {
  var sheet = e.range.getSheet();
  var col   = e.range.getColumn();
  var row   = e.range.getRow();
  if (row === 1 || e.value !== "TRUE") return;

  var sheetName = sheet.getName();
  var itemId, itemType;

  if (sheetName === "Dashboard" && col === 11) {
    var type = sheet.getRange(row, 1).getValue();
    if (type !== "ESC" && type !== "SAFETY") { e.range.setValue(false); return; }
    itemId   = sheet.getRange(row, 10).getValue();
    itemType = type === "ESC" ? "esc" : "safety";
  } else if (sheetName === "Escalations" && col === 10) {
    itemId   = sheet.getRange(row, 9).getValue();
    itemType = "esc";
  } else if (sheetName === "Safety" && col === 11) {
    itemId   = sheet.getRange(row, 10).getValue();
    itemType = "safety";
  } else {
    return;
  }

  if (!itemId) return;

  var url = CONFIG.baseUrl + "/export/close.php?token=" + CONFIG.token + "&type=" + itemType + "&id=" + itemId;
  try {
    var result = JSON.parse(UrlFetchApp.fetch(url, {muteHttpExceptions: true}).getContentText());
    if (result.success) {
      sheet.hideRows(row);
    } else {
      e.range.setValue(false);
      SpreadsheetApp.getUi().alert("Could not close: " + (result.message || "Unknown error"));
    }
  } catch(err) {
    e.range.setValue(false);
    SpreadsheetApp.getUi().alert("Network error. Please try again.");
  }
}

function setupTriggers() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  ScriptApp.getProjectTriggers().forEach(function(t) { ScriptApp.deleteTrigger(t); });
  ScriptApp.newTrigger("runExport").forSpreadsheet(ss).onOpen().create();
  ScriptApp.newTrigger("handleEdit").forSpreadsheet(ss).onEdit().create();
  ScriptApp.newTrigger("runExport").timeBased().everyDays(1).atHour(6).create();
  SpreadsheetApp.getUi().alert("Done. onOpen, daily 6am, and handleEdit triggers are active.");
}`;
    }

    $('#btnCreateToken').on('click', function() {
        $('#createTokenForm').show();
        $('#tokenLabel').focus();
    });

    $('#btnCancelToken').on('click', function() {
        $('#createTokenForm').hide();
        $('#tokenLabel').val('');
    });

    $('#btnSaveToken').on('click', function() {
        var label = $('#tokenLabel').val().trim();
        if (!label) { alert('Enter a label first.'); return; }
        $.post('export/token-mgmt.php', {action:'create', label:label}, function(res) {
            if (res.success) {
                $('#createTokenForm').hide();
                $('#tokenLabel').val('');
                loadTokens();
                setTimeout(function(){ showScript(res.token); }, 300);
            }
        });
    });

    $(document).on('click', '.btn-revoke', function() {
        if (!confirm('Revoke this token? The Apps Script using it will stop working.')) return;
        var id = $(this).data('id');
        $.post('export/token-mgmt.php', {action:'revoke', id:id}, function(res) {
            if (res.success) loadTokens();
        });
    });

    $(document).on('click', '.btn-script', function() {
        showScript($(this).data('token'));
    });

    $('#btnCopyScript').on('click', function() {
        var text = $('#appsScriptCode').text();
        navigator.clipboard.writeText(text).then(function() {
            $('#btnCopyScript').text('Copied!');
            setTimeout(function(){ $('#btnCopyScript').text('Copy'); }, 2000);
        });
    });

    loadTokens();
})();
</script>
