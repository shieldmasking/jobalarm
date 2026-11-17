<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';
?>

<style>
body {
    font-size: 14px;
} 
</style>



<!-- BEGIN: Subheader -->

<!-- END: Subheader -->
<div class="content">
    <!--Begin::Main Portlet-->
            <div class="portlet">
                <div class="portlet__body">
				
		<div class="col-lg-12">
		<button class="btn btn-success pull-right" onclick="window.print()">Print This Page</button>	
               
			
				<div align="center">
				<div class="row" style="margin-top:5px;margin-bottom:20px;color:black">
                        <div class="col-lg-12">
							<div class="title"><h4><span id="qrdeptname"></span></h4>						
							</div>
							<div class="title"><h5>Updated: <span id="qrupdated"></span></h5>						
							</div>
													
                       				
						
							
                        </div>
                  </div>
			  </div>
			  
					 
                    <!--begin::Form-->
                    
                		
		
						
					
							
						<div class="form-group">
						<div class="col-lg" align="center">
							
						
						<div class="title"><h5>Staffing Reports</h5>							
							</div>
							
															
					
							<span id="staffingqrcode"></span>
						
					
				
					</div>
					</div>
					
						<div class="form-group">
						<div class="col-lg" align="center">
							
						
						<div class="title" style="text-align: center"><h5>QBL Calculator</h5>							
							</div>						
							
						
							<span id="qblqrcode"></span>
						
			
				
					</div>
					</div>
						<div class="form-group">
						<div class="col-lg" align="center">
					
						
						<div class="title" style="text-align: center"><h5>Tasks</h5>							
							</div>						
								
						
							<span id="tasksqrcode"></span>
						
				
					</div>
					</div>
           
		
		
					
					
                      
						
                    
                </div>
				</div>
</div>
</div>	               
