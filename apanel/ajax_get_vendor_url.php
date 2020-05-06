<?php
include("connect.php");
$ctable     = "url";
$ctable1    = "Url";
$page       = "Url";
$ctable_where = "";

//for sidebar active menu
if(isset($_REQUEST['searchName']) && $_REQUEST['searchName']!=""){
		$ctable_where .= " (
			name like '%".$_REQUEST['searchName']."%' 
		) and";
}

$ctable_where .= " isDelete=0 AND vendor_id=".$_SESSION[SESS_PRE.'_VENDOR_SESS_ID'];
$item_per_page =  ($_REQUEST["show"] <> "" && is_numeric($_REQUEST["show"]) ) ? intval($_REQUEST["show"]) : 10;

if(isset($_REQUEST["page"]) && $_REQUEST["page"]!=""){
	$page_number = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
	if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
}else{
	$page_number = 1; //if there's no page number, set it to 1
}

$get_total_rows = $db->rpgetTotalRecord($ctable,$ctable_where); //hold total records in variable

//break records into pages
$total_pages   = ceil($get_total_rows/$item_per_page);

//get starting position to fetch the records
$page_position = (($page_number-1) * $item_per_page);
$pagiArr       = array($item_per_page, $page_number, $get_total_rows, $total_pages);

$ctable_r = $db->rpgetData($ctable,"*",$ctable_where, "id DESC limit $page_position, $item_per_page");
?>
<form action="" name="frm" id="frm" method="post">
	<input type="hidden" name="hdnmode" value="delete">
	<input type="hidden" name="hdndb" value="<?php echo $ctable; ?>">
		<br>
		<?php
			// $db->getDeleteButton();
			// echo $db->getAddButton1("vendor-url",$ctable1);
		?>
		<table id="example" class="table table-striped table-bordered table-colored table-info">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" name="chkall" id="chkall" onclick="javascript:check_all();"></th> -->
					<th>No.</th>
					<th>Url</th>
					<th>Assigned Team Member</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(@mysqli_num_rows($ctable_r)>0){
					$count = 0;
					while($ctable_d = @mysqli_fetch_array($ctable_r)){
					$count++;
					?>
					<tr>
						<!-- <td><input type="checkbox" name="chkid[]" value="<?php echo $ctable_d['id']; ?>"></td> -->
						<td><?php echo $count+$page_position; ?></td>
						<td><?php echo $ctable_d['url']; ?></td>
						<td><?php if($ctable_d['vendor_team_id'] == null){ echo "-"; }else{ echo $db->rpgetValue("admin","name","isDelete=0 AND id=".$ctable_d['vendor_team_id']); } ?></td>
						<td>
							<a class="btn btn-xs btn-icon waves-effect waves-light btn-success m-b-5" href="<?php echo ADMINURL?>view-url/view/<?php echo $ctable_d['id']; ?>/" title="Website details"><i class="fa fa-eye"></i></a>

							<a class="btn btn-xs btn-icon waves-effect waves-light btn-primary m-b-5" href="<?php echo ADMINURL?>manage-vendor-url-link/<?php echo $ctable_d['id']; ?>/" title="Add Link"><i class="fa fa-plus"></i></a>

							<a class="btn btn-xs btn-icon waves-effect waves-light btn-default m-b-5" href="<?php echo ADMINURL?>assign-url-vendor-team/assign/<?php echo $ctable_d['id']; ?>/" title="Assign Team Member"><i class="fa fa-user"></i></a>
							<!-- <a class="btn btn-xs btn-icon waves-effect waves-light btn-danger m-b-5" onClick="del_conf('<?php echo $ctable_d['id']; ?>');" title="Delete"><i class="fa fa-times"></i></a> -->
						</td>
					</tr>
					<?php
					}
				}
				?>
			</tbody>
		</table>
		<?php 
			$db->rpgetTablePaginationBlock($pagiArr);
			// $db->getDeleteButton();
			// $db->getAddButton("vendor-url",$ctable1);
		?>
</br>
</form>
<script type="text/javascript" async>
function approve(id){
	$.ajax({
		type: "POST",
		url: "<?php echo ADMINURL ?>ajax_user_status_update.php",
		data: {id:id},
		dataType: "json",
		success: function(response){}
	});
}
</script>