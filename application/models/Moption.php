<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Moption extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function add($uid, $key, $value)
    {
        
    }

    public function get($uid)
    {
        $query = $this->db->select('*')
                          ->where(TBL_OPTION_UID , $uid)
                          ->limit(1)
                          ->get(TBL_NAME_OPTION);

        if ($query->num_rows() === 1)
        {
            return $query->result_array();           

        }

        return FALSE;
    }

    
    public function getStatewithUID($uid){

        $query = $this->db->select('*, tbl_user.email as email, tbl_user.fname as fname, tbl_user.lname as lname') // (or whichever fields you're interested in)
                        ->from("tbl_option")
                        ->join("tbl_user", "tbl_option.uid = tbl_user.id")
                        ->where('tbl_user.uid', $uid)
                        ->limit(1)
                        ->get();

        return $query->row();

    }

    public function getOtherStatewithID($uid){
        $query = $this->db->select(TBL_OPTION_OTHER)
                          ->where(TBL_OPTION_UID , $uid)
                          ->limit(1)
                          ->get(TBL_NAME_OPTION);

        if ($query->num_rows() === 1)
        {
            return $query->row()->{TBL_OPTION_OTHER};           

        }
    }


    
    public function update($data_arr, $id)
    {
        $this->db->update(TBL_NAME_OPTION, $data_arr, array(TBL_OPTION_UID => $id));
    }

    public function getInterval($id){
        $query = $this->db->select('interval')
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_OPTION);
        if ($query->num_rows() === 1)
        {
            return $query->row();         
        }   
        return FALSE;                  
    }

    public function getSummary($id){
        $query = $this->db->select('*')
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_SUMMARY);
        if ($query->num_rows() === 1)
        {
            return $query->row();         
        }                   
        return FALSE;    
    }

    public function deleteSummary($id){
        $query = $this->db->select('*')
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_SUMMARY);

        if ($query->num_rows() === 1)
        {
            $this->db->where(TBL_SUM_UID, $id);
            $this->db->delete(TBL_NAME_SUMMARY); 
            return TRUE;         
        }                   
        return FALSE;                   
    }

    public function AddSubNum($id, $num){
        $query = $this->db->select('*')
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_SUMMARY);
        if ($query->num_rows() === 1)
        {
            $this->db->update(TBL_NAME_SUMMARY, array(TBL_SUM_SUBMIT => $num), array(TBL_SUM_UID => $id));
        }                  
        else{
            $this->db->insert(TBL_NAME_SUMMARY, array(TBL_SUM_UID => $id, TBL_SUM_SUBMIT => $num));

            $nid = $this->db->insert_id();
        }
    }

    public function deleteleader($code){
        $this->db->where(TBL_LEADER_CODE, $code);
        $this->db->delete(TBL_NAME_LEADER); 
    }

    public function getPublicSubNum(){
        $query = $this->db->select('id')
                          ->where(TBL_QUESTION_POST , QUESTION_POST_PUBLIC)
                          ->where(TBL_QUESTION_STATUS, QUESTION_STATUS_SUBMITTED)
                          ->get(TBL_NAME_QUESTION);
        return $query->num_rows();
    }

    public function getSummaryField($field, $id){
        $query = $this->db->select($field)
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_SUMMARY);
        if ($query->num_rows() === 1)
        {
            return $query->row()->{$field};         
        }                  
        return FALSE;
    }

   

    public function updateSummary($data_arr, $id){
        
        $query = $this->db->select(TBL_SUM_UNREAD)
                          ->where(TBL_SUM_UID , $id)
                          ->limit(1)
                          ->get(TBL_NAME_SUMMARY);
        if ($query->num_rows() === 1)
        {
            $this->db->update(TBL_NAME_SUMMARY, $data_arr, array(TBL_SUM_UID => $id));
            return "success";         
        }     
        else{
            $this->db->insert(TBL_NAME_SUMMARY, $data_arr);

            $nid = $this->db->insert_id();

            return FALSE;
        }
    }

   

 
}