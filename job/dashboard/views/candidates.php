<?php
include "../inc/initializer.php";

require_once '../../inc/class.db.php';

require_once '../../inc/class.jatwitter.php';

require_once '../../inc/config.php';
$role = intval($_SESSION['account']['role']);
$accountId = $_SESSION['account']['accountId'];
$status = intval($_SESSION['account']['userstatus']);
$acctType = intval($_SESSION['account']['acctType']);
$smsSend = intval($_SESSION['account']['smsSend']);

?>
<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">
                Candidates
            </h3>
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
                        <div class="col-sm-6"><h4>Search</h4></div>
						<div class="col-sm-6">
                            <div class="pull-right">
							<?php if($role >4): ?>
                                <div class="m-dropdown m-dropdown--inline  m-dropdown--arrow m-dropdown--align-right" data-dropdown-toggle="click">
                                    <a href="#" class="m-dropdown__toggle btn btn-info dropdown-toggle">
                                        Tools
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__item">
                                                            <a href="inc/data.php?req=downloadReport" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-download"></i>
                                                                <span class="m-nav__link-text">
                                                                    Export to CSV
                                                                </span>
                                                            </a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php endif; ?>
                                <!--end: Dropdown-->
                            </div>
                        </div>
					</div>
                    <div class="row"  style="margin-top:15px">
                        <div class="col-xl-12">
                            <!--begin::Form-->
                            <form id="candidate_search_form" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <label class="col-lg-2 col-form-label">
                                                Brand
                                            </label>
                                            <div class="col-lg-4">
											<select id="candidate_search_brand" type="select" class="form-control">
											<option value="0">All</option>
											<?php
											$smsUsers = Config::get('db')->get_results("select s.id, s.storeBrand FROM `sms_brand` s LEFT JOIN `account_brand` as a on a.brandId=s.id where a.accountId='{$accountId}' order by s.id");
											foreach($smsUsers as $user) :
											?>
											<option value=<?php echo $user['id']; ?>><?php echo $user['storeBrand']; ?></option>
											<?php endforeach; ?>
											</select>
                                             </div>
                                            <label class="col-lg-2 col-form-label">
                                                Keywords
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control m-input" id="candidate_search_keyword" placeholder="keyword">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top:15px">
                                            <label class="col-lg-2 col-form-label">
                                                Zip
                                            </label>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control m-input" id="candidate_search_zipcode" placeholder="enter zip">
                                            </div>
                                            <label class="col-lg-2 col-form-label">
                                                Radius
                                            </label>
                                            <div class="col-lg-4">
                                                <select class="form-control m-input" id="candidate_search_zipradius">
                                                    <option value=0>Choose...</option>
                                                    <option value=5>5 miles</option>
                                                    <option value=10>10 miles</option>
                                                    <option value=15>15 miles</option>
                                                    <option value=25>25 miles</option>
                                                    <option value=50>50 miles</option>
                                                </select>
                                            </div>
										</div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <label class="col-lg-2 col-form-label">
                                                Group
                                            </label>
                                            <div class="col-lg-5">
                                                <select class="form-control m-input" id="candidate_search_group">
                                                    <option value="">Select...</option>
                                                    <option value=1>- None -</option>
                                                    <option value=13>Texted</option>
                                                    <option value=14>Interviewed</option>
                                                    <option value=15>Offered</option>
                                                    <option value=16>Rejected</option>
                                                    <option value=17>Hired</option>
													<option value=18>Opted Out</option>
                                                </select>
                                            </div>
										</div>
										<div class="row" style="margin-top:15px">
										<label class="col-lg-3 col-form-label">
                                                Zip Only
                                            </label>
										<div class="col-lg-1" style="margin-top:8px">
                                                <input type="checkbox" id="candidate_search_ziponly" value="1"/>
                                            </div>
										
                                         </div>   
                                    </div>
                                </div>
                                <div class="row" style="margin-top:15px">
                                    <div class="order-xs-2 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <!--begin: Dropdown-->
                                        <div id="candidate_actions" class="m-dropdown m-dropdown--inline  m-dropdown--arrow" data-dropdown-toggle="click">
                                            <a href="#" class="m-dropdown__toggle btn btn-success dropdown-toggle disabled">
                                                Actions
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--left"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
														
                                                            <ul class="m-nav">
															<?php if($smsSend !=0): ?>
                                                                <li class="m-nav__item">
                                                                    <a href="#" data-toggle="modal" data-target="#send_message" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                        <span class="m-nav__link-text">
                                                                            Send Text Message
                                                                        </span>
                                                                    </a>
                                                                </li>
																<?php endif; ?>
                                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                                <li class="m-nav__section m-nav__section--first">
                                                                    <span class="m-nav__section-text">
                                                                        Set Candidate Group
                                                                    </span>
                                                                </li>
                                                                <?php
                                                                $smsGroups = Config::get('db')->get_results("select `id`,`groupName` from `group` order by id asc");
                                                                foreach($smsGroups as $group) :
                                                                    ?>
                                                                <li class="m-nav__item">
                                                                    <a href="javascript:;" class="m-nav__link" onclick="tj.updateCandGroup(<?php echo $group['id'];?>)">
                                                                        <span class="m-nav__link-text">
                                                                            <?php echo $group['groupName']; ?>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Dropdown-->
                                    </div>
                                    <div class="order-xs-1 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="text-align:right">
                                        <button type="reset" onclick="tj.resetCandSearch()" class="btn btn-secondary">
                                            Reset
                                        </button>
                                        <button type="button" onclick="tj.candSearch()" class="btn btn-primary">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" style="margin-top:15px">
                        <table id="candidatesTable"  class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th><th><i class="fa fa-info fa-sm"></i></th>
                                    <th width="">Brand</th>
                                    <th width="">Group</th>
                                    <th width="">First</th>
                                    <th width="">Last</th>
                                    <th width="">Position</th>
                                    <th width="" class="no-select">Mobile</th>
									<th width="">Rec</th>
                                    <th width="">Zip</th>
                                    <th width="">Skills/Resume</th>
									<th width="">Resume</th>
									<th width="">Email</th>
									<th width="">Date</th>
                                 </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
    <div class="modal fade" id="send_message" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Send Text Message</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="send_sms_message">Message</label>
                                <textarea class="form-control" rows="3" maxlength="160" placeholder="" id="send_sms_message"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="assignGroup">Set Group To:</label>
                                <select id="assignGroup" type="select" class="form-control">
                                    <option value=13>Texted</option>
                                    <option value=14>Interviewed</option>
                                    <option value=15>Offered</option>
                                    <option value=16>Rejected</option>
                                    <option value=17>Hired</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.sendCandSMS($('#send_sms_message').val(),$('#assignGroup').val())">Send</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="edit_candidate" tabindex="-1" role="basic" aria-hidden="true">
	<div class="col-md-6 col-sm-6">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
				<h4 class="modal-title">Update Candidate Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                 </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_candidate_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
							<div class="row">
                            <div class="form-group col-sm-5">
                                <label for="ecf_cand_first_name" class="col-6 col-form-label">
                                    First Name
                                </label>
								<div class="col-10">
                                <input class="form-control m-input" type="text" value="" id="ecf_cand_first_name">
								</div>
							</div>
                            <div class="form-group col-sm-7">
                                <label for="ecf_cand_last_name" class="col-6 col-form-label">
                                    Last Name
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_cand_last_name">
                            </div>
							</div>
							</div>
							<div class="row">
                            <div class="form-group col-sm-7">
                                <label for="ecf_cand_email" class="col-6 col-form-label">
                                    Email
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="email" value="" id="ecf_cand_email">
                                </div>
                            </div>
                            <div class="form-group col-sm-5">
                                <label for="ecf_cand_telephone" class="col-6 col-form-label" >
                                    Phone
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="tel" value="" id="ecf_cand_telephone" readonly>
                                </div>
                            </div>
							</div>
							<div class="row">
							<div class="form-group col-sm-6">
                                <label for="ecf_cand_city" class="col-3 col-form-label">
                                    City
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_cand_city" readonly>
                                </div>
                            </div>
							<div class="form-group col-sm-3">
                                <label for="ecf_cand_state" class="col-3 col-form-label">
                                    State
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_cand_state" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="ecf_cand_zip_code" class="col-6 col-form-label">
                                    Zip
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" maxlength="5" type="text" value="" id="ecf_cand_zip_code">
                                </div>
                            </div>
							</div>
							<div class="form-group col-sm-12"> 
                                <label for="ecf_resume_paste" class="col-4 col-form-label">Resume Text:</label>
                                <textarea class="form-control" rows="6" cols="20" id="ecf_resume_paste" name="ecf_resume_paste"></textarea>
                            </div>
							<div class="row">
							<div class="form-group col-sm-6">
                                <label for="ecf_cand_resume" class="col-6 col-form-label">
                                    Resume File:
                                </label>
                                <div class="col-10">
									<span id="ecf_cand_resume"></span>
                                </div>
                            </div>
							</div>
							<button type="button" class="btn btn-success" onclick="tj.forwardProfile(tj.editCandidateTarget);">Forward</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateCandidate(tj.editCandidateTarget);">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
		</div>
        <!-- /.modal-dialog -->
    </div>
    <!--End::Main Portlet-->
    <div class="modal fade" id="add_note" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Note</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="addnote_candidate_id" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="add_note_body">Note</label>
                                <textarea class="form-control" rows="3" placeholder="" id="add_note_body"></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.addNote();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
	<div class="modal fade" id="forward" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Email Profile</h4>
                </div>
                <div class="modal-body">
				<form id="send_candidate_form" class="m-form m-form--fit m-form--label-align-right">
					<input id="forward_candidate_id" type="hidden" value="" />
                        <div class="m-portlet__body">
							<div class="row">
							<div class="form-group col-sm-12"><h5>Sends Candidate profile and attached resume via email.</h5>
							</div>
                            <div class="form-group col-sm-12">
                                <label for="send-to" class="col-10 col-form-label">
                                    Send Email To (Separate with commas): 
									</label>
								<div>
                                <input class="form-control m-input" type="text" value="" id="send-to">
								</div>
							</div>
                            </div>
							<div class="row">
                            <div class="form-group col-sm-12"> 
                                <label for="message" class="col-4 col-form-label">Message:</label>
                                <textarea class="form-control" rows="8" cols="20" id="message" name="message"></textarea>
                            </div>
							</div>
							
                        </div>
                    </form>
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.forwardEmail(tj.editCandidateTarget);">Send</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
	
</div>