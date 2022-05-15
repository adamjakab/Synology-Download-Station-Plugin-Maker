<?php
namespace SynoPluginHelper;

use DateTime;

/**
 *
 * @author jackisback
 *        
 */
final class SearchLink
{
    /** @var string The name of the plugin used to find the resource */
    public $src = "";
    
    /** @var string The name of the item */
    public $name = "";
    
    /** @var string The URL of the page where the resource was found */
    public $link = "";
    
    /** @var string The URL from where the resource can be fetched from */
    public $enclosure_url = "";
    
    /** @var string The hash of the resource */
    public $hash = "";
    
    /** @var integer The size in bytes of the resource */
    public $size = 0;
    
    /** @var DateTime The date and time of the resource */
    public $time;
    
    /** @var integer The number of seeds avalable */
    public $seeds = 0;
    
    /** @var integer The number of peers leaching on the same resource */
    public $peers = 0;
    
    /** @var string The category where this fits */
    public $category = "";
    
    /**
     * 
     * @return string
     */
    public function getISO8601Date() {
        return $this->time->format(DateTime::ATOM);
    }
}
