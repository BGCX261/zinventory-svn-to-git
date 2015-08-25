<?php defined('SYSPATH') or die('No direct script access.'); 
/* 
 * Funciones especiales
 
 */

/**
 * Funciones especiales para uso domestico
 *
 * @author Hardlick
 */
class Helper_functionsJson {
    
    /**
     * Funcion getFechaActual
     * Sirve para formatear la fecha de forma predeterminada para el insertado en la base de datos mysql format
     * Formato Y-m-d
     * @author Hardlick
     */
    static  function AllreturnJson($obj)
	{
	$json = array();
        foreach ($obj as $item) {
            $json[] = $item->as_array();
        }
        return json_encode($json);

	}

  

}
?>
