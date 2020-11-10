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

if (!empty($data)) {
    // Validar dados
    $user->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
    $user->password = filter_var($data->password, FILTER_SANITIZE_STRING);
    $error = '';
    if ($user->password == '') {
        $error .= 'Password não definida. ';
    }
    if ($user->email == '') {
        $error .= 'Email não definido. ';
    }
    if (!$user->emailExists()) {
        $error .= 'Email não existe. ';
    }

    if ($error == '') {
        if (password_verify($data->password, $user->password)) {
            //Criar token
            $token = array (
                "iss" => $iss,
                "jti" => $jti,
                "iat" => $iat,
                "nbf" => $nbf,
                "exp" => $exp,
                "data" => array(
                    
                    "nome" => $user->nome,
                    "email" => $user->email
                )              
            );
            // Sucesso na autenticação - 200 Ok
            http_response_code(200);
            $jwt = JWT::encode($token, $key);
            echo json_encode(
                    array(
                        "message" => "Autenticado com sucesso.",
                        "jwt" => $jwt
                    )
            );
    } else {
        //Erros no pedido - 503 service unavailable
        http_responde_code(401);
        // Enviar resposta com mensagens de erro
        echo json_encode(array("message" => 'Erro na autenticação'));              
    }
    } else {
        // Erros no pedido - 400 bad request
        http_response_code(400);
        // Enviar resposta com mensagens de erro
        echo json_encode(array("message" => $error));
    }
} else {
    // Erros no pedido - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => 'Pedido sem informação'));
}    