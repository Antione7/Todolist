<?php

namespace Application\ApiControllers;

use \Application\Models\Task as ModelTask;
use Library\Core\WebService;

class Task {

    private $mt;

    public function __construct() {
        $this->mt = new ModelTask('localhost');
    }

    public function get() {
        WebService::sendResponse($this->mt->fetchAll('id_users = '.$_SESSION['user']->id));
    }

    public function post($data) {
        $data['id_users']=$_SESSION['user']->id;
        $error = $this->mt->getErrorData($data);

        if(!empty($data) && empty($error)){
            $this->mt->insert($this->mt->cleanData($data));
            WebService::sendResponse($this->mt->fetchAll('id_users = '.$_SESSION['user']->id));
        } else {
            WebService::sendResponse(["error" => "there is an error, you should try again later"]);
        }
    }

    public function put($data) {
        $error = $this->mt->getErrorData($data);

        if(!empty($data) && empty($error)){
            $this->mt->updateByPrimary($this->mt->cleanData($data));
            WebService::sendResponse($this->mt->fetchAll('id_users = '.$_SESSION['user']->id));
        } else {
            WebService::sendResponse(["error" => "there is an error, you should try again later"]);
        }
    }

    public function delete($data) {
        $error = $this->mt->getErrorData($data);

        if(!empty($data) && empty($error)){
            $this->mt->deleteByPrimary($data);
            WebService::sendResponse($this->mt->fetchAll('id_users = '.$_SESSION['user']->id));
        } else {
            WebService::sendResponse(["error" => "there is an error, you should try again later"]);
        }
    }
}
