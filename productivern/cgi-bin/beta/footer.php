</div>
<!-- end:: Body -->
<!-- begin::Footer -->
<footer class="m-grid__item	m-footer text-light" align="center" style="background-color:#2C2E3E">
	<div class="container">
	 
	<div class="logo" align="center">
	<a href="https://www.productivern.com" target="_blank">
	<img src="../img/productivePowered.png" alt="logo" style="max-height:60px"/>
	</a>	
	</div>
	
	<div align="center">2018 &copy; Harrelson Software Group LLC. All Rights Reserved.  <a href="privacy.html" target="_blank">  Privacy Policy</a> | <a href="/terms/index.html" target="_blank">  Terms of Use</a></div>
	
	</div>
</footer>
<!--
<footer class="m-grid__item	m-footer" align="center">
    <div class="m-container m-container--fluid m-container--full-height m-page__container">
        <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
            <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                <span style="color:blue" class="m-footer__copyright">
                    2017 &copy; Harrelson Group LLC | <a href="privacy.html" target="_blank">Privacy</a></br>CONFIDENTIAL
                </span>
            </div>
        </div>
    </div>
</footer>
-->
<!-- end::Footer -->
</div>
<!-- end:: Page -->
<!-- begin::Scroll Top -->
<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500"
     data-scroll-speed="300">
    <i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!--begin::Base Scripts -->
<script src="//code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>

<script src="theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/responsive/2.2.0/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js" type="text/javascript"></script>
	
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<!--end::Base Scripts -->
<!--begin::Page Vendors -->
<script src="theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
<!--end::Page Vendors -->
<!--begin::Page Snippets -->
<script src="theme/assets/app/js/dashboard.js" type="text/javascript"></script>
<!--end::Page Snippets -->
<script src="scripts/main.js" type="text/javascript"></script>
<script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
</script>
<script>
jQuery(document).ready(function() {    
   // initialize session timeout settings
   $.sessionTimeout({
    title: 'Session Timeout Notification',
    message: 'Your session is about to expire.',
    keepAliveUrl: 'timeout-keep-alive.php',
    redirUrl: 'logout.php',
    logoutUrl: 'login.php',
    warnAfter: 300000, //warn after 5 seconds
    redirAfter: 400000, //redirect after 10 secons
   });
});
</script>
</body>
<!-- end::Body -->
</html>
