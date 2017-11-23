<?php

namespace Library\Loader;

class Autoloader {

    private static $instance;
    private static $basePath;


    private function __construct(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }


    public static function setBasePath($value){
        if(is_string($value)) {
            self::$basePath = $value;
        }
    }


    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }


    protected static function autoload($className){

        $path = self::$basePath . $className . '.php';
        $path= str_replace("\\",DIRECTORY_SEPARATOR,$path);
        $path= str_replace("/",DIRECTORY_SEPARATOR,$path);

        if(!file_exists($path)){
            throw new \Exception("Error namespace file not found : '$path'");
        }
        require($path);
    }
}