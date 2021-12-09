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
<body>
    <div id="login" class="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form py-5" action="verifyotp.php?enckey=<?php echo $_REQUEST['enckey']; ?>" method="post" onsubmit="return checkvalidateion();">
                            <h2 class="text-center text-white">Your Details</h2>
                            <hr>
                            <div class="form-group">
                                <!--<label for="username" class="text-white">Enter Your OTP:</label>-->
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Your User Name" autocomplete="off">
                                <span id="user-validation" style="display:none;color:red">Please Enter User Name</span>
                            </div>
                            <div class="form-group">
                                <!--<label for="username" class="text-white">Enter Your OTP:</label>-->
                                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter Your Mobile Name" autocomplete="off">
                                <span id="number-validation" style="display:none;color:red" >Please Enter Mobile Number</span>
                                <span id="number-length-validation" style="display:none;color:red" >Please Enter Mobile Number</span>
                                
                            </div>                            

                            
                            <div class="form-group pt-3">
                              <button class="btn btn-danger w-100 p-2">Verify</button>
                            </div>
                              <input type="text" name="orderarray" id="orderarray" class="form-control" value="<?= htmlspecialchars(json_encode($_REQUEST)); ?>" style="display:none">
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
 var username = $("#username").val();
 var mobile = $("#mobile").val();
 if(username == ''){
     $("#user-validation").show();
     return false;
 }
 else{
     $("#user-validation").hide();
 }
  if(mobile == ''){
     $("#number-validation").show();
    return false;
  }
 else{
     $("#number-validation").hide();
     if(mobile.length != 10){
         $("#number-length-validation").show();
     }else{
         $("#number-length-validation").hide();
     }
 }
}
</script>

</body>
</html>