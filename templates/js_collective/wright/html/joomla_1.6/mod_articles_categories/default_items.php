<?php
// no direct access
defined('_JEXEC') or die;

foreach ($list as $item) : ?>
	<li>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($item->id)); ?>">
		<?php echo $item->title;?></a>
		<?php
		if($params->get('show_description', 0))
		{
			echo JHTML::_('content.prepare',$item->description, $item->getParams());
		}
		if($params->get('show_children', 0) && (($params->get('maxlevel', 0) == 0) || ($params->get('maxlevel') >= ($item->level - $startLevel))) && count($item->getChildren()))
		{
			echo '<ul>';
			$temp = $list;
			$list = $item->getChildren();
			require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default').'_items');
			$list = $temp;
			echo '</ul>';
		}
		?>
	</li>
<?php endforeach; ?>