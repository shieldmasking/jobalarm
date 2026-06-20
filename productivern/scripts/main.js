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
tj.policiesLoaded = false;
tj.deliveryLoaded = false;
tj.complianceLoaded = false;
tj.whpLoaded = false;
tj.nurseLoaded = false;
tj.csvLoaded = false;
tj.crashLoaded = false;
tj.msgLoaded = false;
tj.safetyLoaded = false;
tj.customLoaded = false;
tj.chartsLoaded = false;
tj.dayRankLoaded = false;
tj.conciergeLoaded = false;
tj.tasksLoaded = false;
tj.clinicsLoaded = false;
tj.viewlogLoaded = false;
tj.qblLoaded = false;
tj.ablLoaded = false;
tj.nowLoaded = false;
tj.qrcodesLoaded = false;
tj.reportLoaded = false;
tj.qrLoaded = false;
tj.huddleLoaded = false;

tj.debug = true;    // allow debug console messages


/////////////////////////////////////
// LOAD REPORTS PAGE
tj.loadReports = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[1]);
	
    $('#reportsView').show();
	tj.reportsId='';
	//var categoryId=$('#filter').val();
    if (!tj.reportsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#reportsView').load('views/reports.php', {}, function () {
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.reportsId=tj.urlParams['id'];
            }
			if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
            tj.sortId=tj.urlParams['i'];
			}
			var startPay = $('#startPay').val();
			var endPay = $('#endPay').val();
			var start = $('#startDate').val();
			var end = $('#endDate').val();
			var role = $('#role').val();
			var demoMode = $('#demoMode').val();
			var currentSort = $('#currentSort').val();
			var a0 = document.getElementById('sort0');
			var a1 = document.getElementById('sort1');
			var a2 = document.getElementById('myCoverage');
			var safety = 0;
			
			
			if(role==6 || role==7 || role==11){
			a2.style.display='';	
			}else{
			a2.style.display='none';	
			}
						
			if(currentSort==0){
			a0.style.display='none';
			a1.style.display='';
			}else{
			a0.style.display='';
			a1.style.display='none';
			}
			console.log('safety: ',safety);
            tj.daterangepickerInit(tj.reportsId,startPay,endPay,role,start,end,currentSort,tj.sortId);
			tj.initSupportGrid();
			
			//tj.createReportCharts(tj.reportsId);
            tj.reportsLoaded = true;
            tj.stopLoading();
			
			//tj.initializeCoverageGrid();
        });
    }else{
		//jQuery('#reportsView').load('views/reports.php', {}, function () {
		    
			var startPay = $('#startPay').val();
			var endPay = $('#endPay').val();
			var categoryId = $('#filter').val();
			var locationId = $('#location').val();
			var currentSort = $('#currentSort').val();
			var role = $('#role').val();
			//var start = moment().subtract($('#startDate').val(), 'days').format('YYYY-MM-DD');
			//var end = moment().subtract($('#endDate').val(), 'days').format('YYYY-MM-DD');
			var start = $('#startDate').val();
			var end = $('#endDate').val();
			var a0 = document.getElementById('sort0');
			var a1 = document.getElementById('sort1');
			var safety = $('#showSafety').is(':checked') ? 1 : 0;
			if(currentSort==0){
			a0.style.display='none';
			a1.style.display='';
			}else{
			a0.style.display='';
			a1.style.display='none';
			}
			
			tj.startLoading('Loading...');
			//tj.updateReports(start,end,tj.reportsId,categoryId,locationId,currentSort);
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
			tj.reportsId=tj.urlParams['id'];
			if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
            tj.sortId=tj.urlParams['i'];
			}
			tj.daterangepickerInit(tj.reportsId,startPay,endPay,role,start,end,currentSort,tj.sortId);
			}else{
			tj.updateReports(start,end,tj.reportsId,categoryId,locationId,currentSort,safety,tj.sortId);
			}
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

tj.loadQRview = function() {
    $('#qrView').show();
    if (!tj.qrLoaded) {
		if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
                tj.qrViewId=tj.urlParams['i'];
            }
        jQuery('#qrView').load('views/QRview.php', {}, function () {
			tj.printqrcodes(tj.qrViewId);
			tj.qrLoaded = true;
            tj.stopLoading();
        });
	}else {
        if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
            tj.qrViewId=tj.urlParams['i'];
			tj.printqrcodes(tj.qrViewId);
        }
	}
};

tj.loadHuddleview = function() {
    $('#huddleView').show();
    if (!tj.huddleLoaded) {
		if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
                tj.huddleViewId=tj.urlParams['i'];
           }
        jQuery('#huddleView').load('views/huddleview.php', {}, function () {
			tj.printhuddle(tj.huddleViewId);
			tj.huddleLoaded = true;
            tj.stopLoading();
        });
	}else{
		if (tj.urlParams['i'] != undefined && tj.urlParams['i'].trim() != '') {
            tj.huddleViewId=tj.urlParams['i'];
			tj.printhuddle(tj.huddleViewId);
			tj.huddleLoaded = true;
	}
	}
};

/////////////////////////////////////

// LOAD UNITS PAGE
tj.loadUnits = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[16]);
    $('#unitsView').show();
	if (!tj.unitsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#unitsView').load('views/units.php', {}, function () {
			var a11 = document.getElementById('unitDetails');
			a11.style.display='none';
            tj.initializeUnitsGrid('');
            tj.unitsLoaded = true;
            tj.stopLoading();
        });
    }else{
			var a11 = document.getElementById('unitDetails');
			var a10 = document.getElementById('unitList');
			a11.style.display='none';
			a10.style.display='';
    }
};

/////////////////////////////////////

// LOAD Accounts PAGE
tj.loadAccounts = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[18]);
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

// LOAD Accounts PAGE
tj.loadConcierge = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[19]);
    $('#conciergeView').show();
	if (!tj.conciergeLoaded) {
        tj.startLoading('Loading...');
        jQuery('#conciergeView').load('views/concierge.php', {}, function () {
			tj.initializeConciergeGrid('');
            tj.conciergeLoaded = true;
            tj.stopLoading();
        });
    }
};

tj.loadTasks = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[17]);
    $('#tasksView').show();
	if (!tj.tasksLoaded) {
        tj.startLoading('Loading...');
        jQuery('#tasksView').load('views/tasks.php', {}, function () {
			tj.initializeTasksGrid('');
            tj.tasksLoaded = true;
            tj.stopLoading();
        });
    }
};


/////////////////////////////////////
// LOAD USERS PAGE
tj.loadUsers = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[15]);
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
			
				}else if(response.data.prodCount==1 && (response.data.prodMeasure==2 || response.data.prodMeasure==3)) {
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
tj.loadProd = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[3]);
    tj.prodId='';
	tj.prodStart='';
	tj.prodEnd='';
	tj.escId='';
	//var todayDate = moment().format('YYYY-MM-DD');
	$('#staffingView').show();
	if (!tj.prodLoaded) {
        tj.startLoading('Loading...');
        jQuery('#staffingView').load('views/staffing.php', {}, function () {
			var a1 = document.getElementById('staffing_daterangepicker');
			var a2 = document.getElementById('escButton');
			a1.style.display='';
			a2.style.display='';
				var acctEscalation = $('#acctEscalations').val();
				if(acctEscalation == 0){
				a2.style.display='none';	
				}else{
				a2.style.display='';	
				}
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.prodId=tj.urlParams['id'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStart=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			}
			if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEnd=tj.urlParams['e'];
			}
			if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escId=tj.urlParams['c'];
			}
			tj.todayDate = moment().format('YYYY-MM-DD');
			var vids = $('#vids').val();
			var startPay = $('#startStaff').val();
			var endPay = $('#endStaff').val();
			var role = $('#staffingRole').val();
			$('#pdfTitle').val('Staffing Reports');
			//$('#todayDate').val(todayDate);
			tj.prodLoaded = true;
			//tj.vids(vids);
			tj.initializeProdGrid('');
			tj.staffingdaterangepickerInit(startPay,endPay,role,tj.prodStart,tj.prodEnd,tj.escId);
			
			//tj.initializeCSVGrid('');
			//tj.loadCSV();
			tj.stopLoading();
        });
    }else{
		var a1 = document.getElementById('staffing_daterangepicker');
		var a2 = document.getElementById('escButton');
		a1.style.display='';
		a2.style.display='';
		var acctEscalation = $('#acctEscalations').val();
				if(acctEscalation == 0){
				a2.style.display='none';	
				}else{
				a2.style.display='';	
				}
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.prodId=tj.urlParams['id'];
			}
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStart=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			}
		if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEnd=tj.urlParams['e'];
			}
		if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escId=tj.urlParams['c'];
			}
		tj.todayDate = moment().format('YYYY-MM-DD');
		//$('#todayDate').val(todayDate);
		var startPay = $('#startStaff').val();
		var endPay = $('#endStaff').val();
		var role = $('#staffingRole').val();
		tj.prodTable.ajax.reload();
		
		//tj.staffingdaterangepickerInit(startPay,endPay,role,tj.prodStart,tj.prodEnd);
   }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadProdwhp = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[4]);
	tj.prodIdwhp='';
	tj.prodStartwhp='';
	tj.prodEndwhp='';
	tj.escIdwhp=''
    $('#staffingViewwhp').show();
	if (!tj.prodLoadedwhp) {
        tj.startLoading('Loading...');
        jQuery('#staffingViewwhp').load('views/supportstaffing.php', {}, function () {
			var a1 = document.getElementById('staffing_daterangepickerwhp');
			var a2 = document.getElementById('escButtonwhp');
			a1.style.display='';
			a2.style.display='';
			var acctEscalation = $('#acctEscalationswhp').val();
				if(acctEscalation == 0){
				a2.style.display='none';	
				}else{
				a2.style.display='';	
				}
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.prodIdwhp=tj.urlParams['id'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartwhp=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			console.log('prodStart',tj.prodStartwhp);
			}
			if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndwhp=tj.urlParams['e'];
			console.log('prodEnd',tj.prodEndwhp);
			}
			if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escIdwhp=tj.urlParams['c'];
			console.log('prodEnd',tj.prodEndwhp);
			}
			var startPay = $('#startSupport').val();
			var endPay = $('#endSupport').val();
			var role = $('#supportRole').val();
			//var newStart = $('#startDate').val();
			//var newEnd = $('#endDate').val();
            tj.initializeProdGridwhp('');
			tj.staffingdaterangepickerInitwhp(startPay,endPay,role,tj.prodStartwhp,tj.prodEndwhp,tj.escIdwhp);
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
		var acctEscalation = $('#acctEscalationswhp').val();
				if(acctEscalation == 0){
				a2.style.display='none';	
				}else{
				a2.style.display='';	
				}
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.prodIdwhp=tj.urlParams['id'];
			}
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartwhp=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			}
		if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndwhp=tj.urlParams['e'];
			}
		if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escIdwhp=tj.urlParams['c'];
			}
		var startPay = $('#startSupport').val();
		var endPay = $('#endSupport').val();
		var role = $('#supportRole').val();
		tj.staffingdaterangepickerInitwhp(startPay,endPay,role,tj.prodStartwhp,tj.escIdwhp);
            //tj.prodTablewhp.ajax.reload();
   }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadClinics = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[5]);
	tj.prodIdcli='';
	tj.prodStartcli='';
	tj.prodEndcli='';
	tj.escIdcli=''
    $('#opsView').show();
	if (!tj.clinicsLoaded) {
        tj.startLoading('Loading...');
        jQuery('#opsView').load('views/ops.php', {}, function () {
			var a1 = document.getElementById('staffing_daterangepickercli');
			var a2 = document.getElementById('escButtoncli');
			a1.style.display='';
			a2.style.display='';
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.prodIdcli=tj.urlParams['id'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartcli=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			console.log('prodStart',tj.prodStartwhp);
			}
			if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndcli=tj.urlParams['e'];
			console.log('prodEnd',tj.prodEndwhp);
			}
			if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escIdcli=tj.urlParams['c'];
			console.log('prodEnd',tj.prodEndwhp);
			}
			var startPay = $('#startSupportcli').val();
			var endPay = $('#endSupportcli').val();
			var role = $('#supportRolecli').val();
			//var newStart = $('#startDate').val();
			//var newEnd = $('#endDate').val();
            tj.initializeProdGridcli('');
			tj.staffingdaterangepickerInitcli(startPay,endPay,role,tj.prodStartcli,tj.prodEndcli,tj.escIdcli);
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.prodLoadedcli = true;
            tj.stopLoading();
        });
    }else{
		var a1 = document.getElementById('staffing_daterangepickercli');
		var a2 = document.getElementById('escButtoncli');
		a1.style.display='';
		a2.style.display='';
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.prodIdcli=tj.urlParams['id'];
			}
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.prodStartcli=tj.urlParams['s'];
			//a1.style.display='none';
			a2.style.display='none';
			}
		if (tj.urlParams['e'] != undefined && tj.urlParams['e'].trim() != '') {
            tj.prodEndcli=tj.urlParams['e'];
			}
		if (tj.urlParams['c'] != undefined && tj.urlParams['c'].trim() != '') {
            tj.escIdcli=tj.urlParams['c'];
			}
		var startPay = $('#startSupportcli').val();
		var endPay = $('#endSupportcli').val();
		var role = $('#supportRolecli').val();
		tj.staffingdaterangepickerInitcli(startPay,endPay,role,tj.prodStartcli,tj.escIdcli);
            //tj.prodTablewhp.ajax.reload();
   }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadCompliance = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[9]);
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
tj.loadCustom = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[10]);
	tj.customId='';
    $('#customView').show();
	//tj.complianceLoaded = false;
	if (!tj.customLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#customView').load('views/customReports.php', {}, function () {
            if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.customId=tj.urlParams['id'];
            }
			var startPay = $('#startCustom').val();
			var endPay = $('#endCustom').val();
			tj.initializePdfGrid('');
			tj.customdaterangepickerInit(startPay,endPay);
			tj.customLoaded = true;
			tj.initializeEmailGrid('0');
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadCharts = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[10]);
    $('#chartsView').show();
	//tj.complianceLoaded = false;
	if (!tj.chartsLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#chartsView').load('views/charts.php', {}, function () {
			//tj.initializechartsGrid('');
			tj.chart2();
			tj.chartsdaterangepickerInit();
			tj.chartsLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD DAY RANK
tj.loadDayRank = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[12]);
	tj.dayRankId='';
    $('#dayRankView').show();
	//tj.complianceLoaded = false;
	if (!tj.dayRankLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#dayRankView').load('views/dayrank.php', {}, function () {
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.dayRankId=tj.urlParams['id'];
            }
            //var startDay = $('#estartDay').val();
			//var endDay = $('#eendDay').val();
			$('#dayRankTable').DataTable().destroy();
			tj.initializedayRankGrid('');
			tj.dayRankdaterangepickerInit('');
			tj.dayRankLoaded = true;
            tj.stopLoading();
        });
    }else {
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.dayRankId=tj.urlParams['id'];
			tj.dayRankLoaded = false;
            tj.dayRankTable.ajax.reload();
        }
	}
};

/////////////////////////////////////
// LOAD Crash Carts PAGE
tj.loadCrash = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	tj.complianceId='';
    $('#logsView').show();
	//tj.complianceLoaded = false;
	if (!tj.crashLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#logsView').load('views/crashcart.php', {}, function () {
            //if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            //    tj.complianceId=tj.urlParams['id'];
            //}
			
			//$('#closeQR').style.display='none';
			var role = $('#crashRole').val();
			tj.initializeCrashGrid('');
			var startPay = moment().format('YYYY-MM-DD');
			var endPay = moment().format('YYYY-MM-DD');
			tj.tasksdaterangepickerInit(startPay,endPay,role);
			tj.crashLoaded = true;
            tj.stopLoading();
        });
    }
};
/////////////////////////////////////
// LOAD Safety Huddle PAGE
tj.loadSafety = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	//tj.complianceId='';
    $('#safetyView').show();
	//tj.complianceLoaded = false;
	if (!tj.safetyLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#safetyView').load('views/safety.php', {}, function () {
          
			tj.initializeSafetyGrid('');
			var startPay = moment().format('YYYY-MM-DD');
			var endPay = moment().format('YYYY-MM-DD');
			tj.safetydaterangepickerInit(startPay,endPay);
			tj.safetyLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD Safety Huddle Messages
tj.loadMessages = function() {
    $('#msgView').show();
	if (!tj.msgLoaded) {
        jQuery('#msgView').load('views/messaging.php', {}, function () {
          	tj.initializemsgGrid();
			tj.msgLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD Crash Carts PAGE
tj.loadQRcodes = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	//tj.complianceId='';
    $('#qrcodesView').show();
	//tj.complianceLoaded = false;
	if (!tj.qrcodesLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#qrcodesView').load('views/qrcodes.php', {}, function () {
			document.getElementById('gocloseQR').style.display='none';
			tj.initializeqrcodesGrid('');
			tj.qrcodesLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD Crash Carts PAGE
tj.loadQBL = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	tj.qblId='';
    $('#qblView').show();
	//tj.complianceLoaded = false;
	if (!tj.qblLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#qblView').load('views/qblLog.php', {}, function () {
            
			tj.initializeQBLGrid('');
			var startPay = moment().format('YYYY-MM-DD');
			var endPay = moment().format('YYYY-MM-DD');
			tj.qbldaterangepickerInit(startPay,endPay);
			tj.qblLoaded = true;
            tj.stopLoading();
        });
    }
};
tj.loadqblNow = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	tj.nowId='';
    $('#nowView').show();
	if (!tj.nowLoaded) {
        jQuery('#nowView').load('views/qbl.php', {}, function () {
			if (tj.urlParams['m'] != undefined && tj.urlParams['m'].trim() != '') {
                var qblId=tj.urlParams['m'];
            }
			if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
                var roomId=tj.urlParams['s'];
            }
			tj.nowLoaded = true;
            	
			//tj.startQBL(qblId);	
			tj.startQBL2(qblId);				
            tj.stopLoading();
        });
    }
};

tj.loadablNow = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[14]);
	//tj.nowId='';
    $('#ablView').show();
	if (!tj.ablLoaded) {
        jQuery('#ablView').load('views/abl.php', {}, function () {
			if (tj.urlParams['m'] != undefined && tj.urlParams['m'].trim() != '') {
                var ablId=tj.urlParams['m'];
            }
			tj.ablLoaded = true;
			tj.startABL2(ablId);				
            tj.stopLoading();
        });
    }
};


/////////////////////////////////////
// LOAD USERS PAGE
tj.loadCSV = function() {
	//tj.asideMenu.setActiveItem($('.m-menu__item')[11]);
    $('#csvView').show();
	if (!tj.csvLoaded) {
        jQuery('#csvView').load('views/csv_map.php', {}, function () {
            tj.initializeCSVGrid('');
			tj.csvLoaded = true;
            tj.stopLoading();
        });
    }
};

/////////////////////////////////////
// LOAD USERS PAGE
tj.loadEscalations = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[8]);
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

tj.loadPolicies = function() {
    //tj.asideMenu.setActiveItem($('.m-menu__item')[8]);
	tj.policiesId='';
    $('#policiesView').show();
	tj.policiesLoaded = false;
	if (!tj.policiesLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#policiesView').load('views/policies.php', {}, function () {
            if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.policiesId=tj.urlParams['id'];
            }
			tj.initializePoliciesGrid('');
			//tj.escalationsdaterangepickerInit();
			//tj.initializeUserAssignedGrid('');
            // tj.initializeJobsGrid('');
            // tj.initializeUsersGrid('');
            tj.policiesLoaded = true;
            tj.stopLoading();
        });
    }else {
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.policiesId=tj.urlParams['id'];
            tj.policiesTable.ajax.reload();
        }
	}
};

/////////////////////////////////////
// LOAD PERFORMANCE PAGE
tj.loadPerformance = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[7]);
	tj.performanceId='';
	tj.performanceDept='';
	$('#performanceView').show();
	//tj.performanceLoaded = false;
	if (!tj.performanceLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#performanceView').load('views/performance.php', {}, function () {
			//if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            //    tj.performanceId=tj.urlParams['id'];
            //}
			//if (tj.urlParams['d'] != undefined && tj.urlParams['d'].trim() != '') {
            //    tj.performanceDept=tj.urlParams['d'];
            //}
			tj.performanceId='';
			tj.performanceDept='';
			tj.performanceShift='';			
            tj.initializePerformanceGrid('');
			var startPay = $('#startPerf').val();
			var endPay = $('#endPerf').val();
			//console.log('startPerf',startPay);
			//console.log('endPerf',endPay);
			tj.performancedaterangepickerInit(startPay,endPay);
			tj.performanceLoaded = true;
        });
    }else{
        if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
            tj.performanceId=tj.urlParams['id'];
		if (tj.urlParams['d'] != undefined && tj.urlParams['d'].trim() != '') {
            tj.performanceDept=tj.urlParams['d'];
            }
		if (tj.urlParams['s'] != undefined && tj.urlParams['s'].trim() != '') {
            tj.performanceShift=tj.urlParams['s'];
            }
            tj.performanceTable.ajax.reload();
        }
    }
};

/////////////////////////////////////
// LOAD VIEW LOG PAGE
tj.viewLog = function() {
    tj.asideMenu.setActiveItem($('.m-menu__item')[10]);
	tj.viewlogId='';
    $('#viewLog').show();
	if (!tj.viewlogLoaded) {
        //tj.startLoading('Loading...');
        jQuery('#viewLog').load('views/viewlog.php', {}, function () {
			if (tj.urlParams['id'] != undefined && tj.urlParams['id'].trim() != '') {
                tj.viewlogId=tj.urlParams['id'];
            }
            tj.initializeviewlogGrid('');
			tj.viewlogdaterangepickerInit();
			tj.viewlogLoaded = true;
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

tj.chart2 = function() {
		//var dow = $('#dow').val();
        var dow = 8;
		var deptId = 533;
		
		//if ($('#chart_2').size() != 1) {
         //           return;
          // }
				
				$.ajax({
					url:'inc/data.php?req=getchartData',
					data:{
						deptId:deptId,
						dow:dow
					},
					method:'POST',
					dataType:'json',
					success:function(response) {
				var days = response.days;
				var nights = response.nights;
			
                var plot = $.plot($("#chart_2"), [{
                    data: days,
                    label: "Days",
                    lines: {
                        lineWidth: 1,
                    },
                    shadowSize: 0

                }, {
                    data: nights,
                    label: "Nights",
                    lines: {
                        lineWidth: 1,
                    },
                    shadowSize: 0
                }], {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 2,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 0.05
                                }, {
                                    opacity: 0.01
                                }]
                            }
                        },
                        points: {
                            show: true,
                            radius: 3,
                            lineWidth: 1
                        },
                        shadowSize: 2
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: "#eee",
                        borderColor: "#eee",
                        borderWidth: 1
                    },
                    colors: ["#d12610", "#37b7f3", "#52e136"],
                    xaxis: {
                        ticks: 11,
                        tickDecimals: 0,
                        tickColor: "#eee",
                    },
                    yaxis: {
                        ticks: 11,
                        tickDecimals: 0,
                        tickColor: "#eee",
                    }
                });


                function showTooltip(x, y, contents) {
                    $('<div id="tooltip">' + contents + '</div>').css({
                        position: 'absolute',
                        display: 'none',
                        top: y + 5,
                        left: x + 15,
                        border: '1px solid #333',
                        padding: '4px',
                        color: '#fff',
                        'border-radius': '3px',
                        'background-color': '#333',
                        opacity: 0.80
                    }).appendTo("body").fadeIn(200);
                }

                var previousPoint = null;
                $("#chart_2").bind("plothover", function(event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));

                    if (item) {
                        if (previousPoint != item.dataIndex) {
                            previousPoint = item.dataIndex;

                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);

                            showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
			}
		})
}

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
		     console.log('beds blocked');
		   
       }
   })
}

	/////////////////////////////////////
// GET TRANSFERS
tj.portal = function() {
	//var accountId = $('#accountId').val();
	var portalsummary = $('#transfer1');
	var newbutton = $('#pbutton');
	//console.log('accountId',accountId);
   $.ajax({
       url:'inc/data.php?req=getportal',
       data: {
       },
       success:function(response) {
		   var html = '';
		   
			if(response.data.length !=0){
			var shift = response.data[0].flowShift;
			var newDate = new Date(response.data[0].flowdayDate +'T00:00');
			var dayDate = newDate.getTime();
			//console.log('date',newDate.toString());
			console.log('dayDate',dayDate);
			var portallength = response.data.length;
			var deplength = response.depts.length;
				html += '<div class="form-group">';
				html += '<div class="row">';
				html += '<div class="title col-12"><strong>The Anticipated Transfers below are based on the ' + response.data[0].shiftName + ' Shift Reports</strong></div>';
				html += '</div>';		
				html += '<hr></hr>';						
				for (var i = 0; i < portallength; i++) {
				html += '<div class="row" style="padding-top:20px">';
				html += '<div class="col-md mt-auto">';
				html += '<div>';
				html += '<select id="outDept'+i+'" type="select" style="padding-top:5px" class="form-control" disabled></select>';
				//html += '<div class="title"><strong>' + response.datai.outDept + '</strong>';
				html += '<input type="number" min="0" id="outflow'+i+'" value="' + response.data[i].outflowCount +'" class="form-control number">';
				html += '</div>';
				html += '</div>';
				html += '<div class="col-md mt-auto">';
				//html += '<button type="button" id="trbutton'+i+'" name="trbutton'+i+'" onclick="tj.confirmTransfer('+i+');" class="btn btn-success btn-md">Confirm -></button />';
				html += '<img src="/img/blueArrow.png"/>';
				html += '</div>'
				html += '<div class="col-md mt-auto">';
				//html += '<div class="title"><strong>' + response.data[i].inDept + '</strong>';
				html += '<div>';
				html += '<select id="inDept'+i+'" type="select" class="form-control" style="padding-top:5px" onchange="tj.changeTransfer('+i+');" /></select>';
				html += '<input type="number" min="0" id="inflow'+i+'" value="' + response.data[i].flowCount +'" class="form-control number">';
				html += '</div>';
				html += '</div>';
				html += '<div class="col-md mt-auto">';
				html += '<div class="title"><strong>RN Variance</strong>';
				html += '<input type="text" id="rnvar'+i+'" value="' + response.data[i].rnvar +'" class="form-control" disabled>';
				html += '</div>';
				html += '</div>';
				html += '<div class="col-md mt-auto">';
				html += '<div class="title"><strong>Beds</strong>';
				html += '<input type="text" id="beds'+i+'" value="' + response.data[i].beds +'" class="form-control" disabled>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
				
				html += '<div class="row" style="padding-top:10px">';
				html += '<div class="col-md">';
				html += '<div class="title"><strong>Unit Note</strong>';
				html += '<textarea class="form-control" id="unitnote'+i+'" maxlength="250" rows="1" disabled>' + response.data[i].Note + '</textarea>';
				html += '</div>';
				html += '<div class="title"><strong>Transfer Note</strong>';
				html += '<textarea class="form-control" id="note'+i+'" maxlength="250" rows="1">' + response.data[i].note + '</textarea>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
				html += '<hr style="height:10px;color:DodgerBlue"></hr>';
				html += '<input id="flowId'+i+'" value="'+response.data[i].id+'" type="text" hidden>';
				html += '<input id="useFlow'+i+'" value="'+response.data[i].useFlow+'" type="text"hidden>';
				html += '<input id="outdeptId'+i+'" value="'+response.data[i].outdeptId+'" type="text" hidden>';
				html += '<input id="indeptId'+i+'" value="'+response.data[i].flowdeptId+'" type="text" hidden>';
				html += '<input id="flowrecordId'+i+'" value="'+response.data[i].flowrecordId+'" type="text" hidden>';
				html += '<input id="flowShift'+i+'" value="'+response.data[i].flowShift+'" type="text" hidden>';
				html += '<input id="flowdayDate'+i+'" value="'+response.data[i].flowdayDate+'" type="text" hidden>';
				html += '<input id="newRecord'+i+'" value="0" type="text" hidden>';
				}
				html += '<span id="addPortal"></span>';
				html += '<input id="lastRecord" value="'+i+'" type="text" hidden>';
				
				html += '</div>';
				
				
			}else{
			var shift = 0;
			var dayDate = 0;
			}
			portalsummary.empty().append(html);
						
			for (var p = 0; p < portallength; p++) {
				var depSelect = $("#indeptId"+[p]+"").val();
				var outSelect = $("#outdeptId"+[p]+"").val();
				//console.log('depSelect',depSelect);
				$("#inDept"+[p]+"").append($('<option>', {value: 0,text: 'Cancel'}));
				$("#outDept"+[p]+"").append($('<option>', {value: 1,text: 'External'}));
				for (var z = 0; z < deplength; z++) {
				$("#inDept"+[p]+"").append($('<option>', {value: response.depts[z].id,text: response.depts[z].dept}));
				$("#outDept"+[p]+"").append($('<option>', {value: response.depts[z].id,text: response.depts[z].dept}));
				}
				$("#inDept"+[p]+"").val(depSelect);
				$("#outDept"+[p]+"").val(outSelect);
			}
			
			var pbutton = '';
			pbutton += '<button type="button" id="addtrbutton" name="addtrbutton" onclick="tj.addTransfer('+shift+','+dayDate+');" class="btn btn-success btn-sm pull-left">New Transfer</button>';
			newbutton.empty().append(pbutton);
			
		    $('#transferportal').modal('show');
       }
   })
   
}

tj.changeTransfer = function(id) {
	var deptId = $('#inDept'+id+'').val();
	var flowId = $('#flowId'+id+'').val();
	var shift = $('#flowShift0').val();
	var dayDate = $('#flowdayDate0').val();
	
	//console.log('deptId',deptId);
	//console.log('flowId',flowId);
	//console.log('shift',shift);
	//console.log('dayDate',dayDate);
	//console.log('i=',id);
	
	
	$.ajax({
       url:'inc/data.php?req=changetransfer',
       data: {
			deptId: deptId,
			flowId: flowId,
			shift: shift,
			dayDate: dayDate
       },
       success:function(response) {
		   $('#rnvar'+id+'').val(response.data.nvariance);
		   $('#beds'+id+'').val(response.data.beds); 
		   $('#unitnote'+id+'').val(response.data.note); 
       }
   })
	
}


tj.updatetransfer = function(direction,id) {
	var dir = direction;
	var inflow = $('#inflow'+id+'').val();
	var outflow = $('#outflow'+id+'').val();
		
	if(dir==1){
		$('#inflow'+id+'').val(outflow);
	}
	if(dir==2){
		$('#outflow'+id+'').val(inflow);
	}
	
}

tj.addTransfer = function(shift,dayDate) {
	
	if(parseInt($('#lastRecord').val())>=0){
	var i = parseInt($('#lastRecord').val());
	}else{
	var i=0;	
	}
	//console.log('shift: ',shift);
	//console.log('dayDate: ',dayDate);
	var portalsummary = $('#transfer1');
	var html = '';
		$.ajax({
       url:'inc/data.php?req=getportaldepts',
       data: {
       },
       success:function(response) {
		var deplength = response.depts.length;
		html += '<div class="row" style="padding-top:20px">';
		html += '<div class="col-md mt-auto">';
		//html += '<div class="title"><strong>' + response.data[i].outDept + '</strong>';
		html += '<div>';
		html += '<select id="outDept'+i+'" type="select" class="form-control" /></select>';
		html += '<input type="number" min="0" id="outflow'+i+'" value="1" class="form-control number">';
		html += '</div>';
		html += '</div>';
		html += '<div class="col-md mt-auto">';
		html += '<img src="/img/blueArrow.png"/>';
		//html += '<button type="button" id="trbutton'+i+'" name="trbutton'+i+'" onclick="tj.confirmTransfer('+i+');" class="btn btn-success btn-md">Confirm -></button />';
		html += '</div>'
		html += '<div class="col-md mt-auto">';
		//html += '<div class="title"><strong>' + response.data[i].inDept + '</strong>';
		html += '<div>';
		html += '<select id="inDept'+i+'" type="select" class="form-control" onchange="tj.changeTransfer('+i+');" /></select>';
		html += '<input type="number" min="0" id="inflow'+i+'" value="1" class="form-control number">';
		html += '</div>';
		html += '</div>';
		html += '<div class="col-md mt-auto">';
		html += '<div class="title"><strong>RN Variance</strong>';
		html += '<input type="text" id="rnvar'+i+'" value="" class="form-control" disabled>';
		html += '</div>';
		html += '</div>';
		html += '<div class="col-md mt-auto">';
		html += '<div class="title"><strong>Beds</strong>';
		html += '<input type="text" id="beds'+i+'" value="" class="form-control" disabled>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '<div class="row" style="padding-top:10px">';
		html += '<div class="col-md">';
		html += '<div class="title"><strong>Unit Note</strong>';
		html += '<textarea class="form-control" id="unitnote'+i+'" maxlength="250" rows="1" disabled></textarea>';
		html += '</div>';
		html += '<div class="title"><strong>Transfer Note</strong>';
		html += '<textarea class="form-control" id="note'+i+'" maxlength="250" rows="1"></textarea>';
		html += '<input id="flowId'+i+'" value="0" type="text" hidden>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '<hr style="height:10px;color:blue"></hr>';
		html += '<input id="flowId'+i+'" value="0" type="text" hidden>';
		html += '<input id="flowShift'+i+'" value="'+shift+'" type="text" hidden>';
		html += '<input id="flowdayDate'+i+'" value="'+dayDate+'" type="text" hidden>';
		html += '<input id="newRecord'+i+'" value="1" type="text" hidden>';
		portalsummary.append(html);
		$("#inDept"+i+"").append($('<option>', {value: -1,text: 'To'}));
		$("#outDept"+i+"").append($('<option>', {value: -1,text: 'From'}));
		$("#inDept"+i+"").append($('<option>', {value: 0,text: 'Cancel'}));
		$("#outDept"+i+"").append($('<option>', {value: 1,text: 'External'}));
		
		for (var p = 0; p < deplength; p++) {
		$("#inDept"+i+"").append($('<option>', {value: response.depts[p].id,text: response.depts[p].dept}));
		$("#outDept"+i+"").append($('<option>', {value: response.depts[p].id,text: response.depts[p].dept}));
		}
		i=i+1;
		$('#lastRecord').val(i)
		}
   })
	
}

tj.confirmTransfer = function(id) {
	var outflow = $('#outflow'+id+'').val();
	$('#inflow'+id+'').val(outflow);
	
}


tj.updatePortal = function() {
	var shift = $('#flowShift0').val();
	var dayDate = $('#flowdayDate0').val();
	var flowid0 = $('#flowId0').val();
	var flowid1 = $('#flowId1').val();
	var flowid2 = $('#flowId2').val();
	var flowid3 = $('#flowId3').val();
	var flowid4 = $('#flowId4').val();
	var flowid5 = $('#flowId5').val();
	var flowid6 = $('#flowId6').val();
	var flowid7 = $('#flowId7').val();
	var Note0 = $('#note0').val();
	var Note1 = $('#note1').val();
	var Note2 = $('#note2').val();
	var Note3 = $('#note3').val();
	var Note4 = $('#note4').val();
	var Note5 = $('#note5').val();
	var Note6 = $('#note6').val();
	var Note7 = $('#note7').val();
	var useFlow0 = $('#useFlow0').val();
	var useFlow1 = $('#useFlow1').val();
	var useFlow2 = $('#useFlow2').val();
	var useFlow3 = $('#useFlow3').val();
	var useFlow4 = $('#useFlow4').val();
	var useFlow5 = $('#useFlow5').val();
	var useFlow6 = $('#useFlow6').val();
	var useFlow7 = $('#useFlow7').val();
	var indeptId0 = $('#inDept0').val();
	var indeptId1 = $('#inDept1').val();
	var indeptId2 = $('#inDept2').val();
	var indeptId3 = $('#inDept3').val();
	var indeptId4 = $('#inDept4').val();
	var indeptId5 = $('#inDept5').val();
	var indeptId6 = $('#inDept6').val();
	var indeptId7 = $('#inDept7').val();
	var flowrecordId0 = $('#flowrecordId0').val();
	var flowrecordId1 = $('#flowrecordId1').val();
	var flowrecordId2 = $('#flowrecordId2').val();
	var flowrecordId3 = $('#flowrecordId3').val();
	var flowrecordId4 = $('#flowrecordId4').val();
	var flowrecordId5 = $('#flowrecordId5').val();
	var flowrecordId6 = $('#flowrecordId6').val();
	var flowrecordId7 = $('#flowrecordId7').val();
	var newRecord0 = $('#newRecord0').val();
	var newRecord1 = $('#newRecord1').val();
	var newRecord2 = $('#newRecord2').val();
	var newRecord3 = $('#newRecord3').val();
	var newRecord4 = $('#newRecord4').val();
	var newRecord5 = $('#newRecord5').val();
	var newRecord6 = $('#newRecord6').val();
	var newRecord7 = $('#newRecord7').val();
	var outdeptId0 = $('#outDept0').val();
	var outdeptId1 = $('#outDept1').val();
	var outdeptId2 = $('#outDept2').val();
	var outdeptId3 = $('#outDept3').val();
	var outdeptId4 = $('#outDept4').val();
	var outdeptId5 = $('#outDept5').val();
	var outdeptId6 = $('#outDept6').val();
	var outdeptId7 = $('#outDept7').val();
	
	if(indeptId0== -1 || outdeptId0== -1 || indeptId1== -1 || outdeptId1== -1 || indeptId2== -1 || outdeptId2== -1 || indeptId3== -1 || outdeptId3== -1 || indeptId4== -1 || outdeptId4== -1 || indeptId5== -1 || outdeptId5== -1 || indeptId6== -1 || outdeptId6== -1 || indeptId7== -1 || outdeptId7== -1){
	bootbox.alert('You must select a From and To location for each transfer.');
	return;
	}
	
	
	
	//if(flowid0!=undefined){
	//var flow0 = $('#flowId0').val();
	var outCount0 = $('#outflow0').val();
	var inCount0 = $('#inflow0').val();
	//}
	//if(flowid1!=undefined){
	//var flow1 = $('#flowId1').val();
	var outCount1 = $('#outflow1').val();
	var inCount1 = $('#inflow1').val();
	//}
	//if(flowid2!=undefined){
	//var flow2 = $('#flowId2').val();
	var outCount2 = $('#outflow2').val();
	var inCount2 = $('#inflow2').val();
	//}
	//if(flowid3!=undefined){
	//var flow3 = $('#flowId3').val();
	var outCount3 = $('#outflow3').val();
	var inCount3 = $('#inflow3').val();
	//}
	//if(flowid4!=undefined){
	//var flow4 = $('#flowId4').val();
	var outCount4 = $('#outflow4').val();
	var inCount4 = $('#inflow4').val();
	//}
	//if(flowid5!=undefined){
	//var flow5 = $('#flowId5').val();
	var outCount5 = $('#outflow5').val();
	var inCount5 = $('#inflow5').val();
	//}
	//if(flowid6!=undefined){
	//var flow6 = $('#flowId6').val();
	var outCount6 = $('#outflow6').val();
	var inCount6 = $('#inflow6').val();
	//}
	//if(flowid7!=undefined){
	//var flow7 = $('#flowId7').val();
	var outCount7 = $('#outflow7').val();
	var inCount7 = $('#inflow7').val();
	//}
	  $.ajax({
       url:'inc/data.php?req=updateportal',
       data: {
           flowid0: flowid0,
		   outCount0: outCount0,
		   inCount0: inCount0,
		   flowid1: flowid1,
		   outCount1: outCount1,
		   inCount1: inCount1,
		   flowid2: flowid2,
		   outCount2: outCount2,
		   inCount2: inCount2,
		   flowid3: flowid3,
		   outCount3: outCount3,
		   inCount3: inCount3,
		   flowid4: flowid4,
		   outCount4: outCount4,
		   inCount4: inCount4,
		   flowid5: flowid5,
		   outCount5: outCount5,
		   inCount5: inCount5,
		   flowid6: flowid6,
		   outCount6: outCount6,
		   inCount6: inCount6,
		   flowid7: flowid7,
		   outCount7: outCount7,
		   inCount7: inCount7,
		   note0: Note0,
		   note1: Note1,
		   note2: Note2,
		   note3: Note3,
		   note4: Note4,
		   note5: Note5,
		   note6: Note6,
		   note7: Note7,
		   useFlow0: useFlow0,
		   useFlow1: useFlow1,
		   useFlow2: useFlow2,
		   useFlow3: useFlow3,
		   useFlow4: useFlow4,
		   useFlow5: useFlow5,
		   useFlow6: useFlow6,
		   useFlow7: useFlow7,
		   indeptId0: indeptId0,
		   indeptId1: indeptId1,
		   indeptId2: indeptId2,
		   indeptId3: indeptId3,
		   indeptId4: indeptId4,
		   indeptId5: indeptId5,
		   indeptId6: indeptId6,
		   indeptId7: indeptId7,
		   flowrecordId0: flowrecordId0,
		   flowrecordId1: flowrecordId1,
		   flowrecordId2: flowrecordId2,
		   flowrecordId3: flowrecordId3,
		   flowrecordId4: flowrecordId4,
		   flowrecordId5: flowrecordId5,
		   flowrecordId6: flowrecordId6,
		   flowrecordId7: flowrecordId7,
		   newRecord0: newRecord0,
		   newRecord1: newRecord1,
		   newRecord2: newRecord2,
		   newRecord3: newRecord3,
		   newRecord4: newRecord4,
		   newRecord5: newRecord5,
		   newRecord6: newRecord6,
		   newRecord7: newRecord7,
		   outdeptId0: outdeptId0,
		   outdeptId1: outdeptId1,
		   outdeptId2: outdeptId2,
		   outdeptId3: outdeptId3,
		   outdeptId4: outdeptId4,
		   outdeptId5: outdeptId5,
		   outdeptId6: outdeptId6,
		   outdeptId7: outdeptId7,
		   shift: shift,
		   dayDate: dayDate	
       },
       success:function(response) {
		   $('#transferportal').modal('hide');
		  bootbox.alert('Transfers Updated.');
       }
   })
	
}

tj.dayData = function(dataId,c) {
   var start = $("#startDate").val();
   var end = $("#endDate").val();
   console.log('dataId',dataId);
   console.log('c ',c);
   document.getElementById("data1").style.display='none';
   document.getElementById("data2").style.display='none';
   document.getElementById("data3").style.display='none';
   document.getElementById("data4").style.display='none';
   $('#daydata1').val('0');
   $('#daydata2').val('0');
   $('#daydata3').val('0');
   $('#daydata4').val('0');
   
   //document.getElementById("daySave").disabled = true;
   $.ajax({
       url:'inc/data.php?req=getdayData',
       data: {
           dataId: dataId,
		   start: start,
		   end: end
       },
       success:function(response) {
		 $('#daydataDept').html(response.data.deptName);
		 
		if(parseInt(response.data.roleId)>9 || response.data.logId==response.data.userId){
		$('#dayButton').html('<button type="button" class="btn btn-success" id="daySave" onclick="tj.savedayData(' + c +');" >Save</button>');		
		}else{
		$('#dayButton').html('<button type="button" class="btn btn-success" id="daySave" onclick="tj.savedayData(' + c +');" disabled>Save</button>');	
		}
		
		if(response.data.track1Desc.length>0){
		document.getElementById('data1').style.display='';
		$('#daydataName1').html('<h5>' + response.data.track1Desc + '</h5>');	
		$('#daydata1').val(response.data.dayCount1);	
		}
		
		if(response.data.track2Desc.length>0){
		document.getElementById('data2').style.display='';
		$('#daydataName2').html('<h5>' + response.data.track2Desc + '</h5>');	
		$('#daydata2').val(response.data.dayCount2);	
		}
		
		if(response.data.track3Desc.length>0){
		document.getElementById('data3').style.display='';
		$('#daydataName3').html('<h5>' + response.data.track3Desc + '</h5>');	
		$('#daydata3').val(response.data.dayCount3);	
		}
		
		if(response.data.track4Desc.length>0){
		document.getElementById('data4').style.display='';
		$('#daydataName4').html('<h5>' + response.data.track4Desc + '</h5>');	
		$('#daydata4').val(response.data.dayCount4);	
		}
		$('#daydataId').val(response.data.id);
		$('#daydesc1').val(response.data.dayDataTitle);
		$('#daydesc2').val(response.data.dayDataTitle);
		$('#daydesc3').val(response.data.dayDataTitle);
		$('#daydesc4').val(response.data.dayDataTitle);
		if(response.data.upTime=='00/00 00:00'){
		$('#daydataDate').html('');
		}else{
		$('#daydataDate').html(response.data.upTime + '<div>By: ' + response.data.firstname + ' ' + response.data.lastname + '<div>' );
		}
		$('#dayData').modal('show');
       }
   })
}

tj.savedayData = function(c) {
	var dataId = $("#daydataId").val();
   var data1 = $("#daydata1").val();
   var data2 = $("#daydata2").val();
   var data3 = $("#daydata3").val();
   var data4 = $("#daydata4").val();
   var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
   var newTime = moment().format('MM/DD HH:mm');
	var atotal = parseInt(data1) + parseInt(data2) + parseInt(data3) + parseInt(data4);
	var title = $('#daydesc' + c + '').val();
   console.log('title',title);
   console.log('total',atotal);
   $.ajax({
       url:'inc/data.php?req=savedayData',
       data: {
		   dataId: dataId,
           data1: data1,
		   data2: data2,
		   data3: data3,
		   data4: data4,
		   currentTime: currentTime
		   
       },
       success:function(response) {
		$('#dn' + c + '').html(atotal);
		$('#dd' + c + '').html('<a href="javascript:;" style="color:white" onclick="tj.dayData('+ dataId +','+ c +')" ><u><h5>' + title + ' (' + newTime + ')</h5></u></a>');
		$('#dayData').modal('toggle');
       }
   })
}

	/////////////////////////////////////
// GET BLOCKED BEDS
tj.staffSummary = function(dataId,prodMeasure) {
   //var deptId = $('#staffdataId').val();
   var staffsummary = $('#summaryStaff');
   //console.log('dataId',dataId);
   //console.log('prodMeasure',prodMeasure);
   $.ajax({
       url:'inc/data.php?req=getstaff',
       data: {
           dataId: dataId,
		   prodMeasure: prodMeasure
       },
       success:function(response) {
		$('#staffName').html(response.data.dept);
		var html = '';
		
		var cnvar='';
		var rnvar='';
		var pctvar='';
		var secvar='';
		var rn1var='';
		var rn2var='';
		var other1var='';
		var other2var='';
		var other3var='';
		var sitvar='';	
		
		var staff1 = parseInt(response.data.staff1);
		var staff2 = parseInt(response.data.staff2);
		var staff3 = parseInt(response.data.staff3);
		var staff4 = parseInt(response.data.staff4);
		var staff5 = parseInt(response.data.staff5);
		var staff6 = parseInt(response.data.staff6);
		var staff7 = parseInt(response.data.staff7);
		var staff8 = parseInt(response.data.staff8);
		var staff9 = parseInt(response.data.staff9);
		var staff10 = parseInt(response.data.staff10);
		
		
		if(response.data.gridOpt1==0){
		var other2 = '';
		var gother2 = '';
		}else{
		var other2 = parseInt(response.data.other2var) * parseInt(response.data.gridOpt1);
		var gother2 = parseInt(response.data.gother2var) * parseInt(response.data.gridOpt1);		
		}
		
		if(response.data.gridOpt2==0){
		var other3 = '';
		var gother3 = '';
		}else{
		var other3 = parseInt(response.data.other3var) * parseInt(response.data.gridOpt2);
		var gother3 = parseInt(response.data.gother3var) * parseInt(response.data.gridOpt2);
		}
		
		if(response.data.gridOpt3==0){
		var sitter = '';
		var gsitter = '';
		}else{
		var sitter = parseInt(response.data.sitvar) * parseInt(response.data.gridOpt3);
		var gsitter = parseInt(response.data.gsitvar) * parseInt(response.data.gridOpt3);
		}
		
		if(parseInt(response.data.cnvar)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var cnvar = '<span class="text-danger">' + response.data.cnvar + '</span>';
		}else if(parseInt(response.data.gcnvar)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var cnvar = '<span class="text-danger">' + response.data.gcnvar + '</span>';
		}else{
			var cnvar = '';
		}
		
		if(parseInt(response.data.rnvar)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var rnvar = '<span class="text-danger">' + response.data.rnvar + '</span>';
		}else if(parseInt(response.data.grnvar)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var rnvar = '<span class="text-danger">' + response.data.grnvar + '</span>';
		}else{
			var rnvar = '';
		}

		if(parseInt(response.data.rn1var)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var rn1var = '<span class="text-danger">' + response.data.rn1var + '</span>';
		}else if(parseInt(response.data.grn1var)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var rn1var = '<span class="text-danger">' + response.data.grn1var + '</span>';
		}else{
			var rn1var = '';
		}		
		
		if(parseInt(response.data.rn2var)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var rn2var = '<span class="text-danger">' + response.data.rn2var + '</span>';
		}else if(parseInt(response.data.grn2var)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var rn2var = '<span class="text-danger">' + response.data.grn2var + '</span>';
		}else{
			var rn2var = '';
		}
		
		if(parseInt(response.data.pctvar)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var pctvar = '<span class="text-danger">' + response.data.pctvar + '</span>';
		}else if(parseInt(response.data.gpctvar)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var pctvar = '<span class="text-danger">' + response.data.gpctvar + '</span>';
		}else{
			var pctvar = '';
		}
		
		if(parseInt(response.data.secvar)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var secvar = '<span class="text-danger">' + response.data.secvar + '</span>';
		}else if(parseInt(response.data.gsecvar)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var secvar = '<span class="text-danger">' + response.data.gsecvar + '</span>';
		}else{
			var secvar = '';
		}
		
		if(parseInt(response.data.sitvar)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var sitvar = '<span class="text-danger">' + sitter + '</span>';
		}else if(parseInt(response.data.gsitvar)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var sitvar = '<span class="text-danger">' + gsitter + '</span>';
		}else{
			var sitvar = '';
		}
		
		if(parseFloat(response.data.other1var)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var other1var = '<span class="text-danger">' + parseFloat(response.data.other1var).toFixed(1) + '</span>';
		}else if(parseInt(response.data.gother1var)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var other1var = '<span class="text-danger">' + parseFloat(response.data.gother1var).toFixed(1)+ '</span>';
		}else{
			var other1var = '';
		}
		
		if(parseInt(response.data.other2var)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var other2var = '<span class="text-danger">' + other2 + '</span>';
		}else if(parseInt(response.data.gother2var)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var other2var = '<span class="text-danger">' + gother2 + '</span>';
		}else{
			var other2var = '';
		}
		
		if(parseInt(response.data.other3var)!=0 && response.data.useGrid !=0 && response.data.useGrid!=2 && response.data.useGrid!=3 && response.data.useGrid!=7 && response.data.useGrid!=5){
			var other3var = '<span class="text-danger">' + other3 + '</span>';
		}else if(parseInt(response.data.gother3var)!=0 && (response.data.useGrid==2 || response.data.useGrid==3)){
			var other3var = '<span class="text-danger">' + gother3 + '</span>';
		}else{
			var other3var = '';
		}
		
			
		if(response.data.prodMeasure !=2 && response.data.prodMeasure !=3){
		html += '<div class="row">';
		html += '<h5><strong>NOTE:  </strong></h5></div>';
		html += '<div class="textarea"><strong>' +(response.data.note) + '</strong></div>';
		html += '<hr></hr>';
		
		html += '<div class="row">';
		html += '<h5><strong>STAFF </strong></h5></div>';
		html += '<hr></hr>';
		html += '<div class="row">';
		html += '<div class="col-9">';
		html += '<h5>Charge:</h5></div>';
		html += '<div class="col-3">';
		html += '<h5>' + (response.data.chargeCount * staff1) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + cnvar + '</h5></div>';
		html += '</div>';
		html += '<hr></hr>';
			
		console.log('chargecount', response.data.chargeCount);
		console.log('staff1', response.data.staff1);
		
		if(response.data.nurse1Desc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.nurse1Desc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.customNurse * staff3) +  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + rn1var + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.nurseDesc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.nurseDesc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.anteCount * staff2) +  '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + rnvar + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.nurse2Desc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.nurse2Desc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.customNurse2 * staff4) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + rn2var + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.techLabel.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.techLabel) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.techcount * staff5) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + pctvar + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.secLabel.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.secLabel) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.seccount * staff6) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + secvar + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.sittersNEWDesc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.sittersNEWDesc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.sittercount * staff7) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + sitvar + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		console.log('sitter ',response.data.sittercount);
		console.log('tech ',response.data.techcount);
		console.log('secc ',response.data.seccount);
		console.log('nurse1 ',response.data.otherNurse1);
		console.log('nurse2 ',response.data.otherNurse2);
		console.log('nurse3 ',response.data.otherNurse3);
		if(response.data.other1Desc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.other1Desc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.otherNurse1) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + other1var + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.other2Desc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.other2Desc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.otherNurse2 * staff9) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + other2var + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.other3Desc.length >0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.other3Desc) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.otherNurse3 * staff10) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + other3var + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
			
		
		}else{
		html += '<div class="row">';
		html += '<h5><strong>Note:  </strong></h5></div>';
		html += '<div class="textarea"><strong>' +(response.data.note) + '</strong></div>';
		html += '<hr></hr>';
		
		html += '<div class="row">';
		html += '<h5><strong>STAFF  </strong></h5></div>';
		html += '<hr></hr>';
		if(response.data.skill1 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc1) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill1val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill2 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc2) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill2val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill3 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc3) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill3val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill4 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc4) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill4val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill5 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc5) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill5val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill6 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc6) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill6val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill7 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc7) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill7val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill8 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc8) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill8val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill9 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc9) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill9val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.skill10 ==1){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + (response.data.skilldesc10) + ':</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + (response.data.skill10val) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(parseInt(response.data.addResourceHrs)!=0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>Indirect Hours:</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + parseFloat(response.data.addResourceHrs).toFixed(2) + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		}
		if(parseInt(response.data.trackdata)==1){
			html += '<div class="row">';
			html += '<h5><strong>ADDITIONAL DATA</strong></h5></div>';
			html += '<hr></hr>';
		}
		if(response.data.track1Desc.length>0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + response.data.track1Desc + '</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + response.data.track1 + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.track2Desc.length>0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + response.data.track2Desc + '</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + response.data.track2 + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.track3Desc.length>0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + response.data.track3Desc + '</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + response.data.track3 + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		if(response.data.track4Desc.length>0){
			html += '<div class="row">';
			html += '<div class="col-9">';
			html += '<h5>' + response.data.track4Desc + '</h5></div>';
			html += '<div class="col-3">';
			html += '<h5>' + response.data.track4 + '</h5></div>';
			html += '</div>';
			html += '<hr></hr>';
		}
		
		
		staffsummary.empty().append(html);
		$('#staffSummary').modal('show');
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
tj.getReportData = function(reportsId,categoryId,locationId,start,end,sort,sortId,callback) {
    //console.log('reportsId',reportsId);
	$.ajax({
        url:'inc/data.php?req=getReports',
        data:{
            start:start,
            end:end,
			idsearch:reportsId,
			categoryId:categoryId,
			locationId:locationId,
			sort:sort,
			sortId:sortId
        },
        method:'POST',
        dataType:'json',
        success:function(response) {
		    
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
// UPDATE REPORTS PAGE DATE
tj.updateReports = function(start,end,reportsId,categoryId,locationId,sort,safety,sortId) {
	var reportsId = reportsId;
    //console.log('safety-',safety);
    tj.reportDates = {
        start:start,
        end:end
    }
    tj.getReportData(reportsId,categoryId,locationId,start,end,sort,sortId,function(data){
        var color = Chart.helpers.color;
		var reportBody = $('#reportBody');
		var html = '';
		var chartBody = $('#chartBody');
		var charthtml = '';
		//var safety=0;
		
		
		html += '<div class="col-12">';
		for (var i=1;i<= data['deptCount1'];i++){
		if(i==1){	

		if(data['daytotal1']>0){
		html += '<div class="col-12 row">';
		if(data['daytotal1']>0){
		
		html += '<div class="col-md">';
		html += '<div class="dashboard-stat red-intense">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="dn1" ' + data['daySave1'] + '>' + data['daycount1'] + '</div>';
		html += '<div class="desc" id="dd1"><h5>' + data['daycountTitle1'] + ' ' + data['updateTime1'] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(data['daytotal1']>1){
		
		html += '<div class="col-md">';
		html += '<div class="dashboard-stat red-intense">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="dn2" ' + data['daySave2'] + '>' + data['daycount2'] + '</div>';
		html += '<div class="desc" id="dd2" ><h5>' + data['daycountTitle2'] + ' ' + data['updateTime2'] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(data['daytotal1']>2){
		
		html += '<div class="col-md">';
		html += '<div class="dashboard-stat red-intense">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="dn3" ' + data['daySave3'] + '>' + data['daycount3'] + '</div>';
		html += '<div class="desc" id="dd3" ><h5>' + data['daycountTitle3'] + ' ' + data['updateTime3'] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(data['daytotal1']>3){
		
		html += '<div class="col-sm">';
		html += '<div class="dashboard-stat red-intense">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="dn4" ' + data['daySave4'] + '>' + data['daycount4'] + '</div>';
		html += '<div class="desc" id="dd4" ><h5>' + data['daycountTitle4'] + ' ' + data['updateTime4'] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		html += '</div>';
		}
		}
		if(data['showProd' + i]==2){
		var budgetNum = data['totalProdVal' + i].toLocaleString('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 });
		}else{
		var budgetNum = data['totalProdVal' + i];
		}
		html += '<div id="unit' + data['deptId' + i] + '">';
		html += '<div class="mr-auto">';
		
		html += '<h4>' + data['dept' + i] + '<small><span class="title" ' + data['styleRed' + i] + '>' + data['shiftName' + i] + '</span></small>';
		html += '' + data['closeButton' + i] + '</h4></div>';
		
		if(data['note' + i] !=''){
		html += '<div class="title bg-secondary border border-info"><strong>' + data['note' + i] + '</strong></div>';
		}
		
		html += '<div class="row">';
		html += '<div class="col-sm">';
		
		if(data['showcompliance' + i]==0 && data['staffCount' + i] >0 && (data['totalCan' + i] >= data['rnThresholdHigh' + i] || data['totalCan' + i] <= data['rnThresholdLow' + i])){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['showcompliance' + i]==2 && data['staffCount' + i] >0 && (data['totalCan' + i] >= data['gThresholdHigh' + i] || data['totalCan' + i] <= data['gThresholdLow' + i])){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['showcompliance' + i]==5 && data['staffCount' + i] >0 && data['totalCan' + i] >100){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['showcompliance' + i]==5 && data['staffCount' + i] >0 && data['totalCan' + i] >80 && data['totalCan' + i] <100){
		html += '<div class="dashboard-stat yellow-gold">';
		}else{
		html += '<div class="dashboard-stat green-haze">';
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		if(data['newRNvar' + i] !=''){
		html += '<div class="number"><a href="#" data-toggle="tooltip" data-placement="top" style="color:white" title="' + data['newRNvar' + i] + '">' + data['totalCan' + i] + '</a></div>';
		html += '<div class="desc"><h5>' + data['variance' + i] + '</h5></div></div></div></div></div>';
		}else{
		html += '<div class="number">' + data['totalCan' + i] + '</div>';
		html += '<div class="desc"><h5>' + data['variance' + i] + '</h5></div></div></div></div></div>';	
		}
		if (data['showProd' + i] !=0) {
		html += '<div class="col-sm">';
		}else{
		html += '<div class="col-sm" hidden>';
		}
		
		if(data['showProd' + i]==5 && data['staffCount' + i] >0 && (data['totalProdVal' + i] >= data['rnThresholdHigh' + i] || data['totalProdVal' + i] <= data['rnThresholdLow' + i])){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['showProd' + i]==6 && data['staffCount' + i] >0 && (data['totalProdVal' + i] >= data['gThresholdHigh' + i] || data['totalProdVal' + i] <= data['gThresholdLow' + i])){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat green-haze">';
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + budgetNum + '</div>';
		html += '<div class="desc"><h5>' + data['prodLabel' + i] + '</h5></div></div></div></div></div>';
		
		html += '<div class="col-sm">';
		html += '<div class="dashboard-stat green-haze">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + data['staffCount' + i] + '</div>';
		html += '<div class="desc"><h5>' + data['productivity' + i] + '</h5></div></div></div></div></div>';

		html += '<div class="col-sm">';
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + data['pCount' + i] + '</div>';
		html += '<div class="desc"><h5>' + data['planned' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		
		
		if(data['trackdc' + i]==1){
		html += '<div class="col-sm">';
		}else{
		html += '<div class="col-sm" hidden>';
		}
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + data['totalDischarges' + i] + '</div>';
		html += '<div class="desc"><h5>' + data['discharges' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		
		if(data['pMeasure' + i]==1){
		html += '<div class="col-sm">';
		}else{
		html += '<div class="col-sm" hidden>';	
		}
	
		if(data['bedsBlocked' + i] !=0 && data['dash5' + i] ==0){
		html += '<div class="dashboard-stat red-intense">';
		}else{
		html += '<div class="dashboard-stat blue-madison">';
		}
		if(data['dash5' + i] ==0){
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + data['totalBlocked' + i] + ' / ' + data['bedsBlocked' + i] + '</div>';
		html += '<div class="desc"><h5>' + data['blockedbeds' + i] + '</h5></div></div></div></div></div>';
		}else{
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number">' + data['discharges' + i] + '</div>';
		html += '<div class="desc"><h5>Discharges</h5></div></div></div></div></div>';	
		}
		html += '<div class="col-sm">';
		
		if (data['newEscalation' + i] ==0){
		html += '<div class="dashboard-stat blue-madison">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number"><h2>None</h2></div>';
		}else{
		html += '<div class="dashboard-stat red-intense">';
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number"><a href="javascript:;" onclick="tj.showEscalation(' + data['escId' + i] + ');" data-toggle="tooltip" data-placement="top" style="color:white" title="' + data['escComment' + i] + '"><u><h3>' + data['newEscalation' + i] + '</h3></u></a></div>';
		}
		html += '<div class="desc"><h5>Escalation</h5></div></div></div></div></div></div>';
		//html += '</div>';
				////////safety huddle	
		
		if(safety==1){
		//html += '<div class="row">';			
		html += '<div class="col-12 row">';
		if(safety==1 && data['safeT1' + i] ==1){
		
		html += '<div class="col-md-3">';
		if(data['priority1' + i] ==1){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['dueDate1' + i] ==1){
		html += '<div class="dashboard-stat yellow-gold">';	
		}else{
		html += '<div class="dashboard-stat green-haze">';	
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="safety1" ><h5>' + data['safeNote1' + i] + '</h5></div>';
		html += '<div class="desc" id="safetyDesc1"><h5>' + data['safeTitle1' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(safety==1 && data['safeT2' + i] ==1){
		
		html += '<div class="col-md-3">';
		if(data['priority2' + i] ==1){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['dueDate2' + i] ==1){
		html += '<div class="dashboard-stat yellow-gold">';	
		}else{
		html += '<div class="dashboard-stat green-haze">';	
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="safety2" ><h5>' + data['safeNote2' + i] + '</h5></div>';
		html += '<div class="desc" id="safetyDesc2" ><h5>' + data['safeTitle2' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(safety==1 && data['safeT3' + i] ==1){
		
		html += '<div class="col-md-3">';
		if(data['priority3' + i] ==1){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['dueDate3' + i] ==1){
		html += '<div class="dashboard-stat yellow-gold">';	
		}else{
		html += '<div class="dashboard-stat green-haze">';	
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="safety3" ><h5>' + data['safeNote3' + i] + '</h5></div>';
		html += '<div class="desc" id="safetyDesc3" ><h5>' + data['safeTitle3' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		}
		if(safety==1 && data['safeT4' + i] ==1){
		
		html += '<div class="col-md-3">';
		if(data['priority4' + i] ==1){
		html += '<div class="dashboard-stat red-intense">';
		}else if(data['dueDate4' + i] ==1){
		html += '<div class="dashboard-stat yellow-gold">';	
		}else{
		html += '<div class="dashboard-stat green-haze">';	
		}
		html += '<div class="visual">';
		html += '<div class="details">';
		html += '<div class="number" id="safety4" ><h5>' + data['safeNote4' + i] + '</h5></div>';
		html += '<div class="desc" id="safetyDesc4" ><h5>' + data['safeTitle4' + i] + '</h5></div>';
		html += '</div></div></div></div>';
		
		}
	
		html += '</div>';
		}
		}		
		html += '</div></div>';
		
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
		
		if (data['hidePlanned1']==1 && data['pMeasure1']!=4 && data['deptCount1']==1){
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
		/*
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
		*/
    })
}

tj.showEscalation = function (dataId) {
		//console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getEscalation',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if (response.data.active==1){
				document.getElementById("closeEsc").checked = false;
				}else{
				document.getElementById("closeEsc").checked = true;	
				}
				
				$('#escdataId').val(response.data.id);
				$('#escType').val(response.data.escalationName);
				$('#escUnit').val(response.data.dept);
				$('#escSubmitted').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dateSubmitted);
				$('#escComment').val(response.data.note);
				$('#escResponse').val(response.data.response);
                $('#showEscalation').modal('show');
            }
        });
}

tj.updateEscalation = function () {
        var dataId = $('#escdataId').val();
		var response = $('#escResponse1').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var active = $('#closeEsc').is(':checked') ? 2 : 1;
		
        $.ajax({
            url: 'inc/data.php?req=updateEscalation',
            data: {
                dataId: dataId,
				response: response,
				currentTime: currentTime,
				active: active
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Response Successfully Submitted.');
				document.getElementById("escdataId").value = "";
				document.getElementById("escResponse1").value = "";
                $('#showEscalation').modal('hide');
            }
        });
}

tj.showTracking = function (dataId) {
		//console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getTracking',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				var a1 = document.getElementById('track1Details');
				var a2 = document.getElementById('track2Details');
				var a3 = document.getElementById('track3Details');
				var a4 = document.getElementById('track4Details');
				
				if(response.data.track1Show==0){
				a1.style.display='none';	
				}else{
				a1.style.display='';
				}
				if(response.data.track2Show==0){
				a2.style.display='none';	
				}else{
				a2.style.display='';
				}
				if(response.data.track3Show==0){
				a3.style.display='none';	
				}else{
				a3.style.display='';
				}
				if(response.data.track4Show==0){
				a4.style.display='none';	
				}else{
				a4.style.display='';
				}
				$('#trackId').val(response.data.id);
				$('#track1Type').html(response.data.track1Desc);
				$('#track2Type').html(response.data.track2Desc);
				$('#tracktype1').html(response.data.track1Desc);
				$('#tracktype2').html(response.data.track2Desc);
				$('#track3Type').html(response.data.track3Desc);
				$('#track4Type').html(response.data.track4Desc);
				$('#tracktype3').html(response.data.track3Desc);
				$('#tracktype4').html(response.data.track4Desc);
				$('#track1Number').val(response.data.track1);
				$('#track2Number').val(response.data.track2);
				$('#track3Number').val(response.data.track3);
				$('#track4Number').val(response.data.track4);
				$('#trackUnit').val(response.data.dept);
				$('#trackSubmitted').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dayTime);
				$('#track1Comment').val(response.data.trackNote1);
				$('#track2Comment').val(response.data.trackNote2);
				$('#track3Comment').val(response.data.trackNote3);
				$('#track4Comment').val(response.data.trackNote4);
                $('#showTracking').modal('show');
            }
        });
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
tj.daterangepickerInit = function(reportsId,startPay,endPay,role,startReport,endReport,nosort,sortId) {
	
    if ($('#m_dashboard_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#m_dashboard_daterangepicker');
	
	
	if(startReport && endReport){
	var start = moment().subtract(startReport, 'days');
    var end = moment().subtract(endReport, 'days');
	//var start = moment();
    //var end = moment();
	}else{
	var start = moment();
    var end = moment();	
	}	
	//var start = moment().subtract(startPay, 'days');
	//var end = moment().add(endPay, 'days');

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
		var sort = $('#currentSort').val();
		var safety = 0;
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
        $("#startDate").val(start.format('YYYY-MM-DD'));
		$("#endDate").val(end.format('YYYY-MM-DD'));
        tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'),reportsId,categoryId,locationId,sort,safety,sortId);

    }
	if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
		
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		showCustomRangeLabel: false,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        }
    }, cb);	
		
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
		showCustomRangeLabel: false,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        }
    }, cb);
	}

    cb(start, end, '');
};

tj.updateUser2 = function() {
		var mobile = $('#activateMobile').val();
		var email = $('#activateEmail').val();
		var curmobile = $('#currentMobile').val();
		var userId = $('#activateuserId').val();
		var times = $('#times').val();
		var report = $('#report').is(':checked') ? 1 : 0;
		var newRole = $('#updateRole').val();
		var updateFirst = $('#updateFirst').val();
		var updateLast = $('#updateLast').val();
		var currentRole = $('#currentRole').val();
		var missed = $('#missed').is(':checked') ? 1 : 0;
		var pause = $('#pause').is(':checked') ? 1 : 0;
		
		
		//var houseAlerts = $('#houseAlerts').is(':checked') ? 1 : 0;
		//console.log('audit ',audit);
		//console.log('mobile ',mobile);
		
		if( /(.+)@(.+){2,}\.(.+){2,}/.test($('#activateEmail').val()) ){
			var emailAlerts = $('#emailAlerts').is(':checked') ? 1 : 0;
			var emailMissed = $('#emailMissed').is(':checked') ? 1 : 0;
		} else {
			var emailAlerts = 0;
			var emailMissed = 0;
		}
		
		if(mobile.length >0 && mobile.length !=10){
		bootbox.alert('Mobile Number must be 10 digits only (ie. 2145551234).');
		return;
		}
		if(mobile!=curmobile && mobile.length==10){
		var newMobile = 1;
		}else{
		var newMobile = 0;	
		}
		
		if(mobile.length !=10){
			var escalation = 0;
			var audit = 0;
		}else{
			var escalation = $('#escalation').is(':checked') ? 1 : 0;
			var audit = $('#audit').is(':checked') ? 1 : 0;			
		}
		
		if(report==1 || escalation==1 || missed==1){
			var txt=1;
		}else{
			var txt=0;
		}
		
		if(newRole != currentRole && (newRole==6 || newRole==7 || currentRole==6 || currentRole==7)){
		bootbox.confirm({
        message:"By changing this user's role either to or from a Manager or Director, all of their current Unit assignments will need to be reset in the User/Unit Settings. <br>Continue?</br>",
		backdrop:true,
        callback:function (result) {
		if (result) {
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
				emailMissed: emailMissed,
				newMobile: newMobile,
				txt: txt,
				email: email,
				newRole: newRole,
				currentRole: currentRole,
				updateFirst: updateFirst,
				updateLast: updateLast,
				audit: audit
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('User profile updated successfully.');
				document.getElementById("report").checked = false;
				document.getElementById("escalation").checked = false;
				document.getElementById("audit").checked = false;
				document.getElementById("missed").checked = false;
				document.getElementById("pause").checked = false;
				//document.getElementById("houseAlerts").checked = false;
				document.getElementById("emailAlerts").checked = false;
				document.getElementById("emailMissed").checked = false;
				tj.UserTable.ajax.reload(null,false);
                $('#activate').modal('hide');
				
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
				emailMissed: emailMissed,
				newMobile: newMobile,
				txt: txt,
				email: email,
				newRole: newRole,
				updateFirst: updateFirst,
				updateLast: updateLast,
				currentRole: currentRole,
				audit: audit
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				bootbox.alert('User profile updated successfully.');
				document.getElementById("report").checked = false;
				document.getElementById("escalation").checked = false;
				document.getElementById("audit").checked = false;
				document.getElementById("missed").checked = false;
				document.getElementById("pause").checked = false;
				document.getElementById("emailAlerts").checked = false;
				document.getElementById("emailMissed").checked = false;
				//tj.UserTable.ajax.reload(null,false);
                $('#activate').modal('hide');
				
            }
        });
	}
};
 
 
tj.emailAlerts = function() {
		var emailAlert = $('#emailAlerts').is(':checked') ? 1 : 0;
		if(emailAlert==1){
		bootbox.alert('Checking this box will turn on Email Alerts for ALL UNITS your are associated with. <p>You can turn on and off Email Alerts for specific Units in the Unit Settings for each User.</p>');
		return;
		}else{
		//do nothing;	
		}
}
	
tj.categorySort = function(sort) {
		var a0 = document.getElementById('sort0');
		var a1 = document.getElementById('sort1');
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var safety = $('#showSafety').is(':checked') ? 1 : 0;
		if(sort==0 || sort==1){
		var currentSort = sort;
		$('#currentSort').val(sort);
		}else{
		var currentSort=$('#currentSort').val();
		}
		//console.log('currentSort', currentSort);
		if (sort==0){
			a0.style.display='none';
			a1.style.display='';
		}else{
			a0.style.display='';
			a1.style.display='none';
		}
		var start = $('#startDate').val();
		var end = $('#endDate').val();		
		//console.log('catStart',start);
		//var start = moment().subtract($('#startDate').val(), 'days').format('YYYY-MM-DD');
		//var end = moment().subtract($('#endDate').val(), 'days').format('YYYY-MM-DD');
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId,currentSort,safety);
		
    };

tj.updaterptSafety = function() {
		var start = $('#startDate').val();
		var end = $('#endDate').val();
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var reportsId= '';	
		var currentSort=$('#currentSort').val();	
		var dataId = $('#rptsafetyId').val();
		var deptId = $('#rptunit1').val();
		var roomId1 = $('#rptloc1').val();
		var priority = $('#rptpriority').is(':checked') ? 1 : 0;
		var active = $('#rptactive').is(':checked') ? 0 : 1;
		var safety = $('#showSafety').is(':checked') ? 1 : 0;
		var peri1 = $('#rptperi1').val();
		
		//var hr1 = $('#rpthrpt1').val();
		
		//var periGen1 = $('#rptperigen1').val();
	
		var note1 = $('#rptrdesc1').val();
		
		var dueDate = $('#rptdue1').val();
	
        $.ajax({
            url:'inc/data.php?req=updatesafety',
            data:{
                dataId: dataId,
				deptId: deptId,
				roomId1: roomId1,
				peri1: peri1,
				note1: note1,
				dueDate: dueDate,
				priority: priority,
				active: active
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#rpthuddle').modal('hide');
				//tj.safetyTable.ajax.reload();
				tj.updateReports(start,end,reportsId,category,locationId,currentSort,safety);
				}	
        })
		
  }
	
tj.rptSafety = function(dataId) {
	document.getElementById("rptType").style.display='';	
        $.ajax({
            url:'inc/data.php?req=getsafetyDetails',
            data:{
                dataId: dataId		
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.priority==1){
				document.getElementById("rptpriority").checked = true;
				}else{
				document.getElementById("rptpriority").checked = false;	
				}
				if (response.data.active==1){
				document.getElementById("rptactive").checked = false;
				}else{
				document.getElementById("rptactive").checked = true;	
				}
				if(response.data.safetyTypes==2){
				document.getElementById("rptType").style.display='none';	
				}
				$('#rptsafetyId').val(response.data.id);
				$('#rptunit1').val(response.data.deptId);
				$('#submittedDate').html(' ' + response.data.submitDate);
				$('#rptloc1').val(response.data.roomId1);
				$('#rptdue1').val(response.data.dueDate);
				//var perievent = JSON.parse(response.data.periEvent1);
				$('#rptperi1').val(response.data.safetyConfig);
				//var hr1 = JSON.parse(response.data.hr1);
				//$('#rpthrpt1').val(response.data.hr1);
				//var periGen1 = JSON.parse(response.data.periGen1);
				//$('#rptperigen1').val(response.data.periGen1);
				$('#rptrdesc1').val(response.data.note1);
				$('#rpthuddle').modal('show');
				
				}	
        })
		
  }

tj.showSafety = function() {
		var a0 = document.getElementById('sort0');
		var a1 = document.getElementById('sort1');
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var safety = $('#showSafety').is(':checked') ? 1 : 0;
		var currentSort=$('#currentSort').val();
		console.log('showSafety: ',safety);
		/*
		if (sort==0){
			a0.style.display='none';
			a1.style.display='';
		}else{
			a0.style.display='';
			a1.style.display='none';
		}
		*/
		var start = $('#startDate').val();
		var end = $('#endDate').val();		
		//console.log('catStart',start);
		//var start = moment().subtract($('#startDate').val(), 'days').format('YYYY-MM-DD');
		//var end = moment().subtract($('#endDate').val(), 'days').format('YYYY-MM-DD');
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId,currentSort,safety);
		
    };
	
tj.categorySelect = function(sort) {
		var a0 = document.getElementById('sort0');
		var a1 = document.getElementById('sort1');
		var safety = $('#showSafety').is(':checked') ? 1 : 0;
		var locationId = $('#location').val();
		var category = $('#filter').val();
		if(sort==0 || sort==1){
		var currentSort = sort;
		$('#currentSort').val(sort);
		}else{
		var currentSort=$('#currentSort').val();
		}
		//console.log('currentSort', currentSort);
		if (sort==0){
			a0.style.display='none';
			a1.style.display='';
		}else{
			a0.style.display='';
			a1.style.display='none';
		}
		var start = $('#startDate').val();
		var end = $('#endDate').val();		
		//console.log('catStart',start);
		//var start = moment().subtract($('#startDate').val(), 'days').format('YYYY-MM-DD');
		//var end = moment().subtract($('#endDate').val(), 'days').format('YYYY-MM-DD');
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId,currentSort);
		
    };
	
tj.locationSelect = function() {
		var locationId = $('#location').val();
		var category = $('#filter').val();
		var sort = $('#currentSort').val();
		//var end = $('#endDate').val();
		var start = moment().subtract($('#startDate').val(), 'days').format('YYYY-MM-DD');
		var end = moment().subtract($('#endDate').val(), 'days').format('YYYY-MM-DD');
		var reportsId= '';
		tj.updateReports(start,end,reportsId,category,locationId,sort);
		
    };
	
tj.activate = function(userId,showRole) {
		//var userId = $('#userId').val()
		document.getElementById("updateFirst").disabled = false;
		document.getElementById("escalation").disabled = false;
		document.getElementById("times").disabled = false;
		document.getElementById("pause").disabled = false;
		document.getElementById("emailAlerts").disabled = false;
		document.getElementById("updateRole").disabled = false;
		document.getElementById("updateLast").disabled = false;
		document.getElementById("activateMobile").disabled = false;	
				       
        $.ajax({
            url:'inc/data.php?req=getUserDetailsActivate',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var a1 = document.getElementById('activateRole');
				var a2 = document.getElementById('deleteUser');
				var a3 = document.getElementById('demoMode');
				//var a4 = document.getElementById('activateHouse');
				
				var valid_email = response.data.email2;
				if( /(.+)@(.+){2,}\.(.+){2,}/.test(valid_email) ){
				  var validEmail=1;
				} else {
				  var validEmail=0;
				}
				
				if(parseInt(response.role)>10 && response.loggedIn==response.data.id){
				a3.style.display='';
				}else{
				a3.style.display='none';
				}
				//console.log('role ',response.data.role);
				//if(parseInt(response.data.role)>=9){
				//a4.style.display='';
				//}else{
				//a4.style.display='none';
				//}
				
				if(parseInt(response.role)>10 || (parseInt(response.role)>5 && response.loggedIn==response.data.id)){
				document.getElementById("activateEmail").disabled = false;
				document.getElementById("emailAlerts").disabled = false;
				document.getElementById("emailMissed").disabled = false;
				}else{
				document.getElementById("activateEmail").disabled = true;
				document.getElementById("emailAlerts").disabled = true;
				document.getElementById("emailMissed").disabled = true;
				}
				
				if(validEmail==1){
				$('#invalid').html('');	
				}else{
				$('#invalid').html('<span style="color:red">(Your email address is INVALID)</span>');	
				}
				
				if (response.data.xcount!=0 && response.data.xemail == response.data.xcount && validEmail==1){
				document.getElementById("emailAlerts").checked = true;
				}else{
				document.getElementById("emailAlerts").checked = false;	
				}
				if (response.data.emailMissed >0 && validEmail==1){
				document.getElementById("emailMissed").checked = true;
				}else{
				document.getElementById("emailMissed").checked = false;	
				}
				
				if(response.loggedIn!= response.data.id && parseInt(response.role) > parseInt(response.data.role)){
				a2.style.display='';
				}else{
				a2.style.display='none';
				}
				
				if(parseInt(response.role)<11){
				document.getElementById("updateRole").disabled = true;
				}else if(parseInt(response.role)>10){
				document.getElementById("activateMobile").disabled = false;
				document.getElementById("updateRole").disabled = false;
				document.getElementById("updateFirst").disabled = false;
				document.getElementById("updateLast").disabled = false;
				}else if(parseInt(response.role)<11 && response.loggedIn==response.data.id){	
				document.getElementById("updateFirst").disabled = false;
				document.getElementById("updateLast").disabled = false;
				document.getElementById("activateMobile").disabled = false;				
				}else{
				document.getElementById("updateFirst").disabled = true;
				document.getElementById("updateLast").disabled = true;
				document.getElementById("activateMobile").disabled = true;
				}
				
				if(response.data.role==3){
				document.getElementById("updateFirst").disabled = true;
				document.getElementById("escalation").disabled = true;
				document.getElementById("times").disabled = true;
				document.getElementById("pause").disabled = true;
				document.getElementById("emailAlerts").disabled = true;
				document.getElementById("updateRole").disabled = true;
				document.getElementById("updateLast").disabled = true;
				document.getElementById("activateMobile").disabled = true;	
				}
				//if (response.data.houseAlerts >0){
				//document.getElementById("houseAlerts").checked = true;
				//}else{
				//document.getElementById("houseAlerts").checked = false;
				//}
				
				if (response.data.txtPause >0){
				document.getElementById("pause").checked = true;
				}else{
				document.getElementById("pause").checked = false;
				}
			
				if (response.data.demoMode >0){
				document.getElementById("demo").checked = true;
				}else{
				document.getElementById("demo").checked = false;	
				}
				if (response.data.txtEscalation >0){
				document.getElementById("escalation").checked = true;
				}else{
				document.getElementById("escalation").checked = false;	
				}
				if (response.data.logAlerts >0){
				document.getElementById("audit").checked = true;
				}else{
				document.getElementById("audit").checked = false;	
				}
								
				$('#validEmail').val(validEmail);
				$('#activateMobile').val(response.data.mobile);
				$('#activateEmail').val(response.data.email2);
				$('#currentMobile').val(response.data.mobile);
                $('#activateuserId').val(response.data.id);
				$('#updateRole').val(response.data.role);
				$('#updateFirst').val(response.data.first_name);
				$('#updateLast').val(response.data.last_name);
				$('#currentRole').val(response.data.role);
				$('#times').val(response.data.alertTimes);
				$('#settingsFor').html('    ' + response.data.last_name +', '+response.data.first_name);
				$('#activate').modal('toggle');
				}
        })
        
		
    };
	
tj.coverage = function() {
	//$('#coverageTable').DataTable().destroy();
	tj.coverageTable.ajax.reload();	
	//var today = new Date().toISOString().split('T')[0];
	//document.getElementsByName("endCoverage")[0].setAttribute('min', today);
	//$('#activateMobile').val(response.data.mobile);
	$('#coverage').modal('toggle');
};
/*
tj.changeCover = function(deptId) {
	var coverId = $('#selectCoverage'+deptId+'').val();
	//var coverId = document.getElementById('selectCoverage'+deptId+'').val();
	console.log('deptId :',deptId);
	console.log('userId :',coverId);
	
};
*/

tj.hidebox = function(deptId) {
	var a1 = document.getElementById('unit' + deptId + '');
	//console.log('deptId',deptId);
	bootbox.confirm({
        message:"Confirm: Remove this Unit from your Dashboard?",
		backdrop:true,
        callback:function (result) {
		if (result) { 
	$.ajax({
        url:"inc/data.php?req=changecoverage",
        data: {
                deptId: deptId,
				userId: 0
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		a1.style.display='none';
		//tj.coverageTable.ajax.reload();			
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
	
	
tj.initializeCoverageGrid = function() {
	
tj.coverageTable = $('#coverageTable').DataTable({
        "ajax": {
            url:"inc/data.php?req=getcoverage",
            data: {},
            type:"POST"
        },
		"pageLength": 50,
        "order": [0,'asc'],
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "cover" },
			{ "data": "end" }
        ],
		/*
		success:function(response) {
		console.log('role',response.data.role);
		var a1 = document.getElementById('myCoverage');
			if(response.data.role==11){
			a1.style.display='none';
			}else{
			a1.style.display='';
			}			
        }
		*/
		
    });

tj.changeCover = function(deptId) {
	var userId = $('#selectCoverage'+deptId+'').val();
	var endDate = $('#end'+deptId+'').val();
	
	$.ajax({
        url:"inc/data.php?req=changecoverage",
        data: {
                deptId: deptId,
				userId: userId,
				endDate: endDate
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		if(userId==0){
		tj.coverageTable.ajax.reload();	
		}		
        }
    })
	
	
}


}

tj.activate2 = function(userId) {
		$('#edit_Unit').modal('toggle');			       
        $.ajax({
            url:'inc/data.php?req=getUserDetailsActivate',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//var a1 = document.getElementById('activateRole');
				var a2 = document.getElementById('deleteUser');
				var a3 = document.getElementById('demoMode');
				//a1.style.display='none';
				a2.style.display='none';
				a3.style.display='none';

				var valid_email = response.data.email2;
				if( /(.+)@(.+){2,}\.(.+){2,}/.test(valid_email) ){
				  var validEmail=1;
				} else {
				  var validEmail=0;
				}				
				
				if (response.data.txtPause >0){
				document.getElementById("pause").checked = true;
				}else{
				document.getElementById("pause").checked = false;
				}
			
				if(parseInt(response.role)<11){
				document.getElementById("updateRole").disabled = true;
				}else if(parseInt(response.role)>10){
				document.getElementById("activateMobile").disabled = false;
				document.getElementById("updateRole").disabled = false;
				document.getElementById("updateFirst").disabled = false;
				document.getElementById("updateLast").disabled = false;
				}else if(parseInt(response.role)<11 && response.loggedIn==response.data.id){	
				document.getElementById("updateFirst").disabled = false;
				document.getElementById("updateLast").disabled = false;
				document.getElementById("activateMobile").disabled = false;				
				}else{
				document.getElementById("updateFirst").disabled = true;
				document.getElementById("updateLast").disabled = true;
				document.getElementById("activateMobile").disabled = true;
				}
						
				if(parseInt(response.role)>10 || (parseInt(response.role)>5 && response.loggedIn==response.data.id)){
				document.getElementById("activateEmail").disabled = false;
				document.getElementById("emailAlerts").disabled = false;
				document.getElementById("emailMissed").disabled = false;
				}else{
				document.getElementById("activateEmail").disabled = true;
				document.getElementById("emailAlerts").disabled = true;
				document.getElementById("emailMissed").disabled = true;
				}
				
				if(validEmail==1){
				$('#invalid').html('');	
				}else{
				$('#invalid').html('<span style="color:red">(Your email address is INVALID)</span>');	
				}
				
				if (response.data.demoMode >0){
				document.getElementById("demo").checked = true;
				}else{
				document.getElementById("demo").checked = false;	
				}
				if (response.data.txtEscalation >0){
				document.getElementById("escalation").checked = true;
				}else{
				document.getElementById("escalation").checked = false;	
				}
				if (response.data.logAlerts >0){
				document.getElementById("audit").checked = true;
				}else{
				document.getElementById("audit").checked = false;	
				}
				if (response.data.xcount!=0 && response.data.xemail == response.data.xcount && validEmail==1){
				document.getElementById("emailAlerts").checked = true;
				}else{
				document.getElementById("emailAlerts").checked = false;	
				}
				if (response.data.emailMissed >0 && validEmail==1){
				document.getElementById("emailMissed").checked = true;
				}else{
				document.getElementById("emailMissed").checked = false;	
				}
				
				$('#validEmail').val(validEmail);					
				$('#activateMobile').val(response.data.mobile);
				$('#activateEmail').val(response.data.email2);
				$('#currentMobile').val(response.data.mobile);
				$('#updateRole').val(response.data.role);
				$('#updateFirst').val(response.data.first_name);
				$('#updateLast').val(response.data.last_name);
				$('#currentRole').val(response.data.role);
                $('#activateuserId').val(response.data.id);
				$('#times').val(response.data.alertTimes);
				$('#settingsFor').html(response.data.last_name +', '+response.data.first_name);
				$('#activate').modal('toggle');
				}
        })
        
		
    };
	
tj.edittextVariance = function(variance,userId,deptId) {
		$('#edit_Unit').modal('toggle');
		console.log('variance: ',variance);
		console.log('userId: ',userId);
		console.log('deptId: ',deptId);
        $('#txtVaruserId').val(userId);
		$('#txtVardeptId').val(deptId);
		$('#txtVar').val(variance);
		$('#edittxtVar').modal('toggle');
        
		
    };

tj.updatetxtVar = function() {
	var userId = $('#txtVaruserId').val();
	var deptId = $('#txtVardeptId').val();
	var txtVar = $('#txtVar').val();
	        
        $.ajax({
            url:'inc/data.php?req=updatetxtVar',
            data:{
                userId:userId,
				deptId:deptId,
				txtVar:txtVar
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#edittxtVar').modal('toggle');
				bootbox.alert('Variance Updated');
				//tj.unitTableMgr.ajax.reload();
				}
        })
        //console.log(recordId);
		
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
		"pageLength": 25,
        "order": [[4,'asc'],[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "Name" },
			{ "data": "Unit" },
			{ "data": "Role" },
			{ "data": "Login" },
			{ "data": "Shared" }
        ],
		"columnDefs": [
						{"visible": false, "targets": [4] }
        ],
		 } );
	
/////////////////////////////////////
// EDIT USER

tj.editUser = function(userId,deptId) {
		//$('#unitTableMgr').DataTable().destroy();
		//console.log('userId',userId);
		//console.log('deptId',deptId);
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
				
				if (response.data.provider !=0){
				document.getElementById("isprov").checked = true;
				}else{
				document.getElementById("isprov").checked = false;
				}
				
				if (response.user.userRole<7) {
				a2.style.display='none';
				a1.style.display='';
				}else{
				a2.style.display='';
				a1.style.display='none';
				}
				
				$('#ecf_user_email').val(response.data.email2);
				$('#ecf_username').val(response.data.userName);
                $('#ecf_user_role').val(response.data.role);
				$('#roleName').html(response.data.roleName);
				$('#roleOrig').val(response.data.role);
                $('#edit_userId').val(response.data.userId);
				//$('#unitChange').val(response.data.depid);	
				$('#unitOrig').val(response.data.deptId);	
				//$('#updatealerts').val(response.data.Alerts);	
				$('#userName3').html(response.data.first_name+' '+response.data.last_name);
				$('#lastLogin').html(response.data.lastlogin);
	            //$('#UserTable').DataTable().search('').draw();
				$('#edit_user').modal('show');
				//console.log('login',response.data.lastLogin);
				//if(parseInt(response.data.role)<8){
				//tj.unitGrid(response.data.userId);
				//}
				}
        })
        
    }
	
tj.editUnits = function(userId) {
		$('#unitTableMgr').DataTable().destroy();
		
        $.ajax({
            url:'inc/data.php?req=getUserDetails',
            data:{
                userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.unitGrid(response.data.userId);
				//console.log('userId',response.data.userId);
				$('#userName4').html(response.data.first_name+' '+response.data.last_name);
				$('#roleUnit').val(response.data.role);
				$('#roleUserId').val(response.data.id);
				$('#edit_Unit').modal('show');
				}
        })
        
    }
	

tj.newUnit = function() {
	var role = $('#roleUnit').val();
	var userId = $('#roleUserId').val();
	//console.log('userId',userId);
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
 
tj.compReports = function(userId) {
	var check1 = $('#checkComp' + userId).is(':checked') ? 1 : 0;
	var check2 = 2;
	//console.log('user',userId);
	//console.log('check1',check1);
	//console.log('check2',check2);
	
		$.ajax({
            url:'inc/data.php?req=compReports',
            data:{
				check1: check1,
				check2: check2,
				userId: userId
           },
            method:'POST',
            dataType:'json',
            success:function(response) {

			}
        })
		
 }
 
tj.unitReports = function(userId) {
	var check1 = 2;
	var check2 = $('#checkUnit' + userId).is(':checked') ? 1 : 0;
	//console.log('2user',userId);
	//console.log('2check1',check1);
	//console.log('2check2',check2);
		$.ajax({
            url:'inc/data.php?req=compReports',
            data:{
				check1: check1,
				check2: check2,
				userId: userId
           },
            method:'POST',
            dataType:'json',
            success:function(response) {
			}
        })
		
 }
  
 tj.newUnitAdd = function() {
	var deptId = $('#newunitdeptId').val();
	var userId = $('#newunituserId').val();
	//var grantText = $('#newunitText').val();
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
				role:role
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("newunitdeptId").selectedIndex = 0;
				//document.getElementById("newunitText").selectedIndex = 0;
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
				role:role
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				document.getElementById("newunitdeptId").selectedIndex = 0;
				//document.getElementById("newunitText").selectedIndex = 0;
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
		//		console.log('dept',deptId);
			//	console.log('deptOrig',deptIdOrig);
	
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
		//var unit = $('#unitChange').val();
		//var unitOrig = $('#unitOrig').val();
		//var stop = $('#updatealerts').val();
		var roleOrig = $('#roleOrig').val();
		var provider = $('#isprov').is(':checked') ? 1 : 0;
		//var provider = $('#isprov').val();
		
		   $.ajax({
            url:'inc/data.php?req=updateUser',
            data:{
                email:email,
                role:role,
				roleOrig:roleOrig,
				userId:userId,
				provider:provider
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				
				bootbox.alert('User updated successfully.');
				//document.getElementById("updatealerts").selectedIndex = "0";
				//document.getElementById("ecf_user_role").selectedIndex = 4;
				//document.getElementById("unitChange").selectedIndex = 0;
				document.getElementById("edit_userId").value = "";
				document.getElementById("unitOrig").value = "";
				document.getElementById("ecf_user_email").value = "";
				document.getElementById("roleOrig").value = "";
				$('#edit_user').modal('hide');
				tj.UserTable.ajax.reload(null,false);
				
				
            }
        });
}
	
	tj.deleteUser = function() {
		var userId = $('#activateuserId').val();
		//console.log('userId',userId);
		bootbox.confirm({
        message:"Remove User access to ProductiveRN?",
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
				$('#activate').modal('hide');
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

tj.editescStatus = function(textAlerts,recordId) {
		//console.log('alerts',textAlerts);
		//console.log('record',recordId);
		if (textAlerts == 1){
		bootbox.confirm({
        message:"Remove Escalation Text Alerts for this Unit?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateEscAlerts',
            data:{
                textAlerts:0,
				recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//tj.UserTable.ajax.reload(null,false);
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
        message:'Add Escalation Text Alerts for this Unit?',
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateEscAlerts',
            data:{
                textAlerts:1,
				recordId:recordId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {				
				//tj.UserTable.ajax.reload(null,false);
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
}
}

tj.edittextStatus = function(textAlerts,recordId) {
		//console.log('alerts',textAlerts);
		//console.log('record',recordId);
		if (textAlerts == 1){
		bootbox.confirm({
        message:"Remove Shift Report Text Alerts for this Unit?",
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
        message:'Add Shift Report Text Alerts for this Unit?',
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
				tj.UserTable.ajax.reload(null,false);
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
}
}

tj.editemailStatus = function(emailAlerts,recordId,deptId) {
		//console.log('alerts',textAlerts);
		//console.log('record',recordId);
		if (emailAlerts == 1){
		bootbox.confirm({
        message:"Remove Email Alerts for this User?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateemailAlerts',
            data:{
                emailAlerts:0,
				recordId:recordId,
				deptId:deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//tj.UserTable.ajax.reload(null,false);
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
        message:'Add Email Alerts for this User.',
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateemailAlerts',
            data:{
                emailAlerts:1,
				recordId:recordId,
				deptId:deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//tj.unitTable.ajax.reload();				
				//tj.UserTable.ajax.reload(null,false);
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
  //return re.test(email);
  return true;
}

tj.startNewUser = function () {
        
		var first = $('#ecf_newuser_first').val();
		var last = $('#ecf_newuser_last').val();
		var email = $('#ecf_newuser_email').val();
		var role = $('#ecf_newuser_role').val();
		var unit = $('#unitAssign').val();
		var accountId = $('#accountAssign').val();
		
		//var sendemail = $('#sendemail').is(':checked') ? 1 : 0;
		var provider = $('#isprovider').is(':checked') ? 1 : 0;
		
		//console.log('sendemail',sendemail);
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
		if(role >14 && accountId==0){
		bootbox.alert('Account is required for this Role');
		return;
		}
		if(role <=8 && unit ==0){
		bootbox.alert('Primary Unit is required');
		return;
		}	
        
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
				//console.log('good ',response.data.goodEmail);
				if (response.data.exist == true) {
				$('#userMessage').html('Contact your Admin to transfer this person into your Unit or give them access through your Unit Settings feature.');
				$('#usermsgType').html('User Already Exists');
				$('#confirmUser').modal('show');
				}else if(response.data.goodEmail == true){
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
					tj.sendUserEmail(response.data.user);
				}else{
				$('#userMessage').html('User added successfully but no email sent because of invalid or missing email.');
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
		if(username.length < 6){
		bootbox.alert('Username must be at least 6 characters in length.');
		return;
		}
		if(role == 0){
		bootbox.alert('Role is required');
		return;
		}
		if(accountId==0){
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
            { "data": "text" },
			{ "data": "variance" },
			{ "data": "esc" },
			{ "data": "email" },
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
				//console.log('goodEmail: ',response.data.goodEmail);
				if (response.data.exist == true) {
					$('#userMessage').html('Contact your Admin to transfer this person into your Unit.');
					$('#usermsgType').html('User Already Exists');
					$('#confirmUser').modal('show');
				}else if(response.data.goodEmail == true){
					$('#userMessage').html('User added successfully and email sent.');
					$('#usermsgType').html('Success');
					$('#confirmUser').modal('show');
					document.getElementById("ecf_newuser_first").value = "";
					document.getElementById("ecf_newuser_last").value = "";
					document.getElementById("ecf_newuser_email").value = "";
					document.getElementById("ecf_newuser_role").selectedIndex = "";
					$('#addnewUser').modal('hide');
					tj.UserTable.ajax.reload(null,false);
					tj.sendUserEmail(response.data.user);
				}else{
					$('#userMessage').html('User added successfully.');
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

tj.sendUserEmail = function(userId){
		        
        $.ajax({
            url: 'inc/data.php?req=newuseremail',
            data: {
				userId:userId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
			}
		})
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
		   $('#forgot').modal('hide');
		   alert('Please check your email for your temporary password.');
		   
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
				if (response.data.showArchive ==1){
				document.getElementById("showArchive").checked = true; 
				}else{
				document.getElementById("showArchive").checked = false;
				}
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

tj.edittextStatus1 = function(recordId) {
        
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
  
  tj.updateData = function() {
	var startDate = $('#updatestartDate').val();
	var endDate = $('#updateendDate').val();
	var uosValue = $('#editunitprodValueMgr').val();
	var deptId = $('#unitIdMgr').val();
	//console.log('start:',startDate);
	//console.log('end:',endDate);
	//console.log('uos:',uosValue);
	//console.log('deptId:',deptId);
	
	if(startDate.length>0 && endDate.length>0){
		bootbox.confirm({
        message:"Are you sure you want to update all data in this date range?",
        backdrop:true,
        callback:function (result) {
        if (result) {
		$.ajax({
            url:'inc/data.php?req=updateData',
            data:{
				startDate: startDate,
				endDate: endDate,
				uosValue: uosValue,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#updateDataModal').modal('hide');
				document.getElementById("updatestartDate").value = "";
				document.getElementById("updateendDate").value = "";
				if(response.message == true){
					bootbox.alert('Records Successfully Updated.');
				}else{
					bootbox.alert('No Records Updated.');
				}
				}
			})
			}
		}
		}).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
		});
	}else{
		bootbox.alert('Start Date and End Date are required.');
	}
      
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
	var showArchive = $('#showArchive:checked').val();
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
				desc6:desc6,
				showArchive:showArchive
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
				
			bootbox.alert('Text Alerts have been activated.  You will receive a confirmation text shortly.  Please select which alerts you want to receive and when you want to receive them.  <br><br>Message and data rates may apply.  Reply STOP to Cancel or update your User Profile.');	
                //$('#confirm').modal('toggle');
            }
        });
    };
	
tj.optin2 = function() {
        var mobile = $('#activateMobile').val();
		var curmobile = $('#currentMobile').val();
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
		
		
	if(mobile.length !=10){
    bootbox.alert('Mobile Number must be 10 digits only (ie. 2145551234).');
	return;
	}
	if(mobile!=curmobile){
    var newMobile = 1;
	}else{
	var newMobile = 0;	
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
				pause: pause,
				newMobile: newMobile
            },
            success: function(data) {
			$('#activate').modal('hide');
			bootbox.alert('Text Alerts have been activated.  You will receive a confirmation text shortly.  Message and data rates may apply.  Reply STOP to Cancel or update your User Profile.');	
            
            }
        });
    };
	
tj.demoMode = function() {
        var id = $('#activateuserId').val();
		var demo = $('#demo').is(':checked') ? 1 : 0;
	
		$.ajax({
            url: 'inc/data.php?req=demomode',
			dataType: 'json',
            method: 'post',
            data: {
				id: id,
				demo: demo
            },
            success: function(data) {
			bootbox.alert('You will need to log out and log back in for Demo Mode to take effect.');	
            
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
	//var grantText = $('#grantText').val();
     
        $.ajax({
            url:'inc/data.php?req=addtextResource',
            data:{
                deptId:deptId,
				userId:userId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.message == false){
				alert('This User already has access to this Unit.  Go to the Users tab to make updates.');
				}
				document.getElementById("textuserId").selectedIndex = "0";
				//document.getElementById("grantText").selectedIndex = "0";
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
        "order": [[5,'desc'],[0,'asc']],
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
		"pageLength": 50,
        "columns": [
            { "data": "Name" },
			{ "data": "Category" },
            { "data": "Value" },
			{ "data": "Manager" },
			{ "data": "Number" },
			{ "data": "Active" }
        ],
		"columnDefs": [
						{"visible": false, "targets": [5] }
        ],
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
				$('#unitCategory').val(response.data.unitCategory);
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
		$('#getLinked').empty();
		$('#resourceTableMgr').DataTable().destroy();
		$('#textTableMgr').DataTable().destroy();
		//console.log('deptId2 ', deptId);
		//console.log('role2 ', Role);
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
				var linklength = response.linklist.length;
				$("#getLinked").append($('<option>', {value: '0',text: 'Not Linked'}));
				for (var i = 0; i < linklength; i++) {
				$("#getLinked").append($('<option>', {value: response.linklist[i].deptId,text: response.linklist[i].deptName}));
				}
				var e1 = document.getElementById('useEscalations');	
		
				//var a3 = document.getElementById('nursing1');
				var a4 = document.getElementById('nursing3');
				var a5 = document.getElementById('UOSskill');
				var a6 = document.getElementById('skill');
				var a7 = document.getElementById('PTcount');
				//var a7 = document.getElementById('budget');
				var a8 = document.getElementById('uosDescription');
				
				var a10 = document.getElementById('unitList');
				var a11 = document.getElementById('unitDetails');
				var a12 = document.getElementById('nursing4');
				
				var g0 = document.getElementById('grid0');
				var g1 = document.getElementById('grid1');
				var g2 = document.getElementById('grid2');
				var g3 = document.getElementById('grid3');
				var g4 = document.getElementById('grid4');
				var g5 = document.getElementById('grid5');
				
				var s3 = document.getElementById('showprod3');
				var s4 = document.getElementById('showprod4');
				var s5 = document.getElementById('showprod5');
				var s6 = document.getElementById('showprod6');
				var s7 = document.getElementById('showprod7');
				var s8 = document.getElementById('showprod8');
				
				var v1 = document.getElementById('gridvar1');
				var v3 = document.getElementById('gridvar3');
				var v4 = document.getElementById('gridvar4');	
				
				var r1 = document.getElementById('rnthresh');
				var r2 = document.getElementById('roundrnhide');
				var r3 = document.getElementById('gthresh');
				var r4 = document.getElementById('hrsthresh');
				var r5 = document.getElementById('minStaff');
				
				//var t9 = document.getElementById('showVisit');
				var a9 = document.getElementById('censusTime');
				var a13 = document.getElementById('defaultUOS');
				var a14 = document.getElementById('useEAS');
				//var a15 = document.getElementById('ptFlow');
				var gr1 = document.getElementById('gr1');
				var gr2 = document.getElementById('gr2');
				var gr3 = document.getElementById('gr3');
				var gr4 = document.getElementById('gr4');
				var gr6 = document.getElementById('gr6');
				var gr7 = document.getElementById('gr7');
	
				var measure = response.data.prodMeasure;
				/*
				if(response.data.transferPortal==1){
				a15.style.display='';	
				}else{
				a15.style.display='none';		
				}
				*/
			
				
				if (deptId>0){
					a11.style.display='';
					a10.style.display='none';
				}else{
					a11.style.display='none';
					a10.style.display='';
				}
				/*
				if (response.role==9 || response.role==11) {
					document.getElementById("halert").disabled = false;
					$('#halert').val(response.data.houseAlert);
					}else{
					document.getElementById("halert").disabled = true;
					$('#halert').val(response.data.houseAlert);
					}
				*/				
				if (response.data.prodMeasure==2) {
				a4.style.display='none';
				a7.style.display='none';
				a12.style.display='none';
				g1.style.display='';
				g4.style.display='';
				g5.style.display='none';
				g0.style.display='none';
				g2.style.display='none';
				g3.style.display='none';
				s3.style.display='';
				s4.style.display='';
				s5.style.display='none';
				s6.style.display='';
				s7.style.display='none';
				s8.style.display='';
				v1.style.display='none';
				v3.style.display='none';
				v4.style.display='';
				r1.style.display='none';
				r2.style.display='none';
					if(response.data.showProdWHP==6 || response.data.rnVariance==2){
					r3.style.display='';
					}else{
					r3.style.display='none';
					}
				r4.style.display='';
				r5.style.display='none';
				gr1.style.display='none';
				gr2.style.display='';
				gr3.style.display='';
				gr4.style.display='none';
				gr6.style.display='none';
				gr7.style.display='none';
				e1.style.display='none';
				
				a6.style.display='';
				a5.style.display='';
				a9.style.display='none';
				}else{
				a7.style.display='';
				a12.style.display='';
				a4.style.display='';
				g1.style.display='none';
				g4.style.display='none';
				g5.style.display='';
				g0.style.display='';
				g2.style.display='';
				g3.style.display='';
				s3.style.display='';
				s4.style.display='none';
				s5.style.display='';
				s6.style.display='';
				s7.style.display='';
				s8.style.display='none';
				v1.style.display='';
				v3.style.display='';
				v4.style.display='none';
				r1.style.display='';
				r2.style.display='';
				r3.style.display='';
				r4.style.display='none';
				r5.style.display='';
				gr1.style.display='';
				gr2.style.display='';
				gr3.style.display='none';
				gr4.style.display='';
				gr6.style.display='';
				gr7.style.display='';
				e1.style.display='';
				a5.style.display='none';
				a6.style.display='none';
				a9.style.display='';	
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
			
				if (response.data.showArchive ==1){
				document.getElementById("showArchive").checked = true; 
				}else{
				document.getElementById("showArchive").checked = false;
				}
				if (response.data.ptTotal2 ==1){
				document.getElementById("ptuos2").checked = true; 
				}else{
				document.getElementById("ptuos2").checked = false;
				}
				if (response.data.ptTotal3 ==1){
				document.getElementById("ptuos3").checked = true; 
				}else{
				document.getElementById("ptuos3").checked = false;
				}
				if (response.data.ptTotal4 ==1){
				document.getElementById("ptuos4").checked = true; 
				}else{
				document.getElementById("ptuos4").checked = false;
				}
				$('#ptuosdesc').val(response.data.patientTotalDesc);
				$('#ptuosdesc2').val(response.data.patientTotalDesc2);
				$('#ptuosdesc3').val(response.data.patientTotalDesc3);
				$('#ptuosdesc4').val(response.data.patientTotalDesc4);
				$('#editunitNameMgr').val(response.data.unitName);
				$('#shiftDept').html(response.data.unitName);
				$('#activeUnit').val(response.data.active);
				$('#editunitCategory').val(response.data.unitCategory);
				$('#serviceLine').val(response.data.category);
				$('#addEscalations').val(response.data.escalations);
				$('#editunitNumberMgr').val(response.data.unitId);
				$('#daydataTitle').val(response.data.dayDataTitle);
				$('#deptIdunit').html(response.data.id);
                $('#unitIdMgr').val(response.data.id);
			
				
				$('#chargeDefault').val(response.data.charge);
				$('#useHuddle').val(response.data.useHuddle);
				//$('#shiftsDay').val(response.data.shiftsperDay);
				$('#shiftsOrig').val(response.data.shiftsperDay);
                $('#editunitTargetMgr').val(response.data.target);	
				$('#editunitBedsMgr').val(response.data.totalbeds);
				$('#editunituosDescMgr').val(response.data.prodTitle);
				$('#editunitprodMeasureMgr').val(response.data.prodMeasure);
				$('#prodDesc').html(response.data.prodDesc);
				$('#editunitprodValueMgr').val(response.data.hppd);
				
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
				
				$('#editcensusShiftMgr').val(response.data.censusShift);
				$('#showVisits').val(response.data.showVisits);
	            $('#UnitsTableMgr').DataTable().search('').draw();
				$('#descConfig1Mgr').val(response.data.desc1);
				$('#descConfig2Mgr').val(response.data.desc2);
				$('#descConfig3Mgr').val(response.data.desc3);
				$('#descConfig4Mgr').val(response.data.desc4);
				$('#descConfig5Mgr').val(response.data.desc5);
				$('#descConfig6Mgr').val(response.data.desc6);
				
				$('#one2oneValue').val(response.data.oneto1Acuity);
				$('#one2twoValue').val(response.data.oneto2Acuity);
				$('#one2threeValue').val(response.data.oneto3Acuity);
				$('#one2fourValue').val(response.data.oneto4Acuity);
				$('#one2fiveValue').val(response.data.oneto5Acuity);
				$('#one2sixValue').val(response.data.oneto6Acuity);
				
				//console.log('oneto2',response.data.oneto2Acuity);
				
				$('#trackdesc1').val(response.data.track1Desc);
				$('#rnDisplay').val(response.data.rnVariance);
				$('#countcharge').val(response.data.countcharge);
				$('#countcharge').val(response.data.countcharge);
				console.log('unitId3: ',deptId);
				var showrnObj = JSON.parse(response.data.showrntest);
				$('#showrntest').val(showrnObj);
				var linkedObj = JSON.parse(response.data.linkedUnits);
				$('#getLinked').val(linkedObj);
				$('#linkOrig').val(response.data.linkedUnits);
				$('#roundrn').val(response.data.roundrn);
				$('#minRN').val(response.data.minRN);
				$('#showEpic').val(response.data.showEpic);
				$('#trackdesc2').val(response.data.track2Desc);
				$('#trackdesc3').val(response.data.track3Desc);
				$('#trackdesc4').val(response.data.track4Desc);
				$('#typetrack1').val(response.data.typetrack1);
				$('#typetrack2').val(response.data.typetrack2);
				$('#typetrack3').val(response.data.typetrack3);
				$('#typetrack4').val(response.data.typetrack4);
				
				$('#chargedesc').val(response.data.chargeDesc);
				$('#nursedesc').val(response.data.nurseDesc);
				$('#nurse1desc').val(response.data.nurse1Desc);
				$('#nurse2desc').val(response.data.nurse2Desc);
				$('#secdesc').val(response.data.secLabel);
				$('#techdesc').val(response.data.techLabel);
				//$('#tech1desc').val(response.data.other1Desc);
				$('#sitterdesc').val(response.data.sittersNEWDesc);
				$('#other1desc').val(response.data.other1Desc);
				$('#other2desc').val(response.data.other2Desc);
				$('#other3desc').val(response.data.other3Desc);
				
				$('#uosDefault').val(response.data.uosDefault);
				
				if (response.data.countcharge ==1){
				document.getElementById("chargeRN").checked = true; 
				}else{
				document.getElementById("chargeRN").checked = false;
				}
				
				if (response.data.rnCount ==1){
				document.getElementById("nurseRN").checked = true; 
				}else{
				document.getElementById("nurseRN").checked = false;
				}
				
				if (response.data.rn1Count ==1){
				document.getElementById("nurse1RN").checked = true; 
				}else{
				document.getElementById("nurse1RN").checked = false;
				}
				
				if (response.data.rn2Count ==1){
				document.getElementById("nurse2RN").checked = true; 
				}else{
				document.getElementById("nurse2RN").checked = false;
				}
				
				if (response.data.gridOpt1 ==0){
				document.getElementById("gridOpt1").checked = true; 
				}else{
				document.getElementById("gridOpt1").checked = false;
				}
				if (response.data.other1Calc==1){
				document.getElementById("other1calc").checked = false;  
				}else{
				document.getElementById("other1calc").checked = true;
				}
				
				if (response.data.gridOpt2 ==0){
				document.getElementById("gridOpt2").checked = true; 
				}else{
				document.getElementById("gridOpt2").checked = false;
				}
				
				if (response.data.gridOpt3 ==0){
				document.getElementById("gridOpt3").checked = true; 
				}else{
				document.getElementById("gridOpt3").checked = false;
				}
								
				if (response.data.oneto1 ==1){
				document.getElementById("one2oneCheckedMgr").checked = true; 
				}else{
				document.getElementById("one2oneCheckedMgr").checked = false;
				}
				if (response.data.trackdc ==1){
				document.getElementById("dc1").checked = true; 
				}else{
				document.getElementById("dc1").checked = false;
				}
				if (response.data.showEpic ==1){
				document.getElementById("showEpic").checked = true; 
				}else{
				document.getElementById("showEpic").checked = false;
				}
				console.log('eas: ',response.data.eas);
				if (response.data.eas ==1){ 
				a14.style.display='';
				document.getElementById("showEpic").disabled = true;
				}else{
				a14.style.display='none';
				document.getElementById("showEpic").disabled = false;
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
				$('#skilldesc6').val(response.data.skilldesc6);
				$('#skilldesc7').val(response.data.skilldesc7);
				$('#skilldesc8').val(response.data.skilldesc8);
				$('#skilldesc9').val(response.data.skilldesc9);
				$('#skilldesc10').val(response.data.skilldesc10);
				
				
				$('#uosdesc1').val(response.data.customDesc);
				$('#uosdesc2').val(response.data.customDesc2);
				$('#uosdesc3').val(response.data.customDesc3);
				$('#uosdesc4').val(response.data.customDesc4);
				
				if (response.data.customWHP ==1){
				document.getElementById("uos1").checked = true; 
				}else{
				document.getElementById("uos1").checked = false;
				}				
				if (response.data.customWHP2 ==1){
				document.getElementById("uos2").checked = true; 
				}else{
				document.getElementById("uos2").checked = false;
				}				
				if (response.data.customWHP3 ==1){
				document.getElementById("uos3").checked = true; 
				}else{
				document.getElementById("uos3").checked = false;
				}				
				if (response.data.customWHP4 ==1){
				document.getElementById("uos4").checked = true; 
				}else{
				document.getElementById("uos4").checked = false;
				}
				
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
				if (response.data.skill6 ==1){
				document.getElementById("skill6").checked = true; 
				}else{
				document.getElementById("skill6").checked = false;
				}
				if (response.data.skill7 ==1){
				document.getElementById("skill7").checked = true; 
				}else{
				document.getElementById("skill7").checked = false;
				}
				if (response.data.skill8 ==1){
				document.getElementById("skill8").checked = true; 
				}else{
				document.getElementById("skill8").checked = false;
				}
				if (response.data.skill9 ==1){
				document.getElementById("skill9").checked = true; 
				}else{
				document.getElementById("skill9").checked = false;
				}
				if (response.data.skill10 ==1){
				document.getElementById("skill10").checked = true; 
				}else{
				document.getElementById("skill10").checked = false;
				}
				//if (response.data.hasGrid){
				if(response.data.hasGrid && response.data.useGrid!=5){
					$('#grid').val(response.data.useGrid);
					document.getElementById("grid").disabled = false;
					//r1.style.display='';
				}else if(response.data.hasGrid && response.data.useGrid==5){
					$('#grid').val('0');
					document.getElementById("grid").disabled = false;
					//r1.style.display='';
				}else{
					$('#grid').val('5');
					document.getElementById("grid").disabled = true;
					//r1.style.display='none';
				}
				console.log('unitId4: ',deptId);
				
				if(response.role!=11){
					document.getElementById("grid").disabled = true;
					document.getElementById("activeUnit").disabled = true;
					document.getElementById("showArchive").disabled = true;
					document.getElementById("catButton").disabled = true;
					document.getElementById("serviceLine").disabled = true;
					document.getElementById("editunitCategory").disabled = true;
					document.getElementById("editunitNameMgr").disabled = true;
					document.getElementById("editunitNumberMgr").disabled = true;
					//document.getElementById("daydataTitle").disabled = true;
					document.getElementById("addEscalations").disabled = true;
					document.getElementById("avgWage").disabled = true;
					document.getElementById("unitDirector").disabled = true;
					document.getElementById("unitManagerMgr").disabled = true;
					document.getElementById("daysWk").disabled = true;
					document.getElementById("commentTitle1").disabled = true;
					document.getElementById("editunitBedsMgr").disabled = true;
					document.getElementById("chargeDefault").disabled = true;
					document.getElementById("useHuddle").disabled = true;
					document.getElementById("churn").disabled = true;
					document.getElementById("editunituosDescMgr").disabled = true;
					document.getElementById("editunitprodValueMgr").disabled = true;
					document.getElementById("showVisits").disabled = true;
					document.getElementById("thresholdHigh").disabled = true;
					document.getElementById("thresholdLow").disabled = true;
					document.getElementById("rnThreshold").disabled = true;
					document.getElementById("hrsThreshold").disabled = true;
					document.getElementById("gThreshold").disabled = true;
					document.getElementById("roundrn").disabled = true;
					document.getElementById("minRN").disabled = true;
					document.getElementById("rnDisplay").disabled = true;
					document.getElementById("showProd").disabled = true;
					document.getElementById("showrntest").disabled = true;
					document.getElementById("getLinked").disabled = true;
					document.getElementById("editunitTargetMgr").disabled = true;
					document.getElementById("ptuosdesc").disabled = true;
					document.getElementById("ptuos3").disabled = true;
					document.getElementById("ptuosdesc3").disabled = true;
					document.getElementById("ptuos2").disabled = true;
					document.getElementById("ptuosdesc2").disabled = true;
					document.getElementById("ptuos4").disabled = true;
					document.getElementById("ptuosdesc4").disabled = true;
					document.getElementById("one2oneCheckedMgr").disabled = true;
					document.getElementById("one2oneValue").disabled = true;
					document.getElementById("descConfig1Mgr").disabled = true;
					document.getElementById("one2twoCheckedMgr").disabled = true;
					document.getElementById("one2twoValue").disabled = true;
					document.getElementById("descConfig2Mgr").disabled = true;
					document.getElementById("one2threeCheckedMgr").disabled = true;
					document.getElementById("one2threeValue").disabled = true;
					document.getElementById("descConfig3Mgr").disabled = true;
					document.getElementById("one2fourCheckedMgr").disabled = true;
					document.getElementById("one2fourValue").disabled = true;
					document.getElementById("descConfig4Mgr").disabled = true;
					document.getElementById("one2fiveCheckedMgr").disabled = true;
					document.getElementById("one2fiveValue").disabled = true;
					document.getElementById("descConfig5Mgr").disabled = true;
					document.getElementById("one2sixCheckedMgr").disabled = true;
					document.getElementById("one2sixValue").disabled = true;
					document.getElementById("descConfig6Mgr").disabled = true;
					document.getElementById("showEpic").disabled = true;
					document.getElementById("dc1").disabled = true;
					//document.getElementById("ptdesc1").disabled = true;
					document.getElementById("ptdesc2").disabled = true;
					document.getElementById("ptdesc3").disabled = true;
					document.getElementById("ptdesc4").disabled = true;
					document.getElementById("chargeRN").disabled = true;
					document.getElementById("chargedesc").disabled = true;
					document.getElementById("nurse1RN").disabled = true;
					document.getElementById("nurse1desc").disabled = true;
					document.getElementById("techdesc").disabled = true;
					document.getElementById("other1desc").disabled = true;
					document.getElementById("gridOpt1").disabled = true;
					document.getElementById("nurseRN").disabled = true;
					document.getElementById("nursedesc").disabled = true;
					document.getElementById("nurse2RN").disabled = true;
					document.getElementById("nurse2desc").disabled = true;
					document.getElementById("secdesc").disabled = true;
					document.getElementById("sitterdesc").disabled = true;
					document.getElementById("gridOpt2").disabled = true;
					document.getElementById("gridOpt3").disabled = true;
					document.getElementById("other3desc").disabled = true;
					document.getElementById("uos1").disabled = true;
					document.getElementById("uosdesc1").disabled = true;
					document.getElementById("uos3").disabled = true;
					document.getElementById("uosdesc3").disabled = true;
					document.getElementById("uos2").disabled = true;
					document.getElementById("uosdesc2").disabled = true;
					document.getElementById("uos4").disabled = true;
					document.getElementById("uosdesc4").disabled = true;
					document.getElementById("uosDefault").disabled = true;
					document.getElementById("skill1").disabled = true;
					document.getElementById("skilldesc1").disabled = true;
					document.getElementById("skill3").disabled = true;
					document.getElementById("skilldesc3").disabled = true;
					document.getElementById("skill5").disabled = true;
					document.getElementById("skilldesc5").disabled = true;
					document.getElementById("skill7").disabled = true;
					document.getElementById("skilldesc7").disabled = true;
					document.getElementById("skill9").disabled = true;
					document.getElementById("skilldesc9").disabled = true;
					document.getElementById("skill2").disabled = true;
					document.getElementById("skilldesc2").disabled = true;
					document.getElementById("skill4").disabled = true;
					document.getElementById("skilldesc4").disabled = true;
					document.getElementById("skill6").disabled = true;
					document.getElementById("skilldesc6").disabled = true;
					document.getElementById("skill8").disabled = true;
					document.getElementById("skilldesc8").disabled = true;
					document.getElementById("skill10").disabled = true;
					document.getElementById("skilldesc10").disabled = true;
					document.getElementById("trackdesc1").disabled = true;
					document.getElementById("trackdesc2").disabled = true;
					document.getElementById("trackdesc3").disabled = true;
					document.getElementById("trackdesc4").disabled = true;
					document.getElementById("prodResources").disabled = true;
					document.getElementById("res1").disabled = true;
					document.getElementById("textTableMgr").disabled = true;
					document.getElementById("unitButton").disabled = true;
				}else{
					document.getElementById("grid").disabled = false;
					document.getElementById("activeUnit").disabled = false;
					document.getElementById("showArchive").disabled = false;
					document.getElementById("catButton").disabled = false;
					document.getElementById("serviceLine").disabled = false;
					document.getElementById("editunitCategory").disabled = false;
					document.getElementById("editunitNameMgr").disabled = false;
					document.getElementById("editunitNumberMgr").disabled = false;
					//document.getElementById("daydataTitle").disabled = false;
					document.getElementById("addEscalations").disabled = false;
					document.getElementById("avgWage").disabled = false;
					document.getElementById("unitDirector").disabled = false;
					document.getElementById("unitManagerMgr").disabled = false;
					document.getElementById("daysWk").disabled = false;
					document.getElementById("commentTitle1").disabled = false;
					document.getElementById("editunitBedsMgr").disabled = false;
					document.getElementById("chargeDefault").disabled = false;
					document.getElementById("useHuddle").disabled = false;
					document.getElementById("churn").disabled = false;
					document.getElementById("editunituosDescMgr").disabled = true;
					document.getElementById("editunitprodValueMgr").disabled = true;
					document.getElementById("showVisits").disabled = false;
					document.getElementById("thresholdHigh").disabled = false;
					document.getElementById("thresholdLow").disabled = false;
					document.getElementById("rnThreshold").disabled = false;
					document.getElementById("hrsThreshold").disabled = false;
					document.getElementById("gThreshold").disabled = false;
					document.getElementById("roundrn").disabled = false;
					document.getElementById("minRN").disabled = false;
					document.getElementById("rnDisplay").disabled = false;
					document.getElementById("showProd").disabled = false;
					document.getElementById("showrntest").disabled = false;
					document.getElementById("getLinked").disabled = false;
					document.getElementById("editunitTargetMgr").disabled = false;
					document.getElementById("ptuosdesc").disabled = false;
					document.getElementById("ptuos3").disabled = false;
					document.getElementById("ptuosdesc3").disabled = false;
					document.getElementById("ptuos2").disabled = false;
					document.getElementById("ptuosdesc2").disabled = false;
					document.getElementById("ptuos4").disabled = false;
					document.getElementById("ptuosdesc4").disabled = false;
					document.getElementById("one2oneCheckedMgr").disabled = false;
					document.getElementById("one2oneValue").disabled = false;
					document.getElementById("descConfig1Mgr").disabled = false;
					document.getElementById("one2twoCheckedMgr").disabled = false;
					document.getElementById("one2twoValue").disabled = false;
					document.getElementById("descConfig2Mgr").disabled = false;
					document.getElementById("one2threeCheckedMgr").disabled = false;
					document.getElementById("one2threeValue").disabled = false;
					document.getElementById("descConfig3Mgr").disabled = false;
					document.getElementById("one2fourCheckedMgr").disabled = false;
					document.getElementById("one2fourValue").disabled = false;
					document.getElementById("descConfig4Mgr").disabled = false;
					document.getElementById("one2fiveCheckedMgr").disabled = false;
					document.getElementById("one2fiveValue").disabled = false;
					document.getElementById("descConfig5Mgr").disabled = false;
					document.getElementById("one2sixCheckedMgr").disabled = false;
					document.getElementById("one2sixValue").disabled = false;
					document.getElementById("descConfig6Mgr").disabled = false;
					document.getElementById("showEpic").disabled = false;
					document.getElementById("dc1").disabled = false;
					//document.getElementById("ptdesc1").disabled = false;
					document.getElementById("ptdesc2").disabled = false;
					document.getElementById("ptdesc3").disabled = false;
					document.getElementById("ptdesc4").disabled = false;
					document.getElementById("chargeRN").disabled = false;
					document.getElementById("chargedesc").disabled = false;
					document.getElementById("nurse1RN").disabled = false;
					document.getElementById("nurse1desc").disabled = false;
					document.getElementById("techdesc").disabled = false;
					document.getElementById("other1desc").disabled = false;
					document.getElementById("gridOpt1").disabled = false;
					document.getElementById("nurseRN").disabled = false;
					document.getElementById("nursedesc").disabled = false;
					document.getElementById("nurse2RN").disabled = false;
					document.getElementById("nurse2desc").disabled = false;
					document.getElementById("secdesc").disabled = false;
					document.getElementById("sitterdesc").disabled = false;
					document.getElementById("gridOpt2").disabled = false;
					document.getElementById("gridOpt3").disabled = false;
					document.getElementById("other3desc").disabled = false;
					document.getElementById("uos1").disabled = false;
					document.getElementById("uosdesc1").disabled = false;
					document.getElementById("uos3").disabled = false;
					document.getElementById("uosdesc3").disabled = false;
					document.getElementById("uos2").disabled = false;
					document.getElementById("uosdesc2").disabled = false;
					document.getElementById("uos4").disabled = false;
					document.getElementById("uosdesc4").disabled = false;
					document.getElementById("uosDefault").disabled = false;
					document.getElementById("skill1").disabled = false;
					document.getElementById("skilldesc1").disabled = false;
					document.getElementById("skill3").disabled = false;
					document.getElementById("skilldesc3").disabled = false;
					document.getElementById("skill5").disabled = false;
					document.getElementById("skilldesc5").disabled = false;
					document.getElementById("skill7").disabled = false;
					document.getElementById("skilldesc7").disabled = false;
					document.getElementById("skill9").disabled = false;
					document.getElementById("skilldesc9").disabled = false;
					document.getElementById("skill2").disabled = false;
					document.getElementById("skilldesc2").disabled = false;
					document.getElementById("skill4").disabled = false;
					document.getElementById("skilldesc4").disabled = false;
					document.getElementById("skill6").disabled = false;
					document.getElementById("skilldesc6").disabled = false;
					document.getElementById("skill8").disabled = false;
					document.getElementById("skilldesc8").disabled = false;
					document.getElementById("skill10").disabled = false;
					document.getElementById("skilldesc10").disabled = false;
					document.getElementById("trackdesc1").disabled = false;
					document.getElementById("trackdesc2").disabled = false;
					document.getElementById("trackdesc3").disabled = false;
					document.getElementById("trackdesc4").disabled = false;
					document.getElementById("prodResources").disabled = false;
					document.getElementById("res1").disabled = false;
					document.getElementById("textTableMgr").disabled = false;
					document.getElementById("unitButton").disabled = false;
				}
				
				if(response.data.unitCategory!=4){
					document.getElementById("daydataTitle").disabled = true;
				}
				$('#thresholdLow').val(response.data.thresholdLow);
				$('#thresholdHigh').val(response.data.thresholdHigh);
				//$('#budgetValue').val(response.data.uosValue);
				//$('#inshiftProd').val(response.data.inshiftProd);
				$('#rnThreshold').val(response.data.rnThreshold);
				$('#hrsThreshold').val(response.data.hrsThreshold);
				$('#gThreshold').val(response.data.gThreshold);
				//$('#budgetMeasure').val(response.data.budgetMeasure);
				$('#churn').val(response.data.churn);
				
				$('#flow1').val(response.data.flow1);
				$('#flow2').val(response.data.flow2);
				$('#flow3').val(response.data.flow3);
				$('#flow4').val(response.data.flow4);
				$('#flow5').val(response.data.flow5);
				$('#flow6').val(response.data.flow6);
				$('#flow7').val(response.data.flow7);
				$('#flow8').val(response.data.flow8);
				$('#inflow').val(response.data.useFlow);
				$('#inflowAcuity').val(response.data.avgAcuity);
				
				$('#showProd').val(response.data.showProdWHP);
				//$('#showPlanned').val(response.data.showPlanned);
				$('#avgWage').val(response.data.avgWage);
				$('#daysWk').val(response.data.daysWk);
				$('#commentTitle1').val(response.data.actionPlan);
				$('#ptdesc1').val(response.data.desc7);
				$('#dashboard5').val(response.data.dash5);
				$('#ptdesc2').val(response.data.desc8);
				$('#ptdesc3').val(response.data.desc9);
				$('#ptdesc4').val(response.data.desc10);
				$('#shiftSelect').val(response.shifts);
				
				$('#currentTimes').html(response.shiftnames);
				$('#currentTimes1').html(response.shiftnames);
				$('#WHPvalue').val(response.data.customWHPvalue);
				$('#WHP2value').val(response.data.customWHP2value);
				$('#WHP3value').val(response.data.customWHP3value);
				$('#WHP4value').val(response.data.customWHP4value);
				$('#WHP5value').val(response.data.customWHP5value);
								
				tj.ConfigureGrid(deptId);
				//console.log('shifttest',shifttest);
				//console.log('shiftparse',shiftparse);
				}
        })
        
    }
	
tj.closeUnit = function() {
var a11 = document.getElementById('unitDetails');
var a10 = document.getElementById('unitList');
a10.style.display='';
a11.style.display='none';
}

tj.editTimes = function() {
	//var shifts = $('#reportTimes').val();
	//$('#shiftSelect').val(shifts);
	$('#updateShifts').modal('show');
}

tj.newTimes = function() {
	var shifts = $('#shiftSelect').val();
	$('#newTimes').html(shifts);
}

tj.updateShifts = function() {
	var deptId = $('#unitIdMgr').val();
	var shifts = $('#shiftSelect').val();
	//console.log('dept',deptId);
	//console.log('shifts',shifts);
	bootbox.confirm({
        message:"Update Shift Report Times?",
		backdrop:true,
        callback:function (result) {
			if (result) {
			$.ajax({
            url:'inc/data.php?req=updateshifts',
            data:{
                deptId:deptId,
				shifts:shifts
            },
            method:'POST',
            dataType:'json',
            success:function(response) {	
				bootbox.alert('Shift Times have been successfully updated and will populate the next day.');
				$('#currentTimes1').html(response.shiftnames);
				$('#updateShifts').modal('hide');
				//document.getElementById("shiftSelect").value = shifts;
				
				//tj.UnitsTable.ajax.reload(null,false);
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

tj.addGrid = function(deptId) {
//var grid = '';
//var dep = deptId;
//grid += '<input id="newgriddeptId" value="1" hidden>';
//$('#gridForm').empty().append(grid);
$('#griddeptid').val(deptId);
$('#addGrid').modal('toggle');
console.log('gridDept',deptId);
}
/*
tj.selectGrid = function(grd) {

if(grd==1){
var grid = '<input type="number" name="newgriddeptId" value="1" hidden>';
}else if(grd==2){
var grid = '<input type="number" name="newgriddeptId" value="2" hidden>';
}else{
var grid = '<input type="number" name="newgriddeptId" value="0" hidden>';
}	
$('#gridForm').empty().append(grid);

}
*/


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
		var active = $('#activeUnit').val();
		var unitCategory = $('#editunitCategory').val();
		var deptId = $('#unitIdMgr').val();
		var escalations = $('#addEscalations').val();
		var unitNumber = $('#editunitNumberMgr').val();
		var daydataTitle = $('#daydataTitle').val();
		var unitTarget = $('#editunitTargetMgr').val();
		var uosDesc = $('#editunituosDescMgr').val();
		var bedCount = $('#editunitBedsMgr').val();
		var serviceLine = $('#serviceLine').val();
		var prodMeasure = $('#editunitprodMeasureMgr').val();
		var value = $('#editunitprodValueMgr').val();
		var censusShift = $('#editcensusShiftMgr').val();
		//var unitType = $('#typeUnit').val();
		var showArchive = $('#showArchive:checked').val();
		
		//console.log('archive',showArchive);
		
		var desc1 = $('#descConfig1Mgr').val();
		var desc2 = $('#descConfig2Mgr').val();
		var desc3 = $('#descConfig3Mgr').val();
		var desc4 = $('#descConfig4Mgr').val();
		var desc5 = $('#descConfig5Mgr').val();
		var desc6 = $('#descConfig6Mgr').val();
		
		var calc1 = $('#one2oneValue').val();
		var calc2 = $('#one2twoValue').val();
		var calc3 = $('#one2threeValue').val();
		var calc4 = $('#one2fourValue').val();
		var calc5 = $('#one2fiveValue').val();
		var calc6 = $('#one2sixValue').val();
		/*
		var flow1 = $('#flow1').val();
		var flow2 = $('#flow2').val();
		var flow3 = $('#flow3').val();
		var flow4 = $('#flow4').val();
		var flow5 = $('#flow5').val();
		var flow6 = $('#flow6').val();
		var flow7 = $('#flow7').val();
		var flow8 = $('#flow8').val();
		*/
		//var houseAlert = $('#halert').val();
		
		var useFlow = $('#inflow').val();
		var avgAcuity = $('#inflowAcuity').val();
				
		if(desc1.length==0){
		var one2one = 0;
		}else{
		var one2one = $('#one2oneCheckedMgr:checked').val();
		}
		
		if(desc2.length==0){
		var one2two = 0;
		}else{
		var one2two = $('#one2twoCheckedMgr:checked').val();
		}
		
		if(desc3.length==0){
		var one2three = 0;
		}else{
		var one2three = $('#one2threeCheckedMgr:checked').val();
		}
		
		if(desc4.length==0){
		var one2four = 0;
		}else{
		var one2four= $('#one2fourCheckedMgr:checked').val();
		}
		
		if(desc5.length==0){
		var one2five = 0;
		}else{
		var one2five = $('#one2fiveCheckedMgr:checked').val();
		}
		
		if(desc6.length==0){
		var one2six = 0;
		}else{
		var one2six = $('#one2sixCheckedMgr:checked').val();
		}
				
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
		var skill6 = $('#skill6:checked').val();
		var skilldesc6 = $('#skilldesc6').val();
		var skill7 = $('#skill7:checked').val();
		var skilldesc7 = $('#skilldesc7').val();
		var skill8 = $('#skill8:checked').val();
		var skilldesc8 = $('#skilldesc8').val();
		var skill9 = $('#skill9:checked').val();
		var skilldesc9 = $('#skilldesc9').val();
		var skill10 = $('#skill10:checked').val();
		var skilldesc10 = $('#skilldesc10').val();
		var churn = $('#churn').val();
		var grid = $('#grid').val();
		
		var uos1 = $('#uos1:checked').val();
		var uosdesc1 = $('#uosdesc1').val();
		var uos2 = $('#uos2:checked').val();
		var uosdesc2 = $('#uosdesc2').val();
		var uos3 = $('#uos3:checked').val();
		var uosdesc3 = $('#uosdesc3').val();
		var uos4 = $('#uos4:checked').val();
		var uosdesc4 = $('#uosdesc4').val();
		
		var WHPvalue = $('#WHPvalue').val();
		var WHP2value = $('#WHP2value').val();
		var WHP3value = $('#WHP3value').val();
		var WHP4value = $('#WHP4value').val();
		var WHP5value = $('#WHP5value').val();
		
		var showProd = $('#showProd').val();
		var avgWage = $('#avgWage').val();
		var daysWk = $('#daysWk').val();
		var actionPlan = $('#commentTitle1').val();
		var chargeDefault = $('#chargeDefault').val();
		var useHuddle = $('#useHuddle').val();
		var thresholdLow = $('#thresholdLow').val();
		var thresholdHigh = $('#thresholdHigh').val();
		var u1 = document.getElementById('unitList');
		var u2 = document.getElementById('unitDetails');
		
		var trackdesc1 = $('#trackdesc1').val();
		var trackdesc2 = $('#trackdesc2').val();
		var trackdesc3 = $('#trackdesc3').val();
		var trackdesc4 = $('#trackdesc4').val();
		var typetrack1 = $('#typetrack1').val();
		var typetrack2 = $('#typetrack2').val();
		var typetrack3 = $('#typetrack3').val();
		var typetrack4 = $('#typetrack4').val();
		
		var dc1 = $('#dc1').is(':checked') ? 1 : 0;
		var showEpic = $('#showEpic').is(':checked') ? 1 : 0;
		//var dc1 = $('#dc1:checked').val();
		
		var rnThreshold = $('#rnThreshold').val();
		var hrsThreshold = $('#hrsThreshold').val();
		var gThreshold = $('#gThreshold').val();
		var rndisplay = $('#rnDisplay').val();
		var countcharge = $('#countcharge').val();
		//var showrn = $('#showrn').val();
		
		var roundrn = $('#roundrn').val();
		var minRN = $('#minRN').val();
		//var showEpic = $('#showEpic').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		
		var showrnNew = $('#showrntest').val();
		let result = String(showrnNew).substring(0,1);
		if(result=='0'){
		var showrntest = '["0"]';
		}else{
		var showrntest = JSON.stringify(showrnNew);
		}
		
		var linkOrig = $('#linkOrig').val();
		var linkedNew = $('#getLinked').val();
		var linklength = linkedNew.length;
		let linkedresult = String(linkedNew).substring(0,1);
		if(linkedresult=='0'){
		var linkedUnits = '["0"]';
		}else{
		var linkedUnits = JSON.stringify(linkedNew);
		}
		linkDept = true;
		var linkDept = linkedUnits.includes(deptId);
		
		var chargeDesc = $('#chargedesc').val();
		var nurseDesc = $('#nursedesc').val();
		var nurse1Desc = $('#nurse1desc').val();
		var nurse2Desc = $('#nurse2desc').val();
		var secLabel = $('#secdesc').val();
		var techLabel = $('#techdesc').val();
		var other1Desc = $('#other1desc').val();
		var sittersNEWDesc = $('#sitterdesc').val();
		var other2Desc = $('#other2desc').val();
		var other3Desc = $('#other3desc').val();
		var uosDefault = $('#uosDefault').val();
		var showVisits = $('#showVisits').val();
		
		var chargeRN = $('#chargeRN').is(':checked') ? 1 : 0;
		var nurseRN = $('#nurseRN').is(':checked') ? 1 : 0;
		var nurse1RN = $('#nurse1RN').is(':checked') ? 1 : 0;
		var nurse2RN = $('#nurse2RN').is(':checked') ? 1 : 0;
		var gridOpt1 = $('#gridOpt1').is(':checked') ? 0 : 1;
		var gridOpt2 = $('#gridOpt2').is(':checked') ? 0 : 1;
		var gridOpt3 = $('#gridOpt3').is(':checked') ? 0 : 1;
		var other1calc = $('#other1calc').is(':checked') ? 0 : 1;
		
		var desc7 = $('#ptdesc1').val();
		var dash5 = $('#dashboard5').val();
		var desc8 = $('#ptdesc2').val();
		var desc9 = $('#ptdesc3').val();
		var desc10 = $('#ptdesc4').val();
		
		
		if(desc7.length==0){
			var oneto7 = 0;
		}else{
			var oneto7 = 1;
		}
		if(desc8.length==0){
			var oneto8 = 0;
		}else{
			var oneto8 = 1;
		}
		if(desc9.length==0){
			var oneto9 = 0;
		}else{
			var oneto9 = 1;
		}
		if(desc10.length==0){
			var oneto10 = 0;
		}else{
			var oneto10 = 1;
		}
		var ptuos2 = $('#ptuos2').is(':checked') ? 1 : 0;
		var ptuos3 = $('#ptuos3').is(':checked') ? 1 : 0;
		var ptuos4 = $('#ptuos4').is(':checked') ? 1 : 0;
		var ptuosdesc = $('#ptuosdesc').val();
		var ptuosdesc2 = $('#ptuosdesc2').val();
		var ptuosdesc3 = $('#ptuosdesc3').val();
		var ptuosdesc4 = $('#ptuosdesc4').val();
		
		if(parseInt(newDir)>0 && newDir==newMgr){
		$('#unitManagerMgr').val('0');
		$('#unitDirector').val('0');
		}
		if(parseInt(newDir)==0 && parseInt(dirOrig)>0 && dirOrig==newMgr){
		$('#unitManagerMgr').val('0');	
		}
		if(parseInt(newMgr)==0 && parseInt(mgrOrig)>0 && mgrOrig==newDir){
		$('#unitDirector').val('0');	
		}
				
		
		if((parseInt(newDir)>0 && newDir==newMgr) || (parseInt(newDir)==0 && parseInt(dirOrig)>0 && dirOrig==newMgr) || (parseInt(newMgr)==0 && parseInt(mgrOrig)>0 && mgrOrig==newDir)){
		bootbox.alert('The Manager and Director cannot be the same person.');
		return;
		}
		
		if(linklength>1 && linkedUnits.includes('"0"')){
		bootbox.alert('You cannot include NOT LINKED in your Link Unit Selections.');
		return;
		}
		
		if(linklength==1 && !linkedUnits.includes('"0"')){
		bootbox.alert('You must select more than 1 unit to link them.');
		return;	
		}
		
		if(linkDept === false && linklength>1){
		bootbox.alert('To link units you must also include this unit in your link selections.');
		return;
		}
		
				
		$.ajax({
            url:'inc/data.php?req=updateUnitMgr',
            data:{
				chargeDesc: chargeDesc,
				nurseDesc: nurseDesc,
				nurse1Desc: nurse1Desc,
				nurse2Desc: nurse2Desc,
				secLabel: secLabel,
				techLabel: techLabel,
				other1Desc: other1Desc,
				sittersNEWDesc: sittersNEWDesc,
				other2Desc: other2Desc,
				other3Desc: other3Desc,
				chargeRN: chargeRN,
				nurseRN: nurseRN,
				nurse1RN: nurse1RN,
				nurse2RN: nurse2RN,
                name: name,
				deptId: deptId,
				unitTarget: unitTarget,
                unitNumber: unitNumber,
				bedCount: bedCount,
				prodMeasure: prodMeasure,
				value: value,
				censusShift: censusShift,
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
				skill6: skill6,
				skill7: skill7,
				skill8: skill8,
				skill9: skill9,
				skill10: skill10,
				uos1: uos1,
				uos2: uos2,
				uos3: uos3,
				uos4: uos4,
				skilldesc1: skilldesc1,
				skilldesc2: skilldesc2,
				skilldesc3: skilldesc3,
				skilldesc4: skilldesc4,
				skilldesc5: skilldesc5,
				skilldesc6: skilldesc6,
				skilldesc7: skilldesc7,
				skilldesc8: skilldesc8,
				skilldesc10: skilldesc10,
				skilldesc9: skilldesc9,
				uosdesc1: uosdesc1,
				uosdesc2: uosdesc2,
				uosdesc3: uosdesc3,
				uosdesc4: uosdesc4,
				thresholdLow: thresholdLow,
				thresholdHigh: thresholdHigh,
				churn: churn,
				uosDesc: uosDesc,
				serviceLine: serviceLine,
				rnThreshold: rnThreshold,
				hrsThreshold: hrsThreshold,
				gThreshold: gThreshold,
				charge: chargeDefault,
				useHuddle: useHuddle,
				showProd: showProd,
				avgWage: avgWage,
				active: active,
				daysWk: daysWk,
				unitCategory: unitCategory,
				trackdesc1: trackdesc1,
				trackdesc2: trackdesc2,
				trackdesc3: trackdesc3,
				trackdesc4: trackdesc4,
				dc1: dc1,
				rndisplay: rndisplay,
				countcharge: countcharge,
				currentTime: currentTime,
				grid: grid,
				showrntest: showrntest,
				roundrn: roundrn,
				minRN: minRN,
				typetrack1: typetrack1,
				typetrack2: typetrack2,
				typetrack3: typetrack3,
				typetrack4: typetrack4,
				uosDefault: uosDefault,
				actionPlan: actionPlan,
				showVisits: showVisits,
				desc7: desc7,
				oneto7: oneto7,
				desc8: desc8,
				oneto8: oneto8,
				desc9: desc9,
				oneto9: oneto9,
				desc10: desc10,
				oneto10: oneto10,
				showEpic: showEpic,
				useFlow: useFlow,
				avgAcuity: avgAcuity,
				linkedUnits: linkedUnits,
				linkOrig: linkOrig,
				linkedNew: linkedNew,
				linkedresult: linkedresult,
				calc1: calc1,
				calc2: calc2,
				calc3: calc3,
				calc4: calc4,
				calc5: calc5,
				calc6: calc6,
				showArchive: showArchive,
				WHPvalue: WHPvalue,
				WHP2value: WHP2value,
				WHP3value: WHP3value,
				WHP4value: WHP4value,
				WHP5value: WHP5value,
				ptuos2: ptuos2,
				ptuos3: ptuos3,
				ptuos4: ptuos4,
				ptuosdesc: ptuosdesc,
				ptuosdesc2: ptuosdesc2,
				ptuosdesc3: ptuosdesc3,
				ptuosdesc4: ptuosdesc4,
				gridOpt1: gridOpt1,
				gridOpt2: gridOpt2,
				gridOpt3: gridOpt3,
				other1calc: other1calc,
				daydataTitle: daydataTitle,
				dash5: dash5
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
				//window.history.pushState("", "", window.location.href.split('?')[0]);
				//tj.unitsLoaded=false;
				tj.unitCheck();
            }
        })
    }
	
tj.visitShow = function() {
	var prod = $('#editunitprodMeasureMgr').val();
	var ct = $('#showVisits').val();
	var t9 = document.getElementById('showVisit');
	//var a9 = document.getElementById('censusTime');
	//console.log('prod',prod);
	
	if(prod ==1){
	a9.style.display='';
	t9.style.display='none';
	$('#editcensusShiftMgr').val('52');	
	}else if(prod==4){
	t9.style.display='';
	//a9.style.display='none';
		if(ct==0){
		$('#showVisits').val('3');
		}	
	}else{
	t9.style.display='none';
	//a9.style.display='none';	
	}

}

tj.globalUnit = function() {
	var global = $('#editunitCategory').val();
	console.log('global',global);
	if(global==4){
	document.getElementById('daydataTitle').disabled = false;
	}else{
	document.getElementById('daydataTitle').disabled = true;
	}
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

tj.unitCheck = function() {
		
			$.ajax({
            url:'inc/data.php?req=unitcheck',
            data:{
               
            },
            method:'POST',
            dataType:'json',
            success:function(response) {				
				//tj.UnitsTable.ajax.reload(null,false);
				//ecf_cand_first_name
            }
        });
	 
}

	
tj.addUnit = function () {
        
		var unitName = $('#unitName').val();
		var unitNumber = $('#unitNumber').val();
		var unitBeds = $('#unitBeds').val();
		//var unitTarget = $('#unitTarget').val();
		//var prodMeasure = $('#unitprodMeasure').val();
		//var value = $('#unitprodValue').val();
		//var uosDesc = $('#uosDesc').val();
		var director = $('#newDirector').val();
		var manager = $('#newManager').val();
		//var role = $('#userRole').val();
		//var accountId = $('#accountSelect').val();
		var unitCategory = $('#unitCategory').val();
		var currentDate = moment().format('YYYY-MM-DD');
		var opDays = $('#opDays').val();
		
	console.log('dir',director);
	console.log('mgr',manager);		
    if(unitName.length ==0){
		bootbox.alert('Unit Name is required.');
    return;
		}
	if(unitNumber.length == 0){
		bootbox.alert('Unit Number is required.');
		return;
		}
	if(unitCategory==0){
		bootbox.alert('Unit Type is required.');
    return;
		}
	if(opDays==0){
		bootbox.alert('Operation Days is required.');
    return;
		}
	if(unitBeds.length == 0 && unitCategory == 1){
		bootbox.alert('Bed Count is required for this Unit.');
		return;
		}
	if(unitCategory==2){
		var shiftsDay = 2;
		var censusShift = 0;
		var prodMeasure = 2;
	}else{
		var shiftsDay = 6;
		var censusShift = 52;
		var prodMeasure = 1;
	}
	
	
		
        
        $.ajax({
            url: 'inc/data.php?req=addNewUnit',
            data: {
				unitName: unitName,
                unitNumber: unitNumber,
				unitBeds: unitBeds,
				censusShift: censusShift,
				shiftsDay: shiftsDay,
				director: director,
				manager: manager,
				unitCategory: unitCategory,
				currentDate: currentDate,
				opDays: opDays,
				prodMeasure: prodMeasure
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				$('#addnewUnits').modal('hide');
				if(response.exists ==true){
				bootbox.alert('Unit/Dept. Already Exists.<br><br>Please go to Unit Settings to make configuration changes.');
                }else{
				bootbox.alert('Unit successfully added.  <br><br>Please go to Unit Settings to complete the configuration.');	
				document.getElementById("unitName").value = "";
				document.getElementById("unitNumber").value = "";
				document.getElementById("unitBeds").value = "";
				document.getElementById("unitCategory").selectedIndex = "0";
				document.getElementById("newManager").selectedIndex = "0";
				document.getElementById("newDirector").selectedIndex = "0";
				document.getElementById("opDays").selectedIndex = "0";		
				tj.UnitsTable.ajax.reload();
				tj.unitCheck();
				}
				
            }
        });

		
}

tj.unitCoverage = function(deptId) {
	$.ajax({
        url:"inc/data.php?req=getunitcoverage",
        data: {
                deptId: deptId
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
	
		$('#coverDept').val(response.data.deptId);
		$('#coverageDept').html(' ' + response.data.dept);
		$('#coverId').val(response.data.userId);
		$('#coverDate').val(response.data.endCover);
		$('#unitcoverage').modal('toggle');		
        }
    });
	
};

tj.copyUnit = function(deptId) {
	$('#copydeptId').val(deptId);
	$('#copyUnit').modal('toggle');		
};

tj.copyDept = function() {
	//var userId = $('#coverId').val();
	var accountId = $('#copyaccountId').val();
	var deptId = $('#copydeptId').val();
	$.ajax({
        url:"inc/data.php?req=CopyUnit",
        data: {
                deptId: deptId,
				accountId: accountId
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		bootbox.alert('Unit Successfully Copied');	
		$('#copyUnit').modal('toggle');		
        }
    })
	
	
}

tj.updateCover = function() {
	var userId = $('#coverId').val();
	var endDate = $('#coverDate').val();
	var deptId = $('#coverDept').val();
	
	$.ajax({
        url:"inc/data.php?req=changecoverage",
        data: {
                deptId: deptId,
				userId: userId,
				endDate: endDate
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		tj.UnitsTable.ajax.reload();	
		$('#unitcoverage').modal('toggle');		
        }
    })
	
	
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
				$('#prodCalc').val(response.data.productivityPosNeg);
				$('#accountImage').val(response.data.accountImage);
				$('#rnvarianceLive').val(response.data.rnvarianceLive);
				$('#defaultSort').val(response.data.dashboardSort);
				$('#useEpic').val(response.data.eas);
				$('#updateLevel').val(response.data.updateLevel);
				$('#trainingVids').val(response.data.trainingVids);
				$('#bedCount').val(response.data.bedCount);
				$('#useSafety').val(response.data.useSafety);
				$('#safetyTypes').val(response.data.safetyTypes);
				$('#acctEscalation').val(response.data.acctEscalation);
				$('#housealert').val(response.data.houseAlert);
				$('#houseLogins').val(response.data.houseLogins);
				$('#emailTime').val(response.data.emailTime);
				$('#tportal').val(response.data.transferPortal);
				$('#varMessage').val(response.data.varMessage);
				$('#prodTerm').val(response.data.prodTerm);
				$('#hrsTerm').val(response.data.hrsTerm);
				$('#dashTerm').val(response.data.dashTerm);
				$('#easTerm').val(response.data.easTerm);
				$('#usecrashcarts').val(response.data.logs);
				$('#crashcarttime').val(response.data.crashcartTime);
				//console.log('emailTime',response.data.emailTime);
				
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
				console.log('account' , accountId);
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
	
tj.updateEpic = function() {

		
        $.ajax({
            url:'inc/data.php?req=updateepic',
            data:{
		
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				console.log('success');
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
		var prodCalc = $('#prodCalc').val();
		var rnvarianceLive = $('#rnvarianceLive').val();
		var defaultSort = $('#defaultSort').val();
		var vids = $('#trainingVids').val();
		var bedCount = $('#bedCount').val();
		var useSafety = $('#useSafety').val();
		var safetyTypes = $('#safetyTypes').val();
		var acctEscalation = $('#acctEscalation').val();
		var housealert = $('#housealert').val();
		var houseLogins = $('#houseLogins').val();
		var emailTime = $('#emailTime').val();
		var tportal = $('#tportal').val();
		var eas = $('#useEpic').val();
		var updateLevel = $('#updateLevel').val();
		var varmessage = $('#varMessage').val();
		var accountName = $('#accountName').val();
		var prodTerm = $('#prodTerm').val();
		var hrsTerm = $('#hrsTerm').val();
		var dashTerm = $('#dashTerm').val();
		var easTerm = $('#easTerm').val();
		var crashcart = $('#usecrashcarts').val();
		var crashcarttime = $('#crashcarttime').val();
		
		var currentDate = moment().format('YYYY-MM-DD');
		var payDate = moment(payFirst).format('YYYY-MM-DD');
		
		//console.log('currentDate',currentDate);
		//console.log('payDate',payDate);
		if(moment(payFirst).isValid() && payDate >= currentDate){
			bootbox.alert('The Pay Period Reference Date must be prior to today\'s date');
			return;
			}
		
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
				dashColor: dashColor,
				prodCalc: prodCalc,
				rnvarianceLive: rnvarianceLive,
				defaultSort: defaultSort,
				vids: vids,
				eas: eas,
				varmessage: varmessage,
				accountName: accountName,
				updateLevel: updateLevel,
				tportal: tportal,
				acctEscalation: acctEscalation,
				houseLogins: houseLogins,
				emailTime: emailTime,
				prodTerm: prodTerm,
				hrsTerm: hrsTerm,
				dashTerm: dashTerm,
				easTerm: easTerm,
				housealert: housealert,
				crashcart: crashcart,
				crashcarttime: crashcarttime,
				bedCount: bedCount,
				useSafety: useSafety,
				safetyTypes: safetyTypes
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//$('#prodTablewhp').DataTable().destroy();
				//$('#prodTable').DataTable().destroy();
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
	//console.log('todayDate',tj.todayDate);
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
				d.todayDate = tj.todayDate;
				d.category = $('#serviceFilter').val();
            }
            
        },
		select: {
            style:    'multi',
            selector: 'td:first-child'
        },
		"pageLength": 100,
		"scrollY": '500px',
        //sDom:'<"top"<"clear">>tr<"bottom"lip<"clear">>',
		"order": [[0,'asc'],[10,'desc']],
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
			{ "data": "variance" },
			{ "data": "patients" },
			{ "data": "total" },
			{ "data": "charge" },
			{ "data": "nursecount" },
			{ "data": "techs" },
			{ "data": "other" },
			{ "data": "note" },
			{ "data": "shiftnum" }
        ],
		"columnDefs": [
						{"visible": false, "targets": [10] }
        ],
    } );

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
	var hoursrqd = $('#hoursrqd').val();
	//var hrs1 = $('#hrs1').val();
	//var hrs2 = $('#hrs2').val();
	//var hrs3 = $('#hrs3').val();
	var otherhrs = parseInt(hrs1)+parseInt(hrs2)+parseInt(hrs3);
	var totalhrs = parseInt(hoursTotal);
	//var hrsrqd = parseInt(hoursrqd);
	
	//if(visitTotal==0){
	//bootbox.alert('Visits cannot be zero.');
	//return;
	//}
	
	$.ajax({
            url:'inc/data.php?req=updatevisits',
            data:{
                dataId: dataId,
				visitTotal: visitTotal,
				hoursTotal: hoursTotal
			},
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#visits').modal('hide');
				tj.prodTable.ajax.reload(null,false);
				bootbox.alert('Visits successfully submitted.');
				}
        })
		
};

////////////////////
//add visits
tj.addvisits = function(dataId) {
	document.getElementById("visitTotal").value = "";
	document.getElementById("hoursTotal").value = "";
	document.getElementById("hrs1").value = "";
	document.getElementById("hrs2").value = "";
	document.getElementById("hrs3").value = "";
	$.ajax({
            url:'inc/data.php?req=getvisits',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var h0 = document.getElementById('hrs0Total');
				var h1 = document.getElementById('hrs1Total');	
				var h2 = document.getElementById('hrs2Total');	
				var h3 = document.getElementById('hrs3Total');
				h0.style.display='';
				if (response.data.hrs1Calc==0) {
					h1.style.display='none';
				}else{
					h1.style.display='';
				}
				if (response.data.hrs2Calc==0) {
					h2.style.display='none';
				}else{
					h2.style.display='';
				}
				if (response.data.hrs3Calc==0) {
					h3.style.display='none';
				}else{
					h3.style.display='';
				}
				
				$('#visitsType').html('Total ' + response.data.uosDesc);
				$('#visitsType2').html(response.data.uosDesc);
								
				if(response.data.shiftActual==0 || response.data.visitHoursReqd==1){
					$('#hoursReqd').html('<div>(Required)</div>');
				}else{
					$('#hoursReqd').html('<div>(Optional, but highly recommended if all your productivity reports are not completed.)</div>');
				}
				$('#hoursrqd').val(response.data.visitHoursReqd);
				$('#visitsdataId').val(response.data.id);
				$('#visitTotal').val(response.data.visitsSubmitted);
				$('#hoursTotal').val(response.data.hoursSubmitted);
				$('#hrs1').val(response.data.hrs1Submitted);
				$('#hrs2').val(response.data.hrs2Submitted);
				$('#hrs3').val(response.data.hrs3Submitted);
				$('#hrs1Desc').html(response.data.hrs1Desc);
				$('#hrs2Desc').html(response.data.hrs2Desc);
				$('#hrs3Desc').html(response.data.hrs3Desc);
				$('#visitsdayDate').html(response.data.reportdate);
				$('#hoursdayDate1').html(response.data.reportdate);
				$('#hoursdayDate2').html(response.data.reportdate);
				$('#hoursdayDate3').html(response.data.reportdate);
				$('#hoursdayDate').html(response.data.reportdate);
				$('#visitsDate').val(response.data.dayDate);
				//$('#visitTotal').val(response.data.visits);
				$('#visitsaccountId').val(response.data.accountId);
				$('#visitsdeptId').val(response.data.deptId);
				//$('#hoursTotal').val(response.data.totalHours);
				$('#visits').modal('show');
				}
        })
		
}; 	

////////////////////
//gridupdate
tj.gridupdate = function() {
	//var a = atotal;
	var useGrid = $('#useGrid').val();
	var census1 = $('#patienttotalNEW').val();
	var census2 = $('#patienttotalNEW2').val();
	var census3 = $('#patienttotalNEW3').val();
	var census4 = $('#patienttotalNEW4').val();
	var deptId = $('#deptIdNEW').val();
	var unlocked = $('#unlocked').val();
	var shift = $('#shiftNEW').val();
	var cn = $('#chargecountNEW').val();
	var rn1 = $('#nurse1_add').val();
	var rn2 = $('#nurse2_add').val();
	var pct = $('#techcountNEW').val();
	var sec = $('#seccountNEW').val();
	var rn = $('#nursecountNEW').val();
	var other2 = $('#other2').val();
	var other3 = $('#other3').val();
	var sit = $('#sittersNEW').val();
	var other1 = $('#other1').val();
	var acuityTotal = $('#acuityTotal').val();
	var dayDate = $('#dayNEW').val();
		
	var oneto1 = $('#oneto1').val();
	var oneto2 = $('#oneto2').val();
	var oneto3 = $('#highNEW').val();
	var oneto4 = $('#medNEW').val();
	var oneto5 = $('#lowNEW').val();
	var oneto6 = $('#oneto6').val();
	var extoneto6 = $('#extoneto6').val();
	var oneto7 = $('#oneto7').val();
	var oneto8 = $('#oneto8').val();
	var oneto9 = $('#oneto9').val();
	var oneto10 = $('#oneto10').val();
	var oneto11 = $('#oneto11').val();
	var oneto12 = $('#oneto12').val();
	
	var oneto1Acuity = $('#oneto1Acuity').val();
	var oneto2Acuity = $('#oneto2Acuity').val();
	var oneto3Acuity = $('#oneto3Acuity').val();
	var oneto4Acuity = $('#oneto4Acuity').val();
	var oneto5Acuity = $('#oneto5Acuity').val();
	var oneto6Acuity = $('#oneto6Acuity').val();
		
	var roundrn = $('#roundRN').val();
	var minRN = $('#minrn').val();
	var countcharge = $('#countchargereport').val();
	var countrn = $('#rnreport').val();
	var countrn1 = $('#rn1report').val();
	var countrn2 = $('#rn2report').val();
	var showrn = $('#showrnreport').val();
	
	var cngrid = $('#cnGrid').val();
	var rngrid = $('#rnGrid').val();
	var pctgrid = $('#pctGrid').val();
	var secgrid = $('#secGrid').val();
	var rn1grid = $('#rn1Grid').val();
	var rn2grid = $('#rn2Grid').val();
	var other2grid = $('#other2Grid').val();
	var other3grid = $('#other3Grid').val();
	var other1grid = $('#other1Grid').val();
	var sitgrid = $('#sitterGrid').val();
	
	var pttotal2 = $('#pttotal2').val();
	var pttotal3 = $('#pttotal3').val();
	var pttotal4 = $('#pttotal4').val();
	
	//var useFlow = $('#UseFlow').val();
	//var inFlow = $('#transfersin').val();
	
	//if(inFlow===undefined){
	//inFlow = 0;
	//}	
	
	
	//if(useFlow==2){
	//	var flowCalc = 1;
	//}else{
	//	var flowCalc = 0;
	//}
	
	//var addFlow = parseInt(inFlow) * flowCalc;
		var add1 = $('#add1').val();
		var add2 = $('#add2').val();
		var add3 = $('#add3').val();
		var add4 = $('#add4').val();
		var add5 = $('#add5').val();
		var add6 = $('#add6').val();
		var extadd6 = $('#extadd6').val();

	
	//var addFlow =0;
	
	if(acuityTotal==1 && pttotal2!=1 && pttotal3!=1 && pttotal4!=1){
	var census = ((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseInt(extadd6)));
	}else if(pttotal2==1 || pttotal3==1 || pttotal4==1){
	var census = ((parseFloat(census2) * parseInt(pttotal2)) + (parseFloat(census3) * parseInt(pttotal3)) + (parseFloat(census4) * parseInt(pttotal4)));
	}else{
	var census = parseFloat(census1);	
	}
	$('#patienttotalNEW').val(census);
		
	if(useGrid==1 || useGrid==2 || useGrid==3 || useGrid==4){
	
	$.ajax({
            url:'inc/data.php?req=gridupdate',
            data:{
                census: census,
				deptId: deptId,
				shift: shift,
				oneto1: oneto1,
				oneto2: oneto2,
				oneto3: oneto3,
				oneto4: oneto4,
				oneto5: oneto5,
				oneto6: oneto6,
				oneto7: oneto7,
				oneto8: oneto8,
				oneto9: oneto9,
				oneto10: oneto10,
				oneto11: oneto11,
				oneto12: oneto12,
				acuityTotal: acuityTotal,
				useGrid: useGrid,
				dayDate: dayDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
						
				var cnvar = parseFloat(cn) - parseFloat(response.data.cn);
				var rnvar = parseInt(rn) - parseInt(response.data.rn);
				var pctvar = parseInt(pct) - parseInt(response.data.pct);
				var secvar = parseInt(sec) - parseInt(response.data.sec);
				var rn1var = parseInt(rn1) - parseInt(response.data.rn1);
				var rn2var = parseInt(rn2) - parseInt(response.data.rn2);
				var sitvar = (parseInt(sit) - parseInt(response.data.sitter)) * parseInt(response.data.gridOpt3);
				var other1var = (parseInt(other1) * parseFloat(response.data.other1Calc)) - parseInt(response.data.other1);
				var other2var = (parseInt(other2) - parseInt(response.data.other2)) * parseInt(response.data.gridOpt1);
				var other3var = (parseInt(other3) - parseInt(response.data.other3)) * parseInt(response.data.gridOpt2);
								
				if(parseInt(rn) <= parseInt(response.data.minRN) && rnvar>0){
				rnvar=0;
				}
				var gridvar = cnvar + rnvar + pctvar + secvar + rn1var + rn2var + sitvar + other1var + other2var + other3var;
				
				//$('#easTarget').val(response.data.easTarget);
				//$('#easMax').val(response.data.easMax);
				$('#cnGrid').val(response.data.cn);
				$('#rnGrid').val(response.data.rn);
				$('#pctGrid').val(response.data.pct);
				$('#secGrid').val(response.data.sec);
				$('#rn1Grid').val(response.data.rn1);
				$('#rn2Grid').val(response.data.rn2);
				$('#other2Gridval').html(response.data.other2);
				$('#other2Grid').val(response.data.other2);
				$('#other3Grid').val(response.data.other3);
				$('#other1Grid').val(response.data.other1);
				$('#sitterGrid').val(response.data.sitter);
				
				$('#cnVariance').val(cnvar);
				$('#rnVariance').val(rnvar);
				$('#pctVariance').val(pctvar);
				$('#secVariance').val(secvar);
				$('#rn1Variance').val(rn1var);
				$('#rn2Variance').val(rn2var);
				$('#other2Variance').val(other2var);
				$('#other2Varianceval').html(other2var);
				$('#other3Variance').val(other3var);
				$('#other1Variance').val(other1var);
				$('#sitterVariance').val(sitvar);
				//$('#varComment').html(response.data.varianceMsg);
				$('#showgridvariance').val(gridvar);
				
				//console.log('rngrid2',rngrid);
				//console.log('censusOrig2',census);
				//console.log('easTarget2',response.data.easTarget);
				//console.log('easMax2',response.data.easMax);

				
				
				
				}
        })
	}
		
}; 

////////////////////
//gridupdate2
tj.gridupdate2 = function() {
	var useGrid = $('#useGrid').val();
	var cn = $('#chargecountNEW').val();
	var rn1 = $('#nurse1_add').val();
	var pct = $('#techcountNEW').val();
	var sec = $('#seccountNEW').val();
	var rn = $('#nursecountNEW').val();
	var rn2 = $('#nurse2_add').val();
	var other2 = $('#other2').val();
	var other1 = $('#other1').val();
	var other3 = $('#other3').val();
	var sit = $('#sittersNEW').val();
	var hppdNEW = $('#hppdNEW').val();
	var roundrn = $('#roundRN').val();
	var minRN = $('#minrn').val();
	var nedocs = $('#nedocs').val();
	
	if(useGrid==7){
	var oneto1 = $('#oneto1').val();
	if(oneto1===null){
		oneto1=0;
	}
	var oneto2 = $('#oneto2').val();
	if(oneto2===null){
		oneto2=0;
	}
	var oneto3 = $('#highNEW').val();
	if(oneto3===null){
		oneto3=0;
	}
	var oneto4 = $('#medNEW').val();
	if(oneto4===null){
		oneto4=0;
	}
	var oneto5 = $('#lowNEW').val();
	if(oneto5===null){
		oneto5=0;
	}
	var oneto6 = $('#oneto6').val();
	if(oneto6===null){
		oneto6=0;
	}
	var extoneto6 = $('#extoneto6').val();
	if(extoneto6===null){
		extoneto6=0;
	}
	var oneto7 = $('#oneto7').val();
	if(oneto7===null){
		oneto7=0;
	}
	var oneto8 = $('#oneto8').val();
	if(oneto8===null){
		oneto8=0;
	}
	var oneto1Acuity = $('#oneto1Acuity').val();
	var oneto2Acuity = $('#oneto2Acuity').val();
	var oneto3Acuity = $('#oneto3Acuity').val();
	var oneto4Acuity = $('#oneto4Acuity').val();
	var oneto5Acuity = $('#oneto5Acuity').val();
	var oneto6Acuity = $('#oneto6Acuity').val();
	var shift = $('#shiftNEW').val();
	var dayDate = $('#dayNEW').val();
	var deptId = $('#deptIdNEW').val();
	var unlocked = $('#unlocked').val();
	
	var add1 = $('#add1').val();
	var add2 = $('#add2').val();
	var add3 = $('#add3').val();
	var add4 = $('#add4').val();
	var add5 = $('#add5').val();
	var add6 = $('#add6').val();
	var extadd6 = $('#extadd6').val();
		
		console.log('add1 ',add1);
		console.log('add2 ',add2);
		console.log('add3 ',add3);
		console.log('add4 ',add4);
		console.log('add5 ',add5);
		console.log('add6 ',add6);

	var census = ((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseInt(extadd6)));
	//$('#patienttotalNEW').val(census);
	var census1 = (parseFloat(oneto1) * parseInt(add1));
	var census2 = ((parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseInt(extadd6)));
	var census3 = ((parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto5) * parseInt(add5)));
	//console.log('census1',census1);
	//console.log('census2',census2);
	//console.log('census3',census3);
	
	
		
		$.ajax({
            url:'inc/data.php?req=gridupdate5',
            data:{
                census: census,
				census1: census1,
				census2: census2,
				census3: census3,
				deptId: deptId,
				shift: shift,
				dayDate: dayDate,
				useGrid: useGrid
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
					
					
				var cnvar = parseFloat(cn) - parseFloat(response.data.cn);
				var rnvar = parseInt(rn) - parseInt(response.data.rn);
				var pctvar = parseInt(pct) - parseInt(response.data.pct);
				var secvar = parseInt(sec) - parseInt(response.data.sec);
				var rn1var = parseInt(rn1) - parseInt(response.data2.rn1);
				var rn2var = parseInt(rn2) - parseInt(response.data3.rn2);
				var sitvar = (parseInt(sit) - parseInt(response.data.sitter)) * parseInt(response.data.gridOpt3);
				var other1var = (parseInt(other1) * parseFloat(response.data.other1Calc)) - parseInt(response.data.other1);
				var other2var = (parseInt(other2) - parseInt(response.data.other2)) * parseInt(response.data.gridOpt1);
				var other3var = (parseInt(other3) - parseInt(response.data.other3)) * parseInt(response.data.gridOpt2);
				
				
				
				rnvariance1 = (parseFloat(rn) - ((parseFloat(oneto1) * parseInt(add1)) / parseFloat(oneto1Acuity)));
				rnvariance2 = (parseFloat(rn1) - (((parseFloat(oneto2) * parseInt(add2)) / parseFloat(oneto2Acuity)) + ((parseFloat(oneto4) * parseInt(add4)) / parseFloat(oneto4Acuity)) + ((parseFloat(oneto6) * parseInt(add6)) / parseFloat(oneto6Acuity))));
				rnvariance3 = (parseFloat(rn2) - (((parseFloat(oneto3) * parseInt(add3)) / parseFloat(oneto3Acuity)) + ((parseFloat(oneto5) * parseInt(add5)) / parseFloat(oneto5Acuity))));
				rnvariance = rnvariance1 + rnvariance2 + rnvariance3;
										
				
				//if(roundrn==1){
				//var newrnvar = parseFloat(rnvariance).toFixed(0);
				//var newrnvar1 = parseFloat(rnvariance1).toFixed(0);
				//var newrnvar2 = parseFloat(rnvariance2).toFixed(0);
				//var newrnvar3 = parseFloat(rnvariance3).toFixed(0);
				//}else{
				var newrnvar = parseFloat(rnvariance).toFixed(2);
				var newrnvar1 = parseFloat(rnvariance1).toFixed(2);
				var newrnvar2 = parseFloat(rnvariance2).toFixed(2);
				var newrnvar3 = parseFloat(rnvariance3).toFixed(2);
				//}
				
				if(roundrn==1){
				var shownewrnvar = parseFloat(rnvariance).toFixed(0);
				var shownewrnvar1 = parseFloat(rnvariance1).toFixed(0);
				var shownewrnvar2 = parseFloat(rnvariance2).toFixed(0);
				var shownewrnvar3 = parseFloat(rnvariance3).toFixed(0);
				}else if(roundrn==2){
				var shownewrnvar = Math.floor(parseFloat(rnvariance));
				var shownewrnvar1 = Math.floor(parseFloat(rnvariance1));
				var shownewrnvar2 = Math.floor(parseFloat(rnvariance2));
				var shownewrnvar3 = Math.floor(parseFloat(rnvariance3));
				}else if(roundrn==3){
				var shownewrnvar = Math.ceil(parseFloat(rnvariance));
				var shownewrnvar1 = Math.ceil(parseFloat(rnvariance1));
				var shownewrnvar2 = Math.ceil(parseFloat(rnvariance2));
				var shownewrnvar3 = Math.ceil(parseFloat(rnvariance3));
				}else{
				var shownewrnvar = parseFloat(rnvariance).toFixed(2);
				var shownewrnvar1 = parseFloat(rnvariance1).toFixed(2);
				var shownewrnvar2 = parseFloat(rnvariance2).toFixed(2);
				var shownewrnvar3 = parseFloat(rnvariance3).toFixed(2);
				}
				
				if(parseInt(rn) <= parseInt(response.data.minRN) && rnvar>0){
				rnvar=0;
				newrnvar=0;
				}
				var gridvar = cnvar + rnvar + pctvar + secvar + rn1var + rn2var + sitvar + other1var + other2var + other3var;
				//console.log('census', census);
				$('#patienttotalNEW').val(census);
				$('#showrnvariance').val(newrnvar);
				//$('#displayrnvariance').val(shownewrnvar);
				$('#showrnvariance1').val(newrnvar1);
				$('#showrnvariance2').val(newrnvar2);
				$('#showrnvariance3').val(newrnvar3);
				
				$('#cnGrid').val(parseInt(response.data.cn));
				$('#rnGrid').val(parseInt(response.data.rn));
				$('#pctGrid').val(parseInt(response.data.pct));
				$('#secGrid').val(parseInt(response.data.sec));
				$('#rn1Grid').val(parseInt(response.data2.rn1));
				$('#rn2Grid').val(parseInt(response.data3.rn2));
				//$('#other2Gridval').html(response.data.other2);
				$('#other2Grid').val(parseInt(response.data.other2));
				$('#other3Grid').val(parseInt(response.data.other3));
				$('#other1Grid').val(parseInt(response.data.other1));
				$('#sitterGrid').val(parseInt(response.data.sitter));
				
				$('#cnVariance').val(cnvar);
				$('#rnVariance').val(rnvar);
				$('#pctVariance').val(pctvar);
				$('#secVariance').val(secvar);
				$('#rn1Variance').val(rn1var);
				$('#rn2Variance').val(rn2var);
				$('#other2Variance').val(other2var);
				//$('#other2Varianceval').html(other2var);
				$('#other3Variance').val(other3var);
				$('#other1Variance').val(other1var);
				$('#sitterVariance').val(sitvar);
				$('#patienttotalNEW').val(census);
				//$('#varComment').html(response.data.varianceMsg);
				$('#showgridvariance').val(gridvar);
				
				
				
				}
        })
	
	}else{
	var oneto1Acuity = $('#oneto1Acuity').val();
	var oneto1 = $('#oneto1').val();
	var oneto2Acuity = $('#oneto2Acuity').val();
	var oneto2 = $('#oneto2').val();
	var oneto3Acuity = $('#oneto3Acuity').val();
	var oneto3 = $('#highNEW').val();
	var oneto4Acuity = $('#oneto4Acuity').val();
	var oneto4 = $('#medNEW').val();
	var oneto5Acuity = $('#oneto5Acuity').val();
	var oneto5 = $('#lowNEW').val();
	var oneto6Acuity = $('#oneto6Acuity').val();
	var oneto6 = $('#oneto6').val();
	var extoneto6 = $('#extoneto6').val();
	var oneto7 = $('#oneto7').val();
	var oneto8 = $('#oneto8').val();
	var shift = $('#shiftNEW').val();
	var cn = $('#chargecountNEW').val();
	var rn1 = $('#nurse1_add').val();
	var rn2 = $('#nurse2_add').val();
	var pct = $('#techcountNEW').val();
	var sec = $('#seccountNEW').val();
	var rn = $('#nursecountNEW').val();
	var other2 = $('#other2').val();
	var other3 = $('#other3').val();
	var sit = $('#sittersNEW').val();
	var other1 = $('#other1').val();
	var dayDate = $('#dayNEW').val();
	var deptId = $('#deptIdNEW').val();
	var unlocked = $('#unlocked').val();
	var acuityTotal = $('#acuityTotal').val();
	var submitCount = $('#countNEW').val();
	var showEpic = $('#useEAS').val();
	var showEpicNurse = $('#useEASN').val();
	//var easTarget = $('#easTarget').val();
	//var easMax = $('#easMax').val();
	var eaScore = $('#epicAcuityScore').val();
	var countcharge = $('#countchargereport').val();
	var countrn = $('#rnreport').val();
	var countrn1 = $('#rn1report').val();
	var countrn2 = $('#rn2report').val();
	var showrn = $('#showrnreport').val();
	var varmsg = $('#varmsg').val();
	var roundrn = $('#roundRN').val();
	var minRN = $('#minrn').val();
	var nedocs1 = $('#nedocs').val();
	var census1 = $('#patienttotalNEW').val();
	var census2 = $('#patienttotalNEW2').val();
	var census3 = $('#patienttotalNEW3').val();
	var census4 = $('#patienttotalNEW4').val();
	var cngrid = $('#cnGrid').val();
	var rngrid = $('#rnGrid').val();
	var pctgrid = $('#pctGrid').val();
	var secgrid = $('#secGrid').val();
	var rn1grid = $('#rn1Grid').val();
	var rn2grid = $('#rn2Grid').val();
	var other2grid = $('#other2Grid').val();
	var other3grid = $('#other3Grid').val();
	var other1grid = $('#other1Grid').val();
	var sitgrid = $('#sitterGrid').val();
	var pttotal2 = $('#pttotal2').val();
	var pttotal3 = $('#pttotal3').val();
	var pttotal4 = $('#pttotal4').val();
	var prodMeasure = $('#prodMeasureNEW').val();
	var other1Calc = $('#other1Calc').val();
	var gridOpt1 = $('#grid1opt').val();
	var gridOpt2 = $('#grid2opt').val();
	var gridOpt3 = $('#grid3opt').val();
	var bedCount = $('#bedCount').val();
	
	if(nedocs1==5 && parseInt(bedCount)>0){	
		var nedocs =(85.5*(parseInt(oneto1)/parseInt(oneto3)))+(600*(parseInt(oneto2)/parseInt(bedCount)))+(13.4*parseInt(oneto5))+(.93*parseInt(oneto4))+(5.64*parseInt(oneto6))-20;
		}else{
		var nedocs = '';	
		}
	
	//if(useFlow==2){
	//	var flowCalc = 1;
	//}else{
	//	var flowCalc = 0;
	//}
	
	//if(useFlow==2 && parseFloat(flowAcuity)>0){
	//	var acuityCalc = parseFloat(flowAcuity);
//		var addAcuity = ((parseFloat(inFlow)*flowCalc)/acuityCalc);
	//}else{
		var acuityCalc = 0;
		var addAcuity = 0;
		var add1 = $('#add1').val();
		var add2 = $('#add2').val();
		var add3 = $('#add3').val();
		var add4 = $('#add4').val();
		var add5 = $('#add5').val();
		var add6 = $('#add6').val();
		var extadd6 = $('#extadd6').val();

	var censusOrig = ((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseFloat(extadd6)));
	
	if(acuityTotal==1 && censusOrig!=0 && pttotal2!=1 && pttotal3!=1 && pttotal4!=1 && prodMeasure !=6 && nedocs1 !=5){
	tj.gridupdate();
	var census = ((parseFloat(oneto1) * parseFloat(add1)) + (parseFloat(oneto2) * parseFloat(add2)) + (parseFloat(oneto3) * parseFloat(add3)) + (parseFloat(oneto4) * parseFloat(add4)) + (parseFloat(oneto5) * parseFloat(add5)) + (parseFloat(oneto6) * parseFloat(add6)) + (parseFloat(extoneto6) * parseFloat(extadd6)));
	}else if(nedocs1 !=5 && (pttotal2==1 || pttotal3==1 || pttotal4==1)){
	var census = ((parseFloat(census2) * parseInt(pttotal2)) + (parseFloat(census3) * parseInt(pttotal3)) + (parseFloat(census4) * parseInt(pttotal4)));
	}else{
	var census = parseFloat(censusOrig);	
	}
	
	//console.log('census ',census);
	
	//if(parseFloat(rngrid)>0 && censusOrig!=0){
	//var rnLow = censusOrig / 5;
	//var rnHigh = censusOrig / 4.667;
	//}else{
	var rnLow = 0;
	var rnHigh = 0;
	//}
	
	
	//if(parseInt(rn)>0 && censusOrig!=0){
	//var rnNow = parseFloat(eaScore) / parseFloat(rn);
	//}else{
	var rnNow = 0;	
	//}
	
	/*		
	if(prodMeasure==6){
	//rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + (parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity))));
	rnvariance = (((parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto8) * (parseFloat(hppdNEW)/5)) - (parseFloat(oneto7) * (parseFloat(hppdNEW)/5))) / (parseFloat(hppdNEW))));
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));	
	}else if(showEpicNurse==1 && eaScore !=0 && rn !=0 && censusOrig!=0){

		if(rnNow >=350 && rnNow <=375){
		rnvariance = ('0');
		}else if(rnNow < 350){
		rnvariance = (((parseFloat(rn) * 350) - parseFloat(eaScore))/350);
		//rnvariance = parseFloat(rn) - rnLow;
		}else{
		rnvariance = (((parseFloat(rn) * 375) - parseFloat(eaScore))/375);
		//rnvariance = parseFloat(rn) - rnHigh;	
		}
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	$('#epicAcuityNurse').val(((parseFloat(eaScore) / parseFloat(rn)).toFixed(1)));
	$('#epicAcuityVar').val((parseFloat(rnvariance).toFixed(1)));
	}else if(showEpicNurse==1 && eaScore==0 && censusOrig!=0){
	$('#epicAcuityNurse').val('0');
	rnvariance = ('0');
	$('#epicAcuityVar').val('');
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	}else{
	*/
	rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + ((parseFloat(oneto1) * parseInt(add1)) / parseFloat(oneto1Acuity)) + ((parseFloat(oneto2) * parseInt(add2)) / parseFloat(oneto2Acuity)) + ((parseFloat(oneto3) * parseInt(add3)) / parseFloat(oneto3Acuity)) + ((parseFloat(oneto4) * parseInt(add4)) / parseFloat(oneto4Acuity)) + ((parseFloat(oneto5) * parseInt(add5)) / parseFloat(oneto5Acuity)) + ((parseFloat(oneto6) * parseInt(add6)) / parseFloat(oneto6Acuity))));
	//rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + (parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity))));
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	//}

	
	
	
				
	
	if(roundrn==1 && showEpicNurse==0){
	var newrnvar = parseFloat(rnvariance).toFixed(0);
	}else{
	var newrnvar = parseFloat(rnvariance).toFixed(2);
	}
	
	if(roundrn==1){
	//var newrnvar = parseFloat(rnvariance).toFixed(0);
	var newgridrn = parseFloat(gridrnvariance).toFixed(0);
	}else{
	//var newrnvar = parseFloat(rnvariance).toFixed(1);
	var newgridrn = parseFloat(gridrnvariance).toFixed(2);	
	}
	
	//console.log('newrn',newrnvar);
	//console.log('newgrid',newgridrn);
		
	//console.log('other1calc',other1Calc);				
	var cnvar = parseFloat(cn) - parseFloat(cngrid);
	var rnvar = parseInt(rn) - parseInt(rngrid);
	var pctvar = parseInt(pct) - parseInt(pctgrid);
	var secvar = parseInt(sec) - parseInt(secgrid);
	var rn1var = parseInt(rn1) - parseInt(rn1grid);
	var rn2var = parseInt(rn2) - parseInt(rn2grid);
	
	//var other2var = parseInt(other2) - parseInt(other2grid);
	//var other3var = parseInt(other3) - parseInt(other3grid);
	//var other1var = (parseInt(other1) * parseFloat(other1Calc)) - parseInt(other1grid);
	//var sitvar = parseInt(sit) - parseInt(sitgrid);
	
	var other2var = (parseInt(other2) - parseInt(other2grid)) * parseInt(gridOpt1);
	var other3var = (parseInt(other3) - parseInt(other3grid)) * parseInt(gridOpt2);
	var other1var = (parseInt(other1) * parseFloat(other1Calc)) - parseInt(other1grid);
	var sitvar = (parseInt(sit) - parseInt(sitgrid)) * parseInt(gridOpt3);
	
	if(parseInt(rn) <= parseInt(minRN) && rnvar>0){
		rnvar=0;
		newrnvar = 0;
		newgridrn = 0;
		}
	
	var gridvar = cnvar + rnvar + pctvar + secvar + rn1var + rn2var + sitvar + other1var + other2var + other3var;
	//console.log('other2',other2);
	//console.log('other2grid',other2grid);
	
	$('#varComment').html(varmsg);
				
	if(rngrid==''){
		$('#showgridrnvariance').val('0');
	}else{
		$('#showgridrnvariance').val(newgridrn);
	}
	
	
	
	$('#cnVariance').val(cnvar);
	$('#rnVariance').val(rnvar);
	$('#pctVariance').val(pctvar);
	$('#secVariance').val(secvar);
	$('#rn1Variance').val(rn1var);
	$('#rn2Variance').val(rn2var);
	$('#other2Variance').val(other2var);
	$('#other2Varianceval').html(other2var);
	$('#other3Variance').val(other3var);
	$('#other1Variance').val(other1var);
	$('#sitterVariance').val(sitvar);
	$('#showrnvariance').val(newrnvar);
	$('#showgridvariance').val(gridvar);
	$('#patienttotalNEW').val(census);
		//if(deptId==496 || deptId==502){
	$('#varianceNEW').html(parseInt(nedocs));
		//}else{
		//$('#varianceNEW').html(newrnvar);
		//}
	//$('#showgridrnvariance').val(gridrnvariance);
	
}
		
}; 

////////////////////
//gridupdate2
tj.gridupdate5 = function() {
	var useGrid = $('#useGrid').val();
	var acuityTotal = $('#acuityTotal').val();
	var cn = $('#chargecountNEW').val();
	var rn1 = $('#nurse1_add').val();
	var pct = $('#techcountNEW').val();
	var sec = $('#seccountNEW').val();
	var rn = $('#nursecountNEW').val();
	var rn2 = $('#nurse2_add').val();
	var other2 = $('#other2').val();
	var other1 = $('#other1').val();
	var other3 = $('#other3').val();
	var sitter = $('#sittersNEW').val();
	var submitCount = $('#countNEW').val();
	var showEpic = $('#useEAS').val();
	var showEpicNurse = $('#useEASN').val();
	//var easTarget = $('#easTarget').val();
	//var easMax = $('#easMax').val();
	var eaScore = $('#epicAcuityScore').val();
	var oneto1Acuity = $('#oneto1Acuity').val();
	var oneto1 = $('#oneto1').val();
	var oneto2Acuity = $('#oneto2Acuity').val();
	var oneto2 = $('#oneto2').val();
	var oneto3Acuity = $('#oneto3Acuity').val();
	var oneto3 = $('#highNEW').val();
	var oneto4Acuity = $('#oneto4Acuity').val();
	var oneto4 = $('#medNEW').val();
	var oneto5Acuity = $('#oneto5Acuity').val();
	var oneto5 = $('#lowNEW').val();
	var oneto6Acuity = $('#oneto6Acuity').val();
	var oneto6 = $('#oneto6').val();
	var extoneto6 = $('#extoneto6').val();
	var oneto7 = $('#oneto7').val();
	var oneto8 = $('#oneto8').val();
	var countcharge = $('#countchargereport').val();
	var countrn = $('#rnreport').val();
	var countrn1 = $('#rn1report').val();
	var countrn2 = $('#rn2report').val();
	var showrn = $('#showrnreport').val();
	var varmsg = $('#varmsg').val();
	var roundrn = $('#roundRN').val();
	var minRN = $('#minrn').val();
	var census1 = $('#patienttotalNEW').val();
	var census2 = $('#patienttotalNEW2').val();
	var census3 = $('#patienttotalNEW3').val();
	var census4 = $('#patienttotalNEW4').val();
	var cngrid = $('#cnGrid').val();
	var rngrid = $('#rnGrid').val();
	var pctgrid = $('#pctGrid').val();
	var secgrid = $('#secGrid').val();
	var rn1grid = $('#rn1Grid').val();
	var rn2grid = $('#rn2Grid').val();
	var other2grid = $('#other2Grid').val();
	var other3grid = $('#other3Grid').val();
	var other1grid = $('#other1Grid').val();
	var sitgrid = $('#sitterGrid').val();
	var pttotal2 = $('#pttotal2').val();
	var pttotal3 = $('#pttotal3').val();
	var pttotal4 = $('#pttotal4').val();
	//var useFlow = $('#UseFlow').val();
	//var inFlow = $('#transfersin').val();
	//var flowAcuity = $('#flowAcuity').val();
	var deptId = $('#deptIdNEW').val();
	var unlocked = $('#unlocked').val();
	var hppdNEW = $('#hppdNEW').val();
	var prodMeasure = $('#prodMeasureNEW').val();
	var gridOpt1 = $('#grid1opt').val();
	var gridOpt2 = $('#grid2opt').val();
	var gridOpt3 = $('#grid3opt').val();
	var other1Calc = $('#other1Calc').val();
	var vrn = $('#rnvar').val();
	var bedCount = $('#bedCount').val();
	var nedocs1 = $('#nedocs').val();
	
	if(parseInt(nedocs1)==5 && parseInt(bedCount)>0){	
		var nedocs =(85.5*(parseInt(oneto1)/parseInt(oneto3)))+(600*(parseInt(oneto2)/parseInt(bedCount)))+(13.4*parseInt(oneto5))+(.93*parseInt(oneto4))+(5.64*parseInt(oneto6))-20;
		}else{
		var nedocs = 0;	
		}
	
	
	//if(useFlow==2){
	//	var flowCalc = 1;
	//}else{
	//	var flowCalc = 0;
	//}
	
	//if(useFlow==2 && parseFloat(flowAcuity)>0){
	//	var acuityCalc = parseFloat(flowAcuity);
	//	var addAcuity = ((parseFloat(inFlow)*flowCalc)/acuityCalc);
	//}else{
		var acuityCalc = 0;
		var addAcuity = 0;
	//}
	
	var add1 = $('#add1').val();
	var add2 = $('#add2').val();
	var add3 = $('#add3').val();
	var add4 = $('#add4').val();
	var add5 = $('#add5').val();
	var add6 = $('#add6').val();
	var extadd6 = $('#extadd6').val();

	var censusOrig = ((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseInt(extadd6)));
	
	if(acuityTotal==1 && censusOrig!=0 && pttotal2!=1 && pttotal3!=1 && pttotal4!=1 && prodMeasure !=6){
	tj.gridupdate();
	var census = ((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6)) + (parseFloat(extoneto6) * parseInt(extadd6)));
	}else if(pttotal2==1 || pttotal3==1 || pttotal4==1){
	var census = ((parseFloat(census2) * parseInt(pttotal2)) + (parseFloat(census3) * parseInt(pttotal3)) + (parseFloat(census4) * parseInt(pttotal4)));
	}else{
	var census = parseFloat(census1);	
	}
	
	if(parseFloat(rngrid)>0 && censusOrig!=0){
	//var rnLow = parseFloat(easTarget) / parseFloat(rngrid);
	//var rnHigh = parseFloat(easMax) / parseFloat(rngrid);
	var rnLow = censusOrig / 5;
	var rnHigh = censusOrig / 4.667;
	}else{
	var rnLow = 0;
	var rnHigh = 0;
	}
	if(parseInt(rn)>0 && censusOrig!=0){
	var rnNow = parseFloat(eaScore) / parseFloat(rn);
	}else{
	var rnNow = 0;	
	}
	
	//console.log('rngrid5',rngrid);
	//console.log('censusOrig5',censusOrig);
	//console.log('rnLow5',rnLow);
	//console.log('rnHigh5',rnHigh);
	//console.log('easTarget5',easTarget);
	//console.log('easMax5',easMax);
	//console.log('rnNow5',rnNow);
	//console.log('eaScore5',eaScore);
	//console.log('rn5',parseInt(rn));
			
	if(prodMeasure==6){
	//rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + (parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity))));
	rnvariance = (((parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((parseFloat(oneto1) + (parseFloat(oneto8) * (parseFloat(hppdNEW)/5)) - (parseFloat(oneto7) * (parseFloat(hppdNEW)/5))) / (parseFloat(hppdNEW))));
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));	
	rnvariance1 =0;
	rnvariance2 =0;
	rnvariance3 =0;
	}else if(useGrid==7){
	//rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + (parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity))));
	rnvariance1 = (parseFloat(rn) - ((parseFloat(oneto1) * parseInt(add1)) / parseFloat(oneto1Acuity)));
	rnvariance2 = (parseFloat(rn1) - (((parseFloat(oneto2) * parseInt(add2)) / parseFloat(oneto2Acuity))+((parseFloat(oneto4) * parseInt(add4)) / parseFloat(oneto4Acuity))+((parseFloat(oneto6) * parseInt(add6)) / parseFloat(oneto6Acuity))));
	rnvariance3 = (parseFloat(rn2) - (((parseFloat(oneto3) * parseInt(add3)) / parseFloat(oneto3Acuity))+((parseFloat(oneto5) * parseInt(add5)) / parseFloat(oneto5Acuity))));
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	rnvariance = rnvariance1 + rnvariance2 + rnvariance3;	
	}else if(showEpicNurse==1 && eaScore !=0 && rn !=0 && censusOrig!=0){
	
		if(rnNow >=350 && rnNow <=375){
		rnvariance = ('0');
		}else if(rnNow < 350){
		rnvariance = (((parseFloat(rn) * 350) - parseFloat(eaScore))/350);
		//rnvariance = parseFloat(rn) - rnLow;
		}else{
		rnvariance = (((parseFloat(rn) * 375) - parseFloat(eaScore))/375);
		//rnvariance = parseFloat(rn) - rnHigh;	
		}
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	$('#epicAcuityNurse').val(((parseFloat(eaScore) / parseFloat(rn)).toFixed(1)));
	$('#epicAcuityVar').val((parseFloat(rnvariance).toFixed(1)));
	rnvariance1 =0;
	rnvariance2 =0;
	rnvariance3 =0;
	}else if(showEpicNurse==1 && eaScore==0 && censusOrig!=0){
	$('#epicAcuityNurse').val('0');
	rnvariance = ('0');
	$('#epicAcuityVar').val('');
	rnvariance1 =0;
	rnvariance2 =0;
	rnvariance3 =0;
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	}else if(censusOrig!=0){
	rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + ((parseFloat(oneto1) * parseInt(add1)) / parseFloat(oneto1Acuity)) + ((parseFloat(oneto2) * parseInt(add2)) / parseFloat(oneto2Acuity)) + ((parseFloat(oneto3) * parseInt(add3)) / parseFloat(oneto3Acuity)) + ((parseFloat(oneto4) * parseInt(add4)) / parseFloat(oneto4Acuity)) + ((parseFloat(oneto5) * parseInt(add5)) / parseFloat(oneto5Acuity)) + ((parseFloat(oneto6) * parseInt(add6)) / parseFloat(oneto6Acuity))));
	rnvariance1 = 0;
	rnvariance2 = 0;
	rnvariance3 = 0;
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	}else{
	//rnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - ((addAcuity) + (parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity))));
	rnvariance = 0;
	rnvariance1 = 0;
	rnvariance2 = 0;
	rnvariance3 = 0;
	gridrnvariance = (((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2))) - (((parseFloat(cngrid) * parseFloat(countcharge)) + (parseFloat(rngrid) * parseFloat(countrn)) + (parseFloat(rn1grid) * parseFloat(countrn1)) + (parseFloat(rn2grid) * parseFloat(countrn2)))));
	}
		
	
	//if(roundrn==1 && showEpicNurse==0){
	//var newrnvar = parseFloat(rnvariance).toFixed(0);
	//var newrnvar1 = parseFloat(rnvariance1).toFixed(0);
	//var newrnvar2 = parseFloat(rnvariance2).toFixed(0);
	//var newrnvar3 = parseFloat(rnvariance3).toFixed(0);
	//}else{
	var newrnvar = parseFloat(rnvariance).toFixed(2);
	var newrnvar1 = parseFloat(rnvariance1).toFixed(2);
	var newrnvar2 = parseFloat(rnvariance2).toFixed(2);
	var newrnvar3 = parseFloat(rnvariance3).toFixed(2);
	//}
	
	//if(roundrn==1){
	//var newrnvar = parseFloat(rnvariance).toFixed(0);
	//var newgridrn = parseFloat(gridrnvariance).toFixed(0);
	//}else{
	//var newrnvar = parseFloat(rnvariance).toFixed(1);
	var newgridrn = parseFloat(gridrnvariance).toFixed(2);	
	//}
	
	//console.log('newrn5',newrnvar);
	//console.log('newgrid',newgridrn);
		
					
	var cnvar = parseFloat(cn) - parseFloat(cngrid);
	var rnvar = parseInt(rn) - parseInt(rngrid);
	var pctvar = parseInt(pct) - parseInt(pctgrid);
	var secvar = parseInt(sec) - parseInt(secgrid);
	var rn1var = parseInt(rn1) - parseInt(rn1grid);
	var rn2var = parseInt(rn2) - parseInt(rn2grid);
	var other2var = (parseInt(other2) - parseInt(other2grid)) * parseInt(gridOpt1);
	var other3var = (parseInt(other3) - parseInt(other3grid)) * parseInt(gridOpt2);
	var other1var = (parseInt(other1) * parseFloat(other1Calc)) - parseInt(other1grid);
	var sitvar = (parseInt(sitter) - parseInt(sitgrid)) * parseInt(gridOpt3);
	
	//console.log('rn',rn);
	//console.log('minrn',minRN);
	//console.log('rnvar',rnvar);
	
	if(parseInt(rn) <= parseInt(minRN) && rnvar>0){
		rnvar=0;
		newrnvar = 0;
		newgridrn = 0;
		}
	
	var gridvar = cnvar + rnvar + pctvar + secvar + rn1var + rn2var + sitvar + other1var + other2var + other3var;

	
	$('#varComment').html(varmsg);
				
	if(rngrid==''){
		$('#showgridrnvariance').val('0');
	}else{
		$('#showgridrnvariance').val(newgridrn);
	}
	
	var ratioNew = (((parseFloat(oneto1) * parseInt(add1)) + (parseFloat(oneto2) * parseInt(add2)) + (parseFloat(oneto3) * parseInt(add3)) + (parseFloat(oneto4) * parseInt(add4)) + (parseFloat(oneto5) * parseInt(add5)) + (parseFloat(oneto6) * parseInt(add6))) / ((parseFloat(cn) * parseFloat(countcharge)) + (parseFloat(rn) * parseFloat(countrn)) + (parseFloat(rn1) * parseFloat(countrn1)) + (parseFloat(rn2) * parseFloat(countrn2)))).toFixed(1);
	
	
	$('#cnVariance').val(cnvar);
	$('#rnVariance').val(rnvar);
	$('#pctVariance').val(pctvar);
	$('#secVariance').val(secvar);
	$('#rn1Variance').val(rn1var);
	$('#rn2Variance').val(rn2var);
	$('#other2Variance').val(other2var);
	$('#other2Varianceval').html(other2var);
	$('#other3Variance').val(other3var);
	$('#other1Variance').val(other1var);
	$('#sitterVariance').val(sitvar);
	$('#showrnvariance').val(newrnvar);
	$('#showrnvariance1').val(newrnvar1);
	$('#showrnvariance2').val(newrnvar2);
	$('#showrnvariance3').val(newrnvar3);
	$('#showgridvariance').val(gridvar);
	$('#patienttotalNEW').val(census);
	$('#ratioNEW').html(' 1 : ' + ratioNew);
	$('#ptRatio').val(ratioNew);
	//$('#varianceNEW').html(newrnvar);
	//console.log('rnvar',vrn);
		if(parseInt(vrn)==2){
		$('#varianceNEW').html(gridvar);
		}else if(parseInt(vrn)==3){
		$('#varianceNEW').html(rnvar);
		}else if(parseInt(vrn)==0){
		$('#varianceNEW').html(newrnvar);
		}else if(parseInt(vrn)==5){
		$('#varianceNEW').html(parseInt(nedocs));
		}else{
		//do nothing;
		}	
	//$('#showgridrnvariance').val(gridrnvariance);
	
		
};



////////////////////
//showTransfers
tj.showTransfers = function() {
var dataId = $('#dataIdNEW').val();
//console.log('dataid',dataId);
	$.ajax({
            url:'inc/data.php?req=GetTransfers',
            data:{
               dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			
			//$('#transferDept').html(response.data.dept);
			$('#currentTransfers').html(response.data.tfrs);
			$('#TransfersIn').modal('show');			
			}
	});
	
	
}

////////////////////
//gridupdate2
tj.gridupdate3 = function() {
	var census = $('#patienttotalNEW').val();
	var cn = $('#chargecountNEW').val();
	var rn1 = $('#nurse1_add').val();
	var pct = $('#techcountNEW').val();
	var sec = $('#seccountNEW').val();
	var rn = $('#nursecountNEW').val();
	var rn2 = $('#other1').val();
	var pct1 = $('#other2').val();
	var pct2 = $('#other3').val();
	var other1 = $('#sittersNEW').val();
	
	var cngrid = $('#cnGrid').val();
	var rngrid = $('#rnGrid').val();
	var pctgrid = $('#pctGrid').val();
	var secgrid = $('#secGrid').val();
	var rn1grid = $('#rn1Grid').val();
	var rn2grid = $('#rn2Grid').val();
	var pct1grid = $('#pct1Grid').val();
	var pct2grid = $('#pct2Grid').val();
	var other1grid = $('#other1Grid').val();
	
	var oneto1Acuity = $('#oneto1Acuity').val();
	var oneto1 = $('#oneto1').val();
	
	var oneto2Acuity = $('#oneto2Acuity').val();
	var oneto2 = $('#oneto2').val();
	
	var oneto3Acuity = $('#oneto3Acuity').val();
	var oneto3 = $('#highNEW').val();
	
	var oneto4Acuity = $('#oneto4Acuity').val();
	var oneto4 = $('#medNEW').val();
	
	var oneto5Acuity = $('#oneto5Acuity').val();
	var oneto5 = $('#lowNEW').val();
	
	var oneto6Acuity = $('#oneto6Acuity').val();
	var oneto6 = $('#oneto6').val();
	
	var countcharge = $('#countchargereport').val();
	var showrn = $('#showrnreport').val();
	
	if(countcharge==1){
	var rnvariance =  (parseFloat(cn) + parseFloat(rn)) - (Math.ceil(((parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity)))));
	var rnvarianceFloor =  (parseFloat(cn) + parseFloat(rn)) - (Math.floor(((parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity)))));
	}else{
	var rnvariance =  parseFloat(rn) - (Math.ceil(((parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity)))));	
	var rnvarianceFloor =  parseFloat(rn) - (Math.floor(((parseFloat(oneto1) / parseFloat(oneto1Acuity)) + (parseFloat(oneto2) / parseFloat(oneto2Acuity)) + (parseFloat(oneto3) / parseFloat(oneto3Acuity)) + (parseFloat(oneto4) / parseFloat(oneto4Acuity)) + (parseFloat(oneto5) / parseFloat(oneto5Acuity)) + (parseFloat(oneto6) / parseFloat(oneto6Acuity)))));	
	}
	console.log('countcharge',countcharge);
	console.log('rnVariance',rnvariance)
					
	var cnvar = parseFloat(cn) - parseFloat(cngrid);
	var rnvar = parseInt(rn) - parseInt(rngrid);
	var pctvar = parseInt(pct) - parseInt(pctgrid);
	var secvar = parseInt(sec) - parseInt(secgrid);
	var rn1var = parseInt(rn1) - parseInt(rn1grid);
	var rn2var = parseInt(rn2) - parseInt(rn2grid);
	var pct1var = parseInt(pct1) - parseInt(pct1grid);
	var pct2var = parseInt(pct2) - parseInt(pct2grid);
	var other1var = parseInt(other1) - parseInt(other1grid);
	
	//console.log('census',census);
	
	if(parseInt(census) !=0){
	$('#cnVariance').val(cnvar);
	$('#rnVariance').val(rnvar);
	$('#pctVariance').val(pctvar);
	$('#secVariance').val(secvar);
	$('#rn1Variance').val(rn1var);
	$('#rn2Variance').val(rn2var);
	$('#pct1Variance').val(pct1var);
	$('#pct2Variance').val(pct2var);
	$('#other1Variance').val(other1var);
	$('#showrnvariance').val(rnvariance);
	}
		
};
/////////////////////////////////////
// EDIT PROD
/*
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
				//$('#blocked').html('<a href="javascript:;" onclick="blockedBeds('+response.data.blockedBeds+');">  '+response.data.blockedBeds+'</a>');
				$('#dataIdSICU').val(response.data.id);
				$('#dataId2SICU').html('<a href="/v.php?i='+response.data.dataId2+'">Print View</a>');
				$('#prodnoteSICU').val(response.data.note);
				$('#varianceSICU').html(response.data.nvariance);
				$('#aproductivitySICU').html(response.data.nproductivity);
				$('#avarianceSICU').html(response.data.avariance);
				$('#prodTable').DataTable().search('').draw();
				$('#addSICU').modal('show');
				}
        })
        console.log('record updated sucessfully',dataId);
		
  }
  */
  
  /////////////////////////////////////
// EDIT PROD

tj.editNEW = function(dataId,hod,dow) {
		document.getElementById("hidden1").style.display='';
		document.getElementById("hidden2").style.display='';
		document.getElementById("hidden3").style.display='';
		document.getElementById("hidden4").style.display='';
		document.getElementById("hidden5").style.display='';
		document.getElementById("hidden6").style.display='';
		document.getElementById("exthidden6").style.display='';
		document.getElementById("hiddencheckBox").style.display='';
		document.getElementById("secView").style.display='';
		document.getElementById("hidden7").style.display='none';
		document.getElementById("transfers").style.display='none';
		document.getElementById("hidden8").style.display='none';
		document.getElementById("hidden9").style.display='none';
		document.getElementById("hidden10").style.display='none';
		document.getElementById("hidden11").style.display='none';
		document.getElementById("hidden12").style.display='none';
		document.getElementById("showchurn1").style.display='none';
		document.getElementById("customChurn1").style.display='none';
		document.getElementById("Nurse1").style.display='none';
		document.getElementById("other1hide").style.display='none';
		document.getElementById("other2hide").style.display='none';
		document.getElementById("other3hide").style.display='none';
		document.getElementById("sittersNEWhide").style.display='none';
		document.getElementById('ptTitle').style.display='none';
		document.getElementById("cngrid1").style.display='none';
		document.getElementById("cngrid2").style.display='none';
		document.getElementById("rngrid1").style.display='none';
		document.getElementById("rngrid2").style.display='none';
		document.getElementById("pctgrid1").style.display='none';
		document.getElementById("pctgrid2").style.display='none';
		document.getElementById("secgrid1").style.display='none';
		document.getElementById("secgrid2").style.display='none';
		document.getElementById("rn1grid1").style.display='none';
		document.getElementById("rn1grid2").style.display='none';
		document.getElementById("other2grid1").style.display='none';
		document.getElementById("other2grid2").style.display='none';
		document.getElementById("sittergrid1").style.display='none';
		document.getElementById("sittergrid2").style.display='none';
		document.getElementById("rn2grid1").style.display='none';
		document.getElementById("rn2grid2").style.display='none';
		document.getElementById("other3grid1").style.display='none';
		document.getElementById("other3grid2").style.display='none';
		document.getElementById("other1grid1").style.display='none';
		document.getElementById("other1grid2").style.display='none';
		document.getElementById("hidernvariance").style.display='none';
		document.getElementById("hidernvariance1").style.display='none';
		document.getElementById("hidernvariance2").style.display='none';
		document.getElementById("hidernvariance3").style.display='none';
		document.getElementById("hidegridvariance").style.display='none';
		document.getElementById("hidegridrnvariance").style.display='none';
		document.getElementById("submitted").style.display='none';111
		document.getElementById("techView").style.display='';
		document.getElementById("submittedNEW").style.display='none';
		document.getElementById("showSubmit2").style.display='none';
		document.getElementById("hiddentotal2").style.display='none';
		//document.getElementById("hidetrackflow1").style.display='none';
		//document.getElementById("hidetrackflow2").style.display='none';
		//document.getElementById("hidetrackflow3").style.display='none';
		//document.getElementById("hidetrackflow4").style.display='none';
		//document.getElementById("hidetrackflow5").style.display='none';
		//document.getElementById("hidetrackflow6").style.display='none';
		//document.getElementById("hidetrackflow7").style.display='none';
		//document.getElementById("hidetrackflow8").style.display='none';
		//document.getElementById("showFlow").style.display='none';
		document.getElementById("showHPPS").style.display='none';
		document.getElementById("showratio").style.display='none';
		document.getElementById("showtrack").style.display='none';
		$('#zeropts').val('0');
		var currentHour = moment().format('HH');
		var currentDay = moment().format('DD');
		//console.log('hod',hod);
		//console.log('currentHour',currentHour);
		//console.log('dow',dow);
		//console.log('currentDay',currentDay);
		
		if(parseInt(hod) > (parseInt(currentHour) + 7) && parseInt(dow) == parseInt(currentDay)){
		bootbox.alert('<span style="color:red">WAIT.....Please check the Date and Time on this record to make sure you are filling out the correct one.</span> </br></br>If so, please cancel out of this record and click on Yesterday in the date selector drop-down at the top of the page.');
		return;	
		}
		
				       
        $.ajax({
            url:'inc/data.php?req=getProdDetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response,users) {
				
				$('#acuityDesc').html('Additional Patient Data');	
				
				var a1 = document.getElementById('hidden1');
				var a2 = document.getElementById('hidden2');	
				var a3 = document.getElementById('hidden3');	
				var a4 = document.getElementById('hidden4');	
				var a5 = document.getElementById('hidden5');	
				var a6 = document.getElementById('hidden6');
				var e6 = document.getElementById('exthidden6');
				var e7 = document.getElementById('hiddencheckBox');
				var a7 = document.getElementById('showchurn1');
				var a8 = document.getElementById('hiddentotal1');
				var a9 = document.getElementById('hiddentotal2');
				var a10 = document.getElementById('Nurse1');
				var a11 = document.getElementById('hidden7');
				var a12 = document.getElementById('hidden8');
				var a13 = document.getElementById('hidden9');
				var a15 = document.getElementById('customChurn1');
				var a16 = document.getElementById('other1hide');
				var a17 = document.getElementById('other2hide');
				var a18 = document.getElementById('other3hide');
				var a19 = document.getElementById('hidden10');
				var a20 = document.getElementById('hidden11');
				var a201 = document.getElementById('hidden12');
				var a21 = document.getElementById('acuityDesc');
				var a22 = document.getElementById('secView');
				var a23 = document.getElementById('sittersNEWhide');
				var a24 = document.getElementById('showtrack');
				//var a25 = document.getElementById('hiddendischarges');
				var a26 = document.getElementById('hidetrack1');
				var a27 = document.getElementById('hidetrack2');
				var a28 = document.getElementById('hidetrack3');
				var a29 = document.getElementById('hidetrack4');
				var a30 = document.getElementById('hidernvariance');
				var a301 = document.getElementById('hidernvariance1');
				var a302 = document.getElementById('hidernvariance2');
				var a303 = document.getElementById('hidernvariance3');
				var a31 = document.getElementById('Nurse2');
				var a32 = document.getElementById('techView');
				var a33 = document.getElementById('submitted');
				var a34 = document.getElementById('hidegridvariance');
				var a40 = document.getElementById('hidegridrnvariance');
				var a35 = document.getElementById('hiddenvisits');
				var a36 = document.getElementById('submittedNEW');
				var a37 = document.getElementById('charge0');
				var a38 = document.getElementById('nurse0');
				var a39 = document.getElementById('currentPatients2');
				var a50 = document.getElementById('currentPatients3');
				var a51 = document.getElementById('currentPatients4');
				var a52 = document.getElementById('currentPatients');
				
				var a61 = document.getElementById('pos1');
				var a62 = document.getElementById('pos2');
				var a63 = document.getElementById('pos3');
				var a64 = document.getElementById('pos4');
				var a65 = document.getElementById('pos5');
				var a66 = document.getElementById('pos6');
				var a67 = document.getElementById('pos7');
				var a68 = document.getElementById('pos8');
				var a69 = document.getElementById('pos9');
				var a70 = document.getElementById('pos10');
				var h1 = document.getElementById('hidenote1');
				var h2 = document.getElementById('hidenote2');
				var h3 = document.getElementById('hidenote3');
				var h4 = document.getElementById('hidenote4');
				//var f1 = document.getElementById('hidetrackflow1');
				//var f2 = document.getElementById('hidetrackflow2');
				//var f3 = document.getElementById('hidetrackflow3');
				//var f4 = document.getElementById('hidetrackflow4');
				//var f5 = document.getElementById('hidetrackflow5');
				//var f6 = document.getElementById('hidetrackflow6');
				//var f7 = document.getElementById('hidetrackflow7');
				//var f8 = document.getElementById('hidetrackflow8');
				//var f9 = document.getElementById('transfers');
				//var f10 = document.getElementById('showFlow');
				//var f11 = document.getElementById('transfers2');
				var f12 = document.getElementById('showHPPS');
				var f13 = document.getElementById('showratio');
				
				a61.className="";
				a62.className="";
				a63.className="";
				a64.className="";
				a65.className="";
				a66.className="";
				a67.className="";
				a68.className="";
				a69.className="";
				a70.className="";
				
				var newtransfers = '';
                newtransfers += '<label for="transfersin" class="col-6">';
				newtransfers += '<span id="showTransfers"></span>';
				newtransfers += '<input type="number" min="0" class="form-control number" id="transfersin" value="0" style="text-align: right" disabled />';
                newtransfers += '</label>';
				/*
				var discharges = '';
				discharges += '<div class="col-9">';
                discharges += '<label for="trackDischarges" class="form-label">';
                discharges += 'Planned Discharges (Daily)';
				discharges += '<div class="col-9">';
				discharges += '<input type="number" min="0" class="form-control number" id="trackDischarges" style="text-align: right" />';
				discharges += '</div>';
				discharges += '</label>';
				discharges += '</div>';
				*/
				var epicScore = '';
				//epicScore += '<div class="col-5">';
                epicScore += '<label for="epicAcuityScore" class="col-5 form-label">';
                epicScore += '<span id="easName"></span>';
				epicScore += '<div>';
				if(response.data.useGrid==4){
				epicScore += '<input type="number" min="0" class="form-control number" id="epicAcuityScore" onchange="tj.gridupdate2()" style="text-align: right" />';
				//$('#easTarget').val(response.data.easTarget);
				//$('#easMax').val(response.data.easMax);
				}else{
				epicScore += '<input type="number" min="0" class="form-control number" id="epicAcuityScore" style="text-align: right" />';	
				//$('#easTarget').val('0');
				//$('#easMax').val('0');
				}
				epicScore += '</div>';
				epicScore += '</label>';
				//epicScore += '</div>';
				
				var epicNurse = '';
				//epicNurse += '<div class="col-5">';
                epicNurse += '<label for="epicAcuityNurse" class="col-5 form-label">';
                epicNurse += '<span id="eanName"></span> / Nurses';
				epicNurse += '<div>';
				epicNurse += '<input type="text" class="form-control number" id="epicAcuityNurse" style="text-align: right" disabled/>';
				epicNurse += '</div>';
				epicNurse += '</label>';
				//epicNurse += '</div>';
				
				var epicVar = '';
				//epicVar += '<div class="col-9">';
                epicVar += '<label for="epicAcuityVar" class="col-6 form-label">';
                epicVar += 'RN Variance (<span id="eavName"></span>)';
				epicVar += '<div>';
				epicVar += '<input type="text" class="form-control number" id="epicAcuityVar" style="text-align: right" disabled/>';
				epicVar += '</div>';
				epicVar += '</label>';
				//epicVar += '</div>';
				
				var visits = ''
				visits += '<div class="col-9">';
                visits += '<label for="trackVisits" class="form-label">';
                visits += '<span id="visitsName"></span>';
				visits += '<div class="col-9">';
				visits += '<input type="number" min="0" class="form-control number" id="trackVisits" style="text-align: right" />';
				visits += '</div>';
				visits += '</label>';
				visits += '</div>';
				/*
				if(response.data.flow1!=0 || response.data.flow2!=0 || response.data.flow3!=0 || response.data.flow4!=0 || response.data.flow5!=0 || response.data.flow6!=0 || response.data.flow7!=0 || response.data.flow8!=0){
				f10.style.display='';
				}else{
				f10.style.display='none'
				$('#showTransfers').html('Anticipated Transfers In');
				}
				
				if(response.data.useFlow!=0 && response.data.inFlow!=0 && response.data.acuityTotal!=1){
				f9.style.display='';
				f11.style.display='none';
				$('#newTransfers1').empty().append(newtransfers);
				$('#newTransfers2').empty();
				$('#showTransfers').html('<a href="javascript:;" onclick="tj.showTransfers()" id="showTransfers"><u>Anticipated Transfers In</u></a>');	
				}else if(response.data.useFlow!=0 && response.data.inFlow!=0 && response.data.acuityTotal==1){
				f9.style.display='none';
				f11.style.display='';
				$('#newTransfers2').empty().append(newtransfers);
				$('#newTransfers1').empty();
				$('#showTransfers').html('<a href="javascript:;" onclick="tj.showTransfers()" id="showTransfers"><u>Anticipated Transfers In</u></a>');				
				}else if(response.data.useFlow!=0 && response.data.inFlow==0 && response.data.acuityTotal!=1){
				f9.style.display='';
				f11.style.display='none';
				$('#newTransfers1').empty().append(newtransfers);
				$('#newTransfers2').empty();
				$('#showTransfers').html('Anticipated Transfers In');
				}else if(response.data.useFlow!=0 && response.data.inFlow==0 && response.data.acuityTotal==1){
				f9.style.display='none';
				f11.style.display='';
				$('#newTransfers2').empty().append(newtransfers);
				$('#newTransfers1').empty();
				$('#showTransfers').html('Anticipated Transfers In');
				}else{
					f9.style.display='none';
				f11.style.display='none';
				$('#newTransfers1').empty();
				$('#newTransfers2').empty();
				
				$('#showTransfers').html('Anticipated Transfers In');
				}
				
				if(response.data.flow1!=0){
				f1.style.display='';	
				}
				if(response.data.flow2!=0){
				f2.style.display='';	
				}
				if(response.data.flow3!=0){
				f3.style.display='';	
				}
				if(response.data.flow4!=0){
				f4.style.display='';	
				}
				if(response.data.flow5!=0){
				f5.style.display='';	
				}
				if(response.data.flow6!=0){
				f6.style.display='';	
				}
				if(response.data.flow7!=0){
				f7.style.display='';	
				}
				if(response.data.flow8!=0){
				f8.style.display='';	
				}
				*/
				
				if(response.data.showEpic==1){
					$('#epicScore').empty().append(epicScore);
					$('#epicAcuityScore').val(response.data.epicScore);
				}else{
					$('#epicScore').empty();
					$('#epicAcuityScore').val('0');
				}
				
				let showrntext = String(response.data.showrntest);
				
				$('#varName1').html('RN Variance');
				$('#varName2').html('Grid Variance');
				$('#varName3').html('Grid RN Variance');
				
				if(showrntext.includes("0")){
				a30.style.display='none';
				a34.style.display='none';
				a40.style.display='none';
				}
			
				
				if(showrntext.includes("1") && (response.data.rnvarianceLive==0 || (response.data.rnvarianceLive==1 && response.data.userId !=0))){
				a30.style.display='';
				}else{
				a30.style.display='none';
				}
				
				
				if(showrntext.includes("2")){
				a40.style.display='none';
				}
				if(showrntext.includes("3")){
				a34.style.display='';
				}
				
				if(response.data.hidenote2==1){
				h2.style.display='none';
				}else{
				h2.style.display='';
				}
				if(response.data.hidenote3==1){
				h3.style.display='none';
				}else{
				h3.style.display='';
				}
				if(response.data.hidenote1==1){
				h1.style.display='none';
				}else{
				h1.style.display='';
				}
				if(response.data.hidenote4==1){
				h4.style.display='none';
				}else{
				h4.style.display='';
				}
				
				if(response.data.showEpicNurse==1 && response.data.antecount!=0 && response.data.useGrid==4){
				var epicCalc = (parseFloat(response.data.epicScore) / parseFloat(response.data.antecount)).toFixed(1);
					$('#epicNurse').empty().append(epicNurse);
					$('#epicVar').empty().append(epicVar);
					$('#epicAcuityNurse').val(epicCalc);
					$('#epicAcuityVar').val(response.data.nvariance);
				}else if(response.data.showEpicNurse==1 && response.data.antecount==0 && response.data.useGrid==4){
				//var epicCalc = (parseFloat(response.data.epicScore) / parseFloat(response.data.antecount)).toFixed(1);
					$('#epicNurse').empty().append(epicNurse);
					$('#epicVar').empty().append(epicVar);
					$('#epicAcuityNurse').val('0');
					$('#epicAcuityVar').val('');
				}else if(response.data.useGrid==7){
					a30.style.display='none';
					a301.style.display='';
					a302.style.display='';
					a303.style.display='';	
					$('#varName101').html('L&D RN Variance');
					$('#varName102').html('PP RN Variance');
					$('#varName103').html('NICU RN Variance');
					$('#epicNurse').empty();
					$('#epicVar').empty();
					$('#epicAcuityNurse').val('');
				}else{
					$('#epicNurse').empty();
					$('#epicVar').empty();
					$('#epicAcuityNurse').val('');					
				}
				
				if(response.data.practiceId==1) {
				a33.style.display='';
				a36.style.display='none';
				$('#submittedbyNEW').val(response.data.adminId);
				$('#submittedby').val('');
				}else{
				a33.style.display='none';
				a36.style.display='none';
				$('#submittedbyNEW').val(response.data.adminId);
				$('#submittedby').val('');
				}
				//console.log('adminId',response.data.adminId);
				/*
				var length = users.length;
				var usersBody = $('#usersbody');
				var newrow = '';
				    for (var i = 0; i < length; i++) {
					newrow += '<option value="' + users[i].userId + '">';
					newrow += users[i].last_name;
					newrow += '</option>';
					}
				usersBody.empty().append(users.user);
				}
				*/
				
				//if(response.data.userId==0 || response.data.practiceId==1){
				//$('#submittedbyNEW').val('0');
				//}else{
					
				//}
				
				//if(response.data.acuityTotal==1 && response.data.trackdc==1){
				//$('#discharges2').empty().append(discharges);
				//$('#discharges3').empty();
				//}else if(response.data.acuityTotal!=1 && response.data.trackdc==1){
				//$('#discharges3').empty().append(discharges);
				//$('#discharges2').empty();
				//}else{
				$('#discharges3').empty();
				$('#discharges2').empty();
					
				//}
				
				if (response.data.prodMeasure==4 && response.data.acuityTotal==1 && response.data.shift==52 && (response.data.showVisits==1 || response.data.showVisits==3)) {
				$('#visits2').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Yesterday (' + response.data.reportdate2 + ')');
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal!=1 && response.data.shift==52 && (response.data.showVisits==1 || response.data.showVisits==3)) {
				$('#visits1').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Yesterday (' + response.data.reportdate2 + ')');
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal==1 && response.data.shift!=52 && response.data.shift<15 && response.data.showVisits>=2) {
				$('#visits2').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Today (' + response.data.reportdate + ')');	
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal!=1 && response.data.shift!=52 && response.data.shift<15 && response.data.showVisits>=2) {
				$('#visits1').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Today (' + response.data.reportdate + ')');	
				}else{
				$('#visits1').empty();
				$('#visits2').empty();
				$('#visitsName').empty();
				}
				
				
				var html = '';
				var html2 = '';
				
				var nurseCount = $('#nCount');
				//console.log('acuityTotal',response.data.acuityTotal);
				//console.log('unlocked',response.data.unlockedPatientCount);				
				if (response.data.acuityTotal ==1 || response.data.nurseTrack==1 || (response.data.unlockedPatientCount ==1 && response.data.acuityTotal!=0 && response.data.deptId !=496 && response.data.deptId !=502)) {
				a8.style.display='none';
				a9.style.display='none';
				}else if(response.data.acuityTotal==0 || response.data.deptId==496 || response.data.deptId==502){
				a8.style.display='';
				a9.style.display='none';	
				}else{
				a8.style.display='';
				a9.style.display='';					
				}
				
				
				/*
				if(response.data.trackInsert==999){
					$('#trackInsert').val('1');
				}else{
					$('#trackInsert').val('0');
				}
				console.log('insert',response.data.trackInsert);
				console.log('Trackinsert',$('#trackInsert').val());
				
				if (response.data.trackdc==1) {
				a25.style.display='';
				}else{
				a25.style.display='none';					
				}
				*/
				if (response.data.showHPPS==0) {
				f12.style.display='none';
				f13.style.display='';
				}else{
				f12.style.display='';
				f13.style.display='none';				
				}
				if (response.data.patientTotalDesc2.length>0) {
				a39.style.display='';
				}else{
				a39.style.display='none';					
				}
				if (response.data.patientTotalDesc3.length>0) {
				a50.style.display='';
				}else{
				a50.style.display='none';					
				}
				//console.log('length ',response.data.patientTotalDesc3.length);
				if (response.data.patientTotalDesc4.length>0) {
				a51.style.display='';
				}else{
				a51.style.display='none';					
				}
				if (response.data.ptTotal2==1 || response.data.ptTotal3==1 || response.data.ptTotal4==1) {
				//document.getElementById("patienttotalNEW").disabled = true;
				a52.style.display='none';
				}else{
				//document.getElementById("patienttotalNEW").disabled = false;
				a52.style.display='';					
				}
				//if(response.data.deptId==536){
				document.getElementById("patienttotalNEW").disabled = true;
				//}else{
				//document.getElementById("patienttotalNEW").disabled = false;
				//}
				if (response.data.chargeDesc=='') {
				a37.style.display='none';
				}else{
				a37.style.display='';					
				}
				if (response.data.nurseDesc=='') {
				a38.style.display='none';
				}else{
				a38.style.display='';					
				}
				if (response.data.track1Desc=='') {
				a26.style.display='none';
				}else{
				a26.style.display='';					
				}
				if (response.data.track2Desc=='') {
				a27.style.display='none';
				}else{
				a27.style.display='';					
				}
				if (response.data.track3Desc=='') {
				a28.style.display='none';
				}else{
				a28.style.display='';					
				}
				if (response.data.track4Desc=='') {
				a29.style.display='none';
				}else{
				a29.style.display='';					
				}
							
				if (response.data.trackdata==1) {
				a24.style.display='';
				}else{
				a24.style.display='none';					
				}
				if (response.data.nurse1Label ==0) {
				a10.style.display='none';
				}else{
				a10.style.display='';
				}
				if (response.data.nurse2Label ==0) {
				a31.style.display='none';
				}else{
				a31.style.display='';
				}
				if (response.data.sLabel ==0) {
				a22.style.display='none';
				}
				if (response.data.tLabel ==0) {
				a32.style.display='none';
				}
				if (response.data.churnLabel ==0) {
				a15.style.display='none';
				}else{
				a15.style.display='';
				}
				if (response.data.other1Label==0) {
				a16.style.display='none';
				}else{
				a16.style.display='';
				}
				if (response.data.sittersLabel==0) {
				a23.style.display='none';
				}else{
				a23.style.display='';
				}
				if (response.data.other2Label==0) {
				a17.style.display='none';
				}else{
				a17.style.display='';
				}
				if (response.data.other3Label==0) {
				a18.style.display='none';
				}else{
				a18.style.display='';
				}
				if (response.data.acuity1 ==0 || response.data.desc1=='') {
				a1.style.display='none';
				}else{
				a1.style.display='';
				}
				if (response.data.acuity2 ==0 || response.data.desc2=='') {
				a2.style.display='none';
				}else{
				a2.style.display='';
				}
				if (response.data.acuity3 ==0 || response.data.desc3=='') {
				a3.style.display='none';
				}else{
				a3.style.display='';
				}
				if (response.data.acuity4 ==0 || response.data.desc4=='') {
				a4.style.display='none';
				}else{
				a4.style.display='';
				}
				if (response.data.acuity5 ==0 || response.data.desc5=='') {
				a5.style.display='none';
				}else{
				a5.style.display='';
				}
				if (response.data.acuity6 ==0 || response.data.desc6=='') {
				a6.style.display='none';
				}else{
				a6.style.display='';
				}
				if (response.data.extacuity6 ==0 || response.data.extdesc6=='') {
				e6.style.display='none';
				}else{
				e6.style.display='';
				}
				if (response.data.checkboxName=='') {
				e7.style.display='none';
				}else{
				e7.style.display='';
				}
				if (response.data.dash5 ==1 && response.data.desc7 !='') {
				a11.style.display='';
				}
				if (response.data.acuity8 ==1 && response.data.desc8 !='') {
				a12.style.display='';
				}
				if (response.data.acuity9 ==1 && response.data.desc9 !='') {
				a13.style.display='';
				}
				if (response.data.acuity10 ==1 && response.data.desc10 !='') {
				a19.style.display='';
				}
				if (response.data.acuity11 ==1 && response.data.desc11 !='') {
				a20.style.display='';
				}
				if (response.data.acuity12 ==1 && response.data.desc12 !='') {
				a201.style.display='';
				}
				if (response.data.churn ==0) {
				a7.style.display='none';
				}else{
				a7.style.display='none';
				}
				//console.log('dp',response.data.displayProd);
				if (response.data.displayProd ==1){
				$('#varianceNEW').html(response.data.currentVar + ' hrs');
				}else if(response.data.displayProd ==0){
				$('#varianceNEW').html(response.data.nvariance + ' RNs');
				}else if(response.data.displayProd ==4){
				$('#varianceNEW').html(response.data.ghrsVariance + ' hrs');
				}else if(response.data.displayProd ==5){
				$('#varianceNEW').html(response.data.nedocScore);
				}else{
				$('#varianceNEW').html(response.data.roundnvariance);					
				}
				
				$('#varianceName').html(response.data.varianceName);	
				
				if(parseInt(response.data.antecount)>0){
				var currentRatio = (response.data.atotal / parseFloat(response.data.antecount)).toFixed(1);
				}else{
				var currentRatio = 0;	
				}
				
				$('#hppsNEW').html('HPPS TARGET: ' + response.data.hppsTarget + ' / ACTUAL: '+ response.data.hppsActual);
				$('#ratioNEW').html(currentRatio + ' : 1');
				
				if ((response.data.acuity7 ==1 && response.data.dash5 == 1) || response.data.acuity8 ==1 || response.data.acuity9 ==1 || response.data.acuity10 ==1 || response.data.acuity11 ==1 || response.data.acuity12 ==1 || response.data.showEpic==1) {
				document.getElementById('ptTitle').style.display='';
				}else{
				document.getElementById('ptTitle').style.display='none';				
				}
				
				if(response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4 || response.data.useGrid==6 || response.data.useGrid==7){
				document.getElementById("cngrid1").style.display='';
				document.getElementById("cngrid2").style.display='';
				document.getElementById("rngrid1").style.display='';
				document.getElementById("rngrid2").style.display='';
				document.getElementById("pctgrid1").style.display='';
				document.getElementById("pctgrid2").style.display='';
				document.getElementById("secgrid1").style.display='';
				document.getElementById("secgrid2").style.display='';
				document.getElementById("rn1grid1").style.display='';
				document.getElementById("rn1grid2").style.display='';
				document.getElementById("other2grid1").style.display='';
				document.getElementById("other2grid2").style.display='';
				document.getElementById("other3grid1").style.display='';
				document.getElementById("other3grid2").style.display='';
				document.getElementById("rn2grid1").style.display='';
				document.getElementById("rn2grid2").style.display='';
				document.getElementById("sittergrid1").style.display='';
				document.getElementById("sittergrid2").style.display='';
				document.getElementById("other1grid1").style.display='';
				document.getElementById("other1grid2").style.display='';
		
				a61.classList.add("col-md-5");
				a62.classList.add("col-md-5");
				a63.classList.add("col-md-5");
				a64.classList.add("col-md-5");
				a65.classList.add("col-md-5");
				a66.classList.add("col-md-5");
				a67.classList.add("col-md-5");
				a68.classList.add("col-md-5");
				a69.classList.add("col-md-5");
				a70.classList.add("col-md-5");
				}else{
				
				a61.classList.add("col-md-6");
				a62.classList.add("col-md-6");
				a63.classList.add("col-md-6");
				a64.classList.add("col-md-6");
				a65.classList.add("col-md-6");
				a66.classList.add("col-md-6");
				a67.classList.add("col-md-6");
				a68.classList.add("col-md-6");
				a69.classList.add("col-md-6");
				a70.classList.add("col-md-6");
				}
				
				if(response.data.halfNurse==1){
				html += '<option value=0>0</option>';
				html += '<option value=0.5>0.5</option>';
				html += '<option value=1>1</option>';
				html += '<option value=1.5>1.5</option>';
				html += '<option value=2>2</option>';
				html += '<option value=2.5>2.5</option>';
				html += '<option value=3>3</option>';
				html += '<option value=3.5>3.5</option>';
				html += '<option value=4>4</option>';
				html += '<option value=4.5>4.5</option>';
				html += '<option value=5>5</option>';
				html += '<option value=5.5>5.5</option>';
				html += '<option value=6>6</option>';
				html += '<option value=6.5>6.5</option>';
				html += '<option value=7>7</option>';
				html += '<option value=7.5>7.5</option>';
				html += '<option value=8>8</option>';
				html += '<option value=8.5>8.5</option>';
				html += '<option value=9>9</option>';
				html += '<option value=9.5>9.5</option>';
				html += '<option value=10>10</option>';
				html += '<option value=10.5>10.5</option>';
				html += '<option value=11>11</option>';
				html += '<option value=11.5>11.5</option>';
				html += '<option value=12>12</option>';
				html += '<option value=12.5>12.5</option>';
				html += '<option value=13>13</option>';
				html += '<option value=13.5>13.5</option>';
				html += '<option value=14>14</option>';
				html += '<option value=14.5>14.5</option>';
				html += '<option value=15>15</option>';
				html += '<option value=15.5>15.5</option>';
				html += '<option value=16>16</option>';
				html += '<option value=16.5>16.5</option>';
				html += '<option value=17>17</option>';
				html += '<option value=17.5>17.5</option>';
				html += '<option value=18>18</option>';
				html += '<option value=18.5>18.5</option>';
				html += '<option value=19>19</option>';
				html += '<option value=19.5>19.5</option>';
				html += '<option value=20>20</option>';
				}else{
				html += '<option value=0>0</option>';
				html += '<option value=1>1</option>';
				html += '<option value=2>2</option>';
				html += '<option value=3>3</option>';
				html += '<option value=4>4</option>';
				html += '<option value=5>5</option>';
				html += '<option value=6>6</option>';
				html += '<option value=7>7</option>';
				html += '<option value=8>8</option>';
				html += '<option value=9>9</option>';
				html += '<option value=10>10</option>';
				html += '<option value=11>11</option>';
				html += '<option value=12>12</option>';
				html += '<option value=13>13</option>';
				html += '<option value=14>14</option>';
				html += '<option value=15>15</option>';
				html += '<option value=16>16</option>';
				html += '<option value=17>17</option>';
				html += '<option value=18>18</option>';
				html += '<option value=19>19</option>';
				html += '<option value=20>20</option>';
				}
				//nurseCount.empty().append(html);
				//$('#resources').empty().append(html2);
				$('#chargeDesc').html(response.data.chargeDesc);
				$('#easName').html(response.data.easTerm);
				$('#eavName').html(response.data.easTerm);
				$('#eanName').html(response.data.easTerm);
				$('#nurse1Desc').html(response.data.nurse1Desc);
				$('#nurse2Desc').html(response.data.nurse2Desc);
				$('#other1Desc').html(response.data.other1Label);
				$('#other2Desc').html(response.data.other2Label);
				$('#other3Desc').html(response.data.other3Label);
				$('#sittersNEWDesc').html(response.data.sittersNEWDesc);
				$('#customChurnLabel').html(response.data.churnLabel);
				$('#customChurn').val(response.data.customChurn);
				$('#patientTotalDesc').html(response.data.patientTotalDesc);
				$('#patientTotalDesc2').html(response.data.patientTotalDesc2);
				$('#patientTotalDesc3').html(response.data.patientTotalDesc3);
				$('#patientTotalDesc4').html(response.data.patientTotalDesc4);
				
				if(response.data.submittedby.length>0){
				$('#userNameNEW').html(response.data.submittedby);
				}else{
				$('#userNameNEW').html(response.data.first_name+' '+response.data.last_name);
				}
				/*
				if(response.data.userId==0 && response.isTime==1){
				$('#chargecountNEW').val(response.timeData.pos1Count);
				$('#showgridvariance').val('0');
				$('#showgridrnvariance').val('0');
				$('#deleteRecord').html('');
				$('#nursecountNEW').val(response.timeData.pos2Count);
				$('#nurse1_add').val(response.timeData.pos3Count);
				$('#nurse2_add').val(response.timeData.pos4Count);
				$('#techcountNEW').val(response.timeData.pos5Count);
				$('#seccountNEW').val(response.timeData.pos7Count);
				$('#other1').val(response.timeData.pos6Count);
				$('#other2').val(response.timeData.pos9Count);
				$('#clockedIn').html(' (PER KRONOS)');
				$('#sittersNEW').val(response.timeData.pos8Count);
				$('#other3').val(response.timeData.pos10Count);
				if(response.data.userId==0 && response.isTime==0){
					*/
				if(response.data.userId==0){
				$('#chargecountNEW').val(response.data.charge1);
				$('#showgridvariance').val('0');
				$('#showgridrnvariance').val('0');
				$('#deleteRecord').html('');
				$('#techcountNEW').val(response.data.techcount);
				$('#seccountNEW').val(response.data.seccount);
				$('#sittersNEW').val(response.data.sittercount);
				$('#nursecountNEW').val(response.data.antecount1);
				$('#nurse1_add').val(response.data.customNurse);
				$('#nurse2_add').val(response.data.customNurse2);
				$('#other1').val(response.data.otherNurse1);
				$('#other2').val(response.data.otherNurse2);
				$('#other3').val(response.data.otherNurse3);
				$('#clockedIn').html('');
				}else{
				$('#deleteRecord').html('<a href="javascript:;" onclick="tj.alertDelete();">Delete Record</a>');
				$('#chargecountNEW').val(response.data.chargecount1);
				$('#techcountNEW').val(response.data.techcount);
				$('#seccountNEW').val(response.data.seccount);
				$('#sittersNEW').val(response.data.sittercount);
				$('#nursecountNEW').val(response.data.antecount1);
				$('#nurse1_add').val(response.data.customNurse);
				$('#nurse2_add').val(response.data.customNurse2);
				$('#other1').val(response.data.otherNurse1);
				$('#other2').val(response.data.otherNurse2);
				$('#other3').val(response.data.otherNurse3);
				$('#clockedIn').html('');
				}
				
				$('#patienttotalNEW').val(response.data.atotal);
				$('#patienttotalNEW2').val(response.data.patientCount2);
				$('#patienttotalNEW3').val(response.data.patientCount3);
				$('#patienttotalNEW4').val(response.data.patientCount4);
				$('#openbedsNEW').html(response.data.openbeds);
				if(response.data.reportdate=='00/00/0000 00:00'){
				$('#reportdateNEW').html('');	
				}else{
				$('#reportdateNEW').html(response.data.reportdate);
				}
				//$('#reportshiftNEW').html(response.data.reportshift);
				$('#deptIdNEW').val(response.data.deptId);
				$('#shiftNEW').val(response.data.shift);
				$('#hppdNEW').val(response.data.hppd);
				$('#secLabel').html(response.data.secLabel);
				$('#techLabel').html(response.data.techLabel);
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
				$('#extdesc6').html(response.data.extdesc6);
				$('#checkboxName').html(response.data.checkboxName);
				if (response.data.checkBox ==1){
				document.getElementById("checkBox").checked = true; 
				}else{
				document.getElementById("checkBox").checked = false;
				}
				$('#desc7').html(response.data.desc7);
				$('#desc8').html(response.data.desc8);
				$('#desc9').html(response.data.desc9);
				$('#desc10').html(response.data.desc10);
				$('#desc11').html(response.data.desc11);
				$('#desc12').html(response.data.desc12);
				$('#oneto1').val(response.data.oneto1);
				$('#oneto2').val(response.data.oneto2);
				$('#oneto6').val(response.data.oneto6);
				$('#extoneto6').val(response.data.extoneto6);
				$('#oneto7').val(response.data.oneto7);
				$('#oneto8').val(response.data.oneto8);
				$('#oneto9').val(response.data.oneto9);
				$('#oneto10').val(response.data.oneto10);
				$('#oneto11').val(response.data.oneto11);
				$('#oneto12').val(response.data.oneto12);
				$('#admissions1').val(response.data.admits);
				$('#transfers1').val(response.data.transfers);
				$('#discharges1').val(response.data.discharges);				
				$('#dayNEW').val(response.data.dayDate);
				$('#prodMeasureNEW').val(response.data.prodMeasure);
				$('#unlocked').val(response.data.unlockedPatientCount);
				$('#grid1opt').val(response.data.gridOpt1);
				$('#grid2opt').val(response.data.gridOpt2);
				$('#grid3opt').val(response.data.gridOpt3);
				$('#rnvar').val(response.data.displayProd);
			
				//$('#blocked').html('<a href="javascript:;" onclick="blockedBeds('+response.data.accountId+','+response.data.deptId+');">  '+response.data.blockedBeds+'</a>');
				$('#dataIdNEW').val(response.data.id);
				//console.log('dataId',response.data.id);
				//$('#dataId2NEW').html('<a href="javascript:;" onclick="tj.getNurseView('+response.data.dataIdNEW+')">Print View</a>');
				$('#prodnoteNEW').val(response.data.note);
				$('#notebody').val(response.data.note);
				
				$('#patientcountTitle').html(response.data.patientcountTitle + ' ' + response.data.patienttitleExt);
				
				$('#depNAME').html(response.data.depname);
				
				$('#aproductivityNEW').html(response.data.nproductivity);
				//$('#avarianceNEW').html(response.data.avariance);
				$('#nurseDesc').html(response.data.nurseDesc);
				//$('#patientCustomDivider').html(response.data.patientCustomDivider);
				
				$('#trackDC').val(response.data.trackdc);
				$('#trackAdd').val(response.data.trackdata);
				
				//$('#trackDischarges').val(response.data.trackDischarges);
				$('#track1Desc').html(response.data.track1Desc);
				$('#track2Desc').html(response.data.track2Desc);
				$('#track3Desc').html(response.data.track3Desc);
				$('#track4Desc').html(response.data.track4Desc);
				$('#track1').val(response.data.track1);
				$('#track2').val(response.data.track2);
				$('#track3').val(response.data.track3);
				$('#track4').val(response.data.track4);
				$('#trackNote1').val(response.data.notetrack1);
				$('#trackNote2').val(response.data.notetrack2);
				$('#trackNote3').val(response.data.notetrack3);
				$('#trackNote4').val(response.data.notetrack4);
				$('#trackVisits').val(response.data.visitsSubmitted);
				$('#LinkedId').val(response.data.linkedId);
				$('#LinkedDept').val(response.data.linkedDept);
				
				if(response.data.useGrid==2 || response.data.useGrid==3){
				$('#cnGrid').val(response.data.gcn);
				$('#rnGrid').val(response.data.grn);
				$('#pctGrid').val(response.data.gpct);
				$('#secGrid').val(response.data.gsec);
				$('#rn1Grid').val(response.data.grn1);
				$('#rn2Grid').val(response.data.grn2);
				$('#other2Grid').val(response.data.gother2);
				$('#other3Grid').val(response.data.gother3);
				$('#other1Grid').val(response.data.gother1);
				$('#sitterGrid').val(response.data.gsitter);
				$('#cnVariance').val(response.data.gcnvar);
				$('#rnVariance').val(response.data.grnvar);
				$('#pctVariance').val(response.data.gpctvar);
				$('#secVariance').val(response.data.gsecvar);
				$('#rn1Variance').val(response.data.grn1var);
				$('#rn2Variance').val(response.data.grn2var);
				$('#other1Variance').val(response.data.gother1var);
				$('#sitterVariance').val((parseInt(response.data.gsitvar) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.gother2var) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.gother3var) * parseInt(response.data.gridOpt2)));
				$('#other2Gridval').html(response.data.gother2);
				$('#other2Varianceval').html(response.data.gother2var);
				}else if(response.data.useGrid==7){
				$('#cnGrid').val(response.data.grid1);
				$('#rnGrid').val(response.data.grid2);
				$('#rn1Grid').val(response.data.grid3);
				$('#rn2Grid').val(response.data.grid4);
				$('#pctGrid').val(response.data.grid5);
				$('#secGrid').val(response.data.grid6);
				$('#other1Grid').val(response.data.grid7);
				$('#sitterGrid').val(response.data.grid8);
				$('#other2Grid').val(response.data.grid9);
				$('#other3Grid').val(response.data.grid10);
				
				$('#cnVariance').val(response.data.gridvar1);
				$('#rnVariance').val(response.data.gridvar2);
				$('#rn1Variance').val(response.data.gridvar3);
				$('#rn2Variance').val(response.data.gridvar4);
				$('#pctVariance').val(response.data.gridvar5);
				$('#secVariance').val(response.data.gridvar6);
				$('#other1Variance').val(response.data.gridvar7);
				
				$('#sitterVariance').val((parseInt(response.data.gridvar8) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.gridvar9) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.gridvar10) * parseInt(response.data.gridOpt2)));
				
				$('#other2Gridval').html('');
				$('#other2Varianceval').html('');
				}else{
				$('#cnGrid').val(response.data.cn);
				$('#rnGrid').val(response.data.rn);
				$('#pctGrid').val(response.data.pct);
				$('#secGrid').val(response.data.sec);
				$('#rn1Grid').val(response.data.rn1);
				$('#rn2Grid').val(response.data.rn2);
				$('#other2Gridval').html(response.data.other2);
				$('#other2Grid').val(response.data.other2);
				$('#other3Grid').val(response.data.other3);
				$('#other1Grid').val(response.data.other1);
				$('#sitterGrid').val(response.data.sitter);
				$('#cnVariance').val(response.data.cnvar);
				$('#rnVariance').val(response.data.rnvar);
				$('#pctVariance').val(response.data.pctvar);
				$('#secVariance').val(response.data.secvar);
				$('#rn1Variance').val(response.data.rn1var);
				$('#rn2Variance').val(response.data.rn2var);
				$('#other2Varianceval').html(response.data.other2var);
				$('#sitterVariance').val((parseInt(response.data.sitvar) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.other2var) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.other3var) * parseInt(response.data.gridOpt2)));
				$('#other1Variance').val(response.data.other1var);
				
				}
				
				if(response.data.userId!=0){
				$('#showgridvariance').val(response.data.gvariance);
				$('#showgridrnvariance').val(response.data.gridrnvariance);
				}else{
				$('#showgridvariance').val('0');
				$('#showgridrnvariance').val('0');
				}						
				$('#useGrid').val(response.data.useGrid);		
				$('#oneto1Acuity').val(response.data.oneto1Acuity);
				$('#oneto2Acuity').val(response.data.oneto2Acuity);
				$('#oneto3Acuity').val(response.data.oneto3Acuity);
				$('#oneto4Acuity').val(response.data.oneto4Acuity);
				$('#oneto5Acuity').val(response.data.oneto5Acuity);
				$('#oneto6Acuity').val(response.data.oneto6Acuity);
				$('#countchargereport').val(response.data.countcharge);
				$('#rnreport').val(response.data.rnCount);
				$('#rn1report').val(response.data.rn1Count);
				$('#rn2report').val(response.data.rn2Count);
				$('#showrnreport').val(response.data.showrn);
				$('#showrnvariance').val(response.data.nvariance);
				$('#showrnvariance1').val(response.data.nvariance1);
				//console.log('show1',response.data.nvariance1);
				$('#showrnvariance2').val(response.data.nvariance2);
				$('#showrnvariance3').val(response.data.nvariance3);
				$('#submitreqd').val(response.data.practiceId);
				$('#varComment').html(response.data.varMessage);
				$('#actionPlan').html(response.data.actionPlan);
				$('#roundRN').val(response.data.roundrn);
				$('#minrn').val(response.data.minRN);
				$('#nedocs').val(response.data.displayProd);
				$('#userIdNEW').val(response.data.adminId);
				$('#roleNEW').val(response.data.role);
				
				$('#varmsg').val(response.data.varMessage);
				$('#pttotal2').val(response.data.ptTotal2);
				$('#pttotal3').val(response.data.ptTotal3);
				$('#pttotal4').val(response.data.ptTotal4);
				$('#other1Calc').val(response.data.other1Calc);
				
				//console.log('user ',response.data.userId);
				//console.log('gother',response.data.gother1var);
				//console.log('other',response.data.other1var);
				
				/*
				$('#trackflow1Desc').html(response.data.flowDept1);
				
				if(response.transfers===null || response.transfers.outflowCount1===undefined){
				$('#trackflow1').val('0');
				}else{
				$('#trackflow1').val(response.transfers.outflowCount1);
				}
				$('#trackflow2Desc').html(response.data.flowDept2);
				
				if(response.transfers===null || response.transfers.outflowCount2===undefined){
				$('#trackflow2').val('0');
				}else{
				$('#trackflow2').val(response.transfers.outflowCount2);
				}
				
				$('#trackflow3Desc').html(response.data.flowDept3);
				
				if(response.transfers===null || response.transfers.outflowCount3===undefined){
				$('#trackflow3').val('0');
				}else{
				$('#trackflow3').val(response.transfers.outflowCount3);
				}
			
				$('#trackflow4Desc').html(response.data.flowDept4);
				
				if(response.transfers===null || response.transfers.outflowCount4===undefined){
				$('#trackflow4').val('0');
				}else{
				$('#trackflow4').val(response.transfers.outflowCount4);
				}
			
				$('#trackflow5Desc').html(response.data.flowDept5);
				
				if(response.transfers===null || response.transfers.outflowCount5===undefined){
				$('#trackflow5').val('0');
				}else{
				$('#trackflow5').val(response.transfers.outflowCount5);
				}
				
				$('#trackflow6Desc').html(response.data.flowDept6);
				
				if(response.transfers===null || response.transfers.outflowCount6===undefined){
				$('#trackflow6').val('0');
				}else{
				$('#trackflow6').val(response.transfers.outflowCount6);
				}
				
				$('#trackflow7Desc').html(response.data.flowDept7);
				
				if(response.transfers===null || response.transfers.outflowCount7===undefined){
				$('#trackflow7').val('0');
				}else{
				$('#trackflow7').val(response.transfers.outflowCount7);
				}
				
				$('#trackflow8Desc').html(response.data.flowDept8);
				
				if(response.transfers===null || response.transfers.outflowCount8===undefined){
				$('#trackflow8').val('0');
				}else{
				$('#trackflow8').val(response.transfers.outflowCount8);
				}
				
				$('#flowdept1').val(response.data.flow1);
				$('#flowdept2').val(response.data.flow2);
				$('#flowdept3').val(response.data.flow3);
				$('#flowdept4').val(response.data.flow4);
				$('#flowdept5').val(response.data.flow5);
				$('#flowdept6').val(response.data.flow6);
				$('#flowdept7').val(response.data.flow7);
				$('#flowdept8').val(response.data.flow8);
				$('#transfersin').val(response.data.inFlow);
				$('#UseFlow').val(response.data.useFlow);
				$('#flowAcuity').val(response.data.averageAcuity);
				*/
				
				$('#countNEW').val(response.data.submitCount);
				$('#useEAS').val(response.data.showEpic);
				$('#useEASN').val(response.data.showEpicNurse);
				$('#resourcesTitle').html(response.data.resourcesTitle);
				$('#unscheduledNEW').val('0');
				
				$('#add1').val(response.data.add1);
				$('#add2').val(response.data.add2);
				$('#add3').val(response.data.add3);
				$('#add4').val(response.data.add4);
				$('#add5').val(response.data.add5);
				$('#add6').val(response.data.add6);
				$('#extadd6').val(response.data.extadd6);
				$('#bedCount').val(response.data.bedCount);
				$('#newRecord').val(response.data.userId);
				
				
				console.log('newRecord',response.data.userId);
				//console.log('pt3',response.data.patientCount3);
				//console.log('pt4',response.data.patientCount4);
				//console.log('bedCount',response.data.bedCount);
								
				$('#addNEW').modal('show');
					if(response.data.userId !=0){
					tj.gridupdate5();
					}
				}
        })
        //console.log('record updated sucessfully',dataId);
		
		
  } 
 
 tj.editUnscheduled = function(deptNum) {
		document.getElementById("hidden1").style.display='';
		document.getElementById("hidden2").style.display='';
		document.getElementById("hidden3").style.display='';
		document.getElementById("hidden4").style.display='';
		document.getElementById("hidden5").style.display='';
		document.getElementById("hidden6").style.display='';
		document.getElementById("secView").style.display='';
		document.getElementById("hidden7").style.display='none';
		document.getElementById("transfers").style.display='none';
		document.getElementById("hidden8").style.display='none';
		document.getElementById("hidden9").style.display='none';
		document.getElementById("hidden10").style.display='none';
		document.getElementById("hidden11").style.display='none';
		document.getElementById("hidden12").style.display='none';
		document.getElementById("showchurn1").style.display='none';
		document.getElementById("customChurn1").style.display='none';
		document.getElementById("Nurse1").style.display='none';
		document.getElementById("other1hide").style.display='none';
		document.getElementById("other2hide").style.display='none';
		document.getElementById("other3hide").style.display='none';
		document.getElementById("sittersNEWhide").style.display='none';
		document.getElementById('ptTitle').style.display='none';
		document.getElementById("cngrid1").style.display='none';
		document.getElementById("cngrid2").style.display='none';
		document.getElementById("rngrid1").style.display='none';
		document.getElementById("rngrid2").style.display='none';
		document.getElementById("pctgrid1").style.display='none';
		document.getElementById("pctgrid2").style.display='none';
		document.getElementById("secgrid1").style.display='none';
		document.getElementById("secgrid2").style.display='none';
		document.getElementById("rn1grid1").style.display='none';
		document.getElementById("rn1grid2").style.display='none';
		document.getElementById("other2grid1").style.display='none';
		document.getElementById("other2grid2").style.display='none';
		document.getElementById("sittergrid1").style.display='none';
		document.getElementById("sittergrid2").style.display='none';
		document.getElementById("rn2grid1").style.display='none';
		document.getElementById("rn2grid2").style.display='none';
		document.getElementById("other3grid1").style.display='none';
		document.getElementById("other3grid2").style.display='none';
		document.getElementById("other1grid1").style.display='none';
		document.getElementById("other1grid2").style.display='none';
		document.getElementById("hidernvariance").style.display='none';
		document.getElementById("hidernvariance1").style.display='none';
		document.getElementById("hidernvariance2").style.display='none';
		document.getElementById("hidernvariance3").style.display='none';
		document.getElementById("hidegridvariance").style.display='none';
		document.getElementById("hidegridrnvariance").style.display='none';
		document.getElementById("submitted").style.display='none';
		document.getElementById("techView").style.display='';
		document.getElementById("submittedNEW").style.display='none';
		document.getElementById("showSubmit2").style.display='none';
		document.getElementById("hiddentotal2").style.display='none';
		document.getElementById("showHPPS").style.display='none';
		document.getElementById("showratio").style.display='none';
		document.getElementById("showtrack").style.display='none';
		$('#zeropts').val('0');
		var currentHour = moment().format('HH');
		var currentDay = moment().format('DD');
		
		var currentdayDate = moment().format('YYYY-MM-DD');
		var currentReportDate = moment().format('MM/DD/YYYY');
		var currentReportTime = moment().format('HH:mm');
		var currentHour = moment().format('HH');
		
		if(parseInt(deptNum)>0){
		var deptId = deptNum;
		}else{
		var deptId = $('#unscheduleddeptId').val();
		}
		
		if(deptId == 0){
		bootbox.alert('Unit Required');
		return;	
		}
		$('#unscheduled').modal('hide');
		console.log('unscheduled dept ',deptId);
		console.log('hour ',currentHour);
		
        $.ajax({
            url:'inc/data.php?req=getunscheduledDetails',
            data:{
                deptId: deptId,
				currentHour: currentHour
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#acuityDesc').html('Additional Patient Data');
				var a1 = document.getElementById('hidden1');
				var a2 = document.getElementById('hidden2');	
				var a3 = document.getElementById('hidden3');	
				var a4 = document.getElementById('hidden4');	
				var a5 = document.getElementById('hidden5');	
				var a6 = document.getElementById('hidden6');
				var a7 = document.getElementById('showchurn1');
				var a8 = document.getElementById('hiddentotal1');
				var a9 = document.getElementById('hiddentotal2');
				var a10 = document.getElementById('Nurse1');
				var a11 = document.getElementById('hidden7');
				var a12 = document.getElementById('hidden8');
				var a13 = document.getElementById('hidden9');
				var a15 = document.getElementById('customChurn1');
				var a16 = document.getElementById('other1hide');
				var a17 = document.getElementById('other2hide');
				var a18 = document.getElementById('other3hide');
				var a19 = document.getElementById('hidden10');
				var a20 = document.getElementById('hidden11');
				var a201 = document.getElementById('hidden12');
				var a21 = document.getElementById('acuityDesc');
				var a22 = document.getElementById('secView');
				var a23 = document.getElementById('sittersNEWhide');
				var a24 = document.getElementById('showtrack');
				//var a25 = document.getElementById('hiddendischarges');
				var a26 = document.getElementById('hidetrack1');
				var a27 = document.getElementById('hidetrack2');
				var a28 = document.getElementById('hidetrack3');
				var a29 = document.getElementById('hidetrack4');
				var a30 = document.getElementById('hidernvariance');
				var a301 = document.getElementById('hidernvariance1');
				var a302 = document.getElementById('hidernvariance2');
				var a303 = document.getElementById('hidernvariance3');
				var a31 = document.getElementById('Nurse2');
				var a32 = document.getElementById('techView');
				var a33 = document.getElementById('submitted');
				var a34 = document.getElementById('hidegridvariance');
				var a40 = document.getElementById('hidegridrnvariance');
				var a35 = document.getElementById('hiddenvisits');
				var a36 = document.getElementById('submittedNEW');
				var a37 = document.getElementById('charge0');
				var a38 = document.getElementById('nurse0');
				var a39 = document.getElementById('currentPatients2');
				var a50 = document.getElementById('currentPatients3');
				var a51 = document.getElementById('currentPatients4');
				var a52 = document.getElementById('currentPatients');
				
				var a61 = document.getElementById('pos1');
				var a62 = document.getElementById('pos2');
				var a63 = document.getElementById('pos3');
				var a64 = document.getElementById('pos4');
				var a65 = document.getElementById('pos5');
				var a66 = document.getElementById('pos6');
				var a67 = document.getElementById('pos7');
				var a68 = document.getElementById('pos8');
				var a69 = document.getElementById('pos9');
				var a70 = document.getElementById('pos10');
				var f12 = document.getElementById('showHPPS');
				var f13 = document.getElementById('showratio');
				
				a61.className="";
				a62.className="";
				a63.className="";
				a64.className="";
				a65.className="";
				a66.className="";
				a67.className="";
				a68.className="";
				a69.className="";
				a70.className="";
				
				var newtransfers = '';
                newtransfers += '<label for="transfersin" class="col-6">';
				newtransfers += '<span id="showTransfers"></span>';
				newtransfers += '<input type="number" min="0" class="form-control number" id="transfersin" value="0" style="text-align: right" disabled />';
                newtransfers += '</label>';
				/*
				var discharges = '';
				discharges += '<div class="col-9">';
                discharges += '<label for="trackDischarges" class="form-label">';
                discharges += 'Planned Discharges (Daily)';
				discharges += '<div class="col-9">';
				discharges += '<input type="number" min="0" class="form-control number" id="trackDischarges" style="text-align: right" />';
				discharges += '</div>';
				discharges += '</label>';
				discharges += '</div>';
				*/
				var epicScore = '';
				//epicScore += '<div class="col-5">';
                epicScore += '<label for="epicAcuityScore" class="col-5 form-label">';
                epicScore += '<span id="easName"></span>';
				epicScore += '<div>';
				if(response.data.useGrid==4){
				epicScore += '<input type="number" min="0" class="form-control number" id="epicAcuityScore" onchange="tj.gridupdate2()" style="text-align: right" />';
				//$('#easTarget').val(response.data.easTarget);
				//$('#easMax').val(response.data.easMax);
				}else{
				epicScore += '<input type="number" min="0" class="form-control number" id="epicAcuityScore" style="text-align: right" />';	
				//$('#easTarget').val('0');
				//$('#easMax').val('0');
				}
				epicScore += '</div>';
				epicScore += '</label>';
				//epicScore += '</div>';
				
				var epicNurse = '';
				//epicNurse += '<div class="col-5">';
                epicNurse += '<label for="epicAcuityNurse" class="col-5 form-label">';
                epicNurse += '<span id="eanName"></span> / Nurses';
				epicNurse += '<div>';
				epicNurse += '<input type="text" class="form-control number" id="epicAcuityNurse" style="text-align: right" disabled/>';
				epicNurse += '</div>';
				epicNurse += '</label>';
				//epicNurse += '</div>';
				
				var epicVar = '';
				//epicVar += '<div class="col-9">';
                epicVar += '<label for="epicAcuityVar" class="col-6 form-label">';
                epicVar += 'RN Variance (<span id="eavName"></span>)';
				epicVar += '<div>';
				epicVar += '<input type="text" class="form-control number" id="epicAcuityVar" style="text-align: right" disabled/>';
				epicVar += '</div>';
				epicVar += '</label>';
				//epicVar += '</div>';
				
				var visits = ''
				visits += '<div class="col-9">';
                visits += '<label for="trackVisits" class="form-label">';
                visits += '<span id="visitsName"></span>';
				visits += '<div class="col-9">';
				visits += '<input type="number" min="0" class="form-control number" id="trackVisits" style="text-align: right" />';
				visits += '</div>';
				visits += '</label>';
				visits += '</div>';
				
				if(response.data.showEpic==1){
					$('#epicScore').empty().append(epicScore);
					$('#epicAcuityScore').val(response.data.epicScore);
				}else{
					$('#epicScore').empty();
					$('#epicAcuityScore').val('0');
				}
				
				let showrntext = String(response.data.showrntest);
				
				$('#varName1').html('RN Variance');
				$('#varName2').html('Grid Variance');
				$('#varName3').html('Grid RN Variance');
				
				if(showrntext.includes("0")){
				a30.style.display='none';
				a34.style.display='none';
				a40.style.display='none';
				}
			
				
				if(showrntext.includes("1") && (response.data.rnvarianceLive==0 || (response.data.rnvarianceLive==1 && response.data.userId !=0))){
				a30.style.display='';
				}else{
				a30.style.display='none';
				}
				
				
				if(showrntext.includes("2")){
				a40.style.display='none';
				}
				if(showrntext.includes("3")){
				a34.style.display='';
				}
				
				if(response.data.showEpicNurse==1 && response.data.antecount!=0 && response.data.useGrid==4){
				var epicCalc = (parseFloat(response.data.epicScore) / parseFloat(response.data.antecount)).toFixed(1);
					$('#epicNurse').empty().append(epicNurse);
					$('#epicVar').empty().append(epicVar);
					$('#epicAcuityNurse').val(epicCalc);
					$('#epicAcuityVar').val(response.data.nvariance);
				}else if(response.data.showEpicNurse==1 && response.data.antecount==0 && response.data.useGrid==4){
				//var epicCalc = (parseFloat(response.data.epicScore) / parseFloat(response.data.antecount)).toFixed(1);
					$('#epicNurse').empty().append(epicNurse);
					$('#epicVar').empty().append(epicVar);
					$('#epicAcuityNurse').val('0');
					$('#epicAcuityVar').val('');
				}else if(response.data.useGrid==7){
					a30.style.display='none';
					a301.style.display='';
					a302.style.display='';
					a303.style.display='';	
					$('#varName101').html('L&D RN Variance');
					$('#varName102').html('PP RN Variance');
					$('#varName103').html('NICU RN Variance');
					$('#epicNurse').empty();
					$('#epicVar').empty();
					$('#epicAcuityNurse').val('');
				}else{
					$('#epicNurse').empty();
					$('#epicVar').empty();
					$('#epicAcuityNurse').val('');					
				}
				
				if(response.data.practiceId==1) {
				a33.style.display='';
				a36.style.display='none';
				$('#submittedbyNEW').val(response.data.adminId);
				$('#submittedby').val('');
				}else{
				a33.style.display='none';
				a36.style.display='none';
				$('#submittedbyNEW').val(response.data.adminId);
				$('#submittedby').val('');
				}
				//console.log('adminId',response.data.adminId);
				$('#discharges3').empty();
				$('#discharges2').empty();
				
				if (response.data.prodMeasure==4 && response.data.acuityTotal==1 && response.data.shift==52 && (response.data.showVisits==1 || response.data.showVisits==3)) {
				$('#visits2').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Yesterday (' + response.data.reportdate2 + ')');
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal!=1 && response.data.shift==52 && (response.data.showVisits==1 || response.data.showVisits==3)) {
				$('#visits1').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Yesterday (' + response.data.reportdate2 + ')');
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal==1 && response.data.shift!=52 && response.data.shift<15 && response.data.showVisits>=2) {
				$('#visits2').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Today (' + response.data.reportdate + ')');	
				}else if (response.data.prodMeasure==4 && response.data.acuityTotal!=1 && response.data.shift!=52 && response.data.shift<15 && response.data.showVisits>=2) {
				$('#visits1').empty().append(visits);
				$('#visitsName').html('OPTIONAL:</br> ' + response.data.uosDesc + ' Completed Today (' + response.data.reportdate + ')');	
				}else{
				$('#visits1').empty();
				$('#visits2').empty();
				$('#visitsName').empty();
				}
				
				
				var html = '';
				var html2 = '';
				
				var nurseCount = $('#nCount');
				//console.log('acuityTotal',response.data.acuityTotal);
				//console.log('unlocked',response.data.unlockedPatientCount);				
				if (response.data.acuityTotal ==1 || (response.data.unlockedPatientCount ==1 && response.data.acuityTotal!=0 && response.data.deptId !=496 && response.data.deptId !=502)) {
				a8.style.display='none';
				a9.style.display='none';
				}else if(response.data.acuityTotal==0 || response.data.deptId==496 || response.data.deptId==502){
				a8.style.display='';
				a9.style.display='none';	
				}else{
				a8.style.display='';
				a9.style.display='';					
				}
				
				if (response.data.showHPPS==0) {
				f12.style.display='none';
				f13.style.display='';
				}else{
				f12.style.display='';
				f13.style.display='none';				
				}
				if (response.data.patientTotalDesc2=='') {
				a39.style.display='none';
				}else{
				a39.style.display='';					
				}
				if (response.data.patientTotalDesc3=='') {
				a50.style.display='none';
				}else{
				a50.style.display='';					
				}
				if (response.data.patientTotalDesc4=='') {
				a51.style.display='none';
				}else{
				a51.style.display='';					
				}
				if (response.data.ptTotal2==1 || response.data.ptTotal3==1 || response.data.ptTotal4==1) {
				//document.getElementById("patienttotalNEW").disabled = true;
				a52.style.display='none';
				}else{
				//document.getElementById("patienttotalNEW").disabled = false;
				a52.style.display='';					
				}
				if(response.data.deptId==496 || response.data.deptId==502){
				document.getElementById("patienttotalNEW").disabled = true;
				}else{
				document.getElementById("patienttotalNEW").disabled = false;
				}
				if (response.data.chargeDesc=='') {
				a37.style.display='none';
				}else{
				a37.style.display='';					
				}
				if (response.data.nurseDesc=='') {
				a38.style.display='none';
				}else{
				a38.style.display='';					
				}
				if (response.data.track1Desc=='') {
				a26.style.display='none';
				}else{
				a26.style.display='';					
				}
				if (response.data.track2Desc=='') {
				a27.style.display='none';
				}else{
				a27.style.display='';					
				}
				if (response.data.track3Desc=='') {
				a28.style.display='none';
				}else{
				a28.style.display='';					
				}
				if (response.data.track4Desc=='') {
				a29.style.display='none';
				}else{
				a29.style.display='';					
				}
							
				if (response.data.trackdata==1) {
				a24.style.display='';
				}else{
				a24.style.display='none';					
				}
				if (response.data.nurse1Label ==0) {
				a10.style.display='none';
				}else{
				a10.style.display='';
				}
				if (response.data.nurse2Label ==0) {
				a31.style.display='none';
				}else{
				a31.style.display='';
				}
				if (response.data.sLabel ==0) {
				a22.style.display='none';
				}
				if (response.data.tLabel ==0) {
				a32.style.display='none';
				}
				if (response.data.churnLabel ==0) {
				a15.style.display='none';
				}else{
				a15.style.display='';
				}
				if (response.data.other1Label==0) {
				a16.style.display='none';
				}else{
				a16.style.display='';
				}
				if (response.data.sittersLabel==0) {
				a23.style.display='none';
				}else{
				a23.style.display='';
				}
				if (response.data.other2Label==0) {
				a17.style.display='none';
				}else{
				a17.style.display='';
				}
				if (response.data.other3Label==0) {
				a18.style.display='none';
				}else{
				a18.style.display='';
				}
				if (response.data.acuity1 ==0 || response.data.desc1=='') {
				a1.style.display='none';
				}else{
				a1.style.display='';
				}
				if (response.data.acuity2 ==0 || response.data.desc2=='') {
				a2.style.display='none';
				}else{
				a2.style.display='';
				}
				if (response.data.acuity3 ==0 || response.data.desc3=='') {
				a3.style.display='none';
				}else{
				a3.style.display='';
				}
				if (response.data.acuity4 ==0 || response.data.desc4=='') {
				a4.style.display='none';
				}else{
				a4.style.display='';
				}
				if (response.data.acuity5 ==0 || response.data.desc5=='') {
				a5.style.display='none';
				}else{
				a5.style.display='';
				}
				if (response.data.acuity6 ==0 || response.data.desc6=='') {
				a6.style.display='none';
				}else{
				a6.style.display='';
				}
				if (response.data.acuity7 ==1 && response.data.desc7 !='') {
				a11.style.display='';
				}
				if (response.data.acuity8 ==1 && response.data.desc8 !='') {
				a12.style.display='';
				}
				if (response.data.acuity9 ==1 && response.data.desc9 !='') {
				a13.style.display='';
				}
				if (response.data.acuity10 ==1 && response.data.desc10 !='') {
				a19.style.display='';
				}
				if (response.data.acuity11 ==1 && response.data.desc11 !='') {
				a20.style.display='';
				}
				if (response.data.acuity12 ==1 && response.data.desc12 !='') {
				a201.style.display='';
				}
				if (response.data.churn ==0) {
				a7.style.display='none';
				}else{
				a7.style.display='none';
				}
				//console.log('dp',response.data.displayProd);
				if (response.data.displayProd ==1){
				$('#varianceNEW').html(response.data.currentVar + ' hrs');
				}else if(response.data.displayProd ==0){
				$('#varianceNEW').html(response.data.nvariance + ' RNs');
				}else if(response.data.displayProd ==4){
				$('#varianceNEW').html(response.data.ghrsVariance + ' hrs');
				}else if(response.data.displayProd ==5){
				$('#varianceNEW').html(response.data.nedocScore);
				}else{
				$('#varianceNEW').html(response.data.roundnvariance);					
				}
				
				if(parseInt(response.data.antecount)>0){
				var currentRatio = (response.data.atotal / parseFloat(response.data.antecount)).toFixed(1);
				}else{
				var currentRatio = 0;	
				}
				
				$('#hppsNEW').html('HPPS TARGET: ' + response.data.hppsTarget + ' / ACTUAL: '+ response.data.hppsActual);
				$('#ratioNEW').html(currentRatio + ' : 1');
				
				if (response.data.acuity7 ==1 || response.data.acuity8 ==1 || response.data.acuity9 ==1 || response.data.acuity10 ==1 || response.data.acuity11 ==1 || response.data.acuity12 ==1 || response.data.showEpic==1) {
				document.getElementById('ptTitle').style.display='';
				}else{
				document.getElementById('ptTitle').style.display='none';				
				}
				
				if(response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4 || response.data.useGrid==6 || response.data.useGrid==7){
				document.getElementById("cngrid1").style.display='';
				document.getElementById("cngrid2").style.display='';
				document.getElementById("rngrid1").style.display='';
				document.getElementById("rngrid2").style.display='';
				document.getElementById("pctgrid1").style.display='';
				document.getElementById("pctgrid2").style.display='';
				document.getElementById("secgrid1").style.display='';
				document.getElementById("secgrid2").style.display='';
				document.getElementById("rn1grid1").style.display='';
				document.getElementById("rn1grid2").style.display='';
				document.getElementById("other2grid1").style.display='';
				document.getElementById("other2grid2").style.display='';
				document.getElementById("other3grid1").style.display='';
				document.getElementById("other3grid2").style.display='';
				document.getElementById("rn2grid1").style.display='';
				document.getElementById("rn2grid2").style.display='';
				document.getElementById("sittergrid1").style.display='';
				document.getElementById("sittergrid2").style.display='';
				document.getElementById("other1grid1").style.display='';
				document.getElementById("other1grid2").style.display='';
		
				a61.classList.add("col-md-5");
				a62.classList.add("col-md-5");
				a63.classList.add("col-md-5");
				a64.classList.add("col-md-5");
				a65.classList.add("col-md-5");
				a66.classList.add("col-md-5");
				a67.classList.add("col-md-5");
				a68.classList.add("col-md-5");
				a69.classList.add("col-md-5");
				a70.classList.add("col-md-5");
				}else{
				
				a61.classList.add("col-md-6");
				a62.classList.add("col-md-6");
				a63.classList.add("col-md-6");
				a64.classList.add("col-md-6");
				a65.classList.add("col-md-6");
				a66.classList.add("col-md-6");
				a67.classList.add("col-md-6");
				a68.classList.add("col-md-6");
				a69.classList.add("col-md-6");
				a70.classList.add("col-md-6");
				}
				
				if(response.data.halfNurse==1){
				html += '<option value=0>0</option>';
				html += '<option value=0.5>0.5</option>';
				html += '<option value=1>1</option>';
				html += '<option value=1.5>1.5</option>';
				html += '<option value=2>2</option>';
				html += '<option value=2.5>2.5</option>';
				html += '<option value=3>3</option>';
				html += '<option value=3.5>3.5</option>';
				html += '<option value=4>4</option>';
				html += '<option value=4.5>4.5</option>';
				html += '<option value=5>5</option>';
				html += '<option value=5.5>5.5</option>';
				html += '<option value=6>6</option>';
				html += '<option value=6.5>6.5</option>';
				html += '<option value=7>7</option>';
				html += '<option value=7.5>7.5</option>';
				html += '<option value=8>8</option>';
				html += '<option value=8.5>8.5</option>';
				html += '<option value=9>9</option>';
				html += '<option value=9.5>9.5</option>';
				html += '<option value=10>10</option>';
				html += '<option value=10.5>10.5</option>';
				html += '<option value=11>11</option>';
				html += '<option value=11.5>11.5</option>';
				html += '<option value=12>12</option>';
				html += '<option value=12.5>12.5</option>';
				html += '<option value=13>13</option>';
				html += '<option value=13.5>13.5</option>';
				html += '<option value=14>14</option>';
				html += '<option value=14.5>14.5</option>';
				html += '<option value=15>15</option>';
				html += '<option value=15.5>15.5</option>';
				html += '<option value=16>16</option>';
				html += '<option value=16.5>16.5</option>';
				html += '<option value=17>17</option>';
				html += '<option value=17.5>17.5</option>';
				html += '<option value=18>18</option>';
				html += '<option value=18.5>18.5</option>';
				html += '<option value=19>19</option>';
				html += '<option value=19.5>19.5</option>';
				html += '<option value=20>20</option>';
				}else{
				html += '<option value=0>0</option>';
				html += '<option value=1>1</option>';
				html += '<option value=2>2</option>';
				html += '<option value=3>3</option>';
				html += '<option value=4>4</option>';
				html += '<option value=5>5</option>';
				html += '<option value=6>6</option>';
				html += '<option value=7>7</option>';
				html += '<option value=8>8</option>';
				html += '<option value=9>9</option>';
				html += '<option value=10>10</option>';
				html += '<option value=11>11</option>';
				html += '<option value=12>12</option>';
				html += '<option value=13>13</option>';
				html += '<option value=14>14</option>';
				html += '<option value=15>15</option>';
				html += '<option value=16>16</option>';
				html += '<option value=17>17</option>';
				html += '<option value=18>18</option>';
				html += '<option value=19>19</option>';
				html += '<option value=20>20</option>';
				}
				//nurseCount.empty().append(html);
				//$('#resources').empty().append(html2);
				$('#chargeDesc').html(response.data.chargeDesc);
				$('#easName').html(response.data.easTerm);
				$('#eavName').html(response.data.easTerm);
				$('#eanName').html(response.data.easTerm);
				$('#nurse1Desc').html(response.data.nurse1Desc);
				$('#nurse2Desc').html(response.data.nurse2Desc);
				$('#other1Desc').html(response.data.other1Label);
				$('#other2Desc').html(response.data.other2Label);
				$('#other3Desc').html(response.data.other3Label);
				$('#sittersNEWDesc').html(response.data.sittersNEWDesc);
				$('#customChurnLabel').html(response.data.churnLabel);
				$('#customChurn').val(response.data.customChurn);
				$('#patientTotalDesc').html(response.data.patientTotalDesc);
				$('#patientTotalDesc2').html(response.data.patientTotalDesc2);
				$('#patientTotalDesc3').html(response.data.patientTotalDesc3);
				$('#patientTotalDesc4').html(response.data.patientTotalDesc4);
				
				if(response.data.submittedby.length>0){
				$('#userNameNEW').html(response.data.submittedby);
				}else{
				$('#userNameNEW').html(response.data.first_name+' '+response.data.last_name);
				}
				
				if(response.data.userId==0){
				$('#chargecountNEW').val(response.data.charge1);
				$('#showgridvariance').val('0');
				$('#showgridrnvariance').val('0');
				$('#deleteRecord').html('');
				$('#techcountNEW').val(response.data.techcount);
				$('#seccountNEW').val(response.data.seccount);
				$('#sittersNEW').val(response.data.sittercount);
				$('#nursecountNEW').val(response.data.antecount1);
				$('#nurse1_add').val(response.data.customNurse);
				$('#nurse2_add').val(response.data.customNurse2);
				$('#other1').val(response.data.otherNurse1);
				$('#other2').val(response.data.otherNurse2);
				$('#other3').val(response.data.otherNurse3);
				$('#clockedIn').html('');
				}else{
				$('#deleteRecord').html('<a href="javascript:;" onclick="tj.alertDelete();">Delete Record</a>');
				$('#chargecountNEW').val(response.data.chargecount1);
				$('#techcountNEW').val(response.data.techcount);
				$('#seccountNEW').val(response.data.seccount);
				$('#sittersNEW').val(response.data.sittercount);
				$('#nursecountNEW').val(response.data.antecount1);
				$('#nurse1_add').val(response.data.customNurse);
				$('#nurse2_add').val(response.data.customNurse2);
				$('#other1').val(response.data.otherNurse1);
				$('#other2').val(response.data.otherNurse2);
				$('#other3').val(response.data.otherNurse3);
				$('#clockedIn').html('');
				}
				
				$('#patienttotalNEW').val(response.data.atotal);
				$('#patienttotalNEW2').val(response.data.patientCount2);
				$('#patienttotalNEW3').val(response.data.patientCount3);
				$('#patienttotalNEW4').val(response.data.patientCount4);
				$('#openbedsNEW').html(response.data.openbeds);
				$('#reportdateNEW').html(currentReportDate);
				//$('#reportshiftNEW').html(currentReportTime);
				$('#deptIdNEW').val(response.data.deptId);
				$('#shiftNEW').val(response.data.shift);
				$('#hppdNEW').val(response.data.hppd);
				$('#secLabel').html(response.data.secLabel);
				$('#techLabel').html(response.data.techLabel);
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
				$('#desc9').html(response.data.desc9);
				$('#desc10').html(response.data.desc10);
				$('#desc11').html(response.data.desc11);
				$('#desc12').html(response.data.desc12);
				$('#oneto1').val(response.data.oneto1);
				$('#oneto2').val(response.data.oneto2);
				$('#oneto6').val(response.data.oneto6);
				$('#oneto7').val(response.data.oneto7);
				$('#oneto8').val(response.data.oneto8);
				$('#oneto9').val(response.data.oneto9);
				$('#oneto10').val(response.data.oneto10);
				$('#oneto11').val(response.data.oneto11);
				$('#oneto12').val(response.data.oneto12);
				$('#admissions1').val(response.data.admits);
				$('#transfers1').val(response.data.transfers);
				$('#discharges1').val(response.data.discharges);				
				$('#dayNEW').val(currentdayDate);
				$('#prodMeasureNEW').val(response.data.prodMeasure);
				$('#unlocked').val(response.data.unlockedPatientCount);
				$('#grid1opt').val(response.data.gridOpt1);
				$('#grid2opt').val(response.data.gridOpt2);
				$('#grid3opt').val(response.data.gridOpt3);
				$('#rnvar').val(response.data.displayProd);
			
				//$('#blocked').html('<a href="javascript:;" onclick="blockedBeds('+response.data.accountId+','+response.data.deptId+');">  '+response.data.blockedBeds+'</a>');
				$('#dataIdNEW').val(response.data.id);
				//console.log('dataId',response.data.id);
				//$('#dataId2NEW').html('<a href="javascript:;" onclick="tj.getNurseView('+response.data.dataIdNEW+')">Print View</a>');
				$('#prodnoteNEW').val(response.data.note);
				$('#notebody').val(response.data.note);
				$('#patientcountTitle').html(response.data.patientcountTitle);
				$('#depNAME').html(response.data.depname);
				
				$('#aproductivityNEW').html(response.data.nproductivity);
				//$('#avarianceNEW').html(response.data.avariance);
				$('#nurseDesc').html(response.data.nurseDesc);
				//$('#patientCustomDivider').html(response.data.patientCustomDivider);
				
				$('#trackDC').val(response.data.trackdc);
				$('#trackAdd').val(response.data.trackdata);
				
				//$('#trackDischarges').val(response.data.trackDischarges);
				$('#track1Desc').html(response.data.track1Desc);
				$('#track2Desc').html(response.data.track2Desc);
				$('#track3Desc').html(response.data.track3Desc);
				$('#track4Desc').html(response.data.track4Desc);
				$('#track1').val(response.data.track1);
				$('#track2').val(response.data.track2);
				$('#track3').val(response.data.track3);
				$('#track4').val(response.data.track4);
				$('#trackNote1').val(response.data.notetrack1);
				$('#trackNote2').val(response.data.notetrack2);
				$('#trackNote3').val(response.data.notetrack3);
				$('#trackNote4').val(response.data.notetrack4);
				$('#trackVisits').val(response.data.visitsSubmitted);
				$('#LinkedId').val(response.data.linkedId);
				$('#LinkedDept').val(response.data.linkedDept);
				
				if(response.data.useGrid==2 || response.data.useGrid==3){
				$('#cnGrid').val(response.data.gcn);
				$('#rnGrid').val(response.data.grn);
				$('#pctGrid').val(response.data.gpct);
				$('#secGrid').val(response.data.gsec);
				$('#rn1Grid').val(response.data.grn1);
				$('#rn2Grid').val(response.data.grn2);
				$('#other2Grid').val(response.data.gother2);
				$('#other3Grid').val(response.data.gother3);
				$('#other1Grid').val(response.data.gother1);
				$('#sitterGrid').val(response.data.gsitter);
				$('#cnVariance').val(response.data.gcnvar);
				$('#rnVariance').val(response.data.grnvar);
				$('#pctVariance').val(response.data.gpctvar);
				$('#secVariance').val(response.data.gsecvar);
				$('#rn1Variance').val(response.data.grn1var);
				$('#rn2Variance').val(response.data.grn2var);
				$('#other1Variance').val(response.data.gother1var);
				$('#sitterVariance').val((parseInt(response.data.gsitvar) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.gother2var) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.gother3var) * parseInt(response.data.gridOpt2)));
				$('#other2Gridval').html(response.data.gother2);
				$('#other2Varianceval').html(response.data.gother2var);
				}else if(response.data.useGrid==7){
				$('#cnGrid').val(response.data.grid1);
				$('#rnGrid').val(response.data.grid2);
				$('#rn1Grid').val(response.data.grid3);
				$('#rn2Grid').val(response.data.grid4);
				$('#pctGrid').val(response.data.grid5);
				$('#secGrid').val(response.data.grid6);
				$('#other1Grid').val(response.data.grid7);
				$('#sitterGrid').val(response.data.grid8);
				$('#other2Grid').val(response.data.grid9);
				$('#other3Grid').val(response.data.grid10);
				
				$('#cnVariance').val(response.data.gridvar1);
				$('#rnVariance').val(response.data.gridvar2);
				$('#rn1Variance').val(response.data.gridvar3);
				$('#rn2Variance').val(response.data.gridvar4);
				$('#pctVariance').val(response.data.gridvar5);
				$('#secVariance').val(response.data.gridvar6);
				$('#other1Variance').val(response.data.gridvar7);
				
				$('#sitterVariance').val((parseInt(response.data.gridvar8) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.gridvar9) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.gridvar10) * parseInt(response.data.gridOpt2)));
				
				$('#other2Gridval').html('');
				$('#other2Varianceval').html('');
				}else{
				$('#cnGrid').val(response.data.cn);
				$('#rnGrid').val(response.data.rn);
				$('#pctGrid').val(response.data.pct);
				$('#secGrid').val(response.data.sec);
				$('#rn1Grid').val(response.data.rn1);
				$('#rn2Grid').val(response.data.rn2);
				$('#other2Gridval').html(response.data.other2);
				$('#other2Grid').val(response.data.other2);
				$('#other3Grid').val(response.data.other3);
				$('#other1Grid').val(response.data.other1);
				$('#sitterGrid').val(response.data.sitter);
				$('#cnVariance').val(response.data.cnvar);
				$('#rnVariance').val(response.data.rnvar);
				$('#pctVariance').val(response.data.pctvar);
				$('#secVariance').val(response.data.secvar);
				$('#rn1Variance').val(response.data.rn1var);
				$('#rn2Variance').val(response.data.rn2var);
				$('#other2Varianceval').html(response.data.other2var);
				$('#sitterVariance').val((parseInt(response.data.sitvar) * parseInt(response.data.gridOpt3)));
				$('#other2Variance').val((parseInt(response.data.other2var) * parseInt(response.data.gridOpt1)));
				$('#other3Variance').val((parseInt(response.data.other3var) * parseInt(response.data.gridOpt2)));
				$('#other1Variance').val(response.data.other1var);
				
				}
				
				if(response.data.userId!=0){
				$('#showgridvariance').val(response.data.gvariance);
				$('#showgridrnvariance').val(response.data.gridrnvariance);
				}else{
				$('#showgridvariance').val('0');
				$('#showgridrnvariance').val('0');
				}						
				$('#useGrid').val(response.data.useGrid);		
				$('#oneto1Acuity').val(response.data.oneto1Acuity);
				$('#oneto2Acuity').val(response.data.oneto2Acuity);
				$('#oneto3Acuity').val(response.data.oneto3Acuity);
				$('#oneto4Acuity').val(response.data.oneto4Acuity);
				$('#oneto5Acuity').val(response.data.oneto5Acuity);
				$('#oneto6Acuity').val(response.data.oneto6Acuity);
				$('#countchargereport').val(response.data.countcharge);
				$('#rnreport').val(response.data.rnCount);
				$('#rn1report').val(response.data.rn1Count);
				$('#rn2report').val(response.data.rn2Count);
				$('#showrnreport').val(response.data.showrn);
				$('#showrnvariance').val(response.data.nvariance);
				$('#showrnvariance1').val(response.data.nvariance1);
				//console.log('show1',response.data.nvariance1);
				$('#showrnvariance2').val(response.data.nvariance2);
				$('#showrnvariance3').val(response.data.nvariance3);
				$('#submitreqd').val(response.data.practiceId);
				$('#varComment').html(response.data.varMessage);
				$('#actionPlan').html(response.data.actionPlan);
				$('#roundRN').val(response.data.roundrn);
				$('#minrn').val(response.data.minRN);
				$('#nedocs').val(response.data.displayProd);
				$('#userIdNEW').val(response.data.adminId);
				$('#roleNEW').val(response.data.role);
				
				$('#varmsg').val(response.data.varMessage);
				$('#pttotal2').val(response.data.ptTotal2);
				$('#pttotal3').val(response.data.ptTotal3);
				$('#pttotal4').val(response.data.ptTotal4);
				$('#other1Calc').val(response.data.other1Calc);
				
				//console.log('calc',response.data.other1Calc);
				//console.log('gother',response.data.gother1var);
				//console.log('other',response.data.other1var);
				
				$('#countNEW').val(response.data.submitCount);
				$('#useEAS').val(response.data.showEpic);
				$('#useEASN').val(response.data.showEpicNurse);
				$('#resourcesTitle').html(response.data.resourcesTitle);
				$('#unscheduleddeptId').val('0');
				$('#unscheduledNEW').val('1');
				
				
				
				//console.log('pt2',response.data.patientCount2);
				//console.log('pt3',response.data.patientCount3);
				//console.log('pt4',response.data.patientCount4);
				//console.log('pts',response.data.atotal);
								
				$('#addNEW').modal('show');
					if(response.data.userId !=0){
					tj.gridupdate5();
					}
				}
        })
        //console.log('record updated sucessfully',dataId);
		
		
  } 

  
tj.showSubmit = function () {
$('#submittedbyNEW').val('0');
$('#submittedby').val('');
document.getElementById('submitted').style.display='';
document.getElementById('submittedNEW').style.display='none';
document.getElementById('showSubmit2').style.display='';
}

tj.showSubmit2 = function () {
$('#submittedbyNEW').val('0');
$('#submittedby').val('');
document.getElementById('submitted').style.display='none';
document.getElementById('submittedNEW').style.display='';
document.getElementById('showSubmit2').style.display='none';
}

tj.alertDelete = function () {
	bootbox.alert('If you need to delete a record, please contact your Manager or System Administrator.');
	return;

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
				//document.getElementById("reportshiftNEW").val = "";
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
				//document.getElementById("dataId2NEW").val = "";
				document.getElementById("prodnoteNEW").val = "";
				document.getElementById("varianceNEW").val = "";
				document.getElementById("aproductivityNEW").val = "";
				//document.getElementById("avarianceNEW").val = "";
				document.getElementById("hidden1").style.display='';
				document.getElementById("hidden2").style.display='';
				document.getElementById("hidden3").style.display='';
				document.getElementById("hidden4").style.display='';
				document.getElementById("hidden5").style.display='';
				document.getElementById("hidden6").style.display='';
				
        
  }
  

///Clear Record

tj.clearRecord = function(dataId) {
		bootbox.confirm({
        message:"Delete this record?  This cannot be undone.",
		backdrop:true,
        callback:function (result) {
		if (result) {
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
        });
	  }
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
        
		
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
		var nurse1 = $('#nurse1_add').val();
		var nurse2 = $('#nurse2_add').val();
		var other1 = $('#other1').val();
		var other2 = $('#other2').val();
		var other3 = $('#other3').val();
		var high = $('#highNEW').val();
		var med = $('#medNEW').val();
		var low = $('#lowNEW').val();
		var oneto1 = $('#oneto1').val();
		var oneto2 = $('#oneto2').val();
		var oneto6 = $('#oneto6').val();
		var extoneto6 = $('#extoneto6').val();
		var oneto7 = $('#oneto7').val();
		var oneto8 = $('#oneto8').val();
		var oneto9 = $('#oneto9').val();
		var oneto10 = $('#oneto10').val();
		var oneto11 = $('#oneto11').val();
		var oneto12 = $('#oneto12').val();
		var dataId = $('#dataIdNEW').val();
		var inshiftProd = $('#inshiftProd').val();
		var rnThreshold = $('#rnThreshold').val();
		var acuityTotal = $('#acuityTotal').val();
		var note = $('#prodnoteNEW').val();
		var sitters = $('#sittersNEW').val();
		var admissions = $('#admissions1').val();
		var transfers = $('#transfers1').val();
		var customChurn = $('#customChurn').val();
		var discharges = $('#discharges1').val();
		var a1= document.getElementById('addNEW');
		var trackInsert = $('#trackInsert').val();
		var track1 = $('#track1').val();
		var track2 = $('#track2').val();
		var trackNote1 = $('#trackNote1').val();
		var trackNote2 = $('#trackNote2').val();
		var trackNote3 = $('#trackNote3').val();
		var trackNote4 = $('#trackNote4').val();
		var track3 = $('#track3').val();
		var track4 = $('#track4').val();
		var trackDischarges = 0;
		var submittedby = $('#submittedby').val();
		var submitreqd = $('#submitreqd').val();
		var gvariance = $('#showgridvariance').val();
		var rnvariance = $('#showgridrnvariance').val();
		var showrnvariance = $('#showrnvariance').val();
		var showrnvariance1 = $('#showrnvariance1').val();
		var showrnvariance2 = $('#showrnvariance2').val();
		var showrnvariance3 = $('#showrnvariance3').val();
		var trackVisits = $('#trackVisits').val();
		var adminId = $('#userIdNEW').val();
		var submittedbyNEW = $('#submittedbyNEW').val();
		var role = $('#roleNEW').val();
		//var patienttotal1 = $('#patienttotalNEW').val();
		var patienttotal2 = $('#patienttotalNEW2').val();
		var patienttotal3 = $('#patienttotalNEW3').val();
		var patienttotal4 = $('#patienttotalNEW4').val();
		var epicScore = $('#epicAcuityScore').val();
		var pttotal2 = $('#pttotal2').val();
		var pttotal3 = $('#pttotal3').val();
		var pttotal4 = $('#pttotal4').val();
		var linkedId= $('#LinkedId').val();
		var linkedDept = $('#LinkedDept').val();
		var unlocked = $('#unlocked').val();
		var ptRatio = $('#ptRatio').val();

		var grid1 = $('#cnGrid').val();
		var grid2 = $('#rnGrid').val();
		var grid3 = $('#rn1Grid').val();
		var grid4 = $('#rn2Grid').val();
		var grid5 = $('#pctGrid').val();
		var grid6 = $('#secGrid').val();
		var grid7 = $('#other1Grid').val();
		var grid8 = $('#sitterGrid').val();
		var grid9 = $('#other2Grid').val();
		var grid10 = $('#other3Grid').val();
		
		var add1 = $('#add1').val();
		var add2 = $('#add2').val();
		var add3 = $('#add3').val();
		var add4 = $('#add4').val();
		var add5 = $('#add5').val();
		var add6 = $('#add6').val();
		var extadd6 = $('#extadd6').val();
	
				
		var gridvar1 = $('#cnVariance').val();
		var gridvar2 = $('#rnVariance').val();
		var gridvar3 = $('#rn1Variance').val();
		var gridvar4 = $('#rn2Variance').val();
		var gridvar5 = $('#pctVariance').val();
		var gridvar6 = $('#secVariance').val();
		var gridvar7 = $('#other1Variance').val();
		var gridvar8 = $('#sitterVariance').val();
		var gridvar9 = $('#other2Variance').val();
		var gridvar10 = $('#other3Variance').val();
		var zeropts = $('#zeropts').val();
		var unscheduled = $('#unscheduledNEW').val();
		var deptId = $('#deptIdNEW').val();
		var checkBox = $('#checkBox').is(':checked') ? 1 : 0;
		var bedCount = $('#bedCount').val();
		var nedocs1 = $('#nedocs').val();
		var newRecord = $('#newRecord').val();
		
		
		if(parseInt(nedocs1)==5 && parseInt(bedCount)>0){	
		var nedocs =(85.5*(parseInt(oneto1)/parseInt(high)))+(600*(parseInt(oneto2)/parseInt(bedCount)))+(13.4*parseInt(low))+(.93*parseInt(med))+(5.64*parseInt(oneto6))-20;
		}else{
		var nedocs = 0;	
		}
	
		if(pttotal2==1 || pttotal3==1 || pttotal4==1){
			var patienttotal = ((parseInt(patienttotal2) * parseInt(pttotal2)) + (parseInt(patienttotal3) * parseInt(pttotal3)) + (parseInt(patienttotal4) * parseInt(pttotal4)));
		}else if(acuityTotal== 1 || unlocked==1){
			var patienttotal = (parseInt(oneto1) * parseInt(add1))+(parseInt(oneto2) * parseInt(add2))+(parseInt(oneto6) * parseInt(add6))+(parseInt(extoneto6) * parseInt(extadd6))+(parseInt(high) * parseInt(add3))+(parseInt(med) * parseInt(add4))+(parseInt(low) * parseInt(add5));
		}else{
			var patienttotal = $('#patienttotalNEW').val();
		}
			
		if (parseInt(patienttotal)!= (parseInt(oneto1)+parseInt(oneto2)+parseInt(oneto6)+parseInt(high)+parseInt(med)+parseInt(low)) && unlocked==0) {
			bootbox.alert('Your Total Patient Count does not add up to match your Patient Selections.  <br><br>Please correct this.');
			return;
			}
	
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		
		if(submittedby.length==0 && submitreqd==1) {
			bootbox.alert('Please add your name to the Submitted By field.  This is required because you are using a Department ID.');
			return;
			}
		if (parseInt(patienttotal)==0 && parseInt(zeropts)==0){
			$('#zeropts').val('1');
			bootbox.alert('Your record shows NO PATIENTS, if this is not correct, please update the patient count.');
			return;
			}
		
		if ((parseInt(chargecount) + parseInt(techcount) + parseInt(seccount) + parseInt(nursecount) + parseInt(nurse1) + parseInt(nurse2) + parseInt(other1) + parseInt(other2) + parseInt(other3))==0){
			bootbox.alert('Staff count cannot be zero.');
			return;	
		}
		
		
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
				oneto8: oneto8,
				oneto9: oneto9,
				oneto10: oneto10,
				oneto11: oneto11,
				oneto12: oneto12,
				dataId: dataId,
				patienttotal: patienttotal,
				patienttotal2: patienttotal2,
				patienttotal3: patienttotal3,
				patienttotal4: patienttotal4,
				note: note,
				sitters: sitters,
				admissions: admissions,
				transfers: transfers,
				discharges: discharges,
				currentTime: currentTime,
				admissions: admissions,
				transfers: transfers,
				customChurn: customChurn,
				acuityTotal: acuityTotal,
				nurse1: nurse1,
				nurse2: nurse2,
				inshiftProd: inshiftProd,
				rnThreshold: rnThreshold,
				other1: other1,
				other2: other2,
				other3: other3,
				trackInsert: trackInsert,
				trackDischarges: trackDischarges,
				track1: track1,
				track2: track2,
				track3: track3,
				track4: track4,
				trackNote1: trackNote1,
				trackNote2: trackNote2,
				trackNote3: trackNote3,
				trackNote4: trackNote4,
				submittedby: submittedby,
				gvariance: gvariance,
				rnvariance: rnvariance,
				showrnvariance: showrnvariance,
				showrnvariance1: showrnvariance1,
				showrnvariance2: showrnvariance2,
				showrnvariance3: showrnvariance3,
				trackVisits: trackVisits,
				submittedbyNEW: submittedbyNEW,
				submitreqd: submitreqd,
				epicScore: epicScore,
				grid1: grid1,
				grid2: grid2,
				grid3: grid3,
				grid4: grid4,
				grid5: grid5,
				grid6: grid6,
				grid7: grid7,
				grid8: grid8,
				grid9: grid9,
				grid10: grid10,
				gridvar1: gridvar1,
				gridvar2: gridvar2,
				gridvar3: gridvar3,
				gridvar4: gridvar4,
				gridvar5: gridvar5,
				gridvar6: gridvar6,
				gridvar7: gridvar7,
				gridvar8: gridvar8,
				gridvar9: gridvar9,
				gridvar10: gridvar10,
				ptRatio: ptRatio,
				zeropts: zeropts,
				unscheduled: unscheduled,
				deptId: deptId,
				extoneto6: extoneto6,
				checkBox: checkBox,
				nedocs: nedocs,
				newRecord: newRecord
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//console.log('deptId ',deptId);
				$('#unscheduled').val('0');
				if (response.data.message == true && response.data.updated == true) {
				$('#notedataId').val(response.data.dataId);
				$('#notedeptId').val(response.data.deptId);
				$('#goesc').val('1');
				//$('#varianceType').html(response.data.varianceType);
				$('#varianceType').html(response.data.varianceType);
				$('#staffingTitle').html(response.data.staffingTitle);
				$('#notebody').val('');
				//console.log('note',response.data.note);
				//console.log('updated',response.data.updated);
				tj.prodTable.ajax.reload(null,false);
				//tj.complianceTable.ajax.reload();
				$('#addNEW').modal('hide');
				$('#addprodnote').modal('show');
				}else if(response.data.updated == true && response.data.escalations==0 && response.data.escAlerts==1 && response.data.txtpause ==0){
				//}else if(response.data.updated == true && response.data.escalations==0 && response.data.textAlerts==1 && response.data.txtpause ==0 && response.data.txtactive ==1 && response.data.txtescalation==1) {
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
				document.getElementById("sittersNEW").selectedIndex = "";
				document.getElementById("userIdNEW").value = "";
				document.getElementById("shiftNEW").value = "";
				document.getElementById("dayNEW").value = "";
				document.getElementById("prodnoteNEW").value = "";
				document.getElementById("dataIdNEW").value = "";
				document.getElementById("admissions1").value = "";
				document.getElementById("transfers1").value = "";
				document.getElementById("discharges1").value = "";
				document.getElementById("trackInsert").value = "";
				document.getElementById("track1").value = "";
				document.getElementById("track2").value = "";
				document.getElementById("track3").value = "";
				document.getElementById("track4").value = "";
				document.getElementById("trackNote1").value = "";
				document.getElementById("trackNote2").value = "";
				document.getElementById("trackNote3").value = "";
				document.getElementById("trackNote4").value = "";
				document.getElementById("submitted").value = "";
				document.getElementById("patienttotalNEW").val = "";
				//document.getElementById("trackVisits").value = "";
				tj.prodTable.ajax.reload(null,false);
				}else{
				$('#addNEW').modal('hide');
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
				document.getElementById("patienttotalNEW").val = "";
				document.getElementById("sittersNEW").selectedIndex = "";
				document.getElementById("userIdNEW").value = "";
				document.getElementById("shiftNEW").value = "";
				document.getElementById("dayNEW").value = "";
				document.getElementById("prodnoteNEW").value = "";
				document.getElementById("dataIdNEW").value = "";
				document.getElementById("admissions1").value = "";
				document.getElementById("transfers1").value = "";
				document.getElementById("discharges1").value = "";
				document.getElementById("trackInsert").value = "";
				document.getElementById("track1").value = "";
				//document.getElementById("trackVisits").value = "";
				document.getElementById("track2").value = "";
				document.getElementById("track3").value = "";
				document.getElementById("track4").value = "";
				document.getElementById("trackNote1").value = "";
				document.getElementById("trackNote2").value = "";
				document.getElementById("trackNote3").value = "";
				document.getElementById("trackNote4").value = "";
				document.getElementById("submitted").value = "";
				
				tj.prodTable.ajax.reload(null,false);
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editNEW(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
				}	
				}		
            }
			
        })
				
    }
	
tj.newNote = function(dataId,deptId) {
	$('#notedataId').val(dataId);
	$('#goesc').val('0');
	$('#notedeptId').val(deptId);
	$('#varianceType').html('');
	$('#staffingTitle').html('Add a Note');
	$('#addprodnote').modal('show');
}
	

	
tj.addProdNote = function () {
        var dataId = $('#notedataId').val();
		var note = $('#notebody').val();
		var deptId = $('#notedeptId').val();
		var linkedId = $('#LinkedId').val();
		var linkedDept = $('#LinkedDept').val();
		var goesc = $('#goesc').val();
		//console.log('dataid',dataId);
		//console.log('note',note);
				
        $.ajax({
            url: 'inc/data.php?req=prodNote',
            data: {
                note: note,
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success:function(response) {
				if(response.data.escalations==0 && response.data.escAlerts==1 && response.data.txtpause==0 && goesc==1){
				document.getElementById("notebody").value = "";
				$('#dataIdesc').val(dataId);
				$('#deptIdesc').val(deptId);
				$('#addprodnote').modal('hide');
				$('#escalationNEW').modal('show');
				tj.prodTable.ajax.reload();
				//tj.stopLoading();				
				}else{				
				$('#addprodnote').modal('hide');
				$('#addNEW').modal('hide');
				document.getElementById("notebody").value = "";
				document.getElementById("notedataId").value = "";
				document.getElementById("notedeptId").value = "";
				//tj.prodTable.ajax.reload(null,false);
				tj.prodTable.ajax.reload(null,false);
				
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editNEW(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
				}
				
				}				
            }
        });
    }
	
tj.linkedDept3 = function () {
		var linkedId= $('#LinkedId').val();
		var linkedDept = $('#LinkedDept').val();
		$('#escalationNEW').modal('hide');
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editNEW(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
    }
	
tj.linkedDept4 = function () {
		var linkedId= $('#LinkedId').val();
		var linkedDept = $('#LinkedDept').val();
		$('#addEscalation').modal('hide');
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editNEW(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
    }
	
tj.addProdNoteOrig = function () {
        var dataId = $('#notedataIdOrig').val();
		var note = $('#notebodyOrig').val();
		var deptId = $('#notedeptIdOrig').val();
		console.log('origdataid',dataId);
		
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
				$('#addprodnoteOrig').modal('hide');
				$('#escalationNEW').modal('show');				
				}else{				
				document.getElementById("notebodyOrig").value = "";
				document.getElementById("notedataIdOrig").value = "";
				document.getElementById("notedeptIdOrig").value = "";
				$('#addprodnoteOrig').modal('hide');
				$('#addProd').modal('hide');
				tj.prodTable.ajax.reload();
				}
				console.log('esca',response.data.escalations);				
            }
        });
    }
	
	
tj.noEscalation = function () {
        //var deptId = $('#deptIddesc').val();
		var dataId = $('#dataIdesc').val();
		var deptId = $('#deptIdesc').val();
		var esc = 0;
		var sendtext = 0;
				
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                sendtext: sendtext,
				dataId: dataId,
				deptId: deptId
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

tj.showaddesc = function () {
        var dataId = $('#dataIdesc').val();
		var deptId = $('#deptIdesc').val();
		$('#escalationNEW').modal('hide');
		//console.log('dataId ',dataId);
		//console.log('deptId ',deptId);
		document.getElementById("escval").selectedIndex = 0;
		$('#dataId2').val(dataId);
		$('#deptId2').val(deptId);
        $('#addEscalation').modal('show');
}
	
tj.addEscalation = function () {
        var dataId = $('#dataId2').val();
		var deptId = $('#deptId2').val();
		var esc = $('#escval').val();
		var comment = $('#escalationcomment').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var linkedId = $('#LinkedId').val();
		var linkedDept = $('#LinkedDept').val();
		//console.log('linkedId',linkedId);
		if(esc==0){
    bootbox.alert('Please select an Escalation');
    return;
		}		
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                dataId: dataId,
				deptId: deptId,
				comment: comment,
				currentTime: currentTime
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Successfully Submitted.');
				document.getElementById("escval").selectedIndex = 0;
				document.getElementById("dataId2").value = "";
				document.getElementById("escalationcomment").value = "";
				//$('#escalationNEW').modal('hide');
                $('#addEscalation').modal('hide');
				tj.prodTable.ajax.reload();
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editNEW(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
				}
            }
        });
    }
	
tj.getEscalation = function (dataId) {
		console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getEscalation',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				$('#dataIdedit').val(response.data.id);
				$('#edittype').val(response.data.escalationName);
				$('#editunit').val(response.data.dept);
				$('#editSubmitted').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dateSubmitted);
				$('#escalationcommentedit').val(response.data.note);
				$('#escalationresponse').val(response.data.response);
                $('#editEscalation').modal('show');
            }
        });
}

	
tj.addEscalation3 = function () {
        var dataId = ''
		var deptId = $('#deptId3').val();
		var esc = $('#escval3').val();
		var comment = $('#escalationcomment3').val();
		var sendtext = 1;
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	
		if(esc==0){
    bootbox.alert('Please select an Escalation');
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
				comment: comment,
				currentTime: currentTime
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

///////Add Day Rank	
/////////////////////////////////////
// GET DAY RATING DATA

tj.getdayRating = function(deptId,countDept) {
		//$('#addProd').modal({backdrop: 'static', keyboard: false})
        //console.log('deptId:', deptId);
		//console.log('count:', countDept);
		newDate = tj.prodStartDate;
		newEndDate = tj.prodEndDate;
		$('#dayDate').val('');
		$('#dateNew').val('');
		$('#dateExisting').val('');
		$('#dayUnit').val('');
		$('#dayUpdated').html('');
		$('#dayUser').html('');
        if(deptId && countDept==1 && newDate==newEndDate){
		$.ajax({
            url:'inc/data.php?req=getdayRankdetails',
            data:{
                newDate: newDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//if(countDept==1){
				$('#dayUnit').val(response.data.deptId);
				//}
				if(response.data.dayRank !='0'){
				$('#dayUpdated').html('Last Update: '+response.data.submittedDate);				
				$('#dayUser').html('Updated By: '+response.data.first_name+' '+response.data.last_name);
				}
				$('#dayDate').val(response.data.dayDate);
				$('#daySD').val(response.data.dayRank);
				$('#dateNew').val(response.data.dateNew);
				$('#dateExisting').val(response.data.dateExisting);
				$('#dayEE').val(response.data.empEngagement);
				$('#dayrateId').val(response.data.id);
				$('#addDay').modal('show');
			}
        })
		}else{
			if(newDate==newEndDate){
				$('#dayDate').val(newDate);
			}
				$('#dayEE').val('0');
				$('#daySD').val('0');
				$('#dateNew').val('');
				$('#dateExisting').val('');
				$('#dayrateId').val('0');
				$('#addDay').modal('show');
		}
        
  }
  
// GET DAY RATING DATA

tj.checkday = function() {
		var dayDate = $('#dayDate').val();
		var deptId = $('#dayUnit').val();
        if(dayDate && deptId !=0){
		$.ajax({
            url:'inc/data.php?req=checkday',
            data:{
                dayDate: dayDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.message==true){
				bootbox.alert('A record has already been submitted for this Unit/Day.');
				$('#daySD').val(response.data.dayRank);
				$('#dayEE').val(response.data.empEngagement);
				}else{
				$('#daySD').val('0');
				$('#dayEE').val('0');
				}
				
			}
        })
		}        
  }
  
 tj.updatedayRating = function() {
		var dataId = $('#dayrateId').val();
		var sd = $('#daySD').val();
		var ee = $('#dayEE').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var deptId = $('#dayUnit').val();
		var dayDate = $('#dayDate').val();
		
		
		
		if(ee.length==0 || deptId.length==0 || dayDate.length==0){
		bootbox.alert('All fields are required.');
		return;
		}
		
        $.ajax({
            url:'inc/data.php?req=editdayRank',
            data:{
                dataId: dataId,
				sd: sd,
				ee: ee,
				currentTime: currentTime,
				dayDate: dayDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#addDay').modal('hide');
				tj.dayRankTable.ajax.reload();
				bootbox.alert('Record Successfully Updated.');
				}
        })
        
  }
	

/////////////////////////////////////
// DOWNLOAD CSV
tj.downloadCSV = function() {
	csvstart = tj.prodStartDate;
    csvend = tj.prodEndDate;
	console.log('start',tj.prodStartDate);
	console.log('end',tj.prodEndDate);
    $.ajax({
        url:'inc/data.php?req=downloadcsv',
        data: {
				//d.user = tj.userParam;
				start: csvstart,
                end: csvend
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
            //console.log('csv downloaded');
        }
    })
}

/////////////////////////////////////
// CREATE PDF
tj.downloadPDF = function() {
	//pdfstart = tj.prodStartDate;
    //pdfend = tj.prodEndDate;
	//console.log('pdfstart',tj.prodStartDate);
	//console.log('pdfend',tj.prodEndDate);
	//var accountId = $('#acctId').val();
	//var role = $('#staffingRole').val();
	//console.log('accountId',accountId);
	//console.log('role',role);
    $.ajax({
        url:'inc/data.php?req=getpdf',
        data: {
				//d.user = tj.userParam;
				start: tj.prodStartDate,
                end: tj.prodEndDate
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
         //tj.downloadFile(response.locate,response.file);   console.log('pdf created');
        }
    })
}

tj.setUserDates = function() {
	var startuser = tj.prodStartDate;
	var enduser = tj.prodEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
}




tj.downloadFile = function(fileloc,filename) {
	
    $.ajax({
        url:'inc/data.php?req=forceDownload',
        data: {
				fileloc: fileloc,
                filename: filename
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
         console.log('pdf downloaded');
        }
    })
}
	
}

/////////////////////////////////////
// INITIALIZE THE whp TABLE
tj.prodStartDatewhp = '';
tj.prodEndDatewhp = '';
tj.initializeProdGridwhp = function(id) {
	tj.prodStartDatewhp = moment().subtract(tj.prodStartwhp, 'days').format('YYYY-MM-DD');
	tj.prodEndDatewhp = moment().subtract(tj.prodEndwhp, 'days').format('YYY-MM-DD');
    //tj.prodStartDatewhp = moment().format('YYYY-MM-DD');
    //tj.prodEndDatewhp = moment().format('YYYY-MM-DD');
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
		"pageLength": 100,
		"scrollY": '500px',
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
			case 3:
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
			{ "data": "variance" },
			{ "data": "patients" },
			{ "data": "total" },
			{ "data": "charge" },
			{ "data": "nursecount" },
			{ "data": "techs" },
			{ "data": "secs" },
			{ "data": "other" },
			{ "data": "note" },
			{ "data": "categoryName" },
			{ "data": "shiftnum" },
			
        ],
		"columnDefs": [
						{"visible": document.getElementById('col1').val == "1", "targets": [5]},
						{"visible": document.getElementById('col2').val == "1", "targets": [6]},
						{"visible": document.getElementById('col3').val == "1", "targets": [7]},
						{"visible": document.getElementById('col4').val == "1", "targets": [8]},
						{"visible": document.getElementById('col5').val == "1", "targets": [9]},
						{"visible": false, "targets": [12] }
                     ],
    } );


///Edit WHP

tj.editWHP = function(dataId) {
		document.getElementById("skillval1").disabled = false;
		document.getElementById("hiddenskill1").style.display='none';
		document.getElementById("hiddenskill2").style.display='none';
		document.getElementById("hiddenskill3").style.display='none';
		document.getElementById("hiddenskill4").style.display='none';
		document.getElementById("hiddenskill5").style.display='none';
		document.getElementById("hiddenskill6").style.display='none';
		document.getElementById("hiddenskill7").style.display='none';
		document.getElementById("hiddenskill8").style.display='none';
		document.getElementById("hiddenskill9").style.display='none';
		document.getElementById("hiddenskill10").style.display='none';
		document.getElementById("hiddenskill11").style.display='none';
		document.getElementById("whpselect").style.display='none';
		document.getElementById("customWHPhide").style.display='none';
		document.getElementById("customWHP2hide").style.display='none';
		document.getElementById("customWHP3hide").style.display='none';
		document.getElementById("plannedWHPhide").style.display='';
		document.getElementById("staffHours").style.display='';
		document.getElementById("customWHP4hide").style.display='none';
		document.getElementById("customWHP5hide").style.display='none';
		document.getElementById("submittedwhp").style.display='none';
		document.getElementById("hidegridvariancewhp").style.display='';
						
        $.ajax({
            url:'inc/data.php?req=getProdDetailswhp',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response,prov) {
				var supprole = $('#supportRole').val();
				var a1 = document.getElementById('hiddenskill1');
				var a2 = document.getElementById('hiddenskill2');	
				var a3 = document.getElementById('hiddenskill3');	
				var a4 = document.getElementById('hiddenskill4');	
				var a5 = document.getElementById('hiddenskill5');	
				var a6 = document.getElementById('hiddenskill6');
				var a7 = document.getElementById('hiddenskill7');	
				var a8 = document.getElementById('hiddenskill8');	
				var a9 = document.getElementById('hiddenskill9');	
				var a10 = document.getElementById('hiddenskill10');
				var a11 = document.getElementById('whpselect');
				var a12 = document.getElementById('customWHPhide');
				var a13 = document.getElementById('plannedWHPhide');
				var a14 = document.getElementById('customWHP2hide');
				var a15 = document.getElementById('customWHP3hide');
				var a16 = document.getElementById('hiddenskill11');
				var a17 = document.getElementById('customWHP4hide');
				var a18 = document.getElementById('customWHP5hide');
				var a24 = document.getElementById('showtrackwhp');
				var a26 = document.getElementById('hidetrack1whp');
				var a27 = document.getElementById('hidetrack2whp');
				var a28 = document.getElementById('hidetrack3whp');
				var a29 = document.getElementById('hidetrack4whp');
				var a30 = document.getElementById('submittedwhp');
				var a31 = document.getElementById('staffHours');
				var v1 = document.getElementById('hidegridvariancewhp');
				var v2 = document.getElementById('whpselectTest');
				
				let showrntext = String(response.data.showrntest);
				
				if(showrntext.includes("4")){
				v1.style.display='';
				}else{
				v1.style.display='none';
				}
				
				if (response.data.practiceId==1) {
				a30.style.display='';
				}
				
				if (response.data.deptId==455) {
				v2.style.display='';
				}else{
				v2.style.display='none';	
				}
				
				if (response.data.indMeasure==1 && response.data.type==2) {
				a11.style.display='';
				a31.style.display='none';
				a16.style.display='';
				}
				if (response.data.customDesc !='') {
				a12.style.display='';
				}
				if (response.data.customDesc2 !='') {
				a14.style.display='';
				}
				if (response.data.customDesc3 !='') {
				a15.style.display='';
				}
				if (response.data.customDesc4 !='') {
				a17.style.display='';
				}
				if (response.data.customDesc5 !='') {
				a18.style.display='';
				}
				
				
				if (response.data.skill2!=0) {
				a2.style.display='';
				}
				if (response.data.skill1!=0) {
				a1.style.display='';
				}
				if (response.data.skill3!=0) {
				a3.style.display='';
				}
				if (response.data.skill4!=0) {
				a4.style.display='';
				}
				if (response.data.skill5!=0) {
				a5.style.display='';
				}
				if (response.data.skill6!=0) {
				a6.style.display='';
				}
				if (response.data.skill7!=0) {
				a7.style.display='';
				}
				if (response.data.skill8!=0) {
				a8.style.display='';
				}
				if (response.data.skill9!=0) {
				a9.style.display='';
				}
				if (response.data.skill10!=0) {
				a10.style.display='';
				}
				//if (response.data.skill11!=0) {
				//a16.style.display='';
				//}
				var html = '';
				var newRow = $('#addRow');
				if (response.data.customWHP==1 || response.data.customWHP2==1 || response.data.customWHP3==1 || response.data.customWHP4==1 || response.data.uosDefault >0) {
				document.getElementById("plannedWHP").disabled = true;
				document.getElementById("customWHP2").disabled = false;
				$('#uosDesc').html(response.data.uosDesc);
				html += '</div></div><div class="row"><div class="col-sm-6">';
				//a13.style.display='none';
				}else if(response.data.deptId==455){
				document.getElementById("plannedWHP").disabled = true;
				document.getElementById("skillval1").disabled = true;
				$('#uosDesc').html(response.data.uosDesc);
				//a13.style.display='';
				}else if(response.data.deptId==459 || response.data.deptId==458){
				document.getElementById("plannedWHP").disabled = true;
				document.getElementById("customWHP2").disabled = false;
				$('#uosDesc').html(response.data.uosDesc);
				//a13.style.display='';
				}else{
				document.getElementById("plannedWHP").disabled = false;
				document.getElementById("customWHP2").disabled = false;
				$('#uosDesc').html(response.data.uosDesc);
				//a13.style.display='';
				}
								
				if (response.data.track1Desc=='') {
				a26.style.display='none';
				}else{
				a26.style.display='';					
				}
				if (response.data.track2Desc=='') {
				a27.style.display='none';
				}else{
				a27.style.display='';					
				}
				if (response.data.track3Desc=='') {
				a28.style.display='none';
				}else{
				a28.style.display='';					
				}
				if (response.data.track4Desc=='') {
				a29.style.display='none';
				}else{
				a29.style.display='';					
				}
				if (response.data.trackdata==1 && response.data.whpPlan==0) {
				a24.style.display='';
				}else{
				a24.style.display='none';					
				}
				
							
				if (response.data.whpPlan ==1 && response.data.unitCategory<5) {
				//b2.style.display='none';
				//b3.style.display='none';
				$('#actual').html('Planned for '+response.data.reportdate);
				}
				if (response.data.whpPlan ==0 && response.data.unitCategory<5) {
				//b2.style.display='none';
				//b3.style.display='none';
				$('#actual').html('Actuals for '+response.data.reportdate);
				}
				if (response.data.unitCategory==5) {
				//b2.style.display='none';
				//b3.style.display='none';
				$('#actual').html('RVUs for '+response.data.reportdate);
				}
				
				if(response.data.practice==1){
				$('#userNameWHP').html(response.data.submittedby);
				}else{
				$('#userNameWHP').html(response.data.first_name+' '+response.data.last_name);
				}
				var workedhrs = parseFloat(response.data.skill1val) + parseFloat(response.data.skill2val) + parseFloat(response.data.skill3val) + parseFloat(response.data.skill4val) + parseFloat(response.data.skill5val) + parseFloat(response.data.skill6val) + parseFloat(response.data.skill7val) + parseFloat(response.data.skill8val) + parseFloat(response.data.skill9val) + parseFloat(response.data.skill10val); 
				//console.log('Grid: ',response.data.useGrid);
				if(response.data.useGrid==2 || response.data.useGrid==3){
				document.getElementById("skill1grid1").style.display='';
				document.getElementById("skill1grid2").style.display='';
				document.getElementById("skill2grid1").style.display='';
				document.getElementById("skill2grid2").style.display='';
				document.getElementById("skill3grid1").style.display='';
				document.getElementById("skill3grid2").style.display='';
				document.getElementById("skill4grid1").style.display='';
				document.getElementById("skill4grid2").style.display='';
				document.getElementById("skill5grid1").style.display='';
				document.getElementById("skill5grid2").style.display='';
				document.getElementById("skill6grid1").style.display='';
				document.getElementById("skill6grid2").style.display='';
				document.getElementById("skill7grid1").style.display='';
				document.getElementById("skill7grid2").style.display='';
				document.getElementById("skill8grid1").style.display='';
				document.getElementById("skill8grid2").style.display='';
				document.getElementById("skill9grid1").style.display='';
				document.getElementById("skill9grid2").style.display='';
				document.getElementById("skill10grid1").style.display='';
				document.getElementById("skill10grid2").style.display='';
				//document.getElementById("hidegridvariancewhp").style.display='';
				$('#varName2whp').html('Grid Variance');
				
				//$('#useGridwhp').val(response.data.useGrid);
						
				
					if(response.data.userId!=0){
					//$('#showgridvariance').val(gridvar);
					$('#showgridvariancewhp').val(response.data.gvariance);
					}else{
					$('#showgridvariancewhp').val('');	
					}
					
				}else if(response.data.useGrid==6){
				document.getElementById("skill1grid1").style.display='';
				document.getElementById("skill1grid2").style.display='';
				document.getElementById("skill2grid1").style.display='';
				document.getElementById("skill2grid2").style.display='';
				document.getElementById("skill3grid1").style.display='';
				document.getElementById("skill3grid2").style.display='';
				document.getElementById("skill4grid1").style.display='';
				document.getElementById("skill4grid2").style.display='';
				document.getElementById("skill5grid1").style.display='';
				document.getElementById("skill5grid2").style.display='';
				document.getElementById("skill6grid1").style.display='';
				document.getElementById("skill6grid2").style.display='';
				document.getElementById("skill7grid1").style.display='';
				document.getElementById("skill7grid2").style.display='';
				document.getElementById("skill8grid1").style.display='';
				document.getElementById("skill8grid2").style.display='';
				document.getElementById("skill9grid1").style.display='';
				document.getElementById("skill9grid2").style.display='';
				document.getElementById("skill10grid1").style.display='';
				document.getElementById("skill10grid2").style.display='';
				$('#varName2whp').html('HRS Variance');
				
				//$('#useGridwhp').val(response.data.useGrid);
				
				
				//$('#gridhrs').val(response.data.jhours);
				//$('#workedhrs').val(workedhrs);
				
					if(response.data.userId!=0){
					//$('#showgridvariance').val(gridvar);
					$('#showgridvariancewhp').val(response.data.jvariance);
					}else{
					$('#showgridvariancewhp').val('');	
					}
					
				}else{
					document.getElementById("skill1grid1").style.display='none';
					document.getElementById("skill1grid2").style.display='none';
					document.getElementById("skill2grid1").style.display='none';
					document.getElementById("skill2grid2").style.display='none';
					document.getElementById("skill3grid1").style.display='none';
					document.getElementById("skill3grid2").style.display='none';
					document.getElementById("skill4grid1").style.display='none';
					document.getElementById("skill4grid2").style.display='none';
					document.getElementById("skill5grid1").style.display='none';
					document.getElementById("skill5grid2").style.display='none';
					document.getElementById("skill6grid1").style.display='none';
					document.getElementById("skill6grid2").style.display='none';
					document.getElementById("skill7grid1").style.display='none';
					document.getElementById("skill7grid2").style.display='none';
					document.getElementById("skill8grid1").style.display='none';
					document.getElementById("skill8grid2").style.display='none';
					document.getElementById("skill9grid1").style.display='none';
					document.getElementById("skill9grid2").style.display='none';
					document.getElementById("skill10grid1").style.display='none';
					document.getElementById("skill10grid2").style.display='none';
					
				}
				
				
				
				if(response.data.uosDefault > 0){
				$('#plannedWHP').val(response.data.uosDefault);
				//document.getElementById("plannedWHP").disabled = true;
				}else{
				$('#plannedWHP').val(response.data.procedureCount);
				//document.getElementById("plannedWHP").disabled = false;
				}
				
				if(response.data.useGrid==3){
				$('#customWHP').val(response.data.ghours);
				document.getElementById("customWHP").disabled = true;
				}else{
				$('#customWHP').val(response.data.whpCustom);
				document.getElementById("customWHP").disabled = false;
				}
				
				
				$('#customWHP2').val(response.data.whpCustom2);
				$('#submittedbywhp').val('');
				$('#submitreqdwhp').val(response.data.practiceId);
				
				$('#reportdateWHP').html(response.data.reportdate);
				$('#reportshiftWHP').html(response.data.reportshift);
				$('#shiftWHP').val(response.data.shift);
				$('#selectWHP').val(response.data.provId);
				$('#indMeasure').val(response.data.indMeasure);
				$('#type').val(response.data.type);
				$('#deptIdadd').val(response.data.deptId);
				
				$('#customDesc').html(response.data.customDesc);
				
				$('#customDesc2').html(response.data.customDesc2);
				$('#customWHP3').val(response.data.whpCustom3);
				$('#customWHP3Orig').val(response.data.whpCustom3);
				$('#planVal3').val(response.data.planVal3);
				$('#customDesc3').html(response.data.customDesc3);
				$('#customWHP4').val(response.data.whpCustom4);
				$('#customDesc4').html(response.data.customDesc4);
				$('#customWHP5').val(response.data.whpCustom5);
				$('#customDesc5').html(response.data.customDesc5);
				$('#track1Descwhp').html(response.data.track1Desc);
				$('#track2Descwhp').html(response.data.track2Desc);
				$('#track3Descwhp').html(response.data.track3Desc);
				$('#track4Descwhp').html(response.data.track4Desc);
				$('#track1whp').val(response.data.track1);
				$('#track2whp').val(response.data.track2);
				$('#track3whp').val(response.data.track3);
				$('#track4whp').val(response.data.track4);
				$('#trackNote1whp').val(response.data.notetrack1);
				$('#trackNote2whp').val(response.data.notetrack2);
				$('#trackNote3whp').val(response.data.notetrack3);
				$('#trackNote4whp').val(response.data.notetrack4);
				$('#trackAddwhp').val(response.data.trackdata);
				$('#selectA1').val(response.data.Prov1);
				$('#selectA2').val(response.data.Prov2);
				$('#selectP1').val(response.data.Prov3);
				$('#selectP2').val(response.data.Prov4);
				$('#selectE1').val(response.data.Prov5);
				$('#selectC1').val(response.data.Prov6);
				$('#A1pprocs').val(response.data.provProcs1);
				$('#A2pprocs').val(response.data.provProcs2);
				$('#P1pprocs').val(response.data.provProcs3);
				$('#P2pprocs').val(response.data.provProcs4);
				$('#E1pprocs').val(response.data.provProcs5);
				$('#C1pprocs').val(response.data.provProcs6);
				$('#A1phours').val(response.data.provHours1);
				$('#A2phours').val(response.data.provHours2);
				$('#P1phours').val(response.data.provHours3);
				$('#P2phours').val(response.data.provHours4);
				$('#E1phours').val(response.data.provHours5);
				$('#C1phours').val(response.data.provHours6);
				
				$('#actualWHP').val(response.data.hppd);
				$('#resourcesTitlewhp').html(response.data.resourcesTitle);
				
				
				$('#dayWHP').val(response.data.dayDate);
				//$('#currentVar').html(response.data.currentVar);
				
				if (response.data.displayProd ==1){
				$('#currentVar').html(' '+response.data.currentVar + ' Hrs');
				$('#VarianceType').html('Variance to ' + response.data.hrsTerm);
				}else if(response.data.displayProd ==5){
				$('#currentVar').html(' '+response.data.gvariance+ ' FTE');
				$('#VarianceType').html('Variance');
				}else{
				$('#currentVar').html(' '+response.data.nvariance1);
				$('#VarianceType').html('Variance');				
				}
				
				if(response.data.displayProd==5){
				$('#currentWHP').html('');
				$('#targetWHP').html('');
				}else if(response.data.useGrid==3 && response.data.displayProd!=1){
				$('#currentWHP').html('');
				$('#targetWHP').html('');
				}else{
				$('#targetWHP').html(response.data.hppd);
				$('#currentWHP').html(response.data.actualWHP);				
				}
				
				$('#customwhpVal').val(response.data.customWHPvalue);
				$('#customwhp2Val').val(response.data.customWHP2value);
				$('#customwhp3Val').val(response.data.customWHP3value);
				$('#customwhp4Val').val(response.data.customWHP4value);
				$('#customwhp5Val').val(response.data.customWHP5value);
				$('#customwhp3').val(response.data.customWHP3);
				$('#depNAMEwhp').html(response.data.depname);
				
								
				$('#dataIdWHP').val(response.data.id);
				//$('#dataId2WHP').html('<a href="#whpView?i='+response.data.dataIdWHP+'">Print View</a>');
				$('#prodnoteWHP').val(response.data.note);
				$('#notebodywhp').val(response.data.note);
				$('#skillval11').val(response.data.skill11val);
				$('#descskill1').html(response.data.skilldesc1);
				$('#descskill2').html(response.data.skilldesc2);
				$('#descskill3').html(response.data.skilldesc3);
				$('#descskill4').html(response.data.skilldesc4);
				$('#descskill5').html(response.data.skilldesc5);
				$('#descskill6').html(response.data.skilldesc6);
				$('#descskill7').html(response.data.skilldesc7);
				$('#descskill8').html(response.data.skilldesc8);
				$('#descskill9').html(response.data.skilldesc9);
				$('#descskill10').html(response.data.skilldesc10);
				$('#descskill11').html(response.data.skilldesc11);
				$('#providerName').html(response.data.reportshift);
				$('#provType').val(response.data.reportshift);
				$('#actionPlanwhp').html(response.data.actionPlan);
				$('#useGridwhp').val(response.data.useGrid);
				$('#varmsgwhp').val(response.data.varMessage);
				$('#linkedId').val(response.data.linkedId);
				$('#linkedDept').val(response.data.linkedDept);
				$('#posneg').val(response.data.productivityPosNeg);
				
				if(response.data.autoPop==1 && response.data.userId==0){
				$('#skill1Grid').val(response.data.gcn);
				$('#skill1Var').val('0');
				$('#skill2Grid').val(response.data.grn);
				$('#skill2Var').val('0');
				$('#skill3Grid').val(response.data.grn1);
				$('#skill3Var').val('0');
				$('#skill4Grid').val(response.data.grn2);
				$('#skill4Var').val('0');
				$('#skill5Grid').val(response.data.gsec);
				$('#skill5Var').val('0');
				$('#skill6Grid').val(response.data.gother1);
				$('#skill6Var').val('0');	
				$('#skill7Grid').val(response.data.gother2);
				$('#skill7Var').val('0');
				$('#skill8Grid').val(response.data.gother3);
				$('#skill8Var').val('0');
				$('#skill9Grid').val(response.data.gpct);
				$('#skill9Var').val('0');
				$('#skill10Grid').val(response.data.gsitter);
				$('#skill10Var').val('0');
				$('#skillval1').val(response.data.gcn);
				$('#skillval2').val(response.data.grn);
				$('#skillval3').val(response.data.grn1);
				$('#skillval4').val(response.data.grn2);
				$('#skillval5').val(response.data.gsec);
				$('#skillval6').val(response.data.gother1);
				$('#skillval7').val(response.data.gother2);
				$('#skillval8').val(response.data.gother3);
				$('#skillval9').val(response.data.gpct);
				$('#skillval10').val(response.data.gsitter);
				$('#gridhrs').val(response.data.ghours);
				$('#workedhrs').val(response.data.ghours);
				}else{
				$('#skill1Grid').val(response.data.gcn);
				$('#skill1Var').val(response.data.gcnvar);
				$('#skill2Grid').val(response.data.grn);
				$('#skill2Var').val(response.data.grnvar);
				$('#skill3Grid').val(response.data.grn1);
				$('#skill3Var').val(response.data.grn1var);
				$('#skill4Grid').val(response.data.grn2);
				$('#skill4Var').val(response.data.grn2var);
				$('#skill5Grid').val(response.data.gsec);
				$('#skill5Var').val(response.data.gsecvar);
				$('#skill6Grid').val(response.data.gother1);
				$('#skill6Var').val(response.data.gother1var);	
				$('#skill7Grid').val(response.data.gother2);
				$('#skill7Var').val(response.data.gother2var);
				$('#skill8Grid').val(response.data.gother3);
				$('#skill8Var').val(response.data.gother3var);
				$('#skill9Grid').val(response.data.gpct);
				$('#skill9Var').val(response.data.gpctvar);
				$('#skill10Grid').val(response.data.gsitter);
				$('#skill10Var').val(response.data.gsitvar);
				$('#skillval1').val(response.data.skill1val);
				$('#skillval2').val(response.data.skill2val);
				$('#skillval3').val(response.data.skill3val);
				$('#skillval4').val(response.data.skill4val);
				$('#skillval5').val(response.data.skill5val);
				$('#skillval6').val(response.data.skill6val);
				$('#skillval7').val(response.data.skill7val);
				$('#skillval8').val(response.data.skill8val);
				$('#skillval9').val(response.data.skill9val);
				$('#skillval10').val(response.data.skill10val);
				$('#gridhrs').val(response.data.ghours);
				$('#workedhrs').val(workedhrs);
				}
				
				var length = prov.length;
				var providerBody = $('#providerDetail');
				
				//$('#addRow').empty().append(html);
				$('#addWHP').modal('show');
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  }
  
tj.editWHP2 = function (dataId){
$(".tabs").removeClass("active");
$(".tabs h6").removeClass("font-weight-bold");
$(".tabs h6").addClass("text-muted");
$(this).children("h6").removeClass("text-muted");
$(this).children("h6").addClass("font-weight-bold");
$(this).addClass("active");
current_fs = $(".active");
next_fs = $(this).attr('id');
next_fs = "#" + next_fs + "1";
$("fieldset").removeClass("show");
$(next_fs).addClass("show");
$('#testModal').modal('show');
current_fs.animate({}, {
step: function() {
current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({
'display': 'block'
});
}
});	
	
}

////////////////////
//gridupdate
tj.gridupdatewhp = function() {
	var useGrid = $('#useGridwhp').val();
	var dayDate = $('#dayWHP').val();
	var deptId = $('#deptIdadd').val();
	var shift = $('#shiftWHP').val();
	
	var skill1 = $('#skillval1').val();
	var skill2 = $('#skillval2').val();
	var skill3 = $('#skillval3').val();
	var skill4 = $('#skillval4').val();
	var skill5 = $('#skillval5').val();
	var skill6 = $('#skillval6').val();
	var skill7 = $('#skillval7').val();
	var skill8 = $('#skillval8').val();
	var skill9 = $('#skillval9').val();
	var skill10 = $('#skillval10').val();
	
	var profile = $('#plannedWHP').val();
	var profile1 = $('#customWHP').val();
	var profile2 = $('#customWHP2').val();
	var profile3 = $('#customWHP3').val();
	var profile4 = $('#customWHP4').val();
	
	var customval = $('#customwhpVal').val();
	var custom2val = $('#customwhp2Val').val();
	var custom3val = $('#customwhp3Val').val();
	var custom4val = $('#customwhp4Val').val();
	
	//console.log('shift',shift);
	//console.log('deptId',deptId);
	//console.log('census',census);
	//console.log('date',dayDate);
	//console.log('useGrid',useGrid);
	
	var census = (parseFloat(profile) + (parseFloat(profile1) * parseFloat(customval)) + (parseFloat(profile2) * parseFloat(custom2val)) + (parseFloat(profile3) * parseFloat(custom3val)) + (parseFloat(profile4) * parseFloat(custom4val)));
	
	
	if(useGrid==2 || useGrid==3){
	
	$.ajax({
            url:'inc/data.php?req=gridupdatewhp',
            data:{
                census: census,
				deptId: deptId,
				shift: shift,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				skill5: skill5,
				skill6: skill6,
				skill7: skill7,
				skill8: skill8,
				skill9: skill9,
				skill10: skill10,
				useGrid: useGrid,
				dayDate: dayDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
						
				var skill1var = parseFloat(skill1) - parseFloat(response.data.cn);
				var skill2var = parseFloat(skill2) - parseFloat(response.data.rn);
				var skill3var = parseFloat(skill3) - parseFloat(response.data.other1);
				var skill4var = parseFloat(skill4) - parseFloat(response.data.sec);
				var skill5var = parseFloat(skill5) - parseFloat(response.data.rn1);
				var skill6var = parseFloat(skill6) - parseFloat(response.data.rn2);
				var skill7var = parseFloat(skill7) - parseFloat(response.data.other2);
				var skill8var = parseFloat(skill8) - parseFloat(response.data.other3);
				var skill9var = parseFloat(skill9) - parseFloat(response.data.pct);
				var skill10var = parseFloat(skill10) - parseFloat(response.data.sitter);
							
				$('#skill1Grid').val(response.data.cn);
				$('#skill2Grid').val(response.data.rn);
				$('#skill3Grid').val(response.data.other1);
				$('#skill4Grid').val(response.data.sec);
				$('#skill5Grid').val(response.data.rn1);
				$('#skill6Grid').val(response.data.rn2);
				$('#skill7Grid').val(response.data.other2);
				$('#skill8Grid').val(response.data.other3);
				$('#skill9Grid').val(response.data.pct);
				$('#skill10Grid').val(response.data.sitter);
							
				$('#skill1Var').val(skill1var);
				$('#skill2Var').val(skill2var);
				$('#skill3Var').val(skill3var);
				$('#skill4Var').val(skill4var);
				$('#skill5Var').val(skill5var);
				$('#skill6Var').val(skill6var);
				$('#skill7Var').val(skill7var);
				$('#skill8Var').val(skill8var);
				$('#skill9Var').val(skill9var);
				$('#skill10Var').val(skill10var);
			
				//$('#varCommentwhp').html(response.data.varianceMsg);
				
				
				}
        })
	}
		
}; 

////////////////////
//gridupdate
tj.gridupdatewhp2 = function() {
	var useGrid = $('#useGridwhp').val();
	var dayDate = $('#dayWHP').val();
	var deptId = $('#deptIdadd').val();
	var shift = $('#shiftWHP').val();
	
		var skill1 = $('#skillval1').val();
	var skill2 = $('#skillval2').val();
	var skill3 = $('#skillval3').val();
	var skill4 = $('#skillval4').val();
	var skill5 = $('#skillval5').val();
	var skill6 = $('#skillval6').val();
	var skill7 = $('#skillval7').val();
	var skill8 = $('#skillval8').val();
	var skill9 = $('#skillval9').val();
	var skill10 = $('#skillval10').val();
	
	var census = $('#plannedWHP').val();
	//var profile1 = $('#customWHP').val();
	//var profile2 = $('#customWHP2').val();
	//var profile3 = $('#customWHP3').val();
	//var profile4 = $('#customWHP4').val();
	//var profile5 = $('#customWHP5').val();
	
	//var customval = $('#customwhpVal').val();
	//var custom2val = $('#customwhp2Val').val();
	//var custom3val = $('#customwhp3Val').val();
	//var custom4val = $('#customwhp4Val').val();
	//var custom5val = $('#customwhp5Val').val();
	
	console.log('grid',useGrid);
	//console.log('day',dayDate);
	//console.log('dept',deptId);
	//console.log('shift',shift);
	//console.log('uos',census);
	
	
	if(useGrid==6){
	
	$.ajax({
            url:'inc/data.php?req=gridupdatewhp',
            data:{
                census: census,
				deptId: deptId,
				shift: shift,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				skill5: skill5,
				skill6: skill6,
				skill7: skill7,
				skill8: skill8,
				skill9: skill9,
				skill10: skill10,
				useGrid: useGrid,
				dayDate: dayDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
						
				var skill1var = parseFloat(skill1) - parseFloat(response.data.cn);
				var skill2var = parseFloat(skill2) - parseFloat(response.data.rn);
				var skill3var = parseFloat(skill3) - parseFloat(response.data.other1);
				var skill4var = parseFloat(skill4) - parseFloat(response.data.sec);
				var skill5var = parseFloat(skill5) - parseFloat(response.data.rn1);
				var skill6var = parseFloat(skill6) - parseFloat(response.data.rn2);
				var skill7var = parseFloat(skill7) - parseFloat(response.data.other2);
				var skill8var = parseFloat(skill8) - parseFloat(response.data.other3);
				var skill9var = parseFloat(skill9) - parseFloat(response.data.pct);
				var skill10var = parseFloat(skill10) - parseFloat(response.data.sitter);
							
				$('#skill1Grid').val(response.data.cn);
				$('#skill2Grid').val(response.data.rn);
				$('#skill3Grid').val(response.data.other1);
				$('#skill4Grid').val(response.data.sec);
				$('#skill5Grid').val(response.data.rn1);
				$('#skill6Grid').val(response.data.rn2);
				$('#skill7Grid').val(response.data.other2);
				$('#skill8Grid').val(response.data.other3);
				$('#skill9Grid').val(response.data.pct);
				$('#skill10Grid').val(response.data.sitter);
							
				$('#skill1Var').val(skill1var);
				$('#skill2Var').val(skill2var);
				$('#skill3Var').val(skill3var);
				$('#skill4Var').val(skill4var);
				$('#skill5Var').val(skill5var);
				$('#skill6Var').val(skill6var);
				$('#skill7Var').val(skill7var);
				$('#skill8Var').val(skill8var);
				$('#skill9Var').val(skill9var);
				$('#skill10Var').val(skill10var);
			
				//$('#varCommentwhp').html(response.data.varianceMsg);
				
				
				}
        })
	}
		
};

////////////////////
//gridupdate2
tj.addwhp = function() {

	var whp1 = $('#customWHP').val();
	var whp2 = $('#customWHP2').val();
	var whp3 = $('#customWHP3').val();
	var whp3Orig = $('#customWHP3Orig').val();
	var whp4 = $('#customWHP4').val();
	var whp5 = $('#customWHP5').val();
	var val1 = $('#customwhpVal').val();
	var val2 = $('#customwhp2Val').val();
	var val3 = $('#customwhp3Val').val();
	var val4 = $('#customwhp4Val').val();
	var val5 = $('#customwhp5Val').val();
	var deptId = $('#deptIdadd').val();
	var plan3 = $('#customwhp3').val();
	var planVal3 = $('#planVal3').val();
	
	//console.log('plan',plan3);
	//console.log('whp3',whp3);
	//console.log('whp3Orig',whp3Orig);
	//console.log('planVal3',planVal3);
	
	if(deptId == 459){
	var whp3 = $('#customWHP2').val();
	var whp4 = parseFloat(whp3)*143.3;
	$('#plannedWHP').val(whp4);
	var gridId = 0;
	}else if(deptId == 458){
	var whp3 = $('#customWHP2').val();
	var whp4 = parseFloat(whp3)*13.7;
	$('#plannedWHP').val(whp4);
	var gridId = 0;
	}else if(parseInt(plan3)==2 && parseInt(whp3) != parseInt(whp3Orig) && (parseInt(whp1) + parseInt(whp2) + parseInt(whp4) + parseInt(whp5))==0){
	var totalwhp = (parseInt(whp3) * parseFloat(planVal3));
	$('#plannedWHP').val(parseFloat(totalwhp).toFixed(0));
	var gridId = $('#useGridwhp').val();
	}else{
	var totalwhp = (parseInt(whp1) * parseFloat(val1)) + (parseInt(whp2) * parseFloat(val2)) + (parseInt(whp3) * parseFloat(val3)) + (parseInt(whp4) * parseFloat(val4)) + (parseInt(whp5) * parseFloat(val5));
	$('#plannedWHP').val(parseFloat(totalwhp).toFixed(0));
	var gridId = $('#useGridwhp').val();
	}
	if(gridId==6){
	tj.gridupdatewhp2();
	}
	
		
}; 

tj.updateWHP2 = function() {
	var deptId = $('#deptIdadd').val();
	//var gridId = $('#useGridwhp').val();
	//var totalUOS = $('#plannedWHP').val();
	//console.log('grid: ',gridId);
	//console.log('UOS: ',totalUOS);
	//if(deptId == 458){
	//var whp1 = $('#plannedWHP').val();
	//var whp2 = parseFloat(whp1)*186;
	//$('#customWHP2').val(whp2);
	//}		
};

////////////////////
//Update Procedures
tj.updateProcs = function() {
	
	if (isNaN(parseInt($('#A1pprocs').val()))){
		var whp1 = 0;
	}else{
		var whp1 = $('#A1pprocs').val();
	}
	if (isNaN(parseInt($('#A2pprocs').val()))){
		var whp2 = 0;
	}else{
		var whp2 = $('#A2pprocs').val();
	}
	if (isNaN(parseInt($('#P1pprocs').val()))){
		var whp3 = 0;
	}else{
		var whp3 = $('#P1pprocs').val();
	}
	if (isNaN(parseInt($('#P2pprocs').val()))){
		var whp4 = 0;
	}else{
		var whp4 = $('#P2pprocs').val();
	}
	if (isNaN(parseInt($('#E1pprocs').val()))){
		var whp5 = 0;
	}else{
		var whp5 = $('#E1pprocs').val();
	}
	if (isNaN(parseInt($('#E2pprocs').val()))){
		var whp6 = 0;
	}else{
		var whp6 = $('#E2pprocs').val();
	}
	if (isNaN(parseInt($('#C1pprocs').val()))){
		var whp7 = 0;
	}else{
		var whp7 = $('#C1pprocs').val();
	}
		
	var totalwhp = (parseInt(whp1) + parseInt(whp2) + parseInt(whp3) + parseInt(whp4) + parseInt(whp5) + parseInt(whp6) + parseInt(whp7));
	
	$('#plannedWHP').val(totalwhp);	
		
};

////////////////////
//Update Prov Hours
tj.updateProv = function() {
	
	if (isNaN(parseInt($('#A1phours').val()))){
		var hrs1 = 0;
	}else{
		var hrs1 = $('#A1phours').val();
	}
	if (isNaN(parseInt($('#A2phours').val()))){
		var hrs2 = 0;
	}else{
		var hrs2 = $('#A2phours').val();
	}
	if (isNaN(parseInt($('#P1phours').val()))){
		var hrs3 = 0;
	}else{
		var hrs3 = $('#P1phours').val();
	}
	if (isNaN(parseInt($('#P2phours').val()))){
		var hrs4 = 0;
	}else{
		var hrs4 = $('#P2phours').val();
	}
	if (isNaN(parseInt($('#E1phours').val()))){
		var hrs5 = 0;
	}else{
		var hrs5 = $('#E1phours').val();
	}
	if (isNaN(parseInt($('#E2phours').val()))){
		var hrs6 = 0;
	}else{
		var hrs6 = $('#E2phours').val();
	}
	if (isNaN(parseInt($('#C1phours').val()))){
		var hrs7 = 0;
	}else{
		var hrs7 = $('#C1phours').val();
	}
		
	var totalhrs = (parseInt(hrs1) + parseInt(hrs2) + parseInt(hrs3) + parseInt(hrs4) + parseInt(hrs5) + parseInt(hrs6) + parseInt(hrs7));
	
	$('#skillval1').val(totalhrs);	
		
};

tj.gridupdate2whp = function() {
	
	var deptId = $('#deptIdadd').val();
	var posNeg = $('#posneg').val();
	
	//if(deptId==440){
	//var newVal = parseInt($('#skillval3').val()) * 2.75;
	//$('#customWHP2').val(newVal);	
	//}else{
	var skill1 = $('#skillval1').val();
	var skill2 = $('#skillval2').val();
	var skill3 = $('#skillval3').val();
	var skill4 = $('#skillval4').val();
	var skill5 = $('#skillval5').val();
	var skill6 = $('#skillval6').val();
	var skill7 = $('#skillval7').val();
	var skill8 = $('#skillval8').val();
	var skill9 = $('#skillval9').val();
	var skill10 = $('#skillval10').val();
	var worked = parseFloat(skill1) + parseFloat(skill2) + parseFloat(skill3) + parseFloat(skill4) + parseFloat(skill5) + parseFloat(skill6) + parseFloat(skill7) + parseFloat(skill8) + parseFloat(skill9) + parseFloat(skill10);
		
	var varmsg = $('#varmsgwhp').val();
	
	var skill1grid = $('#skill1Grid').val();
	var skill2grid = $('#skill2Grid').val();
	var skill3grid = $('#skill3Grid').val();
	var skill4grid = $('#skill4Grid').val();
	var skill5grid = $('#skill5Grid').val();
	var skill6grid = $('#skill6Grid').val();
	var skill7grid = $('#skill7Grid').val();
	var skill8grid = $('#skill8Grid').val();
	var skill9grid = $('#skill9Grid').val();
	var skill10grid = $('#skill10Grid').val();
		//console.log('posneg',posNeg);			
	var skill1var = (parseFloat(skill1) - parseFloat(skill1grid)) * parseFloat(posNeg);
	var skill2var = (parseFloat(skill2) - parseFloat(skill2grid)) * parseFloat(posNeg);
	var skill3var = (parseFloat(skill3) - parseFloat(skill3grid)) * parseFloat(posNeg);
	var skill4var = (parseFloat(skill4) - parseFloat(skill4grid)) * parseFloat(posNeg);
	var skill5var = (parseFloat(skill5) - parseFloat(skill5grid)) * parseFloat(posNeg);
	var skill6var = (parseFloat(skill6) - parseFloat(skill6grid)) * parseFloat(posNeg);
	var skill7var = (parseFloat(skill7) - parseFloat(skill7grid)) * parseFloat(posNeg);
	var skill8var = (parseFloat(skill8) - parseFloat(skill8grid)) * parseFloat(posNeg);
	var skill9var = (parseFloat(skill9) - parseFloat(skill9grid)) * parseFloat(posNeg);
	var skill10var = (parseFloat(skill10) - parseFloat(skill10grid)) * parseFloat(posNeg);
	
	var gridvar = skill1var + skill2var + skill3var + skill4var + skill5var + skill6var + skill7var + skill8var + skill9var + skill10var;
	
	$('#varCommentwhp').html(varmsg);			

	//if(parseInt(censusTotal) !=0){
	$('#skill1Var').val(skill1var);
	$('#skill2Var').val(skill2var);
	$('#skill3Var').val(skill3var);
	$('#skill4Var').val(skill4var);
	$('#skill5Var').val(skill5var);
	$('#skill6Var').val(skill6var);
	$('#skill7Var').val(skill7var);
	$('#skill8Var').val(skill8var);
	$('#skill9Var').val(skill9var);
	$('#skill10Var').val(skill10var);
	$('#workedhrs').val(worked);
	$('#showgridvariancewhp').val(gridvar);	
	//}
		
};  
  

///Clear WHP Record

tj.clearwhpRecord = function(dataId) {
		bootbox.confirm({
        message:"Delete this record?  This cannot be undone.",
		backdrop:true,
        callback:function (result) {
		if (result) {
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
        });
	  }
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
        
		
  }
  
 ///Copy WHP Record

tj.copyPlan = function(dataId,deptId,shift,dayDate) {
		bootbox.confirm({
        message:"Copy data from previous week?",
		backdrop:true,
        callback:function (result) {
		if (result) {
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
        });
		}
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
        
		
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
		var skill6 = $('#skillval6').val();
		var skill7 = $('#skillval7').val();
		var skill8 = $('#skillval8').val();
		var skill9 = $('#skillval9').val();
		var skill10 = $('#skillval10').val();
		var skill11 = $('#skillval11').val();
		var dataId = $('#dataIdWHP').val();
		var userId = $('#selectWHP').val();
		var procedureCount = $('#plannedWHP').val();
		var note = $('#prodnoteWHP').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var role = $('#supportRole').val();
		var indMeasure = $('#indMeasure').val();
		var type = $('#type').val();
		var customVal = $('#customWHP').val();
		var customVal2 = $('#customWHP2').val();
		var customVal3 = $('#customWHP3').val();
		var customVal4 = $('#customWHP4').val();
		var customVal5 = $('#customWHP5').val();
		var provType = $('#provType').val();
		var deptIdadd = $('#deptIdadd').val();
		var trackInsert = $('#trackInsertwhp').val();
		var track1 = $('#track1whp').val();
		var track2 = $('#track2whp').val();
		var trackNote1 = $('#trackNote1whp').val();
		//trackNote1 = trackNote1.stripSlashes();
		var trackNote2 = $('#trackNote2whp').val();
		//trackNote2 = trackNote2.stripSlashes();
		var track3 = $('#track3whp').val();
		var track4 = $('#track4whp').val();
		var trackNote3 = $('#trackNote3whp').val();
		//trackNote3 = trackNote3.stripSlashes();
		var trackNote4 = $('#trackNote4whp').val();
		//trackNote4 = trackNote4.stripSlashes();
		var gvar = $('#showgridvariancewhp').val();
		var linkedId= $('#linkedId').val();
		var linkedDept = $('#linkedDept').val();
		var prov1 = $('#selectA1').val();
		var prov2 = $('#selectA2').val();
		var prov3 = $('#selectP1').val();
		var prov4 = $('#selectP2').val();
		var prov5 = $('#selectE1').val();
		var prov6 = $('#selectC1').val();
		var procs1 = $('#A1pprocs').val();
		var procs2 = $('#A2pprocs').val();
		var procs3 = $('#P1pprocs').val();
		var procs4 = $('#P2pprocs').val();
		var procs5 = $('#E1pprocs').val();
		var procs6 = $('#C1pprocs').val();
		var hours1 = $('#A1phours').val();
		var hours2 = $('#A2phours').val();
		var hours3 = $('#P1phours').val();
		var hours4 = $('#P2phours').val();
		var hours5 = $('#E1phours').val();
		var hours6 = $('#C1phours').val();

		var grid1 = $('#skill1Grid').val();
		var grid2 = $('#skill2Grid').val();
		var grid3 = $('#skill3Grid').val();
		var grid4 = $('#skill4Grid').val();
		var grid5 = $('#skill5Grid').val();
		var grid6 = $('#skill6Grid').val();
		var grid7 = $('#skill7Grid').val();
		var grid8 = $('#skill8Grid').val();
		var grid9 = $('#skill9Grid').val();
		var grid10 = $('#skill10Grid').val();
		
		var gridvar1 = $('#skill1Var').val();
		var gridvar2 = $('#skill2Var').val();
		var gridvar3 = $('#skill3Var').val();
		var gridvar4 = $('#skill4Var').val();
		var gridvar5 = $('#skill5Var').val();
		var gridvar6 = $('#skill6Var').val();
		var gridvar7 = $('#skill7Var').val();
		var gridvar8 = $('#skill8Var').val();
		var gridvar9 = $('#skill9Var').val();
		var gridvar10 = $('#skill10Var').val();
		
		//console.log('LinkedId',linkedId);
		//console.log('LinkedDept',linkedDept);
		
		var submittedby = $('#submittedbywhp').val();
		//console.log('submit',submittedby);
		var submitreqd = $('#submitreqdwhp').val();
		
		
		if(submittedby.length==0 && submitreqd==1) {
			bootbox.alert('Please add your name to the Submitted By field.');
			return;
			}
		
		if(userId==0 && indMeasure==1 && type==2){
		bootbox.alert('You must select a ' + provType + '');
		return;	
		}
		
		if (deptIdadd==350 && (parseInt(procedureCount) != parseInt(customVal)+parseInt(customVal2)+parseInt(customVal3)+parseInt(customVal4)+parseInt(customVal5))) {
			bootbox.alert('Level counts must add up to the total visits.');
			return;
			}
		
		
		if (parseInt(indMeasure)+parseInt(procedureCount)+parseInt(customVal)+parseInt(skill1)+parseInt(skill2)+parseInt(skill3)+parseInt(skill4)+parseInt(skill5)+parseInt(skill6)+parseInt(skill7)+parseInt(skill8)+parseInt(skill9)+parseInt(skill10)+parseInt(skill11)==0) {
		bootbox.confirm({
        message:"Do you want to submit a record with no Hours and no Units of Service?",
		backdrop:true,
        callback:function (result) {
		if (result) { 
		$.ajax({
            url:'inc/data.php?req=updateprodWHP',
            data:{
                shift: shift,
				day: day,
				dataId: dataId,
				procedureCount: procedureCount,
				customVal: customVal,
				customVal2: customVal2,
				customVal3: customVal3,
				customVal4: customVal4,
				customVal5: customVal5,
				note: note,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				skill5: skill5,
				skill6: skill6,
				skill7: skill7,
				skill8: skill8,
				skill9: skill9,
				skill10: skill10,
				skill11: skill11,
				currentTime: currentTime,
				userId: userId,
				trackInsert: trackInsert,
				track1: track1,
				track2: track2,
				track3: track3,
				trackNote1: trackNote1,
				trackNote2: trackNote2,
				track4: track4,
				trackNote3: trackNote3,
				trackNote4: trackNote4,
				submittedby: submittedby,
				submitreqd: submitreqd,
				gvar: gvar,
				prov1: prov1,
				procs1: procs1,
				hours1: hours1,
				prov2: prov2,
				procs2: procs2,
				hours2: hours2,
				prov3: prov3,
				procs3: procs3,
				hours3: hours3,
				prov4: prov4,
				procs4: procs4,
				hours4: hours4,
				prov5: prov5,
				procs5: procs5,
				hours5: hours5,
				prov6: prov6,
				procs6: procs6,
				hours6: hours6,
				grid1: grid1,
				grid2: grid2,
				grid3: grid3,
				grid4: grid4,
				grid5: grid5,
				grid6: grid6,
				grid7: grid7,
				grid8: grid8,
				grid9: grid9,
				grid10: grid10,
				gridvar1: gridvar1,
				gridvar2: gridvar2,
				gridvar3: gridvar3,
				gridvar4: gridvar4,
				gridvar5: gridvar5,
				gridvar6: gridvar6,
				gridvar7: gridvar7,
				gridvar8: gridvar8,
				gridvar9: gridvar9,
				gridvar10: gridvar10
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
				/*
				}else if (response.data.message == false && response.data.escalations==0 && response.data.type==true && ((response.data.textAlerts ==1 && response.data.txtpause ==0) || response.data.emailAlerts==1)) {
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
				document.getElementById("skillval6").value = "";
				document.getElementById("skillval7").value = "";
				document.getElementById("skillval8").value = "";
				document.getElementById("skillval9").value = "";
				document.getElementById("skillval10").value = "";
				document.getElementById("skillval11").value = "";
				document.getElementById("trackInsertwhp").value = "";
				document.getElementById("track1whp").value = "";
				document.getElementById("track2whp").value = "";
				document.getElementById("track3whp").value = "";
				document.getElementById("trackNote1whp").value = "";
				document.getElementById("trackNote2whp").value = "";
				document.getElementById("track4whp").value = "";
				document.getElementById("trackNote3whp").value = "";
				document.getElementById("trackNote4whp").value = "";
				document.getElementById("submittedbywhp").value = "";
				$('#addWHP').modal('hide');
				$('#escalationNEWwhp').modal('show');
				tj.prodTablewhp.ajax.reload(null,false);
				*/
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
				document.getElementById("skillval6").value = "";
				document.getElementById("skillval7").value = "";
				document.getElementById("skillval8").value = "";
				document.getElementById("skillval9").value = "";
				document.getElementById("skillval10").value = "";
				document.getElementById("skillval11").value = "";
				document.getElementById("trackInsertwhp").value = "";
				document.getElementById("track1whp").value = "";
				document.getElementById("track2whp").value = "";
				document.getElementById("track3whp").value = "";
				document.getElementById("trackNote1whp").value = "";
				document.getElementById("trackNote2whp").value = "";
				document.getElementById("track4whp").value = "";
				document.getElementById("trackNote3whp").value = "";
				document.getElementById("trackNote4whp").value = "";
				document.getElementById("submittedbywhp").value = "";
				$('#addWHP').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
				}
				}	
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
            url:'inc/data.php?req=updateprodWHP',
            data:{
                shift: shift,
				day: day,
				dataId: dataId,
				procedureCount: procedureCount,
				customVal: customVal,
				customVal2: customVal2,
				customVal3: customVal3,
				customVal4: customVal4,
				customVal5: customVal5,
				note: note,
				skill1: skill1,
				skill2: skill2,
				skill3: skill3,
				skill4: skill4,
				currentTime: currentTime,
				skill5: skill5,
				skill6: skill6,
				skill7: skill7,
				skill8: skill8,
				skill9: skill9,
				skill10: skill10,
				skill11: skill11,
				userId: userId,
				trackInsert: trackInsert,
				track1: track1,
				track2: track2,
				track3: track3,
				trackNote1: trackNote1,
				trackNote2: trackNote2,
				track4: track4,
				trackNote3: trackNote3,
				trackNote4: trackNote4,
				submittedby: submittedby,
				submitreqd: submitreqd,
				gvar: gvar,
				prov1: prov1,
				procs1: procs1,
				hours1: hours1,
				prov2: prov2,
				procs2: procs2,
				hours2: hours2,
				prov3: prov3,
				procs3: procs3,
				hours3: hours3,
				prov4: prov4,
				procs4: procs4,
				hours4: hours4,
				prov5: prov5,
				procs5: procs5,
				hours5: hours5,
				prov6: prov6,
				procs6: procs6,
				hours6: hours6,
				grid1: grid1,
				grid2: grid2,
				grid3: grid3,
				grid4: grid4,
				grid5: grid5,
				grid6: grid6,
				grid7: grid7,
				grid8: grid8,
				grid9: grid9,
				grid10: grid10,
				gridvar1: gridvar1,
				gridvar2: gridvar2,
				gridvar3: gridvar3,
				gridvar4: gridvar4,
				gridvar5: gridvar5,
				gridvar6: gridvar6,
				gridvar7: gridvar7,
				gridvar8: gridvar8,
				gridvar9: gridvar9,
				gridvar10: gridvar10
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
				/*
				}else if (response.data.message == false && response.data.escalations==0 && response.data.type==true && ((response.data.textAlerts ==1 && response.data.txtpause ==0 && response.data.txtactive ==1 && response.data.txtescalation ==1) || response.data.emailAlerts==1)) {
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
				document.getElementById("skillval6").value = "";
				document.getElementById("skillval7").value = "";
				document.getElementById("skillval8").value = "";
				document.getElementById("skillval9").value = "";
				document.getElementById("skillval10").value = "";
				document.getElementById("skillval11").value = "";
				document.getElementById("trackInsertwhp").value = "";
				document.getElementById("track1whp").value = "";
				document.getElementById("track2whp").value = "";
				document.getElementById("track3whp").value = "";
				document.getElementById("trackNote1whp").value = "";
				document.getElementById("trackNote2whp").value = "";
				document.getElementById("track4whp").value = "";
				document.getElementById("trackNote3whp").value = "";
				document.getElementById("trackNote4whp").value = "";
				document.getElementById("submittedbywhp").value = "";
				$('#addWHP').modal('hide');
				$('#escalationNEWwhp').modal('show');
				tj.prodTablewhp.ajax.reload(null,false);
				*/
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
				document.getElementById("skillval6").value = "";
				document.getElementById("skillval7").value = "";
				document.getElementById("skillval8").value = "";
				document.getElementById("skillval9").value = "";
				document.getElementById("skillval10").value = "";
				document.getElementById("skillval11").value = "";
				document.getElementById("trackInsertwhp").value = "";
				document.getElementById("track1whp").value = "";
				document.getElementById("track2whp").value = "";
				document.getElementById("track3whp").value = "";
				document.getElementById("trackNote1whp").value = "";
				document.getElementById("trackNote2whp").value = "";
				document.getElementById("track4whp").value = "";
				document.getElementById("trackNote3whp").value = "";
				document.getElementById("trackNote4whp").value = "";
				document.getElementById("submittedbywhp").value = "";
				$('#addWHP').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
				}
				}	
            }
			
        }) 
  }
				
 }
	
	  /////////////////////////////////////
// ADD PROD NOTE
	
tj.addProdNotewhp = function () {
        var dataId = $('#notedataIdwhp').val();
		var note = $('#notebodywhp').val();
		var deptId = $('#notedeptIdwhp').val();
		var linkedId= $('#linkedId').val();
		var linkedDept = $('#linkedDept').val();
		
		console.log('linkedId',linkedId);
		
        $.ajax({
            url: 'inc/data.php?req=prodNote',
            data: {
                note: note,
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success:function(response) {
				if(response.data.escalations==0 && response.data.type!=2 && ((response.data.txtactive==1 && response.data.textAlerts==1 && response.data.txtpause==0 && response.data.txtescalation==1) || response.data.emailAlerts==1)){
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
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
			}
			}
        });
    }
	
tj.linkedDept = function () {
		var linkedId= $('#linkedId').val();
		var linkedDept = $('#linkedDept').val();
		$('#escalationNEWwhp').modal('hide');
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
    }
	
tj.linkedDept2 = function () {
		var linkedId= $('#linkedId').val();
		var linkedDept = $('#linkedDept').val();
		$('#addEscalationwhp').modal('hide');
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
    }
	
	
tj.noEscalationwhp = function () {
        //var dataId = $('#dataId').val();
		var esc = 0;
		var sendtext = 0;
		var dataId = $('#dataIdescwhp').val();
		var deptId = $('#deptIdescwhp').val();
				
        $.ajax({
            url: 'inc/data.php?req=addEscalation',
            data: {
                esc: esc,
                sendtext: sendtext,
				dataId: dataId,
				deptId: deptId
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
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var linkedId= $('#linkedId').val();
		var linkedDept = $('#linkedDept').val();
		
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
				comment: comment,
				currentTime: currentTime
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
				if(linkedId !=0){
					bootbox.confirm({
					message:"Continue to "+ linkedDept + " Staffing Report?",
					backdrop:true,
					callback:function (result) {
					if (result) {
					tj.editWHP(linkedId);
					}
					
				}
				
				}).find('.modal-content').css({
				'background-color': '#fff',
				'color': '#000',
				'font-size': '16px'
				});				
            }
            }
        });
    }
	
tj.getEscalationwhp = function (dataId) {
		//console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getEscalation',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				$('#dataIdeditwhp').val(response.data.id);
				$('#edittypewhp').val(response.data.escalationName);
				$('#editunitwhp').val(response.data.dept);
				$('#editSubmittedwhp').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dateSubmitted);
				$('#escalationcommenteditwhp').val(response.data.note);
				$('#escalationresponsewhp').val(response.data.response);
                $('#editEscalationwhp').modal('show');
            }
        });
}
	
tj.updateEscalationwhp = function () {
        var dataId = $('#dataIdeditwhp').val();
		var response = $('#escalationresponsewhp').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		
        $.ajax({
            url: 'inc/data.php?req=updateEscalation',
            data: {
                dataId: dataId,
				response: response,
				currentTime: currentTime
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Response Successfully Submitted.');
				document.getElementById("dataIdeditwhp").value = "";
				document.getElementById("escalationresponsewhp").value = "";
                $('#editEscalationwhp').modal('hide');
				tj.prodTablewhp.ajax.reload(null,false);
            }
        });
}
	
tj.addEscalation3whp = function () {
        var dataId = 0;
		var deptId = $('#deptId3whp').val();
		var esc = $('#escval3whp').val();
		var comment = $('#escalationcomment3whp').val();
		var sendtext = 1;
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	if(esc==0){
    bootbox.alert('Please select an Escalation');
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
				comment: comment,
				currentTime: currentTime
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

// GET DAY RATING DATA

tj.getdayRatingwhp = function(deptId,countDept) {
		//$('#addProd').modal({backdrop: 'static', keyboard: false})
        //console.log('deptId:', deptId);
		//console.log('count:', countDept);
		newDate = tj.prodStartDatewhp;
		newEndDate = tj.prodEndDatewhp;
		console.log('date:', newDate);
		$('#dayDatewhp').val('');
		$('#dayUnitwhp').val('');
		$('#dateNew').val('');
		$('#dateExisting').val('');
		$('#dayUpdatedwhp').html('');
		$('#dayUserwhp').html('');
		$('#dayReferrals').val('');
        if(deptId && countDept==1 && newDate==newEndDate){
		$.ajax({
            url:'inc/data.php?req=getdayRankdetails',
            data:{
                newDate: newDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//if(countDept==1){
				$('#dayUnitwhp').val(response.data.deptId);
				//}
				if(response.data.dayRank !='0'){
				$('#dayUpdatedwhp').html('Last Update: '+response.data.submittedDate);				
				$('#dayUserwhp').html('Updated By: '+response.data.first_name+' '+response.data.last_name);
				}
				$('#dayDatewhp').val(response.data.dayDate);
				$('#daySDwhp').val(response.data.dayRank);
				$('#dateNew').val(response.data.dateNew);
				$('#dateExisting').val(response.data.dateExisting);
				$('#dayEEwhp').val(response.data.empEngagement);
				$('#dayrateIdwhp').val(response.data.id);
				$('#addDaywhp').modal('show');
				$('#dayReferrals').val(response.data.referrals);
			}
        })
		}else{
			if(newDate==newEndDate){
				$('#dayDatewhp').val(newDate);
			}
				$('#dayEEwhp').val('0');
				$('#daySDwhp').val('0');
				$('#dayrateIdwhp').val('0');
				$('#addDaywhp').modal('show');
		}
        
  }
  
// GET DAY RATING DATA

tj.checkdaywhp = function() {
		var dayDate = $('#dayDatewhp').val();
		var deptId = $('#dayUnitwhp').val();
        if(dayDate && deptId !=0){
		$.ajax({
            url:'inc/data.php?req=checkday',
            data:{
                dayDate: dayDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.message==true){
				bootbox.alert('A record has already been submitted for this Unit/Day.');
				$('#daySDwhp').val(response.data.dayRank);
				$('#dayEEwhp').val(response.data.empEngagement);
				}else{
				$('#daySDwhp').val('0');
				$('#dayEEwhp').val('0');
				}
				
			}
        })
		}        
  }
  
 tj.updatedayRatingwhp = function() {
		var dataId = $('#dayrateIdwhp').val();
		var sd = $('#daySDwhp').val();
		var ee = $('#dayEEwhp').val();
		var currentTime = moment().format('YYYY-MM-DD');
					
		var deptId = $('#dayUnitwhp').val();
		var dayDate = $('#dayDatewhp').val();
		var dateNew = $('#dateNew').val();
		var dateExisting = $('#dateExisting').val();
		var referrals = $('#dayReferrals').val();
		//console.log('dataId: ',dataId);
		//console.log('dateNew: ',dateNew);
		//console.log('dateExisting: ',dateExisting);
		//console.log('curDate: ',currentTime);
		
		if(ee.length==0 || deptId.length==0 || dayDate.length==0){
		bootbox.alert('All fields are required.');
		return;
		}
		
		if(dateExisting.length>0 && (dateExisting.length<6 || dateExisting.length>10)){
		bootbox.alert('Please correct date format to mm/dd/yyyy');
		return;
		}
		if(dateNew.length>0 && (dateNew.length<6 || dateNew.length>10)){
		bootbox.alert('Please correct date format to mm/dd/yyyy');
		return;
		}
		if(dayDate.length>0 && (dayDate.length<6 || dayDate.length>10)){
		bootbox.alert('Please correct date format to mm/dd/yyyy');
		return;
		}
		var dateE = new Date(dateExisting);
		var dateN = new Date(dateNew);
		var dateC = new Date(currentTime);
		
		var secondsE = dateE.getTime() / 1000; //1440516958
		var secondsN = dateN.getTime() / 1000; //1440516958
		var secondsC = dateC.getTime() / 1000; //1440516958
		
		if(secondsN < secondsC || secondsE < secondsC){
			bootbox.alert('Date submitted cannot be prior to today\'s date');
		return;
		}
		
        $.ajax({
            url:'inc/data.php?req=editdayRank',
            data:{
                dataId: dataId,
				sd: sd,
				ee: ee,
				currentTime: currentTime,
				dayDate: dayDate,
				deptId: deptId,
				dateNew: dateNew,
				dateExisting: dateExisting,
				referrals: referrals
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#addDaywhp').modal('hide');
				//tj.dayRankTable.ajax.reload();
				bootbox.alert('Record Successfully Updated.');
				}
        })
        
  }

tj.setUserDateswhp = function() {
	var startuser = tj.prodStartDatewhp;
	var enduser = tj.prodEndDatewhp;
	//console.log('startuser',startuser);
	//console.log('enduser',enduser);
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
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
	document.getElementById('group0').style.display="none";
    tj.performanceTable = $('#performanceTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getPerformance",
            data: function(d) {
				d.id = tj.performanceId;
				d.dept = tj.performanceDept;
                d.start = tj.performanceStartDate;
                d.end = tj.performanceEndDate;
				d.group = tj.currentGroup;
				d.shift = tj.performanceShift;
            },
            type:"POST"
        },
        "order": [[0,'asc'],[1,'desc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
            { "data": "unit" },
			{ "data": "desc" },
			{ "data": "pdf" }
        ]
		/*
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + tj.performanceStartDate + ' through ' + tj.performanceEndDate,
				title: 'Performance',
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
		*/
    } );


tj.categoryGroup = function() {
		var url= document.location.href;
		var group = $('#categoryGroup').val();
		window.history.pushState({}, "", url.split("?")[0]);
		tj.performanceId='';
		tj.performanceDept='';
		var a0 = document.getElementById('group0');
		var a1 = document.getElementById('group1');
		tj.currentGroup = group;
		$('#currentGroup').val(group);
		console.log('group: ',group);
		if (group==0){
			a0.style.display='none';
			a1.style.display='';
		}else{
			a0.style.display='';
			a1.style.display='none';
		}		
		tj.performanceTable.ajax.reload();
		
    };	

tj.setPerfDates = function() {
	var startuser = tj.perfStartDate;
	var enduser = tj.perfEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
};

}

/////////////////////////////////////
// VIEW LOG GLOBALS
//tj.performanceId = '';

tj.viewlogStartDate = '';
tj.viewlogEndDate = '';
tj.initializeviewlogGrid = function(id) {
    tj.viewlogStartDate = moment().format('YYYY-MM-DD');
    tj.viewlogEndDate = moment().format('YYYY-MM-DD');
    tj.viewlogTable = $('#viewlogTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getviewlog",
            data: function(d) {
				d.id = tj.viewlogId;
                d.start = tj.viewlogStartDate;
                d.end = tj.viewlogEndDate;
            },
            type:"POST"
        },
        "order": [[2,'desc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
// COMPLIANCE GLOBALS
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
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "categoryName" },
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
// PDF GLOBALS


tj.initializeEmailGrid = function() {
	
    tj.emailTable = $('#emailTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getemaildist",
            data: function(d) {
				d.deptId = tj.emaildeptId;
            },
            type:"POST"
        },
        "order": [[3,'desc'],[0,'asc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "user" },
			{ "data": "email" },
			{ "data": "dist" },
			{ "data": "reports" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [3] }
                     ]
    });

tj.emailChange = function(userId,recordId,accountId,deptId) {
	var sendEmail = $('#emailChange' + userId + recordId).is(':checked') ? 1 : 0;
	//console.log('user',userId);
	//console.log('recordId',recordId);
	//console.log('accountId',accountId);
	//console.log('changeId',deptId);
	//console.log('sendEmail',sendEmail);
	var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	$.ajax({
        url:"inc/data.php?req=emailChange",
        data: {
                deptId: deptId,
				userId: userId,
				accountId: accountId,
				sendEmail: sendEmail,
				recordId: recordId,
				currentTime: currentTime
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		tj.emailTable.ajax.reload();			
		//tj.emailTable.ajax.reload();
		//$('#emailReports').modal('toggle');
        }
    })
	
}


}


tj.pdfStartDate = '';
tj.pdfEndDate = '';
tj.initializePdfGrid = function(id) {
    tj.pdfStartDate = moment().format('YYYY-MM-DD');
    tj.pdfEndDate = moment().format('YYYY-MM-DD');
    tj.pdfTable = $('#pdfTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getpdfs",
            data: function(d) {
				d.id = tj.pdfId;
                d.start = tj.pdfStartDate;
                d.end = tj.pdfEndDate;
            },
            type:"POST"
        },
        //"order": [[0,'asc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "desc" },
			{ "data": "type" },
			{ "data": "type2" }
        ],
    } );
	
tj.customReport = function(pdfId,deptId) {
	//var startuser = tj.customStartDate;
	//var enduser = tj.customEndDate;
	//console.log('pdfId',pdfId);
	//console.log('deptId',deptId);
    $.ajax({
        url:'inc/data.php?req=pdf' + pdfId,
        data: {
				pdfId: pdfId,
                deptId: deptId
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			
        }
    })
}

tj.emailDist = function(accountId,deptId) {
	console.log('deptId',deptId);
	tj.emaildeptId = deptId;
	$.ajax({
        url:"inc/data.php?req=getreportdetails",
        data: {
                deptId: deptId
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		if(deptId==0){
		$('#reportName').html('All Units Shift Detail Report');			
		}else if(deptId==2){
		$('#reportName').html('Escalations Report');			
		}else{
		$('#reportName').html('Multi-Unit Shift Detail Report');
		}	
		if(response.data.emailTime=='00:00:00'){
		$('#dailyTime').html('Not Active');
		}else{
		$('#dailyTime').html(response.data.emailTime);
		}
		$('#reportdeptId').val(deptId);	
		tj.emailTable.ajax.reload();
		$('#emailReports').modal('show');
        }
    })
	
}

tj.addnonUser = function() {
	var deptId = $('#reportdeptId').val();
	var first = $('#emailFirst').val();
	var last = $('#emailLast').val();
	var email = $('#emailAdd').val();
	var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	console.log('reprtId',deptId);
	
	if(first.length == 0 || last.length == 0 || email.length == 0){
    bootbox.alert('All fields are required.');
    return;
	}
	
	
	$.ajax({
        url:'inc/data.php?req=addnonUser',
        data: {
				deptId: deptId,
                first: first,
				last: last,
				email: email,
				currentTime: currentTime
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
		tj.emailTable.ajax.reload();
		$('#addNonUser').modal('hide');	
        }
    })
	
}

tj.addEmail = function() {
document.getElementById("emailFirst").value = "";
document.getElementById("emailLast").value = "";
document.getElementById("emailAdd").value = "";
var deptId = $('#reportdeptId').val();
//if(deptId==1){
//    bootbox.alert('External emails are not available for this report.');
//    return;
//	}
$('#addNonUser').modal('show');		
}
	
}

tj.setUserDatesCustom = function() {
	var startuser = tj.customStartDate;
	var enduser = tj.customEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
}




///////////////////////////////////
//TASK GLOBALS
///
tj.tasksStartDate = '';
tj.tasksEndDate = '';
tj.initializeTasksGrid = function(id) {
    //tj.tasksStartDate = moment().format('YYYY-MM-DD');
    //tj.tasksEndDate = moment().format('YYYY-MM-DD');
	//tj.tasksHour = moment().format('HH:mm');
    tj.tasksTable = $('#tasksTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=gettasks",
            data: function(d) {
				d.id = '1';
            },
            type:"POST"
        },
        "order":  [[6,'desc'],[0,'asc'],[1,'asc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "name" },
			{ "data": "days" },
			{ "data": "time" },
			{ "data": "text" },
			{ "data": "entered" },
			{ "data": "active" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [6] }
                     ],
    } );

	
tj.textMe = function(userId,taskId,active) {
	var textMe = $('#text' + taskId).is(':checked') ? 1 : 0;
	//console.log('taskId',taskId);
	//console.log('userId',userId);
	console.log('textMe',textMe);
        $.ajax({
            url:'inc/data.php?req=updatetaskText',
            data:{
                taskId: taskId,
				userId: userId,
				textMe: textMe
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(textMe==1 && active==1){
				bootbox.alert('Text Added');	
				}else if(textMe==1 && active==0){
				bootbox.alert('Task is not Active');	
				}else{
				bootbox.alert('Text Removed');	
				}					
				tj.tasksTable.ajax.reload(null,false);
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }
  
 tj.closeTask = function() {
	var taskId = $('#edittaskId').val();
	var active = $('#deactivate').is(':checked') ? 0 : 1;
	console.log('taskId', taskId);
	console.log('active',active);
        $.ajax({
            url:'inc/data.php?req=closeTask',
            data:{
                taskId: taskId,
				active: active
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
								
				tj.tasksTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }
  
  tj.saveTask = function() {
	var taskName = $('#taskName').val();
	var taskUnit = $('#taskUnit').val();
	var taskDays = $('#taskDays').val();
	var taskDue = $('#taskDue').val();
	var taskIns = $('#taskIns').val();
	var task1 = $('#task1').val();
	var type1 = $('#type1').val();
	var task2 = $('#task2').val();
	var type2 = $('#type2').val();
	var task3 = $('#task3').val();
	var type3 = $('#type3').val();
	var task4 = $('#task4').val();
	var type4 = $('#type4').val();
	var task5 = $('#task5').val();
	var type5 = $('#type5').val();
	var task6 = $('#task6').val();
	var type6 = $('#type6').val();
	var task7 = $('#task7').val();
	var type7 = $('#type7').val();
	var task8 = $('#task8').val();
	var type8 = $('#type8').val();
	var task9 = $('#task9').val();
	var type9 = $('#type9').val();
	var task10 = $('#task10').val();
	var type10 = $('#type10').val();
	var task11 = $('#task11').val();
	var type11 = $('#type11').val();
	var task12 = $('#task12').val();
	var type12 = $('#type12').val();
	var task13 = $('#task13').val();
	var type13 = $('#type13').val();
	var task14 = $('#task14').val();
	var type14 = $('#type14').val();
	var task15 = $('#task15').val();
	var type15 = $('#type15').val();
	
	var r1 = $('#r1').is(':checked') ? 1 : 0;
	var r2 = $('#r2').is(':checked') ? 1 : 0;
	var r3 = $('#r3').is(':checked') ? 1 : 0;
	var r4 = $('#r4').is(':checked') ? 1 : 0;
	var r5 = $('#r5').is(':checked') ? 1 : 0;
	var r6 = $('#r6').is(':checked') ? 1 : 0;
	var r7 = $('#r7').is(':checked') ? 1 : 0;
	var r8 = $('#r8').is(':checked') ? 1 : 0;
	var r9 = $('#r9').is(':checked') ? 1 : 0;
	var r10 = $('#r10').is(':checked') ? 1 : 0;
	var r11 = $('#r11').is(':checked') ? 1 : 0;
	var r12 = $('#r12').is(':checked') ? 1 : 0;
	var r13 = $('#r13').is(':checked') ? 1 : 0;
	var r14 = $('#r14').is(':checked') ? 1 : 0;
	var r15 = $('#r15').is(':checked') ? 1 : 0;
	
	
	
        $.ajax({
            url:'inc/data.php?req=SaveTask',
            data:{
                taskName: taskName,
				taskUnit: taskUnit,
				taskDays: taskDays,
				taskDue: taskDue,
				task1 : task1,
				task2 : task2,
				task3 : task3,
				task4 : task4,
				task5 : task5,
				task6 : task6,
				task7 : task7,
				task8 : task8,
				task9 : task9,
				task10 : task10,
				task11 : task11,
				task12 : task12,
				task13 : task13,
				task14 : task14,
				task15 : task15,
				type1 : type1,
				type2 : type2,
				type3 : type3,
				type4 : type4,
				type5 : type5,
				type6 : type6,
				type7 : type7,
				type8 : type8,
				type9 : type9,
				type10 : type10,
				type11 : type11,
				type12 : type12,
				type13 : type13,
				type14 : type14,
				type15 : type15,
				taskIns : taskIns,
				r1 : r1,
				r2 : r2,
				r3 : r3,
				r4 : r4,
				r5 : r5,
				r6 : r6,
				r7 : r7,
				r8 : r8,
				r9 : r9,
				r10 : r10,
				r11 : r11,
				r12 : r12,
				r13 : r13,
				r14 : r14,
				r15 : r15			
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#addTask').modal('hide');
				bootbox.alert('Task Created');						
				tj.tasksTable.ajax.reload();
				tj.crashTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }
  
 tj.updateTask = function() {
	 var dataId = $('#edittaskId').val();
	var taskName = $('#edittaskName').val();
	var taskUnit = $('#edittaskUnit').val();
	var taskDays = $('#edittaskDays').val();
	var taskDue = $('#edittaskDue').val();
	var taskIns = $('#edittaskIns').val();
	var task1 = $('#edittask1').val();
	var type1 = $('#edittype1').val();
	var task2 = $('#edittask2').val();
	var type2 = $('#edittype2').val();
	var task3 = $('#edittask3').val();
	var type3 = $('#edittype3').val();
	var task4 = $('#edittask4').val();
	var type4 = $('#edittype4').val();
	var task5 = $('#edittask5').val();
	var type5 = $('#edittype5').val();
	var task6 = $('#edittask6').val();
	var type6 = $('#edittype6').val();
	var task7 = $('#edittask7').val();
	var type7 = $('#edittype7').val();
	var task8 = $('#edittask8').val();
	var type8 = $('#edittype8').val();
	var task9 = $('#edittask9').val();
	var type9 = $('#edittype9').val();
	var task10 = $('#edittask10').val();
	var type10 = $('#edittype10').val();
	var task11 = $('#edittask11').val();
	var type11 = $('#edittype11').val();
	var task12 = $('#edittask12').val();
	var type12 = $('#edittype12').val();
	var task13 = $('#edittask13').val();
	var type13 = $('#edittype13').val();
	var task14 = $('#edittask14').val();
	var type14 = $('#edittype14').val();
	var task15 = $('#edittask15').val();
	var type15 = $('#edittype15').val();
	var copyTask = $('#copyTask').val();
	
	var r1 = $('#editr1').is(':checked') ? 1 : 0;
	var r2 = $('#editr2').is(':checked') ? 1 : 0;
	var r3 = $('#editr3').is(':checked') ? 1 : 0;
	var r4 = $('#editr4').is(':checked') ? 1 : 0;
	var r5 = $('#editr5').is(':checked') ? 1 : 0;
	var r6 = $('#editr6').is(':checked') ? 1 : 0;
	var r7 = $('#editr7').is(':checked') ? 1 : 0;
	var r8 = $('#editr8').is(':checked') ? 1 : 0;
	var r9 = $('#editr9').is(':checked') ? 1 : 0;
	var r10 = $('#editr10').is(':checked') ? 1 : 0;
	var r11 = $('#editr11').is(':checked') ? 1 : 0;
	var r12 = $('#editr12').is(':checked') ? 1 : 0;
	var r13 = $('#editr13').is(':checked') ? 1 : 0;
	var r14 = $('#editr14').is(':checked') ? 1 : 0;
	var r15 = $('#editr15').is(':checked') ? 1 : 0;
	var active = $('#deactivate').is(':checked') ? 0 : 1;
	var dayDate = moment().format('YYYY-MM-DD');
	
	
	
        $.ajax({
            url:'inc/data.php?req=UpdateTask',
            data:{
                dataId: dataId,
				taskName: taskName,
				taskUnit: taskUnit,
				taskDays: taskDays,
				taskDue: taskDue,
				task1 : task1,
				task2 : task2,
				task3 : task3,
				task4 : task4,
				task5 : task5,
				task6 : task6,
				task7 : task7,
				task8 : task8,
				task9 : task9,
				task10 : task10,
				task11 : task11,
				task12 : task12,
				task13 : task13,
				task14 : task14,
				task15 : task15,
				type1 : type1,
				type2 : type2,
				type3 : type3,
				type4 : type4,
				type5 : type5,
				type6 : type6,
				type7 : type7,
				type8 : type8,
				type9 : type9,
				type10 : type10,
				type11 : type11,
				type12 : type12,
				type13 : type13,
				type14 : type14,
				type15 : type15,
				taskIns : taskIns,
				r1 : r1,
				r2 : r2,
				r3 : r3,
				r4 : r4,
				r5 : r5,
				r6 : r6,
				r7 : r7,
				r8 : r8,
				r9 : r9,
				r10 : r10,
				r11 : r11,
				r12 : r12,
				r13 : r13,
				r14 : r14,
				r15 : r15,
				active: active,
				copyTask: copyTask,
				dayDate: dayDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#editTask').modal('hide');
				bootbox.alert('Task Updated');						
				tj.tasksTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }
  
 tj.copyTask = function() {
	 var dataId = $('#edittaskId').val();
	var taskName = $('#edittaskName').val();
	var taskUnit = $('#edittaskUnit').val();
	var taskDays = $('#edittaskDays').val();
	var taskDue = $('#edittaskDue').val();
	var taskIns = $('#edittaskIns').val();
	var task1 = $('#edittask1').val();
	var type1 = $('#edittype1').val();
	var task2 = $('#edittask2').val();
	var type2 = $('#edittype2').val();
	var task3 = $('#edittask3').val();
	var type3 = $('#edittype3').val();
	var task4 = $('#edittask4').val();
	var type4 = $('#edittype4').val();
	var task5 = $('#edittask5').val();
	var type5 = $('#edittype5').val();
	var task6 = $('#edittask6').val();
	var type6 = $('#edittype6').val();
	var task7 = $('#edittask7').val();
	var type7 = $('#edittype7').val();
	var task8 = $('#edittask8').val();
	var type8 = $('#edittype8').val();
	var task9 = $('#edittask9').val();
	var type9 = $('#edittype9').val();
	var task10 = $('#edittask10').val();
	var type10 = $('#edittype10').val();
	var task11 = $('#edittask11').val();
	var type11 = $('#edittype11').val();
	var task12 = $('#edittask12').val();
	var type12 = $('#edittype12').val();
	var task13 = $('#edittask13').val();
	var type13 = $('#edittype13').val();
	var task14 = $('#edittask14').val();
	var type14 = $('#edittype14').val();
	var task15 = $('#edittask15').val();
	var type15 = $('#edittype15').val();
	var logType = $('#logType').val();
	var logLock = $('#logLock').val();
	
	var r1 = $('#editr1').is(':checked') ? 1 : 0;
	var r2 = $('#editr2').is(':checked') ? 1 : 0;
	var r3 = $('#editr3').is(':checked') ? 1 : 0;
	var r4 = $('#editr4').is(':checked') ? 1 : 0;
	var r5 = $('#editr5').is(':checked') ? 1 : 0;
	var r6 = $('#editr6').is(':checked') ? 1 : 0;
	var r7 = $('#editr7').is(':checked') ? 1 : 0;
	var r8 = $('#editr8').is(':checked') ? 1 : 0;
	var r9 = $('#editr9').is(':checked') ? 1 : 0;
	var r10 = $('#editr10').is(':checked') ? 1 : 0;
	var r11 = $('#editr11').is(':checked') ? 1 : 0;
	var r12 = $('#editr12').is(':checked') ? 1 : 0;
	var r13 = $('#editr13').is(':checked') ? 1 : 0;
	var r14 = $('#editr14').is(':checked') ? 1 : 0;
	var r15 = $('#editr15').is(':checked') ? 1 : 0;
	var active = $('#deactivate').is(':checked') ? 0 : 1;
	
	
	
        $.ajax({
            url:'inc/data.php?req=CopyTask',
            data:{
                dataId: dataId,
				taskName: taskName,
				taskUnit: taskUnit,
				taskDays: taskDays,
				taskDue: taskDue,
				task1 : task1,
				task2 : task2,
				task3 : task3,
				task4 : task4,
				task5 : task5,
				task6 : task6,
				task7 : task7,
				task8 : task8,
				task9 : task9,
				task10 : task10,
				task11 : task11,
				task12 : task12,
				task13 : task13,
				task14 : task14,
				task15 : task15,
				type1 : type1,
				type2 : type2,
				type3 : type3,
				type4 : type4,
				type5 : type5,
				type6 : type6,
				type7 : type7,
				type8 : type8,
				type9 : type9,
				type10 : type10,
				type11 : type11,
				type12 : type12,
				type13 : type13,
				type14 : type14,
				type15 : type15,
				taskIns : taskIns,
				r1 : r1,
				r2 : r2,
				r3 : r3,
				r4 : r4,
				r5 : r5,
				r6 : r6,
				r7 : r7,
				r8 : r8,
				r9 : r9,
				r10 : r10,
				r11 : r11,
				r12 : r12,
				r13 : r13,
				r14 : r14,
				r15 : r15,
				active: active,
				logType: logType,
				logLock: logLock
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#editTask').modal('hide');
				bootbox.alert('Copy of Task Created');						
				tj.tasksTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }
  
  tj.editTask = function(dataId) {
	
        $.ajax({
            url:'inc/data.php?req=gettaskDetails',
            data:{
                dataId: dataId		
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
					$('#copyTask').val(response.data.copyTask);
					$('#edittaskId').val(response.data.id);
					$('#edittaskName').val(response.data.title);
					$('#edittaskUnit').val(response.data.deptId);
					$('#edittaskDays').val(response.data.dow);
					$('#edittaskDue').val(response.data.shift);
					$('#edittaskIns').val(response.data.instructions);
					$('#edittask1').val(response.data.q1);
					$('#edittype1').val(response.data.qType1);
					$('#edittask2').val(response.data.q2);
					$('#edittype2').val(response.data.qType2);
					$('#edittask3').val(response.data.q3);
					$('#edittype3').val(response.data.qType3);
					$('#edittask4').val(response.data.q4);
					$('#edittype4').val(response.data.qType4);
					$('#edittask5').val(response.data.q5);
					$('#edittype5').val(response.data.qType5);
					$('#edittask6').val(response.data.q6);
					$('#edittype6').val(response.data.qType6);
					$('#edittask7').val(response.data.q7);
					$('#edittype7').val(response.data.qType7);
					$('#edittask8').val(response.data.q8);
					$('#edittype8').val(response.data.qType8);
					$('#edittask9').val(response.data.q9);
					$('#edittype9').val(response.data.qType9);
					$('#edittask10').val(response.data.q10);
					$('#edittype10').val(response.data.qType10);
					$('#edittask11').val(response.data.q11);
					$('#edittype11').val(response.data.qType11);
					$('#edittask12').val(response.data.q12);
					$('#edittype12').val(response.data.qType12);
					$('#edittask13').val(response.data.q13);
					$('#edittype13').val(response.data.qType13);
					$('#edittask14').val(response.data.q14);
					$('#edittype14').val(response.data.qType14);
					$('#edittask15').val(response.data.q15);
					$('#edittype15').val(response.data.qType15);
					$('#logType').val(response.data.logType);
					$('#logLock').val(response.data.logLock);
					
					if(response.data.copyTask==1){
					document.getElementById("edittaskUnit").disabled = false;	
					}else{
					document.getElementById("edittaskUnit").disabled = true;		
					}
					if(response.data.qType1==3){
					document.getElementById("editr1").disabled=true;
					document.getElementById("editr1").checked = false;
					}else if(response.data.qType1 !=3 && response.data.r1==1){
					document.getElementById("editr2").checked = true;
					}else{
					document.getElementById("editr1").checked = false;				
					}
					
					if(response.data.qType2==3){
					document.getElementById("editr2").disabled=true;
					document.getElementById("editr2").checked = false;
					}else if(response.data.qType2 !=3 && response.data.r2==1){
					document.getElementById("editr2").checked = true;
					}else{
					document.getElementById("editr2").checked = false;				
					}
					
					if(response.data.qType3==3){
					document.getElementById("editr3").disabled=true;
					document.getElementById("editr3").checked = false;
					}else if(response.data.qType3 !=3 && response.data.r3==1){
					document.getElementById("editr3").checked = true;
					}else{
					document.getElementById("editr3").checked = false;				
					}
					
					if(response.data.qType4==3){
					document.getElementById("editr4").disabled=true;
					document.getElementById("editr4").checked = false;
					}else if(response.data.qType4 !=3 && response.data.r4==1){
					document.getElementById("editr4").checked = true;
					}else{
					document.getElementById("editr4").checked = false;				
					}
					
					if(response.data.qType5==3){
					document.getElementById("editr5").disabled=true;
					document.getElementById("editr5").checked = false;
					}else if(response.data.qType5 !=3 && response.data.r5==1){
					document.getElementById("editr5").checked = true;
					}else{
					document.getElementById("editr5").checked = false;				
					}
					
					if(response.data.qType6==3){
					document.getElementById("editr6").disabled=true;
					document.getElementById("editr6").checked = false;
					}else if(response.data.qType6 !=3 && response.data.r6==1){
					document.getElementById("editr6").checked = true;
					}else{
					document.getElementById("editr6").checked = false;				
					}
					
					if(response.data.qType7==3){
					document.getElementById("editr7").disabled=true;
					document.getElementById("editr7").checked = false;
					}else if(response.data.qType7 !=3 && response.data.r7==1){
					document.getElementById("editr7").checked = true;
					}else{
					document.getElementById("editr7").checked = false;				
					}
					
					
					if(response.data.qType8==3){
					document.getElementById("editr8").disabled=true;
					document.getElementById("editr8").checked = false;
					}else if(response.data.qType8 !=3 && response.data.r8==1){
					document.getElementById("editr8").checked = true;
					}else{
					document.getElementById("editr8").checked = false;				
					}
					
					if(response.data.qType9==3){
					document.getElementById("editr9").disabled=true;
					document.getElementById("editr9").checked = false;
					}else if(response.data.qType9 !=3 && response.data.r9==1){
					document.getElementById("editr9").checked = true;
					}else{
					document.getElementById("editr9").checked = false;				
					}
					
					if(response.data.qType10==3){
					document.getElementById("editr10").disabled=true;
					document.getElementById("editr10").checked = false;
					}else if(response.data.qType10 !=3 && response.data.r10==1){
					document.getElementById("editr10").checked = true;
					}else{
					document.getElementById("editr10").checked = false;				
					}
					
					if(response.data.qType11==3){
					document.getElementById("editr11").disabled=true;
					document.getElementById("editr11").checked = false;
					}else if(response.data.qType11 !=3 && response.data.r11==1){
					document.getElementById("editr11").checked = true;
					}else{
					document.getElementById("editr11").checked = false;				
					}
					
					if(response.data.qType12==3){
					document.getElementById("editr12").disabled=true;
					document.getElementById("editr12").checked = false;
					}else if(response.data.qType12 !=3 && response.data.r12==1){
					document.getElementById("editr12").checked = true;
					}else{
					document.getElementById("editr12").checked = false;				
					}
					
					if(response.data.qType13==3){
					document.getElementById("editr13").disabled=true;
					document.getElementById("editr13").checked = false;
					}else if(response.data.qType13 !=3 && response.data.r13==1){
					document.getElementById("editr13").checked = true;
					}else{
					document.getElementById("editr13").checked = false;				
					}
					
					if(response.data.qType14==3){
					document.getElementById("editr14").disabled=true;
					document.getElementById("editr14").checked = false;
					}else if(response.data.qType14 !=3 && response.data.r14==1){
					document.getElementById("editr14").checked = true;
					}else{
					document.getElementById("editr14").checked = false;	
					}
					
					if(response.data.qType15==3){
					document.getElementById("editr15").disabled=true;
					document.getElementById("editr15").checked = false;
					}else if(response.data.qType15 !=3 && response.data.r15==1){
					document.getElementById("editr15").checked = true;
					}else{
					document.getElementById("editr15").checked = false;				
					}
					if(response.data.active==1){
					document.getElementById("deactivate").checked = false;
					}else{
					document.getElementById("deactivate").checked = true;				
					}
					//console.log('r15',response.data.r15);
				//$('#editTask').modal('toggle');
				$('#editTask').modal('show');
				//bootbox.alert('Task Updated');						
				//tj.tasksTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  }

 
	
	
};
/////////////////////////////////////
// CRASH GLOBALS


tj.initializeCrashGrid = function(id) {

    tj.crashTable = $('#crashTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getcrash",
            data: function(d) {
				d.id = '1';
                d.start = tj.tasksStartDate;
                d.end = tj.tasksEndDate;
				d.tasksTime = tj.tasksTime;
				d.tasksHour = tj.tasksHour;
            },
            type:"POST"
        },
        "order": [[0,'asc']],
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
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "date" },
			{ "data": "user" },
			{ "data": "entered" }
        ],
    } );

tj.setUserDatesTasks = function() {
	var startuser = tj.tasksStartDate;
	var enduser = tj.tasksEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
} 

tj.generateQRCode = function(logix2,id){
	document.getElementById("qrcodename").innerHTML = "";
	document.getElementById("qrcode").innerHTML = "";
	document.getElementById("closeQR").style.display='';
	console.log('logix2', logix2);
	//var logix = parseInt(logix2).toFixed(0);
	var qrcodelink = 'https://productivern.com/go/indexx.php?m=' + logix2 + '&p=' + id + '';
	new QRCode(document.getElementById("qrcode"), qrcodelink);
	
};

tj.closeQR = function(){
	document.getElementById("qrcodename").innerHTML = "";
	document.getElementById("qrcode").innerHTML = "";
	document.getElementById("closeQR").style.display='none';
};

tj.addHuddle = function(id){
	document.getElementById('sf'+id).style.display='';
	document.getElementById('Add'+id).style.display='none';
};

tj.updateHuddle = function() {
		var dataId = $('#dataIdHuddle').val();
		var deptId = $('#deptIdHuddle').val();
		console.log('dataId ',dataId);
		var q1 = $('#s1Room').val();
		var q2 = $('#s1').val();
		
		var select1 = $('#s1').val();
		var select1Orig = $('#s1Orig').val();
		/*
		let result1 = String(select1).substring(0,1);

		if(result1=='0'){
		var q2 = '["0"]';
		}else{
		var q2 = JSON.stringify(select1);
		}
		*/
		var q3 = $('#s1Notes').val();
		var q4 = $('#s1chk1').is(':checked') ? 1 : 0;
		var q5 = $('#s1chk2').is(':checked') ? 1 : 0;
				//$('#dir1').val(response.data.q6);
				
		var q6 = $('#s2Room').val();
		var q7 = $('#s2').val();
		
		var select2 = $('#s2').val();
		var select2Orig = $('#s2Orig').val();
		/*
		let result2 = String(select2).substring(0,1);
		if(result2=='0'){
		var q7 = '["0"]';
		}else{
		var q7 = JSON.stringify(select2);
		}
		*/
		var q8 = $('#s2Notes').val();
		var q9 = $('#s2chk1').is(':checked') ? 1 : 0;
		var q10 = $('#s2chk2').is(':checked') ? 1 : 0;
				//$('#dir2').val(response.data.q12);
				
		var q11 = $('#s3Room').val();
		var q12 = $('#s3').val();
		
		var select3 = $('#s3').val();
		var select3Orig = $('#s3Orig').val();
		/*
		let result3 = String(select3).substring(0,1);
		if(result3=='0'){
		var q12 = '["0"]';
		}else{
		var q12 = JSON.stringify(select3);
		}
		*/
		var q13 = $('#s3Notes').val();
		var q14 = $('#s3chk1').is(':checked') ? 1 : 0;
		var q15 = $('#s3chk2').is(':checked') ? 1 : 0;
		var q16 = $('#s1esc').is(':checked') ? 1 : 0;
		var q17 = $('#s2esc').is(':checked') ? 1 : 0;
		var q18 = $('#s3esc').is(':checked') ? 1 : 0;
		/*		
		var q16 = $('#s4Room').val();
		var q17 = $('#s4').val();
		var select4 = $('#s4').val();
		var select4Orig = $('#s4Orig').val();
		var q18 = $('#s4Notes').val();
		var q19 = $('#s4chk1').is(':checked') ? 1 : 0;
		var q20 = $('#s4chk2').is(':checked') ? 1 : 0;
		*/
		var s1escOrig = $('#s1escOrig').val();
		var s2escOrig = $('#s2escOrig').val();
		var s3escOrig = $('#s3escOrig').val();		
		var cdate = moment().format('YYYY-MM-DD HH:mm:ss');
		let dnotes = $('#dnotes').val();
		let q21 = dnotes.replace(/<\/br>/g,"  ");
        var huddle=2;
		$.ajax({
            url:'inc/data.php?req=updateCrash',
            data:{
                huddle: huddle,
				dataId: dataId,
				deptId: deptId,
				q1: q1,
				q2: q2,
				q3: q3,
				q4: q4,
				q5: q5,
				q6: q6,
				q7: q7,
				q8: q8,
				q9: q9,
				q10: q10,
				q11: q11,
				q12: q12,
				q13: q13,
				q14: q14,
				q15: q15,
				q16: q16,
				q17: q17,
				q18: q18,
				q21: q21,
				cdate: cdate,
				select1: select1,
				select2: select2,
				select3: select3,
				select1Orig: select1Orig,
				select2Orig: select2Orig,
				select3Orig: select3Orig,
				s1escOrig: s1escOrig,
				s2escOrig: s2escOrig,
				s3escOrig: s3escOrig
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.crashTable.ajax.reload();
				$('#editHuddle').modal('hide');
				}
        })
		
  }
  
 tj.printhuddle = function(dataId){
	document.getElementById('sk2').style.display='none';
	document.getElementById('sk3').style.display='none';
	//document.getElementById('sk4').style.display='none';
	var cdate = moment().format('YYYY-MM-DD HH:mm:ss');
		$.ajax({
            url:'inc/data.php?req=getCrashDetails',
            data:{
                dataId: dataId,
				cdate: cdate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			$('#huddleTitle').html(response.data.title);
			$('#huddleUnit').html(response.data.dept);
			$('#huddleDate').html(response.data.reportDate);
			$('#dhuddle').html(response.msg);
			
			$('#huddleroom').val(response.data.q1);
			$('#huddleselect').val(response.data.safetyDesc1);
			$('#huddlenotes').val(response.data.q3);
			if(response.data.q4=='1'){
			$('#huddletask1').val('Yes');
			}else{
			$('#huddletask1').val('');	
			}
			if(response.data.q5=='1'){
			$('#huddletask2').val('Yes');
			}else{
			$('#huddletask2').val('');	
			}
			
			if(response.data.q6.length>0){
			document.getElementById('sk2').style.display='';
			$('#huddleroom2').val(response.data.q6);
			$('#huddleselect2').val(response.data.safetyDesc2);
			$('#huddlenotes2').val(response.data.q8);
			if(response.data.q9=='1'){
			$('#huddletask12').val('Yes');
			}else{
			$('#huddletask12').val('');	
			}
			if(response.data.q10=='1'){
			$('#huddletask22').val('Yes');
			}else{
			$('#huddletask22').val('');	
			}	
				
			}
			
			if(response.data.q11.length>0){
			document.getElementById('sk3').style.display='';
			$('#huddleroom3').val(response.data.q11);
			$('#huddleselect3').val(response.data.safetyDesc3);
			$('#huddlenotes3').val(response.data.q13);
			if(response.data.q14=='1'){
			$('#huddletask13').val('Yes');
			}else{
			$('#huddletask13').val('');	
			}
			if(response.data.q15=='1'){
			$('#huddletask23').val('Yes');
			}else{
			$('#huddletask23').val('');	
			}	
				
			}
			/*
			if(response.data.q16.length>0){
			document.getElementById('sk4').style.display='';
			$('#huddleroom4').val(response.data.q16);
			$('#huddleselect4').val(response.data.safetyDesc4);
			$('#huddlenotes4').val(response.data.q18);
			if(response.data.q19=='1'){
			$('#huddletask14').val('Yes');
			}else{
			$('#huddletask14').val('No');	
			}
			if(response.data.q20=='1'){
			$('#huddletask24').val('Yes');
			}else{
			$('#huddletask24').val('No');	
			}	
				
			}
			*/
			
			
			
			$('#huddleView').show();
			tj.crashTable.ajax.reload();
			}
		})
	

};


tj.editHuddle = function(dataId) {
		
		document.getElementById("sf1").style.display = '';
		document.getElementById("sf2").style.display = 'none';
		document.getElementById("sf3").style.display = 'none';
		var cdate = moment().format('YYYY-MM-DD HH:mm:ss');
		//document.getElementById("sf4").style.display = 'none';
		//document.getElementById("dnotes").disabled = true;	
        $.ajax({
            url:'inc/data.php?req=getCrashDetails',
            data:{
                dataId: dataId,
				cdate: cdate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				
				if(response.data.q6.length >0){
				document.getElementById("sf2").style.display = '';
				document.getElementById('Add2').style.display='none';
				}
				if(response.data.q11.length >0){
				document.getElementById("sf3").style.display = '';
				document.getElementById('Add2').style.display='none';
				document.getElementById('Add3').style.display='none';				
				}
				/*
				if(response.data.q16.length >0){
				document.getElementById("sf4").style.display = '';
				document.getElementById('Add2').style.display='none';
				document.getElementById('Add3').style.display='none';
				document.getElementById('Add4').style.display='none';				
				}
				*/
				//if(parseInt(response.data.role) >5){
				//document.getElementById("dnotes").disabled = false;	
				//}
				
				if(response.data.q4==1){
				document.getElementById("s1chk1").checked = true;
				}else{
				document.getElementById("s1chk1").checked = false;	
				}
				if(response.data.q5==1){
				document.getElementById("s1chk2").checked = true;
				}else{
				document.getElementById("s1chk2").checked = false;	
				}
				if(response.data.q9==1){
				document.getElementById("s2chk1").checked = true;
				}else{
				document.getElementById("s2chk1").checked = false;	
				}
				if(response.data.q10==1){
				document.getElementById("s2chk2").checked = true;
				}else{
				document.getElementById("s2chk2").checked = false;	
				}
				if(response.data.q14==1){
				document.getElementById("s3chk1").checked = true;
				}else{
				document.getElementById("s3chk1").checked = false;	
				}
				if(response.data.q15==1){
				document.getElementById("s3chk2").checked = true;
				}else{
				document.getElementById("s3chk2").checked = false;	
				}
				
				if(response.data.q16==1){
				document.getElementById("s1esc").checked = true;
				}else{
				document.getElementById("s1esc").checked = false;	
				}
				if(response.data.q17==1){
				document.getElementById("s2esc").checked = true;
				}else{
				document.getElementById("s2esc").checked = false;	
				}
				if(response.data.q18==1){
				document.getElementById("s3esc").checked = true;
				}else{
				document.getElementById("s3esc").checked = false;	
				}				
				$('#s1escOrig').val(response.data.q16);
				$('#s2escOrig').val(response.data.q17);
				$('#s3escOrig').val(response.data.q18);				
				
				$('#HuddleDept').html(response.data.dept);	
				
				
								
			
				$('#Huddledate').html(response.data.reportDate);	
				
				
				$('#dataIdHuddle').val(dataId);
				$('#deptIdHuddle').val(response.data.deptId);
				//console.log('huddleId ',dataId);
				
				$('#s1Room').val(response.data.q1);
				//var showq1 = JSON.parse(response.data.q2);
				//$('#s1').val(showq1);
				$('#s1').val(response.data.q2);
				$('#s1Orig').val(response.data.q2);
				$('#s1Notes').val(response.data.q3);
				$('#s1chk1').val(response.data.q4);
				$('#s1chk2').val(response.data.q5);
				//$('#dir1').val(response.data.q6);
				
				$('#s2Room').val(response.data.q6);
				//var showq2 = JSON.parse(response.data.q7);
				//$('#s2').val(showq2);
				$('#s2').val(response.data.q7);
				$('#s2Orig').val(response.data.q7);
				$('#s2Notes').val(response.data.q8);
				$('#s2chk1').val(response.data.q9);
				$('#s2chk2').val(response.data.q10);
				//$('#dir2').val(response.data.q12);
				
				$('#s3Room').val(response.data.q11);
				//var showq3 = JSON.parse(response.data.q12);
				//$('#s3').val(showq3);
				$('#s3').val(response.data.q12);
				$('#s3Orig').val(response.data.q12);
				$('#s3Notes').val(response.data.q13);
				$('#s3chk1').val(response.data.q14);
				$('#s3chk2').val(response.data.q15);
				//$('#dir3').val(response.data.q18);
				/*
				$('#s4Room').val(response.data.q16);
				$('#s4').val(response.data.q17);
				$('#s4Orig').val(response.data.q17);
				$('#s4Notes').val(response.data.q18);
				$('#s4chk1').val(response.data.q19);
				$('#s4chk2').val(response.data.q20);
				*/
				
				$('#dnotes').val(response.msg);
				$('#leaderComments').html(response.msg);
				
				
				
				$('#editHuddle').modal('show');
				tj.crashTable.ajax.reload();
				}
        })
        
		
  }
	
tj.editCrash = function(dataId) {
		document.getElementById("crashsubmittedby").disabled = false;
		var cdate = moment().format('YYYY-MM-DD HH:mm:ss');
        $.ajax({
            url:'inc/data.php?req=getCrashDetails',
            data:{
                dataId: dataId,
				cdate: cdate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				var c1 = document.getElementById('crashsubmittedby');					
				//if(response.data.practiceId==1 && response.data.userId==0) {
				$('#crashsubmittedby').val('');
				$('#userNamecrash').html(response.data.submittedBy);
				//}else{
				//document.getElementById("crashsubmittedby").disabled = true;
				//$('#crashsubmittedby').val(response.data.first_name + ' ' + response.data.last_name);
				//$('#crashsubmittedby').val('');
				//$('#userNamecrash').html(response.data.submittedBy);
				//}
				
				if(response.data.taskEsc==1){
				document.getElementById("taskEsc").checked = true;
				document.getElementById("taskNote").style.display='';
				}else if(response.data.taskEsc==0 && response.data.taskNote.length>0){
				document.getElementById("taskEsc").checked = false;
				document.getElementById("taskNote").style.display='';				
				}else{
				document.getElementById("taskEsc").checked = false;
				document.getElementById("taskNote").style.display='none';
				}
								
				$('#taskEscOrig').val(response.data.taskEsc);	
				$('#taskNote').val(response.data.taskNote);	
				$('#deptIdCRASH').val(response.data.deptId);	
				$('#taskNameCRASH').val(response.data.title);	
				
				var a1 = $('#a1');
				var a2 = $('#a2');
				var a3 = $('#a3');
				var a4 = $('#a4');
				var a5 = $('#a5');
				var a6 = $('#a6');
				var a7 = $('#a7');
				var a8 = $('#a8');
				var a9 = $('#a9');
				var a10 = $('#a10');
				var a11 = $('#a11');
				var a12 = $('#a12');
				var a13 = $('#a13');
				var a14 = $('#a14');
				var a15 = $('#a15');
				//var a16 = $('#a16');
				//var a17 = $('#a17');
				//var a18 = $('#a18');
				//var a19 = $('#a19');
				//var a20 = $('#a20');
				
				//var Q1 = response.qData.q1;
				//console.log('q1 ', Q1);
				
				if(response.qData.qType1==1){
					var html1 = '';
					html1 += '<hr></hr>';
					html1 += '<div class="form-group row">';
					html1 += '<label for="q1" class="col-9 form-label" style="color:black">';
					html1 += '<span id="Q1">' + response.qData.q1 + '</span>';
					html1 += '<div class="col-9">';
					html1 += '<select id="q1" type="select" class="form-control">';
					html1 += '<option value="">Select</option>';
					html1 += '<option value="1">Yes</option>';
					html1 += '<option value="2">No</option>';
					html1 += '<option value="3">N/A</option>';
					html1 += '</select>';
					html1 += '</div>';
					html1 += '</label>';
					html1 += '</div>';
				}else if(response.qData.qType1==2){
					var html1 = '';
					html1 += '<hr></hr>';
					html1 += '<div class="form-group row">';
					html1 += '<label for="q1" class="col-9 form-label" style="color:black">';
					html1 += '<span id="Q1">' + response.qData.q1 + '</span>';
					html1 += '<div class="col-9">';
					html1 += '<input type="text" class="form-control" id="q1" />';
					html1 += '</div>';
					html1 += '</label>';
					html1 += '</div>';	
				}else if(response.qData.qType1==3){
					var html1 = '';
					html1 += '<hr></hr>';
					html1 += '<div class="form-group row">';
					html1 += '<label for="q1" class="col-9 form-label" style="color:black">';
					html1 += '<span id="Q1">' + response.qData.q1 + '</span>';
					html1 += '<div class="col-9">';
					html1 += '<input type="checkbox" id="q1" name="q1" value=""></input>';
					html1 += '</div>';
					html1 += '</label>';
					html1 += '</div>';
				}else if(response.qData.qType1==4){
					var html1 = '';
					html1 += '<hr></hr>';
					html1 += '<div class="form-group row">';
					html1 += '<label for="q1" class="col-9 form-label" style="color:black">';
					html1 += '<span id="Q1">' + response.qData.q1 + '</span>';
					html1 += '<div class="col-9">';
					html1 += '<input type="number" class="form-control" id="q1" />';
					html1 += '</div>';
					html1 += '</label>';
					html1 += '</div>';	
				}else{
					var html1 = '';
				}					
					a1.empty().append(html1);
				
				
				if(response.qData.qType2==1){
					var html2 = '';
					html2 += '<hr></hr>';
					html2 += '<div class="form-group row">';
					html2 += '<label for="q2" class="col-9 form-label" style="color:black">';
					html2 += '<span id="Q2">' + response.qData.q2 + '</span>';
					html2 += '<div class="col-9">';
					html2 += '<select id="q2" type="select" class="form-control">';
					html2 += '<option value="">Select</option>';
					html2 += '<option value="1">Yes</option>';
					html2 += '<option value="2">No</option>';
					html2 += '<option value="3">N/A</option>';
					html2 += '</select>';
					html2 += '</div>';
					html2 += '</label>';
					html2 += '</div>';
				}else if(response.qData.qType2==3){
					var html2 = '';
					html2 += '<hr></hr>';
					html2 += '<div class="form-group row">';
					html2 += '<label for="q2" class="col-9 form-label" style="color:black">';
					html2 += '<span id="Q2">' + response.qData.q2 + '</span>';
					html2 += '<div class="col-9">';
					html2 += '<input type="checkbox" id="q2" name="q2"></input>';
					html2 += '</div>';
					html2 += '</label>';
					html2 += '</div>';
				}else if(response.qData.qType2==4){
					var html2 = '';
					html2 += '<hr></hr>';
					html2 += '<div class="form-group row">';
					html2 += '<label for="q2" class="col-9 form-label" style="color:black">';
					html2 += '<span id="Q2">' + response.qData.q2 + '</span>';
					html2 += '<div class="col-9">';
					html2 += '<input type="number" class="form-control" id="q2" />';
					html2 += '</div>';
					html2 += '</label>';
					html2 += '</div>';	
				}else if(response.qData.qType2==2){
					var html2 = '';
					html2 += '<hr></hr>';
					html2 += '<div class="form-group row">';
					html2 += '<label for="q2" class="col-9 form-label" style="color:black">';
					html2 += '<span id="Q2">' + response.qData.q2 + '</span>';
					html2 += '<div class="col-9">';
					html2 += '<input type="text" class="form-control" id="q2" />';
					html2 += '</div>';
					html2 += '</label>';
					html2 += '</div>';	
				}else{
					var html2 = '';
				}					
					a2.empty().append(html2);
				
					if(response.qData.qType3==1){
					var html3 = '';
					html3 += '<hr></hr>';
					html3 += '<div class="form-group row">';
					html3 += '<label for="q3" class="col-9 form-label" style="color:black">';
					html3 += '<span id="Q3">' + response.qData.q3 + '</span>';
					html3 += '<div class="col-9">';
					html3 += '<select id="q3" type="select" class="form-control">';
					html3 += '<option value="">Select</option>';
					html3 += '<option value="1">Yes</option>';
					html3 += '<option value="2">No</option>';
					html3 += '<option value="3">N/A</option>';
					html3 += '</select>';
					html3 += '</div>';
					html3 += '</label>';
					html3 += '</div>';
				}else if(response.qData.qType3==2){
					var html3 = '';
					html3 += '<hr></hr>';
					html3 += '<div class="form-group row">';
					html3 += '<label for="q3" class="col-9 form-label" style="color:black">';
					html3 += '<span id="Q3">' + response.qData.q3 + '</span>';
					html3 += '<div class="col-9">';
					html3 += '<input type="text" class="form-control" id="q3" />';
					html3 += '</div>';
					html3 += '</label>';
					html3 += '</div>';	
				}else if(response.qData.qType3==3){
					var html3 = '';
					html3 += '<hr></hr>';
					html3 += '<div class="form-group row">';
					html3 += '<label for="q3" class="col-9 form-label" style="color:black">';
					html3 += '<span id="Q3">' + response.qData.q3 + '</span>';
					html3 += '<div class="col-9">';
					html3 += '<input type="checkbox" id="q3" name="q3" value=""></input>';
					html3 += '</div>';
					html3 += '</label>';
					html3 += '</div>';
				}else if(response.qData.qType3==4){
					var html3 = '';
					html3 += '<hr></hr>';
					html3 += '<div class="form-group row">';
					html3 += '<label for="q3" class="col-9 form-label" style="color:black">';
					html3 += '<span id="Q3">' + response.qData.q3 + '</span>';
					html3 += '<div class="col-9">';
					html3 += '<input type="number" class="form-control" id="q3" />';
					html3 += '</div>';
					html3 += '</label>';
					html3 += '</div>';	
				}else{
					var html3 = '';
				}					
					a3.empty().append(html3);
				
				if(response.qData.qType4==1){
					var html4 = '';
					html4 += '<hr></hr>';
					html4 += '<div class="form-group row">';
					html4 += '<label for="q4" class="col-9 form-label" style="color:black">';
					html4 += '<span id="Q4">' + response.qData.q4 + '</span>';
					html4 += '</label>';
					html4 += '<div class="col-9">';
					html4 += '<select id="q4" type="select" class="form-control">';
					html4 += '<option value="">Select</option>';
					html4 += '<option value="1">Yes</option>';
					html4 += '<option value="2">No</option>';
					html4 += '<option value="3">N/A</option>';
					html4 += '</select>';
					html4 += '</div>';
					html4 += '</div>';
				}else if(response.qData.qType4==2){
					var html4 = '';
					html4 += '<hr></hr>';
					html4 += '<div class="form-group row">';
					html4 += '<label for="q4" class="col-9 form-label" style="color:black">';
					html4 += '<span id="Q4">' + response.qData.q4 + '</span>';
					html4 += '</label>';
					html4 += '<div class="col-9">';
					html4 += '<input type="text" class="form-control" id="q4" />';
					html4 += '</div>';
					html4 += '</div>';	
				}else if(response.qData.qType4==3){
					var html4 = '';
					html4 += '<hr></hr>';
					html4 += '<div class="form-group row">';
					html4 += '<label for="q4" class="col-9 form-label" style="color:black">';
					html4 += '<span id="Q4">' + response.qData.q4 + '</span>';
					html4 += '<div class="col-9">';
					html4 += '<input type="checkbox" id="q4" name="q4" value=""></input>';
					html4 += '</div>';
					html4 += '</label>';
					html4 += '</div>';
				}else if(response.qData.qType4==4){
					var html4 = '';
					html4 += '<hr></hr>';
					html4 += '<div class="form-group row">';
					html4 += '<label for="q4" class="col-9 form-label" style="color:black">';
					html4 += '<span id="Q4">' + response.qData.q4 + '</span>';
					html4 += '<div class="col-9">';
					html4 += '<input type="number" class="form-control" id="q4" />';
					html4 += '</div>';
					html4 += '</label>';
					html4 += '</div>';	
				}else{
					var html4 = '';
				}					
					a4.empty().append(html4);
					
						if(response.qData.qType5==1){
					var html5 = '';
					html5 += '<hr></hr>';
					html5 += '<div class="form-group row">';
					html5 += '<label for="q5" class="col-9 form-label" style="color:black">';
					html5 += '<span id="Q5">' + response.qData.q5 + '</span>';
					html5 += '</label>';
					html5 += '<div class="col-9">';
					html5 += '<select id="q5" type="select" class="form-control">';
					html5 += '<option value="">Select</option>';
					html5 += '<option value="1">Yes</option>';
					html5 += '<option value="2">No</option>';
					html5 += '<option value="3">N/A</option>';
					html5 += '</select>';
					html5 += '</div>';
					html5 += '</div>';
				}else if(response.qData.qType5==2){
					var html5 = '';
					html5 += '<hr></hr>';
					html5 += '<div class="form-group row">';
					html5 += '<label for="q5" class="col-9 form-label" style="color:black">';
					html5 += '<span id="Q5">' + response.qData.q5 + '</span>';
					html5 += '</label>';
					html5 += '<div class="col-9">';
					html5 += '<input type="text" class="form-control" id="q5" />';
					html5 += '</div>';
					html5 += '</div>';	
				}else if(response.qData.qType5==3){
					var html5 = '';
					html5 += '<hr></hr>';
					html5 += '<div class="form-group row">';
					html5 += '<label for="q5" class="col-9 form-label" style="color:black">';
					html5 += '<span id="Q5">' + response.qData.q5 + '</span>';
					html5 += '<div class="col-9">';
					html5 += '<input type="checkbox" id="q5" name="q5" value=""></input>';
					html5 += '</div>';
					html5 += '</label>';
					html5 += '</div>';
				}else if(response.qData.qType5==4){
					var html5 = '';
					html5 += '<hr></hr>';
					html5 += '<div class="form-group row">';
					html5 += '<label for="q5" class="col-9 form-label" style="color:black">';
					html5 += '<span id="Q5">' + response.qData.q5 + '</span>';
					html5 += '<div class="col-9">';
					html5 += '<input type="number" class="form-control" id="q5" />';
					html5 += '</div>';
					html5 += '</label>';
					html5 += '</div>';	
				}else{
					var html5 = '';
				}					
					a5.empty().append(html5);
					
				if(response.qData.qType6==1){
					var html6 = '';
					html6 += '<hr></hr>';
					html6 += '<div class="form-group row">';
					html6 += '<label for="q6" class="col-9 form-label" style="color:black">';
					html6 += '<span id="Q6">' + response.qData.q6 + '</span>';
					html6 += '</label>';
					html6 += '<div class="col-9">';
					html6 += '<select id="q6" type="select" class="form-control">';
					html6 += '<option value="">Select</option>';
					html6 += '<option value="1">Yes</option>';
					html6 += '<option value="2">No</option>';
					html6 += '<option value="3">N/A</option>';
					html6 += '</select>';
					html6 += '</div>';
					html6 += '</div>';
				}else if(response.qData.qType6==2){
					var html6 = '';
					html6 += '<hr></hr>';
					html6 += '<div class="form-group row">';
					html6 += '<label for="q6" class="col-9 form-label" style="color:black">';
					html6 += '<span id="Q6">' + response.qData.q6 + '</span>';
					html6 += '</label>';
					html6 += '<div class="col-9">';
					html6 += '<input type="text" class="form-control" id="q6" />';
					html6 += '</div>';
					html6 += '</div>';	
				}else if(response.qData.qType6==3){
					var html6 = '';
					html6 += '<hr></hr>';
					html6 += '<div class="form-group row">';
					html6 += '<label for="q6" class="col-9 form-label" style="color:black">';
					html6 += '<span id="Q6">' + response.qData.q6 + '</span>';
					html6 += '<div class="col-9">';
					html6 += '<input type="checkbox" id="q6" name="q6" value=""></input>';
					html6 += '</div>';
					html6 += '</label>';
					html6 += '</div>';
				}else if(response.qData.qType6==4){
					var html6 = '';
					html6 += '<hr></hr>';
					html6 += '<div class="form-group row">';
					html6 += '<label for="q6" class="col-9 form-label" style="color:black">';
					html6 += '<span id="Q6">' + response.qData.q6 + '</span>';
					html6 += '<div class="col-9">';
					html6 += '<input type="number" class="form-control" id="q6" />';
					html6 += '</div>';
					html6 += '</label>';
					html6 += '</div>';	
				}else{
					var html6 = '';
				}					
					a6.empty().append(html6);
					
						if(response.qData.qType7==1){
					var html7 = '';
					html7 += '<hr></hr>';
					html7 += '<div class="form-group row">';
					html7 += '<label for="q7" class="col-9 form-label" style="color:black">';
					html7 += '<span id="Q7">' + response.qData.q7 + '</span>';
					html7 += '</label>';
					html7 += '<div class="col-9">';
					html7 += '<select id="q7" type="select" class="form-control">';
					html7 += '<option value="">Select</option>';
					html7 += '<option value="1">Yes</option>';
					html7 += '<option value="2">No</option>';
					html7 += '<option value="3">N/A</option>';
					html7 += '</select>';
					html7 += '</div>';
					html7 += '</div>';
				}else if(response.qData.qType7==2){
					var html7 = '';
					html7 += '<hr></hr>';
					html7 += '<div class="form-group row">';
					html7 += '<label for="q7" class="col-9 form-label" style="color:black">';
					html7 += '<span id="Q7">' + response.qData.q7 + '</span>';
					html7 += '</label>';
					html7 += '<div class="col-9">';
					html7 += '<input type="text" class="form-control" id="q7" />';
					html7 += '</div>';
					html7 += '</div>';	
				}else if(response.qData.qType7==3){
					var html7 = '';
					html7 += '<hr></hr>';
					html7 += '<div class="form-group row">';
					html7 += '<label for="q7" class="col-9 form-label" style="color:black">';
					html7 += '<span id="Q7">' + response.qData.q7 + '</span>';
					html7 += '<div class="col-9">';
					html7 += '<input type="checkbox" id="q7" name="q7" value=""></input>';
					html7 += '</div>';
					html7 += '</label>';
					html7 += '</div>';
				}else if(response.qData.qType7==4){
					var html7 = '';
					html7 += '<hr></hr>';
					html7 += '<div class="form-group row">';
					html7 += '<label for="q7" class="col-9 form-label" style="color:black">';
					html7 += '<span id="Q7">' + response.qData.q7 + '</span>';
					html7 += '<div class="col-9">';
					html7 += '<input type="number" class="form-control" id="q7" />';
					html7 += '</div>';
					html7 += '</label>';
					html7 += '</div>';	
				}else{
					var html7 = '';
				}					
					a7.empty().append(html7);
					
						if(response.qData.qType8==1){
					var html8 = '';
					html8 += '<hr></hr>';
					html8 += '<div class="form-group row">';
					html8 += '<label for="q8" class="col-9 form-label" style="color:black">';
					html8 += '<span id="Q8">' + response.qData.q8 + '</span>';
					html8 += '</label>';
					html8 += '<div class="col-9">';
					html8 += '<select id="q8" type="select" class="form-control">';
					html8 += '<option value="">Select</option>';
					html8 += '<option value="1">Yes</option>';
					html8 += '<option value="2">No</option>';
					html8 += '<option value="3">N/A</option>';
					html8 += '</select>';
					html8 += '</div>';
					html8 += '</div>';
				}else if(response.qData.qType8==2){
					var html8 = '';
					html8 += '<hr></hr>';
					html8 += '<div class="form-group row">';
					html8 += '<label for="q8" class="col-9 form-label" style="color:black">';
					html8 += '<span id="Q8">' + response.qData.q8 + '</span>';
					html8 += '</label>';
					html8 += '<div class="col-9">';
					html8 += '<input type="text" class="form-control" id="q8" />';
					html8 += '</div>';
					html8 += '</div>';	
				}else if(response.qData.qType8==3){
					var html8 = '';
					html8 += '<hr></hr>';
					html8 += '<div class="form-group row">';
					html8 += '<label for="q8" class="col-9 form-label" style="color:black">';
					html8 += '<span id="Q8">' + response.qData.q8 + '</span>';
					html8 += '<div class="col-9">';
					html8 += '<input type="checkbox" id="q8" name="q8" value=""></input>';
					html8 += '</div>';
					html8 += '</label>';
					html8 += '</div>';
				}else if(response.qData.qType8==4){
					var html8 = '';
					html8 += '<hr></hr>';
					html8 += '<div class="form-group row">';
					html8 += '<label for="q8" class="col-9 form-label" style="color:black">';
					html8 += '<span id="Q8">' + response.qData.q8 + '</span>';
					html8 += '<div class="col-9">';
					html8 += '<input type="number" class="form-control" id="q8" />';
					html8 += '</div>';
					html8 += '</label>';
					html8 += '</div>';	
				}else{
					var html8 = '';
				}					
					a8.empty().append(html8);
					
						if(response.qData.qType9==1){
					var html9 = '';
					html9 += '<hr></hr>';
					html9 += '<div class="form-group row">';
					html9 += '<label for="q9" class="col-9 form-label" style="color:black">';
					html9 += '<span id="Q9">' + response.qData.q9 + '</span>';
					html9 += '</label>';
					html9 += '<div class="col-9">';
					html9 += '<select id="q9" type="select" class="form-control">';
					html9 += '<option value="">Select</option>';
					html9 += '<option value="1">Yes</option>';
					html9 += '<option value="2">No</option>';
					html9 += '<option value="3">N/A</option>';
					html9 += '</select>';
					html9 += '</div>';
					html9 += '</div>';
				}else if(response.qData.qType9==2){
					var html9 = '';
					html9 += '<hr></hr>';
					html9 += '<div class="form-group row">';
					html9 += '<label for="q9" class="col-9 form-label" style="color:black">';
					html9 += '<span id="Q9">' + response.qData.q9 + '</span>';
					html9 += '</label>';
					html9 += '<div class="col-9">';
					html9 += '<input type="text" class="form-control" id="q9" />';
					html9 += '</div>';
					html9 += '</div>';	
				}else if(response.qData.qType9==3){
					var html9 = '';
					html9 += '<hr></hr>';
					html9 += '<div class="form-group row">';
					html9 += '<label for="q9" class="col-9 form-label" style="color:black">';
					html9 += '<span id="Q9">' + response.qData.q9+ '</span>';
					html9 += '<div class="col-9">';
					html9 += '<input type="checkbox" id="q9" name="q9" value=""></input>';
					html9 += '</div>';
					html9 += '</label>';
					html9 += '</div>';
				}else if(response.qData.qType9==4){
					var html9 = '';
					html9 += '<hr></hr>';
					html9 += '<div class="form-group row">';
					html9 += '<label for="q9" class="col-9 form-label" style="color:black">';
					html9 += '<span id="Q9">' + response.qData.q9 + '</span>';
					html9 += '<div class="col-9">';
					html9 += '<input type="number" class="form-control" id="q9" />';
					html9 += '</div>';
					html9 += '</label>';
					html9 += '</div>';	
				}else{
					var html9 = '';
				}					
					a9.empty().append(html9);
					
						if(response.qData.qType10==1){
					var html10 = '';
					html10 += '<hr></hr>';
					html10 += '<div class="form-group row">';
					html10 += '<label for="q10" class="col-9 form-label" style="color:black">';
					html10 += '<span id="Q10">' + response.qData.q10 + '</span>';
					html10 += '</label>';
					html10 += '<div class="col-9">';
					html10 += '<select id="q10" type="select" class="form-control">';
					html10 += '<option value="">Select</option>';
					html10 += '<option value="1">Yes</option>';
					html10 += '<option value="2">No</option>';
					html10 += '<option value="3">N/A</option>';
					html10 += '</select>';
					html10 += '</div>';
					html10 += '</div>';
				}else if(response.qData.qType10==2){
					var html10 = '';
					html10 += '<hr></hr>';
					html10 += '<div class="form-group row">';
					html10 += '<label for="q10" class="col-9 form-label" style="color:black">';
					html10 += '<span id="Q10">' + response.qData.q10 + '</span>';
					html10 += '</label>';
					html10 += '<div class="col-9">';
					html10 += '<input type="text" class="form-control" id="q10" />';
					html10 += '</div>';
					html10 += '</div>';	
				}else if(response.qData.qType10==3){
					var html10 = '';
					html10 += '<hr></hr>';
					html10 += '<div class="form-group row">';
					html10 += '<label for="q10" class="col-9 form-label" style="color:black">';
					html10 += '<span id="Q10">' + response.qData.q10 + '</span>';
					html10 += '<div class="col-9">';
					html10 += '<input type="checkbox" id="q10" name="q10" value=""></input>';
					html10 += '</div>';
					html10 += '</label>';
					html10 += '</div>';
				}else if(response.qData.qType10==4){
					var html10 = '';
					html10 += '<hr></hr>';
					html10 += '<div class="form-group row">';
					html10 += '<label for="q10" class="col-9 form-label" style="color:black">';
					html10 += '<span id="Q10">' + response.qData.q10 + '</span>';
					html10 += '<div class="col-9">';
					html10 += '<input type="number" class="form-control" id="q10" />';
					html10 += '</div>';
					html10 += '</label>';
					html10 += '</div>';	
				}else{
					var html10 = '';
				}					
					a10.empty().append(html10);
					
						if(response.qData.qType11==1){
					var html11 = '';
					html11 += '<hr></hr>';
					html11 += '<div class="form-group row">';
					html11 += '<label for="q11" class="col-9 form-label" style="color:black">';
					html11 += '<span id="Q11">' + response.qData.q11 + '</span>';
					html11 += '</label>';
					html11 += '<div class="col-9">';
					html11 += '<select id="q11" type="select" class="form-control">';
					html11 += '<option value="">Select</option>';
					html11 += '<option value="1">Yes</option>';
					html11 += '<option value="2">No</option>';
					html11 += '<option value="3">N/A</option>';
					html11 += '</select>';
					html11 += '</div>';
					html11 += '</div>';
				}else if(response.qData.qType11==2){
					var html11 = '';
					html11 += '<hr></hr>';
					html11 += '<div class="form-group row">';
					html11 += '<label for="q11" class="col-9 form-label" style="color:black">';
					html11 += '<span id="Q11">' + response.qData.q11 + '</span>';
					html11 += '</label>';
					html11 += '<div class="col-9">';
					html11 += '<input type="text" class="form-control" id="q11" />';
					html11 += '</div>';
					html11 += '</div>';	
				}else if(response.qData.qType11==3){
					var html11 = '';
					html11 += '<hr></hr>';
					html11 += '<div class="form-group row">';
					html11 += '<label for="q11" class="col-9 form-label" style="color:black">';
					html11 += '<span id="Q11">' + response.qData.q11 + '</span>';
					html11 += '<div class="col-9">';
					html11 += '<input type="checkbox" id="q11" name="q11" value=""></input>';
					html11 += '</div>';
					html11 += '</label>';
					html11 += '</div>';
				}else if(response.qData.qType11==4){
					var html11 = '';
					html11 += '<hr></hr>';
					html11 += '<div class="form-group row">';
					html11 += '<label for="q11" class="col-9 form-label" style="color:black">';
					html11 += '<span id="Q11">' + response.qData.q11 + '</span>';
					html11 += '<div class="col-9">';
					html11 += '<input type="number" class="form-control" id="q11" />';
					html11 += '</div>';
					html11 += '</label>';
					html11 += '</div>';	
				}else{
					var html11 = '';
				}					
					a11.empty().append(html11);
					
						if(response.qData.qType12==1){
					var html12 = '';
					html12 += '<hr></hr>';
					html12 += '<div class="form-group row">';
					html12 += '<label for="q12" class="col-9 form-label" style="color:black">';
					html12 += '<span id="Q12">' + response.qData.q12 + '</span>';
					html12 += '</label>';
					html12 += '<div class="col-9">';
					html12 += '<select id="q12" type="select" class="form-control">';
					html12 += '<option value="">Select</option>';
					html12 += '<option value="1">Yes</option>';
					html12 += '<option value="2">No</option>';
					html12 += '<option value="3">N/A</option>';
					html12 += '</select>';
					html12 += '</div>';
					html12 += '</div>';
				}else if(response.qData.qType12==2){
					var html12 = '';
					html12 += '<hr></hr>';
					html12 += '<div class="form-group row">';
					html12 += '<label for="q12" class="col-9 form-label" style="color:black">';
					html12 += '<span id="Q12">' + response.qData.q12 + '</span>';
					html12 += '</label>';
					html12 += '<div class="col-9">';
					html12 += '<input type="text" class="form-control" id="q12" />';
					html12 += '</div>';
					html12 += '</div>';	
				}else if(response.qData.qType12==3){
					var html12 = '';
					html12 += '<hr></hr>';
					html12 += '<div class="form-group row">';
					html12 += '<label for="q12" class="col-9 form-label" style="color:black">';
					html12 += '<span id="Q12">' + response.qData.q12 + '</span>';
					html12 += '<div class="col-9">';
					html12 += '<input type="checkbox" id="q12" name="q12" value=""></input>';
					html12 += '</div>';
					html12 += '</label>';
					html12 += '</div>';
				}else if(response.qData.qType12==4){
					var html12 = '';
					html12 += '<hr></hr>';
					html12 += '<div class="form-group row">';
					html12 += '<label for="q12" class="col-9 form-label" style="color:black">';
					html12 += '<span id="Q12">' + response.qData.q12 + '</span>';
					html12 += '<div class="col-9">';
					html12 += '<input type="number" class="form-control" id="q12" />';
					html12 += '</div>';
					html12 += '</label>';
					html12 += '</div>';	
				}else{
					var html12 = '';
				}					
					a12.empty().append(html12);
					
						if(response.qData.qType13==1){
					var html13 = '';
					html13 += '<hr></hr>';
					html13 += '<div class="form-group row">';
					html13 += '<label for="q13" class="col-9 form-label" style="color:black">';
					html13 += '<span id="Q13">' + response.qData.q13 + '</span>';
					html13 += '</label>';
					html13 += '<div class="col-9">';
					html13 += '<select id="q13" type="select" class="form-control">';
					html13 += '<option value="">Select</option>';
					html13 += '<option value="1">Yes</option>';
					html13 += '<option value="2">No</option>';
					html13 += '<option value="3">N/A</option>';
					html13 += '</select>';
					html13 += '</div>';
					html13 += '</div>';
				}else if(response.qData.qType13==2){
					var html13 = '';
					html13 += '<hr></hr>';
					html13 += '<div class="form-group row">';
					html13 += '<label for="q13" class="col-9 form-label" style="color:black">';
					html13 += '<span id="Q13">' + response.qData.q13 + '</span>';
					html13 += '</label>';
					html13 += '<div class="col-9">';
					html13 += '<input type="text" class="form-control" id="q13" />';
					html13 += '</div>';
					html13 += '</div>';	
				}else if(response.qData.qType13==3){
					var html13 = '';
					html13 += '<hr></hr>';
					html13 += '<div class="form-group row">';
					html13 += '<label for="q3" class="col-9 form-label" style="color:black">';
					html13 += '<span id="Q3">' + response.qData.q3 + '</span>';
					html13 += '<div class="col-9">';
					html13 += '<input type="checkbox" id="q13" name="q13" value=""></input>';
					html13 += '</div>';
					html13 += '</label>';
					html13 += '</div>';
				}else if(response.qData.qType13==4){
					var html13 = '';
					html13 += '<hr></hr>';
					html13 += '<div class="form-group row">';
					html13 += '<label for="q13" class="col-9 form-label" style="color:black">';
					html13 += '<span id="Q13">' + response.qData.q13 + '</span>';
					html13 += '<div class="col-9">';
					html13 += '<input type="number" class="form-control" id="q13" />';
					html13 += '</div>';
					html13 += '</label>';
					html13 += '</div>';	
				}else{
					var html13 = '';
				}					
					a13.empty().append(html13);
					
						if(response.qData.qType14==1){
					var html14 = '';
					html14 += '<hr></hr>';
					html14 += '<div class="form-group row">';
					html14 += '<label for="q14" class="col-9 form-label" style="color:black">';
					html14 += '<span id="Q14">' + response.qData.q14 + '</span>';
					html14 += '</label>';
					html14 += '<div class="col-9">';
					html14 += '<select id="q14" type="select" class="form-control">';
					html14 += '<option value="">Select</option>';
					html14 += '<option value="1">Yes</option>';
					html14 += '<option value="2">No</option>';
					html14 += '<option value="3">N/A</option>';
					html14 += '</select>';
					html14 += '</div>';
					html14 += '</div>';
				}else if(response.qData.qType14==2){
					var html14 = '';
					html14 += '<hr></hr>';
					html14 += '<div class="form-group row">';
					html14 += '<label for="q14" class="col-9 form-label" style="color:black">';
					html14 += '<span id="Q14">' + response.qData.q14 + '</span>';
					html14 += '</label>';
					html14 += '<div class="col-9">';
					html14 += '<input type="text" class="form-control" id="q14" />';
					html14 += '</div>';
					html14 += '</div>';	
				}else if(response.qData.qType14==3){
					var html14 = '';
					html14 += '<hr></hr>';
					html14 += '<div class="form-group row">';
					html14 += '<label for="q14" class="col-9 form-label" style="color:black">';
					html14 += '<span id="Q14">' + response.qData.q14 + '</span>';
					html14 += '<div class="col-9">';
					html14 += '<input type="checkbox" id="q14" name="q14" value=""></input>';
					html14 += '</div>';
					html14 += '</label>';
					html14 += '</div>';
				}else if(response.qData.qType14==4){
					var html14 = '';
					html14 += '<hr></hr>';
					html14 += '<div class="form-group row">';
					html14 += '<label for="q14" class="col-9 form-label" style="color:black">';
					html14 += '<span id="Q14">' + response.qData.q14 + '</span>';
					html14 += '<div class="col-9">';
					html14 += '<input type="number" class="form-control" id="q14" />';
					html14 += '</div>';
					html14 += '</label>';
					html14 += '</div>';	
				}else{
					var html14 = '';
				}					
					a14.empty().append(html14);
					
						if(response.qData.qType15==1){
					var html15 = '';
					html15 += '<hr></hr>';
					html15 += '<div class="form-group row">';
					html15 += '<label for="q15" class="col-9 form-label" style="color:black">';
					html15 += '<span id="Q15">' + response.qData.q15 + '</span>';
					html15 += '</label>';
					html15 += '<div class="col-9">';
					html15 += '<select id="q15" type="select" class="form-control">';
					html15 += '<option value="">Select</option>';
					html15 += '<option value="1">Yes</option>';
					html15 += '<option value="2">No</option>';
					html15 += '<option value="3">N/A</option>';
					html15 += '</select>';
					html15 += '</div>';
					html15 += '</div>';
				}else if(response.qData.qType15==2){
					var html15 = '';
					html15 += '<hr></hr>';
					html15 += '<div class="form-group row">';
					html15 += '<label for="q15" class="col-9 form-label" style="color:black">';
					html15 += '<span id="Q15">' + response.qData.q15 + '</span>';
					html15 += '</label>';
					html15 += '<div class="col-9">';
					html15 += '<input type="text" class="form-control" id="q15" />';
					html15 += '</div>';
					html15 += '</div>';	
				}else if(response.qData.qType15==3){
					var html15 = '';
					html15 += '<hr></hr>';
					html15 += '<div class="form-group row">';
					html15 += '<label for="q15" class="col-9 form-label" style="color:black">';
					html15 += '<span id="Q15">' + response.qData.q15 + '</span>';
					html15 += '<div class="col-9">';
					html15 += '<input type="checkbox" id="q15" name="q15" value=""></input>';
					html15 += '</div>';
					html15 += '</label>';
					html15 += '</div>';
				}else if(response.qData.qType15==4){
					var html15 = '';
					html15 += '<hr></hr>';
					html15 += '<div class="form-group row">';
					html15 += '<label for="q15" class="col-9 form-label" style="color:black">';
					html15 += '<span id="Q15">' + response.qData.q15 + '</span>';
					html15 += '<div class="col-9">';
					html15 += '<input type="number" class="form-control" id="q15" />';
					html15 += '</div>';
					html15 += '</label>';
					html15 += '</div>';	
				}else{
					var html15 = '';
				}					
					a15.empty().append(html15);
					
						
				if(response.data.submittedDateTime=='0000-00-00 00:00:00'){
				$('#crashdate').html('');
				}else{
				$('#crashdate').html(response.data.submittedDateTime);	
				}
				$('#dueTime').html(response.data.shiftTime);	
				$('#crashpracticeId').val(response.data.practiceId);
				$('#cartDesc').html(response.data.title);
				$('#cartInstructions').html(response.data.instructions);
				$('#cartDept').html(response.data.dept + ' (' + response.data.reportDate + ')');
				$('#dataIdCRASH').val(response.data.id);
				
				if(response.data.qType1==3){
				document.getElementById("q1").checked = false;
				}
				if(response.data.qType2==3){
				document.getElementById("q2").checked = false;
				}
				if(response.data.qType3==3){
				document.getElementById("q3").checked = false;
				}
				if(response.data.qType4==3){
				document.getElementById("q4").checked = false;
				}
				if(response.data.qType5==3){
				document.getElementById("q5").checked = false;
				}
				if(response.data.qType6==3){
				document.getElementById("q6").checked = false;
				}
				if(response.data.qType7==3){
				document.getElementById("q7").checked = false;
				}
				if(response.data.qType8==3){
				document.getElementById("q8").checked = false;
				}
				if(response.data.qType9==3){
				document.getElementById("q9").checked = false;
				}
				if(response.data.qType10==3){
				document.getElementById("q10").checked = false;
				}
				if(response.data.qType11==3){
				document.getElementById("q11").checked = false;
				}
				if(response.data.qType12==3){
				document.getElementById("q12").checked = false;
				}
				if(response.data.qType13==3){
				document.getElementById("q13").checked = false;
				}
				if(response.data.qType14==3){
				document.getElementById("q14").checked = false;
				}
				if(response.data.qType15==3){
				document.getElementById("q15").checked = false;
				}
				if(response.data.qType1==4 && (isNaN(response.data.q1) || response.data.q1=='')){
					var $qa1 = 0;
				}else if(response.data.qType1==3 && !isNaN(response.data.q1) && response.data.q1==1){
					document.getElementById("q1").checked = true;
				}else{
					var $qa1 = response.data.q1;
				}
				if(response.data.qType2==4 && (isNaN(response.data.q2) || response.data.q2=='')){
					var $qa2 = 0;
				}else if(response.data.qType2==3 && !isNaN(response.data.q2) && response.data.q2==1){
					document.getElementById("q2").checked = true;
				}else{
					var $qa2 = response.data.q2;
				}
				if(response.data.qType3==4 && (isNaN(response.data.q3) || response.data.q3=='')){
					var $qa3 = 0;
				}else if(response.data.qType3==3 && !isNaN(response.data.q3) && response.data.q3==1){
					document.getElementById("q3").checked = true;
				}else{
					var $qa3 = response.data.q3;
				}
				if(response.data.qType4==4 && (isNaN(response.data.q4) || response.data.q4=='')){
					var $qa4 = 0;
				}else if(response.data.qType4==3 && !isNaN(response.data.q4) && response.data.q4==1){
					document.getElementById("q4").checked = true;
				}else{
					var $qa4 = response.data.q4;
				}
				if(response.data.qType5==4 && (isNaN(response.data.q5) || response.data.q5=='')){
					var $qa5 = 0;
				}else if(response.data.qType5==3 && !isNaN(response.data.q5) && response.data.q5==1){
					document.getElementById("q5").checked = true;
				}else{
					var $qa5 = response.data.q5;
				}
				if(response.data.qType6==4 && (isNaN(response.data.q6) || response.data.q6=='')){
					var $qa6 = 0;
				}else if(response.data.qType6==3 && !isNaN(response.data.q6) && response.data.q6==1){
					document.getElementById("q6").checked = true;
				}else{
					var $qa6 = response.data.q6;
				}
				if(response.data.qType7==4 && (isNaN(response.data.q7) || response.data.q7=='')){
					var $qa7 = 0;
				}else if(response.data.qType7==3 && !isNaN(response.data.q7) && response.data.q7==1){
					document.getElementById("q7").checked = true;
				}else{
					var $qa7 = response.data.q7;
				}
				if(response.data.qType8==4 && (isNaN(response.data.q8) || response.data.q8=='')){
					var $qa8 = 0;
				}else if(response.data.qType8==3 && !isNaN(response.data.q8) && response.data.q8==1){
					document.getElementById("q8").checked = true;
				}else{
					var $qa8 = response.data.q8;
				}
				if(response.data.qType9==4 && (isNaN(response.data.q9) || response.data.q9=='')){
					var $qa9 = 0;
				}else if(response.data.qType9==3 && !isNaN(response.data.q9) && response.data.q9==1){
					document.getElementById("q9").checked = true;
				}else{
					var $qa9 = response.data.q9;
				}
				if(response.data.qType10==4 && (isNaN(response.data.q10) || response.data.q10=='')){
					var $qa10 = 0;
				}else if(response.data.qType10==3 && !isNaN(response.data.q10) && response.data.q10==1){
					document.getElementById("q10").checked = true;
				}else{
					var $qa10 = response.data.q10;
				}
				if(response.data.qType11==4 && (isNaN(response.data.q11) || response.data.q11=='')){
					var $qa11 = 0;
				}else if(response.data.qType11==3 && !isNaN(response.data.q11) && response.data.q11==1){
					document.getElementById("q11").checked = true;
				}else{
					var $qa11 = response.data.q11;
				}
				if(response.data.qType12==4 && (isNaN(response.data.q12) || response.data.q12=='')){
					var $qa12 = 0;
				}else if(response.data.qType12==3 && !isNaN(response.data.q12) && response.data.q12==1){
					document.getElementById("q12").checked = true;
				}else{
					var $qa12 = response.data.q12;
				}
				if(response.data.qType13==4 && (isNaN(response.data.q13) || response.data.q13=='')){
					var $qa13 = 0;
				}else if(response.data.qType13==3 && !isNaN(response.data.q13) && response.data.q13==1){
					document.getElementById("q13").checked = true;
				}else{
					var $qa13 = response.data.q13;
				}
				if(response.data.qType14==4 && (isNaN(response.data.q14) || response.data.q14=='')){
					var $qa14 = 0;
				}else if(response.data.qType14==3 && !isNaN(response.data.q14) && response.data.q14==1){
					document.getElementById("q14").checked = true;
				}else{
					var $qa14 = response.data.q14;
				}
				if(response.data.qType15==4 && (isNaN(response.data.q15) || response.data.q15=='')){
					var $qa15 = 0;
				}else if(response.data.qType15==3 && !isNaN(response.data.q15) && response.data.q15==1){
					document.getElementById("q15").checked = true;
				}else{
					var $qa15 = response.data.q15;
				}
				
				$('#q1').val($qa1);
				$('#q2').val($qa2);
				$('#q3').val($qa3);
				$('#q4').val($qa4);
				$('#q5').val($qa5);
				$('#q6').val($qa6);
				$('#q7').val($qa7);
				$('#q8').val($qa8);
				$('#q9').val($qa9);
				$('#q10').val($qa10);
				$('#q11').val($qa11);
				$('#q12').val($qa12);
				$('#q13').val($qa13);
				$('#q14').val($qa14);
				$('#q15').val($qa15);
				
				
				
				$('#at1').val(response.data.qType1);
				$('#at2').val(response.data.qType2);
				$('#at3').val(response.data.qType3);
				$('#at4').val(response.data.qType4);
				$('#at5').val(response.data.qType5);
				$('#at6').val(response.data.qType6);
				$('#at7').val(response.data.qType7);
				$('#at8').val(response.data.qType8);
				$('#at9').val(response.data.qType9);
				$('#at10').val(response.data.qType10);
				$('#at11').val(response.data.qType11);
				$('#at12').val(response.data.qType12);
				$('#at13').val(response.data.qType13);
				$('#at14').val(response.data.qType14);
				$('#at15').val(response.data.qType15);
				
				$('#ar1').val(response.data.r1);
				$('#ar2').val(response.data.r2);
				$('#ar3').val(response.data.r3);
				$('#ar4').val(response.data.r4);
				$('#ar5').val(response.data.r5);
				$('#ar6').val(response.data.r6);
				$('#ar7').val(response.data.r7);
				$('#ar8').val(response.data.r8);
				$('#ar9').val(response.data.r9);
				$('#ar10').val(response.data.r10);
				$('#ar11').val(response.data.r11);
				$('#ar12').val(response.data.r12);
				$('#ar13').val(response.data.r13);
				$('#ar14').val(response.data.r14);
				$('#ar15').val(response.data.r15);
				
				//console.log('id ',response.data.id);
				
				//$('#signature').val(response.data.signature);
				$('#editCrash').modal('show');
				}
        })
        console.log('record updated sucessfully',dataId);
		
  } 
  
 tj.showtaskNote = function() {
	var taskCheck = $('#taskEsc').is(':checked') ? 1 : 0;
	if(taskCheck==1){
	document.getElementById("taskNote").style.display='';
	}else{
	document.getElementById("taskNote").style.display='none';
	}
}
  
 tj.saveCrash = function() {
		var dataId = $('#dataIdCRASH').val();
		var deptId = $('#deptIdCRASH').val();
		var taskName = $('#taskNameCRASH').val();
		var q1 = $('#q1').val();
		var q2 = $('#q2').val();
		var q3 = $('#q3').val();
		var q4 = $('#q4').val();
		var q5 = $('#q5').val();
		var q6 = $('#q6').val();
		var q7 = $('#q7').val();
		var q8 = $('#q8').val();
		var q9 = $('#q9').val();
		var q10 = $('#q10').val();
		var q11 = $('#q11').val();
		var q12 = $('#q12').val();
		var q13 = $('#q13').val();
		var q14 = $('#q14').val();
		var q15 = $('#q15').val();
		var q16 = $('#q16').val();
		var q17 = $('#q17').val();
		var q18 = $('#q18').val();
		var q19 = $('#q19').val();
		var q20 = $('#q20').val();
		
		var at1 = $('#at1').val();
		var at2 = $('#at2').val();
		var at3 = $('#at3').val();
		var at4 = $('#at4').val();
		var at5 = $('#at5').val();
		var at6 = $('#at6').val();
		var at7 = $('#at7').val();
		var at8 = $('#at8').val();
		var at9 = $('#at9').val();
		var at10 = $('#at10').val();
		var at11 = $('#at11').val();
		var at12 = $('#at12').val();
		var at13 = $('#at13').val();
		var at14 = $('#at14').val();
		var at15 = $('#at15').val();
		
		var ar1 = $('#ar1').val();
		var ar2 = $('#ar2').val();
		var ar3 = $('#ar3').val();
		var ar4 = $('#ar4').val();
		var ar5 = $('#ar5').val();
		var ar6 = $('#ar6').val();
		var ar7 = $('#ar7').val();
		var ar8 = $('#ar8').val();
		var ar9 = $('#ar9').val();
		var ar10 = $('#ar10').val();
		var ar11 = $('#ar11').val();
		var ar12 = $('#ar12').val();
		var ar13 = $('#ar13').val();
		var ar14 = $('#ar14').val();
		var ar15 = $('#ar15').val();
		var taskEsc = $('#taskEsc').is(':checked') ? 1 : 0;
		var taskEscOrig = $('#taskEscOrig').val();
		var taskNote = $('#taskNote').val();
		var submittedBy = $('#crashsubmittedby').val();
		var submitreqd = $('#crashpracticeId').val();
		var cdate = moment().format('YYYY-MM-DD HH:mm:ss');
		var ck1 = $('#q1').is(':checked') ? 1 : 0;
		var ck2 = $('#q2').is(':checked') ? 1 : 0;
		var ck3 = $('#q3').is(':checked') ? 1 : 0;
		var ck4 = $('#q4').is(':checked') ? 1 : 0;
		var ck5 = $('#q5').is(':checked') ? 1 : 0;
		var ck6 = $('#q6').is(':checked') ? 1 : 0;
		var ck7 = $('#q7').is(':checked') ? 1 : 0;
		var ck8 = $('#q8').is(':checked') ? 1 : 0;
		var ck9 = $('#q9').is(':checked') ? 1 : 0;
		var ck10 = $('#q10').is(':checked') ? 1 : 0;
		var ck11 = $('#q11').is(':checked') ? 1 : 0;
		var ck12 = $('#q12').is(':checked') ? 1 : 0;
		var ck13 = $('#q13').is(':checked') ? 1 : 0;
		var ck14 = $('#q14').is(':checked') ? 1 : 0;
		var ck15 = $('#q15').is(':checked') ? 1 : 0;
		
	
	if((at1==2 && q1.length==0 && ar1==1) || (at1==1 && q1==0 && ar1==1)) {
			bootbox.alert('Question #1 is Required');
		return;
		}
	if((at2==2 && q2.length==0 && ar2==1) || (at2==1 && q2==0 && ar2==1)) {
			bootbox.alert('Question #2 is Required');
		return;
		}
	if((at3==2 && q3.length==0 && ar3==1) || (at3==1 && q3==0 && ar3==1)) {
			bootbox.alert('Question #3 is Required');
		return;
		}
	if((at4==2 && q4.length==0 && ar4==1) || (at4==1 && q4==0 && ar4==1)) {
			bootbox.alert('Question #4 is Required');
		return;
		}
	if((at5==2 && q5.length==0 && ar5==1) || (at5==1 && q5==0 && ar5==1)) {
			bootbox.alert('Question #5 is Required');
		return;
		}
	if((at6==2 && q6.length==0 && ar6==1) || (at6==1 && q6==0 && ar6==1)) {
			bootbox.alert('Question #6 is Required');
		return;
		}
	if((at7==2 && q7.length==0 && ar7==1) || (at7==1 && q7==0 && ar7==1)) {
			bootbox.alert('Question #7 is Required');
		return;
		}
	if((at8==2 && q8.length==0 && ar8==1) || (at8==1 && q8==0 && ar8==1)) {
			bootbox.alert('Question #8 is Required');
		return;
		}
	if((at9==2 && q9.length==0 && ar9==1) || (at9==1 && q9==0 && ar9==1)) {
			bootbox.alert('Question #9 is Required');
		return;
		}
	if((at10==2 && q10.length==0 && ar10==1) || (at10==1 && q10==0 && ar10==1)) {
			bootbox.alert('Question #5 is Required');
		return;
		}
	if((at11==2 && q11.length==0 && ar11==1) || (at11==1 && q11==0 && ar11==1)) {
			bootbox.alert('Question #11 is Required');
		return;
		}
	if((at12==2 && q12.length==0 && ar12==1) || (at12==1 && q12==0 && ar12==1)) {
			bootbox.alert('Question #12 is Required');
		return;
		}
	if((at13==2 && q13.length==0 && ar13==1) || (at13==1 && q13==0 && ar13==1)) {
			bootbox.alert('Question #13 is Required');
		return;
		}
	if((at14==2 && q14.length==0 && ar14==1) || (at14==1 && q14==0 && ar14==1)) {
			bootbox.alert('Question #14 is Required');
		return;
		}
	if((at15==2 && q15.length==0 && ar15==1) || (at15==1 && q15==0 && ar15==1)) {
			bootbox.alert('Question #15 is Required');
		return;
		}
	if(submittedBy.length==0) {
			bootbox.alert('Please add your name to the Submitted By field.  </br>If you do not want to update this record, go back and click CANCEL.');
		return;
		}
				       
        $.ajax({
            url:'inc/data.php?req=updateCrash',
            data:{
                dataId: dataId,
				q1: q1,
				q2: q2,
				q3: q3,
				q4: q4,
				q5: q5,
				q6: q6,
				q7: q7,
				q8: q8,
				q9: q9,
				q10: q10,
				q11: q11,
				q12: q12,
				q13: q13,
				q14: q14,
				q15: q15,
				q16: q16,
				q17: q17,
				q18: q18,
				q19: q19,
				q20: q20,
				taskEsc: taskEsc,
				taskNote: taskNote,
				submittedBy: submittedBy,
				cdate: cdate,
				deptId: deptId,
				taskName: taskName,
				taskEscOrig: taskEscOrig
				
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(taskEsc==1){
				bootbox.alert('Crash Cart Log Submitted and Escalation Submitted.');	
				}else{
				bootbox.alert('Crash Cart Log Submitted.');	
				}					
				$('#editCrash').modal('hide');
				tj.crashTable.ajax.reload();
				}
			
        })
		
        //console.log('record updated sucessfully',dataId);
		
  } 
	
}


tj.initializeSafetyGrid = function() {
    
    tj.safetyTable = $('#safetyTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getsafety",
            data: function(d) {
				d.start = tj.safetyStartDate;
                d.end = tj.safetyEndDate;
            },
            type:"POST"
        },
        "order": [[6,'desc'],[5,'asc'],[0,'asc']],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			switch(Number(aData.style)) {
            case 1:
				$('td', nRow).addClass('redRow');
                break;
			case 2:
				$('td', nRow).addClass('orangeRow');
                break;
            }			
        },
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "date" },
			{ "data": "room" },
			{ "data": "type" },
			{ "data": "desc" },
			{ "data": "active" },
			{ "data": "priority" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [6] }
                     ]
    } );

tj.setSafetyDates = function() {
	var startuser = tj.safetyStartDate;
	var enduser = tj.safetyEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
}

tj.newSafety = function() {
		document.getElementById("safetyTypes1").style.display='';
        $.ajax({
            url:'inc/data.php?req=getAccountDetails',
            data:{		
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if(response.data.safetyTypes==2){
				document.getElementById("safetyTypes1").style.display='none';	
				}
				$('#huddle').modal('show');
				}	
        })
		
  }

tj.editSafety = function(dataId) {
		document.getElementById("safetyTypes2").style.display='';
        $.ajax({
            url:'inc/data.php?req=getsafetyDetails',
            data:{
                dataId: dataId		
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				if (response.data.priority==1){
				document.getElementById("editpriority").checked = true;
				}else{
				document.getElementById("editpriority").checked = false;	
				}
				if(response.data.safetyTypes==2){
				document.getElementById("safetyTypes2").style.display='none';	
				}
				$('#editsafetyId').val(response.data.id);
				$('#editunit1').val(response.data.deptId);
				$('#editloc1').val(response.data.roomId1);
				$('#editdue1').val(response.data.dueDate);
				//var perievent = JSON.parse(response.data.periEvent1);
				$('#editperi1').val(response.data.safetyConfig);
				//var hr1 = JSON.parse(response.data.hr1);
				//$('#edithrpt1').val(response.data.hr1);
				//var periGen1 = JSON.parse(response.data.periGen1);
				//$('#editperigen1').val(response.data.periGen1);
				$('#editrdesc1').val(response.data.note1);
				$('#edithuddle').modal('show');
				
				}	
        })
		
  }
  
tj.addSafety = function() {
		var deptId = $('#unit1').val();
		var roomId1 = $('#loc1').val();
		var peri1 = $('#peri1').val();
		//var hr1 = $('#hrpt1').val();
		//var periGen1 = $('#perigen1').val();
		var note1 = $('#rdesc1').val();
		var dueDate = $('#due1').val();
		var currentTime = moment().format('YYYY-MM-DD');
		var priority = $('#priority').is(':checked') ? 1 : 0;
		
		if(deptId==0){
		bootbox.alert('Please select a Unit.');
		return;
		}
	
        $.ajax({
            url:'inc/data.php?req=addsafety',
            data:{
				deptId: deptId,
				roomId1: roomId1,
				peri1: peri1,
				note1: note1,
				dueDate: dueDate,
				currentTime: currentTime,
				priority: priority
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#huddle').modal('hide');
				tj.safetyTable.ajax.reload();
				}	
        })
		
  }
  
tj.updateSafety = function() {
		var dataId = $('#editsafetyId').val();
		var deptId = $('#editunit1').val();
		var roomId1 = $('#editloc1').val();
		var priority = $('#editpriority').is(':checked') ? 1 : 0;
		var peri1 = $('#editperi1').val();
		
		//var hr1 = $('#edithrpt1').val();
		
		//var periGen1 = $('#editperigen1').val();
	
		var note1 = $('#editrdesc1').val();
		
		var dueDate = $('#editdue1').val();
	
        $.ajax({
            url:'inc/data.php?req=updatesafety',
            data:{
                dataId: dataId,
				deptId: deptId,
				roomId1: roomId1,
				peri1: peri1,
				note1: note1,
				dueDate: dueDate,
				priority: priority
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#edithuddle').modal('hide');
				tj.safetyTable.ajax.reload();
				}	
        })
		
  }
  
 tj.closeSafety = function(dataId) {
		
		var currentTime = moment().format('YYYY-MM-DD');
		var active = $('#closeSafety'+dataId+'').is(':checked') ? 1 : 0;
		
        $.ajax({
            url:'inc/data.php?req=closesafety',
            data:{
                dataId: dataId,
				currentTime: currentTime,
				active: active
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				//tj.safetyTable.ajax.reload();
				}	
        })
		
  }


tj.closeSafetyOrig = function(dataId) {
		
		var currentTime = moment().format('YYYY-MM-DD');
		var active = $('#closeSafety'+dataId+'').is(':checked') ? 1 : 0;
		if(active==0){
			var msg="Remove this safety issue from";
		}else{
			var msg="Add this safety issue to";
		}
		bootbox.confirm({
        message: msg + ' the Dashboard?',
		backdrop:true,
        callback:function (result) {
		if (result) {
        $.ajax({
            url:'inc/data.php?req=closesafety',
            data:{
                dataId: dataId,
				currentTime: currentTime,
				active: active
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.safetyTable.ajax.reload();
				}	
        })
		}
		}
	});
		
  }


 
}

/////////////////////////////////////
// MESSAGING GLOBALS

tj.initializemsgGrid = function() {

    tj.msgTable = $('#msgTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getMessages",
            data: function(d) {
            },
            type:"POST"
        },
        "order": [[0,'asc']],
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
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "msg" },
			{ "data": "start" },
			{ "data": "end" }
			],
    } );

tj.editMsg = function(dataId,deptId) {		
			
        $.ajax({
            url:'inc/data.php?req=getmsgDetails',
            data:{
                dataId: dataId,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				
				//console.log('expire ',response.data.expDate);
				if(deptId = 0){
				$('#msgDept').html('ALL');
				}else{
				$('#msgDept').html(response.data.dept);
				}
				if(response.data.expDate=='0000-00-00' || response.data.expDate==''){
				$('#expDate').val('');
				}else{
				$('#expDate').val(response.data.expDate);
				}				
				$('#msgId').val(dataId);
				$('#msgdeptId').val(response.data.deptId);
				$('#msgNote').val(response.data.message);				
				$('#editmsg').modal('show');
				}
        })
        
		
  }
  
tj.addMsg = function(userId,deptId) {
		//console.log('dept ',deptId);
	if(parseInt(deptId)>0){	
        $.ajax({
            url:'inc/data.php?req=getUnitDetails',
            data:{
                deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#addmsgDept').html(response.data.dept);
				$('#addmsgdeptId').val(deptId);
				$('#addmsgNote').val('');
				$('#addexpDate').val('');				
				$('#addmsg').modal('show');
				}
        })
	}else{
		$('#addmsgDept').html('ALL UNITS');
		$('#addmsgdeptId').val(deptId);
		$('#addmsgNote').val('');
		$('#addexpDate').val('');
		$('#addmsg').modal('show');
	}
        		
  }
/*  
tj.addglobalMsg = function(userId,deptId) {		
	$('#msgDept').html('ALL UNITS');
	$('#msgdeptId').val(deptId);
	$('#msgNote').val('');				
	$('#addmsg').modal('show'); 
}
*/
  
tj.savemsg = function() {		
	var msgId = $('#msgId').val();
	var message = $('#msgNote').val();
	var expDate = $('#expDate').val();
	
	var createdDate = moment().format('YYYY-MM-DD');
	var deptId = $('#msgdeptId').val();
	       
	   $.ajax({
            url:'inc/data.php?req=updateMsg',
            data:{
                msgId: msgId,
				message: message,
				expDate: expDate,
				createdDate: createdDate,
				deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.msgTable.ajax.reload();
				$('#editmsg').modal('hide');
				
			}
        })
        
		
  }
  
 tj.newmsg = function() {		
	var message = $('#addmsgNote').val();
	var expDate = $('#addexpDate').val();
	var deptId = $('#addmsgdeptId').val();
	var currentDate = moment().format('YYYY-MM-DD');
	//console.log('deptId ',deptId);
        $.ajax({
            url:'inc/data.php?req=newMsg',
            data:{
                deptId: deptId,
				message: message,
				expDate: expDate,
				currentDate: currentDate
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.msgTable.ajax.reload();
				$('#addmsg').modal('hide');
				
			}
        })
        
		
  }
  
  tj.clearMsg = function(dataId) {		
	bootbox.confirm({
        message:"DELETE THIS MESSAGE?",
		backdrop:true,
        callback:function (result) {
		if (result) {
        $.ajax({
            url:'inc/data.php?req=clearMsg',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				tj.msgTable.ajax.reload();
				
			}
        })
		}
		}
	});
       	
  }

};

/////////////////////////////////////
// QBL GLOBALS

tj.initializeqrcodesGrid = function(id) {

    tj.qrcodesTable = $('#qrcodesTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getqrcodes",
            data: function(d) {
            },
            type:"POST"
        },
        "order": [[0,'asc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "desc" },
			{ "data": "updated" },
			{ "data": "qrcode" },
			],
    } );

tj.gogenerateQRCode = function(logix2,id){
	document.getElementById("goqrcodename").innerHTML = "";
	document.getElementById("goqrcode").innerHTML = "";
	document.getElementById("gocloseQR").style.display='';
	//console.log('logix2', logix2);
	//var logix = logix2;
	var goqrcodelink = 'https://productivern.com/go/indexx.php?m=' + logix2 + '&p=' + id + '';
	$('#qrLink').html('<div><a href="https://productivern.com/go/indexx.php?m=' + logix2 + '&p=' + id + '">(Link)</a></div>');
	//console.log('link',goqrcodelink);
	//$('#qrcodename').html('QR Code for: ' + response.data.practiceName);
	new QRCode(document.getElementById("goqrcode"), goqrcodelink);
	
};

tj.resetqrcodes = function(deptId){
	var currentTime = moment().format('YYYY-MM-DD');
	bootbox.confirm({
        message:"RESET ALL QR CODES FOR THIS UNIT?",
		backdrop:true,
        callback:function (result) {
		if (result) {
		$.ajax({
            url:'inc/data.php?req=resetqrcodes',
            data:{
                deptId: deptId,
				currentTime: currentTime
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			document.getElementById("goqrcodename").innerHTML = "";
			document.getElementById("goqrcode").innerHTML = "";
			document.getElementById("gocloseQR").style.display='none';
			tj.qrcodesTable.ajax.reload();
			bootbox.alert('All QR Codes for this Unit have been reset and must be reprinted.');
			}
		})
	
		}
		}
	});
};

tj.printqrcodes = function(deptId){
	document.getElementById("staffingqrcode").innerHTML = "";
	document.getElementById("qblqrcode").innerHTML = "";
	document.getElementById("tasksqrcode").innerHTML = "";
	$('#qrView').show();
		$.ajax({
            url:'inc/data.php?req=printqrcodes',
            data:{
                deptId: deptId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			
			$('#qrdeptname').html(response.data.dept);
			
			if(response.data.updated=='00-00-00'){
			$('#qrupdated').html('');
			}else{
			$('#qrupdated').html(response.data.updated);
			}
			var id = Math.floor(Math.random() * 999) + 1;
			
			var goqrcodelink = 'https://productivern.com/go/indexx.php?m=' + response.data.logix2 + '&p=' + id + '';
			new QRCode(document.getElementById("staffingqrcode"), goqrcodelink);
			var id2 = Math.floor(Math.random() * 9) + 1;
			var goqrcodelink2 = 'https://productivern.com/go/indexx.php?m=' + response.data.logix2 + '&p=9';
			new QRCode(document.getElementById("qblqrcode"), goqrcodelink2);
			//var goqrcodelink2 = 'https://productivern.com/go/indexx.php?m=' + response.data.logix2 + '&p=4';
			//new QRCode(document.getElementById("ablqrcode"), goqrcodelink2);
			var id3 = Math.floor(Math.random() * 96) + 1;
			var goqrcodelink3 = 'https://productivern.com/go/indexx.php?m=' + response.data.logix2 + '&p=' + id3 + '';
			new QRCode(document.getElementById("tasksqrcode"), goqrcodelink3);
			
			}
		})
	

};

tj.gocloseQR = function(){
	document.getElementById("goqrcodename").innerHTML = "";
	document.getElementById("goqrcode").innerHTML = "";
	document.getElementById("gocloseQR").style.display='none';
};


}


tj.sendqblAlert = function(dataId,msgId) {
	$.ajax({
            url:'inc/data.php?req=sendqblesc',
            data:{
                dataId: dataId,
				msgId : msgId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			//return;
			}
	})
}

 tj.startQBL = function(dataId) {
	//var alertId=0;

	var userId = dataId;
	//var alertId=10;
	 //console.log('alertId',alertId);
	 //console.log('dataId',dataId);
	 var resetqbl = 0;
	 var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	 		document.getElementById('s1').style.display='none';
			document.getElementById('s2').style.display='none';
			document.getElementById('s3').style.display='none';
			document.getElementById('s4').style.display='none';
			document.getElementById('s5').style.display='none';
			document.getElementById('s6').style.display='none';
			document.getElementById('s7').style.display='none';
			document.getElementById('s8').style.display='none';
			document.getElementById('s9').style.display='none';
			document.getElementById('s10').style.display='none';
			document.getElementById('s11').style.display='none';
			document.getElementById('s12').style.display='none';
			document.getElementById('s13').style.display='none';
			document.getElementById('s14').style.display='none';
			document.getElementById('s15').style.display='none';
	
	bootbox.confirm({
    message: "Would you like to notify the House about this QBL?",
    buttons: {
        confirm: {
            label: 'Yes',
            className: 'btn-success'
        },
        cancel: {
            label: 'No',
            className: 'btn-danger'
        }
    },
	backdrop:true,
    callback: function (result) {
	if(result){
	var alertId=1;
	$.ajax({
            url:'inc/data.php?req=getqbldetails',
            data:{
                userId: userId,
				currentTime: currentTime,
				resetqbl: resetqbl,
				alertId: alertId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			var a1 = document.getElementById('s1');
			var a2 = document.getElementById('s2');
			var a3 = document.getElementById('s3');
			var a4 = document.getElementById('s4');
			var a5 = document.getElementById('s5');
			var a6 = document.getElementById('s6');
			var a7 = document.getElementById('s7');
			var a8 = document.getElementById('s8');
			var a9 = document.getElementById('s9');
			var a10 = document.getElementById('s10');
			var a11 = document.getElementById('s11');
			var a12 = document.getElementById('s12');
			var a13 = document.getElementById('s13');
			var a14 = document.getElementById('s14');
			var a15 = document.getElementById('s15');
		
			
			$('#unitName').html('<h5>' + response.data.dept + '</h5>');
			$('#q1').val('0');
			$('#q2').val('0');
			$('#q3').val('0');
			$('#q4').val('0');
			$('#q5').val('0');
			$('#q6').val('0');
			$('#q7').val('0');
			$('#q8').val('0');
			$('#q9').val('0');
			$('#q10').val('0');
			$('#q11').val('0');
			$('#q12').val('0');
			$('#q13').val('0');
			$('#q14').val('0');
			$('#q15').val('0');
			$('#reset').val('0');
			$('#total1').val('');
			$('#estqbl').val('0');
			$('#qblNote').val('');
			$('#logId').val(response.log.id);
			$('#qbldept').val(response.data.deptId);
			$('#txtSent').val('0');
			$('#userId').val(userId);
			$('#val1').val(response.data.value1);
			$('#val2').val(response.data.value2);
			$('#val3').val(response.data.value3);
			$('#val4').val(response.data.value4);
			$('#val5').val(response.data.value5);
			$('#val6').val(response.data.value6);
			$('#val7').val(response.data.value7);
			$('#val8').val(response.data.value8);
			$('#val9').val(response.data.value9);
			$('#val10').val(response.data.value10);
			$('#val11').val(response.data.value11);
			$('#val12').val(response.data.value12);
			$('#val13').val(response.data.value13);
			$('#val14').val(response.data.value14);
			$('#val15').val(response.data.value15);
			$('#desc1').html('' + response.data.desc1 + ': ' + response.data.value1 + 'g');
			$('#desc2').html('' + response.data.desc2 + ': ' + response.data.value2 + 'g');
			$('#desc3').html('' + response.data.desc3 + ': ' + response.data.value3 + 'g');
			$('#desc4').html('' + response.data.desc4 + ': ' + response.data.value4 + 'g');
			$('#desc5').html('' + response.data.desc5 + ': ' + response.data.value5 + 'g');
			$('#desc6').html('' + response.data.desc6 + ': ' + response.data.value6 + 'g');
			$('#desc7').html('' + response.data.desc7 + ': ' + response.data.value7 + 'g');
			$('#desc8').html('' + response.data.desc8 + ': ' + response.data.value8 + 'g');
			$('#desc9').html('' + response.data.desc9 + ': ' + response.data.value9 + 'g');
			$('#desc10').html('' + response.data.desc10 + ': ' + response.data.value10 + 'g');
			$('#desc11').html('' + response.data.desc11 + ': ' + response.data.value11 + 'g');
			$('#desc12').html('' + response.data.desc12 + ': ' + response.data.value12 + 'g');
			$('#desc13').html('' + response.data.desc13 + ': ' + response.data.value13 + 'g');
			$('#desc14').html('' + response.data.desc14 + ': ' + response.data.value14 + 'g');
			$('#desc15').html('' + response.data.desc15 + ': ' + response.data.value15 + 'g');
			
			$('#image1').html('<img src="../qblimages/' + response.data.image1 + '" style="max-width:150px" />');
			$('#image2').html('<img src="../qblimages/' + response.data.image2 + '" style="max-width:150px" />');
			$('#image3').html('<img src="../qblimages/' + response.data.image3 + '" style="max-width:150px" />');
			$('#image4').html('<img src="../qblimages/' + response.data.image4 + '" style="max-width:150px" />');
			$('#image5').html('<img src="../qblimages/' + response.data.image5 + '" style="max-width:150px" />');
			$('#image6').html('<img src="../qblimages/' + response.data.image6 + '" style="max-width:150px" />');
			$('#image7').html('<img src="../qblimages/' + response.data.image7 + '" style="max-width:150px" />');
			$('#image8').html('<img src="../qblimages/' + response.data.image8 + '" style="max-width:150px" />');
			$('#image9').html('<img src="../qblimages/' + response.data.image9 + '" style="max-width:150px" />');
			$('#image10').html('<img src="../qblimages/' + response.data.image10 + '" style="max-width:150px" />');
			$('#image11').html('<img src="../qblimages/' + response.data.image11 + '" style="max-width:150px" />');
			$('#image12').html('<img src="../qblimages/' + response.data.image12 + '" style="max-width:150px" />');
			$('#image13').html('<img src="../qblimages/' + response.data.image13 + '" style="max-width:150px" />');
			$('#image14').html('<img src="../qblimages/' + response.data.image14 + '" style="max-width:150px" />');
			$('#image15').html('<img src="../qblimages/' + response.data.image15 + '" style="max-width:150px" />');
			
			if(response.data.value1==0 || response.data.desc1.length ==0){
				a1.style.dispay = '';
			}else{
				a1.style.display = '';
			}
		
			if(response.data.value2==0 || response.data.desc2.length ==0){
				a2.style.dispay = 'none';
			}else{
				a2.style.display = '';
			}
			if(response.data.value3==0 || response.data.desc3.length ==0){
				a3.style.dispay = 'none';
			}else{
				a3.style.display = '';
			}
			if(response.data.value4==0 || response.data.desc4.length ==0){
				a4.style.dispay = 'none';
			}else{
				a4.style.display = '';
			}
			if(response.data.value5==0 || response.data.desc5.length ==0){
				a5.style.dispay = 'none';
			}else{
				a5.style.display = '';
			}
			if(response.data.value6==0 || response.data.desc6.length ==0){
				a6.style.dispay = 'none';
			}else{
				a6.style.display = '';
			}
			if(response.data.value7==0 || response.data.desc7.length ==0){
				a7.style.dispay = 'none';
			}else{
				a7.style.display = '';
			}
			if(response.data.value8==0 || response.data.desc8.length ==0){
				a8.style.dispay = 'none';
			}else{
				a8.style.display = '';
			}
			if(response.data.value9==0 || response.data.desc9.length ==0){
				a9.style.dispay = 'none';
			}else{
				a9.style.display = '';
			}
			
			
			if(response.data.value10==0 || response.data.desc10.length ==0){
				a10.style.dispay = 'none';
			}else{
				a10.style.display = '';
			}
			
			if(response.data.value11==0 || response.data.desc11.length ==0){
				a11.style.dispay = 'none';
			}else{
				a11.style.display = '';
			}
			
			if(response.data.value12==0 || response.data.desc12.length ==0){
				a12.style.dispay = 'none';
			}else{
				a12.style.display = '';
			}
			
			if(response.data.value13==0 || response.data.desc13.length ==0){
				a13.style.dispay = 'none';
			}else{
				a13.style.display = '';
			}
			
			if(response.data.value14==0 || response.data.desc14.length ==0){
				a14.style.dispay = 'none';
			}else{
				a14.style.display = '';
			}
			
			if(response.data.value15==0 || response.data.desc15.length ==0){
				a15.style.dispay = 'none';
			}else{
				a15.style.display = '';
			}
			
			
			}
			
        })
	}
	var alertId=2;
	$.ajax({
            url:'inc/data.php?req=getqbldetails',
            data:{
                userId: userId,
				currentTime: currentTime,
				resetqbl: resetqbl,
				alertId: alertId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			var a1 = document.getElementById('s1');
			var a2 = document.getElementById('s2');
			var a3 = document.getElementById('s3');
			var a4 = document.getElementById('s4');
			var a5 = document.getElementById('s5');
			var a6 = document.getElementById('s6');
			var a7 = document.getElementById('s7');
			var a8 = document.getElementById('s8');
			var a9 = document.getElementById('s9');
			var a10 = document.getElementById('s10');
			var a11 = document.getElementById('s11');
			var a12 = document.getElementById('s12');
			var a13 = document.getElementById('s13');
			var a14 = document.getElementById('s14');
			var a15 = document.getElementById('s15');
		
			
			$('#unitName').html('<h5>' + response.data.dept + '</h5>');
			$('#q1').val('0');
			$('#q2').val('0');
			$('#q3').val('0');
			$('#q4').val('0');
			$('#q5').val('0');
			$('#q6').val('0');
			$('#q7').val('0');
			$('#q8').val('0');
			$('#q9').val('0');
			$('#q10').val('0');
			$('#q11').val('0');
			$('#q12').val('0');
			$('#q13').val('0');
			$('#q14').val('0');
			$('#q15').val('0');
			$('#reset').val('0');
			$('#total1').val('');
			$('#estqbl').val('0');
			$('#qblNote').val('');
			$('#logId').val(response.log.id);
			$('#qbldept').val(response.data.deptId);
			$('#txtSent').val('0');
			$('#userId').val(userId);
			$('#val1').val(response.data.value1);
			$('#val2').val(response.data.value2);
			$('#val3').val(response.data.value3);
			$('#val4').val(response.data.value4);
			$('#val5').val(response.data.value5);
			$('#val6').val(response.data.value6);
			$('#val7').val(response.data.value7);
			$('#val8').val(response.data.value8);
			$('#val9').val(response.data.value9);
			$('#val10').val(response.data.value10);
			$('#val11').val(response.data.value11);
			$('#val12').val(response.data.value12);
			$('#val13').val(response.data.value13);
			$('#val14').val(response.data.value14);
			$('#val15').val(response.data.value15);
			$('#desc1').html('' + response.data.desc1 + ': ' + response.data.value1 + 'g');
			$('#desc2').html('' + response.data.desc2 + ': ' + response.data.value2 + 'g');
			$('#desc3').html('' + response.data.desc3 + ': ' + response.data.value3 + 'g');
			$('#desc4').html('' + response.data.desc4 + ': ' + response.data.value4 + 'g');
			$('#desc5').html('' + response.data.desc5 + ': ' + response.data.value5 + 'g');
			$('#desc6').html('' + response.data.desc6 + ': ' + response.data.value6 + 'g');
			$('#desc7').html('' + response.data.desc7 + ': ' + response.data.value7 + 'g');
			$('#desc8').html('' + response.data.desc8 + ': ' + response.data.value8 + 'g');
			$('#desc9').html('' + response.data.desc9 + ': ' + response.data.value9 + 'g');
			$('#desc10').html('' + response.data.desc10 + ': ' + response.data.value10 + 'g');
			$('#desc11').html('' + response.data.desc11 + ': ' + response.data.value11 + 'g');
			$('#desc12').html('' + response.data.desc12 + ': ' + response.data.value12 + 'g');
			$('#desc13').html('' + response.data.desc13 + ': ' + response.data.value13 + 'g');
			$('#desc14').html('' + response.data.desc14 + ': ' + response.data.value14 + 'g');
			$('#desc15').html('' + response.data.desc15 + ': ' + response.data.value15 + 'g');
			
			$('#image1').html('<img src="../qblimages/' + response.data.image1 + '" style="max-width:150px" />');
			$('#image2').html('<img src="../qblimages/' + response.data.image2 + '" style="max-width:150px" />');
			$('#image3').html('<img src="../qblimages/' + response.data.image3 + '" style="max-width:150px" />');
			$('#image4').html('<img src="../qblimages/' + response.data.image4 + '" style="max-width:150px" />');
			$('#image5').html('<img src="../qblimages/' + response.data.image5 + '" style="max-width:150px" />');
			$('#image6').html('<img src="../qblimages/' + response.data.image6 + '" style="max-width:150px" />');
			$('#image7').html('<img src="../qblimages/' + response.data.image7 + '" style="max-width:150px" />');
			$('#image8').html('<img src="../qblimages/' + response.data.image8 + '" style="max-width:150px" />');
			$('#image9').html('<img src="../qblimages/' + response.data.image9 + '" style="max-width:150px" />');
			$('#image10').html('<img src="../qblimages/' + response.data.image10 + '" style="max-width:150px" />');
			$('#image11').html('<img src="../qblimages/' + response.data.image11 + '" style="max-width:150px" />');
			$('#image12').html('<img src="../qblimages/' + response.data.image12 + '" style="max-width:150px" />');
			$('#image13').html('<img src="../qblimages/' + response.data.image13 + '" style="max-width:150px" />');
			$('#image14').html('<img src="../qblimages/' + response.data.image14 + '" style="max-width:150px" />');
			$('#image15').html('<img src="../qblimages/' + response.data.image15 + '" style="max-width:150px" />');
			
			if(response.data.value1==0 || response.data.desc1.length ==0){
				a1.style.dispay = '';
			}else{
				a1.style.display = '';
			}
		
			if(response.data.value2==0 || response.data.desc2.length ==0){
				a2.style.dispay = 'none';
			}else{
				a2.style.display = '';
			}
			if(response.data.value3==0 || response.data.desc3.length ==0){
				a3.style.dispay = 'none';
			}else{
				a3.style.display = '';
			}
			if(response.data.value4==0 || response.data.desc4.length ==0){
				a4.style.dispay = 'none';
			}else{
				a4.style.display = '';
			}
			if(response.data.value5==0 || response.data.desc5.length ==0){
				a5.style.dispay = 'none';
			}else{
				a5.style.display = '';
			}
			if(response.data.value6==0 || response.data.desc6.length ==0){
				a6.style.dispay = 'none';
			}else{
				a6.style.display = '';
			}
			if(response.data.value7==0 || response.data.desc7.length ==0){
				a7.style.dispay = 'none';
			}else{
				a7.style.display = '';
			}
			if(response.data.value8==0 || response.data.desc8.length ==0){
				a8.style.dispay = 'none';
			}else{
				a8.style.display = '';
			}
			if(response.data.value9==0 || response.data.desc9.length ==0){
				a9.style.dispay = 'none';
			}else{
				a9.style.display = '';
			}
			
			
			if(response.data.value10==0 || response.data.desc10.length ==0){
				a10.style.dispay = 'none';
			}else{
				a10.style.display = '';
			}
			
			if(response.data.value11==0 || response.data.desc11.length ==0){
				a11.style.dispay = 'none';
			}else{
				a11.style.display = '';
			}
			
			if(response.data.value12==0 || response.data.desc12.length ==0){
				a12.style.dispay = 'none';
			}else{
				a12.style.display = '';
			}
			
			if(response.data.value13==0 || response.data.desc13.length ==0){
				a13.style.dispay = 'none';
			}else{
				a13.style.display = '';
			}
			
			if(response.data.value14==0 || response.data.desc14.length ==0){
				a14.style.dispay = 'none';
			}else{
				a14.style.display = '';
			}
			
			if(response.data.value15==0 || response.data.desc15.length ==0){
				a15.style.dispay = 'none';
			}else{
				a15.style.display = '';
			}
			
			
			}
			
        })
	}
	});
		
	
}


 tj.startQBL2 = function(dataId) {
	//var alertId=0;

	var userId = dataId;
	 //console.log('userId',dataId);
	 var resetqbl = 0;
	 var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	 		document.getElementById('s1').style.display='none';
			document.getElementById('s2').style.display='none';
			document.getElementById('s3').style.display='none';
			document.getElementById('s4').style.display='none';
			document.getElementById('s5').style.display='none';
			document.getElementById('s6').style.display='none';
			document.getElementById('s7').style.display='none';
			document.getElementById('s8').style.display='none';
			document.getElementById('s9').style.display='none';
			document.getElementById('s10').style.display='none';
			document.getElementById('s11').style.display='none';
			document.getElementById('s12').style.display='none';
			document.getElementById('s13').style.display='none';
			document.getElementById('s14').style.display='none';
			document.getElementById('s15').style.display='none';

	$.ajax({
            url:'inc/data.php?req=getqbldetails',
            data:{
                userId: userId,
				currentTime: currentTime,
				resetqbl: resetqbl
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			
			var a1 = document.getElementById('s1');
			var a2 = document.getElementById('s2');
			var a3 = document.getElementById('s3');
			var a4 = document.getElementById('s4');
			var a5 = document.getElementById('s5');
			var a6 = document.getElementById('s6');
			var a7 = document.getElementById('s7');
			var a8 = document.getElementById('s8');
			var a9 = document.getElementById('s9');
			var a10 = document.getElementById('s10');
			var a11 = document.getElementById('s11');
			var a12 = document.getElementById('s12');
			var a13 = document.getElementById('s13');
			var a14 = document.getElementById('s14');
			var a15 = document.getElementById('s15');
			
			$('#newabl').html('');
			$('#unitName').html('<h5>' + response.data.dept + '</h5>');
			$('#q1').val('0');
			$('#q2').val('0');
			$('#q3').val('0');
			$('#q4').val('0');
			$('#q5').val('0');
			$('#q6').val('0');
			$('#q7').val('0');
			$('#q8').val('0');
			$('#q9').val('0');
			$('#q10').val('0');
			$('#q11').val('0');
			$('#q12').val('0');
			$('#q13').val('0');
			$('#q14').val('0');
			$('#q15').val('0');
			$('#reset').val('0');
			$('#total1').val('');
			$('#estqbl').val('0');
			$('#qblNote').val('');
			$('#logId').val(response.log.id);
			$('#qbldept').val(response.data.deptId);
			$('#txtSent').val('0');
			$('#userId').val(userId);
			$('#val1').val(response.data.value1);
			$('#val2').val(response.data.value2);
			$('#val3').val(response.data.value3);
			$('#val4').val(response.data.value4);
			$('#val5').val(response.data.value5);
			$('#val6').val(response.data.value6);
			$('#val7').val(response.data.value7);
			$('#val8').val(response.data.value8);
			$('#val9').val(response.data.value9);
			$('#val10').val(response.data.value10);
			$('#val11').val(response.data.value11);
			$('#val12').val(response.data.value12);
			$('#val13').val(response.data.value13);
			$('#val14').val(response.data.value14);
			$('#val15').val(response.data.value15);
			
			$('#ablpatient').val('0');
			$('#ablweight').val('');
			$('#ablweightType').val('1');
			$('#ablih').val('');
			$('#ablfh').val('7');
			$('#ablestabv').val('0');
			$('#ablabl').val('0');
			$('#ablabv').val('0');
	
			$('#desc1').html('' + response.data.desc1 + ': ' + response.data.value1 + 'g');
			$('#desc2').html('' + response.data.desc2 + ': ' + response.data.value2 + 'g');
			$('#desc3').html('' + response.data.desc3 + ': ' + response.data.value3 + 'g');
			$('#desc4').html('' + response.data.desc4 + ': ' + response.data.value4 + 'g');
			$('#desc5').html('' + response.data.desc5 + ': ' + response.data.value5 + 'g');
			$('#desc6').html('' + response.data.desc6 + ': ' + response.data.value6 + 'g');
			$('#desc7').html('' + response.data.desc7 + ': ' + response.data.value7 + 'g');
			$('#desc8').html('' + response.data.desc8 + ': ' + response.data.value8 + 'g');
			$('#desc9').html('' + response.data.desc9 + ': ' + response.data.value9 + 'g');
			$('#desc10').html('' + response.data.desc10 + ': ' + response.data.value10 + 'g');
			$('#desc11').html('' + response.data.desc11 + ': ' + response.data.value11 + 'g');
			$('#desc12').html('' + response.data.desc12 + ': ' + response.data.value12 + 'g');
			$('#desc13').html('' + response.data.desc13 + ': ' + response.data.value13 + 'g');
			$('#desc14').html('' + response.data.desc14 + ': ' + response.data.value14 + 'g');
			$('#desc15').html('' + response.data.desc15 + ': ' + response.data.value15 + 'g');
			
			$('#image1').html('<img src="../qblimages/' + response.data.image1 + '" style="max-width:150px" />');
			$('#image2').html('<img src="../qblimages/' + response.data.image2 + '" style="max-width:150px" />');
			$('#image3').html('<img src="../qblimages/' + response.data.image3 + '" style="max-width:150px" />');
			$('#image4').html('<img src="../qblimages/' + response.data.image4 + '" style="max-width:150px" />');
			$('#image5').html('<img src="../qblimages/' + response.data.image5 + '" style="max-width:150px" />');
			$('#image6').html('<img src="../qblimages/' + response.data.image6 + '" style="max-width:150px" />');
			$('#image7').html('<img src="../qblimages/' + response.data.image7 + '" style="max-width:150px" />');
			$('#image8').html('<img src="../qblimages/' + response.data.image8 + '" style="max-width:150px" />');
			$('#image9').html('<img src="../qblimages/' + response.data.image9 + '" style="max-width:150px" />');
			$('#image10').html('<img src="../qblimages/' + response.data.image10 + '" style="max-width:150px" />');
			$('#image11').html('<img src="../qblimages/' + response.data.image11 + '" style="max-width:150px" />');
			$('#image12').html('<img src="../qblimages/' + response.data.image12 + '" style="max-width:150px" />');
			$('#image13').html('<img src="../qblimages/' + response.data.image13 + '" style="max-width:150px" />');
			$('#image14').html('<img src="../qblimages/' + response.data.image14 + '" style="max-width:150px" />');
			$('#image15').html('<img src="../qblimages/' + response.data.image15 + '" style="max-width:150px" />');
			
			if(response.data.value1==0 || response.data.desc1.length ==0){
				a1.style.dispay = '';
			}else{
				a1.style.display = '';
			}
		
			if(response.data.value2==0 || response.data.desc2.length ==0){
				a2.style.dispay = 'none';
			}else{
				a2.style.display = '';
			}
			if(response.data.value3==0 || response.data.desc3.length ==0){
				a3.style.dispay = 'none';
			}else{
				a3.style.display = '';
			}
			if(response.data.value4==0 || response.data.desc4.length ==0){
				a4.style.dispay = 'none';
			}else{
				a4.style.display = '';
			}
			if(response.data.value5==0 || response.data.desc5.length ==0){
				a5.style.dispay = 'none';
			}else{
				a5.style.display = '';
			}
			if(response.data.value6==0 || response.data.desc6.length ==0){
				a6.style.dispay = 'none';
			}else{
				a6.style.display = '';
			}
			if(response.data.value7==0 || response.data.desc7.length ==0){
				a7.style.dispay = 'none';
			}else{
				a7.style.display = '';
			}
			if(response.data.value8==0 || response.data.desc8.length ==0){
				a8.style.dispay = 'none';
			}else{
				a8.style.display = '';
			}
			if(response.data.value9==0 || response.data.desc9.length ==0){
				a9.style.dispay = 'none';
			}else{
				a9.style.display = '';
			}
			
			
			if(response.data.value10==0 || response.data.desc10.length ==0){
				a10.style.dispay = 'none';
			}else{
				a10.style.display = '';
			}
			
			if(response.data.value11==0 || response.data.desc11.length ==0){
				a11.style.dispay = 'none';
			}else{
				a11.style.display = '';
			}
			
			if(response.data.value12==0 || response.data.desc12.length ==0){
				a12.style.dispay = 'none';
			}else{
				a12.style.display = '';
			}
			
			if(response.data.value13==0 || response.data.desc13.length ==0){
				a13.style.dispay = 'none';
			}else{
				a13.style.display = '';
			}
			
			if(response.data.value14==0 || response.data.desc14.length ==0){
				a14.style.dispay = 'none';
			}else{
				a14.style.display = '';
			}
			
			if(response.data.value15==0 || response.data.desc15.length ==0){
				a15.style.dispay = 'none';
			}else{
				a15.style.display = '';
			}
			bootbox.confirm({
			message: "Would you to send a QBL ALERT?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			backdrop:true,
			callback: function (result) {
			if(result){
			var alertId=1;
			$('#txtSent').val('1');
			tj.sendqblAlert(response.data.deptId,alertId);
			//console.log('deptId ',response.data.deptId);
			//console.log('alertId ', alertId);
			}
			}
        })
		}

	});
	
}

 tj.editABL = function(dataId) {

	$.ajax({
            url:'inc/data.php?req=qbldetails',
            data:{
                dataId: dataId,
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			
			$('#abDept').html('<h5>' + response.data.deptName + '</h5>');
			$('#abDate').html('<h5>' + response.data.dateTime + '</h5>');
			$('#abpatient').val(response.data.ptselect);
			$('#abweight').val(response.data.ptweight);
			$('#weightType').val(response.data.weightType);
			
			$('#ih').val(response.data.ih);
			$('#fh').val(response.data.fh);
			$('#abv').val(parseInt(response.data.abv));
			$('#abl').val(parseInt(response.data.abl));
			$('#estabv').val(response.data.ebv);
					
			$('#editABL').modal('show');
			}
			
        })
		
	
}

 tj.editQBL = function(dataId) {
	 //var logId = $('#logId').val();
	 //console.log('logId',logId);
	 //console.log('room',roomId);
	
	 		document.getElementById('qbs1').style.display='none';
			document.getElementById('qbs2').style.display='none';
			document.getElementById('qbs3').style.display='none';
			document.getElementById('qbs4').style.display='none';
			document.getElementById('qbs5').style.display='none';
			document.getElementById('qbs6').style.display='none';
			document.getElementById('qbs7').style.display='none';
			document.getElementById('qbs8').style.display='none';
			document.getElementById('qbs9').style.display='none';
			document.getElementById('qbs10').style.display='none';
			document.getElementById('qbs11').style.display='none';
			document.getElementById('qbs12').style.display='none';
			document.getElementById('qbs13').style.display='none';
			document.getElementById('qbs14').style.display='none';
			document.getElementById('qbs15').style.display='none';
	$.ajax({
            url:'inc/data.php?req=qbldetails',
            data:{
                dataId: dataId,
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			var a1 = document.getElementById('qbs1');
			var a2 = document.getElementById('qbs2');
			var a3 = document.getElementById('qbs3');
			var a4 = document.getElementById('qbs4');
			var a5 = document.getElementById('qbs5');
			var a6 = document.getElementById('qbs6');
			var a7 = document.getElementById('qbs7');
			var a8 = document.getElementById('qbs8');
			var a9 = document.getElementById('qbs9');
			var a10 = document.getElementById('qbs10');
			var a11 = document.getElementById('qbs11');
			var a12 = document.getElementById('qbs12');
			var a13 = document.getElementById('qbs13');
			var a14 = document.getElementById('qbs14');
			var a15 = document.getElementById('qbs15');
		
			
			$('#qbunitName').html('<h5>' + response.data.dept + '</h5>');
			$('#qbq1').val(response.data.v1);
			$('#qbq2').val(response.data.v2);
			$('#qbq3').val(response.data.v3);
			$('#qbq4').val(response.data.v4);
			$('#qbq5').val(response.data.v5);
			$('#qbq6').val(response.data.v6);
			$('#qbq7').val(response.data.v7);
			$('#qbq8').val(response.data.v8);
			$('#qbq9').val(response.data.v9);
			$('#qbq10').val(response.data.v10);
			$('#qbq11').val(response.data.v11);
			$('#qbq12').val(response.data.v12);
			$('#qbq13').val(response.data.v13);
			$('#qbq14').val(response.data.v14);
			$('#qbq15').val(response.data.v15);
			$('#qbtotal1').val(response.data.totalWt);
			$('#qbqblNote').val(response.data.note);
			$('#qbestqbl').val(response.data.totalQBL);
			$('#qbdesc1').html('' + response.data.desc1 + ': ' + response.data.value1 + 'g');
			$('#qbdesc2').html('' + response.data.desc2 + ': ' + response.data.value2 + 'g');
			$('#qbdesc3').html('' + response.data.desc3 + ': ' + response.data.value3 + 'g');
			$('#qbdesc4').html('' + response.data.desc4 + ': ' + response.data.value4 + 'g');
			$('#qbdesc5').html('' + response.data.desc5 + ': ' + response.data.value5 + 'g');
			$('#qbdesc6').html('' + response.data.desc6 + ': ' + response.data.value6 + 'g');
			$('#qbdesc7').html('' + response.data.desc7 + ': ' + response.data.value7 + 'g');
			$('#qbdesc8').html('' + response.data.desc8 + ': ' + response.data.value8 + 'g');
			$('#qbdesc9').html('' + response.data.desc9 + ': ' + response.data.value9 + 'g');
			$('#qbdesc10').html('' + response.data.desc10 + ': ' + response.data.value10 + 'g');
			$('#qbdesc11').html('' + response.data.desc11 + ': ' + response.data.value11 + 'g');
			$('#qbdesc12').html('' + response.data.desc12 + ': ' + response.data.value12 + 'g');
			$('#qbdesc13').html('' + response.data.desc13 + ': ' + response.data.value13 + 'g');
			$('#qbdesc14').html('' + response.data.desc14 + ': ' + response.data.value14 + 'g');
			$('#qbdesc15').html('' + response.data.desc15 + ': ' + response.data.value15 + 'g');
			
			$('#qbUnit').html('<div><strong>Unit: </strong>' + response.data.deptName + '</div>');
			$('#qbDate').html('<div><strong>Date/Time: </strong>' + response.data.dateTime + '</div>');
			
			$('#qbimage1').html('<img src="../qblimages/' + response.data.image1 + '" style="max-width:150px" />');
			$('#qbimage2').html('<img src="../qblimages/' + response.data.image2 + '" style="max-width:150px" />');
			$('#qbimage3').html('<img src="../qblimages/' + response.data.image3 + '" style="max-width:150px" />');
			$('#qbimage4').html('<img src="../qblimages/' + response.data.image4 + '" style="max-width:150px" />');
			$('#qbimage5').html('<img src="../qblimages/' + response.data.image5 + '" style="max-width:150px" />');
			$('#qbimage6').html('<img src="../qblimages/' + response.data.image6 + '" style="max-width:150px" />');
			$('#qbimage7').html('<img src="../qblimages/' + response.data.image7 + '" style="max-width:150px" />');
			$('#qbimage8').html('<img src="../qblimages/' + response.data.image8 + '" style="max-width:150px" />');
			$('#qbimage9').html('<img src="../qblimages/' + response.data.image9 + '" style="max-width:150px" />');
			$('#qbimage10').html('<img src="../qblimages/' + response.data.image10 + '" style="max-width:150px" />');
			$('#qbimage11').html('<img src="../qblimages/' + response.data.image11 + '" style="max-width:150px" />');
			$('#qbimage12').html('<img src="../qblimages/' + response.data.image12 + '" style="max-width:150px" />');
			$('#qbimage13').html('<img src="../qblimages/' + response.data.image13 + '" style="max-width:150px" />');
			$('#qbimage14').html('<img src="../qblimages/' + response.data.image14 + '" style="max-width:150px" />');
			$('#qbimage15').html('<img src="../qblimages/' + response.data.image15 + '" style="max-width:150px" />');
			
			if(response.data.value1==0 || response.data.desc1.length ==0){
				a1.style.dispay = '';
			}else{
				a1.style.display = '';
			}
		
			if(response.data.value2==0 || response.data.desc2.length ==0){
				a2.style.dispay = 'none';
			}else{
				a2.style.display = '';
			}
			if(response.data.value3==0 || response.data.desc3.length ==0){
				a3.style.dispay = 'none';
			}else{
				a3.style.display = '';
			}
			if(response.data.value4==0 || response.data.desc4.length ==0){
				a4.style.dispay = 'none';
			}else{
				a4.style.display = '';
			}
			if(response.data.value5==0 || response.data.desc5.length ==0){
				a5.style.dispay = 'none';
			}else{
				a5.style.display = '';
			}
			if(response.data.value6==0 || response.data.desc6.length ==0){
				a6.style.dispay = 'none';
			}else{
				a6.style.display = '';
			}
			if(response.data.value7==0 || response.data.desc7.length ==0){
				a7.style.dispay = 'none';
			}else{
				a7.style.display = '';
			}
			if(response.data.value8==0 || response.data.desc8.length ==0){
				a8.style.dispay = 'none';
			}else{
				a8.style.display = '';
			}
			if(response.data.value9==0 || response.data.desc9.length ==0){
				a9.style.dispay = 'none';
			}else{
				a9.style.display = '';
			}
			
			
			if(response.data.value10==0 || response.data.desc10.length ==0){
				a10.style.dispay = 'none';
			}else{
				a10.style.display = '';
			}
			
			if(response.data.value11==0 || response.data.desc11.length ==0){
				a11.style.dispay = 'none';
			}else{
				a11.style.display = '';
			}
			
			if(response.data.value12==0 || response.data.desc12.length ==0){
				a12.style.dispay = 'none';
			}else{
				a12.style.display = '';
			}
			
			if(response.data.value13==0 || response.data.desc13.length ==0){
				a13.style.dispay = 'none';
			}else{
				a13.style.display = '';
			}
			
			if(response.data.value14==0 || response.data.desc14.length ==0){
				a14.style.dispay = 'none';
			}else{
				a14.style.display = '';
			}
			
			if(response.data.value15==0 || response.data.desc15.length ==0){
				a15.style.dispay = 'none';
			}else{
				a15.style.display = '';
			}
			if(response.data.abl>0 && response.data.ih>0 && response.data.fh>0 && response.data.ptweight>0 && response.data.abv>0){
				$('#ablData').html('<div class="title" style="color:red">ABL: ' + parseInt(response.data.abl) + 'ml</div>');
			}else{
				$('#ablData').html('');
			}
			
			$('#seeQBL').modal('show');
			}
			
        })
		
	
}

 tj.resetqbl = function() {
	 var userId = $('#userId').val();
	 //var roomId = $('#roomId').val();
	 var logId = $('#logId').val();
	 var resetqbl = 1;
	 var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	
	bootbox.confirm({
        message:"Reset This Form?  All Data Will Be Lost!",
		backdrop:true,
        callback:function (result) {
		if (result) {
	
	$.ajax({
            url:'inc/data.php?req=getqbldetails',
            data:{
                userId: userId,
				currentTime: currentTime,
				logId: logId,
				resetqbl: resetqbl
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			$('#unitName').html('<h5>' + response.data.dept + '</h5>');
			$('#q1').val('0');
			$('#q2').val('0');
			$('#q3').val('0');
			$('#q4').val('0');
			$('#q5').val('0');
			$('#q6').val('0');
			$('#q7').val('0');
			$('#q8').val('0');
			$('#q9').val('0');
			$('#q10').val('0');
			$('#q11').val('0');
			$('#q12').val('0');
			$('#q13').val('0');
			$('#q14').val('0');
			$('#q15').val('0');
			$('#reset').val('0');
			$('#total1').val('0');
			$('#estqbl').val('0');
			$('#qblNote').val('');
			$('#logId').val(response.log.id);
			$('#userId').val(response.log.userId);
			$('#val1').val(response.data.value1);
			$('#val2').val(response.data.value2);
			$('#val3').val(response.data.value3);
			$('#val4').val(response.data.value4);
			$('#val5').val(response.data.value5);
			$('#val6').val(response.data.value6);
			$('#val7').val(response.data.value7);
			$('#val8').val(response.data.value8);
			$('#val9').val(response.data.value9);
			$('#val10').val(response.data.value10);
			$('#val11').val(response.data.value11);
			$('#val12').val(response.data.value12);
			$('#val13').val(response.data.value13);
			$('#val14').val(response.data.value14);
			$('#val15').val(response.data.value15);
			$('#desc1').html('' + response.data.desc1 + ': ' + response.data.value1 + 'g');
			$('#desc2').html('' + response.data.desc2 + ': ' + response.data.value2 + 'g');
			$('#desc3').html('' + response.data.desc3 + ': ' + response.data.value3 + 'g');
			$('#desc4').html('' + response.data.desc4 + ': ' + response.data.value4 + 'g');
			$('#desc5').html('' + response.data.desc5 + ': ' + response.data.value5 + 'g');
			$('#desc6').html('' + response.data.desc6 + ': ' + response.data.value6 + 'g');
			$('#desc7').html('' + response.data.desc7 + ': ' + response.data.value7 + 'g');
			$('#desc8').html('' + response.data.desc8 + ': ' + response.data.value8 + 'g');
			$('#desc9').html('' + response.data.desc9 + ': ' + response.data.value9 + 'g');
			$('#desc10').html('' + response.data.desc10 + ': ' + response.data.value10 + 'g');
			$('#desc11').html('' + response.data.desc11 + ': ' + response.data.value11 + 'g');
			$('#desc12').html('' + response.data.desc12 + ': ' + response.data.value12 + 'g');
			$('#desc13').html('' + response.data.desc13 + ': ' + response.data.value13 + 'g');
			$('#desc14').html('' + response.data.desc14 + ': ' + response.data.value14 + 'g');
			$('#desc15').html('' + response.data.desc15 + ': ' + response.data.value15 + 'g');
			$('#image1').html('<img src="../qblimages/' + response.data.image1 + '" style="max-width:150px" />');
			$('#image2').html('<img src="../qblimages/' + response.data.image2 + '" style="max-width:150px" />');
			$('#image3').html('<img src="../qblimages/' + response.data.image3 + '" style="max-width:150px" />');
			$('#image4').html('<img src="../qblimages/' + response.data.image4 + '" style="max-width:150px" />');
			$('#image5').html('<img src="../qblimages/' + response.data.image5 + '" style="max-width:150px" />');
			$('#image6').html('<img src="../qblimages/' + response.data.image6 + '" style="max-width:150px" />');
			$('#image7').html('<img src="../qblimages/' + response.data.image7 + '" style="max-width:150px" />');
			$('#image8').html('<img src="../qblimages/' + response.data.image8 + '" style="max-width:150px" />');
			$('#image9').html('<img src="../qblimages/' + response.data.image9 + '" style="max-width:150px" />');
			$('#image10').html('<img src="../qblimages/' + response.data.image10 + '" style="max-width:150px" />');
			$('#image11').html('<img src="../qblimages/' + response.data.image11 + '" style="max-width:150px" />');
			$('#image12').html('<img src="../qblimages/' + response.data.image12 + '" style="max-width:150px" />');
			$('#image13').html('<img src="../qblimages/' + response.data.image13 + '" style="max-width:150px" />');
			$('#image14').html('<img src="../qblimages/' + response.data.image14 + '" style="max-width:150px" />');
			$('#image15').html('<img src="../qblimages/' + response.data.image15 + '" style="max-width:150px" />');
			}
			
        })
		  }
	}
    }).find('.modal-content').css({
        'background-color': '#fff',
        'color': '#000',
        'font-size': '16px'
    });
	
}

tj.selectabl = function() {
	var ptselect = $('#ablpatient').val();
		if(ptselect=='1'){
		var pt = 100;
	}else if(ptselect=='2'){
		var pt = 85;
	}else if(ptselect=='3'){
		var pt = 75;
	}else if(ptselect=='4'){
		var pt = 65;
	}else if(ptselect=='5'){
		var pt = 70;
	}else if(ptselect=='6'){
		var pt = 65;
	}else if(ptselect=='7'){
		var pt = 75;
	}else{
		var pt = 0;
	}
	$('#ablabv').val(pt);
	//console.log('ptselect',ptselect);
}

tj.ablupdate = function() {
	var ptselect = $('#ablpatient').val();
	var weight1 = $('#ablweight').val();
	var type = $('#ablweightType').val();
	var ih1 = $('#ablih').val();
	var fh = $('#ablfh').val();
	var abv = $('#ablabv').val();
	
	if(weight1==''){
		var weight = 0;
	}else{
		var weight = weight1;
	}
	if(ih1==''){
		var ih = 0;
	}else{
		var ih = ih1;
	}
	
	
	if(parseInt(abv)>0 && parseInt(weight)>0 && parseInt(ih)>0 && parseInt(fh)>0){
	var ebv = ((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)).toFixed(1);
	var  abl = ((((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)) * (parseFloat(ih)-parseFloat(fh))) / parseFloat(ih)).toFixed(0);
	$('#ablestabv').val(ebv);
	$('#ablabl').val(abl);
	$('#newabl').html('<h4 style="color:red">Est. ABL: ' + abl + 'ml </h4>');
	$('#ABL').modal('hide');
	tj.updateqbl();
	}
	
}

tj.newabl = function() {
	var ptselect = $('#ablpatient').val();
	var weight = $('#ablweight').val();
	var type1 = $('#ablweightType').val();
	var ih = $('#ablih').val();
	var fh = $('#ablfh').val();
	var abv = $('#ablabv').val();
	
	if(type1=="1"){
		var type = 1;
	}else{
		var type = 2.20462;
	}
	
	
	if(parseInt(abv)>0 && parseInt(weight)>0 && parseInt(ih)>0 && parseInt(fh)>0){
	var ebv = ((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)).toFixed(1);
	var  abl = ((((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)) * (parseFloat(ih)-parseFloat(fh))) / parseFloat(ih)).toFixed(0);
	$('#ablestabv').val(ebv);
	$('#ablabl').val(abl);
	}
	
}

tj.updateqbl = function(saved) {
	var logId = $('#logId').val();	
	var q1 = $('#q1').val();
	var q2 = $('#q2').val();
	var q3 = $('#q3').val();
	var q4 = $('#q4').val();
	var q5 = $('#q5').val();
	var q6 = $('#q6').val();
	var q7 = $('#q7').val();
	var q8 = $('#q8').val();
	var q9 = $('#q9').val();
	var q10 = $('#q10').val();
	var q11 = $('#q11').val();
	var q12 = $('#q12').val();
	var q13 = $('#q13').val();
	var q14 = $('#q14').val();
	var q15 = $('#q15').val();
	var val1 = $('#val1').val();
	var deptId = $('#qbldept').val();
	var txtSent = $('#txtSent').val();
	console.log('txtSent :',txtSent);
	var val2 = $('#val2').val();
	var val3 = $('#val3').val();
	var val4 = $('#val4').val();
	var val5 = $('#val5').val();
	var val6 = $('#val6').val();
	var val7 = $('#val7').val();
	var val8 = $('#val8').val();
	var val9 = $('#val9').val();
	var val10 = $('#val10').val();
	var val11 = $('#val11').val();
	var val12 = $('#val12').val();
	var val13 = $('#val13').val();
	var val14 = $('#val14').val();
	var val15 = $('#val15').val();
	var total2 = $('#total1').val();
	var note = $('#qblNote').val();
	
	if(total2==''){
		var total1 = 0;
	}else{
		var total1 = total2;
	}
	if(parseInt(total1)>0){
	var newqb2 = ((parseFloat(total1) - (parseFloat(q1) * parseFloat(val1)) - (parseFloat(q2) * parseFloat(val2)) - (parseFloat(q3) * parseFloat(val3)) - (parseFloat(q4) * parseFloat(val4)) - (parseFloat(q5) * parseFloat(val5)) - (parseFloat(q6) * parseFloat(val6)) - (parseFloat(q7) * parseFloat(val7)) - (parseFloat(q8) * parseFloat(val8)) - (parseFloat(q9) * parseFloat(val9)) - (parseFloat(q10) * parseFloat(val10)) - (parseFloat(q11) * parseFloat(val11)) - (parseFloat(q12) * parseFloat(val12)) - (parseFloat(q13) * parseFloat(val13)) - (parseFloat(q14) * parseFloat(val14)) - (parseFloat(q15) * parseFloat(val15))) * .9465);
	}else{
	var newqb2 = 0;
	}
	var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
	var currentDay = moment().format('YYYY-MM-DD');
	
	var ptselect = $('#ablpatient').val();
	var weight = $('#ablweight').val();
	var type = $('#ablweightType').val();
	var ih = $('#ablih').val();
	var fh = $('#ablfh').val();
	var estabv = $('#ablestabv').val();
	var abl = $('#ablabl').val();
	var abv = $('#ablabv').val();
	
	//if(parseInt(total1)>1500){
	//		$('#txtSent').val('1');
	//		}	
		$.ajax({
            url:'inc/data.php?req=updateqbl',
            data:{
				ptselect: ptselect,
				weight: weight,
				type: type,
				ih: ih,
				fh: fh,
				estabv: estabv,
				abl: abl,
				abv: abv,
                logId: logId,
				q1: q1,
				q2: q2,
				q3: q3,
				q4: q4,
				q5: q5,
				q6: q6,
				q7: q7,
				q8: q8,
				q9: q9,
				q10: q10,
				q11: q11,
				q12: q12,
				q13: q13,
				q14: q14,
				q15: q15,
				total1: total1,
				newqb2: newqb2,
				currentTime: currentTime,
				note: note,
				currentDay: currentDay,
				deptId: deptId,
				txtSent: txtSent
            },
            method:'POST',
            dataType:'json',
            success:function(response) {			
			$('#estqbl').val(newqb2);
			$('#txtSent').val('1');
			if(saved==1){
			bootbox.alert('Record Has Been Saved.');	
			}
			}
			
        })
	
}

tj.plusqbl = function(num) {
	var q = $('#q' + num + '').val();
	var total1 = $('#total1').val();
	var qplus = parseInt(q) +1;
	var saved = 0;
	
	$('#q' + num +'').val(qplus);
	if(parseInt(total1)>0){
	tj.updateqbl(saved);
	}
}

tj.minusqbl = function(num) {
	var q = $('#q' + num + '').val();
	var total1 = $('#total1').val();
	var saved = 0;
	//console.log('total1',total1);
	if(parseInt(q)>0){
	var qplus = parseInt(q) -1;
	$('#q' + num + '').val(qplus);
	}
	
	if(parseInt(total1)>0){
	tj.updateqbl(saved);
	}
}




///////////////
/////////load qbl
tj.qblStartDate = '';
tj.qblEndDate = '';
tj.initializeQBLGrid = function(id) {

    tj.qblTable = $('#qblTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getqbl",
            data: function(d) {
				d.id = '1';
                d.start = tj.qblStartDate;
                d.end = tj.qblEndDate;
            },
            type:"POST"
        },
        "order": [[0,'desc']],
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
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "date" },
            { "data": "unit" },
			{ "data": "qbl" },
			{ "data": "weight" },
			{ "data": "abl" },
			{ "data": "note" }
        ],
    } );


tj.setUserDatesQBL = function() {
	var startuser = tj.qblStartDate;
	var enduser = tj.qblEndDate;
    $.ajax({
        url:'inc/data.php?req=setdates',
        data: {
				start: startuser,
                end: enduser
		},
        method:'POST',
        dataType:'json',
        success:function(response) {
			//console.log('start',startuser);
			//console.log('end',enduser);
        }
    })
}
tj.ablselect = function() {
	var ptselect = $('#abpatient').val();
	
	if(ptselect=="1"){
		var pt = 100;
	}else if(ptselect=="2"){
		var pt = 85;
	}else if(ptselect=="3"){
		var pt = 75;
	}else if(ptselect=="4"){
		var pt = 65;
	}else if(ptselect=="5"){
		var pt = 70;
	}else if(ptselect=="6"){
		var pt = 65;
	}else if(ptselect=="7"){
		var pt = 75;
	}else{
		var pt = 0;
	}
	$('#abv').val(pt);
	//console.log('ptselect',ptselect);
}

tj.updateabl = function() {
	var ptselect = $('#ablpatient').val();
	var weight = $('#ablweight').val();
	var type1 = $('#ablweightType').val();
	var ih = $('#ablih').val();
	var fh = $('#ablfh').val();
	if(type1=="1"){
		var type = 1;
	}else{
		var type = 2.20462;
	}
	var abv = $('#ablabv').val();
	//console.log('abv',abv);
	//console.log('weight',weight);
	//console.log('ih',ih);
	//console.log('fh',fh);
	//console.log('type',type);
	
	//ABL = weight (kg) × age_sex_factor × [initial_hemoglobin (g/dL) − final_hemoglobin (g/dL)] / initial_hemoglobin (g/dL)
	
	if(parseInt(abv)>0 && parseInt(weight)>0 && parseInt(ih)>0 && parseInt(fh)>=0){
	var ebv = ((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)).toFixed(1);
	var  abl = (((((parseFloat(weight) / parseFloat(type)) * parseFloat(abv)) * (parseFloat(ih)-parseFloat(fh))) / parseFloat(ih)) * -1).toFixed(0);
	$('#ablestabv').val(ebv);
	$('#ablabl').val(abl);
	$('#newabl').html(abl + 'ml');
	$('#ABL').modal('hide');
	}
}

tj.qgenerateQRCode = function(logix2,id){
	document.getElementById("qqrcodename").innerHTML = "";
	document.getElementById("qqrcode").innerHTML = "";
	document.getElementById("qcloseQR").style.display='';
	//console.log('logix2', logix2);
	//var logix = parseInt(logix2).toFixed(0);
	var qqrcodelink = 'https://productivern.com/go/indexx.php?m=' + logix2 + '&p=' + id + '';
	//$('#qrcodename').html('QR Code for: ' + response.data.practiceName);
	new QRCode(document.getElementById("qqrcode"), qqrcodelink);
	
};

tj.qcloseQR = function(){
	document.getElementById("qqrcodename").innerHTML = "";
	document.getElementById("qqrcode").innerHTML = "";
	document.getElementById("qcloseQR").style.display='none';
};
}


tj.initializePoliciesGrid = function(id) {
    //tj.escalationsStartDate = moment().format('YYYY-MM-DD');
    //tj.escalationsEndDate = moment().format('YYYY-MM-DD');
    tj.policiesTable = $('#policiesTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getPolicies",
            data: function(d) {
				d.id = tj.policiesId;
            },
            type:"POST"
        },
        "pageLength": 25,
        "order": [0,'asc'],
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "title" },
            { "data": "created" },
			{ "data": "doc" },
			{ "data": "check1" }
        ],

    } );
	
tj.showPolicy = function (dataId) {
	//console.log('id ',dataId);
        $.ajax({
            url: 'inc/data.php?req=getPolicy',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				console.log('title ',response.data.policyModalTitle);
				$('#policyModalTitle').html(response.data.policyModalTitle);
				$('#policyContent').text(response.data.policyContent);
                $('#policyModal').modal('show');
            }
        });
}

	
/*
tj.showPolicy = function(id) {
      $.post('inc/data.php', { req: 'getPolicy', id: id }, function(res) {
          $('#policyModalTitle').text(res.title);
          $('#policyContent').text(res.content);
          $('#policyModal').modal('show');
      }, 'json');
  };
  
  */

  tj.copyPolicy = function() {
      var text = $('#policyContent').text();
      navigator.clipboard.writeText(text).then(function() {
          var btn = $('[onclick="tj.copyPolicy()"]');
          btn.text('Copied!');
          setTimeout(function() { btn.text('Copy All'); }, 2000);
      });
  };
}


/////////////////////////////////////
///CSV MAPPING

tj.initializeCSVGrid = function() {
    tj.csvTable = $('#csvTable').DataTable( {
        "ajax": {
            url:"inc/reports.php?req=getcsvtable",
            data: {},
            type:"POST"
        },
        //"order": [[0,'desc']],
		"pageLength": 100,
		"scrollY": '500px',
        processing: true,
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "column" },
			{ "data": "unit" },
			{ "data": "desc" }
        ],
		"dom": '<"html5buttons"B>flTgt<"row"<"col-xl-12="i><"col-xl-12"p>>',
         "buttons": [
                {extend: 'pdf', 
				//title: 'User Performance' + '\n' + tj.performanceStartDate + ' through ' + tj.performanceEndDate,
				title: 'CSV Mapping',
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
		"pageLength": 100,
		"scrollY": '500px',
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
			{ "data": "unit" },
            { "data": "date" },
			{ "data": "user" },
			{ "data": "type" },
			{ "data": "notes" },
			{ "data": "style" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [5] }
                     ]
		/*
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
		*/
    } );
	

tj.clearEsc = function(escId) {
		bootbox.confirm({
        message:"Delete this record?  Only use this to delete Test or Erroneous Records.",
		backdrop:true,
        callback:function (result) {
		if (result) {
		$.ajax({
            url:'inc/data.php?req=clearEscalation',
            data:{
                escId: escId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
			tj.escalationsTable.ajax.reload();	
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
  
  
 tj.getEscalationtable = function (dataId) {
		console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getEscalation',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				$('#dataIdedittable').val(response.data.id);
				$('#edittypetable').val(response.data.escalationName);
				$('#editunittable').val(response.data.dept);
				$('#editSubmittedtable').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dateSubmitted);
				$('#escalationcommentedittable').val(response.data.note);
				$('#escalationresponsetable').val(response.data.response);
                $('#editEscalationtable').modal('show');
            }
        });
}
	
tj.updateEscalationtable = function () {
        var dataId = $('#dataIdedittable').val();
		var response = $('#escalationresponsetable').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		
        $.ajax({
            url: 'inc/data.php?req=updateEscalation',
            data: {
                dataId: dataId,
				response: response,
				currentTime: currentTime
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Response Successfully Submitted.');
				document.getElementById("dataIdedittable").value = "";
				document.getElementById("escalationresponsetable").value = "";
                $('#editEscalationtable').modal('hide');
				tj.escalationsTable.ajax.reload(null,false);
            }
        });
}

tj.getEsc = function (dataId) {
		//console.log('dataId',dataId);
        $.ajax({
            url: 'inc/data.php?req=getEscalation',
            data: {
                dataId: dataId
            },
            method: 'POST',
            dataType: 'json',
            success: function (response) {
				if (response.data.active==1){
				document.getElementById("closeesc").checked = false;
				}else{
				document.getElementById("closeesc").checked = true;	
				}
				$('#dataId2').val(response.data.id);
				$('#esctype').val(response.data.escalationName);
				$('#escDept').val(response.data.dept);
				$('#escSubmit').html('Submitted By: ' + response.data.first_name + ' ' + response.data.last_name + ' on ' + response.data.dateSubmitted);
				$('#escalationcomment').val(response.data.note);
				$('#escResp').val(response.data.response);
                $('#editEsc').modal('show');
            }
        });
}

tj.updateEscalation2 = function () {
        var dataId = $('#dataId2').val();
		var response = $('#escResp1').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var active = $('#closeesc').is(':checked') ? 2 : 1;
		
        $.ajax({
            url: 'inc/data.php?req=updateEscalation',
            data: {
                dataId: dataId,
				response: response,
				currentTime: currentTime,
				active: active
            },
            method: 'POST',
            dataType: 'json',
            success: function (result) {
				bootbox.alert('Escalation Response Successfully Submitted.');
				document.getElementById("dataId2").value = "";
				document.getElementById("escResp1").value = "";
				tj.escalationsTable.ajax.reload();
                $('#editEsc').modal('hide');
            }
        });
}

}
  
/////////////////////////////////////
// ESCALATION GLOBALS
//tj.escalationsID = '';


tj.dayRankStartDate = '';
tj.dayRankEndDate = '';
tj.initializedayRankGrid = function() {
    tj.dayRankStartDate = moment().format('YYYY-MM-DD');
    tj.dayRankEndDate = moment().format('YYYY-MM-DD');
    tj.dayRankTable = $('#dayRankTable').DataTable( {
        "ajax": {
            url:"inc/data.php?req=getdayRank",
            data: function(d) {
				d.id = tj.dayRankId;
                d.start = tj.dayRankStartDate;
                d.end = tj.dayRankEndDate;
            },
            type:"POST"
        },
        "order": [[1,'desc'],[0,'asc']],
        processing: true,
		"pageLength": 100,
		"scrollY": '500px',
        "language": {
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
        },
        "columns": [
            { "data": "unit" },
			{ "data": "date" },
			{ "data": "user" },
			{ "data": "dayRank" },
			{ "data": "engage" },
			{ "data": "engageAvg" },
			{ "data": "action" }
        ],
		"columnDefs": [
                       { "visible": false, "targets": [3] }
                     ]
    } );
	
/////////////////////////////////////
// GET DAY RATING DATA

tj.getdayRank = function(dataId) {
		//$('#addProd').modal({backdrop: 'static', keyboard: false})
        console.log('id:', dataId);
		$('#dayRankUpdate').html('');
		$('#dayRankUser').html('');
        $.ajax({
            url:'inc/data.php?req=getdayRankdetails',
            data:{
                dataId: dataId
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#dayRankUnit').html(response.data.dept+' - '+response.data.dayRankDate);
				//$('#dayRankDate').html('Date: '+response.data.dayRankDate);
					
				if(response.data.dayRank !='0'){
				$('#dayRankUpdate').html('Last Update: '+response.data.submittedDate);				
				$('#dayRankUser').html('Updated By: '+response.data.first_name+' '+response.data.last_name);
				}
				$('#edayRank').val(response.data.dayRank);
				$('#empEng').val(response.data.empEngagement);
				$('#dayRankId').val(response.data.id);
				$('#dateExistingdr').val(response.data.dateExisting);
				$('#dateNewdr').val(response.data.dateNew);
				$('#empEng').val(response.data.empEngagement);
				$('#dayReferralsdr').val(response.data.referrals);
				
				//$('#dataId2_add').html('<a href="/view.php?i='+response.data.dataId2+'">Print View</a>');
				//$('#prodTable').DataTable().search('').draw();
				$('#editdayRank').modal('show');
				}
        })
        
  }
  
tj.editdayRank = function() {
		var dataId = $('#dayRankId').val();
		var sd = $('#edayRank').val();
		var ee = $('#empEng').val();
		var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');
		var dateNew = $('#dateNewdr').val();
		var dateExisting = $('#dateExistingdr').val();
		var referrals = $('#dayReferralsdr').val();
		console.log('dataid',dataId);
		console.log('date',dateNew);
		
        $.ajax({
            url:'inc/data.php?req=editdayRank',
            data:{
                dataId: dataId,
				sd: sd,
				ee: ee,
				currentTime: currentTime,
				dateNew: dateNew,
				dateExisting: dateExisting,
				referrals: referrals
            },
            method:'POST',
            dataType:'json',
            success:function(response) {
				$('#editdayRank').modal('hide');
				tj.dayRankTable.ajax.reload();
				bootbox.alert('Record Successfully Updated.');
				}
        })
        
  }


}
	

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.staffingdaterangepickerInit = function(startPay,endPay,role,startdiff,enddiff,escId) {
    if ($('#staffing_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#staffing_daterangepicker');
    //var start = moment().subtract('days', 29);
	
	if(startdiff && enddiff){
	var start = moment().subtract(startdiff, 'days');
    var end = moment().subtract(enddiff, 'days');
	}else{
	var start = moment();
    var end = moment();
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
       
        tj.prodStartDate = start.format('YYYY-MM-DD');
        tj.prodEndDate = end.format('YYYY-MM-DD');
		

		//document.getElementById("startDate").value = tj.prodStartDate;
		//document.getElementById("endDate").value = tj.prodEndDate;
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
		//$("#startDate").val(tj.prodStartDate);
		//$("#endDate").val(tj.prodEndDate);
		
		tj.setUserDates();
		
		tj.prodTable.ajax.reload(null,false);
		if(escId){
		tj.getEscalation(escId);	
		}

    }
	
	if(role>4 && ((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0))){
		
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        }
    }, cb);
	}else if(role>4){
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        }
    }, cb);
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
		showCustomRangeLabel: false,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        }
    }, cb);
	}
    cb(start, end, '');
	
};

// INITIALIZE THE DATE RANGE PICKER
tj.safetydaterangepickerInit = function(startPay,endPay,role) {
    if ($('#safety_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#safety_daterangepicker');
    var start = moment();
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
        if (label == sameDay(start.toDate(),end.toDate())) {
            title = 'Active:';
            range = start.format('MMM D');
        } else {
            range = start.format('MMM D') + ' - ' + end.format('MMM D');
        }

        picker.find('.m-subheader__daterange-date').html(range);
        picker.find('.m-subheader__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.safetyStartDate = start.format('YYYY-MM-DD');
        tj.safetyEndDate = end.format('YYYY-MM-DD');
		//tj.safetyTime = moment().format('HH:mm');
		//tj.safetyHour = moment().format('HH');
		
		tj.setSafetyDates();
		tj.safetyTable.ajax.reload(null,false);
       

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if(role>4 && ((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0))){
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Active': [moment(), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else if(role>4){
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Active': [moment(), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Open Safety Issues': [moment(), moment()],
			'Last 7 Days (ALL)': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days (ALL)': [moment().subtract(29, 'days'), moment()],
        }
    }, cb);
	}

    cb(start, end, '');
};

// INITIALIZE THE DATE RANGE PICKER
tj.tasksdaterangepickerInit = function(startPay,endPay,role) {
    if ($('#tasks_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#tasks_daterangepicker');
    var start = moment();
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
        if (label == sameDay(start.toDate(),end.toDate())) {
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
        tj.tasksStartDate = start.format('YYYY-MM-DD');
        tj.tasksEndDate = end.format('YYYY-MM-DD');
		tj.tasksTime = moment().format('HH:mm');
		tj.tasksHour = moment().format('HH');
		
		tj.setUserDatesTasks();
		tj.crashTable.ajax.reload(null,false);
        //tj.complianceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if(role>4 && ((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0))){
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else if(role>4){
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}else{
    picker.daterangepicker({
        startDate: start,
        endDate: end,
		showCustomRangeLabel: false,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        }
    }, cb);
	}

    cb(start, end, '');
};

// INITIALIZE THE DATE RANGE PICKER
tj.qbldaterangepickerInit = function(startPay,endPay) {
    if ($('#qbl_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#qbl_daterangepicker');
	var start = moment().subtract(6, 'days');
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
/*
    function cb(start, end, label) {
        var title = '';
        var range = '';
        if (label == sameDay(start.toDate(),end.toDate())) {
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
	*/
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
        tj.qblStartDate = start.format('YYYY-MM-DD');
        tj.qblEndDate = end.format('YYYY-MM-DD');
		tj.qblTime = moment().format('HH:mm');
		//console.log('time',tj.tasksTime);
		tj.setUserDatesQBL();
		tj.qblTable.ajax.reload(null,false);
        //tj.complianceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}

    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.customdaterangepickerInit = function(startPay,endPay) {
    if ($('#custom_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#custom_daterangepicker');
    var start = moment();
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
        tj.customStartDate = start.format('YYYY-MM-DD');
        tj.customEndDate = end.format('YYYY-MM-DD');
		tj.setUserDatesCustom();
        //tj.complianceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);
	}

    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER DAY RANK
tj.dayRankdaterangepickerInit = function() {
    if ($('#dayRank_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#dayRank_daterangepicker');
    var start = moment();
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

        picker.find('.m-dayRank__daterange-date').html(range);
        picker.find('.m-dayRank__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.dayRankStartDate = start.format('YYYY-MM-DD');
        tj.dayRankEndDate = end.format('YYYY-MM-DD');
		//tj.setUserDates();
        tj.dayRankTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	picker.daterangepicker({
		startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			//'Current Pay Period': [moment().subtract(startPay, 'days'), moment().add(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);

    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.staffingdaterangepickerInitwhp = function(startPay,endPay,role,startdiff,enddiff,escId) {
    if ($('#staffing_daterangepickerwhp').length == 0) {
        return;
    }

    var picker = $('#staffing_daterangepickerwhp');
	//var newStart = tj.prodStartwhp;
	//var newEnd = tj.prodEndwhp;
    //var start = moment().subtract('days', 29);
	
	if(startdiff && enddiff){
	var start = moment().subtract(startdiff, 'days');
    var end = moment().subtract(enddiff, 'days');
	}else{
	var start = moment();
    var end = moment();
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
		tj.setUserDateswhp();
        tj.prodTablewhp.ajax.reload();
		if(escId){
		tj.getEscalationwhp(escId);	
		}
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
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
        }
    }, cb);
	}
    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.staffingdaterangepickerInitcli = function(startPay,endPay,role,startdiff,enddiff,escId) {
    if ($('#staffing_daterangepickercli').length == 0) {
        return;
    }

    var picker = $('#staffing_daterangepickercli');
	//var newStart = tj.prodStartwhp;
	//var newEnd = tj.prodEndwhp;
    //var start = moment().subtract('days', 29);
	
	if(startdiff && enddiff){
	var start = moment().subtract(startdiff, 'days');
    var end = moment().subtract(enddiff, 'days');
	}else{
	var start = moment();
    var end = moment();
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

        picker.find('.m-cli__daterange-date').html(range);
        picker.find('.m-cli__daterange-title').html(title);
        //if (tj.debug) {
        //    console.log(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        //}
        tj.prodStartDatecli = start.format('YYYY-MM-DD');
        tj.prodEndDatecli = end.format('YYYY-MM-DD');
		tj.setUserDatescli();
        tj.prodTablecli.ajax.reload();
		if(escId){
		tj.getEscalationcli(escId);	
		}
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }
	
	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
	picker.daterangepicker({
        startDate: start,
        endDate: end,
		opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Next 7 Days': [moment().add(1, 'days'), moment().add(7, 'days')],
			//'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
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
tj.chartsdaterangepickerInit = function() {
    if ($('#charts_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#charts_daterangepicker');
    var startPay = moment().subtract(29, 'days');
	//var start = moment();
    var endPay = moment();

    //tj.reportDates = {
    //    start:start,
    //    end:end
    //}

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
		tj.chartsStartDate = start.format('YYYY-MM-DD');
        tj.chartsEndDate = end.format('YYYY-MM-DD');
		//tj.setchartsDates();
        tj.chartsStartDate = start.format('YYYY-MM-DD');
        tj.chartsEndDate = end.format('YYYY-MM-DD');
        //tj.charts2();

    }

	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			
			
        }
    }, cb);
	}else{
	 picker.daterangepicker({
        startDate: startPay,
        endDate: endPay,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			
        }
    }, cb);	
	}
    cb(startPay, endPay, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.performancedaterangepickerInit = function(startPay,endPay) {
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
		tj.perfStartDate = start.format('YYYY-MM-DD');
        tj.perfEndDate = end.format('YYYY-MM-DD');
		tj.setPerfDates();
        tj.performanceStartDate = start.format('YYYY-MM-DD');
        tj.performanceEndDate = end.format('YYYY-MM-DD');
        tj.performanceTable.ajax.reload();
        //tj.updateReports(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));

    }

	//if((startPay>0 && endPay>=0) || (startPay>=0 && endPay>0)){
	if((startPay>=0 && endPay<0) || (startPay>0 && endPay<=0)){
    picker.daterangepicker({
        startDate: start,
        endDate: end,
        opens: 'left',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Current Pay Period': [moment().subtract(startPay, 'days'), moment().subtract(endPay, 'days')],
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			
			
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
            //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            //'This Month': [moment().startOf('month'), moment().endOf('month')],
            //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
			
        }
    }, cb);	
	}
    cb(start, end, '');
};

	/////////////////////////////////////
// INITIALIZE THE DATE RANGE PICKER
tj.viewlogdaterangepickerInit = function() {
    if ($('#viewlog_daterangepicker').length == 0) {
        return;
    }

    var picker = $('#viewlog_daterangepicker');
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
        tj.viewlogStartDate = start.format('YYYY-MM-DD');
        tj.viewlogEndDate = end.format('YYYY-MM-DD');
        tj.viewlogTable.ajax.reload();
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
		case "#csvMap":
            //if (tj.debug) console.log('csvmap');
            tj.loadCSV();
            break;
		case "#reportView":
            //if (tj.debug) console.log('prod');
            tj.loadReportView();
            break;
		case "#qrView":
            //if (tj.debug) console.log('prod');
            tj.loadQRview();
            break;
		case "#huddleView":
            //if (tj.debug) console.log('prod');
            tj.loadHuddleview();
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
		case "#ops":
            //if (tj.debug) console.log('prod');
            tj.loadClinics();
            break;
		case "#logs":
            //if (tj.debug) console.log('prod');
            tj.loadCrash();
            break;
		case "#msg":
            //if (tj.debug) console.log('prod');
            tj.loadMessages();
            break;
		case "#safety":
            //if (tj.debug) console.log('prod');
            tj.loadSafety();
            break;
		case "#qblLog":
            //if (tj.debug) console.log('prod');
            tj.loadQBL();
            break;
		case "#qblNow":
            //if (tj.debug) console.log('prod');
            tj.loadqblNow();
            break;
		case "#ablNow":
            //if (tj.debug) console.log('prod');
            tj.loadablNow();
            break;
		case "#performance":
            tj.loadPerformance();
            break;
		case "#viewlog":
            tj.viewLog();
            break;
		case "#escalations":
            //if (tj.debug) console.log('escalations');
            tj.loadEscalations();
            break;
		case "#policies":
            //if (tj.debug) console.log('escalations');
            tj.loadPolicies();
            break;
		case "#dayRankPage":
            //if (tj.debug) console.log('escalations');
            tj.loadDayRank();
            break;
		case "#compliance":
            //if (tj.debug) console.log('escalations');
            tj.loadCompliance();
            break;
		case "#customreports":
            //if (tj.debug) console.log('escalations');
            tj.loadCustom();
            break;
		case "#chart":
            //if (tj.debug) console.log('escalations');
            tj.loadCharts();
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
		case "#tasks":
            //if (tj.debug) console.log('units');
            tj.loadTasks();
            break;
		case "#qrcodes":
            //if (tj.debug) console.log('units');
            tj.loadQRcodes();
            break;
		case "#concierge":
            //if (tj.debug) console.log('units');
            tj.loadConcierge();
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
		document.getElementById("customValhide").style.display='none';
		document.getElementById("inshiftview").style.display='none';
		document.getElementById("inshiftviewwhp").style.display='none';
		document.getElementById("gvarView").style.display='none';		
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
				var a6 = document.getElementById('hiddenwhp6');
				
				var a11 = document.getElementById('viewWHP');
				
				var n1 = document.getElementById('hidden1view');
				var n2 = document.getElementById('hidden2view');	
				var n3 = document.getElementById('hidden3view');	
				var n4 = document.getElementById('hidden4view');	
				var n5 = document.getElementById('hidden5view');	
				var n6 = document.getElementById('hidden6view');
				var n7 = document.getElementById('churnView');
				var n8 = document.getElementById('viewNurse');
				//var n9 = document.getElementById('customcount');
				//var n10 = document.getElementById('inshiftview');
				var n11 = document.getElementById('hidden7view');
				var n12 = document.getElementById('hidden8view');
				var n13 = document.getElementById('viewchurn');
				
				var n14 = document.getElementById('hidden9view');
				var n15 = document.getElementById('hidden10view');
				var n16 = document.getElementById('inshiftview');
				var a7 = document.getElementById('hiddenwhp7');	
				var a8 = document.getElementById('hiddenwhp8');	
				var a9 = document.getElementById('hiddenwhp9');	
				var a10 = document.getElementById('hiddenwhp10');					
				var n17 = document.getElementById('customValhide');
				
				var n18 = document.getElementById('other1report');
				var n19 = document.getElementById('other2report');
				var n20 = document.getElementById('other3report');
				var n21 = document.getElementById('customNurse2');
				var n22 = document.getElementById('secreport');
				var n23 = document.getElementById('nursereport');
				var n24 = document.getElementById('techreport');
				var n25 = document.getElementById('customnurse1');
				var n26 = document.getElementById('sitterreport');

				var n31 = document.getElementById('hidden7viewwhp');
				var n32 = document.getElementById('hidden8viewwhp');
				var n34 = document.getElementById('hidden9viewwhp');
				var n35 = document.getElementById('hidden10viewwhp');
				var n36 = document.getElementById('inshiftviewwhp');
				
				if(response.data.prodMeasure==2 || response.data.prodMeasure==3){
					
				if (response.data.track1Desc.length==0) {
				n31.style.display='none';
				}else{
				n31.style.display='';
				n36.style.display='';
				}
				if (response.data.track2Desc.length==0) {
				n32.style.display='none';
				}else{
				n32.style.display='';
				n36.style.display='';
				}
				if (response.data.track3Desc.length==0) {
				n34.style.display='none';
				}else{
				n34.style.display='';
				n36.style.display='';
				}
				if (response.data.track4Desc.length==0) {
				n35.style.display='none';
				}else{
				n35.style.display='';
				n36.style.display='';
				}
				
				a11.style.display='';
				n8.style.display='none';
				if (response.data.customWHP ==1) {
				n17.style.display='';
				}else{
				n17.style.display='none';
				}
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
				if (response.data.skill6 ==1) {
				a6.style.display='';
				}else{
				a6.style.display='none';
				}
				if (response.data.skill7 ==1) {
				a7.style.display='';
				}else{
				a7.style.display='none';
				}
				if (response.data.skill8 ==1) {
				a8.style.display='';
				}else{
				a8.style.display='none';
				}
				if (response.data.skill9 ==1) {
				a9.style.display='';
				}else{
				a9.style.display='none';
				}
				if (response.data.skill10 ==1) {
				a10.style.display='';
				}else{
				a10.style.display='none';
				}
				if (response.data.whpPlan ==1) {
				$('#hourswhp').html('Planned Hours');
				$('#uoswhp').html('Planned Units of Service');
				$('#reportType').html(' (Planned)');
				}
				if (response.data.whpPlan ==0) {
				$('#hourswhp').html('Staff / Hours');
				$('#uoswhp').html('Units of Service');
				$('#reportType').html(' (Actual)');
				}
				if (response.data.budgetMeasure ==1) {
				$('#budgetwhpVal').html('Budget: ' + response.data.budgetVal + '%');
				}
				if (response.data.escalationValue !=0) {
				$('#escalationwhpVal').html('Escalation: ' + response.data.escalationType + ', ' + response.data.escalationNote);
				//document.getElementById('escStyle').setAttribute("style", "color:red;");
				}
				if(response.data.practice==1){
				$('#updatedwhpBy').html(response.data.submittedby);
				}else{
				$('#updatedwhpBy').html(response.data.first_name+' '+response.data.last_name);	
				}
				if (response.data.displayProd ==1){
				$('#hourswhpVariance').html(response.data.currentVar + ' Hrs');
				}else if(response.data.displayProd ==0){
				$('#hourswhpVariance').html(response.data.nvariance + ' RNs');
				}else if(response.data.displayProd ==5){
				$('#hourswhpVariance').html(response.data.gvariance+ ' FTE');
				}else{
				$('#hourswhpVariance').html(response.data.nvariance1);				
				}
				$('#updatedwhpDate').html(response.data.reportdate);
				$('#updatedwhpentered').html(response.data.entered);
				$('#depname').html(response.data.depname);
				$('#updatdwhpShift').html(response.data.reportshift);
				//$('#shiftWHP').val(response.data.shift);
				$('#actualwhpUOS').html(response.data.actualWHP);
				$('#targetwhpUOS').html(response.data.hppd);
				//$('#hourswhpVariance').html(response.data.todayVar);
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
				$('#skilldsc5').html(response.data.skilldesc5);
				$('#skillwhp6').val(response.data.skill6val);
				$('#skillwhp7').val(response.data.skill7val);
				$('#skillwhp8').val(response.data.skill8val);
				$('#skillwhp9').val(response.data.skill9val);
				$('#skillwhp10').val(response.data.skill10val);
				$('#skilldsc6').html(response.data.skilldesc6);
				$('#skilldsc7').html(response.data.skilldesc7);
				$('#skilldsc8').html(response.data.skilldesc8);
				$('#skilldsc9').html(response.data.skilldesc9);
				$('#skilldsc10').html(response.data.skilldesc10);
				$('#trackDesc1whp').html(response.data.track1Desc);
				$('#trackDesc2whp').html(response.data.track2Desc);
				$('#trackDesc3whp').html(response.data.track3Desc);
				$('#trackDesc4whp').html(response.data.track4Desc);
				$('#track1Viewwhp').val(response.data.track1);
				$('#track2Viewwhp').val(response.data.track2);
				$('#track3Viewwhp').val(response.data.track3);
				$('#track4Viewwhp').val(response.data.track4);
				
				$('#customvalView').val(response.data.whpCustom);
				$('#customvalDesc').html(response.data.customDesc);
				
				$('#totaluosWHP').val(response.data.procedureCount);
				$('#dataidwhp').html('<a href="../w.php?i='+response.data.id+'">Print View</a>');
				$('#whpnote').html(response.data.note);	
				}else{
				a11.style.display='none';
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
				if (response.data.acuity9 !=0) {
				n14.style.display='';
				}else{
				n14.style.display='none';
				}
				if (response.data.acuity10 !=0) {
				n15.style.display='';
				}else{
				n15.style.display='none';
				}
				
				if (response.data.track1Desc.length==0) {
				n11.style.display='none';
				}else{
				n11.style.display='';
				n16.style.display='';
				}
				if (response.data.track2Desc.length==0) {
				n12.style.display='none';
				}else{
				n12.style.display='';
				n16.style.display='';
				}
				if (response.data.track3Desc.length==0) {
				n14.style.display='none';
				}else{
				n14.style.display='';
				n16.style.display='';
				}
				if (response.data.track4Desc.length==0) {
				n15.style.display='none';
				}else{
				n15.style.display='';
				n16.style.display='';
				}
				
			
				if (response.data.churn ==1) {
				n7.style.display='none';
				n13.style.display='none';
				}else{
				n7.style.display='none';
				n13.style.display='none';
				}
				if (response.data.other1Label==0) {
				n18.style.display='none';
				}else{
				n18.style.display='';
				}
				
				if (response.data.other2Label==0) {
				n19.style.display='none';
				}else{
				n19.style.display='';
				}
				if (response.data.other3Label==0) {
				n20.style.display='none';
				}else{
				n20.style.display='';
				}
				if (response.data.nurse2Label==0) {
				n21.style.display='none';
				}else{
				n21.style.display='';
				}
				if (response.data.sLabel==0) {
				n22.style.display='none';
				}else{
				n22.style.display='';
				}
				if (response.data.nurseLabel==0) {
				n23.style.display='none';
				}else{
				n23.style.display='';
				}
				if (response.data.tLabel==0) {
				n24.style.display='none';
				}else{
				n24.style.display='';
				}
				if (response.data.nurse1Label==0) {
				n25.style.display='none';
				}else{
				n25.style.display='';
				}
				if (response.data.sittersLabel==0) {
				n26.style.display='none';
				}else{
				n26.style.display='';
				}
				
				if (response.data.escalationValue !=0) {
				$('#escalationView').html('Escalation: ' + response.data.escalationType + ', ' + response.data.escalationNote);
				}
				if (response.data.blockedBeds !=0) {
				$('#blockedView').html('Blocked Beds: ' + response.data.blockedBeds);
				}
				
				//if (response.data.inshiftProd ==1) {
				//n10.style.display='';
				//}	
				if(response.data.displayProd==0){
				$('#varianceView').html(response.data.roundnvariance+' RNs');
				}else{
				$('#varianceView').html(response.data.hrsVariance+' hours');
				}
				
				if(response.data.practiceId==1){
				$('#updatedbyView').html(response.data.submittedby+'('+response.data.submitCount+')');
				}else{
				$('#updatedbyView').html(response.data.first_name+' '+response.data.last_name+'('+response.data.submitCount+')');	
				}
				$('#varianceView').html(response.data.roundnvariance+' RNs');
				$('#gridvarianceView').html(response.data.gvariance);
				$('#other1Label').html(response.data.other1Label);
				$('#other2Label').html(response.data.other2Label);
				$('#other3Label').html(response.data.other3Label);
				$('#dateView').html(response.data.reportdate);
				$('#nursecountDesc').html(response.data.nurseDesc);
				$('#sittercountDesc').html(response.data.sittersNEWDesc);
				$('#shiftView').html(response.data.reportshift);
				$('#enteredView').html(response.data.entered);
				$('#depnameView').html(response.data.depname);
				$('#custom1Label').html(response.data.nurse1Desc);
				$('#custom2Label').html(response.data.nurse2Desc);
				//$('#custom2Nursevar').html(' (0)');
				$('#secLabelView').html(response.data.secLabel);
				$('#techLabelView').html(response.data.techLabel);
				$('#churnVal').html(response.data.churnValue);
				$('#openbedsView').html(response.data.openbeds);
				$('#totalpatientsView').val(response.data.totalpatients);
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
				$('#trackDesc1').html(response.data.track1Desc);
				$('#trackDesc2').html(response.data.track2Desc);
				$('#trackDesc3').html(response.data.track3Desc);
				$('#trackDesc4').html(response.data.track4Desc);
				$('#track1View').val(response.data.track1);
				$('#track2View').val(response.data.track2);
				$('#track3View').val(response.data.track3);
				$('#track4View').val(response.data.track4);
				$('#descView5').html(response.data.desc5);
				$('#descView6').html(response.data.desc6);
				$('#admissionsView').val(response.data.admits);
				$('#transfersView').val(response.data.transfers);
				$('#dischargesView').val(response.data.discharges);
				$('#prodnoteView').html(response.data.note);
				if(response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4){
				document.getElementById("gvarView").style.display='';
				}			
				
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.cnvar) !=0){
				//console.log('cnvar',parseInt(response.data.cnvar));
				var cnvar = ' (' + response.data.cnvar + ')';
				$('#chargecountView').val(response.data.chargecount1 + cnvar);
				}else{
				$('#chargecountView').val(response.data.chargecount1);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.pctvar) !=0){
				var pctvar = ' (' + response.data.pctvar + ')';
				$('#techcountView').val(response.data.techcount + pctvar);
				}else{
				$('#techcountView').val(response.data.techcount);
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.sitvar) !=0){
				var sitvar = ' (' + response.data.sitvar + ')';
				$('#otherView').val(response.data.sittercount + sitvar);
				}else{
				$('#otherView').val(response.data.sittercount);
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.secvar) !=0){
				var secvar = ' (' + response.data.secvar + ')';
				$('#seccountView').val(response.data.seccount + secvar);
				}else{
				$('#seccountView').val(response.data.seccount);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.rnvar) !=0){
				var rnvar = ' (' + response.data.rnvar + ')';
				$('#nursecountView').val(response.data.antecount + rnvar);
				}else{
				$('#nursecountView').val(response.data.antecount);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.rn1var) !=0){
				var rn1var = ' (' + response.data.rn1var + ')';
				$('#custom1nurse').val(response.data.customNurse + rn1var);
				}else{
				$('#custom1nurse').val(response.data.customNurse);
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.rn2var) !=0){
				var rn2var = ' (' + response.data.rn2var + ')';
				$('#custom2Nurse').val(response.data.customNurse2 + rn2var);
				}else{
				$('#custom2Nurse').val(response.data.customNurse2);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.other1var) !=0){
				var other1var = ' (' + response.data.other1var + ')';
				$('#other1Nurse').val(response.data.otherNurse1 + other1var);
				}else{
				$('#other1Nurse').val(response.data.otherNurse1);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.other2var) !=0){
				var other2var = ' (' + response.data.other2var + ')';
				$('#other2Nurse').val(response.data.otherNurse2 + other2var);
				}else{
				$('#other2Nurse').val(response.data.otherNurse2);	
				}
				if((response.data.useGrid==1 || response.data.useGrid==2 || response.data.useGrid==4) && parseInt(response.data.other3var) !=0){
				var other3var = ' (' + response.data.other3var + ')';
				$('#other3Nurse').val(response.data.otherNurse3 + other3var);
				}else{
				$('#other3Nurse').val(response.data.otherNurse3);	
				}
				
				}
				$('#reportView').show();
				tj.logView(dataId);
				}
        })
        //console.log('record updated sucessfully',dataId);
		
  }

tj.logView = function(dataId){
	var currentTime = moment().format('YYYY-MM-DD HH:mm:ss');

  $.ajax({
        url: 'inc/data.php?req=logview',
        dataType: 'json',
        method: 'post',
        data: {
          dataId: dataId,
		  currentTime: currentTime
        },
        success: function(data) {
		tj.complianceLoaded = false;
        }
});


};  

//////////////////Load Training Videos
/*
tj.vids = function (vids) {
	//console.log('vids',vids);
	
	if(vids==1){
    OpusWidget.init({
      "client_id": "",
      "secret_key": "",
      "api_url": "https://api.internal.opuseps.com/api/v1/documents",
      "css_url": "https://d12q80fbvoyl73.cloudfront.net/widget.css",
      "targetApp_url": "https://api.internal.opuseps.com/v2/target_applications",
	  //"targetApp_url": "",
      "user_name": "",
      "key_word": "",
      "token_url": "https://api.internal.opuseps.com/api/tokenauth/token",
      "source_name": "opus",
      "location": "lowerright",
      "tokenserver": {
        "proxy_url": "",
        "proxy_method": "GET",
        "postData": "",
        "headers": [{
           "name": "",
           "value": ""
        }],
      }
    });
	}
};
*/
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
        data: {
          type: type,
          name: name,
          phone: phone,
          email: email,
          message: message
        },
		method:'POST',
        dataType:'json',
        success: function() {
			$('#supportModal').modal('hide');
          bootbox.alert('Thank you! We will be in contact within 24hrs.');
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
			console.log('dateE', response.data.dateEntered);
			console.log('datec', response.data.reportDate);
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
            "processing": 'Loading...<br /><img src="images/ajax-loader.gif" />' //add a loading image,simply putting <img src="loader.gif" /> tag.
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
			console.log('classId', classId);
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
	console.log('class',classId);
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
			console.log('update made');
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
	console.log('classId', classId);
	
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