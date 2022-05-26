<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use SynoDlsPluginMaker\Command\PluginRunCommand;
use SynoDlsPluginMaker\Command\PluginListCommand;
use SynoDlsPluginMaker\Command\PluginPackCommand;
use SynoDlsPluginMaker\Command\PluginVerifyCommand;
use SynoDlsPluginMaker\Command\PluginPrepareCommand;

//isset($GLOBALS["is_build_environment"]) || $GLOBALS['is_build_environment'] = TRUE;
isset($GLOBALS["project_folder"]) || $GLOBALS['project_folder'] = dirname(__FILE__);

$application = new Application();
$application->add(new PluginListCommand());
$application->add(new PluginRunCommand());
$application->add(new PluginPackCommand());
$application->add(new PluginPrepareCommand());
$application->add(new PluginVerifyCommand());
$application->run();
