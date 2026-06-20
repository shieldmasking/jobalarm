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
tj.clickTrack = function(jobId,referrer,mobile,brand,account) {
	//var referrer = ref;
    $.ajax({
        url: 'data.php?ct=1',    
        data: {
            jid: jobId,
			ref: referrer,
			mobile: mobile
        },
        method: 'post',
        success: function(response) {
            if (console && console.log) {
                console.log(response);
            }
        }
    })
}

tj.adTrack = function(mobile,referrer) {
	//var referrer = ref;
    $.ajax({
        url: 'data.php?ad=1',    
        data: {
            ref: referrer,
			mobile: mobile
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
            tj.totalTweets = (response) ? response['total'] : 0;
            tj.maxPages = Math.ceil(tj.totalTweets / 20);
            tj.updatePageNum();
            if (tj.totalTweets > 0)
            $.each(response['records'], function(index, data) {
                var hashtagadd = '';
                if (data.rawData && data.rawData.entities && data.rawData.entities.hashtags)
                    $.each(data.rawData.entities.hashtags, function(i, d) {
                        hashtagadd = hashtagadd + '<span class="todo-tasklist-badge badge badge-roundless"> \
                                                        <a target="_blank" href="https://twitter.com/hashtag/' + d.text + '?f=realtime">#' + d.text + '</a> \
                                                    </span> ';
                    });
                var addTweet = $(' \
                                        <div class="todo-tasklist-item todo-tasklist-item-border-green"> \
                                            <img class="todo-userpic pull-left" src="' + ((data.rawData && data.rawData.user && data.rawData.user.profile_image_url) ? data.rawData.user.profile_image_url : '') + '" width="27px" height="27px" /> \
                                            <div class="todo-tasklist-item-title"> \
                                                <a target="_blank" href="https://twitter.com/intent/follow?screen_name=' + data.userName + '" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User">' + data.userName + '</a> \
                                                <a class="btn btn-xs red tooltips" data-container="body" data-placement="top" data-original-title="Delete This Job Tweet" style="float:right" href="javascript:;" onclick="tj.alex.archiveTweet('+ data.id +');"> \
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
    //Index.init(); // init index page
    //Tasks.initDashboardWidget(); // init tash dashboard widget
    //TableAjax.init();


    //5UINestable.init();

    //tj.alex.initializejobGrid();
    //tj.alex.allStoresGrid();
	//tj.initOptGrid();
	//tj.initSMSGrid();
	//tj.initPinRequest();
    tj.alex.initSupportGrid();
	tj.initTrafficGrid();

    //tj.getTweets(1);
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
//});

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
    if ($('#add_campaign_form').length > 0)
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
    if ( $('#edit_campaign_form').length > 0)
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

	tj.alex.archiveTweet = function(jobId) {
     bootbox.confirm("Archive this job?", function(result) {
            if (result) {
	 $.ajax({
         url: 'dataTest.php?archiveTweet=1',
         data: {
             jobId: jobId
         },
         method: 'post',
         success: function(response) {
            tj.getTweets(1)
             if (console && console.log) {
                 console.log(response);
             }
         }
     })
	}
  });
};


	tj.alex.slooce = function(mobile) {
     bootbox.confirm("Please confirm that you would like to subscribe to Jobalarm.  You will receive a text confirmation as well.", function(result) {
            if (result) {
	 $.ajax({
         url: 'data.php?ss=' + mobile,
         data: {},
         method: 'post',
         success: function(response) {

             if (console && console.log) {
                 console.log(response);
             }
         }
     })
	}
  });
};


tj.closejob = function(jobId) {
     bootbox.confirm("Close this job?", function(result) {
            if (result) {
	 $.ajax({
         url: 'dataTest.php?cj=1',
         data: {
             jobId: jobId
         },
         method: 'post',
         success: function(response) {
             //tj.alex.initializejobGrid(1);

             if (console && console.log) {
                 $('#grid').trigger( 'reloadGrid' );
				 console.log(response);

             }
         }
     })
	}
  });
};

//tj.editSubscription = function() {
//     bootbox.dialog({
//                title: "Edit your SMS Subscription for:",
//                message: '<div class="row">  ' +
//                    '<div class="col-md-12"> ' +
//                    '<form class="form-horizontal"> ' +
//                    '<div class="form-group"> ' +
//                    '<div class="col-md-4"> ' +
//                    '<input id="name" name="name" type="text" placeholder="Your name" class="form-control input-md"> ' +
//                    '</div> ' +
//                    '</div> ' +
//                    '<div class="form-group"> ' +
//                    '<label class="col-md-4 control-label" for="awesomeness">Your Subscribed Messaging</label> ' +
//                    '<div class="col-md-4"> <div class="radio"> <label for="awesomeness-0"> ' +
//                    '<input type="radio" name="awesomeness" id="awesomeness-0" value="0" checked="checked"> ' +
//                    'Job Messages Only </label> ' +
//                    '</div><div class="radio"> <label for="awesomeness-1"> ' +
//                    '<input type="radio" name="awesomeness" id="awesomeness-1" value="2"> Promotional Messages Only </label> ' +
//					'</div><div class="radio"> <label for="awesomeness-2"> ' +
//					'<input type="radio" name="awesomeness" id="awesomeness-2" value="1"> Jobs & Promotional Messages</label> ' +
//					'</div><div class="radio"> <label for="awesomeness-3"> ' +
//					'<input type="radio" name="awesomeness" id="awesomeness-3" value="3"> Stop All Messaging</label> ' +
//                    '</div> ' +
//                    '</div> </div>' +
//                    '</form> </div>  </div>',
//                buttons: {
//                    success: {
//                        $.ajax({
//						 url: 'dataTest.php?es=1',
//						 data: {
//
//						 },
//						 method: 'post',
//						 success: function(response) {
//							$('#maTweetBody').empty();
//							tj.alex.initializeAccountGrid();
//							 if (console && console.log) {
//								 console.log(response);
//							 }
//						 }
//					 })
//                    }
//                }
//            });
//};

tj.addGroup = function(jobId, groupId) {
     $.ajax({
         url: 'dataTest.php?addGroup=1',
         data: {
             jobId:jobId,
             fbGroupId:groupId
         },
         method: 'post',
         success: function(response) {
            //tj.getTweets(1)
             if (console && console.log) {
                 console.log(response);
             	var buttonParent = $('#' + groupId).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 80px;\" class=\"btn btn-sm red\">Added <i class=\"fa fa-gears\"></i></a>');

				$('#grid').trigger( 'reloadGrid' );

			 }
             //bootbox.alert("Group Added!");
             //tj.alex.manageGroups(tj.alex.jobId);
         }
     })
};
	tj.openGroup = function(fbGroupId) {
        //var fbGroupId = fbGroupId;
            window.open('http://www.facebook.com/groups/' + fbGroupId, "_blank");
    };
    tj.addJobTweet = function() {

        $.ajax({
            url: 'data.php?atw=1',
            data: {
                message: $('#jobtweetmessage').val()
            },
            dataType: 'json',
            method: 'post',
            success: function(response) {
                if (response.success) {
                    $('#jobtweetmessage').val('');
                    $('#addjobtweet').modal('hide');
                    bootbox.alert('Tweet sent. JobAlarm will pick up this tweet after it processes through the Twitter feed.');
                } else {
                    bootbox.alert('Error sending tweet.  Please verify your tweet message and try again.');
                }
            }
        });
    };
	

// Build Individual FB group data from Manage Groups starling at line 381
tj.alex.jobId = null;
tj.alex.manageGroups = function(a) {
  $('#loadingModal').modal('show');
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
            tj.alex.generateTweetManagementTable(data.data,a);
            $('#groupManagerCell').show();
            Metronic.unblockUI();
            $('#loadingModal').modal('hide');
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
                buttonParent.append('<a style=\"min-width: 80px;\" class=\"btn btn-sm yellow\">Requested <i class=\"fa fa-gears\"></i></a>');
            } else {
                var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
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
    console.log('get groups fb');
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
    var jobId = tj.alex.jobId;
    var fbPermaLink = record.fbPermaLink;
//   console.log("line groupId " +record.fbGroupId);
//   console.log("line jobId " +record.jobId);
//   console.log("line fbBumpDate " +record.fbBumpDate);
//   console.log("line status " +record.joinStatus);
//   console.log("line fbPermaLink " +record.fbPermaLink);
//   console.log("line fbPostDate " +record.fbPostDate);
   if (status == 1) {
        return '<a class="btn btn-sm purple" title="Post to this fb Group" id="' + record.fbGroupId + '" onclick="tj.alex.handlePost(\'' + record.fbGroupId + '\');">Post <i class="fa fa-gears"></i></a>';
    } else if (status == 2) {
        return '<a class="btn btn-sm yellow" title="You may want to message the Group Admin about joining" id="' + record.fbGroupId + '" onclick="tj.openGroup(\'' + record.fbGroupId + '\');">Pending  <i class="fa fa-gears"></i></a>';
		//return '<a class="btn btn-sm yellow" id="' + record.fbGroupId + '">Pending <i class="fa fa-gears"></i></a>';
    } else if (status == 6) {
            return '<a style="min-width: 60px;" class="btn btn-sm blue" title="This job has been posted">Posted <i class=\"fa fa-gears\"></i></a>';
    } else if (status == 7) {
        return '<a class="btn btn-sm red" title="Post a comment to bump up your job" id="' + record.fbGroupId + '" onclick="tj.alex.bumpPost(\'' + record.fbGroupId + '\');">Bump  <i class="fa fa-gears"></i></a>';
         } else if (status == 9) {
        return '<a class="btn btn-sm green" title="Add this group to your job" id="' + record.fbGroupId + '" onclick="tj.addGroup('+jobId+',\'' + record.fbGroupId + '\');">Add  <i class="fa fa-gears"></i></a>';
         }else if (status == 8) {
        return '<a class="btn btn-sm red" title="Post a comment to bump up your job" id="' + record.fbGroupId + '" onclick="tj.alex.bumpPost(\'' + record.fbGroupId + '\');">Bumped  <i class="fa fa-gears"></i></a>';
         }

/*     else if (status == 7) { // need to modify fbGroupId to include FB Permalink by modifying tj.alex.handlePost or building tj.alex.handleComment function
        return '<a class="btn btn-sm blue" id="' + record.fbGroupId + '" onclick="tj.alex.handlePost(\'' + record.fbGroupId + '\');">Bump <i class="fa fa-gears"></i></a>';
    }
*/
    else {
        return '<a class="btn btn-sm green" title="Join this group" id="' + record.fbGroupId + '" onclick="tj.alex.join(\'' + record.fbGroupId + '\');">Join <i class="fa fa-gears"></i></a>';
    }
};
tj.alex.join = function(a) {
	$.ajax({
        url: 'dataTest.php?joinGroup=' + a + '&name=' + tj.alex.userFbName,
        dataType: 'json',
        data: {
            pending: true,
        },
        method: 'post',
        success: function(data) {
			if (data && data.success) {
                var alert_now = data.alert_now;
				if (alert_now == 1) {
					bootbox.alert("Be careful!  You are joining groups very rapidly and we do not want your Facebook account to get blocked.  You may want to switch to Posting or Bumping Jobs for awhile");
						};
				var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 80px;\" class=\"btn btn-sm yellow\">Requested <i class=\"fa fa-gears\"></i></a>');
                window.open('http://www.facebook.com/groups/' + a, "_blank");
               }
			   else {
                var buttonParent = $('#' + a).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 80px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
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

tj.createAddedGroupsTable = function(jobID) {
        $.ajax({
        url: 'dataTest.php?ag=' + jobID,
        dataType: 'json',
        method: 'post',
        success: function(data) {

            tj.alex.agJobData = data.data;
            var row = '';
            var length = tj.alex.agJobData.length;
            $('#tweetManagementTableBody2').empty();

            //LOAD DATA INTO GRIDS
            for (var i = 0; i < length; i++) {
                // first grid
                row = '';
                row += '<tr>';
                row += '<td>';
                row += tj.alex.agJobData[i].groupName;
                row += '</td>';
                row += '<td>';
                row += tj.alex.agJobData[i].groupMembers;
                row += '</td>';
                row += '<td>';
                row += tj.alex.getButton(tj.alex.agJobData[i]);
                row1 = tj.alex.agJobData[i].groupStatus;
                row += '</td>';
                row += '</tr>';
                $('#tweetManagementTableBody2').append(row);
            }
            $("#dataTableTweetManagement2").DataTable();
        },
        error: function(data, err) {
            console.log(data);
            console.log(JSON.parse(data.responseText));
            console.log('***', err);
        }
    });

}

tj.createFindGroupsTable = function(jobID) {
        $.ajax({
        url: 'dataTest.php?fg=' + jobID,
        dataType: 'json',
        method: 'post',
        success: function(data) {

            tj.alex.fgJobData = data.data;
            var row = '';
            var length = tj.alex.fgJobData.length;
            $('#tweetManagementTableBody3').empty();
            //LOAD DATA INTO GRIDS
            for (var i = 0; i < length; i++) {
                // first grid
                row = '';
                row += '<tr>';
                row += '<td>';
                row += tj.alex.fgJobData[i].groupName;
                row += '</td>';
                row += '<td>';
                row += tj.alex.fgJobData[i].groupMembers;
                row += '</td>';
                row += '<td>';
                row += tj.alex.getButton(tj.alex.fgJobData[i]);
                row1 = tj.alex.fgJobData[i].groupStatus;
                row += '</td>';
                row += '</tr>';
                $('#tweetManagementTableBody3').append(row);
            }
            $("#dataTableTweetManagement3").DataTable();

            $('html,body').animate({
               scrollTop: $('#groupManagerCell').offset().top
            });
        },
        error: function(data, err) {
            console.log(data);
            console.log(JSON.parse(data.responseText));
            console.log('***', err);
        }
    });

}

//tj.FindCompanyTable = {};
tj.initializeFindCompanyTable = function() {
    $.ajax({
        url: '../data.php?fc=1',
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
                row += data.data[i][0];
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];
                row += '</td>';
                row += '</tr>';
                $('#fcBody').append(row);
            }
            $("#dataTableCompany_ajax").DataTable();
			Metronic.unblockUI();
        	},
        	error: function(data, err) {
            Metronic.unblockUI();
        }

    });
};

tj.addGroup = function(jobId, groupId) {
     $.ajax({
         url: 'dataTest.php?addGroup=1',
         data: {
             jobId:jobId,
             fbGroupId:groupId
         },
         method: 'post',
         success: function(response) {
            //tj.getTweets(1)
             if (console && console.log) {
                 console.log(response);
             	var buttonParent = $('#' + groupId).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 80px;\" class=\"btn btn-sm red\">Added <i class=\"fa fa-gears\"></i></a>');

				$('#grid').trigger( 'reloadGrid' );

			 }
             //bootbox.alert("Group Added!");
             //tj.alex.manageGroups(tj.alex.jobId);
         }
     })
};
tj.alex.generateTweetManagementTable = function(data,jobID) {
    var html = '<ul class="nav nav-tabs">';
    html += '<li class="active"><a data-toggle="tab" href="#local_groups">Local Groups</a></li>';
    html += '<li><a data-toggle="tab" href="#add_groups">Added Groups</a></li>';
	html += '<li><a data-toggle="tab" href="#find_groups">Find Groups</a></li>';
    html += '</ul>';
    html += '<div class="tab-content">';
    //FIRST GRID
    html += '<div id="local_groups" class="tab-pane fade in active">';
    html += '<table class="table table-striped table-bordered table-hover stripe" id="dataTableTweetManagement">';
    html += '<thead>';
    html += '<tr role="row" class="heading">';
    html += '<th>';
    html += 'Groups';
    html += '</th>';
	html += '<th>';
    html += 'Members';
    html += '</th>';
    html += '<th>';
    html += 'Action';
    html += '</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="tweetManagementTableBody">';
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    //SECOND GRID
    html += '<div id="add_groups" class="tab-pane fade">';
	html += '<table class="table table-striped table-bordered table-hover stripe" id="dataTableTweetManagement2">';
    html += '<thead>';
    html += '<tr role="row" class="heading">';
    html += '<th>';
    html += 'Groups';
    html += '</th>';
	html += '<th>';
    html += 'Members';
    html += '</th>';
    html += '<th>';
    html += 'Action';
    html += '</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="tweetManagementTableBody2">';
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    //THIRD GRID
	html += '<div id="find_groups" class="tab-pane fade">';
    html += '<table class="table table-striped table-bordered table-hover stripe" id="dataTableTweetManagement3">';
    html += '<thead>';
    html += '<tr role="row" class="heading">';
    html += '<th>';
    html += 'Groups';
    html += '</th>';
	html += '<th>';
    html += 'Members';
    html += '</th>';
    html += '<th>';
    html += 'Action';
    html += '</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody id="tweetManagementTableBody3">';
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    html += '</div>';

    //ADD GRID TO PAGE
    $('#groupManagerCellBody').append(html);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      switch(target) {
        case '#add_groups':
            tj.createAddedGroupsTable(tj.alex.jobId);        //$("#dataTableTweetManagement2").DataTable();
        break;
        case '#find_groups':
            tj.createFindGroupsTable(tj.alex.jobId);
        break;
      }
    });
        var row = '';
    var length = data.length;

    //LOAD DATA INTO GRIDS
    for (var i = 0; i < length; i++) {
        // first grid
        row = '';
        row += '<tr>';
        row += '<td>';
        row += data[i].groupName;
        row += '</td>';
        row += '<td>';
        row += data[i].groupMembers;
        row += '</td>';
        row += '<td>';
        row += tj.alex.getButton(data[i]);
        row1 = data[i].groupStatus;
        row += '</td>';
        row += '</tr>';
        $('#tweetManagementTableBody').append(row);
    }
    //INITIALIZE EACH TABLE
    tj.dataTableTweetManagement = $("#dataTableTweetManagement").DataTable();
    tj.createAddedGroupsTable(jobID);
    tj.createFindGroupsTable(jobID);
};

tj.alex.updatePosted = function(groupId, userFbId, postId) {
    $.ajax({
        url: 'dataTest.php?updatePosted=true',
        dataType: 'json',
        data: {
            fbGroupId: groupId,
            fbPostId: postId,   // from FB.api response, required for posting this comment to the original post
            //userFbId: userFbId,
            jobId: tj.alex.jobId
        },

        method: 'post',
        success: function(data) {
            if (data && data.success) {
               var alertnow = data.alert_now;
				if (alertnow == 1) {
				bootbox.alert("Be careful!  You are posting to groups very rapidly and we do not want your Facebook account to get blocked.  You may want to switch to Bumping Jobs or Joining Groups for awhile");
						};
						console.log(data.fbGroupId, data.jobId, data.fbPostId);
			   //console.log(data.fbGroupId, data.userFbId, data.jobId, data.fbPostId);
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

tj.alex.bumpPost = function (groupId, jobId) {
    $.ajax({
        //url: 'dataTest.php?bumpPost=1' + '&groupId=' + groupId,
		url: 'dataTest.php?bumpPost=true',
        dataType: 'json',
        data: {
           fbGroupId: groupId,
           //fbPostId: postId,
		   jobId: tj.alex.jobId
         },
        method: 'post',
        success: function(data) {
            if (data && data.success) {
               console.log(data);
               var alert_now = data.alert_now;
			   var fbpostlink = data.fbPost;

			   //var groupid = data.fbGroupId;
			   //console.log(alert_now);
			   //console.log(fbGroupId);
			   console.log(fbpostlink);

               if (alert_now == 1) {
					bootbox.alert("Be Careful! You may be Commenting too fast for Facebook.  You may want to work on Joining Groups or Posting jobs for awhile.");

			}
				var buttonParent = $('#' + groupId).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm red\" onclick=\"tj.alex.bumpPost(' + groupId + ');\">Bumped <i class=\"fa fa-gears\"></i></a>');
				window.open('https://www.facebook.com/groups/' + groupId + '/permalink/' + fbpostlink + '/', "_blank");
				}
			   else {
                var buttonParent = $('#' + groupId).parent();
                buttonParent.empty();
                buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
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

tj.alex.post = function(groupId, text) {
    var formData = new FormData();
    // Main magic with files here
    formData.append('image', $('#fb_postform input[type=file]')[0].files[0]);
    formData.append('default',$('#myimage').is(':checked') ? 1 : 0);
    tj.formdata = {};
    tj.formdata.groupId = groupId;
    tj.formdata.text = text;
	$.ajax({
        url:'dataTest.php?uploadImg=1',
        data:formData,
        method:'POST',
        cache:false,
        contentType: false,
        processData: false,
        dataType:'json',
        success:function(data) {
            var modal = $('#postModal');

            if(tj.formdata.text.length < 2){
                alert('User content is required to post this job.  Please provide more content for the additional text for the post.');
                return false;
            }
            var pictureFileName = (data.status == 'success') ? data.filename : '';
            FB.api('/' + tj.formdata.groupId + '/feed', 'POST', {
                'message': tj.formdata.text,
                'link': 'http://www.jobalarm.com/'+pictureFileName
            }, function(response) {
                if (response && !response.error) {
                   // grab the postId from the FB.api response data to insert, ie. postId = response.id
                   var postId = response.id;
                    var buttonParent = $('#' + tj.formdata.groupId).parent();
                    buttonParent.empty();
                    buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm blue\">Posted <i class=\"fa fa-gears\"></i></a>');
        //            console.log (response);//  groupId_postID
        //            console.log (groupId);// groupId
        //            console.log (response.id);// id = postID
                    console.log (postId);// id = postID
                } else {
                    var buttonParent = $('#' + tj.formdata.groupId).parent();
                    buttonParent.empty();
					//buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm blue\">Posted<i class=\"fa fa-gears\"></i></a>');
                    buttonParent.append('<a style=\"min-width: 60px;\" class=\"btn btn-sm red\">Error <i class=\"fa fa-gears\"></i></a>');
                }
                modal.modal('toggle');
                tj.alex.updatePosted(tj.formdata.groupId, tj.alex.userFbId, postId);
            });
        }
    });
};


tj.alex.handlePost = function(groupId) {
    var modal = $('#postModal');
    var body = $('#postModalBody');
    body.empty();
    $('#postModalPostButton').off();


    $('#postModalPostButton').click(function() {


        tj.alex.post(groupId, $('#tweetText').val());

    });
    var html = '';
    html += '<div class="well"><strong> When posting to facebook, always be sure to include your Post Link along with additional and unique content about your job.<br><br> Post Link:  </strong>' + tj.alex.tweetData.tweet.text;
    html += '<div style="padding-top: 10px; float: right;"><button type="button" class=" -default" id="addPostButton">Add</button></div></div>';
	//html += '<div class="well"><strong> Company Jobs Link: </strong><br>' + 'Add this link if you have multiple jobs in the same area as this job posting.  Make sure you add content about your jobs! <br><br> http://www.jobalarm.com/ja.php?search_keyword=&search_location=plano%2C+tx&u=&i=0;'
    //html += '<div style="padding-top: 10px; float: right;"><button type="button" class=" -default" id="addButton">Add</button></div></div>';
    html += '<form name="fb_postform" id="fb_postform" method="post" enctype="multipart/form-data"><div class="well"> <div class="form-group" ><label for="tweetText"><strong>Post Content:</strong> </label><textarea type="text" class="form-control" placeholder="Click the Add Button above to include your Post Link." id="tweetText" rows="4"></textarea></div>';
	html += '<strong>Add a Thumbnail Image to your post: </strong><br>Note: JPEG files work the best.<img src="/img/post_images/jalogo.jpg" id="defaultImage" align="right" alt="default image" style="width:75px;height:75px;"><div class="form-group" align="left"><span class="btn btn-default btn-file"><input type="file" id="imagefile" name="imagefile" /></div><input type="checkbox" id="myimage" value="myimage">Set as default</span></div></form>';

    body.append(html);

    $('#addPostButton').click(function(){
    	$('#tweetText').val($('#tweetText').val() + '\r\n\r\n' + tj.alex.tweetData.tweet.text);
    });
	//$('#addButton').click(function(){
    	//$('#tweetText').val($('#tweetText').val() + '\r\n\r\n' + tj.alex.tweetData.tweet.text);
    //});

    modal.modal('toggle');
    $.ajax({
        url:'dataTest.php?checkImg=1',
        dataType:'json',
        success:function(data){
            if (data.success == 'true') {
                $('#defaultImage').attr('src','img/post_images/'+data.imginfo.file_name);
            }
        }
    });
};
tj.alex.test = function() {
    console.log('test3333');
}


    $('#groupManagerBackButton').click(function() {
        tj.alex.jobId = null;
        $('#groupManagerCell').hide();
        $('#faceBookTweetCell').show();
    });

$('#UserxBackButton').click(function() {
        //tj.alex.jobId = null;
        $('#UserxCell').hide();
        $('#ManageUserCell').show();

});



   $('#CompManagerBackButton').click(function() {
    //tj.alex.jobxGrid();
    $('#CompJobsCell').hide();
    $('#ManageCompanyCell').show();
    $('#ManageCompanyCell').trigger( 'reloadGrid' );

    });

});

// Build Manage Groups Block
tj.alex = {};
tj.alex.faceBookGrid = {};
tj.alex.initializeFaceBookGrid = function() {
    console.log('initializeFaceBookGrid');
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
            $('#CompJobsCell').hide();
           // tj.alex.initializejobGrid();
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

// Build Account Block
//tj.alex = {};
tj.alex.accountGrid = {};
tj.alex.initializeAccountGrid = function() {
	$.ajax({
        url: '../data.php?ma=1',
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
                row += data.data[i][0];   // User Name
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];   // POst Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Text
                row += '</td>';
                //row += '<td>';
                //row += data.data[i][3];   // Click Count retrieved from clicks table: clickcount += row; displayed in the Manage Groups grid -> database.
                //row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Manage
                row += '</td>';
                row += '</tr>';
                $('#maTweetBody').append(row);
            }
            tj.alex.accountGrid = $("#datatableAccount_ajax").DataTable();
			tj.initializeFindCompanyTable();
            Metronic.unblockUI();
        },
        error: function(data, err) {
            Metronic.unblockUI();
        }
    });
    return;
    var maGrid = new Datatable();
    maGrid.init({
        src: $("#datatableAccount_ajax"),
        onSuccess: function(maGrid) {},
        onError: function(maGrid) {},
        onDataLoad: function(maGrid) {},
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
                "url": "dataTest.php?ma=1",
            },
            "searching": false,
            "order": [
                [1, "asc"]
            ]
        }
    });
    tj.alex.accountGrid = maGrid.getDataTable();
};
// Build Job Grid
//tj.alex = {};
tj.alex.jobGrid = {};
tj.alex.initializejobGrid = function() {
    console.log('initialize job grid');
	$("#CompBody").html("Please wait, we are mapping candidates to your stores...");
	$.ajax({
        url: 'data.php?gco=1',
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
			$('#CompBody').html(data);
            var row = '';
            var length = data.data.length;
            var temp;
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
				row += '<td>';
                row += data.data[i][2];    // Candidates
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#CompBody').append(row);
            }
            tj.alex.jobGrid = $("#datatableComp_ajax").DataTable(
			{"order": [[ 2, "desc" ]]});
           // tj.alex.allStoresGrid();
            Metronic.unblockUI();
            //tj.alex.jobxGrid();

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

};

tj.initPinRequest = function(){
  $('#pinSubmit').click(function(){
    tj.pinRequest();
  });
};

tj.initOptGrid = function(){
  $('#optinsave').click(function(){
    tj.optin();
  });
};

tj.initSMSGrid = function(){
  $('#editsmsSave').click(function(){
    tj.smsEdit();
  });
};

tj.alex.initSupportGrid = function(){
  $('#supportModalSubmitButton').click(function(){
    tj.alex.submitSupport();
  });
};
tj.initTrafficGrid = function(){
  $('#trafficButton').click(function(){
    tj.trafficUpdate();
  });
};

tj.alex.initPermitGrid = function(){
  $('#permitSubmitButton').click(function(){
    tj.alex.submitPermit();
  });
};

tj.optin = function() {
        var allJobs = $('#allemps:checked').val();
		var allDeals = $('#alldeals:checked').val();
		var mobile = $('#mobile').val();
		//$('#optinjobalarm').modal('toggle');
				
		$.ajax({
            url: 'data.php?opt=1',
			dataType: 'json',
            method: 'post',
            data: {
                allJobs: allJobs,
				allDeals: allDeals,
				mobile: mobile
            },
            success: function(data) {
				
                $('#optinjobalarm').modal('toggle');
            }
        });
    };
	
tj.smsEdit = function() {
        var allJobs = $('#supportBody1:checked').val();
		var allDeals = $('#supportBody2:checked').val();
		var mobile = $('#mobile').val();
		var brand = $(this).data('id');
		
		//$('#smsEdit').modal('toggle');
				
		$.ajax({
            url: 'data.php?opt=1',
			dataType: 'json',
            method: 'post',
            data: {
                allJobs: allJobs,
				allDeals: allDeals,
				mobile: mobile,
				brand: brand
            },
            success: function(data) {
				$('#optinjobalarm').modal('toggle');
                //$('#smsEdit').modal('toggle');
            }
        });
    };

tj.alex.submitSupport = function(){
  var type = $('#supportModalType').val();
  var name = $('#supportModalName').val();
  var phone = $('#supportModalPhone').val();
  var email = $('#supportModalEmail').val();
  var message = $('#supportModalMessage').val();


  if(type.length == 0){
    alert('Please select a support type.');
    return;
  }
  if(name.length == 0){
    alert('Name is required.');
    return;
  }
  if(email.length == 0 && phone.length ==0){
    alert('Please include either email or phone number.');
    return;
  }

  $.ajax({
        url: 'data.php?sp=1',
        dataType: 'json',
        method: 'post',
        data: {
          type: type,
          name: name,
          phone: phone,
          email: email,
          message: message
        },
        success: function(data) {
			$('#supportmodal').modal('toggle');
          alert('Thank you, we will be in contact within 24hrs.');
        },
        error: function(data, err) {

            Metronic.unblockUI();
        }
});


};

tj.trafficUpdate = function(){
  var cand = $('#candid').val();
  var group = $('#trafficGroup').val();
  var note = $('#note').val();
  var message = $('#message').val();

  $.ajax({
        url: '../data.php?tu=1',
        dataType: 'json',
        method: 'post',
        data: {
          group: group,
		  cand: cand,
          note: note,
		  message: message,
        },
        success: function(response) {
			console.log(response);
			$('#updateCand').modal('toggle');
			document.getElementById("message").value = "";
			document.getElementById("trafficGroup").value = "";
			document.getElementById("note").value = "";
			tj.alex.usersGrid.ajax.reload;
			//$("#datatableUsers_ajax").trigger("reloadGrid", [{page: 1}]);
	           
        },
        error: function(data, err) {

            Metronic.unblockUI();
        }
});


};

/////Permit
tj.alex.submitPermit = function(){
  var permit = $('#permit').val();

  //if(type.length == 0 || name.length == 0 || email.length == 0 || message.length == 0  ){
  //  alert('Not all required info provided.');
  //  return;
  //}

  $.ajax({
        url: 'data.php?per=1',
        dataType: 'json',
        method: 'post',
        data: {
          permit: permit
        },
        success: function(data) {
			$('#underAge').modal('toggle');
          
        },
        error: function(data, err) {

            Metronic.unblockUI();
        }
});


};
/////Pin Request

tj.pinRequest = function() {
       var mobile = $('#mobile').val();
		$('#pinRequest').modal('show');	
		$.ajax({
            url: '../data.php?pr=1',
			dataType: 'json',
            method: 'post',
            data: {
				mobile: mobile
            },
            success: function(data) {
				$('#pinRequest').modal('hide');
					            
            }
        });
    };

// Build Job Grid
//tj.alex = {};
tj.alex.jobxGrid = {};
tj.alex.jobxGrid = function(storeId, address, city, street, zip, name, storeNum) {
	$.ajax({
        url: 'data.php?jx=' + storeId,
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {

            $('#ManageCompanyCell').hide();
            var row = '';
            var length = data.data.length;
            var temp;
             $('#CompxBody').empty();
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
				row += '<td>';
                row += data.data[i][3];    // Date Texted
                row += '</td>';
				row += '<td>';
                row += data.data[i][4];    // Click Count
                row += '</td>';
				row += '<td>';
                row += data.data[i][2];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#CompxBody').append(row);
            }
            name = name.replace('\'', '&#39;');
            $("#datatableCompx_ajax").DataTable();
             $('#CompJobsCell .caption-subject').html(name + ' ('+ storeNum +')' + '<br />' + address + '<br />' + city + ', ' + street + ' ' + zip);
            $('#CompJobsCell ').show();
			tj.alex.atsxGrid(storeId);
             Metronic.unblockUI();

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
});

};

// Build ATS Job Grid
//tj.alex = {};
tj.alex.atsxGrid = {};
tj.alex.atsxGrid = function(storeId) {
	$.ajax({
        url: 'data.php?atsx=' + storeId,
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
            //$('#CompxBody').hide();
            var row = '';
            var length = data.data.length;
            var temp;
             $('#ATSxBody').empty();
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Position
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Post Date
                row += '</td>';
				row += '<td>';
                row += data.data[i][2];    // Auto Post
                row += '</td>';
				row += '<td>';
                row += data.data[i][3];    // Click Count
                row += '</td>';
				row += '<td>';
                row += data.data[i][4];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#ATSxBody').append(row);
            }
            $("#datatableATSx_ajax").DataTable();
            $('#CompJobsCell').show();
             Metronic.unblockUI();

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
});

};

// Build All Stores Grid
//tj.alex.allStoresGrid = {};
tj.alex.allStoresGrid = function() {
    console.log('all stores grid');
	$.ajax({
        url: 'data.php?gaj=1',
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
            $('#AllStoresBody').empty();
            var row = '';
            var length = data.data.length;
            var temp;
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#AllStoresBody').append(row);
            }
            //tj.alex.allStoresGrid = $("#datatableAllStores_ajax").DataTable();
            $("#datatableAllStores_ajax").DataTable();
            //$('#CompJobsCell').show();
            Metronic.unblockUI();
            //tj.alex.jobxGrid();

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

};


// Build User Grid

tj.alex.userGrid = {};
tj.alex.initializeuserGrid = function() {
	//$('#UserxCell').hide();
	$.ajax({
        url: 'data.php?ug=1',
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
        	var row = '';
            var length = data.data.length;
            var temp;
            $('#UserBody').empty();
             $('#UserxCell').hide();
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Stores
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#UserBody').append(row);
            }
            tj.alex.userGrid = $("#datatableUser_ajax").DataTable();
            Metronic.unblockUI();
            //tj.alex.userxGrid;

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

 };
 
 // Build New User Grid

tj.alex.usernGrid = {};
tj.alex.initializeusernGrid = function() {
	//$('#UserxCell').hide();
	$.ajax({
        url: 'data.php?nu=1',
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
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Stores
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
				row += '<td>';
                row += data.data[i][4];    // Actions
                row += '</td>';
				row += '<td>';
                row += data.data[i][5];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#UsernBody').append(row);
            }
            tj.alex.usernGrid = $("#datatableUsern_ajax").DataTable(
			{"order": [[ 4, "desc" ]]});
            Metronic.unblockUI();
            //tj.alex.userxGrid;

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

 };
 
  // Build New Users Grid

tj.alex.usersGrid = {};
tj.alex.initializeusersGrid = function() {
	//$('#UserxCell').hide();
	$.ajax({
        url: '../data.php?mtr=1',
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
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Stores
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
				row += '<td>';
                row += data.data[i][4];    // Actions
                row += '</td>';
				row += '</tr>';
                $('#UsersBody').append(row);
            }
            tj.alex.usersGrid = $("#datatableUsers_ajax").DataTable({
			"order": [[ 3, "desc" ]],
			"columnDefs": [
            {
                "targets": [ 4 ],
                "visible": false
            }
			],
			"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            switch(Number(aData[4])) {
            case 1:
				console.log('GOT 1!!!')
				$(nRow).css('color', 'orange').css('font-style', 'italic');
                //$('td', nRow).addClass('whiteRow');
                break;
            case 3:
                console.log('GOT 3!!!')
                $(nRow).css('color', 'green').css('font-style', 'italic');
				//$('td', nRow).addClass('greenRow');
                break;
            }
			}
			});
            Metronic.unblockUI();
            //tj.alex.userxGrid;

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

 };
 
//Build Banners Grid
tj.alex.userbGrid = {};
tj.alex.initializeuserbGrid = function() {
	//$('#UserxCell').hide();
	$.ajax({
        url: 'data.php?bg=1',
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
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Stores
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
				row += '<td>';
                row += data.data[i][4];    // Actions
                row += '</td>';
				row += '<td>';
                row += data.data[i][5];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#UserbBody').append(row);
            }
            tj.alex.userbGrid = $("#datatableUserb_ajax").DataTable(
			{"displayLength": 1500});
            Metronic.unblockUI();
            //tj.alex.userxGrid;

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
    });

 };

// Build User Grid

tj.alex.userxGrid = {};
tj.alex.userxGrid = function(userId) {
    console.log(11);
	$.ajax({
        url: 'data.php?ux=1',
        dataType: 'json',
        method: 'post',
        data: {
        name: name,
        userId: userId
        },
        success: function(data) {
            $('#ManageUserCell').hide();
            $('#UserxCell').show();
            $('#UserxBody').empty();
            var row = '';
            var length = data.data.length;
            var temp;
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Actions
                row += '</td>';
                row += '</tr>';
                $('#UserxBody').append(row);
            }
            $("#datatableUserx_ajax").DataTable();
            //$('#ManageUserCell').show();
             Metronic.unblockUI();

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
        }
});

};



//Delete User
tj.deleteUser = function(userId) {
     bootbox.confirm("Remove this User?<p>All stores currently assigned will become unassigned.</p>", function(result) {
            if (result) {
	 $.ajax({
         url: 'data.php?du=1',
         data: {
         userId: userId
         },
         method: 'post',
         success: function(response) {

             if (console && console.log) {
                 console.log(response);
                 $('#grid').trigger( 'reloadGrid' );
             }


         }
     })
	}
  });
};

//Remove Subscription
tj.removeSMS = function(sid) {
     bootbox.confirm("Remove this SMS Subscription?", function(result) {
            if (result) {
	 $.ajax({
         url: 'data.php?rs=1',
         data: {
         sid: sid
         },
         method: 'post',
         success: function(response) {

             if (console && console.log) {
                 console.log(response);
				 tj.removeSMS_grid.ajax.reload();
                 //$('#grid').trigger( 'reloadGrid' );
             }
         }
     })
	}
  });
};

//Remove Store from User
tj.removeStore = function(userId,storeId) {
     bootbox.confirm("Remove this store from the User?", function(result) {
            if (result) {
	 $.ajax({
         url: 'data.php?rem=1',
         data: {
         userId: userId,
         storeId: storeId
         },
         method: 'post',
         success: function(response) {

             if (console && console.log) {
                 console.log(response);
                 //$('#grid').trigger( 'reloadGrid' );
                 tj.alex.allStoresGrid();
                 tj.alex.userxGrid(userId);
             }


         }
     })
	}
  });
};



tj.getCustomTweetMessage = function(jobId) {
    $.ajax({
        url: 'data.php?gjt=1',
        data: {
            customize : 1,
            jid:jobId,
        },
        dataType: 'json',
        method: 'post',
        success: function(response) {
            if (response.success) {
                bootbox.dialog({
                    title: "Post & Text Job",

                    message: '<div class="row">  ' +
                    '<div class="col-md-12"> Please confirm that you would like to post this job and send it via text to up to 20 Candidates. <br /><br /></div>' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-2 control-label" for="tweetmessage">Job Text</label> ' +
                    '<div class="col-md-10"> ' +
                    '<textarea maxlength="160" style="width:100%" name="tweetmessage" id="tweetmessage">'+response.message+'</textarea>'+
                    '<label class="col-md-12 control-label" for="tweetmessage"><h6>Maxlength = 160 characters</h6></label> ' +
					'</div></div>' +
                    '</form> </div>  </div>',
                      value: response.message,
                      buttons: {
                        confirm: {
                          label: "Post Job",
                          className: "btn-success",
                          callback:function(){
                            $.ajax({
                                url: 'data.php?at=1',
                                data: {
                                    customize:1,
                                    jobId:jobId,
                                    message: $('#tweetmessage').val()
                                },
                                //dataType: 'json',
                                method: 'post',
                                success: function(response) {
                                    //tj.alex.jobxGrid();
                                    bootbox.alert("Job Posted");
                                    //$('#datatableComp_ajax').trigger( 'reloadGrid' );
             						if (console && console.log) {
                 						console.log(response);
                 						$('#grid').trigger( 'reloadGrid' );
             							}
                                }
                            });

                          }
                        },
                        cancel: {
                          label: "Cancel",
                          className: "btn-danger"
                        }
                      },
                      callback:function(result) {
					}
                });
                // $('#jobtweetmessage').val('');
                // $('#addjobtweet').modal('hide');
                // bootbox.alert('Tweet sent. JobAlarm will pick up this tweet after it processes through the Twitter feed.');
            } else {
                // bootbox.alert('Error sending tweet.  Please verify your tweet message and try again.');
            }
        }
    });
}

tj.postMessage = function(jobId) {
    $.ajax({
        url: 'data.php?gjt=1',
        data: {
            jid:jobId
        },
        dataType: 'json',
        method: 'post',
        success: function(response) {
            if (response.success) {
                bootbox.dialog({
                    title: "Post Job",

                    message: '<div class="row">  ' +
                    '<div class="col-md-12"> Please confirm that you would like to post this job to JobAlarm, Twitter and Facebook. <br /><br /></div>' +
                    '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                    '<div class="form-group"> ' +
                    '<label class="col-md-2 control-label" for="tweetmessage">Job Details</label> ' +
                    '<div class="col-md-10"> ' +
                    '<textarea maxlength="160" style="width:100%" name="tweetmessage" id="tweetmessage">'+response.message+'</textarea>'+
                    '<label class="col-md-12 control-label" for="tweetmessage"><h6>Maxlength = 160 characters</h6></label> ' +
					'</div></div>' +
                    '</form> </div>  </div>',
                      value: response.message,
                      buttons: {
                        confirm: {
                          label: "Post Job",
                          className: "btn-success",
                          callback:function(){
                            $.ajax({
                                url: 'data.php?at=1',
                                data: {
                                    jobId:jobId,
                                    message: $('#tweetmessage').val()
                                },
                                //dataType: 'json',
                                method: 'post',
                                success: function(response) {
                                    //tj.alex.jobxGrid();
                                    bootbox.alert("Job Posted");
                                    //$('#datatableComp_ajax').trigger( 'reloadGrid' );
             						if (console && console.log) {
                 						console.log(response);
                 						$('#grid').trigger( 'reloadGrid' );
             							}
                                }
                            });

                          }
                        },
                        cancel: {
                          label: "Cancel",
                          className: "btn-danger"
                        }
                      },
                      callback:function(result) {
					}
                });
                // $('#jobtweetmessage').val('');
                // $('#addjobtweet').modal('hide');
                // bootbox.alert('Tweet sent. JobAlarm will pick up this tweet after it processes through the Twitter feed.');
            } else {
                // bootbox.alert('Error sending tweet.  Please verify your tweet message and try again.');
            }
        }
    });
}

tj.addCustomTweet = function(tweetmessage,jobId) {
    tj.getCustomTweetMessage(jobId);
}

tj.postJob = function(jobId) {
    console.log('jobId', jobId);
    tj.postMessage(jobId);
}

tj.createJob = function(storeId) {
     $.ajax({
         url: 'data.php?createJob=1',
         data: {
             storeId: storeId
         },
         method: 'post',
         success: function(response) {
            tj.alex.jobxGrid(storeId);
           }
  	});
};

tj.textJob = function(jobId) {
    console.log('jobId', jobId);
    tj.getCustomTweetMessage(jobId);
}

tj.createJob = function(storeId) {
     $.ajax({
         url: 'data.php?createJob=1',
         data: {
             storeId: storeId
         },
         method: 'post',
         success: function(response) {
            tj.alex.jobxGrid(storeId);
           }
  	});
};

tj.appClick = function(mobile,brand,acct) {
	//var currentTime = moment().format('YYYY-MM-DD hh:mm:ss');
    $.ajax({
        url: 'data.php?app=1',    
        data: {
            brand: brand,
			mobile: mobile,
			acct: acct
        },
        method: 'post',
        success: function(response) {
            if (console && console.log) {
                console.log(response);
            }
        }
    })
};
