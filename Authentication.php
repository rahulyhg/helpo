<?php
/**
 * Created by PhpStorm.
 * User: RVishwakarma
 * Date: 8/23/2018
 * Time: 11:17 AM
 */

$response = array("tag" => "", "success" => false, "message" => "");
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if (isset($_POST['tag']) && $_POST['tag'] != ''){
        require_once(__DIR__.'/DB_Function.php');
        require_once(__DIR__.'/Constant.php');
        //Get TAG
        $tag = $_POST['tag'];

        //Init DB FUNCTION
        $db = new DB_Function();


        if ($tag==Constant::$ROLE_USER){
            $mobile = $_POST["mobile"];
            $result = $db->authenticateUser($mobile);
            if ($result['result']==true){
                $response["success"] = true;
                $response["response"] = $result["data"];
            }else{
                $response["success"] = false;
                $response["message"] = $result["message"];
            }
            echo json_encode($response,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }elseif ($tag==Constant::$ROLE_POLICE){
            $result = $db->authenticatePolice("","");
        }elseif ($tag==Constant::$USER_VERIFICATION){
            $mobile = $_POST["mobile"];
            $otp = $_POST["otp"];
            $imei = $_POST["imei"];
            $fcm = $_POST["fcm"];
            $result = $db->verifyOTP($mobile,$otp,$imei,$fcm);
            if ($result['result']==true){
                $response["success"] = true;
                $response["response"] = $result["data"];
            }else{
                $response["success"] = false;
                $response["message"] = $result["message"];
            }
            echo json_encode($response,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }elseif ($tag==Constant::$POLICE_VERIFICATION){

        }else{
            header('X-Error-Message: Incorrect TAG', true, 500);
            $response["tag"]="Error";
            $response["success"]=false;
            $response["message"]="Incorrect TAG";
            echo json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        /*$result = $db->authenticate("","");
        $response["tag"]=$tag;
        $response["success"]=true;
        $response["message"]="Success";
        echo json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);*/

    }else{
        header('X-Error-Message: Incorrect TAG', true, 500);
        $response["tag"]="Error";
        $response["success"]=false;
        $response["message"]="Incorrect TAG";
        echo json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}else{
    header('X-Error-Message: Action does not support GET Method', true, 500);
    $response["tag"]="Error";
    $response["success"]=false;
    $response["message"]="Action does not support GET Method";
    echo json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}