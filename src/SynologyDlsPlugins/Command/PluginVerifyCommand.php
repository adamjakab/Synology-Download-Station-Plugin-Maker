<?php
namespace SynologyDlsPlugins\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use ReflectionProperty;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynologyDlsPlugins\Helper\PluginHelper;

class PluginVerifyCommand extends ConsoleCommand
{

    protected static $defaultName = 'plugin:verify';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription("Verify a plugin");
        $this->setHelp("Verify a plugin.");
        
        $this->addArgument('plugin_name', InputArgument::REQUIRED, 'Plugin name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plugin_name = $input->getArgument('plugin_name');
        $output->writeln(sprintf("Verifying plugin: %s...", $plugin_name));

        $pluginListItem = PluginHelper::getPluginListItemByName($plugin_name);
        if (!$pluginListItem) {
            $output->writeln(sprintf("The requested plugin('%s') does not exist!", $plugin_name));
            return ConsoleCommand::FAILURE;
        }
        
        $output->writeln(sprintf("Plugin info: %s", json_encode($pluginListItem)));
        
        $output->writeln(sprintf("Loading Plugin file: %s", $pluginListItem["path"]));
        require_once $pluginListItem["path"];
        
        // Create Plugin Instance
        $fcc = $pluginListItem["namespace"] . '\\' . $pluginListItem["classname"];
        $output->writeln(sprintf("Registering class: %s...", $fcc));
        $plugin = new \ReflectionClass($fcc);
        $pluginInstance = $plugin->newInstance();
        
        // Check if class implements Plugins\SynoPluginLibrary\DlsPluginInterface
        
        
        //Check Property: name
        if (!$plugin->hasProperty("name")) {
            $output->writeln(sprintf("Property '%s' is missing", $plugin_name));
            return ConsoleCommand::FAILURE;
        }
        
        $props = $plugin->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        foreach ($props as $prop) {
            $prop->setAccessible(true);
            $output->writeln(sprintf("Property '%s' = '%s'", $prop->getName(), $prop->getValue($pluginInstance)));
            //print $prop->getName() . "\n";
            //$prop->setAccessible(true);
            //print $prop->getValue($pluginInstance) . "\n";
        }
        
        
        /* The INFO File content
         {
            "name": "ilcorsaronero",
            "displayname": "Il Corsaro Nero",
            "description": "Modded by jack",
            "version": "1.1",
            "site": "http://ilcorsaronero.in",
            "module": "search.php",
            "type": "search",
            "class": "SynoDLMSearchSMKilcorsaronero" 
            }
         */
        
        
        //$plugin->newInstance();
        $output->writeln(sprintf("Plugin '%s' OK.", $plugin_name));
        
        
        return ConsoleCommand::SUCCESS;
    }
}
