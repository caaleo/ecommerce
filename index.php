<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

// Tela principal
$app->get('/', function() {

    $page = new Page();

    $page->setTpl("index");

});
// Tela de admin
$app->get('/admin', function() {

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("index");

});
// Tela de login
$app->get('/admin/login', function (){

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("login");
});

// Rota para tela de login caso tente entrar sem logar
$app->post('/admin/login', function(){

    User::login($_POST["login"], $_POST["password"]);

    header("Location: /admin");
    exit;

});
// Rota de logout tela admin
$app->get('/admin/logout', function (){

    User::logout();

    header("Location: /admin/login");
    exit;

});
// Rota para listar usuário
$app->get("/admin/users", function (){

    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl("users", array(
        "users"=>$users
    ));

});

// Rota para criar usuário
$app->get("/admin/users/create", function (){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("users-create");

});

// Deletar o usuário
$app->get("/admin/users/:iduser/delete", function ($iduser){

    User::verifyLogin();

});

// Rota para editar usuário
$app->get("/admin/users/:iduser", function ($iduser){

    User::verifyLogin();

    $page = new PageAdmin();

    $page->setTpl("users-update");

});

// Salvar a criação do usuário
$app->post("/admin/users/create", function (){

    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);

    $user->save();

    header("Location: /admin/users");
    exit;

});

// Salvar a edição do usuário
$app->post("/admin/users/:iduser", function ($iduser){

    User::verifyLogin();

});



$app->run();

 ?>