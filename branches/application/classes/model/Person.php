<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Person extends ORM{
    protected $_table_names_plural  = false;
    protected $_table_name = 'person';
    protected $_primary_key = 'idPerson';
    protected $_belongs_to = array('country' => array('model' => 'Country','foreign_key' => 'idCountry'),
                                   'person' => array('model' => 'Person','foreign_key' => 'idUser'),
                                   'discount' => array('model' => 'Discount','foreign_key' => 'idDiscount'),
                                   'warehouse' => array('model' => 'Warehouse','foreign_key' => 'idWarehouse'));
    
}

?>