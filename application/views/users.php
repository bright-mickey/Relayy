<script>

	function AllUsers(){
		$(".user_manage_tabs li").removeClass("active");
		$("#mng-user-tab-all").addClass("active");
		$(".mng_user_cell").show();

	}

	function PendingUsers(){
		$(".user_manage_tabs li").removeClass("active");
		$("#mng-user-tab-pending").addClass("active");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-status") == "0") $(this).show();
    	});
	}

	function ActiveUsers(){
		$(".user_manage_tabs li").removeClass("active");
		$("#mng-user-tab-activated").addClass("active");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-status") == "1") $(this).show();
    	});
	}

	function InvitedUsers(){
		$(".user_manage_tabs li").removeClass("active");
		$("#mng-user-tab-invited").addClass("active");
		$(".mng_user_cell").hide();
		$(".mng_user_cell").each(function(){
    		if($(this).attr("data-status") == "2") $(this).show();
    	});
	}




</script>
<div class="container-widget">
	
	

	<div class="row white_back" style="padding:100px 20px 0px 20px;margin:-10px;">
		<div class="col-md-6 col-xs-12">
			<h3 class="gray-text">USER MANAGEMENT</h3>
		</div>
		<?php if($u_type == 1){ ?>
		<h5 class="col-md-6 col-xs-12">
			<div class="col-md-7 col-xs-12" style="margin-top:5px;">
				<center><input id="invite_txt" type="text" class="pull-right full-width radius-item padding_xs" placeholder="Email to invite"></center>
			</div>
		    <div class="col-md-4 col-xs-12">	
		    	<center>    
			        <div class="dropdown pull-right" id="dropdown-invite">
			        	<button type="button" data-toggle="dropdown" class="dropdown-toggle btn"><span class="glyphicon glyphicon-send font-10 pull-left"></span>&nbsp;&nbsp;Invite as</button>
						<ul class="dropdown-menu">
							<li style="margin-left:10px;"><a onclick="sendInvite(11)">Admin</a></li>
					        <li style="margin-left:10px;"><a onclick="sendInvite(12)">Advisor</a></li>
					        <li style="margin-left:10px;"><a onclick="sendInvite(13)">Entrepreneur</a></li>
					        <li style="margin-left:10px;"><a onclick="sendInvite(14)">Moderator</a></li>
					    </ul>
					</div>
				</center>
			</div>
		</h5>
		<?php } ?>
	</div>
	

    <div class="row padding_sm">
    	

		<ul class="nav nav-tabs user_manage_tabs">
		    <li class="active" id="mng-user-tab-all"><a onclick="AllUsers()">All</a></li>
		    <li id="mng-user-tab-pending"><a onclick="PendingUsers()">Pending</a></li>
		    <li id="mng-user-tab-activated"><a onclick="ActiveUsers()">Activated</a></li>
		    <li id="mng-user-tab-invited"><a onclick="InvitedUsers()">Invited</a></li>
		</ul>
	</div>	
	
	<div id="user-manage-noti">	

	


	<?php 
		$index = 0;

		foreach ($users as $user) {
			if($u_type == 4 && $u_group === "") break;//if advisor hasnot his own group
			if ($user['id'] == $u_id) continue;//don't show me
			if($u_type == 4 && $user['group'] !== $u_group) continue;//show only same group users if moderator
			$index = $index + 1;
			if ($user['status'] != 0 && $page == 1) continue;
			if ($user['status'] != 1 && $page == 2) continue;
			if ($user['status'] != 2 && $page == 3) continue;

			if ($user['type'] % 10 == 1) $utype = "Admin";
			if ($user['type'] % 10 == 2) $utype = "Advisor";
			if ($user['type'] % 10 == 3) $utype = "Entrepreneur";
	        if ($user['type'] % 10 == 4) $utype = "Moderator";

	        $username = '';
	        if ($user['fname'].$user['lname']) $username = $user['fname']." ".$user['lname'];
	        else {
	            $str_arr = explode("@", $user['email']);
	            $username = $str_arr[0];
	        }
			?>
		  <div class="row border3 mng_user_cell" data-status = "<?= $user['status'] ?>" style="margin:0px;padding:20px 10px;background:#FFF">
		  	<div class="col-md-1 col-xs-3 canvas" style="margin-top:10px;">
			  	<img class="round pull-right" src="<?= strlen($user['photo'])>0?$user['photo']:asset_base_url().'/images/emp-sm.jpg'?>" width="38" height="38">
			  	<span class="state_<?= $user['id'] ?> offline"></span>
		  	</div>
		    <div class="col-md-2 col-xs-9" style="margin-top:20px;"><span><?= $username?></span></div>
		    <div class="col-md-3 col-md-offset-0 col-xs-9 col-xs-offset-3 wrapword" style="margin-top:20px;"><span><a href="<?= site_url('profile/user/'.$user['id'])?>"><?= $user['email']?></a></span></div>
		    <div class="col-md-1 col-md-offset-0 col-xs-4 col-xs-offset-3" style="margin-top:20px;overflow:hidden;"><span><?= $utype?></span></div>
		    <div class="col-md-1 col-md-offset-0 col-xs-5" style="margin-top:20px;"><span>[<?= $user['groupname']?>]</span></div>
	<?php if ($user['status'] == 0) {?>
		    <div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><span class="text-warning">Pending</span></div>
		    <div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('users')?>/action/<?= $user['id']."/".$page?>">Activate</a></div>
	<?php } else if ($user['status'] == 1) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><span class="text-success">Activated</span></div>
		    <div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="ob pull-right" href="<?= site_url('users')?>/action/<?= $user['id']."/".$page?>">Deactivate</a></div>
	<?php } else if ($user['status'] == 2) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><span class="text-primary">Invited</span></div>
		    <div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="bb pull-right" href="#">Invite</a></div>
	<?php } else if ($user['status'] == 4) {?>
			<div class="col-md-2 col-md-offset-0 col-xs-3 col-xs-offset-3" style="margin-top:20px;"><span class="text-success">Deleted</span></div>
		    <div class="col-md-1 col-xs-6" style="margin-top:10px;"><a class="bb pull-right" href="<?= site_url('users')?>/invite/<?= $user['type']."/".$user['email']."/".$page?>">Invite</a></div>
	<?php }?>
			<div class="col-md-1 col-xs-12 pull-right" style="margin-top:10px;"><a class="rb pull-right" onclick="delAction(this, '<?= $user['email']?>')" data-act="<?= site_url('users')?>/delete/<?= $user['id']."/".$page?>">Delete</a></div>
		  </div>
	<?php		
		}
		if($index == 0){ ?>
		<p style="text-align:center; width:100%; padding:20px;">There is no result.</p>
	<?php } ?>

	</div>
</div>







