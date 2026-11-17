<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['id']);
//$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";


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

/* Larger tap targets for +/- buttons on mobile */
#qblform .btn-sm {
    min-height: 48px;
    min-width: 56px;
    font-size: 20px;
    padding: 8px 16px;
}

/* Compact supply item cards */
div[id^="s"] .label {
    margin-top: 8px !important;
}
div[id^="s"] .dashboard-stat {
    padding-top: 4px !important;
    padding-bottom: 4px !important;
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
                QBL Calculator
            </h3>
		<span id="unitName"></span>
		<button type="button" class="btn btn-info" style="margin-bottom:10px;margin-top:10;display:none;" data-target="#ABL" data-toggle="modal">
                                            Add ABL
                                        </button>
										<div class="title pull-right"><strong><span id="newabl"></span></strong></div>
        </div>
		
</div>
<form id="qblform">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px 15px 0;">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="qblTrainingOnly" onchange="if(this.checked){$.post('inc/data.php?req=qbltraintest',{logId:$('#logId').val()});}" />
			<label class="form-check-label" for="qblTrainingOnly"><strong>QBL Training Only</strong> &mdash; results will not be saved</label>
		</div>
	</div>
   		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
					<div class="dashboard-stat green-haze">
					
						<div class="col-6 visual">
						<input type="button" class="btn btn-danger btn-sm" value="Reset" style="margin-top:30px" onclick="tj.resetqbl();" ></input>
						
						</div>
						<div class="details">
						<div class="desc" style="margin-top:10px">
						<div class="row">
						<label><h5>
							1. Enter Total Weight (g):</h5>
							<input class="form-control pull-right" type="number" min="0" value="" id="total1" inputmode="decimal" style="text-align: right" />
						</label>
						</div>
						
						<div class="row">
						<label><h5>
							2. Select Supplies Included:</h5>
						</label>
						</div>	
						</div>	
					
					</div>
				</div> 
</div>				
 

				
				<input id="val1" type="number" hidden />
				<input id="val2" type="number" hidden />
				<input id="val3" type="number" hidden />
				<input id="val4" type="number" hidden />
				<input id="val5" type="number" hidden />
				<input id="val6" type="number" hidden />
				<input id="val7" type="number" hidden />
				<input id="val8" type="number" hidden />
				<input id="val9" type="number" hidden />
				<input id="val10" type="number" hidden />
				<input id="val11" type="number" hidden />
				<input id="val12" type="number" hidden />
				<input id="val13" type="number" hidden />
				<input id="val14" type="number" hidden />
				<input id="val15" type="number" hidden />
				<input id="val16" type="number" hidden />
				<input id="val17" type="number" hidden />
				<input id="val18" type="number" hidden />
				<input id="val19" type="number" hidden />
				<input id="val20" type="number" hidden />
				<input id="logId" type="number" hidden />
				<input id="locId" type="number" hidden />
				<input id="resetqbl" type="number" hidden />
				<input id="userId" type="number" hidden />
				<input id="roomId" type="number" hidden />
				<input id="qbldept" type="number" hidden />
				<input id="txtSent" type="number" hidden />
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s1">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image1"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc1"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(1);" ></input>
								<input class="form-control" type="number" min="0" id="q1" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(1);" ></input>	
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
			
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s2">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image2"></span>
						<!--<img src="../qblimages/icepack.jpg" style="max-width:150px" alt=""/>-->
						</div>
						
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc2"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(2);" ></input>
								<input class="form-control" type="number" min="0" id="q2" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(2);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s3">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image3"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc3"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(3);" ></input>
								<input class="form-control" type="number" min="0" id="q3" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(3);" ></input>	
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s4">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image4"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc4"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(4);" ></input>
								<input class="form-control" type="number" min="0" id="q4" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(4);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s5">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image5"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc5"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(5);" ></input>
								<input class="form-control" type="number" min="0" id="q5" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(5);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s6">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image6"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc6"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(6);" ></input>
								<input class="form-control" type="number" min="0" id="q6" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(6);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s7">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image7"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc7"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(7);" ></input>
								<input class="form-control" type="number" min="0" id="q7" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(7);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s8">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image8"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc8"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(8);" ></input>
								<input class="form-control" type="number" min="0" id="q8" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(8);" ></input>	
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
				
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s9">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image9"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc9"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(9);" ></input>
								<input class="form-control" type="number" min="0" id="q9" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(9);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s10">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image10"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc10"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(10);" ></input>
								<input class="form-control" type="number" min="0" id="q10" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(10);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s11">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image11"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc11"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(11);" ></input>
								<input class="form-control" type="number" min="0" id="q11" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(11);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
			
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s12">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image12"></span>
						<!--<img src="../qblimages/icepack.jpg" style="max-width:150px" alt=""/>-->
						</div>
						
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc12"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(12);" ></input>
								<input class="form-control" type="number" min="0" id="q12" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(12);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s13">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image13"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc13"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(13);" ></input>
								<input class="form-control" type="number" min="0" id="q13" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(13);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s14">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image14"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc14"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(14);" ></input>
								<input class="form-control" type="number" min="0" id="q14" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(14);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s15">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image15"></span>
						</div>
						
						<div class="details">
						
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc15"></span></h5>
							<div class="desc row pull-right">
							
							
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(15);" ></input>
								<input class="form-control" type="number" min="0" id="q15" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(15);" ></input>
						 
							</div>
						</div>		 
							
							
						</div>
					</div>
				</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s16">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image16"></span>
						</div>
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc16"></span></h5>
							<div class="desc row pull-right">
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(16);" ></input>
								<input class="form-control" type="number" min="0" id="q16" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(16);" ></input>
							</div>
						</div>
						</div>
					</div>
				</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s17">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image17"></span>
						</div>
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc17"></span></h5>
							<div class="desc row pull-right">
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(17);" ></input>
								<input class="form-control" type="number" min="0" id="q17" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(17);" ></input>
							</div>
						</div>
						</div>
					</div>
				</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s18">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image18"></span>
						</div>
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc18"></span></h5>
							<div class="desc row pull-right">
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(18);" ></input>
								<input class="form-control" type="number" min="0" id="q18" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(18);" ></input>
							</div>
						</div>
						</div>
					</div>
				</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s19">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image19"></span>
						</div>
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc19"></span></h5>
							<div class="desc row pull-right">
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(19);" ></input>
								<input class="form-control" type="number" min="0" id="q19" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(19);" ></input>
							</div>
						</div>
						</div>
					</div>
				</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="s20">
					<div class="dashboard-stat blue-madison">
						<div class="visual pull-left">
						<span id="image20"></span>
						</div>
						<div class="details">
						<div class="label" style="color:white; margin-top:20px"><h5><span id="desc20"></span></h5>
							<div class="desc row pull-right">
							<input type="button" class="btn btn-danger btn-sm" value="  -  "onclick="tj.minusqbl(20);" ></input>
								<input class="form-control" type="number" min="0" id="q20" style="text-align: right" disabled />
							<input type="button" class="btn btn-success btn-sm" value="  +  " onclick="tj.plusqbl(20);" ></input>
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
							3. Estimated QBL (ml):</h5>
							<div class="input-group">
							<span class="input-group-btn">
							<input type="button" id="calculateBtn" class="btn btn-success btn-sm" value="CALCULATE" onclick="tj.updateqbl(1);" />
							</span>
							<input class="form-control" type="number" min="0" value="0" id="estqbl" disabled style="max-width:150px;" />
							</div>
						</div>
						</div>
						
						</div>	
						
					
					</div>
				
						</div>
					  
				</div> 	    
 		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;padding:14px 15px;" id="closeQblBtn" hidden>
			<button type="button" class="btn btn-danger btn-lg" onclick="tj.closeQbl();">Close</button>
		</div>
 		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
					<div class="dashboard-stat blue-madison">
					<div class="row">
					<div class="col-9">
					<label for="ers" class="form-label" style="color:white" hidden>ERS Completed:</label>
                                   
                        <input type="checkbox" id="ers" name="ers" value="" hidden></input>
           
						 <label for="ers" class="form-label" style="color:white" hidden>Debrief Completed:</label>
                                   
                        <input type="checkbox" id="debrief" name="debrief" value="" hidden></input>
					</div>
					</div>
					<div class="row">
						</div>
						<div class="title" style="color:white">Disclaimer: This tool should NOT be considered as a substitute for any professional medical service, NOR as a substitute for clinical judgement. Please read the full disclaimer.</div>			
				
						<div class="details">
						<div class="desc" style="margin-top:20px">
					
						
						</div>	
						
					
					</div>
				</div> 
</div>	   
		
 </form>

<div class="modal fade" id="ABL" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-md-6">
		<div class="modal-dialog modal-md">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">ABL Calculator</span>
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				
				<div class="row" style="padding-bottom: 10px;">
				<div class="modal-title"><span id="ablInstructions"></span>
				  </div>
				  </div>
		
                    <!--begin::Form-->
					
                    <form id="addablform" class="m-form m-form--fit bg-secondary" style="padding-top: 20px;">
			
				
					<div class="col-md-12">
		
							<div class="form-group row">
                                <label class="form-label col-md-12">
                                    <strong>Patient:</strong>
									<div class="title">Quantity of blood in mL/kg (per guidelines)</div>
									
                                 
                                    	<select id="ablpatient" onchange="tj.selectabl();" class="form-control col-md-6" >
									<option value="0">Select</option>
									<option value="1">Premature Infant (100mL/kg)</option>
									<option value="2">Infant under 3 months (85mL/kg)</option>
									<option value="3">Children over 3 months (75mL/kg)</option>
									<option value="4">Female Adolescent (65mL/kg)</option>
									<option value="5">Male Adolescent (70mL/kg)</option>
									<option value="6">Adult Female (65mL/kg)</option>
									<option value="7">Adult Male (75mL/kg)</option>
								</select>
                                </label>
                            </div>
							
								<div class="form-group row">
                                <label class="form-label col-md-12">
                                    <strong>Weight:</strong>
								<div class="row col-md-12">	
                                    <input type="number" min="0" onchange="tj.newabl();" class="form-control number col-md-6" id="ablweight" />
									<select id="ablweightType" onchange="tj.newabl();" class="form-control col-md-4" >
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
                                  <input type="number" min="0" onchange="tj.newabl();" class="form-control number" id="ablih" placeholder="  g/dL" />
                               <!-- <select id="ablih" onchange="tj.newabl();" class="form-control col-md-4" >
									<option value="0">g/dL</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
								</select> -->
								</div>
						</label>
                            </div>
								<div class="form-group row">
                                <label for="fh" class="form-label col-md-12">
                                    <strong>Lowest Acceptable Hemoglobin (g/dL):</strong>
									<div class="title">Adjust this value depending on the patient's health.  It should be between 7 and 10 g/dL.</div>
									
                                 <div>
                                 <input type="number" min="0" onchange="tj.newabl();" class="form-control number" id="ablfh" placeholder="  g/dL" />
                                 <!--<select id="ablfh" onchange="tj.newabl();" class="form-control col-md-4" >
									<option value="0">g/dL</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
								</select>-->
								</div>
						</label>
                            </div>
								<div class="form-group row">
                                <label for="abv" class="form-label col-md-12">
                                    <strong>Average Blood Volume:</strong>
									
                                 <div>
                                    <input type="number" min="0" onchange="tj.newabl();" class="form-control number" id="ablabv" placeholder="  %" />
                                </div>
						</label>
                            </div>
							<hr></hr>
							<div class="form-group row">
                                <label for="estabv" class="form-label col-12">
                                    <strong>Estimated Blood Volume (EBV) from Average Blood Volume (ml):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="ablestabv" disabled />
                                </div>
						</label>
                            </div>
							<div class="form-group row">
                                <label for="abl" class="form-label col-12">
                                    <strong>Estimated Allowable Blood Loss (ml):</strong>
									
                                 <div>
                                    <input type="number" min="0" class="form-control number" id="ablabl" disabled />
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
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submitab" class="btn btn-primary" onclick="tj.ablupdate();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>
							 

</div>
</html>