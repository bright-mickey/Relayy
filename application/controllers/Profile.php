<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");
include_once (dirname(__FILE__) . "/UploadHandler.php");

class Profile extends ChatController
{
	public function __construct()            
	{
		parent::__construct();
		$this->load->model('mreview');
		$this->load->model('mbusiness');
		$this->load->model('mgroup');
		$this->load->model('mcode');
	}

	public function index()
	{
    	$this->loginCheck();    	

    	$chat_data = $this->getChatData();
    	
    	//print_r($chat_data);exit;

    	//get common profile data

		$chat_data['body_class'] = 'profile-page';

		$chat_data['page_title'] = 'Profile | Relayy';

		$chat_data['current_id'] = $this->cid;

		$chat_data['current_type'] = $this->ctype;

		$user_data = $this->muser->getUserArray($this->cid);

		if($user_data[TBL_USER_CODE] === "" && $user_data[TBL_USER_TYPE] == 4) $chat_data['op_leave'] = 0;
		else if($user_data[TBL_USER_TYPE] == 1) $chat_data['op_leave'] = 0;
		else $chat_data['op_leave'] = 1;

		$chat_data['entered_chats'] = $user_data[TBL_USER_ENTERED_CHATS];

		$chat_data['self_comments'] = $user_data[TBL_USER_SELF_COMMENTS];

		$chat_data['other_comments'] = $user_data[TBL_USER_OTHER_COMMENTS];

		$chat_data['reviews'] = $user_data[TBL_USER_REVIEWS];

		$chat_data['location'] = $user_data[TBL_USER_LOCATION];

		$chat_data['public_url'] = $user_data[TBL_USER_PUBLIC];

		$chat_data['status'] = $user_data[TBL_USER_STATUS];

		$chat_data['category'] = $user_data[TBL_USER_CATEGORY];

		$chat_data['c_name'] = $user_data[TBL_USER_CNAME];

		$chat_data['c_location'] = $user_data[TBL_USER_CLOCATION];

		$chat_data['c_summary'] = $user_data[TBL_USER_CSUMMARY];

		if($user_data['company'] === "") $chat_data['has_media'] = 0;
		else $chat_data['has_media'] = 1;

		$company = $user_data[TBL_USER_COMPANY];
	
		$chat_data['company'] = json_decode($company);

		$array_review = $this->mreview->getReviews($this->cid);

		$chat_data['array_review'] = $array_review;

		//get business profile data

		$business_data = $this->mbusiness->getArray($this->cid);

		$chat_data['skill'] = $business_data['skill'];
		$chat_data['looking'] = $business_data['looking'];
		$chat_data['interesting'] = $business_data['interesting'];
		$chat_data['position'] = $business_data['position'];
		$chat_data['education'] = $business_data['education'];
		$chat_data['venture_name'] = $business_data['venture_name'];
		$chat_data['summary'] = $business_data['summary'];
		$chat_data['industry'] = $business_data['industry'];
		$chat_data['stage'] = $business_data['stage'];
		$chat_data['employee_num'] = $business_data['employee_num'];
		$chat_data['funding'] = $business_data['funding'];

		$array_link = $this->mbusiness->getLinkswithID($this->cid);
		$chat_data['array_link'] = $array_link;

		$mygroup = $this->mgroup->get($this->cgroup);
		$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
		$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];
    
    	$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('profile', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function dashboard(){

		$this->loginCheck();    	

    	$chat_data = $this->getChatData();
    	
    	//print_r($chat_data);exit;

    	$chat_data['body_class'] = 'profile-page';

		$chat_data['page_title'] = 'Profile | Relayy';

		$chat_data['current_id'] = $this->cid;

		$chat_data['current_type'] = $this->ctype;

		$user_data = $this->muser->getUserArray($this->cid);

		$chat_data['entered_chats'] = $user_data[TBL_USER_ENTERED_CHATS];

		$chat_data['self_comments'] = $user_data[TBL_USER_SELF_COMMENTS];

		$chat_data['other_comments'] = $user_data[TBL_USER_OTHER_COMMENTS];

		$chat_data['reviews'] = $user_data[TBL_USER_REVIEWS];

		$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('dashboard', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);


	}

	public function user($c_id)
	{
    	$this->loginCheck();    	

    	$chat_data = $this->getChatData();
    	
    	//print_r($chat_data);exit;

    	//get common profile data

		$chat_data['body_class'] = 'profile-page';

		$chat_data['page_title'] = 'Profile | Relayy';

		$chat_data['current_id'] = $c_id;

		$user_data = $this->muser->getUserArray($c_id);

		if($user_data[TBL_USER_CODE] === "" && $user_data[TBL_USER_TYPE] == 4) $chat_data['op_leave'] = 0;
		else $chat_data['op_leave'] = 1;

		$chat_data['current_type'] = $user_data[TBL_USER_TYPE];

		$chat_data['entered_chats'] = $user_data[TBL_USER_ENTERED_CHATS];

		$chat_data['self_comments'] = $user_data[TBL_USER_SELF_COMMENTS];

		$chat_data['other_comments'] = $user_data[TBL_USER_OTHER_COMMENTS];

		$chat_data['name'] = $user_data[TBL_USER_FNAME]." ".$user_data[TBL_USER_LNAME];

		$chat_data['bio'] = $user_data[TBL_USER_BIO];

		$chat_data['group'] = $user_data[TBL_USER_GROUP];

		$chat_data['photo'] = $user_data[TBL_USER_PHOTO];

		$chat_data['reviews'] = $user_data[TBL_USER_REVIEWS];

		$chat_data['location'] = $user_data[TBL_USER_LOCATION];

		$chat_data['public_url'] = $user_data[TBL_USER_PUBLIC];

		$chat_data['status'] = $user_data[TBL_USER_STATUS];

		$chat_data['type'] = $user_data[TBL_USER_TYPE];

		$chat_data['category'] = $user_data[TBL_USER_CATEGORY];

		$chat_data['email'] = $user_data[TBL_USER_EMAIL];

		$chat_data['c_name'] = $user_data[TBL_USER_CNAME];

		$chat_data['c_location'] = $user_data[TBL_USER_CLOCATION];

		$chat_data['c_summary'] = $user_data[TBL_USER_CSUMMARY];

		if($user_data['company'] === "") $chat_data['has_media'] = 0;
		else $chat_data['has_media'] = 1;

		$company = $user_data[TBL_USER_COMPANY];
	
		$chat_data['company'] = json_decode($company);

		$array_review = $this->mreview->getReviews($c_id);

		$chat_data['array_review'] = $array_review;

		//get business profile data

		$business_data = $this->mbusiness->getArray($c_id);

		$chat_data['skill'] = $business_data['skill'];
		$chat_data['looking'] = $business_data['looking'];
		$chat_data['interesting'] = $business_data['interesting'];
		$chat_data['position'] = $business_data['position'];
		$chat_data['education'] = $business_data['education'];
		$chat_data['venture_name'] = $business_data['venture_name'];
		$chat_data['summary'] = $business_data['summary'];
		$chat_data['industry'] = $business_data['industry'];
		$chat_data['stage'] = $business_data['stage'];
		$chat_data['employee_num'] = $business_data['employee_num'];
		$chat_data['funding'] = $business_data['funding'];

		$array_link = $this->mbusiness->getLinkswithID($c_id);
		$chat_data['array_link'] = $array_link;

		$mygroup = $this->mgroup->get($user_data[TBL_USER_GROUP]);
		$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
		$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];

    
    	$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('userprofile', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);
                                             
		$this->load->view('templates/footer-chat', $chat_data);
	}

	public function SavePosition(){
		$position = $this->input->post('position');
		$this->mbusiness->updatePosition($position, $this->cid);
		echo "success";
	}

	public function SaveEducation(){
		$education = $this->input->post('education');
		$this->mbusiness->updateEducation($education, $this->cid);
		echo "success";
	}

	public function updateVentureInfo(){
		$category = $this->input->post('category');
		$data = $this->input->post('data');

		$data_arr = array($category=>$data, TBL_BUSINESS_ID=>$this->cid);
		echo $this->mbusiness->updateInfo($data_arr);

	}

	public function addGroup(){
		$name = $this->input->post('name');
		$image = $this->input->post('image');
		$id = $this->input->post('id');
		$code = $this->mcode->getWithID($id);
		$res = $this->mgroup->add($name, $image, $code[TBL_INVITE_CODE]);
		gf_registerCurrentUser($this->muser->getEmail($this->cemail));
		echo $res;
	}

	public function deleteGroupForModerator(){
		$type = $this->input->post('type');
		return $this->mgroup->delete($this->cid, $this->cgroup, $type);
	}

	public function LeaveGroup(){
		$id = $this->input->post('id');
		$this->mgroup->leaveGroup($id);
		gf_registerCurrentUser($this->muser->getEmail($this->cemail));
		return "success";
	}

	public function LeaveGroupForModerator(){
		$id = $this->input->post('id');
		$type = $this->input->post('type');
		$this->mgroup->leaveGroupForModerator($id, $type);
		gf_registerCurrentUser($this->muser->getEmail($this->cemail));
		return "success";
	}

	public function SaveCategory(){
		$category = $this->input->post('category');
		$this->muser->updateUser($this->cid, array(TBL_USER_CATEGORY => $category));
		echo "success";
	}

	public function linkUpload(){//unused
		
		$file_size =$_FILES['image']['size'];
		$attachment_file=$_FILES["image"];
	    $output_dir = "uploads/";
	    $fileName = $_FILES["image"]["name"];
		$spl=explode('.',$fileName);
		$file_ext = $spl[count($spl) - 1];
		$expensions= array("jpeg","jpg","png");
      
	      if(in_array($file_ext,$expensions)=== false){
	      	echo "extension not allowed, please choose a JPEG or PNG file.";
	      	exit;
	      }
	      
	      if($file_size > 2097152){
	      	echo 'File size must be excately 2 MB';
	      	exit;
	      }

	    $now = new DateTime();
        $now->format('Y-m-d H:i:s');    // MySQL datetime format
        $currentTime = $now->getTimestamp();

		move_uploaded_file($_FILES["image"]["tmp_name"],$output_dir.$currentTime.'_'.$fileName);

		if($fileName === "") echo "No file";
		else {
			$fname = 'uploads/'.$currentTime.'_'.$fileName;
			echo $fname;
		}
	}

	public function addLink(){
		$fname = $this->input->post('fname');
		$title = $this->input->post('title');
		$text = $this->input->post('text');

		$data_arr = array(TBL_LINK_ID=>$this->cid, TBL_LINK_TITLE=>$title, TBL_LINK_IMAGE=>$fname, TBL_LINK_LINK=>$text);
		$this->mbusiness->addLink($data_arr);
		echo $fname;
	}

	public function deleteLink(){
		$link = $this->input->post('link');
		echo $this->mbusiness->removeLink($this->cid, $link);
	}	

	public function SaveSkill(){
		$skill = $this->input->post('skill');
		$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_SKILL=>$skill, TBL_BUSINESS_ID=>$this->cid, TBL_BUSINESS_UID=>$this->cuid));
	}

	public function SaveLooking(){
		$looking = $this->input->post('looking');
		$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_LOOKING=>$looking, TBL_BUSINESS_ID=>$this->cid, TBL_BUSINESS_UID=>$this->cuid));
	}

	public function SaveInteresting(){
		$interesting = $this->input->post('interesting');
		$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_INTERESTING=>$interesting, TBL_BUSINESS_ID=>$this->cid, TBL_BUSINESS_UID=>$this->cuid));
	}



	public function savedComments(){// get saved comments

		$this->loginCheck();    	

    	$c_data = $this->getChatData();

		$flag = $this->input->post('flag');

		if($flag == 1) $data_arr = array(TBL_COMMENT_WHO=>$this->cuid);
		else $data_arr = array(TBL_COMMENT_WHOM=>$this->cuid);

		$res_array = $this->mcomment->getComments($flag, $data_arr);
		$uid_array = array();
		$user_array = array();
		if(sizeof($res_array) > 0){
			if($flag == 1){
				foreach($res_array as $res){
					if(in_array($res[TBL_COMMENT_WHOM], $uid_array)) continue;
					$user_array[] = $res;
					$uid_array[] = $res[TBL_COMMENT_WHOM];
				}
			}
			else{
				foreach($res_array as $res){
					if(in_array($res[TBL_COMMENT_WHO], $uid_array)) continue;
					$user_array[] = $res;
					$uid_array[] = $res[TBL_COMMENT_WHO];
				}
			}		
		}
		

    	$c_data['flag'] = $flag;

    	$c_data['comments'] = $res_array;

    	$c_data['users'] = $user_array;

		$this->load->view('showcomments', $c_data);

	}

	public function SaveCompanyInfo(){
		$category = $this->input->post('category');
		$value = $this->input->post('value');
		$this->muser->updateUser($this->cid, array($category => $value));
		echo "success";
	}

	public function ActionUpdate(){
		$now = new DateTime();
		$now->format('Y-m-d H:i:s');    // MySQL datetime format
		$currentTime = $now->getTimestamp();
		$cUser = $this->muser->getEmail($this->cemail);
		$this->muser->edit($cUser->id, array(TBL_USER_TIME=> $currentTime));
		
	} 

	public function useredit($c_id)
	{
    	$this->loginCheck();    	

    	$c_data = $this->getChatData();

    	$c_data['body_class'] = 'profile-page';

		$c_data['page_title'] = 'Edit User Profile | Relayy';

		$user_data = $this->muser->getUserArray($c_id);	

		//print_r($user_data);exit;
    
    	$this->load->view('templates/header-chat', $c_data);
		
		$this->load->view('templates/left-sidebar', $c_data);

		$this->load->view('ueditprofile', $user_data);

		$this->load->view('templates/right-sidebar', $c_data);

		$this->load->view('templates/footer-chat', $c_data);
	}

	public function leaveReview($to_id){
		$this->loginCheck();    	

    	$c_data = $this->getChatData();

    	$c_data['to_id'] = $to_id;

    	$c_data['current_id'] = $this->cid;

    	$c_data['body_class'] = 'profile-page';

    	$c_data['page_title'] = 'Review | Relayy';

    	$user_data = $this->muser->getUserArray($to_id);

    	$c_data['to_photo'] = $user_data[TBL_USER_PHOTO];

    	$c_data['to_name'] = $user_data[TBL_USER_FNAME]." ".$user_data[TBL_USER_LNAME];

		$this->load->view('templates/header-chat', $c_data);
		
		$this->load->view('templates/left-sidebar', $c_data);

		$this->load->view('review', $c_data);

		$this->load->view('templates/right-sidebar', $c_data);

		$this->load->view('templates/footer-chat', $c_data);	

		echo "success";
	}

	public function editReview(){
		$id = $this->input->post('id');

		$text = $this->input->post('txt');

		$this->mreview->update($id, $text);

		echo "success";
	}

	public function deleteComment(){
		$c_id = $this->input->post('id');

		$this->mcomment->delete($c_id);

		echo "success";
	}

	public function edit()
	{
		$this->loginCheck();    

		$chat_data = $this->getChatData();	

    	$chat_data['body_class'] = 'profile-page';

		$chat_data['page_title'] = 'Edit Profile | Relayy';

		$chat_data['profile_js'] = TRUE;		
    
    	$this->load->view('templates/header-chat', $chat_data);
		
		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('profile_edit', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);	
	}

	public function personalProfile(){

	}

	public function companyProfile(){
		$c_id = $this->input->post('c_id');

		$user_data = $this->muser->getUserArray($c_id);
        
        if ($c_id == $this->cid || $this->ctype == USER_TYPE_ADMIN) $user_data['editable'] = true;
        else $user_data['editable'] = false;
		// print_r($user);exit;

		$array_review = $this->mreview->getReviews($c_id);

		$user_data['current_id'] = $this->cid;

		$user_data['my_photo'] = $this->cphoto;

		$user_data['u_id'] = $user_data['id'];

		$user_data['u_fname'] = $user_data['fname'];

		$user_data['u_lname'] = $user_data['lname'];

		$user_data['u_bio'] = $user_data['bio'];

		$user_data['u_email'] = $user_data['email'];

		$user_data['u_photo'] = $user_data['photo'];

		$user_data['u_type'] = $user_data['type'];

		$user_data['array_review'] = $array_review;

		$this->load->view('business_profile', $user_data);
	}

	public function addReview(){
		$from_id = $this->input->post('from_id');
    	$to_id = $this->input->post('to_id');
        $review = $this->input->post('review');

        $data_arr = array(TBL_REVIEW_FROM => $from_id, TBL_REVIEW_TO => $to_id, TBL_REVIEW_TEXT => $review);
        $this->mreview->addReview($data_arr);

      	//send review notification
    	$user = $this->muser->get($from_id);
    	$to = $this->muser->get($to_id);

    	$opt = $this->moption->get($to->{TBL_USER_ID}); 
        if($opt[0][TBL_OPTION_REVIEW] == 1){
            $this->updateReview($user->{TBL_USER_FNAME}." ".$user->{TBL_USER_LNAME}, $to->{TBL_USER_ID});
        }
        else if($opt[0][TBL_OPTION_REVIEW] == 2){
        	$this->email->sendReviewNotification($user->{TBL_USER_FNAME}." ".$user->{TBL_USER_LNAME}, $to->{TBL_USER_EMAIL}, $review, site_url('profile/leaveReview')."/".$from_id);
        }

        //add to feed
        $data_arr = array(
            TBL_FEED_WHO => $to->{TBL_USER_FNAME}." ".$to->{TBL_USER_LNAME},
            TBL_FEED_WHOM => $user->{TBL_USER_FNAME}." ".$user->{TBL_USER_LNAME},
            TBL_FEED_TYPE => 4,
            TBL_FEED_WHO_ID => $to->{TBL_USER_ID},
            TBL_FEED_WHO_BIO => $to->{TBL_USER_BIO},
            TBL_FEED_WHOM_ID => $user->{TBL_USER_ID},
            TBL_FEED_WHOM_BIO => $user->{TBL_USER_BIO}
        );
        $this->mfeed->add($data_arr);
        echo "success";
	}

	public function updateReview($name, $id){
        $temp = $this->moption->getSummaryField(TBL_SUM_REVIEW, $id);
        $pre_review = array();
        if($temp){
            $pre_review = json_decode($temp);
        }
        $pre_review[] = $name;
        $this->moption->updateSummary(array(TBL_SUM_UID => $id, TBL_SUM_REVIEW => json_encode($pre_review)), $id);

    }

	public function deleteReview(){
		$id = $this->input->post('r_id');
		$this->mreview->delete($id);
		echo "success";
	}

	public function save()
	{
    	$this->loginCheck();

    	$fname = $this->input->post('fname');
    	$lname = $this->input->post('lname');
        $password = $this->input->post('password');
        $bio = $this->input->post('bio');
        $picture = $this->input->post('picture');

        if ($this->cstatus == USER_STATUS_INVITE) $this->muser->approve($this->cid);
        
        $object = $this->muser->edit($this->cid, array(
        		TBL_USER_FNAME => $fname,
        		TBL_USER_LNAME => $lname,
        		TBL_USER_PWD   => $password,
        		TBL_USER_BIO   => $bio,
        		TBL_USER_PHOTO => $picture
        	));

        // print_r($object);exit;
        gf_registerCurrentUser($object);

        //$this->email->profile($this->cemail, $fname." ".$lname);

		redirect(site_url('profile'), 'get');
	}

	public function saveuser()
	{
		$this->loginCheck();

		$userid = $this->input->post('uid');
		$userObj = $this->muser->get($userid);

    	$fname = $this->input->post('fname');
    	$lname = $this->input->post('lname');
        $password = $this->input->post('password');
        $bio = $this->input->post('bio');
        $role = $this->input->post('reg_role');
        
        $object = $this->muser->edit($userid, array(
        		TBL_USER_FNAME => $fname,
        		TBL_USER_LNAME => $lname,
        		TBL_USER_PWD   => $password,
                TBL_USER_TYPE  => $role,
                TBL_USER_BIO   => $bio
        	));

        //$this->email->profile($object->email, $fname." ".$lname);

		redirect(site_url('users'), 'get');	
	}

	public function upload()
	{
		$upload_handler = new UploadHandler();
	}
}