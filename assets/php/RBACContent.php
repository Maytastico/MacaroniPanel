<?php


class RBACContent
{
    static public function showAvailableRolesAsDropdown(){
        $roles = RBAC::fetchRoleTable();

        if($roles === false){
            echo "<select class='wrong' name='type'>";
            echo "<option>No roles were found. Please install the application again!</option>";
        }else{
            echo "<select name='type'>";
            foreach ($roles as $entry){
                echo "<option value=\"" . $entry['name'] . "\">". $entry['name'] . "</option>";
            }
        }
        echo "</select>";
    }
}