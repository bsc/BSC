<?php // $Id: grid.php 8 2010-11-03 18:07:23Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementGrid extends JElement
{
	var	$_name = 'Grid';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$size = ($node->attributes('size')) ? $node->attributes('size') : 12;

		$base = 60;

		$options = array ();
		for ($i=1; $i <= $size; $i++)
		{
			$val	= $i;
			$text	= $i . ' (' . $base . 'px)';
			$options[] = JHTML::_('select.option', $val, JText::_($text));
			$base += 80;
		}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);
	}
}