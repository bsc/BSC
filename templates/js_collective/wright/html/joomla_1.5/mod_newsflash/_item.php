<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="newsflash-item<?php echo $params->get( 'moduleclass_sfx' ); ?>">
<?php if ($params->get('item_title')) : ?>
	<h4>
	<?php if ($params->get('link_titles') && $item->linkOn != '') : ?>
		<a href="<?php echo $item->linkOn;?>" class="contentpagetitle<?php echo $params->get( 'moduleclass_sfx' ); ?>">
			<?php echo $item->title;?></a>
	<?php else : ?>
		<?php echo $item->title; ?>
	<?php endif; ?>
	</h4>

<?php endif; ?>

<?php if (!$params->get('intro_only')) :  echo $item->afterDisplayTitle; endif; ?>

<?php echo $item->beforeDisplayContent; ?>

	<?php echo $item->text; ?>

   <?php if (isset($item->linkOn) && $item->readmore && $params->get('readmore')) :
	  echo '<p><a class="readmore" href="'.$item->linkOn.'">'.$item->linkText.'</a></p>';
	endif; ?>
</div>