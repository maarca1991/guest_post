<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url_link";
$ctable1 		= "Url Link";
$main_page 		= "Url Link";
$page 			= "add_".$ctable;
$page_title 	= " View Link Details";

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="view")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

	$url_id				= stripslashes($ctable_d['url_id']);
	$link				= stripslashes($ctable_d['link']);
	$da					= stripslashes($ctable_d['da']);
	$tf					= stripslashes($ctable_d['tf']);
	$dr					= stripslashes($ctable_d['dr']);
	$ttf				= stripslashes($ctable_d['ttf']);
	$monthly_traffic	= stripslashes($ctable_d['tf_min']);
	$ahrefs_rank		= stripslashes($ctable_d['ahrefs_rank']);
	$spam_score			= stripslashes($ctable_d['spam_score']);
	$rank_brand 		= stripslashes($ctable_d['rank_brand']);
	$status		 		= stripslashes($ctable_d['status']);
	$vendorEditDate		= stripslashes($ctable_d['vendorEditDate']);
	$clientEditDate 	= stripslashes($ctable_d['clientEditDate']);
	$adate 	 			= stripslashes($ctable_d['adate']);
	$updatedDate 	 	= stripslashes($ctable_d['updatedDate']);
	$isDelete	 	 	= stripslashes($ctable_d['isDelete	']);

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
								<label class="box-borderd-label">Link Information</label>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Website url : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $db->rpgetValue("url","url"," isDelete=0 AND id=".$url_id); ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Link : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $link; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> DA : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $da; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> TF: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $tf; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> DR: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $dr; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> TTF: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $ttf; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> SEMrush Traffic: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $monthly_traffic; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Ahrefs Rank: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $ahrefs_rank; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Spam Score: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $spam_score; ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Rank For Brand Name: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $rank_brand; ?>	
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
								$back_link = "manage-vendor-url-link/";
							}
							if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
								$back_link = "manage-vendor-team-url-link/";
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