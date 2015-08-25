<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends ORM {

   protected $_table_names_plural  = false;
   protected $_table_name = 'group';
   protected $_primary_key = 'idGroup';
   protected $_has_many = array('menus' => array('model' => 'Menu', 'through' => 'privilege'));

}
