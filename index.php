<?php 

require_once("vendor/autoload.php");

use \Slim\Slim; // CLasses que serão carregadas 
use \Hcode\Page;

$app = new Slim();  // criação de rotas 
$app->config('debug', true);
$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index"); 

});

$app->run();

 ?>