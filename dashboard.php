<?php
session_start();
ini_set('display_errors',1);
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: login.php');
}

$account_data = $_SESSION['account'];
if (!isset($account_data['accountId'])) {
	header('location: login.php');
	exit();
}

if ($_SESSION['account']['billing_plan']==0 || $_SESSION['account']['billing_hold']==1) {
    header('location: chooseplan.php');
}

$dbData = Config::get('db')->get_results("select * from account where id=".$account_data['accountId']);
if (isset($dbData[0])) {
    $account_balance = $dbData[0]['balance'];
}

if (isset($_GET['la']) && ($account_data['role'] > 10)) {

    $login_as = $_GET['la'];

    $query = "SELECT * FROM account WHERE twitter_handle='{$login_as}'";
    $dbData = Config::get('db')->get_results($query);
    if (count($dbData) > 0) {
        $_SESSION['account'] = $dbData[0];
//        $data = array('lastlogin_date'=>date('Y-m-d H:i:s'));
//        $where = array('id'=>$dbData[0]['id']);
//        Config::get('db')->update('account',$data,$where,1);
        header('location: dashboard.php');
        exit();
    }

}

//$accountId = $_SESSION['account']['accountId'];
//echo "account Id:"$accountId;
//$userx = $_SESSION['account']['id'];
//echo "user Id: "$userx;

//$candidateCount = Config::get('db')->get_results("select c.*, x.promo, ce.latitude as latitude, ce.longitude as longitude from candidate c LEFT JOIN candidateXref as x on x.candidateId = c.id LEFT JOIN cities_extended as ce on ce.zip = c.zip where c.mobile !='' and (x.promo=1 or x.promo=2) and active=1");


	/* foreach ($candidateCount as $count) {
	if ($count['latitude']) {
		$lat = $count['latitude'];
		$lon = $count['longitude'];

		$candidateId = $count['id'];
		$zip = $count['zip'];
		$zipOrig = intval($zip);
		$zipLow = intval($zipOrig)-1;
		$zipHigh = intval($zipOrig)+1;

	$storeList = Config::get('db')->get_results("select ce.* FROM cities_extended ce WHERE (3963*ACOS(SIN({$lat}/57.2958)*SIN(ce.latitude/57.2958)+ COS({$lat}/57.2958) *COS(ce.latitude/57.2958)*COS((ce.longitude/57.2958)-({$lon}/57.2958))))<=7 group by zip");

		foreach ($storeList as $store) {
		$updatedata = array(
		'candidateId'=>$candidateId,
		'zip'=>$store['zip']
	 	);
		Config::get('db')->insert('candidatecountXref',$updatedata);
		}
	}
	} */


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
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>JobAlarm | Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/jquery-nestable/jquery.nestable.css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
    <link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />

	<style type="text/css">
	#datatableCompx_ajax_length,
	#datatableCompx_ajax_filter
	{
	display:none;
	}
	</style>

    <style type="text/css">
        .modal-backdrop {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 0;
            background-color: #000;
        }
        .dd3-content,.dd-handle {
            height:70px;
        }
        .dd-handle {
            line-height:58px;
        }
        .dd-item {
            line-height:15px;
        }

    .style1 {
	color: #FF0000;
	font-weight: bold;
	font-size: 12px;
}
    </style>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		.auto-style1 {
			margin-bottom: 24px;
		}
		</style>



		<script type="text/javascript">
<?php
    $query = "SELECT s.*, b.storeBrand from `sms_stores` s LEFT JOIN `sms_brand` b on b.id = s.brandId WHERE s.accountId =" . $account_data['accountId'] . " GROUP BY s.brandId";

    $dbData = Config::get('db')->get_results($query);

    echo "var addStoreBrands = ".json_encode($dbData).";";

    $user = "SELECT * FROM `users` where accountId =" . $account_data['accountId'] . " ORDER BY last_name ASC";

    $dbUser = Config::get('db')->get_results($user);

    echo "var addStoreUsers = ".json_encode($dbUser).";";

    $accountId = $account_data['accountId'];

    $query = "SELECT u.* from `users` u WHERE u.status=1 and accountId={$accountId} ORDER BY u.last_name,u.first_name";

    $dbData = Config::get('db')->get_results($query);

    echo "var assignToStoreUsers = ".json_encode($dbData).";";
	$sessionAccount = $account_data['accountId']*12345;
	$sessionId = $account_data['id']*54321;

?>
	</script>

<SCRIPT TYPE="text/javascript" LANGUAGE="javascript">

<!-- PreLoad Wait - Script -->

function waitPreloadPage() { //DOM
if (document.getElementById){
document.getElementById('prepage').style.visibility='hidden';
}else{
if (document.layers){ //NS4
document.prepage.visibility = 'hidden';
}
else { //IE4
document.all.prepage.style.visibility = 'hidden';
}
}
}
// End -->
</SCRIPT>
<script>  function statusChangeCallback(response)
{

   if (response.status === 'connected') {
      testAPI();    }
   else if (response.status === 'not_authorized') {      document.getElementById('status').innerHTML = 'Please log ' +        'into this app.';    }
   else {      document.getElementById('status').innerHTML = 'Please log ' +        'into Facebook.';    }
   }

function checkLoginState()
  {
     FB.getLoginStatus(function(response)
      {
         statusChangeCallback(response);
      });
   }
   window.fbAsyncInit = function()
   {
       FB.init({      appId      : '1051341108212143',      xfbml      : true,      version    : 'v2.3'    });
      FB.getLoginStatus(function(response)
      {    statusChangeCallback(response);  });
   };
   (function(d, s, id)
 {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs); } (document, 'script', 'facebook-jssdk')
    );


    function testAPI() {
// for FB troubleshooting
/*
    var body = 'Reading JS SDK documentation';
    FB.api('/me/feed', 'post', { message: body },
       function(response)
       {
         if (!response || response.error) {
      console.log(response);
       } else {
      console.log(response);
      }
   });
*/

    FB.api('/me', function(response)
     {
//        testAPI(console.log());
        document.getElementById('status').innerHTML =        'Thanks for logging in, ' + response.name + '!';
        tj.alex.getGroupsFb(response.id, response.last_name + ', ' + response.first_name);
     });

     }


   </script>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed" >

     <!--  Below we include the Login Button social plugin. This button uses  the JavaScript SDK to present a graphical Login button that triggers  the FB.login() function when clicked.-->
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.php">
                        &nbsp;</a></div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                    <a href="index.php">
                        <img src="img/logo1.png" longdesc="http://www.jobalarm.com"></a></div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Account Dashboard</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                <div class="hor-menu">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Tools
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="account_settings.php">
                                        <i class="icon-settings"></i>
                                        Account Settings
                                    </a>
                                </li>
                                <?php if ($_SESSION['account']['role'] > 1) : ?>

                                <li class=" ">
                                    <a href="http://admin.jobalarm.com/login/smslogin/<?php echo $sessionAccount ?>/<?php echo $sessionId ?>/0/0" >
                                        <i class="fa fa-comment-o"></i>
                                        SMS Manager
                                    </a>
                                </li>
								<?php endif; ?>
								<?php if ($_SESSION['account']['role'] > 9) : ?>
								
								<li class=" ">
                                    <a href="http://www.jobalarm.com/messenger/messageApp/messages.html#" >
                                        <i class="fa fa-wechat"></i>
                                        Mobile Messenger
                                    </a>
                                </li>
								
                                <li class=" ">
                                    <a href="users.php">
                                        <i class="fa fa-users"></i>
                                        User Manager
                                    </a>
                                </li>

                                <?php endif; ?>

								<li class=" ">
                                    <a href="#supportModal" id="supportDialog" data-toggle="modal">
									<i class="fa fa-question"></i>
									Support
                                    </a>
                                </li>
								
								<?php if ($_SESSION['account']['role'] > 9) : ?>

								<li class=" ">
                                    <a href="jareports/view/sms_summary.php">
                                        <i class="icon-bar-chart"></i>
                                        Reports
                                    </a>
                                </li>
								<?php endif; ?>

                            </ul>
                        </li>
                        <?php if($account_data['role'] > 10): ?>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Administration
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="useradmin.php">
                                        <i class="icon-users"></i>
                                        Account Manager
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="couponcodes.php">
                                        <i class="icon-list"></i>
                                        Promo Codes
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="hashtagadmin.php">
                                        <i class="icon-settings"></i>
                                        Hashtags
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="admin_reports.php">
                                        <i class="icon-bar-chart"></i>
                                        Reports
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="sitestatus.php">
                                        <i class="icon-heart"></i>
                                        System Status
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <a class="btn blue pull-right margin-top-10" href="logout.php"><i class="fa fa-sign-out"></i>Sign Out</a>
            </div>
        </div>
        <!-- END HEADER MENU -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN PAGE CONTAINER -->
    <div class="page-container">
        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
                <!-- BEGIN PAGE BREADCRUMB -->
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="#">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li class="active">
                        Dashboard
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <div class="col-md-6 col-xs-12">
                        <h1>
                            Welcome, <?php echo $account_data['first_name']; ?>

                        </h1>
                    </div>
					<!--<div class="col-md-6 col-xs-12" align="right">
					<h4> Need help getting started?  Watch this <a href="gettingstarted.php" target="_blank">video.</a></h4>
					</div>
                    <div class="col-md-6 col-xs-12">


                        <h1 class="pull-right">Remaining Balance: <strong>$</strong> </h1>

                    </div>-->
                </div>

				<div class="row margin-top-10">

			<?php if ($_SESSION['account']['role'] > 1) : ?>

			         <div class="col-md-12 col-sm-12" id="ManageCompanyCell" style="left: 0px; top: 0px">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-building"></i> - Locations</span>
                                </div>
                                 <?php if ($_SESSION['account']['role'] > 9) : ?>

                                <div class="actions">
                      <a class="btn btn-sm green" onClick="dashboard.addstore()">Add Location <i class="fa fa-plus"></i></a>
					</div>
					<?php endif; ?>
								</div>
                            <div class="portlet-body">

    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#my_stores">My Locations</a></li>

	<?php if ($_SESSION['account']['role'] >4) : ?>

    <li><a data-toggle="tab" href="#all_stores">All Locations</a></li>
	<?php endif; ?>
	</ul>

	<div class="tab-content">
	<div id="my_stores" class="tab-pane fade in active">

	<table class="table table-striped table-bordered table-hover" id="datatableComp_ajax">	<thead>
	<tr role="row" class="heading">
	<th>Store</th>
	<th>Location</th>
	<th>Avail. Candidates</th>
	<th>Action</th></tr>
	</thead>
	<tbody id="CompBody"></tbody>
	</table>
	</div>


	<div id="all_stores" class="tab-pane fade">
	<table class="table table-striped table-bordered table-hover" id="datatableAllStores_ajax">	 <thead>
	<tr role="row" class="heading">
	<th>Store</th>
	<th>Location</th>
	<th>Assigned</th>
	<th>Actions</th></tr>
	</thead>
	<tbody id="AllStoresBody"></tbody>
	</table>
	</div>

	</div>



                            </div>
                        </div>
                        <!-- END PORTLET-->
                       </div>


					 <div class="auto-style1" id="CompJobsCell">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            
							<div class="portlet-title">
							
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"></span>
                                </div>
                     <div class="actions"> 
					 <div class="pull-right"><fb:login-button scope="public_profile,email,publish_actions,user_groups" onlogin="checkLoginState();" id="faceBookLoginButton"></fb:login-button><div id="status"></div></div>
					<div>&nbsp;</div>			 
					<a class="btn btn-sm purple" data-toggle="modal" href="#" id="CompManagerBackButton">Back <i class="fa fa-backward"></i></a>
					<?php if ($_SESSION['account']['role'] > 9) : ?>

                                <div class="actions">
                      <a class="btn btn-sm green" onClick="dashboard.addjob()">Add Job <i class="fa fa-plus"></i></a>
					</div>
					<?php endif; ?>
                      </div>
					  
					</div>
					
                    <div class="portlet-body">
	<ul class="nav nav-tabs">
	<?php if ($_SESSION['account']['role'] > 11) : ?>
    <li><a data-toggle="tab" href="#ja_jobs">JA Jobs</a></li>
	<?php endif; ?>
    <?php if ($_SESSION['account']['role'] > 2) : ?>

    <li class="active"><a data-toggle="tab" href="#ats_jobs">Jobz</a></li>
	<?php endif; ?>
	</ul>
	<div class="tab-content">
                <div id="ja_jobs" class="tab-pane fade">
				<table class="table table-striped table-bordered table-hover" id="datatableCompx_ajax">								    <thead>
				<tr role="row" class="heading">
				<th>Position</th>
				<th>Last Posted</th>
				<th>Last Texted</th>
				<th>Click Count</th>
				<th>Action</th></tr>
				</thead>
				<tbody id="CompxBody"></tbody>
				</table>
				</div>

				<div id="ats_jobs" class="tab-pane in active">
				<table class="table table-striped table-bordered table-hover" id="datatableATSx_ajax">								    <thead>
				<tr role="row" class="heading">
				<th>Position</th>
				<th>Posted</th>
				<th>Auto-Texts</th>
				<th>Click Count</th>
				<th>Action</th></tr>
				</thead>
				<tbody id="ATSxBody"></tbody>
				</table>
				</div>


				</div>
				</div>
			        </div>

				 </div>

				<?php endif; ?>
				<div class="row margin-top-10" id="faceBookRow">
                    <div class="col-md-12 col-sm-12" style="left: 0px; top: 0px">
                        <!-- BEGIN PORTLET-->
						<!--
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-dollar"></i> - Sponsored Jobs</span>
                                </div>
                                <div class="actions">

                                    <a class="btn btn-sm green" onClick="tj.verifyFunds(25);" href="javascript:;">
                                        Add Campaign
                                        <i class="fa fa-plus"></i>
                                    </a>

                                </div>
                                <div class="modal fade" id="addfundsdialog" tabindex="-1" role="basic" aria-hidden="true">
								    <div class="modal-dialog">
									    <div class="modal-content">
										    <div class="modal-header">
											    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											    <h4 class="modal-title">Notice</h4>
										    </div>
										    <div class="modal-body">
												Your account balance after subtracting current campaign totals must have at least 25$ remaining to add new campaigns.<br />Click OK to add Funds.
										    </div>
										    <div class="modal-footer">
											    <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
											    <button type="button" class="btn blue" onClick="window.location='account_billing.php';">OK</button>
										    </div>
									    </div>

								    </div>

							    </div>
                            </div>

                            <div class="portlet-body">
                                <div class="table-container" >
								    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
								    <thead>
								    <tr role="row" class="heading">
									    <th>
										     ID
									    </th>
									    <th width="15%">
										     Name
									    </th>
									    <th width="10%">
										     Start Date
									    </th>
									    <th width="10%">
										     End Date
									    </th>
                                        <th width="5%">
                                             Tweets
                                        </th>
									    <th width="10%">
										     Per Click<br />Budget
									    </th>
									    <th width="10%">
										     Per Day<br />Budget
									    </th>
									    <th width="10%">
										     Total<br />Budget
									    </th>
                                        <th width="10%">
                                             Remaining<br />Budget
                                        </th>
									    <th width="20%">
										     Action
									    </th>
								    </tr>

								    </thead>
								    <tbody>
								    </tbody>
								    </table>
							    </div>
                            </div>

                        </div>
						-->
                        <!-- END PORTLET-->
                    </div>
					
    <?php if ($_SESSION['account']['role'] > 11) : ?>

                    <div class="col-md-12 col-sm-12" id="faceBookTweetCell" style="left: 0px; top: 0px">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - Facebook Groups</span>
                                </div>                                
								<div class="pull-right"><fb:login-button scope="public_profile,email,publish_actions,user_groups" onlogin="checkLoginState();" id="faceBookLoginButton"></fb:login-button><div id="status"></div></div>
                            </div>
							
			
    <div class="portlet-body">
    <div class="portlet-body">

	<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#active_jobs">Active Jobs</a></li>
    <li><a data-toggle="tab" href="#pending_joins">Pending Joins</a></li>
	</ul>

	<div class="tab-content">
	<div id="active_jobs" class="tab-pane fade in active">
	<table class="table table-striped table-bordered table-hover" id="datatableFacebook_ajax">								    <thead>
	<tr role="row" class="heading">
	<th>User Name</th>
	<th>Post Date</th>
	<th>Job Details</th>
	<th>Click Count</th>
	<th>Action</th></tr>
	</thead>
	<tbody id="fbTweetBody"></tbody>
	</table>
	</div>

	<div id="pending_joins" class="tab-pane fade">
	<table class="table table-striped table-bordered table-hover stripe" id="PendingJoins">
	<thead><tr role="row" class="heading">
	<th>Groups</th>
	<th>Members</th>
	<th>Action</th>
	</tr></thead>
	<tbody id="joinBody"></tbody>
	</table>
	</div>   
	</div>
							</div>
                            </div>
                        
						
						</div>
                        <!-- END PORTLET-->
                      </div>
					  <?php endif; ?>
					  
					<div class="col-md-12 col-sm-12" id="groupManagerCell">
					<div class="portlet light ">
					<div class="portlet-title">
					<div class="caption caption-md">
					<i class="icon-bar-chart theme-font hide"></i>
					<span class="caption-subject theme-font bold uppercase"><i class="fa fa-facebook"></i> - Post to Groups</span></div>
					<div class="actions">
                      <a class="btn btn-sm purple" data-toggle="modal" href="#" id="groupManagerBackButton">Back<i class="fa fa-backward"></i></a>
					  <?php if ($_SESSION['account']['billing_plan'] >= 9) : ?>
					<a class="btn btn-sm blue" href="http://admin.jobalarm.com/globals"target="_blank">TEXT <i class="fa fa-edit"></i></a>  <?php endif; ?>
					</div>
					</div>
					<div class="portlet-body" id="groupManagerCellBody">

					</div>

                </div>
</div>



				 <div class="col-md-12 col-sm-12" style="left: 0px; top: 0px">
					<!-- BEGIN PORTLET-->
					<?php if ($_SESSION['account']['role'] > 11) : ?>
					<div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase"><i class="fa fa-twitter"></i> - Job Tweets</span>
                                </div>
                                <div class="actions">
                                    <a class="btn btn-sm green" data-toggle="modal" href="#addjobtweet">
                                        Add Job Tweet
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <ul class="pager" style="margin:0;margin-bottom:5px;">
                                  <li class="prev"><button style="float:left;" class="btn btn-sm" onClick="tj.prevPage();">Previous</button></li>
                                  <li style="border:none;"><span id="pageNum" style="border:none">Page 1 of 1</span></li>
                                  <li class="next"><button style="float:right;" class="btn btn-sm" onClick="tj.nextPage();">Next</button></li>
                                </ul>
                                <div data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">
                                    <div id="tweet-list" class="todo-tasklist">

                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="addjobtweet" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title"><i class="fa fa-twitter"></i>- Tweet Your Job</h4>
										</div>
										<div class="modal-body">
												<input style="width:100%" type="text" id="jobtweetmessage" maxlength="140" name="jobtweetmessage" />
										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Close</button>
											<button type="button" class="btn blue" onClick="tj.addJobTweet()">Send</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>

					<!-- END PORTLET-->

                        <!-- BEGIN PORTLET-->
						<?php if ($_SESSION['account']['billing_plan'] <= 0) : ?>
                        <div class="portlet light tasks-widget">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">Spend Summary</span>
                                </div>
                            </div>
                            <div class="portlet-body">
<!--                                 <div class="table-scrollable" style="height:474px"> -->
								   <table class="table table-striped table-bordered table-hover" id="datatablespend_ajax">
								    <thead>
								    <tr role="row" class="heading">
									    <th width="0">
										     ID
									    </th>
									    <th width="25%">
										     Campaign
									    </th>
                                        <th width="25%">
                                             Tweet
                                        </th>
									    <th width="25%">
										     Date
									    </th>
									    <th width="25%">
										     Amount
									    </th>
								    </tr>
								    </thead>
								    <tbody>
								    </tbody>
								    </table>
							    <!-- </div> -->
                            </div>
                        </div>
						<?php endif; ?>
                        <!-- END PORTLET-->
                    </div>
					<?php endif; ?>
                    <div class="col-md-6 col-sm-12">
                        <!-- BEGIN PORTLET-->

                        <!-- END PORTLET-->
                    </div>
                </div>

				</div>

                <!-- END PAGE CONTENT INNER -->
            </div>
            </div>

        <!-- END PAGE CONTENT -->

    <!-- END PAGE CONTAINER -->                    <div class="modal fade" id="postModal">  <div class="modal-dialog">    <div class="modal-content">      <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        <h4 class="modal-title">Post to FaceBook Group</h4>      </div>      <div class="modal-body" id="postModalBody">              </div>      <div class="modal-footer">        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>        <button id="postModalPostButton" type="button" class="btn btn-primary">Post</button>      </div>    </div><!-- /.modal-content -->  </div><!-- /.modal-dialog --></div><!-- /.modal -->
  <!-- start bump modal code -->
  <!-- end bump modal code -->
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; Premier SSG, Inc. All Rights Reserved.
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>

    </div>




<div class="modal fade" id="dashboardModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-building"></i>- Locations</h4>
            </div>
            <div id="addlocationMobileBody" class="modal-body">
                   <div class="row margin-bottom-20" style="margin-left: 15px; margin-right:15px;">


        <h3 class="form-section" style="text-align: center"><strong>Add a Location </strong></h3>



        <div class="form-group">
            <select id="brand" name="brand" type="select" class="form-control" required />
                <option>Select a Brand:</option>

                <option value="11">Brand 11</option>

            </select>
        </div>



        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input id="storeNum" name="storeNum" type="text" class="form-control" placeholder="Store Number" />
            </div>
        </div>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input id="address" name="address" type="text" class="form-control" placeholder="Address" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input id="city" name="city" type="text" class="form-control" placeholder="City" maxlength="25" required />
            </div>
        </div>
        <div class="form-group">
            <div class="input-icon">
                <i class="fa fa-envelope"></i>
                <input id="state" name="state" type="text" class="form-control" placeholder="ST" maxlength="2" required />
            </div>
        </div>
        <div class="form-group">
        <div class="input-icon">
        <i class="fa fa-briefcase"></i>
            <input id="zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip Code" maxlength="5" required />


        </div>
        </div>

        <div class="form-group">
            <select id="assign" name="assign" type="select" class="form-control">
                <option value="0">Assign a User:</option>
                <option value="22">User 1</option>
            </select>
        </div>
    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                <button id="postStoreButton" type="button" class="btn blue" onClick="dashboard.PostStore();">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="assignStoreModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-building"></i>- Locations</h4>
            </div>
            <div id="assignstoreMobileBody" class="modal-body">
                   <div class="row margin-bottom-20" style="margin-left: 15px; margin-right:15px;">




        <div class="form-group">
            <select id="assignStoreCombo" name="brand" type="select" class="form-control" required />
                <option>Assign this Location to:</option>

                <option value="11">Brand 11</option>

            </select>
        </div>

    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn blue" onClick="assignStore.assign();">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
<script src="theme/assets/global/plugins/respond.min.js"></script>
<script src="theme/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
    <script src="theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="theme/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
    <script src="theme/assets/global/plugins/jquery-nestable/jquery.nestable.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="theme/assets/admin/pages/scripts/ui-nestable.js"></script>
    <script src="theme/assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="theme/assets/global/scripts/datatable.js"></script>
    <script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>
    <script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
    <script src="inc/tweetedjobs-mainTest.js" type="text/javascript"></script>
     <script src="inc/dashboardObject.js" type="text/javascript"></script>
      <script src="inc/assignStore.js" type="text/javascript"></script>
	  

    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');
    </script>
    <!-- END JAVASCRIPTS -->


    <div class="modal fade" id="loadingModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Hang Tight</h4>
                </div>
                <div id="dashboardMobileBody" class="modal-body">
                  Finding FB Job Groups......

				</div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="supportModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <h4 class="modal-title">Please provide your support details and we will contact you within 24 hours.</h4>
                </div>
                <div id="supportBody" class="modal-body">


                    <div class="form-group">
                			<select id="supportModalType"  name="type" type="select" class="form-control" placeholder="Select">
                			<option value="Select">Support Type....</option>
                			<option value="Password">Password Support</option>
                			<option value="Tech Support">Technical Support</option></select>
          		      </div>
                    <div class="form-group"><label>Name</label><input id="supportModalName" class="form-control required" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text"></div>
                    <div class="form-group"><label>Phone</label><input id="supportModalPhone" class="form-control phone" placeholder="999-999-9999" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text"></div>
                    <div class="form-group"><label>E-Mail</label><input id="supportModalEmail" class="form-control" placeholder="Your email here.." data-placement="top" data-trigger="manual"></input></div>
                    <div class="form-group"><label>Message</label><textarea id="supportModalMessage" class="form-control" placeholder="Your message here.." data-placement="top" data-trigger="manual"></textarea></div>
              	    <div class="form-group"><button id="supportModalSubmitButton" type="submit" class="btn btn-success pull-left">Send</button> <p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p> &nbsp;&nbsp;<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>


				        </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</body>
<!-- END BODY -->
</html>
