<?php

namespace Library\Core;

class Connexion {

    private static $instance;
    private static $connexions = array();

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public static function setConnexion($name, \PDO $pdo){
        self::$connexions[$name] = $pdo;
    }

    public static function getConnexion($name){
        if(array_key_exists($name, self::$connexions)){
            return self::$connexions[$name];
        }
        throw new \Exception("Error DB Connexion: '$name' not found");
    }

    public static function getListConnexions(){
        return array_keys(self::$connexions);
    }

    private function __construct(){

    }

    public static function connectDB($host, $dbname, $user, $password, $charset){
        $db = new \PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
        $db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        $db->exec("SET CHARACTER SET {$charset}");
        return $db;
    }

}