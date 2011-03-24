<?php

class WrightAdapterJoomlaLogo
{
	public function render($args)
	{
		if (!isset($args['name'])) $args['name'] = 'newsflash';
		if (!isset($args['style'])) $args['style'] = 'xhtml';

		$doc = Wright::getInstance();
		$app = JFactory::getApplication();

		// If user wants a module, load it instead of image
		if ($doc->document->params->get('logo', 'template') == 'module') {
			$html = '<div id="logo" class="grid_'.$doc->document->params->get('logowidth', '6').'"><jdoc:include type="modules" name="logo" /></div>';
			if ($doc->document->params->get('logowidth') !== '12') $html .= '<div id="'.$args['name'].'" class="grid_'.(12 - $doc->document->params->get('logowidth', '6')).'"><jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" /></div>';
			$html .= '<div class="clearfix"></div>';
			return $html;
		}

		// If user wants just a title, print it out
		elseif ($doc->document->params->get('logo', 'template') == 'title') {
			$html = '<div id="logo" class="grid_'.$doc->document->params->get('logowidth', '6').'"><a href="'.JURI::root().'" class="title"><h2>'.$app->getCfg('sitename').'</h2></a></div>';
			if ($doc->document->params->get('logowidth') !== '12') $html .= '<div id="'.$args['name'].'" class="grid_'.(12 - $doc->document->params->get('logowidth', '6')).'"><jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" /></div>';
			$html .= '<div class="clearfix"></div>';
			return $html;
		}

		// If user wants an image, decide which image to load
		elseif ($doc->document->params->get('logo', 'template') == 'template') {
			if (is_file(JPATH_ROOT.DS.'templates/'.$doc->document->template.'/images/logo.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/logo.png';
			elseif (is_file(JPATH_ROOT.DS.'templates'.DS.$doc->document->template.DS.'images'.DS.$doc->document->params->get('style').DS.'logo.png'))
				$logo = JURI::root().'templates/'.$doc->document->template.'/images/'.$doc->document->params->get('style').'/logo.png';
			else {
				$logo = JURI::root().'templates/'.$doc->document->template.'/wright/images/logo.png';
			}
		}
		else {
			$logo = JURI::root().'images/'.$doc->document->params->get('logo', 'logo.png');
		}

		$html = '<div id="logo" class="grid_'.$doc->document->params->get('logowidth', '6').'"><a href="'.JURI::root().'" class="image"><h2>'.$app->getCfg('sitename').'</h2><img src="'.$logo.'" alt="" title="" /></a></div>';
		if ($doc->document->params->get('logowidth') !== '12') $html .= '<div id="'.$args['name'].'" class="grid_'.(12 - $doc->document->params->get('logowidth', '6')).'"><jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" /></div>';
		$html .= '<div class="clearfix"></div>';
		return $html;
	}
}