<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$company = $_SESSION['account']['company'];


?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Unit Manager
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content" id="accountList">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >6): ?>
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addnewUnits" data-toggle="modal">
                                            Add Account
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="adminAcctTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="40%" data-priority="1">Account</th>
									<th width="30%" data-priority="2">City</th>
                                    <th width="10%" data-priority="3">St</th>
									 <th width="20%" data-priority="4">Label</th>
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--End::Main Portlet-->
</div>

<div class="m-content" id="unitList">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >6): ?>
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addnewUnits" data-toggle="modal">
                                            Add Unit
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="adminUnitsTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="20%" data-priority="1">Unit</th>
									<th width="20%" data-priority="2">Service Line</th>
                                    <th width="10%" data-priority="3">Unit ID</th>
									 <th width="15%" data-priority="4">Unit Measure</th>
                                    <th width="15%" data-priority="5">Unit of Service</th>
									<!--<th width="15%" data-priority="3">Prod% (30 Day Avg.)</th> -->
									<th width="20%" data-priority="6">Manager</th>
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--End::Main Portlet-->
</div>

<div class="m-content" id="userList">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >6): ?>
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addnewUnits" data-toggle="modal">
                                            Add User
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="adminUsersTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="30%" data-priority="1">Name</th>
									<th width="20%" data-priority="2">Account</th>
                                    <th width="20%" data-priority="3">Unit</th>
									 <th width="15%" data-priority="4">Role</th>
                                    <th width="15%" data-priority="5">Texting</th>
                                </tr>
                            </thead>
                        </table>
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
 	<div class="modal fade" id="addnewUnits" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-light">
                    <h4 class="modal-title">Add New Unit</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_unit" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                           	<div class="form-group">
                                <label for="unitName" class="col-form-label">
                                    Unit Name*:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="unitName" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="unitNumber" class="col-form-label">
                                    Unit ID/Number*:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="unitNumber" >
                                </div>
                            </div>
							<div class="form-group">
                               	<label for="newDirector" class="col-form-label">Unit Director*:</label>
                                <div class="col-8">
                                    <select id="newDirector" type="select" class="form-control" required >
									<option value="0">Select Director</option>
															
								<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} and `role`>=7 order by `last_name`, `first_name` ASC");
								foreach($dbData as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['last_name'].", ".$b['first_name'];?> </option>
								<?php 
								} 
								?>
							</select>
                            </div>
							</div>
							<div class="form-group">
                               	<label for="newManager" class="col-form-label">Unit Manager:</label>
                                <div class="col-8">
                                    <select id="newManager" type="select" class="form-control" required >
									<option value="0">Select Manager</option>
															
								<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} and `role`>=6 order by `last_name`, `first_name` ASC");
								foreach($dbData as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['last_name'].", ".$b['first_name'];?> </option>
								<?php 
								} 
								?>
							</select>
                            </div>
							</div>
							<div class="form-group">
                                <label for="unitprodMeasure" class="col-form-label">
                                    Productivity Measure:
                                </label>
                                <div class="col-8">
								<select id="unitprodMeasure" type="select" class="form-control">
										<option value=0>Select..</option>
										<option value=1>HPPD</option>
										<option value=2>WHPUOS</option>
										<option value=3>Other</option>
										</select>
                                    
                                </div>
                            </div>
							<div class="form-group" hidden>
							    <label for="unitTarget" class="col-form-label">Productivity Target %:</label>
								<div class="col-4">
                                <input type="text" class="form-control number" id="unitTarget" value="100">
							</div>
                            </div>
							
							<div class="form-group">
							    <label for="unitprodValue" class="col-form-label">HPPD/WHPUOS Value*:</label>
								<div class="col-8">
                                <input type="number" class="form-control number" id="unitprodValue" placeholder="ie. 9.301 or 1.65">
							</div>
                            </div>
							<div class="form-group" >
							    <label for="unitBeds" class="col-form-label">Bed Count:</label>
								<div class="col-8">
                                <input type="number" class="form-control number" id="unitBeds" placeholder="Reqd for Nursing Units using HPPD.">
							</div>
                            </div>
						
							<div class="form-group" hidden>
                               	<label for="censusShift" class="col-form-label">Census Time:</label>
                                <div class="col-3">
                                    <select id="censusShift" type="select" class="input-large" required >
										<option value=52>12:00 Midnight</option>
										<option value=50>1:00 AM</option>
										<option value=48>2:00 AM</option>
										<option value=46>3:00 AM</option>
										<option value=44>4:00 AM</option>
										<option value=40>5:00 AM</option>
										<option value=38>6:00 AM</option>
										<option value=36>7:00 AM</option>
										<option value=0>No Census</option>
										</select>
                            </div>
						 </div>
						 	 
                            							
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addUnit();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>
	    <!--begin::Modal-->
		
<div id="confirmUnit" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="msgType"></span></h4>
            </div>
            <div class="modal-body" align="center">
                <h5><span id="Message"></span></h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <!--end::Modal-->

    <!--begin::Modal-->
  


<div class="m-content" id="unitDetails">
    <!--Begin::Main Portlet-->

        
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body bg-light">
				 <div class="row">
                        <div class="col-sm-6">
						</div>
						
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                                              
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
				</div>
             
                    <!--begin::Form-->
					<div class="title">
				 <h4>Unit Configuration</h4>
				 </div>
                    <form id="edit_unit_formMgr" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="unitIdMgr" type="hidden" value="" />
						<input id="mgrOrig" type="hidden" value="" />
						<input id="dirOrig" type="hidden" value="" />
						<input id="shiftsOrig" type="hidden" value="" />
					<div class="col-md-12">
						
				 <div class="form-group">
				 <div class="row">
				 
							<div class="col-6">
                           	
                                <label for="editunitNameMgr" class="col-form-label">
                                    Unit Name:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="editunitNameMgr" required>
                                </div>
                           
							</div>
							<div class="col-6">
                            
                                <label for="editunitNumberMgr" class="col-form-label">
                                    Unit ID/Number:
                                </label>
                                <div class="col-4">
                                    <input class="form-control m-input" type="text" value="" id="editunitNumberMgr" required>
                                </div>
                            </div>
							</div>
							</div>
							
							<div class="form-group">
							<div class="row">
				 
							<div class="col-6">
                               	<label for="unitManagerMgr" class="col-form-label">Manager:    <span id="currentManagerMgr"></span></label>
                                <div class="col-8" id="unitmgr">
                                    <select id="unitManagerMgr" type="select" class="form-control" >
									<option value="0">Select New Manager</option>
									<option value="-1">No Manager (Remove this Manager)</option>
															
								<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} and `role`>5 and `role`<10 order by `last_name`, `first_name` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['last_name'].", ".$m['first_name'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
							
							<div class="col-6">
                               	<label for="unitDirector" class="col-form-label">Director:    <span id="currentDirector"></span></label>
                                <div class="col-8" id="unitdir">
                                    <select id="unitDirector" type="select" class="form-control" >
									<option value="0">Select New Director</option>
									<option value="-1">No Director (Remove this Manager)</option>
															
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} and `role`>6 and 'role'<9 order by `last_name`, `first_name` ASC");
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['last_name'].", ".$d['first_name'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
							</div>							
							</div>
							<div class="form-group">
							<div class="row">
							<div class="col-6">
							<label for="serviceLine" class="col-form-label" style="padding-right: 20px;">Service Line:
							<button type="button" class="btn btn-info btn-sm" data-target="#addCategory" data-toggle="modal">
                                            Add
                                        </button> 
							</label>
							
                                <div class="col-8">
                                    <select id="serviceLine" type="select" class="form-control" >
									<option value="0">Select</option>
								<?php
								$dbCat = Config::get('db')->get_results("SELECT * from `productiveCategory` where `accountId`={$accountId} OR `accountId`=0 order by `categoryName` ASC");
								foreach($dbCat as $c) 
								{ 
								?>
								<option value="<?php echo $c['id'];?>"><?php echo $c['categoryName'];?> </option>
								<?php 
								} 
								?>
										</select>
								 </div>
							
							</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							<div class="col-6">
                                <label for="editunitprodMeasureMgr" class="col-form-label">
                                    Productivity Measure:
                                </label>
                                <div class="col-8">
								<select id="editunitprodMeasureMgr" type="select" class="form-control">
										<option value=1>HPPD</option>
										<option value=2>WHPUOS</option>
										<option value=3>Other</option>
										</select>
                                    
                                </div>
                            </div>
							
							<div class="col-6">
							    <label for="editunitprodValueMgr" class="col-form-label">HPPD/WHPUOS Value:</label>
								<div class="col-4">
                                <input type="number" class="form-control number" id="editunitprodValueMgr" style="text-align: right">
							</div>
                            </div>
							</div>
							</div>
							
							<div class="form-group" id="uosDescription">
							<div class="row">
							<div class="col-md-6">
							 <label for="editunituosDescMgr" class="col-form-label">Unit of Service Description:</label>
							<div class="col-8">
                             <input type="text" class="form-control number" id="editunituosDescMgr">
							</div>
							</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							<div class="col-md-6">
							<label for="thresholdHigh" class="col-form-label">High Indicator Value:</label>
								<div class="col-4">
                                <input type="number" class="form-control number" id="thresholdHigh" style="text-align: right">
							</div>
							</div>
							<div class="col-md-6">
							<label for="thresholdLow" class="col-form-label">Low Indicator Value:</label>
								<div class="col-4">
                                <input type="number" class="form-control number" id="thresholdLow" style="text-align: right">
							</div>
                            </div>
							</div>
							</div>
							<div class="form-group" id="budget">
							<div class="row">
							<div class="col-md-6">
							    <label for="budgetMeasure" class="col-form-label">Use Budget Values:</label>
								<div class="col-4">
                                <select type="select" class="form-control" id="budgetMeasure">
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
							</div>
							</div>
							<div class="col-md-6">
							    <label for="budgetValue" class="col-form-label">Daily Budget Value($):</label>
								<div class="col-4">
                                <input type="number" class="form-control number" id="budgetValue" style="text-align: right">
							</div>
							</div>
							</div>
							</div>
							
							<div class="row">
							
							<div class="col-md-6" id="whp2">
                               	<label for="editcensusShiftMgr" class="col-form-label">Census Time:</label>
                                <div class="col-6">
                                    <select id="editcensusShiftMgr" type="select" class="form-control" required >
										<option value=52>12:00 Midnight</option>
										<option value=50>1:00 AM</option>
										<option value=48>2:00 AM</option>
										<option value=46>3:00 AM</option>
										<option value=44>4:00 AM</option>
										<option value=40>5:00 AM</option>
										<option value=38>6:00 AM</option>
										<option value=36>7:00 AM</option>
										<option value=0>No Census</option>
										</select>
                            </div>
						 </div>
						 <div class="col-md-6" id="nurse2">
                               	<label for="shiftsDay" class="col-form-label">Reporting Shifts Per Day:</label>
                                <div class="col-4">
                                    <select id="shiftsDay" type="select" class="form-control" required >
										<option value="2">2</option>
										<option value="4">4</option>
										<option value="6">6</option>
										</select>
                            </div>
						 </div>
						 </div>
						 <div class="row">
						 <div class="col-md-6" id="whp1">
							    <label for="editunitBedsMgr" class="col-form-label">Bed Count:</label>
								<div class="col-4">
                                <input type="number" class="form-control number" id="editunitBedsMgr" style="text-align: right">
							</div>
                            </div>
						 <div class="col-md-6">
                                <label for="addEscalations" class="col-form-label">
                                    Allow Escalations via Text:
                                </label>
                                <div class="col-4">
								<select id="addEscalations" type="select" class="form-control">
										<option value="0">Yes</option>
										<option value="1">No</option>
										</select>
                                    
                                </div>
                            </div>
							</div>
						<div id="whp">
						<div class="row">
						
						 <div class="col-md-6">
							    <label for="churn" class="col-form-label">Show Churn:</label>
								<div class="col-4">
                                <select type="select" class="form-control" id="churn">
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
							</div>
							</div>
						
							</div>
							
						<div class="form-group" hidden>
							    <label for="editunitTargetMgr" class="col-form-label">Productivity Target %:</label>
								<div class="col-8">
                                <input type="number" class="form-control number" id="editunitTargetMgr" required>
							</div>
                            </div>
						 <hr></hr>
					
					<div class="title" style="padding-top: 20px;">
				 <h4>Acuity Levels</h4>
				 </div>
				 <div class="title">
				 <p>Select the Acuity Levels appropriate for your Unit.  Descriptions can be as simple as High, Med, Low or more specific for each Acuity Level.</p>
				 </div>
				
                       <div class="row">
					   <div class="col-md-6">
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2oneCheckedMgr" name="one2oneCheckedMgr" value="1" />
							<strong> 1 to 1</strong>
							</label>
							<div>
							<label for="descConfig1Mgr" class="col-9 col-form-label">Description:
							
							<input type="text" id="descConfig1Mgr" class="form-control">
							</label>
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2twoCheckedMgr" name="one2twoCheckedMgr" value="1" />
							<strong> 1 to 2</strong>
							</label>
							<div>
							<label for="descConfig2Mgr" class="col-9 col-form-label">Description: 
							<input type="text" id="descConfig2Mgr" class="form-control">
							</label>
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2threeCheckedMgr" name="one2threeCheckedMgr" value="1" />
							<strong> 1 to 3</strong></br>
							</label>
							<div>
							<label for="descConfig3Mgr" class="col-9 col-form-label">Description:
							<input type="text" id="descConfig3Mgr" class="form-control">
							</label>
							</div>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2fourCheckedMgr" name="one2fourCheckedMgr" value="1" />
							<strong> 1 to 4</strong></br>
							</label>
							<div>
							<label for="descConfig4Mgr" class="col-9 col-form-label">Description:
							<input type="text" id="descConfig4Mgr" class="form-control">
							</label>
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2fiveCheckedMgr" name="one2fiveCheckedMgr" value="1" />
							<strong> 1 to 5</strong></br>
							</label>
							<div>
							<label for="descConfig5Mgr" class="col-9 col-form-label">Description:
							<input type="text" id="descConfig5Mgr" class="form-control">
							</label>
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2sixCheckedMgr" name="one2sixCheckedMgr" value="1" />
							<strong> 1 to 6</strong></br>
							</label>
							<div>
							<label for="descConfig6Mgr" class="col-9 col-form-label">Description:  
							<input type="text" id="descConfig6Mgr" class="form-control">
							</label>
							</div>
						</div>
						</div>
					</div>
				</div>	
				<div id="skill">
				 <hr></hr>
					<div class="title" style="padding-top: 20px;">
				 <h4>WHPUOS Resource Hours</h4>
				 </div>
				 <div class="title">
				 <p>Optional:  Define up to 5 Resources for this Support/Ancillary Unit.  Default is one field labeled "Hours of Service".</p>
				 </div>
				
                       
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill1" name="skill1" value="1" />
							<strong> Resource 1</strong>
							</label>
							<div class="row">
							<div class="col-md-6">
							<label for="skilldesc1" class="col-form-label">Description:
							
							<input type="text" id="skilldesc1" class="form-control">
							</label>
							</div>
							<div class="col-md-6">
							<label for="skillbudget1" class="col-form-label">Hourly Budget Value($):
							
							<input type="number" id="skillbudget1" class="form-control number">
							</label>
							</div>
						</div>
						</div>
						
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill2" name="skill2" value="1" />
							<strong> Resource 2</strong>
							</label>
							<div class="row">
							<div class="col-md-6">
							<label for="skilldesc2" class="col-form-label">Description: 
							<input type="text" id="skilldesc2" class="form-control">
							</label>
							</div>
							<div class="col-md-6">
							<label for="skillbudget2" class="col-form-label">Hourly Budget Value($):
							
							<input type="number" id="skillbudget2" class="form-control number">
							</label>
							</div>
						</div>
						</div>
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill3" name="skill3" value="1" />
							<strong> Resource 3</strong></br>
							</label>
							<div class="row">
							<div class="col-md-6">
							<label for="skilldesc3" class="col-form-label">Description:
							<input type="text" id="skilldesc3" class="form-control">
							</label>
							</div>
							<div class="col-md-6">
							<label for="skillbudget3" class="col-form-label">Hourly Budget Value($):
							
							<input type="number" id="skillbudget3" class="form-control number">
							</label>
							</div>
						</div>
						</div>
						<div class="form-group">
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="skill4" name="skill4" value="1" />
							<strong> Resource 4</strong></br>
							</label>
							<div class="row">
							<div class="col-md-6">
							<label for="skilldesc4" class="col-form-label">Description:
							<input type="text" id="skilldesc4" class="form-control">
							</label>
							</div>
							<div class="col-md-6">
							<label for="skillbudget4" class="col-form-label">Hourly Budget Value($):
							
							<input type="number" id="skillbudget4" class="form-control number">
							</label>
							</div>
						</div>
						</div>
						<div class="form-group">
							<label class="form-group-label">
							<input type="checkbox" id="skill5" name="skill5" value="1" />
							<strong> Resource 5</strong></br>
							</label>
							<div class="row">
							<div class="col-md-6">
							<label for="skilldesc5" class="ccol-form-label">Description:
							<input type="text" id="skilldesc5" class="form-control">
							</label>
							</div>
							<div class="col-md-6">
							<label for="skillbudget5" class="col-form-label">Hourly Budget Value($):
							
							<input type="number" id="skillbudget5" class="form-control number">
							</label>
							</div>
						</div>
						</div>
							
						<!--
						<div class="title"><h4>Additional Settings</h4>
						</div>
				  
						<div class="title">To add a Staffing Grid or if you would like to adjust your Reporting Times, please contact your ProductiveRN Representative.
						</div>
						-->
                    
					</div>
                </div>
				</div>
				</form>
				 		<hr></hr>		
                   <div class="title">
				   <h4>Additional Productive Resources</h4>
				  </div>
				  <div class="title"><p>These are resources typically not in staffing but their hours apply to your productivity (ie. Manager, Clinician, Educator, etc.).</p>
				  </div>
				  <div>
								<button type="button" class="btn btn-info" onclick="tj.addnewResource();">Add Resource</button>
                                                                 
					 </div>
					
					<table id="resourceTableMgr"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="16%" data-priority="1">Resources</th>
                                    <th width="10%" data-priority="2">Sun</th>
									<th width="10%" data-priority="3">Mon</th>
									<th width="10%" data-priority="4">Tues</th>
									<th width="10%" data-priority="5">Wed</th>
									<th width="10%" data-priority="6">Thur</th>
									<th width="10%" data-priority="7">Fri</th>
									<th width="10%" data-priority="8">Sat</th>
									<th width="14%" data-priority="9">Action</th>
                                    
                                </tr>
                            </thead>
                        </table>
					
					
				<hr></hr>
						 <div class="title">
				 <h4>Resources from other Units</h4>
				 </div>
				 <div class="title">
				 <p>These are resources that you have granted temporary access to this Unit (ie. Charge Nurse coming over from another unit or a Manager backing you up for vacation).</p>
				 </div>
				  <div>
				<button type="button" class="btn btn-info" onclick="tj.addnewtextResource();">Add User</button>                                        
					 </div>
				 <div>
					<table id="textTableMgr"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="30%" data-priority="1">Name</th>
                                    <th width="20%" data-priority="2">Role</th>
									<th width="40%" data-priority="3">Text Access</th> 
									<th width="10%" data-priority="4">Action</th> 
                                </tr>
                            </thead>
                        </table>
					</div>
					<hr></hr>
				<div class="pull-right">
                    <button type="button" class="btn btn-secondary" onclick="tj.cancelUnit();">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateUnitMgr();">Save </button>
                </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    
  

   

    <!--End::Main Portlet-->
	

<div class="modal fade" id="addtextResource" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add User to this Unit</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="textdeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="textuserId">Select a User to add to this Unit.</label>
                            
							<div class="col-8">
                                    <select id="textuserId" type="select" class="form-control" required >
									<option value="0">Select User</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName, u.last_name FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.accountId = {$accountId} and u.active>0 group by u.id order by u.last_name ASC, u.first_name ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="grantText"><p>Would you like to allow this User to receive Text Alerts for this Unit?</p></label>
                            
							<div class="col-8">
                                    <select id="grantText" type="select" class="form-control" required >
									<option value="0">No</option>
									<option value="1">Yes</option>
									</select>
							</div>
							
							</div>
							

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addtextResource();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<div class="modal fade" id="addCategory" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add a Service Line</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                               <label for="serviceName"><p>Service Line Name:</p></label>
                            
                                <input class="form-control" type="text" id="serviceName">
                            </div>
							
							</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="addserviceButton" class="btn btn-primary" onclick="tj.addServiceLine();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<div class="modal fade" id="updatetextStatus" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add / Remove Text Alerts</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="updaterecordId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                               <label for="updategrantText"><p>Would you like to allow this User to receive Text Alerts for this Unit?</p></label>
                            
							<div class="col-8">
                                    <select id="updategrantText" type="select" class="form-control" required >
									<option value="0">No</option>
									<option value="1">Yes</option>
									</select>
							</div>
							
							</div>
							

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="updatenoteButton" class="btn btn-primary" onclick="tj.updatetextResource();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>

<div class="modal fade" id="addResource" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add a Resource to your Productivity</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
						<input id="accountId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="resourceName">Resource Name:</label>
                                <input class="form-control" type="text" id="resourceName">
                            </div>
							<div class="form-group form-md-line-input">
							<label for="resourcevalue">Budget Value($):</label>
							
							<input type="number" id="resourcevalue" class="form-control number">
							
							</div>
							<hr></hr>
							<h5 class="modal-title">Productive Hours for Each Day</h5>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sunHours" class="col-4 col-form-label">Sunday:</label>
                                <input type="number" id="sunHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="monHours" class="col-4 col-form-label">Monday:</label>
                                <input type="number" id="monHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="tueHours" class="col-4 col-form-label">Tuesday:</label>
                                <input type="number" id="tueHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="wedHours" class="col-4 col-form-label">Wednesday:</label>
                                <input type="number" id="wedHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="thuHours" class="col-4 col-form-label">Thursday:</label>
                                <input type="number" id="thuHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="friHours" class="col-4 col-form-label">Friday:</label>
                                <input type="number" id="friHours" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="satHours" class="col-4 col-form-label">Saturday:</label>
                                <input type="number" id="satHours" min="0" class="form-control number">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="addnoteButton" class="btn btn-primary" onclick="tj.addResource();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<!--begin::Modal-->

<div class="modal fade" id="editResource" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Resource Hours</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="recordId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="editName">Resource Name:</label>
                                <input class="form-control" type="text" id="editName" >
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="resourcevalue1">Budget Value($):</label>
                                <input class="form-control number" type="number" id="resourcevalue1" >
                            </div>
							<hr></hr>
							<h5 class="modal-title">Productive Hours for Each Day</h5>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sunEdit" class="col-4 col-form-label">Sunday:</label>
                                <input type="number" id="sunEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="monEdit" class="col-4 col-form-label">Monday:</label>
                                <input type="number" id="monEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="tueEdit" class="col-4 col-form-label">Tuesday:</label>
                                <input type="number" id="tueEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="wedEdit" class="col-4 col-form-label">Wednesday:</label>
                                <input type="number" id="wedEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="thuEdit" class="col-4 col-form-label">Thursday:</label>
                                <input type="number" id="thuEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="friEdit" class="col-4 col-form-label">Friday:</label>
                                <input type="number" id="friEdit" min="0" class="form-control number">
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="satEdit" class="col-4 col-form-label">Saturday:</label>
                                <input type="number" id="satEdit" min="0" class="form-control number">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="newnoteButton" class="btn btn-primary" onclick="tj.updateResource();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>

    <!--end::Modal-->
	
</div>

