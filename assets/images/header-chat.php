<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
      <title><?php if(isset($page_title)) echo $page_title; ?></title>
      <link rel="shortcut icon" href="<?= asset_base_url()?>/images/favicon.png">

      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/bootstrap.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/style.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/libs/font-awesome.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/chat.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/demo.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/defaults.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/bootstrap-dialog.min.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive1.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">


      <script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/plugins.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/bootstrap-select.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
      <srcipt src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="<?php echo asset_base_url()?>/js/manage.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/ui_helpers.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/dialogs.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/messages.js" type="text/javascript"></script>
      


<?php 
    if (isset($profile_js)) {?>
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/jquery.fileupload.css" type="text/css">
<?php  }
?>
   </head>

    <body class="body" style="font-family:'proximanovar';min-height:100%;">
        <div id="top" class="clearfix" style="background:#22B;">
          <div class="pull-left" style="height:100%">
                <img src="<?= asset_base_url()?>/images/tlogo.png" class="applogo" style="height:100%;width:auto;">
                <img src="<?= asset_base_url()?>/images/logo.png" class="mobilelogo" style="width:50px;height:50px;margin:5px;">
          </div>

          
          <button class="btn-profMenu pull-right" type="button" style="height:100%;">
            <div class="prfile-avatar pull-left"> <img src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp-sm.jpg'?>" class="img-circle" width="30" height="30" style="margin-top:5px;"> </div>
            <span style="font-size:16px;"><?= $u_fname." ".$u_lname ?></span>
          </button>

          
        </div>          
            
        <script>
        

// Using multiple unit types within one animation.

          var currentUser_uid = <?php echo $u_uid ?>;
          var currentUser_id = <?php echo $u_id ?>;
          var currentUser_type = <?php echo $u_type ?>;

          var badgeArray = [];// for unread indicator
          var blocklist = [];// for blocked user list
          
          var toggle_side;
          
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

        
        


      </script>
        
     