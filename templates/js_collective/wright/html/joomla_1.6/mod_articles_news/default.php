<?php
// no direct access
defined('_JEXEC') or die;
?>
<div class="newsflash<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($list as $item) :?>
	<?php
	 require JModuleHelper::getLayoutPath('mod_articles_news', '_item');?>
<?php endforeach; ?>
</div>
