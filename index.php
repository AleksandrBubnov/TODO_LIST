<?php

require_once('vendor/autoload.php');

$request_uri = preg_split('/\/|\?/', $_SERVER['REQUEST_URI']);
// 1 индекс - назваание контролёра
// 2 индекс - назваание action

$controllerName = !isset($request_uri[1]) ? 'user' : ($request_uri[1] == "" ? 'user' : $request_uri[1]);
$actionName = !isset($request_uri[2]) ? 'index' : ($request_uri[2] == "" ? 'index' : $request_uri[2]);

$controllerPath = 'controllers/' . ucfirst($controllerName) . 'Controller.php';

try {
    if (file_exists($controllerPath)) {
        $controllerClassName = '\\controllers\\' . ucfirst($controllerName) . 'Controller';
        $controller = new $controllerClassName;
        $actionClassName = 'action' . ucfirst($actionName);
        if (method_exists($controller, $actionClassName)) {
            $controller->$actionClassName();
        } else {
            throw new Exception("not found method_exists($controller, $actionClassName)");
        }
    } else {
        throw new Exception("not found $controllerPath");
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
    require_once('views/_shared/error.php');
}
