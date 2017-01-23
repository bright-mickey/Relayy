<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mchat extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function add($data_arr)
    {
        $query = $this->db->select('*')
                          ->where(TBL_CHAT_OCCUPANTS, $data_arr[TBL_CHAT_OCCUPANTS])
                          ->where(TBL_CHAT_TYPE, $data_arr[TBL_CHAT_TYPE])
                          ->where(TBL_CHAT_NAME, $data_arr[TBL_CHAT_NAME])
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            return $user;
        }

        foreach(json_decode($data_arr[TBL_CHAT_OCCUPANTS]) as $id){
            $this->db->set(TBL_USER_ENTERED_CHATS, 'entered_chats + 1', FALSE);
            $this->db->where('id', $id);
            $this->db->update(TBL_NAME_USER); 
        }

        $this->db->insert(TBL_NAME_CHAT, $data_arr);

        $nid = $this->db->insert_id();

        return FALSE;
    }

    public function getDialogs($like_uid)
    {
        $live_chat = array(1, 2);
        $query = $this->db->select('*')
                      ->like(TBL_CHAT_OCCUPANTS, $like_uid)
                      ->where_in(TBL_CHAT_STATUS, CHAT_STATUS_LIVE)
                      ->or_where(TBL_CHAT_TYPE, 0)
                      ->order_by(TBL_CHAT_TIME, "desc")
                      ->get(TBL_NAME_CHAT);

        return $query->result_array();
    }

    

    public function checkDialog($occupants, $type){
        if($type == 0) {
            $query = $this->db->select('*')
                          ->where(TBL_CHAT_OCCUPANTS, $occupants)
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);
        }
        else{
            $query = $this->db->select('*')
                              ->like(TBL_CHAT_OCCUPANTS, $occupants)
                              ->group_start()
                              ->where(TBL_CHAT_NAME,'Give feedback')
                              ->or_where(TBL_CHAT_NAME,'Report a problem')
                              ->or_where(TBL_CHAT_NAME,'Request a feature')
                              ->group_end()
                              ->limit(1)
                              ->get(TBL_NAME_CHAT);
        }
        if ($query->num_rows() === 1)
        {
            
            $user = $query->row();
            return $user;
        }
        return FALSE;
    }

    public function getRoomsWithID($q_id){
        $query = $this->db->select('did, name, occupants, type, status')
                          ->where(TBL_CHAT_QUESTIONID, $q_id)
                          ->get(TBL_NAME_CHAT);

        return $query->result_array();
    }

    
    public function updateChatwithDID($did){
      $query = $this->db->update(TBL_NAME_CHAT, array(TBL_CHAT_STATUS => CHAT_STATUS_LIVE), array(TBL_CHAT_DID => $did));
      $query = $this->db->select('*')
                          ->where(TBL_CHAT_DID, $did)
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);
      return $query->row();                          
    }

    public function DeactivateChatwithDID($did){
      $query = $this->db->update(TBL_NAME_CHAT, array(TBL_CHAT_STATUS => CHAT_STATUS_INIT), array(TBL_CHAT_DID => $did));
      $query = $this->db->select('*')
                          ->where(TBL_CHAT_DID, $did)
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);
      return $query->row();                          
    }

    public function upDateChatName($did, $type){
        if($type == 1) $query = $this->db->update(TBL_NAME_CHAT, array(TBL_CHAT_NAME => "Give feedback"), array(TBL_CHAT_DID => $did));
        else if($type == 2) $query = $this->db->update(TBL_NAME_CHAT, array(TBL_CHAT_NAME => "Report a problem"), array(TBL_CHAT_DID => $did));
        else if($type == 3) $query = $this->db->update(TBL_NAME_CHAT, array(TBL_CHAT_NAME => "Request a feature"), array(TBL_CHAT_DID => $did));
        $query = $this->db->select('*')
                          ->where(TBL_CHAT_DID, $did)
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);
        return $query->row();
    }

    public function getDialogList()
    {
        $query = $this->db->select('tbl_chat.type as type, tbl_chat.status as status, tbl_chat.name as name, tbl_chat.occupants as occupants, tbl_user.group as group, tbl_chat.did as did')
                          ->from(TBL_NAME_CHAT)
                          ->join(TBL_NAME_USER, "tbl_chat.owner = tbl_user.id", "left")
                          ->order_by(TBL_CHAT_TIME, "desc")
                          ->get();              
        return $query->result_array();
    }

    public function get($where_did)
    {
        $query = $this->db->select('*')
                          ->where(TBL_CHAT_DID, $where_did)
                          ->limit(1)
                          ->get(TBL_NAME_CHAT);

        if ($query->num_rows() === 1)
        {
            $dialog = $query->row();
            return $dialog;
        }

        return FALSE;
    }

    public function delete($where_did) 
    {
        $this->db->where(TBL_CHAT_DID, $where_did);
        $this->db->delete(TBL_NAME_CHAT); 

        return "success";
    }

    public function update($where_did, $data_arr)
    {
        $this->db->update(TBL_NAME_CHAT, $data_arr, array(TBL_CHAT_DID => $where_did));

        return "success";
    }

    public function changeStatus($where_did)
    {
        $chat = $this->get($where_did);

        if ($chat->{TBL_CHAT_STATUS} == CHAT_STATUS_INIT) {
            $data = array(
                TBL_CHAT_STATUS => CHAT_STATUS_LIVE
            );

            $this->db->update(TBL_NAME_CHAT, $data, array(TBL_CHAT_DID => $where_did));
            return $this->get($where_did);
        } else {
            $data = array(
                TBL_CHAT_STATUS => CHAT_STATUS_INIT
            );

            $this->db->update(TBL_NAME_CHAT, $data, array(TBL_CHAT_DID => $where_did));
            return $this->get($where_did);
        }
    }
}