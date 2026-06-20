var wus = wus || {};

$(function () {
    $('#form').w2form({
        name: 'form',
        url: 'users/register',
        msgSaving: 'Loading...',
        fields: [
            { name: 'company', type: 'text', required: true },
            { name: 'firstname', type: 'text', required: true },
            { name: 'lastname', type: 'text', required: true },
            { name: 'phone', type: 'text', required: true },
            { name: 'email', type: 'email', required: true },
            { name: 'password', type: 'text', required: true },
            { name: 'password_cfm', type: 'text', required: true }
        ],
        actions: {
            register: function () {
                //this.record['g-recaptcha-response'] = $('#g-recaptcha-response').val();
                if (this.record['password'] != this.record['password_cfm']) {
                    $('#password_cfm').w2tag(w2utils.lang("Passwords do not match!"));
                } else {
                    this.submit({}, function (data) {                    
                        if (data.status == 'success') {
                            w2alert('User successfully created. Please check your email for login instructions.', 'Account Created', function () {
                                window.location = 'home';
                            });
                            return;
                        }
                    });
                }
            }
        }
    });
    
});