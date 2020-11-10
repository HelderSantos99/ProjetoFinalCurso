<?php
// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe
require_once '../../objects/Product.php';
$reserva = new Reserva($pdo);

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Obter ID e detalhes do produto
$ID = filter_input(INPUT_GET, 'ID',FILTER_SANITIZE_NUMBER_INT);
$reserva->ID = $ID;
$reserva->readOne();

if ($reserva->name != null) {
    // Array com o produto
    $reservas_arr = array(
        "ID" => $reserva->ID,
        "data_levantamento" => $reserva->data_levantamento,
        "data_entrega" => $reserva->data_entrega,
        "Utilizador_email" => $reserva->Utilizador_email,
        "Veiculo_ID" => $reserva->Veiculo_ID
        
    );

    // Definir resposta - 200 OK
    http_response_code(200);
    echo json_encode($reservas_arr);
} else {
    // Não encontrou produtos - 404 Not found
    http_response_code(404);
    echo json_encode(array("message" => "Registo inexistente."));
}