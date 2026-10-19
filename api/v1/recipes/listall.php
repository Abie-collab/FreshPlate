<?php
require_once "../classes/Recipe.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");

require_once "guard.php";

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

$rcp = new Recipe;
$recipes =$rcp->fetch_recipes();

if(is_array($recipes)){
    $allrecipes = [];
    foreach($recipes as $recp){
        $singlerecp = [
            "id"    => $recp["id"],
            "title" => $recp["title"],
            "desc" =>  $recp["description"],
            "inst" =>  $recp["instructions"],
            "ptime" => $recp["prep_time"],
            "ctime" => $recp["cook_time"],
            "serv" =>  $recp["servings"],
            "image" => $recp["image"],
        ];
        array_push($allrecipes,$singlerecp);
    }

    http_response_code(200);
    $response =[
        "status" => "success",
        "message" => "",
        "data"  =>  $allrecipes
    ];
    echo json_encode($response);
}