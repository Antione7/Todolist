<?php

namespace Library\Template;

class Helpers {

    public function foundInputValue($name, $src=null, $i=null){
        if(!is_null($src) && empty($_POST)){
            return isset($src->$name)?$src->$name:'';
        }
        if(!is_null($i)){
            return isset($_POST[$name][$i])?$_POST[$name][$i]:'';
        }
        return isset($_POST[$name])?$_POST[$name]:'';
    }

    public function isAuthenticated(){
        return isset($_SESSION['user']);
    }

}


