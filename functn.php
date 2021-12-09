<?php

function decrypt($string)
{
    
    	 $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'foodgenee_key';
    $secret_iv = 'foodgenee_iv';
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
  
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
   
    return trim($output);

} 
function decryptEnckey($enckey){
    $decryptKey = decrypt($enckey);
    $merchantexplode = explode(',',$decryptKey);
    $merchantid = $merchantexplode[0];
	$tableid = $merchantexplode[1];

    /*                            if(empty($tableid)){
                                    $tableid = $merchantid;
                                	$tabel_Det = \app\models\Tablename::findOne($tableid);    
                                    $merchantid = $tabel_Det['merchant_id'];
                                }*/
                                $res =  [
	    'merchantid' => $merchantid,
	    'tableid' => $tableid
	    ];
	
	return $res;
	
}
function sqlquery($sqlString)
{
    $data = [];
$mysqli = new mysqli("localhost","superqcp_fooduse","fN&co]*qDG@r","superqcp_fooddev");
    
$result = $mysqli->query($sqlString);

while ($row = $result->fetch_assoc())
{
    $data[] = $row;
}
    return $data;
	$mysqli->close();
}
function insertquery($sqlString){

$mysqli = new mysqli("localhost","superqcp_fooduse","fN&co]*qDG@r","superqcp_fooddev");
   
if ($mysqli->query($sqlString) === TRUE) {
    $last_id = $mysqli->insert_id;
	return $last_id;
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$mysqli->close();
	
}

function sendPilotFCM($id,$title,$message,$imageurl=false,$type=null,$encykey=null,$orderid=null,$notificationdet = []) {
    $imageurl = $imageurl ?: '';
    $api_key = "AAAAtGpeZ64:APA91bEr9V1P9DWoGnvFqXrvoY1DN_gEutreMpxWiPFrzHd1_Zzn7GjtrNxhWjnjxpxSclMeT8QTKymldAGHOnLNwLurHFl9Bz65OmaLUZkx8GCb4MWnDU-OLYYhxQjCJZfHJ6X90uyq";

    $url = 'https://fcm.googleapis.com/fcm/send';
 
    $fields = [
	"registration_ids" => [
	$id
	],
	"notification" => [
		"title" => $title,
        "body" => $message,
        "image" => "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg",
        "mutable_content" => true,
        "sound" => "Tri-tone"
	],
	"data" => [
        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
        "type" => !empty($notificationdet['type']) ? $notificationdet['type'] : "" ,
        "title" => $title,
        "body" => $message,
        "order_id" => $orderid,
        "table" => "05",
        "payment_mode" => "Online",
        "amount" => !empty($notificationdet['orderamount']) ? $notificationdet['orderamount'] : 0,
       "user_name" => !empty($notificationdet['username'])  ? $notificationdet['username'] : '',
 //"amount" => '12',
 //"user_name" => "Ravi",
 
        "user_image" => "https://lwlies.com/wp-content/uploads/2017/04/avatar-2009.jpg",
        "page_name" => "screen 1",
        "image_url" => ""
    ]
];
//if(!empty($notificationdet))
//{
//    echo json_encode($fields);
//}
    $headers = array(
        'Content-Type:application/json',
        'Authorization: key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;

}

/* function sendPilotFCM($id,$title,$message,$imageurl=false,$type=null,$encykey=null,$orderid=null,$notificationdet = []) {
if(!empty($id)){
	$imageurl = $imageurl ?: '';
    $api_key = "AAAAbL_6cuU:APA91bFMr3gEaAHBgPsZlB0Qnp9DICD9xBSP0hRl0kDehZEvFm82CrNr_xsthGTuK_8dAM0gXO5lDnUeJ33OUQkmEKvOVNYbIqQM9op4U5CY7OSXqc0FlEs4opTwXzviQhRIojgLW0-S";

    $url = 'https://fcm.googleapis.com/fcm/send';
 
    $fields = array (
        'to' =>  $id,   
		"priority" => "high",
        'data' => array (
			   "content" => $message,
			   "body" => $message,
			   
			   "type" => $type ?? '1',
			   "encykey" => $enckey ?? '',
			   "title" => $title, 
			   "image" => $imageurl, 
			   "orderid" => $orderid,
        ),
		"text" => $title,
    );
    $headers = array(
        'Content-Type:application/json',
        'Authorization: key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
} */

	function otp_sms1($mobilenumbers,$message){
       $message = urlencode($message);
	$curl = curl_init();
	$smsid = 'FOODQR';
	
	$smsarray = array();
	$smsarray['sender'] = $smsid;
	$smsarray['route'] = '106';
	$smsarray['country'] = '91';
	$smsarray['sms'] = array(array("to"=>array($mobilenumbers),'message'=>$message));
	$authentication_key = '318274A8Ym3ky6q5e4661fbP1';
  
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($smsarray),
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array(
    "authkey: $authentication_key",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}
 
     }

	function otp_sms($mobilenumbers,$message,$otp=1234){
       $message = urlencode($message);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=617a9f5cd80e9118375d6ed1&mobile=91$mobilenumbers&authkey=318274A8Ym3ky6q5e4661fbP1",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{\"OTP\":\"$otp\"}",
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
  //echo $response;
}

     }

     
function send_sms($mobilenumbers,$message){
       $message = urlencode($message); 
 
	$url="http://roundsms.com/api/sendhttp.php?authkey=OGQxNTZkYTc3M2I&mobiles=".$mobilenumbers."&message=".$message."&sender=FOODQR&type=1&route=2"; 
  
$contents = file_get_contents($url); 
return $contents;
}

?>