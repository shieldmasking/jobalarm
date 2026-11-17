<?php
ini_set('display_errors',1);
include "../inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';


$payPeriod = intval($_SESSION['account']['payPeriod']);
$currentDay = intval($_SESSION['account']['currentDay']);
$payFirst = intval($_SESSION['account']['firstDay']);
$role = intval($_SESSION['account']['role']);
$demoMode = intval($_SESSION['account']['demoMode']);
$sort = intval($_SESSION['account']['dashboardSort']);
$vids = intval($_SESSION['account']['trainingVids']);
$userId = intval($_SESSION['account']['id']);
$accountId = intval($_SESSION['account']['accountId']);
$transferPortal = intval($_SESSION['account']['transferPortal']);
$startPay = $_SESSION['account']['newStartPay'];
$endPay = $_SESSION['account']['newEndPay'];
$useSafety = $_SESSION['account']['useSafety'];

if(intval($useSafety)==1 && $role>5){
	$safety = '';
}else{
	$safety = "hidden";
}


?><!-- BEGIN: Subheader -->

<div class="m-subheader">
<div class="col-md-12">
	<input id="startDate" type="hidden" value="" />
	<input id="endDate" type="hidden" value="" />
	<input id="currentSort" type="hidden" value="<?php echo $sort; ?>" />
	<input id="startDay" type="hidden" value="" />
	<input id="endDay" type="hidden" value="" />
	<input id="startPay" type="hidden" value="<?php echo $startPay; ?>" />
	<input id="endPay" type="hidden" value="<?php echo $endPay; ?>" />
	<input id="role" type="hidden" value="<?php echo $role; ?>" />
	<input id="demoMode" type="hidden" value="<?php echo $demoMode; ?>" />
	<input id="accountId" type="hidden" value="<?php echo $accountId; ?>" />

<div class="form-group"  style="padding-bottom: 20px;">
<?php if (intval($_SESSION['account']['id']) === 1799 || intval($_SESSION['account']['id']) === 1798): ?>
<div style="margin-bottom: 10px;">
    <a href="https://docs.google.com/spreadsheets/d/1Y8njAGAYMjcenBWedHWa1DgqHUqXbXEYiqjzrCDr8fA/edit?gid=1305284735#gid=1305284735" target="_blank" class="btn btn-sm btn-primary">📊 Sheets Dashboard</a>
</div>
<?php endif; ?>
<span><a href="javascript:;" onclick="tj.categorySort(1)" id="sort1"><u>Sorted By: Unit Name</u></a>
</span><span><a href="javascript:;" onclick="tj.categorySort(0)" id="sort0"><u>Sorted By: Variance</u></a></span>
<div style="padding-top: 10px;"<?php echo $safety; ?>>
<label><input type="checkbox" id="showSafety" name="showSafety" onchange="tj.showSafety();"><strong>Show Safety</strong></label>
</div>
									
<div class="pull-right">
			<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
			
                <span class="m-subheader__daterange-label">
                    <span class="m-subheader__daterange-title"></span>
                    <span class="m-subheader__daterange-date m--font-brand"></span>
                </span>
                <a href="javascript:;" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                    <i class="la la-angle-down"></i>
                </a>
            </span>	
		</div>
<div class="row pull-right">

			
			<?php if($role>7 && $role<13) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, a.nickName, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left join `productiveAccount` as a on a.id=c.accountId where d.active>0 AND c.id !=0 AND d.accountId={$_SESSION['account']['accountId']} group by c.id order by c.categoryName ASC");
			}else if($role>=13) {
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, a.nickName, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left join `productiveAccount` as a on a.id=c.accountId where d.active>0 AND c.id !=0 AND a.enterpriseId={$_SESSION['account']['enterpriseId']} group by c.id order by c.categoryName ASC");
			}else{
			$dbData = Config::get('db')->get_results("SELECT d.*, c.id as categoryId, a.nickName, c.categoryName from `ProductiveDept` d left join `productiveCategory` as c on c.id=d.category left join `productiveAccount` as a on a.id=c.accountId where d.active>0 AND c.id !=0 AND d.id IN (SELECT `deptId` from `productiveDeptXref` where `userId`={$_SESSION['account']['id']}) group by c.id order by c.categoryName ASC");	
			}?>
		
			<select  class="form-control" id="filter" name="filter" onchange="tj.categorySelect('');">
			<option value="0">All Service Lines</option>
			<?php foreach($dbData as $m) { ?>
			<option value="<?php echo $m['categoryId'];?>"><?php echo $m['nickName'] . ' - ' . $m['categoryName'];?> </option>
			<?php 	} ?>									
			</select>
			</div>


</div>
<div class="col-6 pull-right">
				<button type="button" id="Portal" class="btn btn-success pull-right" onclick="tj.portal();" hidden>
                Transfer Portal
                </button> 
				</div>


</div>

</div>

<!-- END: Subheader -->
<div class="m-content">
<span id="reportBody"></span>
<span id="chartBody"></span>
    <!--End::Main Portlet-->
 
 <div class="modal fade" id="rpthuddle" tabindex="-1" role="basic" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
				<h3 class="modal-title" style="color:white">Safety Issue</h3>
                    <button type="button" class="close" data-dismiss="modal"></button>
                    
                </div>
                <div class="modal-body">
				
                    <form id="rptsafetyForm">
                    <input id="rptsafetyId" type="hidden" value="" />
					
					<div class="form-group">
					<div class="row">
				
					<label><strong>Submitted: </strong></label><span id="submittedDate"></span>
					</div>
					</div>
					<div class="form-group">
					<div class="row">
					<div class="col-md-4">
					<label for="rptunit1"><strong>Unit:</strong>
					 <select id="rptunit1" type="select" class="form-control">
									<option value="0">Select</option>
									<option value="1">Hospital</option>
															
								<?php
								
								if(intval($role)>7){
								$dbunits = Config::get('db')->get_results("SELECT x.*, d.dept from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where d.accountId={$accountId} AND (d.active=1 OR d.active=2) group by x.deptId order by d.dept ASC");	
								}else{
								$dbunits = Config::get('db')->get_results("SELECT x.*, d.dept from `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId where x.deptId IN (SELECT `deptId` FROM `productiveDeptXref` where `userId`={$userId}) AND d.accountId={$accountId} AND (d.active=1 OR d.active=2) group by x.deptId order by d.dept ASC");								
								}
								foreach($dbunits as $x) 
								{ 
								?>
								<option value="<?php echo $x['deptId'];?>"><?php echo $x['dept'];?> </option>
								<?php 
								} 
								?>
							</select>
					</label>
					</div>
					
					<div class="col-md-4">
					<label for="rptloc1"><strong>Room/Location:</strong>
					<input id="rptloc1" class="form-control border-dark" type="text"></input>
					</label>
					</div>
					<div class="col-md-4">
					<label for="rptdue1"><strong>Due Date:</strong>
					<input id="rptdue1" class="form-control border-dark" type="date"></input>
					</label>
					</div>
					</div>
					
					</div>
					<div class="form-group" id="rptType">
					<label><strong>Safety Type:</strong></label>
					<div class="row">
					
						<div class="col-md-4">
							<div class="form-group">
                                <label for="rptperi1">Primary Concern:</label>
                              				 <select id="rptperi1" type="select" class="form-control">
									<option value="0">Select</option>
															
								<?php
								
								
								$dbpt = Config::get('db')->get_results("SELECT t.*, c.id as safeId, c.safetyDesc, c.removeId, c.accountId from `productivesafetyType` t left join `productivesafetyConfig` as c on c.safetyType=t.id where (c.accountId={$accountId} OR c.accountId=0) and c.removeId=0 and t.typeId=1 group by c.id order by t.typeDesc ASC, c.safetyDesc ASC");	
								
								foreach($dbpt as $x) 
								{ 
								?>
								<option value="<?php echo $x['safeId'];?>"><?php echo $x['typeDesc'] . " - " . $x['safetyDesc'];?> </option>
								<?php 
								} 
								?>
							</select>										
                            </div>
						</div>
						<!--
						<div class="col-md-4 vl">
							<div class="form-group form-md-line-input">
                                <label for="rpthrpt1">Workplace Safety:</label>
                              			<select id="rpthrpt1" type="select" class="form-control">
									<option value="0">Select</option>
															
								
							</select>
										
                            </div>
						</div>
						<div class="col-md-4 vl">
							<div class="form-group form-md-line-input">
                                <label for="rptperigen1">Facility Safety:</label>
                              			<select id="rptperigen1" type="select" class="form-control">
									<option value="0">Select</option>
															
								
							</select>
										
                            </div>
						</div>
						-->
						</div>
						</div>
					<hr></hr>
					<div class="form-group">
					<label for="rptrdesc1"><strong>Note:</strong></label>
					<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule.
					</div>
					<textarea id="rptrdesc1" class="form-control border-dark" style="min-width: 100%" rows="5"></textarea>
					
					
					<label><strong>High Priority</strong></label>
					<input type="checkbox" id="rptpriority" name="rptpriority" />
					</div>
					<div class="form-group">
					<label><strong>Remove from Dashboard</strong></label>
					<input type="checkbox" id="rptactive" name="rptactive" />
					</div>
                    </form>
                
				</div>
                <div class="modal-footer bg-info">
				
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="rptsafetyButton" class="btn btn-primary" onclick="tj.updaterptSafety();">Save</button>
                </div>
            
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
 </div>
</div>
	
<div class="modal fade" id="staffSummary" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title" style="color:white">STAFFING SUMMARY</br><span id="staffName"></span>
					</div>
					
                </div>
			
                <div class="modal-body">
                        <input id="staffdataId" type="hidden" value="" />
						<span id="summaryStaff"></span>
                    
                </div>
                <div class="modal-footer bg-info">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="dayData" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title" style="color:white" ><h4><span id="daydataDept"></span></h4>
					</div>
					
                </div>
			
                <div class="modal-body bg-secondary">
                        <input id="daydataId" type="hidden" value="" />
					<div class="modal-title" style="margin-bottom:20px" ><h5>Last Update: <span id="daydataDate"></span></h5>
					</div>
						
					<div id="data1">
                                <label for="daydata1" class="col-6">
                                    <span id="daydataName1"></span>
                                  <input id="daydesc1" type="hidden" value="" />            
								<!--<input type="number" min="0" class="form-control custom-input" id="daydata1" style="text-align: right" />-->
                                <select id="daydata1" type="select" class="form-control" >
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
									<option value=20>20</option></select>
								</label>	
					</div>	
					<div id="data2">
                                <label for="daydata2" class="col-6">
                                    <span id="daydataName2"></span>
                                              <input id="daydesc2" type="hidden" value="" /> 
								<!--<input type="number" min="0" class="form-control custom-input" id="daydata2" style="text-align: right" />-->
                                <select id="daydata2" type="select" class="form-control" >
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
									<option value=20>20</option></select>
								</label>	
					</div>
					<div id="data3">
                                <label for="daydata3" class="col-6">
                                    <span id="daydataName3"></span>
                                      <input id="daydesc3" type="hidden" value="" />         
								<!--<input type="number" min="0" class="form-control custom-input" id="daydata3" style="text-align: right" />-->
                                <select id="daydata3" type="select" class="form-control" >
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
									<option value=20>20</option></select>
								</label>	
					</div>
					<div id="data4">
                                <label for="daydata4" class="col-6">
                                    <span id="daydataName4"></span>
                                   <input id="daydesc4" type="hidden" value="" />            
								<!--<input type="number" min="0" class="form-control custom-input" id="daydata4" style="text-align: right" />-->
                                <select id="daydata4" type="select" class="form-control" >
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
									<option value=20>20</option></select>
								</label>	
					</div>
						
                    
                </div>
                <div class="modal-footer bg-secondary">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<span id="dayButton"></span>
				
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<div class="modal fade" id="blockbeds" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title" style="color:white"><span id="deptName"></span></div>
					
                </div>
                <div class="modal-body bg-secondary">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
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
                <div class="modal-footer bg-secondary" >
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="updateblockedBeds();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
<div class="modal fade" id="showTracking" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Tracking Details</h4>
					 </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="trackId" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">
                            <span id="trackSubmitted" ></span>	
							</div>
							<div>
                            <label for="trackUnit">Unit:</label>
							<div>
                            <input class="form-control" id="trackUnit" value="" disabled>	
							</div>
							</div>
							<hr></hr>
							<div class="form-group">
							<div class="row">
							
                            <div class="col-md" id="track1Details">
                            <label for="track1Number"><span id="track1Type"></span></label>
							<div>
                             <input class="form-control" id="track1Number" value="" disabled>
							</div>						 
							<div>
                                <label for="track1Comment">Note:</label>
                                <textarea class="form-control" rows="2" id="track1Comment" disabled></textarea>
                            </div>
							</div>
							
                            <div class="col-md" id="track2Details">
                            <label for="track2Number"><span id="track2Type"></span></label>
							<div>
                             <input class="form-control" id="track2Number" value="" disabled>
							</div>						 
                            
							<div>
                                <label for="track2Comment">Note:</label>
                                <textarea class="form-control" rows="2" id="track2Comment" disabled></textarea>
                            </div>
							</div>
							
							</div>
							</div>
							<div class="form-group">
							<div class="row">
							<div id="track3Details">
                            <div class="col-md">
                            <label for="track3Number"><span id="track3Type"></span></label>
							<div>
                             <input class="form-control" id="track3Number" value="" disabled>
							</div>						 
							<div>
                                <label for="track3Comment">Note:</label>
                                <textarea class="form-control" rows="2" id="track3Comment" disabled></textarea>
                            </div>
							</div>
							</div>
							<div id="track4Details">
                            <div class="col-md">
                            <label for="track4Number"><span id="track4Type"></span></label>
							<div>
                             <input class="form-control" id="track4Number" value="" disabled>
							</div>						 
                            
							<div>
                                <label for="track4Comment">Note:</label>
                                <textarea class="form-control" rows="2" id="track4Comment" disabled></textarea>
                            </div>
							</div>
							</div>
							</div>
							</div>
							</div>
							
							
							</div>
                    </form>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>
	
<div class="modal fade" id="showEscalation" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" style="color:white">Escalation Details</h4>
					 </div>
                <div class="modal-body col-12">
                    <form role="form">
                        <input id="escdataId" type="hidden" value="" />
						<div class="form-body">
						<div class="form-group">
                            <span id="escSubmitted" ></span>	
							</div>
							<div class="form-group">
                            <label for="escUnit">Unit:</label>
							<div class="col-9">
                            <input class="form-control" id="escUnit" value="" disabled>	
							</div>
							</div>
                            <div class="form-group">
                            <label for="escType">Escalation Type:</label>
							<div class="col-9">
                             <input class="form-control" id="escType" value="" disabled>
							</div>							 
                            </div>
							
							<div class="form-group">
                                <label for="escComment">Comments:</label>
                                <textarea class="form-control" rows="4" id="escComment" disabled></textarea>
                            </div>
							<hr></hr>
							<div class="form-group" id="responseHistory" hidden>
                                <label for="escResponse"><strong>Response History:</strong></label>
                                <textarea class="form-control" rows="3" id="escResponse" disabled></textarea>
                            </div>	
							<div class="form-group" hidden>
                                <label for="escResponse1"><strong> Add Response:</strong></label>
								<div class="title" style="color:red">Do not include any Protected Health Information (PHI), as defined by the HIPAA Privacy Rule, in your comments.
							</div>
                                <textarea class="form-control" rows="3" id="escResponse1" ></textarea>
                            </div>
							<div class="form-group" hidden>									
							  <label> <input type="checkbox" id="closeEsc" name="closeEsc" /><strong> Close Escalation</strong></label>
							</div>
					</div>
                    </form>
                 </div>
                <div class="modal-footer bg-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<!--<button type="button" class="btn btn-secondary" onclick="tj.updateEscalation();">Save</button>-->
                </div>
           
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <!--end::Modal-->
</div>

<div class="modal fade" id="transferportal" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-title" style="color:white">TRANSFER PORTAL
					</div>
					
                </div>
                <div class="modal-body">
				<span id="pbutton" class="pull-right"></span>
                    <form role="form">
                        
                        <div class="form-body">
							<div class="form-group">
							<span id="transfer1"></span>
							
                          
                            </div>
						

                        </div>
                    </form>
				
                </div>
                <div class="modal-footer bg-secondary">
					
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="transferButton" class="btn btn-primary" onclick="tj.updatePortal();">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

