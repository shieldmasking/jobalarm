var wus = wus || {};

wus.onSelectSMSResponse = function (responseId, smsid) {
  //if (responseId) {
    //if (w2ui.grid.getSelection().length > 0) {
    //  w2ui.layout.lock('right', 'Loading...', true);
    //  w2ui.layout.load('right', 'responses/getForm/' + responseId, 'slide-right', function () {
    //    w2ui.layout.unlock('right');
    //  });

      wus.SelectedResponse = responseId;

      $.ajax({
        url: 'sms/markViewed/' + smsid,
        method: 'POST'
      });
      wus.SelectedPersonSMSURL = 'sms/getAllResponse/' + wus.SelectedResponse
      wus.SelectedPersonNotesURL = 'notes/getResponseNotes/' + wus.SelectedResponse;

      if (w2ui.detailslayout) {
        w2ui.detailssmsgrid.load(wus.SelectedPersonSMSURL);
        w2ui.detailsnotesgrid.load(wus.SelectedPersonNotesURL);
        var gridData = w2ui.grid.get(w2ui.grid.getSelection());

        var fullname = gridData['firstname'] + ' ' + gridData['lastname'];

        if (fullname.length > 1)
          $('#candidate_fullname').html(toTitleCase(fullname));
        else
          $('#candidate_fullname').html(" ");
        
        if (gridData['referral']) {
          $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
        } else {
          $('#candidate_referredby').html('Referred By: ');
        }
        
        if (gridData['group'])
          $('#candidate_current_event').html('Group/Stage: ' + toTitleCase(gridData['group']));
        else
          $('#candidate_current_event').html('Group/Stage: ');

        if (gridData['stage'])
          $('#candidate_current_stage').html('Stage: ' + toTitleCase(gridData['stage']));
        else
          $('#candidate_current_stage').html('Stage: ');

      }
      //w2ui.layout.panels[2].toolbar.enable('saveresponsebtn');
      //w2ui.layout.panels[2].toolbar.enable('sendmail_button');
    //}
  //}
};

$(function () {
  var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
  var filtering = '<div id="filterBox" class="filterBox"><form id="filterForm" name="filterForm" action="" method="GET">' +
          //'<label>Keyword</label><input type="text" id="filter_keyword" name="filter_keyword" value="" /><br />' +
          //'<label>Opted In/Out</label><input type="radio" name="filter_optout" id="filter_optout" value="0" checked /> All <input type="radio" name="filter_optout" id="filter_optout" value="1" /> In <input type="radio" name="filter_optout" id="filter_optout" value="2" /> Out<br />' +
          '<label>Group/Stage</label><select multiple id="filter_stageid" name="filter_stageid"><option value="-1"></option></select><br />' +
          //'<label>Job / Event</label><select multiple id="filter_eventid" name="filter_eventid"><option value="-1"></option></select><br />' +
          '<label>Zip Code</label><input style="width:45px" type="text" id="filter_zipCode" name="filter_zipCode" value="" /> - <select style="width:85px;height:23px;" id="filter_zipdist" name="filter_zipdist"><option value="0">Distance</option><option value="5">5 Miles</option><option value="10">10 Miles</option><option value="15">15 Miles</option><option value="30">30 Miles</option><option value="50">50 Miles</option><option value="100">100 Miles</option></select><br />' +
          '</form><hr /><hr /><center><button onclick="wus.doSearch();">Search</button> <button onclick="$(\'#filterForm\')[0].reset();wus.doSearch();">Reset</button></center></div>';
  $().w2layout({
    name: 'searchlayout',
    panels: [
      {type: 'main', content: filtering},
      //{type: 'bottom', size: 45, content: '<hr /><center><button onclick="wus.doSearch();">Search</button> <button onclick="$(\'#filterForm\')[0].reset();wus.doSearch();">Reset</button></center>'}
    ]
  });

  $('#layout').w2layout({
    name: 'layout',
    panels: [
	{type: 'top', size: 50, style: pstyle, content: '<h1 style="float:left">SMS Inbox</h1><div class="pull-right"><button onclick="window.location=\'http://www.jobalarm.com/dashboard/index2.php\';">DASHBOARD <span class="fa fa-back"></span>  </button>&nbsp;&nbsp;&nbsp;<button onclick="window.location=\'http://admin.jobalarm.com/globals\';">CANDIDATE MANAGER <span class="fa fa-people"></span></button></div>'},
      //{type: 'top', size: 50, style: pstyle, content: '<h1>SMS Inbox</h1>'},
      {type: 'left', size: 180, style: pstyle, content: w2ui.searchlayout},
      /*{
        type: 'right', size: 220, resizable: true, content: '<div id="responseForm"></div>',
        toolbar: {
          items: [
            {type: 'spacer'},
            {type: 'button', id: 'sendmail_button', disabled: true, caption: 'Forward', icon: 'fa fa-envelope-o', hint: 'Send an Email'},
            {type: 'spacer'},
            {type: 'button', id: 'saveresponsebtn', disabled: true, caption: 'Save Response', img: 'icon-save', hint: 'Save Response'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'sendmail_button':
                wus.openSendmailDialog();
                break;
              case 'saveresponsebtn':
                w2ui.layout.lock('right', 'Saving...', true);

                var frm = $('#surveyEditForm');

                $.ajax({
                  type: frm.attr('method'),
                  url: frm.attr('action'),
                  data: frm.serializeObject(),
                  success: function (data) {
                    wus.alert('Changes saved successfully.', 'Information', function () {
                      w2ui.grid.reload();
                      w2ui.layout.unlock('right');
                    });
                  }
                });
                break;
            }
          }
        }
      },*/
      {type: 'main', style: pstyle, content: 'main'},
      {
        type: 'preview', size: 250, hidden: true, resizable: true,
        toolbar: {
          items: [
            //{ type: 'button', id: 'detailsopenmsgr', caption: 'Messenger', icon: 'fa fa-comment-o' },
            {type: 'html', html: '<div id="candidate_container"><div id="fullname_container"><span id="candidate_fullname"></span></div><div id="event_container"><span id="candidate_current_event">Group/Stage: </span></div></div>'},
            {type: 'spacer'},
            {type: 'button', id: 'detailsaddnote', caption: 'Add Note', icon: 'fa fa-pencil-square-o'}
          ],
          onClick: function (event) {
            switch (event.target) {
              case 'detailsopenmsgr':
                var selectedItems = w2ui['grid'].getSelection();
                if (selectedItems.length > 0)
                  wus.createSMSChatWindow(wus.SelectedResponse, wus.SelectedResponse);
                break;
              case 'detailsaddnote':
                var selectedItems = w2ui['grid'].getSelection();
                if (selectedItems.length > 0)
                  wus.openNotesDialog(wus.SelectedResponse);
                break;
            }
          }
        }
      }
    ]
  });
  $().w2layout({
    name: 'sublayout',
    panels: [
      {type: 'top', size: 28},
      {type: 'main'}
    ]
  });

  $().w2toolbar({
    name: 'toolbar',
    items: [
      {type: 'button', id: 'hideleft', caption: '', icon: 'fa fa-long-arrow-left', hint: 'Filter Toggle'},
      {type: 'break', id: 'break0'},
      {type: 'button', id: 'sendMessage', caption: 'Send Message', icon: 'fa fa-mobile', hint: 'Send Message'},
      {type: 'break', id: 'break1'},
      //{type: 'button', id: 'actions', caption: 'Action', icon: 'fa fa-gear', hint: 'Add to Group'},
      //{type: 'break', id: 'break2'},
      {type: 'check', id: 'details', caption: 'Details', icon: 'fa fa-file-text', hint: 'Detailed View of People'},
      {type: 'break', id: 'break3'},
      //{type: 'button', id: 'refresh', caption: 'Refresh', icon: 'fa fa-refresh', hint: 'Refresh Data'},
      //{type: 'break', id: 'break4'},
      {type: 'spacer'},
      //{type: 'button', id: 'switchsurvey', caption: 'Candidate view for ' + wus.surveyName, icon: 'fa fa-list', hint: ''},
      //{type: 'break', id: 'break5'},
      //{type: 'button', id: 'hideright', caption: '', icon: 'fa fa-long-arrow-right', hint: 'Response View Toggle'}
    ],
    onClick: function (event) {
      switch (event.target) {
        case 'hideleft':
          w2ui['layout'].toggle('left', window.instant);
          break;
        case 'sendMessage':
          wus.openSMSDialog('smsview');
          break;
        case 'actions':
          wus.openActionsDialog(w2ui.grid.getSelection(), 'smsview'); //in views/shared/sharedfunctions.js
          break;
        //case 'switchsurvey':
        //  window.location = 'surveys?sid=' + wus.surveyId;
        //  break;
        case 'details':
          w2ui['layout'].toggle('preview', true);
          if (!event.item.checked) {
            $().w2grid({
              name: 'detailsnotesgrid',
              header: 'Note History',
              show: {header: true},
              multiSelect: false,
              fixedBody: true,
              columns: [
                {field: 'noteDate', caption: 'Date', size: '100px'},
                {field: 'noteBody', caption: 'Note', size: '350px'},
				{field: 'recruiter', caption: 'Created By', size: '100%'}
              ]
            });
            $().w2grid({
              name: 'detailssmsgrid',
              header: 'SMS Inbox',
              show: {
                header: true,
                footer: true
              },
              multiSelect: false,
              fixedBody: true,
              columns: [
                {field: 'smsDate', caption: 'Date', size: '100px'},
                {field: 'smsMsg', caption: 'Message', size: '100%'}
              ]
            });

            $().w2layout({
              name: 'detailslayout',
              panels: [
                {type: 'left', size: '50%', resizable: true},
                {type: 'main'},
              ]
            });

            w2ui.layout.content('preview', w2ui.detailslayout);
            w2ui.detailslayout.content('left', w2ui.detailssmsgrid);
            w2ui.detailslayout.content('main', w2ui.detailsnotesgrid);
            
            if (wus.SelectedResponse > 0) {
              w2ui.detailssmsgrid.load(wus.SelectedPersonSMSURL);
              w2ui.detailsnotesgrid.load(wus.SelectedPersonNotesURL);
              
              var gridData = w2ui.grid.get(w2ui.grid.getSelection());

              var fullname = gridData['firstname'] + ' ' + gridData['lastname'];

              if (fullname.length > 1)
                $('#candidate_fullname').html(toTitleCase(fullname));
              else
                $('#candidate_fullname').html(" ");
              
              if (gridData['referral']) {
                $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
              } else {
                $('#candidate_referredby').html('Referred By: ');
              }
              
              if (gridData['group'])
                $('#candidate_current_event').html('Group/Stage: ' + toTitleCase(gridData['group']));
              else
                $('#candidate_current_event').html('Group/Stage: ');

              if (gridData['stage'])
                $('#candidate_current_stage').html('Stage: ' + toTitleCase(gridData['stage']));
              else
                $('#candidate_current_stage').html('Stage: ');
			
            }
          } else {
            if (w2ui.detailssmsgrid)
              w2ui['detailssmsgrid'].destroy();
            if (w2ui.detailsnotesgrid)
              w2ui['detailsnotesgrid'].destroy();
            if (w2ui.detailslayout)
              w2ui['detailslayout'].destroy();
          }
          break;
        case 'refresh':
          w2ui['grid'].reset();
          //w2ui['grid'].reload();
          break;
        case 'hideright':
          w2ui['layout'].toggle('right', window.instant);
          break;
      }
    }
  });

  $().w2grid({
    name: 'grid',
    url: 'responses/getAllSMS/' + wus.accountId,
    multiSelect: true,
    show: {
	  toolbar: true,
      selectColumn: true,
      footer: true
    },
	multiSearch: true,
	searches: [
            { field: 'lastname', caption: 'Last Name', type: 'text', operator: 'begins with' },
            { field: 'firstname', caption: 'First Name', type: 'text', operator: 'begins with' },
			{ field: 'mobilenum', caption: 'Mobile', type: 'text', operator: 'begins with'}
        ],
    columns: [
      {field: 'surveyname', caption: 'Survey', size: '120px', hidden: true, resizable: true, sortable: false},
      {field: 'status', caption: '', size: '23px', resizable: true, hidden: true, sortable: false},
      {field: 'updated', caption: 'Received', size: '150px', resizable: true, sortable: true},
      {field: 'firstname', caption: 'First', size: '100px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'lastname', caption: 'Last Name', size: '100px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'mobilenum', caption: 'Mobile Number', size: '90px', resizable: true, sortable: true},
      {field: 'message', caption: 'Message', size: '200px', resizable: true, sortable: false},
      //{field: 'stage', caption: 'Group/Stage', size: '90px', resizable: true, sortable: true},
      {field: 'group', caption: 'Group/Stage', size: '90px', resizable: true, sortable: true},
	  {field: 'recruiter', caption: 'Recruiter', size: '200px', resizable: true, sortable: true},
      {field: 'email', caption: 'Email', size: '200px', resizable: true, sortable: true, editable: {type: 'text'}},
      {field: 'zipcode', caption: 'Zip Code', size: '100px', resizable: true, sortable: true},
	  {field: 'account1', caption: 'acct', size: '15px', hidden: true, resizable: true},
	  {field: 'recId', caption: 'recId', size: '15px', hidden: true, resizable: true},
	  {field: 'brand1', caption: 'brandId', size: '15px', hidden: true, resizable: true}
    ],
    onClick: function (event) {
      event.onComplete = function (event) {
        w2ui['grid'].set(event.recid, {style: 'font-weight:normal'});
        wus.onSelectSMSResponse(w2ui['grid'].get(event.recid).responseId, event.recid);
      }
    },
    records: []
  });

  w2ui.sublayout.content('top', w2ui.toolbar);
  w2ui.sublayout.content('main', w2ui.grid);
  w2ui.layout.content('main', w2ui.sublayout);
  wus.updateGlobalStages();
  wus.updateGlobalEvents();
});