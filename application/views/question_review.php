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
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/responsive1.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/style2.css" type="text/css">
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">


      <script src="<?php echo asset_base_url()?>/libs/jquery.min.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/plugins.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/bootstrap-select.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/libs/jquery.nicescroll.min.js" type="text/javascript"></script>
      <srcipt src="<?php echo asset_base_url()?>/libs/jquery.timeago.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="<?php echo asset_base_url()?>/js/ui_helpers.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/dialogs.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/messages.js" type="text/javascript"></script>
      <script src="<?php echo asset_base_url()?>/js/questions.js" type="text/javascript"></script>
    


<?php 
    if (isset($profile_js)) {?>
      <link rel="stylesheet" href="<?= asset_base_url()?>/css/jquery.fileupload.css" type="text/css">
<?php  }
?>
   </head>

<body class="body scrollbar" style="font-family:'proximanovar';min-height:100%;">

	<script>
		function viewFeedDetail(){
		   if($("#feed_detail").val() === "HIDE DETAILS") {
		            $("#feed_detail").val("VIEW DETAILS");
		            $(".feed_toggle").hide();
		        } else {
		            $("#feed_detail").val("HIDE DETAILS");
		            $(".feed_toggle").show();
		        }    
		}

    function fullSize(obj){
        $('.attached-Img').prop("class", "preview-Img attached-Img");
        $(obj).prop("class", "fullsize-Img attached-Img");    
      }
	</script>
  <div class="profile-container">
    <?php if(isset($exist) && $exist === "no"){ ?>
      <center><p style="margin-top:150px;">The question doesn't exist now. Perhaps it will be deleted.</p></center>
    <?php } else { ?>
	  <div class="row border-style-small" style="margin:0px;">

      <h4 class="padding_sm context_title"><?= $question['title']?></h4>
      <h6 class="Qinput"><?= $question['context']?></h6>



      <div class='row feed_toggle' style="display:none;">
        <h5>Links attached</h5>
        <div class="row">
          <?php foreach(json_decode($question['links']) as $link) { ?>
            <a target="_blank" href="<?= $link?>" class="green_tags Qinput"><?= $link?></a>
          <?php } ?>
        </div>
        <h5>Files Attached</h5>
        <?php foreach(json_decode($question['filename']) as $file) {
                        $spl=explode('.',$file);
                        $file_ext = $spl[count($spl) - 1];
                        $expensions= array("jpeg", "jpg", "png", "PNG", "JPG", "JPEG");
                       ?>
                          <div class="pull-left preview-Img canvas image-Item">
                          <?php if(in_array($file_ext, $expensions)=== true){ ?>
                            <img class="preview-Img attached-Img" src="<?= uploads_base_url().$file?>" alt="<?= $file ?>"/>
                            <button type="button" class="expand-Img trans" style="display:none;"><span class="glyphicon glyphicon-zoom-in"></span></button>
                            <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                              <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                            </a>
                          <?php } else if($file_ext == "pdf") { ?>
                            <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/pdf.png' ?>" alt="<?= $file ?>"/>
                            <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                              <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                            </a>
                          <?php } else if($file_ext == "gif") { ?>
                            <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/gif.png' ?>" alt="<?= $file ?>"/>
                            <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                              <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                            </a>
                          <?php } else { ?>
                            <img class="preview-Img attached-Img" src="<?= asset_base_url().'/images/file.png' ?>" alt="<?= $file ?>"/>
                            <a href="<?= uploads_base_url().$file?>" target="_blank" download="download" class="def-text">
                              <button type="button" class="download-Img trans" style="display:none;"><span class="glyphicon glyphicon-download-alt"></span></button>
                            </a>
                          <?php } ?>
                            
                          </div>
          <?php } ?>

        
      </div>



      <div class="row" style="margin:0px 10px 0px 10px;">
        <div class="col-xs-6">
          <input type="button" class="pull-left ob" id="feed_detail" value="VIEW DETAILS" onclick="viewFeedDetail()"/>
        </div>
        <div class="col-xs-6">
          <p class="col-md-6 col-sm-6 col-xs-12 pull-right gray-text" style="margin-top:5px; text-align:center;" id="feed-time"><?= date("D M d, Y h:i A", $question['time']) ?></p>
        </div>
      </div>


      <div class="row">
        <h5>TAGS</h5>
      </div>
      <div class="row" style="padding:5px;">
        <?php if(sizeof($question['tags']) > 0){
        foreach(json_decode($question['tags']) as $tag){?>
          <div class="online_tags Qinput pull-left"><?= $tag ?></div>
        <?php }} ?>
      </div>

    </div>

    <div class="row border-style-small" style="margin:0px;">
      <div class="col-md-2 col-sm-2 col-xs-4">ADVISORS ANSWERING:</div>
      <div class="col-md-9 col-sm-8 col-xs-12">
        <?php if($question['a_ids']) {?>
          <?php foreach(json_decode($question['a_ids']) as $accepter){?>
            <?php foreach($advisors as $advisor){?>
              <?php if($accepter === $advisor['id']){?>     
                <a class="" href="<?= site_url("profile/user/".$advisor['id'])?>" title="<?= $advisor['fname'] ?> <?= $advisor['lname'] ?>">                   
                <img  style="vertical-align:middle;" class="round" width="40" height="40" src="<?= $advisor['photo']?>" /></a>                        
              <?php }?>
            <?php }?>                     
          <?php }?>  
        <?php }?>
      </div>       
    </div>
  <?php } ?>
</div>


</body>
</html>
