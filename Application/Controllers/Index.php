<?php

namespace Application\Controllers;

use \Library\Core\Controller;
use \Application\Models\User as ModelUser;
use \Application\Models\Game as ModelGame;

class Index extends Controller {

    private $mu;
    private $mg;

    public function __construct() {
        parent::__construct();
        $this->mu = new ModelUser('localhost');
        $this->mg = new ModelGame('localhost');
    }

    public function indexAction($id_platforms = null) {
        $error = $this->mu->getErrorData($_POST);

        if (!empty($_POST) && empty($error)) {
            $user = $this->mu->findForAuth($this->mu->cleanData($_POST));
            if (!empty($user)) {
                if (password_verify($_POST['password'], $user->password)) {
                    unset($user->password);
                    $_SESSION['user'] = $user;
                    //header("location: ".LINK_WEB);
                    //exit();
                } else {
                    array_push($error, "email or password not valid");
                }
            } else {
                array_push($error, "email or password not valid");
            }
        }

        $platforms = $this->mg->getPlatformList();
        if(is_null($id_platforms) || empty($id_platforms)){
            $id_platforms = $platforms[0]->id;
        }
        $games = $this->mg->fetchAll("id_platforms = $id_platforms");

        $this->setDataView(array(
            "errors" => $error,
            "games" => $games,
            "platforms" => $platforms,
            "id_platforms" => $id_platforms
        ));
    }

}
