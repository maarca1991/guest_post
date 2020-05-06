<?php 
	if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'pc-3'){

	    $Protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' || $_SERVER['SERVER_PORT']==443) ? "http://" : "http://";
		
	    $SITEURL = $Protocol.$_SERVER['HTTP_HOST']."/project/guest_post/";
	    $ADMINURL = $Protocol.$_SERVER['HTTP_HOST']."/project/guest_post/apanel/";
	}
	else 
	{
	    $SITEURL = "http://devlopix.com/projects/guest_post/";
	    $ADMINURL = "http://devlopix.com/projects/guest_post/apanel/";
	}       
	
define('SITEURL', $SITEURL);

define('ADMINURL', $ADMINURL);

define('SITENAME','Guest Post Management');

define('SITETITLE','Guest Post Management');

define('SITENUMBER','123-456-7890');

// define('SITEEMAIL','support@writelikesamurai.com');
define('SITEEMAIL','support@yourdomain.com');

define('ADMINTITLE','Guest Post Management');
			
define('CURR','&dollar;');				
?>