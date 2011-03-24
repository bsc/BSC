<?php

class Overrider
{
	static $version;

	public function getVersion()
	{
		if (!isset(self::$version))
		{
			jimport('joomla.version');
			$version = new JVersion();
			$joomla = str_replace('!', '', $version->PRODUCT.'_'.$version->RELEASE);
			self::$version = strtolower($joomla);
		}

		return self::$version;
	}
}