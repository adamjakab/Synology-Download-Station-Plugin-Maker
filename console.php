<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use SynologyDlsPlugins\Command\PluginRunCommand;
use SynologyDlsPlugins\Command\PluginListCommand;
use SynologyDlsPlugins\Command\PluginPackCommand;
use SynologyDlsPlugins\Command\PluginVerifyCommand;

isset($GLOBALS["project_folder"]) || $GLOBALS['project_folder'] = dirname(__FILE__);

$application = new Application();
$application->add(new PluginListCommand());
$application->add(new PluginRunCommand());
$application->add(new PluginPackCommand());
$application->add(new PluginVerifyCommand());
$application->run();
