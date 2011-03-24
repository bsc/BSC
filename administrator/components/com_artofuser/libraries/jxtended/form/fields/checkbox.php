<?php
/**
 * @version		$Id: checkbox.php 352 2010-10-26 08:52:42Z eddieajau $
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die('Restricted Access');

jimport('joomla.html.html');
juimport('jxtended.form.formfield');

/**
 * Form Field class for JXtended Libraries.
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormFieldCheckbox extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	public $type = 'Checkbox';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function _getInput()
	{
		$value = $this->_element->attributes('value') !== null ? $this->_element->attributes('value') : '';
		$checked = (!empty($value) && $value == $this->value) ? 'checked="checked"' : '';
		return '<input type="checkbox" name="'.$this->inputName.'" id="'.$this->inputId.'" value="'.$value.'" '.$checked.' />';
	}
}