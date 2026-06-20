var addUsers = {};


addUsers.initialize = function(){
	console.log('init');

	var role = $('#role');
	var firstName = $('#first_name');
	var lastName = $('#last_name');
	var email = $('#email');

	role.val(1);
	firstName.val(null);
	lastName.val(null);
	email.val(null);


	$('#addUsersModal').modal('show');
};



addUsers.add = function(){
	console.log('add');


	var confirmResult = confirm('Are you sure you want to add this user?');

	if(confirmResult == true){
		addUsers.postUser();

	}else{
		// do nothing or call addUsers.initialize to clear the form and start over.
	}
};

addUsers.postSuccess = function(){
	var role = $('#role');
	var firstName = $('#first_name');
	var lastName = $('#last_name');
	var email = $('#email');

	role.val(1);
	firstName.val(null);
	lastName.val(null);
	email.val(null);

	$('#addUsersModal').modal('hide');
	tj.alex.initializeuserGrid();
};

addUsers.postUser = function(){
	var role = $('#role').val();
	var firstName = $('#first_name').val();
	var lastName = $('#last_name').val();
	var email = $('#email').val();

	$.ajax({
	        url: '/addUsersHandler.php',
	        method: 'POST',
	        data: {
	            adduser: true,
	            role: role,
	            firstName: firstName,
	            lastName: lastName,
	            email: email
	        },
	        success: function(data) {
	           addUsers.postSuccess();

	        }
	    });

};