<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Menu extends ORM {

    protected $_table_names_plural = false;
    protected $_table_name = 'menu';
    protected $_primary_key = 'idMenu';
    protected $_sorting = array('name' => 'asc', 'type' => 'desc ');
    protected $_belongs_to = array('superMenu' => array('model' => 'ParentMenu', 'foreign_key' => 'idMenu'));
    protected $_has_many = array('
        groups' => array('model' => 'Group', 'through' => 'privilege'),
        'users' => array('model' => 'User', 'through' => 'customPrivilege'));

}
