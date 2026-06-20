<?php
include "inc/initializer.php";
require_once '../inc/class.db.php';
require_once '../inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$dept = intval($_SESSION['account']['dept']);


?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>
        JobAlarm Reports Dashboard - Beta
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
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <!--begin::Page Vendors -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/responsive/2.2.0/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.9/css/dataTables.checkboxes.css" rel="stylesheet" />
    <link type="text/css" href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Page Vendors -->
    <link href="theme/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="theme/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Base Styles -->
    <link href="styles/main.css" rel="stylesheet" type="text/css"/>
    <!-- debug info -->
    <script>
    <?php
        echo '/*';
        var_dump(Config::get('account'));
        echo '*/';
    ?>
    </script>
    <!-- end debug info -->

    <style type="text/css">
<!--
.style1 {color: #0066FF}
-->
    </style>
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- BEGIN: Header -->
    <header class="m-grid__item    m-header " data-minimize-offset="200" data-minimize-mobile-offset="200">
        <div class="m-container m-container--fluid m-container--full-height">
            <div class="m-stack m-stack--ver m-stack--desktop">
                <!-- BEGIN: Brand -->
                <div class="m-stack__item m-brand  m-brand--skin-dark ">
                    <div class="m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-stack__item--middle m-brand__logo">
                            <a href="index.php" class="m-brand__logo-wrapper" style="font-size:26px;color:white !important;text-decoration:none">
                            Productive<span class="style1">RN</span>                            </a>                        </div>
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
                <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                    <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                        <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" >
                                <img src="../img/<?php echo $logo ?>" alt="logo" style="max-height:60px"/>
                            </li>
                        </ul>
                    </div>
                    <!-- BEGIN: Topbar -->
                    <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-topbar__nav-wrapper">
                            <ul class="m-topbar__nav m-nav m-nav--inline">
                                <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                    data-dropdown-toggle="click">
                                    <a href="#" class="m-nav__link m-dropdown__toggle">
                                        <span class="m-topbar__userpic">
                                           <i class="flaticon-user" style="font-size:28px"></i>
                                        </span>
                                        <span class="m-topbar__username m--hide">
                                            Nick
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
                                                        <span class="m-card-user__name m--font-weight-500">
                                                            <?php echo Config::get('account')['first_name'].' '.Config::get('account')['last_name']; ?>
                                                        </span>
                                                        <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                                            <?php echo Config::get('account')['email']; ?>
                                                        </a>
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
                                                        <li class="m-nav__item">
                                                            <a href="#profile" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                <span class="m-nav__link-title">
                                                                    <span class="m-nav__link-wrap">
                                                                        <span class="m-nav__link-text">
                                                                            Profile
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </a>
                                                        </li>
<!--                                                        <li class="m-nav__item">-->
<!--                                                            <a href="../../../header/profile.html" class="m-nav__link">-->
<!--                                                                <i class="m-nav__link-icon flaticon-share"></i>-->
<!--                                                                <span class="m-nav__link-text">-->
<!--                                                                    Activity-->
<!--                                                                </span>-->
<!--                                                            </a>-->
<!--                                                        </li>-->
<!--                                                        <li class="m-nav__item">-->
<!--                                                            <a href="../../../header/profile.html" class="m-nav__link">-->
<!--                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>-->
<!--                                                                <span class="m-nav__link-text">-->
<!--                                                                    Messages-->
<!--                                                                </span>-->
<!--                                                            </a>-->
<!--                                                        </li>-->
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
<!--                                                        <li class="m-nav__item">-->
<!--                                                            <a href="../../../header/profile.html" class="m-nav__link">-->
<!--                                                                <i class="m-nav__link-icon flaticon-info"></i>-->
<!--                                                                <span class="m-nav__link-text">-->
<!--                                                                    FAQ-->
<!--                                                                </span>-->
<!--                                                            </a>-->
<!--                                                        </li>-->
                                                        <li class="m-nav__item">
                                                            <a href="#supportModal" id="supportDialog" data-toggle="modal">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">
                                                                    Support
                                                                </span>
                                                            </a>
                                                        </li>
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
                    </div>
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
            <div
                id="main_menu"
                class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
                data-menu-vertical="true"
                data-menu-scrollable="false" data-menu-dropdown-timeout="500">
                <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow ">
                    <li class="m-menu__item m-menu__item--active" aria-haspopup="true">
                        <a href="#report" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-line-graph"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        Dashboard
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					<li class="m-menu__item" aria-haspopup="true">
                        <a href="#staffing" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-users"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        Staffing Reports
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					<?php if($role >4): ?>
                    <li class="m-menu__item" aria-haspopup="true">
                        <a href="#users" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-user"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        Users
                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
					<?php endif; ?>
					<hr></hr>
					
                    <li class="m-menu__item" aria-haspopup="true">
                        <a href="logout.php" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-logout"></i>
                            <span class="m-menu__link-title">
                                <span class="m-menu__link-wrap">
                                    <span class="m-menu__link-text">
                                        Logout
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
		
	    <div class="modal fade" id="supportModal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
				<h2 class="modal-title">Please provide your support details and we will contact you within 24 hours.</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      
                </div>
                <div id="supportBody" class="modal-body">


                    <div class="form-group">
                			<select id="supportModalType"  name="type" type="select" class="form-control" placeholder="Select">
                			<option value="Select">Support Type....</option>
                			<option value="Password">Technical Support</option>
                			<option value="Tech Support">Other</option></select>
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
	

