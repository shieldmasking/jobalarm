var wus = wus || {};

wus.SelectedView = '';

$(function () {
  wus.currentSurveyId = 0;

  wus.SurveySidebarConfig = {
    name: 'SurveySidebar',
    nodes: [
      {id: 'ss_config', text: 'Survey Config', img: 'fa fa-wrench', selected: true},
      {id: 'ss_empref_config', text: 'Emp. Referral.', img: 'fa fa-share'},
      {id: 'ss_details', text: 'Survey Details', img: 'fa fa-bars'},
      {id: 'ss_view_config', text: 'View Config', img: 'fa fa-columns'},
      {id: 'ss_stages_events', text: 'Stages/Events', img: 'fa fa-flash'},
      {id: 'ss_advanced', text: 'Advanced Tools', img: 'fa fa-cogs'}
    ],
    onClick: function (event) {
      switch (event.target) {
        case 'ss_config':
          w2ui.manageLayout.content('main', w2ui.layout_survey_config);
          w2ui.layout_survey_config.content('main', w2ui.SCForm);
          wus.getSurveyConfigFields(wus.currentSurveyId);
          break;
        case 'ss_empref_config':
          w2ui.manageLayout.content('main', w2ui.layout_survey_empref);
          w2ui.layout_survey_empref.content('main', w2ui.ERForm);
          wus.getSurveyEmpRefFields(wus.currentSurveyId);
          break;
        case 'ss_details':
          w2ui.manageLayout.content('main', w2ui.layout_survey_details);
          w2ui.layout_survey_details.content('main', w2ui.SDForm);
          wus.getSurveyDetailsFields(wus.currentSurveyId);
          break;
        case 'ss_view_config':
          w2ui.manageLayout.content('main', w2ui.configureLayout);
          w2ui.configureLayout.content('top', w2ui.viewConfigTabs);
          w2ui.gridviewConfigGrid.load('surveys/getQuestions/' + wus.currentSurveyId);
          w2ui.configureLayout.content('main', w2ui.gridviewConfigGrid);
          wus.SelectedView = 'gridview';
          wus.getGridViewFields(wus.currentSurveyId, 'gridview');
          break;
        case 'ss_stages_events':
          w2ui.SurveyStagesGrid.autoLoad = false;
          w2ui.SurveyEventsGrid.autoLoad = false;
          w2ui.SurveyStagesGrid.url = 'surveys/getStages/' + wus.currentSurveyId;
          w2ui.SurveyEventsGrid.url = 'surveys/getEvents/' + wus.currentSurveyId;

          w2ui.manageLayout.content('main', w2ui.layout_stages_events);
          w2ui.layout_stages_events.content('left', w2ui.SurveyStagesGrid);
          w2ui.layout_stages_events.content('main', w2ui.SurveyEventsGrid);
          break;
        case 'ss_advanced':
          w2ui.manageLayout.content('main', w2ui.advancedConfigureLayout);
          break;
        default:
          break;
      }
    }
  };

  wus.SurveyFormCFG =
          '<table id="survey_config_form" width="100%" border="0" style="border-collapse:collapse"> \
        <tr> \
          <td width="50%"><label for="key">Survey Keyword</label> \
          <input type="text" name="key" id="key" /></td> \
          <td width="50%"><label for="start_key">SMS Survey Start Keyword</label> \
          <input type="text" name="start_key" id="start_key" /></td> \
        </tr> \
        <tr> \
          <td width="50%"><label for="joburl">Job URL</label> \
          <input type="text" name="joburl" id="joburl" /></td> \
          <td width="50%"><label for="defaultpostid">Default Post ID</label> \
          <input type="text" name="defaultpostid" id="defaultpostid" /></td> \
        </tr> \
        <tr> \
          <td rowspan="2" valign="top"><label for="sms_msg">SMS Message</label> \
          <textarea class="taller" name="sms_msg" id="sms_msg" onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount_message\').html(this.value.length);"></textarea><br />Count: <span id="smscharcount_message">0</span></td> \
          <td><label for="sms_start_msg">SMS Survey Start Text</label> \
          <textarea name="sms_start_msg" id="sms_start_msg"  onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount_startmessage\').html(this.value.length);"></textarea><br />Count: <span id="smscharcount_startmessage">0</span></td> \
        </tr> \
        <tr> \
          <td><label for="sms_complete_msg">SMS Survey Finish Text</label> \
          <textarea name="sms_complete_msg" id="sms_complete_msg"  onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount_finishmessage\').html(this.value.length);"></textarea><br />Count: <span id="smscharcount_finishmessage">0</span></td> \
        </tr> \
        <tr class="scftop"> \
          <td valign="bottom"><label for="scf_name_first">First Name</label> \
          <select name="scf_name_first" id="scf_name_first" form="SCForm"> \
          </select> \
          </td> \
          <td valign="bottom"><label for="scf_email">Email</label> \
          <select name="scf_email" id="scf_email" form="SCForm"> \
          </select> \
          </td> \
        </tr> \
        <tr> \
          <td><label for="scf_name_last">Last Name</label> \
          <select name="scf_name_last" id="scf_name_last" form="SCForm"> \
          </select> \
          </td> \
          <td><label for="scf_position">Position</label> \
          <select name="scf_position" id="scf_position" form="SCForm"> \
          </select> \
          </td> \
        </tr> \
        <tr> \
          <td><label for="scf_mobile_num">Mobile Number</label> \
          <select name="scf_mobile_num" id="scf_mobile_num" form="SCForm"> \
          </select> \
          </td> \
          <td><label for="scf_location">Location</label> \
          <select name="scf_location" id="scf_location" form="SCForm"> \
          </select> \
          </td> \
        </tr> \
        <tr> \
          <td><label for="scf_zipcode">Zip Code</label> \
          <select name="scf_zipcode" id="scf_zipcode" form="SCForm"> \
          </select> \
          </td> \
          <td> <label for="scf_upload">File Upload</label> \
          <select name="scf_upload" id="scf_upload" form="SCForm"> \
          </select> \
          </td> \
        </tr> \
        <tr> \
          <td><label for="scf_referral">Referral</label> \
          <select name="scf_referral" id="scf_referral" form="SCForm"> \
          </select> \
          </td> \
          <td><label for="cnd_forwardemail">Forward New Candidates To</label> \
          <input type="text" name="cnd_forwardemail" id="cnd_forwardemail" form="SCForm"> \
          </td> \
        </tr> \
        <tr> \
          <td valign="top"><label for="scf_optin">Opt In/Out</label> \
          <select name="scf_optin" id="scf_optin" form="SCForm"> \
          </select> \
          </td> \
          <td><label for="cnd_forwardemailmsg">Candidate Forward Message (optional)</label> \
          <textarea name="cnd_forwardemailmsg" id="cnd_forwardemailmsg" form="SCForm"></textarea> \
          </td> \
        </tr> \
        <tr> \
          <td valign="top"><label for="scf_defaultadmin">Default Admin</label> \
          <select name="scf_defaultadmin" id="scf_defaultadmin" form="SCForm"> \
          </select> \
          </td> \
          <td> <label for="scf_postid">Post ID</label> \
          <select name="scf_postid" id="scf_postid" form="SCForm"> \
          </select> \
          </td> \
        </tr> \
      </table>';

  wus.SurveyConfigForm = {
    name: 'SCForm',
    msgRefresh: 'Loading Configuration...',
    formHTML: wus.SurveyFormCFG,
    fields: [
      { name: 'key', type: 'alphaNumeric' },
      { name: 'joburl', type: 'alphaNumeric' },
      { name: 'defaultpostid', type:'int'},
      { name: 'sms_msg', type: 'text' },
      { name: 'start_key', type: 'alphaNumeric'},
      { name: 'sms_start_msg', type: 'text'},
      { name: 'sms_complete_msg', type: 'text'},
      { name: 'scf_name_first', type: 'select', showNone: true},
      { name: 'scf_name_last', type: 'select', showNone: true},
      { name: 'scf_mobile_num', type: 'select', showNone: true},
      { name: 'scf_zipcode', type: 'select', showNone: true},
      { name: 'scf_email', type: 'select', showNone: true},
      { name: 'scf_position', type: 'select', showNone: true},
      { name: 'scf_location', type: 'select', showNone: true},
      { name: 'scf_upload', type: 'select', showNone: true},
      { name: 'scf_referral', type: 'select', showNone: true },
      { name: 'scf_optin', type: 'select', showNone: true },
      { name: 'scf_defaultadmin', type: 'select', showNone: true },
      { name: 'scf_postid', type: 'select', showNone: true },
      { name: 'cnd_forwardemail', type: 'text' },
      { name: 'cnd_forwardemailmsg', type: 'text'}
    ],
    actions: {
      reset: function () {
        this.clear();
      },
      save: function () {
        var obj = this;

        this.save({}, function (data) {
          if (data.status == 'error')
            return;

          obj.clear();
        });
      }
    }
  };

  $().w2form(wus.SurveyConfigForm);

  wus.SurveyDetailsFormCFG =
          '<table id="survey_config_form" width="100%" border="0" style="border-collapse:collapse"> \
        <tr> \
            <td width="50%"><label for="surveyName">Survey Name</label> \
            <input type="text" name="surveyName" id="key" /></td> \
            <td width="50%"></td> \
        </tr> \
        <tr> \
            <td rowspan="2" valign="top"><label for="surveyDescription">Description</label> \
            <textarea class="taller" name="surveyDescription" id="sms_msg"></textarea></td> \
            <td></td> \
        </tr></table>';

  wus.SurveyDetailsForm = {
    name: 'SDForm',
    msgRefresh: 'Loading Survey Details...',
    formHTML: wus.SurveyDetailsFormCFG,
    fields: [
      {name: 'surveyName', type: 'text'},
      {name: 'surveyDescription', type: 'text'}
    ],
    actions: {
      reset: function () {
        this.clear();
      },
      save: function () {
        var obj = this;

        this.save({}, function (data) {
          if (data.status == 'error')
            return;

          obj.clear();
        });
      }
    }
  };

  $().w2form(wus.SurveyDetailsForm);


  wus.EmpRefGrid = {
    name: 'emp_ref_grid',
    columns: [
      {field: 'refDate', caption: 'Ref Date', size: '33%', sortable: true, searchable: true},
      {field: 'fullName', caption: 'Full Name', size: '33%', sortable: true, searchable: true},
      {field: 'mobileNum', caption: 'Mobile Number', size: '33%'}
    ],
    records: []
  };

  $().w2grid(wus.EmpRefGrid);

  wus.showEmpRefs = function (surveyId, surveyName) {
    w2popup.open({
      title: 'Employee Referrals for ' + surveyName,
      width: 500,
      height: 500,
      showMax: false,
      body: '<div id="main" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
      onOpen: function (event) {
        event.onComplete = function () {
          $('#w2ui-popup #main').w2render('emp_ref_grid');
          w2ui.emp_ref_grid.load('surveys/getEmpRefs/' + surveyId);
        };
      },
      onToggle: function (event) {
        event.onComplete = function () {
          w2ui.layout.resize();
        }
      }
    });
  }

  wus.SurveyEmpRefFormCFG =
          '<table id="survey_config_form" width="100%" border="0" style="border-collapse:collapse"> \
        <tr> \
            <td width="50%"><label for="refKeyword">Referral Keyword</label> \
            <input type="text" name="refKeyword" id="refKeyword" /></td> \
            <td width="50%"></td> \
        </tr> \
        <tr> \
            <td width="50%"><label for="companyName">Company Name</label> \
            <input type="text" name="companyName" id="companyName" /></td> \
            <td width="50%"></td> \
        </tr> \
        <tr> \
            <td width="50%"><label for="surveyLink">Survey Link</label> \
            <input type="text" name="surveyLink" id="surveyLink" /></td> \
            <td width="50%"></td> \
        </tr> \
        <tr>\
            <td colspan="2"><label><br />Keyword Tags Available In Message: [company], [mobilenum], [surveylink]</label></td>\
        </tr>\
        <tr> \
            <td rowspan="2" valign="top"><label for="messageA">Message 1</label> \
            <textarea class="taller" name="messageA" id="messageA" onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount_messageA\').html(this.value.length);"></textarea><br />Count: <span id="smscharcount_messageA">0</span></td> \
            <td></td> \
        </tr> \
        <tr> \
            <td rowspan="2" valign="top"><label for="messageB">Message 2</label> \
            <textarea class="taller" name="messageB" id="messageB"  onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount_messageB\').html(this.value.length);"></textarea><br />Count: <span id="smscharcount_messageB">0</span></td> \
            <td></td> \
        </tr> \
        <tr><td></td><td></td></tr>\
        <tr> \
            <td width="50%" valign="top"><label for="emprefEmailFwd">Forward To Email</label> \
            <input type="text" name="emprefEmailFwd" id="emprefEmailFwd" /></td> \
            <td width="50%" valign="top"><label for="emprefEmailFwdMsg">Forward Email Message (optional)</label> \
            <textarea name="emprefEmailFwdMsg" id="emprefEmailFwdMsg"></textarea></td> \
        </tr> \
        </table>';

  wus.SurveyEmpRefForm = {
    name: 'ERForm',
    msgRefresh: 'Loading Survey Employee Referral Config...',
    formHTML: wus.SurveyEmpRefFormCFG,
    fields: [
      {name: 'refKeyword', type: 'text'},
      {name: 'surveyLink', type: 'text'},
      {name: 'companyName', type: 'text'},
      {name: 'messageA', type: 'text'},
      {name: 'messageB', type: 'text'},
      {name: 'emprefEmailFwd', type: 'text'},
      {name: 'emprefEmailFwdMsg', type: 'text'}
    ],
    actions: {
      reset: function () {
        this.clear();
      },
      save: function () {
        var obj = this;

        this.save({}, function (data) {
          if (data.status == 'error')
            return;

          obj.clear();
        });
      }
    }
  };

  $().w2form(wus.SurveyEmpRefForm);

  wus.addSurveyStage = function (stagename, surveyId) {
    w2popup.lock('Adding...', true);
    $.ajax({
      url: 'surveys/addStage/' + surveyId,
      method: 'POST',
      data: {
        stageName: stagename
      },
      success: function (text) {
        w2popup.message('');
        w2ui.SurveyStagesGrid.reload();
      }
    });
  };

  wus.openAddSurveyStageDialog = function (surveyId) {
    w2popup.message({
      width: 400,
      height: 50,
      hideOnClick: false,
      html: '<div style="padding:10px" vertical-align="middle"><center>Stage Name: <input type="text" id="SurveyStageName" /><button onclick="wus.addSurveyStage($(\'#SurveyStageName\').val(),' + surveyId + ');">Add</button> <button onclick="w2popup.message(\'\');">Cancel</button></center></div>'
    });
  };

  wus.addSurveyEvent = function (eventname, surveyId) {
    w2popup.lock('Adding...', true);
    $.ajax({
      url: 'surveys/addEvent/' + surveyId,
      method: 'POST',
      data: {
        eventName: eventname
      },
      success: function (text) {
        w2popup.message('');
        w2ui.SurveyEventsGrid.reload();
      }
    });
  };

  wus.openAddSurveyEventDialog = function (surveyId) {
    w2popup.message({
      width: 400,
      height: 50,
      hideOnClick: false,
      html: '<div style="padding:15px"><center>Event Name: <input type="text" id="SurveyEventName" /><button onclick="wus.addSurveyEvent($(\'#SurveyEventName\').val(),' + surveyId + ');">Add</button> <button onclick="w2popup.message(\'\');">Cancel</button></center></div>'
    });
  };

  wus.SurveyStagesGrid = {
    name: 'SurveyStagesGrid',
    multiSelect: false,
    url: 'surveys/getStages',
    autoLoad: true,
    msgRefresh: 'Loading Stages...',
    show: {
      header: true,
      toolbar: true,
      toolbarAdd: true,
      toolbarDelete: true,
      toolbarSave: true,
      toolbarSearch: false,
      toolbarColumns: false
    },
    //buttons:'<button>test</button>',
    header: 'Survey Stages',
    columns: [
      //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
      {field: 'name', caption: 'Name', size: '100%', sortable: false, editable: {type: 'text', inTag: 'maxlength=40'}}
    ],
    onAdd: function (event) {
      wus.openAddSurveyStageDialog(wus.currentSurveyId);
    },
    onClick: function (event) {
    },
    records: []
  };

  wus.SurveyEventsGrid = {
    name: 'SurveyEventsGrid',
    multiSelect: false,
    url: 'surveys/getEvents',
    autoLoad: true,
    msgRefresh: 'Loading Events...',
    show: {
      header: true,
      toolbar: true,
      toolbarAdd: true,
      toolbarDelete: true,
      toolbarSave: true,
      toolbarSearch: false,
      toolbarColumns: false
    },
    header: 'Survey Events',
    columns: [
      //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
      {field: 'name', caption: 'Name', size: '100%', sortable: false, editable: {type: 'text', inTag: 'maxlength=40'}}
    ],
    onAdd: function (event) {
      wus.openAddSurveyEventDialog(wus.currentSurveyId);
    },
    records: []
  };
  $().w2grid(wus.SurveyStagesGrid);
  $().w2grid(wus.SurveyEventsGrid);

  wus.manageLayoutConfig = {
    name: 'manageLayout',
    padding: 0,
    panels: [
      {type: 'left', minSize: 150, size: 200},
      {type: 'main'}
    ]
  };

  $().w2layout({
    name: 'configureLayout',
    padding: 0,
    panels: [
      {type: 'top', size: 30},
      {type: 'main'},
      {type: 'right', size: '50%', id: 'configureright', content: '<div class="sortHeader">&nbsp;Field Configuration</div><div id="configureListView"><ul id="sortable" class="connectedSortable ui-helper-reset"></ul</div>'},
      {
        type: 'bottom', size: 30, toolbar: {
          items: [
            {type: 'spacer'},
            {type: 'button', id: 'saveviews', caption: 'Save', img: 'icon-save', hint: ''}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'saveviews':
                wus.saveSurveyInformation(wus.SelectedView);
                break;
              default:
                break;
            }
          }
        }
      }
    ]

  });

  $().w2layout({
    name: 'layout_survey_config',
    padding: 0,
    panels: [
      {
        type: 'main', style: 'border: none',
        toolbar: {
          items: [
            {type: 'spacer'},
            {type: 'button', id: 'save', caption: 'Save', img: 'icon-save', hint: 'Save Survey Config Form'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'save':
                w2ui.layout_survey_config.lock('main', 'Saving Survey Config...', true);
                $.ajax({
                  url: 'surveys/setConfigFields/' + wus.currentSurveyId,
                  method: 'post',
                  dataType: 'json',
                  data: {record: w2ui.SCForm.record},
                  success: function (data) {
                    w2ui.layout_survey_config.unlock('main');
                    w2alert('Survey Config Saved Successfully.');
                  }
                });
                break;
              default:
                break;
            }
          }
        }
      }
    ]
  });

  $().w2layout({
    name: 'layout_stages_events',
    padding: 0,
    panels: [
      {type: 'left', size: '50%', style: 'border: none'},
      {type: 'main', size: '50%', style: 'border: none'}
    ]
  });

  $().w2tabs({
    name: 'viewConfigTabs',
    active: 'tab0',
    tabs: [
      {id: 'tab0', caption: 'Grid View'},
      {id: 'tab1', caption: 'Survey View'},
      {id: 'tab2', caption: 'Survey Filters'},
      {id: 'tab3', caption: 'SMS Survey'}
    ],
    onClick: function (event) {
      switch (event.target) {
        case 'tab0':
          wus.SelectedView = 'gridview';
          wus.getGridViewFields(wus.currentSurveyId, 'gridview');
          break;
        case 'tab1':
          wus.SelectedView = 'surveyview';
          wus.getGridViewFields(wus.currentSurveyId, 'surveyview');
          break;
        case 'tab2':
          wus.SelectedView = 'surveyfilters';
          wus.getGridViewFields(wus.currentSurveyId, 'surveyfilters');
          break;
        case 'tab3':
          wus.SelectedView = 'smssurvey';
          wus.getGridViewFields(wus.currentSurveyId, 'smssurvey');
          break;
        default:
          break;
      }
    }
  });

  wus.removeListItem = function (item) {
    $(item).parent().hide(500, function () {
      $(this).remove();
      $("#sortable").sortable('refresh');
    });
  };

  $().w2layout({
    name: 'layout_survey_details',
    panels: [
      {
        type: 'main', style: 'border: none',
        toolbar: {
          items: [
            {type: 'spacer'},
            {type: 'button', id: 'save', caption: 'Save', img: 'icon-save', hint: 'Save Survey Details Form'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'save':
                //w2ui.SDForm.save();
                w2ui.layout_survey_details.lock('main', 'Saving Details Config...', true);
                $.ajax({
                  url: 'surveys/setDetailsFields/' + wus.currentSurveyId,
                  method: 'post',
                  dataType: 'json',
                  data: {record: w2ui.SDForm.record},
                  success: function (data) {
                    w2ui.layout_survey_details.unlock('main');
                    w2alert('Survey Details Saved Successfully.');
                  }
                });
                break;
              default:
                break;
            }
          }
        }
      }
    ]
  });

  $().w2layout({
    name: 'layout_survey_empref',
    panels: [
      {
        type: 'main', style: 'border: none',
        toolbar: {
          items: [
            {type: 'spacer'},
            {type: 'button', id: 'save', caption: 'Save', img: 'icon-save', hint: 'Save Survey Employee Referral Form'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'save':
                //w2ui.SDForm.save();
                w2ui.layout_survey_empref.lock('main', 'Saving EmpRef Config...', true);
                $.ajax({
                  url: 'surveys/setEmpRefFields/' + wus.currentSurveyId,
                  method: 'post',
                  dataType: 'json',
                  data: {record: w2ui.ERForm.record},
                  success: function (data) {
                    w2ui.layout_survey_empref.unlock('main');
                    w2alert('Survey Employee Referral Config Saved Successfully.');
                  }
                });
                break;
              default:
                break;
            }
          }
        }
      }
    ]
  });

  wus.saveSurveyInformation = function (selectedview) {
    var data = new Array();

    $("#sortable li").each(function (i, el) {
      //console.log(i);
      //console.log(el);
      data.push({
        id: $(el).index(),
        name: $(el).find('input[name=columnname]').val(),
        text: $(el).find('input[name=columntext]').val(),
        sysid: $(el).find('input[name=sysid]').val()
      });
    });

    //console.log(data);
    //w2popup.message({
    //    html: '<div style="padding: 20px; text-align: center">Saving Survey</div>',
    //    width: 300,
    //    height: 60,
    //    hideOnClick: true
    //});
    w2ui.configureLayout.lock('right', 'Saving View Config...', true);
    $.ajax({
      url: 'surveys/save/' + wus.currentSurveyId + '/' + selectedview,
      type: 'POST',
      data: {
        gridViewConfig: JSON.stringify(data)
      },
      success: function (data) {
        w2ui.configureLayout.unlock('right');
        w2alert('Configuration Saved Successfully');
      }
    });

  };


  wus.getSurveyConfigFields = function (surveyid) {
    $.ajax({
      url: 'surveys/getConfigFields/' + surveyid,
      method: 'post',
      dataType: 'json',
      success: function (data) {
        // console.log(data);
        w2ui.SCForm.record = data.record;
        w2ui.SCForm.refresh();
        w2ui.layout_survey_config.lock('main', 'Loading Static Fields...', true);
        wus.getSurveyConfigOptions(surveyid, data);
        wus.getSurveyDefaultAdmin(data);
      }
    })
    //w2ui.SCForm.url = 'surveys/getSurveyConfigFields/' + surveyid;
    //w2ui.SCForm.request({}, function (data) {
    //    w2ui.layout_survey_config.lock('main', 'Loading Static Fields...', true);
    //    wus.getSurveyConfigOptions(surveyid, data);
    //});
  };

  wus.getSurveyDefaultAdmin = function (formdata) {
      $.ajax({
          url: 'users/getAll',
          dataType: 'json',
          success: function (data) {
              $('#scf_defaultadmin').append($('<option value="0">Choose...</option>'));
              $.each(data.records, function (k, user) {
                  var selected = (formdata.record['scf_defaultadmin'] == user['recid']) ? ' selected="selected"' : '';
                  $('#scf_defaultadmin').append($('<option value="' + user['recid'] + '"'+ selected +'>' + user['firstname'] + ' ' + user['lastname'] + '</option>'));
              });
          }
      });
  }
  wus.getSurveyConfigOptions = function (surveyid, formdata) {
    $.ajax({
      url: 'surveys/getConfigOptions/' + surveyid,
      type: 'POST',
      dataType: 'json',
      success: function (data) {

        //populate the select boxes

        var selects = ['scf_name_first', 'scf_name_last', 'scf_mobile_num', 'scf_zipcode', 'scf_email', 'scf_position', 'scf_location', 'scf_upload', 'scf_referral', 'scf_optin', 'scf_postid'];

        $(selects).each(function (select, obj) {
          $('#' + obj).append($('<option value="-1">Choose...</option>'));
          $.each(data.items, function (k, item) {
            var selected = (formdata.record[obj] == item.id) ? ' selected="selected"' : '';
            $('#' + obj).append($('<option value="' + item.id + '"' + selected + '>' + item.text + '</option>'));
          });

        });
        w2ui.layout_survey_config.unlock('main');
      }
    });

  }

  wus.getSurveyEmpRefFields = function (surveyid) {
    //w2ui.SDForm.url = 'surveys/getSurveyDetailsFields/' + surveyid;
    //w2ui.SDForm.request({ method: 'post' });
    w2ui.manageLayout.lock('main', 'Loading EmpRef Fields...', true);
    $.ajax({
      url: 'surveys/getEmpRefFields/' + surveyid,
      method: 'post',
      dataType: 'json',
      success: function (data) {
        w2ui.ERForm.record = data.record;
        w2ui.ERForm.refresh();
        w2ui.manageLayout.unlock('main');
      }

    })
  };


  wus.getSurveyDetailsFields = function (surveyid) {
    //w2ui.SDForm.url = 'surveys/getSurveyDetailsFields/' + surveyid;
    //w2ui.SDForm.request({ method: 'post' });
    $.ajax({
      url: 'surveys/getDetailsFields/' + surveyid,
      method: 'post',
      dataType: 'json',
      success: function (data) {
        w2ui.SDForm.record = data.record;
        w2ui.SDForm.refresh();
      }

    })
  };


  wus.getGridViewFields = function (surveyid, which) {
    w2ui.configureLayout.lock('right', 'Loading View config...', true);
    var viewurl = '';
    switch (which) {
      case 'gridview':
        viewurl = 'surveys/getGridViewColumns/' + surveyid;
        break;
      case 'surveyview':
        viewurl = 'surveys/getSurveyViewColumns/' + surveyid;
        break;
      case 'surveyfilters':
        viewurl = 'surveys/getSurveyFiltersColumns/' + surveyid;
        break;
      case 'smssurvey':
        viewurl = 'surveys/getSMSSurveyColumns/' + surveyid;
        break;
    }
    ;
    $.ajax({
      url: viewurl,
      type: 'POST',
      success: function (data) {
        //console.log(data);
        $('#sortable').empty();
        if (data) {
          var field_data = JSON.parse(data);

          $.each(field_data.records, function (v, d) {
            $('#sortable').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div>' + d.name + '</div><input name="columntext" type="text" value="' + d.displayname + '" /><input name="sysid" type="hidden" value="' + d.id + '" /><input name="columnname" type="hidden" value="' + d.name + '" /><a style="float:right;display:block" href="javascript:;" onclick="wus.removeListItem(this);"><i class="fa fa-trash-o"></i> </a></li>');
          });
        }
        $('#sortable').sortable({
          placeholder: "ui-state-highlight"
        });
        $("#sortable").sortable('refresh');

        w2ui.configureLayout.unlock('right');
      }
    });
  };

  wus.gridviewConfigGrid = {
    name: 'gridviewConfigGrid',
    //url: 'surveys/getQuestions',
    multiSelect: false,
    columns: [
      {field: 'name', caption: 'Field Name', size: '100%', sortable: false}
    ],
    onClick: function (event) {
      var grid = this;
      setTimeout(function () {
        var record = grid.get(event.recid);
        $('#sortable').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div>' + record.name + '</div><input name="columntext" type="text" value="" /><input name="sysid" type="hidden" value="' + record.sysid + '" /><input name="columnname" type="hidden" value="' + record.name + '" /><a style="float:right;display:block" href="javascript:;" onclick="wus.removeListItem(this);"><i class="fa fa-trash-o"></i> </a></li>');
        //$('#sortable').append('<li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div>' + record.name + '</div><input name="item' + record.recid + '" type="text" value="" /><input name="sysid' + record.recid + '" type="hidden" value="' + record.sysid + '" /><a style="float:right;display:block" href="javascript:;" onclick="wus.removeListItem(this);"><i class="fa fa-trash-o"></i> </a></li>');
        $("#sortable").sortable('refresh');
        //var wtf = $('#layout_configureLayout_panel_right');
        var $t = $('#configureListView').parent();
        $t.animate({"scrollTop": $('#configureListView').parent()[0].scrollHeight}, "slow");
        grid.selectNone();
      }, 150);
    }
  };

  $().w2grid(wus.gridviewConfigGrid);
  $().w2layout(wus.manageLayoutConfig);

  wus.openSurveyManager = function (surveyId, surveyName) {
    wus.currentSurveyId = surveyId;
    $().w2popup({
      title: 'Manage Survey ' + surveyName,
      width: 900,
      height: 600,
      showMax: false,
      body: '<div id="surveyConfigPopupMain" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;margin-bottom:10px;"></div>',
      //            buttons: '<button onclick="wus.saveSurveyInformation();">Save</button> <button onclick="w2popup.close();">Cancel</button>',
      onOpen: function (event) {
        event.onComplete = function () {
          $('#surveyConfigPopupMain').w2render('manageLayout');
          //w2ui.manageLayout.content('main', w2ui.configureLayout);
          //w2ui.configureLayout.content('top', w2ui.viewConfigTabs);
          //w2ui.gridviewConfigGrid.url = 'surveys/getQuestions/' + wus.currentSurveyId;
          //w2ui.configureLayout.content('main', w2ui.gridviewConfigGrid);
          //$('#sortable').sortable({
          //    placeholder: "ui-state-highlight"
          //});
          //wus.getGridViewFields(wus.currentSurveyId);
          w2ui.manageLayout.content('left', w2ui.SurveySidebar);
          w2ui.manageLayout.content('main', w2ui.layout_survey_config);
          w2ui.layout_survey_config.content('main', w2ui.SCForm);
          wus.getSurveyConfigFields(surveyId);
          //w2ui.manageLayout.content('main', w2ui.layout_stages_events);
        };
      },
      onClose: function (event) {
        event.onComplete = function () {
          w2ui.SurveySidebar.select('ss_config');
          w2ui.systemsurveygrid.reload();
        }
      }
    });
  };
  $().w2sidebar(wus.SurveySidebarConfig);

  wus.systemsurveygrid = {
    name: 'systemsurveygrid',
    url: 'surveys/getAll/1',
    multiSelect: false,
    show: {
      header: true
    },
    header: 'System Surveys',
    columns: [
      //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
      {field: 'sname', caption: 'Survey Name', size: '100%', sortable: false},
      {field: 'updated', caption: 'Last Update', size: '150px', sortable: false},
      {field: 'responses', caption: 'Responses', size: '150px', sortable: false},
      {field: 'admin', caption: 'Actions', size: '200px', sortable: false}
    ],
    records: []
  };

  wus.verifyResponseData = function (surveyId) {
    $('#adv_action').show();
    $('#advanced_console_view').append('Verifying Response Data for Survey Id: ' + surveyId + '...\r\n');
    $.ajax({
      url: 'responses/beginVerify/' + surveyId,
      method: 'post',
      dataType: 'json',
      success: function (data) {
        $('#advanced_console_view').append('Found: ' + data.responseCount + ' Live Completed Responses.\r\n');
        $('#advanced_console_view').append('Comparing Live Responses to Local Database...\r\n');
        $.ajax({
          url: 'responses/compareVerify/' + surveyId,
          method: 'post',
          dataType: 'json',
          success: function (data) {
            $('#advanced_console_view').append('Found: ' + data.missingLocal + ' Live Responses Missing From Local Database.\r\n');
            $('#adv_action').hide();
          }
        })
      }
    });
  }

  wus.clearAdvancedOutput = function () {

    $('#advanced_console_view').text('');

  }

  $().w2layout({
    name: 'advancedConfigureLayout',
    padding: 0,
    panels: [
      {type: 'top', size: 30, content: '<h3 style="margin:5px">Advanced Survey Tools</h3>'},
      {
        type: 'main',
        toolbar: {
          items: [
            {type: 'button', id: 'verify', caption: 'Verify Response Data', img: 'fa fa-check fa-lg', hint: 'Verify Response Data'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'verify':
                wus.verifyResponseData(wus.currentSurveyId);
                break;
              default:
                break;
            }
          }
        },
        content: '<br />Debug Output <span id="adv_action" style="display:none"> <img src="img/ajax-loader.gif" /></span><br /><textarea id="advanced_console_view" style="margin:0 3%;margin-top:2%;width:94%;height:76%"></textarea><br /><button onclick="wus.clearAdvancedOutput()">Clear Buffer</button>'
      }
    ]

  });

});