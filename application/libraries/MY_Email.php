<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

include_once (dirname(__FILE__) . "/Mailin.php");
class MY_Email extends CI_Email {

    var $senderEmail = "support@relayy.io";
    var $senderName = "Relayy.io";
    public function __construct()
    {
        parent::__construct();
    }

    public function sendEmail($fromEmail, $fromName, $toEmail, $title, $subtitle, $content, $footer="", $type = 0)
    {

    	$config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html'; // Append This Line
        $this->initialize($config);
		
    	$this->from($fromEmail, $fromName);
		$this->to($toEmail);
		$this->reply_to($fromEmail, $fromName);

		if ($type == 0) {
			$this->subject($title);	
		} else {
			$this->subject('You got Email');	
		}
		
		$mailContent = '
			<html>

            <head>
                <meta charset="UTF-8">
                <title>'.$title.'</title>
            </head>

            <body style="margin: 0; padding: 0;background: #f1f1f1;">
                <center>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;max-width:600px;">
                    <tr>
                        <td align="center" valign="top" style="padding:30px;">
                            <!-- HEADER STARTS -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
                                <tr>
                                    <td align="center" style="padding:20px 30%;">
                                        <img src="'. asset_base_url().'/images/e_logo.png" style="width:80%; height:auto;" />
                                    </td>
                                </tr>
                              
                            </table>
                            <!-- HEADER END -->
                            <!-- CONTENT STARTS -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                   
                                    <td align="left" valign="top">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="color:#4d4d4d;font-size:20px;">';
            if (strlen($title) > 0) {

            $mailContent .= '               <tr>
                                                <td align="left" style="background:white;border-radius:12px 12px 0px 0px;padding:30px 20px;min-height:30px;">
                                                    <center><span style="font-size:25px;line-height:30px;">'.$subtitle.'</span></center>
                                                </td>
                                            </tr>';
                                            
                                        }
            $mailContent .= '               <tr>
                                                <td align="left" style="background:white;padding:20px;border-top:1px solid #BBB;border-radius:0px 0px 12px 12px;font-size:15px;">
                                                <strong>
                               '.$content.'
                                                </strong>
                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                   
                                </tr>
                            </table>
                            <!-- CONTENT END -->
                            <!-- FOOTER STARTS -->
                            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;margin-top:30px;">
                                <tr>
                                    <td align="left" valign="top">
                                        <table cellspacing="0" cellpadding="0" border="0" style="font-size:12px;padding:0px 50px;color:#4d4d4d;">
                                            <tr>
                                                <td align="left">
                                                    '.$footer.
                                                '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                            </table>
                            <!-- FOOTER END -->
                        </td>
                    </tr>
                </table>
                <center>
            </body>

            </html>';

		
        $mailin = new Mailin("https://api.sendinblue.com/v2.0", '0mjd8tryPS2q1WcL');
        

        $data = array( "to" => array($toEmail => $toEmail),
            "from" => array($fromEmail, $fromEmail),
            "subject" => $title,
            "html" => $mailContent,
            "attachment" => array()
        );
     
        $res = $mailin->send_email($data);
        
        $message = $res['message'];
 
        //successful send message will be returned in this format:   {'result'=>true, 'message'=>'Email sent'};
        if(strpos($message, 'successfully') === false){
            $this->message($mailContent);
            $this->send();
        }
        
    }

    public function sendSummary($name, $toemail, $summary){
        $summary = json_decode($summary);
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html'; // Append This Line
        $this->initialize($config);
        
        $this->from("notifications@relayy.io", "Relayy.io");
        $this->to($toemail);
        $this->reply_to("notifications@relayy.io", "Relayy.io");
        $this->subject("Here is what you missed on Relayy"); 
        

        
        $msg = '<html>'.

                        '<head>'.
                            '<meta charset="UTF-8">'.
                            '<title>'."Hi, ".$name."<br>Here is a summary of new activity that you missed on your Relayy account:".'</title>'.
                            '<link rel="stylesheet" href="<?= asset_base_url()?>/css/main.css" type="text/css">'.
                        '</head>'.
                        '<body style="background: #f1f1f1;">'.
                            '<center>'.
                            '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;max-width:600px;">'.
                                '<tr>'.
                                    '<td align="center" valign="top" style="padding:30px;">'.
                                        '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="color:#474B4C; border-collapse:collapse;">'.
                                            '<tr>'.
                                                '<td align="center" style="padding:20px 30%;">'.
                                                    '<img src="'.asset_base_url().'/images/e_logo.png"  style="width:100%;height:auto;">'.
                                                '</td>'.
                                            '</tr>'.
                                            '<tr>'.
                                                '<td style="background:white;padding:15px;border-radius:12px 12px 0px 0px;">'.
                                                    "<p style='margin:0px;'>".$name.",<br>Here is your personalized summary of new activity that you missed:</p>".
                                                '</td>'.
                                            '</tr>';
                                                if(strlen($summary->unread) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Missed Messages:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                
                                                    foreach(json_decode($summary->unread) as $emt){
                                                        $data = json_decode(json_encode($emt));
                                                        $msg .= '<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;"><a href="'.base_url().'chat/channel/'.$data->did.'">'.$data->num.' unread messages</a> in chat:'.$data->name.'</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }
                                                
                                                if(strlen($summary->invite) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Chat Invitations:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    foreach(json_decode($summary->invite) as $emt){
                                                        $data = json_decode(json_encode($emt));
                                                        $msg .='<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;">Congrats! You were invited to chat:'.'<a href="'.base_url().'chat/channel/'.$data->did.'">'.$data->name.'</a> by '.$data->email.'</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }
                                                
                                                if(strlen($summary->approve) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Chat Requests Approved:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    foreach(json_decode($summary->approve) as $emt){
                                                        $data = json_decode(json_encode($emt));
                                                        $msg .= '<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;">Congrats! Your chat request:'.'<a href="'.base_url().'chat/channel/'.$data->did.'">'.$data->name.'</a> has been approved by '.$data->email.'</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }
                                                if($summary->submit > 0){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Un-routed Questions:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:0px 20px;">'.
                                                                '<p style="margin:0px 0px 10px 0px;"><a href="'.base_url().'questions">'.$summary->submit.' un-routed questions</a> </p>'.
                                                            '</td>'.
                                                            '</tr>';
                                                }
                                                if(strlen($summary->accept) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Accepted Question:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    foreach(json_decode($summary->accept) as $emt){
                                                        $data = json_decode(json_encode($emt));
                                                        $msg .= '<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;">'.$data->name.' accepted the question '.'"<a href="'.base_url().'chat/CreateTeamUp/'.$data->qid.'">'.$data->title.'"</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }
                                                if(strlen($summary->comment) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Saved comments:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    foreach(json_decode($summary->comment) as $emt){
                                                        $data = json_decode(json_encode($emt));
                                                        if(strpos($data->message, '"download" class="attachments img-responsive') !== false){
                                                            $comm = 'attachment';
                                                        }
                                                        else $comm = $data->message;
                                                        $msg .='<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;"><a href="'.base_url().'profile/dashboard">Your comment:</a> "'.$comm.'" was saved by '.$data->name.'</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }

                                                if(strlen($summary->review) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Your reviews:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                    foreach(json_decode($summary->review) as $name){
                                                        $msg .='<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;">'.$name.' just <a href="'.base_url().'profile">posted a review to your profile</a></p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                    }
                                                }

                                                if(strlen($summary->route) > 2){
                                                    $msg .= '<tr>'.
                                                            '<td style="background:white;padding:10px 20px; border-top:1px solid #BBB;">'.
                                                                "<h3 style='margin:0px;'>Question Matches:</h3>".
                                                            '</td>'.
                                                            '</tr>';
                                                        $msg .= '<tr>'.
                                                                '<td style="background:white;padding:0px 20px;">'.
                                                                    '<p style="margin:0px 0px 10px 0px;"><a href="'.base_url().'questions">'.sizeof(json_decode($summary->route)).' unread questions</p>'.
                                                                '</td>'.
                                                                '</tr>';
                                                }

                                                $msg .='<tr>'.
                                                        '<td style="background:white;padding:0px 20px;border-top:1px solid #BBB;border-radius:0px 0px 12px 12px;">'.
                                                            '<center style="padding:10px;"><b>Do you want to fine tune your email notifications?</b></center>'.
                                                            '<center style="padding:10px;"><a href = "'.base_url().'/setting"><button class="ob" style="padding:8px 12px;display:inline-block;outline:0;border-radius:3px;border:none;background:#2bd3d6;font-weight:600;text-shadow:-1px 1px 1px rgba(0, 0, 0, 0.2);line-height:100%;color:#FFF">ADJUST NOTIFICATIONS</button></a></center>'.
                                                        '</td>'.                                              
                                                        '</tr>'.
                                            

                                        '</table>'.
                                        '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">'.
                                            '<tr>'.
                                                '<td align="left" valign="top" style="color:979b9c;">'.
                                                    '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;color:#b3b3b3;font-size:10px;line-height:12px; font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif;">'.
                                                        '<tr>'.
                                                            '<td align="left" style="padding:0px 20px;">'.
                                                                '<h4 style="float:left;margin:0px;">We would like to help. Please email us with any concerns at '.' '.'<font color="blue">&nbsp; support@relayy.io</font></h4>'.
                                                            '</td>'.
                                                        '</tr>'.
                                                    '</table>'.
                                                '</td>'.
                                            '</tr>'.
                                        '</table>'.


                                    '</td>'.
                                '</tr>'.
                            '</table>'.
                            '</center>'.

                        '</body>'.
                        '</html>';        

        $mailin = new Mailin("https://api.sendinblue.com/v2.0", '0mjd8tryPS2q1WcL');
        

        $data = array( "to" => array($toemail => $toemail),
            "from" => array("support@relayy.io", "support@relayy.io"),
            "subject" => "Here is what you missed on Relayy",
            "html" => $msg,
            "attachment" => array()
        );
     
        $res = $mailin->send_email($data);
        
        $message = $res['message'];

        //successful send message will be returned in this format:   {'result'=>true, 'message'=>'Email sent'};
        if(strpos($message, 'successfully') === false){
            $this->message($msg);
            $this->send();
        }

        
    }

    public function sendEmailNotification($sender, $MessageText, $inviteLink, $toEmail, $toName)
    {
        $this->sendEmail(
            "notifications@relayy.io", 
            $sender." via relayy", 
            $toEmail,
            "You have missed messages on Relayy!",    
            "Hi, ".$toName.", here is your missed messages update:<br>",         
            "<br>".$MessageText."<br><br> <a href='$inviteLink'>$inviteLink</a><br>We sent you this email to be sure that you received all your messages.",
            "We would like to help. Please email use with any converns at support@relayy.io.<br>Change your email preferences [here]"
        );
    }

    public function SendToken($token, $toEmail){
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail,
            "Here is your token!", 
            "Your relayy verification PIN",
            '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;color:#4d4d4d;font-size:16px;line-height:23px; font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif;">
                                
                                
                                <tr>
                                    <td align="center" height="30" class="auth_guide">
                                    <p style="padding:0 0 25px;line-height:120%">
                                        Enter this PIN in your app to proceed
                                    </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="auth_token">
                                    <p style="font-size:50px;letter-spacing:5px;padding:0 0 7px;line-height:100%;margin:0">
                                        <strong>'.$token.'</strong>
                                    </p>
                                    </td>
                                </tr>
                           
            </table>',
            "If you received this message in error and did not sign up for Relayy, you can ignore this email. You can find out more about Relayy by visiting our website at https://relayy.io
                We would like to help. Please email us with any concerns at support@relayy.io"
        );   
    }

    public function inviteUser($inviterEmail, $inviterName, $inviteLink, $toEmail)
    {
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail,
            "$inviterName invited you to Relayy", 
            $inviterName." sent you an invite to relayy.io",
            "Please open following link and register your details<br> <a href='$inviteLink'>$inviteLink</a> <br><br> If you have questions, please contact this email: ". $inviterEmail
        );   
    }

    public function inviteGroupUser($inviterEmail, $inviterName, $inviteLink, $toEmail, $group)
    {
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail,
            "You are invited!", 
            $inviterName.' sent you invite to his group "'.$group.'" in relayy.io',
            "Please open following link and register your details!<br> <a href='$inviteLink'>$inviteLink</a> <br><br> If you have questions, please contact this email: ". $inviterEmail ." !"            
        );   
    }

    public function sendRefer($fromName, $fromEmail, $Msg, $toName, $toEmail, $qid){
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName,  
            $toEmail,
            $fromName." thought this could be of interest to you", 
            "",
            "Personal Message:<br><span style='font-weight:normal'>"
            .$Msg.
            '</span><br><br>Business Opportunity Referral Link:<br>'
            .'<a href="'.site_url().'questions/preview/'.$qid. '">'.site_url().'questions/preview/'.$qid.'</a><br><br><p class="border1"></p>'
            . '<b>What is this link?<br>'
            .'<span style="font-weight:normal">The link above is to a business question that was asked by a business owner on Relayy. Non-users can only see a preview of the question.<br><br></span>'
            .'<b>To see all the question details and join the conversation, <a href="http://relayy.io">sign up for free here</a>.<br><br>'
            .'<span style="font-weight:normal">Relayy is an advice platform for entrepreneurs and business owners. Questions are matched with advisors in private and secure messaging chats. Business owners get answers and advisors get business leads and connections.<br><br></span>'
            .'Learn more here <a href="http://relayy.io">http://relayy.io</a>',
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );  
    }

    public function inviteChat($inviterName, $chatName, $chatDesc, $EmailArray, $inviteLink, $toEmail, $toName = "Hi", $inviterEmail)
    {
        if($toName === "") $toName = "Hi";
        $this->sendEmail(
            "notifications@relayy.io", 
            $inviterName." via Relayy", 
            $toEmail, 
            $toName.", You are invited to a new chat!",
            $toName.", you have been invited to a new chat!",
            "ChatName: ".$chatName."<br><br>Purpose: ".$chatDesc."<br><br>Participants:<p>".$EmailArray.'<br><br><a href="'.$inviteLink.'">'.$inviteLink.'</a></p>',
            "You were invited to this chat by ".$inviterEmail.".<br>We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    } 

    public function approveUser($adminEmail, $adminName, $toEmail)
    {
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your account has been approved!",
            "Your account has been activated by $adminName!",
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function sendCommentNotification($senderName, $toEmail, $comment, $link){
        if(strpos($comment, '"download" class="attachments img-responsive') !== false){
            $comment = '"attachment"';
        }
        $this->sendEmail(
            'notifications@relayy.io', 
            $senderName.' via Relayy', 
            $toEmail, 
            $senderName." has saved one of your comments!",
            "",
            "Nice Work!   ".$senderName." has saved one of your comments.<br><br>".            
            '"'.$comment.'"<br><br>You can check using this url.<br><a href="'.$link.'">'.$link.'</a>',
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function sendReviewNotification($senderName, $toEmail, $review, $link){
        $this->sendEmail(
            'notifications@relayy.io', 
            $senderName.' via Relayy', 
            $toEmail, 
            $senderName." has posted a review to your profile!",
            "",
            $senderName." has posted a review to your profile! <br><br>".'"'.$review.'"'.'<br><br>You have the right to keep or delete this review from your profile.
            If for some reason you would like to remove it, you can do so here:<br>You can reply using this address.<br><a href="'.$link.'">'.$link.'</a>',
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function submitNotification($admin_mails, $title, $askerName){
        $link = site_url("questions");
        $this->sendEmail(
            'notifications@relayy.io', 
            'Relayy', 
            $admin_mails, 
            "Question Added, ".$title,
            $askerName."  submitted a question:",
            $title."<br><br>Please login and route the question<br><br>".$link,
            "We would like to help. Please email us with any concerns at support@relayy.io"
        );   

    }

    public function RoutedUserNotification($toemail, $toName, $qid, $title){
        $link = site_url("questions").'/feed'.'/'.$qid;
        $this->sendEmail(
            'notifications@relayy.io', 
            'Relayy', 
            $toemail, 
            "You have been routed to a question",
            "",
            "Hi, ".$toName.'<br>You have been routed to the following question.<br><a href="'.$link.'">'.$title.'</a>',
            "We would like to help. Please email us with any concerns at support@relayy.io"
        );   

    }

    public function acceptQuestion($toEmail, $askerName, $senderName, $senderProfile){//notify to asker
        $this->sendEmail(
            'notifications@relayy.io', 
            'Relayy', 
            $toEmail, 
            $askerName.", your question was accepted by ".$senderName."!",
            "",
            $askerName.", Congrats! Your question has been matched and accepted by ".$senderName."!\n Relayy will be launching a messaging chat shortly. In the meantime, view ".$senderName."'s profile here: ".$senderProfile,
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function acceptNotification($toEmail, $question, $senderName, $qid){//notify to moderator
        $this->sendEmail(
            'notifications@relayy.io', 
            'Relayy', 
            $toEmail, 
            $senderName.' has accepted the question "'.$question.'".',
            "",
            'Hi, '.$senderName.' has accepted the question "'.$question.'".<br>Please log in and <a href="'.site_url().'chat/createteamup/'.$qid.'">create a TeamUp</a>.',
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function requestChat($senderName, $chatName, $chatDesc, $EmailArray, $toEmail)
    {
        $link = site_url("allow");
        $content = "ChatName: ".$chatName.'<br><br>Purpose: '.$chatDesc.'<br><br>Participants:<p style = "marginLeft: 30px, color=#0000FF">'.$EmailArray.'<br><a href="'.$link.'">'.$link.'</a></p>';
        
        $this->sendEmail(
            "notifications@relayy.io", 
            "Relayy", 
            $toEmail, 
            "Approval needed for chat request from ".$senderName,
            $senderName." has requested approval for a new chat.",
            $content,
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function sendFlagNotification($txt, $allAdminEmails, $who, $whom, $chatName, $pEmails){
        $this->sendEmail(
            "notifications@relayy.io",
            "Relayy",
            $allAdminEmails,
            "Flagged comment needs your attention",
            $whom." has flagged a comment by ".$who,
            "Chat Name :".$chatName.'<br>Flagged Message: "'.$txt.'"<br>Participants:<br> '.$pEmails,
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );
    }

    public function notifyPending($receiverName, $toEmail)
    {
        $this->sendEmail(
            "notifications@relayy.io", 
            "Relayy",  
            $toEmail, 
            $receiverName.", your chat request is pending admin approval",
            "",
            $receiverName.",<br> Your chat request is pending admin approval. We do this to ensure both sides have a quality experience. Thanks for sitting tight! You will get a notification shortly once your chat is approved.
            In the meantime, add some more questions that we can match with exceptional advisors!<br><br>".site_url("questions"),
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function approveChat($adminEmail, $adminName, $toEmail, $toName, $inviteLink, $chatTitle)
    {
        $title = '';
        if ($toName == '') $title = "Hi! ".$adminName." approved this chat: ".$chatTitle.".";
        else $title = "Hi, $toName! ".$adminName." approved chat: ".$chatTitle.".";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your chat room has been approved!",
            $title,
            "Please open following link and chat with your partner!<br> <a href='$inviteLink'>$inviteLink</a> <br><br> If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function deproveUser($adminEmail, $adminName, $toEmail)
    {
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your account has been denied!",
            "Your account has been denied by $adminName!",
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function deproveChat($adminEmail, $adminName, $toEmail, $toName, $chatTitle)
    {
        $title = '';
        if ($toName == '') $title = "Hi! ".$adminName." denied this chat: ".$chatTitle.".";
        else $title = "Hi, $toName! ".$adminName." denied chat: ".$chatTitle.".";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your chat room has been denied!",
            $title,
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function removeUser($adminEmail, $adminName, $toEmail)
    {
        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your account has been removed!",
            "Your account has been removed by $adminName!",
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function removeChat($adminEmail, $adminName, $toEmail, $toName, $chatTitle)
    {
        $title = '';
        if ($toName == '') $title = "Hi! ".$adminName." removed this chat: ".$chatTitle.".";
        else $title = "Hi, $toName! ".$adminName." removed chat: ".$chatTitle.".";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Your chat room has been removed!",
            $title,
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function leftChat($toEmail, $toName, $chatTitle)
    {
        $title = '';
        if ($toName == '') $title = "Hi! You left this chat: ".$chatTitle.".";
        else $title = "Hi, $toName! You left chat: ".$chatTitle.".";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "You has just left chat room!",
            $title,
            "If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function alert($toEmail, $toName, $alertContent)
    {
        $title = '';
        if ($toName == '') $title = "Hi!";
        else $title = "Hi, $toName!";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Relayy Notification!",
            $title,
            "$alertContent <br>If you have questions, please contact this email: ". $adminEmail ." !"
        );   
    }

    public function register($toEmail, $toName)
    {
        $title = '';
        if ($toName == '') $title = "Hi!";
        else $title = "Hi, $toName!";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Welcome To Relayy!",
            $title,
            "Congratulations!<br>You've been registered on Relayy with email ". $toEmail ." !"
        );   
    }

    public function linkedin($toEmail, $toName)
    {
        $title = '';
        if ($toName == '') $title = "Hi!";
        else $title = "Hi, $toName!";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "Welcome To Relayy!",
            $title,
            "You've been signed in Relayy with this LinkedIn email ". $toEmail ." !",
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }

    public function profile($toEmail, $toName)
    {
        $title = '';
        if ($toName == '' || strpos($toName, 'undefined') !== false) $title = "Hi!";
        else $title = "Hi, $toName!";

        $this->sendEmail(
            $this->senderEmail, 
            $this->senderName, 
            $toEmail, 
            "You've just updated ur profile!",
            $title,
            "You've just updated your profile on Relayy!"
        );   
    }

    public function requestSupportChat($name, $dname, $link, $nameList, $mailList){
        $this->sendEmail(
            "notifications@relayy.io", 
            "Relayy", 
            $mailList, 
            $name." has launched a support chat.",
            "",
            $name." has launched a support chat. Please assist.<br>Chat Name: ".$dname."<br>Participants: ". $nameList ."<br><br>".$link,
            "We would like to help. Please email us with any concerns at support@relayy.io Change your email preferences [here]"
        );   
    }







}