<?php // $Id: rebrand.php 55 2010-12-29 20:42:40Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementRebrand extends JElement
{
	var	$_name = 'Rebrand';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array();
		$options[] = JHTML::_('select.option', 'no', JText::_('No'));
		$options[] = JHTML::_('select.option', 'yes', JText::_('Yes'));

		$template = array_pop(JRequest::getVar('cid'));

		$html = JHTML::_('select.radiolist', $options, ''.$control_name.'['.$name.']', '', 'value', 'text', $value, $control_name.$name );

		$author = simplexml_load_file(JPATH_ROOT.DS.'templates'.DS.$template.DS.'templateDetails.xml')->author;
		if (stripos($author, 'shack'))
			$html .= '&nbsp;<a href="http://www.joomlashack.com/licensing-center" target="_blank">Rebranding requires a license, learn more.</a>';

		return $html;
	}
}