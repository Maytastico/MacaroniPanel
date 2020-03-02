<?php


class UserContent extends Table
{
    public function __construct()
    {
        $userData = User::getUserTableAsUserObj();
        parent::__construct($userData);
    }

    protected function evaluateHeader()
    {
        $this->tableHeader = array("Profile",
                                   "Username",
                                   "Email",
                                   "Last Login",
                                   "Role",
                                   "Actions");
    }

    public function drawTable()
    {
        echo "<table class='tableContent'>";
        $siteData = $this->siteData;
        echo "<tr>";
        foreach ($this->tableHeader as $head){
            echo "<th>" . $head . "</th>";
        }
        echo "</tr>";

        foreach ($siteData as $data){
            echo "<tr>";
            echo "<td><div class='row'><div class='profilePicture'>".$data->getCurrentProfilePicture()."</div></div></td>";
            echo "<td>".$data->getUsername()."</td>";
            echo "<td>".$data->getEmail()."</td>";
            echo "<td>".$data->getLastLogin()."</td>";
            echo "<td>".RBAC::fetchRoleNameFormID($data->getRoleID())."</td>";
            echo '<td><form class="row" action="" method="post">
                    <button class="col radial red"><img src="'.Loader::$jump.'/assets/icons/feather/trash-2.svg">
                    </button>
                    <button class="col radial"><img src="'.Loader::$jump .'/assets/icons/feather/edit.svg">
                    </button>
                </form></td>';
            echo "</tr>";
        }

        echo "</table>";
    }

}