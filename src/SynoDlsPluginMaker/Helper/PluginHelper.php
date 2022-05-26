<?php
namespace SynoDlsPluginMaker\Helper;

class PluginHelper
{
    /** @var string */
    public static $plugin_class_name_pattern = "DlsSearchPlugin";
    
    /** @var string */
    public static $plugins_root_path = "plg";
    
    /** @var string */
    public static $plugins_folder_name = "Plugins";
    
    

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
        $search_pattern = self::getPluginsFolder() . "/**/*" . self::$plugin_class_name_pattern . ".php";
        $file_list = glob($search_pattern);
        foreach ($file_list as $plugin_path) {
            /** @todo: we can use unsderscores and Camel case for better Class name conversion from file name */
            //$name = strtolower(str_replace(self::getPluginsFolder() . "/", "", $plugin_path));
            $class_name = basename($plugin_path, ".php");
            $plugin_folder = str_replace(self::$plugin_class_name_pattern, "", $class_name);
            $plugin_name = strtolower($plugin_folder);
            $plugin_namespace = sprintf("%s\\%s", self::$plugins_folder_name, $plugin_folder);
            
//             print("class name: " . $class_name . "\n");
//             print("plugin name: " . $plugin_name . "\n");
//             print("plugin namespace: " . $plugin_namespace . "\n");

            $answer[] = [
                "name" => $plugin_name,
                "classname" => $class_name,
                "namespace" => $plugin_namespace,
                "path" => $plugin_path
            ];
        }

        return $answer;
    }
    
    public static function getPluginsFolder()
    {
        return (sprintf("%s/%s/%s", $GLOBALS["project_folder"], self::$plugins_root_path, self::$plugins_folder_name));
    }
}

