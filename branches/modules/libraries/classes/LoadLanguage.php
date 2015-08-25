<?php

defined('SYSPATH') or die('No direct script access.');

/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 * Clase loadLanguage
 *  ------------ Crea archivos de traduccion a partir de la Base de datos, tabla traduccion.
 * Funcion loadData($controllersArray,$language,$modoTraduccion)
 * $controllersArray: Es el arreglo de controladores a generar
 * $language : el idioma que quieres que genere es,fr,br,en
 * $modoTraduccion : 0 si es que quieres que haga la busqueda normal o 1 si quieres que invierta la traduccion
 * $deleteFiles : si es 1 entonces elimina los archivos, antes de crearlos.
 * Author: Solman Vaisman Gonzalez.
 * Fecha : 14/09/2010
  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

class LoadLanguage {

    public function loadData($controllersArray, $language, $modoTraduccion, $deleteFiles) {

        for ($j = 0; $j < count($controllersArray); $j++) {
            $nombre_a = $controllersArray[$j];
            if ($deleteFiles == 1)
                FuncionesAyuda::delete('application/i18n/' . $nombre_a . '.php');

            if (file_exists('application/i18n/' . $nombre_a . '.php') == false) {
                $conection = ManualConectionUtil::conection();
                mysql_query("SET NAMES utf8");
                //$sql = "SELECT * FROM `languages` WHERE `lang`='" . $language . "' AND controller='" . $nombre_a . "'";
                //echo $sql;
                //die();
                //$result = mysql_query("SELECT * FROM `languages` WHERE `lang`='" . $language . "' AND controller='" . $nombre_a . "'", $conection);
                $result = mysql_query("SELECT * FROM `translate` WHERE `language`='" . $language . "' AND controller='" . $nombre_a . "'", $conection);


                $nfilas = mysql_num_rows($result);

                if ($nfilas > 0) {
                    $controllerFile = fopen('application/i18n/' . $nombre_a . ".php", 'a') or die("problemas al crear archivo");
                    fputs($controllerFile, "<?php \n /*" . $nombre_a . ".php -- archivo de idiomas Kohana " . strtoupper($language) . " \n");
                    fputs($controllerFile, " Author: Solman Vaisman Gonzalez \n");
                    fputs($controllerFile, " Kohana Framework 3.0 Todos los derecho reservados */ \n\n");
                    fputs($controllerFile, "defined('SYSPATH') or die('No direct script access.'); \n\n");
                    fputs($controllerFile, "return array\n");
                    fputs($controllerFile, "(\n");

                    for ($i = 0; $i < $nfilas - 1; $i++) {
                        $row = mysql_fetch_array($result);
                        if ($modoTraduccion == 0) {
                            $text = $row["text"];
                            $textTranslate = $row["textTranslate"];
                        } else {
                            $text = $row["textTranslate"];
                            $textTranslate = $row["text"];
                        }
                        fputs($controllerFile, "\t'" . $text . "' => '" . $textTranslate . "', \n");
                    }
                    $row = mysql_fetch_array($result);
                    if ($modoTraduccion == 0) {
                        $text = $row["text"];
                        $textTranslate = $row["textTranslate"];
                    } else {
                        $text = $row["textTranslate"];
                        $textTranslate = $row["text"];
                    }
                    fputs($controllerFile, "\t'" . $text . "' => '" . $textTranslate . "' \n");

                    fputs($controllerFile, " \n); \n");
                    fputs($controllerFile, " \n ?> ");
                    fclose($controllerFile);
                }
                mysql_close($conection);
            }
            I18n::lang($nombre_a);
        }
    }

}

?>
