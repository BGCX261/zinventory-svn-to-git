<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Authentication extends Controller_Private_Privatezone {

    public $controller = 'private_zone';
//    public function action_index(){
//        $this->template->content = new View('private/index');
//    }

    public function action_login() {
        if(empty($_POST['usernamePost']) || empty($_POST['usernamePost'])){
            $this->action_logout();
        }
        $user = ORM::factory('User');
        $post = Validate::factory($_POST)
                        ->rule('usernamePost', 'not_empty')
                        ->rule('passwordPost', 'not_empty');
        if ($post->check()) {
            $user = $user->where('userName', '=', $_POST['usernamePost'])
                            ->where('password', '=', $_POST['passwordPost'])
                            ->find();
            if ($user->loaded()) {
                Session::instance('database');
                $this->setSessionParameter('session_id', Session::instance('database')->id());
                $this->setSessionParameter('user_name', $user->userName);
                $this->setSessionParameter('user_id', $user->idUser);
                $this->setSessionParameter('user_group_id', $user->group->idGroup);
                $this->setSessionParameter('permited_actions',array());

//                if ($user->idGroup == 1)
                echo "1|/private/index/index|Ok";
//                if ($user->idGroup == 2)
//                    echo "1|/vendedor/index";
                die();
            } else {
                echo "0|0|" . __("Usuario o contraseña no válidos");
                die();
            }
        }
    }

    public function action_logout() {
        Session::instance('database')->destroy();
        Request::instance()->redirect('public/login/index');
        //$this->request->redirect('public/login/index');
    }

    public function getSessionParameter($parameterId=NULL) {
        return Session::instance('database')->get("'" . $parameterId . "'");
    }

    public function setSessionParameter($parameterId=NULL, $parameterValue=NULL) {
        Session::instance('database')->set("'" . $parameterId . "'", $parameterValue);
    }

    public function getSessionId() {
        return Session::instance('database')->id();
    }

    public function isAccessGranted(){
        $permited_actions = $this->getSessionParameter('permited_actions');
        return array_search($this->getRequestURI(), $permited_actions);
    }
    public function after() {
        parent::after();
        $this->template->username = $this->getSessionParameter('user_name');
    }

    public function redirectAccessDenied(){
        //$this->request->redirect('public/accessdenied/noAccess');
        Request::instance()->redirect('public/accessdenied/noAccess');
    }

}

?>
