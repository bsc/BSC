<?php
// no direct access
defined('_JEXEC') or die;
?>
<?php if ($params->get('item_title')) : ?>

	<<?php echo $params->get('item_heading'); ?> class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
	<?php if ($params->get('link_titles') && $item->link != '') : ?>
		<a href="<?php echo $item->link;?>">
			<?php echo $item->title;?></a>
	<?php else : ?>
		<?php echo $item->title; ?>
	<?php endif; ?>
	</<?php echo $params->get('item_heading'); ?>>

<?php endif; ?>

<?php if (!$params->get('intro_only')) :
	echo $item->afterDisplayTitle;
endif; ?>

<?php echo $item->beforeDisplayContent; ?>

<?php echo $item->introtext; ?>

<?php if (isset($item->link) && $item->readmore && $params->get('readmore')) :
	echo '<a class="readmore" href="'.$item->link.'">'.$item->linkText.'</a>';
endif; ?>
