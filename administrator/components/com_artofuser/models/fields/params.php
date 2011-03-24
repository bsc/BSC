<?php
/**
 * @version		$Id: params.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.html.html');

/**
 * List form field type object
 *
 * @package		TAOJ.Quanta
 * @subpackage	com_quanta
 */
class JFormFieldParams extends JFormField
{
   /**
	* Field type
	*
	* @access	protected
	* @var		string
	*/
	var	$_type = 'Params';

	function fetchField($name, $value, &$node, $controlName)
	{
		$size		= ($node->attributes('size') ? ' size="'.$node->attributes('size').'"' : '');
		$class		= ($node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"');
		$readonly	= ($node->attributes('readonly') == 'true' ? ' readonly="readonly"' : '');
		$onchange	= ($node->attributes('onchange') ? ' onchange="'.$node->attributes('onchange').'"' : '');
		$toarray	= ($node->attributes('toarray') == 'true');

		if (is_object($value)) {
			$p = $value;
		} else {
			$p	= new JParameter($value);
		}
		$p->addElementPath(JPATH_COMPONENT.'/helpers/elements');
		$p->setXML($node);
		return $toarray ? $p->renderToArray($controlName.$name) : $p->render($controlName.$name);
	}
}