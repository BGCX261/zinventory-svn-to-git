<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StringHelper
 *
 * @author Emmanuel
 */
class StringHelper {

    public static function cleanEmptyString4NULL($string){
        if(strcmp(trim($string), '')==0){
            return NULL;
        } else {
            return trim($string);
        }
    }
}
?>
