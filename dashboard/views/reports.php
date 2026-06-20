<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Dashboard
            </h3>
        </div>
		
        <div>
            <span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            <div class="pull-right" style="margin-left:15px;margin-top:2px">
                <!--
				<div class="m-dropdown m-dropdown--inline  m-dropdown--arrow m-dropdown--align-right" data-dropdown-toggle="click">
                    <a href="#" class="m-dropdown__toggle btn btn-info dropdown-toggle">
                        Tools
                    </a>
                    <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li class="m-nav__item">
                                            <a href="inc/data.php?req=downloadReport" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-download"></i>
                                                <span class="m-nav__link-text">
                                                    Export to CSV
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!--end: Dropdown-->
            </div>
			
        </div>
			
    </div>

</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-sm">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCanNumber">0</div>
                    <div class="desc">
                        New Candidates
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="dashboard-stat red-intense">
                <div class="visual">
                    
                </div>
                <div class="details">
                    <div class="number" id="reportCtrNumber">0 (0.0%)</div>
                    <div class="desc">
                        Unique Job Clicks
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="dashboard-stat green-haze">
                <div class="visual">
               
                </div>
                <div class="details">
                    <div class="number" id="reportPromoNumber">0</div>
                    <div class="desc">
                        Total Job Clicks
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="dashboard-stat purple-plum">
                <div class="visual">
                </div>
                <div class="details">
                    <div class="number" id="reportMsgNumber">0 (0.0%)</div>
                    <div class="desc">
                        Active Job Postings
                    </div>
                </div>
            </div>
        </div>
		<div class="col-sm" id="showRewards">
            <div class="dashboard-stat blue-madison">
                <div class="visual">
                </div>
                <div class="details">
                    <div class="number" id="rewardNumber">0 (0.0%)</div>
                    <div class="desc">
                        App/Reward Clicks
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
                                New Candidates
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="new_candidates_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-chat"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Unique Job Clicks
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="outgoing_messages_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
</div>