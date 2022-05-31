<?php
namespace Plugins\SynoPluginLibrary;

use DateTime;

/**
 * Unified search item
 */
class SearchLink
{

    /** @var string The name of the plugin used to find the resource */
    protected $source = "";

    /** @var string The name of the item */
    protected $name = "";

    /** @var string The URL of the page where the resource was found */
    protected $source_url = "";

    /** @var string The URL from where the resource can be downloaded from */
    protected $download_url = "";

    /** @var string The hash of the resource */
    protected $hash = "";

    /** @var integer The size in bytes of the resource */
    protected $size = 0;

    /** @var DateTime The date and time of the resource */
    protected $date_time;

    /** @var integer The number of seeds avalable */
    protected $seeds = 0;

    /** @var integer The number of peers leaching on the same resource */
    protected $peers = 0;

    /** @var string The category where this fits */
    protected $category = "";

    public function __construct()
    {
        $this->date_time = new \DateTime();
    }

    /**
     *
     * @param object $plugin
     *            Run the addResult method on the SynoDLMSearchPlugin class instance
     */
    public function feedDataToPlugin($plugin)
    {
        $plugin->addResult($this->getName(), $this->getDownloadUrl(), $this->getSize(), $this->getISO8601DateTime(), $this->getSourceUrl(), $this->getHash(), $this->getSeeds(), $this->getPeers(), $this->getCategory());
    }

    public function getDataArray()
    {
        return [
            "source" => $this->getSource(),
            "name" => $this->getName(),
            "source_url" => $this->getSourceUrl(),
            "download_url" => $this->getDownloadUrl(),
            "hash" => $this->getHash(),
            "size" => $this->getSize(),
            "date_time" => $this->getISO8601DateTime(),
            "seeds" => $this->getSeeds(),
            "peers" => $this->getPeers(),
            "category" => $this->getCategory()
        ];
    }

    /**
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getSourceUrl()
    {
        return $this->source_url;
    }

    /**
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        return $this->download_url;
    }

    /**
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     *
     * @return number
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     *
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->date_time;
    }

    /**
     *
     * @return string
     */
    public function getISO8601DateTime()
    {
        return $this->date_time->format(DateTime::ATOM);
    }

    /**
     *
     * @return number
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     *
     * @return number
     */
    public function getPeers()
    {
        return $this->peers;
    }

    /**
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        if (!$this->getHash()) {
            $this->setHash(md5($this->name));
        }
    }

    /**
     *
     * @param string $source_url
     */
    public function setSourceUrl($source_url)
    {
        $this->source_url = $source_url;
    }

    /**
     *
     * @param string $download_url
     */
    public function setDownloadUrl($download_url)
    {
        $this->download_url = html_entity_decode($download_url);
    }

    /**
     *
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     *
     * @param string $size
     */
    public function setSize($size)
    {
        $m = [];
        $regex = "#(?<size>[0-9.]*) ?(?<unit>[GKMT]B)#i";
        if (preg_match($regex, $size, $m)) {
            $size = $this->getSizeInBytes($m["size"], $m["unit"]);
        }
        $this->size = $size;
    }

    /**
     *
     * @todo: Needs 1024 based figures
     *
     * @param string $unit
     * @return int
     */
    protected function getSizeInBytes($size, $unit)
    {
        switch (strtoupper(trim($unit))) {
            case "KB":
                return intval(floor(pow(2, 10) * $size));
            case "MB":
                return intval(floor(pow(2, 20) * $size));
            case "GB":
                return intval(floor(pow(2, 30) * $size));
            case "TB":
                return intval(floor(pow(2, 40) * $size));
            default:
                return intval($size);
        }
    }

    /**
     *
     * @param DateTime $datetime
     */
    public function setDateTime($datetime)
    {
        $this->date_time = $datetime;
    }

    /**
     *
     * @param number $seeds
     */
    public function setSeeds($seeds)
    {
        $this->seeds = $seeds;
    }

    /**
     *
     * @param number $peers
     */
    public function setPeers($peers)
    {
        $this->peers = $peers;
    }

    /**
     *
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
