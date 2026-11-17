<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);

$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);
$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";



?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Current Activity
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
				 
				 	<div class="col-xl-12">
                    <div class="row">
					
                        <table id="prodTableuserx"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="10%" data-priority="1">Acct#</th>
                                    <th width="10%" data-priority="2">Dept#</th>
									<th width="10%" data-priority="3">Prod%</th>
									<th width="10%" data-priority="4">Variance</th>
									<th width="10%" data-priority="5">Time</th>                                    
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
</div>
    <!--begin::Modal-->



    <!--end::Modal-->
</div>
