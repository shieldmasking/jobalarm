<?php
session_start();
ini_set('display_errors',1);
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';
include 'vendor/autoload.php';

if (!isset($_SESSION['account'])) {
    header('location: index.php');
}

$account_data = $_SESSION['account'];

$dbData = Config::get('db')->get_results("select * from account where id=".$account_data['accountId']);
if (isset($dbData[0])) {
    $account_balance = $dbData[0]['balance'];
}

if (!isset($account_data['twitter_handle'])) {
	header('location: index.php');
	exit();
}

$campaignData = Config::get('db')->get_results("select sum(budget) as budgetTotal from campaign where status>0 and account_id=".$account_data['accountId']);
$campaignTotal = max(0.00,$campaignData[0]['budgetTotal']);

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
    <title>JobAlarm | Job Tweet Search</title>
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
            height:60px;
        }
        .dd-handle {
            line-height:48px;
        }
        .dd-item {
            line-height:15px;
        }
        
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="index.php">
                        <img src="img/logo1.png" alt="logo" class="logo-default" />
                    </a>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler"></a>
                <!-- END RESPONSIVE MENU TOGGLER -->

            </div>
        </div>
        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->
        <div class="page-header-menu" style="background-color:#444d58">
            <div class="container">
                <h1 style="width:340px;float:left;margin:0;padding:5px;color:white">Account Billing</h1>
                <!-- BEGIN MEGA MENU -->
                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                <div class="hor-menu">
                    <ul class="nav navbar-nav">
                        <li class="">
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="active menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Account
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
                                <li class=" ">
                                    <a href="account_settings.php">
                                        <i class="icon-settings"></i>
                                        Settings
                                    </a>
                                </li>
                                <li class="active">
                                    <a href="account_billing.php">
                                        <i class="icon-briefcase"></i>
                                        Billing
                                    </a>
                                </li>
                                <li class=" ">
                                    <a href="account_reports.php">
                                        <i class="icon-bar-chart"></i>
                                        Reports
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php if($account_data['role'] == 10): ?>
                        <li class="menu-dropdown classic-menu-dropdown ">
                            <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                Administration
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-left">
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
                            </ul>
                        </li>
                        <?php endif; ?>     
                    </ul>
                </div>
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
                        <a href="index.php">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="dashboard.php">Dashboard</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li class="active">
                        Account Billing
                    </li>
                </ul>
                <!-- END PAGE BREADCRUMB -->
                <!-- BEGIN PAGE CONTENT INNER -->
                <div class="row margin-top-10">
                    <h1>
                        Welcome, <?php echo $account_data['fullName']; ?>
                        <span style="font-size:15px;">
                            (@<?php echo $account_data['twitter_handle']; ?>)
                        </span>
                    </h1>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6 col-xs-12">
                        <h3>Remaining Balance: <strong>$<?php echo $account_balance; ?></strong> </h3>
                        <h3>Campaign Budget Total: <strong>$<?php echo $campaignTotal; ?></strong> </h3>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div style="margin-top:20px;margin-bottom:10px;line-height:25px;">
                            <form name="add_funds_form" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" id="paypal_custom" name="custom" value="<?php echo $account_data['accountId']; ?>"/>
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="EBYAH4W3RXWV4">
                                <input type="hidden" name="lc" value="US">
                                <input type="hidden" name="item_name" value="Campaign Funds">
                                <input type="hidden" name="item_number" value="tjcf">
                                <input type="hidden" name="button_subtype" value="services">
                                <input type="hidden" name="no_note" value="1">
                                <input type="hidden" name="no_shipping" value="1">
                                <input type="hidden" name="rm" value="1">
                                Add Amount: <i class="fa fa-usd fa-lg"> </i> <input type="text" id="payment_amount" name="amount" value="25" style="font-size:16px; width:60px; vertical-align:middle"/><br />
                                Promo Code (optional): <input type="text" id="coupon_code" name="coupon_code" value="" style="font-size:16px; width:80px; vertical-align:middle"/><br />   
                                <input type="hidden" name="return" value="https://jobalarm.com/dashboard.php">
                                <input type="hidden" name="cancel_return" value="https://jobalarm.com/account_billing.php?cancelled=1">
                                <input type="hidden" name="currency_code" value="USD">
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
                                <input type="hidden" name="notify_url" value="https://jobalarm.com/payment.php">
                                <a class="btn btn-sm green" style="margin-top:5px;" href="javascript:;" onClick="tj.prevalidatePayment()"><i class="fa fa-paypal"></i> Add Funds With Paypal</a>

                                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <div class="col-md-6 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">Campaign Spend History</span>
                                </div>


                            </div>
                            <div class="portlet-body">
                                <table style="width:100%">
                                    <thead>
                                        <tr><th>Campaign</th><th>Date</th><th>Amount</th></tr>
                                    </thead>
                                    <tbody>                            
                                        <?php 
                                        $dbData = Config::get('db')->get_results("select *, DATE_FORMAT(DATE_SUB(transactionDate,INTERVAL 5 HOUR),'%m/%d/%Y %h:%i %p') as txdate,c.name from transaction left join campaign c on c.id=transaction.campaignId where accountId=".$account_data['accountId']." ORDER BY transactionDate desc limit 0,100");
                                        foreach($dbData as $payment) {
                                            echo "<tr><td>{$payment['name']}</td><td>{$payment['txdate']}</td><td><i class=\"fa fa-usd\"></i> {$payment['amount']}</td></tr>\r\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>                                        
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>                    
                    <div class="col-md-6 col-sm-12">
                        <!-- BEGIN PORTLET-->
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption caption-md">
                                    <i class="icon-bar-chart theme-font hide"></i>
                                    <span class="caption-subject theme-font bold uppercase">Payment History</span>
                                </div>
            

                            </div>
                            <div class="portlet-body">
                                <table style="width:100%">
                                    <thead>
                                        <tr><th>Date</th><th>Amount</th><th>Promo Added</th><th>Total</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $dbData = Config::get('db')->get_results("select *, DATE_FORMAT(DATE_SUB(paymentDate,INTERVAL 5 HOUR),'%m/%d/%Y %h:%i %p') as paydate from payment where accountId=".$account_data['accountId']." ORDER BY paymentDate desc limit 0,100");
                                        foreach($dbData as $payment) {
                                            echo "<tr><td>{$payment['paydate']}</td><td><i class=\"fa fa-usd\"></i> {$payment['amount']}</td><td><i class=\"fa fa-usd\"></i> {$payment['couponAdd']}</td><td><i class=\"fa fa-usd\"></i> {$payment['total']}</td></tr>\r\n";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END PORTLET-->
                    </div>
                </div>
              
                <!-- END PAGE CONTENT INNER -->
            </div>
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="container">
            2015 &copy; Premier SSG, Inc. All Rights Reserved.
        </div>
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
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
    
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/index3.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/table-managed.js"></script>
    <script src="theme/assets/global/scripts/datatable.js"></script>
    <script src="theme/assets/admin/pages/scripts/table-ajax.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="inc/tweetedjobs-account-billing.js" type="text/javascript"></script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>