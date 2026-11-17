<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';


$role = intval($_SESSION['account']['role']);
$accountId = intval($_SESSION['account']['accountId']);
$userId = intval($_SESSION['account']['userId']);
$deptName = $_SESSION['account']['deptName'] . " (" . $_SESSION['account']['unitId'] . ")";



?>

<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
             <?php echo $deptName; ?> Settings
            </h3>
        </div>
		<div>

        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
   
        <div class="col-xl-12" id="details">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
					
				 
					<form id="configure_form" class="form">
					<div class="form-body">
					<div class="title"><h4>
				 Unit Details</h4>
				 </div>
						<input id="dept" type="hidden" value="" />
						<input id="account" type="hidden" value="" />
							
							<div class="form-group">
							    <label for="depName">Name</label>
								<div class="col-xs-4">
                                <input type="text" id="depName" required>
							</div>
                            </div>
							<div class="form-group">	
							    <label for="unitId">Number/ID  </label>
								<div class="col-xs-1">
                                <input type="text" id="unitId" required>
							</div>	
                            </div>
							<div class="form-group">	
							    <label for="totalbeds">Beds  </label>
								<div class="col-xs-1">
                                <input type="number" id="totalbeds" required>
							</div>	
                            </div>
						 
						 
						</div>	
                        
						
                    <hr></hr>
                        <div class="form-body">
						<div class="title"><h4>
				 Productivity</h4>
				 </div>
							<div class="form-group">
                            <label for="prodMeasure">How is your productivity measured:</label>
                             <div class="col-xs-1">   
                            <select id="prodMeasure" type="select" class="input-medium" required >
										<option value=1>HPPD</option>
										<option value=2>Encounters</option>
										<option value=3>Other</option>
										</select>
                             </div>
							</div>
							<div class="form-group">
							    <label for="prodValue">HPPD or Encounters Value</label>
								<div class="col-xs-1">
                                <input type="text" class="number" id="prodValue" required>
							</div>
                            </div>
							<div class="form-group">	
							    <label for="target">Target Productivity % </label>
								<div class="col-xs-1">
                                <input type="number" id="target" required>
							</div>	
                            </div>
							<div class="form-group">
                               	<label for="censusShift">What time is your census taken:</label>
                                <div class="col-xs-1">
                                    <select id="censusShift" type="select" class="input-large" required >
										<option value=52>12:00 Midnight</option>
										<option value=50>1:00 AM</option>
										<option value=48>2:00 AM</option>
										<option value=46>3:00 AM</option>
										<option value=44>4:00 AM</option>
										<option value=40>5:00 AM</option>
										<option value=38>6:00 AM</option>
										<option value=36>7:00 AM</option>
										</select>
                            </div>
						 </div>
						 
						 
						 
						</div>	
                        
						
                    <hr></hr>
					<div class="title">
				 <h4>Acuity Levels</h4>
				 </div>
				 <div class="title">
				 <p>Chose the acuity levels that you want in your reports.  Descriptions can be as simple as High, Med, Low or more specific description of patient conditions.</p>
				 </div>
				
                       
						<div class="form-group">
							<label class="form-group-label" class="col-3 col-form-label">
							<input type="checkbox" id="one2oneChecked" name="one2oneChecked" value="1" />
							<strong> 1 to 1</strong>
							</label>
							<div>
							<label for="descConfig1" class="col-6 col-form-label">Description:
							
							<input type="text" id="descConfig1" class="form-control">
							</label>
							</div>
						</div>
						
						<div class="form-group">
							<label class="form-group-label" class="col-3 col-form-label">
							<input type="checkbox" id="one2twoChecked" name="one2twoChecked" value="1" />
							<strong> 1 to 2</strong>
							</label>
							<div>
							<label for="descConfig2" class="col-6 col-form-label">Description: 
							<input type="text" id="descConfig2" class="form-control">
							</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-group-label" class="col-3 col-form-label">
							<input type="checkbox" id="one2threeChecked" name="one2threeChecked" value="1" />
							<strong> 1 to 3</strong></br>
							</label>
							<div>
							<label for="descConfig3" class="col-6 col-form-label">Description:
							<input type="text" id="descConfig3" class="form-control">
							</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-group-label"class="col-3 col-form-label">
							<input type="checkbox" id="one2fourChecked" name="one2fourChecked" value="1" />
							<strong> 1 to 4</strong></br>
							</label>
							<div>
							<label for="descConfig4" class="col-6 col-form-label">Description:
							<input type="text" id="descConfig4" class="form-control">
							</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-group-label">
							<input type="checkbox" id="one2fiveChecked" name="one2fiveChecked" value="1" />
							<strong> 1 to 5</strong></br>
							</label>
							<div>
							<label for="descConfig5" class="col-6 col-form-label">Description:
							<input type="text" id="descConfig5" class="form-control">
							</label>
							</div>
						</div>
						<div class="form-group">
							<label class="form-group-label" class="col-3 col-form-label">
							<input type="checkbox" id="one2sixChecked" name="one2sixChecked" value="1" />
							<strong> 1 to 6</strong></br>
							</label>
							<div>
							<label for="descConfig6" class="col-6 col-form-label">Description:  
							<input type="text" id="descConfig6" class="form-control">
							</label>
							</div>
						</div>
						<!--
						<div class="form-group">
							<label class="form-group-label">
							<input type="checkbox" id="two2one" name="two2one" value="1" />
							<strong> 2 to 1</strong></br>
							</label>
							<div class="form-group col-xs-4">
							<label for="desc21">Description:  </label>
							<input type="text medium" id="desc21" required >
							</div>
						</div>
						-->
						
                        </form>
				<!--		
			<hr></hr>
			
			<div class="title"><h5>
				 Report Times</h5>
				 </div>
				 
              	
						<div class="form-group col-xs-4 col-md-4">
                                
								<label for="reportTimes">Select Your Reporting Times:</label>
                             <div>   
                            <select multiple id="reportTimes" class="input-medium" required >
										<option value=52>12:00 Midnight</option>
										<option value=51>12:30 AM</option>
										<option value=50>1:00 AM</option>
										<option value=49>1:30 AM</option>
										<option value=48>2:00 AM</option>
										<option value=47>2:30 AM</option>
										<option value=46>3:00 AM</option>
										<option value=45>3:30 AM</option>
										<option value=44>4:00 AM</option>
										<option value=43>4:30 AM</option>
										<option value=42>5:00 AM</option>
										<option value=41>5:30 AM</option>
										<option value=40>6:00 AM</option>
										<option value=39>6:30 AM</option>
										<option value=38>7:00 AM</option>
										<option value=37>7:30 AM</option>
										<option value=36>8:00 AM</option>
										<option value=35>8:30 AM</option>
										<option value=34>9:00 AM</option>
										<option value=33>9:30 AM</option>
										<option value=32>10:00 AM</option>
										<option value=31>10:30 AM</option>
										<option value=30>11:00 AM</option>
										<option value=29>11:30 AM</option>
										<option value=28>12:00 Noon</option>
										<option value=27>12:30 PM</option>
										<option value=26>1:00 PM</option>
										<option value=25>1:30 PM</option>
										<option value=24>2:00 PM</option>
										<option value=23>2:30 PM</option>
										<option value=22>3:00 PM</option>
										<option value=21>3:30 PM</option>
										<option value=20>4:00 PM</option>
										<option value=19>4:30 PM</option>
										<option value=18>5:00 PM</option>
										<option value=17>5:30 PM</option>
										<option value=16>6:00 PM</option>
										<option value=15>6:30 PM</option>
										<option value=14>7:00 PM</option>
										<option value=13>7:30 PM</option>
										<option value=12>8:00 PM</option>
										<option value=11>8:30 PM</option>
										<option value=10>9:00 PM</option>
										<option value=9>9:30 PM</option>
										<option value=8>10:00 PM</option>
										<option value=7>10:30 PM</option>
										<option value=6>11:00 PM</option>
										<option value=5>11:30 PM</option>
										</select>
                             </div> 
						 
						 </div>
						
                      -->  
						
                    <hr></hr>
                    
					<div class="title"><h4>Additional Resources (with Productive Hours)</h4>
				  </div>
				  <div>
						
                                        <button type="button" class="btn btn-danger" data-target="#addResource" data-toggle="modal">
                                            Add
                                        </button>                         
					 </div>
					 <div>
					<table id="resourceTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="16%" data-priority="1">Resources</th>
                                    <th width="10%" data-priority="2">Sun</th>
									<th width="10%" data-priority="3">Mon</th>
									<th width="10%" data-priority="4">Tues</th>
									<th width="10%" data-priority="5">Wed</th>
									<th width="10%" data-priority="6">Thur</th>
									<th width="10%" data-priority="7">Fri</th>
									<th width="10%" data-priority="8">Sat</th>
									<th width="14%" data-priority="9">Action</th>
                                    
                                </tr>
                            </thead>
                        </table>
					</div>
					<hr></hr>
                    
					<div class="title"><h4>Addtional Settings</h4>
				  </div>
				  
				  <div class="title">To add a Staffing Grid or if you would like to adjust your reporting times, please contact your ProductiveRN Representative.
				  </div>
					
					 
					  <hr></hr>
                 <div class="footer">
                    
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.updateConfig();">Save</button>
                </div>   
				 </div>	
					
              
            
			</div>
    </div>

    <!--begin::Modal-->

	<div class="modal fade" id="addResource" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add a Resource to your Productivity</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="deptId" type="hidden" value="" />
						<input id="accountId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="resourceName">Resource Name:</label>
                                <input class="form-control" type="text" id="resourceName" required >
                            </div>
							<hr></hr>
							<h5 class="modal-title">Productive Hours for Each Day</h5>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sunHours" class="col-4 col-form-label">Sunday:</label>
                                <div>
								
                                    <select id="sunHours" type="select" class="form-control">
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
										</select>
							
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="monHours" class="col-4 col-form-label">Monday:</label>
                                <div>
                                    <select id="monHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="tueHours" class="col-4 col-form-label">Tuesday:</label>
                                <div>
                                    <select id="tueHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="wedHours" class="col-4 col-form-label">Wednesday:</label>
                                <div>
                                    <select id="wedHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="thuHours" class="col-4 col-form-label">Thursday:</label>
                                <div>
                                    <select id="thuHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="friHours" class="col-4 col-form-label">Friday:</label>
                                <div>
                                    <select id="friHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="satHours" class="col-4 col-form-label">Saturday:</label>
                                <div>
                                    <select id="satHours" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.addResource();">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	
<!--begin::Modal-->

	<div class="modal fade" id="editResource" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Resource Hours</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="recordId" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="editName">Resource Name:</label>
                                <input class="form-control" type="text" id="editName" required >
                            </div>
							<hr></hr>
							<h5 class="modal-title">Productive Hours for Each Day</h5>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="sunEdit" class="col-4 col-form-label">Sunday:</label>
                                <div>
                                    <select id="sunEdit" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="monEdit" class="col-4 col-form-label">Monday:</label>
                                <div>
                                    <select id="monEdit" type="select" class="form-control">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="tueEdit" class="col-4 col-form-label">Tuesday:</label>
                                <div>
                                    <select id="tueEdit" type="select" class="form-control input-sm">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="wedEdit" class="col-4 col-form-label">Wednesday:</label>
                                <div>
                                    <select id="wedEdit" type="select" class="form-control input-sm">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="thuEdit" class="col-4 col-form-label">Thursday:</label>
                                <div>
                                    <select id="thuEdit" type="select" class="form-control input-sm">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="friEdit" class="col-4 col-form-label">Friday:</label>
                                <div>
                                    <select id="friEdit" type="select" class="form-control input-sm">
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
										</select>
                            </div>
                            </div>
							<div class="form-group m-form__group m--margin-top-10 row">
                                <label for="satEdit" class="col-4 col-form-label">Saturday:</label>
                                <div>
                                    <select id="satEdit" type="select" class="form-control input-sm">
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
										</select>
                            </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="noteButton" class="btn btn-primary" onclick="tj.updateResource();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
    <!--End::Main Portlet-->
</div>