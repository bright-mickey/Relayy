<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");
include_once (dirname(__FILE__) . "/UploadHandler.php");

class Questions extends ChatController
{
	public function __construct()            
	{
		parent::__construct();
		$this->load->model('mquestions');
		$this->load->model('mbusiness');
		$this->load->model('mcode');
		$this->load->model('mgroup');
	}

	public function index()
	{
		
    	$this->loginCheck();    	

    	$q_data = array();
    	
    	$chat_data = $this->getChatData();

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'question-page';

		$chat_data['page_title'] = 'Questions | Relayy';

    	$chat_data['current_id'] = $this->cid;

    	$res = $this->mgroup->get($this->cgroup);

    	$chat_data['my_group'] = isset($res)?$res[TBL_GROUP_NAME]:"";

    	$chat_data['my_group_image'] = isset($res)?$res[TBL_GROUP_IMAGE]:"";

    	$Advisors = $this->mquestions->getAllAdvisors();

			if($Advisors) $chat_data['advisors'] = $Advisors;
			else $chat_data['advisors'] = array();	

    	$questions = array();

    	if($this->ctype == 1){

    		$questions = $this->mquestions->getAllQuestions();

    		$chat_data['state'] = "Detail";

	   		$chat_data['array_question'] = $questions;
    	}

    	else if($this->ctype == 2){

    		$feed = $this->mquestions->getFirstFeed($this->cid);

    		$chat_data['state'] = "Feed";    

    		$chat_data['feed'] = json_decode(json_encode($feed), true);

    		if($feed){
    			$asker = $this->muser->getUserArray($feed->{TBL_QUESTION_ASKER_ID});
				$mygroup = $this->mgroup->get($asker[TBL_USER_GROUP]);
				$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
				$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];
    		}
    		

    	}else if($this->ctype == 3){

    		$questions = $this->mquestions->getOwnQuestion($this->cid);

    		$chat_data['state'] = "Detail";

	   		$chat_data['array_question'] = $questions;

    	}   	
    	else if($this->ctype == 4){

    		$questions = $this->mquestions->getGroupQuestions($this->cgroup);

    		$chat_data['state'] = "Detail";

	   		$chat_data['array_question'] = $questions;
    	}
   
    	$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		if($chat_data['state'] === 'Feed'){
			$business_data = $this->mbusiness->getArray($chat_data['feed']['askerid']);
			$user_data = $this->muser->getUserArray($chat_data['feed']['askerid']);
			$array_link = $this->mbusiness->getLinkswithID($chat_data['feed']['askerid']);
			
			$chat_data['array_link'] = $array_link;
			$chat_data['looking'] = $business_data['looking'];
			$chat_data['position'] = $business_data['position'];
			$chat_data['education'] = $business_data['education'];
			$chat_data['venture_name'] = $business_data['venture_name'];
			$chat_data['summary'] = $business_data['summary'];
			$chat_data['industry'] = $business_data['industry'];
			$chat_data['stage'] = $business_data['stage'];
			$chat_data['employee_num'] = $business_data['employee_num'];
			$chat_data['funding'] = $business_data['funding'];
			$chat_data['location'] = $user_data[TBL_USER_LOCATION];
			$chat_data['public_url'] = $user_data[TBL_USER_PUBLIC];
			$chat_data['asker_type'] = $user_data[TBL_USER_TYPE];
			$chat_data['company'] = json_decode($user_data[TBL_USER_COMPANY]);

			if(!$feed) $chat_data['state'] = "NoFeed"; 
			$this->load->view('question_feed', $chat_data);

		}else{
			$this->load->view('question_detail_draft', $chat_data);
		}		

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function add()
	{
	   	$fname = $this->input->post('fname');
    	$title = $this->input->post('title');
    	$context = $this->input->post('context');
    	$tags = $this->input->post('tags');
    	$link = $this->input->post('link');
    	$status = $this->input->post('status');
    	$post = $this->input->post('post');

		$now = new DateTime();
        //$time = $now->format('D M d, Y h:i A');     // MySQL datetime format(http://php.net/manual/en/function.date.php )
        $time = $now->getTimestamp();
    	$data_arr = array(
    					TBL_QUESTION_ASKER_ID => $this->cid,
    					TBL_QUESTION_TYPE => $this->ctype,
                        TBL_QUESTION_TITLE => $title,
                        TBL_QUESTION_CONTEXT => $context,
                        TBL_QUESTION_TAGS => $tags,
                        TBL_QUESTION_LINKS => $link,
                        TBL_QUESTION_FNAMES => $fname,
                        TBL_QUESTION_STATUS => $status,
                        TBL_QUESTION_TIME => $time,
                        TBL_QUESTION_POST => $post
                    );
		$new_id = $this->mquestions->add($data_arr);
		$to_emails = "";
		if($post === "public"){
	        $admin_Users = $this->muser->getAdminUsers();
			$subnum = $this->moption->getPublicSubNum();
	        foreach ($admin_Users as $admin) {
	                $to_emails.= $admin[TBL_USER_EMAIL].",";
	                $opt = $this->moption->get($admin[TBL_USER_ID]);
					if($opt[0][TBL_OPTION_SUBMIT] == 1){
			            $this->moption->AddSubNum($admin[TBL_USER_ID], $subnum);
			        }
			        else if($opt[0][TBL_OPTION_SUBMIT] == 2){
			        	$this->email->submitNotification($to_emails, $title, $this->cfname." ".$this->clname);
			        }
	        }
	    }
	    else{
	    	$moderators = $this->mcode->getModerators();
	        foreach ($moderators as $moderator) {
	                $to_emails.= $moderator[TBL_USER_EMAIL].","; 
	                $num = sizeof($this->mquestions->getGroupQuestions($moderator[TBL_USER_GROUP]));  
	                $opt = $this->moption->get($moderator[TBL_USER_ID]);
					if($opt[0][TBL_OPTION_SUBMIT] == 1){
			            $this->moption->AddSubNum($moderator[TBL_USER_ID], $num);
			        }
			        else if($opt[0][TBL_OPTION_SUBMIT] == 2){
			        	$this->email->submitNotification($to_emails, $title, $this->cfname." ".$this->clname);
			        }
	        }
	        echo $to_emails;
	    }
		if($new_id){
			//add to feed
			$data_arr = array(
				TBL_FEED_WHO => $this->cfname." ".$this->clname,
				TBL_FEED_TYPE => 1,
				TBL_FEED_TAG => $tags,
				TBL_FEED_WHO_ID => $this->cid,
				TBL_FEED_WHO_BIO => $this->cbio
			);
			$this->mfeed->add($data_arr);
			echo "success";	
		} 
		else echo "error";
	}



	public function changePost(){
		$id = $this->input->post('id');
		$post = $this->input->post('post');
		$data_arr = array(TBL_QUESTION_POST => $post);
		$this->mquestions->updateQuestion($id, $data_arr);

		if($post === "public"){
			$admin_Users = $this->muser->getAdminUsers();
			$subnum = $this->moption->getPublicSubNum();
	        $to_emails = "";
	        for ($i = 0; $i < count($admin_Users); $i++) {
	                $to_emails.= $admin_Users[$i]["email"].",";   
	                $opt = $this->moption->get($admin_Users[$i][TBL_USER_ID]);
					if($opt[0][TBL_OPTION_SUBMIT] == 1){
			            $this->moption->AddSubNum($admin_Users[$i][TBL_USER_ID], $subnum);
			        }              
	        }
	    }
	    echo "success";
	}

	public function fileupload(){
		$attachment_file=$_FILES["FileName"];
	    $output_dir = "uploads/";
	    $fileName = $_FILES["FileName"]["name"];
		$file_size =$_FILES['FileName']['size'];
	    $spl=explode('.',$fileName);
		$extensions= array(".jpeg",".jpg",".png", ".pdf", ".gif", ".JPEG", ".JPG", ".PNG", ".PDF", ".GIF");
      	$rule="\n\n(  Following files are allowed:\nsize: less than 1MB\nlength of file name: less than 20\nextension of file: png, jpg, jpeg, pdf, gif  )";
      	$allowed = 0;
		foreach($extensions as $extension){
			if(strpos($fileName, $extension) !== false && sizeof($spl) == 2){
				$allowed = 1;
				break;
			}
		}
		if($allowed == 0){
			echo "The extension is not allowed.".$rule;
      		exit;
		}
		else if(strlen($fileName) > 20){
			echo "The length of file name is too long.".$rule;
			exit;
		}
		else if($file_size > 1048576){
			echo "The file size is too big.".$rule;
			exit;
		}

	    $now = new DateTime();
        $now->format('Y-m-d H:i:s');    // MySQL datetime format
        $currentTime = $now->getTimestamp();
		move_uploaded_file($_FILES["FileName"]["tmp_name"],"uploads/".$currentTime.'_'.$fileName);

		if($fileName === "") echo "No file";
		else echo $currentTime.'_'.$fileName;
	}

	public function nextFeed(){
		$this->loginCheck();    	

    	$q_data = array();
    	
    	//print_r($chat_data);exit;
    	$chat_data = $this->getChatData();

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'question-next';

		$chat_data['page_title'] = 'Questions | Relayy';

    	$chat_data['current_id'] = $this->cid;


    	$q_id = $this->input->post('q_id');

	   	$r_ids = $this->input->post('r_ids');

	   	$a_ids = $this->input->post('a_ids');

	   	$b_accept = $this->input->post('b_accept');	   

	   	if($b_accept){
	   			$data_arr = array(TBL_QUESTION_ROUTE_IDS => $r_ids, TBL_QUESTION_ID => $q_id, TBL_QUESTION_ACCEPT_IDS => $a_ids, TBL_QUESTION_STATUS => QUESTION_STATUS_ACCEPTED);	
	   		   	$this->mquestions->updateAccept($q_id, $data_arr);
	   	} 
	   	else {
	   		$data_arr = array(TBL_QUESTION_ROUTE_IDS => $r_ids, TBL_QUESTION_ID=>$q_id);
		   	$this->mquestions->updateRoute($q_id, $data_arr);
	   	}

		$feed = $this->mquestions->getFirstFeed($this->cid);

		$chat_data['state'] = "Feed"; 

		if(!$feed) $chat_data['state'] = "NoFeed"; 

		$Advisors = $this->mquestions->getAllAdvisors();

		if($Advisors) $chat_data['advisors'] = $Advisors;
		else $chat_data['advisors'] = array();	

		$chat_data['feed'] = json_decode(json_encode($feed), true);


		$business_data = $this->mbusiness->getArray($chat_data['feed']['askerid']);
		$user_data = $this->muser->getUserArray($chat_data['feed']['askerid']);
		$array_link = $this->mbusiness->getLinkswithID($chat_data['feed']['askerid']);
		
		$chat_data['array_link'] = $array_link;
		$chat_data['looking'] = $business_data['looking'];
		$chat_data['position'] = $business_data['position'];
		$chat_data['education'] = $business_data['education'];
		$chat_data['venture_name'] = $business_data['venture_name'];
		$chat_data['summary'] = $business_data['summary'];
		$chat_data['industry'] = $business_data['industry'];
		$chat_data['stage'] = $business_data['stage'];
		$chat_data['employee_num'] = $business_data['employee_num'];
		$chat_data['funding'] = $business_data['funding'];
		$chat_data['location'] = $user_data[TBL_USER_LOCATION];
		$chat_data['public_url'] = $user_data[TBL_USER_PUBLIC];
		$chat_data['asker_type'] = $user_data[TBL_USER_TYPE];
		$chat_data['company'] = json_decode($user_data[TBL_USER_COMPANY]);

		if($feed){
			$asker = $this->muser->getUserArray($feed->{TBL_QUESTION_ASKER_ID});
			$mygroup = $this->mgroup->get($asker[TBL_USER_GROUP]);
			$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
			$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];
		}

		if(!$feed) $chat_data['state'] = "NoFeed"; 
		$this->load->view('question_feed', $chat_data);

	}

	public function AcceptQuestion(){
		$qid = $this->input->post('q_id');
		$aid = $this->input->post('accepter_id');
		$question = $this->mquestions->getQuestionwithID($qid);

		//===================== delete id from routed ids
		$routers = $question[TBL_QUESTION_ROUTE_IDS];
		$index = 0;
		foreach(json_decode($routers) as $router){
			$index++;
			if($router == $aid) break;
		}
		if($index > 0) unset(json_decode($routers)[$index - 1]);

		//===================== add id to accepted ids
		if(strpos($question[TBL_QUESTION_ACCEPT_IDS], $aid) === false) {
			$accepters = json_decode($question[TBL_QUESTION_ACCEPT_IDS]);
			$accepters[] = $aid;
			$waiters = json_decode($question[TBL_QUESTION_WAIT_IDS]);
			$waiters[] = $aid;
			$this->mquestions->updateQuestion($qid, array(TBL_QUESTION_ACCEPT_IDS => json_encode($accepters), TBL_QUESTION_WAIT_IDS => json_encode($waiters), TBL_QUESTION_ROUTE_IDS => json_encode($routers)));
		}

		if($question[TBL_QUESTION_POST] === "private"){
			$moderator = $this->muser->getModerator($this->cgroup);
			$to_emails = $moderator->{TBL_USER_EMAIL};
			$opt = $this->moption->get($moderator->{TBL_USER_ID});
			if($opt[0][TBL_OPTION_ACCEPT] == 1){
	            $this->updateAccept($this->cfname, $question[TBL_QUESTION_TITLE], $qid, $moderator->{TBL_USER_ID});
	        }
	        else if($opt[0][TBL_OPTION_ACCEPT] == 2){
				$this->email->acceptNotification($to_emails, $question[TBL_QUESTION_TITLE], $this->cfname, $qid);
	        } 
		}
		else{
			$admin_Users = $this->muser->getAdminUsers();
	        $to_emails = "";
	        foreach ($admin_Users as $admin) {
	        	$opt = $this->moption->get($admin[TBL_USER_ID]);
				if($opt[0][TBL_OPTION_ACCEPT] == 1){
	        	    $this->updateAccept($this->cfname, $question[TBL_QUESTION_TITLE], $qid, $admin[TBL_USER_ID]);
		        }
		        else if($opt[0][TBL_OPTION_ACCEPT] == 2){
	            	$to_emails.= $admin[TBL_USER_EMAIL].",";                
	            }
	        }
	        if($to_emails !== ""){
    			$this->email->acceptNotification($to_emails, $question[TBL_QUESTION_TITLE], $this->cfname, $qid);
    		}
		}

		//add to feed
		$asker = $this->muser->get($question[TBL_QUESTION_ASKER_ID]);
		$data_arr = array(
			TBL_FEED_WHO => $this->cfname." ".$this->clname,
			TBL_FEED_WHOM => $asker->{TBL_USER_FNAME}." ".$asker->{TBL_USER_LNAME},
			TBL_FEED_TYPE => 2,
			TBL_FEED_TAG => $question[TBL_QUESTION_TAGS],
			TBL_FEED_WHO_ID => $this->cid,
			TBL_FEED_WHO_BIO => $this->cbio,
			TBL_FEED_WHOM_ID => $question[TBL_QUESTION_ASKER_ID],
			TBL_FEED_WHOM_BIO => $asker->{TBL_USER_BIO}
		);
		$this->mfeed->add($data_arr);
		

		

	}

	public function deleteQuestion(){
		$q_id = $this->input->post('qid');
		$res = $this->mquestions->delete($q_id);
		echo $res;
	}

	public function updateAccept($name, $title, $qid, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_ACCEPT, $id);
        $pre_accept = array();
        if($temp){
            $pre_accept = json_decode($temp);
            $emt = new stdClass();
            $emt->name = $name;
            $emt->title = $title;
            $emt->qid = $qid;
            $pre_accept[] = $emt;
        }
        else{
            $emt = new stdClass();
            $emt->name = $name;
            $emt->title = $title;
            $emt->qid = $qid;
            $pre_accept[] = $emt;
        }
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_ACCEPT => json_encode($pre_accept)), $id);

    }

	public function deleteRouter(){
		$q_id = $this->input->post('q_id');

	   	$r_ids = $this->input->post('r_ids');

	   	$data_arr = array(TBL_QUESTION_ROUTE_IDS => $r_ids, TBL_QUESTION_ID=>$q_id);
		   	echo $this->mquestions->updateRoute($q_id, $data_arr);

	}

	public function deleteWaiter(){
		$q_id = $this->input->post('q_id');

	   	$u_id = $this->input->post('u_id');
	   	$que = $this->mquestions->getQuestionwithID($q_id);

	   	$new_waiters = array_diff(json_decode($que[TBL_QUESTION_WAIT_IDS]), array($u_id));
	   	$new_accepters = array_diff(json_decode($que[TBL_QUESTION_ACCEPT_IDS]), array($u_id));
	   	$data_arr = array(TBL_QUESTION_WAIT_IDS => json_encode($new_waiters), TBL_QUESTION_ACCEPT_IDS => json_encode($new_accepters), TBL_QUESTION_ID=>$q_id);
		echo $this->mquestions->updateRoute($q_id, $data_arr);

	}

	public function load_question_list(){
		$this->loginCheck();    	

    	$q_data = array();
    	
    	//print_r($chat_data);exit;
    	$chat_data = $this->getChatData();

    	$chat_data['state'] = 'Detail';

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'question-page';

		$chat_data['page_title'] = 'Questions | Relayy';

    	$chat_data['current_id'] = $this->cid;

    	$chat_data['type'] = $this->ctype;

    	$questions = array();

    	$questions = $this->mquestions->getAllQuestions();

    	$chat_data['array_question'] = $questions;

    	$Advisors = $this->mquestions->getAllAdvisors();

		if($Advisors) $chat_data['advisors'] = $Advisors;
		else $chat_data['advisors'] = array();



    	
		$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('question_detail_draft', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function Question($q_id){

	  	//print_r($chat_data);exit;
	  	$this->loginCheck();    

    	$chat_data = $this->getChatData();
    	$chat_data['body_class'] = 'question-page';
		
		$question = $this->mquestions->getQuestionwithID($q_id);

		if(!$question) $chat_data['exist'] = "no";
		else{
			$chat_data['question'] = $question;

	    	$chat_data['public'] = "anyone";

	    	$Advisors = $this->mquestions->getAllAdvisors();

			if($Advisors) $chat_data['advisors'] = $Advisors;
			else $chat_data['advisors'] = array();
		}
    	

    	$this->load->view('question_review', $chat_data);
		$this->load->view('templates/footer-chat', $chat_data);

	}

	public function Preview($q_id){

	  	//print_r($chat_data);exit;
    	$chat_data = $this->getChatData();
		
		$chat_data['body_class'] = 'question-page';

		$questions = $this->mquestions->getQuestionwithID($q_id);

		if(!$questions){
			$chat_data['invalid'] = 1;
		}
		else{
			$b_profile = $this->mbusiness->getArray($questions[TBL_QUESTION_ASKER_ID]);
			$user_profile = $this->muser->get($questions[TBL_QUESTION_ASKER_ID]);

	    	$chat_data['question'] = $questions;

	    	$chat_data['public'] = "non-user";	    	

			$chat_data['page_title'] = 'Questions Preview';

			$chat_data['industry'] = $b_profile[TBL_BUSINESS_INDUSTRY];

			$chat_data['location'] = $user_profile->{TBL_USER_LOCATION};

	    	$Advisors = $this->mquestions->getAllAdvisors();

			if($Advisors) $chat_data['advisors'] = $Advisors;
			else $chat_data['advisors'] = array();
		}

    	$this->load->view('question_preview', $chat_data);
		$this->load->view('templates/footer-chat', $chat_data);

	}

	public function feed($q_id){
		$this->loginCheck();    	

    	$q_data = array();
    	
    	//print_r($chat_data);exit;
    	$chat_data = $this->getChatData();

    	$chat_data['d_current'] = $chat_data['d_id'];

    	$chat_data['body_class'] = 'question-feed';

		$chat_data['page_title'] = 'Questions | Relayy';

    	$chat_data['current_id'] = $this->cid;

		$feed = $this->mquestions->getFeedwithID($q_id);

		if(!$feed) $chat_data['state'] = "NoFeed"; 
		else{
			$chat_data['state'] = "Feed"; 
			$Advisors = $this->mquestions->getAllAdvisors();

			if($Advisors) $chat_data['advisors'] = $Advisors;
			else $chat_data['advisors'] = array();	

			$chat_data['feed'] = json_decode(json_encode($feed), true);

			$business_data = $this->mbusiness->getArray($chat_data['feed']['askerid']);
			$user_data = $this->muser->getUserArray($chat_data['feed']['askerid']);
			$array_link = $this->mbusiness->getLinkswithID($chat_data['feed']['askerid']);
			
			$chat_data['array_link'] = $array_link;
			$chat_data['looking'] = $business_data['looking'];
			$chat_data['position'] = $business_data['position'];
			$chat_data['education'] = $business_data['education'];
			$chat_data['venture_name'] = $business_data['venture_name'];
			$chat_data['summary'] = $business_data['summary'];
			$chat_data['industry'] = $business_data['industry'];
			$chat_data['stage'] = $business_data['stage'];
			$chat_data['employee_num'] = $business_data['employee_num'];
			$chat_data['funding'] = $business_data['funding'];
			$chat_data['location'] = $user_data[TBL_USER_LOCATION];
			$chat_data['public_url'] = $user_data[TBL_USER_PUBLIC];
			$chat_data['asker_type'] = $user_data[TBL_USER_TYPE];
			$chat_data['company'] = json_decode($user_data[TBL_USER_COMPANY]);

			$asker = $this->muser->getUserArray($feed->{TBL_QUESTION_ASKER_ID});
			$mygroup = $this->mgroup->get($asker[TBL_USER_GROUP]);
			$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
			$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];
		}

		$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('question_feed', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function refer(){
		$fromName = $this->input->post('fname');
		$fromEmail = $this->input->post('femail');
		$Msg = $this->input->post('tmessage');
		$toName = $this->input->post('tname');
		$toEmail = $this->input->post('temail');
		$qid = $this->input->post('qid');

		$this->email->sendRefer($fromName, $fromEmail, $Msg, $toName, $toEmail, $qid);
		echo "success";

	}

	public function RouteQuestion($id){		

		$route_data = $this->getChatData();

    	$route_data['current_id'] = $this->cid;

    	$route_data['state'] = 'Route';

    	$route_data['body_class'] = 'question-page';

    	$route_data['question_id'] = $id;

		$routed_advisors = $this->mquestions->getRoutedAdvisors($id);

		$accpted_advisors = $this->mquestions->getAcceptedAdvisors($id);

		if($routed_advisors) $route_data['routed_users'] = json_decode($routed_advisors);
		else $route_data['routed_users'] = array();

		if($accpted_advisors) $route_data['accepted_users'] = json_decode($accpted_advisors);
		else $route_data['accepted_users'] = array();

		$Advisors = $this->mquestions->getAllAdvisors();
		$newAdvisors = array();
		foreach ($Advisors as $Advisor) {
			$b_profile = $this->mbusiness->getArray($Advisor[TBL_USER_ID]);
			if(!$b_profile) $Advisor['business_skill'] = "";
			else $Advisor['business_skill'] = $b_profile[TBL_BUSINESS_SKILL];
			$newAdvisors[] = $Advisor;
		}

		if($Advisors) $route_data['advisors'] = $newAdvisors;
		else $route_data['advisors'] = array();

		$this->load->view('templates/header-chat', $route_data);
		
		$this->load->view('templates/left-sidebar', $route_data);

		$this->load->view('question_route', $route_data);

		$this->load->view('templates/right-sidebar', $route_data);
                                             
		$this->load->view('templates/footer-chat', $route_data);
	}

	public function SubmitRoute(){
		$q_id = $this->input->post('q_id');
		$updated_router_ids = $this->input->post('r_ids');
		echo $q_id;
		$question = $this->mquestions->getQuestionwithID($q_id);
		foreach($updated_router_ids as $router){
			if(strpos($question[TBL_QUESTION_ROUTE_IDS], $router) !== false) continue;
			$user = $this->muser->get($router);
			$opt = $this->moption->get($user->{TBL_USER_ID});
			if($opt[0][TBL_OPTION_ACCEPT] == 1){
	            $this->updateRoute($question[TBL_QUESTION_TITLE], $q_id, $user->{TBL_USER_ID});
	        }
	        else if($opt[0][TBL_OPTION_ACCEPT] == 2){
				$this->email->RoutedUserNotification($user->{TBL_USER_EMAIL}, $user->{TBL_USER_FNAME}." ".$user->{TBL_USER_LNAME}, $q_id, $question[TBL_QUESTION_TITLE]);
	        } 
		}

		$data_arr = array(TBL_QUESTION_ROUTE_IDS => json_encode($updated_router_ids), TBL_QUESTION_ID=>$q_id);
		$res = $this->mquestions->updateRoute($q_id, $data_arr);
	}

	public function updateRoute($title, $qid, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_ROUTE, $id);
        $pre_route = array();
        if($temp){
            $pre_route = json_decode($temp);
            $emt = new stdClass();
            $emt->title = $title;
            $emt->qid = $qid;
            $pre_route[] = $emt;
        }
        else{
            $emt = new stdClass();
            $emt->title = $title;
            $emt->qid = $qid;
            $pre_route[] = $emt;
        }
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_ROUTE => json_encode($pre_route)), $id);

    }





	
}