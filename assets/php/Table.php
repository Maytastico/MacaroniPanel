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
    protected $site;
    /**
     * @var int
     */
    protected $currentSite;
    /**
     * @var int
     * Contains the amount of entries that exist
     */
    protected $entries;
    protected $maxEntries;

    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $tableHeader;


    public function __construct($data)
    {
        $this->data = $data;
        $this->entries = $this->evaluateAmountOfEntries();
        $this->evaluateHeader();
        $this->maxEntries = 20;
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
       /* if($sites<1){
            $sites = 1;
        }
        */settype($intSites, "Integer");
        settype($intSites, "Float");
        $calc = $intSites * $this->maxEntries;
        settype($calc, "Float");
        if($sites <= $calc)
            $intSites++;

        return $intSites;
    }

    abstract protected function evaluateHeader();
    abstract public function drawTable();


    /**
     * @param mixed $maxEntries
     */
    public function setMaxEntries($maxEntries)
    {
        $this->maxEntries = $maxEntries;
    }

    /**
     * @param mixed $currentSite
     */
    public function setCurrentSite($currentSite)
    {
        $this->currentSite = $currentSite;
    }

}