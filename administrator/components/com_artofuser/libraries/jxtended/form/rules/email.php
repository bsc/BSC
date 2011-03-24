<?php
/**
 * @version		$Id: email.php 356 2010-10-26 08:58:33Z eddieajau $
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die;

juimport('jxtended.form.formrule');

/**
 * Form Rule class for JXtended Libraries.
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormRuleEmail extends JFormRule
{
	/**
	 * The regular expression.
	 *
	 * @var		string
	 */
	protected $_regex = '[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}';

	/**
	 * Method to test if an e-mail address is unique.
	 *
	 * @param	object		$field		A reference to the form field.
	 * @param	mixed		$values		The values to test for validiaty.
	 * @return	mixed		JException on invalid rule, true if the value is valid, false otherwise.
	 * @since	1.6
	 */
	public function test(&$field, &$values)
	{
		$return = false;
		$name	= $field->attributes('name');
		$check	= ($field->attributes('unique') == 'true' || $field->attributes('unique') == 'unique');

		// If the field is empty and not required, the field is valid.
		if ($field->attributes('required') != 'true')
		{
			// Get the data for the field.
			$value = array_key_exists($name, $values) ? $values[$name] : null;

			// If the data is empty, return valid.
			if ($value == null) {
				return true;
			}
		}

		// Check if we should test for uniqueness.
		if ($check)
		{
			$key	= $field->attributes('field');
			$value	= isset($values[$key]) ? $values[$key] : 0;

			// Check the rule.
			if (!$key) {
				return new JException('Invalid Form Rule :: '.get_class($this));
			}

			// Check if the username is unique.
			$db = &JFactory::getDbo();
			$db->setQuery(
				'SELECT count(*) FROM `#__users`' .
				' WHERE `email` = '.$db->Quote($values[$name]) .
				' AND '.$db->nameQuote($key).' != '.$db->Quote($value)
			);
			$duplicate = (bool)$db->loadResult();

			// Check for a database error.
			if ($db->getErrorNum()) {
				return new JException('Database Error :: '.$db->getErrorMsg());
			}

			// Test the value against the regular expression.
			if (parent::test($field, $values) && !$duplicate) {
				$return = true;
			}
		}
		else
		{
			// Test the value against the regular expression.
			if (parent::test($field, $values)) {
				$return = true;
			}
		}

		return $return;
	}
}