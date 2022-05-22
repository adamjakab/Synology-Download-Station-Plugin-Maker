<?php
namespace Plugins\Test2;
/**
 * The autoloader will make sure load the user classes.
 * This must be required and regisitered before any use statements.
 */
$pluginsBasePath = dirname(__DIR__);
require_once $pluginsBasePath . '/SynoPluginHelper/Autoloader.php';
\Plugins\SynoPluginHelper\Autoloader::register(["Plugins\\" => $pluginsBasePath]);


use Plugins\SynoPluginHelper\DlmPlugin;
use Plugins\SynoPluginHelper\DlmPluginInterface;

class Test2DlmSearchPlugin extends DlmPlugin implements DlmPluginInterface
{
    protected $debug = true;
    protected $name = __CLASS__;

    public function __construct()
    {
        $this->log(str_repeat("-", 60));
        $this->log("Constructed.");
    }

    public function __destruct()
    {
        $this->log("Destructed.");
    }
    
    public function prepare($curl, $query)
    {
        parent::prepare($curl, $query);
    }
    
    public function parse($plugin, $response)
    {
        $this->log("Parsing...");
        return 0;
    }
    
    
    /*
    public function prepare($curl, $query)
    {
        $searchurl = "https://jakab.pro/?s=%s";
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4');
        curl_setopt($curl, CURLOPT_URL, sprintf($searchurl, urlencode($query)));
        $this->log(sprintf("Searching: %s", $query));
    }

    public function parse($plugin, $response)
    {
        $dummyResults = [];
        $this->log("Parsing Response...");
        $dummyResults = $this->getDummyData();
        $this->log("Results Count: " . count($dummyResults));

        foreach ($dummyResults as $dummy) {
            $plugin->addResult($dummy["title"], $dummy["download"], $dummy["size"], $dummy["datetime"], $dummy["page"], $dummy["hash"], $dummy["seeds"], $dummy["leeches"], $dummy["category"]);
        }

        return count($dummyResults);
    }
    
    private function getDummyData() {
        $answer = [];
        $maxCnt = 5;
        
        for ($i=1; $i<=$maxCnt; $i++) {
            $dummy = [
                "title" => sprintf("Test Result %s", $i),
                "download" => sprintf("https://jakab.pro/test_%s.zip", $i),
                "size" => 1024 * rand(1, 1024),
                "datetime" => date('Y-m-d h:i:s'),
                "page" => sprintf("https://jakab.pro/media/file_%s", $i),
                "hash" => md5(sprintf("Test Result %s", $i)),
                "seeds" => rand(50, 150),
                "leeches" => rand(10, 50),
                "category" => "File"
            ];
            $answer[] = $dummy;
        }
        
        return $answer;
    }
    */
}
