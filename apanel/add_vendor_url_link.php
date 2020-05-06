<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url_link";
$ctable1 		= "Url Link";
$main_page 		= "Url Link";
$page 			= "add_".$ctable;
$page_title 	= ucwords($_REQUEST['mode'])." ".$ctable1;

$url_id 			= $_REQUEST['id'];
$link 				= "";
$da 				= "";
$tf 				= "";
$dr 				= "";
$ttf 				= "";
$monthly_traffic 	= "";
$ahrefs_rank 		= "";
$spam_score 		= "";
$rank_brand 		= "";

if(isset($_REQUEST['submit']))
{
	$url_id				= $db->clean($_REQUEST['url_id']);
	$link				= $db->clean($_REQUEST['link']);
	$da					= $db->clean($_REQUEST['da']);
	$tf	 				= $db->clean($_REQUEST['tf']);
	$dr	 				= $db->clean($_REQUEST['dr']);
	$ttf	 			= $db->clean($_REQUEST['ttf']);
	$monthly_traffic	= $db->clean($_REQUEST['monthly_traffic']);
	$ahrefs_rank	 	= $db->clean($_REQUEST['ahrefs_rank']);
	$spam_score	 		= $db->clean($_REQUEST['spam_score']);
	$rank_brand	 		= $db->clean($_REQUEST['rank_brand']);
	
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{
		$rows 	= array(
			"link"				=> $link,
			"da"				=> $da,
			"tf"				=> $tf,
			"dr"				=> $dr,
			"ttf"				=> $ttf,
			"monthly_traffic"	=> $monthly_traffic,
			"ahrefs_rank"		=> $ahrefs_rank,
			"spam_score"		=> $spam_score,
			"rank_brand"		=> $rank_brand
		);
		
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);
		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-vendor-url-link/".$url_id);
		exit;
	}
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="add")
	{
		$rows 	= array(
			"url_id"			=> $url_id,
			"vendor_id"			=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
			"link"				=> $link,
			"da"				=> $da,
			"tf"				=> $tf,
			"dr"				=> $dr,
			"ttf"				=> $ttf,
			"monthly_traffic"	=> $monthly_traffic,
			"ahrefs_rank"		=> $ahrefs_rank,
			"spam_score"		=> $spam_score,
			"rank_brand"		=> $rank_brand
		);
		
		$url_link_id = $db->minsert($ctable,$rows);


		//Send notification to client for added new link
		$client_id = $db->rpgetValue("url","client_id"," isDelete = 0 AND id=".$url_id);
		$row5 = array(
			"to_id" 		=> $client_id,
			"from_id"		=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
			"subject_name"	=> "Added new link for approval",
			"description"	=> "You have received new link for approval.",
			"link"			=> "add-client-url-link/edit/".$url_link_id
		);
		$db->minsert("notification",$row5);
		if(ISMAIL)
		{
			$client_name = $db->rpgetValue("admin","name"," id=".$client_id);
			$nt = new Notification();
			include("../mailbody/vendor_add_link_to_client.php");
			$subject	= SITETITLE." Added new link";
			$nt->rpsendEmail($client_id,$subject,$body);
		}

		//Send notification to seo for added new link
		$seo_id = $db->rpgetValue("url","seo_id"," isDelete = 0 AND id=".$url_id);
		$row5 = array(
			"to_id" 		=> $seo_id,
			"from_id"		=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
			"subject_name"	=> "Added new link for approval",
			"description"	=> "You have received new link for approval.",
			"link"			=> "add-seo-url-link/edit/".$url_link_id
		);
		$db->minsert("notification",$row5);
		if(ISMAIL)
		{
			$seo_name = $db->rpgetValue("admin","name"," id=".$seo_id);
			$nt = new Notification();
			include("../mailbody/vendor_add_link_to_seo.php");
			$subject	= SITETITLE." Added new link";
			$nt->rpsendEmail($seo_id,$subject,$body);
		}

		//Send notification to seo for added new link

		$seo_team_id = $db->rpgetValue("url","seo_team_id"," isDelete = 0 AND id=".$url_id);
		if($seo_team_id != null){
			$row5 = array(
				"to_id" 		=> $seo_team_id,
				"from_id"		=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
				"subject_name"	=> "Added new link for approval",
				"description"	=> "You have received new link for approval.",
				"link"			=> "add-seo-team-url-link/edit/".$url_link_id
			);
			$db->minsert("notification",$row5);
			if(ISMAIL)
			{
				$seo_team_name = $db->rpgetValue("admin","name"," id=".$seo_team_id);
				$nt = new Notification();
				include("../mailbody/vendor_add_link_to_seoteam.php");
				$subject	= SITETITLE." Added new link";
				$nt->rpsendEmail($seo_team_id,$subject,$body);
			}
		}

		$_SESSION['MSG'] = "Inserted";
		$db->rplocation(ADMINURL."manage-vendor-url-link/".$url_id);
		exit;
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);

	$url_id   			= stripslashes($ctable_d['url_id']);
	$link 				= stripslashes($ctable_d['link']);
	$da   				= stripslashes($ctable_d['da']);
	$tf					= stripslashes($ctable_d['tf']);
	$dr  				= stripslashes($ctable_d['dr']);
	$ttf  				= stripslashes($ctable_d['ttf']);
	$monthly_traffic  	= stripslashes($ctable_d['monthly_traffic']);
	$ahrefs_rank  		= stripslashes($ctable_d['ahrefs_rank']);
	$spam_score  		= stripslashes($ctable_d['spam_score']);
	$rank_brand  		= stripslashes($ctable_d['rank_brand']);
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="delete")
{
	$id 	= $_REQUEST['id'];
	$rows 	= array("isDelete" => "1");
	
	$db->rpupdate($ctable,$rows,"id='".$id."'");
	
	$_SESSION['MSG'] = "Deleted";
	$db->rplocation(ADMINURL."manage-vendor-url-link/");
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
						<input type="hidden" name="url_id" id="url_id" value="<?php echo $url_id; ?>">
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<label for="link"> Link <code>*</code></label>
									<input type="text" class="form-control" id="link" name="link" value="<?php echo $link; ?>">
								</div>
							</div><br/>
							<div class="row">
								<div class="col-md-6">
									<label for="da"> DA <code>*</code></label>
									<input type="text" class="form-control" id="da" name="da" value="<?php echo $da; ?>">
								</div>
								<div class="col-md-6">
									<label for="tf"> TF <code>*</code></label>
									<input type="text" class="form-control" id="tf" name="tf" value="<?php echo $tf; ?>" >
								</div>
							</div><br/>
							<div class="row">
								<div class="col-md-6">
									<label for="dr"> DR <code>*</code></label>
									<input type="text" class="form-control" id="dr" name="dr" value="<?php echo $dr; ?>">
								</div>
								<div class="col-md-6">
									<label for="ttf"> TTF <code>*</code></label>
									<input type="text" class="form-control" id="ttf" name="ttf" value="<?php echo $ttf; ?>" >
								</div>
							</div><br/>
							<div class="row">
								<div class="col-md-6">
									<label for="monthly_traffic"> SEMrush Traffic <code>*</code></label>
									<input type="text" class="form-control" id="monthly_traffic" name="monthly_traffic" value="<?php echo $monthly_traffic; ?>">
								</div>
								<div class="col-md-6">
									<label for="ahrefs_rank"> Ahrefs Rank <code>*</code></label>
									<input type="text" class="form-control" id="ahrefs_rank" name="ahrefs_rank" value="<?php echo $ahrefs_rank; ?>" >
								</div>
							</div><br/>
							<div class="row">
								<div class="col-md-6">
									<label for="spam_score"> Spam Score <code>*</code></label>
									<input type="text" class="form-control" id="spam_score" name="spam_score" value="<?php echo $spam_score; ?>">
								</div>
								<div class="col-md-6">
									<label for="rank_brand"> Rank For Brand Name <code>*</code></label>
									<input type="text" class="form-control" id="rank_brand" name="rank_brand" value="<?php echo $rank_brand; ?>" >
								</div>
							</div><br/>
							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-vendor-url-link/<?php echo $url_id; ?>'">Back</button>
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
			// ignore: "",
			rules: {
				link:{required:true},
				da:{required:true,min:30},
				tf:{required:true,min:20},
				dr:{required:true,min:30},
				ttf:{required:true},
				monthly_traffic:{required:true},
				ahrefs_rank:{required:true},
				spam_score:{required:true},
				rank_brand:{required:true},
			},
			messages: {
				link:{required:"Please enter link."},
				da:{required:"Please enter da.",min:"Please enter min 30."},
				tf:{required:"Please enter tf.",min:"Please enter min 20."},
				dr:{required:"Please enter dr.",min:"Please enter min 30."},
				ttf:{required:"Please enter ttf."},
				monthly_traffic:{required:"Please enter monthly traffic."},
				ahrefs_rank:{required:"Please enter ahrefs rank."},
				spam_score:{required:"Please enter spam score."},
				rank_brand:{required:"Please enter rank brand."}
			}
		});
	});

</script>
</body>
</html>
