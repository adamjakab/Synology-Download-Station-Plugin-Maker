<?php
namespace SynologyDlsPlugins\Helper;

class PluginHelper
{

    public static function getPluginsFolder()
    {
        return $GLOBALS["project_folder"] . "/Plugins";
    }
    
    

    /**
     * Returns the list of available pluigin names
     * @return string[]
     */
    public static function getPluginNames()
    {
        $answer = [];
        $list = self::getPluginList();
        foreach ($list as $item) {
            $answer[] = $item["name"];
        }
        
        return $answer;
    }
    
    /**
     *
     * @param string $name
     * @return NULL|[]
     */
    public static function getPluginListItemByName($name) {
        $answer = null;
        $list = self::getPluginList();
        foreach ($list as $item) {
            if($item["name"] == $name) {
                $answer = $item;
                break;
            }
        }
        
        return $answer;
    }

    /**
     * Returns a name/path array for each plugin
     * @return mixed[][]
     */
    public static function getPluginList()
    {
        $answer = [];
        $search_pattern = self::getPluginsFolder() . "/*.php";
        $file_list = glob($search_pattern);
        foreach ($file_list as $plugin_path) {
            /** @todo: we can use unsderscores and Camel case for better Class name conversion from file name */
            $name = strtolower(str_replace(self::getPluginsFolder() . "/", "", $plugin_path));
            $name = str_replace(".php", "", $name);
            $answer[] = [
                "name" => $name,
                "classname" => ucfirst($name),
                "namespace" => "Plugins",
                "path" => $plugin_path
            ];
        }

        return $answer;
    }
}

