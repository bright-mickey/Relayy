<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Policy extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	$data['body_class'] = 'other-page';

		$data['page_title'] = 'Privacy & Policy | Relayy';

    	$data['current_section'] = 'policy';
    
    	$this->load->view('templates/header', $data);
		
		$this->load->view('policy');

		$this->load->view('templates/footer');
	}
}