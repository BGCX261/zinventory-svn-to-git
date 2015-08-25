<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Warehouse extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js',
            'media/js/zinventory.arrayMap.js');
        $warehouse_view = new View('private/warehouse');
        $warehouse = new Model_Warehouse();
        $warehouse = $warehouse->where('status', '=', 1)->find_all();
        $warehouse_view->warehouse_list = $warehouse;
        $this->template->content = $warehouse_view;
    }

    public function action_createOrUpdateWarehouse() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('warehouse_name', 'not_empty')
                            ->rule('warehouse_short_name', 'not_empty')
                            ->rule('warehouse_office_location', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR - Empty Data Post";
                die();
            }
            $warehouse_id = StringHelper::cleanEmptyString4NULL($_POST['warehouse_id']);
            $warehouse_name = StringHelper::cleanEmptyString4NULL($_POST['warehouse_name']);
            $warehouse_short_name = StringHelper::cleanEmptyString4NULL($_POST['warehouse_short_name']);
            $warehouse_office_location = StringHelper::cleanEmptyString4NULL($_POST['warehouse_office_location']);
            $warehouse = new Model_Warehouse();
            if ($warehouse_id != 0)
                $warehouse = ORM::factory('Warehouse', $warehouse_id);
            $warehouse->name = trim($warehouse_name);
            $warehouse->shortName= trim($warehouse_short_name);
            $warehouse->status = $this->GENERAL_STATUS['ACTIVE'];
            $warehouse->idOfficeLocation = $warehouse_office_location;
            $warehouse->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_listWarehouses() {
        try {
            $db = DB::select(
                                    array('warehouse.idWarehouse', 'warehouse_id'),
                                    array('warehouse.name', 'warehouse_name'),
                                    array('warehouse.shortName', 'warehouse_short_name'),
                                    array('officelocation.name', 'office_location_name')
                            )->from('warehouse')
                            ->and_where('warehouse.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->join('officelocation')
                            ->on('officelocation.idOfficeLocation', '=', 'warehouse.idOfficeLocation')
                            ->order_by('warehouse.name', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_removeWarehouse() {
        try {
            $warehouse_id = $_POST['warehouse_id'];
            $warehouse = ORM::factory('Warehouse', $warehouse_id);
            $warehouse->status = $this->GENERAL_STATUS['DEACTIVE'];
            $warehouse->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_findWarehouseById() {
        try {
            $warehouse_id = $_POST['warehouse_id'];
            $warehouse = ORM::factory('Warehouse', $warehouse_id)->as_array();
            echo json_encode($warehouse);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
