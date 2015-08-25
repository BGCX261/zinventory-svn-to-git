<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Warehouse extends ORM{
    protected $_table_names_plural  = false;
    protected $_table_name = 'warehouse';
    protected $_primary_key = 'idWarehouse';
    protected $_belongs_to = array('officeLocation' => array('model' => 'Officelocation','foreign_key' => 'idOfficeLocation'));

}

?>