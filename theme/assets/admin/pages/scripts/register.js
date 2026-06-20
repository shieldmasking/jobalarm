var ContactUs = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#map',
				lat: 32.9757955,
				lng: -96.828075
			  });
			   var marker = map.addMarker({
		            lat: 32.9757955,
					lng: -96.828075,
		            title: 'Premier SSG',
		            infoWindow: {
		                content: "<b>Premier SSG, Inc.</b> 2591 Dallas Parkway, Suite 300 Frisco, TX 75034"
		            }
		        });

			   marker.infoWindow.open(map, marker);
			});

$("#contact").submit(function(e){
  e.preventDefault();
  var name = $("#contact_name").val();
  var email = $("#contact_email").val();
  var company = $("#contact_company").val();
  var mobile = $("#contact_phone").val();
  var dataString = 'name=' + name + '&email=' + email + '&mobile=' + mobile + '&company='+company;
  function isValidEmail(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
  };
 
  if (isValidEmail(email) && (text.length < 100) && (name.length > 1)){
    $.ajax({
    type: "POST",
    url: "specials.php",
    data: dataString,
    success: function(){
    	$("#submit_btn").fadeOut(250);
      $('.success').fadeIn(1000);
    }
    });
  } else{
    $('.error').fadeIn(1000);
  }
 
  return false;
});

        }
    };

}();