<?php

include ".././inc/initializer.php";
require_once '.././inc/class.db.php';
require_once '.././inc/config.php';



?>

	<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                ProductiveRN Setup
            </h3>
        </div>
		
    </div>
</div>
			
<!-- END HEADER -->
<!-- BEGIN PAGE CONTENT -->

	<div class="m-content">
				
				<div class="row">
					<div class="col-md-12">
						 <div class="m-portlet m-portlet--mobile">
						<div class="m-portlet__body">
								<!-- BEGIN FORM-->
								<form action="../app/p2.php" id="apply" name="apply" method="post">
								
								<input type="hidden" name="app" value="1" />
								
								<h5 class="title">Department/Unit Details</h5>
									
																							
									<div class="form-group">
										<div class="input-icon">
										<label for="unit_name" class="col-7 col-form-label">What is your Unit/Department Name?</label>
											
											<input id="unit_name" name="unit_name" type="text" class="form-control" placeholder="ie. NICU, Atepartum, etc." required />
										</div>
									</div>
									<div class="form-group">
										<div class="input-icon">
										<label for="unit_number" class="col-7 col-form-label">What is your Unit/Department Number?</label>
											
											<input id="unit_number" name="unit_number" type="text" class="form-control" placeholder="ie. 625" required />
										</div>
									</div>
									<hr></hr>
									<div class="title">Productivity 
									</div>
									
									<div class="form-group">
									<label for="prod" class="col-7 col-form-label">Is your productivity calculated using HPPD and a daily census?</label>									
									<select id="prod" name="prod" class="form-control" required>
									<option value="1" selected="selected">Yes</option>
									<option value="0">No</option></select>
									</div>
									
									<div class="form-group">
										<div class="input-icon">
										<label for="prod_desc" class="col-7 col-form-label">If No, how is your productivity calculated?</label>
											
											<input id="prod_desc" name="prod_desc" type="textarea" rows="2" class="form-control" />
										</div>
									</div>
									
									<div class="form-group">
										<div class="input-icon">
										<label for="hppd" class="col-7 col-form-label">What are your Hours Per Patient Day (HPPD)?</label>
											
											<input id="hppd" name="hppd" type="number" step=".0001" min=0 class="form-control" placeholder="ie. 9.045" />
										</div>
									</div>
									
									<div class="form-group">
									<label for="census" class="col-7 col-form-label">Does your census take place at midnight?</label>									
									<select id="census" name="census" class="form-control" required>
									<option value="1" selected="selected">Yes</option>
									<option value="0">No</option></select>
									</div>
									
									<div class="form-group">	
									<label for="shifts" class="col-7 col-form-label">Select which times would you like your reports to be submitted?</label>									
									<select multiple="multiple" id="shifts" name="shifts" class="form-control" required>
									<option value="52" selected="selected">12:00 Midnight</option>
									<option value="51">12:30 AM</option>
									<option value="50">1:00 AM</option>
									<option value="49">1:30 AM</option>
									<option value="48">2:00 AM</option>
									<option value="47">2:30 AM</option>
									<option value="46">3:00 AM</option>
									<option value="45">3:30 AM</option>
									<option value="44">4:00 AM</option>
									<option value="43">4:30 AM</option>
									<option value="42">5:00 AM</option>
									<option value="41">5:30 AM</option>
									<option value="40">6:00 AM</option>
									<option value="39">6:30 AM</option>
									<option value="38">7:00 AM</option>
									<option value="37">7:30 AM</option>
									<option value="36">8:00 AM</option>
									<option value="35">8:30 AM</option>
									<option value="34">9:00 AM</option>
									<option value="33">9:30 AM</option>
									<option value="32">10:00 AM</option>
									<option value="31">10:30 AM</option>
									<option value="30">11:00 AM</option>
									<option value="29">11:30 AM</option>
									<option value="28">12:00 Noon</option>
									<option value="27">12:30 PM</option>
									<option value="26">1:00 PM</option>
									<option value="25">1:30 PM</option>
									<option value="24">2:00 PM</option>
									<option value="23">2:30 PM</option>
									<option value="22">3:00 PM</option>
									<option value="21">3:30 PM</option>
									<option value="20">4:00 PM</option>
									<option value="19">4:30 PM</option>
									<option value="18">5:00 PM</option>
									<option value="17">5:30 PM</option>
									<option value="16">6:00 PM</option>
									<option value="15">6:30 PM</option>
									<option value="14">7:00 PM</option>
									<option value="13">7:30 PM</option>
									<option value="12">8:00 PM</option>
									<option value="11">8:30 PM</option>
									<option value="10">9:00 PM</option>
									<option value="9">9:30 PM</option>
									<option value="8">10:00 PM</option>
									<option value="7">10:30 PM</option>
									<option value="6">11:00 PM</option>
									<option value="5">11:30 PM</option></select>
									</div>
									
									<div class="form-group">
										<div class="input-icon">
											
											<input id="contact_email" name="contact_email" type="text" class="form-control" placeholder="Email" required
											/>
										</div>
									</div>
									
									 <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>" />
									 <input type="hidden" id="url" name="url" value="<?php echo $url; ?>" />
									 <input type="hidden" id="brandOrig" name="brandOrig" value="<?php echo $brandOrig; ?>" />
									 <input type="hidden" id="mobile" name="mobile" value="<?php echo $mobile; ?>" />
									 
								  
				
								   <div class="form-group">
								   <input type="submit" value="Next"/>
								   
								   
								<!--<p>By entering my information and clicking "Submit",  I am providing express consent to be contacted by JobAlarm.com via email, phone and text message, regarding job placement, job openings, career services and marketing promotions (if selected above) using automated technology. Standard message and data rates may apply to text messages. You are not required to provide consent to receive services from JobAlarm.com. I also have read and agree to the JobAlarm.com <a href="/terms">Terms of Use.</a></p>
								<p>Text "HELP" to the shortcode from which you're receiving text alerts for help. Text "STOP" to the shortcode from which you're receiving alerts to cancel.</p>
							-->
							</div>
							</form>
						</div>
					</div>
							
						 
						  <!-- END FORM-->
													
						</div>
						</div>
					
				




				<!-- END PAGE CONTENT INNER -->
	
		
	
</div>
<!-- END PAGE CONTENT -->

<!-- BEGIN FOOTER -->


<!-- END FOOTER -->


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59491934-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- END JAVASCRIPTS -->

<!-- END BODY -->
</html>