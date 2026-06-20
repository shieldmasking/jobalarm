var assignStore = {};


 assignStore.assign = function(){
 	var userId = $('#assignStoreCombo').val();



 	$.ajax({
	        url: '/assignStoreHandler.php',
	        method: 'POST',
	        data: {
	            assignStore: true,
	            userId: userId,
	            storeId: assignStore.selectedId
	        },
	        success: function(data) {
	           $('#assignStoreModal').modal('hide');
	           tj.alex.allStoresGrid();

	        }
	    });

 };

assignStore.initialize = function(storeId){
	assignStore.selectedId = storeId;
	var combo = $('#assignStoreCombo');

	var length = assignToStoreUsers.length;
	var html = '';
	html += '<option value="-1">Assign this Location to:</option>';

	combo.empty();
	for(var i = 0; i < length; i++){
		html += '<option value="'+assignToStoreUsers[i].id+'">';
			html += assignToStoreUsers[i].last_name + ', ' + assignToStoreUsers[i].first_name;
		html += '</option>';
	}
	combo.append(html);

	$('#assignStoreModal').modal('show');

};