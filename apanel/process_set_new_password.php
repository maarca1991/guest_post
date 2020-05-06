<?php
include("connect.php");
$id 		= $db->clean($_POST['id']);
$slug 		= $db->clean($_POST['slug']);
$newpass 	= $db->clean($_POST['newpass']);

if($id != '' && $slug!="" && $newpass!="")
{
	$check_user = $db->rpgetData("admin","*","md5(id) = '".$id."' AND forgot_pass_string='".$slug."'");
	if(@mysqli_num_rows($check_user) > 0)
	{
		$rows 	= array(
						"password"			=> md5($newpass),
						"forgot_pass_string" =>"0"
					);
		$db->rpupdate("admin",$rows,"md5(id)='".$id."'");
		
		$_SESSION['MSG'] = 'Update_Pass';
		$db->rplocation(ADMINURL);
		
	}
	else
	{
		$_SESSION['MSG'] = 'Link_Expired';
		$db->rplocation(ADMINURL."forgot-password/");
	}
}
else
{
	$_SESSION['MSG'] = "INVALID_DATA";
	$db->rplocation(ADMINURL."set-new-password/".$id."/".$slug."/");
	exit;
}
?>