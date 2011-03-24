<?php
// no direct access
defined('_JEXEC') or die;
?>
<ul class="stats-module<?php echo $params->get('moduleclass_sfx') ?>">
<?php foreach ($list as $item) : ?>
	<li>
		<strong><?php echo $item->title;?></strong>
		<?php echo $item->data;?>
	</li>
<?php endforeach; ?>
</ul>

