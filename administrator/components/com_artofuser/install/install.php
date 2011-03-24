<?php
/**
 * @version		$Id: install.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die;

// Load the component language file
$language = JFactory::getLanguage();
$language->load('com_artofuser', JPATH_ADMINISTRATOR.'/components/com_artofuser');

// PHP 5 check
if (version_compare(PHP_VERSION, '5.2.4', '<')) {
	$this->parent->abort(JText::_('J_USE_PHP5'));
	return false;
}

// Include dependancies.
require_once dirname(__FILE__).'/helper.php';
require_once dirname(dirname(__FILE__)).'/version.php';

// Install the modules.
$modules = PackageInstallerHelper::installModules($this);
if ($modules === false) {
	return false;
}

// Install the plugins.
$plugins = PackageInstallerHelper::installPlugins($this);
if ($plugins === false) {
	return false;
}

// Fix the link bug.
PackageInstallerHelper::fixLink('com_artofuser');

// Perform upgrades.
if (PackageInstallerHelper::componentExists('com_artofuser')) {
	// Perform the DB upgrades.
	$results = PackageInstallerHelper::upgrade(
		'<?xml version="1.0"?>
<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 <database name="">
  <table_structure name="#__artofuser_notes">
   <field Field="id" Type="int(10) unsigned" Null="NO" Key="PRI" Extra="auto_increment" />
   <field Field="user_id" Type="int(10) unsigned" Null="NO" Key="MUL" Default="0" Extra="" />
   <field Field="catid" Type="int(10) unsigned" Null="NO" Key="MUL" Default="0" Extra="" />
   <field Field="subject" Type="varchar(100)" Null="NO" Key="" Default="" Extra="" />
   <field Field="body" Type="text" Null="NO" Key="" Extra="" />
   <field Field="published" Type="tinyint(3)" Null="NO" Key="" Default="0" Extra="" />
   <field Field="checked_out" Type="int(10) unsigned" Null="NO" Key="" Default="0" Extra="" />
   <field Field="checked_out_time" Type="datetime" Null="NO" Key="" Default="0000-00-00 00:00:00" Extra="" />
   <field Field="created_user_id" Type="int(10) unsigned" Null="NO" Key="" Default="0" Extra="" />
   <field Field="created_time" Type="datetime" Null="NO" Key="" Default="0000-00-00 00:00:00" Extra="" />
   <field Field="modified_user_id" Type="int(10) unsigned" Null="NO" Key="" Extra="" />
   <field Field="modified_time" Type="datetime" Null="NO" Key="" Default="0000-00-00 00:00:00" Extra="" />
   <field Field="review_time" Type="datetime" Null="NO" Key="" Default="0000-00-00 00:00:00" Extra="" />
   <key Table="#__artofuser_notes" Non_unique="0" Key_name="PRIMARY" Seq_in_index="1" Column_name="id" Collation="A" Null="" Index_type="BTREE" Comment="" />
   <key Table="#__artofuser_notes" Non_unique="1" Key_name="idx_user_id" Seq_in_index="1" Column_name="user_id" Collation="A" Null="" Index_type="BTREE" Comment="" />
   <key Table="#__artofuser_notes" Non_unique="1" Key_name="idx_category_id" Seq_in_index="1" Column_name="catid" Collation="A" Null="" Index_type="BTREE" Comment="" />
  </table_structure>
  <table_structure name="#__artofuser_blocked">
   <field Field="user_id" Type="int(11) unsigned" Null="NO" Key="PRI" Default="0" Extra="" />
   <field Field="catid" Type="int(11) unsigned" Null="NO" Key="" Default="0" Extra="" />
   <field Field="checked_out" Type="tinyint(3) unsigned" Null="NO" Key="" Default="0" Extra="" />
   <key Table="#__artofuser_blocked" Non_unique="0" Key_name="PRIMARY" Seq_in_index="1" Column_name="user_id" Collation="A" Null="" Index_type="BTREE" Comment="" />
  </table_structure>
 </database>
</mysqldump>'
	);

	// Log the upgrade.
	jimport('joomla.error.log');
	$user	= JFactory::getUser();
	$userId	= $user->get('id');
	$log	= JLog::getInstance('com_artofuser.install.log.php');

	$log->setOptions(
		array(
		    'format' => "{DATE}\t{TIME}\t{USER_ID}\t{COMMENT}"
		)
	);

	if (empty($results)) {
		$log->addEntry(
			array(
				'user_id' => $userId,
				'comment' => 'ArtofUser '.ArtofUserVersion::getVersion(false, true).' upgraded with no schema changes.'
			)
		);
	}
	else {
		$log->addEntry(
			array(
				'user_id' => $userId,
				'comment' => 'ArtofUser '.ArtofUserVersion::getVersion(false, true).' upgraded with schema changes.'
			)
		);
		foreach ($results as $result)
		{
			$log->addEntry(
				array(
					'user_id' => $userId,
					'comment' => $result
				)
			);
		}
	}
}


// Display the results.
PackageInstallerHelper::displayInstalled(
	$modules,
	$plugins,
	JText::sprintf('COM_ARTOFUSER_INSTALLED', ArtofUserVersion::getVersion(false, true)),
	JText::_('ArtofUser')
);
