<?php

class WrightAdapterJoomlaModule
{
	public function render($args)
	{
		// Set display type
		if (!isset($args['type'])) $args['type'] = 'single';
		// Set module name
		if (!isset($args['name'])) $args['name'] = 'left';
		// Set wrapper class value if not given
		if (!isset($args['wrapper'])) $args['wrapper'] = 'grid';
		// Set style value if not given
		if (!isset($args['chrome'])) $args['chrome'] = 'xhtml';

		$html = '';

		switch ($args['type'])
		{
			case 'grid' :
				$doc = JFactory::getDocument();
				$html .= '<div class="'.$args['wrapper'].'">';
				$html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" grid="'.$doc->countModules($args['name']).'" />';
				$html .= '</div>';
				break;
			default :
				$html .= '<div class="'.$args['wrapper'].'">';
				$html .= '<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['chrome'].'" />';
				$html .= '</div>';
				break;
		}

		return $html;
	}
}