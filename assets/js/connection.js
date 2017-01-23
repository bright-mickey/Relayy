//"use strict";

var currentUser;

$(document).ready(function() {

  // $("#loginForm").modal("show");
  // $('#loginForm .progress').hide();
currentUser = QBUser;
connectToChat(QBUser);

});

function connectToChat(user) {
  // $('#loginForm button').hide();
  // $('#loginForm .progress').show();

  $(".load-msg").show(0);

  // Create session and connect to chat
  //
  QB.createSession({login: user.email, password: QBApp.authKey}, function(err, res) {
    if (res) {
      // save session token
      token = res.token;

      //user.id = res.user_id;
      //mergeUsers([{user: user}]);

      QB.chat.connect({userId: user.id, password: QBApp.authKey}, function(err, roster) {
        if (err) {
          console.log(err);
        } else {
          console.log(roster);
//          retrieveChatDialogs();

          retrieveDialog();
          // setup message listeners
          //
          setupAllListeners();

          // setup scroll events handler
          //
          setupMsgScrollHandler();
          b_QBLogin = true;

        }
        $(".load-msg").hide(0);
      });
    }
  });
}

function setupAllListeners() {
  QB.chat.onDisconnectedListener    = onDisconnectedListener;
  QB.chat.onReconnectListener       = onReconnectListener;
  QB.chat.onMessageListener         = onMessage;
  QB.chat.onSystemMessageListener   = onSystemMessageListener;
  QB.chat.onDeliveredStatusListener = onDeliveredStatusListener;
  QB.chat.onReadStatusListener      = onReadStatusListener;
  setupIsTypingHandler();
}

// reconnection listeners
function onDisconnectedListener(){
  console.log("onDisconnectedListener");
}

function onReconnectListener(){
  console.log("onReconnectListener");
}


// // niceScroll() - ON
// $(document).ready(
//     function() {
//         $("html").niceScroll({cursorcolor:"#02B923", cursorwidth:"7", zindex:"99999"});
//         //$(".nice-scroll").niceScroll({cursorcolor:"#02B923", cursorwidth:"7", zindex:"99999"});
//     }
// );