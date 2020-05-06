<?php
include("connect.php");
include("../include/notification.class.php");

if(isset($_SESSION[SESS_PRE.'_CLIENT_SESS_ID']))
{
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}

$ctable 		= "url";
$ctable1 		= "Url";
$main_page 		= "Url";
$page 			= "add_".$ctable;
$page_title 	= ucwords($_REQUEST['mode'])." ".$ctable1;

$client_id 			= "";
$url 				= "";
$quntity 			= "";
//$budget 			= "";
$notes 				= "";
$site_desc 			= "";
$category 			= "";
$da_min 			= "";
$fa_min 			= "";
$dr_min 			= "";
$ttf_min 			= "";
$monthly_traffic_min= "";
$ahrefs_rank_min 	= "";
$spam_score_min		= "";
$rank_brand_name 	= "";
$category 			= "";
$backlink_id 	  	= "";
$keyword 			= "";
$link 				= "";
$wantSeo 			= "";

if(isset($_REQUEST['submit']))
{
	$client_id			= $db->clean($_REQUEST['client_id']);
	$url				= $db->clean($_REQUEST['url']);
	$quntity			= $db->clean($_REQUEST['quntity']);
	//$budget				= $db->clean($_REQUEST['budget']);
	$notes				= $db->clean($_REQUEST['notes']);
	$site_desc			= $db->clean($_REQUEST['site_desc']);
	$da_min				= $db->clean($_REQUEST['da_min']);
	$tf_min				= $db->clean($_REQUEST['tf_min']);
	$dr_min				= $db->clean($_REQUEST['dr_min']);
	$ttf_min			= $db->clean($_REQUEST['ttf_min']);
	$monthly_traffic_min= $db->clean($_REQUEST['monthly_traffic_min']);
	$ahrefs_rank_min 	= $db->clean($_REQUEST['ahrefs_rank_min']);
	$spam_score_min		= $db->clean($_REQUEST['spam_score_min']);
	$rank_brand_name 	= $db->clean($_REQUEST['rank_brand_name']);
	$category 			= $_REQUEST['category'];
	$backlink_id 		= $_REQUEST['backlink_id'];
	$keyword 			= $_REQUEST['keyword'];
	$link 				= $_REQUEST['link'];
	$wantSeo 		 	= $db->clean($_REQUEST['wantSeo']) == null ? 0 : $db->clean($_REQUEST['wantSeo']);

	$rows 	= array(
		"client_id"				=> $client_id,
		"url"					=> $url,
		"quntity"				=> $quntity,
		//"budget"				=> $budget,
		"notes"					=> $notes,
		"site_desc"				=> $site_desc,
		"da_min"				=> $da_min,
		"tf_min"				=> $tf_min,
		"dr_min"				=> $dr_min,
		"ttf_min"				=> $ttf_min,
		"monthly_traffic_min"	=> $monthly_traffic_min,
		"ahrefs_rank_min"		=> $ahrefs_rank_min,
		"spam_score_min"		=> $spam_score_min,
		"rank_brand_name"		=> $rank_brand_name,
		"wantSeo"				=> $wantSeo
	);
	
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="edit")
	{
		//Update Url table
		$where	= "id=".$_REQUEST['id'];
		$db->rpupdate($ctable,$rows,$where);

		//Update selected category table
		$db->rpdelete("url_category",$where1);
		for ($i=0; $i < count($category); $i++) { 
			$rows2 = array(
				"url_id" 		=> $_REQUEST['id'],
				"category_id" 	=> $category[$i]
			);
			$db->minsert("url_category",$rows2);
		}

		// Add Backlinks
		for( $i=0; $i<count($keyword); $i++ )
		{
			$row3 = array(
				"client_id"	=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
				"url_id"	=> $_REQUEST['id'],
				"keyword"	=> $keyword[$i],
				"link"		=> $link[$i]
			);
			if( $backlink_id[$i] > 0 )
				$db->rpupdate('backlink', $row3, 'id='. (int) $backlink_id[$i]);
			else
				$db->minsert('backlink', $row3);
		}

		$_SESSION['MSG'] = "Updated";
		$db->rplocation(ADMINURL."manage-client-url/");
		exit;
	}
	if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="add")
	{
		$url_id = $db->minsert($ctable,$rows);

		if($url_id > 0)
		{	
			// Add category
			for ($i=0; $i < count($category); $i++) { 
				$rows2 = array(
					"url_id" 		=> $url_id,
					"category_id" 	=> $category[$i]
				);
				$db->minsert("url_category",$rows2);
			}
			// Add Backlinks
			for( $i=0; $i<count($keyword); $i++ )
			{
				$row3 = array(
					"client_id"	=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
					"url_id"	=> $url_id,
					"keyword"	=> $keyword[$i],
					"link"		=> $link[$i]
				);
				if( $backlink_id[$i] > 0 )
					$db->rpupdate('backlink', $row3, 'id='. (int) $backlink_id[$i]);
				else
					$db->minsert('backlink', $row3);
			}

			// Send Notification to admin
			$row4 = array(
				"to_id" 		=> 1,
				"from_id"		=> $_SESSION[SESS_PRE.'_CLIENT_SESS_ID'],
				"url_id"		=> $url_id,
				"subject_name"	=> "Added New Website",
				"description"	=> "You have recived new website url which is added by client.",
				"link"			=> "add-url/edit/".$url_id
			);
			$db->minsert("notification",$row4);

			if(ISMAIL)
			{
				$nt = new Notification();
				include("../mailbody/client_add_website.php");
				$subject	= SITETITLE." Added new website";
				$toemail = SITEMAIL;
				$nt->rpsendEmail($toemail,$subject,$body);
			}

			$_SESSION['MSG'] = "Inserted";
			$db->rplocation(ADMINURL."manage-client-url/");
		}
		exit;
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="edit")
{
	$where 		= " id='".$_REQUEST['id']."' AND isDelete=0";
	$ctable_r 	= $db->rpgetData($ctable,"*",$where);
	$ctable_d 	= @mysqli_fetch_assoc($ctable_r);

	$url					= stripslashes($ctable_d['url']);
	$quntity				= stripslashes($ctable_d['quntity']);
	//$budget					= stripslashes($ctable_d['budget']);
	$notes					= stripslashes($ctable_d['notes']);
	$site_desc				= stripslashes($ctable_d['site_desc']);
	$da_min					= stripslashes($ctable_d['da_min']);
	$tf_min					= stripslashes($ctable_d['tf_min']);
	$dr_min					= stripslashes($ctable_d['dr_min']);
	$ttf_min				= stripslashes($ctable_d['ttf_min']);
	$monthly_traffic_min 	= stripslashes($ctable_d['monthly_traffic_min']);
	$ahrefs_rank_min	 	= stripslashes($ctable_d['ahrefs_rank_min']);
	$spam_score_min		 	= stripslashes($ctable_d['spam_score_min']);
	$rank_brand_name 	 	= stripslashes($ctable_d['rank_brand_name']);
	$wantSeo 	 			= stripslashes($ctable_d['wantSeo']);

	//Get Category Data
	$where1     = " url_id='".$ctable_d['id']."' AND isDelete=0";
	$ctable_r2  = $db->rpgetData("url_category","*",$where1);
	if(@mysqli_num_rows($ctable_r2)>0)
	{
		$count3 = 0;
		while($ctable_d2 = @mysqli_fetch_array($ctable_r2))
		{
			$count3++;
			$category_array .= $ctable_d2['category_id'].",";
		}
		$category_array = rtrim($category_array,",");
		$d1_array = explode(",", $category_array);
	}
	else
	{
		$category_array = "";
	}
}

if(isset($_REQUEST['id']) && $_REQUEST['id']>0 && $_REQUEST['mode']=="delete")
{
	$id 	= $_REQUEST['id'];
	$rows 	= array("isDelete" => "1");
	
	$db->rpupdate($ctable,$rows,"id='".$id."'");
	$db->rpupdate("notification",$rows,"url_id=".$id);

	$_SESSION['MSG'] = "Deleted";
	$db->rplocation(ADMINURL."manage-client-url/");
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
						<input type="hidden" name="client_id" id="client_id" value="<?php echo $_SESSION[SESS_PRE.'_CLIENT_SESS_ID']; ?>">
						<div class="box-body">
							<div class="box-borderd">
								<label class="box-borderd-label">Enter Website</label>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="url"> Website url <code>*</code></label>
											<input type="text" class="form-control" id="url" name="url" value="<?php echo $url; ?>" >
										</div>
										<div class="form-group">
											<label for="wantSeo"> Check for the SEO</label><br/>
											<input type="checkbox" class="form-group"  name="wantSeo" value="1" <?php if($wantSeo == 1){ echo "checked"; } ?> > I want SEO for these website url.
										</div>
										<div class="form-group">
											<label for="quntity"> Quntity (How many links are you looking for?) <code>*</code></label>
											<input type="text" class="form-control" id="quntity" name="quntity" value="<?php echo $quntity; ?>" >
										</div>
										<!-- <div class="form-group">
											<label for="budget">Budget (What's your budget roughly per link?) <code>*</code></label>
											<input type="text" class="form-control" id="budget" name="budget" value="<?php echo $budget; ?>" >
										</div> -->
										<div class="form-group">
											<label for="notes">Note</label>
											<textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $notes; ?></textarea>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="site_desc">Describe your website and write down the niches of links you'd be intrested in.</label><code>*</code>
											<textarea class="form-control" id="site_desc" name="site_desc" rows="3"><?php echo $site_desc; ?></textarea>
											<div class="desc_error"></div>
										</div>
									</div>
								</div>
							</div><br/>
							<div class="box-borderd">
								<label class="box-borderd-label">Select Categories</label>
								<div class="row">
									<div class="col-sm-12">
										<label for="category_id"> Categories For Url <code>*</code></label>
										<br/>
										<?php 
											$getData = $db->rpgetData("category","*","isDelete=0");
											if(@mysqli_num_rows($getData)>0)
											{
												$no_of_rec = @mysqli_num_rows($getData);
												$div = ceil($no_of_rec / 2);

												for ($i=0; $i < 2 ; $i++) { 
													for($j = 0; $j < $div ; $j++)
													{
														$getData_d = @mysqli_fetch_array($getData);
												?>
												<div class="col-md-3">
													<input <?php if(in_array($getData_d['id'], $d1_array)){ ?> checked <?php } ?>  type="checkbox" id="category_<?php echo $getData_d['id']; ?>" name="category[]" value = "<?php echo $getData_d['id'] ?>" ><?php echo $getData_d['name'] ?>
												</div>
										<?php } }  } ?>
									</div>
								</div><br/>
							</div><br/>
							<div class="box-borderd">
								<label class="box-borderd-label">Minimum Requirements(If you have any minimum metric requirements please enter them below.)</label>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="da_min"> DA </label>
											<input type="text" class="form-control" id="da_min" name="da_min" value="<?php echo $da_min; ?>" >	
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="tf_min"> TF </label>
											<input type="text" class="form-control" id="tf_min" name="tf_min" value="<?php echo $tf_min; ?>" >
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="dr_min"> DR </label>
											<input type="text" class="form-control" id="dr_min" name="dr_min" value="<?php echo $dr_min; ?>" >
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="ttf_min"> TTF </label>
											<input type="text" class="form-control" id="ttf_min" name="ttf_min" value="<?php echo $ttf_min; ?>" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<label for="monthly_traffic_min"> SEMrush Traffic </label>
											<input type="text" class="form-control" id="monthly_traffic_min" name="monthly_traffic_min" value="<?php echo $monthly_traffic_min; ?>" >
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="ahrefs_rank_min"> Ahrefs Rank </label>
											<input type="text" class="form-control" id="ahrefs_rank_min" name="ahrefs_rank_min" value="<?php echo $ahrefs_rank_min; ?>" >
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="spam_score_min"> Spam Score </label>
											<input type="text" class="form-control" id="spam_score_min" name="spam_score_min" value="<?php echo $spam_score_min; ?>" >
										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group">
											<label for="rank_brand_name"> Rank For Brand Name </label>
											<input type="text" class="form-control" id="rank_brand_name" name="rank_brand_name" value="<?php echo $rank_brand_name; ?>" >
										</div>
									</div>
								</div>
							</div><br/>
							<div class="box-borderd">
								<label class="box-borderd-label">Backlinks</label>
								<div id="answer_content">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="keyword"> Keywords <code>*</code></label><br/>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label for="link"> links <code>*</code></label><br/>
											</div>
										</div>
										<div class="col-md-1">
											<div class="form-group">
												<label for="remove"> Action</label><br/>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<?php
										$spec_counter = 0;
										$rs = $db->rpgetData('backlink', '*', 'isDelete=0 AND url_id='.$_REQUEST['id'].' AND client_id='.$_SESSION[SESS_PRE.'_CLIENT_SESS_ID']);
										while( $row = @mysqli_fetch_assoc($rs) )
										{
											$spec_counter++;
									?>
									<div class="row" id="<?php echo $spec_counter; ?>">
										<input type="hidden" name="backlink_id[]" id="backlink_id_<?php echo $spec_counter; ?>" value="<?php echo $row['id'] ?>">
										<div class="col-sm-6">
											<div class="form-group">
												<input type="text" class="form-control" id="keyword<?php echo $spec_counter; ?>" name="keyword[]" value="<?php echo $row['keyword']; ?>" >
											</div>
										</div>
										<div class="col-sm-5">
											<div class="form-group">
												<input type="text" class="form-control" id="link<?php echo $spec_counter; ?>" name="link[]" value="<?php echo $row['link']; ?>" >
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<a href="javascript:void(0);" onclick="remove_block('<?php echo $spec_counter; ?>', '<?php echo $row['id']; ?>')"><i class="fa fa-trash" style="font-size: 30px;" aria-hidden="true"></i></a>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
								<div class="row">
									<div class="col-sm-11"></div>
									<div class="col-sm-1" >
										<input type="hidden" name="hdncount" id="hdncount" value="<?php echo $spec_counter; ?>">
										<button type="button" id="add_button" title="Add Link" class="btn btn-primary"><i class="fa fa-plus"></i></button>
									</div>
								</div>
							</div>

							<div class="box-footer">
								<button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
								<button type="button" class="btn btn-inverse waves-effect w-md waves-light" onClick="window.location.href='<?= ADMINURL ?>manage-client-url/'">Back</button>
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
<script src="<?php echo ADMINURL; ?>bower_components/ckeditor/ckeditor.js" type="text/javascript"></script>
<script>
	// CKEDITOR.replace('notes', { toolbar : 'General' });
	CKEDITOR.replace('site_desc', { toolbar : 'General' });

	$("#add_button").click(function () {
		val = $("#hdncount").val();
		val++;
		$("#hdncount").val( val);
		$("#answer_content").append('<div class="row" id="'+val+'"> <input type="hidden" name="backlink_id[]" id="backlink_id_'+val+'" value="0"> <div class="col-md-6"> <div class="form-group"> <input class="form-control" type="text" name="keyword[]" id="keyword'+val+'" value="" required> </div></div> <div class="col-md-5"> <div class="form-group"> <input class="form-control" type="text" name="link[]" id="link'+val+'" value="" required> </div></div> <div class="col-md-1"> <a href="javascript:void(0);" onclick="remove_block(\''+val+'\', \'0\')"><i class="fa fa-trash" style="font-size: 30px;" aria-hidden="true"></i></a> </div> <div class="clearfix"></div> </div>');
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
					data: 'mode=backlink&id='+id,
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

	$(function(){
		$("#frm").validate({
			ignore: "",
			rules: {
				url:{required:true},
				site_desc:{required:true},
				quntity:{required:true,digits:true},
				//budget:{required:true},
				"category[]":{required:true},
				da_min:{min:30},
				tf_min:{min:20},
				dr_min:{min:30}
			},
			messages: {
				url:{required:"Please enter website url."},
				site_desc:{required:"Please enter site description."},
				quntity:{required:"Please enter quntity.",digits:"Please enter digits."},
				//budget:{required:"Please enter budget."},
				"category[]":{required:"Please select category."},
				da_min:{min:"Please enter min 30."},
				tf_min:{min:"Please enter min 20."},
				dr_min:{min:"Please enter min 30."}
			},
			errorPlacement: function(error, element) {
				if (element.attr("name") == "site_desc") 
				{
					error.insertAfter(".desc_error");
				}
				else if (element.attr("name") == "category[]") 
				{
					error.insertAfter(".category_error");
				}
				else
				{
					error.insertAfter(element);
				}
			}
		});
	});
</script>
</body>
</html>