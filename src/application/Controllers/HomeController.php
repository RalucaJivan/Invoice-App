<?php

namespace App\Controllers;

use App\App;

class HomeController extends App
{
    public function index($request, $response, $args){
        return $this->view->render($response, "home/index.twig");
    }

    public function list($request, $response, $args){
        return $this->view->render($response, "list/index.twig");
    }
}