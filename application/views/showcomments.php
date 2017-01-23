<?php $index = 0 ?>
<?php foreach($users as $user) {?>
	<?php $index = $index + 1; ?>
	<div class="accordion-section" style="border-radius:5px 0px 0px 5px;">
		<a href="<?php echo '#accordion-'.$index ?>" class="accordion-section-title">
			<img src = "<?= $user['photo'] ?>" class="avatar avatar_small pull-left" style="margin:0px;" />
			<span class="pull-left line-item" style="padding:10px;float:left;"><?= $user['fname'] ?> <?= $user['lname'] ?></span>
			<span class="glyphicon glyphicon-chevron-down pull-right" style="margin-top:10px;"></span>
		</a>
		
		<div id="<?php echo 'accordion-'.$index ?>" class="accordion-section-content">
			<div class="pre-scrollable list-group" style="height:40vh;margin-bottom:50px;overflow:auto">
				<?php foreach($comments as $comment) {?>
					<?php if($user['uid'] == $comment['uid']) {?>				
						<div class="row" id="<?php echo 'comment-'.$comment['cid'] ?>" style="margin:0px 10px 0px 10px;">
							<?php if($flag == 1) {?>
								<div class="col-md-10 col-sm-9 col-xs-8 list-group-item-text message-text pull-left left-msg-style Qinput">
									<p class="nm"><?= $comment['comment'] ?></p>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-4">
									<span class="glyphicon glyphicon-trash pull-left" onclick = "deleteComment(<?= $comment['cid'] ?>)" style = "margin-left:10px;"></span>
								</div>
								<div class="col-md-6" style="margin-bottom:10px;">
									<p class="pull-left" style="margin-left:10px;"><?= $comment['date'] ?></p>
								</div>
							<?php } else { ?>
								<div class="col-md-2 col-sm-3 col-xs-4">
									<span class="glyphicon glyphicon-trash pull-right" onclick = "deleteComment(<?= $comment['cid'] ?>)" style = "margin-right:10px;"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-xs-8 list-group-item-text message-text pull-right right-msg-style Qinput">
									<p class="nm"><?= $comment['comment'] ?></p>
								</div>
								<div class="col-md-6 pull-right" style="margin-bottom:10px;">
									<p class="pull-right" style="margin-left:10px;"><?= $comment['date'] ?></p>
								</div>
							<?php }?>
						</div>				
					<?php }?>
				<?php }?>
			</div>
		</div>
	</div>
<?php }?>

<?php if($index == 0){ ?>
	<p style="width:100%; text-align:center;" >There is no result.</p>
<?php } ?>

<script>
	jQuery(document).ready(function() {
		function close_accordion_section() {
			jQuery('.accordion .accordion-section-title').removeClass('active');
			jQuery('.accordion .accordion-section-content').slideUp(300).removeClass('open');
		}

		jQuery('.accordion-section-title').click(function(e) {
			// Grab current anchor value
			var currentAttrValue = jQuery(this).attr('href');

			if(jQuery(e.target).is('.active')) {
				close_accordion_section();
			}else {
				close_accordion_section();

				// Add active class to section title
				jQuery(this).addClass('active');
				// Open up the hidden content panel
				jQuery('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
			}

			e.preventDefault();
		});
	});

	function deleteComment(id){
		$.ajax({
           url: site_url + 'profile/deleteComment',
           data: {             
              id: id         
           },
           success: function(data) {			              
              $("#comment-"+id).hide();
           },
           type: 'POST'
        });
	}
</script>
