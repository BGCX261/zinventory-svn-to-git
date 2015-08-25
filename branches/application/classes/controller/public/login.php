<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Public_Login extends Controller_Private_Privatezone {

    public function action_index() {
        $this->template->content = new View('/public/login');
    }

}

?>
