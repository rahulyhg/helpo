<?php
/**
 * Created by PhpStorm.
 * User: ravik
 * Date: 04-09-2018
 * Time: 05:50 PM
 */
$response = array("tag" => "", "success" => false, "message" => "");
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if (isset($_POST['tag']) && $_POST['tag'] != ''){
        require_once(__DIR__.'/FTN_Location.php');
        $tag = $_POST['tag'];
        $db = new FTN_Location();

        if ($tag=='update_loc'){
            $mobile = $_POST["mobile"];
            $lat = $_POST["lat"];
            $long = $_POST["long"];

            $result = $db->insertUpdateLocation($mobile,$lat,$long);
            if ($result['result']==true){
                $response["tag"] = $tag;
                $response["success"] = true;
            }else{
                $response["success"] = false;
                $response["message"] = $result["message"];
            }
            echo json_encode($response,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }else{
            header('X-Error-Message: Incorrect TAG', true, 500);
            $response["tag"]="Error";
            $response["success"]=false;
            $response["message"]="Incorrect TAG";
            echo json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

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