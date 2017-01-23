<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mgroup extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('mcode');
    }

    public function add($name, $image, $code){
        $query = $this->db->select('*')
                        ->where(TBL_GROUP_NAME, $name)
                        ->limit(1)
                        ->get(TBL_NAME_GROUP);
        if ($query->num_rows() === 1){
            $this->db->update(TBL_NAME_GROUP, array(TBL_GROUP_IMAGE => $image), array(TBL_GROUP_CODE => $code));
            return "name_exist";
        }

    	$query = $this->db->select('*')
                        ->where(TBL_GROUP_CODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_GROUP);
		if ($query->num_rows() === 1){
			$this->db->update(TBL_NAME_GROUP, array(TBL_GROUP_NAME => $name, TBL_GROUP_IMAGE => $image), array(TBL_GROUP_CODE => $code));
		}
		else{
			$this->db->insert(TBL_NAME_GROUP, array(TBL_GROUP_NAME => $name, TBL_GROUP_IMAGE => $image, TBL_GROUP_CODE => $code));
			$id = $this->db->insert_id();
		}
        return "success";

    }

    public function get($code){
        $query = $this->db->select('*')
                        ->where('LOWER(code)', strtolower($code))
                        ->limit(1)
                        ->get(TBL_NAME_GROUP);
        if ($query->num_rows() === 1){
            $res = $query->result_array();
            return $res[0];
        }
        return FALSE;
    }

    public function getGroupInfo(){
        $query = $this->db->select('*')
                          ->get(TBL_NAME_GROUP);
        return $query->result_array();
    }

    public function checkwithID($code){
        $query = $this->db->select('*')
                        ->where(TBL_GROUP_CODE, $code)
                        ->limit(1)
                        ->get(TBL_NAME_GROUP);
        if ($query->num_rows() === 1){
            $res = $query->result_array();
            return $res[0];
        }
        return FALSE;
    }

    public function leaveGroup($id){
        $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => ""), array(TBL_USER_ID => $id));
      
        return TRUE;
    }

    public function leaveGroupForModerator($id, $type){
        $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => "", TBL_USER_TYPE => $type), array(TBL_USER_ID => $id));
        $codeA = $this->getInviteCode();
        $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_TYPE => $type, TBL_INVITE_REMAIN => 5, TBL_INVITE_MREMAIN => 0, TBL_INVITE_CODE => $codeA, TBL_INVITE_MCODE => ""), array(TBL_INVITE_ID => $id));
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

    public function delete($id, $group, $type){
        //format group code, change user type
        $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => "", TBL_USER_TYPE => $type), array(TBL_USER_ID => $id));
        //sub-moderator to Enterp
        $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => "", TBL_USER_TYPE => 3), array(TBL_USER_GROUP => $group, TBL_USER_TYPE => 4));
        //format other users' group code
        $this->db->update(TBL_NAME_USER, array(TBL_USER_GROUP => ""), array(TBL_USER_GROUP => $group));
        $codeA = $this->getInviteCode();
        $this->db->update(TBL_NAME_INVITE, array(TBL_INVITE_TYPE => $type, TBL_INVITE_REMAIN => 5, TBL_INVITE_MREMAIN => 0, TBL_INVITE_CODE => $codeA, TBL_INVITE_MCODE => ""), array(TBL_INVITE_ID => $id));

        $this->db->where(TBL_GROUP_CODE, $group);
        $this->db->delete(TBL_NAME_GROUP);
        return "success";        
    }

    

    

}