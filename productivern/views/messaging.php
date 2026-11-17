<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

$role = intval($_SESSION['account']['role']);
$userId = intval($_SESSION['account']['id']);
$accountId = $_SESSION['account']['accountId'];

?>


<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Huddle Messages
            </h3>
        </div>
		<div class="title">
           These messages will appear in their respective Unit Huddle Forms.
        </div>

</div>

<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	  	
				<div class="row col-12">
                        <table id="msgTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
									<th width="45%" data-priority="2">Message</th>
									<th width="20%" data-priority="3">Created</th>
									<th width="15%" data-priority="4">End Date</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
 


<!-- /begin modal -->
<div class="modal fade" id="editmsg" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-6">
		<div class="modal-dialog modal-md">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">Huddle Message
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body bg-secondary">
				<input id="msgId" type="hidden" value="" />
				<input id="msgUser" type="hidden" value="" />
				<input id="msgdeptId" type="hidden" value="" />
			
				<div class="row">
                        <div class="col-sm-12">
						<div class="modal-title"><strong><span id="msgDept"></strong></span>
				  </div>
																	
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="addmsgform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
						
					
						<div class="form-group row">
                                <label for="msgNote" class="col-12 form-label">
                                    Message:
                                 <div>
                                    <textarea class="form-control" rows="10" id="msgNote"></textarea>
                                </div>
								</label>
                            </div>
						<div class="form-group row">	
							<label for="expDate" class="col-12 form-label">End Date (Optional):
							<div>
                              	 <input id="expDate" type="date" value="" />
							</div>								 
							</label>			
						 </div> 
						 
                    </form>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submitmsg" class="btn btn-primary" onclick="tj.savemsg();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>

<div class="modal fade" id="addmsg" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-6">
		<div class="modal-dialog modal-md">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">Huddle Message
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body bg-secondary">
				<input id="addmsgdeptId" type="hidden" value="" />
			
				<div class="row">
                        <div class="col-sm-12">
						<div class="modal-title"><strong><span id="addmsgDept"></span></strong>
				  </div>
																	
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="msgform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
					
			
						<div class="form-group row">
                                <label for="addmsgNote" class="col-12 form-label">
                                    Message:
                                 <div>
                                    <textarea class="form-control" rows="3" id="addmsgNote"></textarea>
                                </div>
								</label>
                            </div>
						<div class="form-group row">	
							<label for="addexpDate" class="col-12 form-label">End Date (Optional):
							<div>
                              	 <input id="addexpDate" type="date" value="" />
							</div>								 
							</label>			
						 </div> 
						 
                    </form>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="submitmsg" class="btn btn-primary" onclick="tj.newmsg();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>
						
				
							