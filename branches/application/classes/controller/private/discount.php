<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_Discount extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js');
        $discount_view = new View('private/discount');
        $discount = new Model_Discount();
        $discount = $discount->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $discount_view->discount_list = $discount;
        $this->template->content = $discount_view;
    }

    public function action_listDiscounts() {
        try {
            $db = DB::select()->from('discount')
                            ->and_where('discount.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->order_by('discount.discount', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_createOrUpdateDiscount() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('discount_value', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR";
                die();
            }
            $dicount_value = $_POST['discount_value'];
            $discount_id = $_POST['discount_id'];
            $discount = new Model_Discount();
            if ($discount_id != 0)
                $discount = ORM::factory('Discount', $discount_id);
            else
                $discount->registrationDate = Date::formatted_time ();
            $discount->discount = trim($dicount_value);
            $discount->status = $this->GENERAL_STATUS['ACTIVE'];
            $discount->save();
            echo "1|ok";
        } catch (Exception $exc) {
            echo "0|" . $exc->getTraceAsString();
        }
        die();
    }

    public function action_removeDiscount() {
        try {
            $discount_id = $_POST['discount_id'];
            $dicount = ORM::factory('Discount', $discount_id);
            $dicount->status = $this->GENERAL_STATUS['DEACTIVE'];
            $dicount->save();
            echo "1";
            die();
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
    }

    public function action_findDiscountById() {
        try {
            $discount_id = $_POST['discount_id'];
            $discount = ORM::factory('Discount', $discount_id)->as_array();
            echo json_encode($discount);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        die();
    }

}

?>
