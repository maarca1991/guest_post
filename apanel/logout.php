<?php
include("connect.php");
unset($_SESSION[SESS_PRE.'_ROLE_SESS_ID'] );
unset($_SESSION[SESS_PRE.'_ID']);
unset($_SESSION[SESS_PRE.'_NAME']);

if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_ADMIN_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_VENDOR_SESS_NAME']);	
}
if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_SEO_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_SEO_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_CONTENT_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_NAME']);
}
if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	unset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']);
	unset($_SESSION[SESS_PRE.'_CLIENT_SESS_NAME']);
}

$db->rplocation(ADMINURL);
exit;

?>