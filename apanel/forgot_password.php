<?php
include("connect.php"); 

if((isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']) && $_SESSION[SESS_PRE.'_ADMIN_SESS_ID']>0)){
	$db->rplocation(ADMINURL."dashboard/");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password | <?php echo ADMINTITLE; ?></title>
<?php include('include_css.php'); ?>
 <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo ADMINURL; ?>plugins/iCheck/square/blue.css">
</head>
    <body class="hold-transition login-page">
        <!-- HOME -->
       <div class="login-box">
               <div class="login-box-body">
                 <div class="login-logo">
                    <a href="javascript:void(0);" class="text-success">
                            <span><img src="<?php echo SITEURL?>images/logo.png" alt="<?php echo SITENAME; ?>" width="150"></span>
                        </a>
                	</div>
                	<div class="text-center m-b-20">
						<p class="text-muted m-b-0 font-13">Enter your email address and we'll send you an email with instructions to reset your password.  </p>
					</div>
                    <form  name="frm" id="frm" action="<?php echo ADMINURL."process-forget-pass/"; ?>" method="post">
                      <div class="form-group has-feedback"><span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                           <a href="<?php echo ADMINURL?>">Already have account?</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                          <button type="submit" class="btn btn-primary btn-block btn-flat">Send Email</button>
                        </div>
                        <!-- /.col -->
                      </div>
                    </form>
                    <div class="clearfix"></div>

                </div>
                    
         </div>   
	<?php include('include_js.php'); ?>
<script>
	$(function(){
		$("#frm").validate({
			rules: {
				email:{required : true,email:true},
			},
			messages: {
				email:{required:"Please enter your email.",email:"Please enter valid email address."},
			}
		});
	});
	
	$(document).ready(function(){
		setTimeout(function(){
		<?php 
		if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Something_Wrong') { ?>
			 $.notify({message: 'Something Went Wrong, Please Try Again !' },{type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'No_Data_Found') { ?>
			$.notify({message: 'Your email address is not registered with us.'},{ type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Link_Expired') { ?>
			$.notify({message: 'Your link to reset the password is expired.'},{type: 'danger'});
		<?php unset($_SESSION['MSG']); }
			?>	
		},1000);
	});
</script>
</body>
</html>