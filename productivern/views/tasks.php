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

$role = intval($_SESSION['account']['role']);
$userId = intval($_SESSION['account']['id']);
$accountId = intval($_SESSION['account']['accountId']);
$company = $_SESSION['account']['company'];
$label = intval($_SESSION['account']['labelId']);
$enterpriseId = intval($_SESSION['account']['enterpriseId']);
$hrsTerm = $_SESSION['account']['hrsTerm'];







?>
<style>
.modal {
    overflow-y: scroll;
}

hr {
  border: none;
  height: 1px;
  /* Set the hr color */
  color: #333;  /* old IE */
  background-color: #333;  /* Modern Browsers */
}
</style>

<!-- BEGIN: Subheader -->
<div class="m-subheader bg-secondary" style="padding-bottom: 30px;">
    
	<div class="mr-auto">
            <h3 class="m-subheader__title ">
                Task Manager
            </h3>
		</div>

</div>
<!-- END: Subheader -->
<div class="m-content bg-secondary">
    <!--Begin::Main Portlet-->
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
				 <div class="row">
                        <div class="col-sm-6"></div>
						<?php if($role >5): ?>
						<div class="col-sm-6">
							<div class="pull-right">
								<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
						
                                        <button type="button" class="btn btn-success" data-target="#addTask" data-toggle="modal" >
                                            New Task
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<?php endif; ?>
			</div>
                    <div class="row">
					
                        <table id="tasksTable"  class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color:black">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
                                    <th width="20%" data-priority="1">Unit</th>
									<th width="15%" data-priority="2">Description</th>
                                    <th width="20%" data-priority="3">Days</th>
									<th width="15%" data-priority="4">Time Due</th>
									<th width="10%" data-priority="5">Past Due Text</th>									
                                    <th width="20%" data-priority="6">Last Updated</th>
                                </tr>
                            </thead>
                        </table>
						</div>
                   
                
            </div>
        </div>
    </div>

    <!--End::Main Portlet-->
 <div class="modal fade" id="copyTask" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Copy This Unit</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="copydeptId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="copyaccountId">Select Unit to where this Unit will be copied.</label>
                            
							<div class="col-8">
                                    <select id="copyaccountId" type="select" class="form-control" >
									<option value="0">Select Unit</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT * FROM `productiveAccount` WHERE `active`>0 order by `name` ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['id']; ?>"><?php echo $d['name'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</div>
								
                            </div>
						</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.copyDept();">Copy</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>	
	
    <!--begin::Modal-->
<div class="modal fade" id="editTask" tabindex="-1" role="basic" aria-hidden="true">
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">Update Task
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				<input id="edittaskId" type="hidden" value="" />
				<input id="copyTask" type="hidden" value="" />
				<input id="logType" type="hidden" value="" />
				<input id="logLock" type="hidden" value="" />
				<div class="row" style="padding-bottom: 30px;">
				
				  <div class="col md-6">
				  <label for="deactivate" class="form-label">
                                    Cancel Task:
                        <input type="checkbox" id="deactivate" name="deactivate" onchange="tj.closeTask()" value=""></input>
                        </label>
						</div>
						<div class="col md-6">
						<button type="button" class="btn btn-info pull-right" onclick="tj.copyTask();">Copy Task</button>
				  </div>
				  </div>
						<div class="m-portlet__body">
						<div class="p-3 mb-2 bg-secondary">
                    <!--begin::Form-->
                    <form id="editaddtaskform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
						
				
						<div class="row">
						  <label for="edittaskName" class="col-9 form-label">
                                    Task Name:
                                 <div>
                                    <input type="text" class="form-control" id="edittaskName" placeholder="">
                                </div>
								</label>
							<label for="edittaskIns" class="col-9 form-label">
                                    Instructions:
                                 <div>
                                    <textarea type="text" class="form-control" rows="2" id="edittaskIns" ></textarea>
                                </div>
								</label>
							</div>
						
						
						  <label for="edittaskUnit" class="col-9 form-label">
                                    Unit:
								<select id="edittaskUnit" class="form-control" >
                                 <option value="0">Select Unit</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT x.*, d.dept FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE x.deptId IN (Select `deptId` from `productiveDeptXref` where `userId`=$userId and `primaryUnit`>=5) and d.active >0 group by x.deptId order by d.dept ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['deptId']; ?>"><?php echo $d['dept'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</label>
						
							<div class="row">
								<div class="col-6">
                                <label for="edittaskDays" class="col-form-label">
                                    Task Days:
                                
                                </label>  
								<select id="edittaskDays" class="form-control" multiple >
										<option value="8">All Days</option>
										<option value="2">Mon</option>
										<option value="3">Tues</option>
										<option value="4">Wed</option>
										<option value="5">Thurs</option>
										<option value="6">Fri</option>
										<option value="7">Sat</option>
										<option value="1">Sun</option>										
								</select>
                                  
                                </div>
							
							<div class="col-6">
								 <label for="edittaskDue" class="col-9 form-label">
                                    Time Due:
                                   </label>   
								<select id="edittaskDue" class="form-control" >
								<option value="0">Select</option>
										<option value="50">01:00</option>
										<option value="48">02:00</option>
										<option value="46">03:00</option>
										<option value="44">04:00</option>
										<option value="42">05:00</option>
										<option value="40">06:00</option>
										<option value="38">07:00</option>
										<option value="36">08:00</option>
										<option value="34">09:00</option>
										<option value="32">10:00</option>
										<option value="30">11:00</option>
										<option value="28">12:00</option>
										<option value="26">13:00</option>
										<option value="24">14:00</option>
										<option value="22">15:00</option>
										<option value="20">16:00</option>
										<option value="18">17:00</option>
										<option value="16">18:00</option>
										<option value="14">19:00</option>
										<option value="12">20:00</option>
										<option value="10">21:00</option>
										<option value="8">22:00</option>
										<option value="6">23:00</option>	
										<option value="52">24:00</option>											
								</select>
                                   
                                </div>
								</div>
						<hr></hr>
						<div class="col-12">
						<div class="modal-title"><strong>Create up to 15 Tasks/Questions, select the answer type and indicate whether or not the answer is required.</strong>
				  </div>
				  </div>
				  <hr></hr>
							<div class="form-group row">
                                <label for="edittask1" class="col-12 form-label">
                                    #1 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask1"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype1" class="form-label">
                                    #1 Question Type:
                        
                                
								<select id="edittype1" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr1" class="form-label">
                                    #1 Answer Required:
                        <input type="checkbox" id="editr1" name="editr1" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						
								<div class="form-group row">
                                <label for="edittask2" class="col-12 form-label">
                                    # 2 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask2"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype2" class="form-label">
                                    #2 Question Type:
                        
                                
								<select id="edittype2" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr2" class="form-label">
                                    #2 Answer Required:
                        <input type="checkbox" id="editr2" name="editr2" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask3" class="col-12 form-label">
                                    # 3 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask3"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype3" class="form-label">
                                    #3 Question Type:
                        
                                
								<select id="edittype3" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr3" class="form-label">
                                    #3 Answer Required:
                        <input type="checkbox" id="editr3" name="editr3" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask4" class="col-12 form-label">
                                    # 4 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask4"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype4" class="form-label">
                                    #4 Question Type:
                        
                                
								<select id="edittype4" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr4" class="form-label">
                                    #4 Answer Required:
                        <input type="checkbox" id="editr4" name="editr4" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask5" class="col-12 form-label">
                                    # 5 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask5"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype5" class="form-label">
                                    #5 Question Type:
                        
                                
								<select id="edittype5" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr5" class="form-label">
                                    #5 Answer Required:
                        <input type="checkbox" id="editr5" name="editr5" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
							<div class="form-group row">
                                <label for="edittask6" class="col-12 form-label">
                                    # 6 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask6"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype6" class="form-label">
                                    #6 Question Type:
                        
                                
								<select id="edittype6" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr6" class="form-label">
                                    #6 Answer Required:
                        <input type="checkbox" id="editr6" name="editr6" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask7" class="col-12 form-label">
                                    # 7 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask7"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype7" class="form-label">
                                    #7 Question Type:
                        
                                
								<select id="edittype7" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr7" class="form-label">
                                    #7 Answer Required:
                        <input type="checkbox" id="editr7" name="editr7" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask8" class="col-12 form-label">
                                    # 8 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask8"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype8" class="form-label">
                                    #8 Question Type:
                        
                                
								<select id="edittype8" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr8" class="form-label">
                                    #8 Answer Required:
                        <input type="checkbox" id="editr8" name="editr8" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask9" class="col-12 form-label">
                                    # 9 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask9"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype9" class="form-label">
                                    #9 Question Type:
                        
                                
								<select id="edittype9" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr9" class="form-label">
                                    #9 Answer Required:
                        <input type="checkbox" id="editr9" name="editr9" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask10" class="col-12 form-label">
                                    # 10 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask10"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype10" class="form-label">
                                    #10 Question Type:
                        
                                
								<select id="edittype10" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr10" class="form-label">
                                    #10 Answer Required:
                        <input type="checkbox" id="editr10" name="editr10" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask11" class="col-12 form-label">
                                    # 11 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask11"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype11" class="form-label">
                                    #11 Question Type:
                        
                                
								<select id="edittype11" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr11" class="form-label">
                                    #11 Answer Required:
                        <input type="checkbox" id="editr11" name="editr11" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask12" class="col-12 form-label">
                                    # 12 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask12"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype12" class="form-label">
                                    #12 Question Type:
                        
                                
								<select id="edittype12" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr12" class="form-label">
                                    #12 Answer Required:
                        <input type="checkbox" id="editr12" name="editr12" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask13" class="col-12 form-label">
                                    # 13 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask13"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype13" class="form-label">
                                    #13 Question Type:
                        
                                
								<select id="edittype13" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr13" class="form-label">
                                    #13 Answer Required:
                        <input type="checkbox" id="editr13" name="editr13" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask14" class="col-12 form-label">
                                    # 14 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask14"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype14" class="form-label">
                                    #14 Question Type:
                        
                                
								<select id="edittype14" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr14" class="form-label">
                                    #14 Answer Required:
                        <input type="checkbox" id="editr14" name="editr14" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="edittask15" class="col-12 form-label">
                                    # 15 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="edittask15"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="edittype15" class="form-label">
                                    #15 Question Type:
                        
                                
								<select id="edittype15" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="editr15" class="form-label">
                                    #15 Answer Required:
                        <input type="checkbox" id="editr15" name="editr15" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>



						
                    </form>
					</div>
                </div>
				</div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateTask();">Update</button>
				</div>
            
            <!-- /.modal-content -->
        </div>
</div>		

 	
</div>

	    <!--begin::Modal-->
<!-- /begin modal -->
<div class="modal fade" id="addTask" tabindex="-1" role="basic" aria-hidden="true">
        
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			
                <div class="modal-header bg-info">
				  <h4 class="modal-title text-white">Create New Task
				  </h4>
				  
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				<div class="row" style="padding-bottom: 30px;">
				<div class="modal-title">
				  </div>
				  </div>
						<div class="m-portlet__body">
						<div class="p-3 mb-2 bg-secondary">
                    <!--begin::Form-->
                    <form id="addtaskform" class="m-form m-form--fit m-form--label-align-right" style="padding-top: 20px;">
						
				
						<div class="row">
						  <label for="taskName" class="col-9 form-label">
                                    Task Name:
                                 <div>
                                    <input type="text" class="form-control" id="taskName" placeholder="">
                                </div>
								</label>
							<label for="taskIns" class="col-9 form-label">
                                    Instructions:
                                 <div>
                                    <textarea type="text" class="form-control" rows="2" id="taskIns" ></textarea>
                                </div>
								</label>
							</div>
						
						
						  <label for="taskUnit" class="col-9 form-label">
                                    Unit:
								<select id="taskUnit" class="form-control" >
                                 <option value="0">Select Unit</option>
									<?php
									$dbDir = Config::get('db')->get_results("SELECT x.*, d.dept FROM `productiveDeptXref` x LEFT JOIN `ProductiveDept` as d on d.id=x.deptId WHERE x.deptId IN (Select `deptId` from `productiveDeptXref` where `userId`=$userId and `primaryUnit`>=5) and d.active >0 group by x.deptId order by d.dept ASC");
									if($dbDir){
									foreach($dbDir as $d) { ?>
									<option value="<?php echo $d['deptId']; ?>"><?php echo $d['dept'] ?></option>
									<?php }
									} ?> 
									
								</select>
								</label>
						
							<div class="row">
								<div class="col-6">
                                <label for="taskDays" class="col-form-label">
                                    Task Days:
                                
                                </label>  
								<select id="taskDays" class="form-control" multiple >
										<option value="8">All Days</option>
										<option value="2">Mon</option>
										<option value="3">Tues</option>
										<option value="4">Wed</option>
										<option value="5">Thurs</option>
										<option value="6">Fri</option>
										<option value="7">Sat</option>
										<option value="1">Sun</option>										
								</select>
                                  
                                </div>
							
							<div class="col-6">
								 <label for="taskDue" class="col-9 form-label">
                                    Time Due:
                                   </label>   
								<select id="taskDue" class="form-control" >
								<option value="0">Select</option>
										<option value="50">01:00</option>
										<option value="48">02:00</option>
										<option value="46">03:00</option>
										<option value="44">04:00</option>
										<option value="42">05:00</option>
										<option value="40">06:00</option>
										<option value="38">07:00</option>
										<option value="36">08:00</option>
										<option value="34">09:00</option>
										<option value="32">10:00</option>
										<option value="30">11:00</option>
										<option value="28">12:00</option>
										<option value="26">13:00</option>
										<option value="24">14:00</option>
										<option value="22">15:00</option>
										<option value="20">16:00</option>
										<option value="18">17:00</option>
										<option value="16">18:00</option>
										<option value="14">19:00</option>
										<option value="12">20:00</option>
										<option value="10">21:00</option>
										<option value="8">22:00</option>
										<option value="6">23:00</option>	
										<option value="52">24:00</option>											
								</select>
                                   
                                </div>
								</div>
						<hr></hr>
						<div class="col-12"">
						<div class="modal-title"><strong>Create up to 15 Tasks/Questions, select the answer type and indicate whether or not the answer is required.</strong>
				  </div>
				  </div>
				  <hr></hr>
							<div class="form-group row">
                                <label for="task1" class="col-12 form-label">
                                    #1 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task1"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type1" class="form-label">
                                    #1 Question Type:
                        
                                
								<select id="type1" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r1" class="form-label">
                                    #1 Answer Required:
                        <input type="checkbox" id="r1" name="r1" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						
								<div class="form-group row">
                                <label for="task2" class="col-12 form-label">
                                    # 2 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task2"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type2" class="form-label">
                                    #2 Question Type:
                        
                                
								<select id="type2" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r2" class="form-label">
                                    #2 Answer Required:
                        <input type="checkbox" id="r2" name="r2" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task3" class="col-12 form-label">
                                    # 3 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task3"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type3" class="form-label">
                                    #3 Question Type:
                        
                                
								<select id="type3" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r3" class="form-label">
                                    #3 Answer Required:
                        <input type="checkbox" id="r3" name="r3" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task4" class="col-12 form-label">
                                    # 4 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task4"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type4" class="form-label">
                                    #4 Question Type:
                        
                                
								<select id="type4" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r4" class="form-label">
                                    #4 Answer Required:
                        <input type="checkbox" id="r4" name="r4" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task5" class="col-12 form-label">
                                    # 5 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task5"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type5" class="form-label">
                                    #5 Question Type:
                        
                                
								<select id="type5" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r5" class="form-label">
                                    #5 Answer Required:
                        <input type="checkbox" id="r5" name="r5" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
							<div class="form-group row">
                                <label for="task6" class="col-12 form-label">
                                    # 6 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task6"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type6" class="form-label">
                                    #6 Question Type:
                        
                                
								<select id="type6" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r6" class="form-label">
                                    #6 Answer Required:
                        <input type="checkbox" id="r6" name="r6" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task7" class="col-12 form-label">
                                    # 7 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task7"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type7" class="form-label">
                                    #7 Question Type:
                        
                                
								<select id="type7" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r7" class="form-label">
                                    #7 Answer Required:
                        <input type="checkbox" id="r7" name="r7" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task8" class="col-12 form-label">
                                    # 8 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task8"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type8" class="form-label">
                                    #8 Question Type:
                        
                                
								<select id="type8" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r8" class="form-label">
                                    #8 Answer Required:
                        <input type="checkbox" id="r8" name="r8" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task9" class="col-12 form-label">
                                    # 9 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task9"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type9" class="form-label">
                                    #9 Question Type:
                        
                                
								<select id="type9" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r9" class="form-label">
                                    #9 Answer Required:
                        <input type="checkbox" id="r9" name="r9" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task10" class="col-12 form-label">
                                    # 10 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task10"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type10" class="form-label">
                                    #10 Question Type:
                        
                                
								<select id="type10" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r10" class="form-label">
                                    #10 Answer Required:
                        <input type="checkbox" id="r10" name="r10" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task11" class="col-12 form-label">
                                    # 11 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task11"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type11" class="form-label">
                                    #11 Question Type:
                        
                                
								<select id="type11" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r11" class="form-label">
                                    #11 Answer Required:
                        <input type="checkbox" id="r11" name="r11" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task12" class="col-12 form-label">
                                    # 12 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task12"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type12" class="form-label">
                                    #12 Question Type:
                        
                                
								<select id="type12" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r12" class="form-label">
                                    #12 Answer Required:
                        <input type="checkbox" id="r12" name="r12" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task13" class="col-12 form-label">
                                    # 13 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task13"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type13" class="form-label">
                                    #13 Question Type:
                        
                                
								<select id="type13" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r13" class="form-label">
                                    #13 Answer Required:
                        <input type="checkbox" id="r13" name="r13" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task14" class="col-12 form-label">
                                    # 14 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task14"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type14" class="form-label">
                                    #14 Question Type:
                        
                                
								<select id="type14" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										<!-- <option value="3">Checkbox</option> -->
										</select>
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r14" class="form-label">
                                    #14 Answer Required:
                        <input type="checkbox" id="r14" name="r14" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>
						<div class="form-group row">
                                <label for="task15" class="col-12 form-label">
                                    # 15 Question / Task:
                                 <div>
        						<textarea class="form-control" rows="3" id="task15"></textarea>
                                </div>
								</label>
								
                            </div>
							<div class="form-group row">
							<div class="col-md-6">
							<label for="type15" class="form-label">
                                    #15 Question Type:
                        
                                
								<select id="type15" type="select" class="form-control">
										<option value="0">Select</option>
										<option value="2">Input Field (Text)</option>
										<option value="4">Input Field (Number Only)</option>
										<option value="1">Select Field (Yes/No)</option>
										</select>
										<!-- <option value="3">Checkbox</option> -->
						</label>
						</div>
						<div class="col-md-6" style="margin-top:20px">
						<label for="r15" class="form-label">
                                    #15 Answer Required:
                        <input type="checkbox" id="r15" name="r15" value=""></input>
                        </label>
						</div>
						</div>
						<hr></hr>



						
                    </form>
					</div>
                </div>
                <div class="modal-footer bg-info">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.saveTask();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
</div>
        <!-- /.modal-dialog -->
		

    <!--end::Modal-->

    <!--begin::Modal-->
</div>
	
<!--begin::Modal-->


	


