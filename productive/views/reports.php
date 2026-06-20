<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Staffing Dashboard
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
                        Total Variance
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
                        Antepartum Productivity
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
                        Antepartum Variance
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
                        Labor Variance
                    </div>
                </div>
            </div>
        </div>
		
	
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Variance
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="all_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		<div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Antepartum Productivity
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="postpartum_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		</div>
		<div class="row">
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Antepartum Variance
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="antepartum_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
   
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Labor Variance
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="labor_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		</div>
    <!--End::Main Portlet-->
</div>