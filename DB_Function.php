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

    function authenticateUser($mobile,$role){

        return "DONE";
    }

    function authenticatePolice(){

    }

}