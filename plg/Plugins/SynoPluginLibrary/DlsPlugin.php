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
    //protected $curl_user_agent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:100.0) Gecko/20100101 Firefox/100.0";
    
    /** @var array      Curl options to be set */
    protected $curl_options = [
        CURLOPT_FAILONERROR =>      TRUE,
        CURLOPT_RETURNTRANSFER =>   TRUE,
        CURLOPT_CONNECTTIMEOUT =>   3,
        CURLOPT_TIMEOUT =>          30, 
        CURLOPT_USERAGENT =>        "SynoDLSSearchPlugin/0.2 (https://github.com/adamjakab/Synology-Download-Station-Plugin-Maker)",
    ];
    
    /** @var array      Curl options to be set */
    protected $curl_headers = [
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"
    ];

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
        $this->curl_options[CURLOPT_HTTPHEADER] = $this->curl_headers;
        curl_setopt_array( $curl, $this->curl_options );
        $this->log(sprintf("Searching(%s): '%s'", $query, $this->curl_options[CURLOPT_URL]));
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

