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
$userId = intval($_SESSION['account']['id']);



$dbText = Config::get('db')->get_results("SELECT d.*, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.deptId IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) and d.escalations=0 and (x.primaryUnit=6 or x.primaryUnit=7)");

if($dbText || $role==11){
	$hidden = '';
}else{
	$hidden = "hidden";
}
/*
$dbHppd = Config::get('db')->get_results("SELECT d.*, count(d.id) as countId, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.userId={$userId} AND d.prodMeasure=2 group by d.accountId");

if($dbHppd && $role <=6){
	$dbData = $dbHppd[0];
	$count = intval($dbData['countId']);
}else{
	$count = 0;
}
*/

?>
<style>
.modal {
    overflow-y: scroll;
}
</style>
<!-- BEGIN: Subheader -->
<div class="m-subheader bg-light" style="padding-bottom: 30px;">
		<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Reports - Nursing
            </h3>
        </div>

<div class="d-flex pull-right">

			<?php if($role>12) {
			$dbLocation = Config::get('db')->get_results("SELECT a.* FROM `productiveAccount` a WHERE a.enterpriseId={$_SESSION['account']['enterpriseId']} group by a.id order by a.name ASC");
			}else{
			$dbLocation = false;	
			}
			if($dbLocation){ ?>
			<select  class="bs-select form-control input-sm" id="locationFilter" name="locationFilter" onchange="tj.reportsSelect();" hidden>
			<option value="0">All Locations</option>
			<?php foreach($dbLocation as $a) { ?>
			<option value="<?php echo $a['id'];?>"><?php echo $a['name'];?> </option>
			<?php 	} ?>
			</select>&nbsp;
			<?php }else{ ?>
			<select  class="bs-select form-control input-sm" id="locationFilter" name="locationFilter" onchange="tj.reportsSelect();" hidden>
			</select>
			<?php } ?>
			
			<?php if($role>7 && $role<=12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where d.prodMeasure !=2 AND c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			}else if($role>12) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left outer join `productiveAccount` as a on a.id=d.accountId where d.prodMeasure !=2 AND c.id !=0 AND a.enterpriseId={$_SESSION['account']['enterpriseId']} group by c.id order by c.categoryName ASC");
			}else{
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category where c.id !=0 AND d.id IN (SELECT `deptId` from `productiveDeptXref` where d.prodMeasure !=2 AND `userId`={$_SESSION['account']['id']}) group by c.id order by c.categoryName ASC");	
			}
			
			if($dbData && count($dbData)>1){ ?>
			<select  class="bs-select form-control input-sm" id="serviceFilter" name="serviceFilter" onchange="tj.serviceSelect();" hidden>
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['categoryName'];?> </option>
			<?php 	} ?>
			</select>
			<span class="m-subheader__daterange" id="staffing_daterangepicker">
			<?php }else{ ?>
			<span class="m-subheader__daterange" id="staffing_daterangepicker">	
			<?php } ?>
						
                <span class="m-subheader__daterange-label">
                    <span class="m-staffing__daterange-title"></span>
                    <span class="m-staffing__daterange-date m--font-brand"></span>
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
<div class="m-content bg-light">
    <!--Begin::Main Portlet-->
	
       
	  <div class="m-portlet m-portlet--mobile">
	  <div class="m-portlet__body">
	  <input id="startStaff" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endStaff" type="hidden" value="<?php echo $endPay; ?>" />
	<input id="pdfTitle" type="hidden"/>
	<input id="staffingRole" type="hidden" value="<?php echo $role; ?>" />
	
	  
	    <div class="row pull-right" <?php echo $hidden; ?>> 
		
				<button type="button" id="escButton" class="btn btn-danger" data-target="#addEscalation3" data-toggle="modal" <?php echo $hidden; ?>>
                Escalation
                </button> 
				
				</div>
						
						<div class="row col-12">
                        <table id="prodTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
									<th width="15%" data-priority="1">Unit</th>
									<th width="20%" data-priority="2">Date/Time</th>
									<th width="10%" data-priority="3">Est. Prod%</th>
									<th width="10%" data-priority="4">RN Variance</th>
									<th width="10%" data-priority="6">Pts</th>
									<th width="10%" data-priority="7">Total Resources</th>
									<th id="col1">Charge</th>
									<th id="col2">RN</th>
									<th id="col3">Tech/CNA</th>
									<th id="col4">Admin</th>
									<th id="col5">Other</th>									
									<th width="25%" data-priority="5">Note</th>
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
<div class="modal fade" id="addProd" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header">
				  <div class="modal-title text-white">Staffing Report Update
				  </div>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        <div class="col-sm-12 job-address">
						<div class="modal-title pull-right"><span id="dataId2_add"></span> 
				  </div>
						<div class="modal-title">Report:  <span id="reportdate"></span> - <span id="reportshift"></span>
				  </div>
						
                           
							<div class="modal-title">Last Updated By:  <span id="userName_add"></span>
							</div>							
							
							<div class="modal-title">Variance (Acuity):  <span id="variance"></span>
							</div>
							
							<div class="modal-title">Open Beds:  <span id="openbeds"></span>
							</div>
													
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userId_add" type="hidden" value="" />
						<input id="shift_add" type="hidden" value="" />
						<input id="day_add" type="hidden" value="" />
						<input id="dataId_add" type="hidden" value="" />
						<input id="deptId_add" type="hidden" value="" />
						
						<div class="m-portlet__body">
						<div style="background-color:#0099FF">
						<h5 class="modal-title text-white">Shared Resources </h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="chargecount_add" class="col-7 col-form-label">
                                   Charge Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="chargecount_add" type="select" class="form-control">
										<option value=0.0>0</option>
										<option value=1.0>1</option>
										<option value=2.0>2</option>
										</select>
                                </div>
                            </div>
							
							
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="seccount_add" class="col-7 col-form-label">
                                   Secretaries:
                                </label>
                                <div class="col-5">
                                    <select id="seccount_add" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										</select>
                                </div>
                            </div>
						<div style="background-color:#0099FF">
						<h5 class="modal-title text-white">Antepartum </h5>
						<div class="modal-title text-white"><strong>Variance: <span id="avariance"></span></strong>
						</div>
						<div class="modal-title text-white"><strong>Est. Productivity: <span id="aproductivity"></span></strong>
						</div>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="atotal" class="col-7 col-form-label"><strong>
                                    Total Antepartem Patients:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="atotal" type="select" class="form-control">
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
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="antecount_add" class="col-7 col-form-label"><strong>
                                    Antepartum Nurses currently in staffing:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="antecount_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="aptechcount_add" class="col-7 col-form-label">
                                  <strong> Antepartum Techs:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="aptechcount_add" type="select" class="form-control">
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
										</select>
                                </div>
                            </div>
							<hr></hr>
							<div class="modal-title"><strong>Selections below must add up to Total Antepartum Patients above.</strong>
							</div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="acs_add" class="col-7 col-form-label">
                                    Antepartum with Complications but stable (1:3):
                                </label>
                                <div class="col-5">
                                    <select id="acs_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="am1_add" class="col-7 col-form-label">
                                    Antepartum Magnesium after 1st hour (1:2):
                                </label>
                                <div class="col-5">
                                    <select id="am1_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="awcm_add" class="col-7 col-form-label">
                                    Antepartum with complications, 1st hour Mag (1:1):
                                </label>
                                <div class="col-5">
                                    <select id="awcm_add" type="select" class="form-control">
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
							
					<div style="background-color:#0099FF">
					<h5 class="modal-title text-white">Labor</h5>
					<div class="modal-title text-white"><strong>Variance: <span id="lvariance"></span></strong>
					</div>
					</div>
					<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ltotal" class="col-7 col-form-label"><strong>
                                    Total Labor & C/S Patients:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="ltotal" type="select" class="form-control">
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
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ldcount_add" class="col-7 col-form-label"><strong>
                                    Labor Nurses currently in staffing:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="ldcount_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="techcount_add" class="col-7 col-form-label">
                                   <strong>L&D Techs:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="techcount_add" type="select" class="form-control">
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
										</select>
                                </div>
                            </div>
						<hr></hr>
						<div class="modal-title"><strong>Selections below must add up to Total Labor Patients above.</strong></div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ev_add" class="col-7 col-form-label">
                                    External Version (1:2):
                                </label>
                                <div class="col-5">
                                    <select id="ev_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="scs_add" class="col-7 col-form-label">
                                    Scheduled C/S Prep (1:2):
                                </label>
                                <div class="col-5">
                                    <select id="scs_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="cr_add" class="col-7 col-form-label">
                                    Cervical Ripening, Labor, Pitocin Induction/Augmentation (1:2):
                                </label>
                                <div class="col-5">
                                    <select id="cr_add" type="select" class="form-control">
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
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="pt_add" class="col-7 col-form-label">
                                    Pt with Medical/OB Complications (Mag, Insulin, Twins, IUFD) (1:1):
                                </label>
                                <div class="col-5">
                                    <select id="pt_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ccs_add" class="col-7 col-form-label">
                                    Circulator C/S, PACU (1:1):
                                </label>
                                <div class="col-5">
                                    <select id="ccs_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ps1_add" class="col-7 col-form-label">
                                    Pts >= 6cm, 2nd Stage Labor, Recovery 1st Hour (1:1):
                                </label>
                                <div class="col-5">
                                    <select id="ps1_add" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="obed_add1" class="col-7 col-form-label">
                                    OBED (1:3):
                                </label>
                                <div class="col-5">
                                    <select id="obed_add1" type="select" class="form-control">
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
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="pp_add" class="col-7 col-form-label">
                                    Postpartum Hold-Over (1:6):
                                </label>
                                <div class="col-5">
                                    <select id="pp_add" type="select" class="form-control">
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
										</select>
                                </div>
                            </div>
							<div style="background-color:#0099FF">
					<h5 class="modal-title text-white">OBED</h5>
					<div class="modal-title text-white"><strong>Variance: <span id="ovariance"></span></strong>
					</div>
					</div>
					<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="obed_add" class="col-7 col-form-label"><strong>
                                    Total OBED Patients:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="obed_add" type="select" class="form-control">
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
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ocount_add" class="col-7 col-form-label"><strong>
                                    OBED Nurses currently in staffing:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="ocount_add" type="select" class="form-control">
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
							<div style="background-color:#0099FF">
                              <h5 class="modal-title text-white">Note / Action Plan</h5>
								</div>
								<div class="title" style="color:red">DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                                <textarea class="form-control" rows="3" placeholder="" id="prodnote"></textarea>
                            </div>
													
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProd();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>

<!-- /begin modal -->
<div class="modal fade" id="addNEW" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header">
				  <h4 class="modal-title text-white">Staffing Report
				  </h4>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        <div class="col-sm-12 job-address">
						<div class="modal-title pull-right"><span id="dataId2NEW"></span> 
				  </div>
						<div class="modal-title">Report:  <span id="reportdateNEW"></span> - <span id="reportshiftNEW"></span>
				  </div>
						
                           
							<div class="modal-title">Last Updated By:   <span id="userNameNEW"></span>
							</div>							
							
							<div class="modal-title">Variance (Acuity):   <span id="varianceNEW"></span>
							</div>
							
							<div class="modal-title">Open Beds:   <span id="openbedsNEW"></span>
							</div>
							<!--
							<div class="modal-title">Blocked Beds:   <span id="blocked"></span>
							</div>
								-->					
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formNEW" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
                        <input id="userIdNEW" type="hidden" value="" />
						<input id="shiftNEW" type="hidden" value="" />
						<input id="dayNEW" type="hidden" value="" />
						<input id="dataIdNEW" type="hidden" value="" />
						<input id="oneto7Acuity" type="hidden" value="" />
						<input id="oneto8Acuity" type="hidden" value="" />
						
						<input id="acuityTotal" type="hidden" value="" />
						<div class="m-portlet__body">
						<div class="p-3 mb-2 bg-light text-dark">
						<h5 class="modal-title">Resources Currently in Staffing </h5>
						<div class="modal-title">Variance: <span id="avarianceNEW"></span></div>
						<div class="modal-title">Est. Productivity: <span id="aproductivityNEW"></span></div>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="chargecountNEW" class="col-7 col-form-label">
                                   Charge Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="chargecountNEW" type="select" class="form-control">
										<option value="0.0">0</option>
										<option value="0.5">0.5</option>
										<option value="1.0">1</option>
										<option value="2.0">2</option>
										</select>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row" id="nurse1">
                                <label for="nurse1_add" class="col-7 col-form-label">
                                   <span id="nurse1Desc">
                                </label>
                                <div class="col-5">
                                    <select id="nurse1_add" type="select" class="form-control">
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										</select>
                                </div>
                            </div>
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="techcountNEW" class="col-7 col-form-label">
                                   Techs / CNAs:
                                </label>
                                <div class="col-5">
                                    <select id="techcountNEW" type="select" class="form-control">
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
										</select>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="seccountNEW" class="col-7 col-form-label">
                                   Secretaries:
                                </label>
                                <div class="col-5">
                                    <select id="seccountNEW" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										</select>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="nursecountNEW" class="col-7 col-form-label">
                                    Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="nursecountNEW" type="select" class="form-control">
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
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sittersNEW" class="col-7 col-form-label">
                                   Other Productive Resources </br>(Sitters, Training, etc.):
                                </label>
                                <div class="col-5">
                                    <select id="sittersNEW" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										</select>
                                </div>
                            </div>
						
						<div class="p-3 mb-2 bg-light text-dark">
					<h5 class="modal-title">Patient Count</h5>
					
					</div>
					<div class="form-group m-form__group m--margin-top-10 row" id="hiddentotal1">
                                <label for="patienttotalNEW" class="col-7 col-form-label">
                                    Current Patients:
                                </label>
                                <div class="col-5">
								<input type="number" min="0" class="form-control number" id="patienttotalNEW" style="text-align: right" />
                                </div>
								
                            </div>
						<hr></hr>
							
							<div class="modal-title" id="hiddentotal2"><strong>Selections below must add up to Current Patients.</strong>
							</div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden1" >
                                <label for="oneto1" class="col-7 col-form-label">
                                    1:1 Acuity Patients: <span id="desc1"></span>
                                </label>
                                <div class="col-5">
                                    <select id="oneto1" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden2" >
                                <label for="oneto2" class="col-7 col-form-label">
                                    1:2 Acuity Patients: <span id="desc2"></span>
                                </label>
                                <div class="col-5">
                                    <select id="oneto2" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden3">
                                <label for="highNEW" class="col-7 col-form-label">
                                    1:3 Acuity Patients: <span id="desc3"></span>
                                </label>
                                <div class="col-5">
                                    <select id="highNEW" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden4">
                                <label for="medNEW" class="col-7 col-form-label">
                                    1:4 Acuity Patients: <span id="desc4"></span>
                                </label>
                                <div class="col-5">
                                    <select id="medNEW" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden5">
                                <label for="lowNEW" class="col-7 col-form-label">
                                    1:5 Acuity Patients: <span id="desc5"></span>
                                </label>
                                <div class="col-5">
                                    <select id="lowNEW" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden6" >
                                <label for="oneto6" class="col-7 col-form-label">
                                    1:6 Acuity Patients: <span id="desc6"></span>
                                </label>
                                <div class="col-5">
                                    <select id="oneto6" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden8" >
                                <label for="oneto8" class="col-7 col-form-label">
                                    <span id="desc8"></span>:
                                </label>
                                <div class="col-5">
                                    <select id="oneto8" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden7" >
                                <label for="oneto7" class="col-7 col-form-label">
                                    <span id="desc7"></span>:
                                </label>
                                <div class="col-5">
                                    <select id="oneto7" type="select" class="form-control">
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
										<option value=21>21</option>
										<option value=22>22</option>
										<option value=23>23</option>
										<option value=24>24</option>
										<option value=25>25</option>
										<option value=26>26</option>
										<option value=27>27</option>
										<option value=28>28</option>
										<option value=29>29</option>
										<option value=30>30</option>
										<option value=31>31</option>
										<option value=32>32</option>
										<option value=33>33</option>
										<option value=34>34</option>
										<option value=35>35</option>
										<option value=36>36</option>
										<option value=37>37</option>
										<option value=38>38</option>
										<option value=39>39</option>
										<option value=40>40</option>
										</select>
                                </div>
                            </div>
														
							<div id="showchurn1">
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title">Churn </h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="admissions1" class="col-7 col-form-label">
                                   Admissions:
                                </label>
                                <div class="col-5">
                                 <input type="number" class="form-control number" id="admissions1">
                                </div>
                            </div>
							<hr></hr>
							<div class="form-group m-form__group m--margin-top-5 row">
                                <label for="transfers1" class="col-7 col-form-label">
                                   Transfers:
                                </label>
                                <div class="col-5">
								<input type="number" class="form-control number" id="transfers1">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" >
                                <label for="discharges1" class="col-7 col-form-label">
                                    Discharges:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" id="discharges1">
                                </div>
                            </div>
							
						</div>
							<div class="p-3 mb-2 bg-light text-dark">
                              <h5 class="modal-title">Note / Action Plan</h5>
								</div>
								<div class="title" style="color:red">DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>	
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" rows="3" id="prodnoteNEW"></textarea>
                            </div>
											
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdNEW();">Submit</button>
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
						
						<div class="form-body">
						<div class="title" style="padding-bottom: 20px;">
						Use this feature to calculate productivity for the entire day.  All in-shift productivity estimates will be replaced with this value.
						</div>
                            <div class="form-group form-md-line-input">
                                <label for="visitTotal"><strong>Enter <span id="visitsType"></span> for <span id="visitsdayDate"></span>:</strong></label>
                                <input type="number" min="0" class="form-control number col-6" id="visitTotal">
                            </div>
							<div class="form-group form-md-line-input">
                                <label for="hoursTotal"><strong>Enter Total Hours for <span id="hoursdayDate"></span>:</strong></label>
                                <input type="number" min="0" class="form-control number col-6" id="hoursTotal">
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
				<!--
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
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
                <div class="modal-header">
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
                                <div class="title" style="color:red">DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>	
								<textarea class="form-control" rows="3" id="notebody" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
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
                <div class="modal-header">
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
                                <textarea class="form-control" rows="3" id="notebodyOrig" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
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
                <div class="modal-header">
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
								<div class="title" style="color:red">DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="blockedcomment" required ></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="blockButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="escalationNEW" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h5 class="modal-title">Your Staffing Report has been submitted.</h5>
                </div>
                <div class="modal-body">
				<input id="esc" type="hidden" value="" />
				<input id="deptIdesc" type="hidden" value="" />
				<input id="dataIdesc" type="hidden" value="" />
                    <div class="modal-title"><strong><p>Do you have any current issues that require immediate escalation (ie. Staffing, Supplies, Close Calls, etc.)?</p> <p>If so, please click YES below to escalate the issue. Otherwise, click No to complete your Report. </p></strong>
					</div>
                </div>
				
                <div class="modal-footer">
                    <button type="button" id="noescbutton" class="btn btn-secondary" onclick="tj.noEscalation();">No</button>
                    <button type="button" id="addescbutton" class="btn btn-primary" data-target="#addEscalation" data-toggle="modal">Yes</button>
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
                                <label for="escval">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              <div class="form-group">	
										<select id="escval" name="escval" class="form-control">
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
                                <label for="escalationcomment">Comments:</label>
								<div class="title" style="color:red">DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment" ></textarea>
                            </div>
							
				
					  

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
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
                                <label for="escval3">Select your primary issue below to escalate.  Your manager will be immediately notified via text.  Also, please document this issue as required on your Unit.</label>
                              	
										<select id="escval3" name="escval3" class="form-control">
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
                                <label for="deptId3">Which Unit is this Escalation for?</label>
                              	
										<select id="deptId3" name="deptId3" class="form-control">
										<?php
								$dbUnit = Config::get('db')->get_results("SELECT d.* from `ProductiveDept` d LEFT JOIN `productiveDeptXref` as x on x.deptId = d.id LEFT JOIN `productiveUser` as u on u.id=x.userId where x.deptId in (SELECT `deptId` from `productiveDeptXref`where `userId`={$_SESSION['account']['id']}) and x.textAlerts >0 and u.txtEscalation>0 and u.txtPause=0 and d.escalations=0 group by d.id order by d.dept ASC");
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
								<div class="title" style="color:red">Please DO NOT include any Personal Health Information (ie. patient name) in your comments.
					</div>
                                <textarea class="form-control" rows="3" id="escalationcomment3" ></textarea>
                            </div>
							
				
					  
                    </form>
                </div>
                <div class="modal-footer">
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

