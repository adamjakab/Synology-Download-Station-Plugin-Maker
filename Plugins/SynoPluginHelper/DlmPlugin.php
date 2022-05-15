<?php
namespace SynoPluginHelper;

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
    protected $log_file = null;
    
    /**
     * 
     * @var string
     */
    protected $name = null;
    
    /**
     */
    public function __construct()
    {}
    
    protected function log($msg)
    {
        if ($this->debug === true) {
            $msg = sprintf("[%s] %s\r\n", date('Y-m-d h:i:s'), $msg);
            print($msg);
            if (!is_null($this->log_file)) {
                file_put_contents($this->log_file, $msg, FILE_APPEND);
            }
        }
    }
}

