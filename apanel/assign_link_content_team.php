<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "link_content";
$ctable1 		= "Assign Content to Team Member";
$main_page 		= "Assign Content to Team Member";
$page_title 	= "Assign Content to Team Member";

$assign_content_team_id 	= "";

if(isset($_REQUEST['submit']))
{
	$assign_content_team_id	 = $db->clean($_REQUEST['assign_content_team_id']);

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="assign")
	{
		$rows 	= array(
			"assign_content_team_id"	=> $assign_content_team_id,
			"content_team_assign_date"	=> date("Y-m-d H:i:s")
		);
		
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);

		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-head-content/");
		exit;
	}
}
if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="assign")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);

	$assign_content_team_id   = stripslashes($ctable_d['assign_content_team_id']);
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
									<label for="assign_content_team_id">Content Writer<code>*</code></label>
									<select class="form-control" id="assign_content_team_id" name="assign_content_team_id">
										<option value="">Select Content Writer</option>
										<?php
											$content_team_where = "isDelete=0 AND role=7 AND content_id=".$_SESSION[SESS_PRE.'_ID'];
											$content_team_r = $db->rpgetData('admin', '*',$content_team_where, 'name');
											while($content_team_d = @mysqli_fetch_assoc($content_team_r))
											{
												echo '<option value="' . $content_team_d['id'] . '"';
												if( $content_team_d['id'] == $assign_content_team_id )
													echo ' selected';
												echo '>' . $content_team_d['name'] . '</option>';
											}
										?>
									</select>
								</div>
								
							</div><br/>
							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-head-content/'">Back</button>
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
				assign_content_team_id:{required:true},
			},
			messages: {
				assign_content_team_id:{required:"Please select content team member name."}
			}
		});
	});

</script>
</body>
</html>
