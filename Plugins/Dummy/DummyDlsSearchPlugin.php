<?php
namespace Plugins\Dummy;
/**
 * The autoloader must be registered before any use statements.
 * @see /console.php
 */
if (!isset($GLOBALS["is_build_environment"])) {
    $pluginsBasePath = dirname(__DIR__);
    require $pluginsBasePath . '/SynoPluginLibrary/Autoloader.php';
    \Plugins\SynoPluginLibrary\Autoloader::register(["Plugins\\" => $pluginsBasePath]);
}
/* ------------------------------------------------------------------------------ */

use Plugins\SynoPluginLibrary\DlsPlugin;
use Plugins\SynoPluginLibrary\DlsPluginInterface;
//use Plugins\Dummy\DummyHelper;

/**
 *
 * @author jackisback
 *        
 */
class DummyDlsSearchPlugin extends DlsPlugin implements DlsPluginInterface
{
    protected $debug = true;
    protected $name = __CLASS__;
    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->log("Constructed.");
        //DummyHelper::Help();
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

