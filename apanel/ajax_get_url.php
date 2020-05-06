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

$ctable_where .= " u.isDelete=0 ";
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

// $ctable_r = $db->rpgetJoinData("$ctable as u","admin as c","c.id = u.client_id","u.id as id, u.url as url, u.isDelete as isDelete, c.name as name, c.email as email ",$ctable_where, "u.id DESC limit $page_position, $item_per_page");
$ctable_r = $db->rpgetJoinData("$ctable as u","admin as c","c.id = u.client_id","u.id as id,u.vendor_id as vendor_id,u.vendor_assign_date as vendor_assign_date , u.url as url, u.isDelete as isDelete, c.name as name, c.email as email ",$ctable_where, "u.id DESC limit $page_position, $item_per_page");

// $ctable_r = $db->rpgetData($ctable,"*",$ctable_where, "id DESC limit $page_position, $item_per_page");
?>
<form action="" name="frm" id="frm" method="post">
	<input type="hidden" name="hdnmode" value="delete">
	<input type="hidden" name="hdndb" value="<?php echo $ctable; ?>">
		<br>
		<?php
			$db->getDeleteButton();
			echo $db->getAddButton1("url",$ctable1);
		?>
		<table id="example" class="table table-striped table-bordered table-colored table-info">
			<thead>
				<tr>
					<th><input type="checkbox" name="chkall" id="chkall" onclick="javascript:check_all();"></th>
					<th>No.</th>
					<th>Url</th>
					<th>Client</th>
					<th>Vendor</th>
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
						<td><input type="checkbox" name="chkid[]" value="<?php echo $ctable_d['id']; ?>"></td>
						<td><?php echo $count+$page_position; ?></td>
						<td><?php echo $ctable_d['url']; ?></td>
						<td><?php echo $ctable_d['name']; ?></td>
						<td><?php if($ctable_d['vendor_id'] == null){ echo "-"; }else{ echo $db->rpgetValue("admin","name","id=".$ctable_d['vendor_id']); } ?></td>
						<td>
                    		<a class="btn btn-xs btn-icon waves-effect waves-light btn-primary m-b-5" href="<?php echo ADMINURL?>add-url/edit/<?php echo $ctable_d['id']; ?>/" title="Edit"><i class="fa fa-pencil"></i></a>
                    
							<a class="btn btn-xs btn-icon waves-effect waves-light btn-danger m-b-5" onClick="del_conf('<?php echo $ctable_d['id']; ?>');" title="Delete"><i class="fa fa-times"></i></a>

							<a class="btn btn-xs btn-icon waves-effect waves-light btn-success m-b-5" href="<?php echo ADMINURL?>view-report/view/<?php echo $ctable_d['id']; ?>/" title="View Report"><i class="fa fa-eye"></i></a>

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
			$db->getDeleteButton();
			$db->getAddButton("url",$ctable1);
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