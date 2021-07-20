<?php

namespace App\Controllers;

use App\App;

class AccountController extends App
{
    public function login($request, $response, $args)
    //aici incarcam doar paginile de login, partea de backend in ApiController
    {
        return $this->view->render($response, "account/login.twig");
    }

    public function actionLogin($request, $response, $args)
    {

    }

    public function register($request, $response, $args)
    {
        return $this->view->render($response, "account/register.twig");
    }

    public function actionRegister($request, $response, $args)
    {

    }
}