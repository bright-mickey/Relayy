<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ChatController extends CI_Controller
{
	var $cid;
	var $cuid;
    var $cfname;
    var $clname;
    var $cemail;
    var $clogin;
    var $cpassword;
    var $ctype;
    var $cstatus;
    var $cphoto;
    var $cbio;
    var $cfacebook;
    var $cgroup;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('mchat');
		$this->load->model('muser');
		$this->load->model('moption');
        $this->load->model('mcomment');
        $this->load->model('mquestions');
        $this->load->model('mhistory');
        $this->load->model('mfeed');

        $this->load->library('email');

		$this->cid = gf_cu_id();

		$this->cuid = gf_cu_uid();
		
		$this->cfname = gf_cu_fname();

		$this->clname = gf_cu_lname();

		$this->cemail = gf_cu_email();

		$this->clogin = gf_cu_email();

		$this->cpassword = gf_cu_password();

		$this->ctype = gf_cu_type();

		$this->cstatus = gf_cu_status();

		$this->cphoto = gf_cu_photo();

		$this->cbio = gf_cu_bio();

		$this->cfacebook = gf_cu_facebook();

        $this->cgroup = gf_cu_group();

        $this->ctime = gf_cu_signup_time();
	}

	public function getChatData()
	{   	
        $self = $this->muser->getUserArray($this->cid);
        if ( !$self )
        {
            gf_unregisterCurrentUser();
            redirect(site_url('home'), 'get');
            return; 
        }
        else{
            gf_registerCurrentUser($this->muser->getEmail($this->cemail));
        }

		$dialog_arr = $this->mchat->getDialogs(gf_cu_id());
        
        //print_r($dialog_arr);exit;

		$chat_data = array();

		if (count($dialog_arr) > 0) {
			$chat_data['d_id'] = 500;//$dialog_arr[0][TBL_CHAT_DID];

	    	$chat_data['d_name'] = $dialog_arr[0][TBL_CHAT_NAME];

	    	$chat_data['d_occupants'] = json_decode($dialog_arr[0][TBL_CHAT_OCCUPANTS]);

	    	$chat_data['d_users'] = array();

            $update_occupants = array();

	    	foreach ($chat_data['d_occupants'] as $d_user) {
                if($this->muser->getUserArray($d_user) != FALSE){
				    $chat_data['d_users'][] = $this->muser->getUserArray($d_user);
                    $update_occupants[] = $d_user;
                }
	    	}

	    	$chat_data['d_type'] = $dialog_arr[0][TBL_CHAT_TYPE];

	    	$chat_data['d_jid'] = $dialog_arr[0][TBL_CHAT_JID];

	    	$chat_data['d_status'] = $dialog_arr[0][TBL_CHAT_STATUS];

	    	$chat_data['d_message'] = $dialog_arr[0][TBL_CHAT_MESSAGE];

	    	$chat_data['d_time'] = $dialog_arr[0][TBL_CHAT_TIME];

            $chat_data['d_qid'] = $dialog_arr[0][TBL_CHAT_QUESTIONID];

	    	$occupants_arr = json_decode($dialog_arr[0][TBL_CHAT_OCCUPANTS]);
	    	
	    	$d_owner = $this->muser->get($occupants_arr[0]);
	    	
            if (!$d_owner) $d_owner = $this->muser->get($this->cid);
	    	$chat_data['d_owner'] = $d_owner->{TBL_USER_FNAME};

	    	$chat_data['d_noti'] = $this->moption->get($this->cid, 'notify_'.$chat_data['d_id']);

	    	if ($d_owner->{TBL_USER_ID} == gf_cu_id()) $chat_data['d_owner'] = "Me";
		} else {
            $chat_data['d_id'] = 0;
        }
    	
        foreach ($dialog_arr as &$dialog) {
            foreach (json_decode($dialog[TBL_CHAT_OCCUPANTS]) as $occupant) {
                    $dialog['d_users'][] = $this->muser->getUserArray($occupant);
            }

            if ($dialog[TBL_CHAT_TYPE] == CHAT_TYPE_PRIVATE) {
                $dialog['name'] = $dialog['d_users'][0][TBL_USER_FNAME]?$dialog['d_users'][0][TBL_USER_FNAME]." ".$dialog['d_users'][0][TBL_USER_LNAME]:$dialog['d_users'][0][TBL_USER_EMAIL];
            }
            if ($dialog[TBL_CHAT_SENDER]) {
                $sender = $this->muser->get($dialog[TBL_CHAT_SENDER]);
                if ($sender) {
                    $dialog['h_message'] = $sender->{TBL_USER_FNAME}.": " . $dialog[TBL_CHAT_MESSAGE];    
                } else {
                    $dialog['h_message'] = "Relayy User: ".$dialog[TBL_CHAT_MESSAGE];
                }
            } else {
                if ($dialog[TBL_CHAT_TYPE] == CHAT_TYPE_PRIVATE)
                    $dialog['h_message'] = $dialog['d_users'][0][TBL_USER_FNAME].": Created a new 1:1 chat";
                else
                    $dialog['h_message'] = $dialog['d_users'][0][TBL_USER_FNAME]." ".$dialog['d_users'][0][TBL_USER_LNAME].": Created a new Group chat"; 
            }
            
            $dialog[TBL_CHAT_TIME] = $this->timeAgo($dialog[TBL_CHAT_TIME]);
        }

		$chat_data['history'] = $dialog_arr;

		$chat_data['u_id'] = $this->cid;

		$chat_data['u_uid'] = $this->cuid;
		
		$chat_data['u_name'] = $this->cfname." ".$this->clname;

		$chat_data['u_fname'] = $this->cfname;

		$chat_data['u_lname'] = $this->clname;

		$chat_data['u_login'] = $this->clogin;

		$chat_data['u_email'] = $this->cemail;

		$chat_data['u_password'] = $this->cpassword;

		$chat_data['u_type'] = $this->ctype;

		$chat_data['u_status'] = $this->cstatus;

        $chat_data['u_group'] = $this->cgroup;

		$chat_data['u_photo'] = $this->cphoto;

		$chat_data['u_bio'] = $this->cbio;

		$chat_data['u_facebook'] = $this->cfacebook;

        $me = $this->muser->getEmail($this->cemail);
        $chat_data['u_stime'] = $me->{TBL_USER_SIGNUP};

        $now = new DateTime();
        $currentTime = $now->getTimestamp();
        $chat_data['c_time'] = $currentTime;

		return $chat_data;
	}

	public function loginCheck()
	{
		if ( !gf_isLogin() )
		{
			redirect(site_url('home'), 'get');
			
			return;
		}
	}

	public function maintenance()
	{
		$this->loginCheck();    	

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'maintenance-page';

		$chat_data['page_title'] = 'Maintenance | Relayy';		

    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('maintenance');

		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function inviteUserLink($id, $email)
	{
		return site_url('invite/user/'.$id."/".urlencode($email));
	}

	public function inviteChatLink($id, $email, $did)
	{
		return site_url('invite/chat/'.$id."/".urlencode($email)."/".$did);
	}
    
    public static function timeAgo($time_ago)
    {
        $time_ago = strtotime($time_ago);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "just now";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "one minute ago";
            }
            else{
                return "$minutes minutes ago";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "an hour ago";
            }else{
                return "$hours hrs ago";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "yesterday";
            }else{
                return "$days days ago";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "a week ago";
            }else{
                return "$weeks weeks ago";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "a month ago";
            }else{
                return "$months months ago";
            }
        }
        //Years
        else{
            if($years==1){
                return "one year ago";
            }else{
                return "$years years ago";
            }
        }
    }
}