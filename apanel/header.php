<?php 
	$notification_count = $db->rpgetData("notification","*"," isDelete = 0 AND to_id=".$_SESSION[SESS_PRE.'_ID']." AND isRead = 0 AND isDelete = 0");
	$notification_r = $db->rpgetData("notification","*"," isDelete = 0 AND to_id=".$_SESSION[SESS_PRE.'_ID']);
	
?>
<!-- Logo -->
<a href="<?php echo ADMINURL?>" class="logo">
	<!-- mini logo for sidebar mini 50x50 pixels -->
	<span class="logo-mini"><b>WLS</b></span>
	<!-- logo for regular state and mobile devices -->
	<span class="logo-lg" style="font-size: 19px !important;"><b><?php echo SITETITLE; ?></b></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">

	<!-- Sidebar toggle button-->
	<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
		<span class="sr-only">Toggle navigation</span>
	</a>
	<!-- Navbar Right Menu -->

	<div class="navbar-custom-menu">
		<ul class="nav navbar-nav">
			<!-- User Account: style can be found in dropdown.less -->
			<li class="dropdown notifications-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<i class="fa fa-bell-o"></i>
					<span class="label label-warning">
					<?php if(@mysqli_num_rows($notification_count)>0){ echo @mysqli_num_rows($notification_count); }else{ echo 0; } ?>
					</span>
				</a>
				<ul class="dropdown-menu">
					<li class="header">You have <?php if(@mysqli_num_rows($notification_r)>0){ echo @mysqli_num_rows($notification_r); }else{ echo 0; } ?> notifications</li>
					<li>
						<!-- inner menu: contains the actual data -->
						<ul class="menu">
							<?php 
							$notification_r1 = $db->rpgetData("notification","*"," isDelete = 0 AND to_id=".$_SESSION[SESS_PRE.'_ID'],"id DESC LIMIT 4");
							if(@mysqli_num_rows($notification_r1)>0){
								while($notification_d = @mysqli_fetch_array($notification_r1)){
									$date = date('Y-m-d H:i:s');
									$date_diff = $db->dateDiff($date,$notification_d['adate']);
							?>
							<li>
								<a href="<?php echo ADMINURL.$notification_d['link']; ?>">
									<p class="mb-0">
										<!-- <i class="fa fa-warning text-yellow"></i> -->
										<strong><?php echo $notification_d['subject_name']; ?></strong><?php echo $notification_d['description']; ?>
									</p>
									<p class="clearfix mb-0">
										<small>
											<span class="pull-left">by <strong><?php echo $db->rpgetValue("admin","name"," isDelete = 0 AND id=".$notification_d['from_id']); ?></strong></span>
											<span class="pull-right"><i>
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
											</i></span>
										</small>
									</p>
								</a>
							</li>
							<?php } } ?>
						</ul>
					</li>
					<li class="footer"><a href="<?php echo ADMINURL.'notification/' ?>">View all</a></li>
				</ul>
			</li>
			<li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<img src="<?php echo ADMINURL?>assets/images/no_user.png" class="user-image" alt="User Image">
					<span class="hidden-xs">
						<?php 
							if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_ADMIN_SESS_NAME']; 
							} 
							if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_VENDOR_SESS_NAME']; 
							}
							if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_NAME']; 
							}
							if(isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_SEO_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_SEO_TEAM_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CONTENT_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CLIENT_SESS_NAME'];
							}
							?>
					</span>
				</a>
				<ul class="dropdown-menu">
					<!-- User image -->
					<li class="user-header">
						<img src="<?php echo ADMINURL?>assets/images/no_user.png" class="img-circle" alt="User Image">
						<p>Hi,<?php 
							if(isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_ADMIN_SESS_NAME']; 
							} 
							if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_VENDOR_SESS_NAME']; 
							}
							if(isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']))
							{ 
								echo $_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_NAME']; 
							}
							if(isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_SEO_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_SEO_TEAM_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CONTENT_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CONTENT_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_NAME'];
							}
							if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
							{
								echo $_SESSION[SESS_PRE.'_CLIENT_SESS_NAME'];
							}
							?>
						</p>
					</li>
					<!-- Menu Footer-->
					<li class="user-footer">
						<div class="pull-left">
							<a href="<?php echo ADMINURL?>my-account/" class="btn btn-default btn-flat">Profile</a>
						</div>
						<div class="pull-right">
							<a href="<?php echo ADMINURL?>logout/" class="btn btn-default btn-flat">Sign out</a>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</nav>