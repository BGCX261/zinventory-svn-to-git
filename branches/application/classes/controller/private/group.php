<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Group extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $group_view = new View('private/group');
        $group = new Model_Group();
        $group = $group->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $group_view->group_list = $group;
        $this->template->content = $group_view;
    }

    public function action_createOrUpdateGroup() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('group_name', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR";
                die();
            }
            $group_name = $_POST['group_name'];
            $group_id = $_POST['group_id'];
            $group = new Model_Group();
            if ($group_id != 0)
                $group = ORM::factory('Group', $group_id);
            $group->name = trim($group_name);
            $group->status = $this->GENERAL_STATUS['ACTIVE'];
            $group->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_listGroups() {
        try {
            $db = DB::select()->from('group')
                            ->and_where('group.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->order_by('group.name', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_removeGroup() {
        try {
            $group_id = $_POST['group_id'];
            $group = ORM::factory('Group', $group_id);
            $group->status = $this->GENERAL_STATUS['DEACTIVE'];
            $group->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_findGroupById() {
        try {
            $group_id = $_POST['group_id'];
            $group = ORM::factory('Group', $group_id)->as_array();
            echo json_encode($group);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
