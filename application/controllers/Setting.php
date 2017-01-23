<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Setting extends ChatController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	$this->loginCheck();  

    	$set_data['setval'] = $this->moption->get($this->cid);

    	$chat_data = $this->getChatData(); 

    	$user_data = $this->muser->getUserArray($this->cid);

    	$chat_data['my_status'] = $user_data['status'];

    	$chat_data['setval'] = $set_data['setval'];

    	$chat_data['body_class'] = 'setting-page';

		$chat_data['page_title'] = 'Settings | Relayy';
    
    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('setting', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function profile(){

		$this->loginCheck();  

    	$chat_data = $this->getChatData(); 

    	$user_data = $this->muser->getUserArray($this->cid);

    	$chat_data['my_status'] = $user_data['status'];

    	$chat_data['body_class'] = 'setting-page';

		$chat_data['page_title'] = 'Profile Setting | Relayy';
    
    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('setting', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function updateOption(){
		$field = $this->input->post('field');
    	$value = $this->input->post('value');
    	$data_arr = array($field => $value);
    	$this->moption->update($data_arr, $this->cid);


	}





}