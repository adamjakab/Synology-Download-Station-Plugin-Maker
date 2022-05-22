<?php
namespace Plugins\SynoPluginLibrary;


/**
 * This class is just a placeholder so the plugin is valid and can be imported.
 */
class SynoPluginLibrary
{

    public function prepare($curl, $query)
    {
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
    }

    public function parse($plugin, $response)
    {
        return 0;
    }
}
