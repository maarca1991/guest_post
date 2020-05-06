<?php
class Cart extends Functions
{
	/*
		*** Cart Function Developed By Ravi Patel :) <<<
	*/
	
	public function rpgetCartTotalItem() // Total no. of products in cart
    {
		if(isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID']>0){
			return parent::rpgetTotalRecord("cartitems","cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'");
		}else{
			return 0;
		}
    }
	public function rpgetCartSubTotalPrice() // Total Price of cart
    {	
		if(isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID']>0)
		{
			$shop_cart_r = parent::rpgetData("cartitems","*","cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'");
			if(@mysqli_num_rows($shop_cart_r)>0)
			{
				$discount = 0;
				$sub_total= 0;
				$total_ship_charge= 0;
				$total_pro_w = 0;
				$total_pro_amt = 0;
				while($shop_cart_d = @mysqli_fetch_array($shop_cart_r))
				{
					$id			= $shop_cart_d['id'];
					$pid 		= $shop_cart_d['pid'];
					$qty 		= $shop_cart_d['qty'];
					$unitprice 	= parent::rpnum($shop_cart_d['unitprice']);
					
					
			
			
					$id			= $shop_cart_d['id'];
					$pid 		= $shop_cart_d['pid'];
					$pro_r 		=parent::rpgetData("product","*","id='".$pid."'");
					
					if(@mysqli_num_rows($pro_r)>0){
						$pro_d 		= @mysqli_fetch_array($pro_r);
						$cid 		= stripslashes($pro_d['cid']);
					
						
					}
					$pro_weight = $shop_cart_d['pro_weight'];
					$totalprice = parent::rpnum($shop_cart_d['totalprice']);
					$sub_total 	+= $totalprice;
					
					if( $cid == 1 )
					{
						
						$total_pro_w += ($pro_weight * $qty);
						$total_pro_amt += $totalprice;
					}
					
					
				}
				
				$discount = 0;
				$disc_check_r = parent::rpgetData("discount","*","cat_id=1 AND isDelete=0","min_w");
				if(@@mysqli_num_rows($disc_check_r)>0){
					while($disc_check_d = @@mysqli_fetch_array($disc_check_r))
					{			
					$discount_type 	= $disc_check_d['disc_type'];
					$discount_amount= $disc_check_d['discount'];
					$min_w			= $disc_check_d['min_w'];
					if($discount_type==1){
						 $discount = parent::rpnum(($total_pro_amt*$discount_amount)/100);
					}else{
						$discount = parent::rpnum($discount_amount);
					}
					if($total_pro_w<$min_w){
						$discount = 0;
					}}
				}
				//echo $discount;
				
				
				$sub_total 			=parent::rpnum($sub_total);
				$shipping_charge 	=parent::rpnum(0);
				$tax = parent::rpnum(0.00);
				$final_total =parent::rpnum(($sub_total + $shipping_charge + $tax) - $discount);
				return  $final_total;
			}
			else
			{
				return parent::rpnum(0);
			}
    	}
		else
		{
			return parent::rpnum(0);
		}
	}
	public function rpgetCartSubTotalPrice_OLD() // Total Price of cart
    {
		if(isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID']>0){
			$t = parent::rpgetSumVal("cartitems","finalprice","cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'");
			return parent::rpnum($t);
		}else{
			return parent::rpnum(0);
		}
    }
	
	public function rpcheckCartQuantity(){
		$q = 1;
		$shop_cart_r = parent::rpgetData("cartitems","*","cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'",'');
		if(@mysqli_num_rows($shop_cart_r)>0){
			while($shop_cart_d = @mysqli_fetch_array($shop_cart_r)){
				$pid 		= $shop_cart_d['pid'];
				$qty 		= $shop_cart_d['qty'];
				$pro_qty 	= parent::rpgetValue("product","qty","id='".$pid."'");
				if($pro_qty<=0 || $pro_qty<$qty){
					$q = 0;
				}
			}
			if($q>0){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function rpupdateCartQuantity() //update pro qty after succcessfull order
	{
		$shop_cart_r = parent::rpgetData("cartitems","*","cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'");
		if(@mysqli_num_rows($shop_cart_r)>0){
			while($shop_cart_d = @mysqli_fetch_array($shop_cart_r)){
				$pid 		= $shop_cart_d['pid'];
				$qty 		= $shop_cart_d['qty'];
				$pro_qty 	= parent::rpgetValue("product","qty","id='".$pid."'");
				$new_qty = intval($pro_qty)-intval($qty);
				if($new_qty==0){
					$rows 	= array(
						"qty"		=> $new_qty,
						"status"	=> "1",
					);
				}else{
					$rows 	= array(
						"qty"		=> $new_qty,
					);
				}
				
				$where	= "id='".$pid."'";
				parent::rpupdate("product",$rows,$where);
				
			}
		}
	}
	
	public function rpgetDiscountAmount($disc_type,$discount,$totalprice){ // $disc_type : 0=flat, 1=perc
		if($disc_type==0){
			return $discount;
		}else{
			$discount_amt = $totalprice*($discount/100);
			return $discount_amt;
		}
	}
	
	public function rpcheckQtyToAddInCart($pid,$qty,$type){ //check product qty before add to cart
		$curr_qty = parent::rpgetProductQty($pid);
		
		if($type==2){
			$curr_cart_qty = 0;
		}else{
			if(isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID']>0){
				$curr_cart_qty = parent::rpgetValue("cartitems","qty","pid='".$pid."' AND cart_id='".$_SESSION[SESS_PRE.'_SESS_CART_ID']."'");
			}else{
				$curr_cart_qty = 0;
			}
		}
		
		$qty1 = $curr_cart_qty+$qty;
		
		if($qty1 > $curr_qty){
			return 0;
		}else{
			return 1;
		}
	}
	
	public function rprcorder($rcOrder,$cart_id){ // return or cancel order
		//Get Cart Details
		$cart_details_r = parent::rpgetData("cartdetails","*","cart_id='".$cart_id."'");
		$cart_details_d = @mysqli_fetch_array($cart_details_r);
		$order_date 	= $cart_details_d["orderdate"];
		$order_status 	= $cart_details_d["orderstatus"];
		
		if($rcOrder==$cart_id+6){ // Ret
			// Return Order
			//return $this->rpreturnOrder($cart_id,$order_date,$order_status);
			$_SESSION['return_ty']			= 0;
			$_SESSION['return_cart_id'] 	= $cart_id;
			$_SESSION['return_cartitem_id'] = 0;
			return "ret";
		}else if($rcOrder==$cart_id+9){ //Can
			// Cancel Order
			//return $this->rpcancelOrder($cart_id,$order_date,$order_status);
			$_SESSION['cancel_ty']			= 0;
			$_SESSION['cancel_cart_id'] 	= $cart_id;
			$_SESSION['cancel_cartitem_id'] = 0;
			return "can";
		}else{
			return "Something went wrong. Please try again or you can contact our customer care.";
		}
	}
	
	public function rprcorder_history($cart_id,$from_status,$to_status){ // Save RCOrder History Starts
		/****RCOrder Item Starts****/
		$today_date	= date('Y-m-d H:i:s');
		$shop_cart_r= parent::rpgetData("cartitems","*","cart_id='".$cart_id."'");
		if(@mysqli_num_rows($shop_cart_r)>0){
			while($shop_cart_d = @mysqli_fetch_array($shop_cart_r)){
				$cart_item_id 	= $shop_cart_d['id'];
				$uid 			= $shop_cart_d['uid'];
				$pid 			= $shop_cart_d['pid'];
				$subpid 		= $shop_cart_d['subpid'];
				$cdrows 	= array(
						"cart_id",
						"cart_item_id",
						"pid",
						"subpid",
						"uid",
						"from_status",
						"to_status",
						"rcdate",
					);
				$cdvalues = array(
						$cart_id,
						$cart_item_id,
						$pid,
						$subpid,
						$uid,
						$from_status,
						$to_status,
						$today_date,
					);
				parent::rpinsert("rcorder_items",$cdvalues,$cdrows);
				/****Update Cartitem Starts****/
				$rows 	= array(
						"rcdate"		=> $today_date,
						"orderstatus"	=> $to_status,
					);
				$where	= "id='".$cart_item_id."'";
				parent::rpupdate("cartitems",$rows,$where);
				/****Update Cartitem Ends****/
			}
		}
		/****RCOrder Item Ends****/
		/****RCOrder Starts****/
		$uid = parent::rpgetValue("cartdetails","uid","cart_id='".$cart_id."'");
		$cdrows 	= array(
				"cart_id",
				"uid",
				"from_status",
				"to_status",
				"rcdate",
			);
		$cdvalues = array(
				$cart_id,
				$uid,
				$from_status,
				$to_status,
				$today_date,
			);
		$rcid = parent::rpinsert("rcorder",$cdvalues,$cdrows);
		return $rcid;
		/****RCOrder Ends****/
	}
	
	public function rprcSingleOrder_history($cart_id,$cartitem_id,$from_status,$to_status){ // Save RCSingleOrder History Starts
		/****RCOrder Single Item Starts****/
		$today_date	= date('Y-m-d H:i:s');
		$shop_cart_r= parent::rpgetData("cartitems","*","cart_id='".$cart_id."' AND id='".$cartitem_id."'");
		if(@mysqli_num_rows($shop_cart_r)>0){
			$shop_cart_d = @mysqli_fetch_array($shop_cart_r);
			$cart_item_id 	= $shop_cart_d['id'];
			$uid 			= $shop_cart_d['uid'];
			$pid 			= $shop_cart_d['pid'];
			$subpid 		= $shop_cart_d['subpid'];
			$cdrows 	= array(
					"cart_id",
					"cart_item_id",
					"pid",
					"subpid",
					"uid",
					"from_status",
					"to_status",
					"rcdate",
				);
			$cdvalues = array(
					$cart_id,
					$cartitem_id,
					$pid,
					$subpid,
					$uid,
					$from_status,
					$to_status,
					$today_date,
				);
			$rcsid = parent::rpinsert("rcorder_items",$cdvalues,$cdrows);
			return $rcsid;
		}
		/****RCOrder Single Item Ends****/
	}
	
	public function rpreturnOrder($cart_id,$order_date,$order_status){ // Return Full Order
		$last_date_to_return= date('Y-m-d', strtotime($order_date." +".RETURN_HOURS." hours"));
		$today_date			= date('Y-m-d');
		if(strtotime($today_date)<=strtotime($last_date_to_return)){
			if($order_status==4){
				/***Save History Starts***/
				$rcid = $this->rprcorder_history($cart_id,$order_status,"5");	
				/***Save History Ends***/
				$today_date1	= date('Y-m-d H:i:s');
				$rows 	= array(
						"rcdate"		=> $today_date1,
						"orderstatus"	=> "5",
					);
				$where	= "cart_id='".$cart_id."'";
				parent::rpupdate("cartdetails",$rows,$where);
				$fn = new parent;
				$nt = new notification($fn);
				/**Send SMS**/
				$smsMsg = "We've receive your return request from order #".$cart_id.". We can reject or accept your request based on conditions ";
				$nt->rpsendSMS2($cart_id,$smsMsg,SMSPROMOTEXT);
				/**Send SMS**/
				
				/*******************************************************/
				$toemail 	= $db->rpgetValue("cartdetails","email","cart_id='".$cart_id."'");
				$subject	= SITENAME." - Order #".$cart_id." Return Request";
				$body = file_get_contents(SITEURL.'mailbody/return_whole_order_request.php?cart_id='.$cart_id.'');
				$nt->rpsendGenEmail($toemail,$subject,$body);
				/*******************************************************/
				
				$_SESSION['rcid'] = $rcid;
				return "Your order return request has been placed successfully.";
				
			}else{
				return "Your order is not delivered yet. So you can not return the order. Instead of that you can cancel your order. If you have any query than please contact our customer care.";
			}
		}else{
			return "Last date to 'Return Order' is already passed. You can not return order. If you have any query than please contact our customer care.";
		}
	}
	
	public function rpcancelOrder($cart_id,$order_date,$order_status){ // update shipping charge in cart if Pincode avail
		$last_date_to_return= date('Y-m-d', strtotime($order_date." +".RETURN_HOURS." hours"));
		$today_date			= date('Y-m-d');
		if($order_status==2 || $order_status==3){
			if(strtotime($today_date)<=strtotime($last_date_to_return)){
				
				/**Update Qty Starts**/
				$this->rprcQtyUpdate($cart_id);
				/**Update Qty Ends**/
				
				/***Save History Starts***/
				$rcid = $this->rprcorder_history($cart_id,$order_status,"0");	
				/***Save History Ends***/
				
				$today_date1	= date('Y-m-d H:i:s');
				$rows 	= array(
						"rcdate"		=> $today_date1,
						"orderstatus"	=> "0",
					);
				$where	= "cart_id='".$cart_id."'";
				parent::rpupdate("cartdetails",$rows,$where);
				
				$fn = new parent;
				$nt = new notification($fn); 
				/**Send SMS**/
				$smsMsg = "We've receive your cancel request for order #".$cart_id.". You can check your updated order in your Account. ";
				$nt->rpsendSMS2($cart_id,$smsMsg,SMSPROMOTEXT);
				/**Send SMS**/
				
				/**Send Email**/
				$subject	= SITENAME." - Order #".$cart_id." Cancelled";
				$toemail 	= parent::rpgetValue("cartdetails","email","cart_id='".$cart_id."'");
				$body = file_get_contents(SITEURL.'mailbody/cancel_whole_order.php?cart_id='.$cart_id.'');
				$nt->rpsendGenEmail($toemail,$subject,$body);
				/**Send Email**/
				
				$_SESSION['rcid'] = $rcid;
				return "Your order has been cancelled successfully.";
			}else{
				return "Last date to 'Cancel Order' is already passed. You can not cancel order. If you have any query than please contact our customer care.";
			}
		}else{
			return "Your order is delivered. You can not cancel your order. Instead of that you can return your order. If you have any query than please contact our customer care.";
		}
	}
	
	public function rpgetRefundAmount($cart_id){ // return or cancel order
		//Get Cart Details
		$cart_details_r = parent::rpgetData("cartdetails","*","cart_id='".$cart_id."'");
		$cart_details_d = @mysqli_fetch_array($cart_details_r);
		$order_status 	= $cart_details_d["orderstatus"];
		$total_ship_charge 	= parent::rpnum($cart_details_d["total_ship_charge"]);
		$cod_charge 		= parent::rpnum($cart_details_d["cod_charge"]);
		$finaltotal 		= parent::rpnum($cart_details_d["finaltotal"]);
		if($order_status==0){
			$prevoius_orderstatus = parent::rpgetValue("rcorder","from_status","cart_id='".$cart_id."' AND to_status='".$order_status."'");
			if($prevoius_orderstatus==3){ // Shipped than take shipping charge
				$refund_amount = parent::rpnum($finaltotal - $total_ship_charge - $cod_charge);
			}else{ //In Progress than refund all amount
				$refund_amount = parent::rpnum($finaltotal);
			}
		}elseif($order_status==5){ // Delivered order refund amount
			$refund_amount = parent::rpnum($finaltotal - $total_ship_charge - $cod_charge);
		}else{
			$refund_amount = 0.00;
		}
		return $refund_amount;
	}
	
	public function rpgetSingleItemRefundAmount($cart_id,$cart_item_id){ // return or cancel order
		//Get Cart Details
		$cart_item_r 	= parent::rpgetData("cartitems","*","cart_id='".$cart_id."' AND id='".$cart_item_id."'");
		$cart_item_d 	= @mysqli_fetch_array($cart_item_r);
		$order_status	= $cart_item_d["orderstatus"];
		$totalprice 	= parent::rpnum($cart_item_d["totalprice"]);
		return $totalprice;
	}
	
	public function rpgetSingleItemShippingRefundAmount($cart_id,$cart_item_id){ // return or cancel order
		//Get Cart Details
		$cart_item_r 	= parent::rpgetData("cartitems","*","cart_id='".$cart_id."' AND id='".$cart_item_id."'");
		$cart_item_d 	= @mysqli_fetch_array($cart_item_r);
		$order_status	= $cart_item_d["orderstatus"];
		$ship_charge	= parent::rpnum($cart_item_d["ship_charge"]);
		if($order_status==0){
			$prevoius_orderstatus = parent::rpgetValue("rcorder_items","from_status","cart_id='".$cart_id."' AND cart_item_id='".$cart_item_id."' AND to_status='".$order_status."'");
			if($prevoius_orderstatus==3){ // Shipped than take shipping charge
				$ship_refund_amount = 0.00;
			}else{ //In Progress than refund all amount
				$ship_refund_amount = parent::rpnum($ship_charge);
			}
		}elseif($order_status==5){ // Delivered order refund amount
			$ship_refund_amount = 0.00;
		}else{
			$ship_refund_amount = 0.00;
		}
		return $ship_refund_amount;
	}
	
	public function rpgetSingleItemCODRefundAmount($cart_id,$cart_item_id,$payment_method){ // return or cancel order
		if($payment_method==1){
			//Get Cart Details
			$cart_item_r 	= parent::rpgetData("cartitems","*","cart_id='".$cart_id."' AND id='".$cart_item_id."'");
			$cart_item_d 	= @mysqli_fetch_array($cart_item_r);
			$order_status	= $cart_item_d["orderstatus"];
			$totalprice 	= parent::rpnum($cart_item_d["totalprice"]);
			if($order_status==0){
				$prevoius_orderstatus = parent::rpgetValue("rcorder_items","from_status","cart_id='".$cart_id."' AND cart_item_id='".$cart_item_id."' AND to_status='".$order_status."'");
				if($prevoius_orderstatus==3){ // Shipped than take shipping charge
					$COD_refund_amount = 0.00;
				}else{ //In Progress than refund all amount
					$cod_charge	= parent::rpnum($totalprice*(COD_PER/100));
					if($cod_charge<COD_FLAT){
						$cod_charge = parent::rpnum(COD_FLAT);
					}
					$COD_refund_amount = parent::rpnum($cod_charge);
				}
			}elseif($order_status==5){ // Delivered order refund amount
				$COD_refund_amount = 0.00;
			}else{
				$COD_refund_amount = 0.00;
			}
		}else{
			$COD_refund_amount = 0.00;
		}
		return $COD_refund_amount;
	}
	
	public function rprcsingle_order($rcOrder,$cart_id,$cartitem_id){ // return or cancel order
		
		$cart_details_r = parent::rpgetData("cartdetails","orderdate,orderstatus","cart_id='".$cart_id."'");
		$cart_details_d = @mysqli_fetch_array($cart_details_r);
		$order_date 	= $cart_details_d["orderdate"];
		$order_status	= $cart_details_d["orderstatus"];
		
		if($rcOrder==$cartitem_id+6){ // Ret
			// Return Single Order
			/*if(parent::rpgetTotalRecord("cartitems","cart_id='".$cart_id."'")==1){
				return $this->rpreturnOrder($cart_id,$order_date,$order_status);
			}else{
				return $this->rpreturnSingleOrder($cart_id,$cartitem_id,$order_date,$order_status);
			}*/
			$_SESSION['return_ty']			= 1;
			$_SESSION['return_cart_id'] 	= $cart_id;
			$_SESSION['return_cartitem_id'] = $cartitem_id;
			return "ret";
		}else if($rcOrder==$cartitem_id+9){ // Can
			// Cancel Single Order
			/*if(parent::rpgetTotalRecord("cartitems","cart_id='".$cart_id."'")==1){
				return $this->rpcancelOrder($cart_id,$order_date,$order_status);
			}else{
				return $this->rpcancelSingleOrder($cart_id,$cartitem_id,$order_date,$order_status);
			}*/
			$_SESSION['cancel_ty']			= 1;
			$_SESSION['cancel_cart_id'] 	= $cart_id;
			$_SESSION['cancel_cartitem_id'] = $cartitem_id;
			return "can";
		}else{
			return "Something went wrong. Please try again or you can contact our customer care.";
		}
	}
	
	public function rpreturnSingleOrder($cart_id,$cartitem_id,$order_date,$order_status){ // return single order
		$last_date_to_return= date('Y-m-d', strtotime($order_date." +".RETURN_HOURS." hours"));
		$today_date			= date('Y-m-d');
		if(strtotime($today_date)<=strtotime($last_date_to_return)){
			if($order_status==4){
				
				/***Save History Starts***/
				$rcsid = $this->rprcSingleOrder_history($cart_id,$cartitem_id,$order_status,"5");	
				/***Save History Ends***/
				
				/****Update Cartitem Starts****/
				$rows 	= array(
						"rcdate"		=> $today_date,
						"orderstatus"	=> "5",
					);
				$where	= "id='".$cartitem_id."'";
				parent::rpupdate("cartitems",$rows,$where);
				/****Update Cartitem Ends****/
				
				/*******Check ALL Item is Returned Starts*******/
				$this->rpisAllRC($cart_id,$order_status,"5");
				/*******Check ALL Item is Returned Ends*******/
				$fn = new parent;
				$nt = new notification($fn);
				/**Send SMS**/
				$itemName = parent::rpgetValue("cartitems","name","id='".$cartitem_id."'");
				$msg = "Your mufat.in order #".$cart_id." item '".$itemName."' return request has been placed...";
				$nt->rpsendSMS2($cart_id,$msg,SMSPROMOTEXT);
				/**Send SMS**/
				
				/**Send Email**/
				$subject	= SITENAME." - Order #".$cart_id." item '".$itemName."' Return Request";
				$toemail 	= parent::rpgetValue("cartdetails","email","cart_id='".$cart_id."'");
				$body = file_get_contents(SITEURL.'mailbody/return_single_order_item.php?cart_id='.$cart_id.'&ciid='.$cartitem_id.'');
				$nt->rpsendGenEmail($toemail,$subject,$body);
				/**Send Email**/
				$_SESSION['rcsid'] = $rcsid;
				return "Your ordered item return request has been placed.";
			}else{
				return "Your order is not delivered yet. So you can not return the order item. Instead of that you can cancel your order item. If you have any query than please contact our customer care.";
			}
		}else{
			return "Last date to 'Return Order' is already passed. You can not return order item. If you have any query than please contact our customer care.";
		}
	}
	
	public function rpcancelSingleOrder($cart_id,$cartitem_id,$order_date,$order_status){ // Cancel single order
		$last_date_to_return= date('Y-m-d', strtotime($order_date." +".RETURN_HOURS." hours"));
		$today_date			= date('Y-m-d');
		if(strtotime($today_date)<=strtotime($last_date_to_return)){
			if($order_status==2 || $order_status==3){
				
				/**Update Qty Starts**/
				$this->rprcSingleQtyUpdate($cartitem_id);
				/**Update Qty Ends**/
				
				/***Save History Starts***/
				$rcsid = $this->rprcSingleOrder_history($cart_id,$cartitem_id,$order_status,"0");	
				/***Save History Ends***/
				
				/****Update Cartitem Starts****/
				$rows 	= array(
						"rcdate"		=> $today_date,
						"orderstatus"	=> "0",
					);
				$where	= "id='".$cartitem_id."'";
				parent::rpupdate("cartitems",$rows,$where);
				/****Update Cartitem Ends****/
				/*******Check ALL Item is Returned Starts*******/
				$this->rpisAllRC($cart_id,$order_status,"0");
				/*******Check ALL Item is Returned Ends*******/
				
				$fn = new parent;
				$nt = new notification($fn); 
				/**Send SMS**/
				
				$msg = "Your mufat.in order #".$cart_id." item '".$itemName."' has been cancelled successfully...";
				$nt->rpsendSMS2($cart_id,$msg,SMSPROMOTEXT);
				/**Send SMS**/
				
				/**Send Email**/
				$subject	= SITENAME." - Order #".$cart_id." item '".$itemName."' Cancelled";
				$toemail 	= parent::rpgetValue("cartdetails","email","cart_id='".$cart_id."'");
				$body = file_get_contents(SITEURL.'mailbody/cancel_single_order_item.php?cart_id='.$cart_id.'');
				$nt->rpsendGenEmail($toemail,$subject,$body);
				/**Send Email**/
				$_SESSION['rcsid'] = $rcsid;
				return "Your ordered item has been cancelled successfully.";
			}else{
				return "Your order is delivered. You can not cancel your order. Instead of that you can return your order. If you have any query than please contact our customer care.";
			}
		}else{
			return "Last date to 'Cancel Order' is already passed. You can not cancel order. If you have any query than please contact our customer care.";
		}
	}
	
	public function rpisAllRC($cart_id,$from_status,$to_status){
		//if all item in cartitem are returned or cancel than update cartdetails and rcorder
		$shop_cart_t = parent::rpgetTotalRecord("cartitems","cart_id='".$cart_id."' AND orderstatus='".$from_status."'");
		if($shop_cart_t>0){
			//Do nothing
		}else{
			/***UPdate Cartdetails Starts***/
			$today_date1	= date('Y-m-d H:i:s');
			$rows 	= array(
					"rcdate"		=> $today_date1,
					"orderstatus"	=> $to_status,
				);
			$where	= "cart_id='".$cart_id."'";
			parent::rpupdate("cartdetails",$rows,$where);
			/***Update Cartdetails Ends***/
			/****RCOrder Starts****/
			$uid = parent::rpgetValue("cartdetails","uid","cart_id='".$cart_id."'");
			$cdrows 	= array(
					"cart_id",
					"uid",
					"from_status",
					"to_status",
					"rcdate",
				);
			$cdvalues = array(
					$cart_id,
					$uid,
					$from_status,
					$to_status,
					$today_date1,
				);
			parent::rpinsert("rcorder",$cdvalues,$cdrows);
			/****RCOrder Ends****/
		}
		
	}
	
	public function rpgetPaymentMode(){
		if(isset($_SESSION['SW_ADMIN_SESS_ID']) && $_SESSION['SW_ADMIN_SESS_ID']!=""){
			return parent::rpgetValue("ccavenue_paymentgateway","status","id=1");
		}else{
			return "0";
		}
	}
	
	public function rpgetShippingDiscount($sub_total,$shipping_charge){
		if(SDP>0){
			if($sub_total>=MOTAFSD){
				$disc_amount = parent::rpnum(($shipping_charge*SDP)/100);
				if($disc_amount<=$shipping_charge){
					return parent::rpnum($disc_amount);
				}else{
					return 0.00;
				}
			}else{
				return 0.00;
			}
		}else{
			return 0.00;
		}
	}
	
	public function rprcSingleQtyUpdate($cartitem_id){
		$ciid_up_d = @mysqli_fetch_array(parent::rpgetData("cartitems","pid,subpid,name,qty","id='".$cartitem_id."' AND orderstatus!=0 AND orderstatus!=5"));
		$itemName 	= stripslashes($ciid_up_d['name']);
		$itemPid	= $ciid_up_d['pid'];
		$itemSubPid	= $ciid_up_d['subpid'];
		$itemQty	= $ciid_up_d['qty'];
		
		if($itemSubPid>0){
			$sp_r 	= parent::rpgetData("sub_product","qty,status","id='".$itemSubPid."'");
			if(@mysqli_num_rows($sp_r)>0){
				$sp_d 	= @mysqli_fetch_array($sp_r);
				$cqty	= $sp_d["qty"];
				$cspstt	= $sp_d["status"];
				$nqty	= $cqty+$itemQty;
				if($cspstt==1 && $nqty>0){
					$nspstt	= 0;
				}else{
					$nspstt	= 0;
				}
				$qrows 	= array(
						"qty"		=> $cqty+$itemQty,
						"status"	=> $nspstt,
					);
				$qwhere	= "id='".$itemSubPid."'";
				parent::rpupdate("sub_product",$qrows,$qwhere);
			}
		}else{
			$p_r 	= parent::rpgetData("product","qty,status","id='".$itemPid."'");
			if(@mysqli_num_rows($p_r)>0){
				$p_d 	= @mysqli_fetch_array($p_r);
				$cqty	= $p_d["qty"];
				$cpstt	= $p_d["status"];
				$nqty	= $cqty+$itemQty;
				if($cpstt==1 && $nqty>0){
					$npstt	= 0;
				}else{
					$npstt	= 0;
				}
				$qrows 	= array(
						"qty"		=> $cqty+$itemQty,
						"status"	=> $npstt,
					);
				$qwhere	= "id='".$itemPid."'";
				parent::rpupdate("product",$qrows,$qwhere);
			}
		}
	}
	public function rprcQtyUpdate($cart_id){
		$rcQty_r = parent::rpgetData("cartitems","id","cart_id='".$cart_id."'  AND orderstatus!=0 AND orderstatus!=5");
		if(@mysqli_num_rows($rcQty_r)>0){
			while($rcQty_d = @mysqli_fetch_array($rcQty_r)){
				$this->rprcSingleQtyUpdate($rcQty_d['id']);
			}
		}
	}
}
//include("notification.class.php");
/*
	*** Cart Function Developed By Ravi Patel :) <<<
*/
?>