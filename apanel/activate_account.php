<?php 
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_ID']))
{
	$db->rpcheckAdminNotLogin();
}else{
	$db->rpcheckAdminLogin();
}

$activation_code = $db->clean($_REQUEST['activation_code']);
$id = $db->clean($_REQUEST['vid']);

if($activation_code!="" && $id!="")
{
	$check_dup = $db->rpgetData("admin",'id',"confirmation_string = '".$activation_code."' AND isDelete=0");

	if(@mysqli_num_rows($check_dup) < 0)
	{
		$_SESSION['MSG'] = "Something_Wrong";
		$db->rplocation(ADMINURL);
		exit;
	}
	else
	{
		$ctable_d = @mysqli_fetch_array($check_dup);
		$where = "confirmation_string='".$activation_code."' AND isDelete=0";
		$row = array(
			"active_account" => 1
		);
		$check = $db->rpupdate('admin',$row,$where);

		if($check > 0)
		{
			$_SESSION['MSG'] = "Activate_account_success";
			$db->rplocation(ADMINURL);
			exit;
		}
	}
}
else
{
	$_SESSION['MSG'] = "Something_Wrong";
	$db->rplocation(ADMINURL);
	exit;
}

?>