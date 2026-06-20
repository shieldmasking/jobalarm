<?php
ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
session_start();

include_once './inc/class.db.php';
include_once './inc/config.php';

if (!isset($_SESSION['account'])) {
    header('location: logout.php');
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
<meta charset="utf-8"/>
<title>ProductiveRN Password Update</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">-->
<link href="theme/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="theme/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="theme/assets/global/plugins/select2/select2.css"/>
<link href="theme/assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="theme/assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="theme/assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="theme/assets/admin/layout3/css/themes/blue-steel.css" rel="stylesheet" type="text/css" id="style_color">
<link href="theme/assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<!--
<link rel="shortcut icon" href="/favicon.ico"/>
-->
<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=400,height=380,scrollbars=no');
return false;
}
//-->
</SCRIPT>



<style type="text/css">


/* Style all input fields */
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-top: 6px;
    margin-bottom: 16px;
}

/* Style the submit button */
input[type=submit] {
    background-color: #4CAF50;
    color: white;
}

/* Style the container for inputs */
.container {
    background-color: #f1f1f1;
    padding: 20px;
}

/* The message box is shown when the user clicks on the password field */
#message {
    display:none;
    background: #f1f1f1;
    color: #000;
    position: relative;
    padding: 20px;
    margin-top: 10px;
}

#message p {
    padding: 10px 35px;
    font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
    color: green;
}

.valid:before {
    position: relative;
    left: -35px;
    content: "&#10004;";
}

/* Add a red text color and an "x" icon when the requirements are wrong */
.invalid {
    color: red;
}

.invalid:before {
    position: relative;
    left: -35px;
    content: "&#10006;";
}
</style>
<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body class="page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo page-sidebar-closed">

    <!-- BEGIN TOP BAR -->
    <div class="pre-header">
        
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        
                        
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    <ul class="list-unstyled list-inline pull-right">
                        
                        <li><a href="/contact.php">Contact Us</a></li>
						
                    </ul>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
               
    </div>
    <!-- END TOP BAR -->

<!-- BEGIN HEADER -->
<div class="page-header page-header-smaller">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container" style="margin:0 auto;padding:0">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<div align="center"><a href="index.php"><img src="/img/productive.png" longdesc="https://www.productivern.com"></a></div>
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
    <div class="content">
	    <form action="/login.php">
		
		<label for="psw">Password</label>
		<input type="password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>

		<label for="usrname">Confirm</label>
		<input type="text" id="usrname" name="usrname" required>
		
		<input type="submit" value="Submit">
		</form>
			
		<div id="message">
		  <h3>Password must contain the following:</h3>
		  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
		  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
		  <p id="number" class="invalid">A <b>number</b></p>
		  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
		</div>
	    
    </div>
	<!-- END PAGE CONTENT -->
</div>
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
	<div align="center">2018 &copy; Harrelson Group LLC. All Rights Reserved.<br>
	  Terms. Privacy Policy </div>
	
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
<script src="/theme/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/theme/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/theme/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE PLUGINS & SCRIPTS -->
<script type="text/javascript" src="/theme/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/theme/assets/global/plugins/select2/select2.min.js"></script>
<script src="/theme/assets/admin/pages/scripts/todo.js" type="text/javascript"></script>
<!-- END PAGE PLUGINS & SCRIPTS -->
<script src="/theme/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="/theme/assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>
<script src="scripts/main.js" type="text/javascript"></script>
<!--
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
Todo.init(); // init todo page
});
</script>
-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

</script>

<script>
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) { 
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
}

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) { 
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) { 
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>




<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
