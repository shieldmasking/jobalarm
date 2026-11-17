<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';
//session_start();

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}



$userId = $_SESSION['account']['id'];
$role = $_SESSION['account']['role'];
$accountId = $_SESSION['account']['accountId'];
$label = $_SESSION['account']['label'];
$dbCoverage = Config::get('db')->get_results("SELECT * from `productiveUser` where `accountId`={$accountId} AND `role`<12 AND `role`>4 AND `active`>0 GROUP BY `id` order by `last_name` ASC, `first_name` ASC");

if($role!=11){
$update = 'disabled';
$allUnits = 'hidden';
}else{
$update = '';
$allUnits = '';
}
	


?>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Users
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
       <div class="m-portlet m-portlet--mobile">
           <div class="m-portlet__body">
				 <div class="col-12">
                       
							<div>
								<div>
										<?php
										if($role<90){ ?>
                                        <button type="button" class="btn btn-success pull-left" data-target="#addnewUser" data-toggle="modal">
                                            New User
                                        </button>
										<?php }else{ ?>
										<button type="button" class="btn btn-success pull-left" data-target="#addnewUser" data-toggle="modal">
                                            New User
                                        </button>
										<button type="button" class="btn btn-info pull-left" data-target="#addnewAdmin" data-toggle="modal">
                                            New Admin
                                        </button>
										<?php } ?>
										
										
										</div>
                                <!--end: Dropdown-->
                            </div>
					
					
					</div>
				
					
                    <div class="row col-12">
					
                        <table id="UserTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="25%" data-priority="1">Name</th>
									<th width="20%" data-priority="2">Unit(s)</th>
									<th width="20%" data-priority="3">Role</th>
									<th width="15%" data-priority="4">Last Login</th>
									<th width="20%" data-priority="5">Coverage</th>
                                    
                                </tr>
                            </thead>
                        </table>
						</div>
                
                </div>
            </div>
       
  
    <!--End::Main Portlet-->
	
<div id="confirmUser" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="usermsgType"></h4>
            </div>
            <div class="modal-body" align="center">
                <h5><span id="userMessage"></span></h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>	
    <!--begin::Modal-->
 	    <div class="modal fade" id="addnewUser" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
				  <h4 class="modal-title" style="color:white">Add New User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
					<form role="form">
                    <!--<form id="add_user_form" class="m-form m-form--fit m-form--label-align-right">-->
                        <div class="m-portlet__body">
                           	<div class="form-group">
							<div class="row">
							<div class="col-md">
                                <label for="ecf_newuser_first" class="col-form-label">
                                    First Name
                                </label>
                                <div>
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_first">
                                </div>
                            </div>
                            <div class="col-md">
                                <label for="ecf_newuser_last" class="col-form-label">
                                    Last Name
                                </label>
                                <div>
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_last">
                                </div>
                            </div>
							</div>
							</div>
							
							
                            <div class="form-group">
							<div class="row">
							<div class="col-md">
                                <label for="ecf_newuser_email" class="col-form-label">
                                    Email Address
                                </label>
                                <div>
                                    <input class="form-control m-input" type="email" value="" id="ecf_newuser_email">
                                </div>
                            </div>
							</div>
							</div>
							
							
                            <div class="form-group">
							<div class="row">
							<div class="col-md">
                                <label for="ecf_newuser_role" class="col-form-label">
                                    Role
                                </label>
                                <div>
								<select id="ecf_newuser_role" type="select" class="form-control">
								<option value="0">Choose Role</option>		
								<?php
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <{$role} AND `id`<11 AND `id`>3 order by `id` ASC");	
								foreach($dbUser as $u):	?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                    
                                </div>
                            </div>
							<div class="form-group" hidden>
                               	<label for="accountAssign" class="col-form-label">Account:
								</label>
                                <div class="col-8">
                                    <select id="accountAssign" type="select" class="form-control">
									<option value="0">Select</option>
								<?php
								if($role<100){
								$dbAcct = Config::get('db')->get_results("SELECT * from `productiveAccount` WHERE `label`={$label} order by `name` ASC");
								}else{
								$dbAcct = Config::get('db')->get_results("SELECT * from `productiveAccount` order by `name` ASC");	
								}
								
								foreach($dbAcct as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['name'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
							<div class="col-md">
                               	<label for="unitAssign" class="col-form-label">Primary Unit:
								</label>
                                <div>
                                    <select name="unitAssign" id="unitAssign" type="select" class="form-control" required >
								<option value="0">Assign Unit</option>		
								<?php
								if ($role<8) {
								$dbData = Config::get('db')->get_results("SELECT *, `dept` as deptName from `ProductiveDept` where `id` in (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId}) and `active`>0 group by `id` order by `dept` ASC");
								}else{
								$dbData = Config::get('db')->get_results("SELECT *, `dept` as deptName from `ProductiveDept` where `accountId`={$accountId} AND `active`>0 order by `dept` ASC");	
								}
								?>
								<?php
								if($dbData): ?>
								<?php foreach($dbData as $m):	?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['deptName'];?> (<?php echo $m['unitId'];?>) </option>
								<?php endforeach; ?>
								<?php endif; ?>
								</select>
                            </div>
							</div>	
							</div>
							</div>
							<hr></hr>
							<div class="form-group">
							<div class="row">
							<div class="col-md" id="compReport2" hidden>
							  <label> <input type="checkbox" id="compReport" name="compReport"><strong> Receive Email Reports</strong>
							  </label>
							</div>
							<div class="col-md" hidden>									
							  <label> <input type="checkbox" id="isprovider" name="isprovider"><strong>Also a Provider?</strong></label><br>
							</div>
							</div>
							</div>
							<div class="form-group" hidden>									
							  <label> <input type="checkbox" id="sendemail" name="sendemail" checked><strong>Send Email?</strong></label><br>
							</div>
                         </div>
						 
                    </form>
                </div>
                <div class="modal-footer bg-info">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="userButton" class="btn btn-primary" onclick="tj.startNewUser();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>

	    <!--begin::Modal-->
<div class="modal fade" id="addnewAdmin" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header bg-info">
				  <h4 class="modal-title" style="color:white">Add New Admin</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
					<form role="form">
                    <!--<form id="add_user_form" class="m-form m-form--fit m-form--label-align-right">-->
                        <div class="m-portlet__body">
                           	<div class="form-group">
                                <label for="admin_newuser_first" class="col-form-label">
                                    First Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="admin_newuser_first">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="admin_newuser_last" class="col-form-label">
                                    Last Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="admin_newuser_last">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="admin_username" class="col-form-label">
                                    Username
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="admin_username">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="admin_newuser_role" class="col-form-label">
                                    Role
                                </label>
                                <div class="col-8">
								<select id="admin_newuser_role" type="select" class="form-control">
								<option value="0">Choose Role</option>		
								<?php
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id`={$role} order by `id` ASC");	
								foreach($dbUser as $u):	?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                    
                                </div>
                            </div>
							<div class="form-group" hidden>
                               	<label for="adminaccountAssign" class="col-form-label">Account:
								</label>
                                <div class="col-8">
                                    <select id="adminaccountAssign" type="select" class="form-control">
									<option value="0">Select</option>
								<?php
							
								$dbAcct = Config::get('db')->get_results("SELECT * from `productiveAccount` WHERE `label`={$label} order by `name` ASC");
								
								foreach($dbAcct as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['name'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>		
                         </div>
						 
                    </form>
                </div>
                <div class="modal-footer bg-info">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="userButton" class="btn btn-primary" onclick="tj.startNewAdmin();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
</div>
	    <!--begin::Modal-->
    <!--end::Modal-->
<div class="modal fade" id="edit_user" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header bg-info">
				  <h4 class="modal-title" style="color:white">Edit User Details</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
			
				<div class="row" style="margin-top:5px;margin-bottom:5px">
                        <div class="col-sm-6">
                            <div><h5><span id="userName3"></span></h5>
							</div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-danger pull-right" onclick="tj.deleteUser();">Delete User</button> 
						</div>
                    </div>
					<div>Last Login: <span id="lastLogin"></span>
							</div>
						<hr></hr>
                    <!--begin::Form-->
					<form id="edit_user_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="edit_userId" type="hidden" value="" />
						<input id="unitOrig" type="hidden" value="" />
						
						<input id="roleOrig" type="hidden" value="" />
						
						
                            <div class="form-group">
                                <label for="ecf_user_email" class="col-form-label">
                                    Email:
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_user_email">
                                </div>
                            </div>
							 <div class="form-group" hidden>
                                <label for="ecf_username" class="col-form-label">
                                    Username:
                                </label>
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" maxlength="50" value="" id="ecf_username" disabled>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label for="ecf_user_role" class="col-form-label">
                                    Role:  <span id="roleName"></span>
                                </label>                         
                                <div class="col-6" id="roleSelect">
                                    <select id="ecf_user_role" type="select" class="form-control">
										<?php
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <14 or `id`={$role} order by `id` ASC");	
								foreach($dbnewRole as $r):	?>
								<option value="<?php echo $r['id'];?>"><?php echo $r['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                </div>
								</div>
							
                            <div class="form-group">
                                <label for="qblWarnMin" class="col-form-label">QBL Warning — Send SMS when QBL exceeds (ml):</label>
                                <div class="col-6">
                                    <input class="form-control m-input" type="number" id="qblWarnMin" value="300" min="0" step="50">
                                </div>
                            </div>

                            <div class="form-group">
							<div class="row">
							<div class="col-md" id="compReport1" hidden>
							  <label class="col-form-label"> <input type="checkbox" id="compReport" name="compReport"><strong> Receive Email Reports</strong>
							  </label>
							</div>
							<div class="col-md" hidden>								
							  <label class="col-form-label"> <input type="checkbox" id="isprov" name="isprov"><strong> Also a Provider?</strong>
							  </label>
							</div>
							</div>
							</div>
							</form>		
							
						
										
				   
       
                </div>
                <div class="modal-footer bg-info">
			
				<div class="col-sm-6">			
					<button type="button" class="btn btn-primary pull-right" onclick="tj.updateUser();" <?php echo $update; ?>>Save</button>
				     <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Cancel</button>
                    
                </div>
			</div>	
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
	
	<div class="modal fade" id="edit_Unit" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-6">
		<div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info">
				  <h4 class="modal-title" style="color:white">Unit Assignments</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				<input id="roleUnit" type="hidden" value="" />	
				<input id="roleUserId" type="hidden" value="" />					
                    <!--begin::Form-->
				<div class="row" style="margin-top:5px;margin-bottom:5px">
				<div class="col-sm-6">
				<div class="title">
				 <h5><span id="userName4"></span></h5>
				 </div>
				 </div>
				 <div class="col-sm-6">
				<button type="button" class="btn btn-info pull-right" onclick="tj.newUnit();">Assign Unit</button>                                        
					 </div>
					 </div>
				 <div class="title">
				 <p>This user is assigned to the following Units.</p>
				 </div>
				  
				 <div style="overflow-x:auto;width:100%">
					<table id="unitTableMgr" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
                                    <th width="15%" data-priority="2">Report (Text)</th>
									<th width="15%" data-priority="3">Text (Variance)</th>
									 <th width="15%" data-priority="4">Esc Alerts (Text)</th>
									 <th width="15%" data-priority="5">Email Alerts</th>
									<th width="20%" data-priority="6">Action</th> 
                                </tr>
                            </thead>
                        </table>
					</div>
					
									       
                </div>
                <div class="modal-footer bg-info">
				<div class="col-sm-6">			
				     <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>
                    
                </div>
			</div>	
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="edittxtVar" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
				  <h4 class="modal-title">Variance Threshold for Report Text Alerts</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">	
				<input id="txtVaruserId" type="hidden" value="" />	
				<input id="txtVardeptId" type="hidden" value="" />	
                    <!--begin::Form-->
				<div class="row" style="margin-top:5px;margin-bottom:5px">
				
				<div class="title">
				 <h5><span id="txtVarDept"></span></h5>
				 </div>
				 
				
					 </div>
				 <div class="title">
				 <p>Select the +/- RN or Grid Variance Threshold at which you want to receive Report Text Alerts. </p>
				 </div>
				  
				 <div>
				       <div class="col-6">
                                    <select id="txtVar" type="select" class="form-control">
									<option value="0">Receive All Texts</option>
									<option value="1">+/- 1 Variance</option>
									<option value="2">+/- 2 Variance</option>
									<option value="3">+/- 3 Variance</option>
									<option value="4">+/- 4 Variance</option>
									<option value="5">+/- 5 Variance</option>
								
										</select>
                            </div>
					
					</div>
					
									       
                </div>
                <div class="modal-footer bg-secondary">
				<div class="col-sm-6">			
				     <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Cancel</button>
					 <button type="button" class="btn btn-primary pull-right" onclick="tj.updatetxtVar();">Save</button>
                    
                </div>
			</div>	
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>

	<div class="modal fade" id="addnewUnit" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add User to another Unit</h4>
                </div>
                <div class="modal-body">
				<form id="new_unit_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="newunituserId" type="hidden" value="" />
						
                        <div class="form-body">
                            <div class="form-group" >
                                <label for="newunitdeptId">Select a Unit to add this User to.</label>
                            
							<div class="col-8">
                                    <select id="newunitdeptId" type="select" class="form-control" required >
									<option value="0">Select Unit</option>
									<!--<option value="1">Add ALL</option>-->
									<?php
									if ($role<8){
									$dbDir = Config::get('db')->get_results("SELECT x.*, COUNT(x.deptId) as depCount, d.dept as depName, d.unitId FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE d.active>0 AND d.active<3 AND x.userId ={$userId} group by x.deptId order by d.dept ASC");
									}else{
									$dbDir = Config::get('db')->get_results("SELECT *, `id` as deptId, `dept` as depName FROM `ProductiveDept` WHERE `accountId`={$accountId} AND `active`>0 group by `id` order by `dept` ASC");
									} ?>
									<?php if($dbDir): ?>
									<?php foreach($dbDir as $d): ?>
									<option value="<?php echo $d['deptId']; ?>"><?php echo $d['depName'];?> (<?php echo $d['unitId'];?>) </option>
									<?php endforeach; ?>
									<?php endif; ?>
									
								</select>
								</div>
								</div>
								<div class="form-group" >
                                <label for="userRole">Select the Role this person will have.</label>
                            
							<div class="col-8">
                                    <select id="userRole" type="select" class="form-control" required >
									<option value="0">Choose Role</option>		
								<?php
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id`<={$role} order by `id` ASC");	
								foreach($dbnewRole as $r):	?>
								<option value="<?php echo $r['id'];?>"><?php echo $r['role'];?> </option>
								<?php endforeach; ?>
									
								</select>
								</div>
								</div>
								<div class="form-group" hidden>
                            
                                <label for="newunitText">Allow User to receive Text Alerts for this Unit?</label>
                            
							<div class="col-8">
                                    <select id="newunitText" type="select" class="form-control" required >
									<option value=0>No</option>
									<option value=1>Yes</option>
									</select>
							</div>
							
							</div>
							

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.newUnitAdd();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
   <!--end::Modal-->
    <!--begin::Modal-->
 	<div class="modal fade" id="transferNewUnit" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-8">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">Transfer User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="transfer_unit_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="transferuserId" type="hidden" value="" />
						<input id="deptIdOrig" type="hidden" value="" />
						
						<div class="title">
				 <p>This will transfer the User out of their current Unit and into the selected Unit.   (Use the Add Unit feature if you want to keep this User in their current Unit and add them to another.)</p>
				 </div>
                           	<div class="form-group" >
							  <label for="transferdeptId">New Unit</label>
                            
							<div class="col-8">
                                    <select id="transferdeptId" type="select" class="form-control">
									<option value="0">Select Unit</option>
									
									<?php
									if ($role<8){
									$dbTran = Config::get('db')->get_results("SELECT x.*, COUNT(x.deptId) as depCount, d.dept as depName FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE x.userId ={$userId} group by x.deptId order by d.dept ASC");
									}else{
									$dbTran = Config::get('db')->get_results("SELECT x.*, COUNT(x.deptId) as depCount, d.dept as depName FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE d.accountId ={$accountId} group by x.deptId order by d.dept ASC");
									} ?>
									<?php if($dbTran): ?>
									<?php foreach($dbTran as $t): ?>
									<option value="<?php echo $t['deptId']; ?>"><?php echo $t['depName']; ?></option>
									<?php endforeach; ?>
									<?php endif; ?> 
									
								</select>
								</div>
								</div>
                            <div class="form-group" >
                            
                                <label for="transferText"><p>Would you like to allow this User to receive Text Alerts for this Unit?</p></label>
                            
							<div class="col-8">
                                    <select id="transferText" type="select" class="form-control" >
									<option value=0>No</option>
									<option value=1>Yes</option>
									</select>
							</div>
							
							</div>
							</form>
																				
						</div>	
						
                
					
				<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.completeTransfer();">Transfer</button>
                </div>                  
					
						 
			       
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
	</div>
</div>
<div class="modal fade" id="usercoverage" tabindex="-1" role="basic">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title" style="color:white"><strong>Coverage:<span id="coverageUserName"></span></strong></h4>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<input type="text" id="coverUserTarget" hidden />
				<p class="text-muted small mb-3">Grant access to others while you are out on Vacation, PTO, On-Call, etc.</p>
				<div class="form-group">
					<label for="coverUserId">Person Covering</label>
					<select id="coverUserId" class="form-control">
						<option value="0">-- select user --</option>
						<?php foreach($dbCoverage as $d){ ?>
						<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] . ', ' . $d['first_name']; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="row">
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverUserStartDate">Start Date</label>
							<input class="form-control" type="date" id="coverUserStartDate">
						</div>
					</div>
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverUserStartTime">Start Time</label>
							<select class="form-control" id="coverUserStartTime">
								<?php for($h=0;$h<24;$h++){$t=str_pad($h,2,'0',STR_PAD_LEFT).':00';echo "<option value=\"$t\">$t</option>";}?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverUserDate">End Date</label>
							<input class="form-control" type="date" id="coverUserDate" value="">
						</div>
					</div>
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverUserTime">End Time</label>
							<select class="form-control" id="coverUserTime">
								<option value="">-- no end time --</option>
								<?php for($h=0;$h<24;$h++){$t=str_pad($h,2,'0',STR_PAD_LEFT).':00';echo "<option value=\"$t\">$t</option>";}?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverVarianceMin">Variance Threshold (Report SMS)</label>
							<select id="coverVarianceMin" class="form-control">
								<option value="0">Any variance</option>
								<option value="1" selected>±1 or more</option>
								<option value="2">±2 or more</option>
								<option value="3">±3 or more</option>
								<option value="4">±4 or more</option>
								<option value="5">±5 or more</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-sm-6">
						<div class="form-group">
							<label for="coverQblMin">QBL Minimum (ml)</label>
							<input class="form-control" type="number" id="coverQblMin" value="300" min="0" step="50">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="coverPauseSms" value="1">
						<label class="form-check-label" for="coverPauseSms">Pause my SMS messages during coverage</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info btn-block" onclick="tj.updateUserCover();">Save</button>
			</div>
		</div>
	</div>
</div>

</div>
</div>

