<?php
/**
 * @version		$Id: groups.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.html.html');

/**
 * List form field type object
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class JFormFieldGroups extends JFormFieldList
{
	function _getOptions()
	{
		$model = JModel::getInstance('Groups', 'ArtofUserModel', array('ignore_request' => true));

		$model->setState('filter.tree',		'1');
		//$model->setState('parent_id',	$node->attributes('parent_id'));
		$model->setState('filter.parent_id',	28);
		$model->setState('list.select',		'a.id AS value, a.name AS text');
		$options = $model->getItems();
		//array_unshift($options, JHTML::_('select.option', 0, 'Not Applicable'));

		if (count($options) == 1) {
			array_unshift($options, JHTML::_('select.option', 0, 'None'));
		}
		else {
			foreach ($options as $i => $option)
			{
				$options[$i]->text = str_repeat('<span class="gi">|&mdash;</span>', max(0, $option->level - 2)).' '.$option->text;
			}
		}

		return $options;
	}

	protected function _getInput()
	{
		$value		= (array) $this->value;
		$html		= '';
		$gid		= $this->_form->getValue('gid');

		foreach ($this->_getOptions() as $option)
		{
			$id = $this->inputId.'_'.$option->value;
			if ($option->value == $gid) {
				$html .= '<input type="checkbox" disabled="disabled" checked="checked" />';
			}
			else if ($option->value <= 30) {
				$html .= '<input type="checkbox" disabled="disabled" />';
			}
			else {
				$selected = in_array($option->value, $value) ? ' checked="checked"' : '';
				$html .= '<input id="'.$id.'" name="'.$this->inputName.'[]" type="checkbox" value="'.$option->value.'"'.$selected.'>';
			}
			$html .= ' <label for="'.$id.'">'.$option->text.'</label>';
			$html .= '<br />';
		}

		return $html;
	}
}