var currentDialog = {};
var opponentId;

var dialogsMessages = [];

// submit form after press "ENTER"
function submit_handler(form) {
  return false;
}

function setupMsgScrollHandler() {
  var msgWindow = $('.col-md-8 .list-group.pre-scrollable');
  var msgList = $('.messages-list');

  msgList.scroll(function() {

    if (msgWindow.scrollTop() == msgWindow.height() - msgList.height()){
      alert('scroll');
      var dateSent = null;
      if(dialogsMessages.length > 0){
        dateSent = dialogsMessages[0].date_sent;
      }
      retrieveChatMessages(currentDialog, dateSent);
    }
  });
}

function getfilesize(s){
    if(s>1000000){
      var mb = Math.ceil(s/100000);
      return mb/10 + "MB";
    }
    else if(s < 1000000 && s>1000){
      return Math.ceil(s/1000)+"KB";
    }
    else{
      return s + "Bytes";
    }
  }


// on message listener
//
function onMessage(userId, msg) {
  if(JSON.stringify(blocklist).indexOf(userId) >= 0) return;
  if(currentUser_uid.toString() !== "12094756" && currentDialogID.toString() === "57a9eb82a28f9aee4e000010" && userId.toString() !== "12094756" && userId.toString() !== currentUser_uid.toString()) return;

  if(document.title.indexOf('Chat | Relayy') < 0){
    $(".list-group-item").prop("class", "list-group-item inactive");
  }
  // check if it's a mesasges for current dialog
  //
  if (isMessageForCurrentDialog(userId, msg.dialog_id)){
    dialogsMessages.push(msg);

    if (msg.markable === 1) {
      sendReadStatus(userId, msg.id, msg.dialog_id);
    }

    // сheck if it's an attachment
    //
    var messageAttachmentFileId = null;
    var messageAttachmentFileName = "untitled";
    var messageAttachmentFileSize;
    var senderName = getUserLoginById(userId);
    if (msg.extension.hasOwnProperty("attachments")) {
      if(msg.extension.attachments.length > 0) {
        messageAttachmentFileId = msg.extension.attachments[0].id;
        
        QB.content.getInfo(messageAttachmentFileId, function(err, file_info) {
                if (file_info) {
                      messageAttachmentFileName = file_info.blob.name;
                      messageAttachmentFileSize = file_info.blob.size;
                      //AttachmentToken = file_info.blob.id;
                      $('a.'+messageAttachmentFileId).prop("alt", messageAttachmentFileName);
                      $("#"+messageAttachmentFileId).text(messageAttachmentFileName);
                      var siz = getfilesize(messageAttachmentFileSize);
                      if(senderName === currentUser_name){
                        $("p."+messageAttachmentFileId).text("By You");
                        $("p.size_"+messageAttachmentFileId).text(siz);
                      } 
                      else{
                        $("p."+messageAttachmentFileId).text("By " + senderName);
                        $("p.size_"+messageAttachmentFileId).text(siz);
                      } 
                      var spary = messageAttachmentFileName.split(".");
                      var ext = spary[spary.length - 1] ;
                      var imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
                      if(imageExts.indexOf(ext)>=0){
                        $('img.'+messageAttachmentFileId).prop("src", 'http://api.quickblox.com/blobs/'+messageAttachmentFileId+"/download.xml?token="+token);
                      }
                      else if(ext === "pdf"){         
                        $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/pdf.png");
                        $('#attach-'+messageAttachmentFileId).prop("src", site_url + "assets/images/pdf.png");
                      }
                      else if(ext === "gif"){
                        $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/gif.png");
                      }
                      else{
                        $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/file.png");
                      }
                      setTimeout(function(){$('.messages-list').scrollTop($('.messages-list').prop('scrollHeight')); }, 5000);
                } else {
                  // error
                }
              });
      }
    }

    showMessage(userId, msg, messageAttachmentFileId, messageAttachmentFileName, messageAttachmentFileSize);
  }

  

  
  // Here we process the regular messages
  //
  var sendDate = new Date();
  updateDialogsList(msg.dialog_id, getUserLoginById(userId)+": "+msg.body, jQuery.timeago(sendDate), userId);
  updateDialogsDB(msg.dialog_id, getUserIDById(userId), msg.body);

}

function sendReadStatus(userId, messageId, dialogId) {
  var params = {
    messageId: messageId,
    userId: userId,
    dialogId: dialogId
  };
  QB.chat.sendReadStatus(params);
}

function onDeliveredStatusListener(messageId) {
  $('#delivered_'+messageId).fadeIn(200);
}

function onReadStatusListener(messageId) {
  $('#delivered_'+messageId).fadeOut(100);
  $('#read_'+messageId).fadeIn(200);
}

function retrieveChatMessages(dialog, beforeDateSent){
  // Load messages history
  //
  $(".load-msg").show(0);
  if(typeof dialog === "undefined") return;
  var params = {chat_dialog_id: dialog._id,
                     sort_desc: 'date_sent',
                         limit: 30};

  // if we would like to load the previous history
  if(beforeDateSent !== null){
    params.date_sent = {lt: beforeDateSent};
  }else{
    currentDialog = dialog;
    dialogsMessages = [];
  }

  QB.chat.message.list(params, function(err, messages) {
    if (messages) {

      console.log(messages);
      messages.items.forEach(function(item, i, arr) {

            dialogsMessages.splice(0, 0, item);

            var messageId = item._id;
            var messageText = item.message;
            var messageSenderId = item.sender_id;
            var messageDateSent = new Date(item.date_sent*1000);
            var messageSenderLogin = getUserLoginById(messageSenderId);
            var messageSenderPic = getUserPicById(messageSenderId);

            //if block user
            if(JSON.stringify(blocklist).indexOf(messageSenderId) >= 0) return;

            if(currentUser_uid.toString() !== "12094756" && currentDialogID.toString() === "57a9eb82a28f9aee4e000010" && messageSenderId.toString() !== "12094756" && messageSenderId.toString() !== currentUser_uid.toString()) return;

            // send read status
            if (item.read_ids.indexOf(currentUser.id) === -1) {
              sendReadStatus(messageSenderId, messageId, currentDialog._id);
            }
            var messageAttachmentFileId = null;
            var messageAttachmentFileName = "download";
            var messageAttachmentFileSize;
            if (item.hasOwnProperty("attachments")) {
              if(item.attachments.length > 0) {
                messageAttachmentFileId = item.attachments[0].id;
                QB.content.getInfo(messageAttachmentFileId, function(err, file_info) {
                  if (file_info) {
                        messageAttachmentFileName = file_info.blob.name;
                        messageAttachmentFileSize = file_info.blob.size;
                        var siz = getfilesize(messageAttachmentFileSize);
                        $('a.'+messageAttachmentFileId).prop("alt", messageAttachmentFileName);
                        $("#"+messageAttachmentFileId).text(messageAttachmentFileName);
                        if(messageSenderLogin === currentUser_name){
                          $("p."+messageAttachmentFileId).text("By You");
                          $("p.size_"+messageAttachmentFileId).text(siz);
                        } 
                        else{
                          $("p."+messageAttachmentFileId).text("By " + messageSenderLogin);
                          $("p.size_"+messageAttachmentFileId).text(siz);
                        }
                        var spary = messageAttachmentFileName.split(".");
                        var ext = spary[spary.length - 1] ;
                        var imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
                        if(imageExts.indexOf(ext)>=0){
                          $('img.'+messageAttachmentFileId).prop("src", 'http://api.quickblox.com/blobs/'+messageAttachmentFileId+"/download.xml?token="+token);
                        }
                        else if(ext === "pdf"){         
                          $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/pdf.png");
                          $('#attach-'+messageAttachmentFileId).prop("src", site_url + "assets/images/pdf.png");
                        }
                        else if(ext === "gif"){
                          $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/gif.png");
                        }
                        else{
                          $('img.'+messageAttachmentFileId).prop("src", site_url + "assets/images/file.png");
                        }
                        setTimeout(function(){$('.messages-list').scrollTop($('.messages-list').prop('scrollHeight')); }, 5000);

                        

                  } else {
                    // error
                  }
            });
           
          }
        }

        var messageHtml = buildMessageHTML(messageSenderId, messageText, messageSenderLogin, messageSenderPic, messageDateSent, messageAttachmentFileId, messageId, messageAttachmentFileName, messageAttachmentFileSize);

        $('.messages-list').prepend(messageHtml);
     
        // Show delivered statuses
        if (item.read_ids.length > 1 && messageSenderId === currentUser.id) {
          $('#delivered_'+messageId).fadeOut(100);
          $('#read_'+messageId).fadeIn(200);
        } else if (item.delivered_ids.length > 1 && messageSenderId === currentUser.id) {
          $('#delivered_'+messageId).fadeIn(100);
          $('#read_'+messageId).fadeOut(200);
        }

        if (i > 5) {$('.messages-list').scrollTop($('.messages-list').prop('scrollHeight'));}
      });

    }else{
      console.log(err);
    }
  });

  $(".load-msg").delay(100).fadeOut(500);
}


// sending messages after confirmation
function clickSendMessage() {

  var currentText = $('#message_text').val();
  if (currentText.length === 0){
    return;
  }
  
  $('#message_text').val('').focus(); 
  sendMessage(currentText, null);
}

function clickSendAttachments(inputFile) {
  // upload image

  $(".messages-list").append('<div class="row uploading"><p class="pull-right padding_xs">Uploading...</p></div>');
  $('.messages-list').scrollTop($('.messages-list').prop('scrollHeight')); 
  var imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
  var flag = 0;
  for(var i=0;i<imageExts.length;i++){
    if(typeof(inputFile) !== "undefined" && inputFile.type.indexOf(imageExts[i]) > -1){
      flag = 1;
      break;
    } 
  }

  if(flag == 0) {
    alert("The file is not allowed. You can upload png, jpeg, jpg, gif, pdf files only.");
    $(".uploading").hide();
    return;
  }
  QB.content.createAndUpload({name: inputFile.name, file: inputFile, type:
        inputFile.type, size: inputFile.size, 'public': false}, function(err, response){
    if (err) {
      alert(JSON.stringify(err));
    } else {
      $(".uploading").hide();
      var uploadedFile = response;

      sendMessage("[attachment]", uploadedFile.id);

      $("input[type=file]").val('');
    }
  });
}

// send text or attachment
function sendMessage(text, attachmentFileId) {
    
  var msg = {
    type: currentDialog.type === 3 ? 'chat' : 'groupchat',
    body: text,
    extension: {
      save_to_history: 1,
    },
    senderId: currentUser.id,
    markable: 1
  };
  if(attachmentFileId !== null){
    msg["extension"]["attachments"] = [{id: attachmentFileId, type: 'photo'}];
  }

  if (currentDialog.type === 3) {
    opponentId = QB.chat.helpers.getRecipientId(currentDialog.occupants_ids, currentUser.id);
    QB.chat.send(opponentId, msg);

    //$('.list-group-item.active .list-group-item-text').text(msg.body);

    if(attachmentFileId === null){
      showMessage(currentUser.id, msg);
    } else {
      showMessage(currentUser.id, msg, attachmentFileId);
    }
  } else {
    //console.log("### current dialog");
    //console.log(resultStanza); 
    QB.chat.send(currentDialog.xmpp_room_jid, msg);
  }

  // var params = {filter: { field: 'id', param: 'in', value: currentDialog.occupants_ids }};
 
  // QB.users.listUsers(params, function(err, result){
  // if (result) {
  //   alert(result[0].id);
  //   // success
  // } else  {
  //   // error
  // }
  // });

  // claer timer and send 'stop typing' status
  clearTimeout(isTypingTimerId);
  isTypingTimeoutCallback();

  dialogsMessages.push(msg);
}

// show messages in UI
function showMessage(userId, msg, attachmentFileId, AttachmentFileName, AttachmentFileSize) {
  // add a message to list
  if(JSON.stringify(blocklist).indexOf(userId) >= 0) return;
  var userLogin = getUserLoginById(userId);
  var messageSenderPic = getUserPicById(userId);
  var messageHtml = buildMessageHTML(userId, msg.body, userLogin, messageSenderPic, new Date(), attachmentFileId, msg.id, AttachmentFileName, AttachmentFileSize);

  $('.messages-list').append(messageHtml);

  // scroll to bottom
  var mydiv = $('.messages-list');
  mydiv.scrollTop(mydiv.prop('scrollHeight'));
}


// show typing status in chat or groupchat
function onMessageTyping(isTyping, userId, dialogId) {
  showUserIsTypingView(isTyping, userId, dialogId);
}

// start timer after keypress event
var isTypingTimerId;
function setupIsTypingHandler() {
  QB.chat.onMessageTypingListener = onMessageTyping;

  $("#message_text").focus().keyup(function(){

    if (typeof isTypingTimerId === 'undefined') {

      // send 'is typing' status
      sendTypingStatus();

      // start is typing timer
      isTypingTimerId = setTimeout(isTypingTimeoutCallback, 5000);
    } else {

      // start is typing timer again
      clearTimeout(isTypingTimerId);
      isTypingTimerId = setTimeout(isTypingTimeoutCallback, 5000);
    }
  });
}

// delete timer and send 'stop typing' status
function isTypingTimeoutCallback() {
  isTypingTimerId = undefined;
  sendStopTypinStatus();
}

// send 'is typing' status
function sendTypingStatus() {
  if (currentDialog.type == 3) {
    QB.chat.sendIsTypingStatus(opponentId);
  } else {
    QB.chat.sendIsTypingStatus(currentDialog.xmpp_room_jid);
  }
}

// send 'stop typing' status
function sendStopTypinStatus() {
  if (currentDialog.type == 3) {
    QB.chat.sendIsStopTypingStatus(opponentId);
  } else {
    QB.chat.sendIsStopTypingStatus(currentDialog.xmpp_room_jid);
  }
}

// show or hide typing status to other users
function showUserIsTypingView(isTyping, userId, dialogId) {
  if(dialogId === "57a9eb82a28f9aee4e000010") return;
  if(isMessageForCurrentDialog(userId, dialogId)){

    if (!isTyping) {
      $('#'+userId+'_typing').remove();
    } else if (userId != currentUser.id) {
      var userLogin = getUserLoginById(userId);
      var typingUserHtml = buildTypingUserHtml(userId, userLogin);
      $('.messages-list').append(typingUserHtml);
    }

    // scroll to bottom
    var mydiv = $('.messages-list');
    mydiv.scrollTop(mydiv.prop('scrollHeight'));
  }
}

// filter for current dialog
function isMessageForCurrentDialog(userId, dialogId) {
  var result = false;
	if (dialogId == currentDialog._id || (dialogId === null && currentDialog.type == 3 && opponentId == userId)) {
		result = true;
	}
	return result;
}
