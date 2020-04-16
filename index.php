<?php 
session_start();
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
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index"); 
});

//ROTA PARA VALIDAR O LOGIN
$app->get("/admin/login", function() {
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});
$app->post("/admin/login", function() {
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});
$app->get("/admin/logout", function() {
	User::logout();
	header("Location: /admin/login");
	exit;
});

// ROTAS PARA O CRUD  DOS USUÁRIOS

//Lista todos os usuários
$app->get("/admin/users", function(){
	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array("users"=>$users));
});

$app->get("/admin/users/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});
//Apaga um usuário
$app->get("/admin/users/:iduser/delete", function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
 	exit;
});
//Update
$app->get("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});

$app->post("/admin/users/create", function(){
	User::verifyLogin();
	$user = new User();
 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 	$user->setData($_POST);
 	$user->save();
	header("Location: /admin/users");
 	exit;
});
//Salvar a edição
$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 	$user->get((int)$iduser);
 	$user->setData($_POST);
 	$user->update();
 	header("Location: /admin/users");
 	exit;
});




$app->run();

 ?>