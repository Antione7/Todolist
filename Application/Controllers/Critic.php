<?php

namespace Application\Controllers;

use \Library\Core\Controller;
use \Application\Models\Critic as ModelCritic;
use \Application\Models\Game as ModelGame;

class Critic extends Controller {

    private $mc;

    public function __construct() {
        parent::__construct();
        $this->mc = new ModelCritic('localhost');
        $this->mg = new ModelGame('localhost');
    }
    
    public function indexAction($id_games = null, $id_criterias = null){
        if (is_null($id_games) || empty($id_games)) {
            header("location: " . LINK_WEB);
            exit();
        }
        
        $criterias = $this->mc->getCriteriaList();
        if(is_null($id_criterias) || empty($id_criterias)){
            $id_criterias = $criterias[0]->id;
        }
        $critics = $this->mc->getCriticList($id_games, $id_criterias);
        
        $this->setDataView(array(
            "criterias" => $criterias,
            "critics" => $critics,
            "param1" => $id_games,
            "param2" => $id_criterias
        ));
    }

    public function createAction($id = null) {
        if (is_null($id) || empty($id)) {
            header("location: " . LINK_WEB);
            exit();
        }
        $error = array();

        if (!empty($_POST)) {
            $id_users = $_SESSION['user']->id;
            $c = count($_POST['criteria']);
            $result = false;
            for ($i = 0; $i < $c; $i++) {
                $data['rating'] = intval($_POST['rating'][$i]);
                $data['note'] = $_POST['note'][$i];
                $data['id_users'] = $id_users;
                $data['id_games'] = intval($id);
                $data['id_criterias'] = intval($_POST['criteria'][$i]);
                $error = $this->mc->getErrorData($data);

                if (empty($error)) {
                    if ($this->mc->insert($data) === 1) {
                        $result = true;
                    } else {
                        array_push($error, "An error occurs");
                    }
                }
            }

            if ($result) {
                header("location: " . LINK_WEB);
                exit();
            }
        }

        $criterias = $this->mc->getCriteriaList();

        $this->setDataView(array(
            "errors" => $error,
            "criterias" => $criterias
        ));
    }

    public function readAction(int $id) {
        
    }

    public function updateAction(int $id_games) {
        if (is_null($id_games) || empty($id_games)) {
            header("location: " . LINK_WEB);
            exit();
        }

        $error = array();
        $id_users = $_SESSION['user']->id;
        
        if (!empty($_POST)) {
            $c = count($_POST['criteria']);
            $result = false;
            for ($i = 0; $i < $c; $i++) {
                $data['rating'] = intval($_POST['rating'][$i]);
                $data['note'] = $_POST['note'][$i];
                $data['id_users'] = $id_users;
                $data['id_games'] = intval($id_games);
                $data['id_criterias'] = intval($_POST['criteria'][$i]);
                $error = $this->mc->getErrorData($data);

                if (empty($error)) {
                    if ($this->mc->updateByPrimary($data)) {
                        $result = true;
                    } else {
                        array_push($error, "An error occurs");
                    }
                }
            }

            if ($result) {
                header("location: " . LINK_WEB);
                exit();
            }
        }

        $critics = $this->mc->getCritic($id_games,$id_users);

        $this->setDataView(array(
            "errors" => $error,
            "critics" => $critics
        ));
    }

    public function deleteAction(int $id_games) {
        if (is_null($id_games) || empty($id_games)) {
            header("location: " . LINK_WEB);
            exit();
        }

        $error = array();
        $id_users = $_SESSION['user']->id;

        if(!empty($_POST) && array_key_exists('confirm',$_POST)){
            if($this->mc->deleteByPrimary([
                'id_users' => $id_users,
                'id_games' => $id_games
            ])){
                header("location: " . LINK_WEB . "game/index/" . $id_users);
                exit();
            } else {
                array_push($error, "An error occurs");
            }
        }

        $game = $this->mg->findByPrimary([
            'id' => $id_games
        ]);
        $this->setDataView(array(
            "errors" => $error,
            "game" => $game[0]
        ));
    }

}
