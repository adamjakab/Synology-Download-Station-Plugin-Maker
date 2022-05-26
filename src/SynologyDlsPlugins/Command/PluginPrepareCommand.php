<?php
namespace SynologyDlsPlugins\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynologyDlsPlugins\Helper\PluginHelper;


class PluginPrepareCommand extends ConsoleCommand
{
    protected static $defaultName = 'plugin:prepare';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this->setDescription("Prepare a plugin");
        $this->setHelp("Select a plugin and prepare it.");
        
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
        
        $output->writeln(sprintf("Preparing plugin: %s...", $plugin_name));
        

        return ConsoleCommand::SUCCESS;
    }
}
