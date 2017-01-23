// build html for messages localStorage.getItem('my_name')
function buildMessageHTML(messageSenderId, messageText, messageSenderName, messageSenderPic, messageDateSent, attachmentFileId, messageId, AttachmentFileName, AttachmentFileSize){
  var messageAttach;
  //If deleted message, return
  if(d_msgIDs.indexOf(messageId) > -1) return;
  // target="_blank" download="download"
  
  if(attachmentFileId){    
      messageAttach ='<a href="http://api.quickblox.com/blobs/'+attachmentFileId+'/download.xml?token='+token+'" style="color:#111;">' + 
       '<img src="http://api.quickblox.com/blobs/'+attachmentFileId+'/download.xml?token='+token+'" alt="' + AttachmentFileName + '" class="attachments img-responsive" id="attach-'+attachmentFileId+'"></a>';
       var htm;
      var spary = AttachmentFileName.split(".");
      var ext = spary[spary.length - 1] ;
      var imageExts = ["png", "jpg", "jpeg", "PNG", "JPG", "JPEG"];
      if(imageExts.indexOf(ext)>=0){
         htm = '<div class="row border1234 radius-item padding_xs xs-mtop"><div class="col-xs-4 no_padding"><img class="pull-left attach-Img '+attachmentFileId+'" src="http://api.quickblox.com/blobs/'+attachmentFileId+"/download.xml?token="+token+'"></div>';  
      }
      else if(ext === "pdf"){         
        htm = '<div class="row border1234 radius-item padding_xs xs-mtop"><div class="col-xs-4 no_padding"><img class="pull-left attach-Img '+attachmentFileId+'" src="'+ site_url + "assets/images/pdf.png" + '"></div>';  
      }
      else if(ext === "gif"){
        htm = '<div class="row border1234 radius-item padding_xs xs-mtop"><div class="col-xs-4 no_padding"><img class="pull-left attach-Img '+attachmentFileId+'" src="'+ site_url + "assets/images/gif.png" + '"></div>';  
      }
      else{
        htm = '<div class="row border1234 radius-item padding_xs xs-mtop"><div class="col-xs-4 no_padding"><img class="pull-left attach-Img '+attachmentFileId+'" src="'+ site_url + "assets/images/file.png" + '"></div>';  
      }
      htm += '<div class="col-xs-8"><p><a download="download" target="_blank" href="http://api.quickblox.com/blobs/'+attachmentFileId+'/download.xml?token='+token+'" id="'+ attachmentFileId + '" class="wrapword row" style="color:#111;">' + AttachmentFileName + '</a></p><p class="' + attachmentFileId + '"></p><p class="pull-right size_'+attachmentFileId+'"></p></div></div>';
      
      $(".attach-div").append(htm);
  }  
  var delivered = '<img class="icon-small" src="assets/images/delivered.jpg" alt="" id="delivered_'+messageId+'">';
  var read = '<img class="icon-small" src="'+site_url+'assets/images/read.jpg" alt="" id="read_'+messageId+'">';
  var parts = messageDateSent.toString().split("GMT");
  var date = getMsgDate(parts[0]);
  var messageHtml;
  messageText = process_message(messageText, messageSenderId);

  if(messageSenderId == currentUser_uid){
    messageHtml = '<div class="row" id="bubble_' + messageId + '">' +
                    '<div class="row" style="margin:0px;">'+
                      '<div class="col-xs-2">'+
                      '</div>'+
                      '<div class="col-xs-9">'+
                        '<div class="list-group-item-text message-text pull-right right-msg-style Qinput">'+
                            '<p class="nm" id = "' + messageId + '">'+(messageAttach ? messageAttach : messageText)+'</p>'+
                        '</div>'+
                      '</div>'+
                      '<div class="col-md-1 col-sm-1 col-xs-1" style="padding:0;">'+
                        '<a href="#" title="'+ messageSenderName +'"><img class="pull-left round photo_'+messageSenderId+'" width="30" height="30" src="'+getPhotoByUID(messageSenderId)+'"></a>'+
                      '</div>'+
                    '</div>'+
                    '<div class="row" style="margin:0px 0px 20px 20px;">'+
                      '<div class="col-xs-2"></div>'+
                      '<div class="col-xs-9">'+
                        '<time datetime="'+messageDateSent+'" class="message-time pull-right gray-text '+ messageId + '">'+ date +'</time>'+
                        '<div class="pull-right">'+
                          '<span class="gray-text glyphicon glyphicon-option-horizontal rightbar-padding" data-toggle="dropdown"></span>'+
                          '<ul class="dropdown-menu user-pop">'+
                           '<li><a onclick="deleteMessage('+ "'" + messageId.toString() +"', this" + ')"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>'+
                          '</ul>'+
                        '</div>'+
                        '<span id="save_'+messageId+'" class="pull-right glyphicon glyphicon-star" style="color:#72b7f8;display:none;"><span style="font-size:8px;color:gray;"></span></span>'+
                        '<span id="like_'+messageId+'" class="pull-right glyphicon glyphicon-thumbs-up" style="color:#72b7f8;display:none;"><span style="font-size:8px;color:gray;"></span></span>'+
                      '</div>'+
                    '</div>'+
                  '</div>';
  
  }
  else{
  messageHtml = '<div class="row" id="bubble_' + messageId + '">' +
                  '<div class="row" style="margin:0px;">'+    
                    '<div class="col-xs-1" style="padding:0;">'+
                      '<a href="#" title="'+ messageSenderName +'"><img class="round pull-right photo_'+messageSenderId+'" width="30" height="30" src="'+getPhotoByUID(messageSenderId)+'"></a>'+
                    '</div>'+
                    '<div class="col-xs-9">'+
                      '<div class="list-group-item-text message-text pull-left left-msg-style Qinput">'+
                        '<p class="nm" id="' + messageId + '">'+(messageAttach ? messageAttach : messageText)+'</p>'+
                      '</div>'+  
                    '</div>'+  
                    '<div class="col-xs-4">'+
                    '</div>'+
                  '</div>'+
                  '<div class="row" style="margin:0px 20px 20px 0px;">'+
                    '<div class="col-xs-1">'+
                    '</div>'+                  
                    '<div class="col-xs-10 pull-left">'+
                    '<time datetime="'+messageDateSent+'" class="message-time pull-left gray-text '+ messageId + '">'+ date +'</time>'+
                    '<span class="pull-left gray-text glyphicon glyphicon-option-horizontal rightbar-padding" data-toggle="dropdown"></span>'+
                    '<ul class="dropdown-menu user-pop">';
                    if(!messageAttach){
                      messageHtml += '<li><a onclick="saveMessage(' + "'"+messageSenderId.toString()+"'" + ',' + "'" + messageId.toString() + "'" + ',' + 'this)"><span class="glyphicon glyphicon-star"></span>&nbsp;<span id="msg-save-'+messageId+'">Save</span></a></li>';
                    }
                    messageHtml += '<li><a onclick="likeMessage(' + "'" + messageId.toString() + "'" + ',' + 'this)"><span id="like-icon-'+messageId+'" class="glyphicon glyphicon-thumbs-up"></span>&nbsp;<span id="msg-like-'+messageId+'">Like</span></a></li>';
                    if(currentUser_type == 1){
                      messageHtml += '<li><a onclick="deleteMessage('+ "'" + messageId.toString() +"'" + ',' + 'this)"><span class="glyphicon glyphicon-trash"></span>&nbsp;<span>Delete</span></a></li>';
                    }
                    messageHtml += '</ul>'+
                    '<span id="save_'+messageId+'" class="pull-left glyphicon glyphicon-star" style="color:#72b7f8;display:none;"><span style="font-size:8px;color:gray;"></span></span>'+
                    '<span id="like_'+messageId+'" class="pull-left glyphicon glyphicon-thumbs-up" style="color:#72b7f8;display:none;"><span style="font-size:8px;color:gray;"></span></span>'+
                    '</div>'+
                  '</div>'+
                '</div>';

      
  }
  return messageHtml;
}

function process_message(msg, senderId){
  var update_msg = "";
  var words = msg.split(" ");
  for(var i = 0; i<words.length; i++){
    if(words[i].indexOf("http://") == 0 || words[i].indexOf("https://") == 0){
      if(senderId == currentUser_uid){
        words[i] = '<a class="wrapword wtext" href="'+words[i]+'" target="_blank" style="text-decoration:underline;">'+words[i]+'</a>';
      }
      else{
        words[i] = '<a class="wrapword" href="'+words[i]+'" target="_blank" style="color:#555;text-decoration:underline;">'+words[i]+'</a>';
      }
    }
    else if(words[i].length > 20){
      words[i] = '<span class="wrapword">'+words[i]+'</span>';
    }
    update_msg += words[i] + " ";
  }
  return update_msg;
}

function getPhotoByUID(id){
  $.ajax({
     url: site_url + 'chat/getPhoto',
     data: {
        uid:id
     },
     success: function(data) {
        var res = data.split("q.q");
        $(".photo_"+id).prop("src", res[0]);
        $(".photo_"+id).parent().prop("href", site_url+"profile/user/"+res[1]);
     },
     type: 'POST'
  });
}





// build html for dialogs
function buildDialogHtml(dialogId, dialogUnreadMessagesCount, dialogIcon, dialogName, dialogLastMessage) {
  alert('update');
  var UnreadMessagesCountShow = '<span class="badge">'+dialogUnreadMessagesCount+'</span>';
      UnreadMessagesCountHide = '<span class="badge" style="display: none;">'+dialogUnreadMessagesCount+'</span>';
  if(dialogUnreadMessagesCount > 0){
      if(document.title.indexOf('message') < 0) document.title = "(New message) " + document.title; 
  }
  var dialogHtml = '<a href="'+site_url + 'chat/channel/' + dialogId + '" class="list-group-item inactive" id='+'"'+dialogId+'"'+' onclick="triggerDialog('+"'"+dialogId+"'"+', 1)">'+
                   (dialogUnreadMessagesCount === 0 ? UnreadMessagesCountHide : UnreadMessagesCountShow)+'<h5 class="list-group-item-heading">'+
                   dialogIcon+'&nbsp;&nbsp;&nbsp;<span><strong>'+dialogName+'</strong></span></h5>'+'<p class="list-group-item-text last-message">'+
                   (dialogLastMessage === null ?  "" : dialogLastMessage)+'</p>'+'</a>';
  return dialogHtml;
}

// build html for typing status
function buildTypingUserHtml(userId, userLogin) {
  //var typingUserHtml = '<div id="'+userId+'_typing" class="list-group-item typing">'+'<time class="pull-right">writing now</time>'+'<h4 class="list-group-item-heading">'+
  //                     userLogin+'</h4>'+'<p class="list-group-item-text"> . . . </p>'+'</div>';
  var typingUserHtml;
  if(userLogin !== "undefined") typingUserHtml = '<div id="'+userId+'_typing" class="list-group-item typing">'+'<p style="color:gray;"><marquee><img alt="" src="'+site_url+'assets/images/waiting.png" /></marquee>'+userLogin + '  is typing...</p></div>';
  return typingUserHtml;
}

function buildMetaHtml(jsonData) {
  var jsonObj = JSON.parse(jsonData);
  console.log("###################");
  console.log(jsonObj);
  var retHtml = '<div id="information_holder">'+
    '<h4>'+
      '<span class="">'+jsonObj.d_name+'</span>'+
    '</h4>'+

    '<h5 class="text-muted">Created by '+jsonObj.d_owner+'</h5>'+
    '<div class="information_actions">';
    
    if (jsonObj.notify == "10") {
      retHtml += '<a id="chat-noti" class="text-muted icon-noti-off" onclick="notifyAction(\''+jsonObj.d_id+'\')">Notifications<span class="">OFF</span></a>';
    } else {
      retHtml += '<a id="chat-noti" class="text-muted icon-noti-on" onclick="notifyAction(\''+jsonObj.d_id+'\')">Notifications<span class="">ON</span></a>';
    }
      
  if (jsonObj.d_owner == "Me") {
    retHtml += '<a class="" onclick="deleteAction(\''+jsonObj.d_id+'\')"><span class="text-danger">Delete</span></a>';
  } else {
    retHtml += '<a class="" onclick="leaveAction(\''+jsonObj.d_id+'\')"><span class="text-warning">Leave</span></a>';
  }
    retHtml += '</div><div class="information_members"><h5 class="">'+jsonObj.d_users.length+' Members';
  if (jsonObj.d_owner == "Me" && jsonObj.d_type == "2") {
    retHtml += '<a class="" onclick="addMember(\''+jsonObj.d_id+'\')">+ Add Members</a>';
  }  
    retHtml += '</h5><ul>';
  for (var i=0; i<jsonObj.d_users.length; i++) {
    var d_user = jsonObj.d_users[i];
    var username = '';
    if (d_user.fname) {
        username = d_user.fname+" "+d_user.lname;
    } else {
        var strArr = d_user.email.split("@");
        username = strArr[0];
    }
    if (d_user.photo == "") d_user.photo = site_url + "/assets/images/emp-sm.jpg";
    retHtml += '<li class="" id="remove-'+d_user.id+'"><a class="" href="'+site_url+'/profile/user/'+d_user.id+'"><img class="avatar avatar_small" src="'+d_user.photo+'">' + username+'</a>';;
    
   if (jsonObj.d_owner == "Me") {
    retHtml += '<a class="information_remove_user" onclick="removeAction(\''+jsonObj.d_id+'\', \''+d_user.id+'\', \''+username+'\')"></a>';
  }
    retHtml += '<span class=""><lastseen data-user-id="4513703"><span class="lastseen">offline</span></lastseen></span></li>';
  }
        
  retHtml += '</ul></div></div>';
  return retHtml;
}

// build html for users list
function buildUserHtml(userLogin, userId, isNew) {
  var userHtml = "<a href='#' id='" + userId;
  if(isNew){
    userHtml += "_new'";
  }else{
    userHtml += "'";
  }
  userHtml += " class='col-md-12 col-sm-12 col-xs-12 users_form' onclick='";
  userHtml += "clickToAdd";
  userHtml += "(\"";
  userHtml += userId;
  if(isNew){
    userHtml += "_new";
  }
  userHtml += "\")'>";
  userHtml += userLogin;
  userHtml +="</a>";

  return userHtml;
}

(function($)
{
    /**
     * Auto-growing textareas; technique ripped from Facebook
     * 
     * 
     * http://github.com/jaz303/jquery-grab-bag/tree/master/javascripts/jquery.autogrow-textarea.js
     */
    $.fn.autogrow = function(options)
    {
        return this.filter('textarea').each(function()
        {
            var self         = this;
            var $self        = $(self);
            var minHeight    = $self.height();
            var noFlickerPad = $self.hasClass('autogrow-short') ? 0 : parseInt($self.css('lineHeight')) || 0;
            var settings = $.extend({
                preGrowCallback: null,
                postGrowCallback: null
              }, options );

            var shadow = $('<div></div>').css({
                position:    'absolute',
                top:         -10000,
                left:        -10000,
                width:       $self.width(),
                fontSize:    $self.css('fontSize'),
                fontFamily:  $self.css('fontFamily'),
                fontWeight:  $self.css('fontWeight'),
                lineHeight:  $self.css('lineHeight'),
                maxHeight:   44,
                resize:      'none',
          'word-wrap': 'break-word'
            }).appendTo(document.body);

            var update = function(event)
            {
                var times = function(string, number)
                {
                    for (var i=0, r=''; i<number; i++) r += string;
                    return r;
                };

                var val = self.value.replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/\n$/, '<br/>&#xa0;')
                                    .replace(/\n/g, '<br/>')
                                    .replace(/ {2,}/g, function(space){ return times('&#xa0;', space.length - 1) + ' ' });

        // Did enter get pressed?  Resize in this keydown event so that the flicker doesn't occur.
        if (event && event.data && event.data.event === 'keydown' && event.keyCode === 13) {
          val += '<br />';
        }

                shadow.css('width', $self.width());
                shadow.html(val + (noFlickerPad === 0 ? '...' : '')); // Append '...' to resize pre-emptively.
                
                var newHeight=Math.max(shadow.height() + noFlickerPad, minHeight);
                if(settings.preGrowCallback!=null){
                  newHeight=settings.preGrowCallback($self,shadow,newHeight,minHeight);
                }
                
                $self.height(newHeight);
                
                if(settings.postGrowCallback!=null){
                  settings.postGrowCallback($self);
                }
            }

            $self.change(update).keyup(update).keydown({event:'keydown'},update);
            $(window).resize(update);

            update();
        });
    };
})(jQuery);








