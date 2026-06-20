<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);
$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";



?><!-- BEGIN: Subheader -->

<div class="m-subheader">
  <div class="d-flex align-items-center">
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Daily Delivery Forecast
            </h3>
        </div>
            <span class="m-subheader__daterange" id="delivery_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-delivery__daterange-title"></span>
                    <span class="m-delivery__daterange-date m--font-brand"></span>
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
<div class="m-content">
<!--
<div>
<button type="button" class="btn btn-info" data-target="#updateDeliveries" data-toggle="modal" >Update</button>

</div>
-->
    <div class="row" id="hidden11">
        <div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                All Deliveries
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                   <canvas id="deliveries_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		
		<div class="col-md-6">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="flaticon-graph"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Primips
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <canvas id="risk_chart" style="width:100%"></canvas>
                </div>
            </div>
        </div>
		
		</div>
    <!--End::Main Portlet-->
	
	
   <!--begin::Modal-->
 	    <div class="modal fade" id="updateDeliveries" tabindex="-1" role="basic" aria-hidden="true">
         <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Update Deliveries</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
				
                    <!--<form id="add_user_form" class="m-form m-form--fit m-form--label-align-right">-->
                        <div class="m-portlet__body">
                           	<div class="form-group">
                                <label for="deliveryCount" class="col-4 col-form-label">
                                    Delivery Count (Last 30 days):
                                </label>
                                <div class="col-8">
                                    <input class="form-control m-input" type="number" value="" id="deliveryCount">
                                </div>
                            </div>
                            	
                         </div>
						 
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateDelivery();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
</div>
