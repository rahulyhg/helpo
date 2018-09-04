<?php
/**
 * Created by PhpStorm.
 * User: RVishwakarma
 * Date: 8/23/2018
 * Time: 11:07 AM
 */

class DB_Function
{
    private $con;
    private $response;

    function __construct(){
        require_once 'DB_Connect.php';
        $db = new DB_Connect();
        $this->con = $db->Connect();
        $this->response = array();
    }

    function authenticateUser($mobile){
        $uuid = uniqid('usr',true);
        $query = "SELECT mobile FROM users WHERE mobile='$mobile'";

        $count = mysqli_fetch_array(mysqli_query($this->con,$query));
        $otp = rand(10000,99999);
        if ($count>0){
            $update = "UPDATE users SET is_verified=0, otp='$otp' WHERE mobile='$mobile'";
            if (mysqli_query($this->con,$update)){
                $this->sendOtp($mobile,$otp);
                $this->response["result"]=true;
                $this->response["message"] = "success";
                $this->response["server_message"] = "success";
                $this->response["data"]["status"] = Constant::$STATUS_ESS;
            }else{
                $this->response["result"]=false;
                $this->response["message"] = "Something Went Wrong, Please Try again later";
                $this->response["server_message"] = mysqli_error($this->con);
            }
        }else{
            $insert = "INSERT INTO users (uid,mobile,otp,created_date) VALUES('$uuid','$mobile',$otp,NOW())";
            if (mysqli_query($this->con,$insert)){
                $this->sendOtp($mobile,$otp);
                $this->response["result"]=true;
                $this->response["message"] = "success";
                $this->response["server_message"] = "success";
                $this->response["data"]["status"] = Constant::$STATUS_NESS;
            }else{
                $this->response["result"]=false;
                $this->response["message"] = "Something Went Wrong, Please Try again later";
                $this->response["server_message"] = mysqli_error($this->con);
            }
        }

        return $this->response;
    }

    function authenticatePolice(){

    }

    function sendOTP($mobile,$otp){
        require_once('config.php');
        $response_type = "json";
        //Define route
        $route = "Enterprise";

        $type = "text";

        $dateTime = "NOW()";

        $message = "Verification Code : $otp";

        //Prepare you post parameters
        $postData = array(
            'username' => SMS_USER_NAME,
            'password' => SMS_USER_PASSWORD,
            'to' => $mobile,
            'message' => $message,
            'sender' => SMS_SENDER_ID,
            'sendondate' => $dateTime
        );

        $url ="http://alotsolutions.in/API/WebSMS/Http/v1.0a/index.php";

        //init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        curl_close($ch);

        //Returning the response
        return $output;


    }

    function verifyOTP($mobile,$otp,$imei,$fcm_regid){
        $query = "SELECT * FROM users WHERE mobile='$mobile' AND otp = '$otp'";
        if(mysqli_query($this->con,$query)){
            $update = "UPDATE users SET imei = '$imei', fcm_regid='$fcm_regid', is_verified=1 WHERE mobile='$mobile'";
            if (mysqli_query($this->con,$update)){
                $data = mysqli_fetch_array(mysqli_query($this->con,$query));
                $this->response["result"] = true;
                $this->response["message"] = "Success";
                $this->response["server_message"] = mysqli_error($this->con);
                $this->response["data"] = $data;
            }else{
                $this->response["result"] = false;
                $this->response["message"] = "Something Went Wrong, Please Try again";
                $this->response["server_message"] = mysqli_error($this->con);
            }
        }else{
            $this->response["result"] = false;
            $this->response["message"] = "Something Went Wrong, Please Try again";
            $this->response["server_message"] = mysqli_error($this->con);
        }
        return $this->response;
    }

    public function hashSSHA($mobile) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($mobile . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }
}