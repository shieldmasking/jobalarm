<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
            body {
                font-family:'Arial', sans-serif;
                margin:0;
                padding:0;
            }
            .header {
                margin:10px auto;
                text-align:center;
                font-size:20px;
                font-weight:bold;
            }
            .smsinfo {
                margin:10px auto;
                text-align:left;
                font-size:14px;
                font-weight:bold;
                width:400px;
            }
            .smsinfo input.num{
                width:80px;
            }
            .smsinfo input.key{
                width:50px;
            }
            .receiver {
                margin:5px auto;
                width:400px;
                height:300px;
                border:1px solid #333;
                overflow-y:scroll;
            }
            .receiver .message {
                width:100%;
                overflow:hidden;
            }
            .receiver .timestampleft {
                float:left;
                font-size:10px;
            }
            .receiver .timestampright {
                float:right;
                font-size:10px;
            }
            .receiver .message .sent {
                float:left;
                min-width:115px;
                max-width:350px;
                margin-top:8px;
                padding:5px;
                border:1px solid #0a0;
                border-left:0;
                background-color:#efe;
                font-size:14px;
                color:#000;
            }
            .receiver .message .received {
                float:right;
                min-width:115px;
                max-width:350px;
                margin-top:8px;
                padding:5px;
                border:1px solid #00f;
                border-right:0;
                background-color:#eef;
                font-size:14px;
                color:#000;
            }
            .sender {
                width:400px;
                height:24px;
                margin:0 auto;
            }
            .sender input[type=text] {
                width:265px;
                height:24px;
                border:1px solid #333;
            }
            .sender button {
                height:30px;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
            $('document').ready(function(){
                function scrollToBottom() {
                    $("#smsbody").animate({ scrollTop: $('#smsbody')[0].scrollHeight}, 1000);                 
                }
                function getTimestamp() {
                    var d = new Date();

                    var month = d.getMonth()+1;
                    var day = d.getDate();
                    var hour = d.getHours();
                    var minute = d.getMinutes();
                    var second = d.getSeconds();

                    var output = d.getFullYear() + '-' +
                        ((''+month).length<2 ? '0' : '') + month + '-' +
                        ((''+day).length<2 ? '0' : '') + day + ' ' +
                        ((''+hour).length<2 ? '0' :'') + hour + ':' +
                        ((''+minute).length<2 ? '0' :'') + minute + ':' +
                        ((''+second).length<2 ? '0' :'') + second;                    
                    return output;
                }
                $('#sendbtn').click(function(){
                    var keyURL = '';
                    var replyURL = '';
                    var usekey = $('#usekey').is(':checked');
                    if (usekey) {
                        keyURL = 'http://wu4.local/sms/receive/'+$('#keyword').val();
                    } 
                    replyURL = 'http://wu4.local/sms/receive?reply=1';
                    
                    var datetime = getTimestamp();
                    var sendmsg = $('#sendmsg').val();
                    if (sendmsg.length > 0) {
                        //keyURL += '&from='+$('#smsnumber').val()+'&message='+sendmsg;
                        //replyURL += '&from='+$('#smsnumber').val()+'&message='+sendmsg;
                        $('#smsbody').append('<div class="message"><div class="sent">'+sendmsg+'</div></div><div class="timestampleft">Sent: '+datetime+'</div>');
                        $('#sendmsg').val('');
                        
                        scrollToBottom();
                        if (usekey) {
                            $.ajax({
                                url: keyURL,
                                method: 'POST',
                                data: {
                                    from: $('#smsnumber').val(),
                                    message:sendmsg
                                },
                                context: document.body
                              }).done(function(response) {
                                $('#smsbody').append('<div class="message"><div class="received">'+response+'</div></div><div class="timestampright">Rcvd: '+datetime+'</div>');
                                scrollToBottom();  
                                $('#usekey').prop('checked',false);
                                $.ajax({
                                    url: replyURL,
                                    method: 'POST',
                                    data: {
                                        from: $('#smsnumber').val(),
                                        message: sendmsg
                                    },
                                    context: document.body
                                  }).done(function(response) {
                                  });
                              });
                        } else {
                            $.ajax({
                                url: replyURL,
                                method: 'POST',
                                data: {
                                    from: $('#smsnumber').val(),
                                    message: sendmsg
                                },
                                context: document.body
                              }).done(function(response) {
                                $('#smsbody').append('<div class="message"><div class="received">'+response+'</div></div><div class="timestampright">Rcvd: '+datetime+'</div>');
                                scrollToBottom();                            
                              });
                        }
                        
                    }
                });                
                $('#cancelbtn').click(function(){
                    $('#sendmsg').val('');
                });   
                $("#sendmsg").keyup(function(event){
                    if(event.keyCode == 13){
                        $("#sendbtn").click();
                    }
                });                
            });
        </script>
    </head>
    <body>
        <div class="header">WalkupScreener SMS Tester</div>
        <div class="smsinfo">Your Number (10 digits): <input class="num" type="text" id="smsnumber" /> Key: <input type="checkbox" id="usekey" /> <input class="key" type="text" id="keyword" /></div>
        <div id="smsbody" class="receiver"></div>
        
        <div class="sender">
            <input type="text" id="sendmsg" />
            <button type="button" id="sendbtn">Send</button>
            <button type="button" id="cancelbtn">Cancel</button>
        </div>
    </body>
</html>