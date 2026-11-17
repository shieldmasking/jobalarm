<?php
ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
session_start();

include_once '../inc/class.db.php';
include_once '../inc/config.php';

$login_error = 0;
$dataId = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : 0;
$dataId2 = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : 0;
$dataId3 = (isset($_REQUEST['c'])) ? $_REQUEST['c'] : 0;
$dataId4 = (isset($_REQUEST['d'])) ? $_REQUEST['d'] : 0;
$dataId5 = (isset($_REQUEST['y'])) ? $_REQUEST['y'] : 0;
$dataId6 = (isset($_REQUEST['z'])) ? $_REQUEST['z'] : 0;

if (isset($_POST['sitelogin'])) {

        $imail = htmlspecialchars($_POST['username']);
		$email = addslashes($imail);
        $rawPassword = $_POST['password'];
        $password = md5($rawPassword);
		$p = (isset($_POST['page'])) ? $_POST['page'] : 0;
		$p2 = (isset($_POST['page2'])) ? $_POST['page2'] : 0;
		$p3 = (isset($_POST['page3'])) ? $_POST['page3'] : 0;
		$p4 = (isset($_POST['page4'])) ? $_POST['page4'] : 0;
		$p5 = (isset($_POST['page5'])) ? $_POST['page5'] : 0;
		$p6 = (isset($_POST['page6'])) ? $_POST['page6'] : 0;
        if (intval($p) > 0) {
            $header = 'location: /dashboard.php#reportView?i=' . $p . '';
        }elseif (intval($p3) >0  && intval($p5) >0) {
            $header = 'location: /dashboard.php#staffing?c=' . $p3 . '';
        }elseif (intval($p3) >0  && intval($p6) >0) {
            $header = 'location: /dashboard.php#supportstaffing?c=' . $p3 . '';
        }elseif (intval($p4) >0) {
            $header = 'location: /dashboard.php#escalations';
        }else {
            $header = 'location: /dashboard.php';
        }

	$query = "SELECT u.*, r.label, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY)),0) as newStartPay, IFNULL(DATEDIFF(CURDATE(),DATE_ADD(a.payPeriodFirst, INTERVAL (FLOOR(DATEDIFF(CURDATE(),a.payPeriodFirst)/a.payPeriod)*a.payPeriod) DAY))-(a.payPeriod-1),0) as newEndPay, UNIX_TIMESTAMP() as login_time, e.image as enterpriselogo, a.useSafety, a.acctEscalation, a.trainingVids, a.transferPortal, a.enterpriseId, a.dashTerm, a.prodTerm, a.hrsTerm, (SELECT COUNT(`deptId`) FROM `productiveDeptXref` WHERE `userId`=u.id AND `primaryUnit`>0) as countDept, (SELECT COUNT(`id`) FROM `ProductiveDept` WHERE d.active =1 AND `id` IN (SELECT `deptId` from `productiveDeptXref` where `userId`=u.id AND `primaryUnit`>0)) as activeDept, IFNULL(d.prodMeasure,1) as prodMeasure, d.id as deptNum, d.rnVariance, d.indMeasure, a.logs, a.eas, d.useDayRank, a.dashboardSort, a.dayRank, a.productivityPosNeg, IFNULL(a.payPeriod,0) as payPeriod, DAYOFYEAR(curdate()) as currentDay, curdate() as todayDate, DAYOFYEAR(a.payPeriodFirst) as firstDay, COUNT(DISTINCT d.prodMeasure) as prodDistinct, a.payPeriodFirst as startPay, IFNULL(a.image,'') as image, count(IFNULL(d.prodMeasure,1)) as prodCount, a.type, a.status, a.displayProd, a.name as company, a.timeZone, IFNULL(l.login,'') as labelLogin, IFNULL(l.logout,'') as labelLogout, IFNULL(l.id,'') as labelId, IFNULL(l.logo,'') as logo, IFNULL(l.favicon,'') as favicon, IFNULL(l.labelName,'') as labelName, l.labelName as labelTitle, IFNULL(l.supportEmail,'') as supportEmail, IFNULL(l.supportLink,'') as supportLink, IFNULL(l.videoLink,'') as videoLink, d.dept as deptName, d.secLabel, d.unitId, a.active as acctActive FROM `productiveUser` u LEFT OUTER JOIN `productiveDeptXref` as x on x.userId = u.id LEFT JOIN `productiveUserRoles` as r on r.id=u.role LEFT OUTER JOIN `ProductiveDept` as d on d.id = x.deptId LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT OUTER JOIN `productiveEnterprise` as e on e.id = a.enterpriseId LEFT JOIN `productiveLabel` as l on l.id = a.label WHERE (u.email='{$email}' OR u.userName='{$email}') GROUP BY u.id";

        $dbData = Config::get('db')->get_results($query);
        $storedHash = isset($dbData[0]['pwd']) ? $dbData[0]['pwd'] : '';
        $passwordValid = false;
        if ($dbData && $storedHash) {
            if (strpos($storedHash, '$2y$') === 0) {
                $passwordValid = password_verify($rawPassword, $storedHash);
            } else {
                $passwordValid = ($password === $storedHash);
                if ($passwordValid) {
                    $newHash = password_hash($rawPassword, PASSWORD_BCRYPT);
                    Config::get('db')->update('productiveUser', array('pwd' => $newHash), array('id' => $dbData[0]['id']), 1);
                    $dbData[0]['pwd'] = $newHash;
                }
            }
        }
        if ($passwordValid) {
           $_SESSION['account'] = $dbData[0];
            $now = time() - (3 + intval($dbData[0]['timeZone'])) * 60 *60;
            $data = array('lastlogin' => date("Y-m-d H:i:s", $now));
            $where = array('id' => $dbData[0]['id']);
            Config::get('db')->update('productiveUser', $data, $where, 1);
			if(intval($dbData[0]['id'])==1679){
				$role=11;
			}else{
				$role=$dbData[0]['role'];
			}

			$logindata = array(
				'userId' =>$dbData[0]['id'],
				'lastlogin' =>date("Y-m-d H:i:s", $now),
				'role' =>$role,
				'accountId' =>$dbData[0]['accountId']
				);
			Config::get('db')->insert('productiveLogin', $logindata);

            if ($_SESSION['account']['active'] == 0 || $_SESSION['account']['acctActive'] == 0) {
                header('location: /contact.php');
            }else if (trim($_SESSION['account']['pwd']) == $_SESSION['account']['temp']) {
                header('location: /pwupdate.php');
            }else if ($_SESSION['account']['role'] <=4 && $_SESSION['account']['prodMeasure'] != 2 && $p==0) {
                header('location: /dashboard.php#staffing');
            }else if ($_SESSION['account']['role'] <=4 && $_SESSION['account']['prodMeasure'] == 2 && $p==0) {
                header('location: /dashboard.php#supportstaffing');
			}else if ($_SESSION['account']['role'] >89) {
                header('location: /dashboard.php#accounts');
            }else {
                header($header);
            }
            exit();
        } else {
            $login_error = 1;
        }

    //}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>ProductivRN — Sign In</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #f7f9fc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      -webkit-font-smoothing: antialiased;
    }

    .login-wrap {
      width: 100%;
      max-width: 420px;
      padding: 1rem;
    }

    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      margin-bottom: 2rem;
      text-decoration: none;
    }
    .brand-name {
      font-size: 1.4rem;
      font-weight: 800;
      color: #0f1525;
      letter-spacing: -0.02em;
    }
    .brand-name span { color: #1a4f9e; }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04);
      padding: 2.25rem 2rem;
    }

    .card h2 {
      font-size: 1.15rem;
      font-weight: 700;
      color: #0f1525;
      margin-bottom: 0.35rem;
      letter-spacing: -0.01em;
    }
    .card .subtitle {
      font-size: 0.85rem;
      color: #6b7280;
      margin-bottom: 1.75rem;
    }

    .error-msg {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 6px;
      color: #b91c1c;
      font-size: 0.85rem;
      padding: 0.65rem 0.9rem;
      margin-bottom: 1.25rem;
    }

    .info-msg {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 6px;
      color: #15803d;
      font-size: 0.85rem;
      padding: 0.65rem 0.9rem;
      margin-bottom: 1.25rem;
    }

    .field { margin-bottom: 1rem; }
    .field label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.4rem;
    }
    .field input {
      width: 100%;
      padding: 0.65rem 0.85rem;
      border: 1.5px solid #d1d5db;
      border-radius: 6px;
      font-size: 0.925rem;
      color: #0f1525;
      background: #fff;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .field input:focus {
      border-color: #1a4f9e;
      box-shadow: 0 0 0 3px rgba(26,79,158,0.1);
    }

    .btn-login {
      width: 100%;
      padding: 0.75rem;
      background: #1a4f9e;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
      margin-top: 0.5rem;
    }
    .btn-login:hover { background: #1d5cb8; transform: translateY(-1px); }
    .btn-login:active { transform: translateY(0); }

    .forgot-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      font-size: 0.82rem;
      color: #6b7280;
      text-decoration: none;
      cursor: pointer;
    }
    .forgot-link:hover { color: #1a4f9e; }

    footer {
      margin-top: 2rem;
      font-size: 0.78rem;
      color: #9ca3af;
      text-align: center;
    }
    footer a { color: #9ca3af; text-decoration: none; }
    footer a:hover { color: #6b7280; }

    /* Forgot modal overrides */
    .modal-content { border-radius: 10px; border: none; box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
    .modal-header { border-bottom: 1px solid #f3f4f6; padding: 1.25rem 1.5rem; }
    .modal-header h5 { font-weight: 700; font-size: 1rem; color: #0f1525; }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer { border-top: 1px solid #f3f4f6; padding: 1rem 1.5rem; }
    .modal-body label { font-size: 0.85rem; color: #374151; margin-bottom: 0.5rem; display: block; }
    .modal-body .form-control {
      border: 1.5px solid #d1d5db;
      border-radius: 6px;
      font-size: 0.9rem;
      padding: 0.6rem 0.85rem;
    }
    .modal-body .form-control:focus { border-color: #1a4f9e; box-shadow: 0 0 0 3px rgba(26,79,158,0.1); outline: none; }
    .modal-footer .btn-primary { background: #1a4f9e; border-color: #1a4f9e; border-radius: 6px; font-weight: 600; }
    .modal-footer .btn-primary:hover { background: #1d5cb8; border-color: #1d5cb8; }
    .modal-footer .btn-secondary { border-radius: 6px; }
  </style>
</head>
<body>

  <div class="login-wrap">

    <a href="/" class="brand">
      <svg width="28" height="28" viewBox="0 0 30 30" fill="none">
        <circle cx="15" cy="15" r="13" stroke="#1a4f9e" stroke-width="1.5" fill="rgba(26,79,158,0.06)"/>
        <path d="M15 8v14M8 15h14" stroke="#1a4f9e" stroke-width="2" stroke-linecap="round"/>
        <circle cx="15" cy="15" r="3" fill="#1a4f9e"/>
      </svg>
      <span class="brand-name">Productiv<span>RN</span></span>
    </a>

    <div class="card">
      <h2>Sign in to your account</h2>
      <p class="subtitle">Enter your credentials to continue</p>

      <?php if (isset($_GET['sc'])): ?>
      <div class="info-msg">Account created. Please log in below.</div>
      <?php elseif (isset($_GET['ae'])): ?>
      <div class="error-msg">Account is already signed up. Please log in below.</div>
      <?php elseif (isset($_GET['se'])): ?>
      <div class="error-msg">Problem creating account. Please try again.</div>
      <?php elseif ($login_error == 1): ?>
      <div class="error-msg">Invalid email/username or password. Please try again.</div>
      <?php endif; ?>

      <form action="index.php" method="post">
        <input type="hidden" name="sitelogin" value="1" />
        <input type="hidden" name="page"  value="<?php echo $dataId; ?>" />
        <input type="hidden" name="page2" value="<?php echo $dataId2; ?>" />
        <input type="hidden" name="page3" value="<?php echo $dataId3; ?>" />
        <input type="hidden" name="page4" value="<?php echo $dataId4; ?>" />
        <input type="hidden" name="page5" value="<?php echo $dataId5; ?>" />
        <input type="hidden" name="page6" value="<?php echo $dataId6; ?>" />

        <div class="field">
          <label for="username">Email or Username</label>
          <input type="text" id="username" name="username" placeholder="you@hospital.org" autocomplete="username" />
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" />
        </div>

        <button type="submit" class="btn-login">Sign In</button>
      </form>

      <a class="forgot-link" data-toggle="modal" data-target="#forgot">Forgot your password?</a>
    </div>

    <footer>
      &copy; 2026 Harrelson Software Group LLC &nbsp;·&nbsp;
      <a href="/privacy.html">Privacy Policy</a>
    </footer>

  </div>

  <!-- Forgot Password Modal -->
  <div id="forgot" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="forgotLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h5 id="forgotLabel">Password Reset</h5>
        </div>
        <div class="modal-body">
          <label for="forgotemail">Enter your email address and we'll send a reset link.</label>
          <input class="form-control" id="forgotemail" placeholder="you@hospital.org" type="text" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="forgotbutton" class="btn btn-primary" onclick="tj.forgot();">Send Reset</button>
        </div>
      </div>
    </div>
  </div>

  <script src="/theme/assets/global/plugins/jquery.min.js"></script>
  <script src="/theme/assets/global/plugins/jquery-migrate.min.js"></script>
  <script src="/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
  <script src="/scripts/login.js"></script>

</body>
</html>
