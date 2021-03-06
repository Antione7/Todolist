<?php

namespace Application\Models;

use \Library\Core\Model;
use \PDO;

class Task extends Model {

    protected $table = 'task';
    protected $primary = ['id'];
    protected $structure = array(
        "libelle" => array(
            "type" => "string",
        ),
        "id_users" => array(
            "type" => "int"
        )
    );

    public function __construct($connexionName) {
        parent::__construct($connexionName);
    }

}
