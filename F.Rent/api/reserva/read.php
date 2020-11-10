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

// Obter produtos
$stmt = $reserva->read();
$num = $stmt->rowCount();

if ($num > 0) {
    // Array de produtos
    $reservas_arr = array();
    $reservas_arr["records"] = array();

    // Obter registos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reserva_item = array(
            "ID" => $row['ID'],
            "data_levantamento" => $row['data_levantamento'],
            "data_entrega" => $row['data_entrega'],
            "Utilizador_email" => $row['Utilizador_email'],
            "Veiculo_ID" => $row['Veiculo_ID']
            
        );
        array_push($reservas_arr["records"], $reserva_item);
    }
    // Definir resposta - 200 OK
    http_response_code(200);
    echo json_encode($reservas_arr);
} else {
    // Não encontrou produtos - 404 Not found
    http_response_code(404);
    echo json_encode(array("message" => "Sem registos."));
}