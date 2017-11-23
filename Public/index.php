<?php

    session_start();

    require('../Library/Loader/Autoloader.php');
    $autoloader = \Library\Loader\Autoloader::getInstance();
    $autoloader::setBasePath(str_replace('Public', '', __DIR__));

    \Application\Configs\Settings::getInstance();

    $connexion = \Library\Core\Connexion::getInstance();
    $connexion::setConnexion("localhost", $connexion::connectDB(
        DB_HOST,
        DB_NAME,
        DB_USER,
        DB_PASS,
        DB_CHAR
    ));

    $router = \Library\Core\Router::getInstance();
    $router::setProtectedRoutes(array(
        "game/create",
        "critic/create"
    ));
    $router::dispatchPage($_GET['p']);