<?php

namespace Library\Core;


class Router {

    private static $instance;
    private static $protectedRoutes;

    private function __construct(){
        // var_dump('router ok');
    }

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function setProtectedRoutes(Array $routes){
        self::$protectedRoutes = $routes;
    }

    private static function getActionName($name){
        return strtolower($name).'Action';
    }

    private static function getControllerName($name){
        return '\Application\Controllers\\' . ucfirst(strtolower($name));
    }

    private static function getControllerPath($name){
        return APP_ROOT . 'Controllers' . DS . ucfirst(strtolower($name)) . '.php';
    }

    private static function getModuleName($name){
        return '\Application\Modules\\' . ucfirst(strtolower($name));
    }

    private static function getModulePath($name){
        return APP_ROOT . 'Modules' . DS . ucfirst(strtolower($name)) . '.php';
    }


    private static function isValid($route){
        $matchRoute = false;
        foreach (self::$protectedRoutes as $value) {
            if(preg_match("#$value#", $route) === 1){
                $matchRoute = true;
                break;
            }
        }
        if($matchRoute && !isset($_SESSION['user'])){
            return false;
        }
        return true;
    }


    public static function dispatchModule($module=null, $action=null, Array $param=array()) {

        if(is_null($module) || is_null($action)){
            throw new \Exception("Modules name and Action name is required");
        }

        if(file_exists(self::getModulePath($module)) && class_exists(self::getModuleName($module))){
            $moduleClassName = self::getModuleName($module);
            $iModule         = new $moduleClassName();

            if(method_exists($iModule, self::getActionName($action))) {
                $action = self::getActionName($action);

                call_user_func_array(array($iModule, $action), $param);
                call_user_func_array(array($iModule, 'renderModule'), array($moduleClassName, $action));

            } else {
                throw new \Exception("Action: '$action' not found in module: '$module'");
            }
        } else {
            throw new \Exception("Module: '$module' not found");
        }
    }


    public static function dispatchPage($url){
        $url= str_replace("\\",DIRECTORY_SEPARATOR,$url);
        $url= str_replace("/",DIRECTORY_SEPARATOR,$url);
        $urlData    = explode(DS, $url);
        $controller = self::getControllerName('index');
        $action     = self::getActionName('index');

        if(!empty($urlData[0])){
            if(file_exists(self::getControllerPath($urlData[0])) && class_exists(self::getControllerName($urlData[0]))){
                $controller = self::getControllerName($urlData[0]);
                array_splice($urlData, 0, 1);
            } else {
                $controller = self::getControllerName('error');
            }
        }
        
        if(!self::isValid($url)){
            $controller = self::getControllerName('error');
            $action     = self::getActionName('forbidden');
            $urlData    = array();
        }

        $iController = new $controller;

        if(!empty($urlData[0])){
            if(method_exists($iController, self::getActionName($urlData[0]))) {
                $action = self::getActionName($urlData[0]);
                array_splice($urlData, 0, 1);
            }
        }
        
        call_user_func_array(array($iController, $action), $urlData);
        call_user_func_array(array($iController, 'renderView'), array($controller, $action));

    }
}