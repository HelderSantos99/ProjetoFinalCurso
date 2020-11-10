<?php

if (count(get_included_files()) == 1) {
    exit("Direct access not permitted.");
}

// Carregar e Instanciar Classe
require_once './objects/Reserva.php';
$reserva = new Reserva($pdo);

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
$html .= '<thead><tr><th>ID</th><th>data_levantamento</th><th>data_entrega</th>'
        . '<th>Utilizador_email</th><th>Veiculo_ID</th></tr></thead><tbody>';

// Obter Produtos e número de registos
$stmt = $reserva->read();
$num = $stmt->rowCount();
if ($num > 0) {
    // Obter conteúdos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $html .= '
            <tr>
                <td>' . $row['ID'] . '</td>
                <td>' . $row['data_levantamento'] . '</td>
                <td>' . $row['data_entrega'] . '</td>
                <td>' . $row['Utilizador_email'] . '</td>
                <td>' . $row['Veiculo_ID'] . '</td>
                <td>
                    <a href="?m=' . $module . '&a=delete&ID=' . $row['ID'] . '">del</a>
                </td>
            </tr>';
    }
} else {
    $html .= '<tr><td colspan="6">Sem registos</td></tr>';
}
$html .= '</tbody></table>';

echo $html;
