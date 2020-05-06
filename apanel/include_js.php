<script>
var resizefunc = [];
</script>

<!-- jQuery 3 -->
<script src="<?php echo ADMINURL; ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo ADMINURL; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/bootstrap-notify.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/bootstrap-datepicker.js"></script>
<!-- FastClick -->
<script src="<?php echo ADMINURL; ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo ADMINURL; ?>dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo ADMINURL; ?>bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="<?php echo ADMINURL?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo ADMINURL?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="<?php echo ADMINURL; ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo ADMINURL; ?>bower_components/chart.js/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo ADMINURL; ?>dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo ADMINURL; ?>dist/js/demo.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo ADMINURL;?>assets/js/additional-methods.min.js"></script>

<script>
	function check_all()
	{
		var chk = $("#chkall").prop("checked");
		if( chk )
		{
			$(document).find('input[name="chkid[]"]').each(function() {
			    $(this).prop('checked', true);
			});			
		}
		else
		{
			$(document).find('input[name="chkid[]"]').each(function() {
			    $(this).prop('checked', false);
			});			
		}
	}
	function bulk_delete()
	{
		var flg = 0;
		$('input[name="chkid[]"]').each(function () {
           if (this.checked) {
               flg = 1;
               //break; 
           }
		});

		if( flg )
		{
			if( confirm("Are you sure to delete all the selected records?") )
			{
				$('#hdnmode').val('delete');
				$.ajax({
		            url: "<?php echo ADMINURL; ?>ajax_bulk_remove.php",
		            type: "post",
		            data : $('#frm').serialize(),
		            success: function(response) {
		            	displayRecords(10,1);
		            }
	       		});
			}
		}
		else
		{
			$.notify({message: "Please select at least one record and try again."}, {type: "danger"});
			return false;
		}
		return false;
	}
	
	$(document).ready(function(){
		setTimeout(function(){
		<?php if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Something_Wrong') { ?>
			 $.notify({message: 'Something Went Wrong, Please Try Again !' },{type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Inserted') { ?>
			 $.notify({message: 'Record Added successfully.' },{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Updated') { ?>
			 $.notify({message: 'Record Updated successfully.' },{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Deleted') { ?>
			$.notify({message: 'Record Deleted successfully.'},{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Activate_account_success') { ?>
			$.notify({message: 'Account Activated successfully.'},{type: 'success'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Activate_account') { ?>
			$.notify({message: 'Please activate accoount.'},{type: 'danger'});
		<?php unset($_SESSION['MSG']); } else if(isset($_SESSION['MSG']) && $_SESSION['MSG'] == 'Duplicate') { ?>
			$.notify({message: 'This Record is Already Exist. Please Try Another.'},{type: 'danger'});
		<?php unset($_SESSION['MSG']); } 
		?>
		},1000);
	});


</script>