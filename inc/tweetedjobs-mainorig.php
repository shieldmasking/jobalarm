var tj = {};
tj.currentPage = 1;
tj.currentCampaign = 0;
tj.maxPages = 1;
tj.updatePageNum = function () {
    $('#pageNum').html('Page ' + tj.currentPage + ' of ' + tj.maxPages);
}
tj.prevPage = function () {
    if (tj.currentPage - 1 >= 1) tj.currentPage -= 1;
    tj.getTweets(tj.currentPage);
};
tj.nextPage = function () {
    if (tj.currentPage + 1 <= tj.maxPages) tj.currentPage += 1;
    tj.getTweets(tj.currentPage);
}
tj.clickTrack = function(jobId) {
    $.ajax({
        url: 'data.php?ct=1',
        data: { jid: jobId },
        method: 'post',
        success: function (response) {
            if (console && console.log) {
                console.log(response);
            }
        }
    })
}

tj.popoverTweet = function (obj,jid) {
    $(obj).popover({
        placement:'top',
        html:true,
        content:function(){ 
            return $.ajax({
                url: 'data.php?got=1',
                data: { jid: jid },
                dataType: 'html',
                async: false
            }).responseText;
        }
    }).popover('show');
};

tj.verifyFunds = function (amount) {
    $.ajax({
        url: 'data.php?cb=1',
        data: { total: amount, cct:1 },
        success: function (result) {
            if (result == 'true') {
                window.location = 'campaign_add.php';
            } else {
                $('#addfundsdialog').modal('show');
            }
        }
    })

};

tj.getTweets = function (page) {
    page = page - 1;
    Metronic.blockUI({
        target: '#tweet-list',
        boxed: true
    });

    $.ajax({
        url: 'data.php?gt=1',
        dataType: 'json',
        method: 'post',
        data: { page: page },
        success: function (response) {

            $('#tweet-list').empty();
            tj.totalTweets = response['total'];
            tj.maxPages = Math.ceil(tj.totalTweets / 20);
            tj.updatePageNum();
            $.each(response['records'], function (index, data) {
                var hashtagadd = '';
                $.each(data.rawData.entities.hashtags, function (i, d) {
                    hashtagadd = hashtagadd + '<span class="todo-tasklist-badge badge badge-roundless"> \
                                                    <a target="_blank" href="https://twitter.com/hashtag/'+ d.text + '?f=realtime">#' + d.text + '</a> \
                                                </span> ';
                });
                var addTweet = $(' \
                                        <div class="todo-tasklist-item todo-tasklist-item-border-green"> \
                                            <img class="todo-userpic pull-left" src="'+ data.rawData.user.profile_image_url + '" width="27px" height="27px" /> \
                                            <div class="todo-tasklist-item-title"> \
                                                <a target="_blank" href="https://twitter.com/intent/follow?screen_name='+ data.userName + '" class="tooltips" data-container="body" data-placement="top" data-original-title="Follow This User">' + data.userName + '</a> \
                                                <a class="btn btn-xs red tooltips" data-container="body" data-placement="top" data-original-title="Archive This Job Tweet" style="float:right" href="javascript:archiveTweet;"> \
                                                    <i class="fa fa-minus"></i> \
                                                </a> \
                                            </div> \
                                            <div class="todo-tasklist-item-text"> \
                                                <a href="'+ data['url'] + '" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no, location=no, status=no, menubar=no, scrollbars=yes,resizable=yes, width=800, height=600\');return false;" class="tooltips" data-container="body" data-placement="top" data-original-title="Go To This Job">' + data.text + '</a> \
                                            </div> \
                                            <div class="todo-tasklist-controls pull-left"> \
                                                <span class="todo-tasklist-date">Tweeted: '+ data.postDate + ' </span> \
                                                '+ hashtagadd + ' \
                                            </div> \
                                            <div class="pull-right"> \
                                                Clicks: '+ data.numClicks +' \
                                            </div> \
                                        </div>');
                $('#tweet-list').append(addTweet);
            });
            Metronic.unblockUI('#tweet-list');
        }
    });

};
jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
    Tasks.initDashboardWidget(); // init tash dashboard widget
    TableAjax.init();
    //5UINestable.init();

    tj.getTweets(1);
    $(document).on("click", "#save_campaign", function (event) {
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
                success: function (data) {
                    tj.campaign_grid.ajax.reload();
                    $('#add_campaign').modal('hide');

                }
            });
        }
    });

    $(document).on("click", "#save_changes", function (event) {
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
                success: function (data) {
                    tj.campaign_grid.ajax.reload();
                    $('#edit_campaign').modal('hide');

                }
            });
        }
    });


    tj.loadCampaign = function(campaignId) {
        $.ajax({
            url:'data.php?goc=1',
            data:{cid:campaignId},
            method:'post',
            dataType:'json',
            success:function(response) {
                $.each(response.data, function (index, value) {
                    $('#' + index).val(value);
                });
                $('#nestable_list_1').html(response.tweetlist);
                $('#nestable_list_2').html(response.tweetpool);
            }
        });
    }

    $('#edit_campaign').on('show.bs.modal', function (event) {
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

        invalidHandler: function (event, validator) { //display error alert on form submit   

        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function (error, element) {

            var name = element.attr("name");
            $("#error_" + name).append(error);
            //console.log(name);

            //else if (element.closest('.input-icon').size() === 1) {
            //    error.insertAfter(element.closest('.input-icon'));
            //} else {
            // error.insertAfter(element);
            //}
        },

        submitHandler: function (form) {
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

        invalidHandler: function (event, validator) { //display error alert on form submit   

        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').addClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label.closest('.form-group').removeClass('has-error');
            label.remove();
        },

        errorPlacement: function (error, element) {

            var name = element.attr("name");
            $("#error_" + name).append(error);
            //console.log(name);

            //else if (element.closest('.input-icon').size() === 1) {
            //    error.insertAfter(element.closest('.input-icon'));
            //} else {
            // error.insertAfter(element);
            //}
        },

        submitHandler: function (form) {
            form.submit();
        }
    });
    tj.removeCampaign = function (cid) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                $.ajax({
                    url: 'data.php?rc=1',
                    data: { cid: cid },
                    method: 'POST',
                    dataType:'json',
                    success: function (response) {
                        tj.campaign_grid.ajax.reload();
                        bootbox.alert("Campaign Removed");
                    }
                })

            }
        });

    };

tj.alex.archiveTweet = function(jobId) {
     bootbox.confirm("Are you sure?", function(result) {
            if (result) {
	 $.ajax({
         url: 'dataTest.php?archiveTweet=1',
         data: {
             jid: jobId
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

    tj.addJobTweet = function () {

        $.ajax({
            url: 'data.php?at=1',
            data: { message: $('#jobtweetmessage').val() },
            dataType: 'json',
            method: 'post',
            success: function (response) {
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
});tj.alex = {};tj.alex.faceBookGrid = {};tj.alex.test = function(){	console.log('test3333');};