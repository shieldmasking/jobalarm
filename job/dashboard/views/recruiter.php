<?php
include "../inc/initializer.php";

require_once '../../inc/class.db.php';

require_once '../../inc/class.jatwitter.php';

require_once '../../inc/config.php';

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
                        <div class="col-sm-6"><h4>Mobile Recruiter</h4></div>
						<div class="col-sm-6">
                            
                        </div>
					</div>
                    <div class="row"  style="margin-top:15px">
                        <div class="col-xl-12">
                            <!--begin::Form-->
                            <form id="recruiter_search_form" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                                <div class="row" style="margin-top:15px">
                                    <div class="order-xs-2 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <!--begin: Dropdown-->
                                        <div id="recruiter_actions" class="m-dropdown m-dropdown--inline  m-dropdown--arrow" data-dropdown-toggle="click">
                                            <a href="#" class="m-dropdown__toggle btn btn-success dropdown-toggle disabled">
                                                Actions
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--left"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
                                                            <ul class="m-nav">
                                                                <li class="m-nav__item">
                                                                    <a href="#" data-toggle="modal" data-target="#recruitersend_message" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                        <span class="m-nav__link-text">
                                                                            Send Text Message
                                                                        </span>
                                                                    </a>
                                                                </li>
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
                                                                    <a href="javascript:;" class="m-nav__link" onclick="tj.updateRecGroup(<?php echo $group['id'];?>)">
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
                                  </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" style="margin-top:15px">
                        <table id="recruiterTable"  class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th><th><i class="fa fa-info fa-sm"></i></th>
                                    <th width="">Info</th>
                                    <th width="">Location</th>
                                    <th width="">Mobile</th>
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
    <div class="modal fade" id="recruitersend_message" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Send Text Message</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="form_control_1">Message</label>
                                <textarea class="form-control" rows="3" placeholder="" id="recruitersend_sms_message"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="form_control_2">Set Group To:</label>
                                <select id="recruiterassignGroup" type="select" class="form-control">
                                    <option value="">Select..</option>
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
                    <button type="button" class="btn btn-primary" onclick="tj.sendRecSMS($('#recruitersend_sms_message').val(),$('#recruiterassignGroup').val())">Send</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="edit_recruiter" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Candidate Details</h4>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="edit_recruiter_form" class="m-form m-form--fit m-form--label-align-right">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group m--margin-top-10 row">
                                <label for="example-text-input" class="col-2 col-form-label">
                                    First Name
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_rec_first_name">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-search-input" class="col-2 col-form-label">
                                    Last Name
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_rec_last_name">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-email-input" class="col-2 col-form-label">
                                    Email
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="email" value="" id="ecf_rec_email">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-tel-input" class="col-2 col-form-label" disabled>
                                    Telephone
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="tel" value="" id="ecf_rec_telephone" readonly>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label for="example-search-input" class="col-2 col-form-label">
                                    Zip Code
                                </label>
                                <div class="col-10">
                                    <input class="form-control m-input" type="text" value="" id="ecf_rec_zip_code">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.updateRecruiter(tj.editRecruiterTarget);">Update</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--End::Main Portlet-->
    <div class="modal fade" id="recruiteradd_note" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Note</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <input id="addnote_recruiter_id" type="hidden" value="" />
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label for="form_control_1">Note</label>
                                <textarea class="form-control" rows="3" placeholder="" id="recruiteradd_note_body"></textarea>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="tj.recruiteraddNote();">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>