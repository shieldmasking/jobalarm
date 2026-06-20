<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['id']);

$user = (isset($_REQUEST['u'])) ? $_REQUEST['u'] : '';

$dbText = Config::get('db')->get_results("SELECT d.*, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.deptId IN (SELECT `deptId` from `productiveDeptXref` WHERE `userId`={$userId}) and u.txtEscalation>0 and u.txtactive>0 and u.txtPause=0 and d.escalations=0 and x.textAlerts>0");

$dbHppd = Config::get('db')->get_results("SELECT d.*, count(d.id) as countId, x.userId from `ProductiveDept` d left outer join `productiveDeptXref` as x on x.deptId = d.id left outer join `productiveUser` as u on u.id = x.userId where x.userId={$userId} AND d.prodMeasure=2 group by d.accountId");

if($dbHppd && $role <=6){
	$dbData = $dbHppd[0];
	$count = intval($dbData['countId']);
}else{
	$count = 0;
}


?>
<html lang="en">
<body>
<head>

</head>
<!--
<script>
$('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
      });
</script>
-->
<style>
body {
    font-size: 16px;
} 
</style>

<style>
td.escalation {
  background-color: red;
}
</style>
<!--
<script>
$(document).on('hidden.bs.modal', function () {
$('body').addClass('modal-open');
});
</script>
-->
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Productivity Reports
            </h3>
        </div>
		<div>
            <span class="m-subheader__daterange" id="staffing_daterangepicker">
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
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
				
				 <div class="row">
				 <?php if($role >3 || $role <8): ?>
				 <div class="col-xl-12">
				 <span id="userName"></span>
				 <?php if($dbText){ ?>
				 		<div class="pull-right">
				 <?php }else{ ?>
						<div class="pull-right" hidden>
				<?php } ?>
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
										
                                        <button type="button" class="btn btn-danger" data-target="#addEscalation3" data-toggle="modal">
                                            Escalation
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>	
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="prodTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="15%" data-priority="1">Unit</th>
									<th width="15%" data-priority="2">Date/Time</th>
									<?php if($count==1){ ?>
                                    <th width="10%" data-priority="3">WHPUOS</th>
									<th width="10%" data-priority="4">Hours Variance</th>
									<th width="10%" data-priority="6">Units of Service</th>
									<th width="10%" data-priority="7">Total Hours</th>
									<th id="col1" value="<?php echo $dbData['skill1']; ?>"><?php echo $dbData['skilldesc1']; ?></th>
									<th id="col2" value="<?php echo $dbData['skill2']; ?>"><?php echo $dbData['skilldesc2']; ?></th>
									<th id="col3" value="<?php echo $dbData['skill3']; ?>"><?php echo $dbData['skilldesc3']; ?></th>
									<th id="col4" value="<?php echo $dbData['skill4']; ?>"><?php echo $dbData['skilldesc4']; ?></th>
									<th id="col5" value="<?php echo $dbData['skill5']; ?>"><?php echo $dbData['skilldesc5']; ?></th>
									<?php }else{ ?>
									<th width="10%" data-priority="3">Est. Prod%</th>
									<th width="10%" data-priority="4">RN Variance</th>
									<th width="10%" data-priority="6">Pts</th>
									<th width="10%" data-priority="7">Total Resources</th>
									<th id="col1" value="1">Charge</span></th>
									<th id="col2" value="1">RN</span></th>
									<th id="col3" value="1">Tech/CNA</span></th>
									<th id="col4" value="1">Admin</span></th>
									<th id="col5" value="1">Other</span></th>									
									<?php }?>
									<th width="30%" data-priority="5">Note</th>
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

		    <!--begin::Modal-->
<div class="modal fade" id="addSICU" tabindex="-1" role="basic" aria-hidden="true">
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
						<div class="modal-title pull-right"><span id="dataId2SICU"></span> 
				  </div>
						<div class="modal-title">Report:  <span id="reportdateSICU"></span> - <span id="reportshiftSICU"></span>
				  </div>
						
                           
							<div class="modal-title">Last Updated By:  <span id="userNameSICU"></span>
							</div>							
							
							<div class="modal-title">Variance (Acuity):  <span id="varianceSICU"></span>
							</div>
							
							<div class="modal-title">Open Beds:  <span id="openbedsSICU"></span>
							</div>
							
													
						
							
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="add_prod_formSICU" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userIdSICU" type="hidden" value="" />
						<input id="shiftSICU" type="hidden" value="" />
						<input id="daySICU" type="hidden" value="" />
						<input id="dataIdSICU" type="hidden" value="" />
						<input id="prodnoteSICU" type="hidden" value="" />
						<div class="m-portlet__body">
						<div style="background-color:#0099FF">
						<h5 class="modal-title text-white">Resources Currently in Staffing </h5>
						<div class="modal-title text-white"><strong>Variance: <span id="avarianceSICU"></span></strong></div>
						<div class="modal-title text-white"><strong>Est. Productivity: <span id="aproductivitySICU"></span></strong></div>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="chargecountSICU" class="col-7 col-form-label">
                                   Charge Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="chargecountSICU" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										</select>
                                </div>
                            </div>
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="techcountSICU" class="col-7 col-form-label">
                                   Techs:
                                </label>
                                <div class="col-5">
                                    <select id="techcountSICU" type="select" class="form-control">
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
                                <label for="seccountSICU" class="col-7 col-form-label">
                                   Secretaries:
                                </label>
                                <div class="col-5">
                                    <select id="seccountSICU" type="select" class="form-control">
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
                                <label for="nursecountSICU" class="col-7 col-form-label">
                                   Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="nursecountSICU" type="select" class="form-control">
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
						<!--	
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="npcountSICU" class="col-7 col-form-label">
                                    Non-Productive Resources (Orientation, Education, etc.):
                                </label>
                                <div class="col-5">
                                    <select id="npcountSICU" type="select" class="form-control">
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
                            </div> -->
						<hr></hr>
						<div style="background-color:#0099FF">
					<h5 class="modal-title text-white">Patients</h5>
					
					</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="patienttotalSICU" class="col-7 col-form-label"><strong>
                                    Total Patients:</strong>
                                </label>
                                <div class="col-5">
                                    <select id="patienttotalSICU" type="select" class="form-control">
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
						
							<gr></hr>
							<div class="modal-title"><h5>Selections below must add up to Total Patients.</h5></div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="highSICU" class="col-7 col-form-label">
                                    High Acuity Patients (1:3):
                                </label>
                                <div class="col-5">
                                    <select id="highSICU" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="medSICU" class="col-7 col-form-label">
                                    Medium Acuity Patients (1:4):
                                </label>
                                <div class="col-5">
                                    <select id="medSICU" type="select" class="form-control">
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="lowSICU" class="col-7 col-form-label">
                                    Low Acuity Patients (1:5):
                                </label>
                                <div class="col-5">
                                    <select id="lowSICU" type="select" class="form-control">
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
								<!--						
							<div class="form-group form-md-line-input">
							<div style="background-color:#0099FF">
                              <h5 class="modal-title text-white">Variance Note / Action Plan</h5>
								</div>
                                <textarea class="form-control" rows="3" placeholder="" id="prodnoteSICU"></textarea>
                            </div>
								-->					
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdSICU();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
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
                    <form id="add_prod_formNEW" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userIdNEW" type="hidden" value="" />
						<input id="shiftNEW" type="hidden" value="" />
						<input id="dayNEW" type="hidden" value="" />
						<input id="dataIdNEW" type="hidden" value="" />
						
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
										<option value=0.0>0</option>
										<option value=0.5>0.5</option>
										<option value=1.0>1</option>
										<option value=2.0>2</option>
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
						<hr></hr>
						<div class="p-3 mb-2 bg-light text-dark">
					<h5 class="modal-title">Patient Count</h5>
					
					</div>
						<div class="form-group m-form__group m--margin-top-10 row" id="hiddentotal1">
                                <label for="patienttotalNEW" class="col-7 col-form-label">
                                    Total Patients:
                                </label>
                                <div class="col-5">
                                    <select id="patienttotalNEW" type="select" class="form-control">
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
						
							<hr></hr>
							<div class="modal-title" id="hiddentotal2"><strong>Selections below must add up to Total Patients.</strong>
							</div>
							<div class="form-group m-form__group m--margin-top-10 row" id="hidden1" >
                                <label for="oneto1" class="col-7 col-form-label">
                                    1:1 Acuity Patients (<span id="desc1"></span>):
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
                                    1:2 Acuity Patients (<span id="desc2"></span>):
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
                                    1:3 Acuity Patients (<span id="desc3"></span>):
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
                                    1:4 Acuity Patients (<span id="desc4"></span>):
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
                                    1:5 Acuity Patients (<span id="desc5"></span>):
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
                                    1:6 Acuity Patients (<span id="desc6"></span>):
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
							<hr></hr>							
							
							<div class="p-3 mb-2 bg-light text-dark">
                              <h5 class="modal-title">Note / Action Plan</h5>
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
 
 <!-- /begin modal -->
<div class="modal fade" id="addWHP" tabindex="-1" role="basic" aria-hidden="true">
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
				
				<div class="row">
                        <div class="col-sm-12 job-address">
						<!--
						<div class="modal-title pull-right"><span id="dataId2WHP"></span> 
				  </div>
				  -->
						<div class="modal-title">Report Date - Time:  <span id="reportdateWHP"></span> - <span id="reportshiftWHP"></span>
				  </div>
						
                           
							<div class="modal-title" style="padding-bottom: 20px;">Last Updated By:   <span id="userNameWHP"></span>
							</div>
							<!--
							<div class="modal-title">Planned Procedures:   <span id="plannedTotal"></span>
							</div>
							
							<div class="modal-title">Variance(Hours):   <span id="plannedVar"></span>
							</div>
							<div class="modal-title">Actual WHPUOS:   <span id="currentWHP"></span>
							</div>
							
							<div class="modal-title">Current Variance:   <span id="currentVar"></span>
							</div>
							-->
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
						<div class="m-portlet__body">
						
						<div id="plannedWHP1">
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title"><span id="actual"></span> </h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="plannedWHP" class="col-7 col-form-label">
                                   Units of Service:
                                </label>
                                <div class="col-5">
                                 <input type="number" class="form-control number" data-bind="value:replyNumber" id="plannedWHP">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill1" >
                                <label for="skill1" class="col-7 col-form-label">
                                    <span id="descskill1"></span>:
                                </label>
                                <div class="col-5">
								<input type="number" class="form-control number" data-bind="value:replyNumber" id="skill1">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill2" >
                                <label for="skill2" class="col-7 col-form-label">
                                    <span id="descskill2"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" data-bind="value:replyNumber" id="skill2">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill3">
                                <label for="skill3" class="col-7 col-form-label">
                                    <span id="descskill3"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" data-bind="value:replyNumber" id="skill3">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill4">
                                <label for="skill4" class="col-7 col-form-label">
                                    <span id="descskill4"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" data-bind="value:replyNumber" id="skill4">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill5">
                                <label for="skill5" class="col-7 col-form-label">
                                   <span id="descskill5"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="number" class="form-control number" data-bind="value:replyNumber" id="skill5">
                                </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-5 row" id="hiddenskill6" >
                                <label for="skill6" class="col-7 col-form-label">
                                    <span id="descskill6"></span>:
                                </label>
                                <div class="col-5">
                                    <input type="text" class="form-control number" id="skill6">
                                </div>
                            </div>
						<!--
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="plannedHRS" class="col-7 col-form-label">
                                   Hours:
                                </label>
                                <div class="col-5">
                                    <select id="plannedHRS" type="select" class="form-control">
										<option value=0.00>0</option>
										<option value=0.50>0.5</option>
										<option value=1.00>1</option>
										<option value=1.50>1.5</option>
										<option value=2.00>2</option>
										<option value=2.50>2.5</option>
										<option value=3.00>3</option>
										<option value=3.50>3.5</option>
										<option value=4.00>4</option>
										<option value=4.50>4.5</option>
										<option value=5.00>5</option>
										<option value=5.50>5.5</option>
										<option value=6.00>6</option>
										<option value=6.50>6.5</option>
										<option value=7.00>7</option>
										<option value=7.50>7.5</option>
										<option value=8.00>8</option>
										<option value=8.50>8.5</option>
										<option value=9.00>9</option>
										<option value=9.50>9.5</option>
										<option value=10.00>10</option>
										<option value=10.50>10.5</option>
										<option value=11.00>11</option>
										<option value=11.50>11.5</option>
										<option value=12.00>12</option>
										<option value=12.50>12.5</option>
										<option value=13.00>13</option>
										<option value=13.50>13.5</option>
										<option value=14.00>14</option>
										<option value=14.50>14.5</option>
										<option value=15.00>15</option>
										<option value=15.50>15.5</option>
										<option value=16.00>16</option>
										<option value=16.50>16.5</option>
										<option value=17.00>17</option>
										<option value=17.50>17.5</option>
										<option value=18.00>18</option>
										<option value=18.50>18.5</option>
										<option value=19.00>19</option>
										<option value=19.50>19.5</option>
										<option value=20.00>20</option>
										<option value=20.50>20.5</option>
										<option value=21.00>21</option>
										<option value=21.50>21.5</option>
										<option value=22.00>22</option>
										<option value=22.50>22.5</option>
										<option value=23.00>23</option>
										<option value=23.50>23.5</option>
										<option value=24.00>24</option>
										<option value=24.50>24.5</option>
										<option value=25.00>25</option>
										<option value=25.50>25.5</option>
										<option value=26.00>26</option>
										<option value=26.50>26.5</option>
										<option value=27.00>27</option>
										<option value=27.50>27.5</option>
										<option value=28.00>28</option>
										<option value=28.50>28.5</option>
										<option value=29.00>29</option>
										<option value=29.50>29.5</option>
										<option value=30.00>30</option>
										<option value=30.50>30.5</option>
										<option value=31.00>31</option>
										<option value=31.50>31.5</option>
										<option value=32.00>32</option>
										<option value=32.50>32.5</option>
										<option value=33.00>33</option>
										<option value=33.50>33.5</option>
										<option value=34.00>34</option>
										<option value=34.50>34.5</option>
										<option value=35.00>35</option>
										<option value=35.50>35.5</option>
										<option value=36.00>36</option>
										<option value=36.50>36.5</option>
										<option value=37.00>37</option>
										<option value=37.50>37.5</option>
										<option value=38.00>38</option>
										<option value=38.50>38.5</option>
										<option value=39.00>39</option>
										<option value=39.50>39.5</option>
										<option value=40.00>40</option>
										<option value=40.50>40.5</option>
										<option value=41.00>41</option>
										<option value=41.50>41.5</option>
										<option value=42.00>42</option>
										<option value=42.50>42.5</option>
										<option value=43.00>43</option>
										<option value=43.50>43.5</option>
										<option value=44.00>44</option>
										<option value=44.50>44.5</option>
										<option value=45.00>45</option>
										<option value=45.50>45.5</option>
										<option value=46.00>46</option>
										<option value=46.50>46.5</option>
										<option value=47.00>47</option>
										<option value=47.50>47.5</option>
										<option value=48.00>48</option>
										<option value=48.50>48.5</option>
										<option value=49.00>49</option>
										<option value=49.50>49.5</option>
										<option value=50.00>50</option>
										<option value=50.50>50.5</option>
										<option value=51.00>51</option>
										<option value=51.50>51.5</option>
										<option value=52.00>52</option>
										<option value=52.50>52.5</option>
										<option value=53.00>53</option>
										<option value=53.50>53.5</option>
										<option value=54.00>54</option>
										<option value=54.50>54.5</option>
										<option value=55.00>55</option>
										<option value=55.50>55.5</option>
										<option value=56.00>56</option>
										<option value=56.50>56.5</option>
										<option value=57.00>57</option>
										<option value=57.50>57.5</option>
										<option value=58.00>58</option>
										<option value=58.50>58.5</option>
										<option value=59.00>59</option>
										<option value=59.50>59.5</option>
										<option value=60.00>60</option>
										</select>
                                </div>
                            </div> -->
						</div>
						<div id="actualHours" hidden>
						<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
						<h5 class="modal-title">Resources in Staffing</h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="chargecountWHP" class="col-7 col-form-label">
                                   Charge Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="chargecountWHP" type="select" class="form-control">
										<option value=0.0>0</option>
										<option value=0.5>0.5</option>
										<option value=1.0>1</option>
										<option value=2.0>2</option>
										</select>
                                </div>
                            </div>
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="techcountWHP" class="col-7 col-form-label">
                                   Techs:
                                </label>
                                <div class="col-5">
                                    <select id="techcountWHP" type="select" class="form-control">
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
                                <label for="seccountWHP" class="col-7 col-form-label">
                                   Secretaries:
                                </label>
                                <div class="col-5">
                                    <select id="seccountWHP" type="select" class="form-control">
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
                                <label for="nursecountWHP" class="col-7 col-form-label">
                                    Nurses:
                                </label>
                                <div class="col-5">
                                    <select id="nursecountWHP" type="select" class="form-control">
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
                                <label for="sittersWHP" class="col-7 col-form-label">
                                   Other Productive Resources </br>(Sitters, Training, etc.):
                                </label>
                                <div class="col-5">
                                    <select id="sittersWHP" type="select" class="form-control">
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										<option value=3>3</option>
										<option value=4>4</option>
										<option value=5>5</option>
										</select>
                                </div>
                            </div>
						</div>
					
						<div  id="plannedWHP2" hidden>
						<div class="p-3 mb-2 bg-light text-dark">
					<h5 class="modal-title">Procedures Completed</span></h5>
					
					</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="procedureCountWHP" class="col-7 col-form-label">
                                    Units of Service:
                                </label>
                                <div class="col-5">
                                    <select id="procedureCountWHP" type="select" class="form-control">
										<option value=''>Select</option>
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
							</div>
													
							
							<div class="p-3 mb-2 bg-light text-dark" style="padding-top: 20px;">
                              <h5 class="modal-title">Note / Action Plan</h5>
								</div>
								<div class="form-group m-form__group m--margin-top-10 row">
                                <textarea class="form-control" rows="3" id="prodnoteWHP"></textarea>
                            </div>
												
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProdWHP();">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
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
				<div class="modal-body">
				<div class="title">To remove the Escalations feature, go to Unit Settings.
					</div>
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
                    <h4 class="modal-title">Escalations</h4>
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
                    <h4 class="modal-title">Escalations</h4>
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
                                <textarea class="form-control" rows="3" id="escalationcomment3" ></textarea>
                            </div>

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
</body>
</html>
