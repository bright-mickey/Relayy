<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
    	$data['body_class'] = 'other-page';

		$data['page_title'] = 'Terms | Relayy';

    	$data['current_section'] = 'terms';
    
    	$this->load->view('templates/header', $data);
		
		$this->load->view('terms');

		$this->load->view('templates/footer');
	}
}