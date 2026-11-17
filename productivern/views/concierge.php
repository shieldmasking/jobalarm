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
                Perinatal Concierge Messages
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
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addMessage" data-toggle="modal">
                                            Add Message
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
				
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="conciergeTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="15%" data-priority="1">Days until EDD</th>
                                    <th width="15%" data-priority="2">Pregnancy</th>
									 <th width="25%" data-priority="3">Complications</th>
									 <th width="35%" data-priority="4">Message</th>
                                    <th width="10%" data-priority="5">Test</th>
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
<div class="modal fade" id="addMessage" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-8">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">New Text Message</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="accountForm_msg" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
						<input id="accountId_msg" type="hidden" value="" />
						<div class="title">
				 <h4>Message Details</h4>
				 </div>
                           	<div class="form-group">
                                <label for="txtmsg" class="col-sm col-form-label">
                                    Text Message (Must be 160 characters or less):
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="text" id="txtmsg">
                                </div>
                            </div>							
			       </div>
				</form>
				</div>
				<div class="modal-footer">
				<div class="pull-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addMessage();">Save</button>
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
   

