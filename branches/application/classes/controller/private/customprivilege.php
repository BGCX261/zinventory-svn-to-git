<?php

class Controller_Private_Customprivilege extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $custom_privilege_view = new View('private/privilege');
        $custom_privilege = ORM::factory("Customprivilege")->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $custom_privilege_view->groups = $custom_privilege;
        $custom_privilege_view->privilegeRows = $this->createMenuArray(NULL, false, $custom_privilege);
        $this->template->content = $custom_privilege_view;
    }
}
?>
