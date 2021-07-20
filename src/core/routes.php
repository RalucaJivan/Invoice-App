<?php

$app->get("/setup","SetupController:createDatabase")->setName("name");//numele rutei - creaza tabelele in baza de data

$app->get("/", "HomeController:index")->setName("home");
$app->get("/list", "HomeController:list")->setName("list");

//get - primire de date
$app->group("/account", function () use($app){
    $app->get("/login", "AccountController:login")->setName("login");//se incarca twigurile
    $app->get("/register", "AccountController:register")->setName("register");//se incarca twigurile
});

//post- update/inserare=adaugare de date
$app->group("/api",function () use($app){
    $app->group("/account",function () use($app){
        $app->post("/register", "ApiController:actionRegister")->setName("register");
        $app->post("/login", "ApiController:actionLogin")->setName("login");
    });
    $app->group("/invoice",function() use ($app){
        $app->post("/create","ApiController:actionCreateInvoice")->setName("invoice");
        $app->get("/all","ApiController:actionGetAllInvoices")->setName("invoices");//ruta pt returnarea tuturor facturilor al unui user
    })->add(new \App\Middlewares\ApiMiddleware($app->getContainer()));
});


// daca apelezi interfata atunci /account/register     http://localhost:8080/account/login
// daca apelezi backendul atunci /api/account/register http://localhost:8080/api/account/login