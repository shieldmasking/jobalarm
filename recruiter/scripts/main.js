var tj = tj || {};

/////////////////////////////////////
// GLOBAL VARIABLE INITIALIZATION
tj.defaultTab = "#reports";
tj.currentTab = tj.defaultTab;
tj.reportsLoaded = false;
tj.locationsLoaded = false;
tj.candidatesLoaded = false;
tj.smsInboxLoaded = false;

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
            tj.initializeLocationsGrid('')
            tj.initializeJobsGrid('');
            tj.locationsLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD CANDIDATES PAGE
tj.loadCandidates = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[3]);
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
// LOAD SMSINBOX PAGE
tj.loadSMSInbox = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[4]);
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
	var ValidStatus = $("#supportModal").valid();
    console.log(ValidStatus);
	tj.submitSupport();
    if (ValidStatus == false) {
        return false;
    }
    
  });
};

// Build Traffic

tj.alex.usersGrid = {};
tj.alex.initializeusersGrid = function() {
	//$('#UserxCell').hide();
	$.ajax({
        url: 'inc/data.php?req=traffic',
        dataType: 'json',
        method: 'post',
        data: {},
        success: function(data) {
        	var row = '';
            var length = data.data.length;
            var temp;
            for (var i = 0; i < length; i++) {
                row = '';
                row += '<tr>';
                row += '<td>';
                row += data.data[i][0];   // Post Date
                row += '</td>';
                row += '<td>';
                row += data.data[i][1];    // Job
                row += '</td>';
                row += '<td>';
                row += data.data[i][2];    // Stores
                row += '</td>';
                row += '<td>';
                row += data.data[i][3];    // Actions
                row += '</td>';
				row += '</tr>';
                $('#UsersBody').append(row);
            }
            tj.alex.usersGrid = $("#datatableUsers_ajax").DataTable(
			{"order": [[ 3, "desc" ]]});
            Metronic.unblockUI();
            //tj.alex.userxGrid;

        },
        error: function(data, err) {
            //tj.alex.getGroups(true);
            Metronic.unblockUI();
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
            { "data": "CandidateLink" },
            { "data": "StoreButton" }
        ]
    } );
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
}

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
// SET JOB/POSITION INACTIVE
tj.statusInactive = function (jobId, storeId) {
    var winHeight = $(window).height();
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
                        console.log(response);
                        tj.jobsTable.ajax.reload();
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
tj.statusActive = function (jobId, storeId) {
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
           console.log('position added');
           tj.jobsTable.ajax.reload();
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
            { "data": "Email" }
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
            '<div style="text-align:center"><button class="btn btn-primary"  data-toggle="modal" data-target="#add_note" onclick="$(\'#addnote_candidate_id\').val('+idx+');$(\'#add_note_body\').val(\'\');">Add Note</button></div>'+
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
        tj.candidatesZipRadius = $('#candidate_search_zipradius').val();
        tj.candidatesBrand = $('#candidate_search_brand').val();
        tj.candidatesGroup = $('#candidate_search_group').val();
        
        //tj.candidatesKeywords = $('#candidate_search_keyword').val();
        tj.candidatesTable.rows().deselect();
        $('#candidate_actions > a').addClass('disabled');
        tj.candidatesTable.ajax.reload();
    }
    
    tj.resetCandSearch = function() {
        tj.candidatesZipCode = '';
		tj.candidatesGroup = '';
        tj.candidatesTable.rows().deselect();
        $('#candidate_actions > a').addClass('disabled');
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
                tj.candidatesTable.ajax.reload();
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
                // $('#ecf_cand_first_name').val(response.data.first_name);
                $('#edit_candidate').modal('show');
            }
        })
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
                zipCode:$('#ecf_cand_zip_code').val()
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
                tj.smsTable.ajax.reload();
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
        case "#candidates":
            if (tj.debug) console.log('candidates');
            tj.loadCandidates();
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


  if(type.length == 0 || name.length == 0 || email.length == 0 || message.length == 0  ){
    alert('Not all required info provided.');
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
			$('#supportModal').modal('toggle');
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