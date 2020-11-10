<?php

// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe
require_once '../../objects/User.php';
$user = new User($pdo);

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
    $user->nome = filter_var($data->nome, FILTER_SANITIZE_STRING);
    $user->password = filter_var($data->password, FILTER_SANITIZE_STRING);
    $user->email = filter_var($data->email, FILTER_SANITIZE_EMAIL);

    $error = '';
    if ($user->nome == '') {
        $error .= 'Username não definido. ';
    }
    if ($user->password == '') {
        $error .= 'Password não definida. ';
    }
    if ($user->email == '') {
        $error .= 'Email não definido. ';
    }
    if ($user->emailExists()) {
        $error .= 'Email já registado. ';      
    }

    if ($error == '') {
        // Criar Utilizador
        if ($user->create()) {
            // Sucesso na criação - 201 created
            http_response_code(201);
            echo json_encode(array("message" => "Registo criado"));
        } else {
            // Erros no pedido - 503 service unavailable
            http_response_code(503);
            // Enviar resposta com mensagens de erro
            echo json_encode(array("message" => 'Erro ao criar registo'));
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