<?php

namespace Application\Models;

use \Library\Core\Model;
use \PDO;

class Critic extends Model {

    protected $table = 'critics';
    protected $primary = [ 'id_users', 'id_games', 'id_criterias' ];
    protected $structure = array(
        "rating" => array(
            "type" => "int",
            "max" => 5,
            "min" => 0
        ),
        "note" => array(
            "type" => "string"
        )
    );

    public function __construct($connexionName) {
        parent::__construct($connexionName);
    }

    public function getCriteriaList() {
        $sql = $this->database->prepare("SELECT id, name FROM criterias");
        $sql->execute();

        return $sql->fetchAll();
    }
    
    public function getCriticList($id_games, $id_criterias){
        $sql = $this->database->prepare("SELECT rating, note, id_users, pseudo FROM critics AS c "
                . "LEFT JOIN users AS u ON c.id_users = u.id "
                . "WHERE id_games = :id_games AND id_criterias = :id_criterias");
        
        $sql->execute(array(
            "id_games" => $id_games,
            "id_criterias" => $id_criterias
        ));

        return $sql->fetchAll();
    }

    public function getCritic($id_games, $id_users){
        $sql = $this->database->prepare("SELECT rating, note, cr.name AS criteria, cr.id AS id_criterias FROM critics AS c "
                . "LEFT JOIN criterias AS cr ON c.id_criterias = cr.id "
                . "WHERE id_games = :id_games AND id_users = :id_users");
        
        $sql->execute(array(
            "id_games" => $id_games,
            "id_users" => $id_users
        ));

        return $sql->fetchAll();
    }
}
