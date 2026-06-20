var jobdashboard = {};


jobdashboard.addjob = function() {
    // alex add store button handler
   // var myWindow = window.open("addstore.php", "", "width=400, height=600");

	$('#jobbrand').off().change(jobdashboard.validateInputs);
	$('#jobstoreNum').off().change(jobdashboard.validateInputs);;
	$('#jobtitle').off().change(jobdashboard.validateInputs);;
	//$('#jobdescription').off().change(jobdashboard.validateInputs);;
	

jobdashboard.initForm();
$('#addJobModal').modal('show');
};

jobdashboard.validateInputs = function(){
	console.log('validate inputs');

	var jobbrand = $('#jobbrand').val();
	var jobstoreNum = $('#jobstoreNum').val();
	var jobtitle = $('#jobtitle').val();
	//var jobdescription = $('#jobdescription').val();


	if(jobbrand != -1&& jobstoreNum != -1&& jobtitle != ''){
		$('#addJobButton').prop("disabled",false);
	}else{
		$('#addJobButton').prop("disabled",true);
	}
};

jobdashboard.initForm = function(){
	$('#addJobButton').prop("disabled",true);
	var brandCombo = $('#jobbrand');
	var userCombo = $('#jobstoreNum');


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
	length = addStoreLocation.length;
	html += '<option value="-1">Select a Location:</option>';
	userCombo.empty();
	for(var i = 0; i < length; i++){
		html += '<option value="'+addStoreLocation[i].id+'">';
			html += addStoreLocation[i].st + ', ' + addStoreLocation[i].city + ': ' + addStoreLocation[i].address;
		html += '</option>';
	}
	userCombo.append(html);
	
};

 jobdashboard.jobSuccess = function(){
 	$('#jobbrand').val(-1);
	$('#jobstoreNum').val(-1);
	$('#jobtitle').val(null);
	//$('#jobdescription').val(null);

	
	//$('#dashboardModal').hide();
	var confirmResult = confirm('Would you like to add another job?');

	if(confirmResult == true){
		//$('#dashboardModal').hide();
		jobdashboard.addjob();
	}else{
		$('#addJobModal').modal('hide');
		$("#datatableATSx_ajax").DataTable();
        $('#CompJobsCell').show();
		//tj.alex.initializejobGrid();
    	//tj.alex.allStoresGrid();
	}
 };


jobdashboard.PostJob = function(){

	var jobbrand = $('#jobbrand').val();
	var jobstoreNum = $('#jobstoreNum').val();
	var jobtitle = $('#jobtitle').val();
	//var jobdescription = $('#jobdescription').val();
	

	$.ajax({
	        url: '/addJobHandler.php',
	        method: 'POST',
	        data: {
	            addjob: true,
	            jobbrand: jobbrand,
	            jobstoreNum: jobstoreNum,
	            jobtitle: jobtitle
	        },
	        success: function(data) {
	           jobdashboard.jobSuccess();

	        }
	    });


};