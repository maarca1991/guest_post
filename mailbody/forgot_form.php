<?php
$bg_img = SITEURL."mailbody/images/bg1.jpg";
$ta = "margin:0 auto;background-image:url(".$bg_img.");background-repeat: no-repeat;background-size: cover; padding: 10px 20px; color: #404040; font-family: lato";


$body = '<table width="600px" border="0" style="'.$ta.'">
	<tr>
		<td style="padding-bottom: 50px;border:none; text-align: center;"><img src="'.SITEURL.'images/logo.png" style="width:150px;margin-top:13px;"></td>
	</tr>
	<tr>
		<td style="padding:30px 0px 0px 0px; background-color: #fff;border:none; border-radius: 5px;">
			<table width="100%" border="0" style="text-align: center">
				
				<tr>
					<td style="padding:0 84px 30px;">
						<p>Hello '.$get_data['name'].'</p>
						<p>We have received a request to reset your password</p>
						<p>You can reset your password by <a href="'.SITEURL.'set-new-password/'.$fps.'/" style="font-size:13px;color:#00baf0;text-decoration:none" target="_blank" data-saferedirecturl="'.SITEURL.'set-new-password/'.$fps.'/"> clicking here </a></p>
					</td>
				</tr>
				<tr>
					<td style="padding:0px 47px 30px;font-size: 14px; font-weight:normal;line-height: 20px;">If you have any questions, please let us know by email <a href="mailto:'.SITEMAIL.'" style="text-decoration: none;color: #0058a9;">'.SITEMAIL.'</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>';
?>
