<?php



// Carregar e Instanciar Classe
require_once './objects/Veiculo.php';
$veiculo = new Veiculo($pdo);

$debug .= "<b>Loading</b>: $module/$action.php";

$html = '<div>
            <a href="?m=' . $module . '&a=create" class="btn btn-primary">Novo</a>
            <form method="post" action="?m=' . $module . '&a=search">
                    <input type="text" name="s" placeholder="Pesquisar" >
                    <input type="submit" value="Pesquisar" class="btn btn-primary" name="submit">
            </form>

        </div>';

//echo "LISTAR";
$html .= '<table class="table table-striped">';
$html .= '<thead><tr><th>ID</th><th>id_Categoria</th><th>Marca</th>'
        . '<th>Modelo</th><th>Lugares</th><th>Preco_dia</th><th>Combustivel</th><th>Portas</th><th>Caixa</th><th>Empresas_ID</th></tr></thead><tbody>';

// Obter Produtos e número de registos
$stmt = $veiculo->read();
$num = $stmt->rowCount();
if ($num > 0) {
    // Obter conteúdos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= '
            <tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['id_Categoria'] . '</td>
                <td>' . $row['Marca'] . '</td>
                <td>' . $row['Modelo'] . '</td>
                <td>' . $row['Lugares'] . '</td>
                <td>' . $row['Preco_dia'] . '</td>
                <td>' . $row['Combustivel'] . '</td>
                <td>' . $row['Portas'] . '</td>
                <td>' . $row['Caixa'] . '</td>
                <td>' . $row['Empresas_ID'] . '</td>
                <td>
                    <a href="?m=' . $module . '&a=update&id=' . $row['ID'] . '">edit</a> | 
                    <a href="?m=' . $module . '&a=delete&id=' . $row['ID'] . '">del</a>
                </td>
            </tr>';
    }
} else {
    $html .= '<tr><td colspan="6">Sem registos</td></tr>';
}
$html .= '</tbody></table>';

echo $html;
