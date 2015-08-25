<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Admin extends Controller_Private_Authentication {

    public $template = 'private/admin';
    public $auto_render = TRUE;

    function __construct(Request $request) {
        parent::__construct($request);
        if ($this->getSessionParameter('session_id') != $this->getSessionId()) {
            $this->action_logout();
        }
    }

    public function action_index() {
        if (strcmp($this->isAccessGranted($this->getRequestURI()), "") == 0) {
            $this->redirectAccessDenied();
        }
    }

    public function before() {
        parent::before();

        $actions = $this->getSessionParameter('permited_actions');
        if (empty($actions)) {
            $permited_actions_array = array();
            $actions = $this->createRestrictedMenuArray(NULL, TRUE);
            foreach ($actions as $a) {
                array_push($permited_actions_array, $a->url);
            }
            $this->setSessionParameter('permited_actions', $permited_actions_array);
            //print_r($permited_actions_array);
        }
        $this->template->menu = $this->createRestrictedMenuArray();
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

    /**
     *
     * @param int $idSuperMenu código del menu padre
     * @param boolean $only_menu true si sólo se requiere items de tipo M o false si se requiere además los php actions
     * @param array<GroupPrivilege> $groups
     * @return array<MenuItem> de menú y/o php actions en árbol jerárquico
     */
    public function createMenuArray($idSuperMenu=NULL, $only_menu=true, $groups=NULL) {
        try {

            $menuArray = array();
            if ($only_menu) {
                $menus = DB::select()->from('menu')
                                ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                                ->and_where('menu.type', '=', $this->MENU_TYPE['MENU'])
                                ->and_where('menu.idSuperMenu', '=', $idSuperMenu)
                                ->order_by('menu.name', 'asc')
                                ->execute()->as_array();
            } else {
                $menus = DB::select()->from('menu')
                                ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                                ->and_where('menu.idSuperMenu', '=', $idSuperMenu)
                                ->order_by('menu.type', 'desc')
                                ->order_by('menu.name', 'asc')
                                ->execute()->as_array();
            }
            foreach ($menus as $m) {
                $privilege_array_group = array();
                if ($groups != NULL) {
                    foreach ($groups as $gp) {
                        $privilege = DB::select()->from('privilege')
                                        ->and_where('privilege.idGroup', '=', $gp->idGroup)
                                        ->and_where('privilege.idMenu', '=', $m['idMenu'])->execute()->as_array();
                        array_push($privilege_array_group, new GroupPrivilege($gp, $privilege));
                    }
                }
                array_push($menuArray, new MenuItem($m['name'], $m['url'], $this->createMenuArray($m['idMenu'], $only_menu, $groups), $m['idMenu'], $m['type'], $privilege_array_group));
            }
            return $menuArray;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     *
     * @param int $idSuperMenu
     * @param boolean $all_type si es TRUE recupera Actios y Menús en un árbol jerárquico, si es FALSE sólo Menús
     * @return array de MenuItem para iterar y mostrar el menu del usuario acutal
     */
    private function createRestrictedMenuArray($idSuperMenu=NULL, $all_type=false) {
        try {
            $menuArray = array();
            if ($all_type) {
                $menus = DB::select()->from('menu')
                                ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                                ->and_where('menu.idSuperMenu', 'IS NOT', $idSuperMenu)
                                ->join('privilege')->on('privilege.idMenu', '=', 'menu.idMenu')
                                ->and_where('privilege.idGroup', '=', $this->getSessionParameter('user_group_id'))
                                ->order_by('menu.name', 'asc')
                                ->execute()->as_array();
            } else {
                $menus = DB::select()->from('menu')
                                ->and_where('menu.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                                ->and_where('menu.type', '=', $this->MENU_TYPE['MENU'])
                                ->and_where('menu.idSuperMenu', '=', $idSuperMenu)
                                ->join('privilege')->on('privilege.idMenu', '=', 'menu.idMenu')
                                ->and_where('privilege.idGroup', '=', $this->getSessionParameter('user_group_id'))
                                ->order_by('menu.name', 'asc')
                                ->execute()->as_array();
            }

            foreach ($menus as $m) {
                array_push($menuArray, new MenuItem($m['name'], $m['url'], $this->createRestrictedMenuArray($m['idMenu']), $m['idMenu'], $m['type'], NULL));
            }

            return $menuArray;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function action_listCountries() {
        try {
            $db = DB::select()->from('country')->and_where('idSuperCountry', '=', NULL)->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    

}

?>
