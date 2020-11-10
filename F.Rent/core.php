<?php

/**
 * Função para fazer ligação à Base de Dados
 * @param array $db - Array com os elmentos necessários à ligação à base dados
 * @return \PDO Ligação PDO à Base de Dados
 */
function connectDB($db) {
    try {
        $sqldb = new PDO(
                'mysql:host=' . $db['host'] . '; ' .
                'dbname=' . $db['dbname'] . ';' .
                'port=' . $db['port'] . ';' .
                'charset=' . $db['charset'] . ';',
                $db['username'], $db['password']);
    } catch (PDOException $e) {
        die('Erro ao ligar ao servidor ' . $e->getMessage());
    }
    return $sqldb;
}

/**
 * Função para carregar os items do menu
 * @param $module string com o módulo ativo
 * @param $modules array com os módulos
 * @return string String com HTML do menu
 */
function loadMenu($module = '', $modules = Array()) {
    $html = '';
    foreach ($modules as $m => $item) {
        if ($item['enabled']) {
            $html .= '<li class="nav-item">
        <a class="nav-link ' . ( ($m == $module) ? 'active' : '' ) . '" href="' . $item['link'] . '">' . $item['text'] . '</a></li>';
        }
    }
    return $html;
}

/**
 * Função para validar um módulo
 * @param $module string com o módulo a validar
 * @param $modules array com os módulos
 * @return Boolean Devolve <b>TRUE</b> caso o módulo exista, <b>FALSE</b> caso contrário
 */
function validateModule($module, $modules) {
    $result = false;
    foreach ($modules as $m => $item) {
        $result = $result || $m == $module;
    }
    return $result;
}

/**
 * Função para paginação
 * @param int $page - número da página atual
 * @param int $total_rows - número total de registos
 * @param int $records_per_page - número de registos por página
 * @param string $page_url - URI para paginação
 * @return type
 */
function getPaging($page, $total_rows, $records_per_page, $page_url) {

    // Array de paginação
    $paging_arr = array();

    // Ir para primeira página
    $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";

    // Calcular total de páginas
    $total_pages = ceil($total_rows / $records_per_page);

    // Número de ligações para páginas disponíveis
    $range = 2;
    $initial_num = $page - $range;
    $condition_limit_num = ($page + $range) + 1;

    $paging_arr['pages'] = array();
    $page_count = 0;

    // Criar Array com ligações para páginas
    for ($x = $initial_num; $x < $condition_limit_num; $x++) {
        // Verificar se '$x é maior do que 0' AND 'menor ou igual que $total_pages'
        if (($x > 0) && ($x <= $total_pages)) {
            $paging_arr['pages'][$page_count]["page"] = $x;
            $paging_arr['pages'][$page_count]["url"] = "{$page_url}page={$x}";
            $paging_arr['pages'][$page_count]["current_page"] = $x == $page ? "yes" : "no";

            $page_count++;
        }
    }

    // Ir para a última página
    $paging_arr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";

    // Devolver Array
    return $paging_arr;
}
