<?php
include "../inc/initializer.php";



?>


<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                User Reports
            </h3>
        </div>
     <div>
            <span class="m-subheader__daterange" id="performance_daterangepicker">
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
<div class="m-content" id="content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row">
					
					
                        <table id="performanceTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="22%" data-priority="1">User</th>
									<th width="15%" data-priority="2">Unit</th>
									<th width="12%" data-priority="3">Avg. Prod%</th>
									<th width="15%" data-priority="4">Date</th>
									<th width="12%" data-priority="2">Target WHPUOS</th>
									<th width="12%" data-priority="6">Actual WHP</th>
									<th width="12%" data-priority="7">Records</th>
							                                
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
