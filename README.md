Synology Download Station Plugin Maker
======================================

***This repo is under heavy development and is not yet usable***

This is a php command line tool for helping to develop, verify, package and maintain search plugins for Synology Download Station (DLS).


# Overview
Plugins live in the `plg/Plugins` folder in their own subfolder. This is the working directory in which you will do your development.


# Installation
Clone the repo and install the composer dependencies.

```bash
git clone https://github.com/adamjakab/Synology-Download-Station-Plugin-Maker
cd Synology-Download-Station-Plugin-Maker
composer install
```

The installed composer packages stored in the vendor folder are only necessary for the local building environment.

# How to use
Use the console `app` application by typing `./app` in the application folder.
This will list the following commands under the plugin section:

```bash
 plugin
  plugin:create   Create a new plugin
  plugin:list     List all plugins
  plugin:pack     Pack a plugin
  plugin:prepare  Prepare a plugin
  plugin:run      Run a plugin
  plugin:verify   Verify a plugin
```


TBC...



### Notes & Documentation

Plugin documentation: https://global.download.synology.com/download/Document/Software/DeveloperGuide/Package/DownloadStation/All/enu/DLM_Guide.pdf


Plugins are installed to: /volume1/@appconf/DownloadStation/download/userplugins/[pluginname]

Oneliner top update from project dir:
```
cp -R ./* /volume1/@appconf/DownloadStation/download/userplugins && sudo chown -R DownloadStation:DownloadStation /volume1/@appconf/DownloadStation/download/userplugins/* && sudo chmod -R a+rwx /volume1/@appconf/DownloadStation/download/userplugins/*
```


Manually create DLM package:
	`rm ilcorsaronero.dlm && tar -zcf ilcorsaronero.dlm INFO SynoDLMSearchIlCorsaroNero.php SearchLink.php PluginHelper.php config.json`


