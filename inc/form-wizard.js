var FormWizard = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            function format(state) {
                if (!state.id) return state.text; // optgroup
                return "<img class='flag' src='theme/assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
            }

            $("#country_list").select2({
                placeholder: "Select",
                allowClear: true,
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            var form = $('#add_campaign_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            $.validator.addMethod("check_budget_total", function (value, element, param) {
                var val_a = $("#campaign_budget_total").val();
                var val_b = $("#campaign_budget_daily").val();
                return (parseInt(val_b) <= parseInt(val_a));
            }, "Your daily budget must be less than or equal to your total budget.");

            $.validator.addMethod("check_budget_daily", function (value, element, param) {
                var val_a = $("#campaign_budget_total").val();
                var val_b = $("#campaign_budget_daily").val();
                return (parseInt(val_a) >= parseInt(val_b));
            }, "Your total budget must be greater than or equal to your daily budget.");

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",
                //onfocusout: false,
                onkeyup: false,
                //onclick: false,
                rules: {

                    campaign_name: {
                        required: true,
                        minlength:3
                    },
                    campaign_from: {
                        required: true,
                        date: true
                    },
                    campaign_to: {
                        required: true,
                        date: true
                    },
                    campaign_budget_click: {
                        required: true,
                        number: true,
                        min:0.20
                    },
                    campaign_budget_daily: {
                        required: true,
                        number: true,
                        min: 10,
                        check_budget_total: true
                    },
                    campaign_budget_total: {
                        required: true,
                        number: true,
                        min: 25,
                        remote: {
                            url: "data.php?cb=1",
                            async:false,
                            type: "post",
                            data: {
                                cct:1,
                                total: function() {
                                    return $( "#campaign_budget_total" ).val();
                                }
                            },
                            message: 'You need to enter an amount less than your current account balance.'
                        }
                    },
                    campaign_agree_terms: {
                        required: true
                    }
                },
                messages: {
                    campaign_budget_total: {
                        remote: 'Your current balance does not cover your total campaign budget.<br />Click <a href="account_billing.php">here</a> to add funds.'
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

            var displayConfirm = function() {
                $('#tab2 .form-control-static', form).each(function(){
                    var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                    if (input.is(":radio")) {
                        input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    } else if ($(this).attr("data-display") == 'payment[]') {
                        var payment = [];
                        $('[name="payment[]"]:checked', form).each(function(){ 
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            }

            var handleTitle = function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    displayConfirm();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    return false;
                    /*
                    success.hide();
                    error.hide();
                    if (form.valid() == false) {
                        return false;
                    }
                    handleTitle(tab, navigation, clickedIndex);
                    */
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    if (form.valid() == false) {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });

            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').click(function () {
                var outlist = new Array();
                window.onbeforeunload = null;
                $("#assign-list .availabletweet").each(function (i, k) { outlist.push($(k).val()) });
                $.ajax({
                    url: 'data.php?ac=1',
                    method: 'POST',
                    data: {
                        campaign_name: $('#campaign_name').val(),
                        campaign_from: $('#campaign_from').val(),
                        campaign_to: $('#campaign_to').val(),
                        campaign_budget_click: $('#campaign_budget_click').val(),
                        campaign_budget_daily: $('#campaign_budget_daily').val(),
                        campaign_budget_total: $('#campaign_budget_total').val(),
                        campaign_items: outlist

                    },
                    success: function (data) {
                        window.location = 'dashboard.php';
                    }
                });
            }).hide();


        }

    };

}();