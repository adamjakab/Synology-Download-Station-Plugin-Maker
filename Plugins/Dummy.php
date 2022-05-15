<?php
namespace Plugins;

/**
 * The autoloader will make sure load the user classes.
 * This must be required and regisitered before any use statements.
 */
require_once 'SynoPluginHelper/Autoloader.php';
SynoPluginHelper\Autoloader::register();

use Plugins\SynoPluginHelper\DlmPlugin;
use Plugins\SynoPluginHelper\DlmPluginInterface;

/**
 *
 * @author jackisback
 *        
 */
class Dummy extends DlmPlugin implements DlmPluginInterface
{

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->debug = true;
        $this->name = "dummy";
        $this->log(sprintf("Plugin class '%s' constructed: %s", $this->name, __CLASS__));
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \SynoPluginHelper\DlmPluginInterface::prepare()
     */
    public function prepare($curl, $query)
    {
        
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \SynoPluginHelper\DlmPluginInterface::parse()
     */
    public function parse($plugin, $searchPageHtml)
    {
        
    }
}

