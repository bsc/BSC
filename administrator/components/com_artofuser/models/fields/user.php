<?php
/**
 * @version		$Id: user.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Field to select a user id from a modal list.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @since		1.6
 */
class JFormFieldUser extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'User';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function _getInput()
	{
		// Initialise variables.
		$html	= array();
		$link	= 'index.php?option=com_artofuser&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;field='.$this->inputId;

		// Initialise some field attributes.
		$attr	= $this->_element->attributes('class') ? ' class="'.(string) $this->_element->attributes('class').'"' : '';
		$attr	.= $this->_element->attributes('size') ? ' size="'.(int) $this->_element->attributes('size').'"' : '';

		// Initialise JavaScript field attributes.
		$onChange = (string) $this->_element->attributes('onchange');

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal_'.$this->inputId);

		// Build the script.
		$script = array();
		$script[] = '	function jSelectUser_'.$this->inputId.'(id, title) {';
		$script[] = '		var old_id = document.getElementById("'.$this->inputId.'_id").value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById("'.$this->inputId.'_id").value = id;';
		$script[] = '			document.getElementById("'.$this->inputId.'_name").value = title;';
		$script[] = '			'.$onChange;
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current username if available.
		$table = JTable::getInstance('user');
		if ($this->value) {
			$table->load($this->value);
			$name = $table->name;
		}
		else {
			$name = JText::_('JLIB_FORM_SELECT_USER');
		}

		// Create a dummy text field with the user name.
		$html[] = '<div class="fltlft">';
		$html[] = '	<input type="text" id="'.$this->inputId.'_name"' .
					' value="'.htmlspecialchars($name, ENT_COMPAT, 'UTF-8').'"' .
					' disabled="disabled"'.$attr.' />';
		$html[] = '</div>';

		// Create the user select button.
		$html[] = '<div class="button2-left">';
		$html[] = '  <div class="blank">';
		if ($this->_element->attributes('readonly') != 'true') {
			$html[] = '		<a class="modal_'.$this->inputId.'" title="'.JText::_('JLIB_FORM_CHANGE_USER').'"' .
							' href="'.$link.'"' .
							' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html[] = '			'.JText::_('JLIB_FORM_CHANGE_USER').'</a>';
		}
		$html[] = '  </div>';
		$html[] = '</div>';

		// Create the real field, hidden, that stored the user id.
		$html[] = '<input type="hidden" id="'.$this->inputId.'_id" name="'.$this->inputName.'" value="'.(int) $this->value.'" />';

		return implode("\n", $html);
	}

	/**
	 * Method to get the filtering groups (null means no filtering)
	 *
	 * @return	array|null	array of filtering groups or null.
	 * @since	1.6
	 */
	protected function getGroups()
	{
		return null;
	}

	/**
	/**
	 * Method to get the users to exclude from the list of users
	 *
	 * @return	array|null array of users to exclude or null to to not exclude them
	 * @since	1.6
	 */
	protected function getExcluded()
	{
		return null;
	}
}
