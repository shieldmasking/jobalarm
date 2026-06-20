var tj = {};

jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    FormWizard.init();
 
    $('#promocode_to').datepicker({ startDate: '+1d', todayBtn: 'linked', autoclose: true });

    $(document).on("click", "#save_promocode", function (event) {
        //console.log($('#add_promocode_form').valid());

        if ($('#add_promocode_form').valid()) {
            $.ajax({
                url: 'data.php?ac=1',
                method: 'POST',
                data: {
                    promocode_name: $('#promocode_name').val(),
                    promocode_desc: $('#promocode_desc').val(),
                    promocode_to: $('#promocode_to').val()
                },
                success: function (data) {
                    tj.promocode_grid.ajax.reload();
                    $('#add_promocode').modal('hide');

                }
            });
        }
    });

    $(document).on("click", "#save_changes", function (event) {
        //console.log($('#add_promocode_form').valid());

        if ($('#edit_promocode_form').valid()) {
            $('#edit_promocode_jobs').val(window.JSON.stringify($('#nestable_list_1').nestable('serialize')));
            $.ajax({
                url: 'data.php?ec=1',
                method: 'POST',
                data: {
                    promocode_id: tj.currentPromocode,
                    promocode_name: $('#edit_promocode_name').val(),
                    promocode_desc: $('#edit_promocode_desc').val(),
                    promocode_to: $('#edit_promocode_to').val()
                },
                success: function (data) {
                    tj.promocode_grid.ajax.reload();
                    $('#edit_promocode').modal('hide');

                }
            });
        }
    });


    $('#edit_promocode').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var promocodeId = button.data('id');
        var modal = $(this)
        tj.currentPromocode = promocodeId;
        tj.loadPromocode(promocodeId);
    });


//    tj.ecfv = $('#edit_promocode_form').validate();
});