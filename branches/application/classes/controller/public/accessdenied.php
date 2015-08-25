<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Public_Accessdenied extends Controller_Private_Admin {

    public function action_noAccess(){
        $access_denied_view = new View('/public/access_denied');
        $this->template->content = $access_denied_view;
    }
}

?>
