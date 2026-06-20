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

            var form = $('#edit_campaign_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);
            $.validator.addMethod("check_budget_total", function (value, element, param) {
                var val_a = $("#campaign_budget_total").val();

                return this.optional(element)
                    || (value <= val_a);
            }, "Your daily budget must be less than or equal to your total budget.");

            $.validator.addMethod("check_budget_daily", function (value, element, param) {
                var val_a = $("#campaign_budget_daily").val();

                return this.optional(element)
                    || (value >= val_a);
            }, "Your total budget must be greater than or equal to your daily budget.");

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {

                   
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
                $('#tab1 .form-control-static', form).each(function(){
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
            $('#form_wizard_1 .button-submit').click(function () {
                var outlist = new Array();
                window.onbeforeunload = null;
                $("#assign-list .availabletweet").each(function (i, k) { outlist.push($(k).val()) });
                if (outlist.length > 1) {
                    bootbox.alert("Your Trial Account only allows 1 sponsored job tweet.");
                } else
                $.ajax({
                    url: 'data.php?ec=1',
                    method: 'POST',
                    data: {
                        campaign_id: tj.campaignId,
                        campaign_items: outlist

                    },
                    success: function (data) {
                        window.location = 'dashboard.php';
                    }
                });
            });


        }

    };

}();