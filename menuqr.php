 <!DOCTYPE html>
<html lang="en">
<head>
  <title>food</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    *{
      margin:0.
      padding:0;
    }
    body{

      overflow-x: hidden;
    }
    .top-banner{
  		background: linear-gradient(rgba(79, 0, 0, 0),rgba(0, 0, 0, 0.9)),url(ban.jpg) no-repeat center;
    	background-size: cover;
		height: 150px;
  	}
  	.res-details img {
    width: 100%;
    margin-left:-40px;
    margin-top:47px;
    border-radius: 0px;
    box-shadow: 0px 0px 10px #555;
}
.row h5{
    font-size:12px;
}
.row h6{
    font-size:10px;
}
.cart-details{
  position:absolute;
  top:110px;
  display:none;
  margin-left:0px;
  text-align: left;
}
  	.w-25 {
    width: 23%!important;
    }
    .display-btn{
      display:block;
    }
    .hide-btn{
      display:none;
    }
    .ft-label{
      font-size:14px;
    }
    .cart-details{
      position: fixed;
top: 20;
z-index: 1050;
    }
    .each-item p{
      height:30px;
    }
    .pop-center{
      width:80%;
    }
    .w-60{
      width:60%;
    }
    .w-70{
      width:40%;
    }
    .res-details{
      top:0;
    }
    @media(max-width:768px){
      .res-details{
      top:10%;
    }
    }
  </style>
</head>
<body>
 <?php     include_once('functn.php');
 
 
 
  $enckey = @$_REQUEST['enckey'];
  $decryptDetails = decryptEnckey($enckey);
 
/*$message = "Dear Customer, 2123 is your OTP to verify your details on FoodQ and is valid for 15 minutes. Please do not share with anyone.";
$message = urlencode('9177208318');
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=617a9f5cd80e9118375d6ed1&mobile=919014306522&authkey=318274A8Ym3ky6q5e4661fbP1",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{\"OTP\":\"2345\"}",
  CURLOPT_HTTPHEADER => array(
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
exit;*/
      error_reporting( E_ALL );
    ini_set( "display_errors", 1 );
$sqltable = sqlquery('select *  from tablename where ID = \''.$decryptDetails['tableid'].'\'');


$sqlmerchant = sqlquery('select *  from merchant where ID = \''.$decryptDetails['merchantid'].'\'');

$sqlfs = sqlquery('select * from food_sections where merchant_id = \''.$decryptDetails['merchantid'].'\''); 
// echo "<pre>";print_r($sqlfs);exit;
$fs = array_column($sqlfs,'food_section_name','ID');

$data = sqlquery('select p.title,p.unique_id,coalesce(sipl.section_item_sale_price,0) as price,p.ID,food_category,p.foodtype,p.labeltag,food_section_id,food_section_name
, food_category_quantity,food_type_name,p.image,fs.ID food_section_id,p.foodtype  from product p
left join food_categeries fc on fc.ID = p.foodtype
left join food_category_types fct on fct.ID = p.food_category_quantity
left join food_sections fs on fc.food_section_id = fs.ID
left join section_item_price_list sipl on sipl.item_id =  p.ID and sipl.section_id = \''.$sqltable[0]['section_id'].'\'

where p.merchant_id = \''.$decryptDetails['merchantid'].'\' and  p.status = \'1\'  and sipl.section_item_price > 0'  ) ;


$titleIdArr =  array_column($data,'ID','title');
//echo "<pre>";print_r($titleIdArr);exit;
$fctQtyArr = sqlquery('SELECT title,unique_id,count(`food_category_quantity`) fctCount 
FROM `product` WHERE merchant_id = \''.$decryptDetails['merchantid'].'\' group by  title','unique_id');

$fctFnlQtyArr = array_column($fctQtyArr,'fctCount','title');

$pricePlainQtyArr =  array_column($data,'price','title');

$arrAngArr = [];
$fcfsArr = [];
$fcqtyarr = [];
for($arr=0;$arr<count($data);$arr++){
    
    $priceSql = sqlquery('select * from section_item_price_list 
					        where merchant_id= \''.$decryptDetails['merchantid'].'\' and item_id = \''.$data[$arr]['ID'].'\' 
					        and section_id = \''.$sqltable[0]['section_id'].'\'');
					        
					        
    
    $rplcetitle =  str_replace(" ","_",$titleIdArr[$data[$arr]['title']]);
    $uniqueIdStr = !empty($data[$arr]['food_category_quantity']) ? $rplcetitle.'_'.$data[$arr]['food_category_quantity'] : $rplcetitle;
    $arrAngArr[$data[$arr]['food_category']][$arr] = $data[$arr];
    $fcfsArr[$data[$arr]['food_section_id']][$arr] = $data[$arr]['food_category'];  
    $fcqtyarr[$rplcetitle][] = $data[$arr]['food_category_quantity'];
	$fcqtypriceArr[$rplcetitle.'_'.$data[$arr]['food_category_quantity']] = !empty($priceSql) ? (($priceSql[0]['section_item_sale_price'] > 0) ? $priceSql[0]['section_item_sale_price'] :  $data[$arr]['price'])  : $data[$arr]['price'];
	$fcqtyuniqueidArr[$uniqueIdStr] = $data[$arr]['unique_id'];
	$imgArr[$rplcetitle] = $data[$arr]['image'];
    
}





$products = sqlquery('select * from product p
where p.merchant_id = \''.$decryptDetails['merchantid'].'\'');
//echo "<pre>";print_r($fcfsArr);exit;

$food_category_types = array_column($data,'food_type_name','food_category_quantity');
//echo "<pre>";print_r($food_category_types);exit;
$fCTitleArr =    array_column($data,'food_category','title');
$foodTags =    array_column($products,'labeltag','title');
$foodServe =    array_column($products,'serveline','title');
$foodTax =    array_column($products,'foodtype','ID');
$foodUniqueId =    array_column($products,'unique_id','ID');
$foodCategeries = array_values(array_unique(array_values($fCTitleArr)));

//echo "<pre>";print_r($foodTax);exit;

		$resMerchantfoodTax = sqlquery('select food_category_id,tax_type,tax_value,merchant_tax_id
		 from merchant_food_category_tax where merchant_id = \''.$decryptDetails['merchantid'].'\'');

	    for($d=0;$d<count($resMerchantfoodTax);$d++){
            $MerchantfoodTaxArr[$resMerchantfoodTax[$d]['food_category_id']][] = 	     $resMerchantfoodTax[$d];   
	    }
//echo "<pre>";print_r($resMerchantfoodTax);exit;


?>

	<div class="top-banner">
<div class="container">
	<div class="row res-details">
		<?php if($sqlmerchant[0]['logo']) { ?>
		<img src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/dev/merchantimages/'.$sqlmerchant[0]['logo'];?>" class="w-25">		
		<?php } else { ?> 
		<img src="res-logo.png" class="w-25">
	    <?php } ?>
	
	</div>
</div>
</div>
<div class="main-section">
<div class="container">
	<div class="">
  
  	<div class="row top-items pb-0 mt-0 pl-2 sticky-top"style="background-color:lightgrey;">
  		<div class="col-md-8 col-8">
        <div class="row">
        <h5 class="w-100" style="font-size:25px;"><?= $sqlmerchant[0]['storename'];?></h5>
        <h6 class="d-block"><i class="fa fa-cutlery" aria-hidden="true"></i> <?= $sqlmerchant[0]['servingtype']; ?></h6>
      </div>
      </div>
    <div class="col-md-4 col-4">
      <div class="row float-right">
      <button class="btn btn-sm btn-primary" style="font-size:10px;margin-left:-50px;margin-right:2px;" type="button">
        Menu
      </button>

    </div>
 </div>
 <div class="row w-100 mx-auto">
    <div class="col-12">
  
  <!-- Nav pills -->
  <ul class="nav nav-pills justify-content-around mt-1" role="tablist">
<?php for($f=0;$f<count($sqlfs);$f++) { ?>      
    <li class="nav-item w-50 text-center pb-2 ">
      <a onclick="fs_cat_change(<?= $sqlfs[$f]['ID'] ?>)" class="nav-link <?php if($f == 0) { echo 'active'; } ?>" data-toggle="pill" href="#<?= $sqlfs[$f]['ID'] ?>"><i class="fa fa-cubes" aria-hidden="true"></i> <?= $sqlfs[$f]['food_section_name'] ?></a>
    </li>
<?php } ?>    
  </ul>
  </div>
  </div>
  	
  </div>
  <div class="row">
    <div class="container">
  <!-- Tab panes -->
  <div class="tab-content" id="food_tab_content">
      <?php for($fos = 0;$fos < count($sqlfs); $fos++ ) {
      $foodCategeries = (array_values(array_unique($fcfsArr[$sqlfs[$fos]['ID']])));
      ?>
    <div id="<?= $sqlfs[$fos]['ID'] ?>" class="container tab-pane <?php if($fos == 0) { echo 'active'; } ?>">
      <div class="row">
         <div id="accordion" class="myaccordion">
              <?php for($i=0;$i<count($foodCategeries);$i++) { ?>
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="d-flex align-items-center justify-content-between btn btn-link toggle-fa" data-toggle="collapse" data-target="#collapse<?= $i; ?>" aria-expanded="true" aria-controls="collapseOne">
          <?= $foodCategeries[$i]; ?>
         
        </button>
      </h2>
    </div>
    <div id="collapse<?= $i; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="row card-body">
                  <?php 
                  
                  $foodCat = (array_values(array_unique(array_column($arrAngArr[$foodCategeries[$i]],'title')))); 
       for($fct=0;$fct<count($foodCat);$fct++){
           $fcid = str_replace(' ','_',$titleIdArr[$foodCat[$fct]]);
        ?>
        <div class="col-md-4 col-sm-6 col-6 mx-auto">
          <div class="each-item">
              <?php if(!empty($imgArr[$fcid] )){ ?>
            <img src="http://foodqonline.com/development/tutors/web/uploads/productimages/<?= $imgArr[$fcid]; ?>">
            <?php }else { ?>
            <img src="images/default-item-image.png">
            <?php } ?>
            <div class="pt-2 pb-1 px-1">
            <h5><?= $foodCat[$fct];
             ?></h5>
            <p class="mb-1"><i class="fa fa-cutlery" aria-hidden="true"></i> <?= $foodTags[$foodCat[$fct]]?><span class="float-right"><i class="fa fa-users" aria-hidden="true"></i>  <?= $foodServe[$foodCat[$fct]]?></span></p>
            
            
            <?php if($fctFnlQtyArr[$foodCat[$fct]] >= 1) {  ?>
            <div class="d-flex">
            <div class="add-btn mx-auto" id="fcaddid_<?= $fcid; ?>" onclick = "openfoodqtypopup('<?= $fcid; ?>')" >View</div>
            <div class="hide-btn" id="incdec_<?= $fcid ?>">
              
            </div>
            </div>
            <?php } else {
            ?>
            
            <div class="row">
                <div class="col-7">
                <h6>₹ <?= $pricePlainQtyArr[$foodCat[$fct]]; ?></h6>
                </div>
            <div class="add-btn text-center col-5" onclick="productorder('<?= $fcid ?>',1,<?= $pricePlainQtyArr[$foodCat[$fct]]; ?>,'<?= $fcqtyuniqueidArr[$fcid]; ?>')" id="fcaddid_<?= $fcid ?>" >View</div>
            <div class="hide-btn" id="incdec_<?= $fcid ?>">
              <span class="quantity">
    <a  class="quantity__minus" style="cursor: pointer;" onclick="orderdecrement('<?= $fcid ?>',<?= $pricePlainQtyArr[$foodCat[$fct]]; ?>)"><span>-</span></a>
    <input name="quantity" type="text" id="quantity_input_<?= $fcid; ?>" class="quantity__input" value="1">
    <a  class="quantity__plus" style="cursor: pointer;" onclick="orderincrement('<?= $fcid; ?>',<?= $pricePlainQtyArr[$foodCat[$fct]]; ?>)" ><span>+</span></a>
  </span>
    <input type="hidden" id="plain_price_<?= $fcid; ?>" value="<?= $pricePlainQtyArr[$foodCat[$fct]]; ?>">
            </div>
          </div>
            <?php } ?>
          
          </div>
          </div>
        </div>
        <?php } ?>

      </div>
    </div>
  </div>
  <?php } ?>
  
</div>
      </div>
    </div>
<?php } ?>
    <div id="menu2" class="container tab-pane fade"><br>
      <h3>Menu 2</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
  </div>
</div>

  </div>
 <div class="row">
   
  </div>
  </div>
  <div class="row justify-content-center align-items-center p-2">
    
  	<div class="cart-details">
      <a  id="pop-close" class="close-right float-right">
      <i class="fa fa-times-circle" aria-hidden="true"></i>
    </a>
  		<h3>Your Order</h3>
  		<p><span id="cartpopuptotqty">0</span> Items</p>
  		<form id="formid" method="POST" action="qrcode.php" onsubmit="return checkcart()">
		<div class="order-items" id="order-items">
<input type="hidden" name="tableid" value="<?= $decryptDetails['tableid']; ?>" />
<input type="hidden" name="merchantid" value="<?= $decryptDetails['merchantid']; ?>" />
<input type="hidden" name="userId" value="<?= @$_GET['userId'] ?? ''; ?>" />
<input type="hidden" name="enckey" value="<?= @$enckey ?? ''; ?>" />
<input type="hidden" name="paytype" value="0" /> 
<div class="total-amount px-2">
  		<h5 class="mt-3">Sub Total <span class="float-right" id="cart_sub_total">₹ 6000</span><input type="hidden" name="sub_total_cart" id="sub_total_cart" ></h5>
  		<p>Tax <span class="float-right" id="cart_tax">₹ 0</span><input type="hidden" name="tax_cart" id="tax_cart" value="0"></p>
  		<p>Tip <span class="float-right" id="cart_tip" style="display:none">₹ 0</span><input type="text" name="tip_cart" id="tip_cart" value="0"></p>
      <hr>
  		<h4>Grand Total <span class="float-right red" id="cart_grand_total">₹ 6600</span><input type="hidden" name="grand_total_cart" id="grand_total_cart"></h4>	
  		</div>
  		</div>
  </form>
 </div>
</div>
</div>
</div>
<div class="amount-hide">
  <div class="container">
    <div class="row">
      <div class="col-md-5 col-5 p-1 mt-0 mb-0">
        <p class="my-0">Total Items <span id="totalitemsqty"> 0 </span> : </p>
        <h5>₹ <span id="totalitemsqtyprice"> 0 </span></h5>
      </div>
      <div class="col-md-7 col-7 text-right">
        <!-- <a onclick="paynow()"><button  class="btn btn-sm btn-primary"><b>Pay Now</b></button></a> -->
      </div>
    </div>
  </div>
  </div>
<div class="modal fade pop-itemlist w-100" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog pop-center" role="document">
    <div class="modal-content w-100">
      <div class="modal-body">  
       <div class="custom-control custom-checkbox d-block w-100 my-2">
        <input type="checkbox" class="custom-control-input" id="customCheck1" checked>
        <label class="custom-control-label ft-label d-flex" for="customCheck1"><span class="w-60">Single - <span class="red px-2">₹ 6600</span></span>
         <span class="float-right w-70"> <span class="quantity">
    <a href="#" class="quantity__minus"><span>-</span></a>
    <input name="quantity" type="text" class="quantity__input" value="1">
    <a href="#" class="quantity__plus"><span>+</span></a>
  </span>
  </span>
        </label>
      </div>
      <div class="custom-control custom-checkbox d-block w-100 my-2">
        <input type="checkbox" class="custom-control-input" id="customCheck2">
        <label class="custom-control-label ft-label d-flex" for="customCheck2"><span class="w-60">Family Pack - <span class="red px-2">₹ 6600</span></span>
          <span class="float-right w-70"> <span class="quantity">
    <a href="#" class="quantity__minus"><span>-</span></a>
    <input name="quantity" type="text" class="quantity__input" value="1">
    <a href="#" class="quantity__plus"><span>+</span></a>
  </span>
  </span>
        </label>
      </div>
      <div class="custom-control custom-checkbox d-block w-100 my-2">
        <input type="checkbox" class="custom-control-input" id="customCheck3">
        <label class="custom-control-label ft-label d-flex" for="customCheck3"><span class="w-60">Zumbo Pack - <span class="red px-2">₹ 6600</span></span>
          <span class="float-right w-70"> <span class="quantity">
    <a href="#" class="quantity__minus"><span>-</span></a>
    <input name="quantity" type="text" class="quantity__input" value="1">
    <a href="#" class="quantity__plus"><span>+</span></a>
  </span>
  </span>
        </label>
      </div>
      <div class="text-center">
      <button type="button" id="amount" class="btn btn-sm mt-3 btn-success" data-dismiss="modal">Close</button>
    </div>
      </div>
     
    </div>
  </div>
</div>
<script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/custom.js"></script>
<script>
$(document).on('click', 'input[type="checkbox"]', function() {      
    $('input[type="checkbox"]').not(this).prop('checked', false);      
});


  $(".cart-pop").click(function(){
  //$(".cart-details").addClass("d-block");
  $(".cart-details").show();
});
  $("#pop-open").click(function(){
    $(".cart-details").addClass("d-block");
  });
  $(document).ready(function(){
  $("#pop-close").click(function(){
    //$(".cart-details").removeClass("d-block");
    $(".cart-details").hide();
  });
  $("#tip_cart").change(function(){
    var sub_total_cart = $("#sub_total_cart").val();
    grnadTotals(sub_total_cart,1);
});
});
 $(document).ready(function(){
  $(".display-btn").click(function(){
    $(".display-btn").addClass("d-none");
  //  $(".hide-btn").addClass("d-block");
    $(".amount-hide").addClass("d-block");
  });
});





var productorder = (function() {
	

    
  return function (catname,fcpoptype,price,uniqueid) {
var titleIdArr = '<?= json_encode(array_flip($titleIdArr)); ?>';
console.log(titleIdArr);
var titleNameIdArr = JSON.parse(titleIdArr);
if(fcpoptype == 1){
        $("#fcaddid_"+catname).hide();
        $("#incdec_"+catname).show();    
}else{
	price = $("#fcqtyprice_"+catname).val();
	uniqueid = $("#fcqtyuniq_"+catname).val();
	addfctocart(catname,price);

}

   if(price != 0 && price != null ){
    $('#order-items').prepend('<h6 id="cartid_'+uniqueid+'"><span><img src="images/non.jpeg"></span><span class="item-name">'+titleNameIdArr[catname]+'</span> <span class="quantity">\
    <a  style="cursor:pointer" class="quantity__minus" onclick="orderdecrement(\''+catname+'\','+price+')"><span>-</span></a>\
    <input name="itemquantity[]" type="text"  class="quantity__input '+catname+'"  id="cart_quantity_input_'+catname+'" value="1">\
    <a  style="cursor:pointer" class="quantity__plus" onclick="orderincrement(\''+catname+'\','+price+')"><span>+</span></a>\
  </span>\
<span class="float-right pl-2 price-item" id="cart_price_item_'+catname+'">₹'+price+'</span>\
<a style="cursor:pointer" onclick="deletecartitem(\''+uniqueid+'\',\''+catname+'\',\''+price+'\')"  class="float-right pl-2 price-item"><i class="fa fa-trash red" aria-hidden="true"></i></a>\
</h6><input type="hidden" id="unique_id_'+uniqueid+'" class="unique_id" name="unique_id[]" value="'+uniqueid+'" >\
<input type="hidden" id="prodd_id_'+catname+'" class="prodd_id" name="prodd_id[]" value="'+catname+'" >\
<input type="hidden" id="item_tot_price_id_'+catname+'" class="item_tot_price" name="item_tot_price[]" value="'+price+'" >\
<hr id="cartrowid_'+uniqueid+'">'); 		
     caltax(catname);
     
          var cur_total_qty = parseInt($("#totalitemsqty").html());
    $("#totalitemsqty").html((cur_total_qty+1));
    $("#cartpopuptotqty").html((cur_total_qty+1));
 
    var currentGrandTotalAmt = parseFloat($("#totalitemsqtyprice").html())+parseFloat(price);
	grnadTotals(currentGrandTotalAmt);

  $(".amount-hide").addClass("d-block");       
   }

      
  }
})();

function fs_cat_change(fcsid){
    $( "#food_tab_content").children().removeClass( "active" );    
    $("#"+fcsid).addClass('active');
}
function openfoodqtypopup(fqty){
    var fcArr =('<?= json_encode($fcqtyarr); ?>');
    var fc = JSON.parse(fcArr);
    $('.modal-body').html('');
    var foodQtyArr = fc[fqty];
    
    var msg = '';
    var php_food_category_types = '<?= json_encode($food_category_types); ?>';
	var phpfcqtypriceArr =  '<?= json_encode($fcqtypriceArr); ?>';
	var phpfcqtyuniqueidArr = '<?= json_encode($fcqtyuniqueidArr); ?>';	
	var food_category_types = JSON.parse(php_food_category_types);
    var fcqtypriceArr = JSON.parse(phpfcqtypriceArr);
	var fcqtyuniqueidArr = JSON.parse(phpfcqtyuniqueidArr);
	for(var f=0;f< foodQtyArr.length;f++)
    {
		if(foodQtyArr[f] != '' && foodQtyArr[f] != null && foodQtyArr[f] != 'null'){
        var itemQtyPrice = fcqtypriceArr[fqty+'_'+foodQtyArr[f]];
		var itemUniqueId = fcqtyuniqueidArr[fqty+'_'+foodQtyArr[f]];
		msg += '<div class="custom-control  d-block w-100 my-2">\
        <label class="d-flex" for="customCheck'+foodQtyArr[f]+'">\
		<span class="w-60">'+food_category_types[foodQtyArr[f]]+' - <span class="red px-2">₹ '+itemQtyPrice+'</span></span>\
         </span>\
        </label>\
      </div>';
        }
    }
        msg +=  '<div class="text-center"><button type="button" id="amount"   class="btn btn-sm mt-3 btn-success" data-dismiss="modal">Close</button></div>\
		<input type="hidden" id="fcqtyprice_'+fqty+'" /><input type="hidden" id="fcqtyuniq_'+fqty+'" >';
		
	$('.modal-body').html(msg)
    $("#myModal").modal('show');    
}
function toaddprice(itemDesc,itemQtyPrice,itemUniqueId){
	$("#fcqtyprice_"+itemDesc).val(itemQtyPrice);
	$("#fcqtyuniq_"+itemDesc).val(itemUniqueId);
}
function addfctocart(fqty,itemprice){
    var  selectedfcat = $('input[name="foodcate"]:checked').val();
    if(selectedfcat != '' &&  typeof selectedfcat !== "undefined"){

    $("#fcaddid_"+fqty).hide();  
    $("#incdec_"+fqty).html('');
var incdecstr = '';
 incdecstr += '<span class="quantity">\
    <a  class="quantity__minus" style="cursor: pointer;" onclick="orderdecrement(\''+fqty+'\','+itemprice+')"><span>-</span></a>\
    <input name="quantity" type="text" id="quantity_input_'+fqty+'" class="quantity__input" value="1">\
    <a  class="quantity__plus" style="cursor: pointer;" onclick="orderincrement(\''+fqty+'\','+itemprice+')" ><span>+</span></a>\
  </span>';
    $("#incdec_"+fqty).html(incdecstr);
    $("#incdec_"+fqty).css('display','block');

    }

}

function caltax(productid){
    
    var tax_cart = $("#tax_cart").val();
    var food_tax_det = '<?= json_encode($foodTax); ?>';
	var food_tax_arr = JSON.parse(food_tax_det);
	var merchantfoodTax = '<?= json_encode($MerchantfoodTaxArr); ?>';
	var merchantfoodTaxArr = JSON.parse(merchantfoodTax);
	
	var calFoodTax = 0;
    var taxamt =0;
    var produ_id_val_arr = $("input[name='prodd_id[]']")
              .map(function(){return $(this).val();}).get();   
              
    var item_tot_price_arr = $("input[name='item_tot_price[]']")
              .map(function(){return $(this).val();}).get();   

for(var t=0;t<produ_id_val_arr.length;t++){

    var food_cat_id = (food_tax_arr[produ_id_val_arr[t]]);
	var foodTaxArr = (merchantfoodTaxArr[food_cat_id]) || [];

        if(foodTaxArr.length > 0){
              for(var f =0;f< foodTaxArr.length ; f++){
                  foodTaxValue = parseFloat(foodTaxArr[f]['tax_value']);
                  calFoodTax = calFoodTax + (parseFloat(item_tot_price_arr[t]) * parseFloat(foodTaxValue/100));
                 }
                    taxamt = parseFloat(( calFoodTax).toFixed(2)); 
        }    
}
$("#cart_tax").html(taxamt);

}


function orderincrement(catname,itemprice){
        var nextQty = parseInt($("#quantity_input_"+catname).val()) + 1;
        $("#quantity_input_"+catname).val(nextQty);
		$("#cart_quantity_input_"+catname).val(nextQty);
		$("#cart_price_item_"+catname).html(nextQty * parseFloat(itemprice)); 
				$("#item_tot_price_id_"+catname).val(nextQty * parseFloat(itemprice)); 
		var current_GrandTotalAmt = parseFloat($("#totalitemsqtyprice").html())+parseFloat(itemprice);
		caltax(catname);
	    grnadTotals(current_GrandTotalAmt);		
		
}
function orderdecrement(catname,itemprice){
    var prevQty = parseInt($("#quantity_input_"+catname).val()) - 1;
		if(prevQty > 0){
			$("#quantity_input_"+catname).val(prevQty);
			$("#cart_quantity_input_"+catname).val(prevQty);
			$("#cart_price_item_"+catname).html(prevQty * parseFloat(itemprice));  
					$("#item_tot_price_id_"+catname).val(prevQty * parseFloat(itemprice));
			var current_GrandTotalAmt = parseFloat($("#totalitemsqtyprice").html())-parseFloat(itemprice);
            caltax(catname);
            grnadTotals(current_GrandTotalAmt);
		}
}
function grnadTotals(currentGrandTotalAmt,tipchange = ''){
			$("#totalitemsqtyprice , #cart_sub_total" ).html(currentGrandTotalAmt);	
			var phpmerchantdet = '<?= json_encode($sqlmerchant); ?>';
			var merchantdet = JSON.parse(phpmerchantdet);
			//var cart_tax = ((parseFloat(merchantdet[0]['tax'])/100)*currentGrandTotalAmt).toFixed(2);
			var cart_tax = $("#cart_tax").html();
			
			$("#sub_total_cart").val(currentGrandTotalAmt);
			$("#tax_cart").val(cart_tax);
			var gtotal = parseFloat(currentGrandTotalAmt) + parseFloat(cart_tax) ;
			if(tipchange == 1){
			    var cart_tip = $("#tip_cart").val();
			}else{
			    var cart_tip = ((parseFloat(merchantdet[0]['tip'])/100)*gtotal).toFixed(2);    
			}
			
			
			$("#cart_tip").html(cart_tip);
			$("#tip_cart").val(cart_tip);
			gtotal = (parseFloat(gtotal) + parseFloat(cart_tip)).toFixed(2) ;
			$("#cart_grand_total").html(gtotal);
			$("#grand_total_cart").val(gtotal);
}


function deletecartitem(uniqueid,catname,price){
$("#cartid_"+uniqueid).hide();    
$("#cartrowid_"+uniqueid).hide();
$("#fcaddid_"+catname).show();
$("#incdec_"+catname).hide();
var currentInput = $("#quantity_input_"+catname).val();
$("#quantity_input_"+catname).val(1);
$('#unique_id_'+uniqueid).remove();
$("#cart_quantity_input_"+catname).remove();
          var cur_total_qty = parseInt($("#totalitemsqty").html());
    $("#totalitemsqty").html((cur_total_qty-1));
    $("#cartpopuptotqty").html((cur_total_qty-1));
var current_GrandTotalAmt =    parseFloat($("#totalitemsqtyprice").html()) - (parseInt(currentInput) * parseFloat(price) ) ;
grnadTotals(current_GrandTotalAmt);
}
function checkcart()
{
              var cur_total_qty = parseInt($("#totalitemsqty").html());
if(cur_total_qty == 0){
    alert("Please Atleast Add One Item!!");
    return false;
}
    
}
function paynow(){
     $('#formid').attr('action', "user/../prepayment.php").submit();
}
</script>
</body>
</html> 