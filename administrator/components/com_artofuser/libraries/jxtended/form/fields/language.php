<?php
/**
 * @version		$Id: language.php 352 2010-10-26 08:52:42Z eddieajau $
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
class JFormFieldLanguage extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Language';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		jimport('joomla.language.helper');
		$client		= $this->_element->attributes('client');
		$options	= array_merge(
						parent::_getOptions(),
						JLanguageHelper::createLanguageList($this->value, constant('JPATH_'.strtoupper($client)), true)
					);

		return $options;
	}
}