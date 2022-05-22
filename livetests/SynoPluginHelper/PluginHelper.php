<?php
namespace Plugins\SynoPluginHelper;

use DateTime;
use Exception;

/**
 * 
 * @author jackisback
 *
 */
class PluginHelper
{
    private static $categories = [
        "Movie" => ["Movie", "BDRiP"],
        "TV Show" => ["TW Show", "Serie TV"],
        "Music" => ["Music", "Musica"],
        "Application" => ["App Win", "App Mac"],
    ];
    
    
    private static function getResourceCategory($category) {
        $answer = "Other";
        foreach (self::$categories as $categoryName => $categoryValues) {
            if (in_array($category, $categoryValues)) {
                $answer = $categoryName;
                break;
            }
        }
        return $answer;
    }
    
    /**
     * @param string $source
     * @param string $name
     * @param string $link
     * @param string $size
     * @param string $unit
     * @param string $seeds
     * @param string $leechers
     * @param string $time
     * @param string $category
     * @param string $enclosure_url
     * @return SearchLink
     * @throws Exception
     */
    public static function getSearchLink($source, $name, $link, $size, $unit, $seeds, $leechers, $time, $category, $enclosure_url = null) {
        $sl = new SearchLink();
        
        // Source
        if (empty($source)) {
            throw new \Exception("SearchLink: Undefined source!");
        }
        $sl->src = $source;
        
        // Name
        $name = trim(strip_tags($name));
        if (empty($name)) {
            throw new \Exception("SearchLink: Undefined name!");
        }
        $sl->name = $name;
        
        //Hash
        $sl->hash = md5($name);
        
        // Link: points to the detail page on which the resource is found
        $link = trim($link);
        if (empty($link)) {
            throw new \Exception("SearchLink: Undefined link!");
        }
        $sl->link = $link;
        
        // Size
        $size = !empty($size) ? floatval($size) : 0;
        $unit = !empty($unit) ? trim($unit) : "";
        $sl->size  = $size * self::UnitSize($unit);
        
        // Seeds
        $sl->seeds = intval($seeds);
        
        // Peers
        $sl->peers = intval($leechers);
        
        // Time
        if (!empty($time)) {
            try {
                if ($time instanceof DateTime) {
                    $sl->time = $time;
                } else {
                    $sl->time = new DateTime($time);
                }
            } catch (Exception $e) {
                $sl->time = new DateTime();
            }
        }
        
        // Category
        $category = !empty($category) ? trim($category) : "Unknown";
        $sl->category = self::getResourceCategory($category);
        
        // Enclosure URL: The link to the resource file to be downloaded
        if (!empty($enclosure_url)) {
            $sl->enclosure_url = $enclosure_url;
        }
        
        // Test
        $sl->test = "Jack";
        
        return $sl;
    }
    
    /**
     * @todo: Needs 1024 based figures
     * 
     * @param string $unit
     * @return int
     */
    public static function UnitSize($unit) {
        switch (strtoupper(trim($unit))) {
            case "KB": return pow(2, 10);
            case "MB": return pow(2, 20);
            case "GB": return pow(2, 30);
            case "TB": return pow(2, 40);
            default: return 1;
        }
    }
}
