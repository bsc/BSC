<?php
/**
 * @version		$Id: category.php 364 2010-10-26 09:05:28Z eddieajau $
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
require_once dirname(__FILE__).'/list.php';

/**
 * Supports an HTML select list of categories
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormFieldCategory extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Category';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		$db			= &JFactory::getDBO();
		$section	= $this->_element->attributes('section');
		$published	= $this->_element->attributes('published');
		$allowNone	= $this->_element->attributes('allow_none');

		if ($published === '') {
			$published = null;
		}

		if ($section == 'content') {
			// This might get a conflict with the dynamic translation - TODO: search for better solution
			$db->setQuery(
				'SELECT c.id AS value, CONCAT_WS("/",s.title, c.title) AS text' .
				' FROM #__categories AS c' .
				' LEFT JOIN #__sections AS s ON s.id=c.section' .
				' WHERE s.scope = '.$db->Quote($section).
				($published !== null ? ' AND published = '.(int) $published : '').
				' ORDER BY s.title, c.title'
			);
		}
		else if (!empty($section))
		{
			$db->setQuery(
				'SELECT c.id AS value, c.title As text' .
				' FROM #__categories AS c' .
				' WHERE c.section = '.$db->Quote($section).
				($published !== null ? ' AND published = '.(int) $published : '').
				' ORDER BY c.title'
			);
		}
		else {
			JError::raiseWarning(500, JText::_('JFramework_Form_Fields_Category_Error_section_empty'));
		}

		try {
			$options = $db->loadObjectList();
		}
		catch(JException $e)
		{
			JError::raiseWarning(500, JText::_('JFramework_Form_Fields_Category_Error_extension_empty'));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::_getOptions(), $options);

		return $options;
	}
}