<?php
include("connect.php");
// $db->rpcheckAdminLogin();

$name		= "";
$email			= "";

if(isset($_REQUEST['submit']))
{

	$name		= addslashes(trim($_REQUEST['name']));
	$email		= trim($_REQUEST['email']);

	$dup_where = "email='".$email."' AND isDelete=0 AND id!='".$_SESSION[SESS_PRE.'_ID']."'";
	$r = $db->rpdupCheck(CTABLE_ADMIN,$dup_where);
	if($r)
	{
		$_SESSION['MSG'] = "Duplicate_Entry";
		$db->rplocation(ADMINURL."my-account/");	
		exit;
	}
	else
	{
		$rows 	= array("name"=>$name,"email"=>$email);
		$where	= "id='".$_SESSION[SESS_PRE.'_ID'] ."' AND isDelete=0";
		$db->rpupdate(CTABLE_ADMIN,$rows,$where);
		
		$_SESSION[SESS_PRE.'_NAME'] 	= $name;
		$_SESSION['MSG'] = 'UPDATE_AC';

		$db->rplocation(ADMINURL."my-account/");
		exit;	
	}
}

if(isset($_REQUEST['submit1']))
{
	$where = " id='".$_SESSION[SESS_PRE.'_ID']."' AND isDelete=0";
	$admin_r = $db->rpgetData(CTABLE_ADMIN,"*",$where);
	$admin_d = @mysqli_fetch_array($admin_r);

	$old_password	= $admin_d['password'];
	$opassword		= md5(trim($_REQUEST['opassword']));
	$password		= md5(trim($_REQUEST['password']));

	if($old_password!=$opassword)
	{
		$_SESSION['MSG'] = 'PASS_NOT_MATCH';
		$db->rplocation(ADMINURL."my-account/");
		exit;
	}
	else
	{
		$rows 	= array("password"=>$password);
		$where	= "id='".$_SESSION[SESS_PRE.'_ID'] ."' AND isDelete=0";
		$db->rpupdate(CTABLE_ADMIN,$rows,$where);

		$_SESSION['MSG'] = 'PASS_CHANGED';
		$db->rplocation(ADMINURL."my-account/");
		exit;
	}
}

$where = " id='".$_SESSION[SESS_PRE.'_ID'] ."' AND isDelete=0";
$admin_r = $db->rpgetData(CTABLE_ADMIN,"*",$where);
$admin_d = @mysqli_fetch_array($admin_r);

$name			= $admin_d['name'];
$email 			= $admin_d['email'];
?>
<!DOCTYPE html>
<html>
<head>
<title>My Account | <?php echo ADMINTITLE; ?></title>
<?php include("include_css.php"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="wrapper">
	<header class="main-header">
  		<?php include("header.php"); ?>
  	</header>
  	<?php include("left.php"); ?>
 <div class="content-wrapper">
	<section class="content-header">
		<h1>My Account</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo ADMINURL?>dashboard/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active">My Account</li>
		</ol>
	</section>
	  <!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
					</div>
						<div class="box-body">
							<form role="form" name="frm" id="frm" action="." method="post" enctype="multipart/form-data">
							<h4 class="m-t-0 header-title"><b>Update Account Info</b></h4>
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" value="<?php echo $name; ?>" id="name" name="name" placeholder="Enter name">
							</div>
												
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?php echo $email; ?>">
							</div>
							<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
						</form>
						</div>	
					</div>
				</div>
			<div class="col-md-6">
				<div class="box box-success">
					<div class="box-header with-border">
					</div>
						<div class="box-body">
							<form role="form" action="" name="frm_pass" id="frm_pass"  method="post">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
								<h4 class="m-t-0 header-title"><b>Change Password</b></h4>
								<div class="form-group">
									<label for="opassword">Current Password</label>
									<input type="password" class="form-control" name="opassword" id="opassword" placeholder="Enter Current Password" value="" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="password">New Password</label>
									<input type="password" class="form-control" name="password" id="password" placeholder="Enter New Password" value="" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="cpassword">Confirm New Password</label>
									<input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Enter New Password" value="<?php //echo $password; ?>" autocomplete="off">
								</div>
								<button type="submit" name="submit1" id="submit1"  class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
							</form>
						</div>
						<div class="box-footer">
							<!-- <button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
							<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>my-account/'">Back</button> -->
						</div>
					
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
	var resizefunc = [];
</script> 
<script type="text/javascript">
	$(function(){
		$("#frm").validate({
			rules: {
				name:{required : true},
				email:{required : true,email:true},
			},
			messages: {
				name:{required:"Please enter Your name."},
				email:{required:"Please enter your email.",email:"Please enter valid email address."},
			}
		});
	});

	$(function(){
		$("#frm_pass").validate({
			rules: {
				opassword:{required : true},
				password:{required : true},
				cpassword:{required : true,equalTo: "#password"},
			},
			messages: {
				opassword:{required:"Please enter current password."},
				password:{required:"Please enter new password."},
				cpassword:{required:"Please enter confirm password.", equalTo:"New password and confirm new password not matched."},
			}
		});
	});

	

	$(document).ready(function(){
		setTimeout(function(){
		<?php if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Something_Wrong') { ?>
		 	$.notify({message: 'Something Went Wrong, Please Try Again !' },{type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'UPDATE_AC') { ?>
		 	$.notify({message: 'Your Account Info has been updated successfully' },{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'PASS_NOT_MATCH') { ?>
		 	$.notify({message: 'Old Password does not matched.' },{type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'PASS_CHANGED') { ?>
		 	$.notify({message: 'New Password has been updated successfully.' },{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Duplicate_Entry') { ?>
		 	$.notify({message: 'This email address is already registered with us. Please try another.' },{type: 'success'});
		<?php unset($_SESSION['MSG']); } 
		?>
		},1000);
	});
</script>
</body>
</html>