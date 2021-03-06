<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginCommandHelper;

class PluginListCommand extends ConsoleCommand
{

    protected static $defaultName = 'plugin:list';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription("List all plugins");
        $this->setHelp("List all available plugins.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Available plugins: " . implode(", ", PluginCommandHelper::getPluginNames()));
        return ConsoleCommand::SUCCESS;
    }
}
