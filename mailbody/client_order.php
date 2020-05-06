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
						<p>Your order with amount <b>' .EURO.$grand_total. '</b> is successfully placed.</p>
						<p>Please contact site administrator if you have any query regarding the order.</p>
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
						<td><b>Order Number</b></td>
						<td><span class="text-muted">'.$order_no.'</td>
						<td><b>Sub Total</b></td>
						<td><span class="text-muted">€'.$sub_total.'</td>
						
					</tr>
					<tr>
						<td><b>Order Status</b></td>
						<td><span class="text-muted">'.$order_status.'</td>
						<td><b>Tax</b></td>
						<td><span class="text-muted">€'.$tax.'</td>
					</tr>
					<tr>
						<td><b>Order Date</b></td>
						<td><span class="text-muted">'.$order_date.'</td>
						<td><b>Grand Total</b></td>
						<td><span class="text-muted">€'.$grand_total.'</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>

	<tr><td style="line-height:5px;">&nbsp;</td></tr>
	<tr>
		<td style="padding:20px; background-color: #fff;border:none; border-radius: 5px;">
			<table class="table table-striped" width="100%" style=" border-collapse:inherit;border-color:black;">
				<thead>
					<tr>
						<th>No</th>
						<th align="left">Label Name</th>
						<th>Quantity</th>
						<th>Period</th>
						<th>Price</th>
					</tr>
				</thead>';
				$ctable_r2 = $db->rpgetData("cart_detail","*"," cart_id=".$cart_det_id);
				if(@mysqli_num_rows($ctable_r2)>0){
				$count = 0;
				while($ctable_d2 = @mysqli_fetch_array($ctable_r2))
				{
					$count++;

				$body.='<tbody>
					<tr>
						<td align="center" scope="row" style="vertical-align:text-top;">'. $count .'</td>
						<td style="vertical-align:text-top;">
							<b>'. $db->rpgetValue("labels","label","labels_id=".$ctable_d2['label_id']) .'</b><br>
						</td>
						<td align="center" style="vertical-align:text-top;">
							<label>'.$ctable_d2['quntity'] .'</label>
						</td>
						<td align="center" style="vertical-align:text-top;">
							<label>'.$ctable_d2['period'] .'</label>
						</td>
						<td align="center" style="vertical-align:text-top;">
							<label>€'. $ctable_d2['total'] .'</label>
						</td>
					</tr>
				';
				} } $body.='
				<tr>
					<td colspan="6" style="font-size: 14px; font-weight:normal;padding:20px;line-height: 20px;">
						Greetings!
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
';
?>