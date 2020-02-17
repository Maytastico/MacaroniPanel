<?php


class UserContent
{
    private static $pages;
    private static $page;
    private static $entries;
    private static $maxEntries;

    public static function showUserTable(){
        $tableContent = User::getUserTable();
        return $tableContent;
    }
}