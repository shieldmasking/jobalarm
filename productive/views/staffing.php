<?php
include "../inc/initializer.php";

require_once '../../inc/class.db.php';

require_once '../../inc/config.php';

$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);



?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Staffing Reports
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
						
					<div class="col-xl-12">
                    <div class="row">
					
                        <table id="prodTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="15%" data-priority="1">Report Date/Time</th>
                                    <th width="10%" data-priority="2">Variance</th>
									<th width="10%" data-priority="3">AP Prod%</th>
									<th width="10%" data-priority="4">RNs</th>
									<th width="10%" data-priority="5">Techs</th>
									<th width="10%" data-priority="6">Sec</th>
									<th width="25%" data-priority="7">Note</th>
                                    
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
	
    <!--begin::Modal-->
	    <!--begin::Modal-->
		<div class="modal fade" id="addProd" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			<div style="background-color:#0099FF">
                <div class="modal-header">
				  <div class="modal-title text-white">Staffing Matrix Update
				  </div>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				</div>
                <div class="modal-body">
				
				<div class="row">
                        <div class="col-sm-12 job-address">
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
						<input id="dataId" type="hidden" value="" />
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
										<option value=0>0</option>
										<option value=1>1</option>
										<option value=2>2</option>
										</select>
                                </div>
                            </div>
							
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="techcount_add" class="col-7 col-form-label">
                                   Techs:
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
						<div class="modal-title text-white"><strong>Variance: <span id="avariance"></span></strong></div>
						<div class="modal-title text-white"><strong>Productivity: <span id="aproductivity"></span></strong></div>
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
							<hr></hr>
							<div class="modal-title"><strong>Selections below must add up to Total Antepartum Patients above.</strong></div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="acs_add" class="col-7 col-form-label">
                                    Antepartum with Complications but stable:
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
                                    Antepartum Magnesium after 1st hour:
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
                                    Antepartum with complications, 1st hour Mag:
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
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="obed_add" class="col-7 col-form-label">
                                    Outpatient Observation / OBED:
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
							<div style="background-color:#0099FF">
					<h5 class="modal-title text-white">Labor</h5>
					<div class="modal-title text-white"><strong>Variance: <span id="lvariance"></span></strong></div>
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
						<hr></hr>
						<div class="modal-title"><strong>Selections below must add up to Total Labor Patients above.</strong></div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ev_add" class="col-7 col-form-label">
                                    External Version:
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
                                    Scheduled C/S Prep:
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
                                    Cervical ripening, Labor, Pitocin induction/augmentation:
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
                                    Pt with Medical/OB complications (mag, insulin, twins, IUFD):
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
                                    Circulator C/S, PACU:
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
                                <label for="psl_add" class="col-7 col-form-label">
                                    Patient in 2nd stage labor, recovery:
                                </label>
                                <div class="col-5">
                                    <select id="psl_add" type="select" class="form-control">
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
					<div style="background-color:#CCCCCC">
					<h5 class="modal-title">Postpartum </h5>
					<h5 class="modal-title">Variance: <span id="pvariance"></span></h5>
					</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="postcount_add" class="col-8 col-form-label"><strong>
                                    Postpartum Nurses currently in staffing:</strong>
                                </label>
                                <div class="col-4">
                                    <select id="postcount_add" type="select" class="form-control">
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
                                <label for="ppvag_add" class="col-8 col-form-label">
                                    PP Vag, C/S without complications:
                                </label>
                                <div class="col-4">
                                    <select id="ppvag_add" type="select" class="form-control">
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
                                <label for="ppwc_add" class="col-8 col-form-label">
                                    PP Vag, C/S with complications but stable:
                                </label>
                                <div class="col-4">
                                    <select id="ppwc_add" type="select" class="form-control">
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
                                <label for="ppmag_add" class="col-8 col-form-label">
                                    PP on Magnesium:
                                </label>
                                <div class="col-4">
                                    <select id="ppmag_add" type="select" class="form-control">
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
							-->
							
							<div class="form-group form-md-line-input">
							<div style="background-color:#0099FF">
                              <h5 class="modal-title text-white">Variance Note / Action Plan</h5>
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
	<div class="modal fade" id="editProd" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-5">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update </h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
				<div class="row" style="margin-top:5px;margin-bottom:8px">
                        <div class="col-sm-6 job-address">
                            <div><span id="userName"></span>
							</div>
                        </div>
                       </div>
                    <!--begin::Form-->
                    <form id="edit_prod_form" class="m-form m-form--fit m-form--label-align-right">
                        <input id="userId" type="hidden" value="" />
						<input id="shift" type="hidden" value="" />
						<input id="day" type="hidden" value="" />
						<div class="m-portlet__body">
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sharedcount" class="col-3 col-form-label">
                                    Shared Staff (Total Count of Charge, Techs and Secretary)
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="number_format" value="" id="sharedcount" required>
                                </div>
                            </div>
						<div style="background-color:#0099CC">
						<h5 class="modal-title">Antepartum </h5>
						</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="antecount" class="col-3 col-form-label">
                                    Antepartum Nurse Count
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="number_format" value="" id="antecount" required>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="nst" class="col-3 col-form-label">
                                    NST
                                </label>
                                <div class="col-9">
                                    <select id="nst" type="select" class="form-control">
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
                                <label for="acs" class="col-3 col-form-label">
                                    Antepartum with Complications but stable:
                                </label>
                                <div class="col-9">
                                    <select id="acs" type="select" class="form-control">
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
                                <label for="am1" class="col-3 col-form-label">
                                    Antepartum Magnesium after 1st hour:
                                </label>
                                <div class="col-9">
                                    <select id="am1" type="select" class="form-control">
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
                                <label for="awcm" class="col-3 col-form-label">
                                    Antepartum with complications, 1st hour Mag:
                                </label>
                                <div class="col-9">
                                    <select id="awcm" type="select" class="form-control">
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
                                <label for="obed" class="col-3 col-form-label">
                                    Outpatient Observation / OBED:
                                </label>
                                <div class="col-9">
                                    <select id="obed" type="select" class="form-control">
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
							<div style="background-color:#0099CC">
					<h5 class="modal-title">Labor</h5>
					</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ldcount" class="col-3 col-form-label">
                                    Labor Nurse Count
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="number_format" value="" id="ldcount" required>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ev" class="col-3 col-form-label">
                                    External Version:
                                </label>
                                <div class="col-9">
                                    <select id="ev" type="select" class="form-control">
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
                                <label for="scs" class="col-3 col-form-label">
                                    Scheduled C/S Prep:
                                </label>
                                <div class="col-9">
                                    <select id="scs" type="select" class="form-control">
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
                                <label for="cr" class="col-3 col-form-label">
                                    Cervical ripening, Labor, Pitocin induction/augmentation:
                                </label>
                                <div class="col-9">
                                    <select id="cr" type="select" class="form-control">
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
                                <label for="ie" class="col-3 col-form-label">
                                    Initiating Epidural:
                                </label>
                                <div class="col-9">
                                    <select id="ie" type="select" class="form-control">
										<option value=''>..</option>
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
                                <label for="pt" class="col-3 col-form-label">
                                    Pt with Medical/OB complications (mag, insulin, twins, IUFD):
                                </label>
                                <div class="col-9">
                                    <select id="pt" type="select" class="form-control">
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
                                <label for="ccs" class="col-3 col-form-label">
                                    Circulator C/S, PACU:
                                </label>
                                <div class="col-9">
                                    <select id="ccs" type="select" class="form-control">
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
                                <label for="psl" class="col-3 col-form-label">
                                    Patient in 2nd stage labor, recovery:
                                </label>
                                <div class="col-9">
                                    <select id="psl" type="select" class="form-control">
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
							<div style="background-color:#0099CC">
					<h5 class="modal-title">Postpartum </h5>
					</div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="postcount" class="col-3 col-form-label">
                                    Postpartum Nurse Count
                                </label>
                                <div class="col-9">
                                    <input class="form-control m-input" type="number_format" value="" id="postcount" required>
                                </div>
                            </div>
						<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="ppvag" class="col-3 col-form-label">
                                    PP Vag, C/S without complications:
                                </label>
                                <div class="col-9">
                                    <select id="ppvag" type="select" class="form-control">
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
                                <label for="ppwc" class="col-3 col-form-label">
                                    PP Vag, C/S with complications but stable:
                                </label>
                                <div class="col-9">
                                    <select id="ppwc" type="select" class="form-control">
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
                                <label for="ppmag" class="col-3 col-form-label">
                                    PP on Magnesium:
                                </label>
                                <div class="col-9">
                                    <select id="ppmag" type="select" class="form-control">
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
							
													
						   
						   </div>
                    </form>
                </div>
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateProd();">Update</button>
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
                        <input id="dataId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="form_control_1">Your Staffing Matrix shows a variance.  Please add a note with an explanation or an action plan to correct the variance.</label>
                                <textarea class="form-control" rows="3" placeholder="" id="notebody" required></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addProdNote();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
	
</div>