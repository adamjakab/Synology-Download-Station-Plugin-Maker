<?php
namespace Plugins\Dummy;
/**
 * The autoloader must be registered before any use statements.
 * @see /console.php
 */
$pluginsBasePath = dirname(__DIR__);
require_once $pluginsBasePath . '/SynoPluginLibrary/Autoloader.php';
\Plugins\SynoPluginLibrary\Autoloader::register(["Plugins\\" => $pluginsBasePath]);
/* ------------------------------------------------------------------------------ */

use Plugins\SynoPluginLibrary\DlsPlugin;
use Plugins\SynoPluginLibrary\DlsPluginInterface;

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
        DummyHelper::Help();
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see DlsPluginInterface::prepare()
     */
    public function prepare($curl, $query)
    {
        parent::prepare($curl, $query);
        $searchurl = sprintf("http://localhost/?s=%s", urlencode($query));
        curl_setopt($curl, CURLOPT_URL, $searchurl);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see DlsPluginInterface::parse()
     */
    public function parse($plugin, $searchPageHtml)
    {
        parent::parse($plugin, $searchPageHtml);
        $this->log("Parsing...");
    }
}

