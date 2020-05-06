<?php
	include("connect.php");
	$ctable  = $_REQUEST['hdndb'];
	$hdnmode = $_REQUEST['hdnmode'];
	
	if( $hdnmode == 'delete')
	{
		$ids = implode(',', $_REQUEST['chkid']);
		$rows = array('isDelete' => 1);
		$db->rpupdate($ctable, $rows, 'id IN (' . $ids . ')');
		$_SESSION['MSG'] = "Deleted";
	}

?>