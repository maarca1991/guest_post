<?php
include("class.phpmailer.php");
class Notification extends Functions
{
	/*
		*** Notification Function Developed By Ravi Patel :) <<<
	*/
	private $mailer2;
	
	public function rpsendEmail($toemail,$subject="",$body="") // Common Email Function
    {
    	
		$from_name =SITENAME;
		$from_mail =SITEMAIL;
		$this->mailer2 = new PHPMailer();
		$this->mailer2->SetFrom($from_mail,$from_name); // From email ID and from name
		$this->mailer2->AddAddress($toemail);
		$this->mailer2->Subject = $subject;
		$this->mailer2->MsgHTML($body);
		//echo "<pre>";print_r($this->mailer2);exit;
		$this->mailer2->Send();
    }
	public function rpsendEmail2($toemail,$email,$subject="",$body="") // Common Email Function
    {
    	
		$from_name =SITENAME;
		$from_mail =SITEMAIL;
		$this->mailer2 = new PHPMailer();
		$this->mailer2->SetFrom($from_mail); // From email ID and from name
		$this->mailer2->AddAddress($toemail,$email);
		$this->mailer2->Subject = $subject;
		$this->mailer2->MsgHTML($body);
		//echo "<pre>";print_r($this->mailer2);exit;
		$this->mailer2->Send();
    }
	
	public function sendEmailAttachment($toemail,$subject="",$body="",$attch = "") // Common Email Function
    {
    	
		$from_name = SITENAME;
		$from_mail = SITEMAIL;
		$this->mailer2 = new PHPMailer();
		$this->mailer2->SetFrom($from_mail,$from_name); // From email ID and from name
		$this->mailer2->AddAddress($toemail);
		$this->mailer2->Subject = $subject;
		$this->mailer2->MsgHTML($body);
		$this->mailer2->addAttachment($attch);
		//$this->mailer2->AddCC($dis_cc);
		//echo "<pre>";print_r($this->mailer2);exit;
		$this->mailer2->Send();
    }
	
	public function rpJobNotification($jid,$prop_id=0,$from_uid,$to_uid,$ntype){
		//$to_uid		= Functions::rpgetValue("job","uid","id='".$jid."'");
		//$from_uid	= $_SESSION[SESS_PRE.'_SESS_USER_ID'];
		$adate		= date("Y-m-d H:i:s");
		$rows 	= array(
					"job_id",
					"prop_id",
					"from_uid",
					"to_uid",
					"notification",
					"ntype",
					"adate",
				);
		$values = array(
					$jid,
					$prop_id,
					$from_uid,
					$to_uid,
					$ntype,
					"JOB",
					$adate,
				);
		Functions::rpinsert("notification",$values,$rows);
	}
	public function rpServiceNotification($sid,$oid,$from_uid,$to_uid,$ntype){
		//$to_uid		= Functions::rpgetValue("job","uid","id='".$jid."'");
		//$from_uid	= $_SESSION[SESS_PRE.'_SESS_USER_ID'];
		$adate		= date("Y-m-d H:i:s");
		$rows 	= array(
					"sid",
					"oid",
					"from_uid",
					"to_uid",
					"notification",
					"ntype",
					"adate",
				);
		$values = array(
					$sid,
					$oid,
					$from_uid,
					$to_uid,
					$ntype,
					"SERVICE",
					$adate,
				);
		Functions::rpinsert("notification",$values,$rows);
	}
};
/*
	*** Notification Function Developed By Ravi Patel :) <<<
*/
?>