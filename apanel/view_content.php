<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "link_content";
$ctable1 		= "Link Content";
$main_page 		= "Link Content";
$page 			= "add_".$ctable;
$page_title 	= " View Content Details";

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="view")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

	$link_id		= stripslashes($ctable_d['link_id']);
	$content		= stripslashes($ctable_d['content']);
	$anchor_text	= stripslashes($ctable_d['anchor_text']);
	$status		 	= stripslashes($ctable_d['status']);
	$adate 	 		= stripslashes($ctable_d['adate']);
	$isDelete	 	= stripslashes($ctable_d['isDelete	']);

	if($ctable_d['status'] == 1 )
	{
		$check='checked';
	}
	else
	{
		$check='';
	}
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
						<input type="hidden" name="url_id" id="url_id" value="<?php echo $url_id; ?>">
						<div class="box-body">
							<div class="box-borderd">
								<label class="box-borderd-label">Content Information</label>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Content : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $content; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Anchor Text : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $anchor_text; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Status: </label>	
										</div>
										<div class="col-sm-9">
											<?php 
											if($status == 1){
												echo APPROVED;
											}else{
												echo REJECTED;
											}
											?>	
										</div>
									</div>
								</div>
							</div><br/>
							<?php 
							if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
								$back_link = "manage-vendor-content/".$link_id."/";
							}
							if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
								$back_link = "manage-vendor-team-content/".$link_id."/";
							}
							?>
							<div class="box-footer">
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL.$back_link.$url_id ?>'">Back</button>
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
</body>
</html>