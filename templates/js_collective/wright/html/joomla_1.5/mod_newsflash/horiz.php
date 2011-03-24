<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<ul class="newsflash-module<?php echo $params->get('moduleclass_sfx') ?>">
	<?php foreach ($list as $item) : ?>
		<li>
			<?php modNewsFlashHelper::renderItem($item, $params, $access); ?>
		</li>
	<?php endforeach; ?>
</ul>