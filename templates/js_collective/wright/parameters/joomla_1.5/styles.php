<?php // $Id: styles.php 55 2010-12-29 20:42:40Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementStyles extends JElement
{
	var	$_name = 'Styles';

	function fetchElement($name, $value, &$node, $control_name)
	{
		jimport('joomla.filesystem.folder');
		$template = array_pop(JRequest::getVar('cid'));
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="inputbox"' );

		$styles = JFolder::files(str_replace('administrator'.DS, '', JPATH_THEMES.DS.$template.DS.'css'), 'style-(.*)?\.css');

		$options = array ();
		foreach ($styles as $style)
		{
			$item = substr($style, 6, strpos($style, '.css') - 6);
			$val	= $item;
			$text	= ucfirst($item);
			$options[] = JHTML::_('select.option', $val, JText::_($text));
		}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', $class, 'value', 'text', $value, $control_name.$name);
	}
}