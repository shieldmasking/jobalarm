<?php

ini_set('display_errors', 1);

session_start();



include 'inc/class.db.php';

include 'inc/class.jatwitter.php';

include 'inc/config.php';



//twitter oauth

use Abraham\TwitterOAuth\TwitterOAuth;



$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);

$access_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "http://jobalarm.com/twitterlogin.php"));



$_SESSION['oauth_token'] = $access_token['oauth_token'];

$_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];



$twitter_login_url = $connection->url("oauth/authorize", array("oauth_token" => $access_token['oauth_token']));



$login_error = 0;


$error_msg = '';
if ($_SESSION['account']['id'] == 0) {
    header('location: login.php');
}
//echo $_SESSION['account']['id'];
$dbData = Config::get('db')->get_results("select * from account where id=".$_SESSION['account']['id']);
$account_data = $dbData[0];

$billing_hold = false;

if ($account_data['billing_plan'] > 0 && $account_data['billing_hold'] > 0) {
    $billing_hold = true;
}

if ($account_data['billing_plan'] > 1 && !$billing_hold) {
    $_SESSION['account'] = $account_data;
    header('location: dashboard.php');
}


if (isset($_GET['pid'])) {
    if ($_GET['pid'] > 0 && $_GET['pid'] < 4) {
        $data = array('billing_plan'=>$_GET['pid']);
        if ($_GET['pid'] >= 2) {
            $data['billing_hold'] = 1;
            $data['hold_date'] = date('Y-m-d H:i:s');
            $_SESSION['account']['billing_hold'] = 1;
        }
        $where = array('id'=>$_SESSION['account']['id']);
        Config::get('db')->update('account',$data,$where,1);

        $_SESSION['account']['billing_plan'] = $_GET['pid'];
        if ($_GET['pid'] == 2) {
            //$query = "update job set campaignId=0 where userName=".$_SESSION['account']['twitter_handle'];
            //Config::get('db')->query($query);
            $query = "update campaign set status=0 where account_id=".$_SESSION['account']['id'];
            Config::get('db')->query($query);
            $data = array(
                'account_id'=>$_SESSION['account']['id'],
                'name'=>'Small Business (5 Sponsored Jobs)',
                'start_date'=>date('Y-m-d H:i:s'),
                'end_date'=>date('Y-m-d H:i:s',strtotime("+30 days")),
                'limited'=>1,
				'joinCount'=>0,
				'postCount'=>0,
                'click_budget'=>'0',
                'daily_budget'=>0,
                'budget'=>0
                );
            Config::get('db')->insert('campaign',$data);

         }
		    elseif ($_GET['pid'] == 3) {
            //$query = "update job set campaignId=0 where userName=".$_SESSION['account']['twitter_handle'];
            //Config::get('db')->query($query);
            $query = "update campaign set status=0 where account_id=".$_SESSION['account']['id'];
            Config::get('db')->query($query);
            $data = array(
                'account_id'=>$_SESSION['account']['id'],
                'name'=>'Advanced Business (20 Sponsored Jobs)',
                'start_date'=>date('Y-m-d H:i:s'),
                'end_date'=>date('Y-m-d H:i:s',strtotime("+30 days")),
                'limited'=>2,
				'joinCount'=>0,
				'postCount'=>0,
                'click_budget'=>'0',
                'daily_budget'=>0,
                'budget'=>0
                );
            Config::get('db')->insert('campaign',$data);

        } else{
            //$query = "update job set campaignId=0 where userName=".$_SESSION['account']['twitter_handle'];
            //Config::get('db')->query($query);
            $query = "update campaign set status=0 where account_id=".$_SESSION['account']['id'];
            Config::get('db')->query($query);
            $data = array(
                'account_id'=>$_SESSION['account']['id'],
                'name'=>'Trial Account (1 Sponsored Job)',
                'start_date'=>date('Y-m-d H:i:s'),
                'end_date'=>date('Y-m-d H:i:s',strtotime("+30 days")),
                'limited'=>0,
				'joinCount'=>0,
				'postCount'=>0,
                'click_budget'=>'0.00',
                'daily_budget'=>0,
                'budget'=>0
                );
            Config::get('db')->insert('campaign',$data);
		        
		} 
	  } header('location: dashboard.php');
	 
	} else {
        $error_msg = '<h4 style="color:red">Error Choosing Plan</h4>';
    }


?>

<!DOCTYPE html>

<!-- 

Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2

Version: 3.2.0

Author: KeenThemes

Website: http://www.keenthemes.com/

Contact: support@keenthemes.com

Follow: www.twitter.com/keenthemes

Like: www.facebook.com/keenthemes

Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes

License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.

-->

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!-->

<html lang="en">

<!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

    <meta charset="utf-8" />

    <title>JobAlarm Login</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />

    <meta content="" name="description" />

    <meta content="" name="author" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />

    <link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />

    <link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="theme/assets/admin/pages/css/pricing-table.css" rel="stylesheet" type="text/css" />

    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN THEME STYLES -->

    <link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />

    <link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />

    <link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css" />

    <link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color" />

    <link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css" />

    <!-- END THEME STYLES -->

    <link rel="shortcut icon" href="img/favicon.ico" />



    <script type="text/javascript">

            <!--

            function popup(mylink, windowname)

            {

                if (!window.focus)

                    return true;

                var href;

                if (typeof (mylink) == 'string')

                    href = mylink;

                else

                    href = mylink.href;

                window.open(href, windowname, 'width=400,height=380,scrollbars=no');

                return false;

            }

            //-->

    </script>



    <style type="text/css">



            .searchbtn {

                background: #3498db;

                background-image: -webkit-linear-gradient(top, #3498db, #2980b9);

                background-image: -moz-linear-gradient(top, #3498db, #2980b9);

                background-image: -ms-linear-gradient(top, #3498db, #2980b9);

                background-image: -o-linear-gradient(top, #3498db, #2980b9);

                background-image: linear-gradient(to bottom, #3498db, #2980b9);

                -webkit-border-radius: 6;

                -moz-border-radius: 6;

                border-radius: 6px;

                -webkit-box-shadow: 0px 1px 3px #666666;

                -moz-box-shadow: 0px 1px 3px #666666;

                box-shadow: 0px 1px 3px #666666;

                font-family: Arial;

                color: #ffffff;

                font-size: 20px;

                padding: 10px 20px 10px 20px;

                text-decoration: none;

            }



            .searchbtn:hover {

                background: #3cb0fd;

                background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);

                background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);

                background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);

                background-image: -o-linear-gradient(top, #3cb0fd, #3498db);

                background-image: linear-gradient(to bottom, #3cb0fd, #3498db);

                text-decoration: none;

            }

            .style1 {font-family: "Kristen ITC"}
.style3 {color: #000000}
    .style4 {
	color: #0099FF;
	font-weight: bold;
}
    </style>

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->

<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->

<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">



    <!-- BEGIN TOP BAR -->

    <div class="pre-header">

        <div class="container">

            <div class="row">

                <!-- BEGIN TOP BAR LEFT PART -->

                <div class="col-md-6 col-sm-6 additional-shop-info">

                    <ul class="list-unstyled list-inline"></ul>

                </div>

                <!-- END TOP BAR LEFT PART -->

                <!-- BEGIN TOP BAR MENU -->

                <div class="col-md-6 col-sm-6 additional-nav">

                    <ul class="list-unstyled list-inline pull-right">

                        <li>
                            <a href="">About</a>
                        </li>

                        <li>
                            <a href="/contact.php">Contact Us</a>
                        </li>

                        <li>
                            <a href="">Canada Jobs (Coming Soon)</a>
                        </li>

                    </ul>

                </div>

                <!-- END TOP BAR MENU -->

            </div>

        </div>

    </div>

    <!-- END TOP BAR -->



    <!-- BEGIN HEADER -->

    <div class="page-header page-header-smaller">

        <!-- BEGIN HEADER TOP -->

        <div class="page-header-top">

            <div class="container" style="margin:0 auto;width:250px;padding:0">

                <!-- BEGIN LOGO -->

                <div class="page-logo" style="width:245px">

                    <div align="center">
                        <a href="index.php">
                            <img src="img/logo1.png" longdesc="http://www.jobalarm.com">
                        </a>
                    </div>

                </div>

                <!-- END LOGO -->

            </div>

        </div>

        <!-- END HEADER TOP -->



    </div>

    <!-- END HEADER -->

    <!-- BEGIN PAGE CONTAINER -->

    <div class="page-container login" style="margin-bottom:30px">

        <!-- BEGIN PAGE CONTENT -->

        <!-- BEGIN PAGE CONTENT -->

        <div class="page-content">

            <div class="container">

                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

                                <h4 class="modal-title">Modal title</h4>

                            </div>

                            <div class="modal-body">
                                Widget settings form goes here

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn blue">Save changes</button>

                                <button type="button" class="btn default" data-dismiss="modal">Close</button>

                            </div>

                        </div>

                        <!-- /.modal-content -->

                    </div>

                    <!-- /.modal-dialog -->

                </div>

                <!-- /.modal -->

                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->


                <!-- BEGIN PAGE CONTENT INNER -->

                <div class="row">

                    <div class="col-md-12">

                        <!-- BEGIN INLINE NOTIFICATIONS PORTLET-->

                        <div class="portlet light">

                            <div class="portlet-title">

                                <div class="caption style3">
                                    Pricing Options
                                </div>
                                <a class="btn blue pull-right margin-top-10" href="logout.php"><i class="fa fa-sign-out"></i>Sign Out</a>
                            </div>

                            <div class="portlet-body">

                                <div class="row margin-bottom-40">

<?php if (!$billing_hold): ?>
                                    <!-- Pricing -->
                                    
                                  <div class="col-md-4">
                                    <div class="pricing hover-effect">

                                            <div class="pricing-head">
                                        <h3> Small Business <span> 5 Sponsored Job Slots <br />
                                          Unlimited Sponsored Clicks </span> </h3>
                                        <span class="font-lg style1"> <strong> <i>$19.00 per month</i> </strong> </span> </div>
                                            <ul class="pricing-content list-unstyled">
                                        <li> <span class="style3">Includes 5 Sponsored Job Slots</span> </li>
                                        <li> <span class="style3"> Sponsor Placement In Top  Page of Search Results (when available) </span> </li>
                                        <li> <span class="style3">Unlimited Sponsored Clicks </span> </li>
                                        <li> <span class="style3">Ability to interchange Job Tweets at any time</span> </li>
                                        <li> <span class="style3">Track Clicks for Each Job Tweet</span> </li>
                                        <li> <span class="style3">Share to <span class="style4">LOCAL JOB RELATED FACEBOOK GROUPS</span></span> </li>
                                        
                                      </ul>
                                      <div class="pricing-footer">
                                        <p></p>
                                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="EBYAH4W3RXWV4">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Small Business: $1 for two weeks, then $19 per month">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="http://www.jobalarm.com/dashboard.php">
<input type="hidden" name="cancel_return" value="http://www.jobalarm.com">
<input type="hidden" name="a1" value="1.00">
<input type="hidden" name="p1" value="2">
<input type="hidden" name="t1" value="W">
<input type="hidden" name="src" value="1">
<input type="hidden" name="a3" value="19.00">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:trynow.png:NonHosted">										
<input type="hidden" name="notify_url" value="https://jobalarm.com/payment.php" >
<input type="hidden" id="paypal_custom" name="custom" value="<?php echo $_SESSION['account']['id']; ?>" >
<a href="javascript:;" onClick="setPaymentPlan();" class="btn btn-primary"> Try It For $1 <i class="m-icon-swapright m-icon-white"></i> </a> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" >
                                        </form>
                                      </div>
                                    </div>
                                  </div>

                                    <div class="col-md-4">

                                        <div class="pricing hover-effect">

                                            <div class="pricing-head">

                                                <h3>Advanced Business <span>
                                                    20 Sponsored Job Slots
                                                    <br />
                                                        Unlimited Sponsored Clicks
                                                    </span>
                                              </h3>
											  <span class="font-lg style1"> <strong> <i>$59.00 per month</i> </strong> </span>

                                            </div>
                                            <ul class="pricing-content list-unstyled">
                                        <li> <span class="style3">Includes 20 Sponsored Job Slots</span> </li>
                                        <li> <span class="style3"> Sponsor Placement In Top  Page of Search Results (when available) </span> </li>
                                        <li> <span class="style3">Unlimited Sponsored Clicks </span> </li>
                                        <li> <span class="style3">Ability to interchange Job Tweets at any time</span> </li>
                                        <li> <span class="style3">Track Clicks for Each Job Tweet</span> </li>
                                        <li> <span class="style3">Share to LOCAL JOB RELATED GROUPS in Facebook</span> </li>
                                        
                                      </ul>
                                            <div class="pricing-footer">
											<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="business" value="EBYAH4W3RXWV4">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Advanced Business: $59 per month">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="return" value="http://www.jobalarm.com/dashboard.php">
<input type="hidden" name="cancel_return" value="http://www.jobalarm.com">
<input type="hidden" name="src" value="1">
<input type="hidden" name="a3" value="59.00">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="M">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribe_LG.gif:NonHosted">											
<input type="hidden" name="notify_url" value="https://jobalarm.com/payment.php" />
<input type="hidden" id="paypal_custom" name="custom" value="<?php echo $_SESSION['account']['id']; ?>" />
<a href="javascript:;" onClick="setPaymentPlan3();" class="btn btn-primary"> Sign Up <i class="m-icon-swapright m-icon-white"></i> </a> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                                        </form>
                                          </div>
											 
                                    </div>
									</div>
									<div class="col-md-4">

                                        <div class="pricing hover-effect">

                                            <div class="pricing-head">
                                                <h3>
                                                    ENTERPRISE
                                                    <span>
                                                        Unlimited Jobs &amp; <br>
                                                        Unlimited Sponsored Clicks                                                    </span>                                                </h3>
												<span class="font-lg style1"> <strong> <i>Contact Us </i></strong></span></div>

                                            
                                            <ul class="pricing-content list-unstyled">
                                                
                                                <li>
                                                    <span class="style3">Jobs can be added to JobAlarm.com either through Twitter or XML feed..                                                </span>                                                </li>
                                                <li>
                                                    <span class="style3">Dedicated Account Support</span>                                                </li>
                                                <li>
                                                    <span class="style3">Access to Texting Module if required*.</span>                                                </li>
                                                <li>
                                                    <span class="style3">Clicks will be directed to your job.  Candidates cannot apply on JobAlarm.</span>                                                </li>
												<li> <span class="style3">Share to LOCAL JOB RELATED GROUPS in Facebook</span> </li>
                                            </ul>
											
                                            <div class="pricing-footer">
											<p class="style3">                                              </p>
												<a href="mailto:support@jobalarm.com" class="btn btn-primary">
                                                    Contact Us
                                                    <i class="m-icon-swapright m-icon-white"></i>                                                </a>                                            </div>
                                        </div>
										<div><strong>*SMS charges may apply.</strong></div>
                                    </div>

                                    <!--//End Pricing -->
<?php endif; ?>
<?php if ($billing_hold): ?>
                                    <h1> Your account payment is currently processing.<br />It can take up to a couple of minutes.<br />Please try refreshing your browser or logging in again later.</h1>
<?php endif; ?>
                                </div>

                            </div>

                        </div>

                        <!-- END INLINE NOTIFICATIONS PORTLET-->

                    </div>

                </div>

                <!-- END PAGE CONTENT INNER -->

            </div>

        </div>

    </div>

    <!-- BEGIN FOOTER -->

    <div class="page-footer">

        <div class="container">

            <div align="center">
                2015 &copy; Premier SSG, Inc. All Rights Reserved.
                <br />

                Terms. Privacy Policy
            </div>



        </div>

    </div>

    <div class="scroll-to-top">

        <i class="icon-arrow-up"></i>

    </div>

    <!-- END FOOTER -->

    <!-- END PAGE CONTAINER -->



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

    <!-- BEGIN PAGE PLUGINS & SCRIPTS -->

    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>

    <script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>

    <!-- END PAGE PLUGINS & SCRIPTS -->

    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>

    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>

    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>

    <script>
            var setPaymentPlan = function() {
                $.ajax({
                    url:'chooseplan.php?pid=2',
                    success:function(data) {
                        $('#subscribe_form').submit();
                    }
                });
            }
            
            jQuery(document).ready(function () {

                Metronic.init(); // init metronic core components

                Layout.init(); // init current layout

                Demo.init(); // init demo features

                Todo.init(); // init todo page


                
            });

    </script>
	<script>
            var setPaymentPlan3 = function() {
                $.ajax({
                    url:'chooseplan.php?pid=3',
                    success:function(data) {
                        $('#subscribe_form').submit();
                    }
                });
            }
            
            jQuery(document).ready(function () {

                Metronic.init(); // init metronic core components

                Layout.init(); // init current layout

                Demo.init(); // init demo features

                Todo.init(); // init todo page


                
            });

    </script>
	

    <script>

            (function (i, s, o, g, r, a, m) {

                i['GoogleAnalyticsObject'] = r;

                i[r] = i[r] || function () {

                    (i[r].q = i[r].q || []).push(arguments)

                }, i[r].l = 1 * new Date();

                a = s.createElement(o),

                        m = s.getElementsByTagName(o)[0];

                a.async = 1;

                a.src = g;

                m.parentNode.insertBefore(a, m)

            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');



            ga('create', 'UA-59491934-1', 'auto');

            ga('send', 'pageview');



    </script>



    <script>!function (d, s, id) {

                var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';

                if (!d.getElementById(id)) {

                    js = d.createElement(s);

                    js.id = id;

                    js.src = p + '://platform.twitter.com/widgets.js';

                    fjs.parentNode.insertBefore(js, fjs);

                }

            }(document, 'script', 'twitter-wjs');</script>



    <div id="fb-root"></div>

    <script>(function (d, s, id) {

                var js, fjs = d.getElementsByTagName(s)[0];

                if (d.getElementById(id))

                    return;

                js = d.createElement(s);

                js.id = id;

                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";

                fjs.parentNode.insertBefore(js, fjs);

            }(document, 'script', 'facebook-jssdk'));</script>



    <!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>
