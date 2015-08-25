<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Public_Welcome extends Controller_Template {

    public $template = "example";
    public $controller = "example";

    public function action_index() {
        //$this->request->response = __("Hola Mundo");
        $this->template->content = new View('/public/welcome');
        I18n::lang($this->controller);
    }

    public function action_cambiarIdiomaEs() {

        $es = new LoadLanguage();
        //$controllers = array("users", "groups", "category", "subcategory", "list", "formconfig", "product");
        $controllers = array("example");
        $es->loadData($controllers, 'es', 1, 1);
        
        //---------------------------------------------------
        echo "1";
        die();
    }

    public function action_cambiarIdiomaEn() {
        //carga de el idioma xD ------------------------------
        $en = new LoadLanguage();
        $controladores = array("example");
        $en->loadData($controladores, 'es', 0, 1);
        
        //---------------------------------------------------
        echo "1";
        die();
    }

}

// End Welcome
