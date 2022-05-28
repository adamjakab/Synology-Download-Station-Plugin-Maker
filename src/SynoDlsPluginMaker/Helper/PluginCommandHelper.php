<?php
namespace SynoDlsPluginMaker\Helper;

use Symfony\Component\String\UnicodeString;

class PluginCommandHelper
{
    /** @var string */
    //public static $plugin_class_name_pattern = "DlsSearchPlugin";
    
    /** @var string */
    public static $plugins_root_path = "plg";
    
    /** @var string */
    public static $plugins_folder_name = "Plugins";
    
    /** @var string */
    public static $plugin_class_name_suffix = "DlsSearchPlugin";

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
        $search_pattern = self::getPluginsFolder() . "/**/*" . self::$plugin_class_name_suffix . ".php";
        $file_list = glob($search_pattern);
        foreach ($file_list as $plugin_file_path) {
            $plugin_folder_path = dirname($plugin_file_path);
            $plugin_info_path = sprintf("%s/INFO", $plugin_folder_path);
            try {
                $plugin_info = self::getPluginInfoDatafromPath($plugin_info_path);
            } catch (\Exception $e) {
                continue;
            }
            
            $plugin_name = $plugin_info["name"];
            //$plugin_folder = self::getFolderNameFromPluginName($plugin_name);
            $class_name = self::getClassNameFromPluginName($plugin_name);
            $plugin_namespace = self::getNamespacedClassNameFromPluginName($plugin_name);
            
//             print("plugin path: " . $plugin_path . "\n");
//             print("class name: " . $class_name . "\n");
//             print("plugin folder: " . $plugin_folder . "\n");
//             print("plugin name: " . $plugin_name . "\n");
//             print("plugin namespace: " . $plugin_namespace . "\n");

            $answer[] = [
                "name" => $plugin_name,
                "classname" => $class_name,
                "namespace" => $plugin_namespace,
                "file_path" => $plugin_file_path,
                "folder_path" => $plugin_folder_path
            ];
        }

        return $answer;
    }
    
    
    
    public static function getPluginInfoDatafromPath($path)
    {
        if(!is_file($path)) {
            throw \Exception("Info file not found!");
        }
        $info_content = file_get_contents($path);
        $info_data = json_decode($info_content, true);
        return $info_data;
    }
    
    public static function getPluginFolderPath($plugin_name) {
        $plugins_folder = self::getPluginsFolder();
        $folder_name = self::getFolderNameFromPluginName($plugin_name);
        return (sprintf("%s/%s", $plugins_folder, $folder_name));
    }
    
    public static function getPluginsFolder()
    {
        return (sprintf("%s/%s/%s", $GLOBALS["project_folder"], self::$plugins_root_path, self::$plugins_folder_name));
    }
    
    /**
     * Module is the search plugin file loaded by DLS
     * @param string $name
     * @return string
     */
    public static function getModuleNameFromPluginName(string $name) {
        $class_name = self::getClassNameFromPluginName($name);
        return sprintf("%s.php", $class_name);
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public static function getNamespacedClassNameFromPluginName(string $name) {
        $folder_name = self::getFolderNameFromPluginName($name);
        $class_name = self::getClassNameFromPluginName($name);
        //\\Plugins\\Dummy\\DummyDlsSearchPlugin
        return sprintf("\\%s\\%s\\%s", self::$plugins_folder_name, $folder_name, $class_name);
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public static function getClassNameFromPluginName(string $name) {
        $folder_name = self::getFolderNameFromPluginName($name);
        return sprintf("%s%s", $folder_name, self::$plugin_class_name_suffix);
    }
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public static function getFolderNameFromPluginName(string $name) {
        $u = new UnicodeString($name);
        return $u->lower()->camel()->title();
    }
}

