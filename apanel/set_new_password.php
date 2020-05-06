<?php
include("connect.php");

if((isset($_SESSION[SESS_PRE.'_ADMIN_SESS_ID']) && $_SESSION[SESS_PRE.'_ADMIN_SESS_ID']>0)){
  $db->rplocation(ADMINURL."dashboard/");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Set New Password | <?php echo ADMINTITLE; ?></title>
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
                    <form name="frm" id="frm" class="form-horizontal" action="<?php echo ADMINURL."process-set-new-password/"; ?>" method="post">
                      <div class="form-group has-feedback"><span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="password" name="newpass" id="newpass" class="form-control" autocomplete="off" placeholder="Enter New Password" />
                      </div>

                      <div class="form-group has-feedback"><span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        <input type="password" name="cnewpass" id="cnewpass" class="form-control" autocomplete="off" placeholder="Confirm New Password" />
                      </div>
                      <input type="hidden" name="slug" id="slug" value="<?php echo $_REQUEST['slug']; ?>" />
                      <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>" />

                      <div class="row">
                        <div class="col-xs-8">
                          <div class="col-xs-4">
                            <!-- <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button> -->
                            <button name="submit" id="submit" class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Submit</button>

                          </div>
                        </div>
                      </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
                    
         </div>   
  <?php include('include_js.php'); ?>
<!-- iCheck -->

</body>
</html>