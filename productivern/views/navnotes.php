<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['id']);
$primaryType = $_SESSION['account']['primaryType'];
$deptCount = intval($_SESSION['account']['deptCount']);

if(intval($role)>7){
		$userx = "`accountId`={$accountId} ";
		$userp = "p.accountId={$accountId} AND p.statusId<4 ";
		$userm = "m.accountId={$accountId} ";
}else if(intval($deptCount)>1 || intval($role)==7){
		$userx = "`accountId`={$accountId} AND `deptId` in (SELECT `deptId` FROM `productiveDeptXref` where `userId` ={$userId}) ";
		$userp = "p.accountId={$accountId} AND p.statusId<4 AND p.pnnId in (SELECT `userId` FROM `productiveDeptXref` where `deptId` IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId` ={$userId}))";
		$userm = "m.accountId={$accountId} AND m.pnnId in (SELECT `userId` FROM `productiveDeptXref` where `deptId` IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId` ={$userId}))";
}else{
		$userx = "`accountId`={$accountId} AND `deptId` in (SELECT `deptId` FROM `productiveDeptXref` where `userId` ={$userId}) ";
		$userp = "p.accountId={$accountId} AND p.statusId<4 AND p.pnnId={$userId}";
		$userm = "m.accountId={$accountId} AND m.pnnId ={$userId}";
}


$mfmdb = Config::get('db')->get_results("SELECT m.*, t.typeDesc FROM `periMFM` m LEFT JOIN `periDoctorTypes` as t on t.id=m.drType where $userm GROUP BY m.id ORDER BY m.lastName,m.firstName ASC");
$facilitiesdb = Config::get('db')->get_results("SELECT * FROM `periFacilities` where $userx OR `deptId`=0 GROUP BY `id` ORDER BY `facilityName` ASC");
$mdxdb = Config::get('db')->get_results("SELECT * FROM `periMDX` where `accountId`={$accountId} GROUP BY `id` ORDER BY `mdxDesc` ASC");
$fdxdb = Config::get('db')->get_results("SELECT * FROM `periFDX` where `accountId`={$accountId} GROUP BY `id` ORDER BY `fdxDesc` ASC");
$navdb = Config::get('db')->get_results("SELECT * FROM `productiveUser` where `accountId`={$accountId} AND `role`<7 GROUP BY `id` ORDER BY `last_name`,`first_name` ASC");

$dbDir = Config::get('db')->get_results("SELECT p.*, DATE_FORMAT(p.dob, '%m-%d-%Y') as bdate, m.lastName as mfmName from `periUser` p left outer join `periMFM` as m on m.id=p.mfmId where $userp GROUP BY p.id ORDER BY p.lastName, p.firstName ASC");
								





?>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<script>
$("#navnoteType").change(function () {
	var physType = $('#navnoteType').val();
	
	if(physType == 1){
	document.getElementById("navPhys").style.display='none';
	$('#addnavigatorTitle').html('New Navigator Note');
    }else if(physType == 2){
	document.getElementById("navPhys").style.display='none';
	$('#addnavigatorTitle').html('New MFM Note');
    }else if(physType == 3){
	document.getElementById("navPhys").style.display='';
	$('#addnavigatorTitle').html('New OB Note');
    }else{
	document.getElementById("navPhys").style.display='';	
	$('#addnavigatorTitle').html('New Consult Note');
	}

})

$("#editnoteType5").change(function () {
	var editphysType3 = $('#editnoteType5').val();
	
	if(editphysType3 == 1 || editphysType3 == 2){
	document.getElementById("editsummaryPhys5").style.display='none';
    }else{
	document.getElementById("editsummaryPhys5").style.display='';	
	}

})


</script>

<html lang="en">
<!-- BEGIN: Subheader -->
<div class="m-subheader" id="navHeader" >
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Patient Notes
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content" id="navList">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				 <div class="row">

			<div class="col-sm-6">
			<div class="col-4">
			<select  class="bs-select form-control input-sm pull-left" id="navnoteFilter" name="navnoteFilter" onchange="tj.navnoteSelect();">
			<option value="1">Current</option>
			<option value="6">Delivered</option>
			<option value="2">Discharged</option>
			<option value="3">Surgical</option>
			<option value="4">Archived</option>
			<option value="0">All</option>
			<option value="5">Unassigned</option>
			</select>&nbsp;
			</div>
			</div>
			
<div class="col-sm-6">
							<div class="pull-right">
								<div style="text-align:right">
                                                           
                                        <button type="button" class="btn btn-success" onclick="tj.addnavNote();">
                                            New Note
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>



</div>
                     
				
					<div class="col-xl-12">
                    <div class="row">
					<input id="primarynavType" type="hidden" value="<?php echo $primaryType ?>" />
                        <table id="NAVTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="15%" data-priority="1">Patient (DOB)</th>
									<th width="15%" data-priority="5">DR.</th>
									<th width="10%" data-priority="4">Type</th>
									<th width="10%" data-priority="6">Updated</th>
									<Th width="10%" data-priority="7">NAV</th>
									<th width="10%" data-priority="8">File</th>
									<th width="30%" data-priority="2">Notes</th>
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
 	<div class="modal fade" id="addnavNote" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-light">
                    <h4 class="modal-title"><span id="addnavigatorTitle"></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				<form id="navnoteform" name="navnoteform" method="post" enctype="multipart/form-data" class="m-form m-form--fit m-form--label-align-right">   
				<input type="date" id="navnotesdate" hidden />
				<input type="date" id="navnoteedd" hidden />				
			   <div class="modal-body">
                    <!--begin::Form-->
                    <!--<form id="add_navNote" class="m-form m-form--fit m-form--label-align-right">-->
                     <input type="hidden" name="update_profile" value="1" />
						
						<div class="m-portlet__body">
						
						<div class="form-group">
						<div class="row">
						<div class="col-md-9">
						<label for="navnoteType" class="label">
						Note Type:</label>
						
                        <select id="navnoteType" class="form-control">
						<option value="13">Anesthesia</option>
						<option value="12">Cardiology</option>
						<option value="9">Diabetes Education</option>
						<option value="6">Family Care Conference</option>
						<option value="3">MFM</option>
						<option value="1">Navigator</option>
						<option value="5">Neonatologist</option>
						<option value="15">NICU</option>
						<option value="2">OB/GYN</option>
						<option value="14">Oncology</option>
						<option value="8">Palliative Care</option>
						<option value="11">Radiology/MRI</option>
						<option value="7">Social Work</option>
						<option value="4">Surgery</option>
						<option value="10">Urology</option>
						<option value="20">Other</option>
						</select>
						                       
						</div>
						</div>
						</div>
						
							<div class="form-group" >
							<div class="row">
							<div class="col-md-12">
                               	<label for="addnavnotePt" class="col-form-label">Patient:
								</label>
                                
                                    <select id="addnavnotePt" onchange="tj.getnavPt();" class="form-control">
									<option value="">Select</option>
								<?php						
								foreach($dbDir as $d) 
								{ 
								?>
								<option value="<?php echo $d['id'];?>"><?php echo $d['lastName'] . ", " . $d['firstName'] . " (DOB: " . $d['bdate'] . ", MFM: " . $d['mfmName'] . ")";?> </option>
								<?php 
								} 
								?>
										</select>
                            </div>
							</div>
					</div>
					<div class="form-group" id="navadddob">
					<div class="row">
					<div class="col-md-9">
                                <label for="addnavdob" class="label" style="color:red">
                                   DOB:
                                </label>
                                
								<input type="date" class="form-control" id="addnavdob" />		
                                
                            
						</div>
						</div>
						</div>
					<div class="form-group" id="mfmnavadd">
					<div class="row">
					<div class="col-md-9">
                                <label for="addnavmfm" class="label" style="color:red">
                                   Primary MFM:
                                </label>
                                
								
										<select id="addnavmfm" class="form-control">
										<option value="0">Select MFM</option>
										<?php
								foreach($mfmdb as $mf) 
								{ 
								?>
								<option value="<?php echo $mf['id'];?>"><?php echo $mf['lastName'] .", " . $mf['firstName'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                                
                            
						</div>
						</div>
						</div>
						<div class="form-group" id="addgravidanav">
					<div class="row">
					<div class="col-md-6">
						<label for="addnavgravida" class="label" style="color:red">
                                   Gravida:
                                </label>
						
							
							
                                <input class="form-control" type="number" min="0" id="addnavgravida">
                        </div>
						<div class="col-md-6">
						<label for="addnavpara" class="label" style="color:red">
                                   Para:
                                </label>
						
							
							
                                <input class="form-control" type="number" min="0" id="addnavpara">
                        </div>
					
						</div>
						</div>
					<div class="form-group" id="addfacilitynav">
					<div class="row">
					<div class="col-md-9">
                                <label for="addnavfacility" class="label" style="color:red">
                                   Facility:
                                </label>
                               
									
										<select id="addnavfacility" class="form-control">
										<option value="0">Select Facility</option>
										<?php
								foreach($facilitiesdb as $fa) 
								{ 
								?>
								<option value="<?php echo $fa['id'];?>"><?php echo $fa['facilityName'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                               
                          
						</div>
					</div>
					</div>
					<div class="form-group" id="addmdxnav">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="addnavmdx" class="label" style="color:red">
                                   MDX(s):
                                </label>
                              
									
										<select id="addnavmdx" class="form-control" multiple>
										
									
										<?php
								foreach($mdxdb as $mx) 
								{ 
								?>
								<option value="<?php echo $mx['id'];?>"><?php echo $mx['mdxDesc'];?> </option>
								<?php 
								} 
								?>									
										</select>
										 
                     
                         
						</div>
						</div>
						</div>
						<div class="form-group" id="addfdxnav">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="addnavfdx" class="label" style="color:red">
                                   FDX(s):
                                </label>
                               
								
										<select id="addnavfdx" class="form-control" multiple>
								
										
										<?php
								foreach($fdxdb as $fx) 
								{ 
								?>
								<option value="<?php echo $fx['id'];?>"><?php echo $fx['fdxDesc'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
             
                            
						</div>
						</div>
						</div>
						<div class="form-group" id="adddelnav">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="addnavdel" class="label" style="color:red">
                                   Previous Delivery:
                                </label>
                              
									
										<select id="addnavdel" class="form-control">
										<option value="0">Select</option>
										<option value="1">C-Section</option>
										<option value="2">Natural</option>
																	
										</select>
										 
                     
                         
						</div>
						</div>
						</div>
					
					<div class="form-group" id="navPhys">
						<div class="row">
						<div class="col-md-9">
							
							<label for="navphysician" class="label" style="color:red">
                                   Physician:
                                </label>
                                <input class="form-control" type="text" id="navphysician">
                        </div>
						</div>
						</div>
						
					<div class="form-group">
						<div class="row">
						<div class="col-md-12">
							
							<label for="navNote" class="label">
                                   Note:
                                </label>
                                <textarea class="form-control" maxlength="1000" rows="4" id="navNote"></textarea>
                        </div>
						</div>
						</div>
									 
					 
					<label for="addnavfile">Upload File:</label> 
		            <input class="form-control-file" type="file" id="addnavfile" name="addnavfile"/>
                    
					
                </div>
				</div>
				
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button name="btnsubmit" type="submit" value="Update" class="btn btn-primary">Add</button>
                </div>
            </form>
			
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>
	    <!--begin::Modal-->
 	<div class="modal fade" id="editsummaryNote5" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-light">
                    <h4 class="modal-title"><span id="editsummarytitle5"></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_summaryNote5" name="edit_summaryNote5" method="post" enctype="multipart/form-data" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						
						<input id="editsummaryuserId5" type="hidden" value="<?php $userId;?>" />
						<input id="editsummarynoteId5" type="hidden" value="" />
						<input id="summarynotefirst5" type="hidden" value="" />
						<input id="summarynotelast5" type="hidden" value="" />
						<input id="summarynotedob5" type="hidden" value="" />
						<input id="editsummaryptId5" type="hidden" value="" />
						<input id="summarynoteedd5" type="date" hidden />
						<input id="summarynotesdate5" type="date" hidden />
						
						<div class="form-group">
						<div class="row">
						<div class="col-md-6">
						<label for="editnoteType5" class="label">
						Note Type:</label>
						
                        <select id="editnoteType5" class="form-control">
						<option value="13">Anesthesia</option>
						<option value="12">Cardiology</option>
						<option value="9">Diabetes Education</option>
						<option value="6">Family Care Conference</option>
						<option value="3">MFM</option>
						<option value="1">Navigator</option>
						<option value="5">Neonatologist</option>
						<option value="15">NICU</option>
						<option value="2">OB/GYN</option>
						<option value="14">Oncology</option>
						<option value="8">Palliative Care</option>
						<option value="11">Radiology/MRI</option>
						<option value="7">Social Work</option>
						<option value="4">Surgery</option>
						<option value="10">Urology</option>
						<option value="20">Other</option>
						</select>
						                       
						</div>
						</div>
						</div>
						
							
						<div class="form-group" >
							<div class="row">
							<div class="col-md-12">
                               	<label for="summarynotePatient5" class="col-form-label">Patient:
								</label>
                                
								<input class="form-control" type="text" id="summarynotePatient5" disabled>
                            </div>
					</div>
					</div>
					<div class="form-group" id="editdob5">
					<div class="row">
					<div class="col-md-9">
                                <label for="editptdob5" class="label" style="color:red">
                                   DOB:
                                </label>
                                
								<input type="date" class="form-control" id="editptdob5" />		
                                
                            
						</div>
						</div>
						</div>
					<div class="form-group" id="editmfm5">
					<div class="row">
					<div class="col-md-9">
                                <label for="editptmfm5" class="label" style="color:red">
                                   Primary MFM:
                                </label>
                                
								
										<select id="editptmfm5" class="form-control">
										<option value="0">Select MFM</option>
										<?php
								foreach($mfmdb as $mf) 
								{ 
								?>
								<option value="<?php echo $mf['id'];?>"><?php echo $mf['lastName'] .", " . $mf['firstName'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                                
                            
						</div>
						</div>
						</div>
						
						<div class="form-group" id="editgravida5">
					<div class="row">
					<div class="col-md-6">
						<label for="editptgravida5" class="label" style="color:red">
                                   Gravida:
                                </label>
						
							
							
                                <input class="form-control" type="number" min="0" id="editptgravida5">
                        </div>
						<div class="col-md-6">
						<label for="editptpara5" class="label" style="color:red">
                                   Para:
                                </label>
						
							
							
                                <input class="form-control" type="number" min="0" id="editptpara5">
                        </div>
					
						</div>
						</div>
					<div class="form-group" id="editfacility5">
					<div class="row">
					<div class="col-md-9">
                                <label for="editptfacility5" class="label" style="color:red">
                                   Facility:
                                </label>
                               
									
										<select id="editptfacility5" class="form-control">
										<option value="0">Select Facility</option>
										<?php
								foreach($facilitiesdb as $fa) 
								{ 
								?>
								<option value="<?php echo $fa['id'];?>"><?php echo $fa['facilityName'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                               
                          
						</div>
					</div>
					</div>
						<div class="form-group" id="editmdx5">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="editptmdx5" class="label" style="color:red">
                                   MDX(s):
                                </label>
                              
									
										<select id="editptmdx5" class="form-control" multiple>
										<option value="0">Select</option>
									
										<?php
								foreach($mdxdb as $mx) 
								{ 
								?>
								<option value="<?php echo $mx['id'];?>"><?php echo $mx['mdxDesc'];?> </option>
								<?php 
								} 
								?>									
										</select>
										 
                     
                         
						</div>
						</div>
						</div>
						<div class="form-group" id="editfdx5">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="editptfdx5" class="label" style="color:red">
                                   FDX(s):
                                </label>
                               
								
										<select id="editptfdx5" class="form-control" multiple>
										<option value="0">Select</option>
										
										<?php
								foreach($fdxdb as $fx) 
								{ 
								?>
								<option value="<?php echo $fx['id'];?>"><?php echo $fx['fdxDesc'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
             
                            
						</div>
						</div>
						</div>
						<div class="form-group" id="editdel5">
						<div class="row">
						<div class="col-md-6">
						
                                <label for="editptdel5" class="label" style="color:red">
                                   Previous Delivery:
                                </label>
                              
									
										<select id="editptdel5" class="form-control">
										<option value="0">Select</option>
										<option value="1">C-Section</option>
										<option value="2">Natural</option>
																	
										</select>
										 
                     
                         
						</div>
						</div>
						</div>
						<div class="form-group" id="editsummaryPhys5">
						<div class="row">
						<div class="col-md-9">
							
							<label for="editsummaryphysician5" class="label" style="color:red">
                                   Physician:
                                </label>
                                <input class="form-control" type="text" id="editsummaryphysician5">
                        </div>
						</div>
						</div>
								<div class="form-group" >
								<hr></hr>
								
								<div class="modal-title">Current Note:
								<textarea class="form-control" maxlength="1000" rows="4" id="summarynoteDetail5" disabled></textarea>
								</div>
								</div>
						
				
					<div class="form-group">
						<div class="row">
						<div class="col-md-12">
							
							<label for="editcurrentsummaryNote5" class="label">
                                   Add Notes:
                                </label>
                                <textarea class="form-control" maxlength="1000" rows="4" id="editcurrentsummaryNote5"></textarea disabled>
                        </div>
						</div>
						</div>
					<div class="form-group" id="fileUploaded5">
						<div class="row">
						<div class="col-md-9">
					<label for="fileName5">Files:</label>
					<span id="fileName5"></span>
					</div>
					</div>
					</div>
					
					<label for="editsummaryfile5">Upload File:</label> 
		            <input class="form-control-file" type="file" id="editsummaryfile5" name="editsummaryfile3" class="hidden"/>
                    
						
						
					
                     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button name="editbutton" type="submit" value="Update" class="btn btn-primary">Update</button>
                </div>
				</form>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>

    <!--end::Modal-->
 	<div class="modal fade" id="changenoteType" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  bg-light">
                    <h4 class="modal-title">Change Note Type</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                   
                        <div class="m-portlet__body">
						
						<input id="changeuserId" type="hidden" value="" />
						<input id="changenoteId" type="hidden" value="" />
						
						 <form id="change_noteType" name="change_noteType" class="m-form m-form--fit m-form--label-align-right">
						<div class="form-group">
						<div class="row">
						<div class="col-md-6">
						<label for="changeNoteType" class="label">
						Note Type:</label>
						
                        <select id="changeNoteType" class="form-control">
						<option value="13">Anesthesia</option>
						<option value="12">Cardiology</option>
						<option value="9">Diabetes Education</option>
						<option value="6">Family Care Conference</option>
						<option value="3">MFM</option>
						<option value="1">Navigator</option>
						<option value="5">Neonatologist</option>
						<option value="15">NICU</option>
						<option value="2">OB/GYN</option>
						<option value="14">Oncology</option>
						<option value="8">Palliative Care</option>
						<option value="11">Radiology/MRI</option>
						<option value="7">Social Work</option>
						<option value="4">Surgery</option>
						<option value="10">Urology</option>
						<option value="20">Other</option>
						</select>
						                       
						</div>
						</div>
						</div>
						
						</form>	
			
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button name="changenotetypebutton" type="button" class="btn btn-primary" onclick="tj.updatenoteType()">Save</button>
                </div>
				
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>

    <!--begin::Modal-->
  
</div>	

</html>



