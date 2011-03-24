<?php

abstract class HtmlAdapterAbstract
{
	protected $columns = array();
	protected $widths = array();
	protected $params;

	protected $tags = array(	'doctype' =>	'/<doctype>/i',
								'html' =>		'/<html(.*)?>/i',
								'htmlComments' =>	'/<!--.*?-->/i',
								'body' => '/<body(.*)?>/i',
								'nav' => '/<nav(.*)>(.*)<\/nav>/isU',
								'sections' => '/<section(.*)>(.*)<\/section>/isU',
								'asides' => '/<aside(.*)>(.*)<\/aside>/isU',
								'footer' => '/<footer(.*)>(.*)<\/footer>/isU',
								'header' => '/<header(.*)>(.*)<\/header>/isU',
		);

	public function  __construct($params) {
		$this->params = $params;
		$this->setupColumns();
	}

	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * Functions below are in leu of an interface, each adapter will implement
	 * them in its own way if they don't like the default
	 */

	public function getDoctype($matches) {
		return '<!DOCTYPE html>';
	}

	public function getHtml($matches) {
		return '<html>';
	}

	public function getHtmlComments($matches)
	{
		return '';
	}

	public function getBody($matches)
	{
		jimport('joomla.environment.browser');
		$browser = JBrowser::getInstance();
		$class = $browser->getBrowser() . '-' . $browser->getMajor();

		if (isset($matches[1])) {
			if (strpos($matches[1], 'class=')) {
				preg_match('/class="(.*)"/i', $matches[1], $classes);
				if (isset($classes[1]))
					$class .= ' ' . $classes[1];
			}
		}

		// If user has custom typography selected, we need to add the classes to trigger it
		if ($this->params->get('body_font', 'default') !== 'default') {
			if ($this->params->get('body_font') == 'googlefonts') {
				if (strpos($this->params->get('body_googlefont'), ',')) {
					$gfont = substr($this->params->get('body_googlefont', 'Cuprum'), 0, strpos($this->params->get('body_googlefont', 'Cuprum'), ','));
				}
				else {
					$gfont = $this->params->get('body_googlefont', 'Cuprum');
				}
				$class .= ' b_' . strtolower(str_replace('+', '', $gfont));
			}
			else {
				$class .= ' b_' . $this->params->get('body_font', 'verdana');
			}
		}
		if ($this->params->get('header_font', 'default') !== 'default') {
			if ($this->params->get('header_font') == 'googlefonts') {
				if (strpos($this->params->get('header_googlefont'), ',')) {
					$gfont = substr($this->params->get('header_googlefont', 'Cuprum'), 0, strpos($this->params->get('header_googlefont', 'Cuprum'), ','));
				}
				else {
					$gfont = $this->params->get('header_googlefont', 'Cuprum');
				}
				
				$class .= ' h_' . strtolower(str_replace('+', '', $gfont));
			}
			else {
				$class .= ' h_' . $this->params->get('header_font', 'helvetica');
			}
		}
		if (JRequest::getVar('Itemid')) $class .= ' id_'.JRequest::getVar('Itemid');

		$menu = & JSite::getMenu();
		if ($menu->getActive() == $menu->getDefault()) $class .= ' home';

		return '<body class="'.$class.'">';
	}

	public function getNav($matches)
	{
		return $matches[0];
	}

	public function getSections($matches)
	{
		$class = 'grid_'.$this->columns['main']->size;
		if ($this->columns['main']->push) $class .= ' push_'.$this->columns['main']->push;
		if ($this->columns['main']->pull) $class .= ' pull_'.$this->columns['main']->pull;
		if (strpos($matches[1], 'class=')) {
			preg_match('/class="(.*)"/i', $matches[1], $classes);
			$class .= ' ' . $classes[1];
		}

		if (strpos($matches[1], 'class='))
			$main = preg_replace('/class=\".*\"/iU', 'class="'.$class.'"', $matches[0]);
		else
			$main = preg_replace('/<section/iU', '<section class="'.$class.'"', $matches[0]);

		return $main;
	}

	public function getAsides($matches)
	{
		// Get id and decide if to even bother
		preg_match('/id=\"(.*)\"/isU', $matches[1], $ids);
		$id = $ids[1];

		$doc = Wright::getInstance();
		if (!$doc->document->countModules($id))
			return;

		$class = 'grid_'.$this->columns[$id]->size;
		if ($this->columns[$id]->push) $class .= ' push_'.$this->columns[$id]->push;
		if ($this->columns[$id]->pull) $class .= ' pull_'.$this->columns[$id]->pull;
		if (strpos($matches[1], 'class=')) {
			preg_match('/class="(.*)"/i', $matches[1], $classes);
			$class .= ' ' . $classes[1];
		}

		if (strpos($matches[1], 'class='))
			$sidebar = preg_replace('/class=\".*\"/iU', 'class="'.$class.'"', $matches[0]);
		else
			$sidebar = preg_replace('/<aside/iU', '<aside class="'.$class.'"', $matches[0]);

		return $sidebar;
	}

	public function getFooter($matches)
	{

		$class = 'footer';

		if (strpos($matches[1], 'class=')) {
			preg_match('/class="(.*)"/i', $matches[1], $classes);
			$class .= ' ' . $classes[1];
		}

		if (strpos($matches[1], 'class='))
			$footer = preg_replace('/class=\".*\"/iU', 'class="'.$class.'"', $matches[0]);

		return $footer;
	}

	public function getHeader($matches)
	{

		$class = 'header';

		if (strpos($matches[1], 'class=')) {
			preg_match('/class="(.*)"/i', $matches[1], $classes);
			$class .= ' ' . $classes[1];
		}

		if (strpos($matches[1], 'class='))
			$header = preg_replace('/class=\".*\"/iU', 'class="'.$class.'"', $matches[0], 1);

		return $header;
	}

	private function setupColumns()
	{
		$doc = Wright::getInstance();

		// Get our column info straight
		$main = 0;
		$check = 0;
		$number = 0;
		$layout = array();
		foreach (explode(';', $doc->document->params->get('columns', 'sidebar1:3;main:6;sidebar2:3')) as $item)
		{
			list ($col, $val) = explode(':', $item);

			if ($col !== 'main' && $check == 0) $main++;
			else $check = 1;

			$this->columns[$col]->name = $col;
			$this->columns[$col]->size = $val;
			$this->columns[$col]->push = 0;
			$this->columns[$col]->pull = 0;
			$this->columns[$col]->check = $check;

			$number++;
			if ($doc->document->countModules($col) || $col == 'main')
					$layout[] = $col;
		}

		// Auto set to full width if editing
		if (JRequest::getVar('task') == 'edit' || JRequest::getVar('layout') == 'form') {
			empty($layout);
			$layout[] = 'main';
		}

		switch(implode('-', $layout))
		{
			case 'main':
				$this->columns['main']->size = 12;
				break;

			case 'main-sidebar1':
				$this->columns['main']->size = (12-$this->columns['sidebar1']->size);
				break;

			case 'sidebar1-main':
				$this->columns['main']->size = (12-$this->columns['sidebar1']->size);
				$this->columns['sidebar1']->pull = $this->columns['main']->size;
				$this->columns['main']->push = $this->columns['sidebar1']->size;
				break;

			case 'main-sidebar2':
				$this->columns['main']->size = (12-$this->columns['sidebar2']->size);
				break;

			case 'sidebar2-main':
				$this->columns['main']->size = (12-$this->columns['sidebar2']->size);
				$this->columns['sidebar2']->pull = $this->columns['main']->size;
				$this->columns['main']->push = $this->columns['sidebar2']->size;
				break;

			case 'main-sidebar2-sidebar1':
				$this->columns['sidebar2']->pull = $this->columns['sidebar1']->size;
				$this->columns['sidebar1']->push = $this->columns['sidebar2']->size;
				break;

			case 'sidebar2-main-sidebar1':
				$this->columns['main']->push = $this->columns['sidebar2']->size;
				$this->columns['sidebar2']->pull = $this->columns['main']->size + $this->columns['sidebar1']->size;
				$this->columns['sidebar1']->push = $this->columns['sidebar2']->size;
				break;

			case 'sidebar1-main-sidebar2':
				$this->columns['main']->push = $this->columns['sidebar1']->size;
				$this->columns['sidebar1']->pull = $this->columns['main']->size;
				break;

			case 'sidebar1-sidebar2-main':
				$this->columns['main']->push = $this->columns['sidebar1']->size + $this->columns['sidebar2']->size;
				$this->columns['sidebar2']->pull = $this->columns['main']->size;
				$this->columns['sidebar1']->pull = $this->columns['main']->size;
				break;

			case 'sidebar2-sidebar1-main':
				$this->columns['main']->push = $this->columns['sidebar1']->size + $this->columns['sidebar2']->size;
				$this->columns['sidebar2']->pull = $this->columns['main']->size +  $this->columns['sidebar1']->size;
				$this->columns['sidebar1']->pull = $this->columns['sidebar2']->size;
				break;
		}
	}

}