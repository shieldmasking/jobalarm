<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['id']);
$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";


?><!-- BEGIN: Subheader -->
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>

<script type="text/javascript">
    window.onbeforeunload = function() {
		return "Reload site?  Changes may not be saved.";
    }
</script>

 <style>
.modal {
    overflow-y: scroll;
}

input[type='number']{
    width: 80px;
	font-size:14px;
} 

input[type='button']{
	font-size:20px;
} 

img {
  max-width: 150px;
  height: auto;
}
</style>
<!-- BEGIN GLOBAL MANDATORY STYLES -->


</head>

<!-- END: Subheader -->
<div class="m-content">
<!--
<div class="m-subheader">
   <div class="d-flex align-items-center">
	<div class="mr-auto">
            <div class="m-subheader__title ">
				<input type="file" capture="camera" accept="image/*" id="cameraInput" name="cameraInput">
				
            </div>
        </div>
		<div class="mr-auto">
            <div class="m-subheader__title ">
				<input type="file" capture="camera" accept="image/*" id="cameraInput" name="cameraInput">
				<input type="file" accept="image/*;capture=camera" id="cameraInput" name="cameraInput">
				
            </div>
        </div>
	 </div>
</div>

-->

    <!--Begin::Main Portlet-->

<!-- BEGIN: Subheader -->
<div class="m-subheader" style="padding-bottom: 20px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                ABL Calculator
            </h3>
		<span id="ablunitName"></span>
        </div> 
</div>
				
                    <form id="addabform" class="m-form m-form--fit bg-secondary" style="padding-top: 20px;">
					
				
					<div class="col-md-12">
		
							<div class="form-group row">
                                <label class="form-label col-md-12">
                                    <strong>Patient:</strong>
									<div class="title">Quantity of blood in mL per kg (per guidelines)</div>
									
                                 
                                    	<select id="ablpatient" onchange="tj.selectabl();" class="form-control col-md-6" >
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
						</div>
<div class="form-group col-md-12">						
						<div class="row col-12">
                                <label class="form-label">
                                    <strong>Weight:</strong></label> 
						</div>
						<div class="row">
								<div class="col-3">
                                    <input type="number" min="0" class="form-control number" id="ablweight" />
								</div>
								<div class="col-4">
									<select id="ablweightType" class="form-control" >
									<option value="1">kg</option>
									<option value="2.20462">lbs</option>
								</select>
								</div>
						</div>            
                      </div>
					  <div class="form-group col-md-12">	
								<div class="row">
                                <label for="ablih" class="form-label col-12">
                                    <strong>Initial Hemoglobin of the Patient (g/dL):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="ablih" placeholder="  g/dL" />
                                </div>
						</label>
                            </div>
							</div>
							<div class="form-group col-md-12">	
								<div class="row">
                                <label for="ablfh" class="form-label col-12">
                               <strong>Lowest Acceptable Hemoglobin (g/dL):</strong>
									<div class="title">Adjust this value depending on the patient's health.  It should be between 7 and 10 g/dL.</div>
														
                                 <div>
                                    <input type="number" min="0" value="7" class="form-control number" id="ablfh" />
                                </div>
						</label>
                            </div>
							</div>
							<div class="form-group col-md-12">
								<div class="row">
                                <label for="ablabv" class="form-label col-12">
                                    <strong>Average Blood Volume:</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="ablabv" placeholder="  %" />
                                </div>
						</label>
						
					
                            </div>
						</div>
							<hr></hr>
							<div class="form-group col-md-12">
							<div class="row">
                                <label for="ablestabv" class="form-label col-12">
                                    <strong>Estimated Blood Volume (EBV) from Average Blood Volume (ABV):</strong>
									
                                 <div>
                                    <input type="text" min="0" class="form-control number col-4" id="ablestabv" disabled />
                                </div>
						</label>
                            </div>
							</div>
							<div class="form-group col-md-12">
							<div class="row">
                                <label for="ablabl" class="form-label col-12">
                                    <strong>Allowable Blood Loss (ABL):</strong>
									
                                 <div>
                                    <input type="text" min="0" class="form-control number col-4" id="ablabl" disabled />
                                </div>
						</label>
                            </div>
							</div>
									<div class="col-6" style="margin-top:10px">
						
						<input type="button" class="btn btn-success btn-sm" value="CALCULATE" onclick="tj.update_ABL();" ></input>
							
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
							<div class="col-12 title">Disclaimer: This tool should NOT be considered as a substitute for any professional medical service, NOR as a substitute for clinical judgement. Please read the full disclaimer.</div>			
						  
						   
                    </form>
						

							 

</div>
</html>