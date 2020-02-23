<?php


class Table
{
    private $sites;
    private $site;
    private $currentSite;
    private $entries;
    private $maxEntries;

    private $data;
    private $tableHeader;


    public function __construct($data)
    {
        $this->data = $data;
    }

    private function evaluateHeader(){

    }

}