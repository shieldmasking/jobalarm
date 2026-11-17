<?php
include "../inc/initializer.php";
?>



<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Report<span id="reportType"></span>
            </h3>
        </div>
     <div>
           
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
		<div class="col-lg-12">
		<!--
            <div class="content">
			-->
                <div class="title">
				<span id="printView"></span>
				</div>
				
				<div class="header" align="center">
				
                 
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-12">
							<div class="title"><h5><span id="depname"></span></h5>						
							</div>
							<div class="title"><h5>Report Date: <span id="updatedwhpDate"></span></h5>						
							</div>	
                           
							<div class="title"><h5>Last Updated By: <span id="updatedwhpBy"></span></h5>						
							</div>
							<div class="title"><h5><span id="updatedwhpentered"></span></h5>							
							</div>
							
						
							
                        </div>
                  </div>
			  </div>
			  
					 <div class="body">
                    <!--begin::Form-->
                    <form id="addwhp" class="m-form m-form--fit m-form--label-align-right">
                		<div class="m-portlet__body">
						<input id="dataidwhp" type="hidden" value="" />
						<div style="background-color:#E0E0E0; padding-left: 20px; padding-top: 20px;">
						<div class="title"><h5><strong>Hours Variance: <span id="hourswhpVariance"></span></strong></h5>
						</div>
						<div class="title"><h5><strong>Actual WHPUOS: <span id="actualwhpUOS"></span></strong></h5>
						</div>
						<div class="title"><h5><strong>Target WHPUOS: <span id="targetwhpUOS"></span></strong></h5>
						</div>
						<div class="title"><h5><strong><span id="budgetwhpVal"></span></strong></h5>
						</div>
						<div class="title"><h5><strong><span id="escalationwhpVal"></span></strong></h5>							
							</div>
						<hr></hr>
						<div class="title"><h5><strong><span id="hourswhp"></span></strong></h5>
						
						</div>
						<div class="form-group" id="hiddenwhp1">
                                <div style="padding-left: 25px; padding-top: 20px; padding-right: 20px;">
								<label for="skillwhp1">
                                   <span id="skilldsc1"></span>
                                </label>
                                
                                    <input id="skillwhp1" name="skillwhp1" type="text" class="form-control" disabled>
                                </div>
                          </div>
							
							<div class="form-group" id="hiddenwhp2">
							<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="skillwhp2" class="col-8 col-form-label">
                                   <span id="skilldsc2"></span>
                                </label>
                                
                                    <input id="skillwhp2" name="skillwhp2" type="text" class="form-control" disabled>
                                </div>
                            </div>
						<div class="form-group" id="hiddenwhp3">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="skillwhp3" class="col-8 col-form-label">
                                   <span id="skilldsc3"></span>
                                </label>
                                
                                    <input id="skillwhp3" name="skillwhp3" type="text" class="form-control" disabled>
                                </div>
                          </div>
						 <div class="form-group" id="hiddenwhp4">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="skillwhp4">
                                   <span id="skilldsc4"></span>
                                </label>
                                
                                    <input id="skillwhp4" name="skillwhp4" type="text" class="form-control" disabled>
                                </div>
                          </div>
						  <div class="form-group" id="hiddenwhp5">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="skillwhp5">
                                    <span id="skilldsc5"></span>
                                </label>
                                
                                    <input id="skillwhp5" name="skillwhp5" type="text" class="form-control" disabled>
                                </div>
                          </div>
						<hr></hr>
							<div class="title"><h5><strong><span id="uoswhp"></span></strong></h5>							
							</div>
						<div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="totaluosWHP">
                                    Total UOS</label>
                                
                                    <input id="totaluosWHP" name="totaluosWHP" type="text" class="form-control" disabled>
                          </div>
                          </div>
						
											
							<hr></hr>
	
						<div id="churnwhp" hidden>
						<hr></hr>
							<div class="title"><h5><strong>Churn (<span id="churnValue"></span>%)</strong></h5>							
							</div>
						<div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px; padding-top: 20px;">
                                <label for="admissionswhp" class="col-8 col-form-label">
                                    Admissions</label>
                                
                                    <input id="admissionswhp" name="admissionswhp" type="text" class="form-control" disabled>
                          </div>
                          </div>
						  <div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="transferswhp" class="col-8 col-form-label">
                                    Transfers</label>
                                
                                    <input id="transferswhp" name="transferswhp" type="text" class="form-control" disabled>
                          </div>
                          </div>
						  <div class="form-group">
						<div style="padding-left: 25px; padding-right: 20px;">
                                <label for="dischargeswhp" class="col-8 col-form-label">
                                    Discharges</label>
                                
                                    <input id="dischargeswhp" name="dischargeswhp" type="text" class="form-control" disabled>
                          </div>
                          </div>
						
											
							<hr></hr>
							
                    </div>
					<div class="form-group">
							<div style="padding-left: 25px; padding-bottom: 10px; padding-right: 20px;">
                                <label for="form_control_1">Variance Note / Action Plan</label>
                                <textarea class="form-control" rows="3" placeholder="" id="whpnote" disabled></textarea>
                            </div>
								</div>
					</div>
					</div>
					
					
					</form>
                
                
            </div>
		
		</div>
					
					
                      
						</div>
                    
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
</div>
