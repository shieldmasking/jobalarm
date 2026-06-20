var wus = wus || {};
wus.client = '';
wus.selectedJob = 0;
wus.newJob = 0;
wus.test = 1;
wus.postLoaded = 0;
wus.ViewCompany = 0;
wus.ViewArchived = 0;
wus.DefaultJobPostList = '';
if (wus.cid > 0) {

    wus.DefaultJobPostList = '<option value="0">Post a Job To:</option><option value="127686">Jobs</option>';


}
$(function () {
    wus.companyKeywords = JSON.parse(wus.keywords);
    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            {type: 'top', style: pstyle, size: 55, content: '<h1 style="float:left;margin-right:10px;">Jobs</h1> <select style="width:150px" name="postJob" id="postJob">'+wus.DefaultJobPostList+'</select>'},
            {type: 'main', style: pstyle}
        ]
    });

    $().w2grid({
        name: 'grid',
        url: 'job/getJobPostings/' + wus.uid,
        searchData: [
            {field: 'TMPTBLDisplay23WUAsIaOi', value: wus.uid, type: 'text', operator: 'is'}
        ],
        show: {
            toolbar: true,
            toolbarSave: true,
            toolbarColumns: false,
            footer:true
        },
        toolbar: {
            items: [
                {type: 'break'},
                { type: 'check', id: 'togglearchived', checked: false, text: 'View Archived', icon: 'fa fa-archive' },
                { type: 'check', id: 'togglecompany', checked: false, text: 'My Jobs', icon: 'fa fa-list-alt' }
            ],
            onClick: function (event) {

                event.onComplete = function (event) {

                    switch (event.target) {
                        case 'togglearchived':
                            if (event.item.checked == true) {
                                wus.ViewArchived = 1;
                                event.item.text = 'View Active';
                                w2ui.grid.url = 'job/getJobPostings/' + wus.uid + '/'+wus.ViewArchived+'/'+wus.ViewCompany;
                                w2ui.grid.reload();
                            } else {
                                wus.ViewArchived = 0;
                                event.item.text = 'View Archived';
                                w2ui.grid.url = 'job/getJobPostings/' + wus.uid + '/' + wus.ViewArchived + '/' + wus.ViewCompany;
                                w2ui.grid.reload();
                            }
                            break;
                        case 'togglecompany':                            
                            if (event.item.checked == true) {
                                wus.ViewCompany = 1;
                                event.item.text = 'All Jobs';
                                w2ui.grid.url = 'job/getJobPostings/' + wus.uid + '/' + wus.ViewArchived + '/' + wus.ViewCompany;
                                w2ui.grid.reload();
                            } else {
                                wus.ViewCompany = 0;
                                event.item.text = 'My Jobs';
                                w2ui.grid.url = 'job/getJobPostings/' + wus.uid + '/' + wus.ViewArchived + '/' + wus.ViewCompany;
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
            {field: 'company', caption: 'Company', size: '20%', sortable: false}, //I4aL59gxZz
            {field: 'positiondisplay', caption: 'Position Title', size: '25%', sortable: false}, //slDsIKOH9I
            {field: 'city', caption: 'City', size: '20%', sortable: false}, //TQ0CvUVUXN_0
            {field: 'state', caption: 'State', size: '120px', sortable: false}, //TQ0CvUVUXN_1
            {field: 'zip', caption: 'Zip', size: '85px', sortable: false}, //TQ0CvUVUXN_2
            { field: 'candidates', caption: 'Candidates', size: '100px', sortable: true }, //TQ0CvUVUXN_2
            { field: 'twitterlink', caption: '<span class="fa fa-twitter fa-lg"></span>', size: '30px', sortable: false }, //TQ0CvUVUXN_2
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
        url: 'job/getPosting',
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
            { name: 'description', type: 'textarea', required: true, html: { caption: 'Description', attr: 'cols="80" rows="20"' } },
            {name: 'requirements', type: 'textarea', required: true, html: {caption: 'Requirements', attr: 'cols="80" rows="20"'}},
            {name: 'imagefile', type: 'file', required: false, options: {placeholder: 'Drag File or Click to Choose', max: 1, style: 'width:250px'}, html: {caption: 'Background Image', attr: 'width="300"'}}
        ],
        actions: {
            Cancel: function () {
                w2popup.close();
            },
            Save: function () {
                w2ui['editJobForm'].record['description'] = addslashes($('#description').val());
                w2ui['editJobForm'].record['requirements'] = addslashes($('#requirements').val());
                w2ui['editJobForm'].refresh();
                $.ajax({
                    url: 'job/updateJob/' + wus.selectedJob,
                    data: this.record,
                    method: 'POST',
                    success: function (data) {
                        w2popup.close();
                        w2ui.grid.reload();

                    }
                });

            }
        },
        onRefresh: function (event) {
            if (!wus.postLoaded) {
                event.onComplete = function () {
                    $('#description').htmlarea({
                        toolbar: [
                        ["html"], ["bold", "italic", "underline", "strikethrough", "|", "subscript", "superscript"],
                        ["increasefontsize", "decreasefontsize"],
                        ["orderedlist", "unorderedlist"],
                        ["indent", "outdent"],
                        ["justifyleft", "justifycenter", "justifyright"],
                        ["link", "unlink", "image", "horizontalrule"],
                        ["p", "h1", "h2", "h3", "h4", "h5", "h6"]
                        ]
                    });
                    $('#requirements').htmlarea({
                        toolbar: [
                        ["html"], ["bold", "italic", "underline", "strikethrough", "|", "subscript", "superscript"],
                        ["increasefontsize", "decreasefontsize"],
                        ["orderedlist", "unorderedlist"],
                        ["indent", "outdent"],
                        ["justifyleft", "justifycenter", "justifyright"],
                        ["link", "unlink", "image", "horizontalrule"],
                        ["p", "h1", "h2", "h3", "h4", "h5", "h6"]
                        ]
                    });
                    wus.postLoaded = true;
                }
            }
        },
        onLoad: function(event) {
            event.onComplete = function () {
                setTimeout(function () {
                    $('#description').html(w2ui['editJobForm'].record['description']);
                    $('#description').htmlarea('updateHtmlArea');
                    $('#requirements').html(w2ui['editJobForm'].record['requirements']);
                    $('#requirements').htmlarea('updateHtmlArea');
                }, 250);
            }
        }
    };

    $().w2layout(wus.editJobLayout);


    $().w2form(wus.editJobForm);

    w2ui['editJobLayout'].content('main', w2ui.editJobForm);

    wus.editJob = function (jobId) {
        wus.selectedJob = jobId;
        wus.postLoaded = false;
        w2popup.open({
            title: 'Edit Job',
            width: 850,
            height: 500,
            showMax: false,
            body: '<div id="main" style="position: absolute; left: 5px; top: 5px; right: 5px; bottom: 5px;"></div>',
            onOpen: function (event) {
                event.onComplete = function (event) {
                    if (wus.selectedJob > 0) {
                        $('#w2ui-popup #main').w2render('editJobLayout');
                        //                w2ui.layout.content('left', w2ui.grid);

                        w2ui.editJobForm.recid = jobId;
                    }

                }
            }
        });

    };

    wus.archiveJob = function (jobId) {
        $.ajax({
            url: 'job/archiveJob/' + jobId,
            success: function (data) {
                w2ui.grid.reload();
            }
        });
    }

    wus.unarchiveJob = function (jobId) {
        $.ajax({
            url: 'job/unarchiveJob/' + jobId,
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
        });
    }

    wus.postToTwitter = function(JobId) {
        $.ajax({
            url: 'job/postToTwitter/'+JobId,
            success:function(data) {
                w2alert('Successfully posted to twitter.');
            }
        });

    }

});
