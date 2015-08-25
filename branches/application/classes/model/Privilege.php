<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Privilege
 *
 * @author Emmanuel
 */
class Model_Privilege extends ORM{
    protected $_table_names_plural = false;
    protected $_table_name = 'privilege';
    protected $_primary_key = 'idPrivilege';
    //protected $_belongs_to = array('group' => array('model' => 'Group', 'foreign_key' => 'idGroup'));
    //protected $_belongs_to = array('menu' => array('model' => 'Menu', 'foreign_key' => 'idMenu'));
    protected $_belongs_to = array('group' => array('model' => 'Group', 'foreign_key' => 'idGroup'), 'menu' => array('model' => 'Menu', 'foreign_key' => 'idMenu'));
}
?>
