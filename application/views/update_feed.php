<script>
	c_time = <?php echo $download_time ?>;
</script>

		<?php
		$index = 0;
		foreach($feeds as $feed){ 
			if($new == 0) $last_feed_num = $feed[TBL_FEED_NO];
			if($index == 0 && $new == 1) $recent_feed_num = $feed[TBL_FEED_NO];
			$index++;
			?>
		<div class="row feed_row padding_3" data-num="<?= $feed[TBL_FEED_NO] ?>">
			<div class="col-md-1 col-xs-2 feed_photo mid-text pull-left padding_3">
				<a title="<?= $feed[TBL_FEED_WHO_BIO] ?>" href="<?= site_url().'profile/user/'.$feed[TBL_FEED_WHO_ID] ?>"><img src="<?= strlen($feed[TBL_FEED_PHOTO])>0?$feed[TBL_FEED_PHOTO]:asset_base_url().'/images/emp.jpg'?>" class="img-circle" width="30" height="30"></a>
			</div>
			<div class="col-md-9 col-xs-7 feed_info pull-left padding_xs">
				<div class="feed_text">

					<?php $text="";
						$a_who = "<a title='".$feed[TBL_FEED_WHO_BIO]."' href='".site_url()."profile/user/".$feed[TBL_FEED_WHO_ID]."'>".$feed[TBL_FEED_WHO]."</a>";
						$a_whom = "<a title='".$feed[TBL_FEED_WHOM_BIO]."' href='".site_url()."profile/user/".$feed[TBL_FEED_WHOM_ID]."'>".$feed[TBL_FEED_WHOM]."</a>";
						if($feed[TBL_FEED_TYPE] == 1){
							$text = $a_who." asked a question";
						}
						else if($feed[TBL_FEED_TYPE] == 2){
							$text = $a_who." joined a TeamUp chat to help ".$a_whom;
						}
						else if($feed[TBL_FEED_TYPE] == 3){
							$text = $a_who." saved a comment from ".$a_whom." in a TeamUp chat";
						}
						else if($feed[TBL_FEED_TYPE] == 4){
							$text = $a_who." was reviewed by ".$a_whom;
						}
						else if($feed[TBL_FEED_TYPE] == 5){
							$text = $a_who." joined Relayy";
						}
						echo $text;
					?>
				</div>
				<?php if(strlen($feed[TBL_FEED_TAG]) > 0){ ?>
					<div class="feed_tags">
						<?php foreach(json_decode($feed[TBL_FEED_TAG]) as $tag){ ?>
							<div class="online_tags pull-left"><?= $tag ?></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-2 col-xs-3 feed_time padding_xs gray-text" data-time="<?= $feed[TBL_FEED_TIME] ?>" style="text-align:right;">
				
			</div>
		</div>
		<?php } ?>

<script>
	<?php if(isset($last_feed_num)) { ?>
		last_feed_num = <?php echo $last_feed_num ?>;
	<?php } ?>
	<?php if(isset($recent_feed_num)) { ?>
		recent_feed_num = <?php echo $recent_feed_num ?>;
	<?php } ?>
	<?php if($index == 0 && $new == 0) { ?>
		$(".loadmorefeedbtn").hide();
	<?php } ?>

	update_feed_time();
</script>

	


