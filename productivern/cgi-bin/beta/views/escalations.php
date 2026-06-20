<?php
include "../inc/initializer.php";
?>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
    
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
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">

                    <div class="row col-12">
					
                        <table id="escalationsTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="20%" data-priority="1">Date/Time</th>
									<th width="20%" data-priority="5">Unit</th>
                                    <th width="20%" data-priority="3">Submitted By</th>
									<th width="20%" data-priority="2">Type</th>
									<th width="20%" data-priority="4">Comments</th>
                                    
									
									
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
	
	<div class="modal fade" id="editEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="esc">Escalation Type:</label>
                              <div class="form-group">	
										<select id="esc2" name="esc" class="form-control">
										<option value=""></option>
										<option value="Staffing">Staffing</option>
										<option value="Supplies">Supplies</option>
										<option value="E-Reach">E-Reach</option>
										<option value="Close Call">Close Call</option>
										<option value="Other">Other</option></select>
										</div>  
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment">Notes:</label>
                                <textarea class="form-control" rows="3" id="escalationcomment" ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscEditbutton" class="btn btn-primary" onclick="tj.editEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>


