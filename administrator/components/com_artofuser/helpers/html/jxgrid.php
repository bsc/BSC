<?php
/**
 * @version		$Id: jxgrid.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * HTML Grid Helper
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class JHTMLJxGrid
{
	/**
	 * Display the published setting and icon
	 *
	 * @param	int		The value of the published field
	 * @param	int		The row index
	 * @param	string	Optional task prefix
	 *
	 * @return	string
	 */
	function published($i, $value, $prefix='')
	{
		$images	= array(-2 => 'components/com_artofuser/media/images/icon_16_trash.png', 0 => 'images/publish_x.png', 1 => 'images/tick.png');
		$alts	= array(-2 => 'Trash', 0 => 'Unpublished', 1 => 'Published');
		$img 	= JArrayHelper::getValue($images, $value, $images[0]);
		$task 	= $value == 1 ? 'unpublish' : 'publish';
		$alt 	= JArrayHelper::getValue($alts, $value, $images[0]);
		$action = $value == 1 ? JText::_('Unpublish Item') : JText::_('Publish item');

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="'. $img .'" border="0" alt="'. $alt .'" /></a>';

		return $href;
	}

	/**
	 * Display the checked out icon
	 *
	 * @param	string	The editor name
	 * @param	string	The checked out time
	 *
	 * @return	string
	 */
	function checkedout($editor, $time)
	{
		$text	= addslashes(htmlspecialchars($editor));
		$date 	= JHtml::_('date',  $time, '%A, %d %B %Y');
		$time	= JHtml::_('date',  $time, '%H:%M');

		$hover = '<span class="editlinktip hasTip" title="'. JText::_('Checked Out') .'::'. $text .'<br />'. $date .'<br />'. $time .'">';
		$checked = $hover .'<img src="components/com_artofuser/media/images/icon_16_checkedout.png" alt="" /></span>';

		return $checked;
	}
}