<?php
namespace Plugins\SynoPluginLibrary;

/**
 *
 * @author jackisback
 *        
 */
interface DlsPluginInterface
{
    /**
     * 
     * @param resource $curl
     * @param string $query
     */
    public function prepare($curl, $query);
    
    /**
     * 
     * @param object $plugin
     * @param string $searchPageHtml
     */
    public function parse($plugin, $searchPageHtml);
}

