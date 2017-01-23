

<script>
  
  var dialogID = "<?php echo $d_id ?>";
  var saved_mids = '<?php echo $saved_mids ?>';
  var liked_mids = '<?php echo $liked_mids ?>';
  var b_send;
  var keys = {
    enter: false,
    ctrl: false
  };
  var d_msgIDs = '<?php echo $d_messages ?>';
  currentDialogID = "<?php echo $d_current ?>";

  $(document.body).keydown(function(event) {
      //auto resize textarea
      var h = $("#message_text").height();
      $(".chat_text_field").css("padding", (80-h)/2+"px 5px");
      $(".chat_text_field").css("width", "70%");
  // save status of the button 'pressed' == 'true'
      if (event.keyCode == 13) {
          keys["enter"] = true;
      } else if (event.keyCode == 17) {
          keys["ctrl"] = true;
      }
      if (keys["enter"] && keys["ctrl"]) { 

      }
      else if(keys["enter"]){
          b_send = 1;
          clickSendMessage();
          keys["enter"] = false; 
          keys["ctrl"] = false; 
          //document.getElementById('message_text').value="";
          $("#message_text").val("");
          $(".chat_text_field").css("padding", "32px 5px");
          $(".chat_text_field").css("width", "70%");
      }
      
  });

  $(document.body).keyup(function(event) {
      // reset status of the button 'released' == 'false' 
          if(b_send == 1){
            //document.getElementById('message_text').value="";
            $("#message_text").val("");
            b_send = 0;
          }
          if (event.keyCode == 13) {
              keys["enter"] = false; 
          } else if (event.keyCode == 17) {
              keys["ctrl"] = false;  
          } 
          
  });

  

  function onEditMessage(mid){
    $(".edit_msg_div").hide();
    $("#edit_"+mid).show();
  }

  function saveMessage(from_uid, message_id, obj){
        
        $.ajax({
           url: site_url + 'chat/saveMessage',
           data: {             
              from_uid: from_uid,
              message_id: message_id,
              message:  $('#'+message_id).html(),
              date: $('.'+message_id).first().text(),
              dialogID: dialogID 
           },
           success: function(data) {    

              var obj = $("#save_"+message_id).find("span");
              var num = $(obj).text();
              

              if(data === "unsave"){
                if(num == 1) $("#save_"+message_id).hide();
                else $(obj).text(num * 1 - 1);
                $("#msg-save-"+message_id).text('save');
              }
              else{
                if($("#save_"+message_id).is(':visible')) $(obj).text(num * 1 + 1);
                else{
                  $("#save_"+message_id).show();
                  $(obj).text(1);
                }
                $("#msg-save-"+message_id).text('unsave');
              }
              
           },
           type: 'POST'
        });
   
  }

  function likeMessage(message_id, obj){
        
        $.ajax({
           url: site_url + 'chat/likeMessage',
           data: {             
              message_id: message_id,
              dialogID: dialogID 
           },
           success: function(data) {    

              var obj = $("#like_"+message_id).find("span");
              var num = $(obj).text();
              

              if(data === "unlike"){
                if(num == 1) $("#like_"+message_id).hide();
                else $(obj).text(num * 1 - 1);
                $("#msg-like-"+message_id).text('like');
                $("#like-icon-"+message_id).prop("class", "glyphicon glyphicon-thumbs-up");
              }
              else{
                if($("#like_"+message_id).is(':visible')) $(obj).text(num * 1 + 1);
                else{
                  $("#like_"+message_id).show();
                  $(obj).text(1);
                }
                $("#msg-like-"+message_id).text('unlike');
                $("#like-icon-"+message_id).prop("class", "glyphicon glyphicon-thumbs-down");
              }
              
           },
           type: 'POST'
        });
   
  }

  function deleteMessage(message_id, obj){
    

    BootstrapDialog.show({
        title:"Delete a message",
        message: "are you sure you want to delete this message ?",
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Delete',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                  params={};
                  QB.chat.message.delete(message_id, params, function(err, res) {
                    if (res) {
                      history_delMsg(message_id, dialogRef);
                    }else{
                      console.log(err);
                    }
                  });
            }
        }, {
            label: 'Cancel',
            action: function(dialogRef){
                $("#edit_"+message_id).hide();
                dialogRef.close();
            }
        }]
    });
  }


  function history_delMsg(mid, dialogRef){
    $.ajax({
        url: site_url + 'chat/deleteMessage',
        data: {
          message_id: mid,
          dialogID: dialogID
        },
        success: function(data) {
          $("#bubble_"+mid).remove();
          dialogRef.close();
        },
        type: 'POST'
    });      
  }
 

  function viewQuestion(qid){
    
    location.href = site_url + "questions/question/"+qid;
  }

  function saveBlocklist(){
    $.ajax({
        url: site_url + 'chat/saveBlocklist',
        data: {
          list: JSON.stringify(blocklist)
        },
        success: function(data) {},
        type: 'POST'
    });         
  }

  function blockUser(uid, name){
    if($("#block_"+uid).css('display') == 'none'){
      BootstrapDialog.show({
        title:"Block User",
        message: 'are you sure you want to block "' + name + '" ?',
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Yes',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                 if(JSON.stringify(blocklist).indexOf(uid)<0){
                    blocklist.push(uid); 
                    $('#block_'+uid).css("display", "block");
                    $('#BB_'+uid).text("Unblock"); 
                    saveBlocklist(); 
                  } 
                  dialogRef.close();
            }
        }, {
            label: 'Cancel',
            action: function(dialogRef){
                dialogRef.close();
            }
        }]
      
      });
    }
    else{
 
         if(blocklist.indexOf(uid) >= 0){
            var index = blocklist.indexOf(uid);
            blocklist.splice(index, 1);
            $('#block_'+uid).css("display", "none");
            $('#BB_'+uid).text("Block");        
            saveBlocklist(); 
          } 
    
    }
  }

  function blockAndreport(uid, name){
      BootstrapDialog.show({
        title:"Report User",
        message: 'are you sure you want to block and report "' + name + '" ?',
        type: BootstrapDialog.TYPE_DANGER,
        buttons: [{
            label: 'Yes',
            cssClass: 'btn-danger',
            autospin: true,
            action: function(dialogRef){
                 if(JSON.stringify(blocklist).indexOf(uid)<0){
                    blocklist.push(uid);
                    $('#block_'+uid).css("display", "block"); 
                    $('#BB_'+uid).text("Unblock");      
                    saveBlocklist();
                  } 
                  dialogRef.close();
                  window.open('mailto:jake@relayy.io?subject='+encodeURIComponent('I would like to report ' + name)+'&body='+encodeURIComponent('Please describe the reason you would like to report this user'));
            }
        }, {
            label: 'Cancel',
            action: function(dialogRef){
                dialogRef.close();
            }
        }]
    });
      

  }

  $(document).ready(function(){
    
    $(".sidepanel-close-button").click(function(){
        $(".sidepanel").hide(100);
        $(".chat-body").css("margin-right", "0px");
        
    });
  });

  $(".content").css("padding", "0");


  
  

</script>
<?php if(isset($deleted) && $deleted == 1){ ?>
<center style="margin-top:200px;">Sorry, the chatroom is invalid, perhaps it was deleted.</center>
<?php } else if(isset($d_users)) { ?>

<!-- <div class="section_wrapper"> -->
<div class="white_back desktop-visible-item" style="padding:60px 20px 50px 20px;">
  
  
  <?php if($d_type == 0){ ?>
    <h4 class="pull-left" style="color:gray;margin-top:10px;">How can I help you?</h4>
  <?php } else { ?>
    <h4 class="pull-left" style="color:gray;margin-top:10px;"><?= $d_name?></h4>
  <?php } ?>

  <?php if ($d_owner == "Me" && $d_type != 0) {?>
    <button class="pull-right rb delete-chat" onclick="deleteAction('<?= $d_id?>')"><span class="btn-icon glyphicon glyphicon-trash"></span>DELETE CHAT</button>
  <?php }?>

</div>

<div id="chat-content">
  <div class="mobile-visible-item">
      <?php if($d_type != 0) { ?>
          
          <div class="row border3 padding_xs">
            <div class="col-xs-2 canvas">
              <a href="#" class="chat-list pull-right sidebar-open-button-mobile canvas full-height"><img src="<?= asset_base_url().'/images/chatlist.png' ?>"></a>
              <span class="chat_notification mobile_chat_notification state_badge" id="<?= $u_uid ?>" style="display:none;"></span>
            </div>
            <div class="col-xs-8" style="padding:0px;">
              <center class="blue-text mobile-chat-title"><?= $d_name?></center>
            </div>
            <div class="col-xs-2">
              <button type = "button" class="pull-right dropdown-toggle trans sidepanel-open-button" data-toggle="dropdown" style="padding:2px 6px;height:25px;">
                <span class="light-gray-text glyphicon glyphicon-menu-hamburger"></span>    
              </button>
            </div>
          </div>
      <?php } else { ?>
         
          <div class="row border3 padding_xs">
            <div class="col-xs-2 canvas">
              <a href="#" class="chat-list sidebar-open-button-mobile canvas full-height"><img src="<?= asset_base_url().'/images/chatlist.png' ?>"></a>
              <span class="chat_notification mobile_chat_notification state_badge" id="<?= $u_uid ?>" style="display:none;"></span>
            </div>
            <div class="col-xs-8" style="padding:0px;">
              <center class="blue-text mobile-chat-title">How can I help you?</center>
            </div>
            <div class="col-xs-2">
              <button type = "button" class="pull-right dropdown-toggle trans sidepanel-open-button" data-toggle="dropdown" style="padding:2px 6px;height:25px;">
                <span class="light-gray-text glyphicon glyphicon-menu-hamburger"></span>    
              </button>
            </div>
          </div>
      <?php } ?>


  </div>

  <div class="desktop-visible-item">

    <div class="row chat-header border3 white_back padding_sm fHeight">

      <div class="col-sm-6">
        <?php if($d_type == 0){ ?>
          <span class="line-item blue-text" style="font-size:18px;"><?= $d_name ?></span>
        <?php } else { ?>
          <button class="dropdown-toggle pull-left trans sidepanel-open-button" data-toggle="dropdown">
            <span class="line-item blue-text" style="font-size:18px;"><?= sizeof($d_occupants) ?>   members</span>
          </button>
        <?php } ?>
      </div>

      <div class="col-sm-6">
        <button type = "button" class="pull-right dropdown-toggle ob sidepanel-open-button chat-more-button" data-toggle="dropdown" style="margin-top:-7px;">HIDE DETAILS</button>
      </div>

      

    </div>


  </div>

<!-- =============================================  Chat Body  ================================================= -->


  <div class="row container-widget chat-body fHeight" style="margin-left:0px; margin-right:0px;background:#FFF;">

    <div class="row chat-text canvas">
      <div class="col-md-12" style="height:100%;">
          <div class="ChatName" style="height:100%;" id = "<?= $d_name ?>">
            <div style="height:100%;">
               
                <div class="basic-list image-list infoChatRoom" style="height:100%;" id='<?= json_encode($d_occupants) ?>'>
                  
                      <div class="list-group scrollbar <!-- pre-scrollable --> nice-scroll messages-list"  style="height:95%;margin-top:20px;">
                          <!-- list of chat messages will be here -->
                      </div>
                      <div><img src="<?php echo asset_base_url()?>/images/ajax-loader.gif" class="load-msg"></div>
                </div>

            </div>
          </div>

      </div>
  

      
    </div>

    <div class="row chat-input border1"> 
        <div class="canvas pull-left chat_upload_button">
            <div class="attach-icon"></div>
            <input id="load-img" type="file">
        </div>

        <div class="pull-left chat_text_field full-height">
            <textarea class="full-width" id="message_text" maxlength="1000" style="border:none;resize:none;" placeholder = "Type a message here"></textarea> 
        </div>    
        
        <div class="pull-left chat_send_button">          
            <button type="button" class="sendMsgButton pull-right" onclick="clickSendMessage();"><img src="<?= asset_base_url().'/images/sendmsg.png' ?>"/></button>
        </center>

        
    </div>

    <div role="tabpanel" class="sidepanel fix-x rightbar-padding" style="display:none;overflow-y:scroll;">

      
          <ul class="sidebar-panel nav scrollbar full-height">
                <li id="mobile-right-back" class="padding_xs chat-detail-title">
                  <div class="row">
                    <button class="trans pull-left line-item dropdown-toggle green-t-button sidepanel-close-button" data-toggle="dropdown">
                      <span class="glyphicon glyphicon-menu-left"></span> Back
                    </button>

                    <button class="pull-right rb" onclick="deleteAction('<?= $d_id?>')">
                      <span class="btn-icon glyphicon glyphicon-trash"></span>DELETE CHAT
                    </button>
                  </div>


                </li>

                <li class="border3">

                  <div class="row border3 desktop-visible-item chat-detail"><h2 class="gray-text" style="font-size:22px;">CHAT DETAILS</h2></div>
                  
                  <div class="padding_xs">
                      <?php if($d_qid != 0) {?>
                        <div class="row def-font gray-80"><b class="gray-text">CHAT TYPE:</b>  TEAMUP</div>
                      <?php } else { ?>
                        <div class="row def-font gray-80"><b class="gray-text">CHAT TYPE:</b>  <?php echo $d_type==1?"1:1 CHAT":"GROUP CHAT"; ?></div>
                      <?php } ?>
                      <div class="row def-font gray-80"><b class="gray-text">CREATED BY:</b>  <?= $d_owner ?></div>
                  </div>

                  <?php if($d_qid != 0) {?>
                    <center><button type="button" class="addmember-btn def-font padding_xs" onclick="viewQuestion(<?= $d_qid ?>)">View Question Details</button></center>
                  <?php } ?>

                </li>  
                <?php if($d_type == 2){ ?>
                <li class="border3">
                  <!-- <div class="row padding_xs">
                    <img class="pull-left" src = "<?= asset_base_url().'/images/gChat.png'; ?>">
                    <p class="line-item"><?= sizeof($d_occupants) ?>   members</p>
                  </div> -->
                  <?php if($d_name !== "Private" && ($u_type != 3 || $d_owner == "Me")) {?>
                    <div class="row padding_xs"><center><button onclick="addMember('<?= $d_id?>', <?= str_replace('"', '',json_encode($d_occupants)) ?>)" class="ob"><span class="glyphicon glyphicon-plus"></span>Add Members</button></center></div>
                  <?php } ?>
                </li>  
                <?php } ?>        

                
                <?php   foreach ($d_users as $user) {
                        $username = '';
                        if ($user['fname']) $username = $user['fname']." ".$user['lname'];
                        else {
                            $str_arr = explode("@", $user['email']);
                            $username = $str_arr[0];
                        }
                ?>
                    <li>
                      <div class="row padding_xs chat-user-li" data-toggle="dropdown">
                        
                            <div class="col-xs-3 canvas flexible_height">
                              <div class="pull-left canvas">
                                <center>
                                  <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;height:40px;">
                                  <span class="state_<?= $user['id'] ?> offline"></span>
                                  <span class="block_user" id="block_<?= $user['uid'] ?>" style="display:none;">
                                    <div class="block_mark" style="font-size:10px;">B</div>
                                  </span>
                                </center>
                              </div>
                            </div>
                            <div class="col-xs-7 flexible_height gray-80"><?= $username ?></div>
                            <div class="col-xs-2 flexible_height">
                              <a class="dropdown-toggle" title="<?= $username ?>">
                                <span class="pull-right xs-Img glyphicon glyphicon-exclamation-sign gray-text">
                              </a>
                            
                          <!-- //===========Dropdown Menu -->
                                
                            </div>
        <!-- //===========Dropdown Menu End -->

                        
                      </div>

                      <ul class="dropdown-menu user-pop" style="width:200px;">  
                                    <?php if($user['id'] == $u_id) { ?> <!-- ========== case you (don't pass here) ===== -->

                                      <li style="background:lightblue;overflow:hidden;height:50px;padding:5px;">
                                      <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;heightL40px;">  You
                                      </li>
                                      <li style="margin-left:10px;"><a href="<?= site_url("profile/user/".$user['id'])?>">Profile</a></li>

                                    <?php } else if($user['status'] == USER_STATUS_DELETE) { ?> <!-- ========== case deleted user ===== -->

                                      <li style="background:lightblue;overflow:visible;height:50px;padding:5px;">
                                      <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;heightL40px;float:left"> Deleted User</li>
                                      <li style="margin-left:10px;"><a onclick="removeAction('<?= $d_id?>', '<?= $user['id']?>', '<?= $username?>')">Remove</a></li>

                                    <?php } else if($user['email'] === 'jake@relayy.io'){ ?><!-- ========== case admin1 ===== -->

                                      <li style="background:lightblue;overflow:hidden;height:50px;padding:5px;">
                                        <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;heightL40px;"> Jake (Admin)                                
                                      </li>
                                      <li style="margin-left:10px;"><a onclick = "chatWithUser('<?= $user['email']?>', '<?= $user['id']?>')">Start 1:1 Chat</a></li>
                                      <li style="margin-left:10px;"><a href="<?= site_url("profile/user/".$user['id'])?>">Profile</a></li>

                                    <?php } else if($user['email'] === 'jeff@relayy.io'){ ?><!-- ========== case admin2 ===== -->

                                      <li style="background:lightblue;overflow:hidden;height:50px;padding:5px;">
                                        <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;heightL40px;"> Jeff (Admin)                              
                                      </li>
                                      <li style="margin-left:10px;"><a onclick = "chatWithUser('<?= $user['email']?>', '<?= $user['id']?>')">Start 1:1 Chat</a></li>
                                      <li style="margin-left:10px;"><a href="<?= site_url("profile/user/".$user['id'])?>">Profile</a></li>

                                    <?php } else { ?><!-- ========== case member ===== -->

                                      <li style="background:lightblue;overflow:hidden;height:50px;padding:5px;">
                                        <img class="round" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" style="width:40px;heightL40px;"> <?= $username ?>                                
                                      </li>
                                      <?php if($d_type == 2) { ?>
                                        <li style="margin-left:10px;"><a onclick = "chatWithUser('<?= $user['email']?>', '<?= $user['id']?>')">Start 1:1 Chat</a></li>
                                      <?php } ?>
                                      <li style="margin-left:10px;"><a href="<?= site_url("profile/user/".$user['id'])?>">Profile</a></li>
                                      <?php if(($d_owner == "Me" || $u_type!=3) && $d_type == 2) {?>   
                                        <li style="margin-left:10px;"><a onclick="removeAction('<?= $d_id?>', '<?= $user['id']?>', '<?= $username?>')">Remove</a></li>
                                      <?php } ?>

                                      <li style="margin-left:10px;"><a onclick="blockUser('<?= $user['uid']?>', '<?= $username?>')" id="BB_<?= $user['uid']?>">Block</a></li>
                                      <li style="margin-left:10px;"><a onclick="blockAndreport('<?= $user['uid']?>', '<?= $username?>')">Block and Report</a></li>     
                                      
                                    <?php } ?>
                      </ul>
                    </li>
                <?php } ?>

                <?php if($d_type != 0){ ?>
                <li>
                  <div class="row padding_xs"><center><button onclick="leaveAction('<?= $d_id?>')" class="rb">Leave this chat</button></center></div>
                </li>
                <?php } ?>

                <li class="border1">
                  <div class="row"><h4 class="gray-text"></span>ATTACHMENTS</h4></div>
                  <div class="row attach-div pre-scrollable" style="min-height:300px;">
                  <!-- show attachment files ( name, filesize, poster) -->


                  </div>
                </li>
          </ul>       
          
    </div>
  </div>

  <!-- =============================================  End of Chat Body  ================================================= -->

</div>

<?php } else {?>  
  <p style="text-align:center; width:100%; margin-top:120px;">There is no chatroom for you.</p>
<?php } ?>
       

<script>

  if($(".mobile-visible-item").is(':visible')){//if mobile, hide sidepanel initially
        console.log('passed');
        $(".sidepanel").hide(100);
        $(".chat-body").css("margin-right", "0px");
  }
  else{//if mobile, show
      $(".chat-body").css("margin-right", "300px");
      $(".sidepanel").show(100);
  }


  $('.nav li').click(function(e) {

        $('.nav li').removeClass('active');

        var $this = $(this);
        if (!$this.hasClass('active')) {
            $this.addClass('active');
        }
        //e.preventDefault();
    });

  function resp2(){

    $(".content").css("overflow", "hidden");
    $(".content").css("height", $(window).height());
    $(".chat-input").css("height", 80);

    if($(".mobile-visible-item").is(':visible')){
      $("#top").hide();
      $(".chat-input").css("padding", "0px");
      $("#message_text").css("border", "0px");
      $("#chat-content").prop("class", "chat-content mobile-visible-item canvas white_back");
      $("#chat-content").prop("style", "");
      $(".chat-text").css("height", $(window).height() - 126);
      $(".sidepanel").css("overflow", "scroll");
      $(".sidepanel").css("margin-top", "-60px");
      $(".sidepanel").css("height", $(window).height());

    }
    else{
      $("#chat-content").prop("class", "chat-content desktop-visible-item canvas col-text white_back border1234 radius-item");
      $("#chat-content").prop("style", "margin:20px");
      $(".chat-text").css("height",$(window).height() - 296);
      $(".sidepanel").css("overflow", "scroll");
      $(".sidepanel").css("height", $(window).height() - 216 -2);//chat-input's padding is 15px 0px, so changed 240 to 210

    }
  }
  

  $(window).resize(function() {
    resp2();
  });


   if($("#feed_detail").val() === "HIDE DETAILS") {
            $("#feed_detail").val("VIEW DETAILS");
            $(".feed_toggle").hide();
    } else {
        $("#feed_detail").val("HIDE DETAILS");
        $(".feed_toggle").show();
    }    




    //==============================  Save and Like function ===============================

    function runRealChatting(){

      setTimeout(function(){        
        $.ajax({
        url: site_url + 'chat/GetChatInfo',
        data: {
          dialog_id: dialogID
        },
        success: function(data) {
            if(data === "empty") return;
            var states = JSON.parse(data);
            console.log(data);
            var obj, state;
            for(var i = 0; i<states.length; i++){
              state = states[i];
              if(state.save > 0){
                obj = $("#save_"+state.mid);
                $(obj).show();
                $(obj).find("span").text(state.save);
                if(saved_mids.indexOf(state.mid) > -1) $("#msg-save-"+state.mid).text('unsave');
              }
              else{
                $("#save_"+state.mid).hide();
              }

              if(state.like > 0){
                obj = $("#like_"+state.mid);
                $(obj).show();
                $(obj).find("span").text(state.like);
                if(liked_mids.indexOf(state.mid) > -1){
                  $("#msg-like-"+state.mid).text('unlike');
                  $("#like-icon-"+state.mid).prop("class", "glyphicon glyphicon-thumbs-down");
                }
              }
              else{
                $("#like_"+state.mid).hide();
              }

              if(state.del > 0){
                $("#bubble_" + state.mid).hide();
              }

            }

        },
        type: 'POST'
      });          
        runRealChatting(); 
      }, 5000);  
    }

    
    runRealChatting();

    jQuery(function($) {
      $('.messages-list').on('scroll', function() {
          if($(this).scrollTop() == 0) {
              //alert('top reached');
              var dateSent = null;
              if(dialogsMessages.length > 0){
                dateSent = dialogsMessages[0].date_sent;
              }
              retrieveChatMessages(currentDialog, dateSent);
          }        
      })
    });

    /* Sidepanel Show-Hide */
    $(".sidepanel-open-button").click(function(){
        if($(".chat-body").css("marginRight") == "0px"){
          $(".chat-more-button").text("HIDE DETAILS");
          $(".chat-body").css("margin-right", "300px");
          if($(".mobile-chat-item").is(':visible')){
            $("#top").hide();
            $(".sidepanel").css("margin-top", "-60px");
          }
        }
        else{
          $(".chat-more-button").text("VIEW DETAILS");
          $(".chat-body").css("margin-right", "0px");
        }
        $(".sidepanel").toggle(100);
    });

    $(".sidebar-open-button-mobile").click(function(){
        $(".sidebar").toggleClass("sidebar-leftin");
        if($(".mobile-visible-item").is(':visible') && $(".sidebar-leftin").is(':visible')){
          $("#top").show();
          $(".content").css("margin-top", "0px");
          $(".sidebar-open-button-mobile").css("margin-top", "0px");

        }
        
    });
    $(document).ready(function() {
      setTimeout(function(){
        resp2();
      }, 200);
    });

    $(".content").removeClass("scrollbar");

    $("#message_text").autogrow();
    

</script>









