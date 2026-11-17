<?php
include "../inc/initializer.php";
$todayDate = $_SESSION['account']['todayDate'];
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
    
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Day Rating
            </h3>
        </div>
		<div class="d-flex pull-right">
            <span class="m-subheader__daterange" id="dayRank_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-dayRank__daterange-title"></span>
                    <span class="m-dayRank__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
            <div class="pull-right" style="margin-left:15px;margin-top:2px">
            </div>
        </div>
    </div>
<!-- END: Subheader -->
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">

                    <div class="row col-12">
					
                        <table id="dayRankTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="15%" data-priority="1">Unit</th>
									<th width="15%" data-priority="2">Date</th>
                                    <th width="15%" data-priority="3">Submitted By</th>
									<th width="15%" data-priority="4">Svc. Deliverable</th>
									<th width="15%" data-priority="5">Emp. Engagement</th>
									<th width="15%" data-priority="6">Engagement Avg.</th>
									<th width="10%" data-priority="7">Action</th>									
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
               </div>
        </div>
    <!--End::Main Portlet-->
    <!--begin::Modal-->
	    <!--begin::Modal-->
	
	<div class="modal fade" id="editdayRank" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Day Rating</h4>
					 </div>
                <div class="modal-body">
				 <form role="form">
                        <input id="dayRankId" type="hidden" value="" />
						<div class="form-body">
					<div class="form-group">	
					 <h5 class="modal-title"><span id="dayRankUnit"></span></h4>
					 
					 <div class="modal-title"><span id="dayRankUpdate"></span></div>
					 <div class="modal-title"><span id="dayRankUser"></span></div>
					 <hr></hr>
                   </div>
                            <div class="form-group form-md-line-input" hidden>
                                <strong><label for="edayRank">Service Deliverable Achieved:</label></strong>
                              <div class="form-group">	
										<select id="edayRank" name="edayRank" class="form-control">
										<option value="0">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
										</select>
										</div>  
                            </div>
							<div class="form-group form-md-line-input">
                                <strong><label for="empEng">Rate the Day for Employee Engagement:</label></strong>
                              <div class="form-group">	
										<select id="empEng" name="empEng" class="form-control">
										<option value="0">Select</option>
										<option value="5">5 - Excellent</option>
										<option value="4">4 - Above Average</option>
										<option value="3">3 - Average</option>
										<option value="2">2 - Below Average</option>
										<option value="1">1 - Poor</option>
										</select>
										</div>  
                            </div>
							<hr></hr>
							<div class="form-group">
                                <label for="dateNewdr">Next available date for New Patients:</label>
                              	 <input id="dateNewdr" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group">
                                <label for="dateExistingdr">Next available date for Existing Patients:</label>
                              	 <input id="dateExistingdr" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group">
                                <label for="dayReferralsdr">Referrals in the queue:</label>
                              	<input type="number" min="0" class="form-control number" id="dayReferralsdr" style="text-align: right">								 
										
                            </div>
							

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.editdayRank();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>


