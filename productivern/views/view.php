<?php
session_start();

?>
<html lang="en">
<body>
<head>

</head>
<style>
body {
    font-size: 16px;
} 
</style>
<style>
.vl {
  border-left: 3px solid LightGrey;
}
</style>


<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Report
            </h3>
        </div>
     <div>
			
        </div>
		
    </div>
</div>
<!-- END: Subheader -->
<div class="content" id="content">
    <!--Begin::Main Portlet-->
            <div class="portlet">
                <div class="portlet__body" id="viewWHP">
				
		<div class="col-lg-12">
		
                <div class="title">
				<span id="printView"></span>
				</div>
			
				<div class="header" align="center">
				<div class="row" style="margin-top:5px;margin-bottom:8px;color:black">
                        <div class="col-sm-12">
							<div class="title"><h4><span id="depname"></span></h4>						
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
			  
					 
                    <!--begin::Form-->
                    
                		
						<input id="dataidwhp" type="hidden" value="" />
						
						<div style="background-color:#E0E0E0">
						<div class="col-md row" style="padding-top:20px;">
						<div class="title"><h5>Variance: <span id="hourswhpVariance"></span></h5>
						</div>
						</div>
						<div class="title"><h5>WHPUOS: <span id="actualwhpUOS"></span></h5>
						</div>
						<div class="title"><h5><span id="budgetwhpVal"></span></h5>
						</div>
						<div class="title" style="color:red"><h5><span id="escalationwhpVal"></span></h5>							
							</div>
						<hr></hr>	
							<div class="title"><h5><span id="uoswhp"></span></h5>							
							</div>
						<div class="form-group">
						<div class="bg-white">
						<div class="row">
						<div class="col-md-6">
						<div class="col-6">
                                <label for="totaluosWHP" class="col-form-label font-weight-normal" style="color:black">
                                    Total UOS
                                
                                    <input id="totaluosWHP" name="totaluosWHP" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                          </div>
						  </div>
						 
                        <div class="col-md-6 vl">
						 <div class="col-6">
						  <div id="customValhide">
						
                                <label for="customvalView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="customvalDesc"></span>
									
                                
                                    <input id="customvalView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                        
                          </div>
						  </div>
						  </div>
						</div>
						</div>
						</div>
						
						<div class="title"><h5><span id="hourswhp"></span></h5>
						
						</div>
						<div class="form-group">
						<div class="bg-white">
						<div class="row">
						<div class="col-md-6">
						<div class="col-6">
						<div id="hiddenwhp1">
                                
								<label for="skillwhp1" class="col-form-label font-weight-normal" style="color:black">
                                   <span id="skilldsc1"></span>
                                
                                
                                    <input id="skillwhp1" name="skillwhp1" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp3">
						
                                <label for="skillwhp3" class="col-form-label font-weight-normal" style="color:black">
                                   <span id="skilldsc3"></span>
                                
                                
                                    <input id="skillwhp3" name="skillwhp3" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp5">
					
                                <label for="skillwhp5" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc5"></span>
                                
                                
                                    <input id="skillwhp5" name="skillwhp5" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                               </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp7">
					
                                <label for="skillwhp7" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc7"></span>
                                
                                
                                    <input id="skillwhp7" name="skillwhp7" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp9">
				
                                <label for="skillwhp9" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc9"></span>
                                
                                
                                    <input id="skillwhp9" name="skillwhp9" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  </div>
						  <div class="col-md-6 vl">
							<div class="col-6">
							<div id="hiddenwhp2">
							
                                <label for="skillwhp2" class="col-form-label font-weight-normal" style="color:black">
                                   <span id="skilldsc2"></span>
                                
                                
                                    <input id="skillwhp2" name="skillwhp2" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
						</div>
						<div class="col-6">
						 <div id="hiddenwhp4">
					
                                <label for="skillwhp4" class="col-form-label font-weight-normal" style="color:black">
                                   <span id="skilldsc4"></span>
                                
                                
                                    <input id="skillwhp4" name="skillwhp4" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp6">
					
                                <label for="skillwhp6" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc6"></span>
                                
                                
                                    <input id="skillwhp6" name="skillwhp6" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp8">
						
                                <label for="skillwhp8" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc8"></span>
                                
                                
                                    <input id="skillwhp8" name="skillwhp8" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                               </label>
                          </div>
						  </div>
						  <div class="col-6">
						  <div id="hiddenwhp10">
						
                                <label for="skillwhp10" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="skilldsc10"></span>
                               
                                
                                    <input id="skillwhp10" name="skillwhp10" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                 </label>
                          </div>
						  </div>
						  </div>
						  </div>
						  </div>
						  </div>
						  <div id="inshiftviewwhp">
						<div class="title"><h5>Data Tracking </h5>
						
						</div>
						<div class="form-group">
							<div class="bg-white">
							<div class="row">
							<div class="col-md">
							
							<div class="col-6">
							<div id="hidden7viewwhp">
                                <label for="track1Viewwhp" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc1whp"></span>
                                
                                
                                    <input id="track1Viewwhp" type="number" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
						
							<div id="hidden9viewwhp">
                                <label for="track3Viewwhp" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc3whp"></span>
                                
                                
                                    <input id="track3Viewwhp" type="number" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
							</div>
							</div>
							<div class="col-md vl">
							<div class="col-6">
							<div id="hidden8viewwhp">
                                <label for="track2Viewwhp" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc2whp"></span>
                                    <input id="track2Viewwhp" type="number" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
				
							<div id="hidden10viewwhp">
                                <label for="track4Viewwhp" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc4whp"></span>
                                    <input id="track4Viewwhp" type="number" style="color:black; font-size:18px;" class="form-control" disabled>
                                 </label>
                            </div>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>
						<div class="title"><h5>Variance Note / Action Plan</h5>							
							</div>						
						<div class="form-group">
						<div class="bg-white">
						<div class="row">	
						<div class="col-md">	
	
					
                                
                                <textarea class="form-control font-weight-normal" style="color:black" rows="3" placeholder="" id="whpnote" disabled></textarea>
                     
					</div>
					</div>
					</div>
					</div>
					
					
			
                
                
            </div>
		
		
					
					
                      
						
                    
                </div>
				</div>
	                <div class="portlet__body" id="viewNurse">
			
		<div class="col-lg-12">
		<!--<a href="javascript:;" onclick="tj.getprintView()">Print View</a>
		

			-->
                <div class="title">
				<span id="printView"></span>
				</div>
				
				<div class="header" align="center">
				
                 
				<div class="row" style="margin-top:5px;margin-bottom:8px;color:black">
                        <div class="col-sm-12">
							<div class="title"><h4><span id="depnameView"></span></h4>						
							</div>
							<div class="title"><h5>Report Date/Shift: <span id="dateView"></span> - <span id="shiftView"></span></h5>						
							</div>	
                           
							<div class="title"><h5>Last Updated By: <span id="updatedbyView"></span></h5>						
							</div>
							<div class="title"><h5><span id="enteredView"></span></h5>							
							</div>
							
						
							
                        </div>
                  </div>
			  </div>
			  
                    <!--begin::Form-->
                
                		
						<div style="background-color:#EDEBEB">
						<div class="form-group">
						<div class="col-md row" style="padding-top:20px;">
						<div class="title"><h5>Variance: <span id="varianceView"></span></h5>
						</div>
						</div>
						<div class="col-md row">
						<div class="title" id="gvarView"><h5>Grid Variance: <span id="gridvarianceView"></span></h5>
						</div>
						</div>
						<!--
						<div class="title" id="inshiftview"><h5>Est. Productivity: <span id="prodView"></span>%</h5>
						</div>
						-->
						<div class="title" id="viewchurn"><h5>Churn: <span id="churnVal"></span>%</h5>
						</div>
						<div class="title"><h5><span id="blockedView"></span></h5>
						</div>
						<div class="title" style="color:red"><h5><span id="escalationView"></span></h5>							
							</div>
						</div>
						<hr></hr>
						<div class="title"><h5>Patients</h5>							
							</div>
						<div class="form-group">
						<div class="bg-white">
							<div class="row">
							<div class="col-md">
							<div class="col-6">
                                <label for="totalpatientsView" class="col-form-label font-weight-normal" style="color:black">
                                    Total Patients                            
								
                                
                                    <input id="totalpatientsView" name="totalpatientsView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                          </div>
						   </div>
						  <div class="col-md vl">
						  <div class="col-6">
                                <label for="totalpatientsView2" class="col-form-label font-weight-normal" style="color:black" hidden>
                                                                                                   
                                    <input id="totalpatientsView2" name="totalpatientsView2" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                          </div>
						   </div>
						  <div class="col-md vl">
						  <div class="col-6">
                                <label for="totalpatientsView3" class="col-form-label font-weight-normal" style="color:black" hidden>
                                                                 
									
                                
                                    <input id="totalpatientsView3" name="totalpatientsView3" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                          </div>
						   </div>
                          </div>
						</div>
						</div>
							
							<div class="title"><h5>Patient Detail</h5>
							
							</div>
							<div class="form-group">
							<div class="bg-white">
							<div class="row">
							<div class="col-md">
							
							<div class="col-6">
							<div id="hidden1view">
							
                                <label for="one2oneView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="descView1"></span>
                                
                                
                                    <input id="one2oneView" name="one2oneView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                               </label>
                            </div>
							</div>
							<div class="col-6">
							<div id="hidden4view">
						
                                <label for="one2fourView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="descView4"></span>
                                
                                
                                    <input id="one2fourView" name="one2fourView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
							</div>
							
						
							</div>
							
							<div class="col-md vl">
							<div class="col-6">
							<div id="hidden2view">
							
                                <label for="one2twoView" class="col-form-label font-weight-normal" style="color:black">
									<span id="descView2"></span>
                                
                                
                                    <input id="one2twoView" name="one2twoView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                             </div> 
                            </div>
							<div class="col-6">
								<div id="hidden5view">
							
                                <label for="one2fiveView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="descView5"></span>
                               
                               
                                    <input id="one2fiveView" name="one2fiveView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                              </div> 
                            </div>		
						
							</div>
							<div class="col-md vl">
							<div class="col-6">
							<div id="hidden3view">
							
                                <label for="one2threeView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="descView3"></span>
                                
                                
                                    <input id="one2threeView" name="one2threeView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                            </div>    
                            </div>
													
							<div class="col-6">			
							<div id="hidden6view">
							
                                <label for="one2sixView" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="descView6"></span>
                                
                                
                                    <input id="one2sixView" name="one2sixView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
									</label>
                            </div>    
                            </div>
							</div>
							</div>
							</div>
							</div>
						<div class="title"><h5>Resources In Staffing <span id="gridVar"> (Grid Variance)</span></h5>
						
						</div>
						<div class="form-group">
							<div class="bg-white">
							<div class="row">
							<div class="col-md">
						
					
							<label for="chargecountView" class="col-6 form-label font-weight-normal">
                                   Charge Nurse    
                                   <input id="chargecountView" name="chargecountView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          <div id="customNurse2">
						
                                <label for="custom2Nurse" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="custom2Label"></span>
                                 
                            
							<input id="custom2Nurse" name="custom2Nurse" type="text" style="color:black; font-size:18px;" class="form-control" disabled>	
                          <!--<span id="custom2Nurse" style="color:black; font-size:18px; padding-right:20px;"></span>-->
					</label> 
                            
                          </div>
							<div id="other2report">
						
                                <label for="other2Nurse" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="other2Label"></span>
                                
                                
                                    <input id="other2Nurse" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                               </label>
                          </div>
						  <div id="secreport">
						 <label for="seccountView" class="col-6 form-label font-weight-normal" style="color:black">
                                   <span id="secLabelView"></span>
                                
                                
                                    <input id="seccountView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
							</div>	
                         
						  </div>
						  <div class="col-md vl">
						  
						<div id="nursereport">
                                <label for="nursecountView" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="nursecountDesc"></span>
                                
                                
                                    <input id="nursecountView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						<div id="techreport">
                                <label for="techcountView" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="techLabelView"></span>
                                
                                
                                    <input id="techcountView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                           </div>
							<div id="other1report">
						
                                <label for="other1Nurse" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="other1Label"></span>
                                
                                
                                    <input id="other1Nurse" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						
						  </div>
						   <div class="col-md vl">
						  
						  <div id="customnurse1">
                                
								<label for="customNursereport" class="col-6 form-label font-weight-normal" style="color:black">
                                   <span id="custom1Label"></span>
                                
                                
                                    <input id="custom1nurse" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                          </div>
						
				<div id="sitterreport">
                                <label for="otherView" class="col-6 form-label font-weight-normal" style="color:black">
                                     <span id="sittercountDesc"></span>
                                
                                
                                    <input id="otherView" name="otherView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
								</div>
								<div id="other3report">
						
                                <label for="other3Nurse" class="col-6 form-label font-weight-normal" style="color:black">
                                    <span id="other3Label"></span>
                                
                                
                                    <input id="other3Nurse" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                               </label>
                          </div>
                        
						  </div>
						  </div>
						  </div>
						  </div>
						
						<div id="inshiftview">
						<div class="title"><h5>In-Shift Data Tracking </h5>
						
						</div>
						<div class="form-group">
							<div class="bg-white">
							<div class="row">
							<div class="col-md">
							
							<div class="col-6">
							<div id="hidden7view">
                                <label for="track1View" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc1"></span>
                                
                                
                                    <input id="track1View" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
						
							<div id="hidden9view">
                                <label for="track3View" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc3"></span>
                                
                                
                                    <input id="track3View" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
							</div>
							</div>
							<div class="col-md vl">
							<div class="col-6">
							<div id="hidden8view">
                                <label for="track2View" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc2"></span>
                                    <input id="track2View" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                </label>
                            </div>
				
							<div id="hidden10view">
                                <label for="track4View" class="col-form-label font-weight-normal" style="color:black">
                                    <span id="trackDesc4"></span>
                                    <input id="track4View" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                                 </label>
                            </div>
							</div>
							</div>
							</div>
							</div>
							</div>
							</div>
							<div id="churnView">
					
							<div class="title"><h5>Churn</h5>							
							</div>
							<div class="form-group">
							<div class="bg-white">
							<div class="row">
							
						<div class="col-md">
                                <label for="admissionsView" class="col-form-label font-weight-normal" style="color:black">
                                    Admissions
                                
                                    <input id="admissionsView" name="admissionsView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                          </label>
                          </div>
						  <div class="col-md vl">
                                <label for="transfersView" class="col-form-label font-weight-normal" style="color:black">
                                    Transfers
                                
                                    <input id="transfersView" name="transfersView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                          </label>
                          </div>
						  <div class="col-md vl">
                                <label for="dischargesView" class="col-form-label font-weight-normal" style="color:black">
                                    Discharges
                                
                                    <input id="dischargesView" name="dischargesView" type="text" style="color:black; font-size:18px;" class="form-control" disabled>
                          </label>
                          </div>
						  </div>
						  </div>
						  </div>
                    </div>
					<div class="title"><h5>Variance Note / Action Plan </h5>
						
						</div>	
					<div class="form-group">
							<div class="bg-white">
							
							<div>							
                               
                                <textarea class="form-control font-weight-normal" style="color:black" rows="3" placeholder="" id="prodnoteView" disabled></textarea>
								
							</div>
						
							</div>
							</div>

								
						</div>
			
		
		</div>
					
					
                      
						</div>
                    
               
            </div>
   
    <!--End::Main Portlet-->
</div>
    <!--begin::Modal-->
</body>
</html>