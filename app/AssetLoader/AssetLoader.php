<?php 

namespace App\AssetLoader;

class AssetLoader 
{
	
	private static $siteScripts = ['site.js'];
	private static $appScripts = ['app.js'];

	public static function registerSiteScript($script)
	{
		self::$siteScripts[] = $script;
	}

	public static function registerAppScript($script)
	{
		self::$appScripts[] = $script;
	}

	public static function getSiteScripts()
	{
		return self::$siteScripts;
	}

	public static function getAppScripts()
	{
		return self::$appScripts;
	}

	public static function excludeSiteScript($script)
	{

		foreach(self::$siteScripts as $key => $siteScript) {
			if ($siteScript == $script) {
				unset(self::$siteScripts[$key]);
			}
		}
	}

}