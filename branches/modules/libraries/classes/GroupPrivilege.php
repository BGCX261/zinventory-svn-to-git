<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GroupPrivilege
 *
 * @author Emmanuel
 */
class GroupPrivilege {
    //put your code here
    public $group;
    public $privilege;

    function __construct($group,$privilege) {
        $this->group = $group;
        $this->privilege = $privilege;
    }

}
?>
