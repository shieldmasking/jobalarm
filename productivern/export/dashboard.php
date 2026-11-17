<?php
require_once __DIR__ . '/../inc/initializer.php';
require_once __DIR__ . '/../inc/class.db.php';
require_once __DIR__ . '/../inc/config.php';

if (!isset($_SESSION['account']) || intval($_SESSION['account']['id']) !== 1799) {
    http_response_code(403); exit('<p style="font-family:sans-serif;text-align:center;margin-top:60px;">Access denied.</p>');
}

$db        = Config::get('db');
$accountId = intval($_SESSION['account']['accountId']);
$today     = date('Y-m-d');

// Staff reports — today only
$staffRows = $db->get_results("
    SELECT
        DATE_FORMAT(n.dayDate,'%Y-%m-%d') as date,
        d.dept                            as unit,
        s.shift                           as shiftName,
        ROUND(n.nvariance,2)              as variance,
        ROUND(n.hrsVariance,2)            as hrsVariance,
        ROUND((n.antecount*d.rnCount + n.customNurse*d.rn1Count + n.customNurse2*d.rn2Count),1) as rnCount,
        ROUND((n.chargecount*d.staff1 + n.antecount*d.staff2 + n.customNurse*d.staff3
             + n.customNurse2*d.staff4 + n.seccount*d.staff5 + n.techcount*d.staff6
             + n.otherNurse1*d.staff7 + n.sittercount*d.staff8
             + n.otherNurse2*d.staff9 + n.otherNurse3*d.staff10),1) as staffCount,
        CASE WHEN n.visits>0 THEN n.visits ELSE n.procedureCount END as ptCount,
        IFNULL(n.note,'') as notes,
        ROUND(n.nproductivity,1) as productivity,
        TRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,''))) as chargeNurse,
        n.antecount, n.customNurse, n.customNurse2, n.chargecount,
        n.techcount, n.seccount, n.sittercount, n.otherNurse1, n.otherNurse2, n.otherNurse3,
        IFNULL(d.skilldesc1,'') as sd1,  IFNULL(d.skilldesc2,'') as sd2,
        IFNULL(d.skilldesc3,'') as sd3,  IFNULL(d.skilldesc4,'') as sd4,
        IFNULL(d.skilldesc5,'') as sd5,  IFNULL(d.skilldesc6,'') as sd6,
        IFNULL(d.skilldesc7,'') as sd7,  IFNULL(d.skilldesc8,'') as sd8,
        IFNULL(d.skilldesc9,'') as sd9,  IFNULL(d.skilldesc10,'') as sd10,
        IFNULL(n.skill1val,0) as sv1,  IFNULL(n.skill2val,0) as sv2,
        IFNULL(n.skill3val,0) as sv3,  IFNULL(n.skill4val,0) as sv4,
        IFNULL(n.skill5val,0) as sv5,  IFNULL(n.skill6val,0) as sv6,
        IFNULL(n.skill7val,0) as sv7,  IFNULL(n.skill8val,0) as sv8,
        IFNULL(n.skill9val,0) as sv9,  IFNULL(n.skill10val,0) as sv10,
        n.id as reportId, n.deptId
    FROM productiveNewData n
    LEFT JOIN ProductiveDept   d ON d.id = n.deptId
    LEFT JOIN productiveShifts s ON s.id = n.shift
    LEFT JOIN productiveUser   u ON u.id = n.userId
    WHERE n.accountId = {$accountId}
      AND n.dayDate = '{$today}'
      AND n.activeRecord > 0
    ORDER BY d.dept ASC, n.shift ASC
") ?: [];

// Open escalations — all active
$escRows = $db->get_results("
    SELECT
        DATE_FORMAT(e.dateSubmitted,'%m/%d/%Y') as date,
        IFNULL(d.dept,'')     as unit,
        IFNULL(a.escalation,'') as type,
        IFNULL(e.note,'')     as notes,
        TRIM(CONCAT(IFNULL(u.first_name,''),' ',IFNULL(u.last_name,''))) as submittedBy,
        IFNULL(e.response,'') as response,
        e.deptId, e.id as escId
    FROM productiveEscalations e
    LEFT JOIN ProductiveDept            d ON d.id = e.deptId
    LEFT JOIN productiveAcctEscalations a ON a.id = e.escalation
    LEFT JOIN productiveUser            u ON u.id = e.userId
    WHERE e.accountId = {$accountId} AND e.active = 1
    ORDER BY e.dateSubmitted DESC
") ?: [];

// Open safety issues — all active
$safetyRows = $db->get_results("
    SELECT
        DATE_FORMAT(s.openedDate,'%m/%d/%Y') as date,
        COALESCE(d.dept,'Hospital') as unit,
        CONCAT(IFNULL(t.typeDesc,''),' - ',IFNULL(c.safetyDesc,'')) as issueType,
        IFNULL(s.note1,'') as description,
        IFNULL(s.roomId1,'') as location,
        IFNULL(s.periGen1,'') as submittedBy,
        s.priority,
        CASE WHEN s.dueDate IS NOT NULL AND s.dueDate!='0000-00-00'
             THEN DATE_FORMAT(s.dueDate,'%m/%d/%Y') ELSE '' END as dueDate,
        s.deptId, s.id as safetyId
    FROM productiveSafety s
    LEFT JOIN ProductiveDept         d ON d.id = s.deptId
    LEFT JOIN productivesafetyConfig c ON c.id = s.safetyConfig
    LEFT JOIN productivesafetyType   t ON t.id = c.safetyType
    WHERE s.accountId = {$accountId} AND s.active = 1
    ORDER BY s.priority DESC, s.openedDate DESC
") ?: [];

// Index esc and safety by deptId for dashboard grouping
$escByDept    = [];
foreach ($escRows    as $r) $escByDept[intval($r['deptId'])][]    = $r;
$safetyByDept = [];
foreach ($safetyRows as $r) $safetyByDept[intval($r['deptId'])][] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Staffing Dashboard — <?php echo date('M j, Y'); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
:root { --navy:#1a4a8a; }
body  { background:#f0f2f5; font-size:.875rem; }
.page-header { background:var(--navy); color:#fff; padding:14px 24px; display:flex; align-items:center; justify-content:space-between; }
.page-header h1 { font-size:1.1rem; margin:0; }
.page-header small { opacity:.7; font-size:.8rem; }
.tab-nav { background:#fff; border-bottom:2px solid var(--navy); padding:0 16px; position:sticky; top:0; z-index:10; }
.tab-nav .nav-link { color:#555; padding:10px 18px; border:none; border-bottom:3px solid transparent; border-radius:0; }
.tab-nav .nav-link.active { color:var(--navy); border-bottom-color:var(--navy); font-weight:600; }
.tab-content { padding:16px; }

/* Dashboard tab */
.report-row  { background:#e8f0fe; font-weight:600; }
.esc-row     { background:#fff8e1; }
.safety-row  { background:#fce4e4; }
.safety-row.high { background:#f8d7da; }
.badge-esc    { background:#e65100; color:#fff; font-size:.7rem; padding:2px 7px; border-radius:10px; }
.badge-safety { background:#b71c1c; color:#fff; font-size:.7rem; padding:2px 7px; border-radius:10px; }
.badge-high   { background:#c62828; color:#fff; font-size:.68rem; padding:1px 5px; border-radius:8px; margin-left:4px; }
.variance-link { color:var(--navy); text-decoration:underline dotted; cursor:pointer; font-weight:700; }

th { background:var(--navy); color:#fff; white-space:nowrap; }
.table thead th { border-bottom:none; }
.card-count { font-size:1.6rem; font-weight:700; color:var(--navy); }

/* Detail modal */
.modal-header { background:var(--navy); color:#fff; }
.skill-row td:first-child { color:#888; font-size:.8rem; }
</style>
</head>
<body>

<div class="page-header">
    <h1>Staffing Dashboard <small>— Google Sheets Preview</small></h1>
    <small><?php echo date('l, F j, Y'); ?> &nbsp;|&nbsp; <?php echo htmlspecialchars($_SESSION['account']['company'] ?? ''); ?></small>
</div>

<ul class="nav tab-nav" id="mainTabs">
    <li class="nav-item"><a class="nav-link active" data-tab="dashboard" href="#">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" data-tab="detail" href="#">Detail</a></li>
    <li class="nav-item"><a class="nav-link" data-tab="escalations" href="#">Escalations <span class="badge badge-warning text-dark"><?php echo count($escRows); ?></span></a></li>
    <li class="nav-item"><a class="nav-link" data-tab="safety" href="#">Safety <span class="badge badge-danger"><?php echo count($safetyRows); ?></span></a></li>
</ul>

<!-- ===== DASHBOARD TAB ===== -->
<div class="tab-content" id="tab-dashboard">
<?php if (empty($staffRows)): ?>
    <div class="alert alert-info mt-3">No staff reports submitted today yet.</div>
<?php else: ?>
    <table class="table table-bordered table-sm bg-white">
        <thead><tr>
            <th>Type</th><th>Unit</th><th>Shift</th><th>Date</th>
            <th>Variance</th><th>RN Count</th><th>Staff Count</th><th>Pt Count</th><th>Notes</th>
        </tr></thead>
        <tbody>
        <?php foreach ($staffRows as $i => $r):
            $deptId = intval($r['deptId']);
            $escs   = $escByDept[$deptId]    ?? [];
            $safes  = $safetyByDept[$deptId] ?? [];
        ?>
            <tr class="report-row">
                <td><span class="badge badge-primary">REPORT</span></td>
                <td><?php echo htmlspecialchars($r['unit']); ?></td>
                <td><?php echo htmlspecialchars($r['shiftName']); ?></td>
                <td><?php echo htmlspecialchars($r['date']); ?></td>
                <td>
                    <a class="variance-link" href="#" data-detail="<?php echo $i; ?>" data-toggle="modal" data-target="#detailModal">
                        <?php echo htmlspecialchars($r['variance']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($r['rnCount']); ?></td>
                <td><?php echo htmlspecialchars($r['staffCount']); ?></td>
                <td><?php echo htmlspecialchars($r['ptCount']); ?></td>
                <td><?php echo htmlspecialchars($r['notes']); ?></td>
            </tr>
            <?php foreach ($escs as $e): ?>
            <tr class="esc-row" id="esc-<?php echo $e['escId']; ?>">
                <td><span class="badge-esc">ESC</span></td>
                <td><?php echo htmlspecialchars($e['unit']); ?></td>
                <td colspan="2" style="color:#555;"><?php echo htmlspecialchars($e['type']); ?> &mdash; <?php echo htmlspecialchars($e['date']); ?></td>
                <td colspan="3" style="color:#333;"><?php echo htmlspecialchars($e['notes']); ?>
                    <?php if ($e['submittedBy']): ?><small class="text-muted ml-2">by <?php echo htmlspecialchars($e['submittedBy']); ?></small><?php endif; ?>
                </td>
                <td><button class="btn btn-xs btn-outline-danger btn-close-esc" data-id="<?php echo $e['escId']; ?>">Close</button></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($safes as $s): ?>
            <tr class="safety-row <?php echo $s['priority']==1 ? 'high' : ''; ?>" id="safety-<?php echo $s['safetyId']; ?>">
                <td><span class="badge-safety">SAFETY</span><?php if($s['priority']==1): ?><span class="badge-high">HIGH</span><?php endif; ?></td>
                <td><?php echo htmlspecialchars($s['unit']); ?></td>
                <td colspan="2" style="color:#555;"><?php echo htmlspecialchars($s['issueType']); ?> &mdash; <?php echo htmlspecialchars($s['date']); ?></td>
                <td colspan="3" style="color:#333;"><?php echo htmlspecialchars($s['description']); ?>
                    <?php if ($s['location']): ?><small class="text-muted ml-2"><?php echo htmlspecialchars($s['location']); ?></small><?php endif; ?>
                </td>
                <td><button class="btn btn-xs btn-outline-danger btn-close-safety" data-id="<?php echo $s['safetyId']; ?>">Close</button></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<!-- ===== DETAIL TAB ===== -->
<div class="tab-content" id="tab-detail" style="display:none;">
<?php if (empty($staffRows)): ?>
    <div class="alert alert-info mt-3">No data.</div>
<?php else: ?>
    <table class="table table-bordered table-sm bg-white" style="font-size:.8rem;">
        <thead><tr>
            <th>Unit</th><th>Shift</th><th>Date</th><th>Variance</th>
            <th>RN</th><th>Staff</th><th>Pt</th><th>Productivity%</th><th>Charge Nurse</th>
            <?php
            // Show skill columns that have a label in any row
            $skillCols = [];
            for ($k=1;$k<=10;$k++) {
                foreach ($staffRows as $r) {
                    if (!empty($r["sd{$k}"])) { $skillCols[] = $k; break; }
                }
            }
            foreach ($skillCols as $k) echo "<th>" . htmlspecialchars($staffRows[0]["sd{$k}"]) . "</th>";
            ?>
        </tr></thead>
        <tbody>
        <?php foreach ($staffRows as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['unit']); ?></td>
                <td><?php echo htmlspecialchars($r['shiftName']); ?></td>
                <td><?php echo htmlspecialchars($r['date']); ?></td>
                <td><?php echo htmlspecialchars($r['variance']); ?></td>
                <td><?php echo htmlspecialchars($r['rnCount']); ?></td>
                <td><?php echo htmlspecialchars($r['staffCount']); ?></td>
                <td><?php echo htmlspecialchars($r['ptCount']); ?></td>
                <td><?php echo htmlspecialchars($r['productivity']); ?>%</td>
                <td><?php echo htmlspecialchars($r['chargeNurse']); ?></td>
                <?php foreach ($skillCols as $k): ?>
                    <td><?php echo htmlspecialchars($r["sv{$k}"]); ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<!-- ===== ESCALATIONS TAB ===== -->
<div class="tab-content" id="tab-escalations" style="display:none;">
<?php if (empty($escRows)): ?>
    <div class="alert alert-success mt-3">No open escalations.</div>
<?php else: ?>
    <table class="table table-bordered table-sm bg-white">
        <thead><tr><th>Date</th><th>Unit</th><th>Type</th><th>Notes</th><th>Submitted By</th><th>Response</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($escRows as $e): ?>
            <tr id="esc-<?php echo $e['escId']; ?>">
                <td><?php echo htmlspecialchars($e['date']); ?></td>
                <td><?php echo htmlspecialchars($e['unit']); ?></td>
                <td><?php echo htmlspecialchars($e['type']); ?></td>
                <td><?php echo htmlspecialchars($e['notes']); ?></td>
                <td><?php echo htmlspecialchars($e['submittedBy']); ?></td>
                <td><?php echo htmlspecialchars($e['response']); ?></td>
                <td><button class="btn btn-sm btn-outline-danger btn-close-esc" data-id="<?php echo $e['escId']; ?>">Close</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<!-- ===== SAFETY TAB ===== -->
<div class="tab-content" id="tab-safety" style="display:none;">
<?php if (empty($safetyRows)): ?>
    <div class="alert alert-success mt-3">No open safety issues.</div>
<?php else: ?>
    <table class="table table-bordered table-sm bg-white">
        <thead><tr><th>Date</th><th>Unit</th><th>Issue Type</th><th>Priority</th><th>Description</th><th>Location</th><th>Submitted By</th><th>Due Date</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($safetyRows as $s): ?>
            <tr class="<?php echo $s['priority']==1 ? 'table-danger' : ''; ?>" id="safety-<?php echo $s['safetyId']; ?>">
                <td><?php echo htmlspecialchars($s['date']); ?></td>
                <td><?php echo htmlspecialchars($s['unit']); ?></td>
                <td><?php echo htmlspecialchars($s['issueType']); ?></td>
                <td><?php echo $s['priority']==1 ? '<strong class="text-danger">High</strong>' : 'Normal'; ?></td>
                <td><?php echo htmlspecialchars($s['description']); ?></td>
                <td><?php echo htmlspecialchars($s['location']); ?></td>
                <td><?php echo htmlspecialchars($s['submittedBy']); ?></td>
                <td><?php echo htmlspecialchars($s['dueDate']); ?></td>
                <td><button class="btn btn-sm btn-outline-danger btn-close-safety" data-id="<?php echo $s['safetyId']; ?>">Close</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<!-- Detail modal (variance drill-down) -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Shift Detail</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailModalBody"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var staffData = <?php echo json_encode($staffRows); ?>;

// Tab switching
$('.nav-link[data-tab]').on('click', function(e) {
    e.preventDefault();
    $('.nav-link').removeClass('active');
    $(this).addClass('active');
    $('.tab-content').hide();
    $('#tab-' + $(this).data('tab')).show();
});

// Variance drill-down modal
$(document).on('click', '.variance-link', function(e) {
    e.preventDefault();
    var idx = parseInt($(this).data('detail'));
    var r   = staffData[idx];
    if (!r) return;

    var skills = '';
    for (var k = 1; k <= 10; k++) {
        var label = r['sd' + k];
        var val   = parseFloat(r['sv' + k]);
        if (label && val > 0) {
            skills += '<tr><td>' + $('<span>').text(label).html() + '</td><td>' + val + ' hrs</td></tr>';
        }
    }

    var html =
        '<h6 class="mb-3">' + r.unit + ' &mdash; ' + r.shiftName + ' &mdash; ' + r.date + '</h6>' +
        '<div class="row mb-3">' +
            '<div class="col-3 text-center"><div class="card-count">' + r.variance + '</div><small class="text-muted">Variance</small></div>' +
            '<div class="col-3 text-center"><div class="card-count">' + r.rnCount + '</div><small class="text-muted">RN Count</small></div>' +
            '<div class="col-3 text-center"><div class="card-count">' + r.staffCount + '</div><small class="text-muted">Staff Count</small></div>' +
            '<div class="col-3 text-center"><div class="card-count">' + r.ptCount + '</div><small class="text-muted">Pt Count</small></div>' +
        '</div>' +
        (skills ? '<h6>Staff Breakdown</h6><table class="table table-sm skill-row"><tbody>' + skills + '</tbody></table>' : '') +
        (r.notes ? '<p><strong>Notes:</strong> ' + $('<span>').text(r.notes).html() + '</p>' : '') +
        (r.chargeNurse.trim() ? '<p><strong>Charge Nurse:</strong> ' + $('<span>').text(r.chargeNurse).html() + '</p>' : '');

    $('#detailModalBody').html(html);
    $('#detailModal').modal('show');
});

function closeItem(action, id) {
    $.post('', {action: action, id: id}, function(res) {
        if (res.success) {
            var prefix = action === 'close_esc' ? 'esc' : 'safety';
            $('tr#' + prefix + '-' + id).fadeOut(300, function(){ $(this).remove(); });
        }
    });
}
$(document).on('click', '.btn-close-esc', function() {
    if (!confirm('Close this escalation?')) return;
    closeItem('close_esc', $(this).data('id'));
});
$(document).on('click', '.btn-close-safety', function() {
    if (!confirm('Close this safety issue?')) return;
    closeItem('close_safety', $(this).data('id'));
});
</script>
</body>
</html>
