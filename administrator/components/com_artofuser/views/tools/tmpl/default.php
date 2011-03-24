<?php
/**
 * @version		$Id: default.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
?>

<a href="<?php echo JRoute::_('index.php?option=com_artofuser&view=analyse');?>">
	<?php echo JText::_('COM_ARTOFUSER_Tools_Analyse_Tables');?></a>

<?php echo JHtml::_('artofuser.footer'); ?>