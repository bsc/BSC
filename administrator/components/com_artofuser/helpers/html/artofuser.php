<?php
/**
 * @version		$Id: artofuser.php 541 2011-01-15 02:36:44Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Component HTML Helper
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class JHtmlArtofUser
{
	/**
	 * Display the published setting and icon
	 *
	 * @param	int		The value of the published field
	 * @param	int		The row index
	 *
	 * @return	string
	 */
	function enabled($i, $value, $category)
	{
		$config	= JComponentHelper::getParams('com_artofuser');
		$images	= array(0 => 'images/publish_x.png', 1 => 'images/tick.png');
		$alts	= array(0 => 'COM_ARTOFUSER_STATE_DISABLED', 1 => 'COM_ARTOFUSER_STATE_ENABLED');
		$img 	= JArrayHelper::getValue($images, $value, $images[0]);

		if ($value == 1) {
			if ($config->get('block_req_cat') || $config->get('block_req_note')) {
				$task = 'block.display';
			}
			else {
				$task = 'users.disable';
			}
		}
		else {
			$task = 'users.enable';
		}

		$alt 	= JText::_(JArrayHelper::getValue($alts, $value, $images[0]));
		$action = $value == 1 ? JText::_('COM_ARTOFUSER_ACTION_DISABLE_USER') : JText::_('COM_ARTOFUSER_ACTION_ENABLE_USER');
		if ($category) {
			$action .= "\n".htmlspecialchars($category);
		}

		$href = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$action.'">
		<img src="'.$img.'" border="0" alt="'.$alt.'" /></a>';

		return $href;
	}

	/**
	 * Display the activated setting and icon
	 *
	 * @param	int		The value of the published field
	 * @param	int		The row index
	 *
	 * @return	string
	 */
	function activated($i, $value)
	{
		$images	= array(0 => 'images/publish_x.png', 1 => 'images/tick.png');
		$alts	= array(0 => 'COM_ARTOFUSER_State_Pending_activations', 1 => 'COM_ARTOFUSER_State_Activated');
		$img 	= JArrayHelper::getValue($images, $value, $images[0]);
		$task 	= 'activate';
		$alt 	= JText::_(JArrayHelper::getValue($alts, $value, $images[0]));
		$action = JText::_('COM_ARTOFUSER_Action_Activate_User');

		$html = '<img src="'.$img.'" border="0" alt="'.$alt.'" />';
		if ($value == 0) {
			$html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\'users.'.$task.'\')" title="'.$action.'">'.$html.'</a>';
		}

		return $html;
	}

	/**
	 * Display an image.
	 *
	 * @param	string	$image
	 *
	 * @return	string
	 * @since	1.1
	 */
	function image($src)
	{
		$src	= preg_replace('#[^A-Z0-9\-_\.]#i', '', $src);
		$file	= JPATH_SITE.'/images/stories/'.$src;
		JPath::check($file);

		if (!file_exists($file)) {
			return '';
		}

		return '<img src="'.JUri::root().'images/stories/'.$src.'" alt="Icon" />';
	}

	/**
	 * Displays an icon to add a note for this user.
	 *
	 * @return	string
	 * @since	1.1
	 */
	function addNote($userId)
	{
		$title = JText::_('COM_ARTOFUSER_ADD_NOTE');

		return
			'<a href="'.JRoute::_('index.php?option=com_artofuser&task=note.add&u_id='.(int) $userId).'">'.
			'<img src="'.JURI::base().'components/com_artofuser/media/images/icon_16_note_add.png" alt="Notes" title="'.$title.'" />'.
			'</a>';
	}

	/**
	 * Displays an icon to filter the notes list on this user.
	 *
	 * @return	string
	 * @since	1.1
	 */
	function filterNotes($count, $userId)
	{
		if (empty($count)) {
			return '';
		}

		$title = JText::_('COM_ARTOFUSER_FITLER_NOTES');

		return
			'<a href="'.JRoute::_('index.php?option=com_artofuser&view=notes&filter_search=uid:'.(int) $userId).'">'.
			'<img src="'.JURI::base().'components/com_artofuser/media/images/icon_16_filter.png" alt="Notes" title="'.$title.'" />'.
			'</a>';
	}

	/**
	 * Displays a note icon.
	 *
	 * @return	string
	 * @since	1.1
	 */
	function notes($count, $userId)
	{
		if (empty($count)) {
			return '';
		}

		$title = JText::sprintf('COM_ARTOFUSER_N_USER_NOTES', $count);

		return
			'<a class="modal" href="'.JRoute::_('index.php?option=com_artofuser&view=unotes&tmpl=component&u_id='.(int) $userId).'">'.
			'<img src="'.JURI::base().'components/com_artofuser/media/images/icon_16_note.png" alt="Notes" title="'.$title.'" />'.
			'</a>';
	}

	function footer()
	{
		JHtml::_('behavior.modal', 'a.modal');
		echo '<div id="taojfooter">';
		echo  '<a href="'.JRoute::_('index.php?option=com_artofuser&view=about&tmpl=component').'" class="modal" rel="{handler: \'iframe\'}">';
		echo 'Artof User '.ArtofUserVersion::getVersion(true, true).'</a>';
		echo ' &copy; 2005 - 2011 <a href="http://www.newlifeinit.com" target="_blank">New Life in IT Pty Ltd</a>. All rights reserved.';
		echo '</div>';
	}
}
