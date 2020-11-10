<?php
// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe 
require_once '../../objects/Reserva.php'; 
$reserva = new Reserva($pdo);

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obter dados do POST
$data = json_decode(file_get_contents("php://input"));

if (!empty($data)) {
    // Validar dados
    $data_levantamento = filter_var($data->data_levantamento, FILTER_SANITIZE_STRING);
    $data_entrega = filter_var($data->data_entrega, FILTER_SANITIZE_STRING);
    $Utilizador_email = filter_var($data->Utilizador_email, FILTER_SANITIZE_EMAIL);
    $Veiculo_ID = filter_var($data->Veiculo_ID, FILTER_SANITIZE_NUMBER_INT);
    
     

    $error = '';
    if ($data_levantamento == '') {
        $error .= 'Nome não definido. ';
    }
    if ($data_entrega == '') {
        $error .= 'Descrição não definida. ';
    }
    if ($Utilizador_email == '') {
        $error .= 'Preço não definido. ';
    }
    if ($Veiculo_ID == '') {
        $error .= 'ID de categoria não definido. ';
    }

    if ($error == '') {
        $reserva->data_levantamento = $data_levantamento;
        $reserva->data_entrega = $data_entrega;
        $reserva->Utilizador_email = $Utilizador_email;
        $reserva->Veiculo_ID = $Veiculo_ID;
        
        

        // Criar produto
        if ($reserva->create()) {
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