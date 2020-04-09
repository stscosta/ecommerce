<?php 

require_once("vendor/autoload.php");

use \Slim\Slim; // CLasses que serão carregadas 
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();  // criação de rotas 
$app->config('debug', true);

//ROTA DAS PÁGINAS GERAIS
$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index"); 
});

// ROTA DAS PÁGINAS DO ADMINISTRADOR
$app->get('/admin', function() {
	$page = new PageAdmin();
	$page->setTpl("index"); 
});



$app->run();

 ?>