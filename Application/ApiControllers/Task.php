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
        WebService::sendResponse($this->mt->fetchAll());
    }

    public function post($data) {
        $this->mt->insert($data);
        WebService::sendResponse($this->mt->fetchAll());
    }

    public function put($data) {
        $this->mt->updateByPrimary($data);
        WebService::sendResponse($this->mt->fetchAll());
    }

    public function delete($data) {
        $this->mt->deleteByPrimary($data);
        WebService::sendResponse($this->mt->fetchAll());
    }

}
