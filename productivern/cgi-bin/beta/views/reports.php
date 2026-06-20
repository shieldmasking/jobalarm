<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';


$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);

if($payPeriod>0 && $payFirst>0){
$calcPay = (($currentDay-$payFirst)/$payPeriod)- FLOOR(($currentDay-$payFirst)/$payPeriod);
	if($calcPay==0){
	$endPay=$payPeriod-1;
	$startPay = 0;
	}else{
	$startCalc = ROUND($payPeriod-($calcPay*$payPeriod),0);
	$startPay = $payPeriod-$startCalc;
	$endPay = ROUND($payPeriod-($calcPay*$payPeriod)-1,0);
	}
}else{
$startPay=0;
$endPay=0;
}


?><!-- BEGIN: Subheader -->
<div class="m-subheader" style="padding-bottom: 20px;">
<div class="col-md-12">

<div class="d-flex pull-right">

	<!--<div style="padding-top: 10px;" >-->
	<input id="startDate" type="hidden" value="" />
	<input id="endDate" type="hidden" value="" />
	<input id="startPay" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endPay" type="hidden" value="<?php echo $endPay; ?>" />
	<input id="role" type="hidden" value="<?php echo $role; ?>" />
	
                
				<!--<select id="filter" name="filter" onchange="tj.categorySelect();" class="input-medium" >-->
			<?php if($role>12) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="location" name="location" onchange="tj.locationSelect();">
			<option value="0">All Locations</option>
			<?php foreach($dbLocation as $a) { ?>
			<option value="<?php echo $a['id'];?>"><?php echo $a['name'];?> </option>
			<?php 	} ?>
			</select>&nbsp;			
			<?php }else{ ?>
			<select  class="bs-select form-control input-sm" id="location" name="location" onchange="tj.locationSelect();" hidden>
			</select>
			<?php } ?>
			
						
			<?php if($role>7 && $role<=12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			}else if($role>12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left outer join `productiveAccount` as a on a.id=d.accountId where c.id !=0 AND a.enterpriseId={$_SESSION['account']['enterpriseId']} group by c.id order by c.categoryName ASC");
			}else{
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.id IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}) group by c.id order by c.categoryName ASC");	
			}
			
			if($dbData && count($dbData)>1){ ?>
			<span>
			<select  class="bs-select form-control input-sm" id="filter" name="filter" onchange="tj.categorySelect();">
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>									
			</select></span>&nbsp;
			<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
			<?php } ?>
			
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>	
		
 
</div>

</div>
</div>

<!-- END: Subheader -->
<div class="m-content">
<span id="reportBody"></span>
<span id="chartBody"></span>
    <!--End::Main Portlet-->

		<div class="modal fade" id="blockbeds" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title"><span id="deptName"></span></div>
					
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
						<input id="accountId" type="hidden" value="" />
                        <div class="form-body">
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="blockedcount" class="col-7 col-form-label">
                                   Currently Blocked Beds:
                                </label>
                                <div class="col-5">
                                    <select id="blockedcount" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										<option value=6>6</option>
										<option value=7>7</option>
										<option value=8>8</option>
										<option value=9>9</option>
										<option value=10>10</option>
										<option value=11>11</option>
										<option value=12>12</option>
										<option value=13>13</option>
										<option value=14>14</option>
										<option value=15>15</option>
										<option value=16>16</option>
										<option value=17>17</option>
										<option value=18>18</option>
										<option value=19>19</option>
										<option value=20>20</option>
										</select>
                                </div>
                            </div>
												
                            <div class="form-group form-md-line-input">
                                <label for="blockedcomment">Comments:</label>
                                <textarea class="form-control" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
