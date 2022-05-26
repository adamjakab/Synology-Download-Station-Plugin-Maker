<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginCommandHelper;
use Symfony\Component\String\UnicodeString;

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

        $u = new UnicodeString($plugin_name);
        if ($plugin_name != $u->lower()) {
            $output->writeln(sprintf("A valid snake cased plugin name must be given! For example: '%s'.", $u->lower()));
            return ConsoleCommand::FAILURE;
        }

        $pluginListItem = PluginCommandHelper::getPluginListItemByName($plugin_name);
        if ($pluginListItem) {
            $output->writeln(sprintf("A plugin with this name('%s') already exists!", $plugin_name));
            return ConsoleCommand::FAILURE;
        }

        $plugin_folder_name = PluginCommandHelper::getFolderNameFromPluginName($plugin_name);

        $plugin_folder_path = PluginCommandHelper::getPluginFolderPath($plugin_name);
        if (is_dir($plugin_folder_path)) {
            $output->writeln(sprintf("A path for this plugin already exists! %s", $plugin_folder_path));
            return ConsoleCommand::FAILURE;
        }

        $output->writeln(sprintf("Creating new plugin '%s' in folder '%s'...", $plugin_name, $plugin_folder_name));

        // Create folder
        if (! @mkdir($plugin_folder_path, 0755)) {
            $output->writeln(sprintf("Folder creation for this plugin failed! %s", $plugin_folder_path));
            return ConsoleCommand::FAILURE;
        }

        // Create INFO file
        $display_name = sprintf("%s (DLS Search Plugin)", $plugin_folder_name);
        $description = sprintf("%s search plugin.", $plugin_folder_name);
        $plugin_version = "0.1";
        $plugin_site = "https://www.example.com";
        $plugin_type = "search";
        $plugin_module = PluginCommandHelper::getModuleNameFromPluginName($plugin_name);
        $plugin_class = PluginCommandHelper::getClassNameFromPluginName($plugin_name);
        $plugin_namespaced_class = PluginCommandHelper::getNamespacedClassNameFromPluginName($plugin_name);

        $info_file_data = [
            "name" => $plugin_name,
            "displayname" => $display_name,
            "description" => $description,
            "version" => $plugin_version,
            "site" => $plugin_site,
            "type" => $plugin_type,
            "module" => $plugin_module,
            "class" => $plugin_namespaced_class
        ];
        $info_file_path = sprintf("%s/INFO", $plugin_folder_path);

        $output->writeln(sprintf("Writing INFO file: %s", json_encode($info_file_data, JSON_PRETTY_PRINT)));
        file_put_contents($info_file_path, json_encode($info_file_data, JSON_PRETTY_PRINT));

        // Create module file
        $template_file_path = sprintf("%s/Template/_search_plugin_template_.php", dirname(__DIR__));
        $module_content = file_get_contents($template_file_path);
        $module_content = str_replace("___FOLDERNAME___", $plugin_folder_name, $module_content);
        $module_content = str_replace("___CLASSNAME___", $plugin_class, $module_content);
        $module_file_path = sprintf("%s/%s", $plugin_folder_path, $plugin_module);
        file_put_contents($module_file_path, $module_content);

        return ConsoleCommand::SUCCESS;
    }
}
