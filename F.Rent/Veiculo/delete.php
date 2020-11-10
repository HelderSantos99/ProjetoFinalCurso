<?php
if(count(get_included_files()) ==1){ exit("Direct access not permitted."); }

//echo "Carregar Classe";
require_once './objects/Veiculo.php';
$veiculo = new Veiculo($pdo);

$debug .= "<b>Loading</b>: $module/$action.php";


$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);
$submit = filter_input(INPUT_POST, 'submit');
$cancel = filter_input(INPUT_POST, 'cancel');
if ($cancel) {
    header('Location: index.php?m=' . $module . '&a=read');
    exit();
}

if ($submit) {
    $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
    $veiculo->ID = $ID;
    // Eliminar produto
    if ($veiculo->delete()) {
        $html .= 'Veiculo eliminado';
    } else {
        $html .= 'Não foi possível eliminar o Veiculo';
    }
} else {
    $debug .= " <b>SHOW FORM</b>";
    $veiculo->ID = $ID;
    $veiculo->readOne();
    $html .= '                
    <div class="alert-danger">Deseja mesmo eliminar o produto <b>' . $veiculo->Marca . '</b>?</div>
    <form method="POST" action="?m=' . $module . '&a=' . $action . '">
        <input  class="form-control" type="hidden" readonly
                name="ID" value="' . $veiculo->ID . '"><br>
        <input type="submit" class="btn btn-primary" name="submit" value="Eliminar">
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancelar">
    </form>
';
}
echo $html;
