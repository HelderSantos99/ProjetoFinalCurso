<?php

// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe
require_once '../../objects/User.php';
$user = new User($pdo);

// Carregar JWT
require_once '../../common/php-jwt-master/src/BeforeValidException.php';
require_once '../../common/php-jwt-master/src/ExpiredException.php';
require_once '../../common/php-jwt-master/src/SignatureInvalidException.php';
require_once '../../common/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, "
        . "Access-Control-Allow-Headers, "
        . "Authorization, X-Requested-With");

// Obter dados do POST
$data = json_decode(file_get_contents("php://input"));

// Obter JWT
$jwt = isset($data->jwt) ? $data->jwt : "";
if ($jwt) {
    try {
        // Decode do JWT
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        // Sucesso na operação - 200 OK
        http_response_code(200);
        // Enviar Resposta
        echo json_encode(array(
            "message" => "Acesso autorizado.",
            "data" => $decoded->data
        ));
        
    } catch (Exception $e) {
    // Acesso negado - 401 Unauthorized
    http_response_code(401);
        echo json_encode(array(
            "message" => "Acesso negado",
            "error" => $e->getMessage()
        ));
    }
} else {
    // Acesso negado - 401 Unauthorized
    http_response_code(401);
    echo json_encode(array("message" => 'Acesso negado'));
}