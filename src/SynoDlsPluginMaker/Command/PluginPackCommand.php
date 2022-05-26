<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginHelper;

class PluginPackCommand extends ConsoleCommand
{

    protected static $defaultName = 'plugin:pack';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription("Pack a plugin");
        $this->setHelp("Package a plugin ready to be used on SDM.");
        
        $this->addArgument('plugin_name', InputArgument::REQUIRED, 'Plugin name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plugin_name = $input->getArgument('plugin_name');
        $output->writeln(sprintf("Packaging plugin: %s...", $plugin_name));
        return ConsoleCommand::SUCCESS;
    }
}
