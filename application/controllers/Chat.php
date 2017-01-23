<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Chat extends ChatController
{
    var $myChat;
    var $jid;
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	
        $width = $this->input->post('screen_width');

	    //$this->maintenance();return;
	
    	$this->loginCheck();    	

		///////////////////////////
    	$chat_data = $this->getChatData();

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'chat-page';

		$chat_data['page_title'] = 'Chat | Relayy';	

        $this->channel($chat_data['history'][0][TBL_CHAT_DID]);      

	}

    public function setLeftbartoMainPage(){
        
    }

	public function channel($current_id)
	{
		$this->loginCheck();    	

        if(strpos($current_id, "p") !== false){
            $jquery = 1;
            $current_id = explode("p", $current_id)[0];
        }
        else{
            $jquery = 0;
        }

		///////////////////////////

		$chat_data = $this->getChatData();
		
		$dialog_arr = $this->mchat->getDialogs($this->cid);

		$find = FALSE;

		foreach ($dialog_arr as $dialog) {

			if ($dialog['did'] == $current_id) {

				$chat_data['d_id'] = $dialog[TBL_CHAT_DID];

		    	$chat_data['d_name'] = $dialog[TBL_CHAT_NAME];

                $chat_data['d_qid'] = $dialog[TBL_CHAT_QUESTIONID];

		    	$chat_data['d_occupants'] = json_decode($dialog[TBL_CHAT_OCCUPANTS]);

		    	$chat_data['d_users'] = array();
		    	foreach ($chat_data['d_occupants'] as $d_user) {
                    if($this->muser->getUserArray($d_user) != FALSE){
					   $chat_data['d_users'][] = $this->muser->getUserArray($d_user);
                    }
		    	}

		    	$chat_data['d_type'] = $dialog[TBL_CHAT_TYPE];

		    	$chat_data['d_jid'] = $dialog[TBL_CHAT_JID];

		    	$chat_data['d_status'] = $dialog[TBL_CHAT_STATUS];

		    	$chat_data['d_message'] = $dialog[TBL_CHAT_MESSAGE];

		    	$chat_data['d_time'] = $dialog[TBL_CHAT_TIME];

		    	$chat_data['d_noti'] = $this->moption->get($this->cid, 'notify_'.$chat_data['d_id']);

                $this->myChat = $chat_data;

		    	$find = TRUE;
			}
		}

        if(!$find){
            $chat_data['deleted'] = 1;

            $this->load->view('templates/header-chat', $chat_data);

            $this->load->view('templates/left-sidebar', $chat_data);

            $this->load->view('chat', $chat_data);

            $this->load->view('templates/right-sidebar', $chat_data);

            $this->load->view('templates/footer-chat', $chat_data);

            return;
        } 

        //If the dialog exists.

        //getting deleted message info
        $chat_data['d_messages'] = json_encode($this->mhistory->getDeletedMsgs($current_id));

		$d_owner = $this->muser->get($chat_data['d_occupants'][0]);
	    	
    	$chat_data['d_owner'] = $d_owner->{TBL_USER_FNAME};
        
        if (!$chat_data['d_owner']) {
            $str_arr = explode("@", $d_owner->{TBL_USER_EMAIL});
            $chat_data['d_owner'] = $str_arr[0];
        }

    	if ($d_owner->{TBL_USER_ID} == gf_cu_id()) $chat_data['d_owner'] = "Me";

		if (!$find && $this->ctype != USER_TYPE_ADMIN) redirect(site_url('chat'), 'get');

    	///////////////////////////  For showing Save, Like States  /////////////////
        $comment_mids = $this->mcomment->getSavedMsgIDs($this->cuid);
        if(!$comment_mids){
            $chat_data['saved_mids'] = "none";
        }
        else{
            $chat_data['saved_mids'] = json_encode($comment_mids);
        }

        $liked_mids = $this->mhistory->getLikedMsgIDs($this->cuid);
        if(!$liked_mids){
            $chat_data['liked_mids'] = "none";
        }
        else{
            $chat_data['liked_mids'] = json_encode($liked_mids);
        }



        /////////////////////////////////////////////////////////////////////////////

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'chat-page';

		$chat_data['page_title'] = 'Chat | Relayy';

        

        $chat_data['my_name'] = $this->cfname." ".$this->clname;

        if($jquery == 1){
            $this->load->view('chat', $chat_data);            
        } 
        else{
            $this->load->view('templates/header-chat', $chat_data);

            $this->load->view('templates/left-sidebar', $chat_data);

            $this->load->view('chat', $chat_data);

            $this->load->view('templates/right-sidebar', $chat_data);

            $this->load->view('templates/footer-chat', $chat_data);
        }       
        
	}	

    public function UpdateLeftbar(){
        
        $chat_data = $this->getChatData();

        $this->load->view('dialogs', $chat_data);
    }

	public function notification() {

		$did = $this->input->post('did');
		
		$notification = $this->input->post('notification');

        echo $this->moption->update($this->cid, "notify_".$did, $notification);

        exit;
	}

    public function saveMessage(){
        $fuid = $this->input->post('from_uid');

        $msgId = $this->input->post('message_id');

        $msg = $this->input->post('message');

        $date = $this->input->post('date');

        $did = $this->input->post('dialogID');

        $data_arr=array(TBL_COMMENT_WHOM=>$fuid, TBL_COMMENT_WHO=>$this->cuid, TBL_COMMENT_MID => $msgId, TBL_COMMENT_TEXT=>$msg, TBL_COMMENT_DATE=>$date);

        $res = $this->mcomment->save($data_arr);
        
        if($res === "unsave"){
            $done = $this->mhistory->unsave($msgId);
            if(!$done) echo "error";
            else echo $res;
            exit; 
        } 
        $whomUser = $this->muser->getWithUID($fuid);
        $opt = $this->moption->get($whomUser->{TBL_USER_ID});
        //================== save comment info to offline user  =========
        if($opt[0][TBL_OPTION_COMMENT] == 1){
            //===============  Inteval case  ======
            
            $this->updateComment($msg, $this->cfname." ".$this->clname, $whomUser->{TBL_USER_ID});
            
        }else if($opt[0][TBL_OPTION_COMMENT] == 2){    
               //send comment notification            
            $this->email->sendCommentNotification($this->cfname." ".$this->clname, $n_value->{TBL_USER_EMAIL}, $msg, site_url('profile/dashboard'));
        }
        $this->mhistory->save($msgId, $did);

        //add to feed
        $data_arr = array(
            TBL_FEED_WHO => $this->cfname." ".$this->clname,
            TBL_FEED_WHOM => $whomUser->{TBL_USER_FNAME}." ".$whomUser->{TBL_USER_LNAME},
            TBL_FEED_TYPE => 3,
            TBL_FEED_WHO_ID => $this->cid,
            TBL_FEED_WHO_BIO => $this->cbio,
            TBL_FEED_WHOM_ID => $whomUser->{TBL_USER_ID},
            TBL_FEED_WHOM_BIO => $whomUser->{TBL_USER_BIO}
        );
        $this->mfeed->add($data_arr);
        echo "success";
        
        
    }

    public function likeMessage(){
        $msgId = $this->input->post('message_id');
        $did = $this->input->post('dialogID');
        $res = $this->mhistory->checkLike($msgId, $this->cuid);
        if(!$res){//like
            $res = $this->mhistory->like($msgId, $this->cuid, $did);
            echo "success";
        }else{//unlike
            $res = $this->mhistory->unlike($msgId);
            echo "unlike";
        }
        
    }

    public function deleteMessage(){
        $msgId = $this->input->post('message_id');
        $did = $this->input->post('dialogID');
        $res = $this->mhistory->deleteMsg($msgId, $did);
        echo "success";
    }

    public function GetChatInfo(){
        $did = $this->input->post('dialog_id');
        $res = $this->mhistory->getMsgStates($did);

        $States = array();
        if(!$res){
            echo "empty";
        }else{
            foreach($res as $state){
                $node = new stdClass();
                $node->mid = $state[TBL_HISTORY_MID];
                $node->save = $state[TBL_HISTORY_SAVE];
                $node->like = $state[TBL_HISTORY_LIKE];
                $node->del = $state[TBL_HISTORY_DTIME];
                $States[] = $node;
            }
            echo json_encode($States);

        }

    }

    public function getPhoto(){
        $uid = $this->input->post('uid');
        $user = $this->muser->getWithUID($uid);
        if(strlen($user->{TBL_USER_PHOTO}) == 0) echo asset_base_url().'/images/emp.jpg';
        else echo $user->{TBL_USER_PHOTO}."q.q".$user->{TBL_USER_ID};
    }

    public function approveTeamUp(){
        $did = $this->input->post('did');
        $res = $this->mchat->updateChatwithDID($did);
        $mailArray = "";
        foreach (json_decode($res->{TBL_CHAT_OCCUPANTS}) as $occupant) {
            $user = $this->muser->get($occupant);
            $mailArray.= $user->{TBL_USER_EMAIL}.'<br>';
        }
        echo $mailArray;
        foreach (json_decode($res->{TBL_CHAT_OCCUPANTS}) as $occupant) {//send email or summary to all occupants except yourself( Entrep: approve notify, advisor: invite notify)
                $tUser = $this->muser->get($occupant);
                if($tUser->{TBL_USER_EMAIL} === $this->cemail) continue;
                if(0){
                    $opt = $this->moption->get($tUser->{TBL_USER_ID}); 
                    if($opt[0][TBL_OPTION_APPROVE] == 1){
                        $this->updateApprove($res->{TBL_CHAT_NAME}, $this->cemail, $did, $tUser->{TBL_USER_ID});
                    }
                    else if($opt[0][TBL_OPTION_APPROVE] == 2){
                        $this->email->approveChat($this->cemail, $this->cfname." ".$this->clname, $tUser->{TBL_USER_EMAIL},
                         $tUser->{TBL_USER_FNAME}, $this->inviteChatLink($user_id, $tUser->{TBL_USER_EMAIL}, $res->{TBL_CHAT_DID}), $res->{TBL_CHAT_NAME});
                    }
                }
                else{
                    $opt = $this->moption->get($tUser->{TBL_USER_ID}); 
                    if($opt[0][TBL_OPTION_INVITE] == 1){
                        $this->updateInvite($res->{TBL_CHAT_NAME}, $this->cemail, $res->{TBL_CHAT_DID}, $occupant);
                    }
                    else if($opt[0][TBL_OPTION_INVITE] == 2){
                        $this->email->inviteChat($this->cfname." ".$this->clname, $res->{TBL_CHAT_NAME}, "TeamUp", $mailArray, $this->inviteChatLink($occupant, $tUser->{TBL_USER_EMAIL}, $res->{TBL_CHAT_DID}), $tUser->{TBL_USER_EMAIL}, $tUser?$tUser->{TBL_USER_FNAME}:"Hi", $this->cemail);
                    }   
                }                 
        }
       
        echo "success";
    }

    public function disapproveTeamUp(){
        $did = $this->input->post('did');
        $dialog = $this->mchat->DeactivateChatwithDID($did);
        foreach (json_decode($dialog->{TBL_CHAT_OCCUPANTS}) as $occupant) {
            $user = $this->muser->get($occupant);
            $this->email->deproveChat($this->cemail, $this->cfname." ".$this->clname, $user->{TBL_USER_EMAIL}, $user->{TBL_USER_FNAME}, $dialog->{TBL_CHAT_NAME});
        }
    }



    public function CreateTeamUp($q_id){
        $this->loginCheck();        

        ///////////////////////////

        $data = $this->getChatData();

        $chatrooms = $this->mchat->getRoomsWithID($q_id);
        $photo = array();
        foreach($chatrooms as $room){
            foreach(json_decode($room[TBL_CHAT_OCCUPANTS]) as $userID){
                $emt = new stdClass();
                $emt->id = $userID;
                $emt->photo = $this->muser->get($userID)->{TBL_USER_PHOTO};
                $photo[] = $emt;
            }
        }
        $data['body_class'] = 'teamup-page';

        $data['page_title'] = 'Create TeamUp | Relayy';

        $data['chatrooms'] = $chatrooms;
        $data['photo'] = $photo;
        $question = $this->mquestions->getQuestionwithID($q_id);
        $data['name'] = $question[TBL_QUESTION_TITLE];
        $data['askerid'] = $question[TBL_QUESTION_ASKER_ID];
        $data['q_id'] = $question[TBL_QUESTION_ID];


        $waiting_advisors = $this->mquestions->getWaitingAdvisors($q_id);
        $advisors = array();
        foreach(json_decode($waiting_advisors) as $advisorID){
            $advisors[] = $this->muser->get($advisorID);
        }
        $data['waiting_advisors'] = $advisors;

        $this->load->view('templates/header-chat', $data);

        $this->load->view('create_teamup', $data);

        $this->load->view('templates/footer-chat', $data);

    }

    public function updateComment($message, $name, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_COMMENT, $id);
        $pre_comment = array();
        if($temp){
            $pre_comment = json_decode($temp);
            $emt = new stdClass();
            $emt->message = $message;
            $emt->name = $name;
            $pre_comment[] = $emt;
        }
        else{
            $emt = new stdClass();
            $emt->message = $message;
            $emt->name = $name;
            $pre_comment[] = $emt;
        }
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_COMMENT => json_encode($pre_comment)), $id);
    }

    public function flagMessage(){
        $txt = $this->input->post('text');
        $occupants = $this->input->post('d_occupants');
        $dname = $this->input->post('d_name');
        $senderName = $this->input->post('senderName');
        $mailArray = "";

        $admin_Users = $this->muser->getAdminUsers();
        $admin_emails = "";
        for ($i = 0; $i < count($admin_Users); $i++) {
                $admin_emails.= $admin_Users[$i]["email"].",";                
        }

        foreach (json_decode($occupants) as $occupant) {
            $email = $this->muser->getEmailwithID($occupant);
            $mailArray.= "         ".$email->{TBL_USER_EMAIL}.'<br>';            
        }                 

        $this->email->sendFlagNotification($txt, $admin_emails , $this->cfname." ".$this->clname, $senderName, $dname, $mailArray);
    }

    public function saveBadges(){
        $state = $this->input->post('state');
        $this->muser->saveBadges($state, $this->cuid);
    }

    public function getBadges(){
        $uid = $this->input->post('uid');
        $state = $this->muser->getBadges($uid);
        echo $state->{TBL_USER_UNREAD};
    }

    public function getBlockUsers(){
        $uid = $this->input->post('uid');
        $state = $this->muser->getBlockList($uid);
        echo $state->{TBL_USER_BLOCKLIST};
    }

    public function saveBlockList(){
        $list = $this->input->post('list');
        $this->muser->saveBlockList($this->cuid, $list);
    }

	public function dialog() {
		
		$did = $this->input->post('did');
		$ret_arr = array(
        			'notify' => $this->moption->get($this->cid, "notify_".$did)
        			// 'd_name' => ,
        			// 'd_owner' => ,
        			// 'd_users' => 
        		);

		$dialog_arr = $this->mchat->getDialogs($this->cid);

		$find = FALSE;

		foreach ($dialog_arr as $dialog) {

			if ($dialog[TBL_CHAT_DID] == $did) {

                $this->jid = $dialog[TBL_CHAT_JID];

				$ret_arr['d_id'] = $dialog[TBL_CHAT_DID];

		    	$ret_arr['d_name'] = $dialog[TBL_CHAT_NAME];

		    	$ret_arr['d_type'] = $dialog[TBL_CHAT_TYPE];

		    	$d_occupants = json_decode($dialog[TBL_CHAT_OCCUPANTS]);

		    	$d_users = array();

		    	foreach ($d_occupants as $d_user) {
					$d_users[] = $this->muser->getUserArray($d_user);
		    	}

		    	$ret_arr['d_users'] = $d_users;

		    	$d_owner = $this->muser->get($d_occupants[0]);

		    	if ($d_owner->{TBL_USER_ID} == $this->cid) $ret_arr['d_owner'] = "Me";
		    	else {
                    $ret_arr['d_owner'] = $d_owner->{TBL_USER_FNAME};   
                    if (!$ret_arr['d_owner']) {
                        $str_arr = explode("@", $d_owner->{TBL_USER_EMAIL});
                        $ret_arr['d_owner'] = $str_arr[0];
                    }
                }

		    	$find = TRUE;

		    	break;
			}
		}

		if (!$find) {echo "error"; exit;}

        echo json_encode($ret_arr);
        exit;	
	}

	public function delete() {
		
		$did = $this->input->post('did');

        echo $this->mchat->delete($did);

        exit;	
	}

	public function leave() {
		
		$did = $this->input->post('did');

		$dialog = $this->mchat->get($did);

		$new_occupants = array();

		foreach (json_decode($dialog->{TBL_CHAT_OCCUPANTS}) as $occu_id) {
			if ((int)$occu_id != $this->cid) {
				$new_occupants[] = $occu_id;
			}
		}

        echo json_encode($new_occupants);

		echo $this->mchat->update($dialog->{TBL_CHAT_DID}, array(
            TBL_CHAT_OCCUPANTS => json_encode($new_occupants)
        ));

        exit;	
	}

	public function remove() {
		
		$did = $this->input->post('did');
		$uid = $this->input->post('uid');

		$dialog = $this->mchat->get($did);

		$new_occupants = array();

		foreach (json_decode($dialog->{TBL_CHAT_OCCUPANTS}) as $occu_id) {
			if ($occu_id != $uid) {
				$new_occupants[] = $occu_id;
			}
		}
		echo $this->mchat->update($dialog->{TBL_CHAT_DID}, array(
            TBL_CHAT_OCCUPANTS => json_encode($new_occupants)
        ));

        exit;	
	}

    public function msgUpdate() {

        $did = $this->input->post('did');
        $uid = $this->input->post('sender');
        $msg = $this->input->post('msg');

        
        if($uid != $this->cid){
            
            return;
        }
        $now = new DateTime();
        $now->format('Y-m-d H:i:s');    // MySQL datetime format
        $currentTime = $now->getTimestamp();

        $chat_data = $this->getChatData();
        
        $dialog_arr = $this->mchat->getDialogs($this->cid);

        $flag = 0;
        
        foreach ($dialog_arr as $dialog) {

            if ($dialog['did'] == $did) {
                $flag = 1;

                $chat_data['d_occupants'] = json_decode($dialog[TBL_CHAT_OCCUPANTS]);
                $chat_data['d_users'] = array();
                foreach ($chat_data['d_occupants'] as $d_user) {

                    $tempUser = $this->muser->getUserArray($d_user);
                    $opt = $this->moption->get($tempUser[TBL_USER_ID]);
                    if($tempUser[TBL_USER_ID] == $this->cid ) continue;

                    //update unread message into the database for offline user
                    $unread = $tempUser[TBL_USER_UNREAD];
                    if(strpos($unread, $did) === false){
                        $new = json_decode($unread);
                        $new[] = $did;
                        $this->muser->edit($tempUser[TBL_USER_ID], array(TBL_USER_UNREAD => json_encode($new)));
                    }
                    
                    //================== send unread message to offline user  =========

                    if($opt[0][TBL_OPTION_UNREAD] == 1){//summary
                        $this->updateUnread($dialog['name'], $dialog['did'], $tempUser[TBL_USER_ID]);
                        
                    }
                    else if($opt[0][TBL_OPTION_UNREAD] == 2 && $currentTime - $tempUser[TBL_USER_TIME] > 500){//instance message
                        $str_arr = explode("@", $dialog[TBL_CHAT_JID]);
                        $dialogID = $str_arr[0];
                        $this->email->sendEmailNotification($this->cfname, $msg, site_url()."chat/channel/".$dialogID, $tempUser[TBL_USER_EMAIL], $tempUser[TBL_USER_FNAME]);
                        echo "sent unread messaget to".$tempUser[TBL_USER_EMAIL]." !";
                    }     

                }

                break;
            }
        }

        if($flag == 0) echo "removed/";
        if($did === "57a9eb82a28f9aee4e000010" && $uid !== "401565172"){
            echo $this->mchat->update($did, array(
                TBL_CHAT_SENDER => $uid,
                TBL_CHAT_TIME => date('Y-m-d H:i:s')
            ));
        }
        else{
            echo $this->mchat->update($did, array(
                TBL_CHAT_SENDER => $uid,
                TBL_CHAT_MESSAGE => $msg,
                TBL_CHAT_TIME => date('Y-m-d H:i:s')
            ));
        }
        

        exit;
    }

    public function updateApprove($name, $email, $did, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_APPROVE, $id);
        $pre_approve = array();
        if($temp){
            $pre_approve = json_decode($temp);
            $emt = new stdClass();
            $emt->name = $name;
            $emt->email = $email;
            $emt->did = $did;
            $pre_invite[] = $emt;
        }
        else{
            $emt = new stdClass();
            $emt->name = $name;
            $emt->email = $email;
            $emt->did = $did;
            $pre_approve[] = $emt;
        }
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_APPROVE => json_encode($pre_approve)), $id);
    }

    public function updateUnread($chatName, $did, $id){
            $temp = $this->moption->getSummaryField(TBL_SUM_UNREAD, $id);
            $pre_unread = array();
            if($temp){
                $pre_unread = json_decode($temp);
                $u_flag = 0;
                foreach($pre_unread as $emt){
                    if($emt->did === $did){
                        $emt->num ++; 
                        $u_flag = 1;
                        break;
                    } 
                }

                if(!$u_flag){
                    $emt = new stdClass();
                    $emt->name = $chatName;
                    $emt->did = $did;
                    $emt->num = 1;
                    $pre_unread[] = $emt;
                }
            }
            else{
                $emt = new stdClass();
                $emt->name = $chatName;
                $emt->did = $did;
                $emt->num = 1;
                $pre_unread[] = $emt;
            }

            $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_UNREAD => json_encode($pre_unread)), $id);
            
            
    }
    
    public function av_users(){
        $email = $this->input->post('email');
        $userList = $this->muser->getAvailableusers($this->cemail);
        if ($email == '') {echo json_encode($userList);exit;}
        $is_new = FALSE;
        foreach ($userList as $user) {
            if ($user[TBL_USER_EMAIL] == $email) {
                $is_new = TRUE;
                echo json_encode(array($user)); exit;
            }
        }
        if (!$is_new) echo json_encode(array());exit;
    }

    public function users()
    {
        $email = $this->input->post('email');
        $randUserList = $this->muser->getUserlist(100);
        
        $userList = array();
        foreach ($randUserList as $user) {
            if ($user[TBL_USER_EMAIL] == $this->cemail) continue;
            $userList[] = $user;
        }

        if ($email == '') {echo json_encode($userList);exit;}
        $is_new = FALSE;
        foreach ($userList as $user) {
            if ($user[TBL_USER_EMAIL] == $email) {
                $is_new = TRUE;
                echo json_encode(array($user)); exit;
            }
        }
        if (!$is_new) echo json_encode(array());exit;
    }

    public function checkExist(){
        $occupants = $this->input->post('occupants');
        $type = $this->input->post('type');
        $r_occupants = array($this->cid);
        if($type == 0){
            foreach ($occupants as $occupant) {            
                $r_occupants[] = $occupant[1];
            }           
        }
        else{
            $admin_Users = $this->muser->getAdminUsers();
            foreach($admin_Users as $admin){
                $r_occupants[] = $admin["id"]; 
            }
        }            
        $res = $this->mchat->checkDialog(json_encode($r_occupants), $type);
        if(!$res) echo "no_exist";
        else{
            echo $res->{TBL_CHAT_DID};
        } 
    }

    public function newTeamUp(){
        $did = $this->input->post('did');
        $jid = $this->input->post('jid');
        $dname = $this->input->post('dname');
        $ddetail = $this->input->post('ddesc');
        $dtype = $this->input->post('type');
        $occupants = $this->input->post('occupants');
        $qid = $this->input->post('q_id');
        $askerid = $this->input->post('askerid');
        $mailArray = "";
        foreach ($occupants as $occupant) {
            $user = $this->muser->get($occupant);
            $mailArray.= $user->email.'<br>';
        }

        $newChat = $this->mchat->add(array(
                TBL_CHAT_DID => $did,
                TBL_CHAT_NAME => $dname,
                TBL_CHAT_OCCUPANTS => json_encode($occupants),
                TBL_CHAT_TYPE => $dtype,
                TBL_CHAT_OWNER => $askerid,
                TBL_CHAT_STATUS => CHAT_STATUS_INIT,
                TBL_CHAT_JID => $jid,
                TBL_CHAT_QUESTIONID => $qid
        ));

        $question = $this->mquestions->getQuestionwithID($qid);
        $waiters = json_decode($question[TBL_QUESTION_WAIT_IDS]);
        $joiners = json_decode($question[TBL_QUESTION_JOIN_IDS]);
        $new_waiters = array();
        foreach($occupants as $occupant) {

            //===================== delete id from routed ids
            if($occupant == $askerid) continue;

            //===================== add id to accepted ids
            if(strpos($question[TBL_QUESTION_JOIN_IDS], $occupant) === false) {
                $joiners[] = $occupant;
            }
        }
        foreach($waiters as $waiter){
            if(in_array($waiter, $occupants)) continue;
            $new_waiters[] = $waiter;
        }


        $this->mquestions->updateQuestion($qid, array(TBL_QUESTION_WAIT_IDS => json_encode($new_waiters), TBL_QUESTION_JOIN_IDS => json_encode($joiners)));
        $this->mquestions->updateQuestion($qid, array(TBL_QUESTION_STATUS=> QUESTION_STATUS_LAUNCHED));
        echo "success";


    }

    public function removeTeamUp(){
        $did = $this->input->post('did');
        $q_id = $this->input->post('q_id');
        $askerid = $this->input->post('askerid');
        $creatorID = $this->input->post('creatorID');
        $chat = $this->mchat->get($did);
        $occupants = $chat->occupants;

        $question = $this->mquestions->getQuestionwithID($q_id);
        $waiters = json_decode($question[TBL_QUESTION_WAIT_IDS]);
        $joiners = json_decode($question[TBL_QUESTION_JOIN_IDS]);
        $new_joiners = array();
        foreach(json_decode($occupants) as $occupant) {
            if($occupant == $creatorID) continue;
            //===================== delete id from routed ids
            if($occupant == $askerid) continue;
            
           
            //===================== add id to accepted ids
            if(strpos($question[TBL_QUESTION_WAIT_IDS], $occupant) === false) {
                $waiters[] = $occupant;
            }
        }

        foreach($joiners as $joiner){
            if(in_array($joiner, json_decode($occupants))) continue;
            $new_joiners[] = $joiner;
        }

        $this->mchat->delete($did);
        $this->mquestions->updateQuestion($q_id, array(TBL_QUESTION_WAIT_IDS => json_encode($waiters), TBL_QUESTION_JOIN_IDS => json_encode($new_joiners)));
        echo "success";

    }

    public function newChat()// return value => "new"  or  dialogID
    {
        $did = $this->input->post('did');
        $jid = $this->input->post('jid');
        $dname = $this->input->post('dname');
        $ddetail = $this->input->post('ddesc');
        $dtype = $this->input->post('type');
        $occupants = $this->input->post('occupants');
        $state = $this->input->post('state');
        $qid = $this->input->post('q_id');
        $r_occupants = array($this->cid);
        $r_emails = array($this->cemail);
        $mailArray = $this->cemail;
        if(!$qid) $qid = 0;

        //================================= SUPPORT CHAT ==========================
        $admin_emails = "";
        $nameArray = $this->cfname." ".$this->clname;
       
        //================================= new CHAT ==========================
            foreach ($occupants as $occupant) {
                $mailArray.= '<br>'.$occupant[0];
                if (in_array($occupant[1], $r_occupants)) continue;
                if ($occupant[1] == "") {
                    
                    $oldUser = $this->muser->getEmail($occupant[0]);
                    $new_id = NULL;
                    
                    if ($oldUser) {
                        $new_id = $oldUser->{TBL_USER_ID};
                        $this->muser->edit($new_id, array(TBL_USER_STATUS=>USER_STATUS_INVITE));

                    } else {
                        $data_arr = array(
                            TBL_USER_TYPE => USER_TYPE_ENTREP,
                            TBL_USER_STATUS => $this->ctype!=USER_TYPE_ENTREP?USER_STATUS_INVITE:USER_STATUS_INIT,
                            TBL_USER_EMAIL => strtolower($occupant[0])
                        );
                        $new_id = $this->muser->add($data_arr);
                    }
                    
                    if (!$new_id) {echo "error";exit;}
                    $this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($new_id, $occupant[0]), $occupant[0]);
                    $r_occupants[] = $new_id;
                    $r_emails[] = $occupant[0];
                } else {
                    $r_occupants[] = $occupant[1];
                    $r_emails[] = $occupant[0];
                }    
            }
        
        $newChat = $this->mchat->add(array(
                TBL_CHAT_DID => $did,
                TBL_CHAT_NAME => $dname,
                TBL_CHAT_OCCUPANTS => json_encode($r_occupants),
                TBL_CHAT_TYPE => $dtype,
                TBL_CHAT_OWNER => $this->cid,
                TBL_CHAT_STATUS => CHAT_STATUS_LIVE,
                TBL_CHAT_JID => $jid,
                TBL_CHAT_QUESTIONID => $qid
            ));


        //If new chat, should send notifications to the occupants
        if (!$newChat) {
            for ($i = 0; $i < count($r_occupants); $i++) {
                $user_email = $r_emails[$i];   
                if($user_email === $this->cemail) continue;
                $tUser = $this->muser->getEmail($user_email);              
                              
                $opt = $this->moption->get($tUser->{TBL_USER_ID}); 
                if($opt[0][TBL_OPTION_INVITE] == 1){
                    $this->updateInvite($dname, $this->cemail, $did, $tUser->{TBL_USER_ID});
                }
                else if($opt[0][TBL_OPTION_INVITE] == 2){
                    $this->email->inviteChat($this->cfname." ".$this->clname, $dname, $ddetail, $mailArray, $this->inviteChatLink($r_occupants[$i], $user_email, $did), $user_email, $tUser?$tUser->{TBL_USER_FNAME}:"Hi", $this->cemail);
                }    
            }
            echo "new";
            //echo $did;
        }
        else{           
                echo $newChat->{TBL_CHAT_DID};
                exit;
        } 
        // if($this->ctype == USER_TYPE_ENTREP){
        //     //request chat
        //     $admin_Users = $this->muser->getAdminUsers();
        //     $admin_emails = "";
        //     for ($i = 0; $i < count($admin_Users); $i++) {
        //             $admin_emails.= $admin_Users[$i]["email"].",";                    
        //         }
        //     $this->email->requestChat($this->cfname." ".$this->clname, $dname, $ddetail, $mailArray, $admin_emails);
        //     //pending message to self
        //     $this->email->notifyPending($this->cfname, $this->cemail);
            
        // }
        
    }

    public function updateInvite($name, $email, $did, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_INVITE, $id);
        $pre_invite = array();
        if($temp){
            $pre_invite = json_decode($temp);
            $emt = new stdClass();
            $emt->name = $name;
            $emt->email = $email;
            $emt->did = $did;
            $pre_invite[] = $emt;
        }
        else{
            $emt = new stdClass();
            $emt->name = $name;
            $emt->email = $email;
            $emt->did = $did;
            $pre_invite[] = $emt;
        }
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_INVITE => json_encode($pre_invite)), $id);

    }
    
    function addMember()
    {
        $did = $this->input->post('did');
        $occupants = $this->input->post('occupants');
        
        $r_occupants = array();
        $r_emails = array();
        
        $mailArray = "";
        $newChat = $this->mchat->get($did);
        foreach(json_decode($newChat->{TBL_CHAT_OCCUPANTS}) as $id){
            if($this->muser->getEmailwithID($id) == FALSE) continue;
            $mailArray .= $this->muser->getEmailwithID($id)->{TBL_USER_EMAIL}."<br>";
        }

        foreach ($occupants as $occupant) {
            $mailArray.= $occupant[0]."<br>";
            if ($occupant[1] == "") {
                 
                $oldUser = $this->muser->getEmail($occupant[0]);
                $new_id = NULL;
                if ($oldUser) {
                    $new_id = $oldUser->{TBL_USER_ID};
                    $this->muser->edit($new_id, array(TBL_USER_STATUS=>USER_STATUS_INVITE));
                } else {
                    $data_arr = array(
                        TBL_USER_TYPE => USER_TYPE_ENTREP,
                        TBL_USER_STATUS => $this->ctype!=USER_TYPE_ENTREP?USER_STATUS_INVITE:USER_STATUS_INIT,
                        TBL_USER_EMAIL => strtolower($occupant[0]),
                        TBL_USER_ENTERED_CHATS => 1
                    );
                    $new_id = $this->muser->add($data_arr);
                }
                
                if (!$new_id) {echo "error";exit;}
                $this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($new_id, $occupant[0]), $occupant[0]);
                $r_occupants[] = $new_id;
                $r_emails[] = $occupant[0];
            } else {
                $r_occupants[] = $occupant[1];
                $r_emails[] = $occupant[0];
            }    
        }                  

        $DiaObj = $this->mchat->get($did);
        for ($i = 0; $i < count($r_occupants); $i++) {
            $user_email = $r_emails[$i];
            $tUser = $this->muser->getEmail($user_email);
            $opt = $this->moption->get($tUser->{TBL_USER_ID}); 
            if($opt[0][TBL_OPTION_INVITE] == 1){
                $this->updateInvite($DiaObj->{TBL_CHAT_NAME}, $this->cemail, $DiaObj->{TBL_CHAT_DID}, $tUser->{TBL_USER_ID});
            }
            else if($opt[0][TBL_OPTION_INVITE] == 2){
                $this->email->inviteChat($this->cfname." ".$this->clname, $DiaObj->{TBL_CHAT_NAME}, "undefined", $mailArray, $this->inviteChatLink($r_occupants[$i], $user_email, $did), $user_email, $tUser->{TBL_USER_FNAME}, $this->cemail);
            }
        }
        
        $n_occupants = json_decode($newChat->{TBL_CHAT_OCCUPANTS});
        foreach ($r_occupants as $uid) {
            if (!in_array($uid, $n_occupants)) $n_occupants[] = $uid;
        }
        
        $this->mchat->update($did, array(
            TBL_CHAT_OCCUPANTS => json_encode($n_occupants)
        ));
        
        echo "success";
    
        exit;
    }

    









}






