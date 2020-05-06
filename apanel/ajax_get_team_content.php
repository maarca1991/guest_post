<?php
include("connect.php");
$ctable     = "link_content";
$ctable1    = "Link Content";
$page       = "Link Content";
$ctable_where = "";
$link_id_array = array();

$get_content_head_id = $db->rpgetValue("admin","content_id"," id=".$_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']);

$url_link_id_q = $db->rpgetJoinData("url as u","url_link as ul","u.id = ul.url_id AND ul.isDelete=0 AND content_id=".$get_content_head_id,"ul.id as link_id");
if(@mysqli_num_rows($url_link_id_q)>0){
	while($url_link_id_d = @mysqli_fetch_assoc($url_link_id_q)){
		$link_id_array1 .= array_push($link_id_array,$url_link_id_d['link_id']);
	}
}
$implod_link_array = implode(",",$link_id_array);

//for sidebar active menu
if(isset($_REQUEST['searchName']) && $_REQUEST['searchName']!=""){
	$ctable_where .= " (
		name like '%".$_REQUEST['searchName']."%' 
	) and";
}

$ctable_where .= " isDelete=0 AND assign_content_team_id=".$_SESSION[SESS_PRE.'_CONTENT_TEAM_SESS_ID']." AND link_id IN (".$implod_link_array.")";
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
			// echo $db->getAddButton1("client-content",$ctable1);
		?>
		
		<?php $url_id = $db->rpgetValue("url_link","url_id"," id=".$_REQUEST['link_id']); ?>

		<table id="example" class="table table-striped table-bordered table-colored table-info">
			<thead>
				<tr>
					<!-- <th><input type="checkbox" name="chkall" id="chkall" onclick="javascript:check_all();"></th> -->
					<th>No.</th>
					<th>Link</th>
					<th>Assigned Team Member</th>
					<th>Status</th>
					<th>Uploaded Date</th>
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
					?>
					<tr>
						<!-- <td><input type="checkbox" name="chkid[]" value="<?php echo $ctable_d['id']; ?>"></td> -->
						<td><?php echo $count+$page_position; ?></td>
						<td><?php echo $db->rpgetValue("url_link","link","id=".$ctable_d['link_id']); ?></td>
						<td><?php if($ctable_d['assign_content_team_id'] == null){ echo "-"; }else{ echo $db->rpgetValue("admin","name","id=".$ctable_d['assign_content_team_id']); } ?></td>
						<td><?php echo $status; ?></td>
						<td><?php echo $db->rpDate($ctable_d['adate'],"m/d/Y H:i A"); ?></td>
						<td><?php 
							if($ctable_d['clientEditDate'] != null){ 
								echo "<b style='color:#00a65a;'>".$db->rpgetValue("admin","name","id=".$ctable_d['client_id'])."</b>"; 
							}elseif($ctable_d['contentEditDate'] != null){
								echo "<b style='color:#367fa9;'>".$db->rpgetValue("admin","name","id=".$ctable_d['content_id'])."</b>";
							}elseif($ctable_d['contentTeamEditDate'] != null){
								echo "<b style='color:#367fa9;'>".$db->rpgetValue("admin","name","id=".$ctable_d['content_team_id'])."</b>"; 
							}else{
								echo "-";
							}
						?></td>
						<td><?php 
							if($ctable_d['clientEditDate'] != null){ 
								echo $db->rpDate($ctable_d['clientEditDate'],"m/d/Y H:i A"); 
							}elseif($ctable_d['contentEditDate'] != null){
								echo $db->rpDate($ctable_d['contentEditDate'],"m/d/Y H:i A"); 
							}elseif($ctable_d['contentTeamEditDate'] != null){
								echo $db->rpDate($ctable_d['contentTeamEditDate'],"m/d/Y H:i A");
							}else{
								echo "-";
							}
						?></td>
						<td>
							<?php if($ctable_d['status'] == 0){ ?>
							<a class="btn btn-xs btn-icon waves-effect waves-light btn-primary m-b-5" href="<?php echo ADMINURL?>approve-team-content/edit/<?php echo $ctable_d['id']; ?>/" title="Content Approval"><i class="fa fa-pencil"></i></a>
							<?php }else{ ?>
								<a class="btn btn-xs btn-icon waves-effect waves-light btn-success m-b-5" href="<?php echo ADMINURL?>approve-team-content/view/<?php echo $ctable_d['id']; ?>/" title="View Content"><i class="fa fa-eye"></i></a>

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
			// $db->getAddButton("client-content",$ctable1);
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