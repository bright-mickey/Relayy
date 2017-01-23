



<div class="profile-container">
<div class="container-widget" style="height:100vh;padding:0px 30px 0px 30px;">


	<div class="row" style="margin-bottom:20px;">
		<div class="col-md-3 col-sm-3 col-xs-5">
			<img src = "<?= strlen($to_photo)>0?$to_photo:asset_base_url().'/images/emp-sm.jpg' ?>" class="pull-right round" width="100" height="100" />
		</div>
		<div class="col-md-9 col-sm-9 col-xs-7">
			<h3><?= $to_name ?></h3>
		</div>
	</div>
	<div class="row question-about">
		<div class="col-md-2 col-sm-2 col-xs-3">
			<img src = "<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp-sm.jpg' ?>" class="round" width="50" height="50"/>
		</div>
		<div class="col-md-10 col-sm-10 col-xs-9" style="height:150px;">
			<textarea class="Qinput border1234" id="review_text" maxlength="1000" style="width:100%;height:100%;padding:15px;" onkeypress="review_typing(event, this)" placeholder="Enter your review here..."></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2 col-sm-2 col-xs-3"></div>
		<div class="col-md-10 col-sm-10 col-xs-9"
			<span id="review_length">0/1000</span>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<button type="button" class="btn btn-primary pull-right" id="review_submit_button" onclick="sendReview(<?= $current_id ?>, <?= $to_id ?>)">SUBMIT REVIEW</button>
		</div>

	</div>

</div>
</div>

<script>
	$("#review_text").focus();
	
	function review_typing(e, object) {
      var len = $(object).val().length;
	   $("#review_length").text(len + '/1000');
  	}

	function sendReview(fid, tid){
		$("#review_submit_button").attr('disabled', true);
		var review = $("#review_text").val();
	              	$.ajax({
			           url: site_url + 'profile/addReview',
			           data: {             
			              from_id: fid,
			              to_id: tid,
			              review:  review          
			           },
			           success: function(data) {			              
			              window.history.back();
			           },
			           type: 'POST'
			        });
	}

	function companyProfile(c_id){
        $.ajax({
           url: site_url + 'profile/companyProfile',
           data: {             
              c_id: c_id             
           },
           success: function(data) {
              $(".container-widget").html(data);
           },
           type: 'POST'
        });
    }
</script>
