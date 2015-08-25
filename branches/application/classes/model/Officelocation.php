<?php defined('SYSPATH') or die('No direct script access.');

class Model_Officelocation extends ORM {

   protected $_table_names_plural  = false;
   protected $_table_name = 'officelocation';
   protected $_primary_key = 'idOfficeLocation';
   protected $_belongs_to = array('country' => array('model' => 'Country','foreign_key' => 'idCountry'));
}
