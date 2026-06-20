var wus = wus || {};

$(function () {

    wus.addGlobalStage = function (stagename) {
        w2popup.lock('Adding...', true);
        $.ajax({
            url: 'globals/addStage',
            method:'POST',
            data: {
                stageName:stagename
            },
            success: function (text) {
                w2popup.unlock();
                w2popup.close();
                w2ui.globalstagesgrid.reload();
            }
        })
    };

    wus.openAddGlobalStageDialog = function () {
        $().w2popup({
            name: 'addGlobalStagePopup',
            title: 'Add Stage',
            width: 400,
            height: 150,
            showMax: false,
            body: '<div style="padding:15px"><center>Stage Name: <input type="text" id="globalstagename" /></center></div>',
            buttons: '<button onclick="wus.addGlobalStage($(\'#globalstagename\').val());">Add</button> <button onclick="w2popup.close();">Cancel</button>'
        });
    };

    wus.addGlobalEvent = function (eventname) {
        w2popup.lock('Adding...', true);
        $.ajax({
            url: 'globals/addEvent',
            method: 'POST',
            data: {
                eventName: eventname
            },
            success: function (text) {
                w2popup.unlock();
                w2popup.close();
                w2ui.globaleventsgrid.reload();
            }
        })
    };

    wus.openAddGlobalEventDialog = function () {
        $().w2popup({
            name: 'addGlobalEventPopup',
            title: 'Add Event',
            width: 400,
            height: 150,
            showMax: false,
            body: '<div style="padding:15px"><center>Event Name: <input type="text" id="globaleventname" /></center></div>',
            buttons: '<button onclick="wus.addGlobalEvent($(\'#globaleventname\').val());">Add</button> <button onclick="w2popup.close();">Cancel</button>'
        });
    };
    
    wus.globalstagesgrid = {
        name: 'globalstagesgrid',
        url: 'globals/getStages',
        multiSelect: false,
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
        header: 'Global Stages',
        columns: [
            //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
            { field: 'name', caption: 'Name', size: '100%', sortable: false, editable: { type: 'text', inTag: 'maxlength=40' } }
        ],
        onAdd: function (event) {
            wus.openAddGlobalStageDialog();
        },
        onClick: function (event) {

        },
        records: []
    };

    wus.globaleventsgrid = {
        name: 'globaleventsgrid',
        url: 'globals/getEvents',
        multiSelect: false,
        show: {
            header: true,
            toolbar: true,
            toolbarAdd: true,
            toolbarDelete: true,
            toolbarSave: true,
            toolbarSearch: false,
            toolbarColumns: false
        },
        header: 'Global Events',
        columns: [
            //{ field: 'surveyid', caption: 'Survey ID', size: '80px', sortable: false },
            { field: 'name', caption: 'Name', size: '100%', sortable: false, editable: { type: 'text', inTag: 'maxlength=40' } }
        ],
        onAdd: function (event) {
            wus.openAddGlobalEventDialog();
        },
        records: []
    };
    $().w2grid(wus.globalstagesgrid);
    $().w2grid(wus.globaleventsgrid);
    $().w2layout({
        name: 'globaladminview',
        padding: 0,
        panels: [
            { type: 'left', size: '50%', content: 'left' },
            { type: 'main', content:'main' }
        ]

    });


});