<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Search extends ChatController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	
	//$this->maintenance();return;
	
    	$this->loginCheck();  

    	$chat_data = $this->getChatData();  	
        
        $searchTxt = $this->input->post('search');
        
        $chat_data['users'] = $this->muser->searchUserlist($searchTxt);
        
        $chat_data['current'] = gf_cu_id();
        
        $chat_data['search'] = $searchTxt;
        //print_r($chat_data['users']);exit;

    	$chat_data['body_class'] = 'search-page';

		$chat_data['page_title'] = 'Search Result | Relayy';
    
    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('search');

		$this->load->view('templates/footer-chat', $chat_data);
	}
    
    public function action()
    {
        $did = $this->input->post('did');
        $jid = $this->input->post('jid');
        $occupant = $this->input->post('occupant');
        $user = $this->muser->get($occupant);
        
        $r_occupants = array($this->cid, $occupant);
                
        $newChat = $this->mchat->add(array(
                TBL_CHAT_DID => $did,
                TBL_CHAT_NAME => "Private",
                TBL_CHAT_OCCUPANTS => json_encode($r_occupants),
                TBL_CHAT_TYPE => CHAT_TYPE_PRIVATE,
                TBL_CHAT_STATUS => $this->ctype!=USER_TYPE_ENTREP?CHAT_STATUS_LIVE:CHAT_STATUS_INIT,
                TBL_CHAT_JID => $jid
            ));

        if ($newChat) {
            $this->email->inviteChat($this->cemail, $this->cfname." ".$this->clname, $this->inviteChatLink($occupant, $user->{TBL_USER_EMAIL}, $did), $user->{TBL_USER_EMAIL}, "", "");
            echo "success";
        }
        else echo "error";
        exit;
    }
}