<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mbusiness extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function getArray($id){
    	$query = $this->db->select('*')
                          ->where(TBL_BUSINESS_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_BUSINESS);

        if ($query->num_rows() === 1)
        {
            $user = $query->result_array();
            return $user[0];
        }
        return FALSE;

    }

    public function saveBusinessData($data_arr){
        $query = $this->db->select('*')
                          ->where(TBL_BUSINESS_ID, $data_arr[TBL_BUSINESS_ID])
                          ->limit(1)
                          ->get(TBL_NAME_BUSINESS);

        if ($query->num_rows() === 1)
        {
            $this->db->update(TBL_NAME_BUSINESS, $data_arr, array(TBL_BUSINESS_ID => $data_arr[TBL_BUSINESS_ID]));

            return "success";
        }

        $this->db->insert(TBL_NAME_BUSINESS, $data_arr);

        $nid = $this->db->insert_id();

        return "success";
    }

    public function getLinkswithID($id){
        $query = $this->db->select('*')
                          ->where(TBL_LINK_ID, $id)
                          ->get(TBL_NAME_LINK);

        return $query->result_array();
    }

    public function updateInfo($data_arr){

      $this->db->update(TBL_NAME_BUSINESS, $data_arr, array(TBL_BUSINESS_ID => $data_arr[TBL_BUSINESS_ID]));
      return "success";

    }

    public function updatePosition($pos, $id){
        $query = $this->db->select('id')
                          ->where(TBL_BUSINESS_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_BUSINESS);

        if($query->num_rows() === 1){
            $this->db->update(TBL_NAME_BUSINESS, array(TBL_BUSINESS_POSITION => $pos), array(TBL_BUSINESS_ID => $id));
            return "success";
        }
        return FALSE;
    }

    public function updateEducation($edu, $id){
      $query = $this->db->select('id')
                          ->where(TBL_BUSINESS_ID, $id)
                          ->limit(1)
                          ->get(TBL_NAME_BUSINESS);

        if($query->num_rows() === 1){
            $this->db->update(TBL_NAME_BUSINESS, array(TBL_BUSINESS_EDUCATION => $edu), array(TBL_BUSINESS_ID => $id));
            return "success";
        }
        return FALSE;
    }

    public function addLink($data_arr){
      $query = $this->db->select('*')
                          ->where(TBL_LINK_LINK, $data_arr[TBL_LINK_LINK])
                          ->limit(1)
                          ->get(TBL_NAME_LINK);

        if ($query->num_rows() > 0)
        {
            return "already exist";
        }

        $this->db->insert(TBL_NAME_LINK, $data_arr);

        $nid = $this->db->insert_id();

        return "success";
    }

    public function removeLink($id, $link){
        $this->db->where(TBL_LINK_LINK, $link);
        $this->db->where(TBL_LINK_ID, $id);
        $this->db->delete(TBL_NAME_LINK); 

        return "success";
    }


    

}