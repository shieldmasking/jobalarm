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
                Policies
            </h3>
        </div>
		<div class="d-flex pull-right">
			<button class="btn btn-sm btn-brand" onclick="tj.openUploadPolicy()"><i class="la la-upload"></i> Upload</button>
		</div>
		<!--
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
		-->
    </div>
	
<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">

                    <div class="row col-12">
					
                        <table id="policiesTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="25%" data-priority="1">Title</th>
									<th width="25%" data-priority="2">Created</th>
									<th width="40%" data-priority="3">Doc</th>
									<th width="10%" data-priority="3">Close</th>
                                    
									
									
                                </tr>
                            </thead>
                        </table>
						</div>
                    </div>
               </div>
<div class="modal fade" id="policyModal" tabindex="-1" role="basic" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-secondary">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title" id="policyModalTitle"></h4>
              </div>
              <div class="modal-body">
                  <button type="button" class="btn btn-default btn-sm" onclick="tj.copyPolicy()">Copy All</button>
                  <hr />
                  <pre id="policyContent" style="white-space: pre-wrap; word-wrap: break-word; font-family: inherit;
  font-size: 13px;"></pre>
              </div>
              <div class="modal-footer bg-secondary">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>

 </div>
    <!--End::Main Portlet-->

<div class="modal fade" id="uploadPolicyModal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title" style="color:white">Upload Policy</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Title</label>
          <input type="text" id="uploadPolicyTitle" class="form-control" placeholder="Policy title" />
        </div>
        <div class="form-group">
          <label>File (PDF, DOC, TXT, XLS, PPT)</label>
          <input type="file" id="uploadPolicyFile" class="form-control" accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx" />
        </div>
        <div id="uploadPolicyMsg" style="display:none;margin-top:8px;"></div>
      </div>
      <div class="modal-footer bg-secondary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-brand" onclick="tj.submitUploadPolicy()">Upload</button>
      </div>
    </div>
  </div>
</div>

</div>


