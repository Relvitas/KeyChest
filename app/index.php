<?php
//llamada utilidad control de errores custom
require_once 'utils/custom_error_handler.php';
//llamada a conexión db
require_once 'db/connection.php';
// llamada utilidad alertas
require_once 'utils/alerts.php';

$controller = (isset($_GET['c']) && !empty($_GET['c'])) 
    ? $_GET['c'] 
    : 'user';

$action = (isset($_GET['a']) && !empty($_GET['a'])) 
    ? $_GET['a'] 
    : 'index';

$view = (isset($_GET['v']) && !empty($_GET['v'])) 
    ? $_GET['v'] 
    : null;


//llamada a controlador
require_once 'controllers/' . $controller . '.php';
//codificación de nombre de controlador
$controller = ucwords($controller) . 'Controller';
//instancia de controlador
$instance = new $controller();
//acción a ejecutar
$view === null ? $instance->$action() : $instance->view($view);
