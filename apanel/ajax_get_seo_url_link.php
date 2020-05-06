<?php
include("connect.php");
$ctable     = "url_link";
$ctable1    = "Url Link";
$page       = "Url Link";
$ctable_where = "";
$url_id 	= $_REQUEST['url_id'];
//for sidebar active menu
if(isset($_REQUEST['searchName']) && $_REQUEST['searchName']!=""){
	$ctable_where .= " (
		name like '%".$_REQUEST['searchName']."%' 
	) and";
}

$ctable_where .= " isDelete=0 AND url_id=".$url_id;
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
			// echo $db->getAddButton1("client-url-link",$ctable1);
		?>
		<button type="button" class="btn btn-inverse sidebar m-t-10" style="margin-bottom: 5px;" onclick="window.location.href='<?php echo ADMINURL; ?>manage-seo-url/'" title="back"><i class="fa fa-reply"></i></button>

		<table id="example" class="table table-striped table-bordered table-colored table-info">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" name="chkall" id="chkall" onclick="javascript:check_all();"></th> -->
					<th>No.</th>
					<th>Url</th>
					<th>Link</th>
					<th>Status</th>
					<th>Uploded Date</th>
					<th>Approved By</th>
					<th>Approval Date</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(@mysqli_num_rows($ctable_r)>0){
					$count = 0;
					while($ctable_d = @mysqli_fetch_array($ctable_r)){
					$count++;
					if($ctable_d['status'] == 0){
						$status = '<div class="btn btn-xs btn-icon waves-effect waves-light btn-warning m-b-5" title="Pending"><i class="fa fa-clock-o"></i></div>';
					}else if($ctable_d['status'] == 1){
						$status = '<div class="btn btn-xs btn-icon waves-effect waves-light btn-success m-b-5" title="Approved"><i class="fa fa-check"></i></div>';
					}else if($ctable_d['status'] == 2){
						$status = '<div class="btn btn-xs btn-icon waves-effect waves-light btn-danger m-b-5" title="Rejected"><i class="fa fa-ban"></i></div>';
					}
					$where = " isDelete = 0 AND id =".$ctable_d['url_id'];
					?>
					<tr>
						<!-- <td><input type="checkbox" name="chkid[]" value="<?php echo $ctable_d['id']; ?>"></td> -->
						<td><?php echo $count+$page_position; ?></td>
						<td><?php echo $db->rpgetValue("url","url",$where); ?></td>
						<td><?php echo $ctable_d['link']; ?></td>
						<td><?php echo $status; ?></td>
						<td><?php echo $db->rpDate($ctable_d['adate'],"m/d/Y H:i A"); ?></td>
						<td><?php 
							if($ctable_d['clientEditDate'] != null){ 
								$client_id = $db->rpgetValue("url","client_id","id=".$ctable_d['url_id']);
								echo "<b style='color:#00a65a;'>".$db->rpgetValue("admin","name","id=".$client_id)."</b>";
							}elseif($ctable_d['seoEditDate'] != null){
								$seo_id = "<b style='color:#367fa9;'>".$db->rpgetValue("url","seo_id","id=".$ctable_d['url_id'])."</b>";
								echo $db->rpgetValue("admin","name","id=".$seo_id);
							}elseif($ctable_d['seoTeamEditDate'] != null){
								$seo_team_id = $db->rpgetValue("url","seo_team_id","id=".$ctable_d['url_id']);
								echo "<b style='color:#367fa9;'>".$db->rpgetValue("admin","name","id=".$seo_team_id)."</b>"; 
							}else{
								echo "-";
							}
						?></td>
						<td><?php 
							if($ctable_d['clientEditDate'] != null){ 
								echo $db->rpDate($ctable_d['clientEditDate'],"m/d/Y H:i A");
							}elseif($ctable_d['seoEditDate'] != null){
								echo $db->rpDate($ctable_d['seoEditDate'],"m/d/Y H:i A");
							}elseif($ctable_d['seoTeamEditDate'] != null){
								echo $db->rpDate($ctable_d['seoTeamEditDate'],"m/d/Y H:i A");
							}else{
								echo "-";
							}
						?></td>
						<td>
							<?php if($ctable_d['status'] == 0 ){ ?>
							<a class="btn btn-xs btn-icon waves-effect waves-light btn-primary m-b-5" href="<?php echo ADMINURL?>add-seo-url-link/edit/<?php echo $ctable_d['id']; ?>/" title="Edit Link"><i class="fa fa-pencil"></i></a>
							<?php }else{ ?>
							<a class="btn btn-xs btn-icon waves-effect waves-light btn-success m-b-5" href="<?php echo ADMINURL?>add-seo-url-link/view/<?php echo $ctable_d['id']; ?>/" title="View Link"><i class="fa fa-eye"></i></a>
							<?php } ?>
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
			// $db->getAddButton("client-url-link",$ctable1);
		?>
		<button type="button" class="btn btn-inverse sidebar m-t-10" style="margin-bottom: 5px;" onclick="window.location.href='<?php echo ADMINURL; ?>manage-seo-url/'" title="back"><i class="fa fa-reply"></i></button>
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