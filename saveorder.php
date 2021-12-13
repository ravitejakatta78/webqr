<?php
 include_once('functn.php');
 date_default_timezone_set("asia/kolkata");
      error_reporting( E_ALL );
    ini_set( "display_errors", 1 );
  $paytypeold = $_REQUEST['paytype'];
  $user_id = $_REQUEST['userId'];
if(isset($_REQUEST['prepayarr'])){
    $_REQUEST = json_decode(htmlspecialchars_decode($_REQUEST['prepayarr']), true);
 }
extract($_REQUEST);
//echo "<pre>";print_r($_REQUEST);exit;
$sqltable = sqlquery('select tb.*,section_name  from tablename tb left join sections s on s.ID = tb.section_id  where tb.ID = \''.$tableid.'\'');
$sqlmerchant = sqlquery('select *  from merchant where ID = \''.$merchantid.'\'');
if(!empty($sqltable[0]['current_order_id']) && $sqlmerchant[0]['table_occupy_status'] == 1)
								{ ?>
								 <h2 style="text-align:center;" class="border border-danger p-3">Table Already Occupied ! Please try another table </h2>      
							 <?php exit;	}


if(!empty($unique_id) && $paytypeold != '2'){
		$prevmerchnat = sqlquery('select MAX(ID) as id from orders');
		$newid = @$prevmerchnat[0]['id']+1; 
	$orderId = strtoupper('SP'.$merchantid.sprintf('%07d',$newid));
	$txn_id = strtoupper('SPTX'.$merchantid.sprintf('%07d',$newid));


			$sqlprevmerchnat = sqlquery("select max(orderline) as id from orders where merchant_id = '".$merchantid."' and reg_date >='".date('Y-m-d')." 00:00:01' and reg_date <='".date('Y-m-d')." 23:59:59'");		
			$prevmerchnat = @$sqlprevmerchnat[0]['id']; 
							 
							$newid = $prevmerchnat>0 ? $prevmerchnat+1 : 100;  
if($paytypeold == '3'){
    $paidstatus = '1';
}
else{
    $paidstatus = '0';
}

    $userdetails = sqlquery('select * from users where ID = \''.$user_id.'\'');
    
$ordetInserId = insertquery('insert into orders (user_id ,merchant_id,tablename,order_id,txn_id,txn_date,amount,tax,tips,subscription,couponamount,totalamount,paymenttype,
orderprocess,status,paidstatus,paymentby,ordertype,orderline,reg_date) values (\''.$user_id.'\',\''.$merchantid.'\'
,\''.$tableid.'\' , \''.$orderId.'\' , \''.$txn_id.'\',\''.date('Y-m-d H:i:s').'\',\''.$sub_total_cart.'\',\''.$tax_cart.'\'
,\''.$tip_cart.'\',\'0\',\'0\',\''.$grand_total_cart.'\',\'cash\',\'0\',\'1\',\''.$paidstatus.'\',\'1\',\'3\',\''.$newid.'\',\''.date('Y-m-d H:i:s').'\'
)');

insertquery('insert into order_transactions (user_id,order_id,merchant_id,amount,tax,tips,subscription,couponamount,totalamount,paymenttype,reorder,paidstatus,reg_date) values (\''.$user_id.'\',\''.$ordetInserId.'\',\''.$merchantid.'\',\''.$sub_total_cart.'\',\''.$tax_cart.'\'
,\'0\',\'0\',\'0\',\''.$grand_total_cart.'\',\'cash\',\'0\',\'0\',\''.date('Y-m-d H:i:s').'\') ');

for($i=0;$i<count($unique_id);$i++){
	$sqluniqprod = sqlquery('select * from product where merchant_id = \''.$merchantid.'\' and unique_id = \''.$unique_id[$i].'\'');
    $sqlitemprice = sqlquery('select * from section_item_price_list where merchant_id = \''.$merchantid.'\' and item_id = \''.$sqluniqprod[0]['ID'].'\' and section_id = \''.$sqltable[0]['section_id'].'\'');
	insertquery('insert into order_products (user_id,order_id,merchant_id,product_id,count,price,inc,reorder,reg_date) values (\''.$user_id.'\',\''.$ordetInserId.'\',\''.$merchantid.'\'
	,\''.@$sqluniqprod[0]['ID'].'\',\''.$itemquantity[$i].'\',\''.@$sqlitemprice[0]['section_item_sale_price'].'\',\''.($i+1).'\',\'0\',\''.date('Y-m-d H:i:s').'\')')."<br>";
}

insertquery('update tablename set table_status = \'1\' , current_order_id = \''.$ordetInserId.'\' where ID = \''.$tableid.'\'');	
	$serviceboyarray = sqlquery("select * from serviceboy where merchant_id = '".$merchantid."' and loginstatus = '1' and push_id <> '' order by ID desc");
											if(!empty($serviceboyarray)){
												$stitle = 'New order.';
												$smessage = 'New order received please check the app for information.';
												$simage = '';
												foreach($serviceboyarray as $serviceboy){
												    $notificationdet = ['type' => 'NEW_ORDER','orderamount' => $grand_total_cart,'username' => $userdetails[0]['name']
													,'tablename' => $sqltable[0]['section_name']];
													sendPilotFCM($serviceboy['push_id'],$stitle,$smessage,$simage,'6',null,$ordetInserId,$notificationdet); 
												}
											}


$url = "orderlist.php?userid=$user_id";
header("Location: ".$url);

    
}else if($paytypeold == '2'){
    $sqlmerchantdetails = sqlquery('select * from merchant where ID = \''.$merchantid.'\'');
?>
<form action="http://superpilot.in/dev/razorpay/pay.php" id="onlinepayment" method="POST" style="display:none;">
<input type = "text" name="totalamount" value="<?= $_REQUEST['grand_total_cart'];?>">
<input type = "text" name="merchantname" value="<?= $sqlmerchantdetails[0]['name'];?>">
<input type = "text" name="storename" value="<?= $sqlmerchantdetails[0]['storename'];?>">
<input type = "text" name="email" value="<?= $sqlmerchantdetails[0]['email']; ?> ">
<input type = "text" name="mobile" value="<?= $sqlmerchantdetails[0]['mobile']; ?>">
<input type = "text" name="paytype" value="<?= $paytypeold; ?>">
<input type = "text" name="orderId" value="1234">
<input type="text" name="prepayarr" value="<?= htmlspecialchars(json_encode($_REQUEST)); ?>">
</form>

    
<?php }
?>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
   var paytype = '<?= $paytypeold; ?>';
   if(paytype == '2'){
       $("#onlinepayment").submit();
   }
});
</script>
