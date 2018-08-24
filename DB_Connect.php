<?php
/**
 * Created by PhpStorm.
 * User: RVishwakarma
 * Date: 8/23/2018
 * Time: 11:01 AM
 */

class DB_Connect
{
    public function Connect(){
        require_once 'config.php';
        $conn = mysqli_connect(HOST,USER,PASS);
        $select_DB = mysqli_select_db($conn,DB);
        return $conn;
    }
}