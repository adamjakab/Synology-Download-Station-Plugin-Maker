<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginHelper;


class PluginCreateCommand extends ConsoleCommand
{
    protected static $defaultName = 'plugin:create';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this->setDescription("Create a new plugin");
        $this->setHelp("Create a new plugin.");
        
        $this->addArgument('plugin_name', InputArgument::REQUIRED, 'Plugin name');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plugin_name = $input->getArgument('plugin_name');
        $pluginListItem = PluginHelper::getPluginListItemByName($plugin_name);
        if ($pluginListItem) {
            $output->writeln(sprintf("A plugin with this name('%s') already exists!", $plugin_name));
            return ConsoleCommand::FAILURE;
        }
        
        $output->writeln(sprintf("Creating new plugin: %s...", $plugin_name));
        

        return ConsoleCommand::SUCCESS;
    }
}
