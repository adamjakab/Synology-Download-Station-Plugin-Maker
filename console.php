<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use SynologyDlsPlugins\Command\PluginRunCommand;
use SynologyDlsPlugins\Command\PluginListCommand;
use SynologyDlsPlugins\Command\PluginPackCommand;
use SynologyDlsPlugins\Command\PluginVerifyCommand;
use SynologyDlsPlugins\Command\PluginPrepareCommand;

//isset($GLOBALS["is_build_environment"]) || $GLOBALS['is_build_environment'] = TRUE;
isset($GLOBALS["project_folder"]) || $GLOBALS['project_folder'] = dirname(__FILE__);

$application = new Application();
$application->add(new PluginListCommand());
$application->add(new PluginRunCommand());
$application->add(new PluginPackCommand());
$application->add(new PluginPrepareCommand());
$application->add(new PluginVerifyCommand());
$application->run();
