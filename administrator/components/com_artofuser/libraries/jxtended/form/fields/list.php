<?php
/**
 * @version		$Id: list.php 352 2010-10-26 08:52:42Z eddieajau $
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
class JFormFieldList extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'List';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function _getOptions()
	{
		$options = array();

		// Iterate through the children and build an array of options.
		foreach ($this->_element->children() as $option) {
			$options[] = JHtml::_('select.option', $option->attributes('value'), JText::_(trim($option->data())));
		}

		return $options;
	}

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function _getInput()
	{
		$disabled	= $this->_element->attributes('disabled') == 'true' ? true : false;
		$readonly	= $this->_element->attributes('readonly') == 'true' ? true : false;
		$attributes	= ' ';

		if ($v = $this->_element->attributes('size')) {
			$attributes	.= 'size="'.$v.'"';
		}
		if ($v = $this->_element->attributes('class')) {
			$attributes	.= 'class="'.$v.'"';
		}
		else {
			$attributes	.= 'class="inputbox"';
		}
		if ($m = $this->_element->attributes('multiple'))
		{
			$attributes	.= 'multiple="multiple"';
		}

		if ($disabled || $readonly) {
			$attributes .= ' disabled="disabled"';
		}
		$options	= (array)$this->_getOptions();
		$return		= null;

		// Handle a disabled list.
		if ($disabled)
		{
			// Create a disabled list.
			$return .= JHtml::_('select.genericlist', $options, $this->inputName, $attributes, 'value', 'text', $this->value, $this->inputId);
		}
		// Handle a read only list.
		else if ($readonly)
		{
			// Create a disabled list with a hidden input to store the value.
			$return .= JHtml::_('select.genericlist', $options, '', $attributes, 'value', 'text', $this->value, $this->inputId);
			$return	.= '<input type="hidden" name="'.$this->inputName.'" value="'.$this->value.'" />';
		}
		// Handle a regular list.
		else
		{
			// Create a regular list.
			$return = JHtml::_('select.genericlist', $options, $this->inputName, $attributes, 'value', 'text', $this->value, $this->inputId);
		}

		return $return;
	}
}