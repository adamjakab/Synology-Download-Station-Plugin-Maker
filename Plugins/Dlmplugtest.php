<?php
/**
 * The autoloader will make sure load the user classes.
 * This must be required and regisitered before any use statements.
 */
require_once 'SynoPluginHelper/Autoloader.php';
SynoPluginHelper\Autoloader::register(dirname(__FILE__));

use SynoPluginHelper\DlmPlugin;
use SynoPluginHelper\DlmPluginInterface;

class Dlmplugtest extends DlmPlugin implements DlmPluginInterface {
    
    protected $debug = true;
    protected $log_file = '/tmp/dlm_plg_test.log';
    
    
    public function __construct() {
        $this->log(str_repeat("-", 60));
        $this->log("Constructed.");
    }
    
    public function __destruct() {
        $this->log("Destructed.");
    }
    
    public function prepare($curl, $query) {
        $searchurl = "https://jakab.pro/?s=%s";
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4');
        curl_setopt($curl, CURLOPT_URL, sprintf($searchurl, urlencode($query)));
        $this->log(sprintf("Searching: %s", $query));
    }
    
    public function parse($plugin, $response) {
        
        $dummyResults = [
            [
                "title" => "Test Result 1",
                "download" => "http://jakab.pro/test_1.zip",
                "size" => "Test Result 1",
                "datetime" => date('Y-m-d h:i:s'),
                "page" => "http://jakab.pro/media/file_1",
                "hash" => md5("Test Result 1"),
                "seeds" => rand(50,150),
                "leeches" => rand(10,50),
                "category" => "File"
            ]
        ];
        
        foreach ($dummyResults as $dummy) {
            $plugin->addResult($dummy["title"], $dummy["download"], $dummy["size"], 
                $dummy["datetime"], $dummy["page"], $dummy["hash"], $dummy["seeds"], $dummy["leeches"], $dummy["category"]);
        }
        
        return count($dummyResults);
    }
    
}
