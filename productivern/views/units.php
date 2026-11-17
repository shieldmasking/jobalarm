<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

require_once '.././inc/class.db.php';
require_once '.././inc/class.fpdf.php';
require_once '.././inc/config.php';

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}

$role = intval($_SESSION['account']['role']);
$userId = intval($_SESSION['account']['id']);
$accountId = intval($_SESSION['account']['accountId']);
$company = $_SESSION['account']['company'];
$label = intval($_SESSION['account']['labelId']);
$enterpriseId = intval($_SESSION['account']['enterpriseId']);
$hrsTerm = $_SESSION['account']['hrsTerm'];
$dbCoverage = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} AND `role`<9 AND `role`>4 AND `active`>0 GROUP BY `id` order by `last_name` ASC, `first_name` ASC");
/*
$dbFlow = Config::get('db')->get_results("SELECT d.* FROM `ProductiveDept` d WHERE d.accountId = {$accountId} and d.active>0 and d.prodMeasure !=2 group by d.id order by d.dept ASC");

*/
if($role==11){
	$disabled = '';
	$hidden = '';
	$admin = 'hidden';	
}else{
	$disabled = 'disabled';
	$hidden = 'hidden';
	$admin = 'hidden';	
}






?>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<script>
$("#coverId").append($('<option>', {value:0,text: 'REMOVE COVERAGE'}));
</script>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Units
            </h3>
        </div>
		
    </div>
	
</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary" id="unitList">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >6): ?>
						<div class="col-sm-6">
							<div class="pull-right" <?php echo $hidden; ?>>
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addnewUnits" data-toggle="modal" <?php echo $hidden; ?>>
                                            Add Unit
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="UnitsTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="25%" data-priority="1">Unit</th>
									<th width="25%" data-priority="2">Service Line</th>
                                    <th width="20%" data-priority="4">Manager</th>
									<th width="20%" data-priority="5" style="display:none">Coverage</th>
									<th width="10%" data-priority="6">Grid</th>
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
 <div class="modal fade" id="copyUnit" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Copy This Unit</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="copydeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="copyaccountId">Select Account to where this Unit will be copied.</label>
                            
							<div class="col-8">
                                    <select id="copyaccountId" type="select" class="form-control" >
									<option value="0">Select Account</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT * FROM `productiveAccount` WHERE `active`>0 order by `name` ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['name'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								
                            </div>
						</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.copyDept();">Copy</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>	
	
<div class="modal fade" id="unitcoverage" tabindex="-1" role="basic" >
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h3 class="modal-title"><strong>Coverage Manager:<span id="coverageDept"></span></strong></h3>
					<button type="button" class="close" data-dismiss="modal" ></button>
                </div>
                <div class="modal-body">
				<input type="text" id="coverDept" hidden />
				<div class="row">
				<div class="form-group">
				<div class="modal-title"><strong>Use this tool to grant access to others while you are out on Vacation, PTO, On-Call, etc.</strong></div>
				</div>
				</div>
				 <div class="form-group">
				<div class="row">
				
				 
				 <div class="col-6">
				  <label for="coverId">Person Covering This Unit:
                                    <select id="coverId" type="select" class="form-control" >
									<option value="0">Select</option>
										<?php
										foreach($dbCoverage as $d){ ?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['last_name'] . ', ' . $d['first_name'];?> </option>
								<?php 
								} 
								?>
									
									</select>
				</label>
				</div>
				 <div class="col-6">
				 <label for="coverDate">Coverage End Date (Optional):
                            
                  <input class="form-control" type="date" id="coverDate" value="">
				  </label>
				 </div>
				</div>	
				</div>
                <div class="modal-footer">
				<div class="col-sm-6">			
				     <button type="button" class="btn btn-info pull-right" onclick="tj.updateCover();">Save</button>
                    
                </div>
			</div>	
            </div>
            <!-- /.modal-content -->
        
		</div>
        <!-- /.modal-dialog -->
 
 </div>

    <!--end::Modal-->
	
</div>	
    <!--begin::Modal-->
	<div class="modal fade" id="addGrid" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Add Grid</h4>
                </div>
                <div class="modal-body">
				<form id="addnewgrid" name="addnewgrid" action="inc/reports.php?req=newGrid" method="post">
					<span id="gridForm"></span>
					<input id="addgriddeptid" value="" hidden>
                       <div class="modal-title"><h4><span id="gridDept"></span></h4></div> 
                        <div class="form-body" style="center">
                            <div class="form-group">
                               
							  
							  
							   <select id="gridAdd" class="form-control">
										<option value="0">Select</option>
										<option value="1">Census Grid (Basic)</option>
										<option value="2">Census Grid (Day of Week)</option>
										<option value="3">Census Grid (Day/Time)</option>
										<option value="4">Schedule Grid (Day/Time)</option>
														
								</select>
							
							
				
                                
                            </div>
							
							</div>
                    
					</form>
					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="addgridButton" class="btn btn-primary">Download</button>
                </div>
				
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
 	<div class="modal fade" id="addnewUnits" tabindex="-1" role="basic" >
         <div class="col-sm-6">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-info">
                    <h4 class="modal-title" style="color:white">Add New Unit</h4>
					<button type="button" class="close" data-dismiss="modal" ></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_unit" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="userRole" type="hidden" value="<?php echo $role; ?>" />
						
					<div class="form-group">
					<div class="row">
                           	<div class="col-md">
                                <label for="unitName" class="col-form-label">
                                    Unit Name*:
                                </label>
                                <div>
                                    <input class="form-control m-input" type="text" value="" id="unitName" >
                                </div>
                            </div>
                            <div class="col-md">
                                <label for="unitNumber" class="col-form-label">
                                    Unit ID/Number*:
                                </label>
                                <div>
                                    <input class="form-control m-input" type="text" value="" id="unitNumber" >
                                </div>
                            </div>
							<div>
                                <label for="unitCategory" class="col-form-label">
                                    Unit Type*:
                                </label>
                                <div>
								<select id="unitCategory" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="3">Ambulatory / Outpatient</option>
										<option value="2">Ancillary / Support</option>
										<option value="1">In-Patient Nursing</option>
										<option value="4">Global Nursing (ie. Float Pool)</option>
										</select>
                                    
                                </div>
							</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							
							<div class="col-md">
                                <label for="opDays" class="col-form-label">
                                    Days of Operation*:
                                </label>
                                <div>
								<select id="opDays" type="select" class="form-control">
										<option value=0>Select..</option>
										<option value=1>Mon-Sun(7 days)</option>
										<option value=2>Mon-Sat(6 days)</option>
										<option value=3>Mon-Fri(5 days)</option>
										</select>
                                    
                                </div>
                            </div>
							<div class="col-md">
							
							    <label for="unitBeds" class="col-form-label">Bed Count:</label>
								<div>
                                <input type="number" class="form-control number" id="unitBeds" placeholder="Reqd for Nursing Units">
							</div>
							</div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							<div class="col-md">
                               	<label for="newDirector" class="col-form-label">Unit Director:</label>
                                <div>
                                    <select id="newDirector" type="select" class="form-control">
									<option value="0">Select Director</option>
															
								<?php
								
								$dbDir = Config::get('db')->get_results("SELECT u.*, CONCAT(u.last_name, ', ', u.first_name, ' (', a.name,' - ',r.role, ')') as newName from `productiveUser` u LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT JOIN `productiveUserRoles` as r on r.id=u.role where u.accountId={$accountId} AND u.role>=6 and u.role<9 and u.practiceId=0 AND u.active>0 AND u.email LIKE '%@%' AND u.last_name !='' group by u.id order by u.last_name ASC, u.first_name ASC");	
														
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['newName'];?> </option>
								<?php 
								} 
								?>
							</select>
                            </div>
							</div>
							<div class="col-md">
                               	<label for="newManager" class="col-form-label">Unit Manager:</label>
                                <div>
                                    <select id="newManager" type="select" class="form-control">
									<option value="0">Select Manager</option>
															
								<?php
								
								$dbMgr = Config::get('db')->get_results("SELECT u.*, CONCAT(u.last_name, ', ', u.first_name, ' (', a.name,' - ',r.role, ')') as newName from `productiveUser` u LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT JOIN `productiveUserRoles` as r on r.id=u.role where u.accountId={$accountId} AND u.role>=5 AND u.role<9 AND u.practiceId=0 AND u.active>0 AND u.email LIKE '%@%' AND u.last_name !='' group by u.id order by u.last_name ASC, u.first_name ASC");	
																
								foreach($dbMgr as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['newName'];?> </option>
								<?php 
								} 
								?>
							</select>
                            </div>
							</div>
							
						
                            
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
  
</div>	

<div class="m-content" id="unitDetails">
    <!--Begin::Main Portlet-->

        
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body bg-secondary">
				
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
				 <div class="pull-right">
		<span><a href="javascript:;" onclick="tj.closeUnit();" id="editTimes" ><u> Back</u></a></span>
		</div>
				 <span id="deptIdunit"></span>
                    <form id="edit_unit_formMgr" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="unitIdMgr" type="hidden" value="" />
						<input id="mgrOrig" type="hidden" value="" />
						<input id="dirOrig" type="hidden" value="" />
						<input id="shiftsOrig" type="hidden" value="" />
						<input id="linkOrig" type="hidden" value="" />
						<input id="WHPvalue" type="hidden" value="" />
						<input id="WHP2value" type="hidden" value="" />
						<input id="WHP3value" type="hidden" value="" />
						<input id="WHP4value" type="hidden" value="" />
						<input id="WHP5value" type="hidden" value="" />
	<!--<div class="col-md-12">-->
						
				 <div class="form-group">
				 <div class="row">
				 <div class="col-md">
                                <label for="activeUnit" class="col-form-label">
                                    Status:
                                
                                
								<select id="activeUnit" type="select" class="form-control">
										<option value="1">Active</option>
										<option value="2">Customer Setup</option>
										<option value="3">Admin Setup</option>
										<option value="0">Inactive</option>
										</select>
                                    
                                
								</label>
								<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="showArchive" name="showArchive" value="1" />
							<strong> Show Archive</strong>
							</label>
								</div>
				 <div class="col-md" hidden>
                                <label for="typeUnit" class="col-form-label">
                                    Unit Type:
                                
                                
								<select id="typeUnit" type="select" class="form-control">
										<option value="1">In-Patient Nursing</option>
										<option value="2">Ancillary / Support</option>
										<option value="3">Ambulatory / Outpatient</option>
										<option value="4">Global Nursing (ie. Float Pool)</option>
										</select>
								</label>
								</div>
                            
				</div>
                            </div>
					<div class="form-group">
				 <div class="row">
				 <div class="col-md">
                           	
                                <label for="editunitNameMgr" class="col-form-label">
                                    Unit Name:
                                
                                <div>
                                    <input class="form-control m-input" type="text" value="" id="editunitNameMgr" >
                                </div>
								</label>
                           
							</div>
						
			
				 
							
							<div class="col-md">
                            
                                <label for="editunitNumberMgr" class="col-form-label">
                                    Unit ID/Number:
                                
                                <div>
                                    <input class="col-9 form-control m-input" type="text" value="" id="editunitNumberMgr" >
                                </div>
								</label>
                            </div>
				 		<div class="col-md">
							<label for="serviceLine" class="col-form-label" style="padding-right: 20px;">Service Line:
							<button type="button" id="catButton" class="btn btn-info btn-sm" data-target="#addCategory" data-toggle="modal" >
                                            Add
                                        </button> 
							
							
                                <div>
                                    <select id="serviceLine" type="select" class="form-control" >
									<option value="0">Select</option>
								<?php
								if($role>12){
								$dbCat = Config::get('db')->get_results("SELECT c.*, a.name, CONCAT(a.name, ' - ', c.categoryName) as catName from `productiveCategory` c LEFT JOIN `productiveAccount` as a on a.id=c.accountId where a.enterpriseId={$enterpriseId} OR c.accountId=0 order by a.accountId DESC, a.name ASC, c.categoryName ASC");
								}else{
								$dbCat = Config::get('db')->get_results("SELECT c.*, a.name, CONCAT(a.name, ' - ', c.categoryName) as catName from `productiveCategory` c LEFT JOIN `productiveAccount` as a on a.id=c.accountId where c.accountId={$accountId} OR c.accountId=0 order by a.name ASC, c.categoryName ASC");	
								}
								foreach($dbCat as $c) 
								{ 
								?>
								<option value="<?php echo $c['id'];?>"><?php echo $c['catName'];?> </option>
								<?php 
								} 
								?>
										</select>
										
								 </div>
							</label>
							</div>
					<div class="col-md">
                                <label for="editunitCategory" class="col-form-label">
                                    Unit Type:
                                </label>
                                <div>
								<select id="editunitCategory" type="select" onchange="tj.globalUnit();" class="form-control" >
										<option value="0">Select</option>
										<option value="3">Ambulatory / Outpatient</option>
										<option value="2">Ancillary / Support</option>
										<option value="1">In-Patient Nursing</option>
										<option value="4">Global Nursing (ie. Float Pool)</option>
										</select>
                                    
                                </div>
					</div>
					
						
							<div class="col-md" hidden>
							    <label for="avgWage" class="col-form-label">Avg Wage:</label>
								<div class="col-9">
                                <input type="number" class="form-control number" id="avgWage" style="text-align: right" />
							</div>
                            </div>
							</div>
							</div>
							
							<div class="form-group">
							<div class="row">
				 
							
						
							<div class="col-md">
                               	<label for="unitDirector" class="col-form-label">Director:    <span id="currentDirector"></span>
                                <div class="col-12">
                                    <select id="unitDirector" type="select" class="form-control" >
									<option value="0">Select New Director</option>
									<option value="-1">No Director (Remove this Manager)</option>
															
								<?php
								
								$dbDir = Config::get('db')->get_results("SELECT u.*, CONCAT(u.last_name, ', ', u.first_name, ' (', a.name,' - ',r.role, ')') as newName from `productiveUser` u LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT JOIN `productiveUserRoles` as r on r.id=u.role where u.accountId={$accountId} AND u.role>=6 and u.role<9 AND u.email LIKE '%@%' AND u.practiceId=0 and u.active>0 and u.last_name !='' group by u.id order by u.last_name ASC, u.first_name ASC");	
								
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['newName'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</label>
							</div>
							<div class="col-md">
                               	<label for="unitManagerMgr" class="col-form-label">Manager:    <span id="currentManagerMgr"></span>
                                <div class="col-12">
                                    <select id="unitManagerMgr" type="select" class="form-control" >
									<option value="0">Select New Manager</option>
									<option value="-1">No Manager (Remove this Manager)</option>
															
								<?php
								
								$dbData = Config::get('db')->get_results("SELECT u.*, CONCAT(u.last_name, ', ', u.first_name, ' (', a.name,' - ',r.role, ')') as newName from `productiveUser` u LEFT JOIN `productiveAccount` as a on a.id = u.accountId LEFT JOIN `productiveUserRoles` as r on r.id=u.role where u.accountId={$accountId} AND u.role>=5 and u.active>0 and u.role<8 AND u.practiceId=0 and u.last_name !='' AND u.email LIKE '%@%' order by u.last_name ASC, u.first_name ASC");	
								
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['newName'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</label>
							</div>
							<div class="col-md">
							    <label for="daysWk" class="col-form-label">Work Days:</label>
								<div class="col-9">
                                <select id="daysWk" type="select" class="form-control" >
										<option value="7">Mon-Sun</option>
										<option value="5">Mon-Fri</option>
										</select>
							</div>
                            </div>
							<div class="col-md">
                            
                                <label for="daydataTitle" class="col-form-label">
                                    Global Unit Dashboard Title:
                                
                                <div>
                                    <input class="col-9 form-control m-input" type="text" value="" id="daydataTitle" >
                                </div>
								</label>
                            </div>
							
							
							</div>							
							</div>
							<div class="form-group" id="nursing3">
							<div class="row">
							 <div class="col-md">
							    <label for="editunitBedsMgr" class="col-form-label">Bed Count:</label>
								<div class="col-9">
                                <input type="number" class="form-control number" id="editunitBedsMgr" style="text-align: right" />
							</div>
                            </div>
						
							<div class="col-md" hidden>
                                <label for="addCrash" class="col-form-label">
                                    Crash Carts:
                                </label>
                                <div class="col-12">
								<select id="addCrash" type="select" class="form-control" >
										<option value="0">Yes</option>
										<option value="1">No</option>
										</select>
                                    
                                </div>
                            </div>
							<div class="col-md" >
                                <label for="useHuddle" class="col-form-label">
                                    Use Huddle/Safety Forms:
                                </label>
                                <div class="col-12">
								<select id="useHuddle" type="select" class="form-control" >
										<option value="1">Yes</option>
										<option value="0">No</option>
										</select>
                                    
                                </div>
                            </div>
								
								
							<div class="col-md">
							    <label for="grid" class="col-form-label">Use Grid:</label>
								<div class="col-12">
                                <select id="grid" type="select" class="form-control">
								<option value="0">No</option>
								<option id="gr1" value="1">Grid (Census)</option>
								<option id="gr4" value="4">Grid (Census w/Epic)</option>
								<option id="gr2" value="2">Grid (Day/Time)</option>
								<option id="gr3" value="3">Grid (Day/Time/Hrs)</option>
								<option id="gr6" value="6">Grid (Day/Time/Census)</option>
								<option id="gr7" value="7">Grid (Multi Census)</option>
								<option value="5">Grid Not Avail.</option>
								</select>
							</div>
							</div>
								<div class="col-md">
                           	
                                <label for="commentTitle1" class="col-form-label">
                                    Comment Title:
                                </label>
                                <div class="col-md">
                                    <input class="form-control m-input" type="text" value="" id="commentTitle1" />
                                </div>
                           
							</div>
							<div class="col-md" id="useEscalations">
                                <label for="addEscalations" class="col-form-label">
                                    Use Escalations:
                                
                                <div class="col-12">
								<select id="addEscalations" type="select" class="form-control" >
										<option value="0">Yes</option>
										<option value="1">No</option>
										</select>
                                    
                                </div>
								</label>
                            </div>
								<div class="col-md" hidden>
							    <label for="churn" class="col-form-label">Show Churn:</label>
								<div class="col-9">
                                <select type="select" class="form-control" id="churn" >
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
							</div>
							</div>
							
							
							</div>
							</div>
					
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Staffing Report Configuration</h4>
					
					</div>
				<!--	
				<div class="title" style="padding-top: 20px;">
				 <h4>Productivity</h4>
				 </div>
				 -->
				 <div class="form-group">
							<div class="row">
							<div class="col-md" hidden>
                                <label for="editunitprodMeasureMgr" class="col-form-label">
                                    Productivity Measure:
                                </label>
                                <div class="col-9">
								<select id="editunitprodMeasureMgr" type="select" class="form-control" name="editunitprodMeasureMgr" onchange="tj.visitShow();" disabled>
										<option value="1">HPPD</option>
										<option value="2">WHPUOS</option>
										<option value="3">BUPHW</option>
										<option value="4">HPPV</option>
										<option value="5">Custom</option>
										<option value="6">N/A</option>
										</select>
                                    
                                </div>
							
                            </div>
							
							<div class="col-md" id="uosDescription" hidden>
							 <label for="editunituosDescMgr" class="col-form-label">UOS Description:</label>
							<div class="col-9">
                             <input type="text" class="form-control number" id="editunituosDescMgr" />
							</div>
							</div>
							
							<div class="col-md" hidden>
							    <label for="editunitprodValueMgr" class="col-form-label">UOS Value:</label>
								<div class="col-9">
                                <input type="number" class="form-control number" id="editunitprodValueMgr" style="text-align:right" />
							</div>
                            </div>
							<div class="col-md" id="hrsthresh" hidden>
							<label for="hrsThreshold" class="col-form-label">Hrs Variance Threshold (=/-)
								<div class="col-9">
                                <input type="number" min="0" class="form-control number" id="hrsThreshold" style="text-align: right" />
							</div>
							</label>
                            </div>
							<div class="col-md" id="censusTime" hidden>
                               	<label for="editcensusShiftMgr" class="col-form-label">Census Time (HPPD):</label>
                                <div class="col-9">
                                    <select id="editcensusShiftMgr" type="select" class="form-control" disabled>
										<option value=52>12:00 Midnight</option>
										<option value=51>12:30 AM</option>
										<option value=50>1:00 AM</option>
										<option value=49>1:30 AM</option>
										<option value=48>2:00 AM</option>
										<option value=47>2:30 AM</option>
										<option value=46>3:00 AM</option>
										<option value=45>3:30 AM</option>
										<option value=44>4:00 AM</option>
										<option value=43>4:30 AM</option>
										<option value=42>5:00 AM</option>
										<option value=41>5:30 AM</option>
										<option value=40>6:00 AM</option>
										<option value=39>6:30 AM</option>
										<option value=38>7:00 AM</option>
										<option value=37>7:30 AM</option>
										<option value=0>No Census</option>
										</select>
                            </div>
							</div>
							<div class="col-md" id="houseAlert" hidden>
                                <label for="halert" class="col-form-label">
                                    House Alert (Text):
                                </label>
                                <div class="col-9">
								<select id="halert" type="select" class="form-control" >
								<option value="0">No</option>	
								<option value="1">Yes</option>
											
								</select>
                                    
                                </div>
							</div>
							<div class="col-md" id="showVisit" hidden>
							    <label for="showVisits" class="col-form-label">Show UOS Field:</label>
								<div class="col-9">
                                <select type="select" class="form-control" id="showVisits" >
								<option value="0">No</option>
								<option value="1">First Report Only</option>
								<option value="2">Last Report Only</option>
								<option value="3">First & Last Reports</option>
								</select>
							</div>
							</div>
							
								
							
							
							
							</div>
							</div>
						<div class="form-group">
							<div class="row">
							<div class="col-md" hidden>
							<label for="thresholdHigh" class="col-form-label" >High Variance % (ie. 105.0):</label>
								<div class="col-9">
                                <input type="number" class="form-control number" id="thresholdHigh" style="text-align: right" />
							</div>
							</div>
							<div class="col-md" hidden>
							<label for="thresholdLow" class="col-form-label">Low Variance %: (ie. 95.0)</label>
								<div class="col-9">
                                <input type="number" class="form-control number" id="thresholdLow" style="text-align: right" />
							</div>
                            </div>
							<div class="col-md">
                                <label for="chargeDefault" class="col-form-label">
                                    Charge Nurse Default:
                                </label>
                                <div class="col-9">
								<select id="chargeDefault" type="select" class="form-control" >
										<option value="0.000">0</option>
										<option value="0.500">.5</option>
										<option value="1.000">1</option>
										<option value="2.000">2</option>
										</select>
                                    
                                </div>
                            </div>
							<div class="col-md" id="rnthresh">
                                <label for="rnThreshold" class="col-form-label">
                                    RN Variance Threshold (+/-):
                                
                                <div class="col-9">
								<select id="rnThreshold" type="select" class="form-control" >
										<option value="0.00">0</option>
										<option value="0.50">0.5</option>
										<option value="1.00">1</option>
										<option value="1.50">1.5</option>
										<option value="2.00">2</option>
										<option value="2.50">2.5</option>
										<option value="3.00">3</option>
										<option value="3.50">3.5</option>
										<option value="4.00">4</option>
										<option value="4.50">4.5</option>
										<option value="5.00">5</option>
										<option value="999.00">None</option>
										</select>
                                    
                                </div>
								</label>
							</div>
								<div class="col-md" id="gthresh">
                                <label for="gThreshold" class="col-form-label">
                                    GRID Variance Threshold (+/-):
                               
                                <div class="col-9">
								<select id="gThreshold" type="select" class="form-control" >
										<option value="0.00">0</option>
										<option value="0.50">0.5</option>
										<option value="1.00">1</option>
										<option value="1.50">1.5</option>
										<option value="2.00">2</option>
										<option value="2.50">2.5</option>
										<option value="3.00">3</option>
										<option value="3.50">3.5</option>
										<option value="4.00">4</option>
										<option value="4.50">4.5</option>
										<option value="5.00">5</option>
										<option value="999.00">None</option>
										</select>
                                    
                                </div>
								 </label>
							</div>
													
							<div class="col-md" id="minStaff">
                                <label for="minRN" class="col-form-label">
                                    Minimum Staffing Threshold (RNs only):
                                </label>
                                <div class="col-9">
								<select id="minRN" type="select" class="form-control" >
								<option value="0">None</option>	
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
											
								</select>
                                    
                                </div>
							</div>
									<div class="col-md" id="roundrnhide">
                                <label for="roundrn" class="col-form-label">
                                    Round RN Variance:
                                </label>
                                <div class="col-9">
								<select id="roundrn" type="select" class="form-control" >
								<option value="0">No</option>	
										<option value="1">Round</option>
										<option value="2">Round ALL Down</option>
										<option value="3">Round ALL Up</option>
											
								</select>
                                    
                                </div>
							</div>
													
							</div>
							
							</div>
							
							<div class="form-group">
							<div class="row">
							<div class="col-md">
                                <label for="rnDisplay" class="col-form-label">
                                    Primary Variance / Dashboard Block 1:
                                </label>
                                <div class="col-9">
								<select id="rnDisplay" type="select" class="form-control" >
										<option value="0" id="grid0">RN Variance (Acuity)</option>
										<option value="1" id="grid1">HRS Variance</option>
										<option value="2" id="grid2">GRID Total Variance</option>
										<option value="3" id="grid3">GRID RN Variance</option>
										<option value="4" id="grid4">GRID HRS Variance</option>
										<option value="5" id="grid5">NEDOCS</option>
								</select>
                                    
                                </div>
							</div>
							<div class="col-md">
                                <label for="showProd" class="col-form-label">
                                    Dashboard Block 2:
                                </label>
                                <div class="col-9">
								<select id="showProd" type="select" class="form-control" >
										<option value="3" id="showprod3"><?php echo $hrsTerm; ?></option>
										<option value="4" id="showprod4">HRS Variance</option>
										<option value="5" id="showprod5">Grid RN Variance</option>
										<option value="6" id="showprod6">Grid Total Variance</option>
										<option value="8" id="showprod8">Grid Hrs Variance</option>
										<option value="7" id="showprod7">RN Count</option>
										<option value="0">None</option>
										</select>
                                    
                                </div>
							</div>
							
							<div class="col-md">
                                <label for="showrntest" class="col-form-label">
                                    In Report Variance:
                                </label>
                                <div class="col-9">
								<select id="showrntest" class="form-control" multiple >
										<option value="0">Do Not Show</option>
										<option value="1" id="gridvar1">RN Variance (Acuity)</option>
										<option value="3" id="gridvar3">Grid Total Variance</option>
										<option value="4" id="gridvar4">HRS Variance</option>									
								</select>
                                    
                                </div>
							</div>
							<div class="col-md">
                                <div class="title">
                                    Shift Report Times:
                               </div>
								<span><a href="javascript:;" onclick="tj.editTimes();" id="editTimes" ><u> edit</u></a></span>
								
                                <div class="col-9">
								<span id="currentTimes1"></span>
								</div>
								
							</div>
							<div class="col-md" id="dash5">
                                <label for="dashboard5" class="col-form-label">
                                    Dashboard Block 5:
                                
                                <div class="col-12">
								<select id="dashboard5" type="select" class="form-control" >
										<option value="0">Beds</option>
										<option value="1">Discharges</option>
										</select>
                                    
                                </div>
								</label>
                            </div>
							<div class="col-md" hidden>
							 <label for="getLinked" class="col-form-label">
                                    Link Units (Select ALL units that you want linked together.):
                                </label>
							<select id="getLinked" type="select" class="form-control" multiple >
								
									
									
								</select>
							
						
							
							</div>
						
							</div>
						
							
							</div>
							
							
							
							
							
				 							
						<div class="form-group" hidden>
							    <label for="editunitTargetMgr" class="col-form-label">Productivity Target %:</label>
								<div class="col-8">
                                <input type="number" class="form-control number" id="editunitTargetMgr" />
							</div>
                            </div>
				<div id="PTcount">
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Patient Total Options</h4>
					
					</div>
				 <div class="title">
				 <p>Check the boxes if you want the Additional Patient Details to automatically add up to the Patient Total.</p>
				 </div>
				 
							
					<div class="row">
                       <div class="col-md-6">
						<div class="form-group">
						<div>
						
							<div class="title">
							<strong> Patient Total</strong>
							
							</div>
							<label for="ptuosdesc" class="col-9 col-form-label">Description:
							
							<input type="text" id="ptuosdesc" class="form-control" />
							</label>
						</div>	
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="ptuos3" name="ptuos3" />
							<strong> Patient Detail 3</strong></br>
							</label>
							</div>
							<label for="ptuosdesc3" class="col-9 col-form-label">Description:
							<input type="text" id="ptuosdesc3" class="form-control" />
							</label>
							</div>
						
						<div>
						
						</div>			
						
						
						</div>
						</div>
						<div class="col-md-6 vl">
						<div class="form-group">
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="ptuos2" name="ptuos2" />
							<strong> Patient Detail 2</strong>
							</label>
							</div>
							<label for="ptuosdesc2" class="col-9 col-form-label">Description: 
							<input type="text" id="ptuosdesc2" class="form-control" />
							</label>
				
						</div>
						
						<div>
						<div>
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="ptuos4" name="ptuos4" />
							<strong> Patient Detail 4</strong></br>
							</label>
							</div>
							<label for="ptuosdesc4" class="col-9 col-form-label">Description:
							<input type="text" id="ptuosdesc4" class="form-control" />
							</label>
			
						</div>
						
						<div>
			
						</div>
						
					
						</div>
						</div>
						</div>
						</div>	
				
				<div class="form-group" id="nursing4">
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Acuity / Workload Level Descriptions</h4>
					
					</div>
				 <div class="title">
				 <p>Descriptions can be as simple as High, Med, Low or more specific for each Level.  Check the box for each Profile that you want to appear in the Shift Report.</p>
				 </div>
				
                       <div class="row">
					   <div class="col-md-6">
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2oneCheckedMgr" name="one2oneCheckedMgr" value="1" />
							<strong> Profile 1</strong>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2oneValue" name="one2oneValue" class="col-9 form-control" />
							</label>
							<label for="descConfig1Mgr" class="col-9 form-label">Description:
							<textarea rows="2" id="descConfig1Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2twoCheckedMgr" name="one2twoCheckedMgr" value="1" />
							<strong> Profile 2</strong>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2twoValue" name="one2twoValue" class="col-9 form-control" />
							</label>
							<label for="descConfig2Mgr" class="col-9 form-label">Description: 
							<textarea rows="2" id="descConfig2Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2threeCheckedMgr" name="one2threeCheckedMgr" value="1" />
							<strong> Profile 3</strong></br>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2threeValue" name="one2threeValue" class="col-9 form-control" />
							</label>
							<label for="descConfig3Mgr" class="col-9 col-form-label">Description:
							<textarea rows="2" id="descConfig3Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2fourCheckedMgr" name="one2fourCheckedMgr" value="1" />
							<strong> Profile 4</strong></br>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2fourValue" name="one2fourValue" class="col-9 form-control" />
							</label>
							<label for="descConfig4Mgr" class="col-9 col-form-label">Description:
							<textarea rows="2" id="descConfig4Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2fiveCheckedMgr" name="one2fiveCheckedMgr" value="1" />
							<strong> Profile 5</strong></br>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2fiveValue" name="one2fiveValue" class="col-9 form-control" />
							</label>
							<label for="descConfig5Mgr" class="col-9 col-form-label">Description:
							<textarea rows="2" id="descConfig5Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="one2sixCheckedMgr" name="one2sixCheckedMgr" value="1" />
							<strong> Profile 6</strong></br>
							</label>
							<div>
							<label class="col-3 form-label">
							<strong> Acuity Val 1:_</strong>
							<input type="number" min="0" id="one2sixValue" name="one2sixValue" class="col-9 form-control" />
							</label>
							<label for="descConfig6Mgr" class="col-9 col-form-label">Description:  
							<textarea rows="2" id="descConfig6Mgr" class="form-control" >
							</textarea>
							</label>
							
							</div>
						</div>
						</div>
					</div>
				
				 <div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Additional Patient Info Tracking</h4>
					
					</div>
				 <div class="title">
				 <p>These items are not included in the total patient count.</p>
				 </div>
					<div class="form-group">
					<div class="row">
					<div class="col-md" id="useEAS">
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="showEpic" name="showEpic" value="" />
							<strong> Use Epic Acuity Score</strong>
							</label>
                      </div>
					<div class="col-md" hidden>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="dc1" name="dc1" value="" />
							<strong> Track Discharges</strong>
							</label>
                      </div>
					  </div>
					  </div>
						<div class="form-group">
						<div class="row">
						<div class="col-md">
						<label for="ptdesc1" class="col-9 col-form-label">Patient Item 1:
							<div>
							<input type="text" id="ptdesc1" class="form-control" disabled />
							
							</label>
							</div>
							</div>
						<div class="col-md">
												
							
							<label for="ptdesc2" class="col-9 col-form-label">Patient Item 2:
							
							<input type="text" id="ptdesc2" class="form-control" />
							</label>
							</div>
						</div>
						</div>
						<div class="form-group">
						<div class="row">
						
						<div class="col-md">
				
							
							<label for="ptdesc3" class="col-9 col-form-label">Patient Item 3:
							
							<input type="text" id="ptdesc3" class="form-control" />
					
							</label>
							</div>
						<div class="col-md">
												
							
							<label for="ptdesc4" class="col-9 col-form-label">Patient Item 4:
							
							<input type="text" id="ptdesc4" class="form-control" />
					
							</label>
							</div>
						</div>
						</div>
				
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Staffing Descriptions</h4>
					
					</div>
				 <div class="title">
				 <p>Check box if position is included in RN Variance calculations.</p>
				 </div>
				
                       <div class="row">
					   <div class="col-md-6">
						<div class="form-group">
							<div>
							<label for="chargedesc" class="col-9 col-form-label">Charge Nurse:
							<input type="checkbox" id="chargeRN" name="chargeRN" />
							<input type="text" id="chargedesc" class="form-control" />
							</label>
							</div>
							
							<div>
							<label for="nurse1desc" class="col-9 col-form-label">Nurse 1 Desc:
							<input type="checkbox" id="nurse1RN" name="nurse1RN" />
							<input type="text" id="nurse1desc" class="form-control" />
							</label>
							</div>
							
							<div>
							<label for="techdesc" class="col-9 col-form-label">Tech Desc:
							<input type="text" id="techdesc" class="form-control" />
							</label>
							</div>
							
							<div>
							<label for="other1desc" class="col-9 col-form-label">Custom 1 Desc:  
							<input type="checkbox" id="other1calc" name="other1calc" value="" />
							<strong> Shared Position </strong></br>
							<input type="text" id="other1desc" class="form-control" />
							</label>
							</div>
							
							<div>
							
							<label for="other2desc" class="col-9 col-form-label">Orientation:  
							<input type="checkbox" id="gridOpt1" name="gridOpt1" value="" />
							<strong> Not in Grid Count </strong></br>
							<input type="text" id="other2desc" class="form-control" disabled />
							
							</label>
							</div>
						
						
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
						
							<div>
							<label for="nursedesc" class="col-9 col-form-label">Primary Nurse Desc: 
							<input type="checkbox" id="nurseRN" name="nurseRN" />
							<input type="text" id="nursedesc" class="form-control" />
							</label>
							</div>				
							
							<div>
							<label for="nurse2desc" class="col-9 col-form-label">Nurse 2 Desc:
							<input type="checkbox" id="nurse2RN" name="nurse2RN" />
							<input type="text" id="nurse2desc" class="form-control" />
							</label>
							</div>
														
							<div>
							<label for="secdesc" class="col-9 col-form-label">Secretary Desc:  
							<input type="text" id="secdesc" class="form-control" />
							</label>
							</div>
						
							<div>
							<label for="sitterdesc" class="col-9 col-form-label">Sitter/Custom 2 Desc: 
							<input type="checkbox" id="gridOpt3" name="gridOpt3" value="" />
							<strong> Not in Grid Count </strong></br>							
							<input type="text" id="sitterdesc" class="form-control" />
							</label>
							</div>
							
							<div>
							
							<label for="other3desc" class="col-9 col-form-label">Other Desc:
							<input type="checkbox" id="gridOpt2" name="gridOpt2" value=""/>
							<strong>Not in Grid Count </strong></br>
							<input type="text" id="other3desc" class="form-control" />
							</label>
							</div>
						
						</div>
						</div>
				</div>
					</div>
				
				<div id="UOSskill">
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Additional UOS Options</h4>
					
					</div>
				 <div class="title">
				 <p>Check the boxes if you want the additional UOS options to add up to the Total UOS value.   If you choose this option, you will want to name the UOS Description above something like 'Total Visits'.</p>
				 </div>
				 
							
					<div class="row">
                       <div class="col-md-6">
						<div class="form-group">
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="uos1" name="uos1" value="1" />
							<strong> Item 1</strong>
							</label>
							</div>
							<label for="uosdesc1" class="col-9 col-form-label">Description:
							
							<input type="text" id="uosdesc1" class="form-control" >
							</label>
						</div>	
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="uos3" name="uos3" value="1" />
							<strong> Item 3</strong></br>
							</label>
							</div>
							<label for="uosdesc3" class="col-9 col-form-label">Description:
							<input type="text" id="uosdesc3" class="form-control" >
							</label>
							</div>
						
						<div>
						
						</div>			
						
						
						</div>
						</div>
						<div class="col-md-6 vl">
						<div class="form-group">
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="uos2" name="uos2" value="1" />
							<strong> Item 2</strong>
							</label>
							</div>
							<label for="uosdesc2" class="col-9 col-form-label">Description: 
							<input type="text" id="uosdesc2" class="form-control" >
							</label>
				
						</div>
						
						<div>
						<div>
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="uos4" name="uos4" value="1" />
							<strong> Item 4</strong></br>
							</label>
							</div>
							<label for="uosdesc4" class="col-9 col-form-label">Description:
							<input type="text" id="uosdesc4" class="form-control" >
							</label>
			
						</div>
						
						<div>
			
						</div>
						
					
						</div>
						</div>
						</div>
						</div>
					<hr></hr>
					<div id="skill">
				 <div class="col-3 row" id="defaultUOS">
				 <div class="form-group">
							    <label for="uosDefault" class="col-form-label">UOS Default Value:  
								
                                <input type="number" class="col-6 form-control" id="uosDefault" style="text-align: right" >
							</label>
                            </div>
							</div>
						
				
				 <div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Resource Hours</h4>
					
					</div>
				 <div class="title">
				 <p>Optional:  Define up to 6 Resources for this Unit.  Default is one field labeled "Hours".  Check the box for each Resource that you want to appear in the Shift Report.</p>
				 </div>
					<div class="row">
                       <div class="col-md-6">
						<div class="form-group">
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill1" name="skill1" value="1" />
							<strong> Resource 1</strong>
							</label>
							</div>
							<label for="skilldesc1" class="col-form-label">Description:
							
							<input type="text" id="skilldesc1" class="form-control" >
							</label>
						</div>	
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill3" name="skill3" value="1" />
							<strong> Resource 3</strong></br>
							</label>
							</div>
							<label for="skilldesc3" class="col-form-label">Description:
							<input type="text" id="skilldesc3" class="form-control" >
							</label>
							</div>
						
						<div>
						<div>
							<label class="form-group-label">
							<input type="checkbox" id="skill5" name="skill5" value="1" />
							<strong> Resource 5</strong></br>
							</label>
							</div>
							<label for="skilldesc5" class="ccol-form-label">Description:
							<input type="text" id="skilldesc5" class="form-control" >
							</label>
						</div>		
						<div>
						<div>
							<label class="form-group-label">
							<input type="checkbox" id="skill7" name="skill7" value="1" />
							<strong> Resource 7</strong></br>
							</label>
							
							</div>
							<label for="skilldesc7" class="ccol-form-label">Description:
							<input type="text" id="skilldesc7" class="form-control" />
							</label>
						</div>	
						<div>
						<div>
							<label class="form-group-label">
							<input type="checkbox" id="skill9" name="skill9" value="1" />
							<strong> Resource 9</strong></br>
							</label>
							</div>
							<label for="skilldesc9" class="ccol-form-label">Description:
							<input type="text" id="skilldesc9" class="form-control" >
							</label>
						</div>							
						
						
						</div>
						</div>
						<div class="col-md-6 vl">
						<div class="form-group">
						
						<div>
						<div>
							<label class="form-group-label" class="col-form-label">
							<input type="checkbox" id="skill2" name="skill2" value="1" />
							<strong> Resource 2</strong>
							</label>
							</div>
							<label for="skilldesc2" class="col-form-label">Description: 
							<input type="text" id="skilldesc2" class="form-control" >
							</label>
				
						</div>
						
						<div>
						<div>
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="skill4" name="skill4" value="1" />
							<strong> Resource 4</strong></br>
							</label>
							</div>
							<label for="skilldesc4" class="col-form-label">Description:
							<input type="text" id="skilldesc4" class="form-control" >
							</label>
			
						</div>
						
						<div>
						<div>
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="skill6" name="skill6" value="1" />
							<strong> Resource 6</strong></br>
							</label>
							</div>
							<label for="skilldesc6" class="col-form-label">Description:
							<input type="text" id="skilldesc6" class="form-control" >
							</label>
			
						</div>
						<div>
						<div>
							<label class="form-group-label"class="col-form-label">
							<input type="checkbox" id="skill8" name="skill8" value="1" />
							<strong> Resource 8</strong></br>
							</label>
							</div>
							<label for="skilldesc8" class="col-form-label">Description:
							<input type="text" id="skilldesc8" class="form-control" >
							</label>
			
						</div>
						<div>
						<div>
							<label class="form-group-label">
							<input type="checkbox" id="skill10" name="skill10" value="1" />
							<strong> Resource 10</strong></br>
							</label>
							</div>
							<label for="skilldesc10" class="ccol-form-label">Description:
							<input type="text" id="skilldesc10" class="form-control" >
							</label>
						</div>	
						
					
						</div>
						</div>
						</div>
						
                    
					</div>
					
						<div id="track">
				 <div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Additional Tracking</h4>
					
					</div>
				 <div class="title">
				 <p>Tracked items will be summarized in Custom Reports.</p>
				 </div>
					<div class="form-group">
						<div class="row">
						<div class="col-md">
				
							
							<label for="trackdesc1" class="col-9 col-form-label">Track Item 1:
							<div>
							<input type="text" id="trackdesc1" class="form-control" >
							
                                    <select id="typetrack1" type="select" class="col-9 form-control" hidden>
									<option value="0">Daily</option>
									<option value="1">In Shift</option>
									</select>
							
							</label>
							</div>
							</div>
						<div class="col-md">
												
							
							<label for="trackdesc2" class="col-9 col-form-label">Track Item 2:
							
							<input type="text" id="trackdesc2" class="form-control" >
							<select id="typetrack2" type="select" class="col-9 form-control" hidden>
									<option value="0">Daily</option>
									<option value="1">In Shift</option>
									</select>
							</label>
							</div>
						</div>
						</div>
						<div class="form-group">
						<div class="row">
						<div class="col-md">
				
							
							<label for="trackdesc3" class="col-9 col-form-label">Track Item 3:
							
							<input type="text" id="trackdesc3" class="form-control" >
							<select id="typetrack3" type="select" class="col-9 form-control" hidden>
									<option value="0">Daily</option>
									<option value="1">In Shift</option>
									</select>
							</label>
							</div>
						<div class="col-md">
												
							
							<label for="trackdesc4" class="col-9 col-form-label">Track Item 4:
							
							<input type="text" id="trackdesc4" class="form-control" >
							<select id="typetrack4" type="select" class="col-9 form-control" hidden>
									<option value="0">Daily</option>
									<option value="1">In Shift</option>
									</select>
							</label>
							</div>
						</div>
						</div>
                    
					</div>
            
				</form>
				<div id="prodResources">
				 <div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">Additional Productive Resource Hours</h4>
					
					</div>
				  <div class="title"><p>These are resources typically not in staffing but their hours apply to your HPPD Productivity (ie. Director, Manager, Clinician, etc.).  This does not apply to RN or GRID variance calculations based on Acuity or Census.</p>
				  </div>
				  <div>
								<button type="button" class="btn btn-info" onclick="tj.addnewResource();">Add Resource</button>
                                                                 
					 </div>
					
					<table id="resourceTableMgr"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="16%" id="res1" data-priority="1">Resources</th>
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
				</div>	
					
				<div class="p-2 mb-2 bg-info" style="padding-top: 20px;">
					<h4 class="modal-title" style="color:white">User Access</h4>
					
					</div>
				 <div class="title">
				 <p>User IDs that currently have access to this unit.</p>
				 </div>
				  <div>
				<button type="button" class="btn btn-info" onclick="tj.addnewtextResource();">Add User</button>                                        
					 </div>
				 <div>
					<table id="textTableMgr"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
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
                    <button type="button" id="unitButton" class="btn btn-primary" onclick="tj.updateUnitMgr();">Save </button>
                </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
		</div>
  

    <!--End::Main Portlet-->
	

<div class="modal fade" id="addtextResource" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Add User to this Unit</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="textdeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="textuserId">Select a User to add to this Unit.</label>
                            
							<div class="col-8">
                                    <select id="textuserId" type="select" class="form-control" >
									<option value="0">Select User</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName, u.last_name FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.accountId = {$accountId} and u.active>0 group by u.id order by u.last_name ASC, u.first_name ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
                            </div>
							<div class="form-group form-md-line-input" hidden>
                                <label for="grantText"><p>Allow User to receive Text Alerts for this Unit?</p></label>
                            
							<div class="col-8">
                                    <select id="grantText" type="select" class="form-control" >
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

	
<div class="modal fade" id="updateShifts" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Update Shifts</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                       <div class="modal-title"><h4><span id="shiftDept"></span></h4></div> 
                        <div class="form-body" style="center">
                            <div class="form-group">
                               <label for="shiftSelect"><p><strong>Select the desired times for this Unit's Shift Reports and these times will be applied the following day. Current shifts are selected.</strong></p>
							   <strong>IMPORTANT: If there is a grid associated with these shift times, that grid also need to be updated.</strong></label>
							  <div class="row">
							  <div class="col-6">
							   <select id="shiftSelect" size="20" class="form-control" multiple>
										<option value="52" id="52">12:00 Midnight</option>
										<option value="51" id="51">12:30AM</option>
										<option value="50" id="50">1:00AM</option>
										<option value="49" id="49">1:30AM</option>
										<option value="48" id="48">2:00AM</option>
										<option value="47" id="47">2:30AM</option>
										<option value="46" id="46">3:00AM</option>
										<option value="45" id="45">3:30AM</option>
										<option value="44" id="44">4:00AM</option>
										<option value="43" id="43">4:30AM</option>
										<option value="42" id="42">5:00AM</option>
										<option value="41" id="41">5:30AM</option>
										<option value="40" id="40">6:00AM</option>
										<option value="39" id="39">6:30AM</option>
										<option value="38" id="38">7:00AM</option>
										<option value="37" id="37">7:30AM</option>
										<option value="36" id="36">8:00AM</option>
										<option value="35" id="35">8:30AM</option>
										<option value="34" id="34">9:00AM</option>
										<option value="33" id="33">9:30AM</option>
										<option value="32" id="32">10:00AM</option>
										<option value="31" id="31">10:30AM</option>
										<option value="30" id="30">11:00AM</option>
										<option value="29" id="29">11:30AM</option>
										<option value="28" id="28">12:00 Noon</option>
										<option value="27" id="27">12:30PM</option>
										<option value="26" id="26">1:00PM</option>
										<option value="25" id="25">1:30PM</option>
										<option value="24" id="24">2:00PM</option>
										<option value="23" id="23">2:30PM</option>
										<option value="22" id="22">3:00PM</option>
										<option value="21" id="21">3:30PM</option>
										<option value="20" id="20">4:00PM</option>
										<option value="19" id="19">4:30PM</option>
										<option value="18" id="18">5:00PM</option>
										<option value="17" id="17">5:30PM</option>
										<option value="16" id="16">6:00PM</option>
										<option value="15" id="15">6:30PM</option>
										<option value="14" id="14">7:00PM</option>
										<option value="13" id="13">7:30PM</option>
										<option value="12" id="12">8:00PM</option>
										<option value="11" id="11">8:30PM</option>
										<option value="10" id="10">9:00PM</option>
										<option value="9" id="9">9:30PM</option>
										<option value="8" id="8">10:00PM</option>
										<option value="7" id="7">10:30PM</option>
										<option value="6" id="6">11:00PM</option>
										<option value="5" id="5">11:30PM</option>
										
																		
								</select>
								</div>
								<div class="col-6">
								<div class="title"><strong>Current Shift Report Times:</strong>
							  </div>
                            <span id="currentTimes"></span>
							
							<div class="title" hidden><strong>New Shift Report Times:</strong>
							  </div>
                            <span id="newTimes"></span>
							
							</div>
							
							</div>
                                
                            </div>
							
							</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="updateshiftsButton" class="btn btn-primary" onclick="tj.updateShifts();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<div class="modal fade" id="addCategory" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
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
	
<div class="modal fade" id="updateDataModal" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Update Records</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                               <label for="updatestartDate"><p>Start Date:</p></label>
                            
                                <input class="form-control" type="date" id="updatestartDate" value="">
                            </div>
							<div class="form-group form-md-line-input">
                               <label for="updateendDate"><p>End Date:</p></label>
                            
                                <input class="form-control" type="date" id="updateendDate" value="">
                            </div>
							
							</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="updateDataButton" class="btn btn-primary" onclick="tj.updateData();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<div class="modal fade" id="updatetextStatus" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
                    <h4 class="modal-title">Add / Remove Text Alerts</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="updaterecordId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                               <label for="updategrantText"><p>Would you like to allow this User to receive Text Alerts for this Unit?</p></label>
                            
							<div class="col-8">
                                    <select id="updategrantText" type="select" class="form-control" >
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

<div class="modal fade" id="addResource" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
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
							<div class="form-group form-md-line-input" hidden>
							<label for="resourcevalue">Budget Value($):</label>
							
							<input type="number" id="resourcevalue" class="form-control number" hidden>
							
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

<div class="modal fade" id="editResource" tabindex="-1" role="basic" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" ></button>
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
	


