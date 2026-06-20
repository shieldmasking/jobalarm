/*global $:false */
/*global wus:true */
//var wus = wus || {};

wus.SelectedPeople = {};
wus.SelectedPersonSMSURL = '';
wus.SelectedPersonNotesURL = '';
wus.StageList = {};
wus.EventList = {};
wus.numOptOut = 0;
wus.totalResults = 0;
wus.voiceFileName = '';
wus.template_list = [];

$(function () {
    'use strict';
    if (wus.surveyId > 0) {
        wus.StageURL = '../groups/get/'+wus.accountId;
        //wus.EventURL = 'surveys/getEventList/' + wus.surveyId;
		wus.EventURL = '../globals/getEventList';

        wus.stageListURL = '../groups/get/'+wus.accountId;
        //wus.eventListURL = 'surveys/getEventList/' + wus.surveyId;
		wus.eventListURL = '../globals/getEventList';
        wus.stageAddFunction = 'wus.quickAddSurveyStage(' + wus.surveyId + ')';
        wus.eventAddFunction = 'wus.quickAddSurveyEvent(' + wus.surveyId + ')';
    } else {
        wus.StageURL = '../groups/get/'+wus.accountId;
        wus.EventURL = '../globals/getEventList';
        wus.stageListURL = '../groups/get/'+wus.accountId;
        wus.eventListURL = '../globals/getEventList';
        wus.stageAddFunction = 'wus.quickAddGlobalStage()';
        wus.eventAddFunction = 'wus.quickAddGlobalEvent()';
    }
        wus.GroupsURL = '../groups/get/'+wus.accountId;

    wus.updateUploadObject = function (params) {
        $('#file_upload').uploadify({
            'formData': params,
            'buttonText': 'Browse...',
            'buttonImage': '../img/bluebtn.png',
            'width': 100,
            'height': 25,
            'swf': '../lib/uploadify.swf',
            'uploader': '../responses/uploadFile',
            'queueSizeLimit': 1,
            onUploadSuccess: function (file, data, response) {

                var status = JSON.parse(data);
                if (!status.success) {
                    w2alert(status.msg);
                } else {

                    $('#filelink').html('<a target="_blank" href="' + status.fileURL + '">' + file.name + '</a>');
                }
            }
        });
    };

    wus.updateSurveyUploadObject = function (params) {
        $('#survey_file_upload').uploadify({
            'formData': params,
            'buttonText': 'Browse...',
            'buttonImage': '../img/bluebtn.png',
            'width': 100,
            'height': 25,
            'swf': '../lib/uploadify.swf',
            'uploader': '../surveys/uploadFile',
            'queueSizeLimit': 1,
            onUploadSuccess: function (file, data, response) {

                var status = JSON.parse(data);
                if (!status.success) {
                    w2alert(status.msg);
                } else {
                    w2popup.close();
                    w2alert('Successfully uploaded data file!', "Survey Upload", function () {

                        w2ui.grid.reload()
                    });
                    //$('#surveyfilelink').html('<a target="_blank" href="' + status.fileURL + '">' + file.name + '</a>');
                }
            }
        });
    }

    wus.updateVoiceUploadObject = function (params) {
        if (params === undefined) {
            params = { none: 0 };
        }
        $('#wave_file_upload').uploadify({
            'formData': params,
            'buttonText': 'Browse...',
            'buttonImage': 'img/bluebtn.png',
            'width': 100,
            'height': 25,
            'swf': 'lib/uploadify.swf',
            'uploader': 'sms/uploadFile',
            'queueSizeLimit': 1,
            onUploadSuccess: function (file, data, response) {

                var status = JSON.parse(data);
                if (!status.success) {
                    w2alert(status.msg);
                    $('#wave_file_name').html('<span style="color:red">' + status.msg + '</span>');
                    $('#send_wave_file').val('0');
                } else {
                    $('#wave_file_name').html('Wave file: ' + status.fileURL);
                    wus.voiceFileName = status.fileURL;
                    $('#send_wave_file').val('1');
                }
            }
        });
    }

    wus.surveyUploadForm = '\
    <div id="surveyUploadForm">\
        <br /><br />\
        <center>\
            <div id="surveyfilequeue"></div>\
            <label for="survey_file_upload">CSV File</label><br /><br />\
		    <input id="survey_file_upload" name="survey_file_upload" type="file" multiple="false">\
            <br /><br />\
            Note: First line ignored as header.<br /><br />\
            Format: MobileNum, Last Name, First Name, Email, ZipCode<br />\
        </center>\
    </div>\
    ';

    wus.openSurveyUploadForm = function (surveyId) {
        w2popup.open({
            title: 'Upload Survey Data',
            width: 450,
            height: 300,
            showMax: true,
            body: wus.surveyUploadForm,
            onOpen: function (event) {
                event.onComplete = function () {
                    wus.updateSurveyUploadObject({ surveyId: surveyId });
                    //$('#w2ui-popup #main').w2render('layout');
                    //w2ui.layout.content('left', w2ui.grid);
                    //w2ui.layout.content('main', w2ui.form);
                };
            },
            onToggle: function (event) {
                event.onComplete = function () {
                    //w2ui.layout.resize();
                }
            }
        });
    }

    wus.updateGlobalStages = function (quickadd, quickid) {
        $.ajax({
            url: wus.StageURL,
            method: 'POST',
            cache: false,
            success: function (data) {
                var stages = JSON.parse(data);
                if (quickadd !== undefined) {
                    $('#stageselect,#stageselectsms').each(function () {
                        $(this).empty();
                        $(this).append($('<option value="-1">- none -</option>'));
                        $(this).append($('<option value="0">- clear -</option>'));
                    });
                } else {
                    $('#filter_stageid,#filter_stagename').each(function () {
                        $(this).empty();
                        $(this).append($('<option value="-1">- none -</option>'));
                        $(this).append($('<option value="0">[Not Set]</option>'));
                    });
                }
                $.each(stages.records, function (s, d) {
                    if (quickadd !== undefined) {
                        $('#stageselect,#stageselectsms').each(function () {
                            $(this).append($('<option value="' + d.id + '">' + d.groupName + '</option>'));
                        });
                    } else {
                        $('#filter_stageid,#filter_stagename').each(function () {
                            $(this).append($('<option value="' + d.id + '">' + d.groupName + '</option>'));
                        });
                    }

                });

                if (quickadd !== undefined && quickid !== undefined) {
                    $('#stageselect option[value=' + quickid + ']').attr('selected', 'selected');
                    $('#stageselectsms option[value=' + quickid + ']').attr('selected', 'selected');
                    w2popup.unlock();
                }

            }
        });
    };

    wus.updateGlobalEvents = function (quickadd, quickid) {
        $.ajax({
            url: wus.EventURL,
            method: 'POST',
            cache: false,
            success: function (data) {
                var events = JSON.parse(data);
                if (quickadd) {
                    $('#eventselect,#eventselectsms').each(function () {
                        $(this).empty();
                        $(this).append($('<option value="-1">- none -</option>'));
                        $(this).append($('<option value="0">- clear -</option>'));
                    });
                }
                else {
                    $('#filter_eventid,#filter_eventname').each(function () {
                        $(this).empty();
                        $(this).append($('<option value="-1">- none -</option>'));
                        $(this).append($('<option value="0">[Not Set]</option>'));
                    });
                }

                $.each(events.items, function (e, d) {
                    if (quickadd) {
                        $('#eventselect,#eventselectsms').each(function () {
                            $(this).append($('<option value="' + d.id + '">' + d.text + '</option>'));
                        });
                    } else {
                        $('#filter_eventid,#filter_eventname').each(function () {
                            $(this).append($('<option value="' + d.id + '">' + d.text + '</option>'));
                        });
                    }
                });
                if (quickadd && quickid) {
                    $('#eventselect option[value=' + quickid + ']').attr('selected', 'selected');
                    $('#eventselectsms option[value=' + quickid + ']').attr('selected', 'selected');
                    w2popup.unlock();
                }
            }
        });
    };

    wus.updateGroupList = function (newId) {
        $.ajax({
            url: wus.GroupsURL,
            method: 'GET',
            cache: false,
            dataType:'json',
            success: function (data) {
                $('#groupselect,#groupselectsms').each(function () {
                    $(this).empty();
                    $(this).append($('<option value="-1">- none -</option>'));
                    //$(this).append($('<option value="0">- clear -</option>'));
                });

                $('#filter_groupid').each(function () {
                    $(this).empty();
                    $(this).append($('<option value="-1">- none -</option>'));
                    $(this).append($('<option value="0">[Not Set]</option>'));
                });

                $.each(data.records, function (e, d) {
                    $('#groupselect,#groupselectsms').each(function () {
                        $(this).append($('<option value="' + d.id + '">' + d.groupName + '</option>'));
                    });
                    $('#filter_groupid').each(function () {
                        $(this).append($('<option value="' + d.id + '">' + d.groupName + '</option>'));
                    });
                });
                if (newId > 0) {
                    $('#groupselect option[value=' + newId + ']').attr('selected', 'selected');
                    $('#groupselectsms option[value=' + newId + ']').attr('selected', 'selected');
                    w2popup.unlock();
                }
            }
        });
    };

    wus.quickAddSurveyStage = function (surveyId) {
        var name = prompt("Enter Stage Name", "");
        if (name != null) {
            w2popup.lock('Adding...', true);
            $.ajax({
                url: 'surveys/addStage/' + surveyId,
                method: 'POST',
                data: {
                    stageName: name
                },
                success: function (data) {
                    var indata = JSON.parse(data);
                    wus.updateGlobalStages(true, indata.newid);
                }
            });
        }
    };


    wus.quickAddGlobalStage = function () {
        var name = prompt("Enter Stage Name", "");
        if (name != null) {
            w2popup.lock('Adding...', true);
            $.ajax({
                url: '../globals/addStage',
                method: 'POST',
                data: {
                    stageName: name
                },
                success: function (data) {
                    var indata = JSON.parse(data);
                    wus.updateGlobalStages(true, indata.newid);
                }
            });
        }
    };

    wus.quickAddSurveyEvent = function (surveyId) {
        var name = prompt("Enter Event Name", "");
        if (name != null) {
            w2popup.lock('Adding...', true);
            $.ajax({
                url: 'surveys/addEvent/' + surveyId,
                method: 'POST',
                data: {
                    eventName: name
                },
                success: function (data) {
                    var indata = JSON.parse(data);
                    wus.updateGlobalEvents(true, indata.newid);
                }
            });
        }
    };

    wus.quickAddGroup = function (accountId) {
        var name = prompt("Enter Group Name", "");
        if (name != null) {
            w2popup.lock('Adding...', true);
            $.ajax({
                url: 'groups/add/' + accountId,
                method: 'POST',
                data: {
                    groupName: name
                },
                success: function (data) {
                    var indata = JSON.parse(data);
                    wus.updateGroupList(indata.newid);
                }
            });
        }
    };

    wus.addEvent = function (invalue) {
        w2popup.message();
        w2popup.lock('Adding...', true);
        $.ajax({
            url: 'globals/addEvent',
            method: 'POST',
            data: {
                eventName: invalue
            },
            success: function (data) {
                var indata = JSON.parse(data);
                wus.updateGlobalEvents(true, indata.newid);
            }
        });

    }
    wus.quickAddGlobalEvent = function () {
        w2popup.message({
            width: 400,
            height: 180,
            html: '<div style="padding: 20px; text-align: center">Enter Group Name:</div>' +
                  '<div style="margin-bottom:20px;text-align:center"><input type="text" size="20" id="eventname" /></div>' +
                  '<div style="text-align: center"><button class="btn" onclick="wus.addEvent($(\'#eventname\').val())">Add</button> <button class="btn" onclick="w2popup.message()">Cancel</button></div>'
        });
    };
    $().w2form({
        name: 'actionsform',
        style: 'border: 0px; background-color: transparent;',
        formHTML:
                '<div class="form">'+
                '<div class="w2ui-page page-0">' +
                /*'	<div class="w2ui-field"><label>Select Stage: </label>' +
                '	  <div><select id="stageselect" name="stageselect" style="width:200px"></select></div>' +
                '	</div>' +*/
                '	<div class="w2ui-field"><label>Change Group to: </label>' +
                '	  <div><select id="groupselect" name="groupselect" style="width:200px"></select> <button style="float:right" onclick="wus.quickAddGroup('+wus.accountId+')">Add</button></div>' +
                '	</div>' +
                '</div>' +
                '</div>',
        fields: [
        ]
    });

    $().w2layout({
        name: 'actionslayout',
        panels: [
          { type: 'top', size: 20, style: "background-color:transparent", content: '<center><h4 style="margin:0;padding:0;">Group Selection</h4></center>' },
          { type: 'main', content: w2ui.actionsform }
        ]
    });


    wus.setActions = function (responseIds, from) {
        w2popup.message({
            html: '<div style="padding: 20px; text-align: center">Setting...</div>',
            width: 300,
            height: 60,
            hideOnClick: true
        });
        var groupval = $('#groupselect').val();
        $.ajax({
            url: 'responses/setActions',
            method: 'POST',
            data: {
                people: responseIds,
                group: groupval,
                from: from,
                accountId: wus.accountId
            },
            success: function () {
                w2popup.message();
                w2popup.close();
                w2ui.grid.reload();
            }
        });

    }

    wus.doSearch = function (indefault) {
        var filters = $('#filterForm').serializeObject();
        var filterArray = [];
        if (indefault) {
            filterArray.push(indefault);
        }
        $.each(filters, function (k, v) {
            if (k == 'filter_zipdist' && v == 0) {
                //do nothing
            } else
                if (v != '') {
                    filterArray.push({
                        field: k,
                        value: v
                    });
                }
        });
        w2ui.grid.search(filterArray);
    }

    wus.setHold = function (responseIds, from) {
        $.ajax({
            url: 'people/setHold',
            method: 'POST',
            data: {
                people: responseIds,
                from: from
            },
            success: function () {
                w2ui.grid.reload();
            }
        });
    }

    wus.openActionsDialog = function (responseIds, from) {
        var clickAction = null;
        if (from) {
            clickAction = "wus.setActions(wus.SelectedPeople,'" + from + "');";
        } else {
            clickAction = "wus.setActions(wus.SelectedPeople);";
        }

        wus.SelectedPeople = responseIds;
        $().w2popup('open', {
            name: 'actionsPopup',
            title: 'Groups',
            width: 430,
            height: 200,
            showMax: false,
            body: '<div id="actionsPopupMain" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;padding:5px"></div>',
            buttons: '<button onclick="' + clickAction + '">Set</button> <button onclick="w2popup.close();">Cancel</button>',
            onOpen: function (event) {
                event.onComplete = function () {
                    $('#actionsPopupMain').w2render('actionslayout');
                    wus.updateGroupList();
                    //w2ui.setstagelayout.content('main', w2ui.setstageform);
                    //w2ui.setstageform.clear();
                }
            }
        });
    }

    $().w2form({
        name: 'setstageform',
        style: 'border: 0px; background-color: transparent;',
        formHTML:
                '<div class="w2ui-page page-0">' +
                '	<div class="w2ui-label">Select Stage:</div>' +
                '	<div class="w2ui-field">' +
                '		<select id="stageselect" name="stageselect" style="width:200px"></select>' +
                '	</div>' +
                '</div>',
        fields: [
          {
              name: 'stageselect', type: 'list', required: false,
              options: { url: wus.StageURL, items: [] }
          }
        ]
    });

    $().w2layout({
        name: 'setstagelayout',
        panels: [
          { type: 'top', size: 20, style: "background-color:transparent", content: '<center><h4 style="margin:0;padding:0;">Please select a stage to change to.</h4></center>' },
          { type: 'main', content: w2ui.setstageform }
        ]
    });
    //w2ui.setstagelayout.content('main', w2ui.setstageform);

    wus.setStage = function (responseIds) {
        w2popup.message({
            html: '<div style="padding: 20px; text-align: center">Setting Stage</div>',
            width: 300,
            height: 60,
            hideOnClick: true
        });
        var stageval = $('#stageselect').val();
        $.ajax({
            url: 'responses/setStage',
            method: 'POST',
            data: {
                people: responseIds,
                stage: stageval
            },
            success: function () {
                w2popup.message();
                w2popup.close();
                w2ui.grid.reload();
            }
        });
    };


    wus.openSetStageDialog = function (responseIds) {
        wus.SelectedPeople = responseIds;
        $().w2popup('open', {
            name: 'setStagePopup',
            title: 'Set Stage',
            width: 400,
            height: 150,
            showMax: false,
            body: '<div id="setStagePopupMain" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;padding:5px"></div>',
            buttons: '<button onclick="wus.setStage(wus.SelectedPeople);">Set</button> <button onclick="w2popup.close();">Cancel</button>',
            onOpen: function (event) {
                event.onComplete = function () {
                    $('#setStagePopupMain').w2render('setstagelayout');
                    //w2ui.setstagelayout.content('main', w2ui.setstageform);
                    //w2ui.setstageform.clear();
                }
            }
        });
    }


    $('').w2form({
        name: 'seteventform',
        style: 'border: 0px; background-color: transparent;',
        formHTML:
                '<div class="form">' +
                '<div class="w2ui-page page-0">' +
                '	<div class="w2ui-field"><label>Select Group:</label>' +
                '	  <div><select id="eventselect" name="eventselect" style="width:200px"></select></div>' +
                '   </div>' +
                '</div>'+
                '</div>',
        fields: [
          {
              name: 'eventselect', type: 'list', required: false,
              options: { url: wus.EventURL, items: [] }
          }
        ]
    });

    $('').w2layout({
        name: 'seteventlayout',
        panels: [
          { type: 'top', size: 20, style: "background-color:transparent", content: '<center><h4 style="margin:0;padding:0;">Group Selection</h4></center>' },
          { type: 'main', content: w2ui.seteventform }
        ]
    });

    wus.setEvent = function (responseIds) {
        w2popup.message({
            html: '<div style="padding: 20px; text-align: center">Set Group</div>',
            width: 300,
            height: 60,
            hideOnClick: true
        });
        var eventval = $('#eventselect').val();
        $.ajax({
            url: 'responses/setEvent',
            method: 'POST',
            data: {
                people: responseIds,
                event: eventval
            },
            success: function () {
                w2popup.message();
                w2popup.close();
                w2ui.grid.reload();
            }
        });
    };

    wus.openSetEventDialog = function (responseIds) {
        wus.SelectedPeople = responseIds;
        $().w2popup({
            name: 'setEventPopup',
            title: 'Set Event',
            width: 400,
            height: 150,
            showMax: false,
            body: '<div id="setEventPopupMain" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;padding:5px"></div>',
            buttons: '<button onclick="wus.setEvent(wus.SelectedPeople);">Set</button> <button onclick="w2popup.close();">Cancel</button>',
            onOpen: function (event) {
                event.onComplete = function () {
                    $('#setEventPopupMain').w2render('seteventlayout');
                };
            }
        });
    };

    $().w2form({
        name: 'sendMessageform',
        url: 'sms/send',
        formHTML:
                '<div class="form">' +
                '<div class="w2ui-page page-0">' +
                /*'	<div class="w2ui-field"><label>Select Stage:</label>' +
                '	  <div><select id="stageselectsms" name="stageselectsms" style="width:150px"></select></div>' +
                '	</div>' +*/
                '	<div class="w2ui-field"><label>Select Group:</label>' +
                '	  <div><select id="groupselectsms" name="groupselectsms" style="width:150px"></select>  <button onclick="wus.quickAddGroup('+wus.accountId+')">Add</button></div>' +
                '	</div>' +
                '</div>' +
                '</div>',
        fields: [
          {
              name: 'stageselectsms', type: 'select', required: false,
              options: { url: wus.stageListURL, items: [] }
          },
          {
              name: 'groupselectsms', type: 'select', required: false,
              options: { url: wus.eventListURL, items: [] }
          }
        ]
    });

    wus.sendMessage = function (from) {
        w2popup.message({
            html: '<div style="padding: 20px; text-align: center">Sending SMS</div>',
            width: 300,
            height: 60,
            hideOnClick: false
        });
        var messageval = $('#smsmessage').val();
        var stageval = $('#stageselectsms').val();
        var eventval = $('#eventselectsms').val();
        var groupval = $('#groupselectsms').val();
        var recips = w2ui.grid.getSelection();
        var accounts = [];
        $.each(recips, function (key, index) {
            accounts.push(w2ui.grid.get(index)['account1']);
        });
        console.log(accounts);
		var brands = [];
        $.each(recips, function (key, index) {
            brands.push(w2ui.grid.get(index)['brand1']);
        });
        console.log(brands);
		var sms_data = {
            recips: recips,
            accounts : accounts,
			brands : brands,
            message: messageval,
            stage: stageval,
            event: eventval,
            group: groupval,
			from: from,
			account: wus.accountId
        };
        console.log(sms_data);
        $.ajax({
            url: '../sms/send',
            method: 'POST',
            data: sms_data,
            success: function (event) {
                w2popup.message();
                w2popup.close();
                w2ui.grid.reload();
                if (w2ui.detailssmsgrid)
                    w2ui.detailssmsgrid.load(wus.SelectedPersonSMSURL);
            }
        });
    };

    wus.getOptOutCountFromGrid = function () {
        var selected = w2ui.grid.getSelection();
        var count = 0;
        $.each(selected, function (key, index) {
            count += w2ui.grid.get(index)['optOut'];
            //            console.log(w2ui.grid.get(key)['optOut']);
        });
        return count;
    };

    wus.quickAddSmsTemplate = function () {
        var name = prompt("Enter Template Name", "");
        if (name != null) {
            w2popup.lock('Adding...', true);
            $.ajax({
                url: 'templates/add',
                method: 'POST',
                data: {
                    template_survey_id: 1,
                    template_name: name,
                    template_message: $('#smsmessage').val()
                },
                success: function (data) {
                    w2alert('SMS Template Added');
                    w2popup.unlock();
                }
            });
        }

    }

    wus.openSMSDialog = function (from, callBack) {
        var clickAction = null;
        if (from) {
            clickAction = "wus.sendMessage('" + from + "');";
        } else {
            clickAction = "wus.sendMessage();";
        }

        var selected = w2ui.grid.getSelection();
        var selcount = selected.length;

        $.ajax({
            url: 'templates/get_sms_list',
            method: 'POST',
            async: false,
            data: {
                cmd: 'get-records'
            },
            dataType: 'json',
            success: function (d) {
                wus.template_list = d.records;
            }
        });

        var sms_popup_top_content = '';

        sms_popup_top_content += '<b>Send Text (Max 160 characters)</b><br />';
        sms_popup_top_content += '<textarea style="width:100%;height:65px" id="smsmessage" name="smsmessage" onkeydown="if ((this.value.length >= 160)&&!(event.keyCode == 8 ||event.keyCode==46||(event.keyCode>=35&&event.keyCode<=40))) return false;" onkeyup="$(\'#smscharcount\').html(this.value.length);"></textarea><br />';
        sms_popup_top_content += '<div style="width:100%;height:35px;overflow:hidden">';
        sms_popup_top_content += '<div style="float:right">Count: <span id="smscharcount">0</span></div>';
        sms_popup_top_content += '<div style="float:left">Templates: <select id="template_selector"><option value="-1">Select a template</option>';
        for (var i = 0; i < wus.template_list.length; i++)
            sms_popup_top_content += '<option value="' + wus.template_list[i]["recid"] + '">' + wus.template_list[i]["template_name"] + '</option>';

        sms_popup_top_content += '</select> <button onclick="wus.quickAddSmsTemplate()">Save</button></div>';
        sms_popup_top_content += '</div>';
        //sms_popup_top_content += '<b>Send Voice Message To: ' + numoptedout + ' Opted Out</b><br />';
        //sms_popup_top_content += 'Voice Message<br />';
        //sms_popup_top_content += '<input type="file" id="wave_file_upload" name="wave_file_upload" multiple="false" /><div id="wave_file_name"></div><input type="hidden" id="send_wave_file" name="send_wave_file" value="0" />';

        $().w2popup({
            name: 'sendMessagePopup',
            title: 'Send Message (' + selcount + ' selected)',
            width: 400,
            height: 340,
            modal: true,
            showMax: false,
            body: '<div id="sendMessagePopupMain" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px;padding:5px"></div>',
            buttons: '<button onclick="' + clickAction + '">Send</button> <button onclick="w2popup.close();">Cancel</button>',
            onOpen: function (event) {
                event.onComplete = function () {
                    if (w2ui.sendMessagelayout)
                        w2ui['sendMessagelayout'].destroy();
                    //if (w2ui.sendMessageform)
                        //w2ui['sendMessageform'].destroy();

                    $('#sendMessagePopupMain').w2render(
                      $().w2layout({
                          name: 'sendMessagelayout',
                          panels: [
                            { type: 'top', size: 130, content: sms_popup_top_content },
                            { type: 'main', content: '' }
                          ],
                          onRender: function (event) {
                              event.onComplete = function () {
                                  w2ui.sendMessagelayout.content('main', w2ui['sendMessageform']);
                                  wus.updateVoiceUploadObject();
                                  wus.updateGroupList();
                                  $('#template_selector').on("change", function () {
                                      var selected = -1;

                                      for (var i = 0; i < wus.template_list.length; i++)
                                          if (wus.template_list[i]["recid"] == $('#template_selector').val())
                                              selected = i;

                                      if (selected >= 0) {
                                          $('#smsmessage').html(wus.template_list[selected]["template_message"]);
                                      }
                                  });

                              // Try{callBack();}catch(e){}
                              };
                          }
                      })
                    );
                };
            }
        });

    };

    $().w2form({
        name: 'sendmailform',
        url: 'sendmail/send',
        style: 'border: 0px; background-color: transparent;',
        formHTML:
                '<div id="sendmailform">' +
                '  <div class="w2ui-page page-0">' +
                '  <div class="w2ui-field">'+
                '    <label>Name:</label>' +
                '    <div><input name="sendmail_name" type="text" maxlength="64" size="32" /></div>' +
                '  </div> ' +

                '  <div class="w2ui-field">' +
                '    <label>Email:</label>' +
                '    <div><input name="sendmail_email" type="text" maxlength="64" size="32"></div>' +
                '  </div> ' +

                '  <div class="w2ui-field">' +
                '    <label>CC:</label>' +
                '    <div><input name="sendmail_cc" type="text" maxlength="1024" size="32"></div>' +
                '  </div> ' +

                '  <div class="w2ui-field">' +
                '    <label>Message:</label>' +
                '    <div><textarea name="sendmail_message" type="text" style="width: 400px; height: 80px; resize: none"></textarea></div>' +
                '  </div> ' +

                '  <div class="w2ui-field">' +
                '    <label>Select Stage:</label>' +
                '	 <div><select id="stageselect" name="stageselect" style="width:200px"></select></div>' +
                '  </div>' +

                '  <div class="w2ui-field">' +
                '    <label>Select Group:</label>' +
                '    <div><select id="eventselect" name="eventselect" style="width:200px"></select><button onclick="' + wus.eventAddFunction + '">Add</button></div>' +
                '  </div>' +

                '  </div>' +
                '</div>'
        ,
        fields: [
          { name: 'sendmail_name', type: 'text', required: true },
          { name: 'sendmail_email', type: 'text', required: true },
          { name: 'sendmail_cc', type: 'text', required: false },
          { name: 'sendmail_message', type: 'text', required: true }
        ]
    });

    wus.sendmail = function (from) {
        w2popup.message({
            html: '<div style="padding: 20px; text-align: center">Sending Mail</div>',
            width: 300,
            height: 60,
            hideOnClick: false
        });

        var sm_name = $('#sendmail_name').val();
        var sm_email = $('#sendmail_email').val();
        var sm_cc = $('#sendmail_cc').val();
        var sm_msg = $('#sendmail_message').val();

        $.ajax({
            type: 'POST',
            url: '../responses/sendmail/' + wus.SelectedResponse,
            data: {
                name: sm_name,
                email: sm_email,
                cc: sm_cc,
                message: sm_msg,
                from: from,
                stage: $('#stageselect').val(),
                event: $('#eventselect').val()
            },
            success: function (event) {
                w2ui.grid.reload();
                w2popup.message();
                w2popup.close();
            }
        });
    };

    wus.openSendmailDialog = function (from) {
        var click = null;

        if (from)
            click = "wus.sendmail('" + from + "');";
        else
            click = "wus.sendmail();";

        $().w2popup({
            name: 'sendmailpopup',
            title: 'Send Email',
            width: 600,
            height: 400,
            showMax: false,
            body: '<div id="sendmail_popup_main" style="position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px; padding: 5px"></div>',
            buttons: '<button onclick="w2ui[\'sendmailform\'].clear();">Clear</button> <button onclick="' + click + '">Send</button> <button onclick="w2popup.close();">Cancel</button>',
            onOpen: function (event) {
                event.onComplete = function () {
                    wus.updateGlobalStages(true);
                    wus.updateGlobalEvents(true);
                    $('#sendmail_popup_main').w2render('sendmailform');
                    w2ui.sendmailform.clear();
                }
            }
        });
    };


    wus.openUserInfoDialog = function (mobileNum, peopleName) {
        var mNum = (mobileNum) ? mobileNum : '';
        var pName = (peopleName) ? peopleName : '';
        wus.userInfoDialog = $(document.createElement('div'));
        wus.userInfoDialog.html('<div style="width:100%;height:100%" id="userInfoDialog"></div>');

        wus.userInfoDialog.dialog({
            autoOpen: false,
            height: 400,
            width: 550,
            title: 'Viewing User: ' + pName,
            buttons: {
                Close: function () {
                    if (w2ui.userInfoDialogLayout)
                        w2ui['userInfoDialogLayout'].destroy();
                    if (w2ui.userInfoDialogSidebar)
                        w2ui['userInfoDialogSidebar'].destroy();
                    if (w2ui.userNotesGrid)
                        w2ui['userNotesGrid'].destroy();
                    if (w2ui.userSMSGrid)
                        w2ui['userSMSGrid'].destroy();
                    $('#userInfoDialog').remove();
                    $(this).dialog("close");
                }
            }
        });

        wus.userInfoDialog.dialog('open');
        $().w2sidebar({
            name: 'userInfoDialogSidebar',
            nodes: [
              { id: 'ss_profile', text: 'Profile', img: 'fa fa-user', selected: true },
              { id: 'ss_notes', text: 'Notes', img: 'fa fa-file-text' },
              { id: 'ss_sms_history', text: 'SMS', img: 'fa fa-mobile' },
              { id: 'ss_surveys', text: 'Surveys', img: 'fa fa-list' }
            ],
            onClick: function (event) {
                switch (event.target) {
                    case 'ss_profile':
                        w2ui.userInfoDialogLayout.load('main', 'people/getProfile/' + mNum, 'slide-right');
                        break;
                    case 'ss_notes':
                        if (!w2ui.userNotesGrid)
                            $().w2grid({
                                name: 'userNotesGrid',
                                url: 'notes/getResponseNotes/' + wus.SelectedResponse,
                                header: 'Notes',
                                show: { header: true },
                                multiSelect: false,
                                fixedBody: true,
                                columns: [
                                  //{ field: 'recid', caption: 'ID', hidden: true },
                                  { field: 'noteDate', caption: 'Date', size: '100px' },
                                  { field: 'noteBody', caption: 'Note', size: '100%' }
                                ]
                            });
                        w2ui.userInfoDialogLayout.content('main', w2ui.userNotesGrid);
                        break;
                    case 'ss_sms_history':
                        if (!w2ui.userSMSGrid)
                            $().w2grid({
                                name: 'userSMSGrid',
                                url: 'sms/getAllResponse/' + wus.SelectedResponse,
                                header: 'SMS History',
                                show: { header: true },
                                multiSelect: false,
                                fixedBody: true,
                                columns: [
                                  //{ field: 'recid', caption: 'ID', hidden: true },
                                  { field: 'smsDate', caption: 'Date', size: '100px' },
                                  { field: 'smsMsg', caption: 'Message', size: '100%' }
                                ]
                            });
                        w2ui.userInfoDialogLayout.content('main', w2ui.userSMSGrid);
                        break;
                    case 'ss_surveys':
                        if (!w2ui.userSurveyGrid)
                            $().w2grid({
                                name: 'userSurveyGrid',
                                url: 'surveys/getAllPerson',
                                header: 'User Surveys',
                                show: { header: true },
                                multiSelect: false,
                                fixedBody: true,
                                columns: [
                                  //{ field: 'recid', caption: 'ID', hidden: true },
                                  { field: 'surveyName', caption: 'Survey', size: '150px' },
                                  { field: 'responseDate', caption: 'Response Date', size: '100%' }
                                ]
                            });
                        w2ui.userInfoDialogLayout.content('main', w2ui.userSurveyGrid);
                        break;
                }
            }
        });
        $('#userInfoDialog').w2layout({
            name: 'userInfoDialogLayout',
            panels: [
              {
                  type: 'left', size: 110
              },
              { type: 'main' }
            ]
        });
        w2ui.userInfoDialogLayout.content('left', w2ui.userInfoDialogSidebar);
        w2ui.userInfoDialogLayout.load('main', 'people/getProfile/' + mNum);
    };
    $().w2form({
        name: 'addNoteForm',
        url: 'notes/add',
        style: 'border: 0px; background-color: transparent;',
        formHTML:
                '<div class="form">'+
                '<div class="w2ui-page page-0">' +
                '	<div class="w2ui-field"><label style="width:60px">Note:</label>' +
                '	  <div style="margin-left:5px"><textarea name="notebody" style="width:240px;height:80px;resize:none"></textarea></div>' +
                '	</div>' +
                '</div>' +
                '<div class="w2ui-buttons">' +
                '	<button class="btn" name="cancel">Cancel</button>' +
                '	<button class="btn" name="save">Save</button>' +
                '</div>'+
                '</div>',
        fields: [
          { name: 'notebody', type: 'text' }
        ],
        actions: {
            "cancel": function () {
                w2popup.close();
            },
            "save": function () {
                this.submit({ candidateId: wus.SelectedResponse, notetype: 2 }, function () {
                    if (w2ui.detailsnotesgrid) {
                        w2ui.detailsnotesgrid.load(wus.SelectedPersonNotesURL);
                    }
                    w2popup.close();
                });
            }
        }
    });
    wus.openNotesDialog = function (responseid) {
        $().w2popup('open', {
            title: 'Add Note',
            body: '<div id="noteform" style="width: 100%; height: 100%;"></div>',
            style: 'padding: 10px 0px 0px 0px',
            width: 350,
            height: 220,
            onOpen: function (event) {
                event.onComplete = function () {
                    w2ui.addNoteForm.clear();
                    $('#w2ui-popup #noteform').w2render('addNoteForm');
                }
            }
        });
    };


});
function addslashes(str) {
    //  discuss at: http://phpjs.org/functions/addslashes/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Ates Goral (http://magnetiq.com)
    // improved by: marrtins
    // improved by: Nate
    // improved by: Onno Marsman
    // improved by: Brett Zamir (http://brett-zamir.me)
    // improved by: Oskar Larsson Högfeldt (http://oskar-lh.name/)
    //    input by: Denny Wardhana
    //   example 1: addslashes("kevin's birthday");
    //   returns 1: "kevin\\'s birthday"

    return (str + '')
        .replace(/[\\"']/g, '\\$&')
        .replace(/\u0000/g, '\\0');
}
