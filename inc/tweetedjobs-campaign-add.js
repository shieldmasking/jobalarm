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
tj.getTweets = function (page) {
    page = page - 1;
    Metronic.blockUI({
        target: '#tweet-list',
        boxed: true
    });

    $.ajax({
        url: 'data.php?gnct=1',
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
                                                    ' + d.text + ' \
                                                </span> ';
                });
                var addTweet = $(' \
                                        <div class="todo-tasklist-item todo-tasklist-item-border-green"> \
                                            <input type="hidden" class="availabletweet" name="add_to_list[]" value="'+ data.id +'" /> \
                                            <img class="todo-userpic pull-left" src="'+ data.rawData.user.profile_image_url + '" width="27px" height="27px" /> \
                                            <div class="todo-tasklist-item-title"> \
                                                '+ data.userName + ' \
                                            </div> \
                                            <div class="todo-tasklist-item-text"> \
                                                ' + data.text + ' \
                                            </div> \
                                            <div class="todo-tasklist-controls pull-left"> \
                                                <span class="todo-tasklist-date">Tweeted: '+ data.postDate + ' </span> \
                                                '+ hashtagadd + ' \
                                            </div> \
                                        </div>');
                $('#tweet-list').append(addTweet);
            });
            Metronic.unblockUI('#tweet-list');
            $('#tweet-list .todo-tasklist-item').click(function () {
                if ($(this).parent().attr('id') == 'tweet-list') {
                    $(this).detach().appendTo('#assign-list');
                } else {
                    $(this).detach().appendTo('#tweet-list');
                }
            });
        }
    });

};
jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    FormWizard.init();
    tj.getTweets(1);
    $('.input-daterange').datepicker({ todayBtn: 'linked', autoclose: true, minDate: new Date(), setDate: new Date() });

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


//    tj.ecfv = $('#edit_campaign_form').validate();
});