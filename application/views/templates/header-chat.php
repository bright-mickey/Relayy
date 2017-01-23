<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      

      <meta property="og:site_name" content="Relayy" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="Relayy" />
      <meta property="og:description" content="Web Chat Application" />
      <meta property="og:url" content="http://dev.relayy.io" />
      <meta property="og:image" content="<?= asset_base_url()?>/images/onlinkedIn.jpg" />
      <meta property="og:image:width" content="245" />
      <meta property="og:image:height" content="150" />      

      <title><?php if(isset($page_title)) echo $page_title; ?></title>
      <link rel="shortcut icon" href="<?= asset_base_url()?>/images/favicon.png">

      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/bootstrap.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/style.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/font-awesome.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/chat.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/demo.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/defaults.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/bootstrap-dialog.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive1.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/font-awesome.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/guiders.css" type="text/css">

      <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" ></script>
      <script src="<?php echo asset_base_url()?>/js/plugins.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/bootstrap-select.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/ui_helpers.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/dialogs.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/messages.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/guiders.js" type="text/javascript"></script>


<?php 
    if (isset($profile_js)) {?>
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/jquery.fileupload.css" type="text/css">
<?php  }
?>
   </head>

    <body class="body" style="font-family:'proximanovar';min-height:100%;overflow-y:hidden;">
        <div id="top" class="clearfix" style="background:#FFF;">
              <div class="pull-left" style="height:100%">
                    <img src="<?= asset_base_url()?>/images/logo.jpg" class="applogo logo-Img">
                    <img src="<?= asset_base_url()?>/images/mlogo.jpg" class="mobilelogo logo-Img" style="padding:5px;">
              </div>

              
              <button data-toggle="dropdown" class="dropdown-toggle btn-profMenu pull-right fix-height" type="button" style="border-left:1px solid #AAA;">
                  <div class="prfile-avatar pull-left"> <img src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp-sm.jpg'?>" class="img-circle" width="30" height="30" style="margin-top:5px;"> </div>
                  <span id="top-right-name" class="gray-text"><?= $u_fname." ".$u_lname ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-list pull-right">
                <li><a href="<?php echo site_url('profile')?>">Profile</a></li>
                <?php if($u_type == 1 || $u_type == 4){ ?>
                  <li><a href="<?php echo site_url('users')?>">User Management</a></li>
                  <li><a href="<?php echo site_url('allow')?>">Chat Management</a></li>
                <?php } ?>
                <li><a href="<?php echo site_url('setting')?>">Notifications</a></li>
                <li><a href="<?php echo site_url('setting/profile')?>">Settings</a></li>
                <li><a href="<?php echo site_url('auth/logout')?>">Log Out</a></li>
              </ul>
              

              <div class="pull-right full-height">

                <ul class="nav nav-tabs full-height">
                  <li class="full-height canvas mobile-visible-item">
                    <a href="#" class="chat-list sidebar-open-button-top full-height">
                      <span class="chat_notification state_badge" style="display:none;"></span>
                    </a>
                    
                  </li>
                  <li class="full-height"><a class="activity full-height" href="<?php echo site_url('users/activity_feed')?>"><p class="desktop-visible-item">ACTIVITY</p></a></li>
                  <li class="full-height"><a class="questions full-height" href="<?php echo site_url('questions')?>"><p class="desktop-visible-item">QUESTIONS</p></a></li>
                  <li class="full-height"><a class="dashboard full-height" href="<?php echo site_url('profile/dashboard')?>"><p class="desktop-visible-item">DASHBOARD</p></a></li>
                </ul>
              </div>
          
        </div>  

        <!-- Some declarations of modal dialogs -->
            <div id="FirstQuestion" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title">Add a Question</h3>
                    <center class="gray-text padding_xs">- All questions asked stay private, and are matched to advisors -</center>
                  </div>
                  <div class="modal-body">
                    
                    <div class="container-widget padding_xs scrollbar" id="channel_edit" style="max-height:70vh;">
                      <div class="row"><h5 class="gray-text">WHAT IS YOUR QUESTION?</h5></div>
                      <div class="row">
                        <input type="text" class="Qinput padding_xs full-width" placeholder="Type question here. You can add details on next page." id="title">
                      </div>
                      <div class="row">
                        <ul id="selected_tag">
                        </ul>
                      </div>
                      <div class="row col-text"><h5 class="gray-text">QUESTION TAGS</h5></div>
                      <div class="row">
                        <input type="text"  class="Qinput padding_xs full-width" placeholder="Add tags to ensure it gets to the most qualified advisors" id="tagname" onkeypress="detect(event, this)">
                        <p class="gray-text padding_xs">Press Enter after each tag to add it to the tag list.</p>
                      </div>

                      <div class="row col-text">
                        <div class="col-xs-6 padding_xs">
                          <button type="button" class="pull-right red-btn teamup-remove-button" style="width:120px;" onclick="closeFirstQuestion()">CLOSE</button>
                        </div>
                        <div class="col-xs-6 padding_xs">
                          <button type="button" class="pull-left online-btn teamup-remove-button" style="width:120px;" onclick="SecondQuestion()">NEXT</button>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div id="SecondQuestion" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title">Add a Question</h3>
                  </div>
                  <div class="modal-body">
                    
                    <div class="container-widget padding_xs scrollbar" style="max-height:70vh;">
                      <div class="row" id="channel_edit">
                        <label class="d-label">
                          <h5 class="gray-text" style="margin-left:10px;">ADD SOME CONTEXT TO YOUR QUESTION</h5>
                          <textarea  class="Qinput padding_xs scrollbar context-area" maxlength="1000" placeholder="The more context surrounding a question, the better the answer. Please type some details here..." id="context-data" autofocus></textarea>
                        </label>
                        <ul id="selected_link" class="wrapword">
                        </ul>
                        <label class="d-label">
                          <h5 class="gray-text" style="margin-left:10px;">ADD WEBSITE LINK</h5>
                            <input   class="Qinput" type="text" placeholder="Paste in a web link and hit Enter" id="web-link"  onkeypress="detect_link(event, this)">
                        </label>
                        <h5 class="gray-text" style="margin-left:10px;">ADD FILES (png, jpg, gifs, pdf)</h5>
                        <div id="add_file">
                          <div class="file-div">
                            <form action="" class="load-img" method="POST" enctype="multipart/form-data">
                               <input type="file" data-file="pp" class="Qinput pull-left addfile" name="FileName" onchange="uploadImage(this)"/>
                            </form>
                            <span class="pull-right big-button" onclick="deleteUploadFile(this);" style="margin:15px 5px;">&times;</span>
                          </div>
                        </div>
                      </div>

                      <div class="row col-text">
                        <div class="col-xs-6 padding_xs">
                          <button type="button" class="pull-right red-btn teamup-remove-button" style="width:120px;" onclick="closeSecondQuestion()">PREVIOUS</button>
                        </div>
                        <div class="col-xs-6 padding_xs">
                          <button type="button" class="pull-left online-btn teamup-remove-button" style="width:120px;" onclick="FinalQuestion()">NEXT</button>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div id="FinalQuestion" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title">PREVIEW YOUR QUESTION</h3>
                  </div>
                  <div class="modal-body">
                    
                    <div class="container-widget padding_xs scrollbar"  style="max-height:70vh;">
                      <div class="row" id="channel_edit">
                        <label class="d-label">
                          <h5 class="gray-text"><b>YOUR QUESTION</b></h5>
                          <h6 class="gray-text Qinput" id="draft_title"></h6>
                        </label>
                        <label class="d-label">
                          <h5 class="gray-text"><b>TAGS</b></h5>
                          <ul id="selected_tag" class="draft_tags">
                          </ul>
                        </label>

                        <label class="d-label">
                          <h5 class="gray-text"><b>CONTEXT</b></h5>
                          <h6 class="Qinput gray-text" id="draft_context"></h6>
                        </label>
                        
                        <label class="d-label">
                          <h5 class="gray-text"><b>WEBSITE LINKS</b></h5>
                        </label>
                        <ul id="web_links" class="wrapword blue-text">
                        </ul>
                        <label class="d-label">
                          <h5 class="gray-text"><b>ATTACHED FILES</b></h5>
                          <ul id="draft_file">
                          </ul>
                        </label>
                        
                      </div>

                      <?php if(strlen($u_group)> 0){ ?>
                        <div class="row">
                          <h5 class="gray-text"><b>YOUR GROUP</b></h5>
                          <?php if(strlen($my_group)>1){ ?>
                            <img class="avatar avatar_small pull-left" src="<?= uploads_base_url().$my_group_image ?>">
                            <h6 class="Qinput gray-text col-text" id="draft_group"><?= $my_group ?></h6>
                          <?php } ?>
                        </div>
                        <?php } ?>

                      <div class="row col-text">
                        <div class="col-sm-4 col-xs-12 padding_xs">
                          <button type="button" class="pull-right red-btn teamup-remove-button" style="width:100%;padding:0px;" onclick="closeFinalQuestion()">PREVIOUS</button>
                        </div>
                        <div class="col-sm-4 col-xs-12 padding_xs">
                          <button type="button" class="pull-left online-btn teamup-remove-button" style="width:100%;padding:0px;" onclick="SubmitToRelayy()">SUBMIT TO RELAYY<div class="teamup-spinner" id="post-relayy-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button>
                        </div>
                        <div class="col-sm-4 col-xs-12 padding_xs">
                          <?php if(strlen($u_group) > 0){ ?>
                            <center><button type="button" class="online-btn teamup-remove-button" style="width:100%;padding:0px;" onclick="SubmitToGroup()">SUBMIT TO GROUP<div class="teamup-spinner" id="post-group-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></center>
                          <?php } ?>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div id="NewGroupChatDialog" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="background: #72B7F8;">
                    <h4 class="modal-title" style="color:white;">Create New Group Chat</h4>
                  </div>
                  <div class="modal-body">
                    <div id="channel_edit">
                        <label class="d-label">
                          <span>DETAILS</span>
                          <input type="text" placeholder="Group chat name" id="groupname" onkeyup="enterhandle(event, this)">
                        </label>
                        <label class="d-label">
                          <span>ADD MEMBERS</span>
                          <div class="row canvas">
                          <input type="text" placeholder="Search email" id="g_add_email" onkeyup="handle(event, this)">
                          <button class="ob pull-right" onclick="add_non_user_to_add()" style="position:absolute;top:5px;right:5px;">add</button>
                          </div>
                        </label>

                        <ul id="g_selected" class="row setting-item">
                        </ul>

                        <ul id="g_contacts" class="scrollbar row setting-item" style="max-height:200px;">
                        </ul>
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align:center;">
                      <button class="btn pull-right wtext" id="NGD_Cancel_Button" onclick="onCancelNG()" style="background: #72B7F8">Cancel</button>
                      <button class="pull-right btn wtext" id="NGD_Create_Button" onclick="onCreateNG()" style="background: #72B7F8"><span class="glyphicon glyphicon-send"></span> Create Chat</button>
                  </div>
                </div>
              </div>
            </div>

            <div id="NewPrivateChatDialog" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="background: #72B7F8;">
                    <h4 class="modal-title" style="color:white;">Create New 1:1 Chat</h4>
                  </div>
                  <div class="modal-body">
                    <div id="channel_edit">
                      <label class="d-label">
                        <span>CHAT WITH</span>
                        <div class="row canvas">
                          <input type="text" placeholder="Search email" id="p_add_email" onkeyup="handle(event, this)">
                          <button class="ob pull-right" onclick="add_non_user_to_private()" style="position:absolute;top:5px;right:5px;">add</button>
                        </div>
                      </label>

                      <ul id="p_selected" class="row setting-item">
                      </ul>

                      <ul id="p_contacts" class="scrollbar row setting-item" style="max-height:200px;">
                      </ul>
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align:center;">
                      <button class="btn pull-right wtext" id="NPD_Cancel_Button" onclick="onCancelNP()" style="background: #72B7F8"> Cancel</button>
                      <button class="pull-right btn wtext" id="NPD_Create_Button" onclick="onCreateNP()" style="background: #72B7F8"><span class="glyphicon glyphicon-send"></span> Create Chat</button>
                  </div>
                </div>
              </div>
            </div>

            <div id="AddMemberDialog" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="background: #72B7F8;">
                    <h4 class="modal-title" style="color:white;">Add New Members</h4>
                  </div>
                  <div class="modal-body">
                    <div id="channel_edit">
                        <label class="d-label">
                          <span>ADD MEMBERS</span>
                          <div class="row canvas">
                          <input type="text" placeholder="Search email" id="a_add_email" onkeyup="handle(event, this)">
                          <button class="ob pull-right" onclick="add_non_user_to_add()" style="position:absolute;top:5px;right:5px;">add</button>
                          </div>
                        </label>

                        <ul id="a_selected" class="row setting-item">
                        </ul>

                        <ul id="a_contacts" class="scrollbar row setting-item" style="max-height:200px;">
                        </ul>
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align:center;">
                      <button class="btn pull-right wtext" id="AMD_Cancel_Button" onclick="onCancelAM()" style="background: #72B7F8">Cancel</button>
                      <button class="pull-right btn wtext" id="AMD_Create_Button" onclick="onAddMember()" style="background: #72B7F8"><span class="glyphicon glyphicon-send"></span> Send Invite</button>
                  </div>
                </div>
              </div>
            </div>

            <div id="ShowUserListDialog" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header" style="background: #72B7F8;">
                    <h4 class="modal-title" id="question-popup-title" style="color:white;"></h4>
                  </div>
                  <div class="modal-body">
                    <div id="channel_edit">           
                        <ul id="q_contacts" class="scrollbar row setting-item" style="height:200px;">
                        </ul>
                    </div>
                  </div>
                  <div class="modal-footer" style="text-align:center;">
                      <button class="btn pull-right wtext" onclick="onCancelPopup()" style="background:#72B7F8">Cancel</button>
                  </div>
                </div>
              </div>
            </div>



            
        <script>
        

// Using multiple unit types within one animation.
          
          var site_url = '<?= site_url()?>';
          var linkedIn_API = '<?= gf_linkedIn_api()?>';
          var currentUser_uid = <?php echo $u_uid ?>;
          var currentUser_id = <?php echo $u_id ?>;
          var currentUser_type = <?php echo $u_type ?>;
          var currentUser_email = "<?php echo $u_email ?>";
          var currentUser_group = "<?php echo $u_group ?>";
          var currentUser_name = "<?php echo $u_fname." ".$u_lname ?>";
          var current_time = <?php echo $c_time ?>;
          var currentUser_stime = <?php echo $u_stime ?>;
          var currentUser_gname = "<?php echo isset($my_group)?$my_group:'' ?>";
          var currentDialogID;
          var currentUser_signup_code;
          var page_title = "<?php if(isset($page_title)) echo $page_title; ?>";
          var badgeArray = [];// for unread indicator
          var blocklist = [];// for blocked user list
          var uploads_base_url = "<?php echo uploads_base_url() ?>";
          var toggle_side;
          var b_QBLogin = false;
          var b_login;//login or signup?

          var isMobile = {
              Android: function() {
                  return navigator.userAgent.match(/Android/i);
              },
              BlackBerry: function() {
                  return navigator.userAgent.match(/BlackBerry/i);
              },
              iOS: function() {
                  return navigator.userAgent.match(/iPhone|iPad|iPod/i);
              },
              Opera: function() {
                  return navigator.userAgent.match(/Opera Mini/i);
              },
              Windows: function() {
                  return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
              },
              any: function() {
                  return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
              }
          };
        
          
          $( "#more" ).click(function() {
            if(toggle_side == 10){
              $( "#r-side" ).animate({width: '300px', opacity: 1}, 500 );
              toggle_side = 20;
            } 
            else{
              $( "#r-side" ).animate({width: "0%", opacity: 0}, 500 );
              toggle_side = 10;
            }
            
          });

          $(".sidebar-open-button").click(function(){
            if($(".sidebar").css("display") === "none"){
              $(".sidebar").css("display", "block");
            }
            else{
              $(".sidebar").css("display", "none");
            }
          });


//left sidebar
          $("#chatlistbutton").click(function(){
            $(".sidebar").toggleClass("sidebar-leftin")
          });

          $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
            // Avoid following the href location when clicking
            event.preventDefault();
            // Avoid having the menu to close when clicking
            event.stopPropagation();
            // If a menu is already open we close it
            //$('ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
            // opening the one you clicked on
            $(this).parent().addClass('open');

            var menu = $(this).parent().find("ul");
            var menupos = menu.offset();

            if ((menupos.left + menu.width()) + 30 > $(window).width()) {
                var newpos = -menu.width();
            } else {
                var newpos = $(this).parent().width();
            }
            menu.css({
                left: newpos
            });

        });

        $(document).ready(function() {
            $("#dropdown-toggle").click(function() {
                $(".dropdown-menu.submenu").toggle();
            });
            
           
        });

        $("#dropdown-invite").click(function(e){
          $("#dropdown-invite").addClass("open");
        });
      </script>

      <!-- User tracking data -->

      <script>
          var user_role;
          if(currentUser_type == 1) user_role = "Admin";
          else if(currentUser_type == 2) user_role = "Advisor";
          else if(currentUser_type == 3) user_role = "Entrepreneur";
          else user_role = "Moderator";

          window.inlineManualTracking = {
            uid: currentUser_uid,
            email: currentUser_email,
            username: currentUser_name,
            created: currentUser_stime,  
            updated: 121321233,
            roles: user_role,
            group: "relayy",
            plan: "Standard",
          }

     
      </script>
      <!-- Inline Manual embed code -->
      <script>
        !function(){
          var e=document.createElement("script"),t=document.getElementsByTagName("script")[0];
          e.async=1,e.src="https://inlinemanual.com/embed/player.0d21c072c1bec5a07fc63ef91daf27c4.js",e.charset="UTF-8",t.parentNode.insertBefore(e,t)
        }();
      </script>
        
     