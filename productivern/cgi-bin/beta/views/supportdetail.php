<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);

if($payPeriod>0 && $payFirst>0){
$calcPay = (($currentDay-$payFirst)/$payPeriod)- FLOOR(($currentDay-$payFirst)/$payPeriod);
	if($calcPay==0){
	$endPay=$payPeriod-1;
	$startPay = 0;
	}else{
	$startCalc = ROUND($payPeriod-($calcPay*$payPeriod),0);
	$startPay = $payPeriod-$startCalc;
	$endPay = ROUND($payPeriod-($calcPay*$payPeriod)-1,0);
	}
}else{
$startPay=0;
$endPay=0;
}
$userId = intval($_SESSION['account']['id']);

//$user = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';

$dbText = Config::get('db')->get_results("SELECT d.*, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.deptId IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) and u.txtEscalation>0 and u.txtactive>0 and u.txtPause=0 and d.escalations=0 and x.textAlerts>0");

if($dbText){
	$hidden = '';
}else{
	$hidden = "hidden";
}
//$dbHppd = Config::get('db')->get_results("SELECT d.*, count(d.id) as countId, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.userId={$userId} AND d.prodMeasure=2 group by d.accountId");
//$dbData = $dbHppd[0];



?>

<!-- BEGIN: Subheader -->
<div class="m-subheader" style="padding-bottom: 20px;">
	<input id="startSupport" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endSupport" type="hidden" value="<?php echo $endPay; ?>" />
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Reports - Support
            </h3>
        </div>
		<div class="row pull-right">
		<div class="d-flex align-items-center">
		<?php if($role>11) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="locationFiltersupport" name="locationFilterwhpsupport" onchange="tj.reportsSelect();" hidden>
			<option value="0">All Locations</option>
			<?php foreach($dbLocation as $a) { ?>
			<option value="<?php echo $a['id'];?>"><?php echo $a['name'];?> </option>
			<?php 	} ?>
			</select>&nbsp;
			<?php }else{ ?>
			<select  class="bs-select form-control input-sm" id="locationFiltersupport" name="locationFiltersupport" onchange="tj.reportsSelect();" hidden>
			</select>
			<?php } ?>
			
			<?php if($role>7 && $role<12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			}else if($role>=12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left outer join `productiveAccount` as a on a.id=d.accountId where c.id !=0 AND a.enterpriseId={$_SESSION['account']['enterpriseId']} group by c.id order by c.categoryName ASC");
			}else{
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.id IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}) group by c.id order by c.categoryName ASC");	
			}
			
			if($dbData && count($dbData)>1){ ?>
			<select  class="bs-select form-control input-sm" id="serviceFiltersupport" name="serviceFiltersupport" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>
			</select>
			<span class="m-subheader__daterange" id="staffing_daterangepickerwhp">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="staffing_daterangepickerwhp">	
			<?php } ?>
                <span class="m-subheader__daterange-label">
                    <span class="m-whp__daterange-title"></span>
                    <span class="m-whp__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row pull-right" <?php echo $hidden; ?>>
				     <button type="button" class="btn btn-danger" data-target="#addEscalation3whp" data-toggle="modal">
                                            Escalation
                                        </button>                         
					 </div>
                             <!--end: Dropdown-->
					<div class="row col-12">
                        <table id="prodTablewhp"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="15%" data-priority="1">Unit</th>
									<th width="10%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="3">Actual WHPUOS</th>
									<th width="10%" data-priority="5">Hours Variance</th>
									<th width="10%" data-priority="6">Units of Service</th>
									<th width="10%" data-priority="7">Total Hours</th>
									<th id="col1" value="<?php echo $dbData['skill1']; ?>"><?php echo $dbData['skilldesc1']; ?></th>
									<th id="col2" value="<?php echo $dbData['skill2']; ?>"><?php echo $dbData['skilldesc2']; ?></th>
									<th id="col3" value="<?php echo $dbData['skill3']; ?>"><?php echo $dbData['skilldesc3']; ?></th>
									<th id="col4" value="<?php echo $dbData['skill4']; ?>"><?php echo $dbData['skilldesc4']; ?></th>
									<th id="col5" value="<?php echo $dbData['skill5']; ?>"><?php echo $dbData['skilldesc5']; ?></th>
									<th width="20%" data-priority="4">Note</th>
                                </tr>
                            </thead>
                        </table>
						</div>
						</div>
                   </div>
    <!--End::Main Portlet-->
</div>


	<div class="modal fade" id="addprodnotewhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdwhp" type="hidden" value="" />
						<input id="notedeptIdwhp" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodywhp">Your Staffing Report shows a possible <span id="varianceTypewhp"></span> variance for <span id="deptwhp"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control" rows="3" id="notebodywhp" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" id="noteButtonwhp" class="btn btn-primary" onclick="tj.addProdNotewhp();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
 
 <!-- /begin modal -->
<div class="modal fade" id="addWHP" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header">
				  <h4 class="modal-title text-white">Productivity Report
				  </h4>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        <div class="col-sm-12 job-address">
						<!--
						<div class="modal-title pull-right"><span id="dataId2WHP"></span> 
				  </div>
				  -->
						<div class="modal-title">Report Date - Time:  <span id="reportdateWHP"></span> - <span id="reportshiftWHP"></span>
				  </div>
						
                           
							<div class="modal-title" style="padding-bottom: 20px;">Last Updated By:   <span id="userNameWHP"></span>
							</div>
							
							<div class="modal-title">Target WHPUOS:   <span id="targetWHP"></span>
							</div>
							<!--
							<div class="modal-title">Variance(Hours):   <span id="plannedVar"></span>
							</div>
							-->
							<div class="modal-title">Actual WHPUOS:   <span id="currentWHP"></span>
							</div>
							
							<div class="modal-title">Hours Variance:   <span id="currentVar"></span>
							</div>
						
							<!--
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formWHP" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userIdWHP" type="hidden" value="" />
						<input id="shiftWHP" type="hidden" value="" />
						<input id="actualWHP" type="hidden" value="" />
						<input id="dayWHP" type="hidden" value="" />
						<input id="dataIdWHP" type="hidden" value="" />
						<div class="m-portlet__body">
						
						
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title"><span id="actual"></span> </h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="plannedWHP" class="col-7 col-form-label">
                                   <span id="uosDesc"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="plannedWHP" style="text-align: right">
                                </div>
                            </div>
							<hr></hr>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill1" >
                                <label for="skillval1" class="col-7 col-form-label">
                                    <span id="descskill1"></span>:
                                </label>
                                <div class="col-5">
								<input type="number" min="0" class="form-control number" id="skillval1" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill2" >
                                <label for="skillval2" class="col-7 col-form-label">
                                    <span id="descskill2"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval2" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill3">
                                <label for="skillval3" class="col-7 col-form-label">
                                    <span id="descskill3"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval3" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill4">
                                <label for="skillval4" class="col-7 col-form-label">
                                    <span id="descskill4"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval4" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill5">
                                <label for="skillval5" class="col-7 col-form-label">
                                   <span id="descskill5"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval5" style="text-align: right">
                                </div>
                            </div>
															
							
							<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
                              <h5 class="modal-title">Note / Action Plan</h5>
								</div>
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" rows="3" id="prodnoteWHP"></textarea>
                            </div>
												
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdWHP();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>
	
<div class="modal fade" id="escalationNEWwhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="escwhp" type="hidden" value="" />
				<input id="deptIdescwhp" type="hidden" value="" />
				<input id="dataIdescwhp" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require immediate escalation (ie. Staffing, Supplies, Close Calls, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer">
                    <button type="button" id="noescbuttonwhp" class="btn btn-secondary" onclick="tj.noEscalationwhp();">No</button>
                    <button type="button" id="addescbuttonwhp" class="btn btn-primary" data-target="#addEscalationwhp" data-toggle="modal">Yes</button>
                </div>
				<div class="modal-body">
				<div class="title">To remove the Escalations feature, go to Unit Settings.
					</div>
					</div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
   
	
<div class="modal fade" id="addEscalationwhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2whp" type="hidden" value="" />
						<input id="deptId2whp" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escvalwhp">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escvalwhp" name="escvalwhp" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `id` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										</div>  
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcommentwhp">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcommentwhp" ></textarea>
                            </div>
							<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="Escbuttonwhp" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbuttonwhp" class="btn btn-primary" onclick="tj.addEscalationwhp();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3whp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3whp" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3whp">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              	
										<select id="escval3whp" name="escval3" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `id` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="deptId3whp">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3whp" name="deptId3whp" class="form-control">
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.* from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id LEFT JOIN `productiveUser` as u on u.id=x.userId where x.deptId in (SELECT `deptId` from `productiveDeptXref`where `userId`={$_SESSION['account']['id']}) and x.textAlerts >0 and u.txtEscalation>0 and u.txtPause=0 and d.escalations=0 group by d.id order by d.dept ASC");
								foreach($dbUnit as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['dept'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment3whp">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcomment3whp" ></textarea>
                            </div>
							<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="Escbutton3whp" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton3whp" class="btn btn-primary" onclick="tj.addEscalation3whp();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

