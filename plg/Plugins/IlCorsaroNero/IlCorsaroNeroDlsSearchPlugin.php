<?php
namespace Plugins\IlCorsaroNero;

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

use DateTime;
use Plugins\SynoPluginLibrary\DlsPlugin;
use Plugins\SynoPluginLibrary\DlsPluginInterface;
use Plugins\SynoPluginLibrary\SearchLink;

/**
 * IlCorsaroNero DLS Search Plugin
 */
class IlCorsaroNeroDlsSearchPlugin extends DlsPlugin implements DlsPluginInterface
{

    protected $debug = true;

    protected $name = __CLASS__;
    
    protected $baseurl = "https://www.ilcorsaronero.in";
    
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
        $searchurl = "https://www.ilcorsaronero.in/argh?search=%s";
        $searchurl = sprintf($searchurl, urlencode($query));
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
        $resultCount = 0;
        
        $searchLinks = $this->elaborateSearchPage($searchPageHtml);
        $resultCount = count($searchLinks);
        
        if($resultCount) {
            //$this->log(json_encode($searchLinks[0]->getDataArray(), JSON_PRETTY_PRINT));
            foreach ($searchLinks as &$searchLink) {
                $this->elaborateDetailPage($searchLink);
                $searchLink->feedDataToPlugin($plugin);
            }
        }
        
        return $resultCount;
    }
    
    private function elaborateDetailPage(SearchLink $searchLink) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_REFERER, $this->baseurl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_options[CURLOPT_TIMEOUT]);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->curl_options[CURLOPT_USERAGENT]);
        curl_setopt($ch, CURLOPT_URL, $searchLink->getSourceUrl());
        $response = curl_exec($ch);
        $header = curl_getinfo( $ch );
        $errno = curl_errno( $ch );
        curl_close($ch);
        
        if ($errno == 0 && $header['http_code'] == 200) {
            //Extract magnet link
            $m = [];
            $pattern = '#' . '<a class="forbtn magnet" href="(?P<magnet>magnet:\?xt=urn:btih:[^"]*)"' . '#siU';
            preg_match_all($pattern, $response, $m);
            if (isset($m["magnet"][0]) && ! empty($m["magnet"][0])) {
                $searchLink->setDownloadUrl($m["magnet"][0]);
                $searchLink->setHash(md5($searchLink->getDownloadUrl()));
            }
        }
    }
    
    /**
     *
     * @param string $body
     * @param number $limit
     * @return array|SearchLink[]
     */
    private function elaborateSearchPage($body, $limit = 10)
    {
        $found = [];
        $m = null;
        
        // https://regex101.com/r/hlpF41/1
        $regex = '<tr class="(odd|odd2)".*<a [^>]*>(?<category>.*)</td>.*<a class="tab" HREF="(?<link>.*)" >(?<name>.*)</A>.*<td.*>(?P<size>[0-9.]*) (?P<unit>[a-zA-Z]*)</font>.*>(?<time>[0-9]{2}\.[0-9]{2}\.[0-9]{2}).*>(?<seeds>[0-9]{1,})<.*>(?<leeches>[0-9]{1,})</font></td>\s*</TR>';
        preg_match_all("#$regex#siU", $body, $m);
        //$this->log("Matches: " . var_dump($matches));
        
        if (! $m || ($len = count($m["name"])) == 0) {
            return $found;
        }
        
        //ICN has a "match any" search matching even only one search term
        
        for ($i = 0; $i < $len; $i++) {
            try {
                $itemName = $m["name"][$i];
                if($this->areAllWordsContainedInHaystack($this->query, $itemName)) {
                    $sl = new SearchLink();
                    $sl->setSource("ICN");
                    $sl->setName($m["name"][$i]);
                    $sl->setSourceUrl($this->baseurl . $m["link"][$i]);
                    //$sl->setDownloadUrl();
                    //$sl->setHash();
                    $sl->setSize($m["size"][$i].$m["unit"][$i]);
                    $sl->setDateTime(DateTime::createFromFormat('d.m.y', $m["time"][$i]));
                    $sl->setSeeds($m["seeds"][$i]);
                    $sl->setPeers($m["leeches"][$i]);
                    $sl->setCategory($m["category"][$i]);
                    $found[] = $sl;
                }
            } catch (\Exception $e) {
                $this->log("Bad Search Link! " . $e->getMessage());
                continue;
            }
            
            if (count($found) >= $limit) {
                break;
            }
        }
        
        return $found;
    }
    
    function areAllWordsContainedInHaystack(string $wordlist, string $haystack) {
        $searchwords = explode(" ", strtolower($wordlist));        
        //build regex pattern: '#(?=.*better)(?=.*multi)#i'
        $pattern = "";
        foreach ( $searchwords as $searchword) {
            $pattern .= sprintf("(?=.*%s)", $searchword);
        }
        $pattern = sprintf("#%s#", $pattern);
        
        $haystack = strtolower($haystack);
        $this->log(sprintf("searching for: ('%s') in '%s'", $pattern, $haystack));
        return preg_match_all($pattern, $haystack);
    }
}

