<?php
include "inc/initializer.php";

if(intval($_SESSION['account']['role'])==4){
	$manager = 'hidden';
	$director = 'hidden';
	$user = '';
	$userReports = '';
	$admin = 'hidden';
}else if(intval($_SESSION['account']['role'])==6){
	$manager = '';
	$user = '';
	$userReports = '';
	$director = 'hidden';
	$admin = 'hidden';
}else if(intval($_SESSION['account']['role'])==7){
	$director = '';
	$manager = '';
	$user = '';
	$admin = 'hidden';
	$userReports = '';
}else if(intval($_SESSION['account']['role'])>=8 && intval($_SESSION['account']['role'])<=10){
	$director = '';
	$manager = 'hidden';
	$user = '';
	$admin = 'hidden';
	$userReports = '';
}else if(intval($_SESSION['account']['role'])==11){
	$director = '';
	$manager = '';
	$user = '';
	$admin = '';
	$userReports = '';
}else if(intval($_SESSION['account']['role'])>89){
	$admin = '';
	$manager = '';
	$director = '';
	$user = 'hidden';
	$userReports = 'hidden';
}else if(intval($_SESSION['account']['role'])>11 && intval($_SESSION['account']['role'])<90){
	$director = 'hidden';
	$manager = 'hidden';
	$user = '';
	$userReports = 'hidden';
	$admin = 'hidden';
}else{
	$manager = 'hidden';
	$director = 'hidden';
	$user = '';
	$userReports = 'hidden';
	$admin = 'hidden';
}

if(intval($_SESSION['account']['role'])<12){
	$logo = $_SESSION['account']['logo'];
}else{
	$logo = $_SESSION['account']['logo'];
}


if (intval($_SESSION['account']['prodDistinct'])==1 && intval($_SESSION['account']['prodMeasure'])==2 && intval($_SESSION['account']['role'])<10){
	$support = '';
	$nursing = 'hidden';
}else if (intval($_SESSION['account']['prodDistinct'])==1 && intval($_SESSION['account']['prodMeasure'])!=2 && intval($_SESSION['account']['role'])<10){
	$support = 'hidden';
	$nursing = '';
}else if (intval($_SESSION['account']['prodDistinct'])>1 && intval($_SESSION['account']['role'])<90) {
	$support = '';
	$nursing = '';
}else{
	$support = '';
	$nursing = '';
}




?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>
      <?php echo $_SESSION['account']['labelName']; ?>  
    </title>
    <meta name="description" content="Base button default style">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
	<style>
	.dashboard-stat {
		height:80%;
	}
	.m-subheader {
		height:80%;
	}
	</style>
<!--
	<style>
/* Tooltip container */
.mr-auto {
  position: relative;
  display: inline-block;
}

/* Tooltip text */
.mr-auto .tooltiptext {
  visibility: hidden;
  width: 200px;
  background-color: blue;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  width: 120px;
  left: 50%; 
  margin-left: -30px;
}

/* Show the tooltip text when you mouse over the tooltip container */
.mr-auto:hover .tooltiptext {
  visibility: visible;
}
</style>
-->	
<style>
body {
	font-size: 16px;
    }
</style>
<style>
.modal {
    overflow-y: scroll;
}
</style>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <!--begin::Page Vendors -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link type="text/css" href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Page Vendors -->
    <link href="theme/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="theme/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Base Styles -->
    <link href="styles/main.css" rel="stylesheet" type="text/css"/>
	<?php if($_SESSION['account']['labelId']>1){ ?> 
	<link rel="shortcut icon" href="/img/<?php echo $_SESSION['account']['favicon']; ?>" />
	<? } ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.0/jspdf.min.js"></script>
    <!-- debug info -->
   
<script>
    <?php
        echo '/*';
        var_dump(Config::get('account'));
        echo '*/';
    ?>
    </script>
</head>
<!-- end::Head -->

<!--<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
-->
<body>
<!-- begin:: Page -->
<!--<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-body">-->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- BEGIN: Header -->
    <header class="m-grid__item m-header">
        <div class="m-container m-container--fluid m-container--full-height">
            <div class="m-stack m-stack--ver m-stack--desktop">
                <!-- BEGIN: Brand -->
                <div class="m-stack__item m-brand  m-brand--skin-dark ">
                    <div class="m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-stack__item--middle m-brand__logo">
						<!--<span id="label"></span>-->
						<img src="../img/<?php echo $logo; ?>" alt="logo" style="max-height:60px"/> 
					
						</div>
                        <div class="m-stack__item m-stack__item--middle m-brand__tools">
                            <!-- BEGIN: Left Aside Minimize Toggle -->
                            <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                                <span></span>
                            </a>
                            <!-- END -->
                            <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                            <a href="javascript:;" id="m_aside_left_offcanvas_toggle"
                               class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                <span></span>
                            </a>
                            <!-- END -->
                        </div>
                    </div>
                </div>
                <!-- END: Brand -->
                <div class="m-stack__item m-header-head" id="m_header_nav">
                    <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                        <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" >
							
							<img src="../img/<?php echo $logo1; ?>" alt="logo" style="max-height:60px"/>
                            </li>
                        </ul>
                    </div>
                    <!-- BEGIN: Topbar -->
                    <span id="m_header_topbar" class="m-topbar m-stack m-stack--ver" style="padding-right: 25px;">
                        <div class="m-stack__item m-topbar__nav-wrapper">
                            <ul class="m-topbar__nav m-nav m-nav--inline">
                                <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                    data-dropdown-toggle="click">
                                    <a href="#" class="m-nav__link m-dropdown__toggle">
                                        <span class="m-topbar__userpic">
                                           <i class="flaticon-user" style="font-size:28px"></i>
                                        </span>
                                        <span class="m-topbar__username m--hide">
                                            
                                        </span>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__header m--align-center"
                                                 style="background: url(theme/assets/app/media/img/misc/quick_actions_bg.jpg); background-size: cover;">
                                                <div class="m-card-user m-card-user--skin-dark">
                                                    <div class="m-card-user__pic">
                                                        <i class="flaticon-user" style="font-size:32px;color:#fff"></i>
                                                    </div>

                                                    <div class="m-card-user__details">
                                                        <span class="m-card-user__name m--font-weight-500" id="name"><?php echo $_SESSION['account']['first_name'] . " " . $_SESSION['account']['last_name']; ?>
                                                                               </span>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav m-nav--skin-light">
                                                        <li class="m-nav__section m--hide">
                                                            <span class="m-nav__section-text">
                                                                Section
                                                            </span>
                                                        </li>
														
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
														

														<li class="m-nav__item">
                                                            <a href="javascript:;" id="activateText" onclick="tj.activate(<?php echo $_SESSION['account']['id']; ?>)">
                                                                <i class="m-nav__link-icon flaticon-comment"></i>
                                                                <span class="m-nav__link-text"> Notifications</span>
                                                            </a>
                                                        </li>
														
														<li class="m-nav__separator m-nav__separator--fit"></li>
                                                        <li class="m-nav__item">
														<?php if($_SESSION['account']['supportLink']){ ?>
															<a href="<?php echo $_SESSION['account']['supportLink']; ?>" target="_blank" id="supportLink">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text"> Support</span>
                                                            </a>
														<? }else{ ?>	
                                                            <a href="#supportModal" id="supportDialog" data-toggle="modal">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text"> Support</span>
                                                            </a>
														<? } ?>
                                                        </li>
														<li class="m-nav__separator m-nav__separator--fit"></li>
														<?php if($_SESSION['account']['videoLink']): ?>
                                                        <li class="m-nav__item">
															<a href="<?php echo $_SESSION['account']['videoLink']; ?>" target="_blank" id="videoLink">
                                                                <i class="m-nav__link-icon flaticon-list"></i>
                                                                <span class="m-nav__link-text"> How to Videos</span>
                                                            </a>
														</li>
														<?php endif; ?>
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
                                                        <li class="m-nav__item">
                                                            <a href="logout.php"
                                                               class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                                Logout
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </span>
                    <!-- END: Topbar -->
                </div>
            </div>
        </div>
    </header>
    <!-- END: Header -->
	
    <!-- begin::Body -->
 <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <!-- BEGIN: Left Aside -->
        <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
            <i class="la la-close"></i>
        </button>
 <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
            <!-- BEGIN: Aside Menu -->
            <div id="main_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark" data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
                <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow "> 
					<li class="m-menu__item m-menu__item--active" aria-haspopup="true" <?php echo $user; ?>>
                        <a href="#reports" class="m-menu__link " onclick="$('#m_aside_left_close_btn').click();">
                            <i class="m-menu__link-icon flaticon-line-graph"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                     <h5>   Dashboard</h5>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					 
					<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-dropdown-toggle-class="m-aside-menu-overlay--on" <?php echo $user; ?>>
					<a href="#" class="m-menu__link m-menu__toggle">
					   <i class="m-menu__link-icon flaticon-list"></i>
                                    <span class="m-menu__link-text">
                                        <h5>Productivity</h5>
                                    </span>
                       <i class="m-menu__ver-arrow la la-angle-right"></i>
                       </a>
					   	<div class="m-menu__submenu">
						
						<ul class="m-menu__subnav">
						<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;" <?php echo $nursing; ?>>
						<a href="#staffing" class="m-menu__link m-menu__toggle" onclick="$('#m_aside_left_close_btn').click();">
						<span class="m-menu__link-text"><h5>Nursing</h5></span>
						</a>
						</li>
						<li class="m-menu__item m-menu__item--submenu pull-right" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;"<?php echo $support; ?>>
						<a href="#supportstaffing" class="m-menu__link m-menu__toggle" onclick="$('#m_aside_left_close_btn').click();">
						<span class="m-menu__link-text"><h5>Support</h5></span>
						
						</a>
						</li>
						</ul>
					
						</div>
                    </li>
					
					<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-dropdown-toggle-class="m-aside-menu-overlay--on" <?php echo $user; ?>>
					<a href="#" class="m-menu__link m-menu__toggle">
					   <i class="m-menu__link-icon flaticon-users"></i>
                                    <span class="m-menu__link-text">
                                        <h5>Reports</h5>
                                    </span>
                       <i class="m-menu__ver-arrow la la-angle-right"></i>
                       </a>
					   	<div class="m-menu__submenu">
						
						<ul class="m-menu__subnav">
						<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;">
						<a href="#performance" class="m-menu__link m-menu__toggle" onclick="$('#m_aside_left_close_btn').click();">
						<span class="m-menu__link-text"><h5>Users</h5></span>
						</a>
						</li>
						<li class="m-menu__item m-menu__item--submenu pull-right" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;"<?php echo $user; ?>>
						<a href="#escalations" class="m-menu__link m-menu__toggle" onclick="$('#m_aside_left_close_btn').click();">
						<span class="m-menu__link-text"><h5>Escalations</h5></span>
						</a>
						</li>
						<li class="m-menu__item m-menu__item--submenu pull-right" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;"<?php echo $user; ?>>
						<a href="#compliance" class="m-menu__link m-menu__toggle" onclick="$('#m_aside_left_close_btn').click();">
						<span class="m-menu__link-text"><h5>Compliance</h5></span>
						</a>
						</li>
						
						</ul>
					
						</div>
                    </li>
					<!--
					<li class="m-menu__item" aria-haspopup="true" >
                        <a href="#performance" class="m-menu__link " onclick="$('#m_aside_left_close_btn').click();" <?php echo $userReports; ?>>
                            <i class="m-menu__link-icon flaticon-users"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        <h5>User Reports</h5>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					<li class="m-menu__item" aria-haspopup="true" <?php echo $user; ?>>
					   <a href="#escalations" class="m-menu__link " onclick="$('#m_aside_left_close_btn').click();">
                            <i class="m-menu__link-icon flaticon-danger"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        <h5>Escalations</h5>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					-->
					<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-dropdown-toggle-class="m-aside-menu-overlay--on" <?php echo $manager; ?>>
					<a href="#" class="m-menu__link m-menu__toggle">
					   <i class="m-menu__link-icon flaticon-settings"></i>
                                    <span class="m-menu__link-text">
                                        <h5>Admin</h5>
                                    </span>
                       <i class="m-menu__ver-arrow la la-angle-right"></i>
                       </a>
					   	<div class="m-menu__submenu">
						
						<ul class="m-menu__subnav">
						<li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;" <?php echo $manager; ?>>
						<a href="#users" class="m-menu__link m-menu__toggle">
						<span class="m-menu__link-text"><h5>User Manager</h5></span>
						</a>
						</li>
						<li class="m-menu__item m-menu__item--submenu pull-right" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;"<?php echo $manager; ?>>
						<a href="#units" class="m-menu__link m-menu__toggle">
						<span class="m-menu__link-text"><h5>Unit Manager</h5></span>
						
						</a>
						</li>
						<li class="m-menu__item m-menu__item--submenu pull-right" aria-haspopup="true" data-menu-submenu-toggle="click" data-menu-submenu-mode="accordion" style="padding-left: 20px;" <?php echo $admin; ?>>
						<a href="#accounts" class="m-menu__link m-menu__toggle">
						<span class="m-menu__link-text"><h5>Accounts</h5></span>
						
						</a>
						</li>
						</ul>
					
						</div>
                    </li>
					<hr></hr>
					
                    <li class="m-menu__item" aria-haspopup="true">
                        <a href="logout.php" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-logout"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        <h5>Logout</h5>
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
						
                </ul>
            </div>
            <!-- END: Aside Menu -->
        </div>
        <!-- END: Left Aside -->
		
	<div class="modal fade" id="activate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="col-sm-6">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Text & Email Notifications</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				
                    <!--begin::Form-->
                    <form id="activate_user_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="activateuserId" type="hidden" value="" />
						
						<div class="m-portlet__body">
                            						
							<div class="form-group" id="activateAlerts">
							
										<div class="form-group-label">
										<h5>Subscribe to Text Alerts</h5>
										</div>	
										<div class="modal-title">To activate texting, enter your 10 digit mobile number and click the Activate button.  Message and data rates may apply.  You can text STOP to cancel or update your configuration below.
										</div>
										
										
										<div class="row" style="margin-top:5px;margin-bottom:8px">
										<label for="activateMobile" class="col-8 col-form-label">
										<strong>Mobile Number (2145551234)</strong>
										</label>
										<div class="col-sm-6">
											<input class="form-control m-input" type="number" maxlength="10" value="" id="activateMobile" required>
										</div>
										<div class="col-sm-6">
										
						                <button type="button" class="btn btn-success" onclick="tj.optin2();">Activate</button>                         
									
										</div>
										</div>
												
										
										<hr></hr>
										<div class="form-group">
										<h5>Configure Text Alerts</h5>
										
										<div class="modal-title">Select when and what types of Text Alerts you would like to receive.  <p><strong>Default selections for a Manager is all reports, days only.</strong></p>
										</div>	
										</div>
										
										<div class="form-group">
										<input type="checkbox" id="report" name="report" value="1">
										 <strong>Report Alerts:</strong>  Receive Text Alerts when Staffing Reports are submitted.
										</input>
										</div>
										<div class="form-group">
										<input type="checkbox" id="missed" name="missed" value="1">
										 <strong>Missed Reports:</strong>  Receive Text Alerts when Staffing Reports are not submitted as scheduled.
										</input>
										</div>
										<div class="form-group">
										<input type="checkbox" id="escalation" name="escalation" value="1"> <strong>Escalation Alerts:</strong>  Receive Text Alerts when Escalations are submitted.
										</input>
										</div>
										<label for="times" class="col-6 col-form-label">
										<strong>Alert Times (Report and Missed Alerts):</strong>
										</label>
										<div class="col-9">
											<select id="times" type="select" class="form-control">
												<option value="0">Days (7am - 7pm)</option>
												<option value="1">Nights (8pm - 6am)</option>
												<option value="3">Days & Nights</option>
												</select>
										</div>
																				
																			
										</div>
										
										<div class="form-group" id="activateSelect">
										<hr></hr>
										<div class="form-group">
										<h5>Stop/Pause Text Alerts</h5>
										</div>
										<input type="checkbox" id="pause" name="pause" value="1"/>
										 STOP all text alerts for this user.
										</input>
										</div>
										
										<div class="form-group" id="emailConfig" <?php echo $manager; ?>>
										<hr></hr>
										<div class="form-group">
										<h5>Email Alerts</h5>
										</div>
										<div class="form-group">
										<input type="checkbox" id="emailAlerts" name="emailAlerts"/>
										 Receive an Email Alert when Productivity and Escalation Reports are submitted.
										</input>
										</div>
										<div class="form-group">
										<input type="checkbox" id="emailMissed" name="emailMissed"/>
										 Receive an Email Alert when Productivity Reports are due but not submitted.
										</input>
										</div>
										</div>
										
										</div>
							
                          
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateUser2();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
		
	    <div class="modal fade" id="supportModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
				<h2 class="modal-title">Please provide your support details and we will contact you within 24 hours.</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      
                </div>
                <div id="supportBody" class="modal-body">

					<form id="support_form" class="m-form m-form--fit m-form--label-align-right">
                    <div class="form-group">
                			<select id="supportModalType"  name="type" type="select" class="form-control" placeholder="Select">
                			<option value="">Support Type....</option>
                			<option value="Technical Support">Technical Support</option>
							<option value="Sales">Sales</option>
                			<option value="Other">Other</option></select>
          		      </div>
                    <div class="form-group"><label>Name</label><input id="supportModalName" class="form-control required" placeholder="Your name" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text"></div>
                    <div class="form-group"><label>Phone</label><input id="supportModalPhone" class="form-control phone" placeholder="999-999-9999" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text"></div>
                    <div class="form-group"><label>E-Mail</label><input id="supportModalEmail" class="form-control" placeholder="Your email here.." data-placement="top" data-trigger="manual"></input></div>
                    <div class="form-group"><label>Message</label><textarea id="supportModalMessage" class="form-control" placeholder="Your message here.." data-placement="top" data-trigger="manual"></textarea></div>
              	    
					</form>

				        </div>
                <div class="modal-footer">
				<div class="form-group"><button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;<button id="supportModalSubmitButton" type="submit" class="btn btn-success">Send</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
</body>
</html>	

