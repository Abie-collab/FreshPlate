<?php 
require_once "../classes/Recipe.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");

//step 2:check if request method is wrong: expecting GET -$_SERVER["REQUEST_METHOD"]
$rm = $_SERVER["REQUEST_METHOD"];
if($rm != "GET"){
   http_response_code(405);
      $response =[
        "status" => "failed",
        "message" => "The expected method is GET",
        "data" => []
    ];
  
    echo json_encode($response);
    exit;
}

if(!isset($_GET["id"]) || empty($_GET["id"])){
     http_response_code(400);
      $response =[
        "status" => "failed",
        "message" => "Kindly supply the id of the recipe you want its detail",
        "data" => []
    ];
      echo json_encode($response);
     exit;
}

// step 4- validate that the id is a number
if(is_nan($_GET["id"])){
     http_response_code(400);
      $response =[
        "status" => "failed",
        "message" => "wrong data type,expected data type is (int)",
        "data" => []
    ];
      echo json_encode($response);
     exit;
}

$recp = new Recipe;
$rdeet = $recp->fetch_recipe_full($_GET["id"]);
if(is_array($rdeet) && count($rdeet) > 0){
    http_response_code(200);
    $response =[
        "status" => "success",
        "message" => "",
        "data"  => $rdeet
    ];
     echo json_encode($response);
       exit;
  }else{
         http_response_code(404);
        $response =[
            "status" => "failed",
            "message" => "recipe not found",
            "data" => []
       ];
       echo json_encode($response);
       exit;
  }
