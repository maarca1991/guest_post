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

if(isset($_REQUEST['submit']))
{
	// print_r("<pre>");
	// print_r($_REQUEST);die();
	$url_id		= $db->clean($_REQUEST['url_id']);
	$live_note	= $db->clean($_REQUEST['live_note']);
	$liveDate	= $db->clean($_REQUEST['liveDate']);
	if( $_REQUEST['liveStatus'] == on ){ $liveStatus = 1; }else{ $liveStatus = 0; }

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="live")
	{
		$rows 	= array(
			"live_note"		=> $live_note,
			"liveDate"		=> date("Y-m-d h:i:s",$liveDate),
			"liveStatus"	=> $liveStatus
		);
		
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);

		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-vendor-url-link/".$url_id);
		exit;
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="live")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);

	$url_id   			= stripslashes($ctable_d['url_id']);
	$link 				= stripslashes($ctable_d['link']);
	$live_note 			= stripslashes($ctable_d['live_note']);
	$liveDate 			= stripslashes($ctable_d['liveDate']);
	
	if($ctable_d['liveStatus'] == 1 )
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
							<div class="row">
								<div class="col-md-6">
									<label for="live_note"> Note <code>*</code></label>
									<textarea class="form-control" id="live_note" name="live_note"><?php echo $live_note; ?></textarea>
								</div>
								<div class="col-md-6">
									<label for="liveDate"> Date <code>*</code></label>
									<input type="text" class="form-control datepicker" id="liveDate" name="liveDate" value="<?php echo $liveDate; ?>" >
								</div>
							</div><br/>
							<div class="row">
								<div class="col-md-6">
									<label for="liveStatus">On For Live Link<code>*</code></label>
									<div>
										<label class="switch">
											<input type="checkbox" name="liveStatus" id="switch<?php echo $_REQUEST['id'];  ?>" class="changeStatus" <?=$check?>>
											<span class="slider round"></span>
										</label>
									</div>								
								</div>
							</div></br>
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
	$(".datepicker").datepicker();
	$(function(){
		$("#frm").validate({
			// ignore: "",
			rules: {
				liveDate:{required:true},
				liveStatus:{required:true}
			},
			messages: {
				liveDate:{required:"Please enter date."},
				liveStatus:{required:"Please on for live"}
			}
		});
	});

</script>
</body>
</html>
