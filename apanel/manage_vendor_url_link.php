<?php
include("connect.php");
if(isset($_SESSION[SESS_PRE.'_VENDOR_SESS_ID'])){
	$db->rpcheckAdminLogin();
}else{
	$db->rpcheckAdminNotLogin();
}
$ctable 	= "url_link";
$ctable1 	= "Url Link";
$main_page 	= "Url Link"; //for sidebar active menu
$page_title = "Manage ".$ctable1;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title?> | <?php echo ADMINTITLE; ?></title>
	<?php include('include_css.php'); ?>
	<link rel="stylesheet" href="<?php echo ADMINURL?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
		<input type="hidden" name="url_id" id="url_id" value="<?php echo $_REQUEST['id']; ?>">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<!-- For Table 1 -->
					<div class="box-header with-border">
						<div class="col-md-12 row">
							<form action="#" onSubmit="return searchByName();">
								<div class="form-group col-md-3">
									<input type="text" value="" name="searchName" class="form-control" placeholder="Search Here..." id="searchName" value="">
								</div>
								<div class="col-md-3 row">
									<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									<button type="submit" class="btn btn-danger" title="Clear Search Result" value="clear" onClick="clearSearchByName();"><i class="fa fa-close"></i></button>
									<a onClick="window.location.reload()" class="btn btn-success" title="Reload Page"><i class="fa fa-refresh"></i></a>
								</div>
							</form>
						</div>
					</div>
					<div class="box-body no-padding">
						<div class="col-md-12 table-responsive">
							<div class="loading-div" style="display:none;">
								<div><img style="width:10%" src="<?php echo SITEURL?>images/loader.svg"></div>
							</div>
							<div id="results"></div>
						</div>
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
<script src="<?php echo ADMINURL?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo ADMINURL?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
	/*Table 1 */
	var searchName="";
	function searchByName(){
		searchName = $("#searchName").val();
		displayRecords(10,1);
		return false;
	}
	function clearSearchByName(){
		searchName = "";
		$("#searchName").val("");
		displayRecords(10,1);
	}
	$("#searchName").keyup(function(event){
		if(event.keyCode == 13){
			$("#searchByName").click();
		}
	});
	function loadDataTable(data_url,page=""){
		setTimeout(function(){
			$("#results" ).load( data_url,{"page":page},function(){
				$('#example').DataTable({
					"bPaginate": false,
					"bFilter": false,
					"bInfo": false,
					"bAutoWidth": false, 
					"aoColumns": [
						// { "sWidth": "5%", "bSortable": false }, 
						{ "sWidth": "5%" },
						{ "sWidth": "20%" },
						{ "sWidth": "25%" },
						{ "sWidth": "5%" },
						{ "sWidth": "10%" },
						{ "sWidth": "15%" },
						{ "sWidth": "10%" },
						{ "sWidth": "10%","bSortable": false }
					]
				});
				$(".loading-div").fadeOut(500);
				$("#results").fadeIn();
			}); //load initial records
		},1500);
	}
	function displayRecords(numRecords) {
		var searchName 	= $("#searchName").val();
		searchName 	    = encodeURIComponent(searchName.trim());
		var url_id = $("#url_id").val();
		var data_url    = "<?php echo ADMINURL?>ajax_get_vendor_url_link.php?show=" + numRecords + "&searchName=" + searchName + "&url_id=" + url_id;
		$("#results").html("");
		$(".loading-div").show();
		loadDataTable(data_url);
		
		//executes code below when user click on pagination links
		$("#results").on("click",".paging_simple_numbers a", function (e){
			e.preventDefault();
			var numRecords  = $("#numRecords").val();
			$(".loading-div").show(); //show loading element
			var page = $(this).attr("data-page"); //get page number from link
			loadDataTable(data_url,page);
		});
		$("#results").on( "change", "#numRecords", function (e){
			e.preventDefault();
			var numRecords  = $("#numRecords").val();
			$(".loading-div").show(); //show loading element
			var page = $(this).attr("data-page"); //get page number from link
			loadDataTable(data_url,page);
		});
	}

	// used when user change row limit
	function changeDisplayRowCount(numRecords) {
		displayRecords(numRecords, 1);
	}

	$(document).ready(function() {
		displayRecords(10,1);
	});

	function del_conf(id){
		var r = confirm("Are you sure you want to delete?");
		if(r){
			window.location.href='<?php echo ADMINURL?>add-vendor-url-link/delete/'+id+'/';
		}
	}

</script>