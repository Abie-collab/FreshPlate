<?php
require_once "../classes/Recipe.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Acess-Control-Max-Age: 3600");

$rm = $_SERVER["REQUEST_METHOD"];
if($rm != "POST"){
    http_response_code(405);
    $response =[
        "status" => "failed",
        "message" => "The expected method is POST",
        "data" => []
    ];

    echo json_encode($response);
    exit;
}

$prod = file_get_contents("php://input");

$pl = json_decode($prod);

// echo json_encode([
//     "raw_body" => $prod,
//     "decode_error" => json_last_error_msg(),
//     "decoded" => $pl
// ]);
// exit;


if(!isset($pl->title) || !isset($pl->desc) || !isset($pl->inst) || !isset($pl->ptime) || !isset($pl->ctime) || !isset($pl->serv) || !isset($pl->image)){
    http_response_code(400);
    $response =[
        "status" => "failed",
        "message" => "title,description,instructions,preptime,cooktime and image should be included in payload",
        "data"  => []
    ];
    echo json_encode($response);
    exit;
}

if(empty($pl->title) || empty($pl->desc) || empty($pl->inst) || empty($pl->ptime) || empty($pl->ctime) || empty($pl->serv) || empty($pl->image)){
    http_response_code(400);
    $response =[
        "status" => "failed",
        "message" => "title,description,instructions,preptime,cooktime and image should be included in payload",
        "data"  => []
    ];
    echo json_encode($response);
    exit;
}

$rcp = new Recipe;
$recipe_updated=$rcp->update_recipe($pl->title,$pl->desc,$pl->inst,$pl->ptime,$pl->ctime,$pl->serv,$pl->image,$pl->id);

if($recipe_updated){
    http_response_code(200);
      $response =[
        "status" => "success",
        "message" => "Recipe Successfully updated",
        "data" => []
      ];
      echo json_encode($response);
      exit;
}else{  
        http_response_code(500);
        $response =[
        "status" => "failed",
        "message" => "server failure.",
        "data" => []
     ];
      echo json_encode($response);
      exit;
}     

