var tj = {};

jQuery(document).ready(function () {
    Metronic.init(); // init metronic core componets
    Layout.init(); // init layout
    Demo.init(); // init demo(theme settings page)
    Index.init(); // init index page
    Tasks.initDashboardWidget(); // init tash dashboard widget

});

tj.prevalidatePayment = function () {
    var amount = $('#payment_amount').val();
    if (amount < 25.00 || !$.isNumeric(amount)) {
        $('#payment_amount').css('border','1px solid #f00');
        $('#payment_amount').tooltip({ trigger: 'manual', placement: 'top', title: '$25.00 Minimum Required' });
        $('#payment_amount').tooltip('show');
        //event.preventDefault();
    } else {
        if ($('#coupon_code').val() != '') {
            $('#paypal_custom').val($('#paypal_custom').val() + '|' + $('#coupon_code').val());
            //console.log($('#paypal_custom').val());
        }
        $('#payment_amount').css('border', '1px solid #333');
        $('#payment_amount').tooltip('hide');
        window.add_funds_form.submit();
    }
}