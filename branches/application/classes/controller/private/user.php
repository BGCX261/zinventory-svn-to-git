<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Private_User extends Controller_Private_Admin {

    public function action_index() {
        parent::action_index();
        $this->template->styles = $styles = array(
            'media/css/jquery.dataTables.css' => 'screen');
        $this->template->scripts = $scripts = array(
            'media/js/jquery.dataTables.min.js',
            'media/js/zinventory.arrayMap.js');
        $user_view = new View('private/user');
        $user_list = new Model_User();
        $user_list = $user_list->where('status', '=', $this->GENERAL_STATUS['ACTIVE'])->find_all();
        $user_view->user_list = $user_list;
        $this->template->content = $user_view;
    }

    public function action_createOrUpdateUser() {
        try {
            $post = Validate::factory($_POST)
                            ->rule('person_fname', 'not_empty')
                            ->rule('person_lname', 'not_empty')
                            ->rule('person_email', 'not_empty')
                            ->rule('user_id', 'not_empty')
                            ->rule('user_name', 'not_empty')
                            ->rule('user_password', 'not_empty')
                            ->rule('user_group', 'not_empty')
                            ->rule('person_country', 'not_empty');
            if (!$post->check()) {
                echo "0|ERROR - Empty Data Post";
                die();
            }
            $person_fname = StringHelper::cleanEmptyString4NULL($_POST['person_fname']);
            $person_lname = StringHelper::cleanEmptyString4NULL($_POST['person_lname']);
            $person_email = StringHelper::cleanEmptyString4NULL($_POST['person_email']);
            $user_id = $_POST['user_id'];
            $user_name = StringHelper::cleanEmptyString4NULL($_POST['user_name']);
            $user_password = $_POST['user_password'];
            $user_group = $_POST['user_group'];
            $person_country = $_POST['person_country'];

            $person_phone1 = StringHelper::cleanEmptyString4NULL($_POST['person_phone1']);
            $person_phone2 = StringHelper::cleanEmptyString4NULL($_POST['person_phone2']);
            $person_cellphone = StringHelper::cleanEmptyString4NULL($_POST['person_cellphone']);
            $person_address1 = StringHelper::cleanEmptyString4NULL($_POST['person_address1']);
            $person_address2 = StringHelper::cleanEmptyString4NULL($_POST['person_address2']);
            $person_city = StringHelper::cleanEmptyString4NULL($_POST['person_city']);
            $person_zipcode = StringHelper::cleanEmptyString4NULL($_POST['person_zipcode']);

            $person_array_data = array(
                'fName' => $person_fname,
                'lName' => $person_lname,
                'phone1' => $person_phone1,
                'phone2' => $person_phone2,
                'address1' => $person_address1,
                'address2' => $person_address2,
                'idCountry' => $person_country,
                'email' => $person_email,
                'city' => $person_city,
                'zipcode' => $person_zipcode,
                'type' => $this->USER_TYPE['EMPLOYEE']
            );

            $user_array_data = array(
                'idUser' => NULL,
                'userName' => $user_name,
                'password' => $user_password,
                'status' => $this->GENERAL_STATUS['ACTIVE'],
                'registrationDate' => NULL,
                'idGroup' => $user_group
            );

            DB::query(NULL, "BEGIN WORK")->execute();

            $success = FALSE;

            if ($user_id == 0) {

                //CREATE PERSON
                $person_saved = DB::insert('person', array_keys($person_array_data))
                                ->values($person_array_data)
                                ->execute();

                //CREATE USER
                $user_array_data['idUser'] = $person_saved[0];
                $user_array_data['registrationDate'] = Date::formatted_time();

                $user_saved = DB::insert('user', array_keys($user_array_data))
                                ->values($user_array_data)
                                ->execute();

                $success = ($person_saved[1] AND $user_saved[1]);
            } else {
                //UPDATE PERSON
                $person_update = DB::update('person')
                                ->set($person_array_data)
                                ->where('idPerson', '=', $user_id)
                                ->execute();
                //UPDATE USER
                unset($user_array_data['idUser']);
                unset($user_array_data['registrationDate']);
                $user_update = DB::update('user')
                                ->set($user_array_data)
                                ->where('idUser', '=', $user_id)
                                ->execute();
                $success = (($person_update >= 0) OR ($user_update >= 0));
            }

            if ($success) {
                DB::query(NULL, "COMMIT")->execute();
                echo "1|ok";
            }
        } catch (Exception $exc) {
            DB::query(NULL, "ROLLBACK")->execute();
            echo "0|COMMIT ERROR - ROLLBACK ACTION SUCCESS - " . $exc->getTraceAsString();
        }
        die();
    }

    public function action_findUserById() {
        $user_id = $_POST['user_id'];
        $user = DB::select(
                                array('user.idUser', 'user_id'),
                                array('user.userName', 'user_name'),
                                array('user.password', 'user_password'),
                                array('user.idGroup', 'user_group_id'),
                                array('person.fName', 'person_fname'),
                                array('person.lName', 'person_lname'),
                                array('person.email', 'person_email'),
                                array('person.phone1', 'person_phone1'),
                                array('person.phone2', 'person_phon2'),
                                array('person.cellPhone', 'person_cellphone'),
                                array('person.address1', 'person_address1'),
                                array('person.address2', 'person_address2'),
                                array('person.city', 'person_city'),
                                array('person.zipcode', 'person_zipcode'),
                                array('person.idCountry', 'person_country_id'),
                                array('user.registrationDate', 'user_regisrationDate')
                        )->from('user')
                        ->and_where('user.idUser', '=', $user_id)
                        ->join('person')
                        ->on('person.idPerson', '=', 'user.idUser')
                        ->execute()->as_array();
        echo json_encode($user);
        die();
    }

    public function action_listUsers() {
        try {
            $db = DB::select(
                                    array('user.idUser', 'user_id'),
                                    array('user.userName', 'user_name'),
                                    array('group.name', 'group_name'),
                                    array('person.fName', 'person_fname'),
                                    array('person.lName', 'person_lname'),
                                    array('person.email', 'person_email'),
                                    array('user.registrationDate', 'user_registrationDate')
                            )->from('user')
                            ->and_where('user.status', '=', $this->GENERAL_STATUS['ACTIVE'])
                            ->join('person')
                            ->on('person.idPerson', '=', 'user.idUser')
                            ->join('group')
                            ->on('group.idGroup', '=', 'user.idGroup')
                            ->order_by('user.userName', 'asc')
                            ->execute()->as_array();
            echo json_encode($db);
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

    public function action_removeUser() {
        try {
            $user_id = $_POST['user_id'];
            $user = ORM::factory('User', $user_id);
            $user->status = $this->GENERAL_STATUS['DEACTIVE'];
            $user->save();
            echo "1";
        } catch (Database_Exception $e) {
            echo $e->getMessage();
        }
        die();
    }

}

?>
