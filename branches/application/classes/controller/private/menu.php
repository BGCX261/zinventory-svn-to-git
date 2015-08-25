<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Menu extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $menu_view = new View('private/menu');
        $menu = new Model_Menu();
        $menu = $menu->where('status', '=', 1)->where('idSuperMenu', '=', null)->find_all();
        $menu_view->menu_list = $menu;
        $this->template->content = $menu_view;
    }

    public function action_createOrUpdateMenu() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('menu_name', 'not_empty')
                            ->rule('menu_url', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR - Empty Data Post";
                die();
            }
            $menu_name = $_POST['menu_name'];
            $menu_url = $_POST['menu_url'];
            $super_menu_id = $_POST['idSuperMenu'];
            $menu_id = $_POST['idMenu'];
            $menu = new Model_Menu();
            if ($menu_id != 0)
                $menu = ORM::factory('Menu', $menu_id);
            $menu->name = trim($menu_name);
            $menu->url = trim($menu_url);
            if ($_POST['menu_type'] == $this->MENU_TYPE['MENU']) {
                $menu->type = $this->MENU_TYPE['MENU'];
            } else {
                $menu->type = $this->MENU_TYPE['ACTION'];
            }
            if ($super_menu_id != 0) {
                $menu->idSuperMenu = $super_menu_id;
            }
            $menu->status = $this->GENERAL_STATUS['ACTIVE'];
            $menu->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_listMenus() {
        try {
            $db = DB::select()->from('menu')
                            ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->and_where('menu.idSuperMenu', '=', NULL)
                            ->order_by('menu.name', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    private function searchSubMenu($superMenu_id = NULL) {
        try {
            $subMenus = new Model_Menu();
            $subMenus = $subMenus->and_where('menu.idSuperMenu', '=', $superMenu_id)->find_all();
            $ids_subMenus = "";
            if (count($subMenus) > 0) {
                foreach ($subMenus as $subMenus) {
                    $ids_subMenus .= $subMenus;
                    $ids_subMenus .=";";
                }
                return $ids_subMenus;
            } else {
                return $ids_subMenus;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function removeSubMenu($idSuperMenu = NULL) {
        $ids = $this->searchSubMenu($idSuperMenu);
        if (!(empty($ids))) {
            $subIds = explode(";", $ids);
            foreach ($subIds as $subId) {
                if (!empty($subId)) {
                    $this->removeSubMenu($subId);
                    DB::update('Menu')->set(array('status' => $this->GENERAL_STATUS['DEACTIVE']))->and_where('idMenu', '=', $subId)->execute();
                }
            }
        }
        return "";
    }

    public function action_removeMenu() {
        try {
            $menu_id = $_POST['idMenu'];
            $menu = ORM::factory('Menu', $menu_id);
            $menu->status = $this->GENERAL_STATUS['DEACTIVE'];
            $this->removeSubMenu($menu_id);
            $menu->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_listSubMenu() {
        try {
            $superMenu_id = $_POST['idMenu'];
            $menus = DB::select()
                            ->from('Menu')
                            ->and_where('menu.idSuperMenu', '=', $superMenu_id)
                            ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->order_by('menu.name', 'desc')
                            ->order_by('menu.type', 'desc')
                            ->execute()->as_array();
            echo json_encode($menus);
            die();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function action_isSuperMenu() {
        $menu_id = $_POST['idMenu'];
        $childs = ORM::factory('Menu')
                        ->where('idSuperMenu', '=', $menu_id)
                        ->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->count_all();
        if ($childs > 0) {
            echo "1";
        }
        die();
    }

    public function action_findMenuById() {
        try {
            $menu_id = $_POST['idMenu'];
            $menu = ORM::factory('Menu', $menu_id)->as_array();
            echo json_encode($menu);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
