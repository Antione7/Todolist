<?php

namespace Application\ApiControllers;

use \Library\Core\Controller;


class Error {

    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
        http_response_code(404);
    }

    public function forbiddenAction(){
        http_response_code(403);
    }
}