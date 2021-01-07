<?php 
use Dotenv\Dotenv;
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();
$dotenv->required('DB_HOST')->notEmpty();
$dotenv->required('DB_NAME')->notEmpty();
$dotenv->required('DB_USER')->notEmpty();
$dotenv->required('DB_PASS')->notEmpty();
$dotenv->required('API_KEY')->notEmpty();

?>  