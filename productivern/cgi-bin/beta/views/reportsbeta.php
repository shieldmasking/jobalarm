<?php
include "./inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);
$deptName = $_SESSION['account']['deptName'];


?><!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Antepartum / L&D
            </h3>
        </div>
        <div>
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
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
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber">0</div>
                    <div class="desc">
                        Variance
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-3 col-md-6">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber">0</div>
                    <div class="desc">
                        Productivity (Est.)
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber">0</div>
                    <div class="desc">
                        Patients
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-3 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber">0</div>
                    <div class="desc">
                        Open Beds
                    </div>
                </div>
            </div>
        </div>
		
	
    </div>
    <!--End::Main Portlet-->
</div>
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Step-Down I.C.U.
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    <i class="fa fa-comments"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber2">0</div>
                    <div class="desc">
                        Variance
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-3 col-md-6">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber2">0</div>
                    <div class="desc">
                        Productivity (Est.)
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="dashboard-stat green-haze">
                <div class="visual">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber2">0</div>
                    <div class="desc">
                        Patients
                    </div>
                </div>
            </div>
        </div>
		<div class="col-xl-3 col-md-6">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    <i class="fa fa-bar-chart-o"></i>
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber2">0</div>
                    <div class="desc">
                        Open Beds
                    </div>
                </div>
            </div>
        </div>
		
	
    </div>
    <!--End::Main Portlet-->
</div>