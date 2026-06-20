var tj = {};

jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
    Tasks.initDashboardWidget(); // init tash dashboard widget
    tj.hashtageditor = $('#hashtageditor').bootstrapDualListbox({
        nonSelectedListLabel: 'Available Hashtags',
        selectedListLabel: 'In Production',
        preserveSelectionOnMove: 'moved',
        moveOnSelect: true,
        moveAllLabel:false,
        nonSelectedFilter: ''
    });
    $('#save_hashtags_btn').click(function () {
        Metronic.blockUI({
            target: '#hashtageditbox',
            boxed: true
        });
        $.ajax({
            url: 'data.php?sh=1',
            data: { hashtags: $('#hashtageditor').val() },
            method: "post",
            success: function (data) {
                Metronic.unblockUI('#hashtageditbox');
                alert('Hashtags Saved.');
            }

        })
    });
});