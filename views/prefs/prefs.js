$(function () {
    var pstyle = 'border: 1px solid #dfdfdf; padding: 5px; margin: 5px';

    wus.UserDetailsFormCFG =
        '<table id="user_details_form" width="100%" border="0" style="border-collapse:collapse"> \
        <tr><td><h2 style="font-size:16px">Change Password</h2></td></tr>\
        <tr> \
            <td><label for="currentPass">Current Password</label> \
            <input type="password" name="currentPass" id="currentPass" /></td> \
        </tr> \
        <tr> \
            <td><label for="newPass">New Password</label> \
            <input type="password" name="newPass" id="newPass" /></td> \
        </tr> \
        <tr> \
            <td><label for="newPassCfm">Confirm Password</label> \
            <input type="password" name="newPassCfm" id="newPassCfm" /></td> \
        </tr> \
        </table>\
	<div class="w2ui-buttons"> \
		<button class="btn" name="reset">Reset</button> \
		<button class="btn" name="save">Save</button> \
	</div> \
    ';

    wus.UserDetailsForm = {
        name: 'UDForm',
        msgRefresh: 'Loading Survey Details...',
        formHTML: wus.UserDetailsFormCFG,
        url:'users/changePass',
        fields: [
          { name: 'currentPass', type: 'password', required:true },
          { name: 'newPass', type: 'password', required:true },
          { name: 'newPassCfm', type: 'password', required:true }
        ],
        actions: {
            reset: function () {
                this.clear();
            },
            save: function () {
                var obj = this;
                var newPass = $(this.get('newPass').el).val();
                var newPassCfm = $(this.get('newPassCfm').el).val();

                if (newPass != newPassCfm) {
                    this.error('Password confirmation failed.');
                    this.validate(true);
                    return;
                }
                this.save({}, function (data) {
                    if (data.status == 'error') {
                        //w2alert(data.message);
                        return;
                    }

                    obj.clear();
                    w2alert(data.message);
                });
            }
        }
    };

    $().w2form(wus.UserDetailsForm);
    $('#layout').w2layout({
        name: 'layout',
        panels: [
            { type: 'top', size: 50, style: pstyle, content: '<h1>User Preferences</h1>' },
            //{ type: 'left', size: 200, style: pstyle, content: 'left' },
            { type: 'main', style: pstyle, content: 'main', content:w2ui.UDForm }
        ]
    });
});