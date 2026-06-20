var wus = wus || {};

wus.SelectedKeyword = 0;
wus.SelectedCompany = 0;

$(function () {

    wus.AddCompany = function (companyname, companydesc) {
        w2popup.lock('Adding...', true);
        $.ajax({
            url: 'companies/Add',
            method: 'POST',
            data: {
                companyName: companyname,
                companyDesc: companydesc
            },
            success: function (text) {
                w2popup.unlock();
                w2popup.close();
                w2ui.companygrid.reload();
            }
        });
    };

    wus.selectCompanySurveys = function (companyId) {
        w2ui.companyeditorlayout.lock('right', 'Loading Access', true);
        $.ajax({
            url: 'companies/getSurveyAccess/' + companyId + '/1',
            dataType: 'json',
            success: function (data) {
                if (data.surveyList.length > 0) {
                    $.each(data.surveyList, function (key, value) {
                        w2ui.companyaccessgrid.select(value);
                    });
                }
                w2ui.companyeditorlayout.unlock('right');
            }
        })
    }

    wus.selectCompanyAdminSurveys = function (companyId) {
        w2ui.companyeditorlayout.lock('right', 'Loading Access', true);
        $.ajax({
            url: 'companies/getSurveyAdmin/' + companyId + '/1',
            dataType: 'json',
            success: function (data) {
                if (data.surveyList.length > 0) {
                    $.each(data.surveyList, function (key, value) {
                        w2ui.companyadmingrid.select(value);
                    });
                }
                w2ui.companyeditorlayout.unlock('right');
            }
        })
    }
    wus.onSelectCompany = function (companyId) {
        wus.SelectedCompany = companyId;
        w2ui.companyaccessgrid.selectNone();
        w2ui.companyaccessgrid.load('companies/getFullAccessList/' + wus.SelectedCompany + '/1');
        w2ui.companyadmingrid.selectNone();
        w2ui.companyadmingrid.load('companies/getFullAccessList/' + wus.SelectedCompany + '/1');
        w2ui.companykeywordgrid.selectNone();
        w2ui.companykeywordgrid.load('companies/getKeywords/' + wus.SelectedCompany);
    };


    wus.onSelectKeyword = function (keywordId) {
        wus.SelectedKeyword = keywordId;
    }

    wus.saveCompanyAccess = function (companyId, surveyList) {
        w2ui.companyeditorlayout.lock('right', 'Saving Access', true);
        $.ajax({
            url: 'companies/saveSurveyAccess/' + companyId,
            data: {
                surveys: surveyList
            },
            dataType: 'json',
            method: 'POST',
            success: function (data) {
                w2ui.companyeditorlayout.unlock('right');
            }
        })

    }

    wus.saveCompanyAdmin = function (companyId, surveyList) {
        w2ui.companyeditorlayout.lock('right', 'Saving Access', true);
        $.ajax({
            url: 'companies/saveSurveyAdmin/' + companyId,
            data: {
                surveys: surveyList
            },
            dataType: 'json',
            method: 'POST',
            success: function (data) {
                w2ui.companyeditorlayout.unlock('right');
            }
        })

    }


    wus.openAddCompanyDiaglog = function () {
        $().w2popup({
            name: 'addCompanyPopup',
            title: 'Add Company',
            width: 400,
            height: 250,
            showMax: false,
            body: '<div style="padding:15px"><center>Company Name: <input type="text" id="companyname" /><br />Description:<br /><textarea id="companydesc"></textarea></center></div>',
            buttons: '<button onclick="wus.AddCompany($(\'#companyname\').val(), $(\'#companydesc\').val());">Add</button> <button onclick="w2popup.close();">Cancel</button>'
        });
    };

    wus.companygrid = {
        name: 'companygrid',
        url: 'companies/getAll',
        multiSelect: false,
        show: {
            header: true,
            toolbar: true,
            toolbarAdd: true,
            toolbarDelete: true,
            toolbarEdit: true,
            toolbarSearch: false,
            toolbarSave: true,
            toolbarColumns: false
        },
        header: 'Companies',
        columns: [
            { field: 'companyname', caption: 'Company Name', size: '200px', sortable: false },
            { field: 'companydesc', caption: 'Company Description', size: '100%', sortable: false },
            { field: 'numpostings', caption: 'Post Count', size: '100px', sortable: false },
            {
                field: 'maxpostings', caption: 'Max Postings', size: '100px', sortable: false, editable: {
                    type: 'list', placeholder: 'Select', items: [{ id: 0, text: '0' }, { id: 5, text: '5' }, { id: 10, text: '10' }, { id: 20, text: '20' }, { id: 50, text: '50' }, { id: 100, text: '100' }, { id: 500, text: '500' }], showAll: true
                },
                render: function (record, index, col_index) {
                    var html = this.getCellValue(index, col_index);
                    return html.text || '';
                }
            }
        ],
        toolbar: {
            items: [
                { type: 'spacer' },
                { type: 'button', id: 'reports', disabled: false, caption: 'Reports', icon: 'fa fa-list', hint: 'Company Reports' },
                { type: 'spacer' },
                { type: 'button', id: 'sync_notes', disabled: false, caption: 'Sync Notes', icon: 'fa fa-refresh', hint: 'Sync Notes to Companies' }
            ],
            onClick: function (event) {
                switch (event.target) {
                    case 'sync_notes':
                        w2ui.companyeditorlayout.lock('main', 'Syncing Notes', true);
                        $.ajax({
                            url: 'notes/sync',
                            dataType: 'json',
                            success: function (data) {
                                w2ui.companyeditorlayout.unlock('main');
                                w2alert('Notes Synced');

                            }
                        });

                        break;
                    case 'reports':
                        wus.displayReportView();
                        break;

                }
            }
        },
        onAdd: function (event) {
            wus.openAddCompanyDiaglog();
        },
        onSave: function (event) {
            //console.log('saving');
        },
        onClick: function (event) {
            event.onComplete = function (event) {
                var selectedItems = w2ui['companygrid'].getSelection();
                if (selectedItems.length > 0)
                    wus.onSelectCompany(event.recid);
            }
        },
        records: []
    };

    wus.companyReportForm = {
        name: 'companyReportForm',
        url: 'companies/getReport',
        //header: 'Job Details',
        fields: [
            { name: 'start_date', type: 'date', required: true, options: { format: 'yyyy-mm-dd' }, html: { caption: 'Start Date' } },
            { name: 'end_date', type: 'date', required: true, options: { format: 'yyyy-mm-dd' }, html: { caption: 'End Date' } }
        ],
        actions: {
            Save: function () {
                // this.submit({ cid: wus.SelectedCompany });
                $.get('companies/getReport', 'cid=' + wus.SelectedCompany + '&start=' + this.record['start_date'] + '&end=' + this.record['end_date'], function () {
                    document.location.href = 'companies/getReport?cid=' + wus.SelectedCompany + '&start=' + w2ui.companyReportForm.record['start_date'] + '&end=' + w2ui.companyReportForm.record['end_date'];
                });
            }
        }
    };

    $().w2form(wus.companyReportForm);

    wus.displayReportView = function () {
        $().w2popup('open',{
            title : 'Company SMS Report',
            body:'<div id="crform" style="width:100%; height:100%"></div>',
            width:350,
            height: 200,
            showMax : false,
            onOpen:function(event) {
                event.onComplete = function() {
                    $('#w2ui-popup #crform').w2render('companyReportForm');
                }
            }

        });
    };

    wus.companyaccessgrid = {
        name: 'companyaccessgrid',
        multiSelect: true,
        show: {
            header: true,
            toolbar: true,
            toolbarSearch: false,
            toolbarShowHide: false,
            toolbarReload: false,
            toolbarColumns: false,
            toolbarSave: false,
            toolbarAdd: false
        },
        header: 'Company Access',
        columns: [
            {field: 'name', caption: 'Survey', size: '100%', sortable: false}
        ],
        toolbar: {
            items: [
                {type: 'button', id: 'savebtn', caption: 'Save Access', icon: 'fa fa-save'}
            ],
            onClick: function (target, data) {
                wus.saveCompanyAccess(wus.SelectedCompany, w2ui.companyaccessgrid.getSelection());
            }
        },
        onSave: function (event) {
            console.log(event);
            //console.log(w2ui.useraccessgrid.getSelection());
        },
        onLoad: function (event) {
            event.onComplete = function (event) {
                if (wus.SelectedCompany > 0) {
                    wus.selectCompanySurveys(wus.SelectedCompany);
                }
            }
        }
    };

    wus.companyadmingrid = {
        name: 'companyadmingrid',
        multiSelect: true,
        show: {
            header: true,
            toolbar: true,
            toolbarSearch: false,
            toolbarShowHide: false,
            toolbarReload: false,
            toolbarColumns: false,
            toolbarSave: false,
            toolbarAdd: false
        },
        header: 'Company Admin',
        columns: [
            { field: 'name', caption: 'Survey', size: '100%', sortable: false }
        ],
        toolbar: {
            items: [
                { type: 'button', id: 'savebtn', caption: 'Save Access', icon: 'fa fa-save' }
            ],
            onClick: function (target, data) {
                wus.saveCompanyAdmin(wus.SelectedCompany, w2ui.companyadmingrid.getSelection());
            }
        },
        onSave: function (event) {
            console.log(event);
            //console.log(w2ui.useraccessgrid.getSelection());
        },
        onLoad: function (event) {
            event.onComplete = function (event) {
                if (wus.SelectedCompany > 0) {
                    wus.selectCompanyAdminSurveys(wus.SelectedCompany);
                }
            }
        }
    };


    wus.addKeyword = function(companyId,keyword) {
        if (keyword.length > 0) {
            $.ajax({
                url:'companies/addKeyword/'+companyId,
                data: {keyword:keyword },
                method:'POST',
                success:function(data) {
                    w2ui.companykeywordgrid.load('companies/getKeywords/' + wus.SelectedCompany);
                    w2ui.companykeywordgrid.selectNone();
                }

            });
        }
    }

    wus.delKeyword = function(keywordId) {
        if (keywordId > 0) {
            $.ajax({
                url:'companies/delKeyword',
                data: {keywordId:keywordId },
                method:'POST',
                success:function(data) {
                    w2ui.companykeywordgrid.load('companies/getKeywords/' + wus.SelectedCompany);
                    w2ui.companykeywordgrid.selectNone();

                }

            });
        }
    }

    wus.setDefaultKeyword = function (keywordId) {
        if (keywordId > 0) {
            $.ajax({
                url: 'companies/setDefaultKeyword/'+wus.SelectedCompany,
                data: { kid: keywordId },
                method: 'POST',
                success: function (data) {
                    w2ui.companykeywordgrid.load('companies/getKeywords/' + wus.SelectedCompany);
                    w2ui.companykeywordgrid.selectNone();

                }

            });
        }
    }


    wus.companykeywordgrid = {
        name: 'companykeywordgrid',
        multiSelect: true,
        show: {
            header: true,
            toolbar: true,
            toolbarSearch: false,
            toolbarShowHide: false,
            toolbarReload: false,
            toolbarColumns: false,
            toolbarSave: false,
            toolbarAdd: false
        },
        header: 'Company Keywords',
        columns: [
            {field: 'keyword', caption: 'Keyword', size: '100%', sortable: false}
        ],
        toolbar: {
            items: [
                {type: 'button', id: 'addbtn', caption: 'Add', icon: 'fa fa-plus'},
                { type: 'button', id: 'delbtn', caption: 'Del', icon: 'fa fa-minus' },
                {type: 'button', id: 'default', caption: 'Default', icon: 'fa fa-bolt'}
            ],
            onClick: function (target, data) {
                switch(target) {
                    case 'addbtn':
                        if (wus.SelectedCompany > 0) {
                            w2confirm({title:w2utils.lang('Add Company Keyword'),msg:'Keyword Name: <input type="text" id="kword" size=15" />',yes_text:'Add',no_text:'Cancel'})
                                .yes(function(){
                                    wus.addKeyword(wus.SelectedCompany,$('#kword').val()); 
                                })
                                .no(function() {
                                });
                        } else {
                            w2alert('No Company Selected');
                        }
                        break;
                    case 'delbtn':
                        if (wus.SelectedCompany > 0) {
                            w2confirm({ title: w2utils.lang('Remove Company Keyword'), msg: 'Are you sure you want to delete this keyword?', yes_text: 'Yes', no_text: 'No' })
                                .yes(function () {
                                    wus.delKeyword(wus.SelectedKeyword);
                                })
                                .no(function () {
                                });
                        } else {
                            w2alert('No Company Selected');
                        }
                        break;
                    case 'default':
                        if (wus.SelectedCompany > 0) {
                            wus.setDefaultKeyword(wus.SelectedKeyword);
                        } else {
                            w2alert('No Company Selected');
                        }
                        break;
                }
            }
        },
        onSave: function (event) {
            console.log(event);
            //console.log(w2ui.useraccessgrid.getSelection());
        },
        onLoad: function (event) {
            event.onComplete = function (event) {
            }
        },
        onClick: function (event) {
            event.onComplete = function (event) {
                var selectedItems = w2ui['companykeywordgrid'].getSelection();
                if (selectedItems.length > 0)
                    wus.onSelectKeyword(event.recid);
            }
        }
    };

    wus.companyurlgrid = {
        name: 'companyurlgrid',
        multiSelect: true,
        show: {
            header: true,
            toolbar: true,
            toolbarSearch: false,
            toolbarShowHide: false,
            toolbarReload: false,
            toolbarColumns: false,
            toolbarSave: false,
            toolbarAdd: false
        },
        header: 'Company URLs',
        columns: [
            { field: 'keyword', caption: 'Keyword', size: '100%', sortable: false }
        ],
        toolbar: {
            items: [
                { type: 'button', id: 'addbtn', caption: 'Add', icon: 'fa fa-plus' },
                { type: 'button', id: 'delbtn', caption: 'Delete', icon: 'fa fa-minus' }
            ],
            onClick: function (target, data) {
                switch (target) {
                    case 'addbtn':
                        if (wus.SelectedCompany > 0) {
                            w2confirm({ title: w2utils.lang('Add Company URL'), msg: 'URL: <input type="text" id="inurl" size=15" />', yes_text: 'Add', no_text: 'Cancel' })
                                .yes(function () {
                                    wus.addURL(wus.SelectedCompany, $('#inurl').val());
                                })
                                .no(function () {
                                });
                        } else {
                            w2alert('No Company Selected');
                        }
                        break;
                    case 'delbtn':
                        if (wus.SelectedCompany > 0) {
                            w2confirm({ title: w2utils.lang('Remove Company Keyword'), msg: 'Are you sure you want to delete this keyword?', yes_text: 'Yes', no_text: 'No' })
                                .yes(function () {
                                    wus.delKeyword(wus.SelectedKeyword);
                                })
                                .no(function () {
                                });
                        } else {
                            w2alert('No Company Selected');
                        }
                        break;
                }
            }
        },
        onSave: function (event) {
            console.log(event);
            //console.log(w2ui.useraccessgrid.getSelection());
        },
        onLoad: function (event) {
            event.onComplete = function (event) {
            }
        },
        onClick: function (event) {
            event.onComplete = function (event) {
                var selectedItems = w2ui['companykeywordgrid'].getSelection();
                if (selectedItems.length > 0)
                    wus.onSelectKeyword(event.recid);
            }
        }
    };


    wus.companyeditorsublayout = {
        name: 'companyeditorsublayout',
        panels: [
            { type: 'left',size: 200 },
            { type: 'main', size: 140 },
            { type: 'right', size: 140, resizable: false }
        ]
    }

    wus.companyeditorlayout = {
        name: 'companyeditorlayout',
        panels: [
            {type: 'main'},
            {type: 'right', size: 480, resizable: true}
        ]
    };




});