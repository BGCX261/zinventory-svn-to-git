<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Privilege extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $privilege_view = new View('private/privilege');
        $groups = ORM::factory("Group")->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $privilege_view->groups = $groups;
        $privilege_view->privilegeRows = $this->createMenuArray(NULL, false, $groups);
        $this->template->content = $privilege_view;
    }

    public function action_createOrDeleteAccess() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('menu_id', 'not_empty')
                            ->rule('group_id', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR - Empty Data Post";
                die();
            }
            $menu_id = $_POST['menu_id'];
            $group_id = $_POST['group_id'];
            $privilege = new Model_Privilege();
            $privilege = ORM::factory('Privilege')
                            ->where('idMenu', '=', $menu_id)
                            ->where('idGroup', '=', $group_id)->find();
            if ($privilege->loaded() == TRUE) {
                $privilege->delete();
            } else {
                $privilege->idMenu = $menu_id;
                $privilege->idGroup = $group_id;
                $privilege->grantDate = Date::formatted_time();
                $privilege->idUser = $this->getSessionParameter('user_id');
                $privilege->save();
            }
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

}

?>
