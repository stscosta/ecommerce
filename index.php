<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;

$app = new Slim();

$app->config('debug', true);

require_once("functions.php");

require_once("site.php");

require_once("admin.php");

require_once("admin-users.php");

require_once("admin-categories.php");

require_once("admin-products.php");

//require_once("admin-orders.php");





//ROTA DAS PÁGINAS GERAIS
/*$app->get('/', function() {
	$page = new Page();
	$page->setTpl("index"); 
});
*/
// ROTA DAS PÁGINAS DO ADMINISTRADOR
/*$app->get('/admin', function() {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index"); 
});
*/
//ROTA PARA VALIDAR O LOGIN
/*$app->get("/admin/login", function() {
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});*/
/*$app->post("/admin/login", function() {
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});
$app->get("/admin/logout", function() {
	User::logout();
	header("Location: /admin/login");
	exit;
});
*/
// ROTAS PARA O CRUD  DOS USUÁRIOS

//Lista todos os usuários
/*$app->get("/admin/users", function(){
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
});*/
//Update
/*$app->get("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});
*/
/*$app->post("/admin/users/create", function(){
	User::verifyLogin();
	$user = new User();
 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 	$user->setData($_POST);
 	$user->save();
	header("Location: /admin/users");
 	exit;
});*/
//Salvar a edição
/*$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
 	$user->get((int)$iduser);
 	$user->setData($_POST);
 	$user->update();
 	header("Location: /admin/users");
 	exit;
});
*/
// ESQUECEU A SENHA
/*$app->get("/admin/forgot", function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot");
	exit;
});

$app->post("/admin/forgot", function(){
	$user = User::getForgot($_POST["email"]);
	header("Location: /admin/forgot/sent");
	exit;
});

$app->get("/admin/forgot/sent", function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-sent");
	exit;
});
*/
/*$app->get("/admin/forgot/reset", function(){
	$user = User::ValidForgotDecrypt($_GET["code"]);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));
	exit;
});

$app->post("/admin/forgot/reset", function(){
	$forgot = User::validForgotDecrypt($_POST["code"]);
	User::setFogotUsed($forgot["idrecovery"]);
	$user = new User();
	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT,["cost"=>12]);

	$user->setPassword($password);
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("forgot-reset-success");
	
});
*/
// CRUD DAS CATEGORIAS

/*$app->get("/admin/categories", function(){
	User::verifyLogin();
	$categories = Category::listAll();
	$page = new PageAdmin();
	$page->setTpl("categories",[
		'categories'=>$categories
	]);
});
$app->get("/admin/categories/create", function(){
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});

$app->post("/admin/categories/create", function(){
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	header('Location: /admin/categories');
	exit;
});
*/
/*$app->get("/admin/categories/:idcategory/delete", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->delete();
	header('Location: /admin/categories');
	exit;
});

$app->get("/admin/categories/:idcategory", function($idcategory){
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-update", [
		'category'=>$category->getValues()
	]);
});
*/
/*$app->post("/admin/categories/:idcategory", function($idcategory){
    User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();
	header('Location: /admin/categories');
	exit;
});

// ADITANDO O MENU DE CATEGORIAS
$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(

		'category'=>$category->getValues(),
		'products'=>[]

	));

});

*/








$app->run();

?>