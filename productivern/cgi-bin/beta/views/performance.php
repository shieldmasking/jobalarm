<?php
include "../inc/initializer.php";



?>


<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                User Reports
            </h3>
        </div>
     <div class="d-flex pull-right">
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

<!-- END: Subheader -->
<div class="m-content bg-light" id="content">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row col-12">
					
					
                        <table id="performanceTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="22%" data-priority="1">User</th>
									<th width="15%" data-priority="3">Unit</th>
									<th width="12%" data-priority="2">Productivity%</th>
									<th width="12%" data-priority="4">Target WHPUOS</th>
									<th width="12%" data-priority="5">WHPOUS</th>
									<th width="12%" data-priority="6">Records</th>
									<th width="15%" data-priority="7">Date</th>
							                                
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
