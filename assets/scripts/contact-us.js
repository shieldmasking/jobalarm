var ContactUs = function () {

    return {
        //main function to initiate the module
        init: function () {
			var map;
			$(document).ready(function(){
			  map = new GMaps({
				div: '#map',
	            lat: 33.099659,
				lng: -96.825246,
			  });
			   var marker = map.addMarker({
		            lat: 33.099659,
					lng: -96.825246,
		            title: 'Premier SSG, Inc.',
		            infoWindow: {
		                content: "<b>Premier SSG, Inc.</b> 2591 Dallas Parkway, Suite 300<br>Frisco, TX  75034"
		            }
		        });

			   marker.infoWindow.open(map, marker);
			});
        }
    };

}();