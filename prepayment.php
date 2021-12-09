
 <html>
	<head>
		<title>Payment Method webpage</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<style>
			h1 {
  				text-align: center;
				margin-top:10px;
				margin-bottom:10px;
				padding:10px;
				font-size:60px;
			}
			h3 {
  				text-align: center;
				margin-top:10px;
				margin-bottom:10px;
				padding:10px;
				font-size:50px;
			}
			    input.checkbox { 
            width: 35px; 
            height: 35px; 
        } 
			
			.card {
  				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  				max-width: 720px;
  				margin: auto;
  				text-align: center;
  				font-family: arial;
				border-radius:15px;
				height:700px;
			}

			.title {
 				 color: grey;
  				 font-size: 45px;
				 margin-left:10px;
			}

			
			h2{
				text-align:left;
				text-decoration:underline;
				margin-left:10px;
				margin-top:10px;
				color:black;
				font-size:50px;

				}
			p{
				text-align:left;
				

				}
			h4{
				text-align:left;		
				margin-left:10px;
				margin-top:20px;
				margin-bottom:20px;
				color:black;
				font-size:55px;

				}
			.payment{
						
				margin-left:235px;
				color:black;
				font-size:300px;
				
			}
			.logo{
				float:left;		
            				margin:auto 220 ;
            				padding:3px 0;
            				display:block;
					width:70px;
					height:70px;
        			}
			.logo1{
				float:left;
				width:30px;		
            				margin:auto 220 ;
            				padding:3px 0;
            				display:block;
        			}
			.payment{
				
				font-family: arial;
				color:black;
				margin-left:-200px;
				font-size:20px;		
			}
			.footer{
 				 color: black;
  				 font-size: 40px;
				 float:left;
				 margin-left:180px;
				 margin-top:30px;
			}
			.right{
				text-align:right;
				padding-left:218px;
			}
			.right1{
				text-align:right;
				padding-left: 262px;
			}
			.right2{
				text-align:right;
				padding-left:363px;
			}
			.right3{
				text-align:right;
				padding-left:130px;
			}
			.right4{
				text-align:right;
				padding-left:252px;
			}
			.right5{
				text-align:right;
				padding-left:200px;
			}
			@media screen and (max-width:600px)
			{
				.payment{width:100%;float:left;margin:10px}
				.logo{width:10%;float:left;margin:10px}
				.logo1{width:10%;float:left;margin:10px}
				.pa{width:100%;margin:auto}
				.footer{float:left;margin:10px}
				.card{width:470px}
				
			}
			


		</style>
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
function placeorder(paytype){
var gdTotal = '<?= $_REQUEST['grand_total_cart'];?>';
    if(isNaN(gdTotal)){
alert("Please Check Order Products price!!") ;
                return false;
    }else{
    $("#paytype").val(paytype);
    $("#formid").submit();        
    }
}
</script>
	</head>
	<body style="background-color:lightblue;">
	
		<div class="container-fluid">
		        <h1 style="background-color:Tomato;"><font color="white">Payment Method</h1><br></div>
		        <h3><font color="Tomato">Have a coupon code ?</h3><br>
		        <div class="card">  
  			        <h2>Fare Details</h2>
  			            <p class="title">Actual Price <span class="right"><i class="fa fa-inr"></i>&nbsp;<?= $_REQUEST['sub_total_cart'];?></p></span>
  			            <p class="title">Digital Tax<span class="right1"><i class="fa fa-inr"></i>&nbsp;<?= $_REQUEST['tax_cart'];?></p></span>
			            <p class="title"><input type="checkbox" class="checkbox">&nbsp;Tip <span class="right2"><i class="fa fa-inr"></i>&nbsp;0.00</p></span>
                        <p class="title">Discounted Price <span class="right3"><i class="fa fa-inr"></i>&nbsp;0</p></span>
		            	<p class="title">you Saved: <span class="right4"><i class="fa fa-inr"></i>&nbsp;0</p></span><hr>
			            <h4>Total Price <span class="right5"><i class="fa fa-inr"></i>&nbsp;<?= $_REQUEST['grand_total_cart'];?></h4></span>
  		        </div>
		        <p class="payment">Payment Types:</p>
		    <div>
   			 <img class="logo" src="images/cash.png" alt="logo" onclick="placeorder('1')" /><span class="payment" style="font-size:45px;"><a onclick="placeorder('1')">Cash on Dine</a></span>
	    	</div><br>
		<div>
			<img class="logo" src="images/online.png" alt="logo" onclick="placeorder('2')"/><a onclick="placeorder('1')"><span class="payment" style="font-size:45px;">Online</span></a>
		</div><br>
		<p class="footer">Online payments are highly appreciated</p>
	<form id="formid" method="POST" action="saveorder.php" style="display:none;">
	    <input type="text" name="prepayarr" value="<?= htmlspecialchars(json_encode($_REQUEST)); ?>">
	    <input type="text" name="paytype" id="paytype">
	    </form>	

	</body>
</html>