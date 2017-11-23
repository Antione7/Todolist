<?php

namespace Library\Core;

abstract class GeneriqueControl {

    private $files          = array();
    private $nameReserved   = array(
        "helpers",
        "pathView"
    );

    public function __construct() {

    }

    protected function getNameReserved(){
        return $this->nameReserved;
    }

    protected function setFile($type, $filename){
        $type = strtolower($type);

        if($type === "script" || $type === "style"){
            if(!array_key_exists($type, $this->files)){
                $this->files[$type] = array();
            }
            $this->files[$type][] = $filename;
            return true;
        }

        return false;
    }


    protected function addFilesRender(&$html){

        if(array_key_exists("script", $this->files)) {
            foreach ($this->files["script"] as $s) {
                $html = str_replace("</body>", "<script src='" . DATA_WEB . "js/{$s}.js'></script></body>", $html);
            }
        }
        if(array_key_exists("style", $this->files)) {
            foreach ($this->files["style"] as $s) {
                $html = str_replace("</head>", "<link href='" . DATA_WEB . "css/{$s}.css' rel='stylesheet'/></head>", $html);
            }
        }
    }
}