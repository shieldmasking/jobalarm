var wus = wus || {};

$(function () {
    $('#form').w2form({
        name: 'form',
        url: 'users/forgot',
        msgSaving: 'Sending...',
        fields: [
            { name: 'emailaddy', type: 'text', required: true }
        ],
        actions: {
            resetpw: function () {
                this.submit({}, function (data) {
                    if (data.status == 'success') {
                        w2alert('We have sent you your password.  Please check your inbox for details.', 'System', function () {
                            window.location = 'home';
                        });
                        return;
                    } else {
                        w2alart(data.msg);
                    }
                });
            }
        }
    });
    $('#emailaddy').keypress(function (e) {
        if (e.which == 13) {
            $('#resetpw').focus();
            $('#resetpw').click();
            return false;
        }
    });
});