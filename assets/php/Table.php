<?php


abstract class Table
{
    /**
     * @var int
     */
    protected $sites;
    /**
     * @var int
     */
    protected $siteData;
    /**
     * @var int
     */
    protected $currentSite;
    /**
     * @var int
     * Contains the amount of entries that exist
     * Will be generated by a method
     */
    protected $entries;
    /**
     * @var int
     * Contains how many entries should exist inside the site data variable
     */
    protected $maxEntries;

    /**
     * @var array
     * Contains all entries.
     * Will be filled when the class gets initialized.
     */
    protected $data;
    /**
     * @var array
     * The table header contains how every column is called
     */
    protected $tableHeader;


    public function __construct($data)
    {
        $this->data = $data;
        $this->maxEntries = 10;
        $this->currentSite = 1;
        $this->reloadData();
    }
    private function reloadData(){
        $this->entries = $this->evaluateAmountOfEntries();
        $this->evaluateHeader();
        $this->siteData = $this->evaluateSiteData();
        $this->sites = $this->evaluateAmountSites();
    }
    private function evaluateAmountOfEntries()
    {
        return count($this->data);
    }
    private function evaluateAmountSites()
    {

        $sites = $this->entries / $this->maxEntries;
        $intSites = $sites;
        settype($intSites, "Integer");
        settype($intSites, "Float");
        $calc = $intSites * $this->maxEntries;
        settype($calc, "Float");
        if($sites <= $calc)
            $intSites++;

        return $intSites;
    }

    abstract protected function evaluateHeader();
    abstract public function drawTable();

    private function evaluateSiteData(){
        $tableData = $this->data;
        $siteData = array();
        $begin = ($this->currentSite -1) * ($this->maxEntries);
        $end = $this->currentSite * $this->maxEntries;
        $index = 0;
        for($i=$begin; $i <= $end; $i++){
            $siteData[$index] = $tableData[$i];
            $index++;
        }
        return $siteData;
    }
    /**
     * @param int $maxEntries
     */
    public function setMaxEntries($maxEntries)
    {
        $this->maxEntries = $maxEntries;
        $this->reloadData();
    }

    /**
     * @param int $currentSite
     * Sets the
     */
    public function setCurrentSite($currentSite)
    {
        $this->currentSite = $currentSite;
        $this->reloadData();
    }

}