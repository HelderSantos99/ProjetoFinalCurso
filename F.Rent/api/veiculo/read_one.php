<?php
// Carregar configurações
require_once '../../config.php';
$pdo = connectDB($db_web);
// Carregar classe
require_once '../../objects/Veiculo.php';
$veiculo = new Veiculo($pdo);

// Definição do cabeçalho
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Obter ID e detalhes do produto
$ID = filter_input(INPUT_GET, 'ID',FILTER_SANITIZE_NUMBER_INT);
$veiculo->ID = $ID;
$veiculo->readOne();

if ($veiculo->name != null) {
    // Array com o produto
    $veiculo_arr = array(
        "ID" => $veiculo->ID,
        "id_Categoria" => $veiculo->id_Categoria,
        "Marca" => $veiculo->Marca,
        "Modelo" => $veiculo->Modelo,
        "Lugares" => $veiculo->Lugares,
        "Preco_dia" => $veiculo->Preco_dia,
        "Combustivel" => $veiculo->Combustivel,
        "Portas" => $veiculo->Portas,
        "Caixa" => $veiculo->Caixa,
        "Empresas_ID" => $veiculo->Empresas_ID
    );

    // Definir resposta - 200 OK
    http_response_code(200);
    echo json_encode($veiculo_arr);
} else {
    // Não encontrou produtos - 404 Not found
    http_response_code(404);
    echo json_encode(array("message" => "Registo inexistente."));
}