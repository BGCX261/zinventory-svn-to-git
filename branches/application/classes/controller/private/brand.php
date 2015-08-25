<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Brand extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $brand_view = new View('private/brand');
        $brand = new Model_Brand();
        $brand = $brand->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $brand_view->brand_list = $brand;
        $this->template->content = $brand_view;
    }

    public function action_listBrands() {
        try {
            $db = DB::select()->from('brand')
                            ->and_where('brand.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->order_by('brand.fullName', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_createOrUpdateBrand() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('brand_full_name', 'not_empty')
                            ->rule('brand_short_name', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR";
                die();
            }
            $brand_full_name = $_POST['brand_full_name'];
            $brand_short_name = $_POST['brand_short_name'];
            $brand_id = $_POST['brand_id'];
            $brand = new Model_Brand();
            if ($brand_id != 0)
                $brand = ORM::factory('Brand', $brand_id);
            $brand->fullName = trim($brand_full_name);
            $brand->shortName = trim($brand_short_name);
            $brand->status = $this->GENERAL_STATUS['ACTIVE'];
            $brand->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_removeBrand() {
        try {
            $brand_id = $_POST['brand_id'];
            $brand = ORM::factory('Brand', $brand_id);
            $brand->status = $this->GENERAL_STATUS['DEACTIVE'];
            $brand->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_findBrandById() {
        try {
            $brand_id = $_POST['brand_id'];
            $brand = ORM::factory('Brand', $brand_id)->as_array();
            echo json_encode($brand);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
