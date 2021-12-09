<!DOCTYPE html>
<html>
<head>
	<title>summary</title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/slick-theme.css">
  <link rel="stylesheet" href="css/slick.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style>
		.top-banner{
  		background: linear-gradient(rgba(79, 0, 0, 0),rgba(0, 0, 0, 0.9)),url(images/item.jpg) no-repeat center;
    	background-size: cover;
		height: 170px;
  	}
  	.res-details{
  		position:absolute;
  		top:50px;
  		margin: auto;
  		z-index: 1;
  	}
  	
  	.res-details h2{
  		color:#fff;
      margin-left:100px;
  		font-size:20px;
  	}
  	.cost{
  		float:right;
		}
		.main{
			margin-top: 5px;
		}
		p{
			margin-top: 0px;
		}
		.add{
			margin-right:120px;
			background-color: white;
		}
		button{
			border-radius: 10px;
			border:2px;
			color:blue;
		}
		.btn{
			float:right;
			margin-right:-10px;
			color:#FF7F50;
			border:1px solid black;
			border-radius:10px;
		}
		footer{
  margin-bottom: 250px;
}
	</style>
</head>
<body>
    <?php 
     include_once('functn.php');  
          error_reporting( E_ALL );
    ini_set( "display_errors", 1 );

    $sqlorder = sqlquery('select *  from orders where ID = \''.$_GET['orderid'].'\'');
    $sqltable = sqlquery('select *  from tablename where ID = \''.$sqlorder[0]['tablename'].'\'');
$sqlmerchant = sqlquery('select *  from merchant where ID = \''.$sqlorder[0]['merchant_id'].'\'');
$sqlordeproducts = sqlquery('select *,op.price prod_price from order_products op inner join product p on op.product_id = p.ID where op.order_id = \''.$_GET['orderid'].'\''); 
$serviceboy = sqlquery('select *  from serviceboy where ID = \''.$sqlorder[0]['serviceboy_id'].'\'');

    ?>
	<div class="container">
		<div class="top-banner">
			<a href="orderlist.php?userid=<?= $_GET['userid']; ?>"><i class="fa fa-arrow-left"style='font-size:30px;color:white;position: absolute;top:15px;left:40px;' aria-hidden="true"></i></a>
			<div class="container">
				<div class="row res-details">
					<h2><?= $sqlmerchant[0]['storename']; ?><br>
					<?= $sqlmerchant[0]['unique_id']; ?></h2>
				</div>
			</div>
		</div>
		<div class="main">
			<h4>Order Summary</h4>
			<?php for($i=0;$i<count($sqlordeproducts);$i++) {
			    
$data = sqlquery('select p.title,p.unique_id,coalesce(sipl.section_item_sale_price,0) as price,p.ID,food_category,p.foodtype,p.labeltag,food_section_id,food_section_name
, food_category_quantity,food_type_name,p.image,fs.ID food_section_id,p.foodtype  from product p
left join food_categeries fc on fc.ID = p.foodtype
left join food_category_types fct on fct.ID = p.food_category_quantity
left join food_sections fs on fc.food_section_id = fs.ID
left join section_item_price_list sipl on sipl.item_id =  p.ID and sipl.section_id = \''.$sqltable[0]['section_id'].'\'

where p.ID = \''.$sqlordeproducts[0]['product_id'].'\' '  ) ;
			
			?>
					<p><?= ($i+1); ?> &nbsp; <?= $sqlordeproducts[$i]['title'].'('. $data[0]['food_type_name'] .')' ; ?> * <?= $sqlordeproducts[$i]['count']; ?> &nbsp;&nbsp;  <span >Rs.<?= $sqlordeproducts[$i]['prod_price']; ?></span> <span class="cost">Rs.<?= $sqlordeproducts[$i]['count'] * $sqlordeproducts[$i]['prod_price']; ?></span></p>


			<?php } ?>
		</div><hr>
		<div class="content">
			<p>Amount<span class="cost">Rs.<?= $sqlorder[0]['amount'] ?? 0.00; ?></span></p>

			<p>Service Tax<span class="cost">Rs.<?= $sqlorder[0]['tax'] ?? 0.00; ?></span></p>
			<p>Tip<span class="cost">Rs.<?= $sqlorder[0]['tips'] ?? 0.00; ?></span></p>
			<p>Coupon Amount<span class="cost">Rs.<?= $sqlorder[0]['couponamount'] ?? 0.00; ?></span></p>
			<p>Discount<span class="cost">Rs.<?= $sqlorder[0]['discount_number'] ?? 0.00; ?></span></p>

			<h4>Total<span class="cost">Rs.<?= $sqlorder[0]['totalamount'] ?? 0.00; ?></span></h4><hr>
		</div>
		<h5>Served By : <?= @$serviceboy[0]['name']; ?></h5>
			<div class="add">
	<button type="button" class="btn btn-lg mt-3" data-toggle="modal" data-target="#myModal">Send Invoice</button>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <form action="" method="post" id="contactform">
         <table class="mytable">
            <tr>
               <td><label for="email">Email :</label></td>
               <td class="email"><input name="email" id="email" type="text" placeholder="Please enter your email" class="contact-input"><span class="error">Enter your email-id here</span>
                  <span class="error" id="invalid_email">Email-id is invalid</span>
               </td>
            </tr>
         </table>
      </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
      
    </div>
  </div>

	<footer></footer>
	</div>
	<script>  
    $(document).ready(function() {
      $('.error').hide();
      $('#submit').click(function(){
        var email = $('#email').val();
        if(email== ''){
          $('#email').next().show();
          return false;
        }
        if(IsEmail(email)==false){
          $('#invalid_email').show();
          return false;
        }
        $.post("", $("#myform").serialize(),  function(response) {
          $('#myform').fadeOut('slow',function(){
          $('#correct').html(response);
          $('#correct').fadeIn('slow');
       });
     });
    return false;
  });
 });
 function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }
}
</script>
</body>
</html>
