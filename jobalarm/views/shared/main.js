var wus = wus || {};

wus.SelectedResponse = 0;

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function getDocHeight() {
    var D = document;
    return Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
}

function toTitleCase(str) {
    return str.replace(/\w\S*/g, function (txt) { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); });
}

wus.resizeWindow = function () {
    $('#masterContent').css('height', $(window).height());
    $('#masterContent').css('width', $(window).width());
    $('#chatContainer').css('height', $(window).height());
    $('#chatContainer').css('width', $(window).width());
    $('.chatContent').css('height', $(window).height());
    $('.chatSegment').css('height', $(window).height());
};

wus.toggleState = function (obj) {
    var target = $(obj).parent().parent().parent().parent();
    if (target.css('bottom') == '-275px') {
        //target.css('bottom', 0);
        target.animate({
            bottom: "0px"
        }, 200);
        target.find('.minBtn').removeClass('fa-sort-desc');
        target.find('.minBtn').addClass('fa-sort-asc');
    } else {
        //        target.css('bottom', -275);
        target.animate({
            bottom: "-275px"
        }, 200);
        target.find('.minBtn').removeClass('fa-sort-asc');
        target.find('.minBtn').addClass('fa-sort-desc');
    }
};

wus.hideChat = function (obj) {
    var target = $(obj).parent().parent().parent().parent();
    target.animate({
        opacity: 0
    },
    200,
    function () {
        target.remove();
    });
};

wus.createSMSChatWindow = function (username, mobileNum) {
    mobileNum = Math.floor(100000000 + Math.random() * 900000000);
    if (!$('#chat' + mobileNum).length) {
        var ChatDiv = $(''+
            '<div class="chatSegment" id="chat'+mobileNum+'">' +
            '    <div class="chatBox">' +
            '        <div class="chatHeader">'+
            '            <div class="chatHeaderTitle">'+
            '                '+username+' ('+mobileNum+')'+
            '            </div>'+
            '            <div class="chatHeaderSystem">' +
            '                <a href="javascript:;" onclick="wus.toggleState(this);" class="minBtn fa fa-sort-asc"></a>' +
            '                <a href="javascript:;" onclick="wus.hideChat(this);" class="closeBtn fa fa-power-off"></a>' +
            '            </div>'+
            '        </div>' +
            '        <div class="chatBody" id="chatBody'+mobileNum+'">'+
            '        </div>' +
            '    </div>' +
            '</div>');
        //ChatDiv.fadeIn();
        $('.chatContent').append(ChatDiv);
        var chatContent = '' +
            '<input class="sendText" type="text" name="textToSend" />';
        $('#chatBody' + mobileNum).w2layout({
            height:200,
            name:'chatBodyContents'+mobileNum,
            panels: [
                { type: 'top', content: 'Chat Body', size: 225, style:'1px solid #ccc' },
                { type: 'main', content: chatContent, style:'border: 1px solid #ccc' }
            ]
        });
        $('.sendText').focus(function () {
            $(this).parent().parent().parent().parent().parent().find('.chatHeader').addClass('chatActive');
        });
        $('.sendText').blur(function () {
            $(this).parent().parent().parent().parent().parent().find('.chatHeader').removeClass('chatActive');
        });
        wus.resizeWindow();
    }
};

$(function () {
    wus.resizeWindow();
    $(window).resize(wus.resizeWindow);
    wus.msg = function (message,callBack) {
        w2popup.message({
            width: 300,
            height: 100,
            html: '<center><br /><br />'+message+'<br /><br /><button style="width:60px" onclick="w2popup.message();">Ok</button></center>',
            onClose: function () {
                if (typeof callBack == 'function') callBack();
            }
        });
    };
    wus.alert = function (message, title, callBack) {
        w2popup.open({
            title: title,
            body: '<center><div style="margin-top:50px">'+message+'</div></center>',
            buttons: '<input type="button" value="Ok" onclick="w2popup.close();" /> ',
            width: 300,
            height: 200,
            overflow: 'hidden',
            color: '#333',
            speed: '0.3',
            opacity: '0.8',
            modal: true,
            showClose: true,
            onClose: function (event) {
                if (typeof callBack == 'function') callBack();
            }
        });
    };
});