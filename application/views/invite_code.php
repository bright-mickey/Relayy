<script>
	var request = "<?php echo $invite_code[TBL_INVITE_REQUEST] ?>"


	function RequestInviteCode(code){
		if(request > 0) return;
		if(currentUser_type == 4){
			BootstrapDialog.show({
		        type: BootstrapDialog.TYPE_PRIMARY,
		        title: "Edit Position",
		        message: '<div class="container-widget" style="padding:20px;">'+
		                    '<div class="row">'+
		                    	'<div class="padding_xs col-sm-4 col-xs-12">'+
		                        	'<span>number of members:</span>'+
		                        '</div>'+
		                        '<div class="col-sm-8 col-xs-12">'+
		                        	'<input type="text" class="Qinput padding_xs" id="requested_members" onkeypress="return isNumberKey(event)">'+
		                        '</div>'+
		                    '</div>'+
		                    '<div class="row">'+
		                    	'<div class="padding_xs col-sm-4 col-xs-12">'+
		                        	'<span>number of moderators:</span>'+
		                        '</div>'+
		                        '<div class="col-sm-8 col-xs-12">'+
		                        	'<input type="text" class="Qinput padding_xs" id="requested_moderators" onkeypress="return isNumberKey(event)">'+
		                        '</div>'+
		                    '</div>'+
		                  '</div>',
		        buttons: [{
		            label: 'Cancel',                          
		            action: function(dialogRef) { 
		                dialogRef.close();
		            }
		        },{
		            label: 'Send Request',
		            cssClass: 'btn-primary',
		            icon: 'glyphicon glyphicon-chevron-right',
		            autospin: true,                
		            action: function(dialogRef) {
		                $.ajax({
				                url: site_url + 'invite/RequestMoreInvite',
				                data: { 
				                	id:currentUser_id,
				              	    code: code,
				              	    members: $("#requested_members").val(),
				              	    moderators: $("#requested_moderators").val(),
				                },
				                success: function(data) {   
				           			$(".content").html(data);
				           			dialogRef.close();	
				                },
				                type: 'POST'
				        });

		            }
		        }]
		    });
		}
		else{
			$.ajax({
	                url: site_url + 'invite/RequestMoreInvite',
	                data: { 
	                	id:currentUser_id,
	              	    code: code,
	              	    members: 5,
	              	    moderators: 0,
	                },
	                success: function(data) {   
	           			$(".content").html(data);
	           			dialogRef.close();	
	                },
	                type: 'POST'
	        });
		}

        
    }

    function isNumberKey(evt){
	    var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}

	function EditInvite(remain, m_remain, type, request, m_request, code){
		var message = '<div class="container-widget" style="padding:20px;">'+
		                    '<div class="row">'+
		                    	'<div class="padding_xs col-sm-4 col-xs-12">'+
		                        	'<span>number of members:</span>'+
		                        '</div>'+
		                        '<div class="col-sm-8 col-xs-12">'+
		                        	'<input type="text" class="Qinput padding_xs" id="edit_members" value="' + (remain+request) + '" onkeypress="return isNumberKey(event)">'+
		                        '</div>'+
		                    '</div>';

            if(type == 4){
            	message += '<div class="row">'+
		                    	'<div class="padding_xs col-sm-4 col-xs-12">'+
		                        	'<span>number of moderators:</span>'+
		                        '</div>'+
		                        '<div class="col-sm-8 col-xs-12">'+
		                        	'<input type="text" class="Qinput padding_xs" id="edit_moderators" value="' + (m_remain+m_request) + '" onkeypress="return isNumberKey(event)">'+
		                        '</div>'+
		                    '</div>';
    		}
			message += '</div>';
			BootstrapDialog.show({
			        type: BootstrapDialog.TYPE_PRIMARY,
			        title: "Edit Position",
			        message: message,
			        buttons: [{
			            label: 'Cancel',                          
			            action: function(dialogRef) { 
			                dialogRef.close();
			            }
			        },{
			            label: 'Save',
			            cssClass: 'btn-primary',
			            icon: 'glyphicon glyphicon-chevron-right',
			            autospin: true,                
			            action: function(dialogRef) {
			                $.ajax({
					                url: site_url + 'invite/SaveInvite',
					                data: { 
					              	    code: code,
					              	    members: $("#edit_members").val(),
					              	    moderators: $("#edit_moderators").val(),
					                },
					                success: function(data) {   
					           			ViewInvitePage();
					           			dialogRef.close();	
					                },
					                type: 'POST'
					        });

			            }
			        }]
			    });
		
	}


    

    function filter_All(){
    	$(".group_leader_table").hide();
    	$(".invite_code_table").show();
    	$(".invite_row").show();
    	$(".invite_tabs li").removeClass("active");
    	$("#invitecode_all").addClass("active");
    }

    function filter_Moderatores(){
    	$(".group_leader_table").hide();
    	$(".invite_code_table").show();
    	$(".invite_row").each(function(){
    		if($(this).find(".invite_role").text() === "Moderator") $(this).show();
    		else $(this).hide();
    	});
    	$(".invite_tabs li").removeClass("active");
    	$("#invitecode_moderator").addClass("active");
    }

    function filter_Advisors(){
    	$(".group_leader_table").hide();
    	$(".invite_code_table").show();
    	$(".invite_row").each(function(){
    		if($(this).find(".invite_role").text() === "Advisor") $(this).show();
    		else $(this).hide();
    	});
    	$(".invite_tabs li").removeClass("active");
    	$("#invitecode_advisor").addClass("active");
    }

    function filter_Entrepreneurs(){
    	$(".group_leader_table").hide();
    	$(".invite_code_table").show();
    	$(".invite_row").each(function(){
    		if($(this).find(".invite_role").text() === "Entrepreneur") $(this).show();
    		else $(this).hide();
    	});
    	$(".invite_tabs li").removeClass("active");
    	$("#invitecode_Entrep").addClass("active");
    }

    function filter_Leaders(){
    	$(".invite_code_table").hide();
    	$(".group_leader_table").show();
    	$(".invite_tabs li").removeClass("active");
    	$("#invitecode_Leader").addClass("active");
    }

    function delete_leader(code){
    	BootstrapDialog.show({
    		title:"Delete Leader",
	        message: "are you sure you want to delete this leader?",
	        type: BootstrapDialog.TYPE_DANGER,
	        buttons: [{
	            label: 'Delete',
	            cssClass: 'btn-danger',
	            autospin: true,
	            action: function(dialogRef){
	                 $.ajax({
			                url: site_url + 'invite/DeleteLeader',
			                data: { 
			              	    code: code,
			                },
			                success: function(data) {   
			           			$("#leader_"+code).parent().remove();
			           			dialogRef.close();	
			                },
			                type: 'POST'
			        });
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


<div class="profile-container">
	<center><h3 class="row blue-text">Invite Page</h3></center>
	
	<?php if($invite_code[TBL_INVITE_TYPE] == 1) { ?>
		<div class="row padding_xs">
		  <ul class="nav nav-tabs invite_tabs">
		    <li class="active" id="invitecode_all"><a onclick="filter_All()">All</a></li>
		    <li id="invitecode_moderator"><a onclick="filter_Moderatores()">Moderators</a></li>
		    <li id="invitecode_advisor"><a onclick="filter_Advisors()">Advisors</a></li>
		    <li id="invitecode_Entrep"><a onclick="filter_Entrepreneurs()">Entrepreneurs</a></li>
		    <li id="invitecode_Leader"><a onclick="filter_Leaders()">Group Leaders</a></li>
		  </ul>
		</div>
	<?php } ?>

	<div class="container-widget white_back border1234" style="padding:30px 10px;line-height:1.5;">
		<?php if($invite_code[TBL_INVITE_TYPE] == 1) { ?>

			<div class="row invite_code_table">

				<?php
					$index = 0;
					foreach($users as $user){ 
						$index ++;
						$role = "Entrepreneur";
						if($user[TBL_USER_TYPE] == 2) $role = "Advisor";
						else if($user[TBL_USER_TYPE] == 4) $role = "Moderator";

						if(strlen($user[TBL_USER_FNAME])<2){
							$str_arr = explode("@", $user[TBL_USER_EMAIL]);
	                    	$user[TBL_USER_FNAME] = $str_arr[0];
						}

						$groupname = strlen($user[TBL_USER_GROUP]) > 0 ? (isset($groups->{$user[TBL_USER_GROUP]})?$groups->{$user[TBL_USER_GROUP]}:"") : "";
						
					?>

					<div class="row invite_row padding_xs border3">
						<div class="col-md-1 col-sm-2 col-xs-3 padding_xs"><center><img class="avatar avatar_small" src="<?= strlen($user[TBL_USER_PHOTO])>0?$user[TBL_USER_PHOTO]:asset_base_url().'/images/emp.jpg'?>"></center></div>
						<div class="col-md-3 col-sm-3 col-xs-9 padding_xs"><?= $user[TBL_USER_FNAME]." ".$user[TBL_USER_LNAME] ?></div>
						<div class="col-md-2 col-sm-2 col-xs-5  padding_xs invite_role"><?= $role ?></div>
						<div class="col-md-2 col-sm-2 col-xs-4 padding_xs">[ <?= $groupname ?> ]</div>
						<div class="col-md-1 col-xs-4 padding_xs"><?= $user[TBL_INVITE_REMAIN]." / ".$user[TBL_INVITE_MREMAIN] ?></div>
						<div class="col-md-2 col-xs-4 padding_xs" style="color:red;"><?= $user[TBL_INVITE_REQUEST]>0?$user[TBL_INVITE_REQUEST]." / ".$user[TBL_INVITE_MREQUEST]:"" ?></div>
						<div class="col-md-1 col-sm-2 col-xs-4 padding_xs"><button type="button" onclick="EditInvite(<?= $user[TBL_INVITE_REMAIN] ?>, <?= $user[TBL_INVITE_MREMAIN] ?>, <?= $user[TBL_INVITE_TYPE] ?>, <?= $user[TBL_INVITE_REQUEST] ?>, <?= $user[TBL_INVITE_MREQUEST] ?>, '<?= $user[TBL_INVITE_CODE] ?>')" class="ob">EDIT</button></div>
					</div>

				<?php } ?>
				<center class="row" id="notify_group_request"><?php if($index == 0) echo "No requests"; ?></center>

			</div>			

			

			<div class="row group_leader_table" id="leader_row" style="display:none;">
				<div class="row padding_xs" style="color:#89ce39;">
					<div class="col-sm-2 col-xs-6 padding_xs">Code</div>
					<div class="col-sm-4 col-xs-6 padding_xs">Name</div>
					<div class="col-sm-2 col-xs-6 padding_xs">Number of Users</div>
					<div class="col-sm-4 col-xs-6 padding_xs">
						<button class="gb pull-right" onclick="leaderGenerate()">Generate</button>
					</div>
				</div>
				<?php
					$index = 0;
					foreach($leaders as $leader){ 
						$index ++;
						
					?>

					<div class="row padding_xs border1">
						<div class="col-sm-2 col-xs-4 padding_xs"><?= $leader[TBL_LEADER_CODE] ?></div>
						<div class="col-sm-4 col-xs-8 padding_xs" id="leader_<?= $leader[TBL_LEADER_CODE] ?>"><?= $leader[TBL_LEADER_NAME] ?></div>
						<div class="col-sm-2 col-xs-4 padding_xs"><?= $leader[TBL_LEADER_USERS] ?></div>
						<div class="col-sm-2 col-xs-4 padding_xs">
							<button class="ob pull-right" onclick="edit_leadername('<?= $leader[TBL_LEADER_CODE] ?>')">EDIT</button>
						</div>
						<div class="col-sm-2 col-xs-4 padding_xs">
							<button class="rb pull-right" onclick="delete_leader('<?= $leader[TBL_LEADER_CODE] ?>')">DELETE</button>
						</div>
					</div>

				<?php } ?>
			</div>	

			<script>
				var leader_codes = '<?php echo json_encode($codes) ?>';
				var array_leader_code = [];
				console.log(leader_codes);
				array_leader_code = JSON.parse(leader_codes);


				function leaderGenerate(){

					var letter = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
					var rand = (Math.random() * 100000) % 25;
					var code1 = letter.substring(rand, rand + 1);
					rand = (Math.random() * 100000) % 25;
					var code2 = letter.substring(rand, rand + 1);
					rand = (Math.random() * 100000) % 25;
					var code3 = letter.substring(rand, rand + 1);
					rand = Math.floor((Math.random() * 100000) % 1000);
					var inviteCode = code1 + code2 + code3 + rand;

					var message = '<div class="container-widget" style="padding:20px;">'+
					                    '<div class="row">'+
						                    '<div class="padding_xs col-xs-4">'+
						                    	'<p id="leader_code">Invite code:'+
					                        '</div>'+
					                        '<div class="padding_xs col-xs-4">'+
					                        	'<p id="leader_code">'+inviteCode+'</p>'+
					                        '</div>'+
					                    '</div>';
			          
			        	message += '<div class="row">'+
				                    	'<div class="padding_xs col-xs-4">'+
				                        	'<span>Name of Group Leader:</span>'+
				                        '</div>'+
				                        '<div class="padding_xs col-xs-8">'+
				                        	'<input type="text" class="full-width Qinput padding_xs" id="edit_leader_name">'+
				                        '</div>'+
				                    '</div>';

						message += '</div>';

						BootstrapDialog.show({
						        type: BootstrapDialog.TYPE_PRIMARY,
						        title: "Edit Group Leader",
						        message: message,
						        buttons: [{
						            label: 'Cancel',                          
						            action: function(dialogRef) { 
						                dialogRef.close();
						            }
						        },{
						            label: 'Create',
						            cssClass: 'btn-primary',
						            icon: 'glyphicon glyphicon-chevron-right',
						            autospin: true,                
						            action: function(dialogRef) {
						                $.ajax({
								                url: site_url + 'invite/CreateGroupLeader',
								                data: { 
								              	    code: inviteCode,
								              	    name: $("#edit_leader_name").val()
								                },
								                success: function(data) {  
								                	add_Leader(inviteCode, $("#edit_leader_name").val()); 
								           			dialogRef.close();	
								                },
								                type: 'POST'
								        });

						            }
						        }]
						    });
					
				}


				function add_Leader(inviteCode, name){
					var new_leader = 	'<div class="row padding_xs border1">'+
											'<div class="col-sm-2 col-xs-4 padding_xs">'+inviteCode+'</div>'+
											'<div class="col-sm-4 col-xs-8 padding_xs" id="leader_'+inviteCode+'">'+name+'</div>'+
											'<div class="col-sm-2 col-xs-4 padding_xs">0</div>'+
											'<div class="col-sm-2 col-xs-4 padding_xs"><button class="ob pull-right" onclick="edit_leadername(\''+inviteCode+'\')">EDIT</button></div>'+
											'<div class="col-sm-2 col-xs-4 padding_xs">'+
												'<button class="rb pull-right" onclick="delete_leader(\''+inviteCode+'\')">DELETE</button>'
											'</div>'+
										'</div>';

					$("#leader_row").append(new_leader);
				}

				function edit_leadername(code){
					var name = $("#leader_"+code).text();
					BootstrapDialog.show({
						        type: BootstrapDialog.TYPE_PRIMARY,
						        title: "Edit Leader Name",
						        message: '<div class="row">'+
				                    	'<div class="padding_xs col-sm-2 col-xs-12">'+
				                        	'<span>Name:</span>'+
				                        '</div>'+
				                        '<div class="col-sm-10 col-xs-12">'+
				                        	'<input type="text" class="Qinput padding_xs full-width" id="new_leader_name" value="' + name + '">'+
				                        '</div>'+
				                    '</div>',
						        buttons: [{
						            label: 'Cancel',                          
						            action: function(dialogRef) { 
						                dialogRef.close();
						            }
						        },{
						            label: 'Save',
						            cssClass: 'btn-primary',
						            icon: 'glyphicon glyphicon-chevron-right',
						            autospin: true,                
						            action: function(dialogRef) {
						                $.ajax({
								                url: site_url + 'invite/UpdateLeaderName',
								                data: { 
								              	    code: code,
								              	    name: $("#new_leader_name").val()
								                },
								                success: function(data) {  
								                	$("#leader_" + code).text($("#new_leader_name").val());
								           			dialogRef.close();	
								                },
								                type: 'POST'
								        });

						            }
						        }]
						    });
				}

			</script>

			<!-- for not admin -->
		<?php } else { ?>

			<div class="row">
				<h4>Help out your friends and colleagues by giving them free access to Relayy during the beta period.</h4>
			</div>
			<?php if(isset($issue_group) && $issue_group == 0){ ?>
			<div class="row">
				<?php if($invite_code[TBL_INVITE_MCODE] !== ""){ ?>
					<h4 class="blue-text">Your Group Invite Code: <?= $invite_code[TBL_INVITE_CODE] ?> (<?= $invite_code[TBL_INVITE_REMAIN] ?> un-redeemed invites remaining)</h4>
					<h4 class="blue-text">Your Moderator Invite Code: <?= $invite_code[TBL_INVITE_MCODE] ?> (<?= $invite_code[TBL_INVITE_MREMAIN] ?> un-redeemed invites remaining)</h4>
				<?php }else if(isset($invite_code[TBL_INVITE_CODE])){ ?>
						<h4 class="blue-text">Your Unique Invite Code: <?= $invite_code[TBL_INVITE_CODE] ?> (<?= $invite_code[TBL_INVITE_REMAIN] ?> un-redeemed invites remaining)</h4>
				<?php } else { ?>
					<h4 class="blue-text">You haven't your invite code yet. If you want to invite your friends, you must request your invite code from admin.</h4>
				<?php } ?>
			</div>
			<?php } else {?>
					<h4 style="color:red;">*Warning: Your group name is empty on your profile page.<h4>
					<p style="color:red;">&nbsp;- In order to generate invite codes for your group members, you must first add your group name to your profile.</p>
					<p style="color:red;">&nbsp;- Once your group name is added, your invite codes will show up here.</p>
					<p style="color:red;">&nbsp;- Your members MUST sign up with your invite code in order to be attached to your group.</p>
					
			<?php } ?>
			<div class="row">
				<h4>How it Works:</h4>
			</div>
			<div class="row">
				<p class="small_icon">-  Your invite code is unique to you. Share it with friends any way you want - they will enter this code when registering for a NEW account.</p>
			</div>
			<div class="row">
				<p class="small_icon">-  An invite is only used when someone register a new account (so share as much as you want). The same code can be used up to your max allowable invite limit(e.g. 5 invites remaining).</p>
			</div>

			<?php if($invite_code[TBL_INVITE_REMAIN] == 0){ ?>

				<div class="row">
					<?php if($invite_code[TBL_INVITE_REQUEST] > 0) { ?>
						<h4 class="blue-text">Your request has been sent successfully!</h4>
					<?php } else { ?>
						<h4>Do you want more invite codes?</h4>
					<?php } ?>
				</div>

				<div class="row">
					<?php if($invite_code[TBL_INVITE_REQUEST] > 0) { ?>
						<button type="button" class="teamup-remove-button green-btn desktop-visible-item" onclick="RequestInviteCode('<?= $invite_code[TBL_INVITE_CODE] ?>')" style="width:250px;">Request is pending</button>
						<center class="mobile-visible-item"><button type="button" class="teamup-remove-button green-btn" onclick="RequestInviteCode('<?= $invite_code[TBL_INVITE_CODE] ?>')" style="width:250px;">Request is pending</button></center>
					<?php } else { ?>
						<button type="button" class="teamup-remove-button blue-btn desktop-visible-item" onclick="RequestInviteCode('<?= $invite_code[TBL_INVITE_CODE] ?>')" style="width:250px;">Request More Invites</button>
						<center class="mobile-visible-item"><button type="button" class="teamup-remove-button blue-btn" onclick="RequestInviteCode('<?= $invite_code[TBL_INVITE_CODE] ?>')" style="width:250px;">Request More Invites</button></center>
					<?php } ?>
				</div>

			<?php } ?>
		<?php } ?>

		
		
	</div>

	

</div>
















