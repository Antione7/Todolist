<?php

namespace Application\Controllers;

use \Library\Core\Controller;
use \Application\Models\User as ModelUser;

class Index extends Controller {

    private $mu;
    private $mg;

    public function __construct() {
        parent::__construct();
        $this->mu = new ModelUser('localhost');
    }

    public function indexAction($id_platforms = null) {
        $error = $this->mu->getErrorData($_POST);

        if (!empty($_POST) && empty($error)) {
            $user = $this->mu->findForAuth($this->mu->cleanData($_POST));
            if (!empty($user)) {
                if (password_verify($_POST['password'], $user->password)) {
                    unset($user->password);
                    $_SESSION['user'] = $user;
                } else {
                    array_push($error, "email or password not valid");
                }
            } else {
                array_push($error, "email or password not valid");
            }
        }

        $this->setDataView(array(
            "errors" => $error
        ));
    }

}
