<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mcode extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function checkcode($code){
        $query = $this->db->select('*')
                        ->where(TBL_INVITE_CODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_INVITE);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            if($user[0][TBL_INVITE_REMAIN] > 0){
                $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REMAIN => $user[0][TBL_INVITE_REMAIN] - 1), array(TBL_INVITE_CODE => $code));
                return $user[0];
            }
        }
        return FALSE;                        
    }

    public function checkLeaderCode($code){
        $query = $this->db->select('*')
                        ->where(TBL_LEADER_CODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_LEADER);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            return $user[0];
        }
        return FALSE;    
    }

    public function getWithID($id){
      $query = $this->db->select('*')
                        ->where(TBL_INVITE_ID, $id)
                        ->limit(1)
                        ->get(TBL_NAME_INVITE);
        if($query->num_rows() === 1)
        {
            $user = $query->result_array();
            return $user[0];
        }
        return FALSE;                        
    }

    public function checkModeratorCode($code){
        $query = $this->db->select('*')
                        ->where(TBL_INVITE_MCODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_INVITE);
        if ($query->num_rows() == 1)
        {
            $user = $query->result_array();
            if($user[0][TBL_INVITE_MREMAIN] > 0){
                $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_MREMAIN => $user[0][TBL_INVITE_MREMAIN] - 1), array(TBL_INVITE_MCODE => $code));
                $user = $query->result_array();
                return $user[0];
            }
        }
        return FALSE;   
    }

    public function getGroupRequests(){
        $query = $this->db->select('*, tbl_group.name as name, tbl_group.image as image, tbl_group.member as member')
                        ->from("tbl_invite_code")
                        ->join("tbl_group", "tbl_group.code = tbl_invite_code.code")
                        ->where(TBL_INVITE_REMAIN, 0)
                        ->where('request>', 0)
                        ->where('tbl_invite_code.type', USER_TYPE_MODERATOR)
                        ->get();

        return $query->result_array();
    }

    public function getNormalRequests(){
        $normal = array(2, 3);
        $query = $this->db->select('*, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.email as email, tbl_user.type as type')
                        ->from("tbl_invite_code")
                        ->join("tbl_user", "tbl_user.id = tbl_invite_code.id")
                        ->where(TBL_INVITE_REMAIN, 0)
                        ->where('request', 5)
                        ->where_in('tbl_invite_code.type', $normal)
                        ->get();

        return $query->result_array();
    }

    public function getInviteInfo(){
        $normal = array(2, 3, 4);
        $query = $this->db->select('tbl_invite_code.*, tbl_user.group as group, tbl_user.signup_code as signup_code, tbl_user.photo as photo, tbl_user.fname as fname, tbl_user.lname as lname, tbl_user.email as email, tbl_user.type as type')
                        ->from("tbl_invite_code")
                        ->join("tbl_user", "tbl_user.id = tbl_invite_code.id")
                        ->where_in('tbl_invite_code.type', $normal)
                        ->get();

        return $query->result_array();
    }

    public function getLeaders(){
        $query = $this->db->select('*')
                        ->from("tbl_leader")
                        ->get();
        return $query->result_array();
    }

    public function addLeaderCode($code, $name){
        $user = $this->db->insert(TBL_NAME_LEADER, array(TBL_LEADER_CODE => $code, TBL_LEADER_NAME => $name));
        $id = $this->db->insert_id();
        return $id ? $id : FALSE;
    }

    public function UpdateLeader($code,$name){
        $this->db->update(TBL_NAME_LEADER, array(TBL_LEADER_NAME => $name), array(TBL_LEADER_CODE => $code));
    }

    public function UpdateInvite($code, $members, $moderators){
        $this->db->set(TBL_INVITE_REMAIN, $members);
        $this->db->set(TBL_INVITE_MREMAIN, $moderators);
        $this->db->set(TBL_INVITE_REQUEST, 0);
        $this->db->set(TBL_INVITE_MREQUEST, 0);
        $this->db->where(TBL_INVITE_CODE, $code);
        $this->db->update(TBL_NAME_INVITE); 
    }

    public function allowRequest($code){
        $this->db->set(TBL_INVITE_REMAIN, 'remain + request', FALSE);
        $this->db->set(TBL_INVITE_MREMAIN, 'm_remain + m_request', FALSE);
        $this->db->set(TBL_INVITE_REQUEST, 0);
        $this->db->set(TBL_INVITE_MREQUEST, 0);
        $this->db->where(TBL_INVITE_CODE, $code);
        $this->db->update(TBL_NAME_INVITE); 
    }

    public function rejectRequest($code){
        $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REQUEST => 0, TBL_INVITE_MREQUEST => 0), array(TBL_INVITE_CODE => $code));
    }

    public function getModerators(){
        $query = $this->db->select('*, tbl_user.email as email')
                        ->from("tbl_invite_code")
                        ->join("tbl_user", "tbl_user.id = tbl_invite_code.id")
                        ->where(TBL_INVITE_CODE, $this->cgroup)
                        ->where('tbl_invite_code.type', USER_TYPE_MODERATOR)
                        ->get();

        return $query->result_array();
    }

    public function request($code, $members, $moderators){
        $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REQUEST => $members, TBL_INVITE_MREQUEST => $moderators), array(TBL_INVITE_CODE => $code));
    }

   

    public function updateWithID($id, $type){
        if($type == 4)   $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REMAIN => 50), array(TBL_INVITE_ID => $id));
        else if($type == 1) $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REMAIN => 9999999), array(TBL_INVITE_ID => $id));
        else $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_REMAIN => 5), array(TBL_INVITE_ID => $id));
        $query = $this->db->select('*')
                        ->where(TBL_INVITE_ID, $id)
                        ->limit(1)
                        ->get(TBL_NAME_INVITE);
        if ($query->num_rows() === 1)
        {
            $user = $query->result_array();
            return $user[0];
        }
        return FALSE;
    }

    

}