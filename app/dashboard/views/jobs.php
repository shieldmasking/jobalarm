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
                Jobs
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
				                        
                    <div class="col-xl-12">
					<div class="row">
                        <table id="alljobsTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="20%" data-priority="1">Title</th>
									<th width="20%" data-priority="2">Location</th>
                                    <th width="25%" data-priority="3">Description</th>
                                    <th width="20%" data-priority="4">Date</th>
									<th width="15%" data-priority="5">Status</th>
									<th>Zip</th>
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
                
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
	
    <!--begin::Modal-->
    <div class="modal fade" id="job_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Jobs
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
                            <div><span id="jobName"></span></div>
                            <div><span id="jobStreet"></span></div>
                            <div><span id="jobCSZ"></span></div>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#add_job_modal">Add Job <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <table id="jobsTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="60%" data-priority="1">Position</th>
                                    <th width="20%" data-priority="2">Source</th>
                                    <th width="20%" data-priority="1">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
	<!--end::Modal-->
	    <div class="modal fade" id="addnewjob" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-6">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
				<h4 class="modal-title">Create New Location</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_location_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                  	<label for="add_users_input" class="col-4 col-form-label">
									Select a Brand
                                    </label>
									<div class="col-8">
									<select id="ecf_newlocation_brand" type="select" class="form-control">
									<?php
									$accountId = $_SESSION['account']['accountId'];
									$smsUsers = Config::get('db')->get_results("select s.id, s.storeBrand FROM `sms_brand` s LEFT JOIN `account_brand` as a on a.brandId=s.id where a.accountId='{$accountId}'");
                                    foreach($smsUsers as $user) :
									?>
									<option value=<?php echo $user['id']; ?>><?php echo $user['storeBrand']; ?></option>
                                    <?php endforeach; ?>
									</select>
									</div>
                                    <!--<input type="text" class="form-control m-input" id="add_users_input" name="add_users_input" placeholder="Select User">-->
                                </div>
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="example-storenum-input" class="col-4 col-form-label">
                                    Store Number
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" maxlength="20" value="" id="ecf_newlocation_num">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-address-input" class="col-4 col-form-label">
                                    Address
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" maxlength="50" value="" id="ecf_newlocation_address">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-city-input" class="col-4 col-form-label">
                                    City
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" maxlength="30" value="" id="ecf_newlocation_city">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-state-input" class="col-4 col-form-label">
                                    State
                                </label>
                                <div class="col-8">
								<select id="ecf_newlocation_state" type="select" class="form-control">
										<option value="">Select...</option>
										<option value="AL">Alabama</option>
										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>
										<option value="DE">Delaware</option>
										<option value="DC">District Of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>
										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>
										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>
										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option></select>
                                    
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-zip-input" class="col-4 col-form-label">
                                    Zip Code
                                </label>
                                <div class="col-8">
                                   <input class="form-control m-input" type="text" maxlength="5" value="" id="ecf_newlocation_zip">
                                </div>
                            </div>
							<div class="form-group m-form__group row">
                                  	<label for="add_users_input" class="col-4 col-form-label">
									Assign a user (optional)
                                    </label>
									<div class="col-8">
									<select id="ecf_newlocation_assign" type="select" class="form-control">
									<option value="">Select...</option>
                                    <?php
									$accountId = $_SESSION['account']['accountId'];
									$smsUsers = Config::get('db')->get_results("select `id`,`last_name`,`first_name` from `users` where `accountId`='{$accountId}' and `role`<6 order by `last_name`,`first_name` asc");
                                    foreach($smsUsers as $user) :
									?>
									<option value=<?php echo $user['id']; ?>><?php echo $user['last_name'].", ".$user['first_name']; ?></option>
                                    <?php endforeach; ?>
									</select>
									</div>
                                    <!--<input type="text" class="form-control m-input" id="add_users_input" name="add_users_input" placeholder="Select User">-->
                                </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addLocation();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
	    <!--begin::Modal-->
    <!--end::Modal-->
	    <div class="modal fade" id="edit_job" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
				<h4 class="modal-title">Edit Job Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				<div class="row" style="margin-top:15px;margin-bottom:15px">
                        <div class="col-sm-6 job-address">
                            <div><span id="locName"></span></div>
                            <div><span id="locStreet"></span></div>
                            <div><span id="locCSZ"></span></div>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-danger pull-right" onclick="tj.deleteLocation();">Delete</button>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form id="edit_location_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="location_id" type="hidden" value="" />
						<div class="m-portlet__body">
                            <div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ecf_location_num" class="col-5 col-form-label">
                                    Store Number
                                </label>
                                <div class="col-7">
                                    <input class="form-control m-input" type="text" maxlength="10" value="" id="ecf_location_num">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="ecf_location_address" class="col-5 col-form-label">
                                    Address
                                </label>
                                <div class="col-7">
                                    <input class="form-control m-input" type="text" maxlength="50" value="" id="ecf_location_address">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="ecf_location_city" class="col-5 col-form-label">
                                    City
                                </label>
                                <div class="col-7">
                                    <input class="form-control m-input" type="text" maxlength="30" value="" id="ecf_location_city">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="ecf_location_state" class="col-5 col-form-label">
                                    State
                                </label>
                                <div class="col-7">
                                    <select id="ecf_location_state" type="select" class="form-control">
										<option value="">Select...</option>
										<option value="AL">Alabama</option>
										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>
										<option value="DE">Delaware</option>
										<option value="DC">District Of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>
										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>
										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>
										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option></select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="ecf_location_zip" class="col-5 col-form-label">
                                    Zip Code
                                </label>
                                <div class="col-7">
                                    <input class="form-control m-input" type="text" maxlength="5" value="" id="ecf_location_zip">
                                </div>
                            </div>
							<hr></hr>
							<h5 class="modal-title">Email address to copy on Candidate Profiles.  System will also send profiles to Users assigned to this location.</h5>
							 <div class="form-group m-form__group row">
                                <label for="ecf_location_email" class="col-5 col-form-label">
                                    Email(cc)
                                </label>
                                <div class="col-7">
                                    <input class="form-control m-input" type="email" value="" id="ecf_location_email">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateJob();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
	    <!--begin::Modal-->
    <div class="modal fade" id="users_modal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usersModalLabel">
                        Assigned Users
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
                            <div><span id="userName"></span></div>
                            <div><span id="userStreet"></span></div>
                            <div><span id="userCSZ"></span></div>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#add_users_modal">Add User <i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <table id="usersTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="60%" data-priority="1">User</th>
                                    <th width="20%" data-priority="2">Role</th>
                                    <th width="20%" data-priority="1">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
	<!--begin::Modal-->
	    <div class="modal fade" id="edit_all_job" tabindex="-1" role="basic" aria-hidden="true">
		<div class="col-sm-5">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
					<h4 class="modal-title">Update Job Posting</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_all_job_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<div class="form-group m--margin-top-10">
                                <label for="jobLink" class="col-form-label">
                                    Job Link
                                </label>
                                <div class="col-10">
                                    <span id="jobLink"></span>
                                </div>
							
                            </div>
                            <div class="form-group m--margin-top-10">
                                <label for="all_job_title" class="col-form-label">
                                    Job Title
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="all_job_title">
                                </div>
							
                            </div>
                            <div class="form-group">
                                <label for="example-search-input" class="col-form-label">
                                    Job Posting
                                </label>
                                <div class="col-10">
									<textarea class="form-control" rows="3" maxlength="250" placeholder="" id="all_job_desc"></textarea>
                                </div>
							</div>
							<div class="row">
							<div class="form-group col-sm-8">
                                <label for="job_cand_city" class="col-3 col-form-label">
                                    City
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="job_cand_city">
                                </div>
                            </div>
							<div class="form-group col-sm-8">
                                <label for="job_cand_state" class="col-3 col-form-label">
                                    State
                                </label>
                                 <div class="col-10">
                                    <select id="job_cand_state" type="select" class="form-control">
										<option value="">Select...</option>
										<option value="AL">Alabama</option>
										<option value="AK">Alaska</option>
										<option value="AZ">Arizona</option>
										<option value="AR">Arkansas</option>
										<option value="CA">California</option>
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option>
										<option value="DE">Delaware</option>
										<option value="DC">District Of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option>
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option>
										<option value="IA">Iowa</option>
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option>
										<option value="MD">Maryland</option>
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option>
										<option value="MN">Minnesota</option>
										<option value="MS">Mississippi</option>
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option>
										<option value="NH">New Hampshire</option>
										<option value="NJ">New Jersey</option>
										<option value="NM">New Mexico</option>
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option>
										<option value="ND">North Dakota</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option>
										<option value="OR">Oregon</option>
										<option value="PA">Pennsylvania</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option>
										<option value="TX">Texas</option>
										<option value="UT">Utah</option>
										<option value="VT">Vermont</option>
										<option value="VA">Virginia</option>
										<option value="WA">Washington</option>
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option>
										<option value="WY">Wyoming</option></select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="job_cand_zip" class="col-6 col-form-label">
                                    Zip
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" maxlength="5" type="number" value="" id="job_cand_zip">
                                </div>
                            </div>
							</div>
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateallJob(tj.editJobTarget);">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="add_job_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Add Job
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
                                <input type="hidden" name="add_job_input_location" id="add_job_input_location" value="" />
                                <div class="form-group m-form__group">
                                  	<label for="add_job_input_position">
                                        Creates a job posting that uses the JobAlarm Mobile Apply feature for the selected location.
                                    </label>
                                    <input type="text" class="form-control m-input" id="add_job_input_position" name="add_job_input_position" placeholder="Enter position title">
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
                    <button type="button" class="btn btn-primary" onclick="tj.addJob();">
                        Save
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
									$smsUsers = Config::get('db')->get_results("select `id`,`last_name`,`first_name` from `users` where `accountId`='{$accountId}' and `role`<6 order by `last_name`,`first_name` asc");
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