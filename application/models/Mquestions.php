<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mquestions extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }



    public function add($data_arr){
    	$query = $this->db->select(TBL_QUESTION_ID)
                          ->where(TBL_QUESTION_CONTEXT, $data_arr[TBL_QUESTION_CONTEXT])
                          ->limit(1)
                          ->get(TBL_NAME_QUESTION);
      if($data_arr[TBL_QUESTION_CONTEXT] === ""){
        $this->db->insert(TBL_NAME_QUESTION, $data_arr);

        $id = $this->db->insert_id();

        return $id ? $id : FALSE;
      }                          
        
      if ($query->num_rows() === 1) {
          $user = $query->row();
          return $user->{TBL_USER_ID};
      }

      $this->db->insert(TBL_NAME_QUESTION, $data_arr);

      $id = $this->db->insert_id();

      return $id ? $id : FALSE;

    }

    public function getQuestionwithID($id){
      $query = $this->db->select('*')
                          ->where(TBL_QUESTION_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_QUESTION);

      if ($query->num_rows() === 1) {
          return $query->result_array()[0];
      }   
      return FALSE;

    }

    public function submit($data_arr){
      $query = $this->db->select(TBL_QUESTION_ID)
                          ->where(TBL_QUESTION_TITLE, $data_arr[TBL_QUESTION_TITLE])
                          ->limit(1)
                          ->get(TBL_NAME_QUESTION);

      if ($query->num_rows() === 1) {
            $user = $query->row();
            $this->db->update(TBL_NAME_QUESTION, $data_arr, array(TBL_QUESTION_ID => $user->{TBL_USER_ID}));
            
            return $user->{TBL_USER_ID};
        }       

        return FALSE;
    }

    public function getOwnQuestion($id){
        $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.type as type, tbl_que_detail.r_ids as r_ids, tbl_que_detail.a_ids as a_ids, tbl_que_detail.id as q_id, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->where_in(TBL_QUESTION_ASKER_ID, $id)
                        ->get();

        return $query->result_array();
    }

    public function getAllQuestions(){
      $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.type as type, tbl_que_detail.post as post, tbl_que_detail.r_ids as r_ids, tbl_que_detail.a_ids as a_ids, tbl_que_detail.id as q_id, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio, tbl_user.status as status') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->where(TBL_QUESTION_POST, QUESTION_POST_PUBLIC)
                        ->where('tbl_user.status', 1)
                        ->get();

        return $query->result_array();
    }

    public function getGroupQuestions($group){
      $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.post as post, tbl_que_detail.type as type, tbl_que_detail.r_ids as r_ids, tbl_que_detail.a_ids as a_ids, tbl_que_detail.id as q_id, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.group as group, tbl_user.bio as bio') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->where(TBL_USER_GROUP, $group)
                        ->where(TBL_QUESTION_POST, QUESTION_POST_PRIVATE)
                        ->get();

        return $query->result_array();
    }

    public function getWaitingAdvisors($q_id){
      $query = $this->db->select(TBL_QUESTION_WAIT_IDS)
                        ->where(TBL_QUESTION_ID, $q_id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      if ($query->num_rows() === 1) {
            $user = $query->row();            
            return $user->{TBL_QUESTION_WAIT_IDS};
        }   
        return FALSE;      
    }

    public function getRoutedAdvisors($id){
      $query = $this->db->select(TBL_QUESTION_ROUTE_IDS)
                        ->where(TBL_QUESTION_ID, $id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      if ($query->num_rows() === 1) {
            $user = $query->row();            
            return $user->{TBL_QUESTION_ROUTE_IDS};
        }   
        return FALSE;           

    }

    public function getAcceptedAdvisors($id){
      $query = $this->db->select(TBL_QUESTION_ACCEPT_IDS)
                        ->where(TBL_QUESTION_ID, $id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      if ($query->num_rows() === 1) {
            $user = $query->row();            
            return $user->{TBL_QUESTION_ACCEPT_IDS};
        }   
        return FALSE;           

    }

    public function delete($q_id){
        $this->db->where(TBL_QUESTION_ID, $q_id);
        $this->db->delete(TBL_NAME_QUESTION); 

        return "success";
    }


    public function getAllAdvisors(){
      $query = $this->db->select('*')
                        ->where(TBL_USER_TYPE, USER_TYPE_ADVISOR)
                        ->get(TBL_NAME_USER);

      // $query = $this->db->select('tbl_user.id as id, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio, tbl_business_profile.skill as business_skill') // (or whichever fields you're interested in)
      //                   ->from("tbl_user")
      //                   ->join("tbl_business_profile", "tbl_business_profile.id = tbl_user.id")
      //                   ->where(TBL_USER_TYPE, USER_TYPE_ADVISOR)
      //                   ->get();                        
      if ($query->num_rows() > 0) {
            return $query->result_array();
        }   
        return FALSE;  
    }

    public function updateRoute($q_id, $data_arr){
      $query = $this->db->select('*')
                        ->where(TBL_QUESTION_ID, $q_id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      if ($query->num_rows() > 0) {
            $this->db->update(TBL_NAME_QUESTION, $data_arr, array(TBL_QUESTION_ID => $q_id));                        
            $this->UpdateState($q_id);
            return "false";
      }       
      else return "true";
    }

    public function updateAccept($q_id, $data_arr){
      $query = $this->db->select('a_ids')
                        ->where(TBL_QUESTION_ID, $q_id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      if ($query->num_rows() > 0) {
            $this->db->update(TBL_NAME_QUESTION, $data_arr, array(TBL_QUESTION_ID => $q_id));                        
            $this->UpdateState($q_id);
            return FALSE;
      }     
    }

    public function UpdateState($q_id){
      $query = $this->db->select('*')
                        ->where(TBL_QUESTION_ID, $q_id)
                        ->limit(1)
                        ->get(TBL_NAME_QUESTION);
      $value = $query->row();
      if($value->r_ids === '[]' && $value->a_ids === '[]') $this->db->update(TBL_NAME_QUESTION, array(TBL_QUESTION_STATUS => QUESTION_STATUS_SUBMITTED) , array(TBL_QUESTION_ID => $q_id));
      else if($value->r_ids !== '[]' && $value->a_ids === '[]') $this->db->update(TBL_NAME_QUESTION, array(TBL_QUESTION_STATUS => QUESTION_STATUS_ROUTED) , array(TBL_QUESTION_ID => $q_id));
      else if($value->j_ids === '[]' && $value->a_ids !== '[]') $this->db->update(TBL_NAME_QUESTION, array(TBL_QUESTION_STATUS => QUESTION_STATUS_ACCEPTED) , array(TBL_QUESTION_ID => $q_id));
      else if($value->j_ids !== '[]') $this->db->update(TBL_NAME_QUESTION, array(TBL_QUESTION_STATUS => QUESTION_STATUS_LAUNCHED) , array(TBL_QUESTION_ID => $q_id));

    }

    public function getFirstFeed($c_id){//priority: private>public

      $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.r_ids as r_ids, tbl_que_detail.id as q_id, tbl_que_detail.post as post, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio, tbl_user.public_url as public_url') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->where('tbl_que_detail.post',  "private")
                        ->like('r_ids',$c_id)
                        ->get();

      if ($query->num_rows() > 0) {
        $res = $query->row();
        return $res;
      }
      else{
        $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.r_ids as r_ids, tbl_que_detail.id as q_id, tbl_user.group as group, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->where('tbl_que_detail.post',  "public")
                        ->like('r_ids',$c_id)
                        ->get();
      }                 
      
      $res = $query->row();
      return $res;
    }

    public function accept($qid, $aid){

    }

    public function getFeedwithID($q_id){

      $query = $this->db->select('*, tbl_que_detail.status as state, tbl_que_detail.r_ids as r_ids, tbl_que_detail.id as q_id, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.bio as bio, tbl_user.public_url as public_url') // (or whichever fields you're interested in)
                        ->from("tbl_que_detail")
                        ->join("tbl_user", "tbl_user.id = tbl_que_detail.askerid")
                        ->like('tbl_que_detail.id',$q_id)
                        ->get();
      
      $res = $query->row();
      return $res;
    }

    

    public function updateQuestion($qid, $data_arr){
      $this->db->update(TBL_NAME_QUESTION, $data_arr, array(TBL_QUESTION_ID => $qid));
      $this->UpdateState($qid);
    }

}
