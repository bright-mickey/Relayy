<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Users extends ChatController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	//$this->maintenance();return;
	
    	$this->loginCheck();

    	$this->roleCheck();    	

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'users-page';

		$chat_data['page_title'] = 'User Management | Relayy';

		$chat_data['users'] = $this->muser->getUserlist(USER_STATUS_ALL);

		$chat_data['current'] = gf_cu_id();

		$chat_data['page'] = 0;
    
    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('users', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}

	

	public function ActionUpdate(){
		
		$now = new DateTime();
		$now->format('Y-m-d H:i:s');    // MySQL datetime format
		$currentTime = $now->getTimestamp();
		$cUser = $this->muser->getEmail($this->cemail);
		if(!$cUser) return;
		if($cUser->status == 4){
			$user_data['message'] = 'Sorry, your account has been deleted.';
			$user_data['page_title'] = 'Notify | Relayy';
			gf_unregisterCurrentUser();
			$this->load->view('notify', $user_data);		
		}
		else if($cUser->status == 0){
			$user_data['message'] = 'Sorry, your account is pending by admin now.';
			$user_data['page_title'] = 'Notify | Relayy';
			gf_unregisterCurrentUser();
			$this->load->view('notify', $user_data);		
		}
		else{
			$object = $this->muser->getEmail($this->cemail);            
            gf_registerCurrentUser($object);
            
			$this->muser->edit($cUser->id, array(TBL_USER_TIME=> $currentTime));
			$UserStates = $this->muser->getUserStates();
			$arr_data=array();
			foreach($UserStates as $userState){
				if($userState[TBL_USER_TIME] < $currentTime - 100 && $userState[TBL_USER_TIME] > $currentTime - 500){
					$data['id'] = $userState['id'];
					$data['state'] = 'away';
				}
				else if($userState[TBL_USER_TIME] < $currentTime - 500){
					$data['id'] = $userState['id'];
					$data['state'] = 'offline';
				}else{
					$data['id'] = $userState['id'];
					$data['state'] = 'online';
				}
				$arr_data[]=$data;
			}
			echo json_encode($arr_data);
		}
		
	} 

	public function activity_feed(){
		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		$chat_data['feeds'] = $this->mfeed->get();

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

	   	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('activity_feed', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}	

	public function updateFeeds(){
		$r_num = $this->input->post('recent_num');

		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		$chat_data['feeds'] = $this->mfeed->getNewFeeds($r_num);

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

        $chat_data['new'] = 1;

		$this->load->view('update_feed', $chat_data);
	}

	public function LoadMoreFeeds(){
		$l_num = $this->input->post('last_num');

		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		$chat_data['feeds'] = $this->mfeed->getMoreFeeds($l_num);

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

        $chat_data['new'] = 0;

		$this->load->view('update_feed', $chat_data);
	}

	public function getServerTime(){
		$now = new DateTime();
        $currentTime = $now->getTimestamp();
        echo $currentTime;
	}

	public function Deactivate(){

		$uid = $this->input->post('id');

		$status = $this->input->post('status');

		$data_arr = array(TBL_USER_STATUS=> $status);

		$this->muser->updateUser($uid, $data_arr);

		echo "success";


	}

	public function check_email(){
		$email = $this->input->post('email');
		$res = $this->muser->getEmail($email);
		if($res[TBL_USER_STATUS] == USER_STATUS_DELETE) echo "deleted_user";
		else if(!$res) echo "no_exist";
		else echo "exist";
	}

	public function delete($id, $page) 
	{
		$this->loginCheck();

		$this->roleCheck();    	

		$userObj = $this->muser->get($id);

		echo $userObj->{TBL_USER_UID}."\\";

		//$this->email->removeUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});

		$this->muser->delete($id);

		if($page == 100){
			echo "success";
		} else if ($page == 0) {
			redirect(site_url('users'), 'get');
		} else if ($page == 1) {
			redirect(site_url('users/pending'), 'get');
		} else if ($page == 2) {
			redirect(site_url('users/activated'), 'get');
		} else {
			redirect(site_url('users/invited'), 'get');
		}
	}

	public function action($uid, $page) 
	{
		$this->loginCheck();

		$this->roleCheck();

		$userObj = $this->muser->changeStatus($uid);

		if ($userObj->{TBL_USER_STATUS} == USER_STATUS_LIVE) $this->email->approveUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});
		else $this->email->deproveUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});


		//page is always 0
		if ($page == 0) {
			redirect(site_url('users'), 'get');
		} else if ($page == 1) {
			redirect(site_url('users/pending'), 'get');
		} else if ($page == 2) {
			redirect(site_url('users/activated'), 'get');
		} else {
			redirect(site_url('users/invited'), 'get');
		}
	}

	public function getBioForActionPage(){
		$id = $this->input->post('id');
		$bio = $this->muser->getBiowithID($id);
		echo $bio;
	}

	public function invite($type, $email, $page) 
	{
		
		$emailAddress = str_replace('%40', '@', $email);
		$emailAddress = str_replace('%2c', ',', $emailAddress);		
		$emails = explode(",", $emailAddress);
		$res = "";
		foreach($emails as $email){

	        $oldUser = $this->muser->getEmail($email);
	        $newID = NULL;
	        if($res !== "") $res = $res."/";
	        if ($oldUser) {
	            $newID = $oldUser->{TBL_USER_ID};
	            $this->muser->edit($newID, array(TBL_USER_STATUS=>USER_STATUS_INVITE, TBL_USER_TYPE => $type, TBL_USER_GROUP => "", TBL_USER_CODE => ""));
	        	$this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($newID, $email), $email);
	        } else {
	            $newID = $this->muser->add(array(
	                TBL_USER_TYPE => $type,
	                TBL_USER_STATUS => USER_STATUS_INVITE,
	                TBL_USER_EMAIL => strtolower($email),
	                TBL_USER_CODE => $this->cgroup
	            ));    
				$this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($newID, $email), $email);
	        }
	        $res = $res.$newID;// add non-user's id to the chat occupants
		}
		if($page == 4){
			echo $res; // id1/id2/id3
			exit;	
		} 



		if ($page == 0) {
			redirect(site_url('users'), 'get');
		} else if ($page == 1) {
			redirect(site_url('users/pending'), 'get');
		} else if ($page == 2) {
			redirect(site_url('users/activated'), 'get');
		} else {
			redirect(site_url('users/invited'), 'get');
		}
		
		
	}

	

	private function roleCheck() {
		if (gf_cu_type() == USER_TYPE_ADVISOR || gf_cu_type() == USER_TYPE_ENTREP) 
		{
			redirect(site_url('profile'), 'get');
		}
	}
}