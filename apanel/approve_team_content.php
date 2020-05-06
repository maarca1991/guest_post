<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "link_content";
$ctable1 		= "Link Content";
$main_page 		= "Link Content";
$page 			= "add_".$ctable;
$page_title 	= " View Link Content Details";

if(isset($_REQUEST['submit']))
{
	$id			= $db->clean($_REQUEST['id']);
	$link_id	= $db->clean($_REQUEST['link_id']);

	if(isset($_REQUEST['status'])){ $status = 1; }else{ $status = 2; }

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{
		$array = array(
			"content_team_id"		=> $_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID'],
			"status" 				=> $status,
			"contentTeamEditDate" 	=> date('Y-m-d H:i:s')
		);
		$where1 = " isDelete=0 AND id=".$id;
		$db->rpupdate("link_content",$array,$where1);

		$vendor_r = $db->rpgetData("link_content","*"," id=".$id);
		$vendor_d = @mysqli_fetch_array($vendor_r);
		$vendor_id 		= $vendor_d['vendor_id'];
		$vendor_team_id = $vendor_d['vendor_team_id'];
		$link 			= $vendor_d['link'];
		if($vendor_d['status'] == 1){
			$status = APPROVED;
		}if($vendor_d['status'] == 2){
			$status = REJECTED;
		}

		// if($vendor_id != null){
		// 	//Send to vendor 			
		// 	$row5 = array(
		// 		"to_id" 		=> $vendor_id,
		// 		"from_id"		=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
		// 		"url_id"		=> $url_id,
		// 		"subject_name"	=> "Approval From client side",
		// 		"description"	=> "You have received approval response from client.",
		// 		"link"			=> "view-vendor-url-link"
		// 	);
		// 	$db->minsert("notification",$row5);
		// 	if(ISMAIL)
		// 	{
		// 		$vendor_name = $db->rpgetValue("admin","name"," id=".$vendor_id);
		// 		$nt = new Notification();
		// 		include("../mailbody/client_to_vendor_forapproval.php");
		// 		$subject	= SITETITLE." Added new link";
		// 		$nt->rpsendEmail($vendor_id,$subject,$body);
		// 	}
		// }

		// if($vendor_team_id != null){
		// 	//Send to vendor team member
		// 	$row5 = array(
		// 		"to_id" 		=> $vendor_team_id,
		// 		"from_id"		=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
		// 		"url_id"		=> $url_id,
		// 		"subject_name"	=> "Approval From client side",
		// 		"description"	=> "You have received approval response from client.",
		// 		"link"			=> "view-vendor-url-link"
		// 	);
		// 	$db->minsert("notification",$row5);
		// 	if(ISMAIL)
		// 	{
		// 		$vendor_team_name = $db->rpgetValue("admin","name"," id=".$vendor_team_id);
		// 		$nt = new Notification();
		// 		include("../mailbody/client_to_vendor_team_forapproval.php");
		// 		$subject	= SITETITLE." Added new link";
		// 		$nt->rpsendEmail($vendor_team_id,$subject,$body);
		// 	}
			
		// 	//Send to vendor 			
		// 	$vendor_id = $db->rpgetValue("admin","vendor_id","id=".$vendor_team_id);
		// 	$row5 = array(
		// 		"to_id" 		=> $vendor_id,
		// 		"from_id"		=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
		// 		"url_id"		=> $url_id,
		// 		"subject_name"	=> "Approval From client side",
		// 		"description"	=> "You have received approval response from client.",
		// 		"link"			=> "view-vendor-url-link"
		// 	);
		// 	$db->minsert("notification",$row5);
		// 	if(ISMAIL)
		// 	{
		// 		$vendor_name = $db->rpgetValue("admin","name"," id=".$vendor_id);
		// 		$nt = new Notification();
		// 		include("../mailbody/client_to_vendor_forapproval.php");
		// 		$subject	= SITETITLE." Added new link";
		// 		$nt->rpsendEmail($vendor_id,$subject,$body);
		// 	}
		// }
	}
	$_SESSION['MSG'] = "Updated";
	$db->rplocation(ADMINURL."manage-team-content/");
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit" || $_REQUEST['mode']=="view")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

	$link_id			= stripslashes($ctable_d['link_id']);
	$content			= stripslashes($ctable_d['content']);
	$anchor_text		= stripslashes($ctable_d['anchor_text']);
	$status		 		= stripslashes($ctable_d['status']);

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
						<input type="hidden" name="link_id" id="link_id" value="<?php echo $link_id; ?>">
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
								<?php if($_REQUEST['mode'] == 'edit'){ ?>
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<?php } ?>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-team-content/'">Back</button>
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