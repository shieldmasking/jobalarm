<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}

if(intval($_SESSION['account']['role'])==11){
	$hide = '';
	$disable= '';
}else{
	$hide = '';
	$disable = 'disabled';
}

if(intval($_SESSION['account']['accountId'])==22){
	$demohide = '';
}else{
	$demohide = 'hidden';
}





?>
<!--
<style>
body {
color: #000;
overflow-x: hidden;
height: 100%;
background-repeat: no-repeat
}
.container {
margin: 200px auto
}
fieldset {
display: none
}
fieldset.show {
display: block
}
select:focus,
input:focus {
-moz-box-shadow: none !important;
-webkit-box-shadow: none !important;
box-shadow: none !important;
border: 1px solid #2196F3 !important;
outline-width: 0 !important;
font-weight: 400
}
button:focus {
-moz-box-shadow: none !important;
-webkit-box-shadow: none !important;
box-shadow: none !important;
outline-width: 0
}
.tabs {
margin: 2px 5px 0px 5px;
padding-bottom: 10px;
cursor: pointer
}
.tabs:hover,
.tabs.active {
border-bottom: 1px solid #2196F3
}
a:hover {
text-decoration: none;
color: #1565C0
}
.box {
margin-bottom: 10px;
border-radius: 5px;
padding: 10px
}
.modal-backdrop {
background-color: #64B5F6
}
.line {
background-color: #CFD8DC;
height: 1px;
width: 100%
}
@media screen and (max-width: 768px) {
.tabs h6 {
font-size: 12px
}
}
</style>

<script>
$(document).ready(function(){
$(".tabs").click(function(){
$(".tabs").removeClass("active");
$(".tabs h6").removeClass("font-weight-bold");
$(".tabs h6").addClass("text-muted");
$(this).children("h6").removeClass("text-muted");
$(this).children("h6").addClass("font-weight-bold");
$(this).addClass("active");
current_fs = $(".active");
next_fs = $(this).attr('id');
next_fs = "#" + next_fs + "1";
$("fieldset").removeClass("show");
$(next_fs).addClass("show");
current_fs.animate({}, {
step: function() {
current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({
'display': 'block'
});
}
});
});
});
</script>

-->

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Account Settings
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">
                        <div class="col-sm-6"></div>
						
						<div class="col-sm-6" hidden>
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right" <?php echo $hide; ?>>
						
                                        <button type="button" class="btn btn-success" data-target="#addAccount" data-toggle="modal">
                                            Add Account
                                        </button>   
										
					 </div>
					 <!--<button type="button" class="btn btn-primary" onclick="tj.updateEpic();">Data</button>-->
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
                                    <th width="30%" data-priority="4">Consultant</th>
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
<div class="modal fade" id="addAccount" tabindex="-1" role="basic" >
         <div class="col-sm-8">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h4 class="modal-title">New Account</h4>
					<button type="button" class="close" data-dismiss="modal" ></button>
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
							<!--
							<div class="form-group" hidden>
                                <label for="accountImage_add" class="col-sm col-form-label">
                                    Account Image:
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="accountImage_add">
                                </div>
                            </div>
							-->
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
								<div class="row">
                                <div class="col-8">
                                    <select id="accountLabel_add" type="select" class="form-control" >
									<option value="0">SELECT</option>						
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveLabel` order by labelName ASC");
								foreach($dbDir as $d) {
								if($d['id']==1){
									$labelName = "ProductiveRN";
								}else{
									$labelName = $d['labelName'];
								}
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $labelName;?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
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
							    <label for="accountPayFirst_add" class="col-sm col-form-label">First Day of Any Pay Period:</label>
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
<div class="modal fade" id="accountSettings" tabindex="-1" role="basic" >
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
		
                <div class="modal-header bg-info">
				  <h5 class="modal-title" style="color:white"><strong>ACCOUNT INFO</strong>
				  </h5>
				  				  				
				<button type="button" class="close" data-dismiss="modal" ></button>
                </div>
			
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="accountForm" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="accountId" type="hidden" value="" />
					<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Account Details </h5>
						</div>
				 <div class="form-group">
				 <div class="bg-secondary">
					<div class="row">		
                           	<div class="col-md">
                                <label for="accountName" class="col-form-label">
                                    Account Name:
                                
                                <div class="col-md">
                                    <input class="form-control m-input" type="text" id="accountName" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
                            <div class="col-md">
                               	<label for="accountEnterprise" class="col-form-label">Enterprise:
								
                               <div class="col-md">
                                    <select id="accountEnterprise" type="select" class="form-control" disabled>
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
										</label>
                            </div>
							</div>
							<div class="row">				 
							<div class="col-md">
                                <label for="accountAddress" class="col-form-label">
                                    Street Address:
                                
                                <div class="col-md">
                                    <input class="form-control m-input" type="text" id="accountAddress" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
							</div>
							
							<div class="row">
							 <div class="col-md">
                                <label for="accountCity" class="col-form-label">
                                    City:
                                
                                <div class="col-12">
                                    <input class="form-control m-input" type="text" id="accountCity" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
							 <div class="col-md">
                                <label for="accountState" class="col-form-label">
                                    State:
                               
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" id="accountState" <?php echo $disable; ?>>
                                </div>
								 </label>
                            </div>
							<div class="col-md">
                                <label for="accountZip" class="col-form-label">
                                    Zip:
                                
                                <div class="col-6">
                                    <input class="form-control m-input" type="text" id="accountZip"<?php echo $disable; ?> >
                                </div>
								</label>
                            </div>
							</div>
					<!--
			
							<div hidden>
                                <label for="accountImage" class="col-form-label">
                                    Account Image:
                                </label>
                                <div class="col-4">
                                    <input class="form-control m-input" type="text" id="accountImage" >
                                </div>
								-->
                            </div>
							</div>
							</div>
				<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Contact Details </h5>
						</div>
						<div class="form-group">
						<div class="bg-secondary">
							<div class="row">
							<div class="col-md">
                                <label for="accountContactName" class="col-form-label">
                                    Contact Name:
                                
                                <div class="col-md">
                                    <input class="form-control m-input" type="text" id="accountContactName" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
							</div>
							<div class="row">
							<div class="col-md">
                                <label for="accountContactEmail" class="col-form-label">
                                    Email:
                                
                                <div class="col-md">
                                    <input class="form-control m-input" type="text" id="accountContactEmail" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
							<div class="col-md">
                                <label for="accountContactPhone" class="col-form-label">
                                    Phone:
                                
                               <div class="col-md">
                                    <input class="form-control m-input" type="text" id="accountContactPhone" <?php echo $disable; ?>>
                                </div>
								</label>
                            </div>
							</div>
							</div>
							</div>
							<!--<div id="label" <?php echo $hide; ?>>-->
				<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Custom Labels </h5>
						</div>
						 <div class="bg-secondary">
							<div class="form-group">
							 
                               	<label for="accountLabel" class="col-6 form-label" <?php echo $demohide; ?>>Label:
							
                                
                                    <select id="accountLabel" type="select" class="form-control">
																
								<?php
								$dbDir = Config::get('db')->get_results("SELECT * from `productiveLabel` where `active`=1");
								foreach($dbDir as $d) {
								$labelName = $d['labelName'];
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $labelName;?> </option>
								<?php 
								} 
								?>
										</select>
                           
							</label>
						
							</div>
							<div class="form-group">
							<label for="dashTerm" class="col-4 form-label">
                                    Dashboard Label:
                                
                                
                                    <input class="form-control" type="text" id="dashTerm" <?php echo $disable; ?>>
                               
								</label>
                                
							
						
                                <label for="prodTerm" class="col-6 form-label">
                                    Productivity Label:
                                
                             
                                    <input class="form-control" type="text" id="prodTerm" <?php echo $disable; ?>>
                                
								</label>
						
                            
								</div>
								<div class="form-group">
						
                                    <label for="hrsTerm" class="col-4 form-label">
                                    WHPUOS Label:
                                
                                
                                    <input class="form-control" type="text" id="hrsTerm" <?php echo $disable; ?>>
                                
								</label>
							
						
                                <label for="easTerm" class="col-6 form-label">
                                    Epic Score Label:
                                
                             
                                    <input class="form-control" type="text" id="easTerm" <?php echo $disable; ?>>
                                
								</label>
						
                                
								</div>
								
								<div class="row">
							<div class="col-6">
                               	<label for="varMessage" class="col-sm col-form-label">Custom Variance Message
								
                                <textarea class="form-control" rows="3" id="varMessage" <?php echo $disable; ?>></textarea>
								</label>
                            </div>
							</div>
							</div>
							
							<div class="p-3 mb-2 bg-info" hidden>
						<h5 class="modal-title" style="color:white">Productivity Settings </h5>
						</div>
						 <div class="bg-secondary">
						 <div class="form-group" hidden>
							<div class="row">
							<div class="col-6">
                               	<label for="prodCalc" class="col-sm col-form-label">Variance Calculations:
								
                                <select type="select" class="form-control" id="prodCalc" <?php echo $disable; ?>>
								<option value="-1">Positive Values = Under Staffed</option>
								<option value="1">Negative Values = Under Staffed</option>
								</select>
								</label>
                            </div>
							<div class="col-6" hidden>
                               	<label for="rnvarianceLive" class="col-sm col-form-label">In-Shift RN Variance View:
								</label>
                                <select type="select" class="form-control" id="rnvarianceLive" <?php echo $disable; ?>>
								<option value="0">Always Show</option>
								<option value="1">Only Show After Record Submission</option>
								</select>
                            </div>
							</div>
						</div>
						</div>
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Additional Settings </h5>
						</div>
						 <div class="bg-secondary">
						 <div class="form-group">
							<div class="row">
							<div class="col-md">
                               	<label for="defaultSort" class="col-sm col-form-label">Default Dashboard Sort
								
                                <select type="select" class="form-control" id="defaultSort" <?php echo $disable; ?>>
								<option value="0">Unit Name</option>
								<option value="1">Variance</option>
								</select>
								</label>
                            </div>
							<div class="col-md" hidden>
                               	<label for="useEpic" class="col-sm col-form-label">Use Epic Acuity Score
								
                                
                                    <select type="select" class="form-control" id="useEpic" disabled>
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
								</label>
                            </div>
								<div class="col-md">
                               	<label for="updateLevel" class="col-sm col-form-label">Update Unit Settings
								
                                
                                    <select type="select" class="form-control" id="updateLevel" <?php echo $disable; ?>>
								<option value="6">Manager</option>
								<option value="7">Director</option>
								<option value="11">Admin</option>
								<option value="12">Super Admin</option>
								</select>
								</label>
                            </div>
							<div class="col-md" hidden>
                               	<label for="acctEscalation" class="col-sm col-form-label">Use Escalations
								
                                <select type="select" class="form-control" id="acctEscalation" <?php echo $disable; ?>>
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
								</label>
                            </div>
							</div>
							<div class="row">
							<div class="col-md" hidden>
                               	<label for="housealert" class="col-sm col-form-label">House Text Alerts for RN or Grid Variance
								
                                <select type="select" class="form-control" id="housealert" <?php echo $disable; ?>>
								<option value="99.00">None</option>
								<option value="1.00">+/- 1</option>
								<option value="1.50">+/- 1.5</option>
								<option value="2.00">+/- 2</option>
								<option value="2.50">+/- 2.5</option>
								<option value="3.00">+/- 3</option>
								<option value="3.50">+/- 3.5</option>
								<option value="4.00">+/- 4</option>
								<option value="4.50">+/- 4.5</option>
								<option value="5.00">+/- 5</option>
								</select>
								</label>
                            </div>
								<div class="col-md">
                               	<label for="houseLogins" class="col-sm col-form-label">Show House Logins on Compliance Report
								
                                <select type="select" class="form-control" id="houseLogins" <?php echo $disable; ?>>
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
								</label>
                            </div>
							<div class="col-md">
                               	<label for="emailTime" class="col-sm col-form-label">Compliance Report Email Time (Eastern Time)
								
                                <select type="select" class="form-control" id="emailTime" <?php echo $disable; ?>>
								<option value="00:00:00">Do Not Send</option>
								<option value="06:00:00">06:00</option>
								<option value="07:00:00">07:00</option>
								<option value="08:00:00">08:00</option>
								<option value="09:00:00">09:00</option>
								<option value="10:00:00">10:00</option>
								<option value="11:00:00">11:00</option>
								<option value="12:00:00">12:00</option>
								<option value="13:00:00">13:00</option>
								<option value="14:00:00">14:00</option>
								<option value="15:00:00">15:00</option>
								<option value="16:00:00">16:00</option>
								<option value="17:00:00">17:00</option>
								<option value="18:00:00">18:00</option>
								<option value="19:00:00">19:00</option>
								<option value="20:00:00">10:00</option>
								<option value="21:00:00">21:00</option>
								</select>
								</label>
                            </div>
							
							<div class="col-md" hidden>
                               	<label for="trainingVids" class="col-sm col-form-label">Training Videos
								
                                <select type="select" class="col-4 form-control" id="trainingVids" <?php echo $disable; ?>>
								<option value="0">Off</option>
								<option value="1">On</option>
								</select>
								</label>
                            </div>
							
							</div>
							<div class="row">
							<div class="col-md" >
                               	<label for="bedCount" class="col-sm col-form-label">Bed Count (for NEDOCS)
								
                                <input type="number" class="form-control" id="bedCount" />
							
								</label>
                            </div>
									<div class="col-md">
                               	<label for="useSafety" class="col-sm col-form-label">Use Safety
								
                                <select type="select" class="form-control" id="useSafety">
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
								</label>
                            </div>
								<div class="col-md">
                               	<label for="safetyTypes" class="col-sm col-form-label">Safety Types
								
                                <select type="select" class="form-control" id="safetyTypes">
								<option value="0">Standard</option>
								<option value="1" disabled>Custom</option>
								<option value="2">None</option>
								</select>
								</label>
                            </div>
							<div class="col-md" hidden>
                               	<label for="usecrashcarts" class="col-sm col-form-label">Audit Logs (ie. Crash Carts)
								
                                <select type="select" class="col-4 form-control" id="usecrashcarts" >
								<option value="0">No</option>
								<option value="1">Yes</option>
								</select>
								</label>
                            </div>
							<div class="col-md" hidden>
                               	<label for="crashcarttime" class="col-sm col-form-label">Audit Log Deadline
								
                                <select type="select" class="col-4 form-control" id="crashcarttime" >
								<option value="0">Select</option>
								<option value="07">07:00</option>
								<option value="08">08:00</option>
								<option value="09">09:00</option>
								<option value="10">10:00</option>
								<option value="11">11:00</option>
								<option value="12">12:00</option>
								<option value="13">13:00</option>
								<option value="14">14:00</option>
								<option value="15">15:00</option>
								<option value="16">16:00</option>
								<option value="17">17:00</option>
								<option value="18">18:00</option>
								<option value="19">19:00</option>
								
								</select>
								</label>
                            </div>
							
							</div>
							
							
						</div>
						</div>
				<div id="pay">
				<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Pay Period </h5>
						</div>	
 <div class="bg-secondary">						
							<div class="form-group">
							<div class="row">
							<div class="col-6">
							    <label for="accountPayPeriod" class="col-sm col-form-label">Pay Period Type:</label>
								<div class="col-8">
                                <select type="select" class="form-control" id="accountPayPeriod">
								<option value="0">Select</option>
								<option value="7">Weekly</option>
								<option value="14">Bi-Weekly</option>
								<option value="15" hidden>Bi-Monthly</option>
								</select>
							</div>
							</div>
							<div class="col-6">
							    <label for="accountPayFirst" class="col-sm col-form-label">First Day of a Pay Period:</label>
								<div class="col-8">
                                <input type="date" class="form-control number" id="accountPayFirst">
							</div>
							</div>
							</div>
							</div>
							</div>
</div>							
			     
				</form>
				</div>	
				<div class="modal-footer bg-info">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateAccount();" <?php echo $disable; ?>>Save</button>
                </div>
                    
					
						 
			       
            
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>

  
</div>	




