<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Officelocation extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js',
            'media/js/zinventory.arrayMap.js');
        $office_location_view = new View('private/office_location');
        $office_location = new Model_Officelocation();
        $office_location = $office_location->where('status', '=', 1)->find_all();
        $office_location_view->office_location_list = $office_location;
        $this->template->content = $office_location_view;
    }

    public function action_createOrUpdateOfficeLocation() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('office_location_name', 'not_empty')
                            ->rule('office_location_address', 'not_empty')
                            ->rule('office_location_country', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR - Empty Data Post";
                die();
            }
            $office_location_id = StringHelper::cleanEmptyString4NULL($_POST['office_location_id']);
            $office_location_name = StringHelper::cleanEmptyString4NULL($_POST['office_location_name']);
            $office_location_address = StringHelper::cleanEmptyString4NULL($_POST['office_location_address']);
            $office_location_country = StringHelper::cleanEmptyString4NULL($_POST['office_location_country']);
            $office_location = new Model_Officelocation();
            if ($office_location_id != 0)
                $office_location = ORM::factory('Officelocation', $office_location_id);
            $office_location->name = trim($office_location_name);
            $office_location->address = trim($office_location_address);
            $office_location->status = $this->GENERAL_STATUS['ACTIVE'];
            $office_location->idCountry = $office_location_country;
            $office_location->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_listOfficeLocations() {
        try {
            $db = DB::select(
                                    array('officelocation.idOfficeLocation', 'office_location_id'),
                                    array('officelocation.name', 'office_location_name'),
                                    array('officelocation.address', 'office_location_address'),
                                    array('country.name', 'country_name')
                            )->from('officelocation')
                            ->and_where('officelocation.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->join('country')
                            ->on('country.idCountry', '=', 'officelocation.idCountry')
                            ->order_by('officelocation.name', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_removeOfficeLocation() {
        try {
            $office_location_id = $_POST['office_location_id'];
            $office_location = ORM::factory('Officelocation', $office_location_id);
            $office_location->status = $this->GENERAL_STATUS['DEACTIVE'];
            $office_location->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_findOfficeLocationById() {
        try {
            $office_location_id = $_POST['office_location_id'];
            $office_location = ORM::factory('Officelocation', $office_location_id)->as_array();
            echo json_encode($office_location);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
