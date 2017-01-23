<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	
    	if ( !gf_isLogin() )
		{
			redirect(site_url('home'), 'get');
			
			return;	
		}

  //   	$data['body_class'] = 'chat-page';

		// $data['page_title'] = 'Chat | Relayy';

  //   	$this->load->view('templates/header-chat', $data);
		
		// $this->load->view('chat');

		// $this->load->view('templates/footer', $data);
	}
}