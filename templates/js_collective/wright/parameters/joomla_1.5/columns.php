<?php // $Id: columns.php 55 2010-12-29 20:42:40Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementColumns extends JElement
{
	var	$_name = 'Columns';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc = JFactory::getDocument();
		$template = array_pop(JRequest::getVar('cid'));
		$doc->addScript(str_replace('/administrator/', '/', JURI::base()).'templates/'.$template.'/wright/parameters/assets/columns/columns.js');
		$doc->addStylesheet(str_replace('/administrator/', '/', JURI::base()).'templates/'.$template.'/wright/parameters/assets/columns/columns.css');

		$values = explode(';', $value);
		foreach ($values as $col)
		{
			$columns[] = explode(':', $col);
		}
		$number = count($values);

		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="columns"' );

		$sidebars = ($node->attributes('sidebars')) ? $node->attributes('sidebars') : 2;

		$options = array ();
		for ($i=1; $i <= 12; $i++)
		{
			$val	= $i;
			$text	= $i;
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}

		$html = '<input type="hidden" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" />';

		$html .= '<p id="column_info">' . JText::_('Using') . ' <span id="columns_used"></span> ' . JText::_('of') . ' 12 <span id="columns_warning">'.JText::_('The total needs to add up to 12').'</span></p>';
		$html .= '<p>'.JText::_('Columns will collapse if no modules are published to them on a particular page.').'</p>';

		foreach ($columns as $column)
		{
			$html .= '<div id="column_'.$column[0].'" class="column" style="width: '.floor(100/$number).'%; float: left;  text-align:center;"><span style="display: block; text-align:center;"><a onclick="swapColumns(\''.$column[0].'\', \'left\')">&nbsp;&nbsp;&laquo;&nbsp;&nbsp;</a>&nbsp;<a onclick="swapColumns(\''.$column[0].'\', \'right\')">&nbsp;&nbsp;&raquo;&nbsp;&nbsp;</a></span><span style="display: block; text-align:center;">' . JText::_(ucfirst($column[0])) . '</span> ' . JHTML::_('select.genericlist',  $options, 'ignore['.$column[0].']', $class, 'value', 'text', $column[1], 'columns_'.$column[0]) . '</div>';
		}

		$html .= '<div style="display: none; clear: both;"></div>';

		return $html;
	}
}