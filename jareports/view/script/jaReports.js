var jaReports = {};
 jaReports.chartType = 'column';
 jaReports.days = 90;
 jaReports.brands = '';
 
 jaReports.selectBrand = function(id, brand){
	 jaReports.brands = id;
	 $('#brandComboButton').text(brand + '  ');
	 $('#brandComboButton').append('<span class="caret"></span>');
	 
	 jaReports.getData(jaReports.brands, jaReports.days);
 };
 
 jaReports.selectDays = function(days){
	 jaReports.days = days;	 
	 jaReports.getData(jaReports.brands, jaReports.days);
 };
 
 jaReports.populateBrandCombo = function(data){

	 var wrapper = $('#brandComboWrapper');
	 wrapper.empty();
	 
	 var length = data.length;
	 var temp;
	 temp = '';
	var items = [];
	var ids = [];
		items.push(temp);
		
	 for(var i = 0; i < length; i++){
		ids.push(data[i].id);
		temp = '';
		temp += '<li><a href="#" onclick="jaReports.selectBrand('+ data[i].id +', \''+ data[i].storeBrand.replace('\'', '\\\'') +'\');return false;">';
			temp += data[i].storeBrand;
		temp += '</a></li>';
		items.push(temp);
	 }
	 temp = '';
	 temp += '<li><a href="#" onclick="jaReports.selectBrand(\''+ ids.join() +'\', \'All\');return false;">';
			temp += 'All';
		temp += '</a></li>';
	 items[0] = temp;
	 
	 wrapper.append(items);
	 jaReports.brands = ids.join();
	 jaReports.getData(jaReports.brands, jaReports.days);
	 
	 //<li><a href="#">All</a></li>
 };
 
 jaReports.getBrands = function(){

	$.ajax({
    type: "GET",
    url: '../model/sms_summary.php',
    data: {
			xaction: 'getBrands'
	 
			},
    success: function(data, status, xhr) {
		data = JSON.parse(data);

		jaReports.populateBrandCombo(data.data);
    }
    });
};

jaReports.incomingChartConstructor = function(data){
	var smsCandidates = 0;
	var totalCandidates = 0;
	var smsReplies = 0;
	var totalSms = 0;
	var cats;
	var dat;
	if(jaReports.chartType == 'column'){		
		var length = data.data.length;
		for(var i = 0; i < length; i++){
			if(data.data[i].type == 0){
				smsCandidates = smsCandidates + data.data[i].typeCount * 1;
			}
			totalCandidates = totalCandidates + data.data[i].typeCount * 1;
		}
		
		length = data.data1.length;
		for(var i = 0; i < length; i++){
			if(data.data1[i].type == 0){
				smsReplies = smsReplies + data.data1[i].typeCount * 1;
				totalSms = totalSms + data.data1[i].typeCount * 1;
			}else if(data.data1[i].type == 8){
				totalSms = totalSms + data.data1[i].typeCount * 1;
			}
		}
		cats = ['Total SMS', 'SMS Replies', 'Total Candidates', 'SMS Candidates'];
		dat = [totalSms, smsReplies, totalCandidates, smsCandidates];
		
	}else if(jaReports.chartType = 'line'){
		cats = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	}
	
	
	 $('#incomingChartContainer').highcharts({
        chart: {
            type:  jaReports.chartType
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: cats
        },
        yAxis: {
            title: {
                text: 'Number'
            }
        },
		legend: {
        		enabled: false
        },
		credits: {
            enabled: false
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '',
           data: dat
        }]
    });
};

jaReports.outgoingChartConstructor = function(data){
	var total = 0;
	var jobs = 0;
	var marketing = 0;
	
	var length = data.data1.length;
		for(var i = 0; i < length; i++){
			if(data.data1[i].type == 1){
				jobs = jobs + data.data1[i].typeCount * 1;
				total = total +  data.data1[i].typeCount * 1;
			}else if(data.data1[i].type == 2){
				marketing = market + data.data1[i].typeCount * 1;
				total = total +  data.data1[i].typeCount * 1; 
			}else if(data.data1[i].type == 9){
				total = total +  data.data1[i].typeCount * 1;
			}
		}
		
		var cats = ['Total SMS', 'Job Messages', 'Mktg Messages'];
		var dat = [total, jobs, marketing];
	
  $('#outgoingChartContainer').highcharts({
        chart: {
            type: jaReports.chartType
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: cats
        },
        yAxis: {
            title: {
                text: ''
            }
        },
		legend: {
        		enabled: false
        },
		credits: {
            enabled: false
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: '',
            data: dat
        }]
    });
};



jaReports.getData = function(id, days){

	$.ajax({
    type: "GET",
    url: '../model/sms_summary.php',
    data: {
			//xaction: 'getIncomingData',
			xaction: 'getDataForRange',
			brandId: id,
			days: days
	 
			},
    success: function(data, status, xhr) {
		data = JSON.parse(data);
    	jaReports.incomingChartConstructor(data);
		jaReports.outgoingChartConstructor(data);
    }
    });
};

// jaReports.getOutgoingData = function(){


	// $.ajax({
    // type: "GET",
    // url: '../model/sms_summary.php',
    // data: {
			// xaction: 'getOutgoingData'
	 
			// },
    // success: function(data, status, xhr) {
////alex override for test data until actual data populates
    	// data = [320, 325, 345, 340, 360, 350, 360, 340, 330, 335, 330, 350];
    	
    	// jaReports.outgoingChartConstructor(data);
    // }
    // });
// };

jaReports.initialize = function(){
	$('#chartDays7Button').click(function(){
		jaReports.selectDays(7);
	});
	$('#chartDays30Button').click(function(){
		jaReports.selectDays(30);
	});
	$('#chartDays90Button').click(function(){
		jaReports.selectDays(90);
	});
	 jaReports.getBrands();	
};



$(document).ready(function() {  
  jaReports.initialize();  
});