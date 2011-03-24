<?php
/**
 * @version		$Id: componentlayout.php 352 2010-10-26 08:52:42Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

require_once dirname(__FILE__).'/list.php';

/**
 * Form Field to display a list of the layouts for a component view from the extension or default template overrides.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldComponentLayout extends JFormFieldList
{
	/**
	 * @var		string
	 */
	protected $_name = 'ComponentLayout';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		$options	= array();
		$path1		= null;
		$path2		= null;

		// Load template entries for each menuid
		$db			=& JFactory::getDBO();
		$query		= 'SELECT template'
			. ' FROM #__template_styles'
			. ' WHERE client_id = 0 AND home = 1';
		$db->setQuery($query);
		$template	= $db->loadResult();

		$extn = $this->_element->attributes('extension');
		if (empty($extn)) {
			$extn = $this->_form->getValue('extension');
		}

		if (($view = $this->_element->attributes('view')) && $extn)
		{
			$view	= preg_replace('#\W#', '', $view);
			$extn	= preg_replace('#\W#', '', $extn);
			$path1	= JPATH_SITE.'/components/'.$extn.'/views/'.$view.'/tmpl';
			$path2	= JPATH_SITE.'/templates/'.$template.'/html/'.$extn.'/'.$view;
			$options[]	= JHTML::_('select.option', '', JText::_('JOption_Use_Menu_Request_Setting'));
		}

		if ($path1 && $path2)
		{
			jimport('joomla.filesystem.file');
			$path1 = JPath::clean($path1);
			$path2 = JPath::clean($path2);

			if (is_dir($path1) && ($files = JFolder::files($path1, '^[^_]*\.php$')))
			{
				foreach ($files as $file) {
					$options[]	= JHTML::_('select.option', JFile::stripExt($file));
				}
			}

			if (is_dir($path2) && ($files = JFolder::files($path2, '^[^_]*\.php$')))
			{
				$options[]	= JHTML::_('select.optgroup', JText::_('JOption_From_Default_Template'));
				foreach ($files as $file) {
					$options[]	= JHTML::_('select.option', JFile::stripExt($file));
				}
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::_getOptions(), $options);

		return $options;
	}
}