<?php
namespace Build;

/**
 *
 * @author jackisback
 *        
 */
class Plugin
{
    /**
     *
     * @var array
     */
    private $results = [];
    
    public function addResult($title, $download, int $size, $datetime, $page, $hash, $seeds, $leechs, $category) {
        array_push($this->results, [
            "title" => $title,
            "download" => $download,
            "size" => $size,
            "datetime" => $datetime,
            "page" => $page,
            "hash" => $hash,
            "seeds" => $seeds,
            "leechs" => $leechs,
            "category" => $category,
        ]);
    }
    
    public function getResults() {
        return $this->results;
    }
}

