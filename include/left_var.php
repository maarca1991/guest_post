<?php			

if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID'] ))
{
	$left_pages_array0 = array(
		"0"=>array("Manage Client", "Client", "manage-client/"),
	);
	$left_pages_array1 = array(
		"0"=>array("Manage Vendor Head", "Vendor Head", "manage-vendor/"),
		"1"=>array("Manage Vendor Team", "Vendor Team", "manage-vendor-team/")
	);
	$left_pages_array2 = array(
		"0"=>array("Manage SEO Head", "SEO Head", "manage-seo/"),
		"1"=>array("Manage SEO Team", "Vendor SEO", "manage-seo-team/")
	);
	$left_pages_array3 = array(
		"0"=>array("Manage Content Head", "Content Head", "manage-content/"),
		"1"=>array("Manage Content Team", "Content SEO", "manage-content-team/")
	);
	
	$left_pages_array4 = array(
		"0"	=>array("Manage Url", "Url", "manage-url/"),
		"1"	=>array("Manage Category", "Url", "manage-category/")
	);

	$left_head_array = array(
		0	=>array("Client", "Client" ,$left_pages_array0, "fa fa-user"),
		1	=>array("Vendor", "vendor" ,$left_pages_array1, "fa fa-user"),
		2	=>array("SEO", "SEO" ,$left_pages_array2, "fa fa-user"),
		3	=>array("Content", "Content" ,$left_pages_array3, "fa fa-user"),
		4	=>array("Url", "Url" ,$left_pages_array4, "fa fa-link"),
	);

	$left_main_array 	= array(
		0	=>	$left_head_array[0],
		1	=>	$left_head_array[1],
		2	=>	$left_head_array[2],
		3	=>	$left_head_array[3],
		4	=>	$left_head_array[4],
	);
}
if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"=>array("Manage Vendor Team", "Vendor Team", "manage-vendor-team/"),
	);
	$left_pages_array1 = array(
		"0"=>array("Manage Url", "Url", "manage-vendor-url/"),
	);

	$left_head_array = array(
		0	=>array("Vendor", "vendor" ,$left_pages_array0, "fa fa-user"),
		1	=>array("Url", "url" ,$left_pages_array1, "fa fa-link"),
	);

	$left_main_array = array(
		0	=>	$left_head_array[0],
		1	=>	$left_head_array[1],
	);
}
if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"=>array("Manage Url", "Url", "manage-vendor-team-url/"),
	);

	$left_head_array = array(
		0	=>array("Url", "url" ,$left_pages_array0, "fa fa-link"),
	);

	$left_main_array = array(
		0	=>	$left_head_array[0],
	);
}
if(isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"=>array("Manage SEO Team", "SEO Team", "manage-seo-team/")
	);
	$left_pages_array1 = array(
		"0"	=>array("Manage Url", "Url", "manage-seo-url/"),
	);

	$left_head_array = array(
		0	=>array("SEO", "SEO" ,$left_pages_array0, "fa fa-user"),
		1	=>array("Url", "Url" ,$left_pages_array1, "fa fa-link"),
	);

	$left_main_array = array(
		0	=>	$left_head_array[0],
		1	=>	$left_head_array[1],
	);
}
if(isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"	=>array("Manage Url", "Url", "manage-seo-team-url/"),
	);

	$left_head_array = array(
		0	=>array("Url", "Url" ,$left_pages_array0, "fa fa-link"),
	);

	$left_main_array = array(
		0	=>	$left_head_array[0]
	);
}
if(isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"=>array("Manage Content Team", "Content Team", "manage-content-team/")
	);

	$left_pages_array1 = array(
		"0"=>array("Manage Content", "Content", "manage-head-content/")
	);

	$left_head_array = array(
		0	=>array("Content Team", "Content" ,$left_pages_array0, "fa fa-user"),
		1	=>array("Content", "Content" ,$left_pages_array1, "fa fa-list")
	);

	$left_main_array = array(
		0	=>	$left_head_array[0],
		1	=>	$left_head_array[1],
	);
}
if(isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"=>array("Manage Content", "Content", "manage-team-content/")
	);

	$left_head_array = array(
		0	=>array("Content", "Content" ,$left_pages_array0, "fa fa-list")
	);

	$left_main_array = array(
		0	=>	$left_head_array[0]
	);
}

if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	$left_pages_array0 = array(
		"0"	=>array("Manage Url", "Url", "manage-client-url/"),
	);

	$left_pages_array1 = array(
		"0"	=>array("Manage Black List", "Url", "manage-black-list/"),
	);

	$left_head_array = array(
		0	=>array("Url", "Url" ,$left_pages_array0, "fa fa-link"),
		1	=>array("Black List", "Black List" ,$left_pages_array1, "fa fa-exclamation-triangle"),
	);

	$left_main_array = array(
		0	=>	$left_head_array[0],
		1	=>	$left_head_array[1],
	);
}

?>