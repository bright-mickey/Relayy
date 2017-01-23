<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Cronjob extends ChatController
{
    
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$now = new DateTime();
        $now->format('Y-m-d H:i:s');    // MySQL datetime format
        $currentTime = $now->getTimestamp();

		$all_user = $this->muser->getAllUsers();
		if(sizeof($all_user) > 0){
			foreach($all_user as $user){
				$interval = 0;
				$option = $this->moption->getInterval($user[TBL_USER_ID]);
				if($option !== FALSE){
					if($option->{TBL_OPTION_INTERVAL} === "1 Hour") $interval = 3600;
					else if($option->{TBL_OPTION_INTERVAL} === "4 Hour") $interval = 14400;
					else if($option->{TBL_OPTION_INTERVAL} === "8 Hour") $interval = 28800;
					else if($option->{TBL_OPTION_INTERVAL} === "Day") $interval = 86400;
					else if($option->{TBL_OPTION_INTERVAL} === "Week") $interval = 604800;
					else if($option->{TBL_OPTION_INTERVAL} === "Off") $interval = 99999999999;
					if($currentTime - $user['summary'] > $interval){
						$summary = $this->moption->getSummary($user[TBL_USER_ID]);
						if($summary !== FALSE){
							$this->email->sendSummary($user[TBL_USER_FNAME], $user[TBL_USER_EMAIL], json_encode($summary));
							echo $currentTime.$user[TBL_USER_ID];
							$this->muser->updateSummary($currentTime, $user[TBL_USER_ID]);
							$this->moption->deleteSummary($user[TBL_USER_ID]);
						}

					}



				}
			}        
    	

    	}
           	

	}

}


