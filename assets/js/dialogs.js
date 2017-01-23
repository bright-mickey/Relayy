var dialogs = {};
var currentDType = 2;
var BadgeCountArray = [];

function onSystemMessageListener(message) {
  if (!message.delay) {
    switch (message.extension.notification_type) {
      case "1":
        // This is a notification about dialog creation
        getAndShowNewDialog(message.extension.dialog_id);
        break;
      case "2":
        // This is a notification about dialog update
        getAndUpdateDialog(message.extension.dialog_id);
        break;
      default:
        break;
    }
  }
}

$("#load-img").change(function(){
  var inputFile = $("#load-img")[0].files[0];
  if (inputFile) {
  }
  clickSendAttachments(inputFile);
});

function retrieveChatDialogs() {
  // get the chat dialogs list
  //
  QB.chat.dialog.list(null, function(err, resDialogs) {
    if (err) {
      console.log(err);
    } else {

      // repackage dialogs data and collect all occupants ids
      //
      var occupantsIds = [];

      if(resDialogs.items.length === 0){

        // hide login form
        $("#loginForm").modal("hide");

        // setup attachments button handler
        //
        

        return;
      }

      resDialogs.items.forEach(function(item, i, arr) {
        var dialogId = item._id;
        dialogs[dialogId] = item;

        // join room
        if (item.type != 3) {
          QB.chat.muc.join(item.xmpp_room_jid, function() {
             console.log("Joined dialog "+dialogId);
          });
        }

        item.occupants_ids.map(function(userId) {
          occupantsIds.push(userId);
        });
      });

      // load dialogs' users
      //
      updateDialogsUsersStorage(jQuery.unique(occupantsIds), function(){
        // show dialogs
        //
        resDialogs.items.forEach(function(item, i, arr) {
          showOrUpdateDialogInUI(item, false);
        });

        //  and trigger the 1st dialog
        //
        triggerDialog(resDialogs.items[0]._id);

        // hide login form
        $("#loginForm").modal("hide");

        // setup attachments button handler
        //
        
      });
    }
  });
}



function retrieveDialog() {

  // join room
  DialogJIDS.forEach(function(item, i, arr) {
    //var dialogId = item._id;
//    dialogs[dialogId] = item;

    // join room
      QB.chat.muc.join(item, function() {
         console.log("Joined dialog "+item);
      });

//    item.occupants_ids.map(function(userId) {
//      occupantsIds.push(userId);
//    });
  });
                                            
    updateDialogsUsersStorage(DialogUIDS, function(){
      // show dialogs
      //
      //showOrUpdateDialogInUI(item, false);

      //  and trigger the 1st dialog
      //
      if (DialogStatus) {
        triggerDialog(DialogID);  
      } else {
        if ($('body').hasClass("chat-page") && DialogJID) {
       BootstrapDialog.alert({
                title: 'Alert',
                message: 'This Chat Room is not approved by admin!',
                type: BootstrapDialog.TYPE_WARNING,
                closable: true,
                draggable: true,
                buttonLabel: 'Cancel'
            });  
       $("#attach_btn").prop('disabled', true);
       $("#message_text").prop('disabled', true);
       $("#send_btn").prop('disabled', true);
        }
        
      }
        

      // hide login form
      // $("#loginForm").modal("hide");

      
    });
      
  // });

}

function notifyAction(did) {
  var currentObj = $("#chat-noti");
  var notiVal = 10;
  if (currentObj.hasClass("icon-noti-off")) notiVal = 11;
  $.ajax({
   url: site_url + 'chat/notification',
   data: {
      did: did,
      notification: notiVal
   },
   success: function(data) {
      console.log(data);
      if (notiVal == 10) {
        currentObj.removeClass("icon-noti-on");
        currentObj.addClass("icon-noti-off");
        $("#chat-noti span").text("OFF");
      } else {
        currentObj.removeClass("icon-noti-off");
        currentObj.addClass("icon-noti-on");
        $("#chat-noti span").text("ON");
      }
   },
   type: 'POST'
  });
}

function deleteAction(did) {
  if (confirm("Are you sure you want delete this chat room?")) {
    $.ajax({
     url: site_url + 'chat/delete',
     data: {
        did: did,
     },
     success: function(data) {
        console.log(data);
        onDialogDelete(did);
        window.location.href = site_url + "chat";
     },
     type: 'POST'
    });
  }
}

function leaveAction(did) {
  if (confirm("Are you sure you want leave this chat room?")) {
    $.ajax({
     url: site_url + 'chat/leave',
     data: {
        did: did,
     },
     success: function(data) {
        console.log(data);
        window.location.href = site_url + "chat";
     },
     type: 'POST'
    });
  }
}

function removeAction(did, uid, name) {  
  BootstrapDialog.show({
        title:"Remove User",
        message: "Are you sure you want remove this user " + name + " ?",
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Delete',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                var currentObj = $("#remove-"+uid);
                $.ajax({
                 url: site_url + 'chat/remove',
                 data: {
                    did: did,
                    uid: uid
                 },
                 success: function(data) {
                    console.log(data);
                    currentObj.remove();    
                    dialogRef.close();
                    location.reload();
                 },
                 type: 'POST'
                });            
            }
        }, {
            label: 'Cancel',
            action: function(dialogRef){
                dialogRef.close();
            }
        }]
    });
}

function showOrUpdateDialogInUI(itemRes, updateHtml) {
  alert('update');
  var dialogId = itemRes._id;
  var dialogName = itemRes.name;
  var dialogType = itemRes.type;
  var dialogLastMessage = itemRes.last_message;
  var dialogUnreadMessagesCount = itemRes.unread_messages_count;
  var dialogIcon = getDialogIcon(itemRes.type);

  if (dialogType == 3) {
    opponentId    = QB.chat.helpers.getRecipientId(itemRes.occupants_ids, currentUser.id);
    opponentLogin = getUserLoginById(opponentId);
    dialogName    = 'Dialog with ' + opponentLogin;
  }

  if (updateHtml === true) {
    var updatedDialogHtml = buildDialogHtml(dialogId, dialogUnreadMessagesCount, dialogIcon, dialogName, dialogLastMessage);
    $('#dialogs-list').prepend(updatedDialogHtml);
    $('.list-group-item.active .badge').text(0).hide(0);
  } else {
    var dialogHtml = buildDialogHtml(dialogId, dialogUnreadMessagesCount, dialogIcon, dialogName, dialogLastMessage);
    $('#dialogs-list').append(dialogHtml);
  }
}

// add photo to dialogs
function getDialogIcon (dialogType) {
  var groupPhoto = '<img src="assets/images/ava-group.svg" width="30" height="30" class="round">';
  var privatPhoto  = '<img src="assets/images/ava-single.svg" width="30" height="30" class="round">';
  var defaultPhoto = '<span class="glyphicon glyphicon-eye-close"></span>';

  var dialogIcon;
  switch (dialogType) {
    case 1:
      dialogIcon = groupPhoto;
      break;
    case 2:
      dialogIcon = groupPhoto;
      break;
    case 3:
        dialogIcon = privatPhoto;
      break;
    default:
      dialogIcon = defaultPhoto;
      break;
  }
  return dialogIcon;
}

function saveBadgeState(state, uid){
  $.ajax({
        url: site_url + 'chat/saveBadges',
        data: {
            state: state,
            uid: uid
        },
        success: function(data) {
            
        },
        type: 'POST'
    });           
}



// show unread message count and new last message
function updateDialogsList(dialogId, text, senderTime, senderId){
  // update unread message count
  // alert('update');
  // var badgeCount = $('#'+dialogId+' .badge').html();
  // $('#'+dialogId+'.list-group-item.inactive .badge').css("display", "block");

  

  if($('#'+dialogId+'.list-group-item.inactive .badge').text() !== "new" && $("#"+dialogId).prop("class").indexOf("inactive") > 0){
    if(dialogId.toString() !== "57a9eb82a28f9aee4e000010" || (dialogId.toString() === "57a9eb82a28f9aee4e000010" && senderId.toString() === "12094756")){
        if(dialogId !== currentDialogID){
            $('#'+dialogId+'.list-group-item.inactive .badge').text("new").fadeIn(500);
            var BadgeStates = JSON.stringify(badgeArray);
            //alert(JSON.stringify(badgeArray));
            if(BadgeStates.indexOf(dialogId) < 0){
              badgeArray.push(dialogId);
              saveBadgeState(JSON.stringify(badgeArray), currentUser_uid);
            }   
            
            document.title = "(New message) " + document.title; 
            //mobile chat notification(red circle) is showing
            $(".chat_notification").css("display", "block");
            $("#new_message_noti").css("display", "block");
        }
    }

  } 

  // update last message
  $('#'+dialogId+' .list-group-item-text').text(text);
  $('#'+dialogId+' .send-time').text(senderTime);
  var firstObj = $('#dialogs-list').children('.list-group-item:first');
  if (dialogId != firstObj.attr('id')) {
    //  alert(parentObj.attr('id'));
    $('#'+dialogId).insertBefore(firstObj);    
  }
}


function updateDialogsDB(dialogId, senderID, text){
    $.ajax({
        url: site_url + 'chat/msgUpdate',
        data: {
            did: dialogId,
            sender: senderID,
            msg: text
        },
        success: function(data) {
            console.log("update-dialog-db: ");
            console.log(data);
            
            if(data.indexOf("removed/") > -1){
              alert("Sorry! You are removed from this chatroom.");
              location.href = site_url + 'chat';
            }
        },
        type: 'POST'
    });            
}

// Choose dialog
function triggerDialog(dialogId, ajaxFlag){

  if (ajaxFlag == 1) {
    console.log("****************************");
    $.ajax({
     url: site_url + 'chat/dialog',
     data: {
        did: dialogId
     },
     success: function(data) {
        // console.log("####################");
        // console.log(data);
        showRightInfo(data);
     },
     type: 'POST'
    });
  }
  // deselect
  var kids = $('#dialogs-list').children().children();
  kids.removeClass('active').addClass('inactive');


  // select
  $('#'+dialogId).removeClass('inactive').addClass('active');

  $('.list-group-item.active .badge').text(0);

  $('.messages-list').html('');

  // load chat history
  //
  var filters = {"_id": dialogId};
 
  QB.chat.dialog.list(filters, function(err, resDialogs) {

    if (err) {
      console.log(err);
    } else {

      retrieveChatMessages(resDialogs.items[0], null);

      $('.messages-list').scrollTop($('.messages-list').prop('scrollHeight')); 
    }
  });
}

function setupUsersScrollHandler(){
  // uploading users scroll event
  $('.list-group.pre-scrollable.for-scroll').scroll(function() {
    if  ($('.list-group.pre-scrollable.for-scroll').scrollTop() == $('#users_list').height() - $('.list-group.pre-scrollable.for-scroll').height()){

      // get and show users
      retrieveUsersForDialogCreation(function(users) {
        $.each(users, function(index, item){
          showUsers(this.user.login, this.user.id);
        });
      });
    }
  });
}

//
function showUsers(userLogin, userId) {
  var userHtml = buildUserHtml(userLogin, userId, false);

  $('#users_list').append(userHtml);
}

function showRightInfo(jsonData) {
  var rightHtml = buildMetaHtml(jsonData);

  $('#information_holder').html(rightHtml); 
}

// show modal window with users
function showNewDialogPopup(type) {
  currentDType = type;

  if (type == 1) {
    $("#add-dialog").text("Create 1:1 Chat");
    $("#new_dialog_title").text("Choose one user to create 1:1 Chat with");
    $("#dlg_name").hide();
  } else {
    $("#add-dialog").text("Create Group");
    $("#new_dialog_title").text("Choose users to create a group with");
    $("#dlg_name").show();
  }

  $("#add_new_dialog").modal("show");
  $('#add_new_dialog .progress').hide();

  retrieveUsersForDialogCreation(function(users) {
    if(users === null || users.length === 0){
      return;
    }
    $.each(users, function(index, item){
      if (this.user.id != QBUser.id)
        showUsers(this.user.full_name, this.user.id);
    });
  });

  setupUsersScrollHandler();
}

// select users from users list
function clickToAdd(forFocus) {
  if (currentDType == 1) {
    if ($('#'+forFocus).hasClass("active")) {
      $('a.users_form').removeClass('active');
    } else {
      $('a.users_form').removeClass('active');
      $('#'+forFocus).addClass("active");
    }
  } else {
    if ($('#'+forFocus).hasClass("active")) {
      $('#'+forFocus).removeClass("active");
    } else {
      $('#'+forFocus).addClass("active");
    }
  }
}

// create new dialog
function createNewDialog() {
  var usersIds = [];
  var usersNames = [];

  $('#users_list .users_form.active').each(function(index) {
    usersIds[index] = $(this).attr('id');
    usersNames[index] = $(this).text();
  });

  usersIds.unshift(QBUser.id);

  $("#add_new_dialog").modal("hide");
  $('#add_new_dialog .progress').show();

  var dialogName;
  var dialogOccupants;
  var dialogType;

  //if (usersIds.length > 1) {
    // if (usersNames.indexOf(currentUser.login) > -1) {
    //   dialogName = usersNames.join(', ');
    // }else{
    //   dialogName = currentUser.login + ', ' + usersNames.join(', ');
    // }
    if (currentDType == 1) dialogName = "Private"
    else dialogName = $("#dlg_name").val();
    dialogOccupants = usersIds;
    // alert(dialogOccupants); 
    dialogType = 1;
  // } else {
  //   dialogOccupants = usersIds;
  //   dialogType = 3;
  // }

  var params = {
    type: dialogType,
    occupants_ids: dialogOccupants,
    name: dialogName
  };

  // create a dialog
  //
  console.log("Creating a dialog with params: " + JSON.stringify(params));

  QB.chat.dialog.create(params, function(err, createdDialog) {
    if (err) {
      console.log(err);
    } else {
      console.log("Dialog " + createdDialog._id + " created with users: " + dialogOccupants);

      // save dialog to local storage
      var dialogId = createdDialog._id;
      dialogs[dialogId] = createdDialog;

      currentDialog = createdDialog;

      joinToNewDialogAndShow(createdDialog);

      notifyOccupants(createdDialog.occupants_ids, createdDialog._id, 1);

      triggerDialog(createdDialog._id);

      saveNewDialogToDB(createdDialog, dialogOccupants, currentDType)

      //alert(currentDType);

      $('a.users_form').removeClass('active');
    }
  });
}

// save data on db
function saveNewDialogToDB(itemDialog, occupants, type) {
  var dialogId = itemDialog._id;
  var dialogName = itemDialog.name;
  var dialogLastMessage = itemDialog.last_message;
  var dialogOccupants = occupants;//.unshift(QBUser.id);
  var dialogType = type;
  var dialogJID = itemDialog.xmpp_room_jid;

  //alert(dialogOccupants);

 $.ajax({
     url: site_url + 'chat/new',
     data: {
        did: dialogId,
        dname: dialogName,
        dmessage: dialogLastMessage,
        dusers: dialogOccupants,
        dtype: dialogType,
        djid: dialogJID
     },
     success: function(data) {
        //alert(data);
     },
     type: 'POST'
  });
}

//
function joinToNewDialogAndShow(itemDialog) {
  var dialogId = itemDialog._id;
  var dialogName = itemDialog.name;
  var dialogLastMessage = itemDialog.last_message;
  var dialogUnreadMessagesCount = itemDialog.unread_messages_count;
  var dialogIcon = getDialogIcon(itemDialog.type);

  // join if it's a group dialog
  if (itemDialog.type != 3) {
    QB.chat.muc.join(itemDialog.xmpp_room_jid, function() {
       console.log("Joined dialog: " + dialogId);
    });
    opponentLogin = null;
  } else {
    opponentId = QB.chat.helpers.getRecipientId(itemDialog.occupants_ids, currentUser.id);
    opponentLogin = getUserLoginById(opponentId);
    dialogName = chatName = 'Dialog with ' + opponentLogin;
  }

  // show it
  var dialogHtml = buildDialogHtml(dialogId, dialogUnreadMessagesCount, dialogIcon, dialogName, dialogLastMessage);
  $('#dialogs-list').prepend(dialogHtml);
}

//
function notifyOccupants(dialogOccupants, dialogId, notificationType) {
  dialogOccupants.forEach(function(itemOccupanId, i, arr) {
    if (itemOccupanId != currentUser.id) {
      var msg = {
        type: 'chat',
        extension: {
          notification_type: notificationType,
          dialog_id: dialogId
        }
      };

      QB.chat.sendSystemMessage(itemOccupanId, msg);
    }
  });
}

//
function getAndShowNewDialog(newDialogId) {
  // get the dialog and users
  //
  QB.chat.dialog.list({_id: newDialogId}, function(err, res) {
    if (err) {
      console.log(err);
    } else {

      var newDialog = res.items[0];

      // save dialog to local storage
      var dialogId = newDialog._id;
      dialogs[dialogId] = newDialog;

      // collect the occupants
      var occupantsIds = [];
      newDialog.occupants_ids.map(function(userId) {
        occupantsIds.push(userId);
      });
      updateDialogsUsersStorage(jQuery.unique(occupantsIds), function(){

      });

      joinToNewDialogAndShow(newDialog);
    }
  });
}

function getAndUpdateDialog(updatedDialogId) {
  // get the dialog and users
  //

  var dialogAlreadyExist = dialogs[updatedDialogId] !== null;
  console.log("dialog " + updatedDialogId + " already exist: " + dialogAlreadyExist);

  QB.chat.dialog.list({_id: updatedDialogId}, function(err, res) {
    if (err) {
      console.log(err);
    } else {

      var updatedDialog = res.items[0];

      // update dialog in local storage
      var dialogId = updatedDialog._id;
      dialogs[dialogId] = updatedDialog;

      // collect the occupants
      var occupantsIds = [];
      updatedDialog.occupants_ids.map(function(userId) {
        occupantsIds.push(userId);
      });
      updateDialogsUsersStorage(jQuery.unique(occupantsIds), function(){

      });

      if(!dialogAlreadyExist){
          joinToNewDialogAndShow(updatedDialog);
      }else{
        // just update UI
        $('#'+dialogId+' h4 span').html('');
        $('#'+dialogId+' h4 span').append(updatedDialog.name);
      }
    }
  });
}

// show modal window with dialog's info
function showDialogInfoPopup() {
  $("#update_dialog").modal("show");
  $('#update_dialog .progress').hide();

  // shwo current dialog's occupants
  setupDialogInfoPopup(currentDialog.occupants_ids, currentDialog.name);
}

// show information about the occupants for current dialog
function setupDialogInfoPopup(occupantsIds, name) {

  // show name
  $('#dialog-name-input').val(name);

  // show occupants
  var logins = [];
  occupantsIds.forEach(function(item, index) {
    login = getUserLoginById(item);
    logins[index] = login;
  });
  $('#all_occupants').text('');
  $('#all_occupants').append('<b>Occupants: </b>'+logins.join(', '));

  // show type
  //
  // private
  if (currentDialog.type == 3) {
    $('.dialog-type-info').text('').append('<b>Dialog type: </b>privat chat');
    $('.new-info').hide();
    $('.push').hide();
    $('#push_usersList').hide();
    $('#update_dialog_button').hide();

  // group
  } else {
    $('.dialog-type-info').text('').append('<b>Dialog type: </b>group chat');
    $('.new-info').show();
    $('.push').show();
    $('#push_usersList').show();
    $('#update_dialog_button').show();

    // get users to add to dialog
    retrieveUsersForDialogUpdate(function(users){
      if(users === null || users.length === 0){
        return;
      }

      $.each(users, function(index, item){
        var userHtml = buildUserHtml(this.user.login, this.user.id, true);
        $('#add_new_occupant').append(userHtml);
      });
    });
    setupScrollHandlerForNewOccupants();
  }
}


function setupScrollHandlerForNewOccupants() {
  // uploading users scroll event
  $('#push_usersList').scroll(function() {
    if  ($('#push_usersList').scrollTop() == $('#add_new_occupant').height() - $('#push_usersList').height()){

      retrieveUsersForDialogUpdate(function(users){
        if(users === null || users.length === 0){
          return;
        }
        $.each(users, function(index, item){
          var userHtml = buildUserHtml(this.user.login, this.user.id, false);
          $('#add_new_occupant').append(userHtml);
        });
      });

    }
  });
}



// delete currend dialog
function onDialogDelete(dialogId) {
  
    QB.chat.dialog.delete(currentDialog._id, function(err, res) {
      if (err) {
        console.log(err);
      } else {
        console.log("Dialog removed");
      }
    });

}

function searchDialogs(object) {
    var searchStr = $(object).val();
    $("#dialogs-list .list-group-item").each(function(){
        var aObject = $(this);
//        console.log("#"+$(this).find(".d_title").text());
        
        if ($(this).find(".d_title").text().search(searchStr) == -1) {
            aObject.hide();
        } else {
            aObject.show();
        }
    });
}

function searchInit() {
    $("#dialogs-list .list-group-item").each(function(){
        $(this).show(); 
    });
}