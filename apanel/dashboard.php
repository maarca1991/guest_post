<?php
include("connect.php");
if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) || isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']) || isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']) || isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']) || isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']) || isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$main_page 	= "home";
$page_title = "Dashboard";

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
				Dashboard
				<small>Version 2.0</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Dashboard</li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!-- Info boxes -->
			<?php
				include("../include/dashboard_var.php");    
			?>
			<div class="row">
				<?php
				$dash = 0;
				foreach($dashboard_main_array as $arr){
				$dash++;
				?>
				<a href="<?php echo ADMINURL; ?><?php echo $arr[3]; ?>">
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-<?php echo $arr[0]; ?>"><i class="ion <?php echo $arr[4]; ?>"></i></span>

						<div class="info-box-content">
							<span class="info-box-text"><?php echo $arr[2]; ?></span>
							<span class="info-box-number"><?php echo $arr[1]; ?><!-- <small>%</small> --></span>
						</div>
						<!-- /.info-box-content -->
					</div>
					<!-- /.info-box -->
				</div>
			</a>
			 <?php
							if($dash%4==0){
							?>
							</div>
							<div class="row">
							<?php
							}
						} 
						?>
				<!-- /.col -->
				 <!-- /.col -->
				<!-- <div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Likes</span>
							<span class="info-box-number">41,410</span>
						</div>
					</div>
				</div>

				<div class="clearfix visible-sm-block"></div>

				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">Sales</span>
							<span class="info-box-number">760</span>
						</div>
					</div>
				</div>

				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

						<div class="info-box-content">
							<span class="info-box-text">New Members</span>
							<span class="info-box-number">2,000</span>
						</div>
					</div>
				</div>
			</div> -->
			<!-- /.row -->

		 
			<!-- /.row -->
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
