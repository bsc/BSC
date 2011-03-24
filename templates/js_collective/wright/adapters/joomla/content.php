<?php

class WrightAdapterJoomlaContent
{
	public function render($args)
	{
		if (!isset($args['above'])) $args['above'] = 'above-content';
		if (!isset($args['above-chrome'])) $args['above-chrome'] = 'xhtml';
		if (!isset($args['below'])) $args['below'] = 'below-content';
		if (!isset($args['below-chrome'])) $args['below-chrome'] = 'xhtml';

		$content = '<jdoc:include type="message" />';

		$doc = Wright::getInstance();
		if ($doc->countModules($args['above']) && $args['above'] !== 'false') {
			$content .= '<div class="'.$args['above'].'">
				<jdoc:include type="modules" name="'.$args['above'].'" style="'.$args['above-chrome'].'" />
				</div>
			<div class="clr"></div>';
		}
		
		$content .= '<jdoc:include type="component" />';

		if ($doc->countModules($args['below']) && $args['below'] !== 'false') {
			$content .= '<div class="'.$args['below'].'">
				<jdoc:include type="modules" name="'.$args['below'].'" style="'.$args['below-chrome'].'" />
				</div>
			<div class="clr"></div>';
		}

		return $content;
	}
}