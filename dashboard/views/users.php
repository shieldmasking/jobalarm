<?php
include "../inc/initializer.php";

require_once '../../inc/class.db.php';

require_once '../../inc/class.jatwitter.php';

require_once '../../inc/config.php';

$role = intval($_SESSION['account']['role']);



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
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >4): ?>
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
					<?php endif; ?>
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="UserTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="50%" data-priority="1">Name</th>
                                    <th width="30%" data-priority="2">Role</th>
                                    <th width="20%" data-priority="3" hidden>Locations</th>
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
	
    <!--begin::Modal-->
 	    <div class="modal fade" id="addnewUser" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-6">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Add New User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_user_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                           	<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="example-storenum-input" class="col-4 col-form-label">
                                    First Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_first">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-address-input" class="col-4 col-form-label">
                                    Last Name
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" value="" id="ecf_newuser_last">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-city-input" class="col-4 col-form-label">
                                    Email Address
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="email" maxlength="40" value="" id="ecf_newuser_email">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-state-input" class="col-4 col-form-label">
                                    Role
                                </label>
                                <div class="col-8">
								<select id="ecf_newuser_role" type="select" class="form-control">
										<option value="3">User (Can see assigned locations)</option>
										<option value="4">Super User (Can see all locations)</option>
										<option value="5">Admin</option>
										</select>
                                    
                                </div>
                            </div>
                         </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addNewUser();">Add</button>
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
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-6 job-address">
                            <div><span id="userName3"></span>
							</div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-danger pull-right" onclick="tj.deleteUser();">Delete User</button>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form id="edit_user_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userId" type="hidden" value="" />
						<div class="m-portlet__body">
                            <div class="form-group m-form__group m--margin-top-10 row">
                                <label for="example-storenum-input" class="col-3 col-form-label">
                                    Email
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="email" maxlength="30" value="" id="ecf_user_email">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-state-input" class="col-3 col-form-label">
                                    Role
                                </label>
                                <div class="col-9">
                                    <select id="ecf_user_role" type="select" class="form-control">
										<option value="">Select...</option>
										<option value=3>User (Can see assigned locations)</option>
										<option value=4>Super User (Can see all locations)</option>
										<option value=5>Admin</option>
										</select>
                                </div>
                            </div>
                           </div>
                    </form>
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
	    <!--begin::Modal-->
    <div class="modal fade" id="usersAssigned" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="userLocationsLabel">
                        Assigned Locations
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-top:15px;margin-bottom:15px">
                        <div class="col-sm-6 job-address">
                            <div><span id="userName2"></span></div>
                         </div>
                     </div>
                    <div class="row">
                        <table id="UserAssignedTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="60%" data-priority="1">Store Number</th>
                                    <th width="20%" data-priority="2">Address</th>
                                    <th width="20%" data-priority="3">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Go Back
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->

    <!--begin::Modal-->
    <div class="modal fade" id="add_users_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="usersModalLabel">
                        Add User
                    </h5>
					  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <!--begin::Form-->
                            <form class="m-form m-form--fit m-form--label-align-right" style="width:100%">
                                <!--<input type="hidden" name="add_users_input" id="add_users_input" value="" />-->
                                <div class="form-group m-form__group">
                                  	<label for="add_users_input">
									Select User to add to this location.
                                    </label>
									<select id="add_users_input" type="select" class="form-control">
									<option value="">Select...</option>
                                    <?php
									$accountId = $_SESSION['account']['accountId'];
									$smsUsers = Config::get('db')->get_results("select `id`,`last_name`,`first_name` from `users` where `accountId`='{$accountId}' and `role`<4 order by `last_name`,`first_name` asc");
                                    foreach($smsUsers as $user) :
									?>
									<option value=<?php echo $user['id']; ?>><?php echo $user['last_name'].", ".$user['first_name']; ?></option>
                                    <?php endforeach; ?>
									</select>
                                    <!--<input type="text" class="form-control m-input" id="add_users_input" name="add_users_input" placeholder="Select User">-->
                                </div>
                              </form>
                            <!--end::Form-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="tj.addUser();">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
	
</div>