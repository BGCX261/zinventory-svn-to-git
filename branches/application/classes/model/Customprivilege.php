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
class Model_Customprivilege extends ORM{
    protected $_table_names_plural = false;
    protected $_table_name = 'customprivilege';
    protected $_primary_key = 'idCustomPrivilege';
    //protected $_belongs_to = array('group' => array('model' => 'Group', 'foreign_key' => 'idGroup'));
    //protected $_belongs_to = array('menu' => array('model' => 'Menu', 'foreign_key' => 'idMenu'));
    protected $_belongs_to = array('user' => array('model' => 'User', 'foreign_key' => 'idUser'), 'menu' => array('model' => 'Menu', 'foreign_key' => 'idMenu'));
}
?>