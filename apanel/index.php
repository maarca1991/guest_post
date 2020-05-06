<?php
include("connect.php");

if((isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']) && $_SESSION[SESS_PRE.'_ADMIN_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
if((isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID']) && $_SESSION[SESS_PRE.'_VENDOR_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
if((isset($_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']) && $_SESSION[SESS_PRE.'_VENDOR_TEAM_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
if((isset($_SESSION[SESS_PRE.'_SEO_SESS_ID']) && $_SESSION[SESS_PRE.'_SEO_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
if((isset($_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']) && $_SESSION[SESS_PRE.'_SEO_TEAM_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
if((isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']) && $_SESSION[SESS_PRE.'_CLIENT_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Login | <?php echo ADMINTITLE; ?></title>
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
                    <form action="<?php echo ADMINURL."process-login/"; ?>" method="post" name="frm" id="frm">
                      <div class="form-group has-feedback"><span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                      </div>
                      <div class="form-group has-feedback">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                      </div>
                      <div class="row">
                        <div class="col-xs-8">
                          <!-- <div class="checkbox icheck">
                            <label>
                              <input type="checkbox"> Remember Me
                            </label>
                          </div> -->
                           <a href="<?php echo ADMINURL."forgot-password/"?>">I forgot my password</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                      </div>
                    </form>

                    <!-- <div class="social-auth-links text-center">
                          <p>- OR -</p>
                          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
                            Facebook</a>
                          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
                            Google+</a>
                        </div> -->
                        <!-- /.social-auth-links -->

                       <!--  <a href="<?php echo ADMINURL."forgot-password/"?>">I forgot my password</a> --><br>
                        <!-- <a href="register.html" class="text-center">Register a new membership</a> -->
                    <div class="clearfix"></div>

                </div>
                    
         </div>   
  <?php include('include_js.php'); ?>
<!-- iCheck -->
<script src="<?php echo ADMINURL; ?>plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' /* optional */
        });
      });
    </script>
  <script>    
    $(function(){
      $("#frm").validate({
        rules: {
          email:{required : true,email:true},
          password:{required : true},
        },
        messages: {
          email:{required:"Please enter your email address.",email:"Please enter valid email address."},
          password:{required:"Please enter your password."},
        }
      });
    });
    
    $(document).ready(function(){
      setTimeout(function(){
      <?php if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Something_Wrong') { ?>
         $.notify({message: 'Something Went Wrong, Please Try Again !' },{type: 'danger'});
      <?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Invalid_Email_Password') { ?>
         $.notify({message: 'Invalid email and password.' },{type: 'danger'});
      <?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Success_Fsent') { ?>
        $.notify({message: 'Your forgot password reset link is successfully sent to your register email address.'},{type: 'success'});
      <?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Update_Pass') { ?>
        $.notify({message: 'Your password has been updated successfully.'},{type: 'success'});
      <?php unset($_SESSION['MSG']); } ?>
      },1000);
    });
  </script>
</body>
</html>