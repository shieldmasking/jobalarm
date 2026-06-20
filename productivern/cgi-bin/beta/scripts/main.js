var tj = tj || {};

/////////////////////////////////////
// GLOBAL VARIABLE INITIALIZATION
tj.defaultTab = "#reports";
tj.currentTab = tj.defaultTab;
tj.reportsLoaded = false;
tj.configureLoaded = false;
tj.usersLoaded = false;
tj.prodLoaded = false;
tj.prodLoadedwhp = false;
tj.performanceLoaded = false;
tj.unitsLoaded = false;
tj.accountsLoaded = false;
tj.userxLoaded = false;
tj.classLoaded = false;
tj.escalationsLoaded = false;
tj.deliveryLoaded = false;
tj.complianceLoaded = false;
tj.whpLoaded = false;
tj.nurseLoaded = false;

tj.debug = true;    // allow debug console messages


/////////////////////////////////////
// LOAD REPORTS PAGE
tj.loadReports = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[1]);
	//tj.loadRoles();
    $('#reportsView').show();
	tj.reportsId='';
	//var categoryId=$('#filter').val();
    if (!tj.reportsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#reportsView').load('views/reports.php', {}, function () {
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.reportsId=tj.urlParams['id'];
            }
			var startPay = $('#startPay').val();
			var endPay = $('#endPay').val();
			var role = $('#role').val();
			//console.log('startPay',startPay);
			//console.log('endPay',endPay);
            tj.daterangepickerInit(tj.reportsId,startPay,endPay,role);
			tj.initSupportGrid();
			//tj.createReportCharts(tj.reportsId);
            tj.reportsLoaded = true;
            tj.stopLoading();
			//tj.loadProd();
        });
    }else {
		//jQuery('#reportsView').load('views/reports.php', {}, function () {
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.reportsId=tj.urlParams['id'];
		}
			tj.startLoading('Loading...');
			var startPay = $('#startPay').val();
			var endPay = $('#endPay').val();
			var role = $('#role').val();
			tj.newCandidateReport.destroy();
			tj.outgoingMsgReport.destroy();
			tj.laborReport.destroy();
			tj.postpartumReport.destroy();
			tj.daterangepickerInit(tj.reportsId,startPay,endPay,role);
			//tj.createReportCharts(tj.reportsId);
            tj.reportsLoaded = true;
			tj.stopLoading();
	}
};

/////////////////////////////////////
// LOAD REPORTS PAGE
tj.loadReports2 = function() {
    if (!tj.reportsLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#reportsView').load('views/reports.php', {}, function () {
			//tj.newCandidateReport.destroy();
			//tj.outgoingMsgReport.destroy();
			//tj.laborReport.destroy();
			//tj.postpartumReport.destroy();
			//tj.createReportCharts();
            tj.daterangepickerInit();
			tj.reportsLoaded = true;
            //tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD REPORTS PAGE
tj.loadDelivery = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[1]);
    $('#deliveryView').show();
    if (!tj.deliveryLoaded) {
        tj.startLoading('Loading...');
        jQuery('#deliveryView').load('views/deliveries.php', {}, function () {
			tj.createDeliveryReportCharts();
			tj.deliverydaterangepickerInit();
            tj.deliveryLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD WHPVIEW PAGE
tj.loadReportView = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[1]);
	tj.reportViewId='';
    $('#reportView').show();
    if (!tj.reportLoaded) {
		if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
                tj.reportViewId=tj.urlParams['i'];
            }
        jQuery('#reportView').load('views/view.php', {}, function () {
			tj.getReportView(tj.reportViewId);
			tj.reportLoaded = true;
            tj.stopLoading();
        });
	}else {
        if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
            tj.reportViewId=tj.urlParams['i'];
			tj.getReportView(tj.reportViewId);
        }
	}
};

/////////////////////////////////////

// LOAD UNITS PAGE
tj.loadUnits = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[11]);
    $('#unitsView').show();
	if (!tj.unitsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#unitsView').load('views/units.php', {}, function () {
			var a11 = document.getElementById('unitDetails');
			a11.style.display='none';
            tj.initializeUnitsGrid('');
            tj.unitsLoaded = false;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////

// LOAD Accounts PAGE
tj.loadAccounts = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[12]);
    $('#accountsView').show();
	if (!tj.accountsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#accountsView').load('views/accounts.php', {}, function () {
			tj.initializeAccountsGrid('');
            tj.accountsLoaded = false;
            tj.stopLoading();
        });
    }
};


/////////////////////////////////////
// LOAD USERS PAGE
tj.loadUsers = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[10]);
	$('#usersView').show();
    if (!tj.usersLoaded) {
        tj.startLoading('Loading...');
        jQuery('#usersView').load('views/users.php', {}, function () {
			tj.initializeUserGrid('');
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.usersLoaded = true;
            tj.stopLoading();
        });
    }
};

tj.loadRoles = function() {
		var u1 = document.getElementById('label');
		var u2 = document.getElementById('logo');
		var u3 = document.getElementById('name');
		var u4 = document.getElementById('email');
		var u5 = document.getElementById('nursing');
		var u6 = document.getElementById('support');
		var u7 = document.getElementById('manager');
		var u8 = document.getElementById('director');
		u7.style.display='none';
		u8.style.display='none';
		
        $.ajax({
            url:'inc/data.php?req=getRoleInfo',
            data:{
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				
				if((response.data.role)>=6){
				u7.style.display='';
				}
				if((response.data.role)>=7){
				u8.style.display='';
				}				
				
				if((response.data.prodCount)>1){
				u5.style.display='';
				u6.style.display='';
			
				}else if ((response.data.prodCount)==1 && (response.data.prodMeasure)==2) {
				u6.style.display='';
				u5.style.display='none';
				}else{
				u6.style.display='none';
				u5.style.display='';	
				}
				//$('#label').html('href="staffing"');
				//$('#logo').html('href="staffing"');
				$('#name').html(response.data.first_name);
				$('#labelName').html(response.data.labelName);
				$('#favicon').html('<link rel="shortcut icon" href="/img/'+ response.data.favicon +'" />');
				$('#label').html('<img src="../img/'+ response.data.logo + '" alt="logo" style="max-height:60px"/>');
				$('#logo').html('<img src="../img/'+ response.data.logo + '" alt="logo" style="max-height:60px"/>');
				}
        })
        
    };
/////////////////////////////////////
// LOAD USERS PAGE
tj.loadUserx = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[8]);
    $('#userxView').show();
    if (!tj.userxLoaded) {
        tj.startLoading('Loading...');
        jQuery('#userxView').load('views/userx.php', {}, function () {
			tj.initializeProdGriduserx('');
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.userxLoaded = true;
            tj.stopLoading();
        });
    }
};
/////////////////////////////////////
// LOAD CLASSES PAGE
/////////////////////////////////////
// LOAD PERFORMANCE PAGE
tj.loadClasses = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[7]);
	//tj.classLoaded = false;
	$('#classView').show();
	if (!tj.classLoaded) {
        tj.startLoading('Loading...');
        jQuery('#classView').load('views/classes.php', {}, function () {
			tj.classGrid('');
			tj.classdaterangepickerInit();
			tj.classLoaded = true;
			tj.stopLoading();
			document.getElementById("reportDate").html=('');
			document.getElementById("reportTime").html=('');
			document.getElementById("lastEdit").html=('');
			document.getElementById("classtable").style.display='';
			document.getElementById("class_daterangepicker").style.display='';			
			document.getElementById("newclass").style.display='';
			document.getElementById("detailtable").style.display='none';
        });
    }
};


/////////////////////////////////////
// LOAD USERS PAGE
tj.loadProd = function(start,end) {
    tj.asideMenu.setActiveItem($('.m-menu__item')[3]);
    tj.prodId='';
	tj.prodStart='';
	tj.prodEnd='';
	$('#staffingView').show();
	if (!tj.prodLoaded) {
        tj.startLoading('Loading...');
        jQuery('#staffingView').load('views/staffing.php', {}, function () {
			var a1 = document.getElementById('staffing_daterangepicker');
			var a2 = document.getElementById('escButton');
			a1.style.display='';
			a2.style.display='';
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.prodId=tj.urlParams['id'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStart=tj.urlParams['s'];
			a1.style.display='none';
			a2.style.display='none';
			}
			if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEnd=tj.urlParams['e'];
			}
			var startPay = $('#startStaff').val();
			var endPay = $('#endStaff').val();
			var role = $('#staffingRole').val();
			$('#pdfTitle').val('Staffing Reports');
            tj.initializeProdGrid('');
			tj.staffingdaterangepickerInit(startPay,endPay,role);
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.prodLoaded = true;
            tj.stopLoading();
        });
    }else{
		var a1 = document.getElementById('staffing_daterangepicker');
		var a2 = document.getElementById('escButton');
		a1.style.display='';
		a2.style.display='';
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.prodId=tj.urlParams['id'];
			}
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStart=tj.urlParams['s'];
			a1.style.display='none';
			a2.style.display='none';
			}
		if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEnd=tj.urlParams['e'];
			}
			$('#pdfTitle').val('Staffing Reports');
            tj.prodTable.ajax.reload();
   }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadProdwhp = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[4]);
	tj.prodIdwhp='';
	tj.prodStartwhp='';
	tj.prodEndwhp='';
    $('#staffingViewwhp').show();
	if (!tj.prodLoadedwhp) {
        tj.startLoading('Loading...');
        jQuery('#staffingViewwhp').load('views/supportstaffing.php', {}, function () {
			var a1 = document.getElementById('staffing_daterangepickerwhp');
			var a2 = document.getElementById('escButtonwhp');
			a1.style.display='';
			a2.style.display='';
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.prodIdwhp=tj.urlParams['id'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartwhp=tj.urlParams['s'];
			a1.style.display='none';
			a2.style.display='none';
			}
			if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndwhp=tj.urlParams['e'];
			}
			var startPay = $('#startSupport').val();
			var endPay = $('#endSupport').val();
			var role = $('#supportRole').val();
            tj.initializeProdGridwhp('');
			tj.staffingdaterangepickerInitwhp(startPay,endPay,role);
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.prodLoadedwhp = true;
            tj.stopLoading();
        });
    }else{
		var a1 = document.getElementById('staffing_daterangepickerwhp');
		var a2 = document.getElementById('escButtonwhp');
		a1.style.display='';
		a2.style.display='';
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.prodIdwhp=tj.urlParams['id'];
			}
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartwhp=tj.urlParams['s'];
			a1.style.display='none';
			a2.style.display='none';
			}
		if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndwhp=tj.urlParams['e'];
			}
            tj.prodTablewhp.ajax.reload();
   }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadCompliance = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[8]);
	tj.complianceId='';
    $('#complianceView').show();
	//tj.complianceLoaded = false;
	if (!tj.complianceLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#complianceView').load('views/compliance.php', {}, function () {
            if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.complianceId=tj.urlParams['id'];
            }
			tj.initializeComplianceGrid('');
			tj.compliancedaterangepickerInit();
			tj.complianceLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadEscalations = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[7]);
	tj.escalationId='';
    $('#escalationsView').show();
	tj.escalationsLoaded = false;
	if (!tj.escalationsLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#escalationsView').load('views/escalations.php', {}, function () {
            if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.escalationId=tj.urlParams['id'];
            }
			tj.initializeEscalationsGrid('');
			tj.escalationsdaterangepickerInit();
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.escalationsLoaded = true;
            tj.stopLoading();
        });
    }else {
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.escalationId=tj.urlParams['id'];
            tj.escalationsTable.ajax.reload();
        }
	}
};

/////////////////////////////////////
// LOAD PERFORMANCE PAGE
tj.loadPerformance = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[6]);
	tj.performanceId='';
    $('#performanceView').show();
	tj.performanceLoaded = false;
	if (!tj.performanceLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#performanceView').load('views/performance.php', {}, function () {
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.performanceId=tj.urlParams['id'];
            }
            tj.initializePerformanceGrid('');
			tj.performancedaterangepickerInit();
			tj.performanceLoaded = true;
        });
    }else {
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.performanceId=tj.urlParams['id'];
            tj.performanceTable.ajax.reload();
        }
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

/////////////////////////////////////
// LOAD NEW PAGE
tj.loadNew = function() {
    tj.asideMenu.setActiveItem();
    $('#newView').show();
    tj.startLoading('Loading...');
    jQuery('#newView').load('views/new.php', {}, function () {
        tj.stopLoading();
    });
};

/////////////////////////////
//LOAD SUPPORT MODAL
tj.initSupportGrid = function(){
  $('#supportModalSubmitButton').click(function(){
	tj.submitSupport();
    //if (ValidStatus == false) {
    //    return false;
    //}
    
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
	var ctc = document.getElementById(id);
    tj.newCandidateReport = new Chart(ctc, {
        type: 'bar',
        data: {},
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: false,
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
// GET BLOCKED BEDS
blockedBeds = function(accountId,deptId) {
   $.ajax({
       url:'inc/data.php?req=getblocked',
       data: {
           accountId: accountId,
           deptId: deptId
       },
       success:function(response) {
		   $('#deptId').val(response.data.deptId);
		   $('#deptName').html(response.data.deptName);
		   $('#accountId').val(response.data.accountId);
		   $('#blockedcount').val(response.data.blockedBeds);
		   $('#blockedcomment').html(response.data.comments);
		   $('#blockbeds').modal('show');
       }
   })
}

	/////////////////////////////////////
// GET BLOCKED BEDS
updateblockedBeds = function() {
	var count = $('#blockedcount').val();
	var comment = $('#blockedcomment').val();
	var accountId = $('#accountId').val();
	var deptId = $('#deptId').val();
   $.ajax({
       url:'inc/data.php?req=updateblocked',
       data: {
           accountId: accountId,
           deptId: deptId,
		   count: count,
		   comment: comment
       },
       success:function(data) {
		   $('#blockbeds').modal('hide');
		   
		    //tj.reportsLoaded = false;
			//tj.loadReports();
			tj.loadReports();	
		   //window.refresh();
		     //console.log('beds blocked');
		   
       }
   })
}

/////////////////////////////////////
// CREATE OUTGOING MSG REPORT OBJECT
tj.buildOutgoingMsgsChart = function(id){
	var ctm = document.getElementById(id);
    tj.outgoingMsgReport = new Chart(ctm, {
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
// CREATE LABOR REPORT OBJECT
tj.buildLaborChart = function(id){
	var ctl = document.getElementById(id);
    tj.laborReport = new Chart(ctl, {
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
// CREATE DELIVERY REPORT OBJECT
tj.buildDeliveryChart = function(id){
	var ctd = document.getElementById(id);
    tj.deliveryChart = new Chart(ctd, {
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
// CREATE DELIVERY REPORT OBJECT
tj.buildDelivery2Chart = function(id){
	var ctv = document.getElementById(id);
    tj.deliveryChart2 = new Chart(ctv, {
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
// CREATE Postpartum REPORT OBJECT
tj.buildPostpartumChart = function(id){
	var ctp = document.getElementById(id);
    tj.postpartumReport = new Chart(ctp, {
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
	tj.buildNewCandidateChart("all_chart");
    tj.buildOutgoingMsgsChart("antepartum_chart");
	tj.buildLaborChart("labor_chart");
	tj.buildPostpartumChart("postpartum_chart");
}

tj.createDeliveryReportCharts = function() {
    tj.buildDeliveryChart("deliveries_chart");
    tj.buildDelivery2Chart("risk_chart");
}

/////////////////////////////////////
// GET REPORT DATA
tj.getReportData = function(reportsId,categoryId,locationId,start,end,callback) {
    //console.log('reportsId',reportsId);
	//console.log('categoryId get report',categoryId);
	//console.log('start',start);
	//console.log('end',end);
	$.ajax({
        url:'inc/data.php?req=getReports',
        data:{
            start:start,
            end:end,
			idsearch:reportsId,
			categoryId:categoryId,
			locationId:locationId
        },
        method:'POST',
        dataType:'json',
        success:function(response) {
		    //if (tj.debug) console.log(response);
            if (typeof(callback) == 'function') {
                callback(response.data);
			}
        }
    })
}

/////////////////////////////////////
// GET REPORT DATA
tj.getDeliveryReportData = function(start,end,callback) {
    
	$.ajax({
        url:'inc/data.php?req=getDeliveryReports',
        data:{
            start:start,
            end:end
        },
        method:'POST',
        dataType:'json',
        success:function(response) {
		    //if (tj.debug) console.log(response);
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
            //console.log(response);
        }
    })
}

/////////////////////////////////////
// UPDATE REPORTS PAGE DATE
tj.updateReports = function(start,end,reportsId,categoryId,locationId) {
	
	var reportsId = reportsId;
	//console.log('idupdate',reportsId);
	//if (tj.debug) {
        //console.log('updating reports',start,end);
    //}
    tj.reportDates = {
        start:start,
        end:end
    }
    tj.getReportData(reportsId,categoryId,locationId,start,end,function(data){
        console.log('updateReports - getReportData',data);
		
		
		//console.log('data', JSON.stringify(data))
        var color = Chart.helpers.color;
		var reportBody = $('#reportBody');
		var html = '';
		var chartBody = $('#chartBody');
		var charthtml = '';
		$('#startDate').val(start);
		$('#endDate').val(end);
		
		for (var i=1;i<= data['deptCount1'];i++){
		
		html += '<div class="col-12">';

		html += '<div class="mr-auto">';
		
		
		if(data['pMeasure' + i]==2){
		html += '<h4>' + data['dept' + i] + ' <small>(WHPUOS Target: ' + data['target' + i] + ')</small></h4>';
		}else{
		html += '<h4 class="m-subheader__title ">' + data['dept' + i] + '</h4>';
		}
		
		if(data['compliance' + i]>0){
		html += '<h6>' + data['compliance' + i] + '% Reports Completed</h6></div>';	
		}else{
		html += '</div>';
		}
		
		
		html += '<div class="row">';
		html += '<div class="col-sm">';
				
		if (((data['totalCan' + i] <= data['rnThresholdLow' + i] && data['dashColor' + i]>=1) || (data['totalCan' + i] >= data['rnThresholdHigh' + i] && data['dashColor' + i]<=1)) && data['pMeasure' + i] !=2){
		html += '<div class="dashboard-stat red-intense">';
		}else if (data['totalCan' + i]> 0 && data['dashColor' + i]<=1 && data['pMeasure' + i] == 2){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat green-haze">';
		}
		
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['totalCan' + i] + '</div>';
		html += '<div class="desc">' + data['variance' + i] + '</div></div></div></div>';
		
		html += '<div class="col-sm">';
		if(((data['totalPromo' + i] < data['thresholdLow' + i] && data['dashColor' + i]>=1 && data['pMeasure' + i] == 2) || (data['totalPromo' + i] > data['thresholdHigh' + i] && data['dashColor' + i]<=1)) && data['thresholdHigh' + i] >0 && data['totalPromo' + i] >0 && data['pMeasure' + i] == 2){
		html += '<div class="dashboard-stat red-intense">';
		}else if(((data['totalPromo' + i] < data['thresholdLow' + i] && data['dashColor' + i]<=1 && data['pMeasure' + i] != 2) || (data['totalPromo' + i] > data['thresholdHigh' + i] && data['dashColor' + i]>=1)) && data['thresholdHigh' + i] >0 && data['totalPromo' + i] >0 && data['pMeasure' + i] != 2){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat green-haze">';	
		}
		
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['totalPromo' + i] + '</div>';
		html += '<div class="desc">' + data['productivity' + i] + '</div></div></div></div>';
		
		if (data['budgetMeasure' + i] ==1) {
		html += '<div class="col-sm">';
		}else{
		html += '<div class="col-sm" hidden>';
		}
		if (data['budgetValue' + i] >100 && data['budgetValue' + i] ==1){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat green-haze">';
		}
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['budgetValue' + i] + '%</div>';
		html += '<div class="desc">Budget</div></div></div></div>';
		
		html += '<div class="col-sm">';
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['totalMsg' + i] + '</div>';
		html += '<div class="desc">' + data['planned' + i] + '</div></div></div></div>';
		
		html += '<div class="col-sm">';
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['totalCTR' + i] + '</div>';
		html += '<div class="desc">' + data['procedures' + i] + '</div></div></div></div>';
		
		html += '<div class="col-sm">';
		
		if (data['totalBlocked' + i] >0){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat green-haze">';
		}
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['totalBlocked' + i] + '</div>';
		html += '<div class="desc">' + data['blockedbeds' + i] + '</div></div></div></div>';
		
		if (data['churn' + i]==1){
		html += '<div class="col-sm">';
		}else{
		html += '<div class="col-sm" hidden>';
		}
		
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number">' + data['churnVal' + i] + '%</div>';
		html += '<div class="desc">Churn</div></div></div></div>';
		
		html += '<div class="col-sm">';
		
		if (data['newEscalation' + i] ==0){
		html += '<div class="dashboard-stat green-haze">';
		}else{
		html += '<div class="dashboard-stat red-intense">';
		}
		html += '<div class="visual"></div><div class="details">';
		html += '<div class="number"><h2>' + data['newEscalation' + i] + '</h2></div>';
		html += '<div class="desc">Escalation</div></div></div></div></div></div>';
		}
		
		reportBody.empty().append(html);
		
		if (data['deptCount1']==1){
		charthtml += '<div class="row">';
		}else{
			charthtml += '<div class="row" hidden>';
		}
        charthtml += '<div class="col-md-6">';
            charthtml += '<div class="m-portlet">';
                charthtml += '<div class="m-portlet__head">';
                    charthtml += '<div class="m-portlet__head-caption">';
                        charthtml += '<div class="m-portlet__head-title">';
                            charthtml += '<span class="m-portlet__head-icon">';
                                charthtml += '<i class="flaticon-graph"></i>';
                            charthtml += '</span>';
                            charthtml += '<h3 class="m-portlet__head-text">';
                                charthtml += '<span>' + data['chart1'] + '</span>';
                            charthtml += '</h3>';
                        charthtml += '</div>';
                    charthtml += '</div>';
                charthtml += '</div>';
                charthtml += '<div class="m-portlet__body">';
                   charthtml += '<canvas id="all_chart" style="width:100%"></canvas>';
                charthtml += '</div>';
            charthtml += '</div>';
        charthtml += '</div>';
		charthtml += '<div class="col-md-6">';
            charthtml += '<div class="m-portlet">';
                charthtml += '<div class="m-portlet__head">';
                    charthtml += '<div class="m-portlet__head-caption">';
                        charthtml += '<div class="m-portlet__head-title">';
                            charthtml += '<span class="m-portlet__head-icon">';
                                charthtml += '<i class="flaticon-graph"></i>';
                            charthtml += '</span>';
                            charthtml += '<h3 class="m-portlet__head-text">';
                                charthtml += '<span>' + data['chart2'] + '</span>';
                            charthtml += '</h3>';
                        charthtml += '</div>';
                    charthtml += '</div>';
                charthtml += '</div>';
                charthtml += '<div class="m-portlet__body">';
                    charthtml += '<canvas id="postpartum_chart" style="width:100%"></canvas>';
                charthtml += '</div>';
            charthtml += '</div>';
        charthtml += '</div>';
		charthtml += '</div>';
		
		if (data['deptCount1']==1){
		charthtml += '<div class="row">';
		}else{
		charthtml += '<div class="row" hidden>';
		}
		        charthtml += '<div class="col-md-6">';
            charthtml += '<div class="m-portlet">';
                charthtml += '<div class="m-portlet__head">';
                    charthtml += '<div class="m-portlet__head-caption">';
                        charthtml += '<div class="m-portlet__head-title">';
                            charthtml += '<span class="m-portlet__head-icon">';
                                charthtml += '<i class="flaticon-graph"></i>';
                            charthtml += '</span>';
                            charthtml += '<h3 class="m-portlet__head-text">';
                                charthtml += '<span>' + data['chart3'] + '</span>';
                            charthtml += '</h3>';
                        charthtml += '</div>';
                    charthtml += '</div>';
                charthtml += '</div>';
                charthtml += '<div class="m-portlet__body">';
                   charthtml += '<canvas id="labor_chart" style="width:100%"></canvas>';
                charthtml += '</div>';
            charthtml += '</div>';
        charthtml += '</div>';
        charthtml += '<div class="col-md-6">';
            charthtml += '<div class="m-portlet">';
                charthtml += '<div class="m-portlet__head">';
                    charthtml += '<div class="m-portlet__head-caption">';
                        charthtml += '<div class="m-portlet__head-title">';
                            charthtml += '<span class="m-portlet__head-icon">';
                                charthtml += '<i class="flaticon-graph"></i>';
                            charthtml += '</span>';
                            charthtml += '<h3 class="m-portlet__head-text">';
                                charthtml += '<span>' + data['chart4'] + '</span>';
                            charthtml += '</h3>';
                        charthtml += '</div>';
                    charthtml += '</div>';
                charthtml += '</div>';
                charthtml += '<div class="m-portlet__body">';
                    charthtml += '<canvas id="antepartum_chart" style="width:100%"></canvas>';
                charthtml += '</div>';
            charthtml += '</div>';
        charthtml += '</div>';
		charthtml += '</div>';
		
		chartBody.empty().append(charthtml);
		
		tj.createReportCharts(tj.reportsId);
		
        tj.newCandidateReport.data.labels = data.canGraphData.labels;
        tj.newCandidateReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.blue).alpha(0.5).rgbString(),
            data:data.canGraphData.data
        }];
        tj.newCandidateReport.update();
    
        tj.outgoingMsgReport.data.labels = data.smsGraphData.labels;
        tj.outgoingMsgReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.blue).alpha(0.5).rgbString(),
            data:data.smsGraphData.data
        }];
        tj.outgoingMsgReport.update();
		
		tj.laborReport.data.labels = data.laborGraphData.labels;
        tj.laborReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.green).alpha(0.5).rgbString(),
            data:data.laborGraphData.data
        }];
        tj.laborReport.update();
		
		tj.postpartumReport.data.labels = data.postpartumGraphData.labels;
        tj.postpartumReport.data.datasets = [{
            backgroundColor:color(tj.chartColors.green).alpha(0.5).rgbString(),
            data:data.postpartumGraphData.data
        }];
        tj.postpartumReport.update();
		
    })
}

/////////////////////////////////////
// UPDATE REPORTS PAGE DATE
tj.updateDeliveryReports = function(start,end) {
	//if (tj.debug) {
    //    console.log('updating reports',start,end);
    //}
    tj.reportDates = {
        start:start,
        end:end
    }
    tj.getDeliveryReportData(start,end,function(data){
        //if (tj.debug) console.log('updateDeliveryReports - getDeliveryReportData',data)
        var color = Chart.helpers.color;
        tj.deliveryChart.data.labels = data.deliveryGraphData.labels;
        tj.deliveryChart.data.datasets = [{
            backgroundColor:color(tj.chartColors.blue).alpha(0.5).rgbString(),
            data:data.deliveryGraphData.data
        }];
        tj.deliveryChart.update();
    
        tj.deliveryChart2.data.labels = data.riskGraphData.labels;
        tj.deliveryChart2.data.datasets = [{
            backgroundColor:color(tj.chartColors.green).alpha(0.5).rgbString(),
            data:data.riskGraphData.data
        }];
		tj.deliveryChart2.update();
    })
}

/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.daterangepickerInit = function(reportsId,startPay,endPay,role) {
	
    if ($('#m_dashboard_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#m_dashboard_daterangepicker');
	
	if(role<8){
	var start = moment();
    var end = moment();
	}else{
	var start = moment().subtract(1, 'days');
    var end = moment().subtract(1, 'days');
	}	

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
		//console.log('cbId',reportsId);
		var locationId = $('#location').val();
		var categoryId = $('#filter').val();
		//console.log('categoryId',categoryId);
        var title = '';
        var range = '';
        if (label == 'Today' && sameDay(start.toDate(),end.toDate())) {
            title = 'Today:';
            range = start.format('MMM D');
        } else if (label == 'Yesterday') {
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-subheader__daterange-date').html(range);
        picker.find('.m-subheader__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),reportsId,categoryId,locationId);

    }
	if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
		
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().add(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);	
		
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}

    cb(start, end, '');
};

tj.updateUser2 = function() {
		var mobile = $('#activateMobile').val();
		var userId = $('#activateuserId').val();
		var times = $('#times').val();
		var report = $('#report').is(':checked') ? 1 : 0;
		var escalation = $('#escalation').is(':checked') ? 1 : 0;
		var missed = $('#missed').is(':checked') ? 1 : 0;
		var pause = $('#pause').is(':checked') ? 1 : 0;
		var emailAlerts = $('#emailAlerts').is(':checked') ? 1 : 0;
		var emailMissed = $('#emailMissed').is(':checked') ? 1 : 0;
		if(mobile.length !=10){
		bootbox.alert('Mobile Number must be 10 digits only (ie. 2145551234).');
		return;
		}
        $.ajax({
            url:'inc/data.php?req=updateUser2',
            data:{
                userId: userId,
				report: report,
				missed: missed,
				escalation: escalation,
				mobile: mobile,
				times: times,
				pause: pause,
				emailAlerts: emailAlerts,
				emailMissed: emailMissed
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(report==0 && escalation ==0 && missed==0 && pause==0 && emailAlerts==0 && emailMissed==0){
				bootbox.alert('Please configure which alerts you want.');
				}else{
				bootbox.alert('User profile updated successfully.');
				document.getElementById("report").checked = false;
				document.getElementById("escalation").checked = false;
				document.getElementById("missed").checked = false;
				document.getElementById("pause").checked = false;
				document.getElementById("emailAlerts").checked = false;
				document.getElementById("emailMissed").checked = false;
                $('#activate').modal('hide');
				}
				
            }
        })
    };
	
tj.categorySelect = function() {
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var start = $('#startDate').val();
		var end = $('#endDate').val();
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId);
		
    };
	
tj.locationSelect = function() {
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var start = $('#startDate').val();
		var end = $('#endDate').val();
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId);
		
    };
	
tj.activate = function(userId) {
		//var userId = $('#userId').val()
				       
        $.ajax({
            url:'inc/data.php?req=getUserDetailsActivate',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
								
				if (response.data.txtPause >0){
				document.getElementById("pause").checked = true;
				}else{
				document.getElementById("pause").checked = false;
				}
								
				if (response.data.txt >0){
				document.getElementById("report").checked = true;
				}else{
				document.getElementById("report").checked = false;	
				}
				if (response.data.reportMissed >0){
				document.getElementById("missed").checked = true;
				}else{
				document.getElementById("missed").checked = false;	
				}
				if (response.data.txtEscalation >0){
				document.getElementById("escalation").checked = true;
				}else{
				document.getElementById("escalation").checked = false;	
				}
				if (response.data.emailAlerts >0){
				document.getElementById("emailAlerts").checked = true;
				}else{
				document.getElementById("emailAlerts").checked = false;	
				}
				if (response.data.emailMissed >0){
				document.getElementById("emailMissed").checked = true;
				}else{
				document.getElementById("emailMissed").checked = false;	
				}
								
				$('#activateMobile').val(response.data.mobile);
                $('#activateuserId').val(response.data.id);
				$('#times').val(response.data.alertTimes);
				$('#activate').modal('toggle');
				}
        })
        //console.log(userId);
		
    };

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
tj.initializeUserGrid = function() {
    tj.UserTable = $('#UserTable').DataTable( {
        "ajax": "inc/data.php?req=getAllUsers",
        "order": [[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Name" },
			{ "data": "Unit" },
			{ "data": "Role" },
			{ "data": "Login" },
            { "data": "Locations" }
        ]
    } );
	
/////////////////////////////////////
// EDIT USER

tj.editUser = function(userId,deptId) {
		$('#unitTableMgr').DataTable().destroy();
			
        $.ajax({
            url:'inc/data.php?req=getUserDetails',
            data:{
                userId:userId,
				deptId:deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var a1 = document.getElementById('roleName');
				var a2 = document.getElementById('roleSelect');
				
				if ((response.user.userRole)<7) {
				a2.style.display='none';
				a1.style.display='';
				}
				
				if ((response.user.userRole)>6) {
				a2.style.display='';
				a1.style.display='none';
				}
				
				$('#ecf_user_email').val(response.data.email);
                $('#ecf_user_role').val(response.data.role);
				$('#roleName').html(response.data.roleName);
				$('#roleOrig').val(response.data.role);
                $('#edit_userId').val(response.data.userId);
				//$('#unitChange').val(response.data.depid);	
				$('#unitOrig').val(response.data.deptId);	
				//$('#updatealerts').val(response.data.Alerts);	
				$('#userName3').html(response.data.first_name+' '+response.data.last_name);
	            //$('#UserTable').DataTable().search('').draw();
				$('#edit_user').modal('show');
				
				tj.unitGrid(userId);
				}
        })
        
    }
	

tj.newUnit = function() {
	var role = $('#roleOrig').val();
	var userId = $('#edit_userId').val();
		$.ajax({
            url:'inc/data.php?req=getnewUnitDetails',
            data:{
				
           },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.data.count ==1){
					bootbox.alert('Adding this User to another Unit must be done either by the Unit Manager or Director.');
				}else{
				$('#newunituserId').val(userId);
				$('#userRole').val(role);
				$('#addnewUnit').modal('show');
				}
			}
        })
		
  }
  
 tj.newUnitAdd = function() {
	var deptId = $('#newunitdeptId').val();
	var userId = $('#newunituserId').val();
	var grantText = $('#newunitText').val();
	var role = $('#userRole').val();
	
	if(role==0){
	bootbox.alert('Please select a Role');
	return;
	}
	if(deptId==0){
	bootbox.alert('Please select a Unit');
	return;
	}
	
	if(role==6 || role==7){
	bootbox.confirm({
        message:"Adding this person as a Manager or Director to this Unit will remove the current Manager or Director.  <br>Is that what you want to do?",
		backdrop:true,
        callback:function (result) {
		if (result) {        
        $.ajax({
            url:'inc/data.php?req=newUnit',
            data:{
                deptId:deptId,
				userId:userId,
				grantText:grantText,
				role:role
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("newunitdeptId").selectedIndex = 0;
				document.getElementById("newunitText").selectedIndex = 0;
				$('#addnewUnit').modal('hide');
				tj.unitTable.ajax.reload();
				}
        });
		}
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
  }else{
	$.ajax({
            url:'inc/data.php?req=newUnit',
            data:{
                deptId:deptId,
				userId:userId,
				grantText:grantText,
				role:role
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("newunitdeptId").selectedIndex = 0;
				document.getElementById("newunitText").selectedIndex = 0;
				$('#addnewUnit').modal('hide');
				tj.unitTable.ajax.reload();
				}
    })  
  }

 } 
	 
tj.transferuserUnit = function(deptId,userId) {
	var user = userId;
	var dep = deptId;
		$.ajax({
            url:'inc/data.php?req=transferUnitDetails',
            data:{
				dep: dep,
				user: user
           },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.count.deptCount ==1){
				bootbox.alert('Transferring this User to another Unit must be done by the Director or Admin.  Adding this User to an additional Unit can be done by the Unit Manager for that Unit.');
				}else{
				$('#transferuserId').val(user);
				$('#deptIdOrig').val(deptId);
				$('#transferNewUnit').modal('show');
			
				}
			}
        })
}

  
tj.completeTransfer = function() {
	var userId = $('#transferuserId').val();
	var deptId = $('#transferdeptId').val();
	var deptIdOrig = $('#deptIdOrig').val();
	var grantText = $('#transferText').val();
	//console.log('user',userId);
				//console.log('dept',deptId);
				//console.log('deptOrig',deptIdOrig);
	
		$.ajax({
            url:'inc/data.php?req=completeTransfer',
            data:{
				userId:userId,
				deptId:deptId,
				deptIdOrig:deptIdOrig,
				grantText:grantText
			
           },
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("transferdeptId").value = "0";
				document.getElementById("transferText").value = "0";
				$('#transferNewUnit').modal('hide');
				tj.unitTable.ajax.reload();
				
			}
        })
        
  }
	
tj.updateUser = function() {
		var role = $('#ecf_user_role').val();
		var email = $('#ecf_user_email').val();
		var userId = $('#edit_userId').val();
		var unit = $('#unitChange').val();
		var unitOrig = $('#unitOrig').val();
		//var stop = $('#updatealerts').val();
		var roleOrig = $('#roleOrig').val();
		
		   $.ajax({
            url:'inc/data.php?req=updateUser',
            data:{
                email:email,
                role:role,
				roleOrig:roleOrig,
				userId:userId,
				unitOrig:unitOrig,
				unit:unit
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (role != roleOrig) {
				bootbox.alert('To change this persons role in a specfic Unit, you must use the Assign Unit button.');
				document.getElementById("roleOrig").value = role;
				}else{
				bootbox.alert('User updated successfully.');
				//document.getElementById("updatealerts").selectedIndex = "0";
				document.getElementById("ecf_user_role").selectedIndex = 4;
				//document.getElementById("unitChange").selectedIndex = 0;
				document.getElementById("edit_userId").value = "";
				document.getElementById("unitOrig").value = "";
				document.getElementById("ecf_user_email").value = "";
				document.getElementById("roleOrig").value = "";
				$('#edit_user').modal('hide');
				tj.UserTable.ajax.reload(null,false);
				}
				
            }
        });
}
	
	tj.deleteUser = function() {
		var userId = $('#edit_userId').val();
		bootbox.confirm({
        message:"Remove this user?",
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
				tj.UserTable.ajax.reload(null,false);
				//console.log('delete: ',userId);
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
}

tj.edittextStatus = function(textAlerts,recordId) {
		//console.log('alerts',textAlerts);
		//console.log('record',recordId);
		if (textAlerts == 1){
		bootbox.confirm({
        message:"Remove Text Alerts from this User?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateTextAlerts',
            data:{
                textAlerts:0,
				recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.UserTable.ajax.reload(null,false);
				tj.unitTable.ajax.reload();
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
			
	}else{
		bootbox.confirm({
        message:'Allow User to receive Text Alerts for this Unit? <br><br> NOTE: If User has not activated texting, they will need to do so before they can receive text alerts.',
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateTextAlerts',
            data:{
                textAlerts:1,
				recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//tj.unitTable.ajax.reload();				
				tj.UserTable.ajax.reload(null,false);
				tj.unitTable.ajax.reload();
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
}
}

tj.validateEmail = function(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

tj.startNewUser = function () {
        
		var first = $('#ecf_newuser_first').val();
		var last = $('#ecf_newuser_last').val();
		var email = $('#ecf_newuser_email').val();
		var role = $('#ecf_newuser_role').val();
		var unit = $('#unitAssign').val();
		var accountId = $('#accountAssign').val();
						
		if(first.length == 0 || last.length == 0){
		bootbox.alert('Name is required');
		return;
		}
		if(email.length == 0){
		bootbox.alert('Email is required');
		return;
		}
		if(role == 0){
		bootbox.alert('Role is required');
		return;
		}
		if(role >11 && accountId==0){
		bootbox.alert('Account is required for this Role');
		return;
		}
		if(role <=8 && unit ==0){
		bootbox.alert('Primary Unit is required');
		return;
		}
		
		if (tj.validateEmail(email)) {		
        
        $.ajax({
            url: 'inc/data.php?req=addNewUser',
            data: {
				first: first,
                last: last,
				email: email,
				role: role,
				unit: unit,
				accountId: accountId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if (response.data.exist == true) {
				$('#userMessage').html('Contact your ProductiveRN Admin to transfer this person into your Unit or give them access through your Unit Settings feature.');
				$('#usermsgType').html('User Already Exists');
				$('#confirmUser').modal('show');
				}else{
				$('#userMessage').html('User added successfully and email sent.');
				$('#usermsgType').html('Success');
				$('#confirmUser').modal('show');
				document.getElementById("ecf_newuser_first").value = "";
				document.getElementById("ecf_newuser_last").value = "";
				document.getElementById("ecf_newuser_email").value = "";
				document.getElementById("ecf_newuser_role").selectedIndex = 0;
				document.getElementById("unitAssign").selectedIndex = 0;
				document.getElementById("accountAssign").selectedIndex = 0;
				$('#addnewUser').modal('hide'); 
				tj.UserTable.ajax.reload(null,false);
				}
            }
		});
		
		}else{
			bootbox.alert('Invalid Email Address');
			return;
		}
		
}

tj.startNewAdmin = function () {
        
		var first = $('#admin_newuser_first').val();
		var last = $('#admin_newuser_last').val();
		var username = $('#admin_username').val();
		var role = $('#admin_newuser_role').val();
		//var unit = $('#unitAssign').val();
		var accountId = $('#adminaccountAssign').val();
						
		if(first.length == 0 || last.length == 0){
		bootbox.alert('Name is required');
		return;
		}
		if(username.length == 0){
		bootbox.alert('Username is required');
		return;
		}
		if(username.length < 8){
		bootbox.alert('Username must be at least 8 characters in length.');
		return;
		}
		if(role == 0){
		bootbox.alert('Role is required');
		return;
		}
		if(role ==11 && accountId==0){
		bootbox.alert('Account is required for this Role');
		return;
		}	
        
        $.ajax({
            url: 'inc/data.php?req=addNewAdmin',
            data: {
				first: first,
                last: last,
				username: username,
				role: role,
				accountId: accountId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if (response.data.exist == true) {
				bootbox.alert('Username already exists.');
				return;
				}else{
				bootbox.alert('User Successfully Added.  Temporary Password is: productive2019');
				document.getElementById("admin_newuser_first").value = "";
				document.getElementById("admin_newuser_last").value = "";
				document.getElementById("admin_username").value = "";
				document.getElementById("admin_newuser_role").selectedIndex = 0;
				//document.getElementById("unitAssign").selectedIndex = 0;
				document.getElementById("adminaccountAssign").selectedIndex = 0;
				$('#addnewAdmin').modal('hide'); 
				tj.UserTable.ajax.reload(null,false);
				}
            }
		})
		
}
}

/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.unitGrid = function(userId) {
    tj.unitTable = $('#unitTableMgr').DataTable( {
        "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getunitGrid",
                    data: {
                        userId: userId
                    }
                },
		"order": [[0,'asc']],
		"paging": false,
		"searching": false,
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
            { "data": "text" },
			{ "data": "role" },
            { "data": "action" }
        ]
    } );
}


tj.addNewUser = function () {
        
		var first = $('#ecf_newuser_first').val();
		var last = $('#ecf_newuser_last').val();
		var email = $('#ecf_newuser_email').val();
		var role = $('#ecf_newuser_role').val();
		var unit = $('#unitAssign').val();
		
		if(first.length == 0 || last.length == 0 || email.length == 0 || role == 0 || unit == 0){
		$('#userMessage').html('All fields are required.');
		$('#usermsgType').html('Alert');
		$('#confirmUser').modal('show');
        return;
  }
				
        
        $.ajax({
            url: 'inc/data.php?req=addNewUser',
            data: {
				first: first,
                last: last,
				email: email,
				role: role,
				unit: unit
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if (response.data.exist == true) {
					$('#userMessage').html('Contact your ProductiveRN Admin or current manager to transfer this person into your Unit.');
					$('#usermsgType').html('User Already Exists');
					$('#confirmUser').modal('show');
				}else{
					$('#userMessage').html('User added successfully and email sent.');
					$('#usermsgType').html('Success');
					$('#confirmUser').modal('show');
					document.getElementById("ecf_newuser_first").value = "";
					document.getElementById("ecf_newuser_last").value = "";
					document.getElementById("ecf_newuser_email").value = "";
					document.getElementById("ecf_newuser_role").selectedIndex = "";
					$('#addnewUser').modal('hide');
					tj.UserTable.ajax.reload(null,false);
				}
            }
        });

		
}


/////////////////////////////////////
// FORGOT PASSWORD

tj.forgot = function() {
   var email = $('#forgotemail').val();
   $.ajax({
       url:'inc/data.php?req=forgot',
       data: {
          email:email
       },
       success:function(data) {
		   alert('Please check your email for your temporary password.');
		   $('#forgot').modal('toggle');
		}
   })
}

tj.forgotPrivate = function() {
   var email = $('#forgotemail').val();
   $.ajax({
       url:'../inc/data.php?req=forgot',
       data: {
          email:email
       },
       success:function(data) {
		   alert('Please check your email for your temporary password.');
		   $('#forgot').modal('toggle');
		}
   })
}

/////////////////////////////////////
// FORGOT PASSWORD2

tj.forgot2 = function() {
   var email = $('#ecf_user_email').val();
   bootbox.confirm({
        message:"Reset password for this User?",
        backdrop:true,
        callback:function (result) {
	if (result) {
   $.ajax({
       url:'inc/data.php?req=forgot',
       data: {
          email:email
       },
       success:function(data) {
		   bootbox.alert('An email has been sent to the User with a temporary password.');
		}
   })
		}
      }
    });
}

/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.ConfigureGrid = function(deptId) {
    tj.configureTable = $('#resourceTableMgr').DataTable( {
        "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=getConfigure",
                    data: {
                        deptId: deptId
                    }
                },
		"order": [[0,'asc']],
		"paging": false,
		"searching": false,
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "position" },
            { "data": "sunday" },
			{ "data": "monday" },
            { "data": "tuesday" },
			{ "data": "wednesday" },
            { "data": "thursday" },
			{ "data": "friday" },
            { "data": "saturday" },
			{ "data": "action" }
        ]
    } );
	tj.configuretextTable = $('#textTableMgr').DataTable( {
        "ajax": {
                    type:"GET",
                    url:"inc/data.php?req=gettextConfigure",
                    data: {
                        deptId: deptId
                    }
                },
		"order": [[0,'asc']],
		"paging": false,
		"searching": false,
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "user" },
            { "data": "role" },
			{ "data": "status" },
			{ "data": "action" }
        ]
    } );
}

tj.getConfig = function() {
		$.ajax({
            url:'inc/data.php?req=getConfigDetails',
            data:{
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#depName').val(response.data.dept);
				$('#unitId').val(response.data.unitId);
				$('#totalbeds').val(response.data.totalbeds);
				$('#prodMeasure').val(response.data.prodMeasure);
				$('#prodValue').val(response.data.hppd);
				$('#target').val(response.data.target);
				$('#deptId').val(response.data.id);
				$('#accountId').val(response.data.accountId);
				$('#dept').val(response.data.id);
				$('#account').val(response.data.accountId);
				$('#censusShift').val(response.data.shift);
				$('#descConfig1').val(response.data.desc1);
				$('#descConfig2').val(response.data.desc2);
				$('#descConfig3').val(response.data.desc3);
				$('#descConfig4').val(response.data.desc4);
				$('#descConfig5').val(response.data.desc5);
				$('#descConfig6').val(response.data.desc6);
				if (response.data.oneto1 ==1){
				document.getElementById("one2oneChecked").checked = true; 
				}else{
				document.getElementById("one2oneChecked").checked = false;
				}				
				if (response.data.oneto2 ==1){
				document.getElementById("one2twoChecked").checked = true; 
				}else{
				document.getElementById("one2twoChecked").checked = false;
				}				
				if (response.data.oneto3 ==1){
				document.getElementById("one2threeChecked").checked = true; 
				}else{
				document.getElementById("one2threeChecked").checked = false;
				}				
				if (response.data.oneto4 ==1){
				document.getElementById("one2fourChecked").checked = true; 
				}else{
				document.getElementById("one2fourChecked").checked = false;
				}				
				if (response.data.oneto5 ==1){
				document.getElementById("one2fiveChecked").checked = true; 
				}else{
				document.getElementById("one2fiveChecked").checked = false;
				}				
				if (response.data.oneto6 ==1){
				document.getElementById("one2sixChecked").checked = true; 
				}else{
				document.getElementById("one2sixChecked").checked = false;
				}				
				$('#configureView').show();
				//tj.prodTable.ajax.reload();
				}
        })
       
  }
  

  
 /////////////////////////////////////
// GET RESOURCE DETAILS

tj.editResource = function(recordId) {
        
        $.ajax({
            url:'inc/data.php?req=getResourceDetails',
            data:{
                recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#editName').val(response.data.position);
				$('#resourcevalue1').val(response.data.resourcevalue);
				$('#recordId').val(response.data.id);
				$('#sunEdit').val(response.data.Sun);
				$('#monEdit').val(response.data.Mon);
				$('#tueEdit').val(response.data.Tue);
				$('#wedEdit').val(response.data.Wed);
				$('#thuEdit').val(response.data.Thu);
				$('#friEdit').val(response.data.Fri);
				$('#satEdit').val(response.data.Sat);                
				$('#editResource').modal('show');
				}
        })
        //console.log(recordId);
		
    }
	
 /////////////////////////////////////
// GET RESOURCE DETAILS

tj.edittextStatus = function(recordId) {
        
        $.ajax({
            url:'inc/data.php?req=gettextResourceDetails',
            data:{
                recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#updategrantText').val(response.data.textAlerts);
				$('#updaterecordId').val(response.data.id);
				$('#updatetextStatus').modal('show');
				}
        })
        //console.log(recordId);
		
    }
	
 /////////////////////////////////////
// ADD RESOURCE

tj.addResource = function() {
	var deptId = $('#deptId').val();
	var accountId = $('#accountId').val();
	var name = $('#resourceName').val();
	var resourcevalue = $('#resourcevalue').val();
	var sun = $('#sunHours').val();
	var mon = $('#monHours').val();
	var tue = $('#tueHours').val();
	var wed = $('#wedHours').val();
	var thu = $('#thuHours').val();
	var fri = $('#friHours').val();
	var sat = $('#satHours').val();
	//var a1 = document.getElementById('unitDetails');
        
        $.ajax({
            url:'inc/data.php?req=addResource',
            data:{
                deptId:deptId,
				accountId:accountId,
				name:name,
				sun:sun,
				mon:mon,
				tue:tue,
				wed:wed,
				thu:thu,
				fri:fri,
				sat:sat,
				resourcevalue: resourcevalue
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Resource added successfully.');
				document.getElementById("resourceName").value = "";
				document.getElementById("resourcevalue").value = "";
				document.getElementById("sunHours").selectedIndex = "0";
				document.getElementById("monHours").selectedIndex = "0";
				document.getElementById("tueHours").selectedIndex = "0";
				document.getElementById("wedHours").selectedIndex = "0";
				document.getElementById("thuHours").selectedIndex = "0";
				document.getElementById("friHours").selectedIndex = "0";
				document.getElementById("satHours").selectedIndex = "0";
				$('#addResource').modal('hide');
				//setTimeout(function(){a1.focus();}, 1);
				tj.configureTable.ajax.reload();
				//window.scrollTo(0,document.body.scrollHeight);
				}
        })
        //console.log(recordId);
	
    }
	

tj.addnewResource = function() {
	var unitId = $('#unitIdMgr').val();
	//var e1 = document.getElementById('editUnitMgr');
		$.ajax({
            url:'inc/data.php?req=getConfigDetails',
            data:{
				unitId: unitId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#deptId').val(response.data.id);
				$('#addResource').modal('show');
				//tj.prodTable.ajax.reload();
				}
        })
      
  }
  
 tj.addServiceLine = function() {
	var serviceName = $('#serviceName').val();
	//var e1 = document.getElementById('editUnitMgr');
		$.ajax({
            url:'inc/data.php?req=addserviceLine',
            data:{
				serviceName: serviceName
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#addCategory').modal('hide');
				if(response.data.message == false){
					bootbox.alert('This Service Line already exists.');
				}else{
				$("#serviceLine").append($('<option>', {value: response.data.serviceNum,text: response.data.serviceName}));
				}//tj.prodTable.ajax.reload();
				}
        })
      
  }

	
 /////////////////////////////////////
// GET RESOURCE DETAILS

tj.updateResource = function() {
	var recordId = $('#recordId').val();
	var resourcevalue = $('#resourcevalue1').val();
	var name = $('#editName').val();
	var sun = $('#sunEdit').val();
	var mon = $('#monEdit').val();
	var tue = $('#tueEdit').val();
	var wed = $('#wedEdit').val();
	var thu = $('#thuEdit').val();
	var fri = $('#friEdit').val();
	var sat = $('#satEdit').val();
        
        $.ajax({
            url:'inc/data.php?req=updateResource',
            data:{
                recordId:recordId,
				name:name,
				resourcevalue: resourcevalue,
				sun:sun,
				mon:mon,
				tue:tue,
				wed:wed,
				thu:thu,
				fri:fri,
				sat:sat
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Resource updated successfully.');
				//tj.configureTable.rows().deselect();
				$('#editResource').modal('hide');
				tj.configureTable.ajax.reload();
				}
        })
        //console.log(recordId);
		
    }
	
 /////////////////////////////////////
// GET RESOURCE DETAILS

tj.updatetextResource = function() {
	var recordId = $('#updaterecordId').val();
	var updateval = $('#updategrantText').val();
        
        $.ajax({
            url:'inc/data.php?req=updatetextResource',
            data:{
                recordId:recordId,
				updateval:updateval
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Record updated successfully.');
				//tj.configureTable.rows().deselect();
				$('#updatetextStatus').modal('hide');
				tj.configuretextTable.ajax.reload();
				}
        })
        //console.log(recordId);
		
    }
	
 /////////////////////////////////////
// GET RESOURCE DETAILS

tj.updateConfig = function() {
	var deptId = $('#dept').val();
	var accountId = $('#account').val();
	var target = $('#target').val();
	var prodMeasure = $('#prodMeasure').val();
	var prodValue = $('#prodValue').val();
	var censusShift = $('#censusShift').val();
	var one2one = $('#one2oneChecked:checked').val();
	var desc1 = $('#descConfig1').val();
	var one2two = $('#one2twoChecked:checked').val();
	var desc2 = $('#descConfig2').val();
	var one2three = $('#one2threeChecked:checked').val();
	var desc3 = $('#descConfig3').val();
	var one2four= $('#one2fourChecked:checked').val();
	var desc4 = $('#descConfig4').val();
	var one2five = $('#one2fiveChecked:checked').val();
	var desc5 = $('#descConfig5').val();
	var one2six = $('#one2sixChecked:checked').val();
	var desc6 = $('#descConfig6').val();
	var deptName = $('#depName').val();
	var unitId = $('#unitId').val();
	var totalbeds = $('#totalbeds').val();
	
        
        $.ajax({
            url:'inc/data.php?req=updateConfig',
            data:{
				deptName:deptName,
				unitId:unitId,
				totalbeds:totalbeds,
                deptId:deptId,
				accountId:accountId,
				target:target,
				prodMeasure:prodMeasure,
				prodValue:prodValue,
				censusShift:censusShift,
				one2one:one2one,
				desc1:desc1,
				one2two:one2two,
				desc2:desc2,
				one2three:one2three,
				desc3:desc3,
				one2four:one2four,
				desc4:desc4,
				one2five:one2five,
				desc5:desc5,
				one2six:one2six,
				desc6:desc6
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Configuration updated successfully.');
				tj.getConfig();
				$('html,body').scrollTop(0);
				//$('#configureView').hide();
				//tj.reportsLoaded = false;
				//tj.loadReports2();				
				}
        })
        //console.log(recordId);
		
    }
  
   /////////////////////////////////////
// REMOVE USER FROM LOCATION
tj.deleteResource = function (recordId) {
	var a1 = document.getElementById('resourceTableMgr');
    var winHeight = $(window).height();
    bootbox.confirm({
        message:"Remove this Resource from your Productivity?",
        backdrop:true,
        callback:function (result) {
            if (result) {
				setTimeout(function(){a1.focus();}, 1);
                $.ajax({
                    url: 'inc/data.php?req=removeResource',
                    data: {
                        recordId: recordId
                    },
                    method: 'post',
                    success: function (response) {
                        //console.log(response);
						tj.configureTable.ajax.reload();
						//window.scrollTo(0,document.body.scrollHeight);
						//$('#editUnitMgr').modal('show');
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

tj.optin = function() {
        var mobile = $('#ecf_mobile').val();
		var id = $('#userId').val();
				
		$.ajax({
            url: 'inc/data.php?req=opt',
			dataType: 'json',
            method: 'post',
            data: {
				mobile: mobile,
				id: id
            },
            success: function(data) {
				
			bootbox.alert('ProductiveRN Text Alerts have been activated.  You will receive a confirmation text shortly.  Please select which alerts you want to receive and when you want to receive them.  <br><br>Message and data rates may apply.  Reply STOP to Cancel or update your ProductiveRN User Profile.');	
                //$('#confirm').modal('toggle');
            }
        });
    };
	
tj.optin2 = function() {
        var mobile = $('#activateMobile').val();
		var id = $('#activateuserId').val();
		//var report = $('#report:checked').val();
		var report = $('#report').is(':checked') ? 1 : 0;
		//var missed = $('#missed:checked').val();
		var missed = $('#missed').is(':checked') ? 1 : 0;
		//var escalation = $('#escalation:checked').val();
		var escalation = $('#escalation').is(':checked') ? 1 : 0;
		var times = $('#times').val();
		var pause = $('#pause:checked').val();
		var a1 = document.getElementById('activate');
		//console.log('length',mobile.length);
		//console.log('mobile',mobile);
		
	if(mobile.length !=10){
    bootbox.alert('Mobile Number must be 10 digits only (ie. 2145551234).');
	return;
	
	}
	if(report==0 && escalation==0 && missed==0){
				alert('Please select which Text Alerts you want to receive then click Activate.');
				 return;
				}
		$.ajax({
            url: 'inc/data.php?req=opt',
			dataType: 'json',
            method: 'post',
            data: {
				mobile: mobile,
				id: id,
				report: report,
				missed: missed,
				escalation: escalation,
				times: times,
				pause: pause
            },
            success: function(data) {
			$('#activate').modal('hide');
			bootbox.alert('ProductiveRN Text Alerts have been activated.  You will receive a confirmation text shortly.  Message and data rates may apply.  Reply STOP to Cancel or update your ProductiveRN User Profile.');	
            
            }
        });
    };

function validateOptin(node) {
    if (!node.value) {
        alert('Please select which Text Alerts you want to receive then click Activate.');
        //setTimeout(function(){node.focus();}, 1);
    }
}


tj.addtextResource = function() {
	var deptId = $('#textdeptId').val();
	var userId = $('#textuserId').val();
	var grantText = $('#grantText').val();
     
        $.ajax({
            url:'inc/data.php?req=addtextResource',
            data:{
                deptId:deptId,
				userId:userId,
				grantText:grantText
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.message == false){
				alert('This User already has access to this Unit.  Go to the Users tab to make updates.');
				}
				document.getElementById("textuserId").selectedIndex = "0";
				document.getElementById("grantText").selectedIndex = "0";
				$('#addtextResource').modal('hide');
				//$('#editUnitMgr').modal('toggle');
				//setTimeout(function(){document.getElementById("editUnitMgr").focus();}, 1);
				tj.configuretextTable.ajax.reload();
				
				}
        })
 }
 
tj.transferResource = function() {
	var deptId = $('#transferuserId').val();
	var userId = $('#transferdeptId').val();
	var grantText = $('#transferText').val();
        
        $.ajax({
            url:'inc/data.php?req=transferResource',
            data:{
                deptId:deptId,
				userId:userId,
				grantText:grantText
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('User transfer successfully.');
				document.getElementById("transferdeptId").selectedIndex = "0";
				document.getElementById("transferText").selectedIndex = "0";
				$('#transferUnit').modal('hide');
				tj.configuretextTable.ajax.reload();
				}
        })
 }

	tj.addnewtextResource = function() {
	//var e1 = document.getElementById('editUnitMgr');
	//setTimeout(function(){e1.focus();}, 1);
	var unitId = $('#unitIdMgr').val();
				$('#textdeptId').val(unitId);
				$('#addtextResource').modal('show');
	}
  
  tj.deletetextResource = function (recordId) {
    var winHeight = $(window).height();
    bootbox.confirm({
        message:"Remove this User from the Unit?",
        backdrop:true,
        callback:function (result) {
            if (result) {
                $.ajax({
                    url: 'inc/data.php?req=removetextResource',
                    data: {
                        recordId: recordId
                    },
                    method: 'post',
                    success: function (response) {
                        //console.log(response);
                        tj.configuretextTable.ajax.reload();
						//var e1 = document.getElementById('editUnitMgr');
						//setTimeout(function(){e1.focus();}, 1);
						//tj.unitTable.ajax.reload();
						//window.scrollTo(0,document.body.scrollHeight);
						//$('#editUnitMgr').modal('show');
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

tj.removeuserUnit = function (dept,user) {
	//var deptId = deptId;
	//var userId = userId;
	var winHeight = $(window).height();
	//var movefocus = document.getElementById("edit_user");
	bootbox.confirm({
        message:"Remove the User from the Unit?",
        backdrop:true,
        callback:function (result) {
            if (result) {
                $.ajax({
                    url: 'inc/data.php?req=removeUnit',
                    data: {
                        dept: dept,
						user: user
                    },
                    method: 'post',
                    success: function (response) {
						tj.unitTable.ajax.reload();
                        tj.UserTable.ajax.reload(null,false);
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



/*
UNITS
   ______  ___  _____  _____
   | ___ \/ _ \|  __ \|  ___|
   | |_/ / /_\ \ |  \/| |__
   |  __/|  _  | | __ |  __|
   | |   | | | | |_\ \| |___
   \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// UNITS GLOBALS
tj.unitID = 0;

/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.initializeUnitsGrid = function(id) {
    tj.UnitsTable = $('#UnitsTable').DataTable( {
        "ajax": "inc/data.php?req=getAllUnits",
        "order": [[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Name" },
			{ "data": "Category" },
            { "data": "Number" },
			{ "data": "ProdName" },
            { "data": "Value" },
			{ "data": "Manager" }
        ]
    } );
	

	
/////////////////////////////////////
// EDIT UNIT

tj.editUnit = function(unitId) {
    
        $.ajax({
            url:'inc/data.php?req=getUnitDetails',
            data:{
                unitId:unitId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#editunitName').val(response.data.dept);
				$('#editunitNumber').val(response.data.unitId);
                $('#unitId').val(response.data.id);
                $('#editunitTarget').val(response.data.target);	
				$('#editunitBeds').val(response.data.totalbeds);
				$('#editunitprodMeasure').val(response.data.prodMeasure);
				$('#editunitprodValue').val(response.data.hppd);
				$('#editcensusShift').val(response.data.shift);
				$('#currentManager').html('<strong>'+response.data.last_name+', '+response.data.first_name+'</strong>');
				//$('#currentDirector').html('<strong>'+response.data.dir_last_name+', '+response.data.dir_first_name+'</strong>');
	            $('#UnitsTable').DataTable().search('').draw();
				$('#editUnit').modal('show');
				}
        })
        
    }
	
tj.editUnitMgr = function(deptid,Role) {
		var deptId = deptid; 
		$('#resourceTableMgr').DataTable().destroy();
		$('#textTableMgr').DataTable().destroy();
		$.ajax({
            url:'inc/data.php?req=getUnitDetails',
            data:{
                deptId:deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var zero = 0;
				var role = Role;
				//var a1 = document.getElementById('unitmgr');	
				//var a2 = document.getElementById('unitdir');
				var a3 = document.getElementById('nursing1');
				var a4 = document.getElementById('nursing3');
				var a5 = document.getElementById('nursing2');
				var a6 = document.getElementById('skill');
				var a7 = document.getElementById('budget');
				var a8 = document.getElementById('uosDescription');
				var a9 = document.getElementById('nurse2');
				var a10 = document.getElementById('unitList');
				var a11 = document.getElementById('unitDetails');
				var a12 = document.getElementById('nursing4');
	
				var measure = response.data.prodMeasure;
				
				if (deptId>0){
					a11.style.display='';
					a10.style.display='none';
				}else{
					a11.style.display='none';
					a10.style.display='';
				}
				
				//if (role !=7 && role <12) {
				//a1.style.display='none';
				//}
				//if (role <15) {
				//a2.style.display='none';
				//}
				if (response.data.prodMeasure ==2) {
				a3.style.display='none';
				a4.style.display='none';
				a5.style.display='none';
				a9.style.display='none';
				a12.style.display='none';
				a6.style.display='';
				a7.style.display='';
				a8.style.display='';
				}else{
				a3.style.display='';
				a4.style.display='';
				a5.style.display='';
				a7.style.display='none';
				a6.style.display='none';
				a8.style.display='none';
				a9.style.display='';
				a12.style.display='';
				}
				if((response.data.last_name) === undefined) {
					$('#currentManagerMgr').html('');
				}else{
					$('#currentManagerMgr').html('<strong>'+response.data.last_name+', '+response.data.first_name+'</strong>');
				}
				if((response.data2.last_name2) === undefined){
					$('#currentDirector').html('');
				}else{
					$('#currentDirector').html('<strong>'+response.data2.last_name2+', '+response.data2.first_name2+'</strong>');
				} 
				$('#editunitNameMgr').val(response.data.unitName);
				$('#serviceLine').val(response.data.category);
				$('#addEscalations').val(response.data.escalations);
				$('#editunitNumberMgr').val(response.data.unitId);
                $('#unitIdMgr').val(response.data.id);
				$('#shiftsDay').val(response.data.shiftsperDay);
				$('#shiftsOrig').val(response.data.shiftsperDay);
                $('#editunitTargetMgr').val(response.data.target);	
				$('#editunitBedsMgr').val(response.data.totalbeds);
				$('#editunituosDescMgr').val(response.data.uosDesc);
				$('#editunitprodMeasureMgr').val(response.data.prodMeasure);
				$('#prodDesc').html(response.data.prodDesc);
				$('#editunitprodValueMgr').val(response.data.hppd);
				//$('#currentManagerMgr').html('<strong>'+response.data.last_name+', '+response.data.first_name+'</strong>');
				//$('#currentDirector').html('<strong>'+response.data2.last_name+', '+response.data2.first_name+'</strong>');
				
				if((response.data.userId) === undefined) {
				$('#mgrOrig').val(zero);
				}else{
				$('#mgrOrig').val(response.data.userId);
				}
				if((response.data2.userId2) === undefined) {
				$('#dirOrig').val(zero);
				}else{
				$('#dirOrig').val(response.data2.userId2);
				}			
				
				$('#editcensusShiftMgr').val(response.data.shift);
	            $('#UnitsTableMgr').DataTable().search('').draw();
				$('#descConfig1Mgr').val(response.data.desc1);
				$('#descConfig2Mgr').val(response.data.desc2);
				$('#descConfig3Mgr').val(response.data.desc3);
				$('#descConfig4Mgr').val(response.data.desc4);
				$('#descConfig5Mgr').val(response.data.desc5);
				$('#descConfig6Mgr').val(response.data.desc6);
				if (response.data.oneto1 ==1){
				document.getElementById("one2oneCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2oneCheckedMgr").checked = false;
				}				
				if (response.data.oneto2 ==1){
				document.getElementById("one2twoCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2twoCheckedMgr").checked = false;
				}				
				if (response.data.oneto3 ==1){
				document.getElementById("one2threeCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2threeCheckedMgr").checked = false;
				}				
				if (response.data.oneto4 ==1){
				document.getElementById("one2fourCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2fourCheckedMgr").checked = false;
				}				
				if (response.data.oneto5 ==1){
				document.getElementById("one2fiveCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2fiveCheckedMgr").checked = false;
				}				
				if (response.data.oneto6 ==1){
				document.getElementById("one2sixCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2sixCheckedMgr").checked = false;
				}
				$('#skilldesc1').val(response.data.skilldesc1);
				$('#skilldesc2').val(response.data.skilldesc2);
				$('#skilldesc3').val(response.data.skilldesc3);
				$('#skilldesc4').val(response.data.skilldesc4);
				$('#skilldesc5').val(response.data.skilldesc5);
				
				$('#skillbudget1').val(response.data.skillbudget1);
				$('#skillbudget2').val(response.data.skillbudget2);
				$('#skillbudget3').val(response.data.skillbudget3);
				$('#skillbudget4').val(response.data.skillbudget4);
				$('#skillbudget5').val(response.data.skillbudget5);
				
				if (response.data.skill1 ==1){
				document.getElementById("skill1").checked = true; 
				}else{
				document.getElementById("skill1").checked = false;
				}				
				if (response.data.skill2 ==1){
				document.getElementById("skill2").checked = true; 
				}else{
				document.getElementById("skill2").checked = false;
				}				
				if (response.data.skill3 ==1){
				document.getElementById("skill3").checked = true; 
				}else{
				document.getElementById("skill3").checked = false;
				}				
				if (response.data.skill4 ==1){
				document.getElementById("skill4").checked = true; 
				}else{
				document.getElementById("skill4").checked = false;
				}				
				if (response.data.skill5 ==1){
				document.getElementById("skill5").checked = true; 
				}else{
				document.getElementById("skill5").checked = false;
				}
				
				$('#thresholdLow').val(response.data.thresholdLow);
				$('#thresholdHigh').val(response.data.thresholdHigh);
				$('#budgetValue').val(response.data.uosValue);
				$('#inshiftProd').val(response.data.inshiftProd);
				$('#rnThreshold').val(response.data.rnThreshold);
				$('#budgetMeasure').val(response.data.budgetMeasure);
				$('#churn').val(response.data.churn);
				//$('#editUnitMgr').modal('show');
				tj.ConfigureGrid(deptId);
				//tj.ConfiguretextGrid(unit);
				}
        })
        
    }	

tj.updateUnit = function() {
		var name = $('#editunitName').val();
		var unitId = $('#unitId').val();
		var unitNumber = $('#editunitNumber').val();
		var unitTarget = $('#editunitTarget').val();
		var bedCount = $('#editunitBeds').val();
		var prodMeasure = $('#editunitprodMeasure').val();
		var value = $('#editunitprodValue').val();
		var censusShift = $('#editcensusShift').val();
		var oldMgr = $('#currentManagerId').val();
		var newMgr = $('#unitManager').val();
		
        $.ajax({
            url:'inc/data.php?req=updateUnit',
            data:{
                name: name,
				unitId: unitId,
				unitTarget: unitTarget,
                unitNumber: unitNumber,
				bedCount: bedCount,
				prodMeasure: prodMeasure,
				value: value,
				newMgr: newMgr,
				censusShift: censusShift
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Unit updated successfully.');
				document.getElementById("unitManager").selectedIndex = "0";
                $('#editUnit').modal('hide');
				tj.UnitsTable.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports2();
				//console.log('unit',unitId);
            }
        })
    }
	
tj.updateUnitMgr = function() {
		var name = $('#editunitNameMgr').val();
		var deptId = $('#unitIdMgr').val();
		var escalations = $('#addEscalations').val();
		var unitNumber = $('#editunitNumberMgr').val();
		var unitTarget = $('#editunitTargetMgr').val();
		var uosDesc = $('#editunituosDescMgr').val();
		var bedCount = $('#editunitBedsMgr').val();
		var serviceLine = $('#serviceLine').val();
		var prodMeasure = $('#editunitprodMeasureMgr').val();
		var value = $('#editunitprodValueMgr').val();
		var censusShift = $('#editcensusShiftMgr').val();
		var shiftsDay = $('#shiftsDay').val();
		var one2one = $('#one2oneCheckedMgr:checked').val();
		var desc1 = $('#descConfig1Mgr').val();
		var one2two = $('#one2twoCheckedMgr:checked').val();
		var desc2 = $('#descConfig2Mgr').val();
		var one2three = $('#one2threeCheckedMgr:checked').val();
		var desc3 = $('#descConfig3Mgr').val();
		var one2four= $('#one2fourCheckedMgr:checked').val();
		var desc4 = $('#descConfig4Mgr').val();
		var one2five = $('#one2fiveCheckedMgr:checked').val();
		var desc5 = $('#descConfig5Mgr').val();
		var one2six = $('#one2sixCheckedMgr:checked').val();
		var desc6 = $('#descConfig6Mgr').val();
		var newMgr = $('#unitManagerMgr').val();
		var newDir = $('#unitDirector').val();
		var mgrOrig = $('#mgrOrig').val();
		var dirOrig = $('#dirOrig').val();
		var shiftsOrig = $('#shiftsOrig').val();
		var skill1 = $('#skill1:checked').val();
		var skilldesc1 = $('#skilldesc1').val();
		var skill2 = $('#skill2:checked').val();
		var skilldesc2 = $('#skilldesc2').val();
		var skill3 = $('#skill3:checked').val();
		var skilldesc3 = $('#skilldesc3').val();
		var skill4 = $('#skill4:checked').val();
		var skilldesc4 = $('#skilldesc4').val();
		var skill5 = $('#skill5:checked').val();
		var skilldesc5 = $('#skilldesc5').val();
		var churn = $('#churn').val();
		var budgetMeasure = $('#budgetMeasure').val();
		var budgetValue = $('#budgetValue').val();
		var thresholdLow = $('#thresholdLow').val();
		var thresholdHigh = $('#thresholdHigh').val();
		var skillbudget1 = $('#skillbudget1').val();
		var skillbudget2 = $('#skillbudget2').val();
		var skillbudget3 = $('#skillbudget3').val();
		var skillbudget4 = $('#skillbudget4').val();
		var skillbudget5 = $('#skillbudget5').val();
		var u1 = document.getElementById('unitList');
		var u2 = document.getElementById('unitDetails');
		
		var rnThreshold = $('#rnThreshold').val();
		
		if(censusShift==0){
			var inshiftProd=0;
		}else{
			var inshiftProd = $('#inshiftProd').val();
		}
						
        $.ajax({
            url:'inc/data.php?req=updateUnitMgr',
            data:{
                name: name,
				deptId: deptId,
				unitTarget: unitTarget,
                unitNumber: unitNumber,
				bedCount: bedCount,
				prodMeasure: prodMeasure,
				value: value,
				censusShift: censusShift,
				shiftsDay: shiftsDay,
				one2one: one2one,
				desc1: desc1,
				one2two: one2two,
				desc2: desc2,
				one2three: one2three,
				desc3: desc3,
				one2four: one2four,
				desc4: desc4,
				one2five: one2five,
				desc5: desc5,
				one2six: one2six,
				desc6: desc6,
				newMgr: newMgr,
				newDir: newDir,
				mgrOrig: mgrOrig,
				dirOrig: dirOrig,
				shiftsOrig: shiftsOrig,
				escalations: escalations,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				skill5: skill5,
				skilldesc1: skilldesc1,
				skilldesc2: skilldesc2,
				skilldesc3: skilldesc3,
				skilldesc4: skilldesc4,
				skilldesc5: skilldesc5,
				thresholdLow: thresholdLow,
				thresholdHigh: thresholdHigh,
				churn: churn,
				uosDesc: uosDesc,
				budgetMeasure: budgetMeasure,
				budgetValue: budgetValue,
				skillbudget1: skillbudget1,
				skillbudget2: skillbudget2,
				skillbudget3: skillbudget3,
				skillbudget4: skillbudget4,
				skillbudget5: skillbudget5,
				serviceLine: serviceLine,
				inshiftProd: inshiftProd,
				rnThreshold: rnThreshold
				
			},
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("unitDirector").selectedIndex = "0";
				//document.getElementById("alsoText").selectedIndex = "";
				document.getElementById("unitManagerMgr").selectedIndex = "0";
				bootbox.alert('Unit updated successfully.');
				$('#editUnitMgr').modal('hide');
				//tj.UnitsTable.ajax.reload();
				tj.reportsLoaded = false;
				tj.loadReports2();
				u1.style.display='';
				u2.style.display='none';
				tj.UnitsTable.ajax.reload(null,false);
				//tj.unitsLoaded=false;
				//tj.loadUnits(null,false);
            }
        })
    }
	
tj.cancelUnit = function() {
	$('#m_aside_left_close_btn').click();
	var a10 = document.getElementById('unitList');
	var a11 = document.getElementById('unitDetails');
	a11.style.display='none';
	a10.style.display='';
	//tj.unitsLoaded=false;
	//tj.loadUnits();
}
	
tj.deleteUnit = function() {
		var unitId = $('#unitId').val();
		bootbox.confirm({
        message:"Remove this Unit?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=deleteUnit',
            data:{
                unitId:unitId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {				
				$('#edit_unit').modal('hide');
				tj.UnitsTable.ajax.reload(null,false);
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
}
	
tj.addUnit = function () {
        
		var name = $('#unitName').val();
		var number = $('#unitNumber').val();
		var bedCount = $('#unitBeds').val();
		var unitTarget = $('#unitTarget').val();
		var prodMeasure = $('#unitprodMeasure').val();
		var value = $('#unitprodValue').val();
		var uosDesc = $('#uosDesc').val();
		var director = $('#newDirector').val();
		var manager = $('#newManager').val();
		var role = $('#userRole').val();
		var accountId = $('#accountSelect').val();
		
				
    if(name.length ==0){
		bootbox.alert('Unit Name is required.');
    return;
		}
	if(role >89 && accountId ==0){
		bootbox.alert('Select an Account.');
    return;
		}
	if(number.length == 0){
		bootbox.alert('Unit Number is required.');
		return;
		}
	if(prodMeasure == 0 ){
		bootbox.alert('Productivity Measure is required.');
		return;
		}
	if(bedCount.length == 0 && prodMeasure == 1){
		bootbox.alert('Bed Count is required for this Unit.');
		return;
		}
	if(prodMeasure > 0 && prodMeasure !=3 && value.length == 0){
		bootbox.alert('Please enter your HPPD or WHPUOS value.');
		return;
		}
	if(prodMeasure==2){
		var shiftsDay = 2;
		var censusShift = 0;
	}else{
		var shiftsDay = 6;
		var censusShift = 52;
	}
		
        
        $.ajax({
            url: 'inc/data.php?req=addNewUnit',
            data: {
				unitName: name,
                unitNumber: number,
				unitBeds: bedCount,
				unitTarget: unitTarget,
				unitprodMeasure: prodMeasure,
				unitprodValue: value,
				uosDesc: uosDesc,
				censusShift: censusShift,
				shiftsDay: shiftsDay,
				director: director,
				manager: manager,
				accountId: accountId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if(response.exists ==true){
				bootbox.alert('Unit/Dept. Already Exists.');
                }else if(response.exists == false && prodMeasure ==1){
				bootbox.alert('Unit successfully added.  <br><br>Please go to Unit Settings to configure Acuity Levels, Census Time and Additional Productive Resources.');
                document.getElementById("unitName").value = "";
				document.getElementById("unitNumber").value = "";
				document.getElementById("unitBeds").value = "";
				document.getElementById("unitTarget").value = "";
				document.getElementById("budgetValue").value = "";
				document.getElementById("thresholdLow").value = "";
				document.getElementById("thresholdHigh").value = "";
				document.getElementById("unitprodMeasure").selectedIndex = "0";
				document.getElementById("newManager").selectedIndex = "0";
				document.getElementById("budgetMeasure").selectedIndex = "0";
				document.getElementById("newDirector").selectedIndex = "0";
				document.getElementById("unitprodValue").value = "";
				document.getElementById("censusShift").selectedIndex = "52";
                $('#addnewUnits').modal('hide');
				tj.UnitsTable.ajax.reload();
				}else{
				bootbox.alert('Unit successfully added.  <br><br>Please go to Unit Settings to configure additional productivity settings.');	
				document.getElementById("unitName").value = "";
				document.getElementById("unitNumber").value = "";
				document.getElementById("unitBeds").value = "";
				document.getElementById("unitTarget").value = "";
				document.getElementById("budgetValue").value = "";
				document.getElementById("thresholdLow").value = "";
				document.getElementById("thresholdHigh").value = "";
				document.getElementById("unitprodMeasure").selectedIndex = "0";
				document.getElementById("newManager").selectedIndex = "0";
				document.getElementById("budgetMeasure").selectedIndex = "0";
				document.getElementById("newDirector").selectedIndex = "0";
				document.getElementById("unitprodValue").value = "";
				document.getElementById("censusShift").selectedIndex = "52";
                $('#addnewUnits').modal('hide');
				tj.UnitsTable.ajax.reload();
				}
				
            }
        });

		
}
	
}



//////////////////
/*
ACCOUNTS
   ______  ___  _____  _____
   | ___ \/ _ \|  __ \|  ___|
   | |_/ / /_\ \ |  \/| |__
   |  __/|  _  | | __ |  __|
   | |   | | | | |_\ \| |___
   \_|   \_| |_/\____/\____/


*/
/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.initializeAccountsGrid = function(id) {
    tj.accountsTable = $('#accountsTable').DataTable( {
        "ajax": "inc/data.php?req=getAllAccounts",
        "order": [[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "name" },
            { "data": "city" },
			{ "data": "state" },
            { "data": "label" }
        ]
    } );
	

	
/////////////////////////////////////
// EDIT Account

tj.editAccount = function(accountId) {
		var accountId = accountId; 

		$.ajax({
            url:'inc/data.php?req=getAccountDetails',
            data:{
                accountId : accountId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//console.log('account' , accountId);
				$('#accountName').val(response.data.name);
				$('#accountEnterprise').val(response.data.enterpriseId);
				$('#accountAddress').val(response.data.accountAddress);
				$('#accountCity').val(response.data.accountCity);
                $('#accountState').val(response.data.accountState);
				$('#accountZip').val(response.data.accountZip);
				$('#accountContactName').val(response.data.contactName);
                $('#accountContactEmail').val(response.data.contactEmail);	
				$('#accountContactPhone').val(response.data.contactPhone);
				$('#accountPayPeriod').val(response.data.payPeriod);
				$('#accountPayFirst').val(response.data.payPeriodFirst);
				$('#accountLabel').val(response.data.label);
				$('#accountId').val(response.data.id);
				$('#dashColor').val(response.data.prodIndicator);
				$('#accountImage').val(response.data.accountImage);
				$('#accountSettings').modal('show');
				}
        })
        
    }
	
/////////////////////////////////////
// EDIT Account

tj.addAccount = function() {
		var name = $('#accountName_add').val();
		var address = $('#accountAddress_add').val();
		var city = $('#accountCity_add').val();
        var state = $('#accountState_add').val();
		var zip = $('#accountZip_add').val();
		var contactName = $('#accountContactName_add').val();
        var contactEmail = $('#accountContactEmail_add').val();	
		var contactPhone = $('#accountContactPhone_add').val();
		var payPeriod = $('#accountPayPeriod_add').val();
		var payStart = $('#accountPayFirst_add').val();
		var label = $('#accountLabel_add').val();
		var accountImage = $('#accountImage_add').val();		

		$.ajax({
            url:'inc/data.php?req=addAccount',
            data:{
                name : name,
				address : address,
				city : city,
				state : state,
				zip : zip,
				contactName : contactName,
				contactEmail : contactEmail,
				contactPhone : contactPhone,
				payPeriod : payPeriod,
				payStart : payStart,
				label : label,
				accountImage: accountImage
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//console.log('account' , accountId);
				bootbox.alert('Account added successfully.');
				document.getElementById("accountName_add").value = "";
				document.getElementById("accountAddress_add").value = "";
				document.getElementById("accountCity_add").value = "";
				document.getElementById("accountState_add").value = "";
				document.getElementById("accountZip_add").value = "";
				document.getElementById("accountContactName_add").value = "";
				document.getElementById("accountContactEmail_add").value = "";
				document.getElementById("accountContactPhone_add").value = "";
				document.getElementById("accountPayPeriod_add").value = "";
				document.getElementById("accountPayFirst_add").value = "";
				document.getElementById("accountLabel_add").value = "";
				document.getElementById("accountImage_add").value = "";
				tj.accountsTable.ajax.reload();
				$('#addAccount').modal('hide');
				}
        })
        
    }

tj.updateAccount = function() {
		var address = $('#accountAddress').val();
		var accountId = $('#accountId').val();
		var enterpriseId = $('#accountEnterprise').val();
		var city = $('#accountCity').val();
		var state = $('#accountState').val();
		var zip = $('#accountZip').val();
		var contactName = $('#accountContactName').val();
		var contactEmail = $('#accountContactEmail').val();
		var contactPhone = $('#accountContactPhone').val();
		var payPeriod = $('#accountPayPeriod').val();
		var payFirst = $('#accountPayFirst').val();
		var accountLabel = $('#accountLabel').val();
		var accountImage = $('#accountImage').val();
		var dashColor = $('#dashColor').val();
		
        $.ajax({
            url:'inc/data.php?req=updateAccount',
            data:{
				accountId: accountId,
                address: address,
				city: city,
				state: state,
				zip: zip,
				contactName: contactName,
				contactEmail: contactEmail,
				contactPhone: contactPhone,
				payPeriod: payPeriod,
				payFirst: payFirst,
				accountLabel: accountLabel,
				accountImage: accountImage,
				enterpriseId: enterpriseId,
				dashColor: dashColor
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('Account updated successfully.');
				$('#accountSettings').modal('hide');
				tj.accountsTable.ajax.reload();
				//tj.UnitsTable.ajax.reload();
				//tj.reportsLoaded = false;
				//tj.loadReports2();
            }
        })
    }	
	
}


/*
PROD
   ______  ___  _____  _____
   | ___ \/ _ \|  __ \|  ___|
   | |_/ / /_\ \ |  \/| |__
   |  __/|  _  | | __ |  __|
   | |   | | | | |_\ \| |___
   \_|   \_| |_/\____/\____/


*/

/////////////////////////////////////
// LOCATIONS GLOBALS
tj.ProdID = 0;


/////////////////////////////////////
// INITIALIZE THE PRODUCTIVITY TABLE
tj.prodStartDate = '';
tj.prodEndDate = '';
tj.initializeProdGrid = function(id) {
    newStart = tj.prodStartDate;
    newEnd = tj.prodEndDate;
    tj.prodTable = $('#prodTable').DataTable( {
		"ajax": {
			type:"POST",
			stateSave:true,
            url:"inc/data.php?req=getProd",
            data: function(d) {
				//d.user = tj.userParam;
				d.id = tj.prodId;
				d.prodStart = tj.prodStart;
				d.prodEnd = tj.prodEnd;
				d.start = tj.prodStartDate;
                d.end = tj.prodEndDate;
				d.category = $('#serviceFilter').val();
            }
            
        },
		select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        //sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
		"order": [[0,'asc'],[12,'desc']],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			switch(Number(aData.style)) {
            case 1:
				$('td', nRow).addClass('redRow');
                break;
			case 2:
				$('td', nRow).addClass('greenRow');
                break;
            }			
        },
        processing: true,
		"language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "unit" },
            { "data": "shift" },
			{ "data": "aprod" },
			{ "data": "variance" },
			{ "data": "patients" },
			{ "data": "total" },
			{ "data": "charge" },
			{ "data": "nursecount" },
			{ "data": "techs" },
			{ "data": "secs" },
			{ "data": "other" },
			{ "data": "note" },
			{ "data": "shiftnum" }
        ],
		"columnDefs": [
						{"visible": false, "targets": [12] }
        ],
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'Staffing Reports',
				title: function() {
				return $('#pdfTitle').val()
				},
				message: 'New Message',
				orientation: 'landscape',
				exportOptions: {
				columns: ':visible'
				},
				customize: function (doc) {
				doc.styles.title = {
				fontSize: '14',
				alignment: 'center'
				}, 
				doc.pageMargins = [50,50,50,50];
				doc.defaultStyle.fontSize = 11;
				doc.styles.tableHeader.fontSize = 12;
				//doc.styles.title.fontSize = 14;
				// Remove spaces around page title
				doc.content[0].text = doc.content[0].text.trim();
				// Create a footer
				doc['footer']=(function(page, pages) {
					return {
						columns: [
							'CONFIDENTIAL',
							{
								// This is the right column
								alignment: 'right',
								text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
							}
						],
						margin: [20, 0]
					}
				});
				// Styling the table: create style object
				var objLayout = {};
				// Horizontal line thickness
				objLayout['hLineWidth'] = function(i) { return .5; };
				// Vertikal line thickness
				objLayout['vLineWidth'] = function(i) { return .5; };
				// Horizontal line color
				objLayout['hLineColor'] = function(i) { return '#aaa'; };
				// Vertical line color
				objLayout['vLineColor'] = function(i) { return '#aaa'; };
				// Left padding of the cell
				objLayout['paddingLeft'] = function(i) { return 10; };
				// Right padding of the cell
				objLayout['paddingRight'] = function(i) { return 10; };
				// Inject the object in the document
				doc.content[1].layout = objLayout;
				}
			}
		]
    } );
/////////////////////////////////////
// EDIT PROD

tj.editProd = function(dataId) {
		$('#addProd').modal({backdrop: 'static', keyboard: false})  
        
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
						
				$('#userName_add').html(response.data.first_name+' '+response.data.last_name);
				$('#chargecount_add').val(response.data.chargecount);
				$('#techcount_add').val(response.data.techcount);
				$('#aptechcount_add').val(response.data.aptechcount);
				$('#seccount_add').val(response.data.seccount);
				$('#antecount_add').val(response.data.antecount);
				$('#atotal').val(response.data.atotal);
				$('#ltotal').val(response.data.ltotal);
				$('#openbeds').html(response.data.openbeds);
				$('#acs_add').val(response.data.acs);
				$('#am1_add').val(response.data.am1);
				$('#awcm_add').val(response.data.awcm);
				$('#obed_add').val(response.data.obed);
				$('#obed_add1').val(response.data.obed1);
				$('#ocount_add').val(response.data.ocount);
				$('#ldcount_add').val(response.data.ldcount);
				$('#ev_add').val(response.data.ev);
				$('#scs_add').val(response.data.scs);
				$('#cr_add').val(response.data.cr);
				$('#pt_add').val(response.data.pt);
				$('#reportdate').html(response.data.reportdate);
				$('#reportshift').html(response.data.reportshift);
				$('#ccs_add').val(response.data.ccs);
				$('#deptId_add').val(response.data.deptId);
				$('#ps1_add').val(response.data.ps1);
				$('#shift_add').val(response.data.shift);
				$('#day_add').val(response.data.dayDate);
				$('#dataId_add').val(response.data.id);
				$('#dataId2_add').html('<a href="/view.php?i='+response.data.dataId2+'">Print View</a>');
				$('#prodnote').val(response.data.note);
				$('#variance').html(response.data.nvariance);
				$('#aproductivity').html(response.data.aproductivity);
				$('#lvariance').html(response.data.lvariance);
				$('#avariance').html(response.data.avariance);
				$('#prodTable').DataTable().search('').draw();
				$('#addProd').modal('show');
				}
        })
        
  }
///////////////////
//Service Select

tj.serviceSelect = function() {
		//var category = $('#serviceFilter').val();
		tj.prodTable.ajax.reload();
		
    };  

///////////////////
//Enter Visits

tj.visits = function() {
	var visitTotal = $('#visitTotal').val();
	var dataId = $('#visitsdataId').val();
	var dayDate = $('#visitsDate').val();
	var accountId = $('#visitsaccountId').val();
	var deptId = $('#visitsdeptId').val();
	var hoursTotal = $('#hoursTotal').val();
	
	
	
	
	$.ajax({
            url:'inc/data.php?req=updatevisits',
            data:{
                dataId: dataId,
				visitTotal: visitTotal,
				dayDate: dayDate,
				accountId: accountId,
				deptId: deptId,
				hoursTotal: hoursTotal
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//if (response.updated != true) {
				//$('#visits').modal('hide');
				//bootbox.alert('All Productivity Reports must be submitted before you can add Visits.');
				//}else{
				$('#visits').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				//}
				}
        })
		
};

////////////////////
//add visits
tj.addvisits = function(dataId) {
	$.ajax({
            url:'inc/data.php?req=getvisits',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.data.prodMeasure==4){
					$('#visitsType').html('Total Visits');
				}else if(response.data.prodMeasure==6){
					$('#visitsType').html('Total Deliveries');
				}else{
					$('#visitsType').html('Patient Census');
				}
				$('#visitsdataId').val(response.data.id);
				$('#visitsdayDate').html(response.data.reportdate);
				$('#hoursdayDate').html(response.data.reportdate);
				$('#visitsDate').val(response.data.dayDate);
				$('#visitTotal').val(response.data.visits);
				$('#visitsaccountId').val(response.data.accountId);
				$('#visitsdeptId').val(response.data.deptId);
				$('#hoursTotal').val(response.data.totalHours);
				$('#visits').modal('show');
				}
        })
		
}; 	
/////////////////////////////////////
// EDIT PROD

tj.editSICU = function(dataId) {
		$('#addSICU').modal({backdrop: 'static', keyboard: false})  
        
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
						
				$('#userNameSICU').html(response.data.first_name+' '+response.data.last_name);
				$('#chargecountSICU').val(response.data.chargecount);
				$('#techcountSICU').val(response.data.techcount);
				$('#seccountSICU').val(response.data.seccount);
				$('#nursecountSICU').val(response.data.antecount);
				$('#patienttotalSICU').val(response.data.atotal);
				$('#openbedsSICU').html(response.data.openbeds);
				$('#reportdateSICU').html(response.data.reportdate);
				$('#reportshiftSICU').html(response.data.reportshift);
				$('#deptIdSICU').val(response.data.deptId);
				$('#shiftSICU').val(response.data.shift);
				$('#highSICU').val(response.data.oneto3);
				$('#medSICU').val(response.data.oneto4);
				$('#lowSICU').val(response.data.oneto5);
				$('#daySICU').val(response.data.dayDate);
				//$('#blocked').html('<a href="javascript:;" onclick="blockedBeds('+response.data.blockedBeds+');">  '+response.data.blockedBeds+'</a>;');
				$('#dataIdSICU').val(response.data.id);
				$('#dataId2SICU').html('<a href="/v.php?i='+response.data.dataId2+'">Print View</a>');
				$('#prodnoteSICU').val(response.data.note);
				$('#varianceSICU').html(response.data.nvariance);
				$('#aproductivitySICU').html(response.data.aproductivity);
				$('#avarianceSICU').html(response.data.avariance);
				$('#prodTable').DataTable().search('').draw();
				$('#addSICU').modal('show');
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  }
  
  /////////////////////////////////////
// EDIT PROD

tj.editNEW = function(dataId) {
		document.getElementById("hidden1").style.display='';
		document.getElementById("hidden2").style.display='';
		document.getElementById("hidden3").style.display='';
		document.getElementById("hidden4").style.display='';
		document.getElementById("hidden5").style.display='';
		document.getElementById("hidden6").style.display='';
		document.getElementById("hidden7").style.display='';
		document.getElementById("hidden8").style.display='';
		document.getElementById("showchurn1").style.display='none';
		document.getElementById("nurse1").style.display='none';
				       
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var a1 = document.getElementById('hidden1');
				var a2 = document.getElementById('hidden2');	
				var a3 = document.getElementById('hidden3');	
				var a4 = document.getElementById('hidden4');	
				var a5 = document.getElementById('hidden5');	
				var a6 = document.getElementById('hidden6');
				var a11 = document.getElementById('hidden7');
				var a12 = document.getElementById('hidden8');
				var a7 = document.getElementById('showchurn1');
				var a8 = document.getElementById('hiddentotal1');
				var a9 = document.getElementById('hiddentotal2');
				var a10 = document.getElementById('nurse1');
				
				if (response.data.acuityTotal ==1) {
				a8.style.display='none';
				a9.style.display='none';
				}else{
				a8.style.display='';
				a9.style.display='';					
				}
				if (response.data.acuity1 ==0) {
				a1.style.display='none';
				}
				if (response.data.acuity2 ==0) {
				a2.style.display='none';
				}
				if (response.data.acuity3 ==0) {
				a3.style.display='none';
				}
				if (response.data.acuity4 ==0) {
				a4.style.display='none';
				}
				if (response.data.acuity5 ==0) {
				a5.style.display='none';
				}
				if (response.data.acuity6 ==0) {
				a6.style.display='none';
				}
				if (response.data.acuity7 ==0) {
				a11.style.display='none';
				}
				if (response.data.acuity8 ==0) {
				a12.style.display='none';
				}
				if (response.data.churn ==1) {
				a7.style.display='';
				}
				if (response.data.nurse1 ==1) {
				a10.style.display='';
				}
				//if (response.data.atotal>0 && response.data.shift==52 && response.data.prodMeasure==4) {
				//	a10.style.display='';
				//}else{
				//	a10.style.display='none';
				//}
				$('#nurse1Desc').html(response.data.nurse1Desc);
				$('#nurse1_add').val(response.data.customNurse);
				$('#userNameNEW').html(response.data.first_name+' '+response.data.last_name);
				$('#chargecountNEW').val(response.data.chargecount);
				$('#techcountNEW').val(response.data.techcount);
				$('#seccountNEW').val(response.data.seccount);
				$('#sittersNEW').val(response.data.sittercount);
				$('#nursecountNEW').val(response.data.antecount);
				$('#patienttotalNEW').val(response.data.atotal);
				$('#openbedsNEW').html(response.data.openbeds);
				$('#reportdateNEW').html(response.data.reportdate);
				$('#reportshiftNEW').html(response.data.reportshift);
				$('#deptIdNEW').val(response.data.deptId);
				$('#shiftNEW').val(response.data.shift);
				$('#acuityTotal').val(response.data.acuityTotal);
				$('#highNEW').val(response.data.oneto3);
				$('#medNEW').val(response.data.oneto4);
				$('#lowNEW').val(response.data.oneto5);
				$('#desc1').html(response.data.desc1);
				$('#desc2').html(response.data.desc2);
				$('#desc3').html(response.data.desc3);
				$('#desc4').html(response.data.desc4);
				$('#desc5').html(response.data.desc5);
				$('#desc6').html(response.data.desc6);
				$('#desc7').html(response.data.desc7);
				$('#desc8').html(response.data.desc8);
				$('#oneto1').val(response.data.oneto1);
				$('#oneto2').val(response.data.oneto2);
				$('#oneto6').val(response.data.oneto6);
				$('#oneto7').val(response.data.oneto7);
				$('#oneto8').val(response.data.oneto8);
				$('#oneto7Acuity').val(response.data.oneto7Acuity);
				$('#oneto8Acuity').val(response.data.oneto8Acuity);
				$('#admissions1').val(response.data.admits);
				$('#transfers1').val(response.data.transfers);
				$('#discharges1').val(response.data.discharges);					
				$('#dayNEW').val(response.data.dayDate);
				//$('#blocked').html('<a href="javascript:;" onclick="blockedBeds('+response.data.accountId+','+response.data.deptId+');">  '+response.data.blockedBeds+'</a>');
				$('#dataIdNEW').val(response.data.id);
				//$('#dataId2NEW').html('<a href="javascript:;" onclick="tj.getNurseView('+response.data.dataIdNEW+')">Print View</a>');
				$('#prodnoteNEW').val(response.data.note);
				$('#varianceNEW').html(response.data.nvariance);
				$('#aproductivityNEW').html(response.data.aproductivity);
				$('#avarianceNEW').html(response.data.avariance);
				
				
				//$('#prodTable').DataTable().search('').draw();
				$('#addNEW').modal('show');
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  } 
  
   /////////////////////////////////////
// EDIT PROD

tj.resetNEW = function() {
				$('#addNEW').modal('hide');
				document.getElementById("userNameNEW").val = "";
				document.getElementById("chargecountNEW").selectedIndex = 1;
				document.getElementById("techcountNEW").selectedIndex = 0;
				document.getElementById("seccountNEW").selectedIndex = 0;
				document.getElementById("nursecountNEW").selectedIndex = 0;
				document.getElementById("patienttotalNEW").selectedIndex = 0;
				document.getElementById("openbedsNEW").val = "";
				document.getElementById("reportdateNEW").val = "";
				document.getElementById("reportshiftNEW").val = "";
				//document.getElementById("deptIdNEW").val = "";
				document.getElementById("shiftNEW").val = "";
				document.getElementById("acuityTotal").val = "";
				document.getElementById("highNEW").selectedIndex = 0;
				document.getElementById("medNEW").selectedIndex = 0;
				document.getElementById("lowNEW").selectedIndex = 0;
				document.getElementById("desc1").val = "";
				document.getElementById("desc2").val = "";
				document.getElementById("desc3").val = "";
				document.getElementById("desc4").val = "";
				document.getElementById("desc5").val = "";
				document.getElementById("desc6").val = "";
				document.getElementById("oneto1").selectedIndex = 0;
				document.getElementById("oneto2").selectedIndex = 0;
				document.getElementById("oneto6").selectedIndex = 0;				
				document.getElementById("dayNEW").val = "";
				document.getElementById("dataIdNEW").val = "";
				document.getElementById("dataId2NEW").val = "";
				document.getElementById("prodnoteNEW").val = "";
				document.getElementById("varianceNEW").val = "";
				document.getElementById("aproductivityNEW").val = "";
				document.getElementById("avarianceNEW").val = "";
				document.getElementById("hidden1").style.display='';
				document.getElementById("hidden2").style.display='';
				document.getElementById("hidden3").style.display='';
				document.getElementById("hidden4").style.display='';
				document.getElementById("hidden5").style.display='';
				document.getElementById("hidden6").style.display='';
				
        
  }
  
  /////////////////////////////////////
// ADD PROD

tj.updateProd = function() {
		
		var shift = $('#shift_add').val();
		var day = $('#day_add').val();
		var chargecount = $('#chargecount_add').val();
		var techcount = $('#techcount_add').val();
		var aptechcount = $('#aptechcount_add').val();
		var seccount = $('#seccount_add').val();
		var antecount = $('#antecount_add').val();
		var acs = $('#acs_add').val();
		var am1 = $('#am1_add').val();
		var awcm = $('#awcm_add').val();
		var obed = $('#obed_add').val();
		var obed1 = $('#obed_add1').val();
		var ldcount = $('#ldcount_add').val();
		var ocount = $('#ocount_add').val();
		var ev = $('#ev_add').val();
		var scs = $('#scs_add').val();
		var cr = $('#cr_add').val();
		var pt = $('#pt_add').val();
		var ccs = $('#ccs_add').val();
		var ps1 = $('#ps1_add').val();
		var dataId = $('#dataId_add').val();
		var atotal = $('#atotal').val();
		var ltotal = $('#ltotal').val();
		var note = $('#prodnote').val();
		var pp = $('#pp_add').val();
		
        $.ajax({
            url:'inc/data.php?req=updateProd',
            data:{
                chargecount: chargecount,
				techcount: techcount,
				aptechcount: aptechcount,
				seccount: seccount,
                antecount: antecount,
				acs: acs,
                am1: am1,
				awcm: awcm,
				obed: obed,
				obed1: obed1,
                ldcount: ldcount,
				ocount: ocount,
				ev: ev,
                scs: scs,
				cr: cr,
                pt: pt,
				ccs: ccs,
				ps1: ps1,
                shift: shift,
				day: day,
				dataId: dataId,
				atotal: atotal,
				ltotal: ltotal,
				note: note,
				pp: pp
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.antecheck != true || response.data.laborcheck !=true) {
				bootbox.alert('Your Total Patient Count does not add up to match your Patient Selections.  <br><br>Please correct this.');
				//document.getElementById("add_prod_form").reset();
				}else if (response.data.message == true && response.data.antecheck == true && response.data.laborcheck == true) {
				$('#notedataIdOrig').val(response.data.dataId);
				$('#notedeptIdOrig').val(response.data.deptId);
				$('#deptOrig').html(response.data.dept);
				$('#varianceTypeOrig').html(response.data.varianceType);
				$('#notebodyOrig').val(response.data.note);
				$('#addprodnoteOrig').modal('show');
				$('#addProd').modal('hide');
				}else{
				$('#dataIdesc').val(response.data.dataId);
				$('#deptIdesc').val(response.data.deptId);
				$('#addProd').modal('hide');
				$('#escalationNEW').modal('show');
				document.getElementById("chargecount_add").selectedIndex = "";
				document.getElementById("techcount_add").selectedIndex = "";
				document.getElementById("aptechcount_add").selectedIndex = "";
				document.getElementById("seccount_add").selectedIndex = "";
				document.getElementById("antecount_add").selectedIndex = "";
				document.getElementById("acs_add").selectedIndex = "";
				document.getElementById("am1_add").selectedIndex = "";
				document.getElementById("awcm_add").selectedIndex = "";
				document.getElementById("obed_add").selectedIndex = "";
				document.getElementById("ldcount_add").selectedIndex = "";
				document.getElementById("ocount_add").selectedIndex = "";
				document.getElementById("ev_add").selectedIndex = "";
				document.getElementById("scs_add").selectedIndex = "";
				document.getElementById("cr_add").selectedIndex = "";
				document.getElementById("pt_add").selectedIndex = "";
				document.getElementById("ccs_add").selectedIndex = "";
				document.getElementById("ps1_add").selectedIndex = "";
				document.getElementById("atotal").selectedIndex = "";
				document.getElementById("ltotal").selectedIndex = "";
				document.getElementById("userId_add").value = "";
				document.getElementById("shift_add").value = "";
				document.getElementById("day_add").value = "";
				document.getElementById("prodnote").value = "";
				document.getElementById("dataId_add").value = "";
				tj.prodTable.ajax.reload(null,false);
                }				
				
            }
        })
    }
	
  /////////////////////////////////////
// ADD PROD

tj.updateProdSICU = function() {
		
		var shift = $('#shiftSICU').val();
		var day = $('#daySICU').val();
		var chargecount = $('#chargecountSICU').val();
		var techcount = $('#techcountSICU').val();
		var seccount = $('#seccountSICU').val();
		var nursecount = $('#nursecountSICU').val();
		var high = $('#highSICU').val();
		var med = $('#medSICU').val();
		var low = $('#lowSICU').val();
		var dataId = $('#dataIdSICU').val();
		var patienttotal = $('#patienttotalSICU').val();
		var note = $('#prodnoteSICU').val();
		
        $.ajax({
            url:'inc/data.php?req=updateProdTest',
            data:{
                chargecount: chargecount,
				techcount: techcount,
				seccount: seccount,
                nursecount: nursecount,
				shift: shift,
				day: day,
				high: high,
				med: med,
				low: low,
				dataId: dataId,
				patienttotal: patienttotal,
				note: note
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.patientcheck != true) {
				bootbox.alert('Your Total Patient Count does not add up to match your Patient Selections.  <br><br>Please correct this.');
				//document.getElementById("add_prod_form").reset();
				}else if (response.data.updated != true) {
				bootbox.alert('Cannot update productivity because not all reports have been submitted for the day.');
				//document.getElementById("add_prod_form").reset();
				}else if (response.data.message == true && response.data.patientcheck == true) {
				$('#notedataId').val(response.data.dataId);
				$('#variance').val(response.data.variance);
				$('#dept').html(response.data.dept);
				$('#varianceType').html(response.data.varianceType);
				$('#notebody').val(response.data.note);
				$('#addprodnote').modal('show');
				}else{
				$('#dataIdesc').val(response.data.dataId);
				$('#deptIdesc').val(response.data.deptId);
				$('#addSICU').modal('hide');
				$('#escalationNEW').modal('show');
				document.getElementById("chargecountSICU").selectedIndex = "";
				document.getElementById("techcountSICU").selectedIndex = "";
				document.getElementById("seccountSICU").selectedIndex = "";
				document.getElementById("nursecountSICU").selectedIndex = "";
				document.getElementById("highSICU").selectedIndex = "";
				document.getElementById("medSICU").selectedIndex = "";
				document.getElementById("lowSICU").selectedIndex = "";
				document.getElementById("patienttotalSICU").selectedIndex = "";
				document.getElementById("userIdSICU").value = "";
				document.getElementById("shiftSICU").value = "";
				document.getElementById("daySICU").value = "";
				document.getElementById("prodnoteSICU").value = "";
				document.getElementById("dataIdSICU").value = "";
				tj.prodTable.ajax.reload(null,false);
                }				
				
            }
        })
    }

///Clear Record

tj.clearRecord = function(dataId) {
		$.ajax({
            url:'inc/data.php?req=clearRecord',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			tj.prodTable.ajax.reload(null,false);	
				}
        })
        
		
  }

 
  /////////////////////////////////////
// Update Productivity Record

tj.updateProdNEW = function() {
		
		var shift = $('#shiftNEW').val();
		var day = $('#dayNEW').val();
		var chargecount = $('#chargecountNEW').val();
		var techcount = $('#techcountNEW').val();
		var seccount = $('#seccountNEW').val();
		var nursecount = $('#nursecountNEW').val();
		var high = $('#highNEW').val();
		var med = $('#medNEW').val();
		var low = $('#lowNEW').val();
		var oneto1 = $('#oneto1').val();
		var oneto2 = $('#oneto2').val();
		var oneto6 = $('#oneto6').val();
		var oneto7 = $('#oneto7').val();
		var oneto7Acuity = $('#oneto7Acuity').val();
		var oneto8 = $('#oneto8').val();
		var oneto8Acuity = $('#oneto8Acuity').val();
		var dataId = $('#dataIdNEW').val();
		var nurse1 = $('#nurse1_add').val();
		var inshiftProd = $('#inshiftProd').val();
		var rnThreshold = $('#rnThreshold').val();
		
		var acuityTotal = $('#acuityTotal').val();
		var note = $('#prodnoteNEW').val();
		var sitters = $('#sittersNEW').val();
		var admissions = $('#admissions1').val();
		var transfers = $('#transfers1').val();
		var discharges = $('#discharges1').val();
		var a1= document.getElementById('addNEW');
		
		if (acuityTotal == 1){
			var patienttotal = parseInt(oneto1)+parseInt(oneto2)+parseInt(oneto6)+parseInt(oneto7)+parseInt(oneto8)+parseInt(high)+parseInt(med)+parseInt(low);
			}else{
			var patienttotal = $('#patienttotalNEW').val();
			}
		
		if (parseInt(patienttotal) != parseInt(oneto1)+parseInt(oneto2)+parseInt(oneto6)+parseInt(oneto7)+parseInt(oneto8)+parseInt(high)+parseInt(med)+parseInt(low)) {
			bootbox.alert('Your Total Patient Count does not add up to match your Patient Selections.  <br><br>Please correct this.');
			return;
			}
		if (nursecount == 0) {
			bootbox.alert('Total Nurses cannot be zero.');
			return;
			}
		var currentTime = moment().format('YYYY-MM-DD hh:mm:ss');
		
		//if(response.data.updated != true) {
		//	bootbox.alert('All reports must be submitted for the day in order to complete productivity.');
		//	return;
		//	}
		console.log('time-',currentTime);
		console.log('acuityTotal-',acuityTotal);
		
		//console.log('sitters-',sitters);
        $.ajax({
            url:'inc/data.php?req=updateprodTest',
            data:{
                chargecount: chargecount,
				techcount: techcount,
				seccount: seccount,
                nursecount: nursecount,
				shift: shift,
				day: day,
				high: high,
				med: med,
				low: low,
				oneto1: oneto1,
				oneto2: oneto2,
				oneto6: oneto6,
				oneto7: oneto7,
				oneto7Acuity: oneto7Acuity,
				oneto8: oneto8,
				oneto8Acuity: oneto8Acuity,
				dataId: dataId,
				patienttotal: patienttotal,
				note: note,
				sitters: sitters,
				admissions: admissions,
				transfers: transfers,
				discharges: discharges,
				currentTime: currentTime,
				admissions: admissions,
				transfers: transfers,
				discharges: discharges,
				acuityTotal: acuityTotal,
				nurse1: nurse1,
				inshiftProd: inshiftProd,
				rnThreshold: rnThreshold
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				console.log('date',response.data.nowDayDate);
				console.log('today',response.data.nowDate);
				if (response.data.message == true && response.data.updated == true) {
				$('#notedataId').val(response.data.dataId);
				$('#notedeptId').val(response.data.deptId);
				$('#dept').html(response.data.dept);
				$('#varianceType').html(response.data.varianceType);
				$('#notebody').html(response.data.note);
				//console.log('updated',response.data.update);
				tj.prodTable.ajax.reload(null,false);
				//tj.complianceTable.ajax.reload();
				$('#addNEW').modal('hide');
				$('#addprodnote').modal('show');
				}else if (response.data.message == false && response.data.updated == true && response.data.textAlerts ==1 && response.data.txtpause ==0 && response.data.txtactive ==1 && response.data.txtescalation ==1 && response.data.escalations ==0) {
				//&& response.data.textAlerts >0 && response.data.txtactive >0 && response.data.txtescalation >0 && response.data.txtpause==0
				$('#dataIdesc').val(response.data.dataId);
				$('#deptIdesc').val(response.data.deptId);
				tj.prodTable.ajax.reload(null,false);
				//tj.complianceTable.ajax.reload();
				$('#addNEW').modal('hide');
				$('#escalationNEW').modal('show');
				document.getElementById("chargecountNEW").selectedIndex = "";
				document.getElementById("techcountNEW").selectedIndex = "";
				document.getElementById("seccountNEW").selectedIndex = "";
				document.getElementById("nursecountNEW").selectedIndex = "";
				document.getElementById("highNEW").selectedIndex = "";
				document.getElementById("medNEW").selectedIndex = "";
				document.getElementById("lowNEW").selectedIndex = "";
				document.getElementById("oneto1").selectedIndex = "";
				document.getElementById("oneto2").selectedIndex = "";
				document.getElementById("oneto6").selectedIndex = "";
				document.getElementById("oneto7").selectedIndex = "";
				document.getElementById("oneto8").selectedIndex = "";
				document.getElementById("patienttotalNEW").selectedIndex = "";
				document.getElementById("sittersNEW").selectedIndex = "";
				document.getElementById("userIdNEW").value = "";
				document.getElementById("shiftNEW").value = "";
				document.getElementById("dayNEW").value = "";
				document.getElementById("prodnoteNEW").value = "";
				document.getElementById("dataIdNEW").value = "";
				document.getElementById("admissions1").value = "";
				document.getElementById("transfers1").value = "";
				document.getElementById("discharges1").value = "";
				tj.prodTable.ajax.reload(null,false);
				
                }else{
				document.getElementById("chargecountNEW").selectedIndex = "";
				document.getElementById("techcountNEW").selectedIndex = "";
				document.getElementById("seccountNEW").selectedIndex = "";
				document.getElementById("nursecountNEW").selectedIndex = "";
				document.getElementById("highNEW").selectedIndex = "";
				document.getElementById("medNEW").selectedIndex = "";
				document.getElementById("lowNEW").selectedIndex = "";
				document.getElementById("oneto1").selectedIndex = "";
				document.getElementById("oneto2").selectedIndex = "";
				document.getElementById("oneto6").selectedIndex = "";
				document.getElementById("oneto7").selectedIndex = "";
				document.getElementById("oneto8").selectedIndex = "";
				document.getElementById("patienttotalNEW").selectedIndex = "";
				document.getElementById("sittersNEW").selectedIndex = "";
				document.getElementById("userIdNEW").value = "";
				document.getElementById("shiftNEW").value = "";
				document.getElementById("dayNEW").value = "";
				document.getElementById("prodnoteNEW").value = "";
				document.getElementById("dataIdNEW").value = "";
				document.getElementById("admissions1").value = "";
				document.getElementById("transfers1").value = "";
				document.getElementById("discharges1").value = "";
				$('#addNEW').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				//tj.complianceTable.ajax.reload();
				//tj.reportsLoaded = false;
				//tj.loadReports2();	
				}		
            }
			
        })
				
    }
	

	
	  /////////////////////////////////////
// ADD PROD

tj.changeProd = function() {
		var shift = $('#shift_add').val();
		var day = $('#day_add').val();
		var chargecount = $('#chargecount_add').val();
		var techcount = $('#techcount_add').val();
		var seccount = $('#seccount_add').val();
		var antecount = $('#antecount_add').val();
		var acs = $('#acs_add').val();
		var am1 = $('#am1_add').val();
		var awcm = $('#awcm_add').val();
		var obed = $('#obed_add').val();
		var ldcount = $('#ldcount_add').val();
		var ev = $('#ev_add').val();
		var scs = $('#scs_add').val();
		var cr = $('#cr_add').val();
		var pt = $('#pt_add').val();
		var ccs = $('#ccs_add').val();
		var ps1 = $('#ps1_add').val();
		var dataId = $('#dataId').val();
		var atotal = $('#atotal').val();
		var ltotal = $('#ltotal').val();
		var note = $('#prodnote').val();
		
        $.ajax({
            url:'inc/data.php?req=changeProd',
            data:{
                chargecount: chargecount,
				techcount: techcount,
				seccount: seccount,
                antecount: antecount,
				acs: acs,
                am1: am1,
				awcm: awcm,
				obed: obed,
                ldcount: ldcount,
				ev: ev,
                scs: scs,
				cr: cr,
                pt: pt,
				ccs: ccs,
				ps1: ps1,
                shift: shift,
				day: day,
				dataId: dataId,
				atotal: atotal,
				ltotal: ltotal,
				note: note
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.antecheck != true || response.data.laborcheck !=true) {
				bootbox.alert('Your Total Patient Count does not add up to match your Patient Selections.  <br><br>Please correct this.');
				}else if (response.data.message == true) {
				$('#notedataId').val(response.data.dataId);
				$('#notedeptId').val(response.data.deptId);
				$('#variance').val(response.data.variance);
				$('#dept').val(response.data.dept);
				$('#varianceType').val(response.data.varianceType);
				$('#notebody').val(response.data.note);
				$('#addprodnote').modal('show');
				}else{
				bootbox.alert('Matrix updated successfully.');
				$('#addProd').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				}				
				
            }
        })
    }
	
tj.addProdNote = function () {
        var dataId = $('#notedataId').val();
		var note = $('#notebody').val();
		var deptId = $('#notedeptId').val();
		//console.log('dataid',dataId);
		//if(note.length == 0){
		//bootbox.alert('Please add a note or action plan.');
		//return;
		//}
		
        $.ajax({
            url: 'inc/data.php?req=prodNote',
            data: {
                note: note,
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success:function(response) {
				if(response.data.txtactive==1 && response.data.textAlerts==1 && response.data.escalations==0 && response.data.txtpause==0 && response.data.txtescalation==1){
				$('#dataIdesc').val(dataId);
				$('#deptIdesc').val(deptId);
				$('#addprodnote').modal('hide');
				$('#escalationNEW').modal('show');
				//tj.prodTable.ajax.reload();
				//tj.stopLoading();				
				}else{				
				$('#addprodnote').modal('hide');
				$('#addNEW').modal('hide');
				document.getElementById("notebody").value = "";
				document.getElementById("notedataId").value = "";
				document.getElementById("notedeptId").value = "";
				tj.prodTable.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports2();
				//tj.stopLoading();
				}				
            }
        });
    }
	
tj.addProdNoteOrig = function () {
        var dataId = $('#notedataIdOrig').val();
		var note = $('#notebodyOrig').val();
		var deptId = $('#notedeptIdOrig').val();
		//console.log('dataid',dataId);
		
        $.ajax({
            url: 'inc/data.php?req=prodNote',
            data: {
                note: note,
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success:function(response) {
				if(response.data.txtactive==1 && response.data.textAlerts==1 && response.data.escalations==0 && response.data.txtpause==0 && response.data.txtescalation==1){
				$('#addProd').modal('hide');
				$('#dataIdesc').val(dataId);
				$('#deptIdesc').val(deptId);
				$('#escalationNEW').modal('show');				
				}else{				
				document.getElementById("notebodyOrig").value = "";
				document.getElementById("notedataIdOrig").value = "";
				document.getElementById("notedeptIdOrig").value = "";
				$('#addprodnoteOrig').modal('hide');
				$('#addProd').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				}
				//console.log('esca',response.data.escalations);				
            }
        });
    }
	
	
tj.noEscalation = function () {
        //var dataId = $('#dataId').val();
		var esc = 0;
		var sendtext = 0;
				
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                sendtext: sendtext
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				document.getElementById("dataIdesc").value = "";
                $('#escalationNEW').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports();
				//tj.loadReports2();
			}
        });
    }
	
tj.addEscalation = function () {
        var dataId = $('#dataIdesc').val();
		var deptId = $('#deptIdesc').val();
		var esc = $('#escval').val();
		var comment = $('#escalationcomment').val();
		var sendtext = 1;
		//console.log('dataId',dataId);
		//console.log('deptId',deptId);
		if(esc==0){
    bootbox.alert('Please select an Esclation');
    return;
		}		
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                dataId: dataId,
				deptId: deptId,
				sendtext: sendtext,
				comment: comment
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Successfully Submitted.');
				document.getElementById("escval").selectedIndex = 0;
				document.getElementById("dataId2").value = "";
				document.getElementById("escalationcomment").value = "";
				$('#escalationNEW').modal('hide');
                $('#addEscalation').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports2();
            }
        });
    }
	
tj.addEscalation3 = function () {
        var dataId = ''
		var deptId = $('#deptId3').val();
		var esc = $('#escval3').val();
		var comment = $('#escalationcomment3').val();
		var sendtext = 1;
		//console.log('dataId',dataId);
		//console.log('deptId',deptId);
		if(esc==0){
    bootbox.alert('Please select an Esclation');
    return;
		}
	if(deptId==0){
    bootbox.alert('Please select the Unit associated with the Escalation.');
    return;
	}			
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                dataId: dataId,
				deptId: deptId,
				sendtext: sendtext,
				comment: comment
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Successfully Submitted.');
				//tj.prodTable.ajax.reload();
				document.getElementById("escval3").selectedIndex = 0;
				document.getElementById("dataId3").value = "";
				document.getElementById("escalationcomment3").value = "";
				$('#addEscalation3').modal('hide');
				tj.reportsLoaded = false;
				tj.loadReports2();
            }
        });
    }
	
}

/////////////////////////////////////
// INITIALIZE THE whp TABLE
tj.prodStartDatewhp = '';
tj.prodEndDatewhp = '';
tj.initializeProdGridwhp = function(id) {
    tj.prodStartDatewhp = moment().format('YYYY-MM-DD');
    tj.prodEndDatewhp = moment().format('YYYY-MM-DD');
    tj.prodTablewhp = $('#prodTablewhp').DataTable( {
        "ajax": {
			type:"POST",
            url:"inc/data.php?req=getProdwhp",
            data: function(d) {
				d.id = tj.prodIdwhp;
				d.prodStart = tj.prodStartwhp;
				d.prodEnd = tj.prodEndwhp;
                d.start = tj.prodStartDatewhp;
                d.end = tj.prodEndDatewhp;
            }
            
        },
		select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        //sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
        //"order": [[12,'desc']],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			switch(Number(aData.style)) {
            case 1:
				$('td', nRow).addClass('redRow');
                break;
			case 2:
				$('td', nRow).addClass('greenRow');
                break;
            }			
        },
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "unit" },
            { "data": "shift" },
			{ "data": "aprod" },
			{ "data": "variance" },
			{ "data": "patients" },
			{ "data": "total" },
			{ "data": "charge" },
			{ "data": "nursecount" },
			{ "data": "techs" },
			{ "data": "secs" },
			{ "data": "other" },
			{ "data": "note" },
			{ "data": "shiftnum" }
        ],
		"columnDefs": [
						{"visible": document.getElementById('col1').val == "1", "targets": [6]},
						{"visible": document.getElementById('col2').val == "1", "targets": [7]},
						{"visible": document.getElementById('col3').val == "1", "targets": [8]},
						{"visible": document.getElementById('col4').val == "1", "targets": [9]},
						{"visible": document.getElementById('col5').val == "1", "targets": [10]},
						{"visible": false, "targets": [12] }
                     ],
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + newStart + ' through ' + newEnd,
				title: 'Staffing Reports',
				//title: function() {
				//return $('#pdfTitle').val()
				//},
				message: 'New Message',
				orientation: 'landscape',
				exportOptions: {
				columns: ':visible'
				},
				customize: function (doc) {
				doc.styles.title = {
				fontSize: '14',
				alignment: 'center'
				}, 
				doc.pageMargins = [50,50,50,50];
				doc.defaultStyle.fontSize = 11;
				doc.styles.tableHeader.fontSize = 12;
				//doc.styles.title.fontSize = 14;
				// Remove spaces around page title
				doc.content[0].text = doc.content[0].text.trim();
				// Create a footer
				doc['footer']=(function(page, pages) {
					return {
						columns: [
							'CONFIDENTIAL',
							{
								// This is the right column
								alignment: 'right',
								text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
							}
						],
						margin: [20, 0]
					}
				});
				// Styling the table: create style object
				var objLayout = {};
				// Horizontal line thickness
				objLayout['hLineWidth'] = function(i) { return .5; };
				// Vertikal line thickness
				objLayout['vLineWidth'] = function(i) { return .5; };
				// Horizontal line color
				objLayout['hLineColor'] = function(i) { return '#aaa'; };
				// Vertical line color
				objLayout['vLineColor'] = function(i) { return '#aaa'; };
				// Left padding of the cell
				objLayout['paddingLeft'] = function(i) { return 10; };
				// Right padding of the cell
				objLayout['paddingRight'] = function(i) { return 10; };
				// Inject the object in the document
				doc.content[1].layout = objLayout;
				}
			}
		]
    } );


///Edit WHP

tj.editWHP = function(dataId) {
		//$('#addWHP').modal({backdrop: 'static', keyboard: false})
		//var b1 = document.getElementById('plannedWHP1');
		//document.getElementById("showchurn").style.display='none';
		document.getElementById("hiddenskill1").style.display='none';
		document.getElementById("hiddenskill2").style.display='none';
		document.getElementById("hiddenskill3").style.display='none';
		document.getElementById("hiddenskill4").style.display='none';
		document.getElementById("hiddenskill5").style.display='none';
		//document.getElementById("hiddenskill6").style.display='none';
		//var b2 = document.getElementById('plannedWHP2');
		//var b3 = document.getElementById('actualHours');
		//b2.style.display='';
		//b1.style.display='';
		//b3.style.display='';		
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var a1 = document.getElementById('hiddenskill1');
				var a2 = document.getElementById('hiddenskill2');	
				var a3 = document.getElementById('hiddenskill3');	
				var a4 = document.getElementById('hiddenskill4');	
				var a5 = document.getElementById('hiddenskill5');	
				
				
				//var nowwhp = ((response.data.skill1val+response.data.skill2val+response.data.skill3val+response.data.skill4val+response.data.skill5val+response.data.addResourceHrs)/response.data.procedureCount);
				
				if (response.data.skill1 ==1) {
				a1.style.display='';
				}
				if (response.data.skill2 ==1) {
				a2.style.display='';
				}
				if (response.data.skill3 ==1) {
				a3.style.display='';
				}
				if (response.data.skill4 ==1) {
				a4.style.display='';
				}
				if (response.data.skill5 ==1) {
				a5.style.display='';
				}
							
				if (response.data.whpPlan ==1) {
				//b2.style.display='none';
				//b3.style.display='none';
				$('#actual').html('Planned for '+response.data.reportdate);
				}
				if (response.data.whpPlan ==0) {
				//b2.style.display='none';
				//b3.style.display='none';
				$('#actual').html('Actuals for '+response.data.reportdate);
				}
				if (response.data.uosDesc.length >0){
				$('#uosDesc').html(response.data.uosDesc);
				}else{
				$('#uosDesc').html('Units of Service');	
				}
				$('#userNameWHP').html(response.data.first_name+' '+response.data.last_name);
				$('#reportdateWHP').html(response.data.reportdate);
				$('#reportshiftWHP').html(response.data.reportshift);
				$('#shiftWHP').val(response.data.shift);
				
				$('#actualWHP').val(response.data.hppd);
				$('#targetWHP').html(response.data.hppd);
				$('#currentWHP').html(response.data.actualWHP);
				$('#dayWHP').val(response.data.dayDate);
				$('#currentVar').html(response.data.currentVar);
				$('#plannedWHP').val(response.data.procedureCount);
				$('#dataIdWHP').val(response.data.id);
				//$('#dataId2WHP').html('<a href="#whpView?i='+response.data.dataIdWHP+'">Print View</a>');
				$('#prodnoteWHP').val(response.data.note);
				$('#skillval1').val(response.data.skill1val);
				$('#skillval2').val(response.data.skill2val);
				$('#skillval3').val(response.data.skill3val);
				$('#skillval4').val(response.data.skill4val);
				$('#skillval5').val(response.data.skill5val);
				$('#descskill1').html(response.data.skilldesc1);
				$('#descskill2').html(response.data.skilldesc2);
				$('#descskill3').html(response.data.skilldesc3);
				$('#descskill4').html(response.data.skilldesc4);
				$('#descskill5').html(response.data.skilldesc5);
				//$('#prodTablewhp').DataTable().search('').draw();
				$('#addWHP').modal('show');
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  } 
  

///Clear WHP Record

tj.clearwhpRecord = function(dataId) {
		$.ajax({
            url:'inc/data.php?req=clearwhpRecord',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			tj.prodTablewhp.ajax.reload(null,false);	
				}
        })
        
		
  }
  
 ///Copy WHP Record

tj.copyPlan = function(dataId,deptId,shift,dayDate) {
		console.log('dataId: ',dataId);
		console.log('deptId: ',deptId);
		console.log('shift: ',shift);
		console.log('date: ',dayDate);
		
		$.ajax({
            url:'inc/data.php?req=copyPlan',
            data:{
                dataId: dataId,
				deptId: deptId,
				shift: shift,
				dayDate: dayDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			if(response.message == false){
			alert('No data from previous week to copy.  Use the Add button.');
			}else{
			tj.prodTablewhp.ajax.reload(null,false);	
			}
			}
        })
        
		
  }

tj.updateWHP = function() {
		var procs = $('#plannedWHP').val();
		var hours = $('#plannedHRS').val();
		var whp = $('#actualWHP').val();
		if(procs >=1 && hours >=1){
		$('.modalShow').click(function(event){
			event.preventDefault();
			var currentwhp = hours / procs;
			var currentvar = (currentwhp - whp) / procs;
			//var e = $(this);
			//var title = e.data('title');
			//var body = e.data('value');
			$("#addWHP").modal("show");
			$('#plannedVar').html(currentvar);
			$('#currentWHP').html(currentwhp);
		});
		}
  }  
  
	
  /////////////////////////////////////
// Update Productivity Record

tj.updateProdWHP = function() {
		
		var shift = $('#shiftWHP').val();
		var day = $('#dayWHP').val();
		var skill1 = $('#skillval1').val();
		var skill2 = $('#skillval2').val();
		var skill3 = $('#skillval3').val();
		var skill4 = $('#skillval4').val();
		var skill5 = $('#skillval5').val();
		var dataId = $('#dataIdWHP').val();
		var procedureCount = $('#plannedWHP').val();
		var note = $('#prodnoteWHP').val();
		var currentTime = moment().format('YYYY-MM-DD hh:mm:ss');
					
		if (procedureCount == '') {
			bootbox.alert('Please select your Procedure Count.');
		}
		
        $.ajax({
            url:'inc/data.php?req=updateprodWHP',
            data:{
                shift: shift,
				day: day,
				dataId: dataId,
				procedureCount: procedureCount,
				note: note,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				currentTime: currentTime,
				skill5: skill5
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.message == true) {
				$('#notedataIdwhp').val(response.data.dataId);
				$('#notedeptIdwhp').val(response.data.deptId);
				$('#deptwhp').html(response.data.dept);
				$('#varianceTypewhp').html(response.data.varianceType);
				$('#notebodywhp').html(response.data.note);
				$('#addWHP').modal('hide');
				$('#addprodnotewhp').modal('show');
				tj.prodTablewhp.ajax.reload(null,false);
				tj.complianceTable.ajax.reload();
				//console.log('dataId: ',response.data.dataId);
				}else if (response.data.message == false && response.data.textAlerts ==1 && response.data.txtpause ==0 && response.data.txtactive ==1 && response.data.txtescalation ==1 && response.data.escalations ==0) {
				$('#dataIdescwhp').val(response.data.dataId);
				$('#deptIdescwhp').val(response.data.deptId);
				document.getElementById("userIdWHP").value = "";
				document.getElementById("shiftWHP").value = "";
				document.getElementById("dayWHP").value = "";
				document.getElementById("prodnoteWHP").value = "";
				document.getElementById("dataIdWHP").value = "";
				document.getElementById("skillval1").value = "";
				document.getElementById("skillval2").value = "";
				document.getElementById("skillval3").value = "";
				document.getElementById("skillval4").value = "";
				document.getElementById("skillval5").value = "";
				$('#addWHP').modal('hide');
				$('#escalationNEWwhp').modal('show');
				tj.prodTablewhp.ajax.reload(null,false);
				tj.complianceTable.ajax.reload();
                }else{
				document.getElementById("userIdWHP").value = "";
				document.getElementById("shiftWHP").value = "";
				document.getElementById("dayWHP").value = "";
				document.getElementById("prodnoteWHP").value = "";
				document.getElementById("dataIdWHP").value = "";
				document.getElementById("skillval1").value = "";
				document.getElementById("skillval2").value = "";
				document.getElementById("skillval3").value = "";
				document.getElementById("skillval4").value = "";
				document.getElementById("skillval5").value = "";
				$('#addWHP').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
				tj.complianceTable.ajax.reload();
				}	
            }
			
        })
				
 }
	
	  /////////////////////////////////////
// ADD PROD NOTE
	
tj.addProdNotewhp = function () {
        var dataId = $('#notedataIdwhp').val();
		var note = $('#notebodywhp').val();
		var deptId = $('#notedeptIdwhp').val();
		
		
        $.ajax({
            url: 'inc/data.php?req=prodNote',
            data: {
                note: note,
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success:function(response) {
				if(response.data.txtactive==1 && response.data.textAlerts==1 && response.data.escalations==0 && response.data.txtpause==0 && response.data.txtescalation==1){
				$('#dataIdescwhp').val(dataId);
				$('#deptIdescwhp').val(deptId);
				$('#addprodnotewhp').modal('hide');
				$('#escalationNEWwhp').modal('show');
				//tj.prodTablewhp.ajax.reload();				
				}else{				
				document.getElementById("notebodywhp").value = "";
				document.getElementById("notedataIdwhp").value = "";
				document.getElementById("notedeptIdwhp").value = "";
				$('#addprodnotewhp').modal('hide');
				$('#addWHP').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports2();
				}				
            }
        });
    }
	
	
tj.noEscalationwhp = function () {
        //var dataId = $('#dataId').val();
		var esc = 0;
		var sendtext = 0;
				
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                sendtext: sendtext
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				document.getElementById("dataIdescwhp").value = "";
                $('#escalationNEWwhp').modal('hide');
				//tj.reportsLoaded = false;
				//tj.loadReports();
				//tj.loadReports2();	
                tj.prodTablewhp.ajax.reload(null,false);
			}
        });
    }
	
tj.addEscalationwhp = function () {
        var dataId = $('#dataIdescwhp').val();
		var deptId = $('#deptIdescwhp').val();
		var esc = $('#escvalwhp').val();
		var comment = $('#escalationcommentwhp').val();
		var sendtext = 1;
		if(esc==0){
    bootbox.alert('Please select an Esclation');
    return;
		}
						
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                dataId: dataId,
				deptId: deptId,
				sendtext: sendtext,
				comment: comment
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Successfully Submitted.');
				document.getElementById("escvalwhp").selectedIndex = 0;
				document.getElementById("dataId2whp").value = "";
				document.getElementById("escalationcommentwhp").value = "";
				$('#escalationNEWwhp').modal('hide');
                $('#addEscalationwhp').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
				//tj.reportsLoaded = false;
				//tj.loadReports2();
            }
        });
    }
	
tj.addEscalation3whp = function () {
        var dataId = ''
		var deptId = $('#deptId3whp').val();
		var esc = $('#escval3whp').val();
		var comment = $('#escalationcomment3whp').val();
		var sendtext = 1;
	if(esc==0){
    bootbox.alert('Please select an Esclation');
    return;
	}
	if(deptId==0){
    bootbox.alert('Please select the Unit associated with the Escalation.');
    return;
	}				
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                dataId: dataId,
				deptId: deptId,
				sendtext: sendtext,
				comment: comment
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Successfully Submitted.');
				//tj.reportsLoaded = false;
				//tj.loadReports();
				//tj.loadReports2();	
                document.getElementById("escval3whp").selectedIndex = 0;
				document.getElementById("dataId3whp").value = "";
				document.getElementById("escalationcomment3whp").value = "";
				//$('#escalationNEW').modal('hide');
                $('#addEscalation3whp').modal('hide');
            }
        });
    }
	
}

/////////////////////////////////////
// PERFORMANCE GLOBALS
//tj.performanceId = '';

tj.performanceStartDate = '';
tj.performanceEndDate = '';
tj.initializePerformanceGrid = function(id) {
    tj.performanceStartDate = moment().format('YYYY-MM-DD');
    tj.performanceEndDate = moment().format('YYYY-MM-DD');
    tj.performanceTable = $('#performanceTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getPerformance",
            data: function(d) {
				d.id = tj.performanceId;
                d.start = tj.performanceStartDate;
                d.end = tj.performanceEndDate;
            },
            type:"POST"
        },
        "order": [[2,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading your Candidates...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
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
        "columns": [
            { "data": "user" },
			{ "data": "unit" },
			{ "data": "prod" },
			{ "data": "target" },
			{ "data": "patients" },
			{ "data": "records" },
			{ "data": "date" }
        ],
		//"columnDefs": [
		//				{"visible": false, "targets": [3] }
        //],
		 "dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + tj.performanceStartDate + ' through ' + tj.performanceEndDate,
				title: 'User Performance',
				message: 'New Message',
				orientation: 'landscape',
				exportOptions: {
				columns: ':visible'
				},
				customize: function (doc) {
				doc.styles.title = {
				fontSize: '14',
				alignment: 'center'
				}, 
				doc.pageMargins = [50,50,50,50];
				doc.defaultStyle.fontSize = 14;
				doc.styles.tableHeader.fontSize = 16;
				//doc.styles.title.fontSize = 14;
				// Remove spaces around page title
				doc.content[0].text = doc.content[0].text.trim();
				// Create a footer
				doc['footer']=(function(page, pages) {
					return {
						columns: [
							'CONFIDENTIAL',
							{
								// This is the right column
								alignment: 'right',
								text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
							}
						],
						margin: [20, 0]
					}
				});
				// Styling the table: create style object
				var objLayout = {};
				// Horizontal line thickness
				objLayout['hLineWidth'] = function(i) { return .5; };
				// Vertikal line thickness
				objLayout['vLineWidth'] = function(i) { return .5; };
				// Horizontal line color
				objLayout['hLineColor'] = function(i) { return '#aaa'; };
				// Vertical line color
				objLayout['vLineColor'] = function(i) { return '#aaa'; };
				// Left padding of the cell
				objLayout['paddingLeft'] = function(i) { return 10; };
				// Right padding of the cell
				objLayout['paddingRight'] = function(i) { return 10; };
				// Inject the object in the document
				doc.content[1].layout = objLayout;
				}
			}
		]
    } );
	

}


/////////////////////////////////////
// ESCALATION GLOBALS
//tj.escalationsID = '';


tj.complianceStartDate = '';
tj.complianceEndDate = '';
tj.initializeComplianceGrid = function(id) {
    tj.complianceStartDate = moment().format('YYYY-MM-DD');
    tj.complianceEndDate = moment().format('YYYY-MM-DD');
    tj.complianceTable = $('#complianceTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getcompliance",
            data: function(d) {
				d.id = tj.complianceId;
                d.start = tj.complianceStartDate;
                d.end = tj.complianceEndDate;
            },
            type:"POST"
        },
        "order": [[3,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "director" },
			{ "data": "manager" },
			{ "data": "reports" }
        ],
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + tj.performanceStartDate + ' through ' + tj.performanceEndDate,
				title: 'Reporting Compliance',
				message: 'New Message',
				orientation: 'portrait',
				exportOptions: {
				columns: ':visible'
				},
				customize: function (doc) {
				doc.styles.title = {
				fontSize: '14',
				alignment: 'center'
				}, 
				doc.pageMargins = [100,50,50,50];
				doc.defaultStyle.fontSize = 11;
				doc.styles.tableHeader.fontSize = 12;
				//doc.styles.title.fontSize = 14;
				// Remove spaces around page title
				doc.content[0].text = doc.content[0].text.trim();
				// Create a footer
				doc['footer']=(function(page, pages) {
					return {
						columns: [
							'CONFIDENTIAL',
							{
								// This is the right column
								alignment: 'right',
								text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
							}
						],
						margin: [20, 0]
					}
				});
				// Styling the table: create style object
				var objLayout = {};
				// Horizontal line thickness
				objLayout['hLineWidth'] = function(i) { return .5; };
				// Vertikal line thickness
				objLayout['vLineWidth'] = function(i) { return .5; };
				// Horizontal line color
				objLayout['hLineColor'] = function(i) { return '#aaa'; };
				// Vertical line color
				objLayout['vLineColor'] = function(i) { return '#aaa'; };
				// Left padding of the cell
				objLayout['paddingLeft'] = function(i) { return 10; };
				// Right padding of the cell
				objLayout['paddingRight'] = function(i) { return 10; };
				// Inject the object in the document
				doc.content[1].layout = objLayout;
				}
			}
		]
    } );
	
}

/////////////////////////////////////
// ESCALATION GLOBALS
//tj.escalationsID = '';


tj.escalationsStartDate = '';
tj.escalationsEndDate = '';
tj.initializeEscalationsGrid = function(id) {
    tj.escalationsStartDate = moment().format('YYYY-MM-DD');
    tj.escalationsEndDate = moment().format('YYYY-MM-DD');
    tj.escalationsTable = $('#escalationsTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getEscalations",
            data: function(d) {
				d.id = tj.escalationId;
                d.start = tj.escalationsStartDate;
                d.end = tj.escalationsEndDate;
            },
            type:"POST"
        },
        "order": [[0,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "date" },
			{ "data": "unit" },
			{ "data": "user" },
			{ "data": "type" },
			{ "data": "notes" }
        ],
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + tj.performanceStartDate + ' through ' + tj.performanceEndDate,
				title: 'Escalations',
				message: 'New Message',
				orientation: 'landscape',
				exportOptions: {
				columns: ':visible'
				},
				customize: function (doc) {
				doc.styles.title = {
				fontSize: '14',
				alignment: 'center'
				}, 
				doc.pageMargins = [50,50,50,50];
				doc.defaultStyle.fontSize = 11;
				doc.styles.tableHeader.fontSize = 12;
				//doc.styles.title.fontSize = 14;
				// Remove spaces around page title
				doc.content[0].text = doc.content[0].text.trim();
				// Create a footer
				doc['footer']=(function(page, pages) {
					return {
						columns: [
							'CONFIDENTIAL',
							{
								// This is the right column
								alignment: 'right',
								text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
							}
						],
						margin: [20, 0]
					}
				});
				// Styling the table: create style object
				var objLayout = {};
				// Horizontal line thickness
				objLayout['hLineWidth'] = function(i) { return .5; };
				// Vertikal line thickness
				objLayout['vLineWidth'] = function(i) { return .5; };
				// Horizontal line color
				objLayout['hLineColor'] = function(i) { return '#aaa'; };
				// Vertical line color
				objLayout['vLineColor'] = function(i) { return '#aaa'; };
				// Left padding of the cell
				objLayout['paddingLeft'] = function(i) { return 10; };
				// Right padding of the cell
				objLayout['paddingRight'] = function(i) { return 10; };
				// Inject the object in the document
				doc.content[1].layout = objLayout;
				}
			}
		]
    } );
	
}
	

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.staffingdaterangepickerInit = function(startPay,endPay,role) {
    if ($('#staffing_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#staffing_daterangepicker');
    //var start = moment().subtract('days', 29);
	
	if(role<8){
	var start = moment();
    var end = moment();
	}else{
	var start = moment().subtract(1, 'days');
    var end = moment().subtract(1, 'days');
	}

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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-staffing__daterange-date').html(range);
        picker.find('.m-staffing__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
		//if(start1 && end1){
		//tj.prodStartDate = start1.format('YYYY-MM-DD');
        //tj.prodEndDate = end1.format('YYYY-MM-DD');
		//}else{
        tj.prodStartDate = start.format('YYYY-MM-DD');
        tj.prodEndDate = end.format('YYYY-MM-DD');
		//}
        tj.prodTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
		
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().add(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else{
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
	}
    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.staffingdaterangepickerInitwhp = function(startPay,endPay,role) {
    if ($('#staffing_daterangepickerwhp').length == 0) {
        return;
    }

    var picker = $('#staffing_daterangepickerwhp');
    //var start = moment().subtract('days', 29);
	if(role<8){
	var start = moment();
    var end = moment();
	}else{
	var start = moment().subtract(1, 'days');
    var end = moment().subtract(1, 'days');
	}

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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-whp__daterange-date').html(range);
        picker.find('.m-whp__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.prodStartDatewhp = start.format('YYYY-MM-DD');
        tj.prodEndDatewhp = end.format('YYYY-MM-DD');
        tj.prodTablewhp.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
		
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().add(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}
    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER FOR ESCALATIONS
tj.compliancedaterangepickerInit = function() {
    if ($('#compliance_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#compliance_daterangepicker');
    var start = moment().subtract(1, 'days');
	//var start = moment();
    var end = moment().subtract(1, 'days');;

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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-subheader__daterange-date').html(range);
        picker.find('.m-subheader__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.complianceStartDate = start.format('YYYY-MM-DD');
        tj.complianceEndDate = end.format('YYYY-MM-DD');
        tj.complianceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

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

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER FOR ESCALATIONS
tj.escalationsdaterangepickerInit = function() {
    if ($('#escalations_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#escalations_daterangepicker');
    var start = moment().subtract(29, 'days');
	//var start = moment();
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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-escalations__daterange-date').html(range);
        picker.find('.m-escalations__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.escalationsStartDate = start.format('YYYY-MM-DD');
        tj.escalationsEndDate = end.format('YYYY-MM-DD');
        tj.escalationsTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

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

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER FOR DELIVERIES
tj.deliverydaterangepickerInit = function() {
    if ($('#delivery_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#delivery_daterangepicker');
    //var start = moment().subtract('days', 29);
	var start = moment();
    //var end = moment();
	var end = moment().add(30, 'days');

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
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-delivery__daterange-date').html(range);
        picker.find('.m-delivery__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.deliveryStartDate = start.format('YYYY-MM-DD');
        tj.deliveryEndDate = end.format('YYYY-MM-DD');
        //tj.escalationsTable.ajax.reload();
        tj.updateDeliveryReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }

    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Next 7 Days': [moment(), moment().add(6, 'days')],
            'Next 14 Days': [moment(), moment().add(13, 'days')],
            'Next 30 Days': [moment(), moment().add(29, 'days')],
        }
    }, cb);

    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.performancedaterangepickerInit = function() {
    if ($('#performance_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#performance_daterangepicker');
    var start = moment().subtract(29, 'days');
	//var start = moment();
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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-subheader__daterange-date').html(range);
        picker.find('.m-subheader__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.performanceStartDate = start.format('YYYY-MM-DD');
        tj.performanceEndDate = end.format('YYYY-MM-DD');
        tj.performanceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

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

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.classdaterangepickerInit = function() {
    if ($('#class_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#class_daterangepicker');
    var start = moment().subtract(29, 'days');
	//var start = moment();
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
            //title = 'Yesterday:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-class__daterange-date').html(range);
        picker.find('.m-class__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.classStartDate = start.format('YYYY-MM-DD');
        tj.classEndDate = end.format('YYYY-MM-DD');
        //tj.classTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

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

/////////////////////////////////////
// INITIALIZE THE LOCATIONS TABLE
tj.initializeProdGriduserx = function() {
    tj.prodTableuserx = $('#prodTableuserx').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getProduserx",
            data: {},
            type:"POST"
        },
        //"order": [[5,'desc']],
        processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "acct" },
			{ "data": "dept" },
			{ "data": "prod" },
			{ "data": "var" },
			{ "data": "time" },
			{ "data": "shiftnum" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [5] }
                     ]
    } );
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
    //if (tj.debug) console.log('page load');
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
            //if (tj.debug) console.log('reports');
            tj.loadReports();
            break;
        case "#profile":
            //if (tj.debug) console.log('profile');
            tj.loadProfile();
            break;
        case "#support":
            //if (tj.debug) console.log('support');
            tj.loadSupport();
            break;
        case "#users":
            //if (tj.debug) console.log('support');
            tj.loadUsers();
            break;
		case "#userx":
            //if (tj.debug) console.log('support');
            tj.loadUserx();
            break;
		case "#staffing":
            //if (tj.debug) console.log('prod');
            tj.loadProd();
            break;
		case "#reportView":
            //if (tj.debug) console.log('prod');
            tj.loadReportView();
            break;
		/*
		case "#nurseView":
            //if (tj.debug) console.log('prod');
            tj.loadNurseView();
            break;
		*/
		case "#supportstaffing":
            //if (tj.debug) console.log('prod');
            tj.loadProdwhp();
            break;
		case "#performance":
            //if (tj.debug) console.log('performance');
			//$('#performanceTable').DataTable().destroy();
			//tj.performanceLoaded = false;
            tj.loadPerformance();
            break;
		case "#escalations":
            //if (tj.debug) console.log('escalations');
            tj.loadEscalations();
            break;
		case "#compliance":
            //if (tj.debug) console.log('escalations');
            tj.loadCompliance();
            break;
		case "#delivery":
            //if (tj.debug) console.log('delivery');
            tj.loadDelivery();
            break;
		case "#new":
            //if (tj.debug) console.log('new');
            tj.loadNew();
            break;
		case "#configure":
            //if (tj.debug) console.log('configure');
            tj.loadConfigure();
            break;
		case "#units":
            //if (tj.debug) console.log('units');
            tj.loadUnits();
            break;
		case "#accounts":
            //if (tj.debug) console.log('units');
            tj.loadAccounts();
            break;
		case "#classes":
            //if (tj.debug) console.log('classes');
            tj.loadClasses();
            break;
    }
};

/////////////////////////////////////
// INITIALIZE THE DASHBOARD ON LOAD
tj.initializeDashboard = function() {
    //if (tj.debug) console.log('Initializing Dashboard');
    tj.parseHash();
    tj.switchView(tj.currentTab);
};

/////////////////////////////////////
// HANDLE URL HASH CHANGE
jQuery(window).on('hashchange', function() {
    //if (tj.debug) console.log('hash changed');
    tj.parseHash();
    tj.switchView(tj.currentTab);
});


////Report View/////////

tj.getReportView = function(dataId) {
		//$('#nurseView').show();
		document.getElementById("viewWHP").style.display='';
		document.getElementById("viewNurse").style.display='';
		document.getElementById("customcount").style.display='none';
		document.getElementById("inshiftview").style.display='none';		
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var a1 = document.getElementById('hiddenwhp1');
				var a2 = document.getElementById('hiddenwhp2');	
				var a3 = document.getElementById('hiddenwhp3');	
				var a4 = document.getElementById('hiddenwhp4');	
				var a5 = document.getElementById('hiddenwhp5');	
				var a6 = document.getElementById('viewWHP');
				
				var n1 = document.getElementById('hidden1view');
				var n2 = document.getElementById('hidden2view');	
				var n3 = document.getElementById('hidden3view');	
				var n4 = document.getElementById('hidden4view');	
				var n5 = document.getElementById('hidden5view');	
				var n6 = document.getElementById('hidden6view');
				var n7 = document.getElementById('churnView');
				var n8 = document.getElementById('viewNurse');
				var n9 = document.getElementById('customcount');
				var n10 = document.getElementById('inshiftview');
				var n11 = document.getElementById('hidden7view');
				var n12 = document.getElementById('hidden8view');				
				
				if(response.data.prodMeasure==2){
				
				a6.style.display='';
				n8.style.display='none';
				if (response.data.skill1 ==1) {
				a1.style.display='';
				}else{
				a1.style.display='none';
				}
				if (response.data.skill2 ==1) {
				a2.style.display='';
				}else{
				a2.style.display='none';
				}
				if (response.data.skill3 ==1) {
				a3.style.display='';
				}else{
				a3.style.display='none';
				}
				if (response.data.skill4 ==1) {
				a4.style.display='';
				}else{
				a4.style.display='none';
				}
				if (response.data.skill5 ==1) {
				a5.style.display='';
				}else{
				a5.style.display='none';
				}
				if (response.data.whpPlan ==1) {
				$('#hourswhp').html('Planned Hours');
				$('#uoswhp').html('Planned Units of Service');
				$('#reportType').html(' (Planned)');
				}
				if (response.data.whpPlan ==0) {
				$('#hourswhp').html('Actual Hours');
				$('#uoswhp').html('Actual Units of Service');
				$('#reportType').html(' (Actual)');
				}
				if (response.data.budgetMeasure ==1) {
				$('#budgetwhpVal').html('Budget: ' + response.data.budgetVal + '%');
				}
				if (response.data.escalationValue !=0) {
				$('#escalationwhpVal').html('Escalation: ' + response.data.escalationType + ', ' + response.data.escalationNote);
				//document.getElementById('escStyle').setAttribute("style", "color:red;");
				}
				
				$('#updatedwhpBy').html(response.data.first_name+' '+response.data.last_name);
				$('#updatedwhpDate').html(response.data.reportdate);
				$('#updatedwhpentered').html(response.data.entered);
				$('#depname').html(response.data.depname);
				$('#updatdwhpShift').html(response.data.reportshift);
				//$('#shiftWHP').val(response.data.shift);
				$('#actualwhpUOS').html(response.data.actualWHP);
				$('#targetwhpUOS').html(response.data.hppd);
				$('#hourswhpVariance').html(response.data.todayVar);
				$('#targetwhp').html(response.data.hppd);
				$('#daywhpDate').html(response.data.reportDate);
				$('#skillwhp1').val(response.data.skill1val);
				$('#skillwhp2').val(response.data.skill2val);
				$('#skillwhp3').val(response.data.skill3val);
				$('#skillwhp4').val(response.data.skill4val);
				$('#skillwhp5').val(response.data.skill5val);
				$('#skilldsc1').html(response.data.skilldesc1);
				$('#skilldsc2').html(response.data.skilldesc2);
				$('#skilldsc3').html(response.data.skilldesc3);
				$('#skilldsc4').html(response.data.skilldesc4);
				
				$('#totaluosWHP').val(response.data.procedureCount);
				$('#dataidwhp').html('<a href="../w.php?i='+response.data.id+'">Print View</a>');
				$('#whpnote').html(response.data.note);	
				}else{
				a6.style.display='none';
				n8.style.display='';
				if (response.data.acuity1 !=0) {
				n1.style.display='';
				}else{
				n1.style.display='none';
				}
				if (response.data.acuity2 !=0) {
				n2.style.display='';
				}else{
				n2.style.display='none';
				}
				if (response.data.acuity3 !=0) {
				n3.style.display='';
				}else{
				n3.style.display='none';
				}
				if (response.data.acuity4 !=0) {
				n4.style.display='';
				}else{
				n4.style.display='none';
				}
				if (response.data.acuity5 !=0) {
				n5.style.display='';
				}else{
				n5.style.display='none';
				}
				if (response.data.acuity6 !=0) {
				n6.style.display='';
				}else{
				n6.style.display='none';
				}
				if (response.data.acuity7 !=0) {
				n11.style.display='';
				}else{
				n11.style.display='none';
				}
				if (response.data.acuity8 !=0) {
				n12.style.display='';
				}else{
				n12.style.display='none';
				}
				if (response.data.churn ==1) {
				n7.style.display='';
				}else{
				n7.style.display='none';
				}
				
				if (response.data.escalationValue !=0) {
				$('#escalationView').html('Escalation: ' + response.data.escalationType + ', ' + response.data.escalationNote);
				}
				if (response.data.blockedBeds !=0) {
				$('#blockedView').html('Blocked Beds: ' + response.data.blockedBeds);
				}
				if (response.data.nurse1 ==1) {
				n9.style.display='';
				}
				if (response.data.inshiftProd ==1) {
				n10.style.display='';
				}				
				
				$('#updatedbyView').html(response.data.first_name+' '+response.data.last_name);
				$('#dateView').html(response.data.reportdate);
				$('#shiftView').html(response.data.reportshift);
				$('#enteredView').html(response.data.entered);
				$('#depnameView').html(response.data.depname);
				$('#customcountTitle').html(response.data.nurse1Desc);
				$('#customcountView').val(response.data.customNurse);
				//$('#shiftWHP').val(response.data.shift);
				$('#varianceView').html(response.data.nvariance);
				$('#prodView').html(response.data.aproductivity);
				$('#openbedsView').html(response.data.openbeds);
				$('#totalpatientsView').val(response.data.totalpatients);
				$('#chargecountView').val(response.data.chargecount);
				$('#techcountView').val(response.data.techcount);
				$('#seccountView').val(response.data.seccount);
				$('#nursecountView').val(response.data.antecount);
				$('#otherView').val(response.data.sittercount);
				
				$('#one2oneView').val(response.data.oneto1);
				$('#one2twoView').val(response.data.oneto2);
				$('#one2threeView').val(response.data.oneto3);
				$('#one2fourView').val(response.data.oneto4);
				$('#one2fiveView').val(response.data.oneto5);
				$('#one2sixView').val(response.data.oneto6);
				$('#descView1').html(response.data.desc1);
				$('#descView2').html(response.data.desc2);
				$('#descView3').html(response.data.desc3);
				$('#descView4').html(response.data.desc4);
				$('#descView5').html(response.data.desc5);
				$('#descView6').html(response.data.desc6);
				$('#descView7').html(response.data.desc7);
				$('#one2sevenView').val(response.data.oneto7);
				$('#descView8').html(response.data.desc8);
				$('#one2eightView').val(response.data.oneto8);
				
				//$('#printView2').html('<a href="../n.php?i='+response.data.id+'">Print View</a>');
				$('#prodnoteView').html(response.data.note);
				}
				$('#reportView').show();
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  }	

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
    //if (tj.debug) console.log('url params',tj.urlParams);
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
    if (menu && menu.mMenu) {
        tj.asideMenu = menu.mMenu(menuOptions);
    }

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
    bootbox.alert('Not all required info provided.');
    return;
  }

  $.ajax({
        url: 'inc/data.php?req=support',
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
          bootbox.alert('Thank you, we will be in contact within 24hrs.');
        }
});


};



/////////////////////////////////////
// INITIALIZE THE CLASSES TABLE
tj.classStartDate = '';
tj.classEndDate = '';
tj.classGrid = function() {
	tj.classStartDate = moment().format('YYYY-MM-DD');
    tj.classEndDate = moment().format('YYYY-MM-DD');
	document.getElementById("classtable").style.display='';
	document.getElementById("newclass").style.display='';
	document.getElementById("detailtable").style.display='none';
	
    tj.classTable = $('#classesTable').DataTable( {
        "ajax": {
                    url:"inc/data.php?req=getAllClasses",
                    data: function(d) {
					d.start = tj.classStartDate;
					d.end = tj.classEndDate;
            },
			type:"POST"
                },
		"order": [[0,'asc']],
		"paging": false,
		"searching": false,
		processing: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "nameClass" },
            { "data": "classDate" },
			{ "data": "attendees" },
			{ "data": "risk" }
        ]
    } );
}

tj.getclass = function(classId) {
		document.getElementById("reportDate").html=('');
		document.getElementById("reportTime").html=('');
		document.getElementById("lastEdit").html=('');
		$.ajax({
            url:'inc/data.php?req=getclass',
            data:{
                classId:classId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			//console.log('dateE', response.data.dateEntered);
			//console.log('datec', response.data.reportDate);
			$('#reportName').html(response.data.reportName);
			if(response.data.reportDate !='00/00/00'){
				$('#reportDate').html('Class Date: ' + response.data.reportDate);
			}else{
				$('#reportDate').html('Report Date: ' + response.data.dateEntered);				
			}
			if(response.data.reportTime !='00:00'){
			$('#reportTime').html('Class Time:  ' +response.data.reportTime);
			}else{
			$('#reportTime').html('');
			}
			if(response.data.lastEdit !=''){
			$('#lastEdit').html('Last Updated By:  ' +response.data.lastEdit);
			}else{
			$('#lastEdit').html('');
			}
			$('#classId').val(response.data.reportId);			
			document.getElementById("classtable").style.display='none';
			document.getElementById("class_daterangepicker").style.display='none';			
			document.getElementById("newclass").style.display='none';
			document.getElementById("detailtable").style.display='';
			tj.classDetails(classId);
			}
		})
}

tj.cancelclass = function() {
		//$('#item_table').DataTable().destroy();
		//$('#m_aside_left_close_btn').click();
		tj.classLoaded = false;
		tj.loadClasses();
		
}


	
/////////////////////////////////////
// GET CLASS DETAILS
tj.classDetails = function(classId) {
    tj.classdetailsTable = $('#item_table').DataTable({
        "ajax": {
                   url:"inc/data.php?req=getclassDetails",
                    data: {
						classId: classId
                     },
					type:'POST'
                },
		"order": [[0,'asc']],
		"paging": false,
		"searching": false,
		"aLengthMenu": [100],
        processing: true,
		serverSide: true,
        "language": {
            "processing": 'Loading Users...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "edc" },
			{ "data": "age" },
			{ "data": "grav" },
			{ "data": "par" },
			{ "data": "plan" },
			{ "data": "comp" },
			{ "data": "remove","orderable":false},
			{ "data": "recordId" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [7] }
                     ],
		});
		var count = 1;
		$('#add').click(function(){
		count = count +1;;
		var html = '';
		html += '<tr id="row'+count+'">';
		html += '<td><input type="date" id="edc" contenteditable="true" name="edc" value="" class="edc" /></td>';
		html += '<td><select name="age" id="age" contenteditable="true" class="age" ><option value=""></option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option><option value="60">60</option></select></td>';
		html += '<td><select name="grav" id="grav" contenteditable="true" class="grav" ><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option></select></td>';
		html += '<td><select name="par" id="par" contenteditable="true" class="par" ><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option></select></td>';
		html += '<td><select name="plan" id="plan" contenteditable="true" class="plan"><option value="1">Vag</option><option value="2">Induction</option><option value="3">C-Section</option><option value="4">V-Back</option><option value="5">Other</option></select></td>';
		html += '<td><select name="comp" id="comp" contenteditable="true" class="comp"><option value="0">None</option><option value="1">Diabetes</option><option value="2">Hypertension/MCD</option><option value="8">IUGR</option><option value="4">Multiple Gestation</option><option value="6">Oligo/Poly</option><option value="5">Previa</option><option value="3">Preterm Labor History/Demise</option><option value="7">Ruptured Membrane</option><option value="9">Other</option></select></td>';
		//html += '<td><button type="button" name="insert" class="btn btn-info btn-sm insert">Save</button />  <button type="button" name="remove" class="btn btn-danger btn-sm remove">-</button /></td>';
		html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">-</button /></td>';
		html += '<td><input type="text" name="recordId" id="recordId" class="recordId" hidden /></td>';
		html += '</tr>';
		$('#item_table').prepend(html);
		});
		$(document).on('click', '.save', function(){
			var edc = [];
			var grav = [];
			var par = [];
			var plan = [];
			var comp = [];
			var age = [];
			var classId = $('#classId').val();
			//console.log('classId', classId);
			$('.edc').each(function(){
				edc.push($(this).text());
			});
			$('.grav').each(function(){
				grav.push($(this).text());
			});
			$('.par').each(function(){
				par.push($(this).text());
			});
			$('.plan').each(function(){
				plan.push($(this).text());
			});
			$('.comp').each(function(){
				comp.push($(this).text());
			});
			$('.age').each(function(){
				age.push($(this).text());
			});
			
			if(edc !=''){
				$.ajax({
				url:'inc/data.php?req=saveTable',
				data:{
					edc: edc,
					grav: grav,
					par: par,
					plan: plan,
					comp: comp,
					classId: classId,
					age: age
				},
				method:'POST',
				dataType:'json',
				success:function(data) {
				//$('#item_table').DataTable().destroy();
				$("td[contentEditable='true']").text("");
				for(var i=2; i<=count; i++)
				{
					$('tr#'+i+'').remove();
				}
				tj.classdetailsTable.ajax.reload();
				}
				})
					
			}else{
				bootbox.alert("Nothing to save.");
			}
			
		});
		$(document).on('click','.remove', function(){
			//var delete_row = $(this).data("row");
			//$('#' + delete_row).remove();
			$(this).closest('tr').remove();
			tj.classdetailsTable.ajax.reload();
		});
}

////////////////////////////////////
//ADD BIRTHING CLASS

tj.birthClass = function(){
  var name = $('#className').val();
  var date = $('#classDate').val();
  var time = $('#classTime').val();
  var total = $('#attendTotal').val();
  
	

  if(name.length == 0 ){
    bootbox.alert('Report Name is required.');
    return;
  }
 

  $.ajax({
        url: 'inc/data.php?req=addClass',
        dataType: 'json',
        method: 'post',
        data: {
          date: date,
          name: name,
          time: time
        },
        success: function(response) {
		
		tj.getclass(response.data.classId);
		//document.getElementById("attendTotal").selectedIndex ="1";				
		document.getElementById("classDate").val = "";
		document.getElementById("classTime").val = "";
		document.getElementById("className").val = "";
		$('#addClass').modal('hide');
		
        }
        
});


}

////////////////////////////////////
//ADD DELIVERY RECORD

tj.addDelivery = function(add){
  var edc = $('#deliveryEDD').val();
  var age = $('#deliveryAge').val();
  var grav = $('#deliveryGrav').val();
  var par = $('#deliveryPar').val();
  var plan = $('#deliveryPlan').val();
  var comp = $('#deliveryComp').val();
  var classId = $('#deliveryclassId').val();
  var newrecord = add;
  
  if(edc.length == 0 ){
    bootbox.alert('EDD is required.');
    return;
  }
 
  $.ajax({
        url: 'inc/data.php?req=saveDelivery',
        dataType: 'json',
        method: 'post',
        data: {
          edc: edc,
          age: age,
          grav: grav,
		  par: par,
		  plan: plan,
		  comp: comp,
		  classId: classId
        },
        success: function(response) {
		
		$('#addDelivery').modal('hide');
		document.getElementById("deliveryEDD").val = "";
		document.getElementById("deliveryAge").val = "0";
		document.getElementById("deliveryGrav").val = "1";
		document.getElementById("deliveryPar").val = "0";
		document.getElementById("deliveryPlan").val = "1";
		document.getElementById("deliveryComp").val = "0";
		tj.classdetailsTable.ajax.reload();
		if(newrecord == 1){
		$('#addDelivery').modal('show');
		}
		}
        
});


}

////////////////////////////////////
//ADD DELIVERY RECORD

tj.newDelivery = function(){
  var classId = $('#classId').val();
	$("#deliveryclassId").val(classId);
	document.getElementById("deliveryEDD").val = "";
	document.getElementById("deliveryAge").val = "0";
	document.getElementById("deliveryGrav").val = "1";
	document.getElementById("deliveryPar").val = "0";
	document.getElementById("deliveryPlan").val = "1";
	document.getElementById("deliveryComp").val = "0";
	$('#addDelivery').modal('show');
	//console.log('class',classId);
}

////////////////////////////////////
//ADD BIRTHING CLASS

tj.deleteTable = function(recordId){ 
bootbox.confirm({
        message:"Delete this record?",
		backdrop:true,
        callback:function (result) {
			if (result) {	
			  $.ajax({
					url: 'inc/data.php?req=deleteTable',
					data: {
					  recordId: recordId
					},
					method:'POST',
					dataType:'json',
					success:function(data) {
					tj.classdetailsTable.ajax.reload();
					}
					
				});
			}
		}
		}).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
}

tj.updateClass = function(){
	
	 var form_data = JSON.stringify( $('#insert_form').serializeArray() ); 
	//var form_data = $('#insert_form').serializeArray();
	//$.post('url', form_data);
	console.info(form_data);
 
 $.ajax({
        url: 'inc/data.php?req=saveclass',
        method: 'POST',
        data: {
			form_data: form_data,
			classId: classId
			},
		dataType: 'json',
        success: function(data) {
		if (data == 'ok'){
			$('#item_table').find("tr:gt(0)").remove();
			//console.log('update made');
			}
		}
        
	});
	
}

tj.saveTable2 = function(){
	var edc = [];
	var grav = [];
	var par = [];
	var plan = [];
	var comp = [];
	var age = [];
	var classId = $('#classId').val();
	//console.log('classId', classId);
	
			$('.edc').each(function(){
				edc.push($(this).val());
			});
			$('.grav').each(function(){
				grav.push($(this).val());
			});
			$('.par').each(function(){
				par.push($(this).val());
			});
			$('.plan').each(function(){
				plan.push($(this).val());
			});
			$('.comp').each(function(){
				comp.push($(this).val());
			});
			$('.age').each(function(){
				age.push($(this).val());
			});
			
			if(edc !=''){
				$.ajax({
				url:'inc/data.php?req=saveTable',
				data:{
					edc: edc,
					grav: grav,
					par: par,
					plan: plan,
					comp: comp,
					classId: classId,
					age: age
				},
				method:'POST',
				dataType:'json',
				success:function(data) {
				tj.classdetailsTable.ajax.reload();
				edc = [];
				grav = [];
				par = [];
				plan = [];
				comp = [];
				age = [];
				classId='';
				}
			});
					
			}else{
				bootbox.alert("Nothing to save.");
			}
}

tj.addclassDetail = function() {
var html = '';
	html += '<tr>';
	html += '<td><input type="date" name="edc[]" value="" min="2018-01-01" max="2018-12-31" class="form-control edc" /></td>';
	html += '<td><select name="count[]" class="form-control count"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option></select></td>';
	html += '<td><input type="text" name="grav[]" class="form-control grav" /></td>';
	html += '<td><input type="text" name="par[]" class="form-control par" /></td>';
	html += '<td><select name="comp[]" class="form-control count"><option value="0">None</option><option value="1">Diabetes</option><option value="2">HBP/Preeclampsia</option><option value="3">Incomplete</option></select></td>';
	html += '<td><button type="button" name="remove" class="btn btn-danger btn-sm remove">Delete</button></td>';
	$('#item_table').append(html);	
}

////////////////////////////////////
//UPDATE DELIVERY

tj.updateDelivery = function(){ 
	var count = $('#deliveryCount').val();
	$.ajax({
		url: 'inc/data.php?req=updatedelivery',
		data: {
		  count: count
				},
			method:'POST',
			dataType:'json',
			success:function(data) {
			//tj.classdetailsTable.ajax.reload();
				}
			});
}



tj.classTable2 = function() {
$("#newclasstable").FullTable({
	"alwaysCreating":true,
  "fields": {
	  "Due Date":{
		"type":"date",
		"mandatory":true,
		"errors":{
        "mandatory":"Due Date is required."
      }
    },
    "Babies":{
      "options":[
        {
          "title":"1",
          "value":"1"
        },
		{
          "title":"2",
          "value":"2"
        },
		{
          "title":"3",
          "value":"3"
        },
		{
          "title":"4",
          "value":"4"
        },
		{
          "title":"5",
          "value":"5"
        },
		{
          "title":"6",
          "value":"6"
        },
        {
          "title":"7",
          "value":"7"
        }
      ],
      "mandatory":true,
      "placeholder":"Select one",
      "errors":{
        "mandatory":"# of Babies is required"
      }
    },
	"Delivery Plano":{
      "options":[
        {
          "title":"Natural",
          "value":"1"
        },
		{
          "title":"Induction",
          "value":"2"
        },
		{
          "title":"C-Section",
          "value":"3"
        },
		{
          "title":"V-Back",
          "value":"4"
        }
      ],
      "mandatory":false,
      "placeholder":"Select one",
    },
	"Gravida":{
      "options":[
        {
          "title":"1",
          "value":"1"
        },
		{
          "title":"2",
          "value":"2"
        },
		{
          "title":"3",
          "value":"3"
        },
		{
          "title":"4",
          "value":"4"
        },
		{
          "title":"5",
          "value":"5"
        },
		{
          "title":"6",
          "value":"6"
        },
        {
          "title":"7",
          "value":"7"
        },
		{
          "title":"8",
          "value":"8"
        },
		{
          "title":"9",
          "value":"9"
        },
		{
          "title":"10",
          "value":"10"
        }
      ],
      "mandatory":true,
      "placeholder":"Select one",
      "errors":{
        "mandatory":"Gravida is required"
      }
    },
	"Para":{
      "options":[
        {
          "title":"1",
          "value":"1"
        },
		{
          "title":"2",
          "value":"2"
        },
		{
          "title":"3",
          "value":"3"
        },
		{
          "title":"4",
          "value":"4"
        },
		{
          "title":"5",
          "value":"5"
        },
		{
          "title":"6",
          "value":"6"
        },
        {
          "title":"7",
          "value":"7"
        },
		{
          "title":"8",
          "value":"8"
        },
		{
          "title":"9",
          "value":"9"
        },
		{
          "title":"10",
          "value":"10"
        }
      ],
      "mandatory":true,
      "placeholder":"Select one",
      "errors":{
        "mandatory":"Para required"
      }
    },
     "Complications":{
      "options":[
        {
          "title":"Diabetes (Gestational or Other)",
          "value":"1"
        },
		{
          "title":"High Blood Pressure or Preeclampsia",
          "value":"2"
        },
		{
          "title":"Preterm Labor",
          "value":"3"
        }
      ],
      "mandatory":false,
      "placeholder":"Select one",
      },
  }
});	
	
}


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

window.refresh = function() {
    location.reload();
};

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
    
    
}(this));;