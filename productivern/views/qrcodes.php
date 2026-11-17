<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$userId = intval($_SESSION['account']['id']);
$accountId = $_SESSION['account']['accountId'];


?>

<style>
.modal {
    overflow-y: scroll;
}
</style>
<div class="m-content">
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                QR Codes
            </h3>
        </div>



</div>

<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	  	<div class="row">
					<h4><span id="goqrcodename"></span></h4>
					</div>
					<div class="row">
					<div id="goqrcode"></div>
					<div id="gocloseQR" class="bottom-align-text">
					<a href="javascript:;" onclick="tj.gocloseQR();return false;" >(close)</a>
                  </div> 
					<span id="qrLink"></span>
					</div>
	   	
				<div class="row col-12">
                        <table id="qrcodesTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
	                   		<thead>
                                <tr>
									<th width="25%" data-priority="1">Unit</th>
									<th width="30%" data-priority="3">Description</th>
									<th width="25%" data-priority="4">Updated</th>
									<th width="20%" data-priority="2">QR Code</th>
                                </tr>
                            </thead>
                        </table>
				</div>
			
        </div>
    </div>
    <!--End::Main Portlet-->

</div>
    <!--begin::Modal-->
	    <!--begin::Modal-->

<!-- QBL Config Modal -->
<div class="modal fade" id="qblConfigModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configure QBL Supplies</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
        <input type="hidden" id="qblConfigDeptId" />
        <input type="hidden" id="qblConfigId" />
        <p class="text-muted" style="font-size:12px;">Enter the supply name, dry weight (grams), and select or upload an image for each slot. Leave name blank to hide a slot.</p>
        <table class="table table-sm table-bordered" style="font-size:13px;">
          <thead><tr>
            <th>#</th><th>Supply Name</th><th>Dry Wt (g)</th><th>Image</th><th>Upload</th><th>Clear</th>
          </tr></thead>
          <tbody id="qblConfigRows"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="tj.saveQBLConfig();">Save</button>
      </div>
    </div>
  </div>
</div>

</div>