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
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive1.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/font-awesome.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/guiders.css" type="text/css">
      <script src="<?php echo asset_base_url()?>/libs/bootstrap.min.css" type="text/css"></script>

      <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>  
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" ></script>
      <script src="<?php echo asset_base_url()?>/js/plugins.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/bootstrap-select.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
      <srcipt src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/ui_helpers.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/dialogs.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/messages.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/guiders.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/bootstrap.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/quickblox.min.js"></script>
      <script src="<?php echo asset_base_url()?>/js/config.js"></script>


<?php 
    if (isset($profile_js)) {?>
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/jquery.fileupload.css" type="text/css">
<?php  } ?>
   </head>

<body class="body" style="font-family:'proximanovar';min-height:100vh;">
<?php if(isset($invalid) && $invalid == 1){ ?>
  <center><p style="margin-top:150px;">The question doesn't exist now. Perhaps it will be deleted.</p></center>
<?php } else { ?>
	<div class="scrollbar" style="height:100vh;">
  <div class="profile-container" style="margin-top:30px;">
    
      <div class="row">
        <center><img class="preview-logo" src = "<?= asset_base_url()?>/images/e_logo.png"></center>
      </div>

      <div class="row border1234" style="border-radius:4px;">

      	  <div class="row white_back padding_sm" style="margin:0px;">
            <h4 class="context_title" style="color:#444444;"><?= $question['title']?></h4>
            <div class="row">
              <h5>TAGS</h5>
            </div>
            <div class="row padding_xs">
              <?php if(sizeof($question['tags']) > 0){
              foreach(json_decode($question['tags']) as $tag){?>
                <div class="online_tags Qinput pull-left"><?= $tag ?></div>
              <?php }} ?>
            </div>
          </div>

          <div class="row border1 white_back padding_xs" style="margin:0px;">
            <div class="row padding_xs">Question asked by business owner in <?= $location===""?'???':$location ?></div>
            <div class="row padding_xs">
              <div class="col-xs-6 np">INDUSTRY: <?= $industry===""?'???':$industry ?></div>
              <div class="col-xs-6 np"><p class="pull-right"><?= date("D M d, Y h:i A", $question['time']) ?></p></div>
            </div>
          </div>
      </div>
      

      <div class="row radius-item white_back border1234" style="margin:60px 0px;">
          <div class="row padding_xs">
            <div class="col-xs-12"><center><h4 style="color:#0073f7;">VIEW QUESTION DETAILS AND JOIN IN THE CONVERSATION:</h4></center></div>
            <div class="col-xs-12 padding_xs"><center>Advisors and business owners can sign up in less than 60 seconds.</center></div>
            <div class="col-xs-12 padding_xs"><center>Start asking and answering questions for free.</center></div>
            <div class="col-xs-12 padding_xs desktop-visible-item">
                <div class="col-xs-3"></div>
                <div class="col-xs-6"><center><a href="<?php echo site_url() ?>"><button type="button" class="ob">SIGN UP FOR FREE ACCOUNT</button></a></center></div>
                <div class="col-xs-3"></div>
                  
            </div>

            <div class="col-xs-12 padding_xs mobile-visible-item">
                <center><a href="<?php echo site_url() ?>"><button type="button" class="ob">SIGN UP FOR FREE ACCOUNT</button></a></center>
            </div>
            

            <div class="col-xs-12"><center><h4 style="color:#0073f7;">WHERE DID THIS QUESTON COME FROM?</h4></center></div>
            <div class="col-xs-12 padding_xs"><center>The question listed above was asked by a business owner on Relayy. Registered users can see the full question details and participate in the conversation.</center></div>
            <div class="col-xs-12 padding_xs desktop-visible-item">
              <div class="col-xs-3"></div>
              <div class="col-xs-6 padding_xs"><center><a href="<?php echo ($u_type==1 || $u_type==4)?site_url().'questions/question/'.$question['id']:site_url().'questions/feed/'.$question['id'] ?>"><button type="button" class="btn">View Detailed Question</button></a></center></div>
              <div class="col-xs-3"></div>
            </div>
            <div class="col-xs-12 padding_xs mobile-visible-item">
              <center><a href="<?php echo ($u_type==1 || $u_type==4)?site_url().'questions/question/'.$question['id']:site_url().'questions/feed/'.$question['id'] ?>"><button type="button" class="btn">View Detailed Question</button></a></center>
            </div>
          </div>

          <div class="row border1 white_back padding_xs">
            <div class="col-xs-12"><center><h4 style="color:#0073f7;">WHAT IS RELAYY?</h4></center></div>
            <div class="col-xs-12 padding_xs"><center>Relayy is on-demand advice for business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.</center></div>
            <div class="col-xs-12 padding_xs"><center>Learn more about Relayy here: <a href = "http://relayy.io">http://relayy.io</a></center></div>

          </div>
      </div>



  </div>
<?php } ?>
</body>
</html>
