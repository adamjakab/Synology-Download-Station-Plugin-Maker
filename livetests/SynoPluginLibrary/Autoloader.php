<?php
namespace Plugins\SynoPluginLibrary;

/**
 * Autoloader for DLS search plugins.
 *
 * @author Adam Jakab (adam at jakab dot pro)
 *        
 */
class Autoloader
{

    /** @var array  namespace(key) to path(value) mapping array */
    private static $namespacemap;

    private static $do_file_logging = true;

    private static $do_stdout_logging = true;

    /**
     * Class names ending with this pattern will not be autoloaded.
     * All plugin classes must be named with this suffix and they must not be
     * autoloaded otherwise BTSearch will bail not run them.
     *
     * @var string
     */
    private static $plugin_class_name_pattern = "DlsSearchPlugin";

    /**
     *
     * @param string $base_path
     *            Class names will be prefixed with this path if set
     */
    public static function register($namespacemap = [])
    {
        self::registerNamespaceMap($namespacemap);

        spl_autoload_register(function ($class) {
            if (substr($class, (strlen(self::$plugin_class_name_pattern) * - 1)) !== self::$plugin_class_name_pattern) {
                $file = self::getFilePathForClass($class);
                if ($file) {
                    $msg = sprintf("Loading (Class name: %s): %s", $class, $file);

                    self::log($msg);

                    if (file_exists($file)) {
                        include $file;
                        return true;
                    } else {
                        self::log("File Not Found! " . $file);
                        // throw new \Exception("File Not Found! " . $file);
                    }
                } else {
                    self::log("No registered namespace fits the required class: " . $class);
                }
            }

            return false;
        });
    }

    private static function getFilePathForClass($class)
    {
        $answer = false;
        // [Autoloader] Registered namespace map: {"Plugins\\":"\/volume1\/@appconf\/DownloadStation\/download\/userplugins"}
        foreach (self::$namespacemap as $ns => $path) {
            if (substr($class, 0, strlen($ns)) === $ns) {
                $classNoNsRoot = substr($class, strlen($ns));
                self::log("classNoNsRoot: " . $classNoNsRoot);
                $answer = $path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classNoNsRoot) . '.php';
                break;
            }
        }

        return $answer;
    }

    private static function registerNamespaceMap($namespacemap = [])
    {
        if (empty($namespacemap)) {
            $base_path = dirname(__DIR__);
            $namespace = "//";
            $namespacemap[] = [
                $namespace => $base_path
            ];
        }
        self::log("Registered namespace map: " . json_encode($namespacemap));
        self::$namespacemap = $namespacemap;
    }

    private static function log($msg)
    {
        $msg = "[Autoloader] " . $msg . "\r\n";

        if (self::$do_stdout_logging) {
            print($msg);
        }

        if (self::$do_file_logging) {
            $log_file = '/tmp/dls_userplugins_autoloader.log';
            file_put_contents($log_file, $msg, FILE_APPEND);
        }
    }
}

