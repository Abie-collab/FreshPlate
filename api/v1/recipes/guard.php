<?php
require_once "../classes/Authentication.php";

if(!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"])){
   http_response_code(401);
     $response =[
    "status" => "failed",
    "message" => "Authentication required, Provide username and password",
    "data" => []
   ];
   echo json_encode($response);
   exit;

   $username = $_SERVER["PHP_AUTH_USER"];
   $password = $_SERVER["PHP_AUTH_PW"];

$auth = new Authentication;
$user_exist=$auth->check_user($username,$password);

if($user_exist == false){
    http_response_code(401);
    $response =[
        "status" => "failed",
        "message" => "Invalid Authentication. Username|Password is incorrect",
        "data" => []
    ];
    echo json_encode($response);
    exit;

}

}