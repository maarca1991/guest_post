<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']))
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

if(isset($_REQUEST['submit']))
{
	$id			= $db->clean($_REQUEST['id']);
	$url_id		= $db->clean($_REQUEST['url_id']);
	if(isset($_REQUEST['status'])){ $status = 1; }else{ $status = 2; }

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{
		$array = array(
			"status" 	=> $status,
			"seoEditDate" => date('Y-m-d H:i:s')
		);
		$where1 = " isDelete=0 AND id=".$id;
		$db->rpupdate("url_link",$array,$where1);

		$vendor_r = $db->rpgetData("url_link","*"," id=".$id);
		$vendor_d = @mysqli_fetch_array($vendor_r);
		$vendor_id 		= $vendor_d['vendor_id'];
		$vendor_team_id = $vendor_d['vendor_team_id'];
		$link 			= $vendor_d['link'];

		if($vendor_d['status'] == 1){
			$status = APPROVED;
		}if($vendor_d['status'] == 2){
			$status = REJECTED;
		}

		$row5 = array(
			"to_id" 		=> $vendor_id,
			"from_id"		=> $_SESSION[SESS_PRE.'_SEO_SESS_ID'],
			"subject_name"	=> "Approval From seo side",
			"description"	=> "You have received approval response from seo.",
			"link"			=> "view-url-link/view/".$id
		);
		$db->minsert("notification",$row5);
		if(ISMAIL)
		{
			$vendor_name = $db->rpgetValue("admin","name"," id=".$vendor_id);
			$nt = new Notification();
			include("../mailbody/seo_to_vendor_forapproval.php");
			$subject	= SITETITLE." Added new link";
			$nt->rpsendEmail($vendor_id,$subject,$body);
		}

		if($vendor_team_id != null){
			//Send to vendor team member
			$row5 = array(
				"to_id" 		=> $vendor_team_id,
				"from_id"		=> $_SESSION[SESS_PRE.'_SEO_SESS_ID'],
				"subject_name"	=> "Approval From client side",
				"description"	=> "You have received approval response from client.",
				"link"			=> "view-url-link/view/".$id
			);
			$db->minsert("notification",$row5);
			if(ISMAIL)
			{
				$vendor_team_name = $db->rpgetValue("admin","name"," id=".$vendor_team_id);
				$nt = new Notification();
				include("../mailbody/seo_to_vendor_team_forapproval.php");
				$subject	= SITETITLE." Added new link";
				$nt->rpsendEmail($vendor_team_id,$subject,$body);
			}
		}
	}
	$_SESSION['MSG'] = "Updated";
	$db->rplocation(ADMINURL."manage-seo-url-link/".$url_id);
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit" || $_REQUEST['mode']=="view")
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
								<?php if($status != 0){ ?>
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
								<?php } ?>
							</div><br/>
							<?php if($status == 0){ ?>
							<div class="box-borderd">
								<label class="box-borderd-label">Approved/Rejected</label>
								<div class="row">
									<div class="col-md-12">
										<p class="btn-success" style="pointer-events: none; padding-left: 5px;"><strong>NOTE : </strong> Once you submit, status will changed and you will not edit again.</p>
									</div>
									<div class="col-sm-12">
										<label class="switch">
											<input type="checkbox" name="status" id="switch<?php echo $_REQUEST['id'];  ?>" class="changeStatus" <?=$check?>>
											<span class="slider round">
										</label>
									</div>
								</div>
							</div><br/>
							<?php } ?>
							<div class="box-footer">
								<?php if($status == 0){ ?>
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<?php } ?>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-seo-url-link/<?php echo $url_id; ?>'">Back</button>
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