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
        $tableContent = User::getUserTable();
        return $tableContent;
    }

}