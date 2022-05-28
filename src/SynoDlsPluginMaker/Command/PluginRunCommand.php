<?php
namespace SynoDlsPluginMaker\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SynoDlsPluginMaker\Helper\PluginCommandHelper;
use SynoDlsPluginMaker\Plugin\SynoDLMSearchPlugin;


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
        $this->addArgument('search_term', InputArgument::REQUIRED, 'Search term');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $plugin_name = $input->getArgument('plugin_name');
        $search_term = $input->getArgument('search_term');
        $pluginListItem = PluginCommandHelper::getPluginListItemByName($plugin_name);
        if (!$pluginListItem) {
            $output->writeln(sprintf("The requested plugin('%s') does not exist!", $plugin_name));
            return ConsoleCommand::FAILURE;
        }

        $output->writeln(sprintf("Running plugin(%s) - searching: '%s' ...", $plugin_name, $search_term));
        

        require_once $pluginListItem["file_path"];
        //$fcc = $pluginListItem["namespace"] . '\\' . $pluginListItem["classname"];
        $fcc = $pluginListItem["namespace"];
        $plugin = new \ReflectionClass($fcc);
        $pluginInstance = $plugin->newInstance();
        
        /* Set up curl and run the prepare methon on the search plugin */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        $pluginInstance->prepare($ch, $search_term);
        
        // Execute Curl
        $content = curl_exec( $ch );
        $header  = curl_getinfo( $ch );
        $errno     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        curl_close( $ch );
        
        // Check for errors
        if ( $errno != 0 ) {
            $output->writeln(sprintf("Curl error %s: %s",$errno, $errmsg));
            return ConsoleCommand::FAILURE;
        }
            
        if ( $header['http_code'] != 200 ) {
            $output->writeln(sprintf("HTTP error(%s)!", $header['http_code']));
            return ConsoleCommand::FAILURE;
        }

        $output->writeln(sprintf("OK. Got response: %s" , $header["http_code"]));
        
        // Run the parse method on the search plugin 
        $_syno_dlm_search_plugin = new SynoDLMSearchPlugin($pluginListItem["folder_path"]);
        $pluginInstance->parse($_syno_dlm_search_plugin, $content);
        
        //@todo: find a better way to show the resuts
        $output->write(json_encode($_syno_dlm_search_plugin->getResults(), JSON_PRETTY_PRINT));
        //var_dump($_syno_dlm_search_plugin->getResults());

        return ConsoleCommand::SUCCESS;
    }
}
