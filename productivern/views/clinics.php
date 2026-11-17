<?php
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';


$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);
$displayProd = intval($_SESSION['account']['displayProd']);
$userId = intval($_SESSION['account']['id']);
$prodCount = intval($_SESSION['account']['prodCount']);
$deptId = intval($_SESSION['account']['deptNum']);
$prodMeasure = intval($_SESSION['account']['prodMeasure']);
$secLab = $_SESSION['account']['secLabel'];
$accountId = $_SESSION['account']['accountId'];
$dayRank = intval($_SESSION['account']['useDayRank']);
$todayDate = $_SESSION['account']['todayDate'];

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
$userId = intval($_SESSION['account']['id']);

//$user = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';

$dbText = Config::get('db')->get_results("SELECT d.*, SUM(d.escalations) as sumEsc, (SELECT (`dayRank`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate) as dayRankVal, (SELECT (`id`) FROM `productiveDayRank` WHERE `deptId`={$deptId} AND `dayDate`=u.lastStartDate AND `dayDate`=u.lastEndDate ) as dayRankId, COUNT(d.id) as countDept, DAYOFYEAR(u.lastStartDate) AS lastStartDate, DAYOFYEAR(u.lastEndDate) AS lastEndDate, u.id as userId from `ProductiveDept` d left outer join `productiveUser` as u on u.id ={$userId} left outer join `productiveDeptXref` as x on x.userId = u.id where (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND d.accountId={$accountId}");

if($dbText){
	$countDept = $dbText[0]['countDept'];
	$lastStartDate = $dbText[0]['lastStartDate'];
	$lastEndDate = $dbText[0]['lastEndDate'];
	$dayRankVal = $dbText[0]['dayRankVal'];
}else{
	$countDept = 0;
	$lastStartDate = '';
	$lastEndDate = '';
	$dayRankVal = 0;
}

if(($role<7 || $role==11) && intval($dbText[0]['countDept']) != intval($dbText[0]['sumEsc'])){
	$hidden = '';
}else{
	$hidden = "hidden";
}

$dbUnits = Config::get('db')->get_results("SELECT a.*, a.dept as deptId, d.dept, d.active, u.role FROM `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id=a.dept LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=d.id LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND a.accountId={$accountId} AND d.active>0 GROUP BY a.dept ORDER BY d.dept ASC");




?>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
	<input id="startSupportcli" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endSupportcli" type="hidden" value="<?php echo $endPay; ?>" />
	<input id="supportRolecli" type="hidden" value="<?php echo $role; ?>" />
	<input id="supportTitlecli" type="hidden"/>
	
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Reports - Support
            </h3>
        </div>
		
		<div class="d-flex pull-right">
		<?php if($role>12) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="locationFiltersupportcli" onchange="tj.reportsSelect();" hidden>
			<option value="0">All Locations</option>
			<?php foreach($dbLocation as $a) { ?>
			<option value="<?php echo $a['id'];?>"><?php echo $a['name'];?> </option>
			<?php 	} ?>
			</select>&nbsp;
			<?php }else{ ?>
			<select  class="bs-select form-control input-sm" id="locationFiltersupport" name="locationFiltersupport" onchange="tj.reportsSelect();" hidden>
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
			<select  class="bs-select form-control input-sm" id="serviceFiltersupportcli" name="serviceFiltersupportcli" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>
			</select>
			<span class="m-subheader__daterange" id="staffing_daterangepickercli">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="staffing_daterangepickercli">	
			<?php } ?>
                <span class="m-subheader__daterange-label">
                    <span class="m-cli__daterange-title"></span>
                    <span class="m-cli__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>
        </div>
    
</div>
<!-- END: Subheader -->
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				<div>
				<div class="row pull-left">
							<?php if($role >=5): ?>
				<form id="dateFormcli" name="dateFormcli" action="inc/data.php?req=getpdf" method="post">
				<button name="downloadPDFcli" id="downloadPDFcli" class="btn btn-info" type="submit">PDF</button>
				</form>
				<?php endif; ?>
					<?php if($role>10): ?>			
				<form id="dateFormCSVcli" name="dateFormCSVcli" action="inc/data.php?req=downloadcsv" method="post">
				<button name="downloadCSVcli" id="downloadCSVcli" class="btn btn-success" type="submit">CSV</button>
				</form>
								<?php endif; ?>
                                <!--end: Dropdown-->
                            </div>
				 
				     <button type="button" id="escButtoncli" class="btn btn-danger pull-right" data-target="#addEscalation3cli" data-toggle="modal"  <?php echo $hidden; ?>>
                                            Escalation
                                        </button>   
					<?php if(($role==4|| $role==5 || $role==6 || $role==11) && $dayRank>0): ?>
				<button type="button" id="dayRankcli" class="btn btn-info pull-right" onclick="tj.getdayRatingcli(<?php echo $deptId .",". $countDept;?>);">
                Rate Day
                </button>
				<?php endif; ?>
				</div>	 
                             <!--end: Dropdown-->
					<div class="row col-12">
                        <table id="prodTablecli"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="15%" data-priority="1">Unit</th>
									<th width="15%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="3">Actual WHPUOS</th>
									<th width="10%" data-priority="5">Hours Variance</th>
									<th width="10%" data-priority="6">Units of Service</th>
									<th width="10%" data-priority="7">Total Hours</th>
									<th id="col1cli" value="<?php echo $dbData['skill1']; ?>"><?php echo $dbData['skilldesc1']; ?></th>
									<th id="col2cli" value="<?php echo $dbData['skill2']; ?>"><?php echo $dbData['skilldesc2']; ?></th>
									<th id="col3cli" value="<?php echo $dbData['skill3']; ?>"><?php echo $dbData['skilldesc3']; ?></th>
									<th id="col4cli" value="<?php echo $dbData['skill4']; ?>"><?php echo $dbData['skilldesc4']; ?></th>
									<th id="col5cli" value="<?php echo $dbData['skill5']; ?>"><?php echo $dbData['skilldesc5']; ?></th>
									<th width="30%" data-priority="4">Note</th>
									<th id="linecli" data-priority="8">Svc. Line</th>
                                </tr>
                            </thead>
                        </table>
						</div>
						</div>
                   </div>
    <!--End::Main Portlet-->
</div>


	<div class="modal fade" id="addprodnotecli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdcli" type="hidden" value="" />
						<input id="notedeptIdcli" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodycli">Your Staffing Report shows a possible <span id="varianceTypecli"></span> variance for <span id="deptcli"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control" rows="3" id="notebodycli" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" id="noteButtoncli" class="btn btn-primary" onclick="tj.addProdNotecli();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
 
 <!-- /begin modal -->
<div class="modal fade" id="addcli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header">
				  <h4 class="modal-title text-white">Productivity Report
				  </h4>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				<input id="indMeasurecli" type="hidden" value="" />
				<input id="typecli" type="hidden" value="" />
				<div class="row">
                        <div class="col-sm-12 job-address">
						<!--
						<div class="modal-title pull-right"><span id="dataId2cli"></span> 
				  </div>
				  -->
						<div class="modal-title">Report Date - Time:  <span id="reportdatecli"></span> - <span id="reportshiftcli"></span>
				  </div>
						
                           
							<div class="modal-title" style="padding-bottom: 20px;">Last Updated By:   <span id="userNamecli"></span>
							</div>
							
							<div class="modal-title">Target WHPUOS:   <span id="targetcli"></span>
							</div>
							<!--
							<div class="modal-title">Variance(Hours):   <span id="plannedVar"></span>
							</div>
							-->
							<div class="modal-title">Actual WHPUOS:   <span id="currentcli"></span>
							</div>
							
							<div class="modal-title">Hours Variance:   <span id="currentVarcli"></span>
							</div>
						
							<!--
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formcli" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userIdcli" type="hidden" value="" />
						<input id="shiftcli" type="hidden" value="" />
						<input id="actualcli" type="hidden" value="" />
						<input id="daycli" type="hidden" value="" />
						<input id="dataIdcli" type="hidden" value="" />
						<div class="m-portlet__body">
						
						
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title"><span id="actualcli"></span> </h5>
						</div>
						<div id="cliselect">
						<div class="form-group m-form__group m--margin-top-10 row">
						<label for="selectcli" class="col-7 col-form-label">
                                   Select <span id="providerNamecli"></span>
                                </label>
						<div class="col-5">
                                    <select id="selectcli" type="select" class="form-control" required >
									<option value="0">Select</option>
									<span id="providerDetailcli"></span>
									<?php
									if($role>7){
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.active>0 AND u.provider=1 AND d.indMeasure>0 AND u.role<7 and u.accountId={$_SESSION['account']['accountId']} group by u.id order by u.last_name ASC, u.first_name ASC");
									}else{
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.active>0 AND u.provider=1 AND x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) group by u.id order by u.last_name ASC, u.first_name ASC");
									}
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
						</div>
						<!--
						<div class="form-group m-form__group m--margin-top-5 row">
                                <label for="indUOS" class="col-7 col-form-label">
                                   <span id="indUOSDesc"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="indUOS" style="text-align: right">
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-5 row">
                                <label for="indHRS" class="col-7 col-form-label">
                                   <span id="indHRSDesc"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="indHRS" style="text-align: right">
                                </div>
                            </div>
							-->
						</div>
					
						<div class="form-group m-form__group m--margin-top-5 row" id="plannedclihide">
                                <label for="plannedcli" class="col-7 col-form-label">
                                   <span id="uosDesccli"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="plannedcli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="customclihide">
                                <label for="customcli" class="col-7 col-form-label">
                                   <span id="customDesccli"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="customcli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="customcli2hide">
                                <label for="customcli2" class="col-7 col-form-label">
                                   <span id="customDesc2cli"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="customcli2" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="customcli3hide">
                                <label for="customcli3" class="col-7 col-form-label">
                                   <span id="customDesc3cli"></span>
                                </label>
                                <div class="col-5">
                                 <input type="number" min="0" class="form-control number" id="customcli3" style="text-align: right">
                                </div>
                            </div>
							<hr></hr>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill1cli" >
                                <label for="skillval1cli" class="col-7 col-form-label">
                                    <span id="descskill1cli"></span>:
                                </label>
                                <div class="col-5">
								<input type="number" min="0" class="form-control number" id="skillval1cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill2cli" >
                                <label for="skillval2cli" class="col-7 col-form-label">
                                    <span id="descskill2cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval2cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill3cli">
                                <label for="skillval3cli" class="col-7 col-form-label">
                                    <span id="descskill3cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval3cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill4cli">
                                <label for="skillval4cli" class="col-7 col-form-label">
                                    <span id="descskill4cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval4cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill5cli">
                                <label for="skillval5cli" class="col-7 col-form-label">
                                   <span id="descskill5cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval5cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill6cli">
                                <label for="skillval6cli" class="col-7 col-form-label">
                                   <span id="descskill6cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval6cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill7cli">
                                <label for="skillval7cli" class="col-7 col-form-label">
                                   <span id="descskill7cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval7cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill8cli">
                                <label for="skillval8cli" class="col-7 col-form-label">
                                   <span id="descskill8cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval8cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill9cli">
                                <label for="skillval9cli" class="col-7 col-form-label">
                                   <span id="descskill9cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval9cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill10cli">
                                <label for="skillval10cli" class="col-7 col-form-label">
                                   <span id="descskill10cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval10cli" style="text-align: right">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill11cli">
                                <label for="skillval11cli" class="col-7 col-form-label">
                                   <span id="descskill11cli"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" min="0" class="form-control number" id="skillval11cli" style="text-align: right">
                                </div>
                            </div>
															
							
							<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
                              <h5 class="modal-title">Note / Action Plan</h5>
								</div>
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" rows="3" id="prodnotecli"></textarea>
                            </div>
												
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdcli();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>
	
<div class="modal fade" id="escalationNEWcli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="esccli" type="hidden" value="" />
				<input id="deptIdesccli" type="hidden" value="" />
				<input id="dataIdesccli" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require immediate escalation (ie. Staffing, Supplies, Close Calls, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer">
					<button type="button" id="noescbuttoncli" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <!--<button type="button" id="noescbuttoncli" class="btn btn-secondary" onclick="tj.noEscalationcli();">No</button>-->
                    <button type="button" id="addescbuttoncli" class="btn btn-primary" data-target="#addEscalationcli" data-toggle="modal">Yes</button>
                </div>
				<div class="modal-body">
				<div class="title">To remove the Escalations feature, go to Unit Settings.
					</div>
					</div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
   
	
<div class="modal fade" id="addEscalationcli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2cli" type="hidden" value="" />
						<input id="deptId2cli" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escvalcli">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escvalcli" name="escvalcli" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `id` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										</div>  
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcommentcli">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcommentcli" ></textarea>
                            </div>
							<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="Escbuttoncli" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbuttoncli" class="btn btn-primary" onclick="tj.addEscalationcli();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3cli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3cli" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3cli">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              	
										<select id="escval3cli" name="escval3" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `id` ASC");
								foreach($dbData as $m) 
								{ 
								?>
								<option value="<?php echo $m['id'];?>"><?php echo $m['escalation'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="deptId3cli">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3cli" name="deptId3cli" class="form-control">
										<?php
										$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.escalations=0 AND d.accountId={$_SESSION['account']['accountId']} AND (x.deptId in (SELECT `deptId` from `productiveDeptXref`where `userId`={$_SESSION['account']['id']}) OR u.role=11) group by d.id order by d.dept ASC");
									foreach($dbUnit as $b){ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['dept'];?> </option>
								<?php 
								} 
								?>									
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment3cli">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcomment3cli" ></textarea>
                            </div>
							<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="Escbutton3cli" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton3cli" class="btn btn-primary" onclick="tj.addEscalation3cli();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<div class="modal fade" id="addDaycli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Day Rating</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dayrateIdcli" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">	
								 
						 <div class="modal-title"><span id="dayUpdatedcli"></span></div>
						 <div class="modal-title"><span id="dayUsercli"></span></div>
						 
					   </div>
					   <div class="form-group form-md-line-input">
                                <label for="dayDatecli">Select Date:</label>
                              	 <input id="dayDatecli" type="date" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayUnitcli">Select Unit:</label>
                              			<select id="dayUnitcli" name="dayUnitcli" class="form-control" onchange="tj.checkdaycli();">
										<option value="">Select</option>
										<?php foreach($dbUnits as $u) { ?>
										<option value="<?php echo $u['deptId'];?>"><?php echo $u['dept'];?> </option>
										<?php 	} ?>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input" hidden>
                                <label for="daySDcli">Service Deliverable Achieved?</label>
                              			<select id="daySDcli" name="daySDcli" class="form-control">
										<option value="0">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayEEcli">Rate the Day for Employee Engagement:</label>
                              			<select id="dayEEcli" name="dayEEcli" class="form-control">
										<option value="0">Select</option>
										<option value="5">5 - Excellent</option>
										<option value="4">4 - Above Average</option>
										<option value="3">3 - Average</option>
										<option value="2">2 - Below Average</option>
										<option value="1">1 - Poor</option>										
										</select>
										
                            </div>	
							<hr></hr>
							<div class="form-group form-md-line-input">
                                <label for="dateNewcli">Next available date for New Patients:</label>
                              	 <input id="dateNewcli" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dateExistingcli">Next available date for Existing Patients:</label>
                              	 <input id="dateExistingcli" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayReferralscli">Referrals in the queue:</label>
                              	<input type="number" min="0" class="form-control number" id="dayReferralscli" style="text-align: right">								 
										
                            </div>
				
					  
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updatedayRatingcli();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="editEscalationcli" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation Response</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="dataIdeditcli" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">
                                <span id="editSubmittedcli" ></span>	
							</div>
                            <div class="form-group">
                                <label for="edittypecli">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="edittypecli" value="" disabled>				
                            </div>
							</div>
							<div class="form-group">
                                <label for="editunitcli">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="editunitcli" value="" disabled>	
							</div>
							</div>
							<div class="form-group">
                                <label for="escalationcommenteditcli">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcommenteditcli" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group">
                                <label for="escalationresponsecli"><strong>Response:</strong></label>
								<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your response.
							</div>
                                <textarea class="form-control" rows="3" id="escalationresponsecli" ></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateEscalationcli();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>



    <!--end::Modal-->


