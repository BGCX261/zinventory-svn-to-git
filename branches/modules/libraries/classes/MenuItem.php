<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuItem
 * Es una clase que apoya para mostrar y filtrar las opciones de Menú al usuario.
 * También se usa para visualizar el conjunto de privilegios del sistema
 *
 * Los nombres de cada MenuItem se deben ingresar en la base de datos en la tabla translate para que sean
 * traducidos por el sistema. Se puede anidar sub menus de forma infinita
 * @author Emmanuel
 */
class MenuItem {

    public $name;
    public $url;
    public $id;
    public $type;
    public $items = array();
    public $groups;
    private $submenuIni = "<ul class=\"sub_menu\">";
    private $submenuEnd = "</ul>";

    function __construct($name, $url, $items, $id=NULL, $type=NULL, $groups=NULL) {
        $this->name = $name;
        $this->url = $url;
        $this->items = $items;
        $this->id = $id;
        $this->type = $type;
        $this->groups = $groups;
    }

    /**
     *
     * @param MenuItem $objMI
     * @return String conjuntos de "<ul><li></li></ul>" para el menú desplegable.
     */
    public function printMenu($objMI) {
        $menuItem = "<li><a rel=\"url\" href=\"url\">name</a>submenu</li>";
        $out = $objMI->submenuIni;
        if (empty($objMI->items)) {
            $out = $out . str_ireplace("name", __($objMI->name), str_replace("url", $objMI->$url, str_replace("submenu", "", $menuItem)));
        } else {
            foreach ($objMI->items as $mitem) {
                if (empty($mitem->items)) {
                    $out = $out . str_ireplace("name", __($mitem->name), str_replace("url", $mitem->url, str_replace("submenu", "", $menuItem)));
                } else {
                    $out = $out . str_ireplace("name", __($mitem->name), str_replace("url", $mitem->url, str_replace("submenu", $mitem->printMenu($mitem), $menuItem)));
                }
            }
        }
        return $out . $objMI->submenuEnd;
    }

    /**
     *
     * @param MenuItem $menuItemRow
     * @param int $margin_left
     * @param array<GroupPrivilege> $groups
     * @return string la fila de la tabla tblPrivileges (view/private/privilege)
     */
    public function printMenuItemRow($menuItemRow, $margin_left=0, $groups=NULL) {
        $html_row_template = "<tr id=\"menu_id\"><td><div class=\"class_name\" style=\"margin-left: margin_left_valuepx;\"></div>menu_name</td><td>checkbox_group</td></tr>new_row";
        $has_child = (!empty($menuItemRow->items));
        if ($has_child) {
            $class_name = "close_node";
        } else {
            $class_name = $this->getNodeClassName($menuItemRow->type);
        }
        $html_row = "";
        $html_row = $html_row . str_ireplace("menu_id", $menuItemRow->id, str_ireplace("class_name", $class_name, str_ireplace("margin_left_value", $margin_left, str_ireplace("menu_name", __($menuItemRow->name), str_ireplace("new_row", "", str_ireplace("checkbox_group", $this->getCheckBoxRow($menuItemRow->groups, $menuItemRow), $html_row_template))))));
        if ($has_child) {
            $margin_left = $margin_left + 25;
            foreach ($menuItemRow->items as $row) {
                if (empty($row->items)) {
                    $class_name = $this->getNodeClassName($row->type);
                    $html_row = $html_row . str_ireplace("menu_id", $row->id, str_ireplace("class_name", $class_name, str_ireplace("margin_left_value", $margin_left, str_ireplace("menu_name", __($row->name), str_ireplace("new_row", "", str_ireplace("checkbox_group", $this->getCheckBoxRow($row->groups, $row), $html_row_template))))));
                } else {
                    $class_name = "close_node";
                    $html_row = $html_row . $row->printMenuItemRow($row, $margin_left, $row->groups);
                }
            }
        }
        return $html_row;
    }

    /**
     *
     * @param String $type el tipo de nodo del menú M o A
     * @return String La clase css que enmascara un nodo terminal del menú o una acción PHP
     */
    private function getNodeClassName($type) {
        if ($type == 'M') {
            return "disable_node";
        } else {
            return "php_action_node";
        }
    }

    /**
     *
     * @param array<GroupPrivilege> $groups
     * @param MenuItem $menuItemRow
     * @return string conjunto de links que emulan checkboxes
     */
    private function getCheckBoxRow($groups, $menuItemRow) {
        $out = "";
        if ($groups != NULL) {
            foreach ($groups as $gp) {
                $name = 0;
                $privilege_id = 0;

                if (!empty($gp->privilege)) {
                    if ($gp->privilege[0]['idMenu'] == $menuItemRow->id && $gp->privilege[0]['idGroup'] == $gp->group->idGroup)
                        $name = 1;
                    $privilege_id = $gp->privilege[0]['idPrivilege'];
                }
                $checkbox_template = "<label class=\"label_privilege\"><a id=\"privilege_id\" class=\"check_link\" rel=\"menu_id:group_id\" name=\"privilege_switch\"></a></label>";
                $out = $out . str_ireplace("privilege_id", $privilege_id, str_ireplace('privilege_switch', $name, str_ireplace("group_id", $gp->group->idGroup, str_ireplace("menu_id", $menuItemRow->id, $checkbox_template))));
            }
        }
        return $out;
    }

}

?>
