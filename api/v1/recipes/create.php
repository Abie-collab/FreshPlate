<?php
require_once "../classes/Recipe.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Acess-Control-Max-Age: 3600");


  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      http_response_code(200);
      exit;
  }

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


if(!isset($pl->title) || trim($pl->title) === '' ||
   !isset($pl->desc)  || trim($pl->desc) === '' ||
   !isset($pl->inst)  || trim($pl->inst) === '' ||
   !is_numeric($pl->ptime) ||
   !is_numeric($pl->ctime) ||
   !is_numeric($pl->serv) ||
   !isset($pl->image) || trim($pl->image) === ''){
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
$recipe_inserted=$rcp->insert_recipe($pl->title,$pl->desc,$pl->inst,$pl->ptime,$pl->ctime,$pl->serv,$pl->image);

if($recipe_inserted){
    http_response_code(200);
      $response =[
        "status" => "success",
        "message" => "Recipe Successfully inserted",
        "data" => []
      ];
      echo json_encode($response);
      exit;
}else{
       
         http_response_code(500);
        $response =[
        "status" => "failed",
        "message" => "server failure.send us an email",
        "data" => []
     ];
      echo json_encode($response);
      exit;
}     

