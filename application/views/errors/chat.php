<!-- <div class="section_wrapper"> -->
  <div class="container col-md-8" style="height:100%; position:relative;">
    <div id="main_block" style="height:100%; margin:0; padding: 70px 0 100px">

        
        <div style="margin-left:50px">
              <?php   foreach ($d_users as $user) {
              $username = '';
              if ($user['fname']) $username = $user['fname']." ".$user['lname'];
              else {
                  $str_arr = explode("@", $user['email']);
                  $username = $str_arr[0];
              }
              ?>
                      <a class="" href="<?= site_url("profile/user/".$user['id'])?>" title=<?= $username ?>>
                      <img class="avatar avatar_small" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>"></a>
                <?php if ($d_owner == "Me") {?>
                <a class="information_remove_user" onclick="removeAction('<?= $d_id?>', '<?= $user['id']?>', '<?= $username?>')"></a>
                <?php }?>
                      <span class="">
                        <lastseen data-user-id="4513703"><span class="lastseen"></span></lastseen>
                      </span>
              <?php }?>
        </div>
        <div>

        </div>
        <div class="panel panel-primary" class="margin:10px">
          <div class="panel-body">
            <div class="row">
              
              <div id="mcs_container" class="col-md-12 nice-scroll">
                  <div class="customScrollBox">
                    <div class="container del-style">
                      <div class="content list-group <!-- pre-scrollable --> nice-scroll" id="messages-list">
                        <!-- list of chat messages will be here -->
                      </div>
                    </div>
                  </div>
                  <div><img src="<?php echo asset_base_url()?>/images/ajax-loader.gif" class="load-msg"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row" style="position:absolute; bottom:12px; width:100%;">
            <form class="form-inline" role="form" method="POST" action="" onsubmit="return submit_handler(this)" style=" margin:0 15px;">
              <div class="form-group">
                <input id="load-img" type="file">
                <button type="button" id="attach_btn" class="btn btn-default" onclick="">Attach</button>
                <input type="text" class="form-control" id="message_text" placeholder="Enter message">                
                <button  type="submit" id="send_btn" class="btn btn-default" onclick="clickSendMessage();console.log('dd');">Send</button>
              </div>
              <img src="<?php echo asset_base_url()?>/images/ajax-loader.gif" id="progress">
            </form>
          </div>
        </div>
    </div>