<?php
	include("connect.php");
	$db->rpcheckAdminLogin();

	$ctable 	= "question";
	$ctable1 	= "Question";
	$main_page 	= "manage-question/";
	$page 		= "add_".$ctable;
	$page_title = ucwords($_REQUEST['mode'])." ".$ctable1;

	$question = '';
	$question_type = '';
	$answer_id = '';
	$answer = '';
	$hdncount = '';

	if(isset($_REQUEST['submit']))
	{
		//print '<pre>'; print_r($_REQUEST); print '</pre>'; exit;
		$question 	= $db->clean($_REQUEST['question']);
		$question_type = $db->clean($_REQUEST['question_type']);
		$answer_id = $_REQUEST['answer_id'];
		$answer = $_REQUEST['answer'];
		$hdncount = $db->clean($_REQUEST['hdncount']);

		$rows = array(
			'question' 	=> $question, 
			'question_type' => $question_type
		);

		if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="add")
		{
			$check = $db->rpgetData($ctable, '*', 'question = "' . $question . '" AND isDelete=0');
		
			if( @mysqli_num_rows($check) > 0 )
			{
				$_SESSION['MSG'] = "Duplicate";
				$db->rplocation(ADMINURL.$main_page);
				exit;
			}
			else
			{	
				$question_id = $db->minsert($ctable, $rows);

				/* START: adding answers */
				for( $i=0; $i<$hdncount; $i++ )
				{
					$rows = array(
						'answer' => $answer[$i], 
						'question_id' => (int) $question_id
					);

					if( $answer_id[$i] > 0 )
						$db->rpupdate('answer', $rows, 'id='. (int) $answer_id[$i]);
					else
						$db->minsert('answer', $rows);
				}
				/* END: adding answers */

				$_SESSION['MSG'] = "Inserted";
				$db->rplocation(ADMINURL.$main_page);
				exit;
			}
		}

		if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
		{
			$question_id = $_REQUEST['id'];
			$db->rpupdate($ctable, $rows, 'id='. (int) $question_id);

			/* START: adding answers */
			for( $i=0; $i<$hdncount; $i++ )
			{
				$rows = array(
						'answer' => $answer[$i], 
						'question_id' => (int) $question_id
					);

				if( $answer_id[$i] > 0 )
					$db->rpupdate('answer', $rows, 'id='. (int) $answer_id[$i]);
				else
					$db->minsert('answer', $rows);
			}
			/* END: adding answers */

			$_SESSION['MSG'] = "Updated";
			$db->rplocation(ADMINURL.$main_page);
			exit;
		}
	}

	if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit")
	{
		$where 		= ' id='. (int) $_REQUEST['id'].' AND isDelete=0';
		$ctable_r 	= $db->rpgetData($ctable, "*", $where);
		$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

		$question = stripslashes($ctable_d['question']);
		$question_type = stripslashes($ctable_d['question_type']);
	}

	if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="delete")
	{
		$id 	= $_REQUEST['id'];
		$rows 	= array("isDelete" => "1");
		
		$db->rpupdate($ctable, $rows, 'id='.$id, 1);
		
		$_SESSION['MSG'] = "Deleted";
		$db->rplocation(ADMINURL.$main_page);
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $page_title . ' | ' .  ADMINTITLE; ?></title>
	<?php include("include/include_css.php"); ?>
	<link href="<?php echo ADMINURL; ?>js/crop/css/demo.html5imageupload.css?v1.3" rel="stylesheet">
</head>

<body id="page-top">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		<?php include("include/side_panel.php"); ?>
		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Topbar -->
				<?php include('include/header.php'); ?>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h4 mb-0 text-gray-900"><?php echo $page_title; ?></h1>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4  border-left-info">
								<form role="form" name="frm" id="frm" action="." method="post" enctype="multipart/form-data">
									<input type="hidden" name="mode" id="mode" value="<?php echo $_REQUEST['mode']; ?>">
									<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>">
  									
									<div class="card-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="question"> Question <code>*</code></label>
													<input type="text" class="form-control" name="question" id="question" value="<?php echo $question; ?>" maxlength="100">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="question"> Question Type <code>*</code></label>
													<select name="question_type" id="question_type" class="form-control">
														<option value="1" <?php if( $question_type == 1 ) echo ' selected'; ?>>Dropdown List</option>
														<option value="2" <?php if( $question_type == 2 ) echo ' selected'; ?>>Radio Buttons</option>
													</select>
												</div>
											</div>
										</div>
										<div id="answer_content">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="question"> Answers <code>*</code></label><br/>
													</div>
												</div>
												<div class="col-md-6">
													<label for="remove"> Remove </label><br/>
												</div>
												<div class="clearfix"></div>
											</div>
										  <?php
										  	$spec_counter = 0;
										  	$rs = $db->rpgetData('answer', '*', 'isDelete=0 AND question_id='.$_REQUEST['id']);
										  	while( $row = @mysqli_fetch_assoc($rs) )
										  	{
										  		$spec_counter++;
										  ?>
											<div class="row" id="<?php echo $spec_counter; ?>">
											    <input type="hidden" name="answer_id[]" id="answer_id_<?php echo $spec_counter; ?>" value="<?php echo $row['id'] ?>">
												<div class="col-md-6">
													<div class="form-group">
														<input class="form-control" type="text" name="answer[]" id="answer<?php echo $spec_counter; ?>" value="<?php echo $row['answer'] ?>" maxlength="100" required>
													</div>
												</div>
												<div class="col-md-6">
													<a href="javascript:void(0);" onclick="remove_block('<?php echo $spec_counter; ?>', '<?php echo $row['id']; ?>')"><i class="fa fa-trash" style="font-size: 30px;" aria-hidden="true"></i></a>
												</div>
												<div class="clearfix"></div>
											</div>
										  <?php
										  	}
										  ?>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<input type="hidden" name="hdncount" id="hdncount" value="<?php echo $spec_counter; ?>">
													<button type="button" id="add_button" title="Add Answer" class="btn btn-primary"><i class="fa fa-plus"></i></button>
												</div>
											</div>
										</div><br/>
										<div class="box-footer">
											<button type="submit" name="submit" id="submit" class="btn btn-success" title="Submit"><i class="fa fa-save"></i></button>
											<button type="button" class="btn btn-secondary waves-effect w-md waves-light" onClick="window.location.href='<?php echo ADMINURL . $main_page; ?>'" title="Back"><i class="fa fa-reply" aria-hidden="true"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- Footer -->
			<?php include("include/footer.php"); ?>
			<!-- End of Footer -->

		</div>
		<!-- End of Content Wrapper -->
	</div>
	<!-- End of Page Wrapper -->

	<!-- Bootstrap core JavaScript-->
	<?php include("include/include_js.php"); ?>

	<script>
		$(function(){
			$("#frm").validate({
				ignore: "",
				rules: {
					question:{required:true}, 
					question_type:{required:true}, 
				},
				messages: {
					question:{required:"Please enter question."}, 
					question_type:{required:"Please select question type."}, 
				}
			});
		});

		$('#add_button').click(function(){
			val = $("#hdncount").val();
			val++;
			$("#hdncount").val( val);
			$("#answer_content").append('<div class="row" id="'+val+'"> <input type="hidden" name="answer_id[]" id="answer_id_'+val+'" value="0"> <div class="col-md-6"> <div class="form-group"> <input class="form-control" type="text" name="answer[]" id="answer'+val+'" value="" maxlength="100" required> </div> </div> <div class="col-md-6"> <a href="javascript:void(0);" onclick="remove_block(\''+val+'\', \'0\')"><i class="fa fa-trash" style="font-size: 30px;" aria-hidden="true"></i></a> </div> <div class="clearfix"></div> </div>');
		});

		function remove_block(ctrlid, id)
		{
			if( confirm("Are you sure you want to delete the record?") )
			{
				if( id > 0 )
				{
					$.ajax({
						type: "POST",
						url: "<?php echo ADMINURL; ?>ajax_remove_row.php",
						data: 'mode=answer&id='+id,
						success: function(res) {
							$("#"+ctrlid).remove();
							$.notify({message: "Record deleted successfully."}, {type: "success"});
						}
					});
				}
				else 
				{
					$("#"+ctrlid).remove();
				} 
			}
		}
	</script>
</body>
</html>