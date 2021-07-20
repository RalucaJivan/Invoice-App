<?php

$container['HomeController'] = function ($c) {
    return new \App\Controllers\HomeController($c);
};

$container['AccountController'] = function ($c) {
    return new \App\Controllers\AccountController($c);
};

$container['SetupController'] = function ($c) {
    return new \App\Controllers\SetupController($c);
};

$container['ApiController'] = function ($c) {
    return new \App\Controllers\APIController($c);
};