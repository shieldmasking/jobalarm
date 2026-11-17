<?php
ini_set('display_errors',1);
include "../inc/initializer.php";

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
    
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Escalations
            </h3>
        </div>
		<div class="d-flex pull-right">
            <span class="m-subheader__daterange" id="escalations_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-escalations__daterange-title"></span>
                    <span class="m-escalations__daterange-date m--font-brand"></span>
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
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">

                    <div class="row col-12">
					
                        <table id="escalationsTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="15%" data-priority="1">Unit</th>	
									<th width="20%" data-priority="4">Date/Time</th>
									<th width="15%" data-priority="5">Submitted By</th>
									<th width="10%" data-priority="2">Type</th>
									<th width="40%" data-priority="3">Comments</th>
                                    
									
									
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
	
	<div class="modal fade" id="editEsc" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">
                            <span id="escSubmit" ></span>	
							</div>
							<div class="form-group">
                            <label for="escDept">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="escDept" value="" disabled>	
							</div>
							</div>
                            <div class="form-group">
                            <label for="esctype">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="esctype" value="" disabled>
							</div>							 
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment">Comments:</label>
                                <textarea class="form-control" rows="5" id="escalationcomment" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group" id="responseHistory" hidden>
                                <label for="escResp"><strong>Response History:</strong></label>
                                <textarea class="form-control" rows="4" id="escResp" disabled></textarea>
                            </div>	
							<div class="form-group" hidden>
                                <label for="escResp1"><strong> Add Response:</strong></label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
							</div>
                                <textarea class="form-control" rows="3" id="escResp1" ></textarea>
                            </div>
							<div class="form-group">									
							  <label> <input type="checkbox" id="closeesc" name="closeesc" hidden><strong></strong></label>
							</div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-secondary">
                    <button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="EscEditbutton" class="btn btn-primary" onclick="tj.updateEscalation2();" hidden>Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<div class="modal fade" id="editEscalationtable" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation Response</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="dataIdedittable" type="hidden" value="" />
						<div class="form-body">
							<div class="form-group">
                            <span id="editSubmittedtable" ></span>	
							</div>
							<div class="form-group">
                            <label for="editunittable">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="editunittable" value="" disabled>	
							</div>
							</div>
                            <div class="form-group">
                            <label for="edittypetable">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="edittypetable" value="" disabled>
							</div>							 
                            </div>
							
							<div class="form-group">
                                <label for="escalationcommentedittable">Comments:</label>
                                <textarea class="form-control" rows="4" id="escalationcommentedittable" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group">
                                <label for="escalationresponsetable"><strong>Response:</strong></label>
								<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your response.
							</div>
                                <textarea class="form-control" rows="3" id="escalationresponsetable" ></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateEscalationtable();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>


    <!--end::Modal-->
</div>


