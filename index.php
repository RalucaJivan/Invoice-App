<?php

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/src/vendor/autoload.php';

session_start();

$settings = require_once __DIR__ . '/src/configs/configs.php';
$app = new \Slim\App($settings);

require_once __DIR__ . '/src/core/init.php';
require_once __DIR__ . '/src/core/controllers.php';
require_once __DIR__ . '/src/core/routes.php';

require_once __DIR__ . '/src/models/user.php';
require_once __DIR__ . '/src/models/authTokens.php';
require_once __DIR__ . '/src/models/invoice.php';
require_once __DIR__ . '/src/models/products.php';

require_once __DIR__ . '/src/application/Helpers/DatabaseBuilder.php';
require_once __DIR__ . '/src/application/Middlewares/ApiMiddleware.php';


try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
} catch (\Slim\Exception\NotFoundException $e) {
} catch (Exception $e) {
}
