<?php

namespace Library\Core;

use Library\Template\Helpers;

abstract class Controller extends GeneriqueControl {

    private $layout         = "default";
    private $responseHeader = "text/html";
    private $dataView       = array();

    public function __construct() {
        parent::__construct();
    }

    protected function setLayout($name) {
        $pathLayout = APP_ROOT . "Views\Layouts\{$name}.phtml";
        $pathLayout= str_replace("\\",DIRECTORY_SEPARATOR,$pathLayout);
        $pathLayout= str_replace("/",DIRECTORY_SEPARATOR,$pathLayout);
        if(!empty($name) && file_exists($pathLayout)){
            $this->layout = $name;
        }
    }

    protected function getLayoutPath(){
        $pathLayout = APP_ROOT . "Views\Layouts\\" . $this->layout . ".phtml";
        $pathLayout= str_replace("\\",DIRECTORY_SEPARATOR,$pathLayout);
        $pathLayout= str_replace("/",DIRECTORY_SEPARATOR,$pathLayout);
        return $pathLayout;
    }


    protected function setResponseHeader($value) {
        $value = strtolower($value);
        $possibilities = array(
            "text" => "text/plain",
            "html" => "text/html",
            "json" => "application/json",
            "xml"  => "application/xml",
        );
        if (array_key_exists($value, $possibilities)){
            $this->responseHeader = $possibilities[$value];
            return true;
        }
        return false;
    }

    protected function getResponseHeader() {
        return $this->responseHeader;
    }


    protected function setDataView(Array $data){
        foreach ($this->getNameReserved() as $value) {
            if(array_key_exists($value, $data)){
                throw new \Exception("Variable name: '$value' is reserved by system");
            }
        }
        $this->dataView = array_merge($this->dataView, $data);
    }


    public function renderView($controllerName, $actionName) {

        header("Content-type: " . $this->getResponseHeader() . "; charset=utf-8");

        $pathView   = APP_ROOT . str_replace('\Application', 'Views', $controllerName) . DS . str_replace('Action', '', $actionName) . '.phtml';
        $pathView= str_replace("\\",DIRECTORY_SEPARATOR,$pathView);
        $pathView= str_replace("/",DIRECTORY_SEPARATOR,$pathView);
        $helpers    = new Helpers();

        ob_start();
            if(file_exists($pathView)){
                extract($this->dataView);
                include($pathView);
            }
        $viewContent = ob_get_clean();

        ob_start();
            include($this->getLayoutPath());
        $finalRender = ob_get_clean();

        $this->addFilesRender($finalRender);
        echo $finalRender;
    }
}