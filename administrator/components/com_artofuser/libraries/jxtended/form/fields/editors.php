<?php
/**
 * @version		$Id: editors.php 352 2010-10-26 08:52:42Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
require_once dirname(__FILE__).'/list.php';

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormFieldEditors extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Editors';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		// compile list of the editors
		$query	= 'SELECT element AS value, name AS text'
				. ' FROM #__plugins'
				. ' WHERE folder = "editors"'
				. ' AND published = 1'
				. ' ORDER BY ordering, name';
		$db = & JFactory::getDbo();
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// @todo: Check for an error msg.

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::_getOptions(), $options);

		return $options;
	}
}