var wus = wus || {};

wus.onSelectSurveyResponse = function (responseId) {
    wus.SelectedResponse = responseId;

    if (w2ui.grid.getSelection().length > 0) {
        w2ui.layout.lock('right', 'Loading...', true);
        w2ui.layout.load('right', 'responses/getForm/' + responseId, '', function () {
            w2ui.layout.unlock('right');

            var params = {
                'responseId': wus.SelectedResponse
            };

            wus.updateUploadObject(params);
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
                $('#candidate_fullname').html("Nobody");

            if (gridData['referral']) {
                $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
            } else {
                $('#candidate_referredby').html('Referred By: ');
            }

            if (gridData['event'])
                $('#candidate_current_event').html('Group: ' + toTitleCase(gridData['event']));
            else
                $('#candidate_current_event').html('Group: ');

            if (gridData['stage'])
                $('#candidate_current_stage').html('Stage: ' + toTitleCase(gridData['stage']));
            else
                $('#candidate_current_stage').html('Stage: ');
        }

        w2ui.layout.panels[2].toolbar.enable('saveresponsebtn');
        w2ui.layout.panels[2].toolbar.enable('sendmail_button');
    }
};


$(function () {
    $().w2layout({
        name: 'searchlayout',
        style: 'width:100%',
        panels: [
          { type: 'main', content: '', overflow: 'auto' },
          { type: 'bottom', size: 45, content: '<hr /><center><button onclick="wus.doSearch( {field: \'filter_\'+wus.postidvar, value: wus.postid, type: \'text\', operator: \'is\'});">Search</button> <button onclick="$(\'#filterForm\')[0].reset();wus.doSearch({field: \'filter_\'+wus.postidvar, value: wus.postid, type: \'text\', operator: \'is\'});">Reset</button></center>' }
        ]
    });
    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
    $('#layout').w2layout({
        name: 'layout',
        panels: [
          { type: 'top', size: 50, style: pstyle, content: '<h1>Manage Candidates - ' + wus.posttitle + '</h1>' },
          { type: 'left', size: 200, style: pstyle, content: w2ui.searchlayout },
          {
              type: 'right', size: 220, resizable: true, content: '<div id="responseForm"></div>',
              toolbar: {
                  items: [
                    { type: 'spacer' },
                    { type: 'button', id: 'sendmail_button', disabled: true, caption: 'Forward', icon: 'fa fa-envelope-o', hint: 'Send an Email' },
                    { type: 'spacer' },
                    { type: 'button', id: 'saveresponsebtn', disabled: true, caption: 'Save Response', img: 'icon-save', hint: 'Save Response' }
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
          },
          { type: 'main', style: pstyle, content: 'main' },
          {
              type: 'preview', size: 250, hidden: true, resizable: true,
              toolbar: {
                  items: [
                    //{ type: 'button', id: 'detailsopenmsgr', caption: 'Messenger', icon: 'fa fa-comment-o' },
                    { type: 'html', html: '<div id="candidate_container"><div id="fullname_container"><span id="candidate_fullname">Nobody</span></div><div id="referredby_container"><span id="candidate_referredby">Referred By: </span></div><br /><div id="stage_container"><span id="candidate_current_stage">Stage: </span></div><div id="event_container"><span id="candidate_current_event">Group: </span></div></div>' },
                    { type: 'spacer' },
                    { type: 'button', id: 'detailsaddnote', caption: 'Add Note', icon: 'fa fa-pencil-square-o' }
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
          { type: 'top', size: 28 },
          { type: 'main' }
        ]
    });

    $().w2toolbar({
        name: 'toolbar',
        items: [
          { type: 'check', id: 'hideleft', caption: 'Filters', icon: 'fa fa-long-arrow-left', hint: 'Filter Toggle', checked: true },
          { type: 'break', id: 'break0' },
          { type: 'button', id: 'sendMessage', caption: 'Send Message', icon: 'fa fa-mobile', hint: 'Send Message' },
          { type: 'break', id: 'break1' },
          { type: 'button', id: 'actions', caption: 'Action', icon: 'fa fa-gear', hint: 'Response Actions' },
          { type: 'break', id: 'break2' },
          { type: 'check', id: 'details', caption: 'Details', icon: 'fa fa-file-text', hint: 'Detailed View of People' },
          { type: 'break', id: 'break3' },
          { type: 'button', id: 'refresh', caption: 'Refresh', icon: 'fa fa-refresh', hint: 'Refresh Data' },
          { type: 'break', id: 'break4' },
          { type: 'button', id: 'import', caption: 'Import', icon: 'fa fa-file', hint: 'Import Data' },
          { type: 'spacer' },
          { type: 'button', id: 'switchsms', caption: 'SMS', icon: 'fa fa-list', hint: 'Switch to SMS View for these Candidates' },
          { type: 'break', id: 'break5' },
          { type: 'check', id: 'hideright', caption: 'Survey', icon: 'fa fa-long-arrow-right', hint: 'Response View Toggle', checked: true }
        ],
        onClick: function (event) {
            switch (event.target) {
                case 'hideleft':
                    w2ui['layout'].toggle('left', window.instant);
                    break;
                case 'sendMessage':
                    wus.openSMSDialog('surveyview');
                    break;
                case 'actions':
                    wus.openActionsDialog(w2ui.grid.getSelection()); //in views/shared/sharedfunctions.js
                    break;
                case 'switchsms':
                    window.location = 'jobsms?job=' + wus.postid;
                    break;
                case 'refresh':
                    w2ui['grid'].searchData = [{field: 'filter_'+wus.postidvar, value: wus.postid, type: 'text', operator: 'is'}];
                    w2ui['grid'].reload();
                    //w2ui['grid'].reload();
                    break;
                case 'import':
                    wus.openSurveyUploadForm(wus.surveyId);
                    break;
                case 'details':
                    w2ui['layout'].toggle('preview', true);
                    if (!event.item.checked) {
                        $().w2grid({
                            name: 'detailsnotesgrid',
                            header: 'Note History',
                            show: { header: true },
                            multiSelect: false,
                            fixedBody: true,
                            columns: [
                              { field: 'noteDate', caption: 'Date', size: '100px' },
                              { field: 'noteBody', caption: 'Note', size: '100%' }
                            ]
                        });
                        $().w2grid({
                            name: 'detailssmsgrid',
                            header: 'SMS History',
                            show: {
                                header: true,
                                footer: true
                            },
                            multiSelect: false,
                            fixedBody: true,
                            columns: [
                              { field: 'smsDate', caption: 'Date', size: '100px' },
                              { field: 'smsMsg', caption: 'Message', size: '100%' }
                            ]
                        });

                        $().w2layout({
                            name: 'detailslayout',
                            panels: [
                              { type: 'left', size: '50%', resizable: true },
                              { type: 'main' },
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
                                $('#candidate_fullname').html("Nobody");

                            if (gridData['referral']) {
                                $('#candidate_referredby').html('Referred By: ' + toTitleCase(gridData['referral']));
                            } else {
                                $('#candidate_referredby').html('Referred By: ');
                            }

                            if (gridData['event'])
                                $('#candidate_current_event').html('Group: ' + toTitleCase(gridData['event']));
                            else
                                $('#candidate_current_event').html('Group: ');

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
                case 'hideright':
                    w2ui['layout'].toggle('right', window.instant);
                    break;
            }
        }
    });
    $().w2grid({
        name: 'grid',
        url: 'responses/getAllSurvey/' + wus.surveyId,
        autoLoad: true,
        multiSelect: true,
        limit: 100,
        markSearchResults: false,
        show: {
            selectColumn: true,
            footer: true
        },
        //sortData: [{ field: 'surveyid', direction: 'asc' }],
        columns: [
        ],
        searchData: [
            {field: 'filter_'+wus.postidvar, value: wus.postid, type: 'text', operator: 'is'}
        ],
        onClick: function (event) {
            //console.log(event);
            event.onComplete = function (event) {
                w2ui['grid'].set(event.recid, { style: 'font-weight:normal' });
                wus.onSelectSurveyResponse(event.recid);
            }
        },
        onSelect: function (event) {
            //console.log(event); event.isStopped = true; event.isCancelled = true; event.preventDefault();
            //event.onComplete = function (event) {
            //    $('#grid_grid_cell_' + event.index + '_select input').attr('checked', false);
            //}
            //event.preventDefault();
            //console.log(event);
        },
        onLoad: function (event) {
            var returnData = JSON.parse(event.xhr.responseText);
            wus.numOptOut = returnData.numOptOut;
            wus.totalResults = returnData.total;
        }
    });
    w2ui.sublayout.content('top', w2ui.toolbar);
    w2ui.sublayout.content('main', w2ui.grid);
    w2ui.layout.content('main', w2ui.sublayout);
    w2ui.searchlayout.load('main', 'surveys/getFilters/' + wus.surveyId, '', function () {
        //$('#layout_searchlayout_panel_main').css('overflow', 'auto');
        //w2ui.layout.resize();
    });
    $.ajax({
        url: 'surveys/getViewColumnConfig/' + wus.surveyId,
        method: 'POST',
        success: function (data) {
            var columns = JSON.parse(data);
            w2ui.grid.columns = columns;
            w2ui.grid.refresh();
        }
    })
});