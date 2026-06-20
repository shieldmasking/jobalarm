var tj = {};
tj.currentPage = 1;
tj.currentCampaign = 0;
tj.maxPages = 1;
tj.updatePageNum = function() {
    $('#pageNum').html('Page ' + tj.currentPage + ' of ' + tj.maxPages);
}
tj.prevPage = function() {
    if (tj.currentPage - 1 >= 1) tj.currentPage -= 1;
    tj.getTweets(tj.currentPage);
};
tj.nextPage = function() {
    if (tj.currentPage + 1 <= tj.maxPages) tj.currentPage += 1;
    tj.getTweets(tj.currentPage);
}
tj.clickTrack = function(jobId) {
    $.ajax({
        url: 'data.php?ct=1',    // see clickTrack at line 43
        data: {
            jid: jobId
        },
        method: 'post',
        success: function(response) {
            if (console && console.log) {
                console.log(response);
            }
        }
    })
}

tj.popoverTweet = function(obj, jid) {
    $(obj).popover({
        placement: 'top',
        html: true,
        content: function() {
            return $.ajax({
                url: 'data.php?got=1',
                data: {
                    jid: jid
                },
                dataType: 'html',
                async: false
            }).responseText;
        }
    }).popover('show');
};

tj.verifyFunds = function(amount) {
    $.ajax({
        url: 'data.php?cb=1',
        data: {
            total: amount,
            cct: 1
        },
        success: function(result) {
            if (result == 'true') {
                window.location = 'campaign_add.php';
            } else {
                $('#addfundsdialog').modal('show');
            }
        }
    })

};

tj.getTweets = function(page) {
    page = page - 1;
    Metronic.blockUI({
        target: '#tweet-list',
        boxed: true
    });

    $.ajax({
        url: 'data.php?gt=1',
        dataType: 'json',
        method: 'post',
        data: {
            page: page
        },
        success: function(response) {

            $('#tweet-list').empty();
            tj.totalTweets = response['total'];
            tj.maxPages = Math.ceil(tj.totalTweets / 20);
            tj.updatePageNum();
            $.each(response['records'], function(index, data) {
                var hashtagadd = '';
                $.each(data.rawData.entities.hashtags, function(i, d) {
                    hashtagadd = hashtagadd + '<span class="todo-tasklist-badge badge badge-roundless"> \
                                                    <a target="_blank" href="https://twitter.com/hashtag/' + d.text + '?f=realtime">#' + d.text + '</a> \
                                                </span> ';
                });
                var addTweet = $(' \
                                        <div class="todo-tasklist-item todo-tasklist-item-border-green"> \
                                            <img class="todo-userpic pull-left" src="' + data.rawData.user.profile_image_url + '" width="27px" height="27px" /> \
                                            <div class="todo-tasklist-item-title"> \
                                                <a target="_blank" href="https://twitter.com/intent/follow?screen_name=' + data.userName + '" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User">' + data.userName + '</a> \
                                                <a class="btn btn-xs red tooltips" data-container="body" data-placement="top" data-original-title="Delete This Job Tweet" style="float:right" href="javascript:;"> \
                                                    <i class="fa fa-minus"></i> \
                                                </a> \
                                            </div> \
                                            <div class="todo-tasklist-item-text"> \
                                                <a href="' + data['url'] + '" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600\');return false;" class="tooltips" data-container="body" data-placement="top" data-original-title="Go To This Job">' + data.jobtext + '</a> \
                                            </div> \
                                            <div class="todo-tasklist-controls pull-left"> \
                                                <span class="todo-tasklist-date">Tweeted: ' + data.postDate + ' </span> \
                                                ' + hashtagadd + ' \
                                            </div> \
                                            <div class="pull-right"> \
                                                Clicks [ <i class="fa fa-mobile"></i> ' + data.txtcount + ' <i class="fa fa-facebook"></i> '+ data.facebook +' <i class="fa fa-globe"></i> '+ data.jobalarm + ' ]\
                                            </div> \
                                        </div>');
                $('#tweet-list').append(addTweet);
            });
            Metronic.unblockUI('#tweet-list');
        }
    });

};
jQuery(document).ready(function() {
    $('#groupManagerCell').hide();
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
    Tasks.initDashboardWidget(); // init tash dashboard widget
    TableAjax.init();
    //5UINestable.init();

    tj.getTweets(1);
    $(document).on("click", "#save_campaign", function(event) {
        //console.log($('#add_campaign_form').valid());

        if ($('#add_campaign_form').valid()) {
            $.ajax({
                url: 'data.php?ac=1',
                method: 'POST',
                data: {
                    campaign_name: $('#campaign_name').val(),
                    campaign_from: $('#campaign_from').val(),
                    campaign_to: $('#campaign_to').val(),
                    campaign_budget_click: $('#campaign_budget_click').val(),
                    campaign_budget_daily: $('#campaign_budget_daily').val(),
                    campaign_budget_total: $('#campaign_budget_total').val()

                },
                success: function(data) {
                    tj.campaign_grid.ajax.reload();
                    $('#add_campaign').modal('hide');

                }
            });
        }
    });

    $(document).on("click", "#save_changes", function(event) {
        //console.log($('#add_campaign_form').valid());

        if ($('#edit_campaign_form').valid()) {
            $('#edit_campaign_jobs').val(window.JSON.stringify($('#nestable_list_1').nestable('serialize')));
            $.ajax({
                url: 'data.php?ec=1',
                method: 'POST',
                data: {
                    campaign_id: tj.currentCampaign,
                    campaign_name: $('#edit_campaign_name').val(),
                    campaign_from: $('#edit_campaign_from').val(),
                    campaign_to: $('#edit_campaign_to').val(),
                    campaign_budget_click: $('#edit_campaign_budget_click').val(),
                    campaign_budget_daily: $('#edit_campaign_budget_daily').val(),
                    campaign_budget_total: $('#edit_campaign_budget_total').val(),
                    campaign_notes: $('#edit_campaign_notes').val(),
                    campaign_jobs: $('#edit_campaign_jobs').val()
                },
                success: function(data) {
                    tj.campaign_grid.ajax.reload();
                    $('#edit_campaign').modal('hide');

                }
            });
        }
    });


    tj.loadCampaign = function(campaignId) {
        $.ajax({
            url: 'data.php?goc=1',
            data: {
                cid: campaignId
            },
            method: 'post',
            dataType: 'json',
            success: function(response) {
                $.each(response.data, function(index, value) {
                    $('#' + index).val(value);
                });
                $('#nestable_list_1').html(response.tweetlist);
                $('#nestable_list_2').html(response.tweetpool);
            }
        });
    }

    $('#edit_campaign').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var campaignId = button.data('id');
        var modal = $(this)
        tj.currentCampaign = campaignId;
        tj.loadCampaign(campaignId);
    });

    tj.acfv = $('#add_campaign_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {

            campaign_name: {
                required: true
            },
            campaign_from: {
                required: true
            },
            campaign_to: {
                required: true
            },
            campaign_budget_click: {
                required: true,
                number: true
            },
            campaign_budget_daily: {
                required: true,
                number: true
            },
            campaign_budget_total: {
                required: true,
                number: true
            }
        },

        invalidHandler: function(event, validator) { //display error alert on form submit

        },

        highlight: function(element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function(label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function(error, element) {

            var name = element.attr("name");
            $("#error_" + name).append(error);
            //console.log(name);

            //else if (element.closest('.input-icon').size() === 1) {
            //    error.insertAfter(element.closest('.input-icon'));
            //} else {
            // error.insertAfter(element);
            //}
        },

        submitHandler: function(form) {
            form.submit();
        }
    });
    tj.ecfv = $('#edit_campaign_form').validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",
        rules: {

            edit_campaign_name: {
                required: true
            },
            edit_campaign_from: {
                required: true
            },
            edit_campaign_to: {
                required: true
            },
            edit_campaign_budget_click: {
                required: true,
                number: true
            },
            edit_campaign_budget_daily: {
                required: true,
                number: true
            },
            edit_campaign_budget_total: {
                required: true,
                number: true
            }
        },

        invalidHandler: function(event, validator) { //display error alert on form submit

        },

        highlight: function(element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function(label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function(error, element) {

            var name = element.attr("name");
            $("#error_" + name).append(error);
            //console.log(name);

            //else if (element.closest('.input-icon').size() === 1) {
            //    error.insertAfter(element.closest('.input-icon'));
            //} else {
            // error.insertAfter(element);
            //}
        },

        submitHandler: function(form) {
            form.submit();
        }
    });
    tj.removeCampaign = function(cid) {
        bootbox.confirm("Are you sure?", function(result) {
            if (result) {
                $.ajax({
                    url: 'data.php?rc=1',
                    data: {
                        cid: cid
                    },
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        tj.campaign_grid.ajax.reload();
                        bootbox.alert("Campaign Removed");
                    }
                })

            }
        });

    };
    tj.addJobTweet = function() {

        $.ajax({
            url: 'data.php?at=1',
            data: {
                message: $('#jobtweetmessage').val()
            },
            dataType: 'json',
            method: 'post',
            success: function(response) {
                if (response.success) {
                    $('#jobtweetmessage').val('');
                    $('#addjobtweet').modal('hide');
                    bootbox.alert('Tweet sent. It can takes up to 5 minutes for it to be usable in our system if the tweet meets the necessary requirements.');
                } else {
                    bootbox.alert('Error sending tweet.  Please verify your tweet message and try again.');
                }
            }
        });
    };
    $('#groupManagerBackButton').click(function() {
        tj.alex.jobId = null;
        $('#groupManagerCell').hide();
        $('#faceBookTweetCell').show();
    });
});

// Build Manage Groups Block
tj.alex = {};
tj.alex.faceBookGrid = {};
tj.alex.initializeFaceBookGrid = function() {
    $.ajax({
        url: 'dataTest.php?fb=1',
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
            var row = '';
            var length = data.data.length;
            var temp;
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][5];   // User Name
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];   // POst Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Text
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];   // Click Count retrieved from clicks table: clickcount += row; displayed in the Manage Groups grid -> database.
                row += '</td>';
                row += '<td>';
                row += data.data[i][4];    // Manage
                row += '</td>';
                row += '</tr>';
                $('#fbTweetBody').append(row);
            }
            tj.alex.faceBookGrid = $("#datatableFacebook_ajax").DataTable();
            Metronic.unblockUI();
        },
        error: function(data, err) {
            tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });
    return;
    var fbGrid = new Datatable();
    fbGrid.init({
        src: $("#datatableFacebook_ajax"),
        onSuccess: function(fbGrid) {},
        onError: function(fbGrid) {},
        onDataLoad: function(fbGrid) {},
        loadingMessage: 'Loading...',
        dataTable: {
            "columnDefs": [{
                "visible": false,
                "targets": 0
            }],
            "lengthMenu": [
                [10, 20, 50, 100, 150, -1],
                [10, 20, 50, 100, 150, "All"]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "dataTest.php?fb=1",
            },
            "searching": false,
            "order": [
                [1, "asc"]
            ]
        }
    });
    tj.alex.faceBookGrid = fbGrid.getDataTable();
};

// Build Individual FB group data from Manage Groups starling at line 381
tj.alex.jobId = null;
tj.alex.manageGroups = function(a) {
    tj.alex.jobId = a;
    $.ajax({
        url: 'dataTest.php?got=' + a,
        dataType: 'json',
        method: 'post',
        success: function(data) {
            tj.alex.tweetData = data;
            $('#faceBookTweetCell').hide();
            $('#groupManagerCellBody').empty();
            var html = '';
            html += '<div class="well"> Job ID: ' + data.tweet.id + ' <br />' + data.tweet.text + '<br />' + data.tweet.postDate + '</div>';
            $('#groupManagerCellBody').append(html);
            tj.alex.jobData = data;
            tj.alex.generateTweetManagementTable(data.data);
            $('#groupManagerCell').show();
            Metronic.unblockUI();
        },
        error: function(data, err) {
            console.log(data);
            console.log(JSON.parse(data.responseText));
            console.log('***', err);
        }
    });
};
tj.alex.updateGroups = function(groups) {
    $.ajax({
        url: 'dataTest.php?updateGroups=true',
        dataType: 'json',
        data: {
            groups: JSON.stringify(groups)
        },
        method: 'post',
        success: function(data) {
            if (data && data.success) {
               console.log(data);
                var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm yellow\">Requested <i class=\"fa fa-gears\"></i></a>');
            } else {
                var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
            }
            Metronic.unblockUI();
        },
        error: function(data, err) {
            console.log(data);
            console.log(JSON.parse(data.responseText));
            console.log('***', err);
        }
    });
};
tj.alex.userFbGroups;
tj.alex.getGroupsFb = function(id, name) {
    tj.alex.userFbId = id;
    tj.alex.userFbName = name;
    FB.api('/me/groups?limit=2000', function(response) {
//console.log(response);
        tj.alex.userFbGroups = response;
        tj.alex.initializeFaceBookGrid();
        tj.alex.updateGroups(tj.alex.userFbGroups.data);
    });
};
tj.alex.getButton = function(record) {
    var groupId = record.fbGroupId;
    var postId = record.fbPostId;   // from job_groups_postings table
    var fbPostDate = record.fbPostDate;   // from job_groups_postings table
    var showBump = record.fbBumpDate;   // value set in dataTest.php line 99, give or take
    var status = record.joinStatus;
    var jobId = record.jobId;
    var fbPermaLink = record.fbPermaLink;
//   console.log("line groupId " +record.fbGroupId);
//   console.log("line jobId " +record.jobId);
//   console.log("line fbBumpDate " +record.fbBumpDate);
//   console.log("line status " +record.joinStatus);
//   console.log("line fbPermaLink " +record.fbPermaLink);
//   console.log("line fbPostDate " +record.fbPostDate);
   if (status == 1) {
        return '<a class="btn btn-sm purple" id="' + record.fbGroupId + '" onclick="tj.alex.handlePost(\'' + record.fbGroupId + '\');">Post <i class="fa fa-gears"></i></a>';
    } else if (status == 2) {
        return '<a class="btn btn-sm yellow" id="' + record.fbGroupId + '">Pending <i class="fa fa-gears"></i></a>';
    } else if (status == 6) {
            return '<a style="min-width: 125px;" class="btn btn-sm blue">Posted <i class=\"fa fa-gears\"></i></a>';
    } else if (status == 7) {
            return '<a style="min-width: 125px;" class="btn btn-sm blue">Bump <i class=\"fa fa-gears\"></i></a>';
         }

/*     else if (status == 7) { // need to modify fbGroupId to include FB Permalink by modifying tj.alex.handlePost or building tj.alex.handleComment function
        return '<a class="btn btn-sm blue" id="' + record.fbGroupId + '" onclick="tj.alex.handlePost(\'' + record.fbGroupId + '\');">Bump <i class="fa fa-gears"></i></a>';
    }
*/
    else {
        return '<a class="btn btn-sm green" id="' + record.fbGroupId + '" onclick="tj.alex.join(\'' + record.fbGroupId + '\');">Join <i class="fa fa-gears"></i></a>';
    }
};
tj.alex.join = function(a, alert_now) {
    $.ajax({
        url: 'dataTest.php?joinGroup=' + a + '&name=' + tj.alex.userFbName,
        dataType: 'json',
        data: {
            pending: true,
            //$(fbalert): document.getElementById('fbalert'),
            alert_now: 0 
        },
        method: 'post',
        success: function(data) {
            if (data && data.success) {
                if (alert_now = 1) {
					bootbox.alert("Be careful!  You are joining groups very rapidly and we do not want your Facebook account to get blocked.  You may want to switch to Posting or Bumping Jobs for awhile");
                  //document.getElmentById('fbalert').innerHTML = alert;
                  //document.getElmentById('fbalert').show();
                  //fbalert.onClick = function() {fbalert.hide();};
				  //bootbox.alert('Be careful!  You are joining groups very rapidly and we do not want your Facebook account to get blocked.');
			}
				else {
				}
				var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm yellow\">Requested <i class=\"fa fa-gears\"></i></a>');
                window.open('http://www.facebook.com/groups/' + a, "_blank");
               } 
			   else {
                var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
            }
            Metronic.unblockUI();
        },
        error: function(data, err) {
            console.log(data);
            console.log(JSON.parse(data.responseText));
            console.log('***', err);
        }
    });
};
tj.alex.jobData;
tj.alex.generateTweetManagementTable = function(data) {
    var html = '';
    html += '';
    html += '<table class="table table-striped table-bordered table-hover stripe" id="dataTableTweetManagement">';
    html += '<thead>';
    html += '<tr role="row" class="heading">';
    html += '<th>';
    html += 'Tweet';
    html += '</th>';
    html += '<th>';
    html += 'Action';
    html += '</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="tweetManagementTableBody">';
    html += '</tbody>';
    html += '</table>';
    $('#groupManagerCellBody').append(html);
    var row = '';
    var length = data.length;
    for (var i = 0; i < length; i++) {
        row = '';
        row += '<tr>';
        row += '<td>';
        row += data[i].groupName;
        row += '</td>';
        row += '<td>';
        row += tj.alex.getButton(data[i]);
        row1 = data[i].groupStatus;
        row += '</td>';
        row += '</tr>';
        $('#tweetManagementTableBody').append(row);
    }
    $("#dataTableTweetManagement").DataTable();
};

tj.alex.updatePosted = function(groupId, userFbId, postId) {
    $.ajax({
        url: 'dataTest.php?updatePosted=true',
        dataType: 'json',
        data: {
            fbGroupId: groupId,
            fbPostId: postId,   // from FB.api response, required for posting this comment to the original post
            userFbId: userFbId,
            jobId: tj.alex.jobId
        },

        method: 'post',
        success: function(data) {
            if (data && data.success) {
               console.log(data.fbGroupId, data.userFbId, data.jobId, data.fbPostId);
//               console.log(JSON.parse(data.responseText));
            } else {
                alert('error saving facebook post update');
            }
            Metronic.unblockUI();
        },
        error: function(data, err) {
//           console.log(data);
//            console.log(JSON.parse(data.responseText));
            console.log('***', err, data);
        }
    });
};
tj.alex.post = function(groupId, text) {
	 var modal = $('#postModal');
    var groupId;
    if(text.length < 2){
    	alert('User content is required to post this job.  Please provide more content for the additional text for the post.');
    	return false;
    }

    FB.api('/' + groupId + '/feed', 'POST', {
        'message': text
    }, function(response) {
        if (response && !response.error) {
           // grab the postId from the FB.api response data to insert, ie. postId = response.id
           var postId = response.id;
            var buttonParent = $('#' + groupId).parent();
            buttonParent.empty();
            buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm blue\">Posted <i class=\"fa fa-gears\"></i></a>');
//            console.log (response);//  groupId_postID
//            console.log (groupId);// groupId
//            console.log (response.id);// id = postID
            console.log (postId);// id = postID
        } else {
            var buttonParent = $('#' + groupId).parent();
            buttonParent.empty();
            buttonParent.append('<a style=\"min-width: 125px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
        }
        modal.modal('toggle');
        tj.alex.updatePosted(groupId, tj.alex.userFbId, postId);
    });
};
tj.alex.handleBump = function () {
// update j_g_p fbBumpDate to timestamp + 7 days
// update fbAlerts for userID post count
// past fbPermaLink to window.open

}
tj.alex.handlePost = function(groupId) {
    var modal = $('#postModal');
    var body = $('#postModalBody');
    body.empty();
    $('#postModalPostButton').off();



    $('#postModalPostButton').click(function() {


        tj.alex.post(groupId, $('#tweetText').val());

    });
    var html = '';
    html += '<div class="well"><strong> Job Details: </strong><br>' + tj.alex.tweetData.tweet.text;
    html += '<div style="padding-top: 10px; float: right;"><button type="button" class="btn btn-default" id="addPostButton">Add</button></div></div>';
	//html += '<div class="well"><strong> Company Jobs Link: </strong><br>' + tj.alex.tweetData.tweet.text;
    //html += '<div style="padding-top: 10px; float: right;"><button type="button" class="btn btn-default" id="addPostButton">Add</button></div></div>';
    html += '<div class="well"> <div class="form-group"><label for="tweetText">Post Content </label><textarea type="text" class="form-control" id="tweetText" rows="6"></textarea></div></div>';

    body.append(html);

    $('#addPostButton').click(function(){
    	$('#tweetText').val($('#tweetText').val() + '\r\n\r\n' + tj.alex.tweetData.tweet.text);
    });

    modal.modal('toggle');
};
tj.alex.test = function() {
    console.log('test3333');
};