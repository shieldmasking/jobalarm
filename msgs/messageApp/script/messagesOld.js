var messages = {};
messages.conversationUser = false;

messages.conversation = function(data){
	messages.buildTextBox();
	$('.footer').height('23vh');
	$('.bodyWrapper').height('63vh');
	$('.footer').show();
  $('#mainConent').empty();
	$('#backButtonWrapper').remove();

	var length = data.length;
	var html;

	html = '';
	html += '<div id="backButtonWrapper" style="float: left; margin-top: 5px;margin-left: 4vw;">';
			html += '<button id="conversationBackButton" style="font-size: 3vh;" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span></button>';
	html += '</div>';
	//html += '</div>';

	$('#logoHolder').prepend(html);

	$('#conversationBackButton').click(messages.initialize);

	var name;
	for(i = 0; i < length; i++){
		if(data[i].last_name == null){
			if(data[i].mobile == null){
				name = 'No Name or Number in System';
			}else{
				name = data[i].mobile;
			}
		}else if(data[i].last_name.length == 0){
			name = data[i].mobile;
		}else{
			name = data[i].last_name + ', ' + data[i].first_name;
		}
		html = '';
		html += '<div class="row"  style="font-size: 2.5vh;">';
		if(data[i].type == 3){
			html += '<div class="col-md-6 col-md-offset-4 col-xs-10">';
				html += '<div class="panel panel-info pull-left">';
				html += '<div class="panel-heading">';
					html += name + ' - ' + data[i].MsgDate;
				html += '</div>';
		}else{
			html += '<div class="col-md-6 col-md-offset-1 col-xs-10 col-xs-offset-2">';
				html += '<div class="panel panel-success pull-right">';
				html += '<div class="panel-heading">';
					html += data[i].MsgDate;
				html += '</div>';
		}






					html += '<div class="panel-body">';
							html += data[i].message;
					 html += '</div>';
				html += '</div>';
			html += '</div>';
		html += '</div>';


		$('#mainConent').append(html);


	}


};

	messages.conversationHandler = function(){
		var candidateId = $(this)[0].id;
		messages.getConversation(candidateId);
	};

	messages.getConversation = function(candidateId){
		$.ajax({
 	 	url: "../messageApp/messageController.php",
 	 	 data: {
 	 	 	xaction: 'getConversation',
			candidateId: candidateId
 	 	 } ,
 	 	success: function(res){
 	 		var res = JSON.parse(res);
         	var data = res.data;
					console.log(data);

					messages.conversationUser = {};
					messages.conversationUser.mobile = data[0].mobile;
					messages.conversationUser.keyword = data[0].keyword;
					messages.conversationUser.brandId = data[0].brandId;
					messages.conversationUser.candidateId = data[0].candidateId;
					messages.conversation(data);
     	}
    	});
	};

messages.buildConversations = function(data){
	$('#mainConent').empty();
	var length = data.length;
	var html;


	var name;
	for(i = 0; i < length; i++){
		if(data[i].last_name == null){
			if(data[i].mobile == null){
				name = 'No Name or Number in System';
			}else{
				name = data[i].mobile;
			}
		}else if(data[i].last_name.length == 0){
			name = data[i].mobile;
		}else{
			name = data[i].last_name + ', ' + data[i].first_name;
		}
		html = '';
		html += '<div class="row"  style="font-size: 2.5vh;">';
			html += '<div class="col-md-6 col-md-offset-3">';


				html += '<div class="panel panel-info" id="'+data[i].candidateId+'">';

					html += '<div class="panel-heading">';
						html += name;
						html += '    ';
						html += data[i].lastDate;
					html += '</div>';
					html += '<div class="panel-body">';
				    	html += data[i].msg;
				 	 html += '</div>';
				html += '</div>';
			html += '</div>';
		html += '</div>';


		$('#mainConent').append(html);

		$('#' + data[i].candidateId).click(messages.conversationHandler);
	}



};

messages.getMessages = function(){
	messages.conversationUser = false;
	 $.ajax({
	 	url: "../messageApp/messageController.php",
	 	 data: {
	 	 	xaction: 'getMessages'
	 	 } ,
	 	success: function(res){
	 		var res = JSON.parse(res);
        	var data = res.data;
					$('#backButtonWrapper').remove();;
					$('.footer').hide();
					$('#textBoxRow').remove();
					$('.footer').height('0vh');
					$('.bodyWrapper').height('88vh');

        	messages.buildConversations(data);
    	}
   	});
};

messages.sendMessage = function(){
	var textField = $('#messageText');
	var message = textField.val();

	if(message.length < 1){
		return;
	}

	$.ajax({
	 url: "../messageApp/messageController.php",
		data: {
		 xaction: 'sendTextMessage',
		 message: message,
		 mobile: messages.conversationUser.mobile,
		 keyword: messages.conversationUser.keyword,
		 brandId: messages.conversationUser.brandId,
		 candidateId: messages.conversationUser.candidateId
		 //mobile: '21418500163',
		 //keyword: 'dollar',
		 //brandId: '27',
		 //candidateId: '1'

		} ,
	 success: function(res){
		 		textField.val('');
				var data = JSON.parse(res);
				if(data.success != true){
					console.log('return out here later');
				}
				messages.getConversation(messages.conversationUser.candidateId);
		 }
	 });

};

messages.buildTextBox = function(){
	var html = '';

	html += '<div id="textBoxRow" class="row" style="height:24vh;width:99%;margin-top: 4px;margin-left: 2vw;">';
		html += '<div class="col-md-10 col-xs-12 col-md-offset-2" style="height:16vh;">';
			html += '<textarea class="form-control" style="background-color: #F2FAFF; height:16vh; width:100%; resize: none;margin-top: 4px;" id="messageText"></textarea>';
		html += '</div>';
		html += '<div class="col-md-12 col-xs-12" style="height:8vh;margin-bottom: 5px;">';
			html += '<button id="sendMessageButton" type="button" class="btn btn-info" style="margin-top: 2vh; width:98%; font-size: 3vh;">SEND</button>';
		html += '</div>';
	html += '</div>';

	$('.footer').append(html);

	$('#sendMessageButton').off().click(messages.sendMessage);
};

messages.initialize = function(){
	messages.conversationUser = false;
	messages.getMessages();

};








$(document).ready(function() {
  messages.initialize();
});
