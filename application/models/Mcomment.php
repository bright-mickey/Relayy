<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mcomment extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function save($data_arr){
        
   		$query = $this->db->select('*')
                          ->where(TBL_COMMENT_MID, $data_arr[TBL_COMMENT_MID])
                          ->where(TBL_COMMENT_WHO, $data_arr[TBL_COMMENT_WHO])
                          ->limit(1)
                          ->get(TBL_NAME_COMMENT);

        if ($query->num_rows() === 1)
        {
            $item = $query->result_array();
            $this->db->where(TBL_COMMENT_ID, $item[0][TBL_COMMENT_ID]);
            $this->db->delete(TBL_NAME_COMMENT);
            return "unsave"; 
        }
      $this->db->set(TBL_USER_SELF_COMMENTS, 'self_comments + 1', FALSE);
            $this->db->where(TBL_USER_UID, $data_arr[TBL_COMMENT_WHO]);
            $this->db->update(TBL_NAME_USER);

      $this->db->set(TBL_USER_OTHER_COMMENTS, 'other_comments + 1', FALSE); 
            $this->db->where(TBL_USER_UID, $data_arr[TBL_COMMENT_WHOM]);
            $this->db->update(TBL_NAME_USER); 

    	$this->db->insert(TBL_NAME_COMMENT, $data_arr);

        $id = $this->db->insert_id();

        return $id ? "save" : FALSE;

    }

    public function getComments($flag, $data_arr){

        if($flag == 1){//saved by self
          $query = $this->db->select('*, tbl_comment.id as cid, tbl_user.uid as uid, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname')
                          ->from("tbl_comment")
                          ->join("tbl_user", "tbl_user.uid = tbl_comment.whom_uid")
                          ->where(TBL_COMMENT_WHO, $data_arr[TBL_COMMENT_WHO])
                          ->get();
        }
        else if($flag == 2){//saved by other
          $query = $this->db->select('*, tbl_comment.id as cid, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.uid as uid')
                          ->from("tbl_comment")
                          ->join("tbl_user", "tbl_user.uid = tbl_comment.who_uid")
                          ->where(TBL_COMMENT_WHOM, $data_arr[TBL_COMMENT_WHOM])
                          ->get();
        }
        return $query->result_array();

    }

    public function delete($c_id){
        $this->db->where(TBL_COMMENT_ID, $c_id);
        $this->db->delete(TBL_NAME_COMMENT); 

        return "success";
    }

    public function unSave($data_arr){
      $this->db->where(TBL_COMMENT_WHOM, $data_arr[TBL_COMMENT_WHOM]);
      $this->db->where(TBL_COMMENT_WHO, $data_arr[TBL_COMMENT_WHO]);
      $this->db->where(TBL_COMMENT_MID, $data_arr[TBL_COMMENT_MID]);
      $this->db->delete(TBL_NAME_COMMENT);
      return "success"; 
    }

    function getSavedMsgIDs($myuid){
      $query = $this->db->select('message_id')
                          ->where(TBL_COMMENT_WHO, $myuid)
                          ->get(TBL_NAME_COMMENT);
      if ($query->num_rows() > 0)
      {
        return $query->result_array();
      }
      return FALSE;
    }

   



}
