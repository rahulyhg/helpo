<?php
/**
 * Created by PhpStorm.
 * User: ravik
 * Date: 04-09-2018
 * Time: 05:50 PM
 */

class FTN_Location
{
    private $con;
    private $response;

    function __construct(){
        require_once 'DB_Connect.php';
        $db = new DB_Connect();
        $this->con = $db->Connect();
        $this->response = array();
    }

    function insertUpdateLocation($mobile,$lat,$long){
        $uuid = uniqid('loc',true);
        $query = "SELECT * FROM police_location WHERE mobile='$mobile'";

        $count = mysqli_fetch_array(mysqli_query($this->con,$query));
        if ($count>0){
            $update = "UPDATE police_location SET lat='$lat', lng='$long', updated_at=NOW()";
            if (mysqli_query($this->con,$update)){
                $this->response["result"]=true;
                $this->response["message"] = "success";
                $this->response["server_message"] = "success";
            }else{
                $this->response["result"]=true;
                $this->response["message"] = "Something Went Wrong";
                $this->response["server_message"] = mysqli_error($this->con);
            }
        }else{
            $insert = "INSERT INTO police_location(uid,mobile,lat,lng,updated_at) VALUES('$uuid','$mobile','$lat','$long',NOW())";
            if (mysqli_query($this->con,$insert)){
                $this->response["result"]=true;
                $this->response["message"] = "success";
                $this->response["server_message"] = "success";
            }else{
                $this->response["result"]=true;
                $this->response["message"] = "Something Went Wrong";
                $this->response["server_message"] = mysqli_error($this->con);
            }
        }

        return $this->response;
    }
}