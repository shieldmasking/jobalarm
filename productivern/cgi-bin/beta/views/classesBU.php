<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';

?>
<html lang="en">
<body>
<head>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>-->
<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</head>
<script>
$('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
      });
</script>

<!-- BEGIN: Subheader -->
<div class="m-subheader">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Birthing Reports
            </h3>
        </div>
		<div>
            <span class="m-subheader__daterange" id="class_daterangepicker">
                <span class="m-subheader__daterange-label">
                    <span class="m-class__daterange-title"></span>
                    <span class="m-class__daterange-date m--font-brand"></span>
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
				
				
				 <div class="row" id="newclass">
					<div class="pull-right">
						<div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
										
                                        <button type="button" class="btn btn-success" data-target="#addClass" data-toggle="modal">
                                            New Report
                                        </button>                         
					 </div>
                                <!--end: Dropdown-->
                            </div>
					
					</div>
					<div class="row" id="classtable">
					<div class="col-xl-12">
                    				
                        <table id="classesTable"  class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="20%" data-priority="1">Class</th>
                                    <th width="20%" data-priority="2">Date / Time</th>
									<th width="20%" data-priority="3">Attendees</th>
									<th width="10%" data-priority="4">Single</th>
									<th width="10%" data-priority="5">Twins</th>
									<th width="10%" data-priority="6">Triplets</th>
									<th width="10%" data-priority="7">Other</th>
                                 </tr>
                            </thead>
                        </table>
						</div>
						</div>
					<div class="row" id="detailtable">	
					<div class="col-xl-12">
					<div class="title"><h4><span id="reportName"></span></h4>
					</div>
					<div class="title"><h5><span id="reportDate"></span></h5>
					</div>
					<div class="title"><h5><span id="reportTime"></span></h5>
					</div>
                    <form method="post" id="insert_form">
					<input id="classId" type="hidden" value="" />
					<div class="table-responsive">
                        <table id="item_table"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
    <!--                                <th></th>-->
    <!--                                <th></th>-->
									<th width="25%" data-priority="1">EDC</th>
									<th width="10%" data-priority="2">Babies</th>
									<th width="10%" data-priority="4">Gravida</th>
									<th width="10%" data-priority="5">Para</th>
									<th width="10%" data-priority="6">Birth Plan</th>
									<th width="25%" data-priority="7">Complication</th>
									<th><button type="button" name="add" class="btn btn-success btn-sm add" width="10%" data-priority="3">Add</button></th>                                  
									<th hidden>id</th>
								</tr>
                            </thead>
                        </table>
						</div>
						<div align="right">
						<input type="button" name="cancelsubmit" class="btn btn-danger" onclick="tj.cancelclass();" value="Cancel" />
						<input type="submit" name="submitbutton" class="btn btn-info" value="Save" />
						</div>
						</form>
						</div>
                    
                    </div>
                </div>
            </div>
        </div>
 </div>
    <!--End::Main Portlet-->

    <!--begin::Modal-->
	    <!--begin::Modal-->
<div class="modal fade" id="addClass" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			
                <div class="modal-header bg-light">
				  <div class="modal-title">New Birthing Report
				  </div>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				
                    <!--begin::Form-->
                    <form id="add_class_form" class="m-form m-form--fit m-form--label-align-right">
                        
								
						<div class="m-portlet__body">
						<div class="form-group">
                                <label for="className" class="col-4 col-form-label">Report or Class Name</label>
								<div class="col-8">
                                <input type="text" id="className" >
								</div>
                            </div>
						<div class="form-group">
                                <label for="classDate" class="col-4 col-form-label">
                                   Class Date:
                                </label>
								<div class="col-8">
                               <input type="date" id="classDate" class="form-control" name="classDate" value="" /> 
								</div>
						  </div>
							
							
						<div class="form-group">
                                <label for="classTime" class="col-4 col-form-label">
                                   Class Time:
                                </label>
								<div class="col-8">
                                <input type="time" name="time" id="classTime" class="form-control" />
								</div>
                            </div>
						<div class="form-group">
                                <label for="attendTotal" class="col-4 col-form-label"><strong>
                                    Total Attendees (Expecting):</strong>
                                </label>
                                <div class="col-8">
                                    <select id="attendTotal" type="select" class="form-control">
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
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
										<option value="29">29</option>
										<option value="30">30</option>
										</select>
                                </div>
                            </div>
						
													   
						   </div>
                    </form>
                
                <div class="modal-footer">
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.birthClass();">Continue</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>

</div>
    <!--end::Modal-->
</body>
</html>

<script>
$(document).ready(function(){
	$(document).on('click', '.add', function(){
	var html = '';
	html += '<tr>';
	html += '<td><input type="date" name="edc[]" value="" class="form-control edc" /></td>';
	html += '<td><select name="count[]" class="form-control count"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></td>';
	html += '<td><select name="grav[]" class="form-control grav" ><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></td>';
	html += '<td><select name="par[]" class="form-control par" ><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></td>';
	html += '<td><select name="plan[]" class="form-control count"><option value="1">Natural</option><option value="2">Induction</option><option value="3">C-Section</option><option value="4">V-Back</option><option value="5">Other</option></select></td>';
	html += '<td><select name="comp[]" class="form-control count"><option value="0">None</option><option value="1">Diabetes</option><option value="2">HBP/Preeclampsia</option><option value="3">Incomplete</option><option value="4">Other</option></select></td>';
	html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">Delete</button /></td>';
	$('#item_table').append(html);
	});
	$(document).on('click', '.remove', function(){
		$(this).closest('tr').remove();
	});
	
});
</script>
