<?php
include "../inc/initializer.php";



?>


<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                CSV Field Map
            </h3>
			<div class="title">
			<strong>CSV Column Names mapped to respective Departments and Field Titles.</strong>
        </div>
		</div>

</div>
<!-- END: Subheader -->
<div class="m-content bg-light" id="csvcontent">
    <!--Begin::Main Portlet-->
    
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
										
					
                    <div class="row col-12">
					
					
                        <table id="csvTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                         
				
							<thead>
							
                                <tr>
									<th width="25%" data-priority="1">CSV Column Header</th>
									<th width="50%" data-priority="2">Unit (Field Title)</th>
									<th width="25%" data-priority="3">Field Description</th>									
                                </tr>
                            </thead>
                        </table>
						</div>
                    
                </div>
            </div>
     
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
