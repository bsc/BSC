<?php
// no direct access
defined('_JEXEC') or die;
?>
<ul class="newsflash-module<?php echo $params->get('moduleclass_sfx'); ?>">
<?php for ($i = 0, $n = count($list); $i < $n; $i ++) :
	$item = $list[$i]; ?>
	<li class="newsflash-item">
	<?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item');
	if ($n > 1 && (($i < $n - 1) || $params->get('showLastSeparator'))) : ?>
		<span class="article_separator">&#160;</span>
	<?php endif; ?>
	</li>
<?php endfor; ?>
</ul>
