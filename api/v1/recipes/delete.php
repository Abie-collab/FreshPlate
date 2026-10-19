<?php 
require_once "../classes/Recipe.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

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

if(!isset($pl->id) || empty($pl->id)){
    http_response_code(400);
    $response =[
        "status" => "failed",
        "message" => "Kindly supply the id of the recipe you want to delete",
        "data" => []
    ];
    echo json_encode($response);
    exit;
}

if(is_nan($pl->id)){
    http_response_code(400);
    $response =[
        "status" => "failed",
        "message" => "wrong data type, expected data type is (int)",
        "data" => []
    ];
    echo json_encode($response);
    exit;
}

$recp = new Recipe;
$rdeet = $recp->delete_recipe($pl->id);

if($rdeet === true){
    http_response_code(200);
    $response = [
        "status" => "success",
        "message" => "Recipe successfully deleted",
        "data" => []
    ];
    echo json_encode($response);
    exit;
}else{
    http_response_code(404);
    $response = [
        "status" => "failed",
        "message" => "Recipe not found or already deleted",
        "data" => []
    ];
    echo json_encode($response);
    exit;
}