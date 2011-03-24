<?php

class WrightAdapterJoomlaNav
{
	public function render($args)
	{
		// Set module name
		if (!isset($args['name'])) $args['name'] = 'menu';
		// Set module name
		if (!isset($args['style'])) $args['style'] = 'raw';
		// Set module name
		if (!isset($args['class'])) $args['class'] = 'grid_12';
		// Set module name
		if (!isset($args['wrapclass'])) $args['wrapclass'] = '';

		$nav = '<div id="'.$args['name'].'_wrap"';
		if (isset($args['wrapclass'])) $nav .= ' class="'.$args['wrapclass'].'"';
		$nav .= '>
	<nav id="'.$args['name'].'" class="'.$args['class'].'">
		<jdoc:include type="modules" name="'.$args['name'].'" style="'.$args['style'].'" />
	</nav>
</div>';
		return $nav;
	}
}