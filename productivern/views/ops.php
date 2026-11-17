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
                Productivity Reports - Ops
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
			
                                <!--end: Dropdown-->
                            </div>
				 
				     <button type="button" id="escButtoncli" class="btn btn-danger pull-right" data-target="#addEscalation3cli" data-toggle="modal"  <?php echo $hidden; ?>>
                                            Escalation
                                        </button>   
					
				</div>	 
                             <!--end: Dropdown-->
					<div class="row col-12">
                        <table id="prodTablecli"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="20%" data-priority="1">Unit</th>
									<th width="15%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="4">UOS</th>
									<th width="20%" data-priority="5">Total Resources</th>	
									<th width="10%" data-priority="3">Variance</th>	
									<th width="25%" data-priority="6">Note</th>
                                </tr>
                            </thead>
                        </table>
						</div>
						</div>
                   </div>
    <!--End::Main Portlet-->
</div>


<!-- /begin modal -->
<div class="modal fade" id="addOPS" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-12">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header bg-info">
				  <h5 class="modal-title" style="color:white"><strong>STAFFING REPORT</strong>
				  </h5>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        
						
						<div class="col-6 modal-title">Report Date/Shift:  <span id="reportdateOPS"></span> - <span id="reportshiftOPS"></span>
				  </div>
						
                           
							<div class="col-6 modal-title">Last Updated By:   <span id="userNameOPS"></span>
							</div>							
							
							<div class="col-6 modal-title">Variance:   <span id="varianceOPS"></span>
							</div>
							<!--
							<div class="modal-title">Open Beds:   <span id="openbedsOPS"></span>
							</div>
							
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formOPS" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
                        <input id="userIdOPS" type="hidden" value="" />
						<input id="shiftOPS" type="hidden" value="" />
						<input id="dayOPS" type="hidden" value="" />
						<input id="dataIdOPS" type="hidden" value="" />
						<input id="opsoneto1Acuity" type="hidden" value="" />
						<input id="opsoneto2Acuity" type="hidden" value="" />
						<input id="opsoneto3Acuity" type="hidden" value="" />
						<input id="opsoneto4Acuity" type="hidden" value="" />
						<input id="opsoneto5Acuity" type="hidden" value="" />
						<input id="opsoneto6Acuity" type="hidden" value="" />
						<input id="deptIdOPS" type="hidden" value="" />
						<input id="shiftOPS" type="hidden" value="" />			
						<input id="acuityTotal" type="hidden" value="" />
						<input id="trackopsDC" type="hidden" value="" />
						<input id="trackopsAdd" type="hidden" value="" />
						<input id="trackopsInsert" type="hidden" value="" />
						<input id="useopsGrid" type="hidden" value="" />
						<input id="countchargereport" type="hidden" value="" />
						<input id="rnopsreport" type="hidden" value="" />
						<input id="rnops1report" type="hidden" value="" />
						<input id="rnops2report" type="hidden" value="" />
						<input id="showrnreport" type="hidden" value="" />
						<input id="submitreqd" type="hidden" value="" />
						<input id="varmsg" type="hidden" value="" />
						<input id="roundRN" type="hidden" value="" />
						<input id="roleOPS" type="hidden" value="" />
						
					<div class="m-portlet__body">
					<div class="p-2 mb-2 bg-info">
					<h5 class="modal-title" style="color:white"><span id="patientcountTitle"></span></h5>
					
					</div>
					
					<div class="form-group" id="opshiddentotal1">
					<div class="bg-light">
					<div class="row col-lg">							
					<div class="col-md">
					<div class="col-9">
                                <label for="patienttotalOPS" class="col-form-label">
                                    <span id="patientTotalDescops"></span>
                                
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="patienttotalOPS"  onchange="tj.gridupdateops()" style="text-align: right" />
                                </div>
								</div>
					</label>			
                    </div>		
					<div class="col-md vl">
					<span id="visits1"></span>
					<span id="discharges1"></span>
					</div>
					</div>
					<div class="row col-lg" id="currentPatients2">							
					<div class="col-md">
					<div class="col-9">
                                <label for="patienttotalOPS2" class="col-form-label">
                                    <span id="patientTotalDescops2"></span>
                                
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="patienttotalOPS2"  onchange="tj.gridupdateops()" style="text-align: right" />
                                </div>
								</div>
					</label>			
                    </div>
					<div class="col-md vl">
					
					</div>					
					
					</div>
					</div>
					</div>
					
						
							
							<div class="modal-title" id="opshiddentotal2"><strong>Patient counts in this section must add up to Current Patients.</strong>
							</div>
							<!--<span id="rnacuity"></span>-->
							
							<div class="form-group">
							<div class="bg-light">
							<div class="row col-lg">							
							
							<div class="col-md">
							<div id="opshidden1" >
							<div class="col-9">
                                <label for="opsoneto1" class="col-form-label">
                                    <span id="descops1"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto1" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								</label>
                            </div>
							</div>							
							<div id="opshidden3">
							<div class="col-9">
                                <label for="highOPS" class="col-form-label">
                                    <span id="descops3"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="highOPS" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								</label>
							</div>
							</div>
							<div id="opshidden5">
							<div class="col-9">
                                <label for="lowOPS" class="col-form-label">
                                    <span id="descops5"></span>
                                 <div class="col-9">
								<input type="number" min="0" class="form-control number" id="lowOPS" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								 </label>
                           </div>
							</div>
							
							</div>
							<div class="col-md vl">
							<div id="opshidden2" >
							<div class="col-9">
                                <label for="opsoneto2" class="col-form-label">
                                    <span id="descops2"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto2" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								</label>
								</div>
                            </div>
							
							
							<div id="opshidden4">
							<div class="col-9">
                                <label for="medOPS" class="col-form-label">
                                    <span id="descops4"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="medOPS" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								</label>
								</div>
                            </div>
						
							
							<div id="opshidden6">
							<div class="col-9">
                                <label for="opsoneto6" class="col-form-label">
                                    <span id="descops6"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto6" style="text-align: right" onchange="tj.gridupdate2ops()" />
                                </div>
								</label>
								</div>
                            </div>						
							<span id="visits2"></span>
							<span id="discharges2"></span>
							
							</div>
							</div>
							</div>
							</div>
							
							<div class="modal-title"><strong><span id="acuityDescops"></span></strong>
							</div>
							
							<div class="form-group">
							<div class="bg-light">
							<div class="row col-lg">
							<div class="col-md">
							<div class="col-9" id="opshidden7">
                                <label for="opsoneto7" class="col-form-label">
                                    <span id="descops7"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto7" style="text-align: right" />
                                </div>
								</label>
							</div>
							<div class="col-9" id="opshidden9">
                                <label for="opsoneto9" class="col-form-label">
                                    <span id="descops9"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto9" style="text-align: right" />
                                </div>
								</label>
							</div>
							<div class="col-9" id="opshidden11">
                                <label for="opsoneto11" class="col-form-label">
                                    <span id="descops11"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto11" style="text-align: right" />
                                </div>
								</label>
							</div>
							</div>	
						
							<div class="col-md vl">
							<div class="col-9" id="opshidden8">
                                <label for="opsoneto8" class="col-form-label">
                                    <span id="descops8"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto8" style="text-align: right" />
                                </div>
								</label>
								</div>
						
							<div class="col-9" id="opshidden10">
                                <label for="opsoneto10" class="col-form-label">
								
                                    <span id="descops10"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto10" style="text-align: right" />
                                </div>
								</label>
								</div>
							<div class="col-9" hidden>
                                <label for="opsoneto12" class="col-form-label">
								
                                    <span id="descops12"></span>
                                <div class="col-9">
								<input type="number" min="0" class="form-control number" id="opsoneto12" style="text-align: right" />
                                </div>
								</label>
								</div>
                            </div>
							</div>
							</div>							
          
							</div>
						
							
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Resources Currently in Staffing </h5>
						</div>
						
						<div class="form-group">
						<div class="bg-light">
						<div class="row col-lg">
						<div class="col-6">
						<div>
						<div id="charge0">
						
                                <label for="chargecountOPS" class="col-5">
                                   <span id="chargeDescops"></span>
								
                                <div>
                                    <select id="chargecountOPS" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										
										<option value="0.5">0.5</option>
										<option value="1">1</option>
										<option value="2">2</option>
										</select>
                                </div>
						   </label>
						   
						   <label for="cnopsGrid" class="col-3" id="cnopsgrid1">
                                   Grid:
								
                                <div>
                                  <input type="text" class="form-control" id="cnopsGrid" name="cnopsGrid" disabled>
                                </div>
						   </label>
						   <label for="cnVariance" class="col-3" id="cnopsgrid2">
                                   Variance:
								
                                <div>
                                  <input type="text" class="form-control" id="cnVariance" name="cnVariance" disabled>
                                </div>
						   </label>
						   
                            </div>
							</div>
						<div>
						<div id="Nurse1">
						
                                <label for="nurse1_add" class="col-5">
                                   <span id="nurse1Descops"></span>
                                
                                <div>
                                    <select id="nurse1_add" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="rn1opsGrid" class="col-3" id="rn1opsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="rn1opsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rnops1Variance" class="col-3" id="rn1opsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="rnops1Variance" style="text-align: left" disabled>
                                </div>
						   </label>					 
                            </div>
							</div>	
		
							<div>
							<div id="techView">
                                <label for="techcountOPS" class="col-5">
                                   <span id="techLabel"></span>
                                
                                <div>
                                    <select id="techcountOPS" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							 <label for="pctopsGrid" class="col-3" id="pctopsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="pctopsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="pctVariance" class="col-3" id="pctopsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="pctVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						  </div>
                            </div>
						<div>
						<div id="other1hide">
                                <label for="other1" class="col-5">
                                   <span id="other1Descops"></span>
                                
                                <div>
                                    <select id="other1" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other1opsGrid" class="col-3" id="other1opsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other1opsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other1Variance" class="col-3" id="other1opsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other1Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							<div>
						<div id="other2hide">
                                <label for="other2" class="col-5">
                                   <span id="other2Descops"></span>
                                
                                <div>
                                    <select id="other2" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other2opsGrid" class="col-3" id="other2opsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other2opsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other2Variance" class="col-3" id="other2opsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
							</div>
						<div class="col-6 vl">
						<div>
						<div id="nurse0">	
					
                                <label for="nursecountOPS" class="col-5">
                                    <span id="nurseDescops"></span>
                                
                                <div>
                                    <select id="nursecountOPS" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
							<label for="rnopsGrid" class="col-3" id="rnopsgrid1">
                                 Grid: 
								
                                <div>
                                  <input type="text" class="form-control" id="rnopsGrid" name="rnopsGrid" disabled>
                                </div>
						   </label>
						   <label for="rnopsVariance" class="col-3" id="rnopsgrid2">
                                 Variance:  
								
                                <div>
                                  <input type="text" class="form-control" id="rnopsVariance" name="rnVariance" disabled>
                                </div>
						   </label>
								
                            </div>
						</div>
							<div>
							<div id="Nurse2">
                                <label for="nurse2_add" class="col-5">
                                   <span id="nurse2Descops"></span>
                                
                                <div>
                                    <select id="nurse2_add" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="rn2opsGrid" class="col-3" id="rn2opsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="rn2opsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="rnops2Variance" class="col-3" id="rn2opsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="rnops2Variance" style="text-align: left" disabled>
                                </div>
						   </label>
							</div>
                            </div>
						
						<div>
						<div id="secView">
                                <label for="seccountOPS" class="col-5">
                                   <span id="secLabel"></span>
                                
                                <div>
                                    <select id="seccountOPS" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										</select>
                                </div>
							</label>
							<label for="secopsGrid" class="col-3" id="secopsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="secopsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="secVariance" class="col-3" id="secopsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="secVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						<div>
						<div id="sittersOPShide">
                                <label for="sittersOPS" class="col-5">
									<span id="sittersOPSDesc"></span>
                                
                                <div>
                                    <select id="sittersOPS" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="sitteropsGrid" class="col-3" id="sitteropsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="sitteropsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="sitterVariance" class="col-3" id="sitteropsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="sitterVariance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                           </div>
						   <div>
						<div id="other3hide">
                                <label for="other3" class="col-5">
                                   <span id="other3Descops"></span>
                                
                                <div>
                                    <select id="other3" type="select" onchange="tj.gridupdate2ops()" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										</select>
                                </div>
								</label>
								<label for="other3opsGrid" class="col-3" id="other3opsgrid1">
                                Grid:  
								
                                <div>
                                  <input type="text" class="form-control" id="other3opsGrid" style="text-align: left" disabled>
                                </div>
						   </label>
						   <label for="other3Variance" class="col-3" id="other3opsgrid2">
                                Variance:   
								
                                <div>
                                  <input type="text" class="form-control" id="other3Variance" style="text-align: left" disabled>
                                </div>
						   </label>
						   </div>
                            </div>
						
										
							</div>
							</div>
							</div>
							</div>
						<div class="form-group">
						<div class="bg-light">
						<hr></hr>
						<div class="row col-lg">
						
						<div class="col-6">
						<div id="hidernopsvariance">
				
							<label for="showrnopsvariance" class="col-9">
                                <span id="varName1"></span> 
												
                                 <input type="text" class="col-6 form-control" id="showrnopsvariance" style="text-align: right" disabled>
								 <span id="varComment"></span>
								 </label>		
								
								 </div>
							</div>
						   <div class="col-6 vl">
							<div id="hideopsgridrnvariance">
							
							<label for="showopsgridrnvariance" class="col-9">
                                <span id="varName3"></span>  
					 
								
                                  <input type="text" class="col-6 form-control" id="showopsgridrnvariance" style="text-align: right" disabled>
                            </label>
							
							</div>
							<div id="hideopsgridvariance">
							<label for="showopsgridvariance" class="col-9">
                                <span id="varName2"></span>  
					 
								
                                  <input type="text" class="col-6 form-control" id="showopsgridvariance" style="text-align: right" disabled>
                            </label>
							</div>
							 
						  </div>
						  
						   </div>
						   </div>
							</div>
						</div>
						<div id="showtrackops">
						
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">In-Shift Data Tracking </h5>
						</div>
						<div class="bg-light">						
						<div class="form-group">
						<div class="row">
						<div class="col-md">
						<div id="hidetrackops1">
                                <label for="trackops1">
                                   <span id="track1Descops"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="trackops1">
                                </div>
								
								<div class="col-12">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackopsNote1"></textarea>
                            </div>
							</label>
						</div>
						<div id="hidetrackops3">
                                <label for="trackops3">
                                   <span id="track3Descops"></span>
                                
                                <div class="col-6">
                                 <input type="number" min="0" class="form-control number" id="trackops3">
                                </div>
								
								<div class="col-12">					
								<textarea class="form-control" rows="2" placeholder="Note" id="trackopsNote3"></textarea>
                            </div>
							</label>
						</div>
						
						</div>
							
						<div class="col-md vl" >
						
						<div id="hidetrackops2">
							<label for="trackops2">
                                   <span id="track2Descops"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="trackops2">
                                </div>
								
								<div class="col-12">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackopsNote2"></textarea>
							</div>
							</label>
                        </div>
						<div id="hidetrackops4">
							<label for="trackops4">
                                   <span id="track4Descops"></span>
                                
                                <div class="col-6">
								<input type="number" min="0" class="form-control number" id="trackops4">
                                </div>
								
								<div class="col-12">
								<textarea class="form-control" rows="2" placeholder="Note" id="trackopsNote4"></textarea>
							</div>
							</label>
                        </div>
						</div>
											
						</div>
						</div>
						</div>
						</div>
							
						<div id="showchurn1">
						
						<div class="p-2 mb-2 bg-info">
						<h5 class="modal-title" style="color:white">Churn </h5>
						</div>
						
							<div class="bg-light">
							<div class="form-group">
							<div class="row">
							<div class="col-md">
							<label for="admissions1">
                                   Admissions:
                                
                                <div>
                                 <input type="number" class="form-control number" id="admissions1">
                                </div>
								</label>
								</div>
							<div class="col-md">
                                <label for="transfers1">
                                   Transfers:
                                
                                <div>
								<input type="number" class="form-control number" id="transfers1">
                                </div>
								</label>
                            </div>
							<div class="col-md">
                                <label for="discharges1">
                                    Discharges:
                                 <div>
                                    <input type="number" class="form-control number" id="discharges1">
                                </div>
								</label>
                            </div>
							</div>
							</div>
							
							<div class="form-group m-form__group m--margin-top-5 row" id="customChurn1">
                                <label for="customChurn" class="col-7 col-form-label">
                                    <span id="customChurnLabel"></span>
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" id="customChurn">
                                </div>
                            </div>
							</div>
						</div>
					
							<div class="p-2 mb-2 bg-info">
                              <h5 class="modal-title" style="color:white"><span id="actionPlan"></span></h5>
								</div>
								<div class="bg-light">
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" maxlength="250" rows="3" id="prodnoteOPS"></textarea>
                            </div>
							<div id="submitted">
                                <label for="submittedby" style="color:red">
                                    Submitted By (Required):
                                 <div>
                                    <input type="text" class="form-control" id="submittedby" placeholder="Enter your name here.">
                                </div>
								</label>
								<span><a href="javascript:;" onclick="tj.showSubmit2();" id="showSubmit2OPS" ><u>Show Name Selector</u></a></span>
                            </div>
							<div id="submittedOPS">
                                <label for="submittedbyOPS" style="color:red">
                                    Submitted By New(Required):
                                 <div>
                                   <select id="submittedbyOPS" type="select" class="form-control">
									<option value="0">Select User</option>
															
								<?php
								
								if(intval($role)>7){
								$dbuser = Config::get('db')->get_results("SELECT x.*, CONCAT(u.last_name, ', ', u.first_name) as newName from `productiveDeptXref` x LEFT JOIN `productiveUser` as u on u.id=x.userId where u.accountId={$accountId} AND u.role<8 AND u.practiceId=0 AND u.active>0 and u.last_name !='' group by x.userId order by u.last_name ASC, u.first_name");	
								}else{
								$dbuser = Config::get('db')->get_results("SELECT x.*, CONCAT(u.last_name, ', ', u.first_name) as newName from `productiveDeptXref` x LEFT JOIN `productiveUser` as u on u.id=x.userId where x.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND u.accountId={$accountId} AND u.role<8 AND u.practiceId=0 AND u.active>0 and u.last_name !='' group by x.userId order by u.last_name ASC, u.first_name");								
								}
								foreach($dbuser as $x) 
								{ 
								?>
								<option value="<?php echo $x['userId'];?>"><?php echo $x['newName'];?> </option>
								<?php 
								} 
								?>
							</select>
                                </div>
								</label>
								<span><a href="javascript:;" onclick="tj.showSubmitOPS();" ><u>Can't Find Your Name?</u></a></span>
                            </div>
											
						   </div>
						   </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdOPS();" <?php echo $exec; ?>>Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
 </div>

 	<div class="modal fade" id="visits" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Productivity Calculator</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="visitsdataId" type="hidden" value="" />
						<input id="visitsaccountId" type="hidden" value="" />
						<input id="visitsdeptId" type="hidden" value="" />
						<input id="visitsDate" type="hidden" value="" />
						<input id="hoursrqd" type="hidden" value="" />
						
						<div class="form-body">
						<div class="title" style="padding-bottom: 20px;">
						Use this feature to calculate and over-write productivity and variance values for the entire day.  All in-shift productivity and hours variance estimates will be replaced with this value.
						</div>
                            <div class="form-group form-md-line-input">
                                <label for="visitTotal"><strong>Enter <span id="visitsType"></span> for <span id="visitsdayDate"></span>:</strong></label>
                                <input type="number" min="0" class="form-control number col-6" id="visitTotal">
                            </div>
							<hr></hr>
							<div class="form-group form-md-line-input" id="hrs0Total">
                                <label for="hoursTotal"><strong>Enter Total Hours for <span id="hoursdayDate"></span>:</strong></label>
								<span id="hoursReqd"></span>
								<input type="number" min="0" class="form-control number col-6" id="hoursTotal">
                            </div>
							<div class="form-group form-md-line-input" id="hrs1Total">
                                <label for="hrs1"><strong>Enter <span id="hrs1Descops"></span> for <span id="hoursdayDate1"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs1">
                            </div>
							<div class="form-group form-md-line-input" id="hrs2Total">
                                <label for="hrs2"><strong>Enter Total <span id="hrs2Descops"></span> for <span id="hoursdayDate2"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs2">
                            </div>
							<div class="form-group form-md-line-input" id="hrs3Total">
                                <label for="hrs3"><strong>Enter Total <span id="hrs3Descops"></span> for <span id="hoursdayDate3"></span>:</strong></label>
								<input type="number" min="0" class="form-control number col-6" id="hrs3">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.visits();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

	<div class="modal fade" id="addprodnote" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataId" type="hidden" value="" />
						<input id="notedeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebody">Your Staffing Report shows a possible <span id="varianceType"></span> variance for <span id="dept"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>	
								<textarea class="form-control" rows="3" maxlength="250" id="notebody" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addProdNote();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
 
	<div class="modal fade" id="addprodnoteOrig" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Variance Action Required</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="notedataIdOrig" type="hidden" value="" />
						<input id="notedeptIdOrig" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="notebodyOrig">Your Staffing Report shows a possible <span id="varianceTypeOrig"></span> variance for <span id="deptOrig"></span>.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control" rows="3" maxlength="250" id="notebodyOrig" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addProdNoteOrig();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>

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
										<option value=1>6</option>
										<option value=2>7</option>
										<option value=3>8</option>
										<option value=4>9</option>
										<option value=5>10</option>
										<option value=1>11</option>
										<option value=2>12</option>
										<option value=3>13</option>
										<option value=4>14</option>
										<option value=5>15</option>
										<option value=1>16</option>
										<option value=2>17</option>
										<option value=3>18</option>
										<option value=4>19</option>
										<option value=5>20</option>
										</select>
                                </div>
                            </div>
												
                            <div class="form-group form-md-line-input">
                                <label for="blockedcomment">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="blockButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="escalationOPS" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="escops" type="hidden" value="" />
				<input id="deptIdescops" type="hidden" value="" />
				<input id="dataIdescops" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require Escalation (ie. Staffing, Supplies, Close Calls, Team Member Recognition, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer bg-light">
					<button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" id="addescbutton" class="btn btn-secondary" onclick="tj.showaddesc();">Yes</button>
                    <!--<button type="button" id="addescbutton" class="btn btn-primary" data-target="#addEscalation" data-toggle="modal">Yes</button>-->
                </div>
				</div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
   
	
<div class="modal fade" id="addEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId2" type="hidden" value="" />
						<input id="deptId2" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval">Use this tool to Escalate an issue or Recognize a Team Member.  Your manager will be immediately notified via text and/or email.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escval" name="escval" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `escalation` ASC");
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
                                <label for="escalationcomment">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment" ></textarea>
                            </div>
							
				
					  

                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" id="Escbutton" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton" class="btn btn-primary" onclick="tj.addEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="addEscalation3" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dataId3" type="hidden" value="" />
						<div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="escval3">Use this tool to Escalate an issue or Recognize a Team Member.  Your manager will be immediately notified via text and/or email.  Also, please document this issue as required on your Unit.</label>
                              	
										<select id="escval3" name="escval3" class="form-control">
										<option value="0">Select Escalation</option>
										<?php
								$dbData = Config::get('db')->get_results("SELECT * from `productiveAcctEscalations` where `accountId`={$_SESSION['account']['accountId']} order by `escalation` ASC");
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
                                <label for="deptId3">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3" name="deptId3" class="form-control">
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.*, u.role from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id LEFT JOIN `productiveUser` as u on u.id={$_SESSION['account']['id']} where d.escalations=0 AND d.accountId={$_SESSION['account']['accountId']} AND (x.deptId in (SELECT `deptId` from `productiveDeptXref`where `userId`={$_SESSION['account']['id']}) OR u.role=11) group by d.id order by d.dept ASC");
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
                                <label for="escalationcomment3">Comments:</label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment3" ></textarea>
                            </div>
							
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" id="Escbutton3" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="EscAddbutton3" class="btn btn-primary" onclick="tj.addEscalation3();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="editEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Escalation Response</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="dataIdedit" type="hidden" value="" />
						<div class="form-body">
							<div class="form-group">
                            <span id="editSubmitted" ></span>	
							</div>
							<div class="form-group">
                            <label for="editunit">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="editunit" value="" disabled>	
							</div>
							</div>
                            <div class="form-group">
                            <label for="edittype">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="edittype" value="" disabled>
							</div>							 
                            </div>
							
							<div class="form-group">
                                <label for="escalationcommentedit">Comments:</label>
                                <textarea class="form-control" rows="4" id="escalationcommentedit" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group">
                                <label for="escalationresponse"><strong>Response:</strong></label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
							</div>
                                <textarea class="form-control" rows="3" id="escalationresponse" ></textarea>
                            </div>			  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateEscalation();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="addDay" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Day Rating</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="dayrateId" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">	
								 
						 <div class="modal-title"><span id="dayUpdated"></span></div>
						 <div class="modal-title"><span id="dayUser"></span></div>
						 
					   </div>
					   <div class="form-group form-md-line-input">
                                <label for="dayDate">Select Date:</label>
                              	 <input id="dayDate" type="date" value="" />		
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayUnit">Select Unit:</label>
                              			<select id="dayUnit" name="dayUnit" class="form-control" onchange="tj.checkday();">
										<option value="">Select</option>
										<?php foreach($dbUnit as $u) { ?>
										<option value="<?php echo $u['id'];?>"><?php echo $u['dept'];?> </option>
										<?php 	} ?>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="daySD">Service Deliverable Achieved?</label>
                              			<select id="daySD" name="daySD" class="form-control">
										<option value="0">Select</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
										</select>
										
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="dayEE">Rate the Day for Employee Engagement:</label>
                              			<select id="dayEE" name="dayEE" class="form-control">
										<option value="0">Select</option>
										<option value="5">5 - Excellent</option>
										<option value="4">4 - Above Average</option>
										<option value="3">3 - Average</option>
										<option value="2">2 - Below Average</option>
										<option value="1">1 - Poor</option>										
										</select>
										
                            </div>				
				
					  
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updatedayRating();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

