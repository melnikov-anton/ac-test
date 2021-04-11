<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__, 2));

require_once(ROOT . DS . 'config' . DS . 'autoload.php');

$dbConfig = require_once(ROOT . DS . 'config' . DS . 'db_connection.php');
Db::getDb($dbConfig);

$routes = require_once(ROOT . DS . 'config' . DS . 'routes.php');
$router = new Router($routes);
$router->route();
