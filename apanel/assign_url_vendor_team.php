<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url";
$ctable1 		= "Assign Vendor Team Member";
$main_page 		= "Assign Vendor Team Member";
$page_title 	= "Assign Vendor Team Member";

$vendor_team_id 	= "";

if(isset($_REQUEST['submit']))
{
	$vendor_team_id	 = $db->clean($_REQUEST['vendor_team_id']);

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="assign")
	{
		$rows 	= array(
			"vendor_team_id"	=> $vendor_team_id,
			"vendor_team_assign_date"	=> date("Y-m-d H:i:s")
		);
		
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);

		$where1 = " to_id=".$vendor_team_id." AND from_id=".$_SESSION[SESS_PRE.'_VENDOR_SESS_ID']." AND url_id=".$_REQUEST['id']." AND subject_name='Assign new website url' AND description='You have received new website url to work on.' AND link='view-url/view/".$_REQUEST['id']."'";
		$check1 = $db->rpdupCheck("notification",$where1);
		if(!$check1){
			$row5 = array(
				"to_id" 		=> $vendor_team_id,
				"from_id"		=> $_SESSION[SESS_PRE.'_VENDOR_SESS_ID'],
				"url_id"		=> $_REQUEST['id'],
				"subject_name"	=> "Assign new website url",
				"description"	=> "You have received new website url to work on.",
				"link"			=> "view-url/view/".$_REQUEST['id']
			);
			$db->minsert("notification",$row5);
			if(ISMAIL)
			{
				$url_name = $db->rpgetValue("url","url"," id=".$_REQUEST['id']);
				$vendor_team_name = $db->rpgetValue("admin","name"," id=".$vendor_team_id);
				$nt = new Notification();
				include("../mailbody/assign_url_vendor_team_member.php");
				$subject	= SITETITLE." Assign New Website Url";
				$nt->rpsendEmail($vendor_team_id,$subject,$body);
			}
		}

		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-vendor-url/");
		exit;
	}
}
if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="assign")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);

	$vendor_team_id   	= stripslashes($ctable_d['vendor_team_id']);
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
									<label for="vendor_team_id">Vendor<code>*</code></label>
									<select class="form-control" id="vendor_team_id" name="vendor_team_id">
										<option value="">Select Vendor</option>
										<?php
											$vendor_team_where = "isDelete=0 AND role=2 AND vendor_id=".$_SESSION[SESS_PRE.'_ID'];
											$vendor_team_r = $db->rpgetData('admin', '*',$vendor_team_where, 'name');
											while($vendor_team_d = @mysqli_fetch_assoc($vendor_team_r))
											{
												echo '<option value="' . $vendor_team_d['id'] . '"';
												if( $vendor_team_d['id'] == $vendor_team_id )
													echo ' selected';
												echo '>' . $vendor_team_d['name'] . '</option>';
											}
										?>
									</select>
								</div>
								
							</div><br/>
							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-vendor-url/'">Back</button>
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
				vendor_team_id:{required:true},
			},
			messages: {
				vendor_team_id:{required:"Please select vendor team member name."}
			}
		});
	});

</script>
</body>
</html>
