<?php
/**
 * @version		$Id: media.php 352 2010-10-26 08:52:42Z eddieajau $
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die;

juimport('jxtended.form.formfield');

/**
 * Form Field class for JXtended Libraries.
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormFieldMedia extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Media';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function _getInput()
	{
		static $init = false;
		$html = '';

		if (!$init)
		{
			JHtml::_('behavior.modal');
			$js = "
			function jInsertFieldValue(value,id) {
				document.getElementById(id).value = value;
			}";
			$doc = &JFactory::getDocument();
			$doc->addScriptDeclaration($js);
			$init = true;
		}

		$link	= $this->_element->attributes('link').$this->inputId;
		$size	= $this->_element->attributes('size') ? 'size="'.$this->_element->attributes('size').'"' : '';
		$class	= $this->_element->attributes('class') ? 'class="'.$this->_element->attributes('class').'"' : '';

		$html .= '<div style="float: left;">';
		$html .= '<input type="text" name="'.$this->inputName.'" id="'.$this->inputId.'" value="'.htmlspecialchars($this->value).'" '.$class.$size.' />';
		$html .= '</div>';
		$html .= '<div class="button2-left">';
		$html .= '<div class="blank">';
		$html .= '<a class="modal" title="'.JText::_('SELECT').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">';
		$html .= JText::_('SELECT');
		$html .= '</a>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}