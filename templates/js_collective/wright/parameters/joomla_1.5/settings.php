<?php // $Id: settings.php 55 2010-12-29 20:42:40Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementSettings extends JElement
{
	var	$_name = 'Settings';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$doc = JRegistry::getInstance();

		$template = array_pop(JRequest::getVar('cid'));
		$doc->addScript(str_replace('/administrator/', '/', JURI::base()).'templates/'.$template.'/wright/parameters/assets/jscolor/jscolor.js');

		$size = ( $node->attributes('size') ? 'size="'.$node->attributes('size').'"' : '' );
        $value = htmlspecialchars_decode($value, ENT_QUOTES);

		$html = '<input type="text" name="'.$control_name.'['.$name.']" id="'.$control_name.$name.'" value="'.$value.'" class="color" '.$size.' /> ';

		return $html;
	}
}