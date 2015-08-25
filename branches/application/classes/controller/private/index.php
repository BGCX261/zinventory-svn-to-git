<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Index extends Controller_Private_Admin {

    function __construct(Request $request) {
        parent::__construct($request);
    }

    public function action_index() {
        $this->template->content = new View('private/index');
    }

    public function action_aboutUs() {
        $v = new View('public/about');
        $this->template->content = $v;
        $this->template->content->hola = "Hola";
    }

    public function action_userInfo() {
        try {
            $id = $this->getSessionParameter('user_id');
            $user = ORM::factory('User', $id);
            $array = array(
                'user_fName' => $user->person->fName,
                'user_lName' => $user->person->lName,
                'user_name_info' => $user->userName,
                'user_email' => $user->person->email,
                'user_group_name' => $user->group->name);
            echo json_encode($array);
            die();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

?>
