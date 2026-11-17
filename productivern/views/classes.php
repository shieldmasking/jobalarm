<?php
include "../inc/initializer.php";

require_once '.././inc/class.db.php';

require_once '.././inc/config.php';
$new = 1;
?>
<html lang="en">
<body>
<head>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>-->
<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</head>
<style>
body {
    font-size: 16px;
} 
</style>


<!-- BEGIN: Subheader -->
<div class="m-subheader">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Delivery Reports
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
    
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
				
				<div class="col-xl-12">
				
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
									<th width="20%" data-priority="1">Doctor / Practice</th>
                                    <th width="20%" data-priority="2">Last Update</th>
									<th width="20%" data-priority="3">Patients</th>
									<th width="20%" data-priority="4">High Risk</th>
									<th width="20%" data-priority="5">Primips</th>
                                 </tr>
                            </thead>
                        </table>
						</div>
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
					<div class="title"><h5><span id="lastEdit"></span></h5>
					</div>
					<div align="right">
						<button type="button" name="back" id="back" onclick="tj.cancelclass();" class="btn btn-secondary">Back</button>
						<button type="button" name="save" id="save" onclick="tj.saveTable2();" class="btn btn-info">Save</button>
						</div>
                    
					<input id="classId" type="hidden" value="" />
					<div class="table-responsive">
                        <table id="item_table"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
									<th width="20%" data-priority="1">Pt. Identifier</th>
									<th width="20%" data-priority="2">EDD (Required)</th>
									<th width="10%" data-priority="4">Gravida</th>
									<th width="10%" data-priority="5">Para</th>
									<th width="15%" data-priority="6">Birth Plan</th>
									<th width="20%" data-priority="7">History</th>
									<th width="15%" data-priority="3"><button type="button" name="add" id="add" class="btn btn-success btn-sm add">+</button>  <button type="button" name="addModal" id="addModal" onclick="tj.newDelivery()" class="btn btn-info btn-sm add">+</button></th>                                  
									
								</tr>
                           </thead>
                        </table>
						
						
						<div id="inserted_item_data"></div>
					
						
						<div align="center">
						<button type="button" name="back" id="back" onclick="tj.cancelclass();" class="btn btn-secondary">Back</button>
						<button type="button" name="save" id="save" onclick="tj.saveTable2();" class="btn btn-info">Save</button>
						</div>
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
                                <label for="className" class="col-5 col-form-label">Report or Class Name</label>
								<div class="col-8">
                                <input type="text" id="className" >
								</div>
                            </div>
						<div class="form-group">
                                <label for="classDate" class="col-5 col-form-label">
                                   Class Date:
                                </label>
								<div class="col-8">
                               <input type="date" id="classDate" class="form-control" name="classDate" value="" /> 
								</div>
						  </div>
							
							
						<div class="form-group">
                                <label for="classTime" class="col-5 col-form-label">
                                   Class Time:
                                </label>
								<div class="col-8">
                                <input type="time" name="time" id="classTime" class="form-control" />
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

<div class="modal fade" id="addDelivery" tabindex="-1" role="basic" aria-hidden="true">
        <div class="col-sm-4">
		<div class="modal-dialog modal-lg">
            <div class="modal-content">
			
                <div class="modal-header bg-light">
				  <div class="modal-title">Add Delivery Record
				  </div>
				  				  				
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
				
                <div class="modal-body">
				
                    <!--begin::Form-->
                    <form id="add_delivery_form" class="m-form m-form--fit m-form--label-align-right">
                        
						<input id="deliveryclassId" type="hidden" value="" />
						<div class="m-portlet__body">
						<div class="form-group">
                                <label for="deliveryEDD" class="col-7 col-form-label">
                                   EDD:
                                </label>
								<div class="col-10">
                               <input type="date" id="deliveryEDD" class="form-control" name="deliveryEDD" value="" /> 
								</div>
						  </div>
						
						<div class="form-group">
                                <label for="deliveryAge" class="col-7 col-form-label">
                                   Age:
                                </label>
                                <div class="col-10">
                                    <select id="deliveryAge" type="select" class="form-control">
										<option value="0">Select</option>
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
										<option value="31">31</option>
										<option value="32">32</option>
										<option value="33">33</option>
										<option value="34">34</option>
										<option value="35">35</option>
										<option value="36">36</option>
										<option value="37">37</option>
										<option value="38">38</option>
										<option value="39">39</option>
										<option value="40">40</option>
										<option value="41">41</option>
										<option value="42">42</option>
										<option value="43">43</option>
										<option value="44">44</option>
										<option value="45">45</option>
										<option value="46">46</option>
										<option value="47">47</option>
										<option value="48">48</option>
										<option value="49">49</option>
										<option value="50">50</option>
										<option value="51">51</option>
										<option value="52">52</option>
										<option value="53">53</option>
										<option value="54">54</option>
										<option value="55">55</option>
										<option value="56">56</option>
										<option value="57">57</option>
										<option value="58">58</option>
										<option value="59">59</option>
										<option value="60">60+</option>
										</select>
                                </div>
                        </div>
						
						<div class="form-group">
                                <label for="deliveryGrav" class="col-7 col-form-label">
                                   Gravida:
                                </label>
                                <div class="col-10">
                                    <select id="deliveryGrav" type="select" class="form-control">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="10">10+</option>
										</select>
                                </div>
                        </div>
						<div class="form-group">
                                <label for="deliveryPar" class="col-7 col-form-label">
                                   Para:
                                </label>
                                <div class="col-10">
                                    <select id="deliveryPar" type="select" class="form-control">
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
										<option value="10">10+</option>
										</select>
                                </div>
                        </div>
						<div class="form-group">
                                <label for="deliveryPlan" class="col-7 col-form-label">
                                   Delivery Plan:
                                </label>
                                <div class="col-10">
                                    <select id="deliveryPlan" type="select" class="form-control">
										<option value="1">Vag</option>
										<option value="2">Induction</option>
										<option value="3">C-Section</option>
										<option value="4">V-Back</option>
										<option value="5">Other</option>
										</select>
                                </div>
                        </div>
						<div class="form-group">
                                <label for="deliveryComp" class="col-7 col-form-label">
                                   Complication:
                                </label>
                                <div class="col-8">
                                    <select id="deliveryComp" type="select" class="form-control">
										<option value="0"></option>
										<option value="1">Diabetes</option>
										<option value="2">Hypertension / MCD</option>
										<option value="3">Preterm Labor / Demise</option>
										<option value="4">Multiple Gestion</option>
										<option value="5">Previa</option>
										<option value="6">Oligo / Poly</option>
										<option value="7">Ruptured Membrane</option>
										<option value="8">IUGR</option>
										<option value="9">Other</option>
										</select>
                                </div>
                        </div>
																			   
						   </div>
                    </form>
                
                <div class="modal-footer">
				<div class="center">
				<button type="button" class="btn btn-success" onclick="tj.addDelivery('1');">Add Another</button>
				</div>
				     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addDelivery();">Save</button>
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
