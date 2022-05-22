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

