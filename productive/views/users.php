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
                                    <th width="20%" data-priority="3">Text Alerts</th>
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
                <div class="modal-header">
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
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_newuser_email">
                                </div>
                            </div>
							<div class="form-group m-form__group row">
                                <label for="example-city-input" class="col-4 col-form-label">
                                    Mobile Number
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" maxlength="10" value="" id="ecf_newuser_mobile">
                                </div>
                            </div>
							<!--
							<div class="form-group m-form__group row">
							<label for="example-city-input" class="col-4 col-form-label">
                                    Text Alerts
                                </label>
							<div class="col-8">
							<input type="checkbox" data-toggle="modal" data-target="#optinjobalarm"><strong> Receive Text Message Alerts (Admins Only)</strong></input>
							</div>
							</div>
							-->
                            <div class="form-group m-form__group row">
                                <label for="example-state-input" class="col-4 col-form-label">
                                    Role
                                </label>
                                <div class="col-8">
								<select id="ecf_newuser_role" type="select" class="form-control">
										<option value="4">User</option>
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
                <div class="modal-header">
                    <h4 class="modal-title">Edit User Details</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-6">
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
                                <label for="ecf_user_email" class="col-3 col-form-label">
                                    Email
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="email" maxlength="50" value="" id="ecf_user_email">
                                </div>
                            </div>
							<div class="form-group m-form__group row">
                                <label for="ecf_mobile" class="col-4 col-form-label">
                                    Mobile Number
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" maxlength="10" value="" id="ecf_mobile">
                                </div>
                            </div>
							<!--
							<div class="form-group m-form__group row">
							<label for="example-city-input" class="col-4 col-form-label">
                                    Text Alerts
                                </label>
							<div class="col-8">
							<input type="checkbox" data-toggle="modal" data-target="#optinjobalarm"><strong> Receive Text Message Alerts (Admins Only)</strong></input>
							</div>
							</div>
							-->
                            <div class="form-group m-form__group row">
                                <label for="example-state-input" class="col-3 col-form-label">
                                    Role
                                </label>
                                <div class="col-9">
                                    <select id="ecf_user_role" type="select" class="form-control">
										<option value="">Select...</option>
										<option value=4>User</option>
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
    <div class="modal fade" id="add_users_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
<div class="modal fade" id="optinjobalarm" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
									<input id="mobile" type="hidden" value="" />
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">ProductiveRN Text Alerts</h4>
										</div>
										<div id="optBody" class="modal-body">
										<div class="form-group">
										<h5>Please select which type of Text Alerts you would like to receive. You will receive a text confirmation.</h5>
										</div>
										<div class="form-group">
										<?php if (intval($brandType) !=8) {?>
										<label class="form-group-label">
										<input type="checkbox" id="allvariance" name="allvariance" value="1" />
										<strong>Variance Alerts - Receive Text alerts about possible over or under staffing issues.</strong></br>
										</label>
										<?php } ?>
					
										<label class="form-group-label">
										<input type="checkbox" id="allalerts" name="allalerts" value="2" />
										<strong>Report Alerts - Receive text alerts when a Staffing Report is not completed as scheduled.</strong>
										</label>
										</div>
										<div class="form-group">
										<input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
										<p><strong>You will receive one confirmation text.  Reply STOP at any time to unsubscribe or edit your text profile online.</strong></p>
										</div>
										<div>
										<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via text message. Standard message and data rates may apply. JobAlarm.com <a href="/terms">Terms of Use and Privacy Policy.</a></p>
										
										<!--<p>* Conversational messages with Employers and administrative messages from JobAlarm.com do not count towards the 4 messages per month limit.</p>-->
										</div>
										<!--<div class="form-group">
										<p>*Subject to JobAlarm <a href="/terms" target="_blank"> Terms & Conditions.</a> </p>
										</div>-->
										<div class="form-group">
										<button type="button" class="btn btn-primary" onClick="tj.optin()">Submit</button>
										<!--<button type="submit" class="btn btn-success pull-right" id="optinsave">Save</button>-->
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										
										</div>
										
				
										
									<!-- /.modal-content -->
								<div class="modal-footer">
																						
								</div>
								<!-- /.modal-dialog -->
							</div>
			</div>							
</div>
							
</div>
</div>
	
</div>