<?php

namespace Application\Models;

use \Library\Core\Model;
use \PDO;

class Game extends Model {

    protected $table = 'games';
    protected $primary = ['id'];
    protected $structure = array(
        "name" => array(
            "type" => "string"
        ),
        "id_platforms" => array(
            "type" => "int"
        )
    );

    public function __construct($connexionName) {
        parent::__construct($connexionName);
    }

    public function attributeGenre(int $id_games, array $genres): bool {
        $result;
        foreach ($genres as $genre) {
            $sql = $this->database->prepare("INSERT INTO genreattribution (`id_genre`,`id_games`) VALUES (:id_genre,:id_games)");
            $sql->bindParam(':id_genre', $genre, PDO::PARAM_INT);
            $sql->bindParam(':id_games', $id_games, PDO::PARAM_INT);
            $result = $sql->execute();
            if (!$result) {
                return false;
            }
        }
        return $result;
    }

    public function getGenreList() {
        $sql = $this->database->prepare("SELECT id, name FROM genre");
        $sql->execute();

        return $sql->fetchAll();
    }

    public function getPlatformList() {
        $sql = $this->database->prepare("SELECT id, name FROM platforms");
        $sql->execute();

        return $sql->fetchAll();
    }

    public function getCriticsByUserId($id_users, $id_platforms) {
        $sql = $this->database->prepare("SELECT id_games, g.name AS name FROM critics AS c
LEFT JOIN games AS g ON g.id = c.id_games
WHERE id_users = :id_users AND id_platforms = :id_platforms
GROUP BY id_games");

        $sql->execute(array(
            "id_users" => $id_users,
            "id_platforms" => $id_platforms
        ));

        return $sql->fetchAll();
    }

}
