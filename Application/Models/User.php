<?php

namespace Application\Models;

use \Library\Core\Model;
use \PDO;

class User extends Model {

    protected $table = 'users';
    protected $primary = ['id'];
    protected $structure = array(
        "email" => array(
            "type" => "email"
        ),
        "password" => array(
            "type" => "string",
            "minLength" => "5",
            "maxLength" => "15"
        ),
        "pseudo" => array(
            "type" => "string",
            "minLength" => "3",
            "maxLength" => "15"
        ),
        "lastName" => array(
            "type" => "string"
        ),
        "firstName" => array(
            "type" => "string"
        )
    );

    public function __construct($connexionName) {
        parent::__construct($connexionName);
    }

    public function findForAuth($data) {
        $sql = $this->database->prepare("SELECT * FROM `{$this->table}` WHERE `email`=:email");
        $sql->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetchAll();

        if (count($result) === 1) {
            return $result[0];
        }

        return false;
    }
    
    public function attributeRole(int $id): bool{
        $sql = $this->database->prepare("INSERT INTO roleattribution (`id_users`,`id_roles`) VALUES (:id,1)");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        return $sql->execute();
    }
}
