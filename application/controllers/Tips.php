<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tips extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	$data['body_class'] = 'other-page';

		$data['page_title'] = 'Tips | Relayy';

    	$data['current_section'] = 'tips';
    
    	$this->load->view('templates/header', $data);
		
		$this->load->view('tips');

		$this->load->view('templates/footer');
	}
}