<?php

// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe
require_once '../../objects/Veiculo.php';
$veiculo = new Veiculo($pdo);

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Obter dados do POST
$data = json_decode(file_get_contents("php://input"));
// Obter keywords
$keywords = filter_var($data->s,FILTER_SANITIZE_STRING);

if (!empty($keywords)) {
// Pesquisar
    $stmt = $veiculo->search($keywords);
    $num = $stmt->rowCount();

    // Verificar se existem resultados
    if ($num > 0) {
        // products array
        $veiculo_arr = array();
        $veiculo_arr["records"] = array();

        // Obter registos
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $veiculo_item = array(
                "ID" => $row['ID'],
                "id_Categoria" => $row['id_Categoria'],
                "Marca" => $row['Marca'],
                "Modelo" => $row['Modelo'],
                "Lugares" => $row['Lugares'],
                "Preco_dia" => $row['Preco_dia'],
                "Combustivel" => $row['Combustivel'],
                "Portas" => $row['Portas'],
                "Caixa" => $row['Caixa'],
                "Empresas_ID" => $row['Empresas_ID']
            );
            array_push($veiculo_arr["records"], $veiculo_item);
        }
        // Definir resposta - 200 OK
        http_response_code(200);
        echo json_encode($veiculo_arr);
    } else {
        // Não encontrou registos - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "Nenhum registo encontrado."));
    }
} else {
    // Erros no pedido - 400 bad request
    http_response_code(400);
    echo json_encode(array("message" => 'Pedido sem informação'));
}