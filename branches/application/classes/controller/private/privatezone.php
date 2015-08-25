<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Privatezone extends Controller_Template {

    public $template = 'private/private_zone';
    public $auto_render = TRUE;
    public $GENERAL_STATUS = array('ACTIVE' => 1, 'DEACTIVE' => 0);
    public $MENU_TYPE = array('MENU' => 'M', 'ACTION' => 'A');
    public $USER_TYPE = array('EMPLOYEE' => '1', 'SUPPLIER' => '2', 'CLIENT' => '3');
    

    function __construct(Request $request) {
        parent::__construct($request);
    }

    public function before() {
        parent::before();
        if ($this->auto_render) {
            $this->template->title = 'Zeratul Inventory';
            $this->template->meta_keywords = '';
            $this->template->meta_description = '';
            $this->template->meta_copyright = '';
            //$this->template->header = '';
            $this->template->content = '';
            $this->template->username = '';
            //$this->template->footer = '';
            $this->template->styles = array();
            $this->template->scripts = array();
            //$this->template->library = array();
            //$this->template->message = $this->message;
        }
    }

    public function after() {
//        if ($this->auto_render) {
//            $styles = array();
//            $scripts = array();
//            $this->template->styles = array_reverse(array_merge($this->template->styles, $styles));
//            $this->template->scripts = array_reverse(array_merge($this->template->scripts, $scripts));
//        }
        parent::after();
    }

    public function getRequestURI(){
        return $_SERVER['REQUEST_URI'];
    }

//    public function getMenuType() {
//        return $eFruits = new Enum("APPLE", "ORANGE", "PEACH");
//    }
}

?>
