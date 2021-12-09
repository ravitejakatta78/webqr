 <!DOCTYPE html>
<html lang="en">
<head>
  <title>Order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/slick-theme.css">
  <link rel="stylesheet" href="css/slick.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="orderstyle.css">
  <style>
  .btn{
      background:green;
      padding:2px;
  }
  .paid{
      float:right;
      margin-right:90px;
  }
  .unpaid{
  float:right;
  margin-right:80px;
  padding:0px;
  
}
.sts{
    margin-bottom:5px;
}
      
  </style>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<?php 
 include_once('functn.php');
       error_reporting( E_ALL );
    ini_set( "display_errors", 1 );
 $sqlPrevOrders = sqlquery('select m.name as merchant_name,m.storename as storename,m.mobile mobile,m.email,o.totalamount,o.paymenttype,o.paidstatus,orderprocess
 ,o.reg_date,o.preparedate,o.preparetime,t.name tablename,o.ID orderId from orders o inner join merchant m on o.merchant_id = m.ID
 inner join tablename t on o.tablename = t.ID
 where o.user_id = \''.$_GET['userid'].'\' order by o.ID desc');
 
?>
<body>
	<div class="container">
		<div class="nav sticky">
			<h2>Your Orders</h2>
		</div>
		<?php for($i=0;$i<count($sqlPrevOrders);$i++) { ?> 
		<div class="card">
			<div class="inner"><img src="images/img2.jpeg"><?= $sqlPrevOrders[$i]['storename'] ; ?>
				<span class="amount"><i class="fa fa-inr" aria-hidden="true"></i><?= $sqlPrevOrders[$i]['totalamount'] ; 
				$payAmount = ceil($sqlPrevOrders[$i]['totalamount']); 
				?></span>
				<button type="button" class="btn btn-sm mt-1 btn-success"><a href="rateus.html">Rate us</a></button>
    		</div><hr>
            <div class="content">
    			<p>Payment Method<span class="table">Table Name</span><br>
    			<?= $sqlPrevOrders[$i]['paymenttype']?><span class="table1"><?= $sqlPrevOrders[$i]['tablename'] ?></span></p>
    			<hr>	
    			<p class="sts">Order Status<span class="table2">Payment Status</span><br>
    			<?php 
    			if($sqlPrevOrders[$i]['orderprocess'] == '1' && $sqlPrevOrders[$i]['preparetime'] > 0 && empty($sqlPrevOrders[$i]['preparedate'])){
                                  echo 'Preparing';
                              }
                              else if($sqlPrevOrders[$i]['orderprocess'] == '1' && $sqlPrevOrders[$i]['preparetime'] > 0 && !empty($sqlPrevOrders[$i]['preparedate'])){
                                  echo 'Prepared';
                }
                else if($sqlPrevOrders[$i]['orderprocess'] == '0'){ 
    			    echo "New";
    			}else if($sqlPrevOrders[$i]['orderprocess'] == '1'){ 
    			    echo "Accepted";
    			}else if($sqlPrevOrders[$i]['orderprocess'] == '2'){ 
    			    echo "Served";
    			}else if($sqlPrevOrders[$i]['orderprocess'] == '3'){ 
    			    echo "Cancelled";
    			}else if($sqlPrevOrders[$i]['orderprocess'] == '4'){ 
    			    echo "Completed";
    			}
    			if($sqlPrevOrders[$i]['paidstatus'] == '1'){ ?>
    			    <span class="paid">Paid
    			    </span>
    			<?php }else if($sqlPrevOrders[$i]['paidstatus'] == '0') { ?>
    			<a onclick="payment(<?= $payAmount; ?>,'<?= $sqlPrevOrders[$i]['merchant_name']; ?>','<?= $sqlPrevOrders[$i]['storename']; ?>','<?= $sqlPrevOrders[$i]['email']; ?>'
    			,'<?= $sqlPrevOrders[$i]['mobile']; ?>','<?= $sqlPrevOrders[$i]['orderId']; ?>')"><span class="unpaid btn btn-secondary">Pay
    			    </span></a>     			    
    			<?php }else{ ?>
    			    <span>Payment Failed
    			    </span>
    			<?php }
    			?>

    			    
    			    </p><hr>
                <p><?= date('d M Y',strtotime($sqlPrevOrders[$i]['reg_date']))?><span class="table2">Served Time</span></br>
                    Time:-<?= date('h:i A',strtotime($sqlPrevOrders[$i]['reg_date'])); ?><span class="time"><?= date('h:i A',strtotime($sqlPrevOrders[$i]['preparedate'])); ?></span>
                    <a href="orderdetail.php?userid=<?= $_GET['userid']; ?>&orderid=<?= $sqlPrevOrders[$i]['orderId']; ?>"><input type="button" class="btn-info detail mt-0" value="Details"></a></p>
            </div>	
		</div>
		<?php } ?>
		<div id="paid" style="display:none"></div>
			<footer></footer>
	</div>
	<script>
    function payment(payamount,merchantname,storename,email,mobile,orderid){
        $('#paid').html('');
         $('#paid').append('<form action="http://superpilot.in/dev/paytmkit/pgRedirect.php" id="onlinepayment" method="POST">\
<input type = "text" name="TXN_AMOUNT" value="'+payamount+'">\
<input type = "text" name="CHANNEL_ID" value="WEB">\
<input type = "text" name="INDUSTRY_TYPE_ID" value="Retail">\
<input type = "text" name="ORDER_ID" value="ORDS0000'+orderid+'">\
<input type = "text" name="CUST_ID" value="CUST001">\
<input type = "submit"  value="submit">\
    </form>');
 $('#onlinepayment').submit();
 
    } 	
	</script>
</body>
</html> 