<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mhistory extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('mcode');
    }

    public function unsave($mid){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_MID, $mid)
                        ->limit(1)
                        ->get(TBL_NAME_CHAT_HISTORY);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            if($user[0][TBL_HISTORY_SAVE] > 0){
                $this->db->update(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_SAVE => $user[0][TBL_HISTORY_SAVE] - 1), array(TBL_HISTORY_MID => $mid));
                return $user[0];
            }
        }
        return FALSE;      
    }

    public function save($mid, $did){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_MID, $mid)
                        ->limit(1)
                        ->get(TBL_NAME_CHAT_HISTORY);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            $this->db->update(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_SAVE => $user[0][TBL_HISTORY_SAVE] + 1), array(TBL_HISTORY_MID => $mid));
            return $user[0];
        }
        else{
        	$user = $this->db->insert(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_DID => $did, TBL_HISTORY_MID => $mid, TBL_HISTORY_SAVE => 1));
	        $id = $this->db->insert_id();
	        return $id ? $id : FALSE;
        }
    }

    public function getDeletedMsgs($id){
        $query = $this->db->select(TBL_HISTORY_MID)
                          ->where('del_time>', 0)
                          ->get(TBL_NAME_CHAT_HISTORY);
        return $query->result_array();
    }

    function like($mid, $uid, $did){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_MID, $mid)
                        ->limit(1)
                        ->get(TBL_NAME_CHAT_HISTORY);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            $this->db->update(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_LIKE => $user[0][TBL_HISTORY_LIKE] + 1), array(TBL_HISTORY_MID => $mid));

        }
        else{
        	$user = $this->db->insert(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_DID => $did, TBL_HISTORY_MID => $mid, TBL_HISTORY_LIKE => 1));
	        $id = $this->db->insert_id();
        }    

        $user = $this->db->insert(TBL_NAME_LIKE, array(TBL_LIKE_DID => $did, TBL_LIKE_MID => $mid, TBL_LIKE_UID => $uid));
        $id = $this->db->insert_id();
        return $id ? $id : FALSE;
    }

    function unlike($mid){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_MID, $mid)
                        ->limit(1)
                        ->get(TBL_NAME_CHAT_HISTORY);
        if ($query->num_rows() == 1)
        {

            $user = $query->result_array();
            if($user[0][TBL_HISTORY_LIKE] > 0){
                $this->db->update(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_LIKE => $user[0][TBL_HISTORY_LIKE] - 1), array(TBL_HISTORY_MID => $mid));
                return $user[0];
            }
        }
        return FALSE;  
    }

    function checkLike($mid, $uid){
    	$query = $this->db->select('*')
                        ->where(TBL_LIKE_MID, $mid)
                        ->where(TBL_LIKE_UID, $uid)
                        ->limit(1)
                        ->get(TBL_NAME_LIKE);
        if ($query->num_rows() == 1)
        {
        	$this->db->where(TBL_LIKE_MID, $mid)
                	    ->where(TBL_LIKE_UID, $uid)
	        		    ->delete(TBL_NAME_LIKE); 
	        return "unlike";
        }
        return FALSE;
    }

    function getMsgStates($did){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_DID, $did)
                        ->get(TBL_NAME_CHAT_HISTORY);
        if ($query->num_rows() > 0)
        {
        	return $query->result_array();
        }
        return FALSE;
    }

    function getLikedMsgIDs($uid){
    	$query = $this->db->select('*')
                        ->where(TBL_LIKE_UID, $uid)
                        ->get(TBL_NAME_LIKE);
        if ($query->num_rows() > 0)
        {
        	return $query->result_array();
        }
        return FALSE;
    }

    function deleteMsg($mid, $did){
    	$query = $this->db->select('*')
                        ->where(TBL_HISTORY_MID, $mid)
                        ->limit(1)
                        ->get(TBL_NAME_CHAT_HISTORY);

        $now = new DateTime();
        $currentTime = $now->getTimestamp();

        if ($query->num_rows() == 1)
        {

            $this->db->update(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_DTIME => $currentTime), array(TBL_HISTORY_MID => $mid));
            return "success";
        }

        $user = $this->db->insert(TBL_NAME_CHAT_HISTORY, array(TBL_HISTORY_MID => $mid, TBL_HISTORY_DTIME => $currentTime, TBL_HISTORY_DID => $did));
        $id = $this->db->insert_id();
        return FALSE;                
    }

    

    

}