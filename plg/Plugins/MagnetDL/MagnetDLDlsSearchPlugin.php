<?php
namespace Plugins\MagnetDL;

/**
 * The autoloader must be registered before any use statements.
 *
 * @see /console.php
 */
$pluginsBasePath = dirname(__DIR__);
require_once $pluginsBasePath . '/SynoPluginLibrary/Autoloader.php';
\Plugins\SynoPluginLibrary\Autoloader::register([
    "Plugins\\" => $pluginsBasePath
]);
/* ------------------------------------------------------------------------------ */

use Plugins\SynoPluginLibrary\DlsPlugin;
use Plugins\SynoPluginLibrary\DlsPluginInterface;
use Plugins\SynoPluginLibrary\SearchLink;

/**
 * MagnetDL DLS Search Plugin
 */
class MagnetDLDlsSearchPlugin extends DlsPlugin implements DlsPluginInterface
{

    protected $debug = true;

    protected $name = __CLASS__;

    protected $baseurl = "https://www.magnetdl.com";

    protected $min_seeds = 3;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->log("Constructed.");
    }

    /**
     *
     * {@inheritdoc}
     * @see DlsPluginInterface::prepare()
     */
    public function prepare($curl, $query)
    {
        $searchurl = $this->baseurl . "/%s/%s/se/desc";
        $search_path = strtolower(str_replace(" ", "-", $query));
        $searchurl = sprintf($searchurl, $search_path[0], $search_path);
        $this->curl_options[CURLOPT_URL] = $searchurl;
        parent::prepare($curl, $query);
    }

    /**
     *
     * {@inheritdoc}
     * @see DlsPluginInterface::parse()
     */
    public function parse($plugin, $searchPageHtml)
    {
        parent::parse($plugin, $searchPageHtml);
        $this->log(sprintf("Parsing(contenth length: %s)...", strlen($searchPageHtml)));

        $containerHtml = $this->getContainer($searchPageHtml);
        $resultsHtml = $this->getResultBlocks($containerHtml);

        foreach ($resultsHtml as $res) {
            if (intval($res["seeds"]) >= $this->min_seeds) {
                $sl = new SearchLink();
                $sl->setSource("MDL");
                $sl->setName($res["title"]);
                $sl->setSourceUrl($this->baseurl . $res["source_url"]);
                $sl->setDownloadUrl($res["download_url"]);
                $sl->setHash(md5($res["download_url"]));
                $sl->setSize($res["filesize"]);
                // $sl->setDateTime($res["age"]);
                $sl->setSeeds($res["seeds"]);
                $sl->setPeers($res["leeches"]);
                $sl->setCategory($res["category"]);

                // $this->log(json_encode($sl->getDataArray(), JSON_PRETTY_PRINT));
                $sl->feedDataToPlugin($plugin);
            }
        }

        return count($resultsHtml);
    }

    /**
     * Regex: https://regex101.com/r/k209gf/1
     */
    protected function getResultBlocks($html)
    {
        $answer = false;
        $m = [];
        $regex = '<tr><td class="m"><a href="(?P<download_url>magnet:\?xt=urn:btih:[^"]*)" [^>]*>.*</td><td class="n"><a href="(?P<source_url>[^"]*)".*title="(?P<title>[^"]*)">.*</td><td>(?P<age>[^<]*)</td><td class="t2">(?P<category>[^<]*)</td><td>(?P<filecount>[^<]*)</td><td>(?P<filesize>[^<]*)</td><td class="s">(?P<seeds>[^<]*)</td><td class="l">(?P<leeches>[^<]*)</td>.*</tr>';
        if (preg_match_all("#$regex#siU", $html, $m, PREG_SET_ORDER)) {
            $answer = $m;
        } else {
            $this->log("No result block match!");
        }

        return $answer;
    }

    protected function getContainer($html)
    {
        $answer = false;
        $m = [];
        $regex = '<table class="download">.*</table>';
        if (preg_match("#$regex#siU", $html, $m)) {
            $answer = $m[0];
        } else {
            $this->log("No container match!");
        }
        return $answer;
    }
}

