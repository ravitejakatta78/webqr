<!DOCTYPE html>
<html lang="en">
<head>
  <title>food</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/slick-theme.css">
  <link rel="stylesheet" href="css/slick.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  
</head>
<?php
include_once('functn.php');
      error_reporting( E_ALL );
    ini_set( "display_errors", 1 );
if(!empty($_REQUEST))
{
        
    $sqlusers = sqlquery('select * from users where mobile = \''.$_REQUEST['mobile'].'\'');
    if(count($sqlusers) > 0){
        $otp = (string)rand(1111,9999);
                						$message = "Welcome ".$_REQUEST['username']." to Foodq family,your OTP is ".$otp.".
                						
                						For more rewards & coupons download foodq app https://play.google.com/store/apps/details?id=com.foodgeene
                						";
    otp_sms($_POST['mobile'],$message,$otp);
    //$otp = '1234';
     insertquery('update users set otp = \''.$otp.'\' where ID = \''.$sqlusers[0]['ID'].'\'');
       $userInserId =  $sqlusers[0]['ID'];
    }
    else{
        			$sqlprevmerchnat = sqlquery("select max(ID) as id from users");

			$prevmerchnat = $sqlprevmerchnat[0]['id'];
			$newid = $prevmerchnat+1;
			$unique_id = 'FDQ'.sprintf('%06d',$newid);
			$otp = (string)rand(1111,9999);
			//$otp = '1234';
			$pwd = password_hash(trim(112233),PASSWORD_DEFAULT);
       $userInserId = insertquery('insert into users (unique_id,otp,name,mobile,password,status,reg_date,mod_date) values (\''.$unique_id.'\',\''.$otp.'\'
        ,\''.$_POST['username'].'\',\''.$_POST['mobile'].'\',\''.$pwd.'\',\'1\',\''.date('Y-m-d h:i:s').'\',\''.date('Y-m-d h:i:s').'\')');
        
        $message = "Welcome ".$_POST['username']." to Superpilot family,<br> your OTP is ".$otp.".<br> 
                						For more rewards & coupons download Superpilot app <a>https://play.google.com/store/apps/details?id=com.foodgeene</a>
                						";
						 otp_sms($_POST['mobile'],$message,$otp);
    }

    $_REQUEST = json_decode(htmlspecialchars_decode($_REQUEST['orderarray']), true);
}
?>
<body>
    <div id="login" class="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form py-5" action="saveorder.php" method="post" onsubmit="return checkvalidateion();">
                            <h2 class="text-center text-white">Verify OTP</h2>
                            <hr>
                            <div class="form-group">
                                <!--<label for="username" class="text-white">Enter Your OTP:</label>-->
                                <input type="text" name="verifyotp" id="verifyotp" class="form-control" placeholder="Enter Your OTP" maxlength="4">
                                <span id="otp-validation" style="display:none;color:red">Please Enter Your OTP</span>
                                <span id="valid-otp-validation" style="display:none;color:red">Please Enter Valid OTP</span>
                            </div>

                              <input type="text" name="prepayarr" id="prepayarr" class="form-control" value="<?= htmlspecialchars(json_encode($_REQUEST)); ?>" style="display:none">
<input type="hidden" name="paytype" id="paytype" value="1"  style="display:none">
<input type="hidden" name="userId" id="userId" value="<?= $userInserId; ?>"  style="display:none">
                            <div class="form-group pt-3">
                              <button class="btn btn-danger w-100 p-2">Verify</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/slick.min.js"></script>
  <script src="js/custom.js"></script>
<script>
function checkvalidateion(){
 var verifyotp = $("#verifyotp").val();
 var actualotp = '<?= $otp; ?>';
 if(verifyotp == ''){
     $("#otp-validation").show();
     return false;
 }
 else{
     $("#user-validation").hide();
     if(verifyotp == actualotp ){
     $("#valid-otp-validation").hide();     
     }else{
     $("#valid-otp-validation").show();
     return false;
     }
 }
}
</script>

</body>
</html>