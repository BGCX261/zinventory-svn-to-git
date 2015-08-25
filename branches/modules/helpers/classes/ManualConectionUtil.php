<?php

defined('SYSPATH') or die('No direct script access.');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mconection
 *
 * @author Emmanuel
 */
class ManualConectionUtil {

    public static $host_name = "localhost";
    public static $user_name = "root";
    public static $password = "";
    public static $datbase_name = "db_inventory";

    public static function conection() {

        if (!($conection = mysql_connect(self::$host_name, self::$user_name, self::$password))) {
            echo "Error al conectarse a la base de datos.";
            exit();
        }
        if (!mysql_select_db(self::$datbase_name, $conection)) {
            echo "Error al seleccionar la base de datos.";
            exit();
        }
        return $conection;
    }

    public static function saludo() {
        return "Hola Mundo";
    }

}

?>
