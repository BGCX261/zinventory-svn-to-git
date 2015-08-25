<?php

defined('SYSPATH') or die('No direct script access.');

class Model_User extends ORM {

    protected $_table_names_plural = false;
    protected $_table_name = 'user';
    protected $_primary_key = 'idUser';
    protected $_belongs_to = array('group' => array('model' => 'Group', 'foreign_key' => 'idGroup'));
    protected $_has_one = array('person' => array('model' => 'Person', 'foreign_key' => 'idPerson'));
    protected $_has_many = array('customPrivileges' => array('model' => 'Menu', 'through' => 'customprivilege'));

}

?>
