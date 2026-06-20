var messages = {};
messages.conversationUser = false;

messages.conversation = function(data){
	messages.buildTextBox();
	//$('.footer').height('23vh');
	//$('.bodyWrapper').height('63vh');
	$('.send').show();
  $('#mainConent').empty();
	//$('#backButtonWrapper').remove();

	var length = data.length;
	var html;

	//html = '';
	//html += '<div id="backButtonWrapper" style="float: left; margin-top: 5px;margin-left: 4vw;">';
	//		html += '<button id="conversationBackButton" style="font-size: 3vh;" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span></button>';
	//html += '</div>';
	//html += '</div>';

	//$('#logoHolder').prepend(html);

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
		if(data[i].type == 3){
		html += '<div class="message">';
		html += '<div class="message-body">';
		html += '<div class="message-row">';
		html += '<div class="d-flex align-items-center">';
		html += '<div class="message-content bg-light">';
		html += '<h6 class="mb-2">' + name + '</h6>';
		html += data[i].message;
		html += '<div class="mt-1"><small class="opacity-65">' + data[i].MsgDate + '</small>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		}else{
		html += '<div class="message message-right">';
		html += '<div class="message-body">';
		html += '<div class="message-row">';
		html += '<div class="d-flex align-items-center justify-content-end">';
		html += '<div class="message-content bg-primary text-white">';
		html += data[i].message;
		html += '<div class="mt-1"><small class="opacity-65">' + data[i].MsgDate + '</small>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		html += '</div>';
		}


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
		//html += '<a class="text-reset nav-link p-0 mb-6" href="index.html?i='+data[i].candidateId+'">';
		html += '<a class="text-reset nav-link p-0 mb-6" onclick="messages.getConversation('+data[i].candidateId+')">';
			html += '<div class="card card-active-listener">';
			html += '<div class="card-body">';
			html += '<div class="media">';
			html += '<div class="avatar mr-5">';
			html += '<img class="avatar-img" src="assets/images/avatars/11.jpg" alt="Bootstrap Themes">';
			html += '</div>';
			html += '<div class="media-body overflow-hidden">';
			html += '<div class="d-flex align-items-center mb-1">';


				html += '<h6 class="text-truncate mb-0 mr-auto">'+name+'</h6>';

					html += '<p class="small text-muted text-nowrap ml-4">'+data[i].msgDate+'</p>';
						
					html += '</div>';
					html += '<div class="text-truncate">'+data[i].message+'</div>';
				 	 html += '</div>';
				html += '</div>';
			html += '</div>';
		html += '</div>';
		html += '</a>';


		$('#mainConv').append(html);
		

		//$('#' + data[i].candidateId).click(messages.conversationHandler);
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
					//$('#backButtonWrapper').remove();;
					$('.send').hide();
					//$('#textBoxRow').remove();
					$('.send').height('0vh');
					//$('.bodyWrapper').height('88vh');

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
	html += '<form id="chat-form" data-emoji-form="">';
	html += '<div class="form-row align-items-center">';
	html += '<div class="col">';
	html += '<div class="input-group">';
	html += '<textarea id="messageText" class="form-control bg-transparent border-0" placeholder="Type your message..." rows="1" data-emoji-input="" data-autosize="true"></textarea>';
	html += '<div class="input-group-append">';
	html += '<button class="btn btn-ico btn-secondary btn-minimal bg-transparent border-0" type="button" data-emoji-btn="">';
	html += '<img src="assets/images/smile.svg" data-inject-svg="" alt="">';
	html += '</button>';
	html += '</div>';
	html += '</div>';
	html += '</div>';
	html += '<div class="col-auto">';
	html += '<button id="sendMessageButton" type="button" class="btn btn-info" style="margin-top: 2vh; width:98%; font-size: 3vh;">SEND</button>';
	//html += '<span class="fe-send"></span>';
	html += '</button>';
	html += '</div>';
	html += '</div>';
	html += '</form>';
	
	//html += '<div id="textBoxRow" class="row" style="height:24vh;width:99%;margin-top: 4px;margin-left: 2vw;">';
	//	html += '<div class="col-md-10 col-xs-12 col-md-offset-2" style="height:16vh;">';
	//		html += '<textarea class="form-control" style="background-color: #F2FAFF; height:16vh; width:100%; resize: none;margin-top: 4px;" id="messageText"></textarea>';
	//	html += '</div>';
	//	html += '<div class="col-md-12 col-xs-12" style="height:8vh;margin-bottom: 5px;">';
	//		html += '<button id="sendMessageButton" type="button" class="btn btn-info" style="margin-top: 2vh; width:98%; font-size: 3vh;">SEND</button>';
	//	html += '</div>';
	//html += '</div>';

	$('.send').append(html);

	$('#sendMessageButton').off().click(messages.sendMessage);
};

messages.initialize = function(){
	messages.conversationUser = false;
	messages.getMessages();

};







$(document).ready(function() {
  messages.initialize();
});
