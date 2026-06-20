var tj = tj || {};


/////////////////////////////////////
// FORGOT PASSWORD

tj.forgot = function() {
   var email = $('#forgotemail').val();
   $.ajax({
       url:'../inc/logindata.php?req=forgot',
       data: {
          email:email
       },
       success:function(response) {
		   $('#forgot').modal('hide');
		   alert('Please check your email for your temporary password.');
		   
		}
   })
}

tj.forgotPrivate = function() {
   var email = $('#forgotemail').val();
   $.ajax({
       url:'../inc/logindata.php?req=forgot',
       data: {
          email:email
       },
       success:function(response) {
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
       url:'inc/logindata.php?req=forgot',
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
    
    
}(this));