<?php

use DI\ContainerBuilder;
use Slim\App;

define('API_NAMESPACE',         'MSALPoc');
define('API_DIR_ROOT',          __DIR__ . '/..');
define('API_DIR_DOMAIN',        API_DIR_ROOT . DIRECTORY_SEPARATOR . 'Domain' . DIRECTORY_SEPARATOR);
define('API_DIR_API',         	API_DIR_ROOT . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR);
define('API_DIR_CONTROLLERS',   API_DIR_API . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'v1' . DIRECTORY_SEPARATOR);

require_once API_DIR_ROOT . '/vendor/autoload.php';
require_once API_DIR_ROOT . '/autoload.php'; 

$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create App instance
$app = $container->get(App::class);

// Register routes
(require __DIR__ . '/routes.php')($app);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

// Register middleware
(require __DIR__ . '/middleware.php')($app);

return $app;