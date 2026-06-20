<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>JobAlarm Management System</title>

    <!-- stylesheets -->
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('base_url');?>css/w2ui-1.4.2.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('base_url');?>css/uploadify.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('base_url');?>css/sitemain.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('base_url');?>css/jquery-te-1.4.0.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('base_url');?>css/jHtmlArea.css" />

    <!-- css overrides -->
    <?php
	/*
        $cssoverrides = Config::get('css');
        if ($cssoverrides and count($cssoverrides)>0) {
            echo "\r\n".'    <style type="text/css">'."\r\n";
            foreach($cssoverrides as $css) {
                echo "        {$css}\r\n";
            }
            echo "    </style>\r\n";
        }
		*/
		$budget = 0;
		$remaining = 0;		
		$spend = 0;
		/*
		$userx = (isset($_COOKIE['jobalarm_userId'])) ? $_COOKIE['jobalarm_userId'] : 0;
        $dbMail = Config::get('db') -> get_results("SELECT s.id, s.message, r.keyword, s.brandId, s.userId, s.accountId, s.type as msgtype, a.pText, a.text as cText FROM `sms_messages` s left join `sms_brand` as r on r.id = s.brandId left join `account_brand` as b on b.brandId = r.id left outer join `account` as a on a.id = b.accountId left outer join `users` as u on u.accountId = a.id WHERE ((s.accountId in (SELECT accountId from `users` where id = $userx) and s.type=1) or s.message = r.keyword) and s.brandId in (SELECT brandId from `account_brand` where accountId in (SELECT accountId from `users` where id=$userx)) and MONTH(s.msgDate) = MONTH(CURRENT_DATE())");
        
        $query = "SELECT FOUND_ROWS() AS found_rows;";
		$countData = Config::get('db') -> get_results($query);
		
		$budget = $dbMail[0]['pText'];
		$cText = $dbMail[0]['cText'];
        
        if ($dbMail && count($dbMail) > 0) {
            foreach ($dbMail as $count){
                if (intval($count['msgtype'])==1){
                    $spend = $spend + .04;				
                }else{
                    $spend = $spend + $cText;
                }
            }
        }
				
		if (intval($budget)>0){
		$remaining = $budget - $spend;
		}else{
		$remaining = '';
		}
		*/
                          
    ?>

    <!-- scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>lib/w2ui-1.4.2.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>lib/zeroclipboard/ZeroClipboard.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>lib/jquery.uploadify.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>lib/jquery-te-1.4.0.min.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>lib/jHtmlArea-0.8.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('base_url');?>views/shared/main.js"></script>

    <!-- dynamic javascript -->
	
	<?php  
/*	
    $jsvars = Config::get('jsvars');
    if ($jsvars && count($jsvars) > 0) {
        echo "\r\n".'    <script type="text/javascript">'."\r\n";
        foreach($jsvars as $jsvar) {
            $key = key($jsvar);
            $value = $jsvar[$key];
            echo "        wus.{$key} = '{$value}';\r\n";
        }
        echo '    </script>'."\r\n";
    }    
    
    ?>

    <!-- view scripts -->
    <?php
    //load in javascripts
    $scripts = Config::get('scripts');
    if ($scripts) {    
        foreach($scripts as $script) {
            echo '    <script type="text/javascript" src="'.Config::get('base_url').$script.'"></script>'."\r\n";
        }
    }
	*/
    ?>
	
</head>
<body>
    <?php
    if (!Config::get('noheader')):
    ?>
    <div id="masterContent">
    <div id="content">
        <div class="center">
            <div class="header">
                <?php if (Config::get('sysblock')) : ?>
                <div class="sysblock">
                    <p><a class="user" href="prefs"><?php echo User::getData('fullName');?> <span class="fa fa-user"></span></a></p>
                    <?php if (User::isAdmin()) : ?>
                    <p><a class="adminlink" href="admin">System Admin <span class="fa fa-wrench"></span></a></p>
                    <?php endif; ?>
                    <p><a class="logout" href="logout">Sign Out <span class="fa fa-sign-out"></span></a></p>
					<p><a class="user" href="budget">Remaining Budget: <?php echo $remaining;?> </a></p>
                </div>
                <?php 
                      endif; 
                      $controller = Router::getController();
                      $dashboard = trim("header_btn ".(($controller == 'dashboard') ? "btn_active" : ""));
                      $globals = trim("header_btn ".(($controller == 'globals') ? "btn_active" : ""));
                      $surveys = trim("header_btn ".(($controller == 'surveys') ? "btn_active" : ""));
                      $sms = trim("header_btn ".(($controller == 'sms') ? "btn_active" : ""));
                ?>
                <?php
                      if (Config::get('mainmenu')) : 
                ?>
                <!--<div class="mainmenu">
                    <a class="<?php echo $globals;?>" href="globals?a=<?php echo Config::get('loggedIn'); ?>"><i class="fa fa-globe fa-lg"></i> Subscribers</a>
                    <a class="<?php echo $surveys;?>" href="surveys"><i class="fa fa-list fa-lg"></i> Surveys</a>
                    <a class="<?php echo $sms;?>" href="sms?a=<?php echo Config::get('loggedIn'); ?>"><i class="fa fa-phone fa-lg"></i> SMS Inbox</a>
                </div>-->
                <?php endif; ?>
            </div>
    <?php
    endif;
    ?>