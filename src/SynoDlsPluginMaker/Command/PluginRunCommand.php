<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginHelper;


class PluginRunCommand extends ConsoleCommand
{
    protected static $defaultName = 'plugin:run';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this->setDescription("Run a plugin");
        $this->setHelp("Select a plugin and run it.");
        
        $this->addArgument('plugin_name', InputArgument::REQUIRED, 'Plugin name');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plugin_name = $input->getArgument('plugin_name');
        $pluginListItem = PluginHelper::getPluginListItemByName($plugin_name);
        if (!$pluginListItem) {
            $output->writeln(sprintf("The requested plugin('%s') does not exist!", $plugin_name));
            return ConsoleCommand::FAILURE;
        }
        
        
        
        $output->writeln(sprintf("Requested plugin: %s: %s", $pluginListItem["name"], $pluginListItem["path"]));
        

        return ConsoleCommand::SUCCESS;
    }
}
