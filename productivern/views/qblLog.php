<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$userId = intval($_SESSION['account']['id']);
$accountId = $_SESSION['account']['accountId'];


?>

<style>
.modal {
    overflow-y: scroll;
}

input[type='number']{
    width: 120px;
	font-size:14px;
} 
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                QBL Log
            </h3>
			  
        </div>
		<!--
<button type="button" class="btn btn-success" data-target="#editABL" data-toggle="modal">
                                            ABL
                                        </button>-->
<div class="d-flex pull-right">
			<span class="m-subheader__daterange" id="qbl_daterangepicker">						
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
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	   
				<div class="row col-12">
                        <table id="qblTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="15%" data-priority="1">Date/Time</th>
									<th width="20%" data-priority="3">Unit</th>
									<th width="10%" data-priority="2">Est. QBL</th>
									<th width="15%" data-priority="4">Total Weight</th>
									<th width="10%" data-priority="5">Est. ABL</th>
									<th width="30%" data-priority="6">Note</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
    <!--begin::Modal-->
	    <!--begin::Modal-->

<!-- /begin modal -->
<div class="modal fade" id="seeQBL" tabindex="-1" role="basic">
        
		<div class="modal-dialog modal-lg">
		
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title" style="color:white">QBL Detail
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				<div class="title"><span id="qbUnit"></span></div>
				<div class="title"><span id="qbDate"></span></div>
				<span id="ablData"></span>
					
				   		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
					<div class="dashboard-stat green-haze">
					
						<div class="col-6 visual">
						
						
						</div>
						<div class="details">
						<div class="desc" style="margin-top:10px">
						<div class="row">
						<label><h5>
							Total Weight (g)</h5>
							<input class="form-control pull-right" type="number" min="0" value="" id="qbtotal1" style="text-align: right" disabled />
						</label>
						</div>
					
						</div>	
					
					</div>
				</div> 
</div>				
 
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs1">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage1"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc1"></span></h5>
							<div class="desc row pull-right">
							
								<input class="form-control" type="number" min="0" id="qbq1" style="text-align: right" disabled />	
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
			
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs2">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage2"></span>
						<!--<img src="../qblimages/icepack.jpg" style="max-width:150px" alt=""/>-->
						</div>
						
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc2"></span></h5>
							<div class="desc row pull-right">
							
								<input class="form-control" type="number" min="0" id="qbq2" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs3">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage3"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc3"></span></h5>
							<div class="desc row pull-right">
							
								<input class="form-control" type="number" min="0" id="qbq3" style="text-align: right" disabled />	
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs4">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage4"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc4"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq4" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs5">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage5"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc5"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq5" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs6">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage6"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc6"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq6" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs7">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage7"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc7"></span></h5>
							<div class="desc row pull-right">
							<input class="form-control" type="number" min="0" id="qbq7" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs8">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage8"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc8"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq8" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
				
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs9">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage9"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc9"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq9" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs10">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage10"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc10"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq10" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs11">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage11"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc11"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq11" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
			
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs12">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage12"></span>
						<!--<img src="../qblimages/icepack.jpg" style="max-width:150px" alt=""/>-->
						</div>
						
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc12"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq12" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs13">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage13"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc13"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq13" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs14">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage14"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc14"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq14" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="qbs15">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="qbimage15"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="qbdesc15"></span></h5>
							<div class="desc row pull-right">
							
							<input class="form-control" type="number" min="0" id="qbq15" style="text-align: right" disabled />
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
   		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
					<div class="dashboard-stat red-intense">
					
						<div class="col-6 visual">
						</div>
						<div class="details">
						<div class="desc" style="margin-top:20px">
						<div class="pull-left">
						<div class="title"><h5>
							Estimated QBL (ml)</h5>
							<input class="form-control pull-right" type="number" min="0" id="qbestqbl" style="text-align: right" disabled />
						</div>
						</div>
						
						</div>	
						
					
					</div>
				
						</div>
				</div> 	    
 		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
					<div class="dashboard-stat blue-madison">
					<div class="row">
						<div class="col-9">
						<div class="title" style="color:white"><h5>
							Notes:</h5>
							<textarea class="form-control" rows="3" id="qbqblNote"></textarea>
						</div>	
						</div>
						
						</div>
							<div class="title" style="color:white">Disclaimer: This tool should NOT be considered as a substitute for any professional medical service, NOR as a substitute for clinical judgement. Please read the full disclaimer.</div>			
				
						<div class="details">
						<div class="desc" style="margin-top:20px">
					
						
						</div>	
						
					
					</div>
				</div> 
</div>
             </div>  
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 

</div>
	
<div class="modal fade" id="editABL" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-md-6">
		<div class="modal-dialog modal-md">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">ABL Calculation</span>
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				
				<div class="row" style="padding-bottom: 10px;">
				<div class="modal-title"><span id="abInstructions"></span>
				  </div>
				  </div>
				<div class="row">
                        <div class="col-md-12">
						<div class="modal-title">Unit: <span id="abDept"></span>
				  </div>
						<div class="modal-title">Updated:  <span id="abDate"></span>
				  </div>		
                        </div>
                       </div>
                    <!--begin::Form-->
					
                    <form id="addabform" class="m-form m-form--fit bg-secondary" style="padding-top: 20px;">
						
				
					<div class="col-md-12">
		
							<div class="form-group row">
                                <label class="form-label col-md-12">
                                    <strong>Patient:</strong>
									<div class="title">Quantity of blood in mL/kg (per guidelines)</div>
									
                                 
                                    	<select id="abpatient" onchange="tj.ablselect();" class="form-control col-md-6" disabled>
									<option value="mL/kg">Select</option>
									<option value="1">Premature Infant (100mL/kg)</option>
									<option value="2">Infant under 3 months (85mL/kg)</option>
									<option value="3">Children over 3 months (75mL/kg)</option>
									<option value="4">Female Adolescents (65mL/kg)</option>
									<option value="5">Male Adolescents (70mL/kg)</option>
									<option value="6">Adult Female (65mL/kg)</option>
									<option value="7">Adult Male (75mL/kg)</option>
								</select>
                                </label>
                            </div>
							
								<div class="form-group row">
                                <label class="form-label col-md-12">
                                    <strong>Weight:</strong>
								<div class="row col-md-12">	
                                    <input type="number" min="0" class="form-control number col-md-6" id="abweight" disabled />
									<select id="weightType" class="form-control col-md-4" disabled >
									<option value="1">kg</option>
									<option value="2">lbs</option>
								</select>
								</div>
								</label>
								
                                
                            </div>
								<div class="form-group row">
                                <label for="ih" class="form-label col-md-12">
                                    <strong>Initial Hemoglobin of the Patient (g/dL):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="ih" placeholder="  g/dL" disabled />
                                </div>
						</label>
                            </div>
								<div class="form-group row">
                                <label for="fh" class="form-label col-md-12">
                                    <strong>Lowest Acceptable Hemoglobin (g/dL):</strong>
									<div class="title">Adjust this value depending on the patient's health.  It should be between 7 and 10 g/dL.</div>
									
                                 <div>
                                    <input type="number" min="0" value="10" class="form-control number" id="fh" placeholder="  g/dL" disabled />
                                </div>
						</label>
                            </div>
								<div class="form-group row">
                                <label for="abv" class="form-label col-md-12">
                                    <strong>Average Blood Volume (ABV):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="abv" placeholder="  %" disabled />
                                </div>
						</label>
                            </div>
							<hr></hr>
							<div class="form-group row">
                                <label for="estabv" class="form-label col-md-12">
                                    <strong>Estimated Blood Volume from Average Blood Volume (ml):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="estabv" disabled />
                                </div>
						</label>
                            </div>
							<div class="form-group row">
                                <label for="abl" class="form-label col-md-12">
                                    <strong>Allowable Blood Loss (ml):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="abl" disabled />
                                </div>
						</label>
                            </div>
							
							<!--
							<div class="form-group row">
                                <label for="absubmittedby" class="col-9 form-label" style="color:red">
                                    <strong>Submitted By (Required):</strong>
                                 <div>
                                    <input type="text" class="form-control" id="absubmittedby" placeholder="Enter your full name here.">
                                </div>
								</label>
                            </div>
							<input type="checkbox" id="abEsc" name="abEsc" onclick="tj.showabNote();" value="">
										 <strong>Escalate this Report</strong>
										</input>
							<textarea class="form-control" rows="3" placeholder="Escalation Details" id="abNote"></textarea>
							-->
							<div class="title">Disclaimer: This tool should NOT be considered as a substitute for any professional medical service, NOR as a substitute for clinical judgement. Please read the full disclaimer.</div>			
						  
						   </div>
                    </form>
					
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>

   
	


