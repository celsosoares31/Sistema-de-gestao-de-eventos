<?php

namespace App\core;

final class Core
{
    public static function init($urlGet)
    {
        $namespace = "App\class\controller";

        if (!isset($urlGet['page'])) {
            $controller = "HomeController";
        } else {
            $controller = ucfirst($urlGet['page'].'Controller');
        }

        $class_name = $namespace."\\".$controller;

        if(!isset($urlGet['action'])) {
            $action = 'index';
        } else {
            $action = $urlGet['action'];
        }
        $params = array();
        if(isset($urlGet['id'])) {
            $id = $urlGet['id'];
            $params[] = $id;
        }

        if (!class_exists($class_name)) {
            $class_name = $namespace."\\".'HomeController';
        }
        call_user_func_array(array(new $class_name(), $action), $params);
    }
   
}
?>