<?php
// Email Submit
// Note: filter_var() requires PHP >= 5.2.0
if ( isset($_POST['email']) && isset($_POST['name']) && isset($_POST['text']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
    
    // detect & prevent header injections
    $test = "/(content-type|bcc:|cc:|to:)/i";
    foreach ( $_POST as $key => $val ) {
        if ( preg_match( $test, $val ) ) {
            echo json_encode(array('success'=>false));
            exit;
        }
    }
    $company = (isset($_POST["company"])  && strlen($_POST['company']) > 0) ?  ", ".$_POST['company'] : '';
    //send email
    mail( "info@jobalarm.com", "JobAlarm Contact Form: ".$_POST['name'].$company, $_POST['name']."\r\n\r\n".$_POST['company']."\r\n\r\n".$_POST['email']."\r\n\r\n".$_POST['text'], "From: JobAlarm ContactUs <info@jobalarm.com>"  );
    echo json_encode(array('success'=>true));
    exit();
}
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
    <title>JobAlarm | Contact Us</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
    <link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
    <link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css" />
    <link rel="stylesheet" type="text/css" href="theme/assets/admin/pages/css/todo.css" />
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
    <link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
    <link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
    <link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
    <link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <style type="text/css">
        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
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

        <div class="container">
            <!-- BEGIN LOGO -->

            <div class="page-logo" align="center">
                <img src="img/firm.jpg">
            </div>


            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->

            <!-- END RESPONSIVE MENU TOGGLER -->

        </div>

        <!-- END HEADER TOP -->
        <!-- BEGIN HEADER MENU -->

        <!-- END HEADER MENU -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN PAGE CONTENT -->
    <div class="page-content">
        <div class="container">
            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <!-- /.modal -->
            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <!-- BEGIN PAGE BREADCRUMB -->
            <!-- END PAGE BREADCRUMB -->
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="portlet light">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Google Map -->
                            <div class="row margin-bottom-20">
                                <div class="col-md-6">
                                    <div class="space20">
                                    </div>
                                    <!-- BEGIN FORM-->
                                    <form action="jobs.php" id="cecform" name="cecform" method="post"  enctype="multipart/form-data">
                                        <input type="hidden" name="submitting_candidate" value="1" />
                                        <input type="hidden" name="for_company" value="126" />

                                        <h3 class="form-section"><strong>Why Mattress Firm? </strong></h3>
                                        <p>
                                            At Mattress Firm, we promote from within, reward success and foster a culture of fun and friendship. There has never been a better time to see what Mattress Firm can mean to you!
                                        </p>
                                        <p>Please provide us with the following information and we will contact you via text or email when we have job openings that fit you.  We will also provide you with a list of current openings in your area.</p>

                                        <div class="form-group">
                                            <select id="job_type" name="job_type" type="select" class="form-control" placeholder="Select a job">
                                                <option value="Select">Select a job....</option>
                                                <option value="Formable">Sales Manager</option>
                                                <option value="Custom">Distribution Center</option>
                                                <option value="Other">Corporate Office</option>
                                            </select>
                                        </div>



                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-user"></i>
                                                <input id="contact_name" name="contact_name" type="text" class="form-control" placeholder="First Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-user"></i>
                                                <input id="contact_lastname" name="contact_lastname" type="text" class="form-control" placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-envelope"></i>
                                                <input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Email Address">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-mobile"></i>
                                                <input id="contact_mobile" name="contact_mobile" type="text" class="form-control" placeholder="Mobile Number">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-briefcase"></i>
                                                <input id="zipcode" name="zipcode" type="text" class="form-control" placeholder="Zip Code" />
                                            </div>
                                        </div>
										<!--
                                        <div class="form-group">
                                            <label for="resume_paste">Paste Resume:</label>
                                            <textarea class="form-control" style="height:80px" id="resume_paste" name="resume_paste"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="resume_file">Upload Resume:</label>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    Browse <input type="file" id="resume_file" name="resume_file" />
                                                </span>
                                            </div>
                                        </div>
                                        	<button onClick="window.location.href='http://www.jobalarm.com/tweetlist3.php?search_keyword=chuck+e+cheese&zipcode='+cecform["zipcode"]">Submit</button> <p>Note:  If you do not wish to be contacted about future job openings, you can enter just your zip code above and still get a list of current openings in your area. 
								   /> -->
                                        <input type="hidden" id="search_keyword" name="search_keyword" value="mattress firm" />
                                        <input type="hidden" id="username" name="username" value="JobAlarm" />
                                        <input type="submit" value="Submit" />
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
    <!-- END PAGE CONTENT -->

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
    <!-- BEGIN PAGE PLUGINS & SCRIPTS -->
    <script type="text/javascript" src="theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="theme/assets/global/plugins/select2/select2.min.js"></script>
    <script src="theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
    <!-- END PAGE PLUGINS & SCRIPTS -->
    <script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
    <script src="theme/assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
    <script src="theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
    <script src="theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
    <script src="theme/assets/admin/pages/scripts/contact-us.js"></script>
    <script>
        jQuery(document).ready(function () {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            Demo.init(); // init demo features
            Todo.init(); // init todo page
            tj = {};
            tj.selectIndustry = function (id) {
                var base_url = window.location.href.split('?')[0];
                window.location = base_url + "?i=" + id + '&k=<?php echo $keywords?>&l=<?php echo $location;?>';
}
    ContactUs.init();


});
    </script>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date(); a = s.createElement(o),
            m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-59491934-1', 'auto');
        ga('send', 'pageview');

    </script>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
