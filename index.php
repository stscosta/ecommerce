<?php 

require_once("vendor/autoload.php");

use \Slim\Slim; // CLasses que serão carregadas 
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

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

//ROTA PARA VALIDAR O LOGIN
$app->get('/admin/login', function() {
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});
$app->post('/admin/login', function() {
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});
$app->get('/admin/logout', function() {
	User::logout();
	header("Location: /admin/login");
	exit;
});


$app->run();

 ?>