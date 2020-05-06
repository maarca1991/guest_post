<?php
$bg_img = SITEURL."mailbody/images/bg1.jpg";
$ta = "margin:0 auto;background-image:url(".$bg_img.");background-repeat: no-repeat;background-size: cover; padding: 10px 20px; font-family: lato";
$body = '
<table width="900px" style="'.$ta.'">
	<tr>
		<td style="padding-bottom: 50px;border:none; text-align: center;"><img src="'.SITEURL.'images/logo.png" style="width:150px;margin-top:13px;"></td>
	</tr>
	<tr>
		<td style="padding:20px 0px 20px 0px; background-color: #fff;border:none; border-radius: 5px;">
			<table width="100%" style="text-align: center">
				<tr>
					<td style="font-size: 16px; font-weight:700;padding:0px 50px 5px;">
						<p>Hello Admin,</p>
						<p>You have received new label which is created by '.$user_data_d["name"].'</p>
						<p>Details are below.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td style="line-height:5px;">&nbsp;</td></tr>
	<tr>
		<td style="padding:20px; background-color: #fff;border:none; border-radius: 5px;">
			<table width="100%" style="text-align: left; border-collapse:inherit;border-color:black;" id="user" class="table table-bordered table-striped">
				<tbody>
					<tr>
						<td><b>Label Image</b></td>
						<td><img src='.$label_file.' class="label-img" height="150px" width="250px"></td>
					</tr>
					<tr>
						<td><b>User Email</b></td>
						<td><span class="text-muted">'.$user_data_d["email"].'</td>
						<td><b>Label Name</b></td>
						<td><span class="text-muted">'.$label.'</td>
					</tr>
					<tr>
						<td><b>Upper circumference C1</b></td>
						<td><span class="text-muted">'.$upcirc.'</td>
						<td><b>Upper circumference C2</b></td>
						<td><span class="text-muted">'.$downcirc.'</td>
					</tr>
					<tr>
						<td><b>Height h</b></td>
						<td><span class="text-muted">'.$height.'</td>
						<td><b>Width w</b></td>
						<td><span class="text-muted">'.$weight.'</td>
					</tr>
					'; if($b1 != ""){ $body .='
					<tr>
						<td><b>Button 1</b></td>
						<td><span class="text-muted">'.$b1.'</td>
						<td><b>Video 1</b></td>
						<td><a target="_blank" href='.$video_file_1.'>Video 1 link</td>
					</tr>';}  if($b2 != ""){ $body .='
					<tr>
						<td><b>Button 2</b></td>
						<td><span class="text-muted">'.$b2.'</td>
						<td><b>Video 2</b></td>
						<td><a target="_blank" href='.$video_file_2.'>Video 2 link</td>
					</tr>';}  if($b3 != ""){ $body .='
					<tr>
						<td><b>Button 3</b></td>
						<td><span class="text-muted">'.$b3.'</td>
						<td><b>Video 3</b></td>
						<td><a target="_blank" href='.$video_file_3.'>Video 3 link</td>
					</tr>';}  if($b4 != ""){ $body .='
					<tr>
						<td><b>Button 4</b></td>
						<td><span class="text-muted">'.$b4.'</td>
						<td><b>Video 4</b></td>
						<td><a target="_blank" href='.$video_file_4.'>Video 4 link</td>
					</tr>'; } $body .='
				</tbody>
			</table>
		</td>
	</tr>

	<tr><td style="line-height:5px;">&nbsp;</td></tr>
</table>
';
?>