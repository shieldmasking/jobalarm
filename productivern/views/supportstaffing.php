<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';

if (!isset($_SESSION['account']) || ($_SESSION['account']['pwd'] == $_SESSION['account']['temp'])) {
	session_destroy();
    header('location: login.php');
    exit();
}


//$payPeriod = intval($_SESSION['account']['payPeriod']);
//$currentDay = intval($_SESSION['account']['currentDay']);
//$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);
$displayProd = intval($_SESSION['account']['displayProd']);
$userId = intval($_SESSION['account']['id']);
$prodCount = intval($_SESSION['account']['prodCount']);
$deptId = intval($_SESSION['account']['deptNum']);
$prodMeasure = intval($_SESSION['account']['prodMeasure']);
$secLab = $_SESSION['account']['secLabel'];
$accountId = $_SESSION['account']['accountId'];
$dayRank =0;
$todayDate = $_SESSION['account']['todayDate'];
$acctEscalation = intval($_SESSION['account']['acctEscalation']);
$prodTerm = $_SESSION['account']['prodTerm'];
$hrsTerm = $_SESSION['account']['hrsTerm'];
$startPay = (isset($_SESSION['account']['newStartPay']) ? $_SESSION['account']['newStartPay']:0);
$endPay = (isset($_SESSION['account']['newEndPay']) ? $_SESSION['account']['newEndPay']:0);
/*
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
*/
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


if($role==9 || $role==8 || $role==10 || $role>12){
$exec = 'disabled';
}else{
$exec = '';
}

if($role<6){
$escButton = '';
}else{
$escButton = "hidden";	
}


$dbUnits = Config::get('db')->get_results("SELECT a.*, a.dept as deptId, d.dept, d.active, u.role FROM `productiveAccountXref` a LEFT JOIN `ProductiveDept` as d on d.id=a.dept LEFT OUTER JOIN `productiveDeptXref` as x on x.deptId=d.id LEFT JOIN `productiveUser` as u on u.id={$userId} WHERE (d.id IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) or u.role>10) AND a.accountId={$accountId} AND d.active>0 GROUP BY a.dept ORDER BY d.dept ASC");

$dbTherapists = Config::get('db')->get_results("SELECT u.*, d.dept as deptName FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.active>0 AND u.role=3 AND x.deptId = 455 AND u.id!=1568 group by u.id order by u.last_name ASC, u.first_name ASC");


?>

<style>
.modal {
    overflow-y: scroll;
}
</style>
<style>
.vl {
  border-left: 3px solid DodgerBlue;
}
</style>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
	<input id="startSupport" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endSupport" type="hidden" value="<?php echo $endPay; ?>" />
	<input id="supportRole" type="hidden" value="<?php echo $role; ?>" />
	<input id="acctEscalationswhp" type="hidden" value="<?php echo $acctEscalation; ?>" />
	<input id="supportTitle" type="hidden"/>
	
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                <?php echo $prodTerm; ?> - Support
            </h3>
        </div>
		
		<div class="d-flex pull-right">
		<?php if($role>12) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="locationFiltersupport" name="locationFilterwhpsupport" onchange="tj.reportsSelect();" hidden>
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
			<select  class="bs-select form-control input-sm" id="serviceFiltersupport" name="serviceFiltersupport" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>
			</select>
			<span class="m-subheader__daterange" id="staffing_daterangepickerwhp">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="staffing_daterangepickerwhp">	
			<?php } ?>
                <span class="m-subheader__daterange-label">
                    <span class="m-whp__daterange-title"></span>
                    <span class="m-whp__daterange-date m--font-brand"></span>
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
				
				<div class="row">
				<div class="col-6" hidden>		
					<?php if(($role==11 || $role==12) && $userId!=1271): ?>			
				<form id="dateFormCSVwhp" name="dateFormCSVwhp" action="inc/reports.php?req=downloadcsv" method="post">
				<button name="downloadCSVwhp" id="downloadCSVwhp" class="btn btn-success" type="submit">CSV</button>
				</form>
								<?php endif; ?>
                
                 </div>           
				 <div class="col-6 pull-right" hidden>
				     <button type="button" id="escButtonwhp" class="btn btn-danger pull-right" data-target="#addEscalation3whp" data-toggle="modal">
                                            Escalation
                                        </button>  
					</div>					
					<?php if(($role==4|| $role==3 || $role==5 || $role==6 || $role==11 || $role==12) && $dayRank>0): ?>
				<button type="button" id="dayRankwhp" class="btn btn-info pull-right" onclick="tj.getdayRatingwhp(<?php echo $deptId .",". $countDept;?>);" hidden>
                Rate Day
                </button>
				<?php endif; ?>
				</div>	 
                             <!--end: Dropdown-->
					<div class="row col-12">
                        <table id="prodTablewhp"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
									<th width="20%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="5">Variance</th>
									<th width="10%" data-priority="6">Units of Service</th>
									<th width="10%" data-priority="7">Total Hours/Staff</th>
									<th id="col1" value="<?php echo $dbData['skill1']; ?>"><?php echo $dbData['skilldesc1']; ?></th>
									<th id="col2" value="<?php echo $dbData['skill2']; ?>"><?php echo $dbData['skilldesc2']; ?></th>
									<th id="col3" value="<?php echo $dbData['skill3']; ?>"><?php echo $dbData['skilldesc3']; ?></th>
									<th id="col4" value="<?php echo $dbData['skill4']; ?>"><?php echo $dbData['skilldesc4']; ?></th>
									<th id="col5" value="<?php echo $dbData['skill5']; ?>"><?php echo $dbData['skilldesc5']; ?></th>
									<th width="30%" data-priority="4">Note</th>
									<th id="line" data-priority="8">Svc. Line</th>
                                </tr>
                            </thead>
                        </table>
						</div>
						</div>
                   </div>
    <!--End::Main Portlet-->
</div>

 
 <!-- /begin modal -->
<div class="modal fade" id="addWHP" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-12">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header bg-info">
				  <h5 class="modal-title" style="color:white">STAFFING REPORT
				  </h5>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				<input id="indMeasure" type="hidden" value="" />
				<input id="type" type="hidden" value="" />
				<input id="deptIdadd" type="hidden" value="" />
				<div class="form-group">
				<div class="row">
                    <div class="col-6 modal-title">Unit:   <span id="depNAMEwhp"></span>
							</div>
							<div class="col-6 modal-title">Last Updated By:   <span id="userNameWHP"></span>
							</div>
						<div class="col-6 modal-title">Report Date/Time:  <span id="reportdateWHP"></span> - <span id="reportshiftWHP"></span>
				  </div>
						
                           
							
							
							<div class="col-6 modal-title">Target <?php echo $hrsTerm ?>:   <span id="targetWHP"></span>
							</div>
							
							<div class="col-6 modal-title">Estimated <?php echo $hrsTerm ?>:   <span id="currentWHP"></span>
							</div>
							
							<div class="col-6 modal-title"><span id="VarianceType"></span>:<span id="currentVar"></span>
							</div>
						
							<!--
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        
                       </div>
					   </div>
                    <!--begin::Form-->
                    <form id="add_prod_formWHP" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userIdWHP" type="hidden" value="" />
						<input id="shiftWHP" type="hidden" value="" />
						<input id="actualWHP" type="hidden" value="" />
						<input id="dayWHP" type="hidden" value="" />
						<input id="dataIdWHP" type="hidden" value="" />
						<input id="provType" type="hidden" value="" />
						<input id="trackAddwhp" type="hidden" value="" />
						<input id="trackInsertwhp" type="hidden" value="" />
						<input id="submitreqdwhp" type="hidden" value="" />
						<input id="useGridwhp" type="hidden" value="" />
						<input id="customwhpVal" type="hidden" value="" />
						<input id="customwhp2Val" type="hidden" value="" />
						<input id="customwhp3Val" type="hidden" value="" />
						<input id="customwhp4Val" type="hidden" value="" />
						<input id="customwhp5Val" type="hidden" value="" />
						<input id="customwhp3" type="hidden" value="" />
						<input id="customWHP3Orig" type="hidden" value="" />
						<input id="planVal3" type="hidden" value="" />
						<input id="varmsgwhp" type="hidden" value="" />
						<input id="linkedId" type="hidden" value="" />
						<input id="linkedDept" type="hidden" value="" />
						<input id="uosRollup" type="hidden" value="" />
						<input id="posneg" type="hidden" value="" />
	
						
						<div class="m-portlet__body">
						
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white"><span id="actual"></h5>
						</div>
						<!--
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title"><span id="actual"></span> </h5>
						</div>
						-->
						<div id="whpselect">
						<div class="form-group">
						<div class="bg-light">
						<div class="row">
						<div class="col-6">
						<label for="selectWHP" class="col-form-label">
                                   Select <span id="providerName"></span>
                                
						<div>
                                    <select id="selectWHP" type="select" class="form-control" required >
									<option value="0">Select</option>
									<span id="providerDetail"></span>
									<?php
									if($role>7){
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.active>0 AND u.role=3 AND d.indMeasure>0 AND u.role<7 and u.accountId={$_SESSION['account']['accountId']} group by u.id order by u.last_name ASC, u.first_name ASC");
									}else{
									$dbDir = Config::get('db')->get_results("SELECT u.*, d.dept as deptName FROM `productiveUser` u left join `productiveDeptXref` as x on x.userId = u.id left join `ProductiveDept` as d on d.id = x.deptId WHERE u.active>0 AND u.role=3 AND x.deptId in (SELECT `deptId` from `productiveDeptXref` where `userId` ={$userId}) group by u.id order by u.last_name ASC, u.first_name ASC");
									}
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
				
						</div>
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
						<div id="whpselectTest">
						<div class="form-group">
						<div class="bg-light">
						<div class="p-2 mb-1 bg-secondary">
						<div class="modal-title"><strong>AM Shift</strong> </div>
						</div>
						<div class="row">
						
						<div class="col-6">
						<label for="selectA1" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectA1" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="A1pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="A1pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="A1phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="A1phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						<div class="row">
						
						<div class="col-6">
						<label for="selectA2" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectA2" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="A2pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="A2pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="A2phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="A2phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						<div class="p-2 mb-1 bg-secondary">
						<div class="modal-title"><strong>PM Shift</strong> </div>
						</div>
						<div class="row">
						<div class="col-6">
						<label for="selectP1" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectP1" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="P1pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="P1pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="P1phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="P1phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						<div class="row">
						
						<div class="col-6">
						<label for="selectP2" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectP2" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="P2pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="P2pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="P2phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="P2phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
							<div class="p-2 mb-1 bg-secondary">
						<div class="modal-title"><strong>Evening Shift</strong> </div>
						</div>
						<div class="row">
						<div class="col-6">
						<label for="selectE1" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectE1" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="E1pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="E1pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="E1phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="E1phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						<div class="row" hidden>
						
						<div class="col-6">
						<label for="selectE2" class="col-form-label">
                                   Therapist
                                
						<div>
                                    <select id="selectE2" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="E2pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="E2pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="E2phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="E2phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						<div class="p-2 mb-1 bg-secondary">
						<div class="modal-title"><strong>Outpatient Therapist</strong> </div>
						</div>
						<div class="row">
						
						<div class="col-6">
						<label for="selectC1" class="col-form-label">
                                   Outpatient Therapist
                                
						<div>
                                    <select id="selectC1" type="select" class="form-control" required >
									<option value="0">Select</option>
									<option value="1568">Other, not in this list.</option>
									<?php
									if($dbTherapists){
									foreach($dbTherapists as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['last_name'] ?>, <?php echo $d['first_name']?> - <?php echo $d['deptName'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								</label>
						</div>
						<div class="col-6">
						<label for="C1pprocs" class="col-6 form-label">
                                   Procedures
                                
						<div>
                                 <input type="number" min="0" class="form-control number" id="C1pprocs" onchange="tj.updateProcs()" style="text-align: right">
                                
                                 
								</div>
								</label>
							<label for="C1phours" class="col-5 form-label">
                                   Hours
                                
						<div>
                              <input type="number" min="0" class="form-control number" id="C1phours" onchange="tj.updateProv()" style="text-align: right">
								</div>
								</label>
						</div>
						</div>
						</div>
						</div>
						</div>
					
						<div class="form-group">
						<div class="bg-light">
						
						<div class="row">
						<div class="col-sm-6">
						<div id="plannedWHPhide">
                                <label for="plannedWHP" class="col-form-label">
                                   <span id="uosDesc"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="plannedWHP" onchange="tj.updateWHP2()" style="text-align: right">
                                </div>
								</label>
                            </div>
						<!--<span id="addRow"></span>-->
						</div></div><div class="row"><div class="col-sm-6">
							<div id="customWHPhide">
                                <label for="customWHP" class="col-form-label">
                                   <span id="customDesc"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="customWHP" onchange="tj.addwhp()" style="text-align: right">
                                </div>
								</label>
                            </div>
								<div id="customWHP3hide">
                                <label for="customWHP3" class="col-form-label">
                                   <span id="customDesc3"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="customWHP3" onchange="tj.addwhp()" style="text-align: right">
                                </div>
								</label>
                            </div>
						
							</div>
									
							
						
						<div class="col-sm-6 vl">
						<div id="customWHP2hide">
                                <label for="customWHP2" class="col-form-label">
                                   <span id="customDesc2"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="customWHP2" onchange="tj.addwhp()" style="text-align: right">
                                </div>
								</label>
                          </div>
							<div id="customWHP4hide">
                                <label for="customWHP4" class="col-form-label">
                                   <span id="customDesc4"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="customWHP4" onchange="tj.addwhp()" style="text-align: right">
                                </div>
								</label>
                            </div>
					
					
						<div id="customWHP5hide">
                                <label for="customWHP5" class="col-form-label">
                                   <span id="customDesc5"></span>
                                
                                <div class="col-9">
                                 <input type="number" min="0" class="form-control number" id="customWHP5" onchange="tj.addwhp()" style="text-align: right">
                                </div>
								</label>
                            </div>
							</div>
							</div>
							</div>
							</div>
							
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white"><span id="resourcesTitlewhp"></span></h5>
						</div>
						<div class="form-group" id="staffHours">
						<div class="bg-light">
						<div class="row">
						<div class="col-sm-6">
						
						
						<div>				
						<div id="hiddenskill1" >
                                <label for="skillval1" class="col-4">
                                    <span id="descskill1"></span>
                                
                                <div>
								<input type="number" min="0" class="form-control number" id="skillval1" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill1Grid" class="col-3" id="skill1grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill1Grid" name="skill1Grid" disabled>
                                </div>
							   </label>
							   <label for="skill1Var" class="col-4" id="skill1grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill1Var" name="skill1Var" disabled>
									</div>
							   </label>
                            </div>
							</div>
							<div>	
								<div id="hiddenskill3">
                                <label for="skillval3" class="col-4">
                                    <span id="descskill3"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval3" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill3Grid" class="col-3" id="skill3grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill3Grid" name="skill3Grid" disabled>
                                </div>
							   </label>
							   <label for="skill3Var" class="col-4" id="skill3grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill3Var" name="skill3Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	
						
						<div>							
						<div id="hiddenskill5">
                                <label for="skillval5" class="col-4">
                                   <span id="descskill5"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval5" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill5Grid" class="col-3" id="skill5grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill5Grid" name="skill5Grid" disabled>
                                </div>
							   </label>
							   <label for="skill5Var" class="col-4" id="skill5grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill5Var" name="skill5Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	
							
							<div>	
						<div id="hiddenskill7">
                                <label for="skillval7" class="col-4">
                                   <span id="descskill7"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval7" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill7Grid" class="col-3" id="skill7grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill7Grid" name="skill7Grid" disabled>
                                </div>
							   </label>
							   <label for="skill7Var" class="col-4" id="skill7grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill7Var" name="skill7Var" disabled>
									</div>
							   </label>
                            </div>
							</div>
								
								<div>	
							<div id="hiddenskill9">
                                <label for="skillval9" class="col-4">
                                   <span id="descskill9"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval9" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill9Grid" class="col-3" id="skill9grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill9Grid" name="skill9Grid" disabled>
                                </div>
							   </label>
							   <label for="skill9Var" class="col-4" id="skill9grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill9Var" name="skill9Var" disabled>
									</div>
							   </label>
                            </div>
						</div>	
					
						
						</div>
						
						<div class="col-sm-6 vl">
							<div>					
							<div id="hiddenskill2" >
                                <label for="skillval2" class="col-4">
                                    <span id="descskill2"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval2" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill2Grid" class="col-3" id="skill2grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill2Grid" name="skill2Grid" disabled>
                                </div>
							   </label>
							   <label for="skill2Var" class="col-4" id="skill2grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill2Var" name="skill2Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	

							<div>	
							<div id="hiddenskill4">
                                <label for="skillval4" class="col-4">
                                    <span id="descskill4"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval4" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill4Grid" class="col-3" id="skill4grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill4Grid" name="skill4Grid" disabled>
                                </div>
							   </label>
							   <label for="skill4Var" class="col-4" id="skill4grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill4Var" name="skill4Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	
							
							<div>	
							<div id="hiddenskill6">
                                <label for="skillval6" class="col-4">
                                   <span id="descskill6"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval6" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill6Grid" class="col-3" id="skill6grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill6Grid" name="skill6Grid" disabled>
                                </div>
							   </label>
							   <label for="skill6Var" class="col-4" id="skill6grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill6Var" name="skill6Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	
							
							<div>	
							<div id="hiddenskill8">
                                <label for="skillval8" class="col-4">
                                   <span id="descskill8"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval8" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill8Grid" class="col-3" id="skill8grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill8Grid" name="skill8Grid" disabled>
                                </div>
							   </label>
							   <label for="skill8Var" class="col-4" id="skill8grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill8Var" name="skill8Var" disabled>
									</div>
							   </label>
                            </div>
							</div>
							
							<div>								
							<div id="hiddenskill10">
                                <label for="skillval10" class="col-4">
                                   <span id="descskill10"></span>
                                
                                <div>
                                    <input type="number" min="0" class="form-control number" id="skillval10" onchange="tj.gridupdate2whp()" style="text-align: right">
                                </div>
								</label>
								<label for="skill10Grid" class="col-3" id="skill10grid1">
                                 Grid:
								
                                <div>
                                  <input type="text" class="form-control" style="border:none" id="skill10Grid" name="skill10Grid" disabled>
                                </div>
							   </label>
							   <label for="skill7Var" class="col-4" id="skill10grid2">
								Variance:
									
									<div>
									  <input type="text" class="form-control" style="border:none" id="skill10Var" name="skill10Var" disabled>
									</div>
							   </label>
                            </div>
							</div>	
							
							</div>
							</div>
						</div>	
						</div>	
				
						<div class="row">
						<div class="col-6">
							
							<div id="hiddenskill11">
                                <label for="skillval11" class="col-form-label">
                                   <span id="descskill11"></span>:
                                
                                <div class="col-6">
                                    <input type="number" min="0" class="form-control number" id="skillval11" style="text-align: right">
                                </div>
								</label>
                            </div>
							</div>
						
						</div>
						
						
						
						<div class="form-group">
						<div class="bg-light">
						<div class="row" id="hidegridvariancewhp">
						
						<div class="col-6">
							
							<label id="hidegridhrs" for="gridhrs" class="col-6">
                                Grid Hours
									<div>			
                                 <input type="text" class="form-control" id="gridhrs" style="text-align: right" disabled>
								</div>
								 </label>	
							<label id="hideworkedhrs" for="workedhrs" class="col-6 pull-right">
                                Worked Hours 
					 
								<div>
                                  <input type="text" class="form-control" id="workedhrs" style="text-align: right" disabled>
								  </div>
                            </label>
												 
						  </div>
						  <div class="col-6 vl">
							
							<label for="showgridvariancewhp" class="col-6">
                                <span id="varName2whp"></span>  
									<div>
                                  <input type="number" class="form-control" id="showgridvariancewhp" style="text-align: right" disabled>
								  </div>
                            </label>
													
							</div>
							
						  </div>
						  </div>
						   </div>
						   
							
						<div id="showtrackwhp">
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Data Tracking </h5>
						</div>
						<!--
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title">In-Shift Data Tracking </h5>
						</div>
						-->
						<div class="bg-light">
					<div class="form-group">
						<div class="row">
						<div class="col-md" id="hidetrack1whp">
                                <label for="track1whp" class="col-form-label">
                                   <span id="track1Descwhp"></span>
                               
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track1whp">
                                </div>
								
                            	<div class="col-12">					
						<textarea class="form-control" rows="2" placeholder="Note" id="trackNote1whp"></textarea>
                            </div>
							</label>
							</div>
							
							<div class="col-md" id="hidetrack2whp">
                                <label for="track2whp" class="col-form-label">
                                   <span id="track2Descwhp"></span>
                               
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track2whp">
                                </div>
								
                            	<div class="col-12">					
						<textarea class="form-control" rows="2" placeholder="Note" id="trackNote2whp"></textarea>
                            </div>
							</label>
							</div>
							</div>
							</div>
							
						<div class="form-group">
						<div class="row">
						<div class="col-md" id="hidetrack3whp">
                                <label for="track3whp" class="col-form-label">
                                   <span id="track3Descwhp"></span>
                               
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track3whp">
                                </div>
								
                            	<div class="col-12">					
						<textarea class="form-control" rows="2" placeholder="Note" id="trackNote3whp"></textarea>
                            </div>
							</label>
							</div>
							
							<div class="col-md" id="hidetrack4whp">
                                <label for="track4whp" class="col-form-label">
                                   <span id="track4Descwhp"></span>
                               
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="track4whp">
                                </div>
								
                            	<div class="col-12">					
						<textarea class="form-control" rows="2" placeholder="Note" id="trackNote4whp"></textarea>
                            </div>
							</label>
							</div>
							</div>
							</div>
							
							</div>
						</div>
															
						<div class="p-3 mb-2 bg-info">
						<h5 class="modal-title" style="color:white"><span id="actionPlanwhp"></span></h5>
						</div>
						<div class="bg-light">
							<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
						<div class="form-group">
						
						<!--
							<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
                              <h5 class="modal-title">Barriers / Action Plan</h5>
								</div>
								-->
							
                                <textarea class="form-control" rows="3" maxlength="350" id="prodnoteWHP"></textarea>
                        <div id="submittedwhp">
                                <label for="submittedbywhp" style="color:red">
                                    Submitted By (Required):
                                 <div>
                                    <input type="text" class="form-control" id="submittedbywhp">
                                </div>
								</label>
                            </div>
							</div>					
						   </div>
						   </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdWHP();" <?php echo $exec; ?>>Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>
 
 	<div class="modal fade" id="addprodnotewhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdwhp" type="hidden" value="" />
						<input id="notedeptIdwhp" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodywhp">Your Staffing Report shows a possible <span id="varianceTypewhp"></span> variance for <span id="deptwhp"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<textarea class="form-control" rows="3" maxlength="350" id="notebodywhp" ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" id="noteButtonwhp" class="btn btn-primary" onclick="tj.addProdNotewhp();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
	
<div class="modal fade" id="escalationNEWwhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="escwhp" type="hidden" value="" />
				<input id="deptIdescwhp" type="hidden" value="" />
				<input id="dataIdescwhp" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require Escalation (ie. Staffing, Supplies, Close Calls, Team Member Recognition, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer bg-light">
					<button type="button" id="noescbuttonwhp" class="btn btn-secondary" onclick="tj.linkedDept();">No</button>
                    <button type="button" id="addescbuttonwhp" class="btn btn-primary" data-target="#addEscalationwhp" data-toggle="modal">Yes</button>
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
   
	
<div class="modal fade" id="addEscalationwhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2whp" type="hidden" value="" />
						<input id="deptId2whp" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escvalwhp">Use this tool to Escalate an issue or Recognize a Team Member.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escvalwhp" name="escvalwhp" class="form-control">
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
                                <label for="escalationcommentwhp">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcommentwhp" ></textarea>
                            </div>
							<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
					<button type="button" id="Escbuttonwhp" class="btn btn-secondary" onclick="tj.linkedDept2();">Cancel</button>
                    <!--<button type="button" id="Escbuttonwhp" class="btn btn-secondary" data-dismiss="modal">Cancel</button>-->
                    <button type="button" id="EscAddbuttonwhp" class="btn btn-primary" onclick="tj.addEscalationwhp();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3whp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3whp" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3whp">Use this tool to Escalate an issue or Recognize a Team Member.  The escalation will appear on the dashboard and your manager will be notified via text and/or email if they are configured to receive notifications.</label>
                              	
										<select id="escval3whp" name="escval3" class="form-control">
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
                                <label for="deptId3whp">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3whp" name="deptId3whp" class="form-control">
										<option value="0">Select Dept</option>
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.active=1 AND d.prodMeasure=2 AND ((u.role=11 AND d.accountId={$_SESSION['account']['accountId']}) OR (d.id in (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}))) group by d.id order by d.dept ASC");
								foreach($dbUnit as $b) 
								{ 
								?>
								<option value="<?php echo $b['id'];?>"><?php echo $b['dept'];?> </option>
								<?php 
								} 
								?>								
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="escalationcomment3whp">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcomment3whp" ></textarea>
                            </div>
							<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" id="Escbutton3whp" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton3whp" class="btn btn-primary" onclick="tj.addEscalation3whp();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<div class="modal fade" id="addDaywhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Day Rating</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dayrateIdwhp" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">	
								 
						 <div class="modal-title"><span id="dayUpdatedwhp"></span></div>
						 <div class="modal-title"><span id="dayUserwhp"></span></div>
						 
					   </div>
					   <div class="form-group form-md-line-input">
                                <label for="dayDatewhp">Select Date:</label>
                              	 <input id="dayDatewhp" type="date" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayUnitwhp">Select Unit:</label>
                              			<select id="dayUnitwhp" name="dayUnitwhp" class="form-control" onchange="tj.checkdaywhp();">
										<option value="">Select</option>
										<?php foreach($dbUnits as $u) { ?>
										<option value="<?php echo $u['deptId'];?>"><?php echo $u['dept'];?> </option>
										<?php 	} ?>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input" hidden>
                                <label for="daySDwhp">Service Deliverable Achieved?</label>
                              			<select id="daySDwhp" name="daySDwhp" class="form-control">
										<option value="0">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayEEwhp">Rate the Day for Employee Engagement:</label>
                              			<select id="dayEEwhp" name="dayEEwhp" class="form-control">
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
                                <label for="dateNew">Next available date for New Patients:</label>
                              	 <input id="dateNew" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dateExisting">Next available date for Existing Patients:</label>
                              	 <input id="dateExisting" type="date" min="<?php echo $todayDate;?>" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayReferrals">Referrals in the queue:</label>
                              	<input type="number" min="0" class="form-control number" id="dayReferrals" style="text-align: right">								 
										
                            </div>
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updatedayRatingwhp();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="editEscalationwhp" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="dataIdeditwhp" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">
                                <span id="editSubmittedwhp" ></span>	
							</div>
                            <div class="form-group">
                                <label for="edittypewhp">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="edittypewhp" value="" disabled>				
                            </div>
							</div>
							<div class="form-group">
                                <label for="editunitwhp">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="editunitwhp" value="" disabled>	
							</div>
							</div>
							<div class="form-group">
                                <label for="escalationcommenteditwhp">Comments:</label>
                                <textarea class="form-control" rows="3" id="escalationcommenteditwhp" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group" hidden>
                                <label for="escalationresponsewhp"><strong>Response:</strong></label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
							</div>
                                <textarea class="form-control" rows="3" id="escalationresponsewhp" ></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary" onclick="tj.updateEscalationwhp();">Submit</button>-->
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>





    <!--end::Modal-->


