<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

if(intval($_SESSION['account']['role'])==100){
	$hide = '';
	$disable= '';
}else{
	$hide = 'hidden';
	$disable = 'disabled';
}


?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Accounts
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
						
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right" <?php echo $hide; ?>>
						
                                        <button type="button" class="btn btn-success" data-target="#addAccount" data-toggle="modal">
                                            Add Account
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
				
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="accountsTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="40%" data-priority="1">Account</th>
                                    <th width="20%" data-priority="2">City</th>
									 <th width="10%" data-priority="3">State</th>
                                    <th width="30%" data-priority="4">Label</th>
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
	
    <!--begin::Modal-->
<div class="modal fade" id="addAccount" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-8">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">New Account</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="accountForm_add" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="accountId_add" type="hidden" value="" />
						<div class="title">
				 <h4>Account Details</h4>
				 </div>
                           	<div class="form-group">
                                <label for="accountName_add" class="col-sm col-form-label">
                                    Account Name:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountName_add">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="accountAddress_add" class="col-sm col-form-label">
                                    Street Address:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountAddress_add" >
                                </div>
                            </div>
							 <div class="form-group">
                                <label for="accountCity_add" class="col-sm col-form-label">
                                    City:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountCity_add" >
                                </div>
                            </div>
							 <div class="form-group">
                                <label for="accountState_add" class="col-sm col-form-label">
                                    State:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountState_add" >
                                </div>
                            </div>
							<div class="form-group">
                                <label for="accountZip_add" class="col-sm col-form-label">
                                    Zip:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountZip_add" >
                                </div>
                            </div>
							<div class="form-group">
                                <label for="accountImage_add" class="col-sm col-form-label">
                                    Account Image:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountImage_add">
                                </div>
                            </div>
							<hr></hr>
						 <div class="title">
				 <h4>Contact Details</h4>
				 </div>
							<div class="form-group">
                                <label for="accountContactName_add" class="col-sm col-form-label">
                                    Primary Contact:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountContactName_add" >
                                </div>
                            </div>
							<div class="form-group">
                                <label for="accountContactEmail_add" class="col-sm col-form-label">
                                    Email:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountContactEmail_add" >
                                </div>
                            </div>
							<div class="form-group">
                                <label for="accountContactPhone_add" class="col-sm col-form-label">
                                    Phone:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountContactPhone_add" >
                                </div>
                            </div>
						<hr></hr>
						 <div class="title">
				 <h4>Private Label</h4>
				 </div>
							<div class="form-group">
                               	<label for="accountLabel_add" class="col-sm col-form-label">Label:
								</label>
                                <div class="col-8">
                                    <select id="accountLabel_add" type="select" class="form-control" >
									<option value="0">SELECT</option>						
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveLabel` order by labelName ASC");
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['labelName'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
							<hr></hr>
				<div class="title">
				 <h4>Pay Period Calculations</h4>
				 </div>		
							<div class="form-group">
							    <label for="accountPayPeriod_add" class="col-sm col-form-label">Pay Period Type:</label>
								<div class="col-8">
                                <select type="select" class="form-control" id="accountPayPeriod_add">
								<option value="0">Select</option>
								<option value="7">Weekly</option>
								<option value="14">Bi-Weekly</option>
								<option value="15">Bi-Monthly</option>
								</select>
							</div>
							</div>
							<div class="form-group">
							    <label for="accountPayFirst_add" class="col-sm col-form-label">Pay Period Reference Date:</label>
								<div class="col-8">
                                <input type="date" class="form-control number" id="accountPayFirst_add">
							</div>
							</div>
			
						
							
			       </div>
				</form>
					<hr></hr>
				<div class="pull-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addAccount();">Save</button>
                </div>
                    
					
						 
			       
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>
	    <!--begin::Modal-->
	

    <!--end::Modal-->

    <!--begin::Modal-->
<div class="modal fade" id="accountSettings" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-8">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Edit Account Information</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="accountForm" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="accountId" type="hidden" value="" />
						<div class="title">
				 <h4>Account Details</h4>
				 </div>
				 <div class="col-md-12">
				 <div class="form-group">
                               	<label for="accountEnterprise" class="col-sm col-form-label">Enterprise:
								</label>
                                <div class="col-6">
                                    <select id="accountEnterprise" type="select" class="form-control" <?php echo $disable; ?>>
									<option value="0">Non-Enterprise</option>
																
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveEnterprise` order by `name` ASC");
								foreach($dbDir as $d) 
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
                                <label for="accountName" class="col-sm col-form-label" disabled>
                                    Account Name:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountName" disabled>
                                </div>
                            </div>
                             <div class="form-group">
							<div class="row">				 
							<div class="col-6">
                                <label for="accountAddress" class="col-sm col-form-label">
                                    Street Address:
                                </label>
                                <div class="col-12">
                                    <input class="form-control m-input" type="text" id="accountAddress" >
                                </div>
                            </div>
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							 <div class="col-4">
                                <label for="accountCity" class="col-sm col-form-label">
                                    City:
                                </label>
                                <div class="col-12">
                                    <input class="form-control m-input" type="text" id="accountCity" >
                                </div>
                            </div>
							 <div class="col-4">
                                <label for="accountState" class="col-sm col-form-label">
                                    State:
                                </label>
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" id="accountState" >
                                </div>
                            </div>
							<div class="col-4">
                                <label for="accountZip" class="col-sm col-form-label">
                                    Zip:
                                </label>
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" id="accountZip" >
                                </div>
                            </div>
							</div>
							</div>
							<div class="form-group" <?php echo $hide; ?>>
                                <label for="accountImage" class="col-sm col-form-label">
                                    Account Image:
                                </label>
                                <div class="col-4">
                                    <input class="form-control m-input" type="text" id="accountImage" >
                                </div>
                            </div>
							<hr></hr>
						 <div class="title">
				 <h4>Contact Details</h4>
				 </div>
							<div class="form-group">
                                <label for="accountContactName" class="col-sm col-form-label">
                                    Primary Contact:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountContactName" >
                                </div>
                            </div>
							<div class="form-group">
							<div class="row">
							<div class="col-6">
                                <label for="accountContactEmail" class="col-sm col-form-label">
                                    Email:
                                </label>
                                <div class="col-12">
                                    <input class="form-control m-input" type="text" id="accountContactEmail" >
                                </div>
                            </div>
							<div class="col-6">
                                <label for="accountContactPhone" class="col-sm col-form-label">
                                    Phone:
                                </label>
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" id="accountContactPhone" >
                                </div>
                            </div>
							</div>
							</div>
						<hr></hr>
						 <div class="title" hidden>
				 <h4>Private Label</h4>
				 </div>
							<div class="form-group" hidden>
                               	<label for="accountLabel" class="col-sm col-form-label">Label:
								</label>
                                <div class="col-6">
                                    <select id="accountLabel" type="select" class="form-control" <?php echo $disable; ?>>
																
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveLabel`");
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['labelName'];?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
						<hr></hr>	
						<div class="title">
						 <h4>Productivity Color Indicators</h4>
						 </div>
						 <div class="form-group">
                               	<label for="dashColor" class="col-sm col-form-label">Change Color to Red When:
								</label>
                                <div class="col-8">
                                    <select type="select" class="form-control" id="dashColor">
								<option value="0">Under Productive</option>
								<option value="2">Over Productive</option>
								<option value="1">Either Under or Over Productive</option>
								</select>
                            </div>
							</div>
							
							<hr></hr>
				<div class="title">
				 <h4>Pay Period Calculations</h4>
				 </div>		
							<div class="form-group">
							<div class="row">
							<div class="col-6">
							    <label for="accountPayPeriod" class="col-sm col-form-label">Pay Period Type:</label>
								<div class="col-8">
                                <select type="select" class="form-control" id="accountPayPeriod">
								<option value="0">Select</option>
								<option value="7">Weekly</option>
								<option value="14">Bi-Weekly</option>
								<option value="15">Bi-Monthly</option>
								</select>
							</div>
							</div>
							<div class="col-6">
							    <label for="accountPayFirst" class="col-sm col-form-label">Pay Period Reference Date:</label>
								<div class="col-8">
                                <input type="date" class="form-control number" id="accountPayFirst">
							</div>
							</div>
							</div>
							</div>
			
						
							
			       </div>
				</form>
					<hr></hr>
				<div class="pull-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateAccount();">Save</button>
                </div>
                    
					
						 
			       
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>
  
</div>	

