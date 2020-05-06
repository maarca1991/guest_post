<?php
	include('connect.php');

	$mode = $_REQUEST['mode'];

	if( $mode == 'backlink' )
	{
		$id 	= $_REQUEST['id'];
		$rows 	= array("isDelete" => "1");
		
		$db->rpupdate('backlink', $rows, 'id='.$id);
		exit;
	}
	if( $mode == 'urlLink' )
	{
		$id 	= $_REQUEST['id'];
		$rows 	= array("isDelete" => "1");
		
		$db->rpupdate('url_link', $rows, 'id='.$id);
		exit;
	}
	
?>