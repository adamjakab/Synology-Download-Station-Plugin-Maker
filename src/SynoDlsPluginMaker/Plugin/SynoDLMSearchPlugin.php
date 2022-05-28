<?php
namespace SynoDlsPluginMaker\Plugin;

/**
 *
 * This is the fake copy of the SynoDLMSearchPlugin class declared in the btsearch.php file
 * and passed to the parse method of the search plugins.
 * We mimic this class here for the development environment so that the plugins can operate
 * in the same way as they were in the real environment.
 * (NdA: sorry for the shitty explanation)
 *
 * @see btsearch.php
 *
 */
class SynoDLMSearchPlugin
{

    /** @var array      This will keep the search results */
    private $results = [];

    /** @var string */
    private $pluginDir;

    /** @var object     Contains the values from the INFO file */
    private $jsonObj;

    private $module;

    public function __construct($pluginDir)
    {
        $this->pluginDir = $pluginDir;
        $infoPath = $this->pluginDir . "/INFO";
        $jsonData = file_get_contents($infoPath);
        $this->jsonObj = json_decode($jsonData, TRUE);
    }

    public function addResult($title, $dlurl, $size, $date, $page, $hash, $seeds, $leechs, $category)
    {
        array_push($this->results, [
            "provider_name" => $this->getName(),
            "provider_displayname" => $this->getDisplayName(),
            "title" => $title,
            "download" => $dlurl,
            "size" => $size,
            "datetime" => $date,
            "page" => $page,
            "hash" => $hash,
            "seeds" => $seeds,
            "leechs" => $leechs,
            "category" => $category
        ]);
    }

    public function addRSSResults($content)
    {
        return;
    }

    public function addJsonResults($content, $itemsKey, $fieldMap, $dtformat = '')
    {
        return;
    }

    public function getName()
    {
        return $this->jsonObj['name'];
    }

    public function getDisplayName()
    {
        return $this->jsonObj['displayname'];
    }

    public function getSite()
    {
        return $this->jsonObj['site'];
    }

    public function getVersion()
    {
        return $this->jsonObj['version'];
    }

    public function getAccountSupport()
    {
        return $this->jsonObj['accountsupport'];
    }

    public function getHostPrefix()
    {
        return $this->jsonObj['hostprefix'];
    }

    public function getClass()
    {
        return $this->jsonObj['class'];
    }

    public function getDescription()
    {
        return $this->jsonObj['description'];
    }

    public function getModule()
    {
        return $this->module;
    }

    public function load($importClass)
    {
        return TRUE;
    }

    // Additional - Custom methods NOT in the original class
    public function getResults()
    {
        return $this->results;
    }
}

