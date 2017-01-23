<script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/libs/quickblox.min.js"></script>
<script src="<?php echo asset_base_url()?>/js/bootstrap-dialog.min.js" type="text/javascript"></script>
<script src="<?php echo asset_base_url()?>/js/config.js"></script>
<script src="<?php echo asset_base_url()?>/js/messages.js"></script>
<script src="<?php echo asset_base_url()?>/js/ui_helpers.js"></script>
<script src="<?php echo asset_base_url()?>/js/dialogs.js"></script>
<script src="<?php echo asset_base_url()?>/js/users.js"></script>

<script>
    var QBUser = {
            id: "<?php if (isset($u_uid)) echo $u_uid?>",
            name: '<?php if (isset($u_name)) echo $u_name?>',
            email: '<?php if (isset($u_login)) echo $u_login?>',
        };
    
    var DialogID = '<?php if (isset($d_id)) echo $d_id?>';
    var DialogJID = '<?php if (isset($d_jid)) echo $d_jid?>';
    var DialogStatus = <?= isset($d_status)&&$d_status==CHAT_STATUS_LIVE?"1":"0"?>; 

    $(".scrollbar").hover(function(){
      $(this).css("overflow-y", "scroll");
      $(this).focus();
    }, function(){
      $(this).css("overflow-y", "hidden");
    });

    function getMsgDate(msgDate){// eg. Wed Jun 08 2016 19:20:04 => Wed Jun 08 7:20pm
        var elems = msgDate.split(" ");
        var tArray = elems[elems.length - 2].split(":");
        var h = tArray[0];
        var ap = h > 11 ? ' PM' : ' AM';
        h = h > 12 ? h - 12 : h;
        var m = tArray[1];
        return msgDate.substring(0,11) + h + ":" + m + ap;
    }


    $("div.preview-Img").hover(function(){
        //if($(this).hasClass("fullsize-Img")) return;
        $(this).find("img").css("opacity", "0.1");
        $(this).find(".download-Img").css("display","block");
        $(this).find(".expand-Img").css("display","block");
    }, 
    function(){
        $(this).find("img").css("opacity", "1");
        $(this).find(".download-Img").css("display","none");
        $(this).find(".expand-Img").css("display","none");
    });

    $("div.fullsize-Img").hover(function(){
        //if($(this).hasClass("fullsize-Img")) return;
        $(this).find("img").css("opacity", "0.1");
        $(this).find(".download-Img").css("display","block");
        $(this).find(".expand-Img").css("display","block");
    }, 
    function(){
        $(this).find("img").css("opacity", "1");
        $(this).find(".download-Img").css("display","none");
        $(this).find(".expand-Img").css("display","none");
    });

    $(".expand-Img").click(function(){
        $('.attached-Img').prop("class", "preview-Img attached-Img");
        $('.attached-Img').parent().prop("class", "pull-left preview-Img canvas image-Item");
        $(this).css("opacity", "1");
        $(this).find(".download-Img").css("display","none");
        $(this).find(".expand-Img").css("display","none");
        $(this).parent().removeClass("preview-Img");
        $(this).parent().addClass("fullsize-Img");
        $(this).parent().find('img').prop("class", "fullsize-Img attached-Img"); 
    });

    //======================  focus events ===========

    $(".messages-list").hover(function(){
        $(".messages-list").focus();
    });

    $("#sidepanel").hover(function(){
        $("#sidepanel").focus();
    });

    $(".content").hover(function(){
        $(".content").focus();
    });

    $("#dialogs-list").hover(function(){
        $("#dialogs-list").focus();
    });

    //====================== Hide top bar for chat room to expand in area  ========


    $(".link-item").hover(function(){
        $(this).find("img").css("opacity", 0.1);
        $(this).find(".row").show();
      }, function(){
        $(this).find("img").css("opacity", 1);
        $(this).find(".row").hide();
      });


    <?php
    $djids = array();
    $duids = array();
    $duidFlag = array();
    foreach ($history as $dialog) {
        $djids[] = $dialog[TBL_CHAT_JID];
        foreach ($dialog['d_users'] as $duser) {
            if (!in_array($duser[TBL_USER_EMAIL], $duidFlag)) {
                $nameArr = explode("@", $duser[TBL_USER_EMAIL]);
                $fname = $nameArr[0];
                $duids[] = array("id"=>$duser[TBL_USER_UID], "sid"=>$duser[TBL_USER_ID], "photo"=>$duser[TBL_USER_PHOTO]?$duser[TBL_USER_PHOTO]:asset_base_url()."/images/emp-sm.jpg", "name"=>$duser[TBL_USER_FNAME]?$duser[TBL_USER_FNAME]." ".$duser[TBL_USER_LNAME]:$fname);
                $duidFlag[] = $duser[TBL_USER_EMAIL];    
            }
        }
    }      
    ?>
    var DialogUIDS = <?= json_encode($duids)?>;

    var DialogJIDS = <?= json_encode($djids)?>;

</script>

<?php if(!isset($public)){ ?>
    <script src="<?php echo asset_base_url()?>/js/connection.js"></script>
<?php } 
    if (isset($profile_js)) {?>
        <script src="<?php echo asset_base_url()?>/js/jquery.ui.widget.js"></script>
        <script src="<?php echo asset_base_url()?>/js/jquery.iframe-transport.js"></script>
        <script src="<?php echo asset_base_url()?>/js/jquery.fileupload.js"></script>
        <script>
        /*jslint unparam: true */
        /*global window, $ */
            $(function () {
                'use strict';
                // Change this to the location of your server-side upload handler:
                var url = '<?= site_url("profile/upload")?>';
                $('#img-file').fileupload({
                    url: url,
                    dataType: 'json',
                    done: function (e, data) {
                        $.each(data.result.files, function (index, file) {
                            console.log("###################");
                            // thumbnailUrl
                            console.log(file);
                            $("#user_pic").attr("src", file.thumbnailUrl);
                            $("#user_pic_info").val(file.thumbnailUrl);
                        });
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                    }
                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });
        </script>

<?php  }
if ($body_class == "users-page") {?>

    <script>
        function delAction(obj, email) {
            var delObj = $(obj);
            
            BootstrapDialog.confirm({
                title: 'Confirm',
                message: 'are you sure you want to delete this user: '+email+' ?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Delete',
                btnOKClass: 'btn-danger',
                callback: function(result) {
                    if(result) {
                        
                        //location.href = delObj.data("act");
                        $.ajax({
                               url: delObj.data("act"),
                               data: {
                                  
                               },
                               success: function(data) {
                                mydata = data.split("\\");
                                  var params = { 'email': "quickblox@relayy.net", 'password': "relayy98IU"};
                                    QB.login(params, function(err, user){
                                      if (user) {
                                        // success
                                                QB.users.delete(mydata[0], function(err, user){
                                                  location.reload();
                                                });
                                      } else  {
                                        // error
                                        alert('error');
                                      }
                                    });
                                  
                               },
                               type: 'POST'
                            });
                    }
                }
            });
        }

        function sendInvite(userType) {
            var sendEmail = $("#invite_txt").val();
            if (sendEmail == '') {
                BootstrapDialog.alert({
                    title: 'WARNING',
                    message: 'Email address is empty!',
                    type: BootstrapDialog.TYPE_WARNING,
                    closable: true,
                    draggable: true,
                    buttonLabel: 'Cancel'
                });
                return;     
            }
            if(!validateEmail(sendEmail)){
                alert(sendEmail + " is invalid email.");
                return;
            }
            var roleTxt = "";
            if (userType == 11) roleTxt = "an Admin";
            else if (userType == 12) roleTxt = "an Advisor";
            else if (userType == 13) roleTxt = "an Entrepreneur";
            else if(userType == 14) roleTxt = "a Moderator";

            BootstrapDialog.show({
                title: 'Send Email Invite',
                message: 'Are you sure you want to invite to email: '+sendEmail+' as '+roleTxt+' ?',
                type: BootstrapDialog.TYPE_INFO,
                buttons: [{
                    icon: 'glyphicon glyphicon-send font-10',
                    label: ' Send Invite',
                    cssClass: 'pull-right btn-info',
                    autospin: true,
                    action: function(dialogRef){
                        dialogRef.enableButtons(false);
                        dialogRef.setClosable(false);
                        $("#invite_txt").val('');
                        location.href = site_url + "users/invite/"+ userType + "/" + encodeURIComponent(sendEmail) + "/<?php echo isset($page)?$page:'0';?>";  
                    }
                }, {
                    label: 'Cancel',
                    action: function(dialogRef){
                        dialogRef.close();
                    }
                }]
            });


        }     
    </script>
<?php } else if($body_class === "feed-page"){ ?>
    

<?php } else if ($body_class == "allow-page" || $body_class == "chat-page") {?>
    <script>
        function delAction(obj, did, dname) {
            var delObj = $(obj);
            
            BootstrapDialog.confirm({
                title: 'Confirm',
                message: 'are you sure you want to delete this chat room: '+dname+' ?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Delete',
                btnOKClass: 'btn-danger',
                callback: function(result) {
                    if(result) {
                        location.href = delObj.data("act");
                    }
                }
            });
        }
    </script>

<?php } ?>
  

