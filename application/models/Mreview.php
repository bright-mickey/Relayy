<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mreview extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function getReviews($receiverID){
        $query = $this->db->select('tbl_review.id as id, tbl_review.from_id as from_id, tbl_review.review as review, tbl_user.photo as photo')
        				  ->from(TBL_NAME_REVIEW)
                          ->join(TBL_NAME_USER, "tbl_user.id = tbl_review.from_id")
                          ->where(TBL_REVIEW_TO, $receiverID)
                          ->get();

        return $query->result_array();
    
    }

    public function addReview($data_arr){
        $this->db->set(TBL_USER_REVIEWS, 'reviews + 1', FALSE);
            $this->db->where(TBL_USER_ID, $data_arr[TBL_REVIEW_TO]);
            $this->db->update(TBL_NAME_USER);

    	$this->db->insert(TBL_NAME_REVIEW, $data_arr);

        $id = $this->db->insert_id();

        return $id ? $id : FALSE;
    }

    public function delete($id){
    	$this->db->where(TBL_REVIEW_ID, $id);
        $this->db->delete(TBL_NAME_REVIEW); 

        return "success";
    }

    public function update($id, $text){
        $this->db->update(TBL_NAME_REVIEW, array(TBL_REVIEW_TEXT => $text), array(TBL_REVIEW_ID => $id));
        return "success";
    }

}


