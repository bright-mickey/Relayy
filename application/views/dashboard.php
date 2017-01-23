<script>

	function showComments(who){
		if(who == 1){
			$("#SavedBySelf").css("background","transparent");
			$("#SavedBySelf").css("border","0px");
			$("#SavedByOthers").css("background","#DDD");
			$("#SavedByOthers").css("border-left","1px solid #BBB");
			$("#SavedByOthers").css("border-bottom","1px solid #BBB");
		}else{
			$("#SavedByOthers").css("background","transparent");
			$("#SavedByOthers").css("border","0px");
			$("#SavedBySelf").css("background","#DDD");
			$("#SavedBySelf").css("border-right","1px solid #BBB");
			$("#SavedBySelf").css("border-bottom","1px solid #BBB");
		}
		$("#saved_comments").html("<center>Loading...</center>");
		$.ajax({
           url: "<?php echo site_url(); ?>" + 'profile/savedComments',
           data: {             
              flag: who         
           },
           success: function(data) {			              
              $("#saved_comments").html(data);
           },
           type: 'POST'
        });
	}

	showComments(1);// Default, shows comments for saved by self.


</script>
<div class="white_back" style="padding:100px 20px 50px 20px;margin:-10px;">
	<h3 class="pull-left gray-text" style="margin-top:10px;">DASHBOARD</h3>
	<div class="pull-right">
		<a href="<?php echo site_url('profile')?>"><button class="btn" type="button"><img class="pull-left btn-icon" src="<?= asset_base_url().'/images/manageuserh.png' ?>">My Profile</button></a>
	</div>
</div>
<div class="col-text white_back border1234 radius-item">
	<div class="container-widget" style="margin:0px;line-height:1.5;">
	<?php if($u_status == USER_STATUS_DELETE) { ?>
	    <p style="text-align:center; width:100%; margin-top:20px;">Your account has been removed.</p>
	<?php } else { ?>

		<div class="row padding_xs">
                <div class="col-md-2 col-xs-12 padding_xs" style="text-align:center;">
                  <img class="preview-Img" src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp.jpg'?>" style="border-radius:100%;">
                </div>
                <div class="col-md-7 col-xs-12 container-widget">
                  <div class="row">
                  	<div class="col-xs-4"><p class="font-20 gray-text">Name: </p></div>
                    <div class="col-xs-8"><p><b><?= $u_fname?> <?= $u_lname?></b></p></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4"><p class="font-20 gray-text">Role: </p></div>
                    <div class="col-xs-8">
                      <p class="">
                      <b>
                      <?php if($u_type == 1) echo "Admin";
                      else if($u_type == 2) echo "Advisor";
                      else if($u_type == 3) echo "Entrepreneur";
                      else echo "Moderator";?>
                      </b>
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-4"><p class="font-20 gray-text">Bio: </p></div>
                    <div class="col-xs-8"><p class=""><?= $u_bio?></p></div>
                  </div>

                </div>
                <div class="col-md-3 col-xs-12">
                	
	                <div class="col-xs-6 padding_xs">
	                   <a href="<?php echo site_url('setting')?>"><button class="btn full-width" type="button"><center><img class="image-Item" src="<?= asset_base_url().'/images/notifications.png' ?>"><div>Notifications</div></center></button></a>
	                </div>
	                <div class="col-xs-6 padding_xs">
	                   <a href="<?php echo site_url('setting/profile')?>"><button class="btn full-width" type="button"><center><img class="image-Item" src="<?= asset_base_url().'/images/settings.png' ?>"><div>Settings</div></center></button></a>
	                </div>
                </div>
        </div>


		<div class="row div-item border1" style="margin-top:0px;">
	        <h4>PROFILE STATS:</h4>
	    </div>

	    <div class="row">

	      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
	          <div class="col-xs-9">
	            <p class="gray-text">TEAMUP CHATS: JOINED</p>
	          </div>
	          <div class="col-xs-3">
	            <p class="font-20"><?= $entered_chats?></p>
	          </div>
	      </div>

	      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
	          <div class="col-xs-9 Qinput">
	            <p class="gray-text">TEAMUP CHATS: COMMENTS ADDED</p>
	          </div>
	          <div class="col-xs-3">
	            <p class="font-20"><?= $self_comments?></p>
	          </div>
	      </div>

	      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
	          <div class="col-xs-9 Qinput">
	            <p class="gray-text">TEAMUP CHATS: COMMENTS SAVED</p>
	          </div>
	          <div class="col-xs-3">
	            <p class="font-20"><?= $other_comments?></p>
	          </div>
	      </div>

	      <div class="col-md-6 col-xs-12" style="padding:0px 20px;">
	          <div class="col-xs-9 Qinput">
	            <p class="gray-text">NUMBER OF REVIEWS</p>
	          </div>
	          <div class="col-xs-3">
	            <p class="font-20"><?= $reviews?></p>
	          </div>
	      </div>

	    </div>



		<div class="row border1">

		    <div class="col-xs-6" style="padding:0px;"><button type="button" class="full-width" id="SavedBySelf" onclick="showComments(1)" style="height:60px;">MESSAGES SAVED BY SELF</button></div>
		    <div class="col-xs-6" style="padding:0px;"><button type="button" class="full-width" id="SavedByOthers" onclick="showComments(2)" style="height:60px;">MESSAGES SAVED BY OTHERS</button></div>

		</div>

		<div class="row no_padding" style="max-height:80vh;margin-bottom:50px;">
	  		<div class="col-xs-12 no_padding full-height">
	  			 <div class="accordion col-text full-height" id="saved_comments">

	  			 	<!--- Show saved by me or other's comments here-->

	  			 </div>

	  		</div>

		</div>



	<?php } ?>




	</div>
</div>
<script>
 	showComments(1);// Default, shows comments for saved by self.
</script>


