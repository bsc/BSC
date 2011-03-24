<?php
/**
 * @version		$Id: helpsite.php 352 2010-10-26 08:52:42Z eddieajau $
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
 * @since		1.6
 */
class JFormFieldHelpsite extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Helpsite';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		jimport('joomla.language.help');
		$options	= array_merge(
						parent::_getOptions(),
						JHelp::createSiteList(JPATH_ADMINISTRATOR.'/help/helpsites-15.xml', $this->value)
					);

		return $options;
	}
}