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
                SMS Inbox
            </h3>
        </div>
    </div>
</div>
<!-- END: Subheader -->
<div class="m-content">
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Messages
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab1_content" role="tab" aria-expanded="true">
                                    Inbox
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab" aria-expanded="false">
                                    Sent
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab3_content1" role="tab" aria-expanded="false">
                                    Trash
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="order-xs-2 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <!--begin: Dropdown-->
                            <div id="smsinbox_actions" class="m-dropdown m-dropdown--inline  m-dropdown--arrow" data-dropdown-toggle="click">
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
                                                        <a href="#" data-toggle="modal" data-target="#send_message_sms" class="m-nav__link">
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
                                                            <a href="javascript:;" class="m-nav__link" onclick="tj.updateSMSGroup(<?php echo $group['id'];?>)">
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
                                        <button type="reset" onclick="tj.resetSMS()" class="btn btn-secondary">
                                            Refresh
                                        </button>
                                        
                                    </div>
                    </div>
                    <div class="row">
                        <table id="smsTable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th data-priority="1"></th>
                                    <th width="10%" data-priority="1">Received</th>
                                    <th width="10%" data-priority="2">First</th>
                                    <th width="10%" data-priority="2">Last</th>
                                    <th width="10%" data-priority="1">Group</th>
                                    <th width="10%" data-priority="1">Mobile</th>
                                    <th width="40%" data-priority="1">Message</th>
                                    <th width="10%" data-priority="3">Recruiter</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
    <!--End::Main Portlet-->
    <div class="modal fade" id="send_message_sms" tabindex="-1" role="basic" aria-hidden="true">
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
                                <textarea class="form-control" rows="3" placeholder="" id="send_sms_message_sms"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="form_control_2">Set Group To:</label>
                                <select id="assign_group_sms" type="select" class="form-control">
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
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn green" onclick="tj.sendSMS($('#send_sms_message_sms').val(),$('#assign_group_sms').val())">Send</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>