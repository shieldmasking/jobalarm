<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';
//session_start();



$userId = $_SESSION['account']['id'];
$role = $_SESSION['account']['role'];
$accountId = $_SESSION['account']['accountId'];
$label = $_SESSION['account']['label'];

if($role==6){
	$manager = 'disabled';
	$director = 'disabled';
	$disabled = 'disabled';
	$hidden = 'hidden';
}else if($role==7){
	$disabled = 'disabled';
	$hidden = 'hidden';
	$manager = '';
	$director = 'disabled';
}else if($role>7 && $role<90){
	$disabled = 'disabled';
	$hidden = 'hidden';
	$manager = 'disabled';
}else{
	$disabled = '';
	$hidden = '';
}
	


?>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Users
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
       <div class="m-portlet m-portlet--mobile">
           <div class="m-portlet__body">
				 <div class="col-12">
                       
							<div class="pull-right">
								<div>
										<?php
										if($role<90){ ?>
                                        <button type="button" class="btn btn-success" data-target="#addnewUser" data-toggle="modal">
                                            New User
                                        </button>
										<?php }else{ ?>
										<button type="button" class="btn btn-success" data-target="#addnewUser" data-toggle="modal">
                                            New User
                                        </button>
										<button type="button" class="btn btn-info" data-target="#addnewAdmin" data-toggle="modal">
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
									<th width="19%" data-priority="2">Primary Unit</th>
									<th width="18%" data-priority="3">Role</th>
									<th width="19%" data-priority="4">Last Login</th>
                                    <th width="19%" data-priority="5">Text Alerts</th>
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
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Add New User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
					<form role="form">
                    <!--<form id="add_user_form" class="m-form m-form--fit m-form--label-align-right">-->
                        <div class="m-portlet__body">
                           	<div class="form-group">
                                <label for="ecf_newuser_first" class="col-form-label">
                                    First Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_first">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ecf_newuser_last" class="col-form-label">
                                    Last Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_last">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ecf_newuser_email" class="col-form-label">
                                    Email Address
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="email" value="" id="ecf_newuser_email">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="ecf_newuser_role" class="col-form-label">
                                    Role
                                </label>
                                <div class="col-8">
								<select id="ecf_newuser_role" type="select" class="form-control">
								<option value="0">Choose Role</option>		
								<?php
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <{$role} AND `id`<10 order by `id` ASC");	
								foreach($dbUser as $u):	?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                    
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden; ?>>
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
							<div class="form-group">
                               	<label for="unitAssign" class="col-form-label">Primary Unit:
								</label>
                                <div class="col-8">
                                    <select name="unitAssign" id="unitAssign" type="select" class="form-control" required >
								<option value="0">Assign Unit</option>		
								<?php
								if ($role<8) {
								$dbData = Config::get('db')->get_results("SELECT *, `dept` as deptName from `ProductiveDept` where `id` in (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId}) group by `id` order by `dept` ASC");
								}else if ($role>=8 && $role<12){
								$dbData = Config::get('db')->get_results("SELECT *, `dept` as deptName from `ProductiveDept` where `accountId`={$accountId} order by `dept` ASC");	
								}else if ($role>89 && $role<100){
								$dbData = Config::get('db')->get_results("SELECT d.*, a.enterpriseId, CONCAT(a.name,' - ',d.dept) as deptName from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id = d.accountId where a.label={$label} group by d.id order by a.name ASC, d.dept ASC");	
								}else{
								$dbData = Config::get('db')->get_results("SELECT d.*, a.enterpriseId, CONCAT(a.name,' - ',d.dept) as deptName from `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id = d.accountId group by d.id order by a.name ASC, d.dept ASC");	
								} ?>
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
						 
                    </form>
                </div>
                <div class="modal-footer">
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
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Add New Admin</h4>
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
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id`={$role} OR`id`=10 order by `id` ASC");	
								foreach($dbUser as $u):	?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                    
                                </div>
                            </div>
							<div class="form-group" <?php echo $hidden; ?>>
                               	<label for="adminaccountAssign" class="col-form-label">Account:
								</label>
                                <div class="col-8">
                                    <select id="adminaccountAssign" type="select" class="form-control">
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
                         </div>
						 
                    </form>
                </div>
                <div class="modal-footer">
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
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Edit User Details</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				<div class="m-portlet__body">
				<div class="row" style="margin-top:5px;margin-bottom:5px">
                        <div class="col-sm-6">
                            <div><h5><span id="userName3"></span></h5>
							</div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-danger pull-right" onclick="tj.deleteUser();">Delete User</button> 
						</div>
                    </div>
					
                    <!--begin::Form-->
					<form id="edit_user_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="edit_userId" type="hidden" value="" />
						<input id="unitOrig" type="hidden" value="" />
						
						<input id="roleOrig" type="hidden" value="" />
						
						
                            <div class="form-group">
                                <label for="ecf_user_email" class="col-3 col-form-label">
                                    Email:
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_user_email" disabled>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label for="ecf_user_role" class="col-3 col-form-label">
                                    Role:  <span id="roleName"></span>
                                </label>                         
                                <div class="col-9" id="roleSelect">
                                    <select id="ecf_user_role" type="select" class="form-control">
										<?php
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <12 or `id`={$role} order by `id` ASC");	
								foreach($dbnewRole as $r):	?>
								<option value="<?php echo $r['id'];?>"><?php echo $r['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                </div>
                            </div>
							<hr></hr>
				<div class="row" style="margin-top:5px;margin-bottom:5px">
				<div class="col-sm-6">
				<div class="title">
				 <h5>Units</h5>
				 </div>
				 </div>
				 <div class="col-sm-6">
				<button type="button" class="btn btn-info pull-right" onclick="tj.newUnit();">Assign Unit</button>                                        
					 </div>
					 </div>
				 <div class="title">
				 <p>This user is assigned to the following Units.</p>
				 </div>
				  
				 <div>
					<table id="unitTableMgr"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="30%" data-priority="1">Unit</th>
                                    <th width="20%" data-priority="2">Text Alerts</th>
									<th width="40%" data-priority="3">Role</th> 
									<th width="10%" data-priority="4">Action</th> 
                                </tr>
                            </thead>
                        </table>
					</div>
					</form>		
										
					</div>     
       
                </div>
                <div class="modal-footer">
				<div class="col-sm-6">
					<button type="button" class="btn btn-info" onclick="tj.forgot2();">Reset Password</button>
				</div>
				<div class="col-sm-6">			
					<button type="button" class="btn btn-primary pull-right" onclick="tj.updateUser();">Update</button>
				     <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Cancel</button>
                    
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
                <div class="modal-header bg-light">
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
									<?php
									if ($role<8){
									$dbDir = Config::get('db')->get_results("SELECT x.*, COUNT(x.deptId) as depCount, d.dept as depName, d.unitId FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE x.userId ={$userId} group by x.deptId order by d.dept ASC");
									}else if($role>=8 && $role<12){
									$dbDir = Config::get('db')->get_results("SELECT *, `id` as deptId, `dept` as depName FROM `ProductiveDept` WHERE `accountId`={$accountId} group by `id` order by `dept` ASC");
									}else if($role>89 && $role<100){
									$dbDir = Config::get('db')->get_results("SELECT d.*, d.id as deptId, CONCAT(a.name, ' - ' , d.dept) as depName FROM `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id = d.accountId WHERE a.label ={$label} group by d.id order by a.name ASC, d.dept ASC");
									}else{
									$dbDir = Config::get('db')->get_results("SELECT d.*, d.id as deptId, CONCAT(a.name, ' - ' , d.dept) as depName FROM `ProductiveDept` d LEFT JOIN `productiveAccount` as a on a.id = d.accountId group by d.id order by a.name ASC, d.dept ASC");
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
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id`<{$role} AND `id`<10 order by `id` ASC");	
								foreach($dbnewRole as $r):	?>
								<option value="<?php echo $r['id'];?>"><?php echo $r['role'];?> </option>
								<?php endforeach; ?>
									
								</select>
								</div>
								</div>
								<div class="form-group" >
                            
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
                <div class="modal-header bg-light">
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
</div>

