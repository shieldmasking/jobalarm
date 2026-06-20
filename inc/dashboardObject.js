var dashboard = {};


dashboard.addstore = function() {
    // alex add store button handler
   // var myWindow = window.open("addstore.php", "", "width=400, height=600");

	$('#brand').off().change(dashboard.validateInputs);
	$('#storeNum').off().change(dashboard.validateInputs);;
	$('#address').off().change(dashboard.validateInputs);;
	$('#city').off().change(dashboard.validateInputs);;
	$('#state').off().change(dashboard.validateInputs);;
	$('#zipcode').off().change(dashboard.validateInputs);;
	$('#assign').off().change(dashboard.validateInputs);;

dashboard.initForm();
$('#dashboardModal').modal('show');
};

dashboard.validateInputs = function(){
	console.log('validate inputs');

	var brand = $('#brand').val();
	var storeNum = $('#storeNum').val();
	var address = $('#address').val();
	var city = $('#city').val();
	var state = $('#state').val();
	var zipcode = $('#zipcode').val();
	var assign = $('#assign').val();


	if(brand != -1&& address != ''&& city != ''&& state != ''&& zipcode != ''){
		$('#postStoreButton').prop("disabled",false);
	}else{
		$('#postStoreButton').prop("disabled",true);
	}
};

dashboard.initForm = function(){
	$('#postStoreButton').prop("disabled",true);
	var brandCombo = $('#brand');
	var userCombo = $('#assign');


	var length = addStoreBrands.length;
	var html = '';
	html += '<option value="-1">Select a Brand:</option>';

	brandCombo.empty();
	for(var i = 0; i < length; i++){
		html += '<option value="'+addStoreBrands[i].brandId+'">';
			html += addStoreBrands[i].storeBrand;
		html += '</option>';
	}
	brandCombo.append(html);

	html = '';
	length = addStoreUsers.length;
	html += '<option value="-1">Select a User:</option>';
	userCombo.empty();
	for(var i = 0; i < length; i++){
		html += '<option value="'+addStoreUsers[i].id+'">';
			html += addStoreUsers[i].last_name + ', ' + addStoreUsers[i].first_name;
		html += '</option>';
	}
	userCombo.append(html);
	
};

 dashboard.postSuccess = function(){
 	$('#brand').val(-1);
	$('#storeNum').val(null);
	$('#address').val(null);
	$('#city').val(null);
	$('#state').val(null);
	$('#zipcode').val(null);
	$('#assign').val(-1);

	
	//$('#dashboardModal').hide();
	var confirmResult = confirm('Would you like to add another store?');

	if(confirmResult == true){
		//$('#dashboardModal').hide();
		dashboard.addstore();
	}else{
		$('#dashboardModal').modal('hide');
		tj.alex.initializejobGrid();
    	tj.alex.allStoresGrid();
	}
 };


dashboard.PostStore = function(){

	var brand = $('#brand').val();
	var storeNum = $('#storeNum').val();
	var address = $('#address').val();
	var city = $('#city').val();
	var state = $('#state').val();
	var zipcode = $('#zipcode').val();
	var assign = $('#assign').val();

	$.ajax({
	        url: '/addStoreHandler.php',
	        method: 'POST',
	        data: {
	            addstore: true,
	            brand: brand,
	            storeNum: storeNum,
	            address: address,
	            city: city,
	            state: state,
	            zipcode: zipcode,
	            assign: assign
	        },
	        success: function(data) {
	           dashboard.postSuccess();

	        }
	    });


};