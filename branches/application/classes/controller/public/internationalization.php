<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Public_Internationalization extends Controller_Private_Privatezone {

    public function action_change2Es() {

        $es = new LoadLanguage();
        //$controllers = array("users", "groups", "category", "subcategory", "list", "formconfig", "product");
        $controllers = array("privatezone");
        $es->loadData($controllers, 'es', 1, 1);

        //---------------------------------------------------
        echo "1";
        die();
    }

    public function action_change2En() {
        //carga de el idioma xD ------------------------------
        $en = new LoadLanguage();
        $controladores = array("privatezone");
        $en->loadData($controladores, 'es', 0, 1);

        //---------------------------------------------------
        echo "1";
        die();
    }

}

?>
