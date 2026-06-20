var tj = tj || {};

/////////////////////////////////////
// GLOBAL VARIABLE INITIALIZATION
tj.defaultTab = "#reports";
tj.currentTab = tj.defaultTab;
tj.reportsLoaded = false;
tj.locationsLoaded = false;
tj.candidatesLoaded = false;
tj.recruiterLoaded = false;
tj.smsInboxLoaded = false;
tj.usersLoaded = false;
tj.jobsLoaded = false;

tj.debug = true;    // allow debug console messages


/////////////////////////////////////
// LOAD REPORTS PAGE
tj.loadReports = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[1]);
    $('#reportsView').show();
    if (!tj.reportsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#reportsView').load('views/reports.php', {}, function () {
            tj.createReportCharts();
            tj.daterangepickerInit();
			tj.initSupportGrid();
            tj.reportsLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD LOCATIONS PAGE
tj.loadLocations = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[2]);
    $('#locationsView').show();
    if (!tj.locationsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#locationsView').load('views/locations.php', {}, function () {
            tj.initializeLocationsGrid('');
            tj.initializeJobsGrid('');
			tj.initializeUsersGrid('');
            tj.locationsLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD LOCATIONS PAGE
tj.loadJobs = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[3]);
    $('#jobsView').show();
    if (!tj.jobssLoaded) {
        tj.startLoading('Loading...');
        jQuery('#jobsView').load('views/jobs.php', {}, function () {
            tj.initializeAllJobsGrid('');
			tj.jobssLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD CANDIDATES PAGE
tj.loadCandidates = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[4]);
    $('#candidatesView').show();
    if (!tj.candidatesLoaded) {
        tj.startLoading('Loading...');
        jQuery('#candidatesView').load('views/candidates.php', {}, function () {
            if (tj.urlParams['zip'] != undefined && tj.urlParams['zip'].trim() != '') {
                tj.candidatesZipCode=tj.urlParams['zip'];
                $('#candidate_search_zipcode').val(tj.candidatesZipCode);
            }
            tj.initializeCandidatesGrid('');
            tj.candidatesLoaded = true;
            tj.stopLoading();
        });
    } else {
        if (tj.urlParams['zip'] != undefined && tj.urlParams['zip'].trim() != '') {
            $("#candidate_search_form")[0].reset();
            tj.candidatesTable.search('');
            tj.candidatesZipCode=tj.urlParams['zip'];
            $('#candidate_search_zipcode').val(tj.candidatesZipCode);
            tj.candidatesTable.ajax.reload();
        }
    }
};

/////////////////////////////////////
// LOAD RECRUITER PAGE
tj.loadRecruiter = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[7]);
	$('#recruiterView').show();
    if (!tj.recruiterLoaded) {
        tj.startLoading('Loading...');
        jQuery('#recruiterView').load('views/recruiter.php', {}, function () {
            if (tj.urlParams['zip'] != undefined && tj.urlParams['zip'].trim() != '') {
                tj.recruiterZipCode=tj.urlParams['zip'];
                //$('#recruiter_search_zipcode').val(tj.recruiterZipCode);
            }
			console.log('updating');
			tj.initializeRecruiterGrid('');
            tj.recruiterLoaded = true;
			tj.stopLoading();
        });
    } else {
        if (tj.urlParams['zip'] != undefined && tj.urlParams['zip'].trim() != '') {
            //$("#recruiter_search_form")[0].reset();
            //tj.recruiterTable.search('');
            tj.recruiterZipCode=tj.urlParams['zip'];
            //$('#recruiter_search_zipcode').val(tj.recruiterZipCode);
            tj.recruiterTable.ajax.reload();
        }
    }
};
/////////////////////////////////////
// LOAD SMSINBOX PAGE
tj.loadSMSInbox = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[5]);
    $('#smsinboxView').show();
    if (!tj.smsInboxLoaded) {
        tj.startLoading('Loading...');
        jQuery('#smsinboxView').load('views/smsinbox.php', {}, function () {
            tj.initializeSmsGrid();
            tj.smsInboxLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadUsers = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[6]);
    $('#usersView').show();
    if (!tj.usersLoaded) {
        tj.startLoading('Loading...');
        jQuery('#usersView').load('views/users.php', {}, function () {
			tj.initializeUserGrid('');
			tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.usersLoaded = true;
            tj.stopLoading();
        });
    }
};


/////////////////////////////////////
// LOAD PROFILE PAGE
tj.loadProfile = function() {
    tj.asideMenu.setActiveItem();
    $('#profileView').show();
    tj.startLoading('Loading...');
    jQuery('#profileView').load('views/profile.php', {}, function () {
        tj.stopLoading();
    });
};

/////////////////////////////////////
// LOAD SUPPORT PAGE
tj.loadSupport = function() {
    tj.asideMenu.setActiveItem();
    $('#supportView').show();
    tj.startLoading('Loading...');
    jQuery('#supportView').load('views/support.php', {}, function () {
        tj.stopLoading();
    });
};

/////////////////////////////
//LOAD SUPPORT MODAL
tj.initSupportGrid = function(){
  $('#supportModalSubmitButton').click(function(){
	var ValidStatus = $("#supportmodal").valid();
    console.log(ValidStatus);
	tj.submitSupport();
    if (ValidStatus == false) {
        return false;
    }
    
  });
};



/*

______ ___________ ___________ _____ _____    ______  ___  _____  _____
| ___ \  ___| ___ \  _  | ___ \_   _/  ___|   | ___ \/ _ \|  __ \|  ___|
| |_/ / |__ | |_/ / | | | |_/ / | | \ `--.    | |_/ / /_\ \ |  \/| |__
|    /|  __||  __/| | | |    /  | |  `--. \   |  __/|  _  | | __ |  __|
| |\ \| |___| |   \ \_/ / |\ \  | | /\__/ /   | |   | | | | |_\ \| |___
\_| \_\____/\_|    \___/\_| \_| \_/ \____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// CREATE NEW CANDIDATE REPORT OBJECT
tj.buildNewCandidateChart = function(id){
    var ctx = document.getElementById(id);
    tj.newCandidateReport = new Chart(ctx, {
        type: 'bar',
        data: {},
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }
                
                        },
                    }
                }],
            },
            responsive:true,
            legend: {
                display:false
            }
        }
    });
}

/////////////////////////////////////
// CREATE OUTGOING MSG REPORT OBJECT
tj.buildOutgoingMsgsChart = function(id){
    var ctx = document.getElementById(id);
    tj.outgoingMsgReport = new Chart(ctx, {
        type: 'bar',
        data: {},
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }
                
                        },
                    }
                }],
            },
            responsive:true,
            legend: {
                display:false
            }
        }
    });
}

/////////////////////////////////////
// CREATE REPORTS PAGE REPORT GRAPHS
tj.createReportCharts = function() {
    tj.buildNewCandidateChart("new_candidates_chart");
    tj.buildOutgoingMsgsChart("outgoing_messages_chart");
}

/////////////////////////////////////
// GET REPORT DATA
tj.getReportData = function(start,end,callback) {
    $.ajax({
        url:'inc/data.php?req=getReports',
        data:{
            start:start,
            end:end
        },
        method:'POST',
        dataType:'json',
        success:function(response) {
            if (tj.debug) console.log(response);
            if (typeof(callback) == 'function') {
                callback(response.data);
            }
        }
    })
}

/////////////////////////////////////
// DOWNLOAD CSV
tj.downloadCSV = function() {
    $.ajax({
        url:'inc/data.php?req=downloadcsv',
        data:{},
        method:'POST',
        dataType:'json',
        success:function(response) {
            console.log(response);
            
        }
    })
}

/////////////////////////////////////
// UPDATE REPORTS PAGE DATE
tj.updateReports = function(start,end) {
    if (tj.debug) {
        console.log('updating reports',start,end);
    }
    tj.reportDates = {
        start:start,
        end:end
    }
    tj.getReportData(start,end,function(data){
        if (tj.debug) console.log('updateReports - getReportData',data)
        var color = Chart.helpers.color;
        $('#reportPromoNumber').html(data.totalPromo);
		$('#rewardNumber').html(data.totalReward);
        $('#reportMsgNumber').html(data.totalMsg);
        $('#reportCanNumber').html(data.totalCan);
        $('#reportCtrNumber').html(data.totalCTR);
        tj.newCandidateReport.data.labels = data.canGraphData.labels;
        tj.newCandidateReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.blue).alpha(0.5).rgbString(),
            data:data.canGraphData.data
        }];
        tj.newCandidateReport.update();
    
        tj.outgoingMsgReport.data.labels = data.smsGraphData.labels;
        tj.outgoingMsgReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.green).alpha(0.5).rgbString(),
            data:data.smsGraphData.data
        }];
        tj.outgoingMsgReport.update();
    })
}

/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.daterangepickerInit = function() {
    if ($('#m_dashboard_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#m_dashboard_daterangepicker');
    var start = moment().subtract('days', 29);
    var end = moment();

    tj.reportDates = {
        start:start,
        end:end
    }

    function sameDay(d1,d2) {
        return d1.getFullYear() === d2.getFullYear() &&
            d1.getMonth() === d2.getMonth() &&
            d1.getDate() === d2.getDate();
    }

    function cb(start, end, label) {
        var title = '';
        var range = '';
        if (label == 'Today' && sameDay(start.toDate(),end.toDate())) {
            title = 'Today:';
            range = start.format('MMM D');
        } else if (label == 'Yesterday') {
            title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-subheader__daterange-date').html(range);
        picker.find('.m-subheader__daterange-title').html(title);
        if (tj.debug) {
            console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        }
        tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }

    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);

    cb(start, end, '');
};


/*

 _     _____ _____   ___ _____ _____ _____ _   _  _____    ______  ___  _____  _____
| |   |  _  /  __ \ / _ \_   _|_   _|  _  | \ | |/  ___|   | ___ \/ _ \|  __ \|  ___|
| |   | | | | /  \// /_\ \| |   | | | | | |  \| |\ `--.    | |_/ / /_\ \ |  \/| |__
| |   | | | | |    |  _  || |   | | | | | | . ` | `--. \   |  __/|  _  | | __ |  __|
| |___\ \_/ / \__/\| | | || |  _| |_\ \_/ / |\  |/\__/ /   | |   | | | | |_\ \| |___
\_____/\___/ \____/\_| |_/\_/  \___/ \___/\_| \_/\____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// LOCATIONS GLOBALS
tj.locationsStoreID = 0;


/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.initializeLocationsGrid = function(id) {
    tj.locationsTable = $('#locationsTable').DataTable( {
        "ajax": "inc/data.php?req=getLocations",
        "order": [[2,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading your Locations...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "StoreBrand" },
            { "data": "Address" },
            { "data": "CandidateLink" }
        ]
    } );
/////////////////////////////////////
// EDIT LOCATION

tj.editLocation = function(storeId, address, city, state, zip, name, storeNum) {
        
        $.ajax({
            url:'inc/data.php?req=getLocationDetails',
            data:{
                storeId:storeId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#ecf_location_state').val(response.data.st);
				$('#ecf_location_num').val(response.data.storeNum);
                $('#ecf_location_address').val(response.data.address);
                $('#ecf_location_city').val(response.data.city);
                $('#ecf_location_zip').val(response.data.zip);
				$('#ecf_location_email').val(response.data.cc);
				$('#location_id').val(storeId);
				$('#locName').html(response.data.storeBrand+'('+response.data.storeNum+')');
				$('#locStreet').html(response.data.address);
				$('#locCSZ').html(response.data.city+', '+response.data.st+' '+response.data.zip);
				$('#locationsTable').DataTable().search('').draw();
                $('#edit_location').modal('show');
				}
        })
        console.log(storeId);
    }
	
	tj.updateLocation = function() {
		//tj.editLocationTarget=storeId;
		//console.log('storeId',storeId);
        $.ajax({
            url:'inc/data.php?req=updateLocation',
            data:{
                storeNum:$('#ecf_location_num').val(),
                address:$('#ecf_location_address').val(),
                city:$('#ecf_location_city').val(),
                state:$('#ecf_location_state').val(),
                zip:$('#ecf_location_zip').val(),
				cc:$('#ecf_location_email').val(),
				storeId:$('#location_id').val()
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.locationsTable.ajax.reload();
				alert('Location updated successfully.');
                $('#edit_location').modal('hide');
				//ecf_cand_first_name
            }
        })
    }
	
	tj.deleteLocation = function() {
		var storeId = $('#location_id').val();
		bootbox.confirm({
        message:"Delete this location?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=deleteLocation',
            data:{
                storeId:storeId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {				
				$('#edit_location').modal('hide');
				tj.locationsTable.ajax.reload();
				//ecf_cand_first_name
            }
        });
	  }
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
};
	
tj.addLocation = function () {
        
		var brand = $('#ecf_newlocation_brand').val();
        var storeNum = $('#ecf_newlocation_num').val();
		var address = $('#ecf_newlocation_address').val();
		var city = $('#ecf_newlocation_city').val();
		var state = $('#ecf_newlocation_state').val();
		var zip = $('#ecf_newlocation_zip').val();
		var user = $('#ecf_newlocation_assign').val();
		
        if (tj.debug) console.log('add new location');
        
        $.ajax({
            url: 'inc/data.php?req=addLocation',
            data: {
				brand: brand,
                storeNum: storeNum,
                address: address,
				city: city,
				state: state,
				zip: zip,
				user: user
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                //$('#cdn'+candidateId).DataTable().ajax.reload();
				tj.locationsTable.ajax.reload();
				tj.usersTable.ajax.reload();
				alert('Location added successfully.');
				document.getElementById("ecf_newlocation_brand").selectedIndex = "";
				document.getElementById("ecf_newlocation_num").value = "";
				document.getElementById("ecf_newlocation_address").value = "";
				document.getElementById("ecf_newlocation_city").value = "";
				document.getElementById("ecf_newlocation_zip").value = "";
				document.getElementById("ecf_newlocation_state").selectedIndex = "";
				document.getElementById("ecf_newlocation_assign").selectedIndex = "";
                $('#addnewLocation').modal('hide');
            }
        });

		
}
	
	
	}

/////////////////////////////////////
// INITIALIZE THE LOCATION JOBS TABLE
tj.initializeJobsGrid = function(id) {
    tj.jobsTable = $('#jobsTable').DataTable( {
        "ajax": {
            "type"  :   "POST",
            "url"   :   "inc/data.php?req=getJobs",
            "data"  :   function(d) {
                d.storeId = tj.locationsStoreID;
            }
        },
		"order": [[2,'asc']],
        processing: true,
        deferLoading: 0,
        "language": {
            "processing": 'Loading Jobs...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Position" },
            { "data": "Source" },
            { "data": "Status" }
        ]
    } );
	
/////////////////////////////////////
// LOAD THE JOBS MODAL
tj.showLocationJobs = function(storeId, address, city, state, zip, name, storeNum) {
    $('#jobName').html(name);
    $('#jobStreet').html(address);
    $('#jobCSZ').html(city+', '+state+' '+zip);
    
    tj.locationsStoreID = storeId;
    
    tj.jobsTable.ajax.reload();
}

/////////////////////////////////////
// ADD POSITION TO LOCATION
tj.addJob = function() {
   if (tj.debug) console.log('current location: ',tj.locationsStoreID);
   $.ajax({
       url:'inc/data.php?req=addJob',
       data: {
           location: tj.locationsStoreID,
           position: $('#add_job_input_position').val()
       },
       success:function(data) {
		   $('#add_job_modal').modal('toggle');
		   document.getElementById("add_job_input_position").value = "";
           alert('Job added successfully.');
           console.log('position added');
           tj.jobsTable.ajax.reload();
       }
   })
}
}

/////////////////////////////////////
// INITIALIZE THE LOCATION JOBS TABLE
tj.initializeAllJobsGrid = function() {
    tj.alljobsTable = $('#alljobsTable').DataTable( {
        "ajax": {
            "type"  :   "POST",
            "url"   :   "inc/data.php?req=getallJobs",
            "data"  :   function() {
                
            }
        },
		"order": [[5,'asc']],
        processing: true,
        //deferLoading: 0,
        "language": {
            "processing": 'Loading Jobs...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
		"columnDefs": [
		{"targets": [ 5 ],
		"visible": false
		}
		],
        "columns": [
            { "data": "Title" },
			{ "data": "Location" },
			{ "data": "Desc" },
			{ "data": "Date" },
			{ "data": "Status" },
			{ "data": "Zip"}
        ]
    } )
}

////////Edit Job

    tj.editallJob = function(jobId) {
        tj.editJobTarget=jobId;
        $.ajax({
            url:'inc/data.php?req=getJobDetails',
            data:{
                jobId:jobId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                //ecf_cand_first_name
                $('#all_job_title').val(response.data.title);
                $('#all_job_desc').val(response.data.text);
				$('#job_cand_city').val(response.data.city);
				$('#job_cand_state').val(response.data.state);
				$('#job_cand_zip').val(response.data.zipCode);
				$('#jobLink').html('<a href="'+response.data.urls+'" target="_blank">'+response.data.title+': '+response.data.city+', '+response.data.state+'</a>');
                $('#edit_all_job').modal('show');
            }
        })
        console.log(jobId);
    }

tj.uploadjobs = function(){
    event.preventDefault();
     var form = $("#uploadForm")[0];
     var data = new FormData(form);
    // AJAX request
    $.ajax({
      url: 'inc/data.php?req=uploadfile',
      type: 'post',
      data: data,
      contentType: false,
      processData: false,
      success: function(response){
        if(response.message == true){
			$('#uploadModal').modal('toggle');
			document.getElementById("file").value = "";
			alert('File upload successful.  Your jobs will update overnight.');
        }else if(response.message == false && response.error==true){
			alert('Upload Error.  Please contact your JobAlarm Representative for upload assistance.');
        }else{
			alert('Upload Error.  File is not a CSV filetype.  Please contact your JobAlarm Representative for upload assistance.');
		}
      }
	  
    });
}
	
///////////UPDATE ALL JOB	
    tj.updateallJob = function(jobId) {
		var title = $('#all_job_title').val();
		var desc = $('#all_job_desc').val();
		var zip = $('#job_cand_zip').val();
		var currentdate = new Date();
		var d = currentdate.getFullYear() + "-"
				+ (currentdate.getMonth()+1)  + "-" 
				+ currentdate.getDate() + " "
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
		console.log('date',d);
		if(title.length == 0){
		bootbox.alert('Job Title is required');
		return;
		}
		if(desc.length == 0){
		bootbox.alert('Job Description is required');
		return;
		}
		if(zip.length <5){
		bootbox.alert('Zip Code is required');
		return;
		}
        $.ajax({
            url:'inc/data.php?req=updateallJob',
            data:{
                jobId:jobId,
                title:$('#all_job_title').val(),
                desc:$('#all_job_desc').val(),
				city:$('#job_cand_city').val(),
				state:$('#job_cand_state').val(), 
				zip: zip,
				d: d
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                $('#edit_all_job').modal('hide');
				alert('Job update successfully.');
                tj.alljobsTable.ajax.reload(null,false);
            }
        })
    }

/////////////////////////////////////
// INITIALIZE THE USERS TABLE
tj.initializeUsersGrid = function(id) {
    tj.usersTable = $('#usersTable').DataTable( {
        "ajax": {
            "type"  :   "POST",
            "url"   :   "inc/data.php?req=getUsers",
            "data"  :   function(d) {
                d.storeId = tj.locationsStoreID;
            }
        },
        processing: true,
        deferLoading: 0,
        "language": {
            "processing": 'Loading Jobs...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "User" },
            { "data": "Role" },
            { "data": "Action" }
        ]
    } );
}



/////////////////////////////////////
// LOAD THE USERS MODAL
tj.showLocationUsers = function(storeId, address, city, state, zip, name, storeNum) {
    $('#userName').html(name);
    $('#userStreet').html(address);
    $('#userCSZ').html(city+', '+state+' '+zip);
    
    tj.locationsStoreID = storeId;
    
    tj.usersTable.ajax.reload();
}

////////Edit Job

    tj.editJob = function(jobId) {
        tj.editJobTarget=jobId;
        $.ajax({
            url:'inc/data.php?req=getJobDetails',
            data:{
                jobId:jobId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                //ecf_cand_first_name
                $('#ecf_job_title').val(response.data.title);
                $('#ecf_job_desc').val(response.data.text);
                $('#ecf_job_zip').val(response.data.zipCode);
                $('#edit_job').modal('show');
            }
        })
        console.log(jobId);
    }
	
///////////UPDATE JOB	
    tj.updateJob = function(jobId) {
        $.ajax({
            url:'inc/data.php?req=updateJob',
            data:{
                jobId:jobId,
                title:$('#ecf_job_title').val(),
                desc:$('#ecf_job_desc').val(),
                zipCode:$('#ecf_job_zip').val()                
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                $('#edit_job').modal('hide');
				alert('Job update successfully.');
                tj.jobsTable.ajax.reload(null,false);
            }
        })
    }


/////////////////////////////////////
// SET JOB/POSITION INACTIVE
tj.statusInactive = function (jobId, tableId) {
    var winHeight = $(window).height();
	var table = tableId;
    bootbox.confirm({
        message:"Change this job to Inactive?",
        backdrop:true,
        callback:function (result) {
            if (result) {
                $.ajax({
                    url: 'inc/data.php?req=setJobInactive',
                    data: {
                        jobId: jobId
                    },
                    method: 'post',
					success: function (response) {
                        if(table==1){
						tj.jobsTable.ajax.reload();
						}else{
						tj.alljobsTable.ajax.reload(null,false);
						}
                    }
                });
            }
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'font-weight' : 'bold',
        'color': '#000',
        'font-size': '18px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};

/////////////////////////////////////
// REMOVE USER FROM LOCATION
tj.removeUser = function (userId, storeId) {
    var winHeight = $(window).height();
    bootbox.confirm({
        message:"Remove this user from this location?",
        backdrop:true,
        callback:function (result) {
            if (result) {
                $.ajax({
                    url: 'inc/data.php?req=removeUser',
                    data: {
                        userId: userId,
						storeId: storeId
                    },
                    method: 'post',
                    success: function (response) {
                        console.log(response);
                        tj.usersTable.ajax.reload();
						tj.UserTable.ajax.reload();
                    }
                });
            }
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};

/////////////////////////////////////
// SET ALL POSITIONS INACTIVE
//
// INCOMPLETE
//
tj.statusInactiveAll = function (jobId) {
    var winHeight = $(window).height();
    bootbox.confirm("Change this job to Inactive?", function (result) {
        if (result) {
            $.ajax({
                url: 'inc/data.php?req=setJobInactive',
                data: {
                    jobId: jobId
                },
                method: 'post',
                success: function (response) {
                    console.log(response);
                    tj.jobsTable.ajax.reload();
                }
            })
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'font-weight' : 'bold',
        'color': '#000',
        'font-size': '18px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};

/////////////////////////////////////
// SET JOB/POSITION ACTIVE
tj.statusActive = function (jobId, tableId) {
    var winHeight = $(window).height();
	var table = tableId;
	console.log('table',table);
    bootbox.confirm("Change this job to Active?", function (result) {
        if (result) {
            $.ajax({
                url: 'inc/data.php?req=setJobActive',
                data: {
                    jobId: jobId
                },
                method: 'post',
                success: function (response) {
                    console.log(response);
					if(table==1){
                    tj.jobsTable.ajax.reload();
					}else{
					tj.alljobsTable.ajax.reload(null,false);
					}
                }
            })
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'font-weight' : 'bold',
        'color': '#000',
        'font-size': '18px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};

/////////////////////////////////////
// SET ALL POSITIONS ACTIVE
//
// INCOMPLETE
//
tj.statusActiveAll = function (jobId) {
    var winHeight = $(window).height();
    bootbox.confirm("Change this job to Active?", function (result) {
        if (result) {
            $.ajax({
                url: 'inc/data.php?req=setJobActive',
                data: {
                    jobId: jobId
                },
                method: 'post',
                success: function (response) {
                    console.log(response);
                    tj.jobsTable.ajax.reload();
                }
            })
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'font-weight' : 'bold',
        'color': '#000',
        'font-size': '18px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};




/////////////////////////////////////
// ADD USER TO LOCATION
tj.addUser = function() {
   if (tj.debug) console.log('current location: ',tj.locationsStoreID);
   $.ajax({
       url:'inc/data.php?req=addUser',
       data: {
           location: tj.locationsStoreID,
           user: $('#add_users_input').val()
       },
       success:function(data) {
		   $('#add_users_modal').modal('toggle');
		   document.getElementById("add_users_input").selectedIndex = "";
		   //document.getElementById("add_users_input").value = "";
           //alert('User added successfully.');
           console.log('user added');
		   tj.usersTable.ajax.reload();
		   tj.UserTable.ajax.reload();
       }
   })
}




/*

 _____   ___   _   _______ ___________  ___ _____ _____ _____    ______  ___  _____  _____
/  __ \ / _ \ | \ | |  _  \_   _|  _  \/ _ \_   _|  ___/  ___|   | ___ \/ _ \|  __ \|  ___|
| /  \// /_\ \|  \| | | | | | | | | | / /_\ \| | | |__ \ `--.    | |_/ / /_\ \ |  \/| |__
| |    |  _  || . ` | | | | | | | | | |  _  || | |  __| `--. \   |  __/|  _  | | __ |  __|
| \__/\| | | || |\  | |/ / _| |_| |/ /| | | || | | |___/\__/ /   | |   | | | | |_\ \| |___
 \____/\_| |_/\_| \_/___/  \___/|___/ \_| |_/\_/ \____/\____/    \_|   \_| |_/\____/\____/


*/


/////////////////////////////////////
// GLOBAL CANDIDATES PAGE VARS
tj.candidatesBrand = 0;
tj.candidatesGroup = 0;
tj.candidatesZipCode = '';
tj.candidatesZipRadius = 0;
tj.candidatesZipOnly = '';

tj.totalCandidatesSelected = 0;

tj.editCandidateTarget = 0;

/////////////////////////////////////
// INITIALIZE THE CANDIDATES TABLE
tj.initializeCandidatesGrid = function(id) {
    tj.candidatesTable = $('#candidatesTable').DataTable( {
        "ajax": {
            type:"POST",
            url:"inc/data.php?req=getCandidates",
            data: function(d) {
                d.zip = tj.candidatesZipCode;
                d.zipradius = tj.candidatesZipRadius;
				d.ziponly = tj.candidatesZipOnly;
                d.brand = tj.candidatesBrand;
                d.group = tj.candidatesGroup;
            }
        },
        // responsive: {
        //     details: {
        //         type:'column',
        //         target:1
        //     }
        // },
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
        order:[],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            switch(Number(aData.Style)) {
            case 1:
				$('td', nRow).addClass('orangeRow');
                break;
            case 2:
				$('td', nRow).addClass('greenRow');
                break;
            case 3:
				$('td', nRow).addClass('redRow');
                break;
            }
        },
        columnDefs:[
            {
                orderable:false,
                targets: 0,
                'checkboxes': {
                    'selectRow': true
                }
            }
        ],
        processing: true,
        "language": {
            "processing": 'Loading your Candidates...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Select" },
            { "className":'details-control',"orderable":false,"data":null,"defaultContent":''},
            { "data": "Brand" },
            { "data": "Group" },
            { "data": "First" },
            { "data": "Last" },
            { "data": "Position" },
            { "data": "Mobile" },
            { "data": "Recruiter" },
            { "data": "Zip" },
            { "data": "Skills" },
			{ "data": "Resume" },
            { "data": "Email" },
			{ "data": "subscribeDate" }
        ]
    } );
    
    tj.updateCandidatesTableActions = function() {
        if (tj.debug) console.log('ucta',tj.getCandidateSelectionCount());
        if (tj.getCandidateSelectionCount() > 0) {
            if (tj.debug) console.log('enabling candidate options');
            $('#candidate_actions > a').removeClass('disabled');
        } else {
            if (tj.debug) console.log('disabling candidate options');
            $('#candidate_actions > a').addClass('disabled');
        }
    }
    
    $('#candidatesTable').on('xhr.dt', function ( e, settings, json, xhr ) {
        if (tj.debug) console.log('got candidate data',json.length);
        tj.candidatesTable.rows().deselect();
        $('#candidate_actions > a').addClass('disabled');
    } );
    tj.detailRows = [];
    
    tj.createCandidateDetails = function(idx) {
        return '' +
            '<div class="row" style="background:#fff;pading-top:5px;padding-bottom:5px">' +
            '<div class="col-sm-4">' +
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">SMS History</span>'+
            '<table id="cds'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="200px">Message</th><th width="100px">User</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '</div>' +
            '<div class="col-sm-4">'+
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">Note History</span>'+
            '<table id="cdn'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="200px">Note</th><th width="100px">User</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '<div style="text-align:center"><button class="btn btn-primary"  data-toggle="modal" data-target="#add_note" onclick="$(\'#addnote_candidate_id\').val('+idx+');$(\'#add_note_body\').val(\'\');">Add Note</button></div>'+
            '</div>' +
			'<div class="col-sm-4">' +
			'</div>' +
            '</div>';
    }
    
    $('#candidatesTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tj.candidatesTable.row( tr );
        var idx = row.data().CandidateId;
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('details');
            tj.detailRows.splice( idx, 1 );
            
        }
        else {
            tr.addClass('details');
            
            row.child(tj.createCandidateDetails(idx)).show();
            if (tj.debug) console.log('creating sms datatable');
            $('#cds'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateSMSHistory",
                    data: {
                        candidateId: idx
                    }
                    
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
					{"data":"User"},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
            if (tj.debug) console.log('creating note datatable');
            $('#cdn'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateNoteHistory",
                    data: {
                        candidateId: idx
                    }
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
					{"data":"recruiter"},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
    
            if ( idx === -1 ) {
                tj.detailRows.push( tr.attr('id') );
            }
        }
    } );
    tj.candidatesTable.on( 'draw', function () {
        $.each( tj.detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );
    
    tj.getCandidateSelectionCount = function() {
        var rowData = tj.candidatesTable.rows( {selected:true} );
        return rowData.count();
    }
    
    tj.candidatesTable
        .on( 'select', function ( e, dt, type, indexes ) {
            tj.updateCandidatesTableActions();
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            tj.updateCandidatesTableActions();
        } );
    
    $('#candidate_search_keyword').on('keyup change',function() {
         tj.candidatesTable.search($(this).val()).draw();
    });
    
    tj.candSearch = function() {
        tj.candidatesZipCode = $('#candidate_search_zipcode').val();
        candidatesZipRadius = $('#candidate_search_zipradius').val();
        tj.candidatesBrand = $('#candidate_search_brand').val();
        tj.candidatesGroup = $('#candidate_search_group').val();
		tj.candidatesZipOnly = $('#candidate_search_ziponly:checked').val();
        
        //tj.candidatesKeywords = $('#candidate_search_keyword').val();
        tj.candidatesTable.rows().deselect();
        $('#candidate_actions > a').addClass('disabled');
        tj.candidatesTable.ajax.reload();
    }
    
    tj.resetCandSearch = function() {
        tj.candidatesZipCode = '';
		tj.candidatesGroup = '';
		tj.candidatesZipOnly = '';
        tj.candidatesTable.rows().deselect();
        $('#candidate_actions > a').addClass('disabled');
		$('#candidatesTable').DataTable().search('').draw();
        tj.candidatesTable.ajax.reload();
    }
    
    tj.sendCandSMS = function (message, group) {
        if (tj.debug) console.log('send message:' + message);
        $('#send_message').modal('hide');
        tj.startLoading('Loading...');
        var selected = tj.candidatesTable.rows( {selected:true} ).data();
        var recipients = [];
        
        $.each(selected, function (key, obj) {
			recipients.push({'keyword': obj['Keyword'], 'number': obj['MobileNum'], 'id': obj['Select']});
        });
        
       if (tj.debug) console.log(message, group, recipients);
        $.ajax({
            url: 'inc/data.php?req=sendSMS',
            data: {
                message: message,
                group: group,
                recipients: recipients
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				document.getElementById("send_sms_message").value = "";
				//$("assignGroup").each(function() { this.selectedIndex = 0 });
				document.getElementById("assignGroup").selectedIndex = "";
                tj.candidatesTable.ajax.reload();
				//tj.smsTable.ajax.reload();
				tj.stopLoading();            					
            }
        });
    }
    
    tj.updateCandGroup = function (id) {
        var selected = tj.candidatesTable.rows( {selected:true} ).data();
        var candidateIDS = [];
        if (tj.debug) console.log('update candidate group data');
        $.each(selected, function (key, obj) {
            if (tj.debug) console.log(key,obj);
            candidateIDS.push(Number(obj['Select']));
        });
        $.ajax({
            url: 'inc/data.php?req=updateGroup',
            data: {
                group: id,
                targets: candidateIDS
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                tj.candidatesTable.ajax.reload();
            }
        });
    }
	
	tj.addNote = function () {
        var selected = tj.candidatesTable.rows( {selected:true} ).data();
        var candidateId = $('#addnote_candidate_id').val();
		var note = $('#add_note_body').val();
		
        if (tj.debug) console.log('add note to candidate record');
        
        $.ajax({
            url: 'inc/data.php?req=addNote',
            data: {
                note: note,
                target: candidateId
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                $('#cdn'+candidateId).DataTable().ajax.reload();
				document.getElementById("add_note_body").value = "";
                $('#add_note').modal('hide');
            }
        });
    }
    
    tj.editCandidate = function(candidateId) {
        tj.editCandidateTarget=candidateId;
        $.ajax({
            url:'inc/data.php?req=getCandidateDetails',
            data:{
                candidateId:candidateId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                //ecf_cand_first_name
                $('#ecf_cand_first_name').val(response.data.first_name);
                $('#ecf_cand_last_name').val(response.data.last_name);
                $('#ecf_cand_email').val(response.data.email);
                $('#ecf_cand_telephone').val(response.data.mobile);
                $('#ecf_cand_zip_code').val(response.data.zip);
				$('#ecf_cand_city').val(response.data.city);
				$('#ecf_cand_state').val(response.data.state_code);
				$('#ecf_resume_paste').val(response.data.resume);
				$('#ecf_cand_resume').html(response.file);
                // $('#ecf_cand_first_name').val(response.data.first_name);
                $('#edit_candidate').modal('show');
            }
        })
        console.log(candidateId);
    }
	
	tj.forwardProfile = function(candidateId) {
        tj.editCandidateTarget=candidateId;
		$('#forward_candidate_id').val(tj.editCandidateTarget);
		$('#edit_candidate').modal('hide');
		$('#forward').modal('show');
		console.log(candidateId);
    }
    
    tj.updateCandidate = function(candidateId) {
        $.ajax({
            url:'inc/data.php?req=updateCandidate',
            data:{
                candidateId:candidateId,
                firstName:$('#ecf_cand_first_name').val(),
                lastName:$('#ecf_cand_last_name').val(),
                email:$('#ecf_cand_email').val(),
                mobile:$('#ecf_cand_telephone').val(),
                zipCode:$('#ecf_cand_zip_code').val(),
				resume:$('#ecf_resume_paste').val()
                // $('#ecf_cand_first_name').val(response.data.first_name);
                
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                $('#edit_candidate').modal('hide');
                tj.candidatesTable.ajax.reload(null,false);
                //ecf_cand_first_name
            }
        })
    }

	tj.forwardEmail = function(candidateId) {
		var message = $('#message').val();
		message = message.replace(/\r?\n/g, '<br />');
        $.ajax({
            url:'inc/data.php?req=forwardemail',
            data:{
                candidateId:candidateId,
                sendEmail:$('#send-to').val(),
                message:message
                // $('#ecf_cand_first_name').val(response.data.first_name);
                
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				alert('Email Sent.');
				document.getElementById("message").value = "";
				document.getElementById("send-to").value = "";
                $('#forward').modal('hide');
                //tj.candidatesTable.ajax.reload(null,false);
                //ecf_cand_first_name
            }
        })
    }
    
}

/////////////////////////////////////
// INITIALIZE THE RECRUITER TABLE
tj.initializeRecruiterGrid = function(id) {
    tj.recruiterTable = $('#recruiterTable').DataTable( {
        "ajax": {
            type:"POST",
            url:"inc/data.php?req=getRecruiter",
            data: function(d) {
                d.zip = tj.recruiterZipCode;
                //d.zipradius = tj.recruiterZipRadius;
				//d.ziponly = tj.recruiterZipOnly;
                //d.brand = tj.recruiterBrand;
                //d.group = tj.recruiterGroup;
            }
        },
        // responsive: {
        //     details: {
        //         type:'column',
        //         target:1
        //     }
        // },
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
        order:[],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            switch(Number(aData.Style)) {
            case 1:
				$('td', nRow).addClass('orangeRow');
                break;
            case 2:
				$('td', nRow).addClass('greenRow');
                break;
            case 3:
				$('td', nRow).addClass('redRow');
                break;
            }
        },
        columnDefs:[
            {
                orderable:false,
                targets: 0,
                'checkboxes': {
                    'selectRow': true
                }
            }
        ],
        processing: true,
        "language": {
            "processing": 'Loading your Candidates...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Select" },
            { "className":'details-control',"orderable":false,"data":null,"defaultContent":''},
            { "data": "Candidate" },
            { "data": "Location" },
            { "data": "Mobile" },
            { "data": "Date" }
        ]
    } );
    
    tj.updateRecruiterTableActions = function() {
        if (tj.debug) console.log('ucta',tj.getRecruiterSelectionCount());
        if (tj.getRecruiterSelectionCount() > 0) {
            if (tj.debug) console.log('enabling recruiter options');
            $('#recruiter_actions > a').removeClass('disabled');
        } else {
            if (tj.debug) console.log('disabling candidate options');
            $('#recruiter_actions > a').addClass('disabled');
        }
    }
    
    $('#recruiterTable').on('xhr.dt', function ( e, settings, json, xhr ) {
        if (tj.debug) console.log('got recruiter data',json.length);
        tj.recruiterTable.rows().deselect();
        $('#recruiter_actions > a').addClass('disabled');
    } );
    tj.recruiterdetailRows = [];
    
    tj.createRecruiterDetails = function(idx) {
        return '' +
            '<div class="row" style="background:#fff;pading-top:5px;padding-bottom:5px">' +
            '<div class="col-sm-5">' +
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">SMS History</span>'+
            '<table id="cds'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="300px">Message</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '</div>' +
            '<div class="col-sm-5">'+
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">Note History</span>'+
            '<table id="cdn'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="300px">Note</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '<div style="text-align:center"><button class="btn btn-primary"  data-toggle="modal" data-target="#recruiteradd_note" onclick="$(\'#addnote_recruiter_id\').val('+idx+');$(\'#recruiteradd_note_body\').val(\'\');">Add Note</button></div>'+
            '</div>' +
            '</div>';
    }
    
    $('#recruiterTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tj.recruiterTable.row( tr );
        var idx = row.data().CandidateId;
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('details');
            tj.recruiterdetailRows.splice( idx, 1 );
            
        }
        else {
            tr.addClass('details');
            
            row.child(tj.createRecruiterDetails(idx)).show();
            if (tj.debug) console.log('creating sms datatable');
            $('#cds'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateSMSHistory",
                    data: {
                        candidateId: idx
                    }
                    
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
            if (tj.debug) console.log('creating note datatable');
            $('#cdn'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateNoteHistory",
                    data: {
                        candidateId: idx
                    }
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
    
            if ( idx === -1 ) {
                tj.recruiterdetailRows.push( tr.attr('id') );
            }
        }
    } );
    tj.recruiterTable.on( 'draw', function () {
        $.each( tj.recruiterRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );
    } );
    
    tj.getRecruiterSelectionCount = function() {
        var rowData = tj.recruiterTable.rows( {selected:true} );
        return rowData.count();
    }
    
    tj.recruiterTable
        .on( 'select', function ( e, dt, type, indexes ) {
            tj.updateRecruiterTableActions();
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            tj.updateRecruiterTableActions();
        } );
    
    //$('#recruiter_search_keyword').on('keyup change',function() {
    //     tj.recruiterTable.search($(this).val()).draw();
    //});
    
    tj.RecSearch = function() {
        //tj.recruiterZipCode = $('#recruiter_search_zipcode').val();
        //tj.recruiterZipRadius = $('#recruiter_search_zipradius').val();
        //tj.recruiterBrand = $('#recruiter_search_brand').val();
        //tj.recruiterGroup = $('#recruiter_search_group').val();
		//tj.recruiterZipOnly = $('#recruiter_search_ziponly:checked').val();
        
        //tj.candidatesKeywords = $('#candidate_search_keyword').val();
        tj.recruiterTable.rows().deselect();
        $('#recruiter_actions > a').addClass('disabled');
        tj.recruiterTable.ajax.reload();
    }
    
    tj.resetRecSearch = function() {
        tj.recruiterZipCode = '';
		tj.recruiterGroup = '';
		tj.recruiterZipOnly = '';
        tj.recruiterTable.rows().deselect();
        $('#recruiter_actions > a').addClass('disabled');
        tj.recruiterTable.ajax.reload();
    }
    
    tj.sendRecSMS = function (message, group) {
        if (tj.debug) console.log('send message:' + message);
        $('#recruitersend_message').modal('hide');
        tj.startLoading('Loading...');
        var selected = tj.recruiterTable.rows( {selected:true} ).data();
        var recipients = [];
        
        $.each(selected, function (key, obj) {
			recipients.push({'keyword': obj['Keyword'], 'number': obj['MobileNum'], 'id': obj['Select']});
        });
        
       if (tj.debug) console.log(message, group, recipients);
        $.ajax({
            url: 'inc/data.php?req=sendSMS',
            data: {
                message: message,
                group: group,
                recipients: recipients
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                tj.recruiterTable.ajax.reload();
				//tj.candidatesTable.ajax.reload();
				//tj.smsTable.ajax.reload();
				document.getElementById("recruitersend_sms_message").value = "";
				document.getElementById("recruiterassignGroup").value = "";
				tj.stopLoading();
            }
        });
    }
    
    tj.updateRecGroup = function (id) {
        var selected = tj.recruiterTable.rows( {selected:true} ).data();
        var candidateIDS = [];
        if (tj.debug) console.log('update candidate group data');
        $.each(selected, function (key, obj) {
            if (tj.debug) console.log(key,obj);
            candidateIDS.push(Number(obj['Select']));
        });
        $.ajax({
            url: 'inc/data.php?req=updateGroup',
            data: {
                group: id,
                targets: candidateIDS
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                tj.recruiterTable.ajax.reload();
            }
        });
    }
	
	tj.recruiteraddNote = function () {
        var selected = tj.recruiterTable.rows( {selected:true} ).data();
        var candidateId = $('#addnote_recruiter_id').val();
		var note = $('#recruiteradd_note_body').val();
		
        if (tj.debug) console.log('add note to candidate record');
        
        $.ajax({
            url: 'inc/data.php?req=addNote',
            data: {
                note: note,
                target: candidateId
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                $('#cdn'+candidateId).DataTable().ajax.reload();
				document.getElementById("recruiteradd_note_body").value = "";
                $('#recruiteradd_note').modal('hide');
            }
        });
    }
    
    tj.editRecruiter = function(candidateId) {
        tj.editRecruiterTarget=candidateId;
        $.ajax({
            url:'inc/data.php?req=getCandidateDetails',
            data:{
                candidateId:candidateId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                //ecf_cand_first_name
                $('#ecf_rec_first_name').val(response.data.first_name);
                $('#ecf_rec_last_name').val(response.data.last_name);
                $('#ecf_rec_email').val(response.data.email);
                $('#ecf_rec_telephone').val(response.data.mobile);
                $('#ecf_rec_zip_code').val(response.data.zip);
                // $('#ecf_cand_first_name').val(response.data.first_name);
                $('#edit_recruiter').modal('show');
            }
        })
        console.log(candidateId);
    }
    
    tj.updateRecruiter = function(candidateId) {
        $.ajax({
            url:'inc/data.php?req=updateCandidate',
            data:{
                candidateId:candidateId,
                firstName:$('#ecf_rec_first_name').val(),
                lastName:$('#ecf_rec_last_name').val(),
                email:$('#ecf_rec_email').val(),
                mobile:$('#ecf_rec_telephone').val(),
                zipCode:$('#ecf_rec_zip_code').val()
                // $('#ecf_cand_first_name').val(response.data.first_name);
                
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
                $('#edit_recruiter').modal('hide');
                tj.recruiterTable.ajax.reload(null,false);
                //ecf_cand_first_name
            }
        })
    }
    
}

/*
USERS
   ______  ___  _____  _____
   | ___ \/ _ \|  __ \|  ___|
   | |_/ / /_\ \ |  \/| |__
   |  __/|  _  | | __ |  __|
   | |   | | | | |_\ \| |___
   \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// LOCATIONS GLOBALS
tj.UserID = 0;


/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.initializeUserGrid = function(id) {
    tj.UserTable = $('#UserTable').DataTable( {
        "ajax": "inc/data.php?req=getAllUsers",
        "order": [[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Name" },
            { "data": "Role" },
            { "data": "Locations" }
        ]
    } );
/////////////////////////////////////
// EDIT USER

tj.editUser = function(userId) {
        
        $.ajax({
            url:'inc/data.php?req=getUserDetails',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#ecf_user_email').val(response.data.email);
                $('#ecf_user_role').val(response.data.role);
                $('#userId').val(response.data.id);			
				$('#userName3').html(response.data.first_name+' '+response.data.last_name+'('+response.data.roleName+')');
				$('#edit_user').modal('show');
				}
        })
    }
	

	tj.updateUser = function() {
		var role = $('#ecf_user_role').val();
		var email = $('#ecf_user_email').val();
		var userId = $('#userId').val();
		
        $.ajax({
            url:'inc/data.php?req=updateUser',
            data:{
                email:email,
                role:role,
				userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#edit_user').modal('hide');
				tj.UserTable.ajax.reload();
				alert('User updated successfully.');
                
				//console.log('role',role);
            }
        })
    }
	
	tj.deleteUser = function() {
		var userId = $('#userId').val();
		bootbox.confirm({
        message:"Delete this user completely?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=deleteUser',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {				
				$('#edit_user').modal('hide');
				tj.UserTable.ajax.reload();
				//ecf_cand_first_name
            }
        });
	  }
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
};
	
tj.addNewUser = function () {
        
		var first = $('#ecf_newuser_first').val();
		var last = $('#ecf_newuser_last').val();
		var email = $('#ecf_newuser_email').val();
		var role = $('#ecf_newuser_role').val();
				
        if (tj.debug) console.log('add new user');
        
        $.ajax({
            url: 'inc/data.php?req=addNewUser',
            data: {
				first: first,
                last: last,
				email: email,
				role: role
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                //$('#cdn'+candidateId).DataTable().ajax.reload();
				//tj.userAssignedTable.ajax.reload();
				tj.UserTable.ajax.reload();
				alert('User added successfully and email sent.');
				document.getElementById("ecf_newuser_first").value = "";
				document.getElementById("ecf_newuser_last").value = "";
				document.getElementById("ecf_newuser_email").value = "";
				document.getElementById("ecf_newuser_role").selectedIndex = "";
                $('#addnewUser').modal('hide');
            }
        });

		
}
	
}

/////////////////////////////////////
// INITIALIZE THE USER LOCATIONS TABLE
tj.initializeUserAssignedGrid = function(id) {
    tj.userAssignedTable = $('#UserAssignedTable').DataTable( {
        "ajax": {
            "type"  :   "POST",
            "url"   :   "inc/data.php?req=getUserAssigned",
            "data"  :   function(d) {
                d.userId = tj.UserID;
            }
        },
        processing: true,
        deferLoading: 0,
        "language": {
            "processing": 'Loading Locations...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "StoreNum" },
            { "data": "Location" },
            { "data": "Action" }
        ]
    } );
	
}

/////////////////////////////////////
// LOAD THE USERS MODAL
tj.showUserLocations = function(userId,name) {
    //$('#userName2').html(name);
    
    tj.UserID = userId;
	$('#UserTable').DataTable().search('').draw();
	tj.userAssignedTable.ajax.reload();
	
}

/////////////////////////////////////
// REMOVE USER FROM LOCATION
tj.removeUserLocation = function (userId, storeId) {
    var winHeight = $(window).height();
    bootbox.confirm({
        message:"Remove this user from this location?",
        backdrop:true,
        callback:function (result) {
            if (result) {
                $.ajax({
                    url: 'inc/data.php?req=removeUser',
                    data: {
                        userId: userId,
						storeId: storeId
                    },
                    method: 'post',
                    success: function (response) {
						console.log(response);
						$('#userAssignedTable').DataTable().search('').draw();
                        tj.userAssignedTable.ajax.reload();
						tj.UserTable.ajax.reload();
                    }
                });
            }
        }
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px',
        'margin-top': Math.max(50,(Math.floor(winHeight/2)-100))+'px'
    });
};

/////////////////////////////////////
// LOAD THE USERS DETAILS
tj.showUserDetails = function(userId, name, roleName, roleId) {
    $('#userName2').html(name+' ('+roleName+')');
	$('#userId').val(userId);
	$('#roleId').val(roleId);
	
	console.log('user',userId);
	console.log('name',name);
	console.log('roleName',roleName);
	
	
	tj.UserID = userId;
    
    tj.userAssignedTable.ajax.reload();
}


















/*

 ________  ___ _____ _____ _   _ ______  _______   __   ______  ___  _____  _____
/  ___|  \/  |/  ___|_   _| \ | || ___ \|  _  \ \ / /   | ___ \/ _ \|  __ \|  ___|
\ `--.| .  . |\ `--.  | | |  \| || |_/ /| | | |\ V /    | |_/ / /_\ \ |  \/| |__
 `--. \ |\/| | `--. \ | | | . ` || ___ \| | | |/   \    |  __/|  _  | | __ |  __|
/\__/ / |  | |/\__/ /_| |_| |\  || |_/ /\ \_/ / /^\ \   | |   | | | | |_\ \| |___
\____/\_|  |_/\____/ \___/\_| \_/\____/  \___/\/   \/   \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// GLOBAL SMS INBOX PAGE VARS
tj.totalSMSSelected = 0

/////////////////////////////////////
// INITIALIZE THE SMSINBOX TABLE
tj.initializeSmsGrid = function(id) {
    tj.smsTable = $('#smsTable').DataTable( {
        "ajax": "inc/data.php?req=getMessages",
        // responsive: {
        //     details: false
        // },
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
		order:[],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            switch(Number(aData.Style)) {
            case 1:
				$('td', nRow).addClass('orangeRow');
                break;
            case 2:
				$('td', nRow).addClass('greenRow');
                break;
            case 3:
				$('td', nRow).addClass('redRow');
                break;
            }
        },
        columnDefs:[
            {
                orderable:false,
                targets:0,
                'checkboxes': {
                    'selectRow': true
                }
            }
        ],
        "order": [[2,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading your Messages...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Select" },
            { "className":'details-control',"orderable":false,"data":null,"defaultContent":''},
            { "data": "Received" },
            { "data": "First" },
            { "data": "Last" },
            { "data": "Group" },
            { "data": "Mobile" },
            { "data": "Message" },
            { "data": "Recruiter" }
        ]
    } );
    
    
    tj.updateSMSTableActions = function() {
        if (tj.debug) console.log('ucta',tj.getSMSInboxSelectionCount());
        if (tj.getSMSInboxSelectionCount() > 0) {
            if (tj.debug) console.log('enabling candidate options');
            $('#smsinbox_actions > a').removeClass('disabled');
        } else {
            if (tj.debug) console.log('disabling candidate options');
            $('#smsinbox_actions > a').addClass('disabled');
        }
    }
    
    $('#candidatesTable').on('xhr.dt', function ( e, settings, json, xhr ) {
        if (tj.debug) console.log('got candidate data',json.length);
        tj.smsTable.rows().deselect();
        $('#smsinbox_actions > a').addClass('disabled');
    } );
    
    tj.smsDetailRows = [];
    
    tj.createSMSInboxDetails = function(idx) {
        return '' +
            '<div class="row" style="background:#fff;pading-top:5px;padding-bottom:5px">' +
            '<div class="col-sm-5">' +
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">SMS History</span>'+
            '<table id="smsds'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="300px">Message</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '</div>' +
            '<div class="col-sm-5">'+
            '<span style="font-size:16px;font-weight:bold;padding-left:20px;padding-top:10px;padding-bottom:5px;">Note History</span>'+
            '<table id="smsdn'+idx+'" class="table table-bordered nowrap" cellspacing="0" width="100%">' +
            '<thead>' +
            '<tr><th width="300px">Note</th><th width="80px">Date</th></tr>' +
            '</thead>' +
            '</table>' +
            '<div style="text-align:center"><button class="btn btn-primary"  data-toggle="modal" data-target="#add_note" onclick="$(\'#addnote_candidate_id\').val('+idx+');$(\'#add_note_body\').val(\'\');">Add Note</button></div>'+
            '</div>' +
            '</div>';
    }
    
    $('#smsTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = tj.smsTable.row( tr );
        var idx = row.data().CandidateId;
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('details');
            tj.smsDetailRows.splice( idx, 1 );
            
        }
        else {
            tr.addClass('details');
            
            row.child(tj.createSMSInboxDetails(idx)).show();
            if (tj.debug) console.log('creating sms datatable');
            $('#smsds'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateSMSHistory",
                    data: {
                        candidateId: idx
                    }
                    
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
            if (tj.debug) console.log('creating note datatable');
            $('#smsdn'+idx).DataTable({
                "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getCandidateNoteHistory",
                    data: {
                        candidateId: idx
                    }
                },
                "order": [[1,'desc']],
                autoWidth:false,
                sDom:'<"top"<"clear">>tr<"bottom"ip<"clear">>',
                columns:[
                    {"data":"Message","orderable":false},
                    {"data":"MsgDate"}
                ],
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return "<div class='text-wrap width-300' style='"+full.Style+"'>" + data + "</div>";
                        },
                        targets: 0
                    }
                ]
            });
            
            if ( idx === -1 ) {
                tj.smsDetailRows.push( tr.attr('id') );
            }
        }
    } );
    
    tj.getSMSInboxSelectionCount = function() {
        var rowData = tj.smsTable.rows( {selected:true} );
        return rowData.count();
    }
    
    tj.smsTable
        .on( 'select', function ( e, dt, type, indexes ) {
            var rowData = tj.smsTable.rows( {selected:true} );
            tj.updateSMSTableActions();
        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            var rowData = tj.smsTable.rows( {selected:true} );
            tj.updateSMSTableActions();
        } );
    
    tj.sendSMS = function(message, group) {
        if (tj.debug) console.log('send message:' + message);
        $('#send_message_sms').modal('hide');
        tj.startLoading('Loading...');
        var selected = tj.smsTable.rows( {selected:true} ).data();
        var recipients = [];
    
        $.each(selected, function (key, obj) {
            recipients.push({'keyword': obj['Keyword'], 'number': obj['MobileNum'], 'id': obj['Select']});
        });
		
        if (tj.debug) console.log(message, group, recipients);
        $.ajax({
            url: 'inc/data.php?req=sendSMS',
            data: {
                message: message,
                group: group,
                recipients: recipients
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				document.getElementById("send_sms_message_sms").value = "";
				document.getElementById("assign_group_sms").selectedIndex = "";
				tj.smsTable.ajax.reload();
				//tj.candidatesTable.ajax.reload();
                tj.stopLoading();
            }
        });
    }
	
	tj.resetSMS = function() {
        tj.smsTable.ajax.reload();
    }
    
    tj.updateSMSGroup = function (id) {
        var selected = tj.smsTable.rows( {selected:true} ).data();
        var candidateIDS = [];
        if (tj.debug) console.log('update candidate group data');
        $.each(selected, function (key, obj) {
            if (tj.debug) console.log(key,obj);
            candidateIDS.push(Number(obj['CandidateId']));
        });
        $.ajax({
            url: 'inc/data.php?req=updateGroup',
            data: {
                group: id,
                targets: candidateIDS
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
                tj.smsTable.ajax.reload();
            }
        });
    }
}


/*

 _   _ _____ _____ _     _____ _____ _____ _____ _____
| | | |_   _|_   _| |   |_   _|_   _|_   _|  ___/  ___|
| | | | | |   | | | |     | |   | |   | | | |__ \ `--.
| | | | | |   | | | |     | |   | |   | | |  __| `--. \
| |_| | | |  _| |_| |_____| |_  | |  _| |_| |___/\__/ /
 \___/  \_/  \___/\_____/\___/  \_/  \___/\____/\____/


 */


/////////////////////////////////////
// PAGE LOAD INITIALIZATION
jQuery(function(){
    if (tj.debug) console.log('page load');
    tj.initLeftAsideMenu();
    tj.initializeDashboard();
});

/////////////////////////////////////
// SWITCH THE VIEW
tj.switchView = function(viewName) {
    $('.content-view').hide();
    switch(viewName) {
        default:
        case "#reports":
            if (tj.debug) console.log('reports');
            tj.loadReports();
            break;
        case "#locations":
            if (tj.debug) console.log('locations');
            tj.loadLocations();
            break;
		case "#jobs":
            if (tj.debug) console.log('jobs');
            tj.loadJobs();
            break;
        case "#candidates":
            if (tj.debug) console.log('candidates');
            tj.loadCandidates();
            break;
		case "#recruiter":
            if (tj.debug) console.log('recruiter');
            tj.loadRecruiter();
            break;
        case "#smsinbox":
            if (tj.debug) console.log('smsinbox');
            tj.loadSMSInbox();
            break;
        case "#profile":
            if (tj.debug) console.log('profile');
            tj.loadProfile();
            break;
        case "#support":
            if (tj.debug) console.log('support');
            tj.loadSupport();
            break;
        case "#users":
            if (tj.debug) console.log('support');
            tj.loadUsers();
            break;
    }
};

/////////////////////////////////////
// INITIALIZE THE DASHBOARD ON LOAD
tj.initializeDashboard = function() {
    if (tj.debug) console.log('Initializing Dashboard');
    tj.parseHash();
    tj.switchView(tj.currentTab);
};

/////////////////////////////////////
// HANDLE URL HASH CHANGE
jQuery(window).on('hashchange', function() {
    if (tj.debug) console.log('hash changed');
    tj.parseHash();
    tj.switchView(tj.currentTab);
});

/////////////////////////////////////
// PAGE LOAD INITIALIZATION
tj.parseHash = function() {
    var hash = window.location.hash;
    var hashArray = hash.split('?');
    tj.urlParams = {};
    if (hashArray.length > 0)
        tj.currentTab = hashArray[0];
    else
        tj.currentTab = tj.defaultTab;
    if (hashArray.length > 1) {
        var paramArray = hashArray[1].split('&');
        if (paramArray.length > 0)
            $.each(paramArray,function(index,param){
               var paramValue = param.split('=');
               if (paramValue.length > 0)
                   if (paramValue[0].trim().length > 0 && paramValue[1].trim().length > 0)
                       tj.urlParams[paramValue[0]] = paramValue[1];
            });
    }
    if (tj.debug) console.log('url params',tj.urlParams);
}

/////////////////////////////////////
// INITIALIZE MAIN MENU
tj.initLeftAsideMenu = function() {
    var menu = $('#main_menu');

    // init aside menu
    var menuOptions = {
        // submenu setup
        submenu: {
            desktop: {
                // by default the menu mode set to accordion in desktop mode
                default: (menu.data('menu-dropdown') == true ? 'dropdown' : 'accordion'),
                // whenever body has this class switch the menu mode to dropdown
                state: {
                    body: 'm-aside-left--minimize',
                    mode: 'dropdown'
                }
            },
            tablet: 'accordion', // menu set to accordion in tablet mode
            mobile: 'accordion'  // menu set to accordion in mobile mode
        },

        //accordion setup
        accordion: {
            autoScroll: true,
            expandAll: false
        }
    };

    tj.asideMenu = menu.mMenu(menuOptions);

    // handle fixed aside menu
    if (menu.data('menu-scrollable')) {
        function initScrollableMenu(obj) {
            if (mUtil.isInResponsiveRange('tablet-and-mobile')) {
                // destroy if the instance was previously created
                mApp.destroyScroller(obj);
                return;
            }

            var height = mUtil.getViewPort().height - $('.m-header').outerHeight()
                - ($('.m-aside-left .m-aside__header').length != 0 ? $('.m-aside-left .m-aside__header').outerHeight() : 0)
                - ($('.m-aside-left .m-aside__footer').length != 0 ? $('.m-aside-left .m-aside__footer').outerHeight() : 0);
            //- $('.m-footer').outerHeight();

            // create/re-create a new instance
            mApp.initScroller(obj, {height: height});
        }

        initScrollableMenu(tj.asideMenu);

        mUtil.addResizeHandler(function() {
            initScrollableMenu(tj.asideMenu);
        });
    }
};

////////////////////////////////////
//HANDLE SUPPORT REQUEST

tj.submitSupport = function(){
  var type = $('#supportModalType').val();
  var name = $('#supportModalName').val();
  var phone = $('#supportModalPhone').val();
  var email = $('#supportModalEmail').val();
  var message = $('#supportModalMessage').val();

  //console.log['name: ',name];

  if(type.length == 0 ){
    alert('Please select a support type.');
    return;
  }
  if(name.length == 0){
    alert('Please input your name.');
    return;
  }
  if(phone.length <10 && email.length == 0 ){
    alert('Please provide either contact information.');
    return;
  }

  $.ajax({
        url: 'inc/data.php?sp=1',
        dataType: 'json',
        method: 'post',
        data: {
          type: type,
          name: name,
          phone: phone,
          email: email,
          message: message
        },
        success: function(data) {
			$('#supportmodal').modal('hide');
          alert('Thank you, we will be in contact within 24hrs.');
        },
        error: function(data, err) {

            Metronic.unblockUI();
        }
});


};




/////////////////////////////////////
// SHOW LOADING SCREEN
tj.startLoading = function(message) {
    var html = '<div class="m-blockui"><span>'+message+'</span><span><div class="m-loader"></div></span></div>';
    $.blockUI({
        message: html,
        centerY: true,
        centerX: true,
        css: {
            top: '50%',
            left: '50%',
            border: '0',
            padding: '0',
            backgroundColor: 'none',
            width: 'auto'
        },
        overlayCSS: {
            backgroundColor: '#000000',
            opacity: 0.5,
            cursor: 'wait'
        }
    })
}

/////////////////////////////////////
// HIDE LOADING SCREEN
tj.stopLoading = function() {
    $.unblockUI();
}

/////////////////////////////////////
// SOME CHART COLORS and functions
tj.chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};

(function(global) {
    var Months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];
    
    var COLORS = [
        '#4dc9f6',
        '#f67019',
        '#f53794',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#58595b',
        '#8549ba'
    ];
    
    var Samples = global.Samples || (global.Samples = {});
    var Color = global.Color;
    
    Samples.utils = {
        // Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
        srand: function(seed) {
            this._seed = seed;
        },
        
        rand: function(min, max) {
            var seed = this._seed;
            min = min === undefined ? 0 : min;
            max = max === undefined ? 1 : max;
            this._seed = (seed * 9301 + 49297) % 233280;
            return min + (this._seed / 233280) * (max - min);
        },
        
        numbers: function(config) {
            var cfg = config || {};
            var min = cfg.min || 0;
            var max = cfg.max || 1;
            var from = cfg.from || [];
            var count = cfg.count || 8;
            var decimals = cfg.decimals || 8;
            var continuity = cfg.continuity || 1;
            var dfactor = Math.pow(10, decimals) || 0;
            var data = [];
            var i, value;
            
            for (i = 0; i < count; ++i) {
                value = (from[i] || 0) + this.rand(min, max);
                if (this.rand() <= continuity) {
                    data.push(Math.round(dfactor * value) / dfactor);
                } else {
                    data.push(null);
                }
            }
            
            return data;
        },
        
        labels: function(config) {
            var cfg = config || {};
            var min = cfg.min || 0;
            var max = cfg.max || 100;
            var count = cfg.count || 8;
            var step = (max - min) / count;
            var decimals = cfg.decimals || 8;
            var dfactor = Math.pow(10, decimals) || 0;
            var prefix = cfg.prefix || '';
            var values = [];
            var i;
            
            for (i = min; i < max; i += step) {
                values.push(prefix + Math.round(dfactor * i) / dfactor);
            }
            
            return values;
        },
        
        months: function(config) {
            var cfg = config || {};
            var count = cfg.count || 12;
            var section = cfg.section;
            var values = [];
            var i, value;
            
            for (i = 0; i < count; ++i) {
                value = Months[Math.ceil(i) % 12];
                values.push(value.substring(0, section));
            }
            
            return values;
        },
        
        color: function(index) {
            return COLORS[index % COLORS.length];
        },
        
        transparentize: function(color, opacity) {
            var alpha = opacity === undefined ? 0.5 : 1 - opacity;
            return Color(color).alpha(alpha).rgbString();
        }
    };
    
    // DEPRECATED
    window.randomScalingFactor = function() {
        return Math.round(Samples.utils.rand(-100, 100));
    };
    
    // INITIALIZATION
    
    Samples.utils.srand(Date.now());
    
    
}(this));