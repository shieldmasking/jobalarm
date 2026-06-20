<?php
include "../inc/initializer.php";



?>


<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Reporting Compliance
            </h3>
			<div class="title">
			<strong>Percentage of Productivity Reports completed during the selected dates.</strong>
        </div>
		</div>
	<div class="d-flex pull-right">
     
            <span class="m-subheader__daterange" id="compliance_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            
			
        
		
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content bg-light" id="compliancecontent">
    <!--Begin::Main Portlet-->
    
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row col-12">
					
					
                        <table id="complianceTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="20%" data-priority="1">Unit</th>
									<th width="30%" data-priority="4">Director</th>
									<th width="30%" data-priority="3">Manager</th>
									<th width="20%" data-priority="2">% Submitted</th>
							                                
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
     
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
