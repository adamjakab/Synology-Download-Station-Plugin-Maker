Synology Download Station Plugin Maker
======================================

### How to use

You must use the console `app` application.

TBC...



### Notes & Documentation

Plugin documentation: https://global.download.synology.com/download/Document/Software/DeveloperGuide/Package/DownloadStation/All/enu/DLM_Guide.pdf


Plugins are installed to: /volume1/@appconf/DownloadStation/download/userplugins/[pluginname]

Manually create DLM package:
	`rm ilcorsaronero.dlm && tar -zcf ilcorsaronero.dlm INFO SynoDLMSearchIlCorsaroNero.php SearchLink.php PluginHelper.php config.json`

