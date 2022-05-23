<?php
namespace SynoPluginHelper;

/**
 * 
 * @author jackisback
 *
 */
class Autoloader
{
    private static $base_path;
    
    private static $loaded;
    
    /**
     * 
     * @param string $base_path Class names will be prefixed with this path if set
     */
    public static function register($base_path = null)
    {
        self::$base_path = $base_path ? $base_path : dirname(__FILE__);
        
        spl_autoload_register(function ($class) {
            $file = self::$base_path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            print(sprintf("Autoload (Class name: %s): %s \n\r",$class, $file));
            
            if (file_exists($file)) {
                self::$loaded[] = $file;
                require $file;
                return true;
            }
            
            return false;
        });
    }
}
