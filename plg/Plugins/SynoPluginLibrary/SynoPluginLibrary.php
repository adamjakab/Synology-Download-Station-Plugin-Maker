<?php
namespace Plugins\SynoPluginLibrary;


/**
 * This class is just a placeholder so the SynoPluginLibrary is considered
 * as a valid plugin and hence can be imported.
 */
class SynoPluginLibrary
{

    public function prepare($curl, $query)
    {
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        $searchurl = "https://do.not.search.me.com";
        curl_setopt($curl, CURLOPT_URL, $searchurl);
    }

    public function parse($plugin, $response)
    {
        return 0;
    }
}
