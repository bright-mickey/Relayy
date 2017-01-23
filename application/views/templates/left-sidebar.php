<script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/dialogs.js" type="text/javascript"></script>

<script src="<?php echo asset_base_url()?>/libs/quickblox.min.js"></script>
<script src="<?php echo asset_base_url()?>/js/bootstrap-dialog.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/css/bootstrap-dialog.min.css" type="text/css"></script>
<script src="<?php echo asset_base_url()?>/libs/bootstrap.min.css" type="text/css"></script>

<script src="<?php echo asset_base_url()?>/js/config.js"></script>
<script type="text/javascript">
    
    // Setup an event listener to make an API call once auth is complete
  console.log('running timer');

  
var array_Dialog = [];
  function runtimer(){

      setTimeout(function(){        
        $.ajax({
        url: site_url + 'Users/ActionUpdate',
        data: {
          
        },
        success: function(data) {
            if(data.indexOf('Sorry,') >= 0){
                $(".body").html(data);
                return;  
            }
            console.log("update-dialog-db: ");
            console.log(data);
            StateArray = JSON.parse(data);
            
            for(var i = 0;i<StateArray.length; i++){
              CN = "state_" + StateArray[i]['id'] + " " + StateArray[i]['state'];
              $(".state_"+StateArray[i]['id']).prop("class", CN);
            }

        },
        type: 'POST'
      });          
        runtimer(); 
      }, 15000);  
    }

    
    runtimer();

    function removeBadge(did){
        
        for(var j=0;j<badgeArray.length;j++){
              if(badgeArray[j].toString() === did.toString()){
                badgeArray.splice(j, 1);
                break;
              }
        }

        saveBadgeState(JSON.stringify(badgeArray), $(".state_badge").prop("id"));
        if(badgeArray.length == 0) $(".chat_notification").hide();
        else $(".chat_notification").show();
        
    }

    function gotoChat(did){
      
      //
      $(".content").html("<center style='margin-top:150px;'>Loading dialog...</center>");
      if($(".mobile-visible-item").is(":visible")){
        $(".sidebar").toggleClass("sidebar-leftin");
      }
      $(".list-group-item").removeClass("active");
      $(".list-group-item").removeClass("inactive");
      
      history.pushState({}, null, site_url + 'chat/channel/' + did);
      var URL;
      if(b_QBLogin){
          $.ajax({
            url: site_url + 'chat/channel/' + did + 'p',
            data: {
              
            },
            success: function(data) {
                $(".content").html(data);
                removeBadge(did);
                triggerDialog(did, 1);
                setupAllListeners();
                document.title = "Chat | Relayy";
            },
            type: 'POST'
          });          
      }
      else{
        removeBadge(did);
        location.href = site_url + 'chat/channel/' + did;
      }

    }

    


    function chatWithAdmin(type){
      
      var occupants = []; 
      var dname;  
      if(type ==1) dname="Give feedback";
      else if(type == 2) dname = "Report a problem";
      else if(type == 3) dname = "Requrest a feature";       
      //occupants.push(['jake@relayy.net', '401565172']);
      $.ajax({
               url: site_url + 'chat/checkExist',
               data: {                  
                  occupants: occupants,
                  type:type
               },
               success: function(data) {   
                  if (data === "no_exist"){

                    var params = {
                      type: 1,
                      name: "Private"
                    };


                    QB.chat.dialog.create(params, function(err, createdDialog) {
                      if (err) {
                        console.log(err);
                        alert(JSON.stringify(err));
                      } else {
                        
                          $.ajax({
                             url: site_url + 'chat/newChat',
                             data: {
                                did: createdDialog._id,
                                jid: createdDialog.xmpp_room_jid,
                                type: <?= CHAT_TYPE_GROUP?>,
                                dname: dname,
                                ddesc: "1:1 chat",
                                occupants: occupants,
                                state:"support"
                             },
                             success: function(data) {
                                if (data == "new")
                                  location.href = site_url + 'chat/channel/' + createdDialog._id;
                                else{
                                  location.href = site_url + 'chat/channel/' + data;
                                }
                             },
                             type: 'POST'
                          });
                      }         
                    });

                  }
                  else{
                    location.href = site_url + 'chat/channel/' + data;
                  }
               },
               type: 'POST'
            });
      
              
    }


    function chatWithUser(email, userId){

      BootstrapDialog.confirm({
        title: 'Confirm',
        message: 'are you sure you want to start a chat with this user?',
        type: BootstrapDialog.TYPE_PRIMARY,
        closable: true,
        draggable: true,
        btnCancelLabel: 'Cancel',
        btnOKLabel: 'Yes',
        btnOKClass: 'btn-danger',
        callback: function(result) {
            if(result) {


              $("#startchat").attr('disabled', false);
              var occupants = [];          
              occupants.push([email, userId]);
              $.ajax({
                       url: site_url + 'chat/checkExist',
                       data: {                  
                          occupants: occupants,
                          type: 0
                       },
                       success: function(data) {   
                          if (data === "no_exist"){

                            var params = {
                              type: 1,
                              name: "Private"
                            };


                            QB.chat.dialog.create(params, function(err, createdDialog) {
                              if (err) {
                                console.log(err);
                                alert(JSON.stringify(err));
                              } else {
                                
                                  $.ajax({
                                     url: site_url + 'chat/newChat',
                                     data: {
                                        did: createdDialog._id,
                                        jid: createdDialog.xmpp_room_jid,
                                        type: <?= CHAT_TYPE_PRIVATE?>,
                                        dname: "Private",
                                        ddesc: "1:1 chat",
                                        occupants: occupants
                                     },
                                     success: function(data) {
                                          if (data == "new")
                                            location.href = site_url + 'chat/channel/' + createdDialog._id;
                                          else{
                                            location.href = site_url + 'chat/channel/' + data;
                                          }
                                     },
                                     type: 'POST'
                                  });
                              }         
                            });

                          }
                          else{
                            location.href = site_url + 'chat/channel/' + data;
                          }
                       },
                       type: 'POST'
                    });
            
            }
          }
      });
              
    }

    function ViewInvitePage(){
      $.ajax({
               url: site_url + 'invite/ViewCodePage',
               data: {                  
                  id: currentUser_id,
               },
               success: function(data) {   
                    //$(".sidepanel").hide();
                    $(".content").html(data);
                    $(".content").css("overflow", "auto");
                    if($(".mobile-visible-item").is(':visible')){
                      $(".sidebar").toggleClass("sidebar-leftin");
                    }
                    document.title = "Invite Code | Relayy";
               },
               type: 'POST'
            });
    }

   
 

</script>

<div class="sidebar scrollbar clearfix white_back border2">


    <!-- <div class="col-md-12"> -->
     
    <li class="border3">
            
            <ul class="sidebar-panel nav row">
              <div class="col-xs-9">
                <form class="canvas radius-item full-width border1234" style="height:60px;">
                    <input type="text" class="padding_xs" id="searchbox" placeholder="Search..." style="border:0px;width:70%;margin:10px 10px 10px 30px;"> 
                    <span class="searchbutton pull-left">
                      <span class="icon-search glyphicon glyphicon-search"></span>
                    </span>
                </form>
              </div>
              <div class="col-xs-3 no_padding" style="height:60px;">

                  <div class="dropdown add-chat-button">
                    <button class="dropbtn"><img src = "<?= asset_base_url().'/images/adchat.png'; ?>"></button>
                    <div class="dropdown-content">
                      <a onclick="createChat(1)">Create 1:1 Chat</a>
                      <a onclick="createChat(2)">Create Group Chat</a>
                      <!-- <a onclick="chatWithAdmin(2)">Report a problem</a>
                      <a onclick="chatWithAdmin(3)">Request a feature</a> -->
                    </div>
                  </div>

              </div>

            </ul>
            <ul>
              <div class="row">
                <center class="padding_xs"><button type="button" class="full-width btn" onclick="ViewInvitePage()"> Invite People </button></center>
              </div>
            </ul>
    </li>

    <div id="dialogs-list">
        <div class="full-height">
        <?php 
            if (isset($history) && $u_status == USER_STATUS_LIVE) {
                foreach ($history as $dialog) {
                  if($dialog['status'] == 0) continue;
            ?>
            <ul class="sidebar-panel nav sidechatlist container-widget" style="margin:0;" >
                <a style="height:100%;padding:15px 5px 0px 5px;"
                class="row list-group-item <?php echo $dialog['did'] === $d_id && isset($d_current)?"active":"inactive"; ?> <?= $dialog[TBL_CHAT_STATUS]==CHAT_STATUS_INIT?"deactive":""?>"
                 id="<?= $dialog['did']?>" onclick="gotoChat('<?= $dialog['did'] ?>')">
                    <div class="chat_owner col-xs-2 pull-left canvas">
                        <img class="sidechatowner pull-right" src="<?= strlen($dialog['d_users'][0]['photo'])>0?$dialog['d_users'][0]['photo']:asset_base_url().'/images/emp-sm.jpg'?>">
                        <span class="state_<?= $dialog['d_users'][0]['id'] ?> offline"></span>
                    </div>

                    <div class="col-xs-10" style="height:100%;">
                        <div class="container-widget" style="margin:0;">
                            <div class="row" style="height:18px;margin:10px 0px;overflow:hidden;">
                                <div class="col-xs-8">
                                    <p class="warpword li-dialog-name" style="font-size:16px; color:#111;"><strong class="warpword d_title" style="<?= $dialog['type'] == 0?'color:#4e4ef3':'' ?>"><?= $dialog['name']?></strong></p>
                                </div>
                                <?php if($dialog['type'] != 0){ ?>
                                <div class="col-xs-4">
                                    <span class="send-time pull-right"><?= $dialog[TBL_CHAT_TIME]?></span>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if($dialog['type'] != 0){ ?>
                            <div class="row" style="height:20px;margin:0;overflow:hidden;">
                                <span class="list-group-item-text last-message font-13 gray-text"><?= $dialog['h_message']?></span>
                            </div>
                            <?php } ?>
                            <div class="row" style="height:40px;margin:5px 0px;overflow:hidden;">
                                <div class="col-xs-10 container-widget">
                                    <?php $index = 0;
                                    foreach ($dialog['d_users'] as $user) {
                                        if($index == 0){
                                            $index = $index + 1;
                                            continue;
                                        } ?>
                                        <div class="col-xs-2" style="padding:0px;">
                                            <div class="chat_user pull-left canvas">
                                                <div style="border-radius:100%;overflow:auto;">
                                                  <img src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>">
                                                </div>
                                                <span class="state_<?= $user['id'] ?> offline"></span>
                                            </div>
                                        </div>
                                    <?php }?>    
                                </div>
                                <div class="col-xs-2 pull-right">
                                    <span class="badge pull-right" style="display: none;">0</span>
                                </div>
                            </div>
                        </div>                    
                    </div>
                
                </a>
            </ul>
            <?php                
                      }      
            }
        ?>
        </div>
      </div>
  
</div>
<div class="content scrollbar" style="margin-left:300px;max-height:100vh;overflow-y:scroll;overflow-x:hidden;">

<script>


  $(".list-group-item").prop("class", "list-group-item inactive");
  
  /* Sidebar Show-Hide On Mobile */
  $(".sidebar-open-button-top").click(function(){
    $(".sidebar").toggleClass("sidebar-leftin");

    if(page_title === "Chat | Relayy"){
          $("#top").hide();
      }
  });

  function resp1(){
    $(".sidebar").css("height", $(window).height() - 60);
    $("#dialogs-list").css("overflow", "auto");
    $("#dialogs-list").css("height", $(window).height() - 216);
  }

  $(window).resize(function() {
    resp1();
  });

  resp1();

  $("#searchbox").on('input', function(){
      var text = $(this).val();
      $(".sidechatlist").each(function(){
        if($(this).find(".li-dialog-name").text().toLowerCase().indexOf(text.toLowerCase()) >= 0) $(this).show();
        else $(this).hide();
      }); 
  });

//=========================  Loading Unread state from server to show  ===============
    surl = '<?php echo base_url() ?>';
    $.ajax({
        url: surl + 'chat/getBadges',
        data: {
          uid: currentUser_uid
        },
        success: function(data) {
            if(data.length<3) return;
            badgeArray = JSON.parse(data);
            if(badgeArray.length > 0){
                for(var k=0;k<badgeArray.length;k++){
                    $('#'+badgeArray[k]+'.list-group-item.inactive .badge').text("new").fadeIn(0);
                }    
                $(".chat_notification").css("display", "block");
            }else if(badgeArray.length == 0){
                $(".chat_notification").css("display", "none");
                document.title = "Chat | Relayy"; 
            }

        },
        type: 'POST'
    });   

    $.ajax({
        url: surl + 'chat/getBlockUsers',
        data: {
          uid: currentUser_uid
        },
        success: function(data) {
            if(data.length<3) return;
            blocklist = JSON.parse(data);
            if(blocklist.length > 0){
                for(var k=0;k<blocklist.length;k++){
                    $('#block_'+blocklist[k]).css("display", "block");
                    $('#BB_'+blocklist[k]).text("Unblock");
                }    
            }
        },
        type: 'POST'
    });


//====================== Update left bar ===============
    function update_leftbar(){

      setTimeout(function(){        
        $.ajax({
        url: site_url + 'Chat/UpdateLeftbar',
        data: {
          
        },
        success: function(data) {
            $(".sidebar").html(data);
            $('#'+currentDialogID).removeClass('inactive').addClass('active');
        },
        type: 'POST'
      });          
        update_leftbar(); 
      }, 15000);  
    }

    
    update_leftbar();  
    
</script>






