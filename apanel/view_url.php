<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url";
$ctable1 		= "Url";
$main_page 		= "Url";
$page 			= "add_".$ctable;
$page_title 	= " View Website Details";

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="view")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

	$url					= stripslashes($ctable_d['url']);
	$quntity				= stripslashes($ctable_d['quntity']);
	$budget					= stripslashes($ctable_d['budget']);
	$notes					= stripslashes($ctable_d['notes']);
	$site_desc				= stripslashes($ctable_d['site_desc']);
	$da_min					= stripslashes($ctable_d['da_min']);
	$tf_min					= stripslashes($ctable_d['tf_min']);
	$dr_min					= stripslashes($ctable_d['dr_min']);
	$ttf_min				= stripslashes($ctable_d['ttf_min']);
	$monthly_traffic_min 	= stripslashes($ctable_d['monthly_traffic_min']);
	$ahrefs_rank_min	 	= stripslashes($ctable_d['ahrefs_rank_min']);
	$spam_score_min		 	= stripslashes($ctable_d['spam_score_min']);
	$rank_brand_name 	 	= stripslashes($ctable_d['rank_brand_name']);
	$wantSeo 	 			= stripslashes($ctable_d['wantSeo']);
	$seo_id 	 			= stripslashes($ctable_d['seo_id']);

	//Get Category Data
	$where1     = " url_id='".$ctable_d['id']."' AND isDelete=0";
	$ctable_r2  = $db->rpgetData("url_category","*",$where1);
	if(@mysqli_num_rows($ctable_r2)>0)
	{
		$count3 = 0;
		while($ctable_d2 = @mysqli_fetch_array($ctable_r2))
		{
			$count3++;
			$category_array .= $ctable_d2['category_id'].",";
		}
		$category_array = rtrim($category_array,",");
		$d1_array = explode(",", $category_array);
	}
	else
	{
		$category_array = "";
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
						<div class="box-body">
							<div class="box-borderd">
								<label class="box-borderd-label">Website Information</label>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Website url : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $url; ?>	
										</div>
									</div>
								</div>
								<?php if($wantSeo == 1){ ?>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> SEO : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $db->rpgetValue("admin","name","id=".$seo_id); ?>	
										</div>
									</div>
								</div>
								<?php } ?>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Quntity : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $quntity; ?>	
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Budget : </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $budget; ?>	
										</div>
									</div>
								</div> -->
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> Note : </label>	
										</div>
										<div class="col-sm-9">
											<?php if($notes == null){ echo "-"; }else{ echo $notes; } ?>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="col-sm-3">
											<label> website and niches of links: </label>	
										</div>
										<div class="col-sm-9">
											<?php echo $site_desc; ?>	
										</div>
									</div>
								</div>
							</div><br/>
							
							<div class="box-borderd">
								<label class="box-borderd-label">Min Required metric from client side</label>
								<div class="row">
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> DA : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($da_min == null){ echo "-"; }else{ echo $da_min; } ?>	
										</div>
									</div>
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> TF : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($tf_min == null){ echo "-"; }else{ echo $tf_min; } ?>	
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> DR : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($dr_min == nul){ echo "-"; }else{ echo $dr_min; } ?>	
										</div>
									</div>
								
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> TTF : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($ttf_min == null){ echo "-"; }else{ echo $ttf_min; } ?>	
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> SEMrush Traffic : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($monthly_traffic_min == null){ echo "-"; }else{ echo $monthly_traffic_min; } ?>	
										</div>
									</div>
								
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> Ahrefs Rank : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($ahrefs_rank_min == null){ echo "-"; }else{ echo $ahrefs_rank_min; } ?>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> Spam Score : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($spam_score_min == null){ echo "-"; }else{ echo $spam_score_min; } ?>	
										</div>
									</div>
								
									<div class="col-sm-6">
										<div class="col-sm-5">
											<label> Rank For Brand Name : </label>	
										</div>
										<div class="col-sm-1">
											<?php if($rank_brand_name == null){ echo "-"; }else{ echo $rank_brand_name; } ?>
										</div>
									</div>
								</div>
							</div><br/>

							<div class="box-borderd">
								<label class="box-borderd-label">Website Categories</label>
								<div class="row">
									<div class="col-sm-12">
										<ul>
										<?php  
										$where2 = " c.isDelete=0 AND uc.isDelete=0";
										$ctable_r1 = $db->rpgetJoinData("category as c","url_category as uc","uc.category_id = c.id AND uc.url_id=".$_REQUEST['id'],"c.id as category_id, c.name as category_name",$where2);
										if(@mysqli_num_rows($ctable_r1)>0){
											while($ctable_d1 = @mysqli_fetch_array($ctable_r1)){
										?>
											<li><?php echo $ctable_d1['category_name']; ?></li>
										<?php }} ?>
										</ul>
									</div>
								</div>
							</div><br/>
							<?php  
								$where3 = " isDelete=0 AND url_id=".$_REQUEST['id'];
								$ctable_r2 = $db->rpgetData("backlink","*",$where3);
								if(@mysqli_num_rows($ctable_r2)>0){
							?>
							<div class="box-borderd">
								<label class="box-borderd-label">Website Backlinks</label>
								<div class="row">
									<div class="col-sm-12">
										<table class="table">
											<thead>
												<tr>
													<th>Keywords</th>
													<th>Links</th>
												</tr>
											</thead>
											<tbody>
												<?php
													while($ctable_d2 = @mysqli_fetch_array($ctable_r2)){
												?>
												<tr>
													<td><?php echo $ctable_d2['keyword']; ?></td>
													<td><?php echo $ctable_d2['link']; ?></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php } ?>
							<?php 
							if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
								$bacl_link = "manage-vendor-url/";
							}
							if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID'])){
								$bacl_link = "manage-vendor-team-url/";
							}
							if (isset($_SESSION[SESS_PRE.'_SEO_SESS_ID'])) {
								$bacl_link = "manage-seo-url/";
							}
							if (isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID'])) {
								$bacl_link = "manage-seo-team-url/";
							}
							if (isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID'])) {
								$bacl_link = "manage-client-url/";
							}
							?>
							<div class="box-footer">
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL.$bacl_link ?>'">Back</button>
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