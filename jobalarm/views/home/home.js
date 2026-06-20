var wus = wus || {};

$(function () {
    $('#form').w2form({
        name: 'form',
        url: 'login',
        msgSaving: 'Loading...',
        fields: [
            { name: 'username', type: 'text', required: true },
            { name: 'password', type: 'password', required: true,  }
        ],
        actions: {
            login: function () {
                this.submit({}, function (data) {                    
                    if (data.status == 'success') {
                        window.location = 'dashboard';
                        return;
                    }
                });
            }
        }
    });
    $('#password').keypress(function (e) {
        if (e.which == 13) {
            w2ui.form.action('login');
            return false;
        }
    });
});