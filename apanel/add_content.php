<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']) )
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "link_content";
$ctable1 		= "Link Content";
$main_page 		= "Link Content";
$page 			= "add_".$ctable;
$page_title 	= ucwords($_REQUEST['mode'])." ".$ctable1;

if($_REQUEST['mode'] == "add"){
	$link_id 		= $_REQUEST['id'];
}else{
	$link_id = "";
}
$content 		= "";
$anchor_text 	= "";

if(isset($_REQUEST['submit']))
{
	$content			= $db->clean($_REQUEST['content']);
	$anchor_text	 	= $db->clean($_REQUEST['anchor_text']);

	if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
	{
		$rows = array(
			"link_id"		=> $link_id,
			"vendor_id"		=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
			"content"		=> $content,
			"anchor_text"	=> $anchor_text
		);
	}
	if (isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])) {
		$rows = array(
			"link_id"		=> $link_id,
			"vendor_team_id"=> $_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'],
			"content"		=> $content,
			"anchor_text"	=> $anchor_text
		);
	}

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{	
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);
		
		$_SESSION['MSG'] = "Updated";
		if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
			$db->rplocation(ADMINURL."manage-vendor-content/".$_REQUEST['id']."/");
		}
		if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
			$db->rplocation(ADMINURL."manage-vendor-team-content/".$_REQUEST['id']."/");	
		}
		exit;
	}
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="add")
	{
		$content_id = $db->minsert($ctable,$rows);

		$_SESSION['MSG'] = "Inserted";
		if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
			$db->rplocation(ADMINURL."manage-vendor-content/".$_REQUEST['id']."/");
		}
		if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
			$db->rplocation(ADMINURL."manage-vendor-team-content/".$_REQUEST['id']."/");	
		}
		exit;
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);

	$link_id   		= stripslashes($ctable_d['link_id']);
	$content   		= stripslashes($ctable_d['content']);
	$anchor_text	= stripslashes($ctable_d['anchor_text']);
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="delete")
{
	$id 	= $_REQUEST['id'];
	$rows 	= array("isDelete" => "1");
	
	$db->rpupdate($ctable,$rows,"id='".$id."'");
	
	$_SESSION['MSG'] = "Deleted";
	if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
		$db->rplocation(ADMINURL."manage-vendor-content/".$_REQUEST['id']."/");
	}
	if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
		$db->rplocation(ADMINURL."manage-vendor-team-content/".$_REQUEST['id']."/");	
	}
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title?> | <?php echo ADMINTITLE; ?></title>
  <?php include('include_css.php'); ?>
  <link href="<?php echo ADMINURL?>assets/crop/css/demo.html5imageupload.css?v1.3" rel="stylesheet">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<header class="main-header">
		<?php include("header.php"); ?>
	</header>
	<?php include("left.php"); ?>
	
	<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $page_title?></h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo ADMINURL?>dashboard/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><?php echo $page_title?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
					</div>
					<form role="form" name="frm" id="frm" action="." method="post" enctype="multipart/form-data">
						<input type="hidden" name="mode" id="mode" value="<?php echo $_REQUEST['mode']; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>">
						<div class="box-body">
							<div class="row">
								<div class="col-md-6">
									<label for="content"> Content <code>*</code></label>
									<input type="text" class="form-control" id="content" name="content" value="<?php echo $content; ?>">
								</div>
								<div class="col-md-6">
									<label for="anchor_text"> Anchor Text <code>*</code></label>
									<input type="text" class="form-control" id="anchor_text" name="anchor_text" value="<?php echo $anchor_text; ?>" >
								</div>
							</div><br/>
	
							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<?php 
								if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
									if($_REQUEST['mode'] == "add"){
										$back_link = "manage-vendor-content/".$_REQUEST['id'];
									}else{
										$back_link = "manage-vendor-content/".$link_id;
									}
								}if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
									$back_link = "manage-vendor-team-content/".$_REQUEST['id'];
								}
								?>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL.$back_link ?>'">Back</button>
							</div>
						</div>
					</form>
				</div>
			</div>
	  </div>
	</section>
  </div>
	<?php include("footer.php"); ?>
	<div class="control-sidebar-bg"></div>
</div>
<?php include('include_js.php'); ?>
<script>
	
	$(function(){
		$("#frm").validate({
			ignore: "",
			rules: {
				content:{required:true},
				anchor_text:{required:true}
			},
			messages: {
				content:{required:"Please enter content."},
				anchor_text:{required:"Please enter email."}
			}
		});
	});

</script>
</body>
</html>
