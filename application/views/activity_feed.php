<script>
var c_time = <?php echo $download_time ?>;
var last_feed_num = 0;
var recent_feed_num = 0;
</script>
<div class="container-widget">
	
	

	<div class="row white_back" style="padding:80px 20px 0px 20px;margin:-10px;">
		<h3 class="pull-left nm padding_xs gray-text">ACTIVITY FEED</h3>
	</div>

	<div class="row padding_30 feed_div">
		<?php $index = 0;
		foreach($feeds as $feed){ 
			$last_feed_num = $feed[TBL_FEED_NO];
			if($index == 0) $recent_feed_num = $feed[TBL_FEED_NO];
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

	</div>
	<center class="row last_div"><button onclick="loadMoreFeeds()" class="ob loadmorefeedbtn">Load More</button></center>
		  
</div>
<script>
	last_feed_num = <?php echo isset($last_feed_num)?$last_feed_num:0 ?>;
	recent_feed_num = <?php echo isset($recent_feed_num)?$recent_feed_num:0 ?>;

    function update_feed(){
     	
        setTimeout(function(){   
	        $.ajax({
		        url: site_url + 'Users/updateFeeds',
		        data: {
		        	recent_num: recent_feed_num
		        },
		        success: function(data) {
		            $(".feed_div").prepend(data);
		            update_feed_time();
		        },
		        type: 'POST'
		     });          
	        update_feed(); 
        }, 20000);      

    }

	update_feed_time();
    update_feed();  

    function loadMoreFeeds(){
    	$.ajax({
	        url: site_url + 'Users/LoadMoreFeeds',
	        data: {
	        	last_num: last_feed_num
	        },
	        success: function(data) {
	            $(".feed_div").append(data);
	        },
	        type: 'POST'
	     });    
    }

    function update_feed_time(){
    	$(".feed_time").each(function(){
    		var offset = c_time - $(this).attr("data-time");
			var time_ago = "";
			if(offset >= 172800) time_ago = parseInt(offset / 86400, 10) + " days ago";
			else if(offset >= 86400) time_ago = "1 day ago";
			else if(offset >= 7200) time_ago = parseInt(offset / 3600, 10) + " hrs ago";
			else if(offset >= 3600) time_ago = "1 hr ago";
			else if(offset >= 120) time_ago = parseInt(offset / 60, 10) + " mins ago";
			else if(offset >= 60) time_ago = "1 min ago";
			else if(offset > 1) time_ago = offset + " secs ago";
			else time_ago = "1 sec ago";
			$(this).text(time_ago);
    	});
    }

    



    


</script>


