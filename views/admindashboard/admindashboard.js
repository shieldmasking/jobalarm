var wus = wus || {};
wus.client = '';
wus.selectedJob = 0;
wus.newJob = 0;
wus.test = 1;

$(function () {
    wus.companyKeywords = JSON.parse(wus.keywords);
    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            {type: 'top', style: pstyle, size: 55, content: '<h1 style="float:left;margin-right:10px;">Administration - All Jobs</h1>'},
            {type: 'main', style: pstyle}
        ]
    });

    $().w2grid({
        name: 'grid',
        url: 'job/getJobPostings/0',
        show: {
            toolbar: true,
            toolbarSave: true,
            toolbarColumns: false,
            footer:true
        },
        toolbar: {
            items: [
                {type: 'break'},
                {type: 'check', id: 'togglearchived', checked: false, text: 'View Archived', icon: 'fa fa-archive'}
            ],
            onClick: function (event) {

                event.onComplete = function (event) {
                    switch (event.target) {
                        case 'togglearchived':
                            if (event.item.checked == true) {
                                event.item.text = 'View Active';
                                w2ui.grid.url = 'job/getJobPostings/0/1';
                                w2ui.grid.reload();
                            } else {
                                event.item.text = 'View Archived';
                                w2ui.grid.url = 'job/getJobPostings/0';
                                w2ui.grid.reload();
                            }
                            break;
                    }

                };
            }
        },
        markSearch: false,
        multiSelect: false,
        autoLoad: true,
        columns: [
            {field: 'created', caption: 'Posting Date', size: '105px', sortable: false},
            {field: 'keywordtext', caption: 'Keyword', size: '100px', sortable: false, editable: {
                    type: 'list', placeholder: 'Select', items: wus.companyKeywords, showAll: true
                },
                render: function (record, index, col_index) {
                    var html = this.getCellValue(index, col_index);
                    return html.text || '';
                }},
            { field: 'email', caption: 'Email', size: '20%', sortable: false }, 
            { field: 'company', caption: 'Company', size: '20%', sortable: false }, //I4aL59gxZz
            {field: 'positiondisplay', caption: 'Position Title', size: '25%', sortable: false}, //slDsIKOH9I
            {field: 'city', caption: 'City', size: '20%', sortable: false}, //TQ0CvUVUXN_0
            {field: 'state', caption: 'State', size: '120px', sortable: false}, //TQ0CvUVUXN_1
            {field: 'zip', caption: 'Zip', size: '85px', sortable: false}, //TQ0CvUVUXN_2
            {field: 'candidates', caption: 'Candidates', size: '100px', sortable: false}, //TQ0CvUVUXN_2
            //{ field: 'DisplaykeqxqvwMSS', caption: 'Compensation', size: '100px', sortable: false }, //keqxqvwMSS
            //{ field: 'DisplayaHCl4OXiBp', caption: 'Job Description', size: '25%', sortable: false }, //aHCl4OXiBp
            //{ field: 'DisplayUby6DbIsDZ', caption: 'Job Requirements', size: '25%', sortable: false }, //Uby6DbIsDZ
            {field: 'copylink', caption: '', size: '180px', sortable: false}
            //background image: 268QKmhHaq
        ],
        onRefresh: function (event) {
            event.onComplete = function () {
                if ($('.clipboard_button').length > 0) {
                    //console.log('refreshed');
                    ZeroClipboard.config({
                        forceHandCursor: true
                    });

                    var client = new ZeroClipboard($('.clipboard_button'));

                    client.on("error", function (e) {
                        alert('Error when attempting to copy the URL');
                    });

                    client.on("ready", function (e) {
                        client.on("aftercopy", function (e) {
                            w2alert('URL Successfully Copied to Clipboard!');
                        });
                    });
                    wus.client = client;
                }
            }
        },
        onLoad: function (event) {
            event.onComplete = function () {
                if (wus.newJob > 0) {

                    w2ui.grid.select(wus.newJob);
                    wus.editJob(wus.newJob);
                    wus.newJob = 0;
                }
            }
        },
        onChange: function(event) {
            var JobId = event.recid;
            var KeywordId = event.value_new.id;
            wus.changeKeyword(JobId,KeywordId);
        },
        records: []
    });

    $('#jobpostbtn').click(function () {
        var mywin = window;
        var win = window.open('http://m.jobalarm.com/s/post/?sid=127884&uid=' + wus.uid, '_blank');
        var winClosed = setInterval(function () {

            if (win.closed) {
                clearInterval(winClosed);
                w2ui.grid.reload();
            }

        }, 250);
        win.focus();
    });


    w2ui.layout.content('main', w2ui.grid);

    // ZeroClipboard.config({ swfPath: wus.url+"lib/zeroclipboard/ZeroClipboard.swf" });


    //w2ui.grid.search([{ field: 'TMPTBLDisplay23WUAsIaOi', value: wus.uid, type: 'text', operator: 'is' }], 'AND');

    $('#postJob').selectmenu({
        change: function (event, data) {
            if (data.item.value > 0) {
                var mywin = window;
                var win = window.open('http://m.jobalarm.com/s/post/?sid=' + data.item.value + '&uid=' + wus.uid, '_blank');
                var winClosed = setInterval(function () {

                    if (win.closed) {
                        clearInterval(winClosed);
                        w2ui.grid.reload();
                    }

                }, 250);
                win.focus();
            }
            $('#postJob').val(0);
            $('#postJob').selectmenu('refresh', true);
        }
    });

    $.ajax({
        url: 'companies/getAccessList/' + wus.cid,
        dataType: 'json',
        success: function (data) {
            if (data.total > 0) {
                $.each(data.records, function (k, v) {
                    $('#postJob').append('<option value="' + v.surveyId + '">' + v.name + '</option>');
                });
            }
        }
    });

    wus.duplicateJob = function (jobId) {
        w2ui.grid.lock();
        $.ajax({
            url: 'job/duplicate/' + jobId,
            dataType: 'json',
            success: function (data) {
                //console.log(data);
                w2ui.grid.unlock();
                //w2alert('Job Posting Duplicated');
                wus.newJob = data.newJobId;
                w2ui.grid.reload();
            }
        });

    };

    wus.editJobLayout = {
        name: 'editJobLayout',
        padding: 4,
        panels: [
            {type: 'main'}
        ]
    };

    wus.editJobForm = {
        name: 'editJobForm',
        //header: 'Job Details',
        fields: [
            {name: 'company', type: 'text', required: true, html: {caption: 'Company', attr: 'size="40" maxlength="40"'}},
            {name: 'keyword', type: 'list', required: true, html: {caption: 'Keyword', attr: 'size="15"'},
                options: {placeholder: 'Select', items: wus.companyKeywords}},
            {name: 'position', type: 'text', required: true, html: {caption: 'Job Title', attr: 'size="40" maxlength="254"'}},
            {name: 'city', type: 'text', required: true, html: {caption: 'City', attr: 'size="30" maxlength="30"'}},
            {name: 'state', type: 'text', required: true, html: {caption: 'State', attr: 'size="30" maxlength="30"'}},
            {name: 'zip', type: 'text', required: true, html: {caption: 'Zip', attr: 'size="15" maxlength="15"'}},
            {name: 'compensation', type: 'text', required: true, html: {caption: 'Compensation', attr: 'size="20" maxlength="20"'}},
            {name: 'description', type: 'textarea', required: true, html: {caption: 'Description', attr: 'cols="50" rows="10"'}},
            {name: 'requirements', type: 'textarea', required: true, html: {caption: 'Requirements', attr: 'cols="50" rows="10"'}},
            {name: 'imagefile', type: 'file', required: false, options: {placeholder: 'Drag File or Click to Choose', max: 1, style: 'width:250px'}, html: {caption: 'Background Image', attr: 'width="300"'}}
        ],
        actions: {
            Cancel: function () {
                w2popup.close();
            },
            Save: function () {
                $.ajax({
                    url: 'job/updateJob/' + wus.selectedJob,
                    data: this.record,
                    method: 'POST',
                    success: function (data) {
                        w2popup.close();
                        w2ui.grid.reload();

                        //console.log(data);
                    }
                });
//                var errors = this.validate();
//                if (errors.length > 0) return;
//                if (this.recid == 0) {
//                    w2ui.grid.add($.extend(true, { recid: w2ui.grid.records.length + 1 }, this.record));
//                    w2ui.grid.selectNone();
//                    this.clear();
//                } else {
//                    w2ui.grid.set(this.recid, this.record);
//                    w2ui.grid.selectNone();
//                    this.clear();
//                }
            }
        },
        onRender: function (event) {
            event.onComplete = function () {
                var sel = w2ui.grid.getSelection();
                if (sel.length == 1 && sel[0] > 0) {
                    w2ui.editJobForm.clear();
                    w2ui.editJobForm.recid = sel[0];
                    w2ui.editJobForm.record = $.extend(true, {}, w2ui.grid.get(sel[0]));
                    w2ui.editJobForm.refresh();
                }
                ;
            }
        }
    };

    $().w2layout(wus.editJobLayout);

    $().w2form(wus.editJobForm);

    wus.editJob = function (jobId) {
        wus.selectedJob = jobId;
        w2popup.open({
            title: 'Edit Job',
            width: 600,
            height: 400,
            showMax: false,
            body: '<div id="main" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
            onOpen: function (event) {
                event.onComplete = function () {
                    $('#w2ui-popup #main').w2render('editJobLayout');
                    //                w2ui.layout.content('left', w2ui.grid);
                    w2ui['editJobLayout'].content('main', w2ui.editJobForm);

                }
            }
        });

    };

    wus.archiveJob = function (jobId) {
        $.ajax({
            url: 'job/adminArchiveJob/' + jobId,
            success: function (data) {
                w2ui.grid.reload();
            }
        });
    }

    wus.unarchiveJob = function (jobId) {
        $.ajax({
            url: 'job/unAdminArchiveJob/' + jobId,
            success: function (data) {
                w2ui.grid.reload();
            }
        });
    }

    wus.changeKeyword = function(JobId,KeywordId) {
        $.ajax({
            url: 'job/changeKeyword',
            data:{
                jid : JobId,
                kid: KeywordId
            },
            success:function(data) {
                //console.log(data);
            }
        })
    }

});
