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


<script>
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
                	if(badgeArray[k] === currentDialogID) continue;
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

    resp1();

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



</script>