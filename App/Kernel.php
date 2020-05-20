<?php

namespace App;

use App;

class Kernel
{
    public $defaultControllerName = 'Home';

    public $defaultActionName = "index";

    public function launch()
    {

        list($controllerName, $actionName, $params) = App::$router->resolve();
        echo $this->launchAction($controllerName, $actionName, $params);

    }

    public function launchAction($controllerName, $actionName, $params)
    {

        $controllerName = empty($controllerName) ? $this->defaultControllerName : ucfirst($controllerName);
        if (!file_exists(CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $controllerName . '.php')) {
            throw new \App\Exceptions\InvalidRouteException();
        }
        require_once CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $controllerName . '.php';
        if (!class_exists("\\App\\Http\\Controllers\\" . ucfirst($controllerName))) {
            throw new \App\Exceptions\InvalidRouteException();
        }
        $controllerName = "\\App\\Http\\Controllers\\" . ucfirst($controllerName);
        $controller = new $controllerName;
        $actionName = empty($actionName) ? $this->defaultActionName : $actionName;
        if (!method_exists($controller, $actionName)) {
            throw new \App\Exceptions\InvalidRouteException();
        }
        return $controller->$actionName($params);

    }
}
