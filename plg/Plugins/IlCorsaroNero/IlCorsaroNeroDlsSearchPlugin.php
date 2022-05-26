<?php
namespace Plugins\IlCorsaroNero;
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
 * IlCorsaroNero DLS Search Plugin 
 */
class IlCorsaroNeroDlsSearchPlugin extends DlsPlugin implements DlsPluginInterface
{
    protected $debug = true;
    protected $name = __CLASS__;
    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->log("Constructed.");
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see DlsPluginInterface::prepare()
     */
    public function prepare($curl, $query)
    {
        parent::prepare($curl, $query);
        $searchurl = "https://example.com/?s=%s";
        $searchurl = sprintf($searchurl, urlencode($query));
        curl_setopt($curl, CURLOPT_URL, $searchurl);
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

