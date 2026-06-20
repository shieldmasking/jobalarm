<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';
//session_start();



$userId = $_SESSION['account']['id'];
$role = $_SESSION['account']['role'];
$accountId = $_SESSION['account']['accountId'];

//$dbData = Config::get('db')->get_results("SELECT *, count(`deptId`) as totalDept from `productiveDeptXref` where `userId`={$userId} group by `deptId`");
//if($dbData){
//	$units = intval($dbData[0]['totalDept']);
//}else{
//	$units = 0;
//}
	


?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Users
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
       <div class="m-portlet m-portlet--mobile">
           <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6">
						</div>
						
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addnewUser" data-toggle="modal">
                                            Add User
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					</div>
				
					<div class="col-xl-12">
                    <div class="row">
					
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
                                <label for="ecf_newuser_first" class="col-4 col-form-label">
                                    First Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_first" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ecf_newuser_last" class="col-4 col-form-label">
                                    Last Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_last" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ecf_newuser_email" class="col-4 col-form-label">
                                    Email Address
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_newuser_email" required>
                                </div>
                            </div>
							<div class="form-group">
                                <label for="ecf_newuser_role" class="col-4 col-form-label">
                                    Role
                                </label>
                                <div class="col-8">
								<select id="ecf_newuser_role" type="select" class="form-control">
								<option value="0">Choose Role</option>		
								<?php
								if($role==9) {
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where (`id`=4 OR `id`=8 OR `id`=9)  order by `id` ASC");
								}else{
								$dbUser = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <{$role} order by `id` ASC");	
								}
								foreach($dbUser as $u):	?>
								<option value="<?php echo $u['id'];?>"><?php echo $u['role'];?> </option>
								<?php endforeach; ?>
										</select>
                                    
                                </div>
                            </div>
							
							<div class="form-group">
                               	<label for="unitAssign" class="col-4 col-form-label">Primary Unit:
								</label>
                                <div class="col-8">
                                    <select name="unitAssign" id="unitAssign" type="select" class="form-control" required >
								<option value="0">Assign Unit</option>		
								<?php
								if ($role<10) {
								$dbData = Config::get('db')->get_results("SELECT * from `ProductiveDept` where `id` in (SELECT `deptId` FROM `productiveDeptXref` WHERE `userId`={$userId}) group by `id` order by `dept` ASC");
								}else{
								$dbData = Config::get('db')->get_results("SELECT * from `ProductiveDept` where `accountId`={$accountId} order by `dept` ASC");	
								} ?>
								<?php
								if($dbData): ?>
								<?php foreach($dbData as $m):	?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['dept'];?> </option>
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
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_user_email" required>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label for="ecf_user_role" class="col-3 col-form-label">
                                    Role:  <span id="roleName"></span>
                                </label>                         
                                <div class="col-9" id="roleSelect">
                                    <select id="ecf_user_role" type="select" class="form-control">
										<?php
								if($role==9) {
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where (`id`=4 OR `id`=8 OR `id`=9)  order by `id` ASC");
								}else{
								$dbnewRole = Config::get('db')->get_results("SELECT * from `productiveUserRoles` where `id` <={$role} order by `id` ASC");	
								}
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
				<button type="button" class="btn btn-info pull-right" onclick="tj.newUnit();">Add Unit</button>                                        
					 </div>
					 </div>
				 <div class="title">
				 <p>This user is associated with the following Units.</p>
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
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateUser();">Update</button>
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
						<input id="userRole" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group" >
                                <label for="newunitdeptId">Select a Unit to add this User to.   (Use the Transfer feature if you want to transfer this User out of this Unit and into another.)</label>
                            
							<div class="col-8">
                                    <select id="newunitdeptId" type="select" class="form-control" required >
									<option value="0">Select Unit</option>
									<?php
									if ($role<8){
									$dbDir = Config::get('db')->get_results("SELECT x.*, COUNT(x.deptId) as depCount, d.dept as depName FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE x.userId ={$userId} group by x.deptId order by d.dept ASC");
									}else{
									$dbDir = Config::get('db')->get_results("SELECT *, `id` as deptId, `dept` as depName FROM `ProductiveDept` WHERE `accountId`={$accountId} group by `id` order by `dept` ASC");
									} ?>
									<?php if($dbDir): ?>
									<?php foreach($dbDir as $d): ?>
									<option value="<?php echo $d['deptId']; ?>"><?php echo $d['depName']; ?></option>
									<?php endforeach; ?>
									<?php endif; ?>
									
								</select>
								</div>
								</div>
								<div class="form-group" >
                            
                                <label for="newunitText"><p>Would you like to allow this User to receive Text Alerts for this Unit?</p></label>
                            
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

