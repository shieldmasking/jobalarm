<?php
//require_once 'class.phpmailer.php';

   if(isset($_POST['contact'])){
	   
	   $name = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
	   $company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
	   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
	   $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

	   $message = "\nName    : ".$name.'<br><br>';  
	   $message .= "\nCompany    : ".$company.'<br><br>';                   
	   $message .= "\nEmail   : ".$email.'<br><br>'; 
	   $message .= "\nMobile Number   : ".$phone.'<br><br>';  
	   $message .= $_POST['message'];
	   
		   
	   $to = 'rstrenger@buildersurfaces.com';               
				 
	   $headers  = 'MIME-Version: 1.0' . "\r\n";
	   $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$subject = "Contact Request";
	  
   
	   // Additional headers
	   $headers .= 'To: '.$name.' <'.$to.'>' . "\r\n";
	   $headers .= 'From: <buildadmin@buildersurfaces.com>' . "\r\n";  
									   
	   mail($to, $subject, $message, $headers);  
	  
	   
	   $message = "<div class='success'>Your message has been sent and we will contact you asap.</div>";                                                
   }

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="content-type" content="text/html" />
<meta charset="utf-8">
<meta name="description" content="">
<meta name="keywords" content="Bootstrap4, Multipurpose, responsive, Business, template,Shipping, Cargo, logistic, html5, css, javascript" />
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Title -->
<title>Builder Surfaces Contact</title>

<!-- Favicon -->
<link rel="shortcut icon" type="image/png" href="assets/images/logopic.jpg">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Font awesome CSS -->
<link rel="stylesheet" href="assets/css/fontawesome.all.min.css">

<!-- Animate CSS -->
<link rel="stylesheet" href="assets/css/animate.min.css">

<!-- OwlCarousel CSS -->
<link rel="stylesheet" href="assets/css/owl.carousel.min.css">

<!-- Magnific popup CSS -->
<link rel="stylesheet" href="assets/css/fancybox.min.css">

<!-- chat CSS -->
<link rel="stylesheet" href="assets/css/chat.css">

<!-- Slicknav CSS -->
<link rel="stylesheet" href="assets/css/slicknav.min.css">

<!-- Date picker CSS -->
<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

<!-- Main CSS -->
<link rel="stylesheet" href="style.css">

<!-- Responsive CSS -->
<link rel="stylesheet" href="assets/css/responsive.css">

<!-- jQuery -->
<script src="assets/js/jquery-3.4.1.min.js"></script>
</head>

<body>
<!-- Main Wrapper Start -->
<div class="main-wrapper"> 
  
  <!-- skiptocontent start ( This section for blind and Google SEO, Also for page speed )--> 
  <a id="skiptocontent" href="#maincontent">skip navigation</a> 
  <!-- skiptocontent End -->
  
   <header class="for-sticky"> 
    <!-- Header top area start -->
    <div class="header-top-area">
      <div class="container">
        <div class="row">
          <div class="col-12 col-lg-8">
            <div class="top-contact"> <a href="#"><i class="fa fa-envelope"></i> sales@buildersurfaces.com</a> </div>
          </div>
          <div class="col-12 col-lg-4 d-flex justify-content-center justify-content-lg-end">
            <div class="top-menu">
              <ul>
                <li><a href="customer-login.html"><i class="fas fa-sign-in-alt"></i> Login</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Header top area End --> 
    
    <!-- Header area start -->
    <div class="header-area">
      <div class="container"> 
        <!-- Site logo Start -->
        <div class="logo"> <a href="index.html" title="W-shipping"><img src="assets/images/logo.png" alt="W-shipping"/></a> </div>
        <!-- Site logo end -->
        <div class="mobile-menu-wrapper"></div>
        <!-- Search Start -->
		<!--
        <div class="dropdown header-search-bar">
          <form action="index.html">
            <span class="" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i></span>
            <input type="search" placeholder="kyewords.." class="dropdown-menu search-box">
          </form>
        </div>
		-->
        <!-- Search End --> 
        
        <!-- Main menu start -->
        <nav class="mainmenu">
          <ul id="navigation">
		   <li><a href="#">Products</a>
              <ul>
                <li><a href="image-gallery-bath.html">Bathtubs</a></li>
				<li><a href="image-gallery-vanities.html">Vanities</a></li>
                <li><a href="image-gallery-kitchen.html">Kitchen</a></li>
                <li><a href="image-gallery-flooring.html">Flooring</a></li>
				<li><a href="image-gallery-roofing.html">Roofing</a></li>
              </ul>
            </li>
             <li><a href="#">Expertise</a>
              <ul>
                <li><a href="design.html">Product Development</a></li>
                <li><a href="manufacture.html">Surfaces</a></li>
				<li><a href="import.html">Logistics</a></li>
              </ul>
            </li>
			<!--
            <li><a href="#">Shipping</a>
              <ul>
                <li><a href="create-shipping.html">Create New Shipment</a></li>
                <li><a href="current-shipment.html">Current Shipment list</a></li>
                <li><a href="shipment-history.html">Shipment History</a></li>
              </ul>
            </li>
			
            <li><a href="#">Tracking</a>
              <ul>
                <li><a href="customer-login.html">Customer Login</a></li>
                <li><a href="customer-register.html">Customer Register</a></li>
                <li><a href="tracking.html">Tracking</a></li>
                <li><a href="tracking-result.html">Your shipment progress</a></li>
              </ul>
            </li>
			-->
            
			
            <li><a href="#">About Us</a>
              <ul>
                <li><a href="about.html">Leadership</a></li>
              </ul>
            </li>
            <li><a href="contact.php">Contact Us</a></li>
          </ul>
        </nav>
        <!-- Main menu end --> 
      </div>
    </div>
    <!-- Header area End --> 
  </header>
  
  <!-- Breadcroumbs start -->
  <div class="wshipping-content-block wshipping-breadcroumb inner-bg-2">
    <div class="container">
      <div class="row">
         <div class="col-12 col-lg-7">
            <h1>Contact Us</h1>
            <a href="index.html" title="Home">Home</a> / Contact Us
         </div>
         <div class="col-12 col-lg-5 text-right"><h4><span>Give us a shout!</span>  We would be happy to show you our Capabilities.</h4></div>
      </div>
    </div>
  </div>
  <!-- Breadcroumbs end --> 

  <!-- Contact Start -->
  <div class="wshipping-content-block">
    <div class="container">
      <div class="row flex-lg-row-reverse">
        <div class="col-12 col-lg-4">
          <div class="address">
            <h3>Corporate Office</h3>
            <div class="address-block">
              <ul>
                <li class="address-icon"><strong>Address:</strong><br>
                  P.O. Box 123 </br>Aubrey, TX  76227</li>
                <!--<li class="phone-icon"><strong>Telephone No:</strong><br>
                  +01 214-555-1212</li>-->
                <li class="email-icon"><strong>Email:</strong><br>
                  <a href="mailto:sales@buildersurfaces.com" title="">sales@buildersurfaces.com</a></li>
              </ul>
            </div>
          </div>
        </div>
         <div class="col-12 col-md-8">
            <div class="contact-text">
              <h3>We're here for you!</h3>
              
            </div>
			
            <div class="contact-form">
			<!--
               <h3 class="heading3-border text-uppercase">Contact Request</h3>
               <?php if(isset($message)) echo $message;  ?> 
               <form action="contact.php" enctype="multipart/form-data" method="post">
                  <div class="form-group">
                     <div class="row">
                       <div class="col-12 col-lg-6">
                        <input type="text" class="form-control" placeholder="Your Name" name="fullname">
                       </div> 
                       <div class="col-12 col-lg-6">
                        <input type="text" class="form-control" placeholder="Company" name="company">
                       </div> 
                     </div>   
                   </div>
                   <div class="form-group">
                     <div class="row">
                       <div class="col-12 col-lg-6">
                        <input type="email" class="form-control" placeholder="Email" name="email">
                       </div>
                       <div class="col-12 col-lg-6">  
                        <input type="text" class="form-control" placeholder="Phone Number" name="phone">
                       </div>  
                     </div>   
                   </div>
                   <div class="form-group">
                        <textarea class="form-control" placeholder="Message" name="message"></textarea>
                   </div>
                    <div class="form-group">
                       <button type="submit" class="btn btn-submit" name="contact">Submit</button>
                    </div>
                </form>
				 -->
             </div>  
			
         </div>
      </div>
    </div>
  </div>
  <!-- Contact end -->
  
  <!-- Map start --> 
  <!--
  <div class="map">
      <iframe src="https://www.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=++Gulshan-1,+Dhaka-1212.&aq=&sll=23.78024,90.418081&sspn=0.01076,0.018475&ie=UTF8&hq=&hnear=1+Gulshan+Ave,+Gulshan,+Dhaka,+Dhaka+Division+1212,+Bangladesh&t=m&z=14&ll=23.780244,90.418078&output=embed"></iframe>
  </div>
-->  
  <!-- Map end -->

 <!-- Footer start -->
  <footer class="site-footer"> 
    <!-- Footer Top start -->
    <div class="footer-top-area">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-3">
            <div class="footer-wiz">
              <h3 class="footer-logo"><img src="assets/images/logoW.png" alt="footer logo"/></h3>
              
              
            </div>
            
          </div>
		  
          <div class="col-12 col-md-6 col-lg-3">
            <div class="footer-wiz footer-menu">
              
            </div>
          </div>
		  
		  
          <div class="col-12 col-md-6 col-lg-3">
            <div class="footer-wiz footer-menu">
              <h3 class="footer-wiz-title">Contact Us</h3>
              <ul class="footer-contact">
                <li><i class="fa fa-envelope"></i> sales@buildersurfaces.com</li>
              </ul>
            </div>
          </div>
		 
		  
          <div class="col-12 col-md-6 col-lg-3">
            <div class="footer-wiz">
              <div class="top-social bottom-social"> <a href="#"><i class="fab fa-facebook-f"></i></a> <a href="#"><i class="fab fa-twitter"></i></a> </div>
           
            </div>
          </div>
		  
        </div>
      </div>
    </div>
    <!-- footer top end --> 
    
    <!-- copyright start -->
    <div class="footer-bottom-area">
      <div class="container">
        <div class="row">
          <div class="col-12 col-lg-6 wow fadeInLeft">Copyright © 2022 <span>Builder Surfaces Technology LLC.</span>. All Rights Reserved</div>
          </div>
      </div>
    </div>
    <!-- copyright end --> 
  </footer>
  <!-- Footer end --> 
</div>
<!-- Main Wrapper end --> 

<!-- Start scroll top -->
<div class="scrollup"><i class="fas fa-chevron-up"></i></div>
<!-- End scroll top --> 

<!-- Tether JS --> 
<script src="assets/js/tether.min.js"></script> 

<!-- Popper JS --> 
<script src="assets/js/popper.min.js"></script> 

<!-- Bootstrap JS --> 
<script src="assets/js/bootstrap.min.js"></script> 

<!-- OwlCarousel JS --> 
<script src="assets/js/owl.carousel.min.js"></script> 

<!-- Bootstrap dateTimePicker --> 
<script src="assets/js/datetimepicker-moment.min.js"></script> 
<script src="assets/js/bootstrap-datetimepicker.min.js"></script> 

<!-- SlickNav JS --> 
<script src="assets/js/jquery.slicknav.min.js"></script> 

<!-- fancybox Popup JS --> 
<script src="assets/js/jquery.fancybox.min.js"></script> 

<!-- WOW JS --> 
<script src="assets/js/wow-1.3.0.min.js"></script> 

<!-- Step Form with validate --> 
<script src="assets/js/jquery.validate.js"></script> 
<script src="assets/js/form-step.js"></script> 

<!-- SlickNav JS --> 
<script src="assets/js/youtube-background.js"></script> 

<!-- Gallery Filter --> 
<script src="assets/js/jquery.filterizr.min.js"></script> 

<!-- Chat JS --> 
<script src="assets/js/chat.js"></script> 

<!-- Coming Soon JS --> 
<script src="assets/js/coming-soon.js"></script> 

<!-- Active JS --> 
<script src="assets/js/active.js"></script>
</body>
</html>