<?php

/* Descrição: Configurações da aplicação
 * Autor: Mário Pinto
 * 
 */
define('DESC', 'Página principal');
define('DEBUG', true);
define('UC', 'PAW');
define('AUTHOR', 'Hélder Santos');

define('SERVER_URL','https://esan-tesp-ds-paw.web.ua.pt/');
define('HOME_URL',SERVER_URL.'tesp-ds-g9/F.Rent/api/');
/**
 * Definições JWT
 */
$key = "example_key";
$iss = SERVER_URL;
$jti = base64_encode(mcrypt_create_iv(32));
$iat = time();
$nbf = $iat;   
$exp = $iat + 600;   // Válido durante 60'

$db_web = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'dbname' => 'esan-dsg09',
    'username' => 'esan-dsg09-web',
    'password' => 'TJOUNbJO2AwnQaTo',
    'charset' => 'utf8'
];
$db_dbo = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'dbname' => 'esan-dsg09',
    'username' => 'esan-dsg09-dbo',
    'password' => 'GUQ5bXxyYQplf7Rl',
    'charset' => 'utf8'
];

/**
 * Array que contém os módulos da aplicação 
 */
$modules = Array(
    'home' => Array('enabled' => true, 'text' => 'Home', 'link' => '?'),
    'user' => Array('enabled' => true, 'text' => 'Utilizadores', 'link' => '?'),
    'product' => Array('enabled' => true, 'text' => 'Produtos', 'link' => '?m=product&a=read'),
    'purchase' => Array('enabled' => false, 'text' => 'Compras', 'link' => '?')
);

// Integrar ficheiro core.php
require_once 'core.php';