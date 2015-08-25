<?php defined('SYSPATH') or die('No direct script access.');
/* 
 * Funciones especiales
 
 */

/**
 * Funciones especiales para uso domestico
 *
 * @author Hardlick
 */
class Helper_functionsDate {
    
    /**
     * Funcion getFechaActual
     * Sirve para formatear la fecha de forma predeterminada para el insertado en la base de datos mysql format
     * Formato Y-m-d
     * @author Hardlick
     */
    static  function getfechaActual()
	{
	//formateado para el insertado en la bd
	$fechaA= date('Y-m-d');
	return $fechaA;
	}

    /**
     * Funcion getFechaFormateadaActual
     * Sirve para formatear la fecha para la vista del usuario
     * Formato Y-m-d H-M-S
     * @author Hardlick
     */
	static function getFechaFormateadaActual()
	{
		$fechaA= strftime( "%Y-%m-%d %H:%M:%S", time() );
		return $fechaA;
	}
        
        /**
     * Funcion getFechaAnterior
     * Sirve para obtener la fecha del dia anterior basandose en la fecha actual,
     * formatear la fecha de forma predeterminada para el insertado en la base de datos mysql format
     * Formato Y-m-d
     * @author Hardlick
     */
    static  function getfechaAnterioraHoy()
	{
	//formateado para el insertado en la bd
        $dia = date("Y-m-d", time()-86400);
	return $dia;
	}
   
     /**
     *Convertir el formato 00/00/0000 mes/dia/año, a formato de MYSQL
     * Fecha en Nuestro Formato
     * Creamos una lista de variables a la cual le asignamos los valores parciales de $dtm_fechainicial, divididos por el signo "/"     
     * @author Hardlick
     */
     static function cambiafmysqlre($dtm_fechainicial)
      {    
    list($mes ,$dia , $anio) = split( '[/__]', $dtm_fechainicial );
    // reasignamos la fecha a $dtm_fechainicial con su nuevo formato
    $dtm_fechainicial = "$anio-$mes-$dia";
    return $dtm_fechainicial;
    }

     /**
     *Convertir el formato 0000-00-00 año-mes-dia a formato: mes/dia/año.
     * Fecha en Nuestro Formato
     * Creamos una lista de variables a la cual le asignamos los valores parciales de $dtm_fechainicial, divididos por el signo "/"
     * @author Hardlick
     */
    static function cambiafmysql($fecha){
    ereg( "([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    $lafecha=$mifecha[2]."/".$mifecha[3]."/".$mifecha[1];
    return $lafecha;
    }


}
?>
