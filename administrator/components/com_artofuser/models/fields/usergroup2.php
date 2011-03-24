<?php
/**
 * @version		$Id: usergroup2.php 537 2011-01-15 02:22:57Z eddieajau $
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
class JFormFieldUserGroup2 extends JFormFieldList
{
	function _getOptions()
	{
		$type	= 'aro';
		$model = JModel::getInstance('Groups', 'ArtofUserModel', array('ignore_request' => true));

		$model->setState('filter.tree',		'1');
		//$model->setState('parent_id',	$node->attributes('parent_id'));
		$model->setState('filter.parent_id',	28);
		$model->setState('list.select',		'a.id AS value, a.name AS text');
		$options = $model->getItems();
		//array_unshift($options, JHTML::_('select.option', 0, 'Not Applicable'));

		if (count($options) == 1) {
			array_unshift($options, JHTML::_('select.option', 0, 'None'));
		} else {
			foreach ($options as $i => $option) {
				$options[$i]->text = str_pad($option->text, strlen($option->text) + 2*$option->level, '- ', STR_PAD_LEFT);
			}
		}

		return $options;
	}
}