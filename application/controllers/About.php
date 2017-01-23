<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	$data['body_class'] = 'other-page';

		$data['page_title'] = 'About | Relayy';

    	$data['current_section'] = 'about';
    
    	$this->load->view('templates/header', $data);
		
		$this->load->view('about');

		$this->load->view('templates/footer');
	}
}