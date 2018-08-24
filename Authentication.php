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
        //Get TAG
        $tag = $_POST['tag'];

        //Init DB FUNCTION
        $db = new DB_Function();


        if ($tag==Constant::$ROLE_USER){
            $result = $db->authenticate("","");
        }elseif ($tag==Constant::$ROLE_POLICE){
            $result = $db->authenticatePolice("","");
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