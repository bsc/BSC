<?php

class WrightAdapterJoomlaHead
{
	public function render($args)
	{
		$head = '<jdoc:include type="head" />';

		$doc = Wright::getInstance();
		// Add header script if set
		if (trim($doc->document->params->get('headerscript', '')) !== '')
		{
			$head .= '
				'.$doc->document->params->get('headerscript');
		}

		return $head;
	}
}