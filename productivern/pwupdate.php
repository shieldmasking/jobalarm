<?php
session_start();
ini_set('display_errors',0);
include './inc/class.db.php';
include './inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: index.php');
}

$account_data = $_SESSION['account'];
$user = $account_data['id'];
$labelName = $account_data['labelName'];
$logo = $account_data['logo'];
$favicon = $account_data['favicon'];
$login = $account_data['labelLogin'];

$dbData = Config::get('db')->get_results("select * from `productiveUser` where `id`={$user}");
$user = $dbData[0]['userName'];

if (!isset($account_data['accountId'])) {
	header('location: index.php');
	exit();
}

$changepwdstatus = 0;
$changepwderror = '';
if (isset($_REQUEST['changing_pwd'])) {
    $old_password = (isset($_REQUEST['orig_password'])) ? trim($_REQUEST['orig_password']) : '';
    $new_password = (isset($_REQUEST['new_password'])) ? trim($_REQUEST['new_password']) : '';
    $cfm_password = (isset($_REQUEST['cfm_password'])) ? trim($_REQUEST['cfm_password']) : '';
	$new_username = (isset($_REQUEST['userName'])) ? trim($_REQUEST['userName']) : '';
	$userName = strtolower(trim($new_username));

    if (!(strlen($old_password) > 0)) {
        $changepwdstatus = 2;
        $changepwderror .= 'You must enter your original password.<br />';
    }
    
    if (!(strlen($new_password) > 5)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your new password must be at least 6 characters.<br />';
    } 
	
	if (!(strlen($new_username) > 5)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your new username must be at least 7 characters.<br />';
    } 

    if (!($new_password == $cfm_password)) {
        $changepwdstatus = 2;
        $changepwderror .= 'Your confirmation password must match the new password.<br />';
    }
   
    if ($changepwdstatus == 0) {
        $raw_old_password = $_REQUEST['orig_password'];
        $new_password = password_hash($_REQUEST['new_password'], PASSWORD_BCRYPT);
		$new_username = htmlspecialchars($_REQUEST['userName']);
		$dbUsername = Config::get('db')->get_results("select * from `productiveUser` where LOWER(`userName`)='{$userName}' and `id`!={$account_data['id']}");
        $userRow = Config::get('db')->get_results("select `id`, `pwd` from `productiveUser` where `id`={$account_data['id']}");
        $storedPwd = isset($userRow[0]['pwd']) ? $userRow[0]['pwd'] : '';
        $pwdValid = (strpos($storedPwd, '$2y$') === 0)
            ? password_verify($raw_old_password, $storedPwd)
            : (md5($raw_old_password) === $storedPwd);
        if ($dbUsername) {
            $changepwdstatus = 2;
            $changepwderror .= 'Username is already in use.  Please select a new Username<br />';
        }else if(!$pwdValid) {
            $changepwdstatus = 2;
            $changepwderror .= 'Current password is incorrect.  Please retry.<br />';
        }else{
            $changepwdstatus = 1;
            $data = array('pwd'=>$new_password,
						'userName'=>$new_username,
						'temp'=>''
						);
            $where = array('id'=>$account_data['id']);
            Config::get('db')->update('productiveUser',$data,$where,1);
			session_destroy();
			header('Location: ' . $login . '');
        }                
    }    

}


//echo json_encode($account_data);

//var_dump($account_data);
//var_dump($twitter_login_url);
// $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
// $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
// switch ($connection->http_code) {
// 	case 200:
// 		$url = $connection->getAuthorizeURL($token);
// 		break;
// 	default:
// 		$error = 'Could not connect to Twitter. Refresh the page or try again later.';
// }

//$zipSearch = getDistanceQuery(Config::get('db'),75218,20);
//echo $zipSearch."<br />";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>ProductivRN — Set New Password</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    .wrap {
      width: 100%;
      max-width: 460px;
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
    .success-msg {
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
    .field .hint {
      font-size: 0.76rem;
      color: #9ca3af;
      margin-top: 0.3rem;
    }

    .pw-rules {
      background: #f8fafc;
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      font-size: 0.8rem;
      color: #6b7280;
    }
    .pw-rules p { margin: 0.2rem 0; display: flex; align-items: center; gap: 0.4rem; }
    .pw-rules .ok  { color: #15803d; }
    .pw-rules .bad { color: #9ca3af; }

    .btn-submit {
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
    .btn-submit:hover { background: #1d5cb8; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    footer {
      margin-top: 2rem;
      font-size: 0.78rem;
      color: #9ca3af;
      text-align: center;
    }
    footer a { color: #9ca3af; text-decoration: none; }
    footer a:hover { color: #6b7280; }
  </style>
</head>
<body>

  <div class="wrap">

    <a href="/" class="brand">
      <svg width="28" height="28" viewBox="0 0 30 30" fill="none">
        <circle cx="15" cy="15" r="13" stroke="#1a4f9e" stroke-width="1.5" fill="rgba(26,79,158,0.06)"/>
        <path d="M15 8v14M8 15h14" stroke="#1a4f9e" stroke-width="2" stroke-linecap="round"/>
        <circle cx="15" cy="15" r="3" fill="#1a4f9e"/>
      </svg>
      <span class="brand-name">Productiv<span>RN</span></span>
    </a>

    <div class="card">
      <h2>Set your new password</h2>
      <p class="subtitle">Welcome, <?php echo htmlspecialchars($account_data['first_name']); ?>. Choose a username and password to continue.</p>

      <?php if ($changepwdstatus == 2): ?>
      <div class="error-msg"><?php echo $changepwderror; ?></div>
      <?php elseif ($changepwdstatus == 1): ?>
      <div class="success-msg">Password changed successfully. Redirecting to sign in&hellip;</div>
      <?php endif; ?>

      <form action="pwupdate.php" method="post">
        <input type="hidden" name="changing_pwd" value="1" />

        <div class="field">
          <label for="userName">Username</label>
          <input type="text" id="userName" name="userName" value="<?php echo htmlspecialchars($user); ?>" autocomplete="username" />
          <div class="hint">Min. 8 characters. Can be different from your email.</div>
        </div>

        <div class="field">
          <label for="orig_password">Current (Temporary) Password</label>
          <input type="password" id="orig_password" name="orig_password" autocomplete="current-password" />
        </div>

        <div class="field">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" autocomplete="new-password" />
        </div>

        <div class="pw-rules" id="pw-rules">
          <p id="r-length" class="bad">&#10006; Minimum 8 characters</p>
          <p id="r-upper"  class="bad">&#10006; One uppercase letter</p>
          <p id="r-lower"  class="bad">&#10006; One lowercase letter</p>
          <p id="r-number" class="bad">&#10006; One number</p>
        </div>

        <div class="field">
          <label for="cfm_password">Confirm New Password</label>
          <input type="password" id="cfm_password" name="cfm_password" autocomplete="new-password" />
        </div>

        <button type="submit" class="btn-submit">Set Password</button>
      </form>
    </div>

    <footer>
      &copy; 2026 Harrelson Software Group LLC &nbsp;&middot;&nbsp;
      <a href="/privacy.html">Privacy Policy</a> &nbsp;&middot;&nbsp;
      <a href="/terms/index.html">Terms of Use</a>
    </footer>

  </div>

  <script>
    var inp = document.getElementById('new_password');
    if (inp) {
      inp.addEventListener('input', function() {
        var v = this.value;
        set('r-length', v.length >= 8);
        set('r-upper',  /[A-Z]/.test(v));
        set('r-lower',  /[a-z]/.test(v));
        set('r-number', /[0-9]/.test(v));
      });
    }
    function set(id, ok) {
      var el = document.getElementById(id);
      if (!el) return;
      el.className = ok ? 'ok' : 'bad';
      el.firstChild.nodeValue = (ok ? '\u2714 ' : '\u2716 ') + el.firstChild.nodeValue.slice(2);
    }
    <?php if ($changepwdstatus == 1): ?>
    setTimeout(function(){ window.location = '<?php echo htmlspecialchars($login); ?>'; }, 2000);
    <?php endif; ?>
  </script>

</body>
</html>