<?php
include("connect.php");

if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$main_page 	= "home";
$page_title = "Notification";

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title?> | <?php echo ADMINTITLE; ?></title>
	<?php include('include_css.php'); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<header class="main-header">
		<?php include("header.php"); ?>
	</header>
	<?php include("left.php"); ?>
	
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Notification
			</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo ADMINURL.'dashboard/' ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active">Notification</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-success">
						<div class="box-body">
							<!-- <div class="row">
								<div class="col-md-3 col-md-offset-9">
									<select class="form-control input-sm mb-3">
										<option selected="selected">All Notifications</option>
										<option>Unread Notifications</option>
										<option>Read Notifications</option>
									</select>
								</div>
							</div> -->
							<?php 
								$notification_r = $db->rpgetData("notification","*"," isDelete = 0 AND to_id=".$_SESSION[SESS_PRE.'_ID'],"id DESC");
								if(@mysqli_num_rows($notification_r)>0){
									while($notification_d = @mysqli_fetch_array($notification_r)){
										$date = date('Y-m-d H:i:s');
										$date_diff = $db->dateDiff($date,$notification_d['adate']);
							?>
								<div class="notification">
									<img src="http://picsum.photos/50" class="img-circle mr-5">
									<div>
										<a href="<?php echo ADMINURL.$notification_d['link']; ?>">
										<p><strong><?php echo $notification_d['subject_name']; ?> : </a></strong><?php echo $notification_d['description']; ?></p>
										<span>
										<?php
										if($date_diff[1] > 0){
											echo $date_diff[1]." month ago";
										}
										elseif($date_diff[2] > 0){
											echo $date_diff[2]." day ago";
										}
										elseif($date_diff[3] > 0){
											echo $date_diff[3]." hour ago";
										}
										elseif($date_diff[4] > 0){
											echo $date_diff[4]." minute ago";
										}
										elseif($date_diff[5] > 0){
											echo $date_diff[5]." second ago";
										}
										else{
											echo "year ago";
										} 
										?>
										by <strong><?php echo $db->rpgetValue("admin","name"," isDelete = 0 AND id=".$notification_d['from_id']); ?></strong></span>
									</div>
								</div>
							<?php } }else{ ?>
								<div align="center">
									You have 0 notifications
								</div>
							<?php } ?>
							<!-- <div class="box-tools mt-3">
			                	<ul class="pagination pagination-sm inline">
									<li><a href="#">«</a></li>
									<li><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li><a href="#">»</a></li>
			                	</ul>
			              	</div> -->
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
		<?php include("footer.php"); ?>
	<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<?php include('include_js.php'); ?>
</body>
</html>
