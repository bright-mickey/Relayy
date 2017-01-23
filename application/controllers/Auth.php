<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('muser');
		$this->load->library('session');
        $this->load->library('email');
        $this->load->model('mfeed');
	}

	public function index()
	{
    	$data['body_class'] = 'home';

		$data['page_title'] = 'Welcome! Relayy';

    	$data['current_section'] = 'home';

    	$data['js_home'] = 1;
    
    	$this->load->view('templates/header-home');
		
		$this->load->view('home');

		$this->load->view('templates/footer', $data);
	}

	public function login() 
	{
    
        $email = $this->input->post('sgn_email');
        $password = $this->input->post('sgn_pwd');
        
        $did = $this->input->post('did');
        
        $login_status = $this->muser->login(strtolower($email), $password);
        
        if($login_status == USER_LOGIN_SUCCESS) {

            $object = $this->muser->getEmail($email);
            
            gf_registerCurrentUser($object);

            if ($did) {
                redirect(site_url('chat/channel/'.$did), 'get');
            } else if (gf_cu_type() == 1) {

            	redirect(site_url('users'), 'get');

            } else {

            	redirect(site_url('profile'), 'get');

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

    public function send_token()
    {
        $token = $this->input->post('token');
        $email = $this->input->post('email');
        $res = $this->muser->getEmail($email);        
        if($res == FALSE) echo "not_exist";
        else if($res->{TBL_USER_STATUS} == 0) echo "pending_user";
        else if($res->{TBL_USER_STATUS} == 4) echo "deleted_user";
        else{
            $res = $this->email->SendToken($token, $email);
            echo "success";
        }
    }

    public function pass_token()
    {
        $email = $this->input->post('email');
        $object = $this->muser->getEmail($email);

        if(!$object || $object->{TBL_USER_STATUS} != USER_STATUS_LIVE) echo "failed";
        else{
            gf_registerCurrentUser($object);
            echo "questions";
        }
    }

	public function register() 
	{
    	//TODO:  called when
    	$id = $this->input->post('reg_id');
    	$type = $this->input->post('reg_role');
    	$fname = $this->input->post('reg_fname');
        $lname = $this->input->post('reg_lname');
        $email = $this->input->post('reg_email');
        $password = $this->input->post('reg_pwd');

        $object = $this->muser->register(
                array(
                    TBL_USER_UID => $id,
                    TBL_USER_TYPE => $type,
                    TBL_USER_FNAME => $fname,
                    TBL_USER_LNAME => $lname,
                    TBL_USER_STATUS => USER_STATUS_LIVE,
                    TBL_USER_EMAIL => strtolower($email),
                    TBL_USER_PWD => $password
                    )
            );
        
        if($object) {

            gf_registerCurrentUser($object);

            $this->email->register(strtolower($email), $fname." ".$lname);

            redirect(site_url('profile'), 'get');

        } else {
            show_error("An Error has occurred while registering!", 500, "Register Error");
        }

	}

	public function linkedin()
	{
        $id = $this->input->post('li_id');
        $fname = $this->input->post('li_fname');
        $lname = $this->input->post('li_lname');
        $email = $this->input->post('li_email');
        $login = $this->input->post('li_login');
        $photo = $this->input->post('li_photo');
        $bio = $this->input->post('li_bio');
        $location = $this->input->post('li_location');
        $public = $this->input->post('li_public');
        $company = $this->input->post('li_company');       
        $role = $this->input->post('li_role');
        $group = $this->input->post('li_group'); 
        $code = $this->input->post('li_code');  
        
        if(!$role) $role = 4; 
        $now = new DateTime();
        $currentTime = $now->getTimestamp();
        
        $userObj = $this->muser->getEmail($email);
        if (!$userObj){
            $object = $this->muser->register(
                array(
                    TBL_USER_UID => $id,
                    TBL_USER_FNAME => $fname,
                    TBL_USER_LNAME => $lname,
                    TBL_USER_EMAIL => strtolower($email),
                    TBL_USER_FACEBOOK => $login,
                    TBL_USER_STATUS => USER_STATUS_LIVE,
                    TBL_USER_PHOTO => $photo,
                    TBL_USER_BIO   => $bio,
                    TBL_USER_TYPE  => $role,
                    TBL_USER_SIGNUP => $currentTime,
                    TBL_USER_LOCATION => $location,
                    TBL_USER_PUBLIC => $public,
                    TBL_USER_COMPANY => $company,
                    TBL_USER_GROUP => $group,
                    TBL_USER_CODE => $code
                    )
            );
            gf_registerCurrentUser($object);

            //add to feed
            $data_arr = array(
                TBL_FEED_WHO => $object->{TBL_USER_FNAME}." ".$object->{TBL_USER_LNAME},
                TBL_FEED_TYPE => 5,
                TBL_FEED_WHO_ID => $object->{TBL_USER_ID},
                TBL_FEED_WHO_BIO => $object->{TBL_USER_BIO}
            );
            $this->mfeed->add($data_arr);
            
        } 
        else{

            $data_arr = array();
            if($photo) $data_arr[TBL_USER_PHOTO] = $photo;
            if($bio) $data_arr[TBL_USER_BIO] = $bio;
            if($location) $data_arr[TBL_USER_LOCATION] = $location;
            if($public) $data_arr[TBL_USER_PUBLIC] = $public;
            if($company) $data_arr[TBL_USER_COMPANY] = $company;
            
            $data_arr[TBL_USER_TYPE] = $role;
           
            

            //add to feed
            if($userObj->{TBL_USER_STATUS} == 4){
                if($group) $data_arr[TBL_USER_GROUP] = $group;
                if($code) $data_arr[TBL_USER_CODE] = $code;
                $data_arr[TBL_USER_STATUS] = USER_STATUS_LIVE;

                $this->muser->updateLinkedInData($email, $data_arr, 1);//if deleted user signs up, add invite code (3rd param)
                $object = $this->muser->getEmail($email);
                $data_arr = array(
                    TBL_FEED_WHO => $object->{TBL_USER_FNAME}." ".$object->{TBL_USER_LNAME},
                    TBL_FEED_TYPE => 5,
                    TBL_FEED_WHO_ID => $object->{TBL_USER_ID},
                    TBL_FEED_WHO_BIO => $object->{TBL_USER_BIO}
                );
                $this->mfeed->add($data_arr);
            }
            else{
                $this->muser->updateLinkedInData($email, $data_arr, 0);
                $object = $this->muser->getEmail($email);
            }
            
            gf_registerCurrentUser($object);
        }
            
        if (gf_cu_type() == 1) {

        	redirect(site_url('chat'), 'get');

        } else {

        	redirect(site_url('profile'), 'get');
        	
        }

        
	}

	public function logout()
	{
		gf_unregisterCurrentUser();

		redirect(site_url('home'), 'get');

	}
}