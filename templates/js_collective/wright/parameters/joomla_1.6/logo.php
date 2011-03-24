<?php

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLogo extends JFormFieldList
{
	public $type = 'Logo';

	protected function getOptions()
	{
		$options = array();

		$options[] = JHTML::_('select.option', 'template', '- '.JText::_('Use template logo').' -');

		$files = JFolder::files(JPATH_ROOT.DS.'images', '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$');

		$options = array ();

		$options[] = JHTML::_('select.option', 'template', '- '.JText::_('Use template logo').' -');

		foreach ($files as $file)
		{
			$options[] = JHTML::_('select.option', $file, $file);
		}

		return $options;
	}
}