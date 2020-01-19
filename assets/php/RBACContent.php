<?php


class RBACContent
{
    /**
     * Generates a HTML Dropdown for a User Management UI
     */
    static public function showAvailableRolesAsDropdown(){
        $roles = RBAC::fetchRoleTable();

        if($roles === false){
            echo "<select class='wrong' name='type'>";
            echo "<option>No roles were found. Please install the basic Permission Model!</option>";
        }else{
            echo "<select name='type'>";
            foreach ($roles as $entry){
                echo "<option value=\"" . $entry['name'] . "\">". $entry['name'] . "</option>";
            }
        }
        echo "</select>";
    }
    static public function showSelectedRoleAsDropdown($rolename){
        $roles = RBAC::fetchRoleTable();

        if($roles === false){
            echo "<select class='wrong' name='type'>";
            echo "<option>No roles were found. Please install the basic Permission Model!</option>";
        }else{
            echo "<select name='type'>";
            foreach ($roles as $entry){
                echo "<option value=\"" . $entry['name'] . "\">". $entry['name'] . "</option>";
            }
        }
        echo "</select>";
    }
}