<?php
namespace Plugins\SynoPluginHelper;

/**
 *
 * @author jackisback
 *        
 */
class DlmPlugin
{
    /**
     * @var bool
     */
    protected $debug = false;
    
    /**
     * 
     * @var string
     */
    protected $log_file = '/tmp/dlm_userplugins.log';
    
    /**
     * 
     * @var string
     */
    protected $name = __CLASS__;
    
    /**
     */
    public function __construct()
    {}
    
    public function prepare($curl, $query)
    {
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4');
        $this->log(sprintf("Searching: %s", $query));
    }
    
    protected function log($msg)
    {
        if ($this->debug === true) {
            
            $msg = sprintf("[%s][%s] %s\r\n", date('Y-m-d H:i:s'), $this->name, $msg);
            print($msg);
            if (!is_null($this->log_file)) {
                file_put_contents($this->log_file, $msg, FILE_APPEND);
            }
        }
    }
}

