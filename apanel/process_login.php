<?php

include("connect.php");
$last_login = date('Y-m-d H:i:s');
$last_ip 	= $db->rpget_client_ip();
$email 		= $db->clean($_POST['email']);
$password 	= $db->clean($_POST['password']);

if($email!="" && $password!="" && !filter_var($email, FILTER_VALIDATE_EMAIL) === false)
{
	$check_user_r = $db->rpgetData(CTABLE_ADMIN,"*","email = '".$email."' AND  password = '".md5($password)."' AND isDelete=0");
	if(@mysqli_num_rows($check_user_r)>0)
	{
		$check_user_d 	= @mysqli_fetch_array($check_user_r);
		
		if($check_user_d['active_account'] == 1)
		{
			$id 			=  $check_user_d['id'];
			$name 			=  $check_user_d['name'];
			
			$_SESSION[SESS_PRE.'_ROLE_SESS_ID'] = $check_user_d['role'];
			
			if($check_user_d['role'] == 0)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME'] 				= 	$name;
				$_SESSION[SESS_PRE.'_ADMIN_SESS_ID'] 		= 	$id;
				$_SESSION[SESS_PRE.'_ADMIN_SESS_NAME'] 		= 	$name;
			}
			if($check_user_d['role'] == 1)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME'] 			 	= 	$name;
				$_SESSION[SESS_PRE.'_VENDOR_SESS_ID'] 		= 	$id;
				$_SESSION[SESS_PRE.'_VENDOR_SESS_NAME'] 	= 	$name;
			}
			if($check_user_d['role'] == 2)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME'] 				= 	$name;
				$_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'] 	= 	$id;
				$_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_NAME']= 	$name;
			}
			if($check_user_d['role'] == 3)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME']					= 	$name;
				$_SESSION[SESS_PRE.'_SEO_SESS_ID'] 			= 	$id;
				$_SESSION[SESS_PRE.'_SEO_SESS_NAME']		= 	$name;
			}
			if($check_user_d['role'] == 4)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME']					= 	$name;
				$_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID'] 	= 	$id;
				$_SESSION[SESS_PRE.'_SEO_TEAM_SESS_NAME']	= 	$name;
			}
			if($check_user_d['role'] == 6)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME']					= 	$name;
				$_SESSION[SESS_PRE.'_CONTENT_SESS_ID'] 		= 	$id;
				$_SESSION[SESS_PRE.'_CONTENT_SESS_NAME']	= 	$name;
			}
			if($check_user_d['role'] == 7)
			{
				$_SESSION[SESS_PRE.'_ID'] 					= 	$id;
				$_SESSION[SESS_PRE.'_NAME']					= 	$name;
				$_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID'] = 	$id;
				$_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_NAME']= 	$name;
			}
			if($check_user_d['role'] == 5)
			{
				$_SESSION[SESS_PRE.'_ID'] 				= 	$id;
				$_SESSION[SESS_PRE.'_NAME']				= 	$name;
				$_SESSION[SESS_PRE.'_CLIENT_SESS_ID'] 	= 	$id;
				$_SESSION[SESS_PRE.'_CLIENT_SESS_NAME']	= 	$name;
			}

			$rows 	= array("last_login"=>$last_login,"last_ip"=>$last_ip);
			$where	= "id='".$id."'";
			$db->rpupdate(CTABLE_ADMIN,$rows,$where);
			
			if(isset($_REQUEST['from']) && $_REQUEST['from']!="")
			{
				$db->rplocation($_REQUEST['from']);
				exit;
			}
			else
			{
				$db->rplocation(ADMINURL."dashboard/");
				exit;
			}
		}
		else{
			$_SESSION['MSG'] = 'Activate_account';
			$db->rplocation(ADMINURL);
		}
	}
	else
	{
		$_SESSION['MSG'] = 'Invalid_Email_Password';
		$db->rplocation(ADMINURL);
	}
}
else
{
	$_SESSION['MSG'] = 'Something_Wrong';
	$db->rplocation(ADMINURL);
}
?>