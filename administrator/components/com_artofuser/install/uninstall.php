<?php
/**
 * @version		$Id: uninstall.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die;

// Load the component language file
$language = JFactory::getLanguage();
$language->load('com_redirect');

// Include dependancies.
require_once dirname(__FILE__).'/helper.php';
require_once dirname(dirname(__FILE__)).'/version.php';

// Uninstall the modules.
$modules = PackageInstallerHelper::uninstallModules($this);
if ($modules === false) {
	return false;
}

// Uninstall the plugins.
$plugins = PackageInstallerHelper::uninstallPlugins($this);
if ($plugins === false) {
	return false;
}

// Display the results.
PackageInstallerHelper::displayInstalled(
	$modules,
	$plugins,
	JText::_('COM_ARTOFUSER_UNINSTALLED'),
	JText::_('ArtofUser')
);
