<script>
	var selected_waiters = [];
	var creator_id = "<?php echo $u_id ?>";
	var question_asker_id = "<?php echo $askerid ?>";
	var question_title = "<?php echo $name ?>";
	var q_id = "<?php echo $q_id ?>";
	selected_waiters.push(question_asker_id);

	function RemoveRoom(did){
		$("#tremove-"+did).show();
		$.ajax({
	       url: site_url + 'chat/removeTeamUp',
	       data: {
	          did: did,
	          askerid: question_asker_id,
	          q_id: q_id,
	          creatorID: creator_id         
	       },
	       success: function(data) {
	       		location.reload();
	       },
	       type: 'POST'
	     });
	}

	function ApproveRoom(did){
		$("#tstart-"+did).show();
		$.ajax({
	       url: site_url + 'chat/approveTeamUp',
	       data: {
	          did: did	          
	       },
	       success: function(data) {
	       		location.reload();
	       },
	       type: 'POST'
	     });
	}

	function DisapproveRoom(did)
	{
		$("#tdeactive-"+did).show();
		$.ajax({
	       url: site_url + 'chat/disapproveTeamUp',
	       data: {
	          did: did	          
	       },
	       success: function(data) {
	       		location.reload();
	       },
	       type: 'POST'
	     });
	}

	function selectAllWaiters(){
		selected_waiters = [];
		selected_waiters.push(question_asker_id);
		$(".waiter-div").each(function(){
			selected_waiters.push($(this).attr("data-id"));
			$(this).find(".check-icon").show();
		});
	}

	function selectNone(){
		selected_waiters = [];
		selected_waiters.push(question_asker_id);
		$(".waiter-div").each(function(){
			$(this).find(".check-icon").hide();
		});
	}

	function createTeamUp(){
		if(selected_waiters.length < 2){
			alert('You must select 1 or more advisors !');
			selected_waiters = [];
			selected_waiters.push(question_asker_id);
			return;
		} 
		$(".teamup-create-spinner").show();
		var params = {
			type: 1,
            name: question_title
		};
		var type;

		type = 2;

		QB.chat.dialog.create(params, function(err, createdDialog) {
			if (err) {
        		alert("error occured");
			} else {

	          $.ajax({
	           url: site_url + 'chat/newTeamUp',
	           data: {
	              did: createdDialog._id,
	              jid: createdDialog.xmpp_room_jid,
           		  q_id: q_id,
	              type: type,
	              dname: question_title,
	              ddesc: "TeamUp",
	              occupants: selected_waiters,
	              askerid: question_asker_id
	           },
	           success: function(data) {
	           		location.reload();
	           },
	           type: 'POST'
	          });

	        }
	    });

	}

	function goTeamupBack(){
		location.href = site_url + 'questions';
	}




</script>



<div class="teamup-create-content scrollbar" style="height:100vh;">
<div>
	<?php if($u_type != 1 && $u_type != 4){?>
	    <div class="row" style="margin-top:200px;">
	      <p style="text-align:center;"> You can't manage teamup Chats, because you are not a admin or a moderator.</p>
	    </div>
	<?php } else {?>
	
	<div class="row"><center><h4>Create New TeamUp Chats</h4></center></div>
	<div class="row"><h5><?= $name ?></h5></div>
	<div class="row padding_xs border1234 white_back radius-item" style="height:auto;max-height:40vh;overflow:scroll;">
	<ul>
	<?php foreach($chatrooms as $room) { ?>
		<li class="padding_xs">
		<div class="row flexible_height">

			<div class="col-md-4 col-xs-12 teamup-occupants">
				<?php
					foreach(json_decode($room[TBL_CHAT_OCCUPANTS]) as $userid) { 
						foreach($photo as $elem){
							if($elem->id == $userid) $user_photo = $elem->photo;
						}
				?>
					<div class="col-xs-2" style="padding:0px;">
                        <div class="chat_user pull-left canvas">
                            <div style="border-radius:100%;overflow:auto;">
                              <img  class="avatar avatar_small" src="<?= strlen($user_photo)>0?$user_photo:asset_base_url().'/images/emp-sm.jpg'?>">
                            </div>
                            <span class="state_<?= $userid ?> offline"></span>
                        </div>
                    </div>

				<?php } ?>
			</div>

			<div class="col-md-2 col-xs-6 teamup-type"><?= $room['type']==1?'1:1 Chat':'Group' ?></div>
			<div class="col-md-2 col-xs-6 teamup-status"><?= $room['status']==1?'Activated':'Pending' ?></div>
			<?php if($room['status'] == 1){ ?>
				<div class="col-md-2 col-xs-5"><button type="button" class="pull-right red-btn teamup-remove-button" onclick="DisapproveRoom('<?= $room[TBL_CHAT_DID] ?>')">DEACTIVATE<div class="teamup-spinner" id="tdeactive-<?= $room[TBL_CHAT_DID] ?>" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></div>
			<?php } ?>
			<?php if($room['status'] == 0){ ?>
				<div class="col-md-2 col-xs-5"><button type="button" class="pull-right red-btn teamup-remove-button" onclick="RemoveRoom('<?= $room[TBL_CHAT_DID] ?>')">REMOVE<div class="teamup-spinner" id="tremove-<?= $room[TBL_CHAT_DID] ?>" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></div>
				<div class="col-md-2 col-xs-7"><button type="button" class="pull-right green-btn teamup-remove-button" onclick="ApproveRoom('<?= $room[TBL_CHAT_DID] ?>')"><p style="margin:0 auto;">START CHAT</p><div class="teamup-spinner" id="tstart-<?= $room[TBL_CHAT_DID] ?>" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></div>
			<?php } ?>
		</div>
		</li>

	<?php } ?>
	</ul>
	<?php if(sizeof($chatrooms) == 0){ ?>
		<div class="row gray-text" style="padding:30px;">
	      <p style="text-align:center;"> There is no chatroom for this question.</p>
	    </div>
	<?php } ?>
	</div>
	<div class="row Qinput">
		<div class="row desktop-visible-item">
			<div class="col-xs-12"><h5 style="color:red;">ADVISORS WAITING FOR TEAMUP:</h5></div>
			<div class="col-md-6 col-xs-12">SELECT: <a onclick="selectAllWaiters()">ALL</a> | <a onclick="selectNone()">NONE</a> | <span id="selected_waiting"></span> SELECTED</div>
			<div class="col-md-6 col-xs-12"><button type="button" class="ob pull-right" onclick="createTeamUp()">CREATE TEAMUP WITH SELECTED<div class="teamup-spinner teamup-create-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></div>
		</div>
		<div class="row mobile-visible-item">
			<div class="col-xs-12"><center><h5 style="color:red;">ADVISORS WAITING FOR TEAMUP:</h5></center></div>
			<div class="col-md-6 col-xs-12"><center>SELECT: <a onclick="selectAllWaiters()">ALL</a> | <a onclick="selectNone()">NONE</a> | <span id="selected_waiting"></span> SELECTED</center></div>
			<div class="col-md-6 col-xs-12"><center><button type="button" class="ob" onclick="createTeamUp()">CREATE TEAMUP WITH SELECTED<div class="teamup-spinner teamup-create-spinner" style="display:none;"><i class="fa sm-button fa-spinner fa-spin"></i></div></button></center></div>
		</div>
	</div>
	
	<div class="row border1234 white_back radius-item" style="height:auto;">
		<ul>

		<?php if($askerid != $u_id) { ?>
			<li class="row padding_xs waiter-div" data-id="<?= $u_id ?>">
				<div class="row padding_xs">
					<div class="col-xs-1" style="margin-top:5px;"><center><img class="check-icon" src="<?= asset_base_url().'/images/blue-check.png' ?>" style="display:none;"></center></div>
					<div class="col-xs-2" style="padding:0px;">
			            <div class="chat_user pull-left canvas">
			                <div style="border-radius:100%;">
			                  <img class="avatar avatar_small" src="<?= strlen($u_photo)>0?$u_photo:asset_base_url().'/images/emp-sm.jpg'?>">
			                </div>
			                <span class="state_<?= $u_id?> offline"></span>
			            </div>
			        </div>
			        <div class="col-md-4 col-xs-8"><?= $u_fname." ".$u_lname ?> ( You )</div>
			        <div class="col-md-5 col-xs-9 pull-right"><?= $u_email ?></div>
			    </div>
			</li>
		<?php } ?>

		<?php foreach($waiting_advisors as $advisor){ ?>
		<li class="row padding_xs waiter-div" data-id="<?= $advisor->id ?>">
			<div class="row padding_xs">
				<div class="col-xs-1" style="margin-top:5px;"><center><img class="check-icon" src="<?= asset_base_url().'/images/blue-check.png' ?>" style="display:none;"></center></div>
				<div class="col-xs-2" style="padding:0px;">
		            <div class="chat_user pull-left canvas">
		                <div style="border-radius:100%;">
		                  <img class="avatar avatar_small" src="<?= strlen($advisor->photo)>0?$advisor->photo:asset_base_url().'/images/emp-sm.jpg'?>">
		                </div>
		                <span class="state_<?= $advisor->id?> offline"></span>
		            </div>
		        </div>
		        <div class="col-md-4 col-xs-8"><?= $advisor->fname." ".$advisor->lname ?></div>
		        <div class="col-md-5 col-xs-9 pull-right"><?= $advisor->email ?></div>
		    </div>
		</li>
		<?php } ?>
		<?php if(sizeof($waiting_advisors) == 0){ ?>
			<div class="row gray-text" style="padding:30px;">
		      <p style="text-align:center;"> There are no waiting advisors for this question.</p>
		    </div>
		<?php } ?>

		</ul>
	</div>

	<div class="row padding_xs"><button type="button" class="ob pull-left" onclick="goTeamupBack()">Go Back</button></div>

	<script>
		$(".waiter-div").click(function(e){

			var index;
			var checkbox = $(this).find(".check-icon");
			if(checkbox.is(":hidden")){
				checkbox.show();
				selected_waiters.push($(this).attr("data-id"));
			}
			else{
				checkbox.hide();				
				index = selected_waiters.indexOf($(this).attr("data-id"));
				selected_waiters.splice(index, 1);
			}

		});



	</script>

	<?php } ?>





</div>
</div>
</body>
</html>