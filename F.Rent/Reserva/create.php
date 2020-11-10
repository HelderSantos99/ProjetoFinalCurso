<?php
if(count(get_included_files()) ==1){ exit("Direct access not permitted."); }

//echo "Carregar Classe";
require_once './objects/Reserva.php';
$reserva = new Reserva($pdo);

$debug .= "<b>Loading</b>: $module/$action.php";

$submit = filter_input(INPUT_POST, 'submit');
if ($submit) {
    $debug .= " <b>INSERT INTO DB</b>";
    // Verificar dados do formulário
    $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
    $data_levantamento = filter_input(INPUT_POST, 'data_levantamento', FILTER_SANITIZE_STRING);
    $data_entrega = filter_input(INPUT_POST, 'data_entrega', FILTER_SANITIZE_STRING);
    $Utilizador_email = filter_input(INPUT_POST, 'Utilizador_email', FILTER_SANITIZE_STRING);
    $Veiculo_ID = filter_input(INPUT_POST, 'Veiculo_ID', FILTER_SANITIZE_NUMBER_INT);
    

    $errors = false;
    if ($ID == '') {
        $html .= '<div class="alert-danger">Tem que definir um ID</div>';
        $errors = true;
    }
    if ($data_levantamento == '') {
        $html .= '<div class="alert-danger">Tem que definir uma descrição.</div>';
        $errors = true;
    }
    if ($data_entrega == '') {
        $html .= '<div class="alert-danger">Tem que definir um preço.</div>';
        $errors = true;
    }
    if ($Utilizador_email == '') {
        $html .= '<div class="alert-danger">Tem que definir um ID de categoria.</div>';
        $errors = true;
    }
    if ($Veiculo_ID == '') {
        $html .= '<div class="alert-danger">Tem que definir um ID de categoria.</div>';
        $errors = true;
    }

    if (!$errors) {
        $debug .= '<p>Informação válida proceder ao registo.</p>';
        $reserva->ID = $ID;
        $reserva->data_levantamento = $data_levantamento;
        $reserva->data_entrega = $data_entrega;
        $reserva->Utilizador_email = $Utilizador_email;
        $reserva->Veiculo_ID = $Veiculo_ID;
        

        // Criar produto
        if ($reserva->create()) {
            $html .= 'Produto criado';
        }else {
            $html .= 'Não foi possível criar Produto';
        }
    }
} else {
    $debug .= " <b>SHOW FORM</b>";
    $html .= '                
    <form method="POST" action="?m=' . $module . '&a='.$action.'">
        <input class="form-control" type="text" size="2" placeholder="ID" name="ID" disabled><br>
        <input class="form-control" type="text" placeholder="Nome" name="NOME"><br>
        <input class="form-control" type="text" placeholder="Descrição" name="DESC"><br>
        <input class="form-control" type="text" placeholder="Preço" name="PRECO"><br>
        <input class="form-control" type="text" placeholder="Categoria" name="CATEGORIA"><br>
        <input type="submit" class="btn btn-primary" name="submit" value="Adicionar">
        <input type="reset" class="btn btn-secondary" value="Limpar">
    </form>
';
}
echo $html;
