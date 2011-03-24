<?php
/**
 * @version		$Id: artofuser.php 543 2011-01-15 04:39:43Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

// Load local language file.
$lang = JFactory::getLanguage();
$lang->load('com_artofuser', JPATH_COMPONENT);

// Include dependancies.
require_once JPATH_COMPONENT.'/version.php';
require_once JPATH_COMPONENT.'/libraries/loader.php';
require_once JPATH_COMPONENT.'/helpers/artofuser.php';

// Access check.
if (!ArtofUserHelper::authorise()) {
	JFactory::getApplication()->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

juimport('joomla.application.component.controller');

$controller = JController::getInstance('ArtofUser');
$controller->execute(JRequest::getVar('task'));
$controller->redirect();
