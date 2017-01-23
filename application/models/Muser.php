<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Muser extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function add( $data_arr )
    {
        
        
        $query = $this->db->select(TBL_USER_ID)
                          ->where(TBL_USER_EMAIL, $data_arr[TBL_USER_EMAIL])
                          ->limit(1)
                          ->get(TBL_NAME_USER);
        
        if ($query->num_rows() === 1) {
            $user = $query->row();
            return $user->{TBL_USER_ID};
        }


        $user = $this->db->insert(TBL_NAME_USER, $data_arr);
        $id = $this->db->insert_id();

        //=================================== create column to invite code table

        $newUser = $this->db->select('*')
                          ->where(TBL_USER_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        $this->db->insert(TBL_NAME_OPTION, array(TBL_OPTION_UID=>$id));
        $this->db->insert_id();

        $this->addInviteCode($newUser->row());

        return $id ? $id : FALSE;
    }

    public function addInviteCode($user){
        if($user->{TBL_USER_CODE}){
            $query = $this->db->select('*')
                          ->where(TBL_INVITE_MCODE, $user->{TBL_USER_CODE})
                          ->where(TBL_INVITE_TYPE, USER_TYPE_MODERATOR)
                          ->limit(1)
                          ->get(TBL_NAME_INVITE);

            if ($query->num_rows() > 0) {//if moderator code, copy the invite codes
                $moderator = $query->row();
                $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => $moderator->{TBL_INVITE_CODE}), array(TBL_USER_ID => $user->{TBL_USER_ID}));
                //if in invite code table, update else insert
                $query = $this->db->select('*')
                                  ->where(TBL_INVITE_ID, $user->{TBL_USER_ID})
                                  ->limit(1)
                                  ->get(TBL_NAME_INVITE);

                if ($query->num_rows() > 0) {
                  $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_TYPE => $user->{TBL_USER_TYPE} % 10,
                                                 TBL_INVITE_REMAIN => $moderator->{TBL_INVITE_REMAIN},
                                                 TBL_INVITE_MREMAIN => $moderator->{TBL_INVITE_MREMAIN},
                                                 TBL_INVITE_CODE => $moderator->{TBL_INVITE_CODE},
                                                 TBL_INVITE_MCODE => $moderator->{TBL_INVITE_MCODE}), array(TBL_INVITE_ID => $user->{TBL_USER_ID}));
                }
                else{
                  $this->db->insert(TBL_NAME_INVITE, array(TBL_INVITE_ID => $user->{TBL_USER_ID},
                                                 TBL_INVITE_TYPE => $user->{TBL_USER_TYPE} % 10,
                                                 TBL_INVITE_REMAIN => $moderator->{TBL_INVITE_REMAIN},
                                                 TBL_INVITE_MREMAIN => $moderator->{TBL_INVITE_MREMAIN},
                                                 TBL_INVITE_CODE => $moderator->{TBL_INVITE_CODE},
                                                 TBL_INVITE_MCODE => $moderator->{TBL_INVITE_MCODE}));
                  $id = $this->db->insert_id();
                  return $id;
                }
                
            }

            $query = $this->db->select('*')
                          ->where(TBL_INVITE_CODE, $user->{TBL_USER_CODE})
                          ->where(TBL_INVITE_TYPE, USER_TYPE_MODERATOR)
                          ->limit(1)
                          ->get(TBL_NAME_INVITE);
            if ($query->num_rows() > 0) {//if group code, set group to user table
              $moderator = $query->row();
              $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => $moderator->{TBL_INVITE_CODE}), array(TBL_USER_ID => $user->{TBL_USER_ID}));
            }
            
        }

        //if invited user, generate invite code based on the type
        $codeA = $this->getInviteCode();
        $codeB = "";
        if($user->{TBL_USER_TYPE} % 10 == 4) $codeB = $this->getInviteCode();

        $m_bullet = 0;
        $bullet = 0;
        if($user->{TBL_USER_TYPE} % 10 == 4){
            $bullet = 250;
            $m_bullet = 4;
        }
        else if($user->{TBL_USER_TYPE} % 10 == 1){
            $bullet = 9999999;  
        } 
        else{
            $bullet = 5;  
        } 
        $query = $this->db->select('*')
                          ->where(TBL_INVITE_ID, $user->{TBL_USER_ID})
                          ->limit(1)
                          ->get(TBL_NAME_INVITE);

        if ($query->num_rows() > 0) {
            $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_TYPE => $user->{TBL_USER_TYPE} % 10,
                                                 TBL_INVITE_REMAIN => $bullet,
                                                 TBL_INVITE_MREMAIN => $m_bullet,
                                                 TBL_INVITE_CODE => $codeA,
                                                 TBL_INVITE_MCODE => $codeB), array(TBL_USER_ID => $user->{TBL_USER_ID}));
        }else{
            $this->db->insert(TBL_NAME_INVITE, array(TBL_INVITE_ID => $user->{TBL_USER_ID},
                                                 TBL_INVITE_TYPE => $user->{TBL_USER_TYPE} % 10,
                                                 TBL_INVITE_REMAIN => $bullet,
                                                 TBL_INVITE_MREMAIN => $m_bullet,
                                                 TBL_INVITE_CODE => $codeA,
                                                 TBL_INVITE_MCODE => $codeB));
        }
        
        if($user->{TBL_USER_TYPE} % 10 == 4){
          $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => $codeA), array(TBL_USER_ID => $user->{TBL_USER_ID}));
        }
        $id = $this->db->insert_id();
    }

    public function getInviteCode(){
        $Letter = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        do{
            $code1 = mt_rand(0, 25);
            $code2 = mt_rand(0, 25);
            $code3 = (string)mt_rand(0, 32767)/32767;

            $query = $this->db->select(TBL_INVITE_ID)
                              ->where(TBL_INVITE_CODE, substr($Letter, $code1, 1).substr($Letter, $code2, 1).substr($code3, 2, 4))
                              ->limit(1)
                              ->get(TBL_NAME_INVITE);

           
        }while($query->num_rows() > 0);
        return substr($Letter, $code1, 1).substr($Letter, $code2, 1).substr($code3, 2, 4);

    }

    public  function floattostr( $val )
    {
        preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o );
        return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
    }

    public function addInvitedUser($id, $data_arr){
        $query = $this->db->select(TBL_USER_ID)
                          ->where(TBL_USER_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1) {
            $this->db->update(TBL_NAME_USER, $data_arr, array(TBL_USER_ID => $id));
            $this->addInviteCode($this->get($id));
        }
        

    }

    public function getAllUsers(){
        $query = $this->db->select('*')
                          ->get(TBL_NAME_USER);
        return $query->result_array();                  

    }

    public function getUserlist($status = USER_STATUS_INIT)
    {
        if ($status == USER_STATUS_ALL) {
            $query = $this->db->select('*, tbl_group.name as groupname')
                          ->from(TBL_NAME_USER)
                          ->join(TBL_NAME_GROUP, "tbl_group.code = tbl_user.group", 'left')
                          ->where_not_in(TBL_USER_STATUS, array(USER_STATUS_DELETE))
                          ->get();
        } else {
            $query = $this->db->select('*, tbl_group.name as groupname')
                          ->from(TBL_NAME_USER)
                          ->join(TBL_NAME_GROUP, "tbl_group.code = tbl_user.group")
                          ->where(TBL_USER_STATUS, $status)
                          ->get();
        }

        return $query->result_array();

    }

    public function getAvailableusers($email){
      $query = $this->db->select('*')
                          ->from(TBL_NAME_USER)
                          ->where(TBL_USER_STATUS, USER_STATUS_LIVE)
                          ->where_not_in(TBL_USER_EMAIL, $email)
                          ->get();
      return $query->result_array();
    }

    public function updateSummary($cTime, $id){
        $this->db->update(TBL_NAME_USER, array(TBL_USER_SUMMARY => $cTime), array(TBL_USER_ID => $id));
    }

    public function getUserStates(){
        $query = $this->db->select('id, last_sign_in')
                          ->where(TBL_USER_STATUS, USER_STATUS_LIVE)
                          ->get(TBL_NAME_USER);

        return $query->result_array();




    }

    public function saveBadges($state, $uid){
        $this->db->update(TBL_NAME_USER, array(TBL_USER_UNREAD => $state), array(TBL_USER_UID => $uid));
    }

    public function getBadges($uid){
        $query = $this->db->select(TBL_USER_UNREAD)
                          ->where(TBL_USER_UID, $uid)
                          ->limit(1)
                          ->get(TBL_NAME_USER);
        if ($query->num_rows() === 1)
        {
            $state = $query->row();
            return $state;
        }                  
    }

    public function getModerator($groupname){
        $query = $this->db->select('*')
                          ->where(TBL_USER_GROUP, $groupname)
                          ->where(TBL_USER_TYPE, USER_TYPE_MODERATOR)
                          ->limit(1)
                          ->get(TBL_NAME_USER);
        if ($query->num_rows() === 1)
        {
            $state = $query->row();
            return $state;
        }                  
    }

    public function getBlockList($uid){
        $query = $this->db->select(TBL_USER_BLOCKLIST)
                          ->where(TBL_USER_UID, $uid)
                          ->limit(1)
                          ->get(TBL_NAME_USER);
        if ($query->num_rows() === 1)
        {
            $state = $query->row();
            return $state;
        }                  
    }

    public function saveBlockList($uid, $list){
        $this->db->update(TBL_NAME_USER, array(TBL_USER_BLOCKLIST => $list), array(TBL_USER_UID => $uid));
    }

    public function getEmailwithID($id){
        $query = $this->db->select(TBL_USER_EMAIL)
                          ->where(TBL_USER_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            return $user;
        }
        return FALSE;

    }

    public function updateLinkedInData($email, $data_arr, $deleted){
        $this->db->update(TBL_NAME_USER, $data_arr, array(TBL_USER_EMAIL => $email));
        if($deleted) $this->addInviteCode($this->getEmail($email));
    }

    public function updateUser($uid, $data_arr){

        $this->db->update(TBL_NAME_USER, $data_arr, array(TBL_USER_ID => $uid));

        return "success";

    }
    
    public function searchUserlist($searchText = "")
    {
        $query = $this->db->select('*')
                      ->or_like(TBL_USER_FNAME, $searchText, 'both')
                      ->or_like(TBL_USER_LNAME, $searchText, 'both')
                      ->or_like(TBL_USER_EMAIL, $searchText, 'both')
                      ->where(TBL_USER_STATUS, USER_STATUS_LIVE)
                      ->get(TBL_NAME_USER);

        return $query->result_array();

    }

    public function getAdminUsers()
    {
        $query = $this->db->select('*')
                      ->where(TBL_USER_TYPE, USER_TYPE_ADMIN)
                      ->get(TBL_NAME_USER);

        return $query->result_array();

    }

    public function get($where_id)
    {
        $query = $this->db->select('*')
                          ->where(TBL_USER_ID, $where_id)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            return $user;
        }

        return FALSE;
    }

    public function getWithUID($uid){
        $query = $this->db->select('*')
                          ->where(TBL_USER_UID, $uid)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            return $user;
        }

        return FALSE;
    }
    
    public function getEmail($where_email)
    {
        $query = $this->db->select('*')
                          ->where(TBL_USER_EMAIL, $where_email)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            return $user;
        }

        return FALSE;
    }

    public function getUserArray($where_id)
    {
        $query = $this->db->select('*')
                          ->where(TBL_USER_ID, $where_id)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $result = $query->result_array();
            return $result[0];
        }

        return FALSE;
    }

    public function edit($where_id, $data_arr)
    {
        $this->db->update(TBL_NAME_USER, $data_arr, array(TBL_USER_ID => $where_id));

        return $this->get($where_id);
    }

    public function changeStatus($where_id)
    {
        $user = $this->get($where_id);

        if ($user->status == USER_STATUS_INIT) {
            $data = array(
                TBL_USER_STATUS => USER_STATUS_LIVE
            );

            $this->db->update(TBL_NAME_USER, $data, array(TBL_USER_ID => $where_id));            

            return $this->get($where_id);
        } else {
            $data = array(
                TBL_USER_STATUS => USER_STATUS_INIT
            );

            $this->db->update(TBL_NAME_USER, $data, array(TBL_USER_ID => $where_id));

            return $this->get($where_id);
        }
    }

    public function approve($where_id) {
        $data = array(
            TBL_USER_STATUS => USER_STATUS_LIVE
        );

        $this->db->update(TBL_NAME_USER, $data, array(TBL_USER_ID => $where_id));
        return $this->get($where_id);                
    }

    public function delete($id)
    {
        
        $this->db->update(TBL_NAME_USER, array(TBL_USER_STATUS => USER_STATUS_DELETE, TBL_USER_GROUP => "", TBL_USER_CODE => ""), array(TBL_USER_ID => $id));

        $this->db->where(TBL_INVITE_ID, $id);
        $this->db->delete(TBL_NAME_INVITE);

    }

    

    public function resetPassword($emailAddress, $password)
    {

    }

    public function login($email, $password)
    {

        //$query = $this->db->select('uid, fname, pwd, type, status, email, photo')
        $query = $this->db->select('*')
                          ->where(TBL_USER_EMAIL, $email)
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() === 1)
        {
            $user = $query->row();

            $pwd = $user->{TBL_USER_PWD};

            if ($password == $pwd) {
                if ($user->{TBL_USER_STATUS} == USER_STATUS_DELETE) {
                    return USER_LOGIN_DELETE;
                } else {
                    return USER_LOGIN_SUCCESS;
                }
            } else {
                return USER_LOGIN_PWD;
            }
        }

        return USER_LOGIN_404;
    }

    public function register($data_arr) {

        $query = $this->db->select('*')
                          ->where(TBL_USER_UID, $data_arr[TBL_USER_UID])
                          ->limit(1)
                          ->get(TBL_NAME_USER);

        if ($query->num_rows() >= 1) {
            $newUser = $query->row();
            
            if ($newUser->{TBL_USER_STATUS} == USER_STATUS_DELETE) {
                
                $data = array(
                    TBL_USER_STATUS => USER_STATUS_INIT
                );
                $this->db->update(TBL_NAME_USER, $data, array(TBL_USER_ID => $newUser->{TBL_USER_ID}));   
                $newUser->{TBL_USER_STATUS} = USER_STATUS_INIT;
            }
            
            $this->db->update(TBL_NAME_USER, $data_arr, array(TBL_USER_ID => $newUser->{TBL_USER_ID}));
            $newUser = $this->get($newUser->{TBL_USER_ID});
 
            
            return $newUser;
        } else {
       
            $newUser = $this->add($data_arr);
            $this->UpdateGroupLeaderUsers($data_arr[TBL_USER_CODE]);
            return $this->get($newUser);
        }

        return FALSE;
    }

    public function UpdateGroupLeaderUsers($code){
        $query = $this->db->select('*')
                        ->where(TBL_LEADER_CODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_LEADER);
        if ($query->num_rows() == 1)
        {

            $leader = $query->result_array();
            $this->db->update(TBL_NAME_LEADER, array(TBL_LEADER_USERS => $leader[0][TBL_LEADER_USERS] + 1), array(TBL_LEADER_CODE => $code));
                return $leader[0];
        }
        return FALSE;   
    }

    public function getBiowithID($id){
      $query = $this->db->select('*')
                        ->where(TBL_USER_ID, $id)
                        ->limit(1)
                        ->get(TBL_NAME_USER);
      if ($query->num_rows() == 1)
      {
        return $query->row()->{TBL_USER_BIO};
      }   
      else return "undefined user";              
    }


}