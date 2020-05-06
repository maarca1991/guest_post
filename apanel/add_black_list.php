<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url_blacklist";
$ctable1    	= "Black List";
$page       	= "Black List";
$page 			= "add_".$ctable;
$page_title 	= ucwords($_REQUEST['mode'])." ".$ctable1;

$url_id 	= "";
$link 	= "";

if(isset($_REQUEST['submit']))
{
	$url_id	= $db->clean($_REQUEST['url_id']);
	$link	= $db->clean($_REQUEST['link']);

	$rows 	= array(
		"client_id"	=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
		"url_id" 	=> $url_id,
		"link"		=> $link
	);

	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);
		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-black-list/");
		exit;
	}
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="add")
	{
		$db->minsert($ctable,$rows);
		$_SESSION['MSG'] = "Inserted";
		$db->rplocation(ADMINURL."manage-black-list/");
		exit;
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_array($ctable_r);
	
	$url_id	= stripslashes($ctable_d['url_id']);
	$link	= stripslashes($ctable_d['link']);
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="delete")
{
	$id 	= $_REQUEST['id'];
	$rows 	= array("isDelete" => "1");
	
	$db->rpupdate($ctable,$rows,"id='".$id."'");
	
	$_SESSION['MSG'] = "Deleted";
	$db->rplocation(ADMINURL."manage-black-list/");
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
								<div class="col-sm-6">
									<div class="form-group">
										<label for="url_id">Url<code>*</code></label>
										<select class="form-control" id="url_id" name="url_id">
											<option value="">Select Url</option>
											<?php
												$where1 = " isDelete = 0 AND client_id=".$_SESSION[SESS_PRE.'_CLIENT_SESS_ID'];
												$url_r = $db->rpgetData("url","*",$where1);
												while($url_d = @mysqli_fetch_assoc($url_r))
												{
													echo '<option value="' . $url_d['id'] . '"';
													if( $url_d['id'] == $url_id )
														echo ' selected';
													echo '>' . $url_d['url'] . '</option>';
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="link">Link<code>*</code></label>
										<input type="text" class="form-control" id="link" name="link" value="<?php echo $link; ?>" >
									</div>
								</div>
							</div>
						</div>
							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-black-list/'">Back</button>
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
				url_id:{required:true},
				link:{required:true}
			},
			messages: {
				url_id:{required:"Please select url."},
				link:{required:"Please enter link."}
			}
		});
	});

</script>
</body>
</html>
