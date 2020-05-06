<?php

include("connect.php");
$ctable = "labels";
// $old_status='';
// $status=0;

if(isset($_REQUEST['id']) && $_REQUEST['id']!="")
{	
	$old_status=$db->rpgetValue($ctable,"status"," isDelete= 0 AND labels_id=".$_REQUEST['id']);

	if($old_status=='0' || $old_status=='2')
	{
		$status='1';
	}
	else if($old_status=='1')
	{
		$status='2';
	}
	else
	{
		$status="";
	}

	$rows 	= array(
		"status"=> $status
	);

	$where	= " isDelete= 0 AND labels_id='".$_REQUEST['id']."' ";
	$db->rpupdate($ctable ,$rows,$where);	
}

?>