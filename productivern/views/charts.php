<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}

$startPay = (isset($_SESSION['account']['newStartPay']) ? $_SESSION['account']['newStartPay']:0);
$endPay = (isset($_SESSION['account']['newEndPay']) ? $_SESSION['account']['newEndPay']:0);


?>
<style>
#chart_2 {
    max-width: 100%;
    height: 400px;
}
</style>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Shift Report Details
            </h3>
		</div>

<div class="d-flex pull-right">
			<span class="m-subheader__daterange" id="charts_daterangepicker">						
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
			<div class="pull-right" style="margin-left:15px;margin-top:2px">
            </div>
        </div>
Select a Date Range to Chart.
</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary" id="pdfcontent">
    <!--Begin::Main Portlet-->

    
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row col-12">
								<!-- BEGIN INTERACTIVE CHART PORTLET-->
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-chart"></i>RN Variance Avg.
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart_2" class="chart">
							</div>
						</div>
					</div>
					<!-- END INTERACTIVE CHART PORTLET-->
					
                 
						</div>
                    
                </div>
            </div>
     
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
