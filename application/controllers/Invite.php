<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Invite extends ChatController//change CI_Controller to ChatController on 6th July
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('muser');
        $this->load->model('mcode');
        $this->load->model('mgroup');
        $this->load->model('mfeed');
        $this->load->library('email');
	}

	public function index()
	{
	
	// $this->maintenance();return;

	}

	public function user($uid, $email) 
	{
        $CI =& get_instance();
		if ( $email === $CI->session->userdata('cu_email'))
		{
			redirect(site_url('profile'), 'get');
			
			return;	
		}

        $user = $this->muser->get($uid);

        $email = str_replace('%40', '@', $email);		
        
        //if ($user->{TBL_USER_EMAIL} != $email) show_error("Sorry, You are not allowed to register!", 500, "Invite Error");
		
    	$data['body_class'] = 'invite-page';

		$data['page_title'] = 'Welcome! Relayy';

    	$data['current_section'] = 'invite';

    	$data['current_id'] = $uid;

    	$data['current_email'] = urldecode($email);

        if(!$user){

            $user_data['message'] = 'Invalid URL';
            $user_data['page_title'] = 'Notify | Relayy';
            gf_unregisterCurrentUser();
            $this->load->view('notify', $user_data);        

        }
        else{

        	$data['current_type'] = $user->{TBL_USER_TYPE};
        	$this->load->view('templates/header-home', $data);
    		$this->load->view('invite', $data);
    		$this->load->view('templates/footer', $data);

        }


	}

	public function chat($uid, $email, $did) {
        $CI =& get_instance();
        if ( $email === $CI->session->userdata('cu_email'))
        {
            redirect(site_url('profile'), 'get');
            
            return; 
        }

        $n_email = urldecode($email);
        
        $user = $this->muser->get($uid);
        
        if ($user->{TBL_USER_EMAIL} != $n_email) show_error("Sorry, You are not allowed to register!", 500, "Invite Error");
        
        if ($user->{TBL_USER_STATUS} == USER_STATUS_INVITE || $user->{TBL_USER_STATUS} == USER_STATUS_INVITED) {
            $data['body_class'] = 'invite-page';

            $data['page_title'] = 'Welcome! Relayy';

            $data['current_section'] = 'invite';

            $data['current_id'] = $uid;

            $data['current_email'] = urldecode($n_email);
            
            $data['current_did'] = urldecode($did);

            $data['current_type'] = $user->{TBL_USER_TYPE};
        
            $this->load->view('templates/header-home', $data);
            
            $this->load->view('invite', $data);

            $this->load->view('templates/footer', $data);    
        } else {
            redirect(site_url('home/channel/'.$email."/".$did), 'get');
        }
	}

    public function register(){
        $id = $this->input->post('id');
        
        $uid = $this->input->post('uid');
        
        $email = $this->input->post('email');

        $fname = $this->input->post('fname');
        
        $lname = $this->input->post('lname');

        $photo = $this->input->post('photo');
        
        $bio = $this->input->post('bio');

        $type = $this->input->post('type');
        
        $location = $this->input->post('location');
        
        $public_url = $this->input->post('public_url');
        
        $company = $this->input->post('company');

        $code = $this->mcode->getWithID($id);

        $now = new DateTime();
        $currentTime = $now->getTimestamp();
        $data_arr = array(   TBL_USER_ID => $id,
                             TBL_USER_UID => $uid,
                             TBL_USER_EMAIL => $email,
                             TBL_USER_FNAME => $fname,
                             TBL_USER_LNAME => $lname,
                             TBL_USER_PHOTO =>$photo,
                             TBL_USER_BIO =>$bio,
                             TBL_USER_TYPE =>$type,
                             TBL_USER_SIGNUP => $currentTime,
                             TBL_USER_STATUS => USER_STATUS_LIVE,
                             TBL_USER_LOCATION => $location,
                             TBL_USER_PUBLIC => $public_url,
                             TBL_USER_COMPANY =>$company,
                          );
        $this->muser->addInvitedUser($id, $data_arr);

        $object = $this->muser->getEmail($email);
            
        gf_registerCurrentUser($object);

        $data_arr = array(
            TBL_FEED_WHO => $object->{TBL_USER_FNAME}." ".$object->{TBL_USER_LNAME},
            TBL_FEED_TYPE => 5,
            TBL_FEED_WHO_ID => $object->{TBL_USER_ID},
            TBL_FEED_WHO_BIO => $object->{TBL_USER_BIO}
        );
        $this->mfeed->add($data_arr);


        echo "success";

    }

    public function check_code(){
        $code = $this->input->post('code');

        $res = $this->mcode->checkcode($code);
        $b_m = $this->mcode->checkModeratorCode($code);
        $b_l = $this->mcode->checkLeaderCode($code);
        
        if($b_l)  echo "no_group";
        else if($b_m)  echo 'Moderator_'.$b_m[TBL_INVITE_CODE];
        else if(!$res || $res[TBL_INVITE_REMAIN] == 0) echo "Invalid";
        else if($res[TBL_INVITE_TYPE] == 4) echo $res[TBL_INVITE_CODE];
        else echo "no_group";
    }

    public function ViewCodePage(){
        $id = $this->input->post('id');
        $res = $this->mcode->getWithID($id);
        $data['invite_code'] = $res;

        if($res[TBL_INVITE_TYPE] == 4){
            $g_data = $this->mgroup->checkwithID($res[TBL_INVITE_CODE]);
            if(!$g_data) $data['issue_group'] = 1;
            else $data['issue_group'] = 0;
        }else{
            $data['issue_group'] = 0;
        }

        if($this->ctype == 1){
            $invites = $this->mcode->getInviteInfo();
            $data['users'] = $invites;  

            $groups = $this->mgroup->getGroupInfo();
            $group_class = new stdClass();

            foreach($groups as $group){
                $group_class->{$group[TBL_GROUP_CODE]} = $group[TBL_GROUP_NAME];
            }
            $data['groups'] = $group_class; 

            $Leaders = $this->mcode->getLeaders(); 
            $data['leaders'] = $Leaders;
            $codes = array();
            foreach($Leaders as $leader){
                $codes[] = $leader[TBL_LEADER_CODE];
            }
            $data['codes'] = $codes;
            //echo json_encode((array)json_decode(json_encode($group_class), true));
        }

        $this->load->view('invite_code', $data);
    }

    public function CreateGroupLeader(){
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        $this->mcode->addLeaderCode($code, $name);
        echo "success";
    }

    public function DeleteLeader(){
        $code = $this->input->post('code');
        $this->moption->deleteleader($code);
    }

    public function UpdateLeaderName(){
        $code = $this->input->post('code');
        $name = $this->input->post('name');
        $this->mcode->UpdateLeader($code, $name);
        echo "success";
    }

    public function RequestMoreInvite(){
        $code = $this->input->post('code');
        $id = $this->input->post('id');
        $members = $this->input->post('members');
        $moderators = $this->input->post('moderators');
        $this->mcode->request($code, $members, $moderators);

        $res = $this->mcode->getWithID($id);
        $data['invite_code'] = $res;

        if($this->ctype == 1){
            $g_requests = $this->mcode->getGroupRequests();
            $data['g_requests'] = $g_requests;

            $n_requests = $this->mcode->getNormalRequests();
            $data['n_requests'] = $n_requests;
        }

        $this->load->view('invite_code', $data);
    }

    

    public function SaveInvite(){
        $code = $this->input->post('code');
        $members = $this->input->post('members');
        $moderators = $this->input->post('moderators');
        $this->mcode->UpdateInvite($code, $members, $moderators);
        echo "success";
    }

	public function accept() {

		$id = $this->input->post('reg_id');
        
        $did = $this->input->post('reg_did');
        
        $uid = $this->input->post('reg_uid');

        $password = $this->input->post('reg_pwd');
        
//        echo $id.$did.$password.$uid; exit;

        $user = $this->muser->get($id);

        $user = $this->muser->edit($id, array(
            TBL_USER_PWD => $password,
            TBL_USER_UID => $uid,
            TBL_USER_STATUS => USER_STATUS_LIVE,
            TBL_USER_TYPE => $user->{TBL_USER_TYPE} % 10
        ));
        
        if (!$user) show_error("An Error has occurred while registering!", 500, "Register Error");

		// $object = $this->muser->login($user->{TBL_USER_EMAIL}, $password);

        $login_status = $this->muser->login(strtolower($user->{TBL_USER_EMAIL}), $password);
        
        if($login_status == USER_LOGIN_SUCCESS) {

            $object = $this->muser->getEmail($user->{TBL_USER_EMAIL});
            
            gf_registerCurrentUser($object);

            if ($did) {
                redirect(site_url('chat/channel/'.$did), 'get');
            } else {
                redirect(site_url('profile/edit'), 'get');    
            }

        } else {
            if ($login_status == USER_LOGIN_DELETE)
                show_error("Your account had been deleted by admin!", 500, "Login Error");
            else if ($login_status == USER_LOGIN_PWD)
                show_error("Login password is incorrect!", 500, "Login Error");
            else 
                show_error("Couldn't find user on Relayy!", 500, "Login Error");
        }		        
	}
}