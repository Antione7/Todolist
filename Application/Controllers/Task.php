<?php

namespace Application\Controllers;

use \Library\Core\Controller;
use \Application\Models\Task as ModelTask;

class Task extends Controller {

    private $mt;

    public function __construct() {
        parent::__construct();
        $this->mt = new ModelTask('localhost');
    }

    public function indexAction() {
    }

}
