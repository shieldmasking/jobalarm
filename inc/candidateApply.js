var apply = {};


apply.updateCandidate = function(){
  $('#applyModalSubmitButton').click(function(){
    apply.submitUpdate();
  });
  $('#applyModalSubmitButton2').click(function(){
    apply.submitUpdate2();
  });
  $('#applyModalSubmitButton3').click(function(){
    apply.submitUpdate3();
  });
  $('#applyModalSubmitButton4').click(function(){
    apply.submitUpdate4();
  });
  $('#liButton').click(function(){
    apply.submitUpdate5();
  });
  $('#clericalButton').click(function(){
    apply.submitUpdate6();
  });
  $('#medicalButton').click(function(){
    apply.submitUpdate7();
  });
  $('#legalButton').click(function(){
    apply.submitUpdate8();
  });
  $('#proButton').click(function(){
    apply.submitUpdate9();
  });
};

/*apply.updateCandidate2 = function(){
  $('#applyModalSubmitButton2').click(function(){
    apply.submitUpdate2();
  });
};
apply.updateCandidate3 = function(){
  $('#applyModalSubmitButton3').click(function(){
    apply.submitUpdate3();
  });
};
apply.updateCandidate4 = function(){
  $('#applyModalSubmitButton4').click(function(){
    apply.submitUpdate4();
  });
};
*/

apply.submitUpdate = function(){
	//var permit = $('#permit').val();
	var permitYes = $('#permitYes').is(':checked');
	var permitNo = $('#permitNo').is(':checked');
	//var aId = $(this).data('id');
	var aId = $('#ageYes')[0].attributes['data-id'].value;

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate: true,
	            aId: aId,
	            permitYes: permitYes,
				permitNo: permitNo
	        },
	        success: function(data) {
	           $('#underAge').modal('toggle');

	        }
	    });

};

apply.submitUpdate2 = function(){
	//var permit = $('#permit').val();
	var current = $('#current').val();
	var howlong = $('#long').val();
	var referenceYes = $('#referenceYes').is(':checked');
	var referenceNo = $('#referenceNo').is(':checked');
	//var currentEmp = $('#currentEmp').is(':checked');
	//var currentEmpNo = $('#currentEmpNo').is(':checked');
	//var aId = $(this).data('id');
	var aId = $('#currentEmpYes')[0].attributes['data-id'].value;

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate2: true,
	            aId: aId,
	            current: current,
				howlong: howlong,
				referenceYes: referenceYes,
				referenceNo: referenceNo
	        },
	        success: function(data) {
	           $('#currentEmpModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate3 = function(){
	//var permit = $('#permit').val();
	var previous = $('#previous').val();
	var pastLong = $('#pastLong').val();
	var pastReferenceYes = $('#pastReferenceYes').is(':checked');
	var pastReferenceNo = $('#pastReferenceNo').is(':checked');
	//var pastEmp = $('#pastEmp').is(':checked');
	//var pastEmpNo = $('#pastEmpNo').is(':checked');
	var aId = $('#pastEmpYes')[0].attributes['data-id'].value;

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate3: true,
	            aId: aId,
	            previous: previous,
				pastLong: pastLong,
				pastReferenceYes: pastReferenceYes,
				pastReferenceNo: pastReferenceNo
	        },
	        success: function(data) {
	           $('#pastEmpModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate4 = function(){
	var e = document.getElementById("prefer1");
	var prefer1 = e.options[e.selectedIndex].text;
	var a = document.getElementById("prefer2");
	var prefer2 = a.options[a.selectedIndex].text;
	var day = $('#day').val();
	//var aId = $(this).data('id');
	var aId = $('#scheduleNo')[0].attributes['data-id'].value;

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate4: true,
	            aId: aId,
	            prefer1: prefer1,
				prefer2: prefer2,
				day: day
	        },
	        success: function(data) {
	           $('#scheduleModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate5 = function(){
	var e = document.getElementById("type3");
	var type5 = e.options[e.selectedIndex].text;
	var aId = $('#liPref')[0].attributes['data-id'].value;
	

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate5: true,
	            aId: aId,
	            type5: type5
	        },
	        success: function(data) {
	           $('#liModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate6 = function(){
	var e = document.getElementById("type4");
	var type5 = e.options[e.selectedIndex].text;
	var aId = $('#clericalPref')[0].attributes['data-id'].value;
	

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate5: true,
	            aId: aId,
	            type5: type5
	        },
	        success: function(data) {
	           $('#clericalModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate7 = function(){
	var e = document.getElementById("type5");
	var type5 = e.options[e.selectedIndex].text;
	var aId = $('#medicalPref')[0].attributes['data-id'].value;
	

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate5: true,
	            aId: aId,
	            type5: type5
	        },
	        success: function(data) {
	           $('#medicalModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate8 = function(){
	var e = document.getElementById("type6");
	var type5 = e.options[e.selectedIndex].text;
	var aId = $('#legalPref')[0].attributes['data-id'].value;
	

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate5: true,
	            aId: aId,
	            type5: type5
	        },
	        success: function(data) {
	           $('#legalModal').modal('toggle');

	        }
	    });

};

apply.submitUpdate9 = function(){
	var e = document.getElementById("type7");
	var type5 = e.options[e.selectedIndex].text;
	var aId = $('#proPref')[0].attributes['data-id'].value;
	

	$.ajax({
	        url: '/app/applyHandler.php',
	        method: 'POST',
	        data: {
				updateCandidate5: true,
	            aId: aId,
	            type5: type5
	        },
	        success: function(data) {
	           $('#proModal').modal('toggle');

	        }
	    });

};
