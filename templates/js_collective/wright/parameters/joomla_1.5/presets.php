<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementPresets extends JElement
{

	var	$_name = 'Presets';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc = JFactory::getDocument();

		$template = array_pop(JRequest::getVar('cid'));
		$doc->addScript(str_replace('/administrator/', '/', JURI::base()).'templates/'.$template.'/wright/parameters/assets/presets/presets.js');
		
		$file = simplexml_load_file(JPATH_ROOT.DS.'templates'.DS.$template.DS.'presets.xml');

		$json = str_replace('@attributes', 'attributes', json_encode($file));

		$doc->addScriptDeclaration('var presets = '.$json);

		$options = array ();

		foreach ($file->xpath('//preset') as $preset)
		{
			$options[] = JHTML::_('select.option', $preset['name'], $preset['title']);
		}

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name);
	}
}
