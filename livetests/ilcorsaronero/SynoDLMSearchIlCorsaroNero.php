<?php
require 'SearchLink.php';
require 'PluginHelper.php';

class SynoDLMSearchIlCorsaroNero
{

    /** @var string */
    private $site_uri = 'http://ilcorsaronero.in';

    /** @var string */
    private $search_uri = '/argh.php?search=%s';

    /** @var string */
    private $curl_user_agent = 'SynoDLMSearch/0.1 (https://github.com/adamjakab ::: Repo not yet published)';

    /** @var integer */
    private $search_limit = 1;

    /** @var boolean Enable this if you want to log to a temporary file */
    private $debug = true;

    public function __construct()
    {
        $this->log("IlCorsaroNero Ready...");
        $configStr = file_get_contents(dirname(__FILE__) . '/config.json');
        $config = json_decode($configStr, true);
        $this->log("Config: " . json_encode($config));
        $this->search_limit = $config["max_results"];
    }

    public function prepare($curl, $query)
    {
        $this->search_uri = sprintf($this->site_uri . $this->search_uri, urlencode($query));
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_REFERER, $this->site_uri . '/');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->curl_user_agent);
        curl_setopt($curl, CURLOPT_URL, $this->search_uri);
    }

    public function parse($plugin, $searchPageHtml)
    {
        $results = $this->ElaborateSearchPage($searchPageHtml, $this->search_limit);
        //$this->log("Search Links(1): " . var_dump($results));

        //load each resource detail page and update the search link
        if (count($results)) {
            foreach ($results as &$searchLink) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_REFERER, $this->search_uri);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                curl_setopt($ch, CURLOPT_USERAGENT, $this->curl_user_agent);
                curl_setopt($ch, CURLOPT_URL, $searchLink->link);
                $response = curl_exec($ch);
                curl_close($ch);
                $enclosure_url = $this->ElaborateDetailPage($response, $searchLink);
                //$searchLink->enclosure_url = $enclosure_url;
            }
        }
        //$this->log("Search Links(2): " . var_dump($results));

        $result_count = 0;
        if (count($results)) {
            foreach ($results as &$searchLink) {
                $plugin->addResult(
                    $searchLink->name, 
                    $searchLink->enclosure_url, 
                    $searchLink->size, 
                    $searchLink->getISO8601Date(), 
                    $searchLink->link, 
                    $searchLink->hash, 
                    $searchLink->seeds,
                    $searchLink->peers,
                    $searchLink->category
                );
                $result_count++;
            }
        }

        return $result_count;
    }


    /**
     * 
     * @param string $body
     * @param SearchLink $searchLink
     * @return void
     */
    public function ElaborateDetailPage($body, $searchLink)
    {
        $this->log("Elaborating detail page...");
        // $this->log("\n" . $body);

        //Extract magnet link
        $pattern = '#' . '<a class="forbtn magnet" href="(?P<magnet>magnet:\?xt=urn:btih:[^"]*)"' . '#siU';
        preg_match_all($pattern, $body, $matches);
        // $this->log("\nMagnet matches" . var_dump($matches));
        if (isset($matches["magnet"][0]) && ! empty($matches["magnet"][0])) {
            $searchLink->enclosure_url = $matches["magnet"][0];
        }
    }

    /**
     *
     * @param string $body
     * @param number $limit
     * @return array|SearchLink[]
     */
    private function ElaborateSearchPage($body, $limit = 10)
    {
        $this->log(sprintf("Elaborating search page(%s)...", $this->search_uri));
        $found = [];
        $matches = null;
        /*
        <tr class="odd">
        <td class="lista" align="center"><a class="red" href="/cat/1">BDRiP</a></td>
        <td align="left"><a class="tab" href="/tor/176386/Assassinio_sul_Nilo__2022_ITA_ENG___720p__HEVC_H265_AC3_5_1_Sub_Ita_Eng_">Assassinio sul Nilo (2022 ITA/ENG) [720p][HEVC-H265-AC3..</a> <span style="color:red"></span> 	</td><td align="center"><font size="-2" color="#FF6600">2.26 GB</font></td>
	    <td class="lista" align="center"><form action="/tor/176386/Assassinio_sul_Nilo__2022_ITA_ENG___720p__HEVC_H265_AC3_5_1_Sub_Ita_Eng_" method="post" target="_blank" class="action"><input type="submit" class="downarrow" name="cerca" value="f621afba22d4245d92b556d112dab4ae0d63a792" title="Download"><a href="/tor/176386/Assassinio_sul_Nilo__2022_ITA_ENG___720p__HEVC_H265_AC3_5_1_Sub_Ita_Eng_"><img src="/images/details.gif" class="details" title="Descrizione" alt="Descrizione"></a></form></td>
	    <td align="center"><font color="#669999">31.03.22</font></td>
	    <td align="center"><font color="#00CC00">51</font></td>	
	    <td align="center"><font color="#0066CC">4</font></td>
	    </tr>
        */
        $pattern = '#' . '<tr class="(odd|odd2)".*<a.*>(?<category>.*)</td>.*<a class="tab" HREF="(?<link>.*)" >(?<name>.*)</A>.*' . '<td.*>(?P<size>[0-9.]*) (?P<unit>[a-zA-Z]*)</font>' . '.*>(?<time>[0-9]{2}\.[0-9]{2}\.[0-9]{2}).*>(?<seeds>[0-9]{1,})<.*' . '>(?<leechers>[0-9]{1,})</font></td></TR>' . '#siU';
        preg_match_all($pattern, $body, $matches);
        //$this->log("Matches: " . var_dump($matches));
        
        if (! $matches || ($len = count($matches["name"])) == 0) {
            return $found;
        }

        for ($i = 0; $i < $len; $i++) {
            try {
                //15.08.21
                $itemDate = DateTime::createFromFormat('d.m.y', $matches["time"][$i]);
                
                $searchLink = PluginHelper::getSearchLink(
                    "ilcorsaronero", 
                    $matches["name"][$i], 
                    $this->site_uri . $matches["link"][$i], 
                    $matches["size"][$i], 
                    $matches["unit"][$i], 
                    $matches["seeds"][$i], 
                    $matches["leechers"][$i], 
                    $itemDate, 
                    $matches["category"][$i]
                );
            } catch (\Exception $e) {
                $this->log("Bad Search Link! " . $e->getMessage());
                continue;
            }
            $found[] = $searchLink;

            if (count($found) >= $limit) {
                break;
            }
        }

        return $found;
    }

    private function log($msg)
    {
        if ($this->debug === true) {
            $msg = date('Y-m-d h:i:s') . "> " . $msg . "\r\n";
            file_put_contents('/tmp/ilcorsaronero_search.log', $msg, FILE_APPEND);
            print($msg);
        }
    }
}


