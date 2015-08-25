<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Funciones Demasiado especiales

 */

/**
 * Funciones especiales para uso domestico
 *
 * @author Solman Vaisman Gonzalez
 * Solman28@hotmail.com
 * Tu papi!
 */
class FuncionesAyuda {

    public static function noCache() {
      header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
    }

    //Function: delete($file)
    //$file: un archivo o carpeta
    //accion: Borra el archivo o carpeta o subcarpetas no deja nada.
    
    public static function delete($file) {
        if (file_exists($file)){
            chmod($file,0777);
            if (is_dir($file)) {
                $handle = opendir($file);
                while($filename = readdir($handle)) {
                    if ($filename != "." && $filename != "..") {
                        Helper_funcionesayuda::delete($file."/".$filename);
                    }
                }
                closedir($handle);
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }


}
?>
