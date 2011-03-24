<?php // $Id: rebrand.php 8 2010-11-03 18:07:23Z jeremy $
defined('_JEXEC') or die('Restricted access');

class JElementHelp extends JElement
{
	var	$_name = 'Help';

	function fetchElement($name, $value, &$node, $control_name)
	{
		jimport('joomla.filesystem.file');

		JHTML::_('behavior.modal');
		$template = array_pop(JRequest::getVar('cid'));
		$html = '<a class="modal" href="'.JURI::root().'templates/'.$template.'/wright/help" rel="{\'handler\': \'iframe\', \'size\': {x: 800, y:600}}">'.JText::_('View the documentation.').'</a>';

		// Refresh CSS cache since we are editing params
		if (is_file(JPATH_ROOT.DS.'templates'.DS.$template.DS.'css'.DS.$template.'.css')) JFile::delete(JPATH_ROOT.DS.'templates'.DS.$template.DS.'css'.DS.$template.'.css');

		return $html;
	}
}