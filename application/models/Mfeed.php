<?php
/**
 * Created by PhpStorm.
 * User: win
 * Date: 1/7/15
 * Time: 9:35 PM
 */

class Mfeed extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function add($data_arr){
        $now = new DateTime();
        $currentTime = $now->getTimestamp();

        $data_arr[TBL_FEED_TIME] = $currentTime;

        $this->db->insert(TBL_NAME_ACTION, $data_arr);

        $id = $this->db->insert_id();

        return $id ? $id : FALSE;
    
    }

    public function get(){
        $query = $this->db->select('*, tbl_user.type as utype, tbl_action_feed.type as type')
                        ->from("tbl_action_feed")
                        ->join("tbl_user", "tbl_action_feed.who_id = tbl_user.id", "left")
                        ->limit(30)
                        ->order_by(TBL_FEED_TIME, "desc")
                        ->get();
        return $query->result_array();
    }

    public function getNewFeeds($rnum){
        $query = $this->db->select('*')
                        ->from("tbl_action_feed")
                        ->join("tbl_user", "tbl_action_feed.who_id = tbl_user.id", "left")
                        ->where('no >', $rnum)
                        ->order_by(TBL_FEED_TIME, "desc")
                        ->get();
        return $query->result_array();
    }

    public function getMoreFeeds($lnum){
        $query = $this->db->select('*')
                        ->from("tbl_action_feed")
                        ->join("tbl_user", "tbl_action_feed.who_id = tbl_user.id", "left")
                        ->where('no <', $lnum)
                        ->limit(30)
                        ->order_by(TBL_FEED_TIME, "desc")
                        ->get();
        return $query->result_array();
    }

    

}


