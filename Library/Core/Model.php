<?php

namespace Library\Core;

abstract class Model {

    protected $database;
    protected $table;
    protected $primary;
    protected $structure;

    public function getPrimary() {
        return $this->primary;
    }

    public function __construct($connexionName) {
        $classConnexion = Connexion::getInstance();
        $this->database = $classConnexion::getConnexion($connexionName);
    }

    public function cleanData($data) {
        $tmpData = $data;

        foreach ($data as $key => $value) {
            if (in_array(key,$this->primary)) {
                continue;
            }
            if (!array_key_exists($key, $this->structure)) {
                unset($tmpData[$key]);
            } else {
                if (!isset($this->structure[$key]['ignoreEncode']) || $this->structure[$key]['ignoreEncode'] === false) {
                    $tmpData[$key] = htmlentities($tmpData[$key], ENT_QUOTES);
                }
            }
        }

        return $tmpData;
    }

    public function getErrorData($data) {

        $error = array();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->structure)) {
                foreach ($this->structure[$key] as $rule => $info) {
                    if ($rule === 'type' && $info === 'string' && !is_string($value)) {
                        array_push($error, "Bad String type field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'type' && $info === 'int' && !is_numeric($value)) {
                        array_push($error, "Bad Int type field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'minLength' && strlen($value) < $info) {
                        array_push($error, "Minimal length is: '<strong>$info</strong>' in field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'maxLength' && strlen($value) > $info) {
                        array_push($error, "Maximum length is: '<strong>$info</strong>' in field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'min' && $value < $info) {
                        array_push($error, "Minimal value is: '<strong>$info</strong>' in field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'max' && $value > $info) {
                        array_push($error, "Maximum value is: '<strong>$info</strong>' in field: '<strong>$key</strong>'");
                    }
                    if ($rule === 'type' && $info === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        array_push($error, "Email is not valid");
                    }
                }
            }
        }

        return $error;
    }

    public function fetchAll($where = 1, $fields = '*') {
        $sql = $this->database->prepare("SELECT {$fields} FROM `{$this->table}` WHERE {$where}");
        $sql->execute();
        return $sql->fetchAll();
    }

    public function findByPrimary($primaryList, $fields = '*') {
        $query = "SELECT {$fields} FROM `{$this->table}` WHERE ";

        $c = count($this->primary);
        for($i = 0; $i < $c; $i++){
            if(!array_key_exists($this->primary[$i],$primaryList)){
                continue;
            }
            if($i > 0){
                $query .= ' AND ';
            }

            $query .= "`{$this->primary[$i]}`=:{$this->primary[$i]}";
        }
        $sql = $this->database->prepare($query);
        $sql->execute($primaryList);
        return $sql->fetchAll();
    }

    /**
     * array(
     *      'field1' => 'field1Value'
     *      'field2' => 'field1Value'
     *      'field3' => 'field1Value'
     * )
     */
    public function insert(Array $data): int {

        // `field1`,`field2`,`field3`
        $listFields = "`" . implode('`,`', array_keys($data)) . "`";

        // :field1,:field2,:field3
        $listValues = ":" . implode(',:', array_keys($data));

        $sql = $this->database->prepare("INSERT INTO `{$this->table}` ({$listFields}) VALUES ({$listValues})");
        $result = $sql->execute($data);
        if ($result && $this->primary === 'id') {
            return $this->database->lastInsertId();
        } else if($result){
            return 1;
        }
        return 0;
    }

    public function updateByPrimary(Array $data) {
        // `field1`=:field1,`field2`=:field2,`field3`=:field3,
        $listFieldsValues = "";
        foreach ($data as $key => $value) {
            if (!in_array($key,$this->primary)) {
                $listFieldsValues .= "`$key`=:$key,";
            }
        }
        $listFieldsValues = substr($listFieldsValues, 0, -1);
        $query = "UPDATE `{$this->table}` SET {$listFieldsValues} WHERE ";
        $c = count($this->primary);
        for($i = 0; $i < $c; $i++){
            if(!array_key_exists($this->primary[$i],$primaryList)){
                continue;
            }
            if($i > 0){
                $query .= ' AND ';
            }

            $query .= "`{$this->primary[$i]}`=:{$this->primary[$i]}";
        }
        $sql = $this->database->prepare($query);
        return $sql->execute($data);
    }

    public function deleteByPrimary($primaryList) {
        $query = "DELETE FROM `{$this->table}` WHERE ";
        $c = count($this->primary);
        for($i = 0; $i < $c; $i++){
            if(!array_key_exists($this->primary[$i],$primaryList)){
                continue;
            }
            if($i > 0){
                $query .= ' AND ';
            }

            $query .= "`{$this->primary[$i]}`=:{$this->primary[$i]}";
        }
        
        $sql = $this->database->prepare($query);
        return $sql->execute($primaryList);
    }

}
