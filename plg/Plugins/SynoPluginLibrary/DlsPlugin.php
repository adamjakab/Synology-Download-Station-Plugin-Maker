<?php
namespace Plugins\SynoPluginLibrary;

/**
 *
 * @author jackisback
 *        
 */
class DlsPlugin
{

    /** @var bool */
    protected $debug = false;

    /** @var string     The default log file for debug output */
    protected $log_file = '/tmp/dls_userplugins.log';

    /** @var string     The class name is used to identify the plugin in the logs */
    protected $name = __CLASS__;

    /** @var string     The User-Agent header that is sent along with the request by curl. */
    protected $curl_user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4";

    /**
     * Constructor
     */
    public function __construct()
    {}

    /**
     * This method is called by BTSearch to prepare for finding results.
     *
     * @param resource $curl
     * @param string $query
     */
    public function prepare($curl, $query)
    {
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->curl_user_agent);
        $this->log(sprintf("Searching: %s", $query));
    }
    
    /**
     * Parses the loaded html and returns the number of results added to the plugin.
     * @param object $plugin
     * @param string $searchPageHtml
     * @return int
     */
    public function parse($plugin, $searchPageHtml)
    {
        return 0;
    }

    /**
     *
     * @param object $plugin
     * @param string $searchPageHtml
     */
    protected function log($msg)
    {
        if ($this->debug === true) {

            $msg = sprintf("[%s][%s] %s\r\n", date('Y-m-d H:i:s'), $this->name, $msg);
            print($msg);
            if (! is_null($this->log_file)) {
                file_put_contents($this->log_file, $msg, FILE_APPEND);
            }
        }
    }
}

