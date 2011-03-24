<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="random-image-module<?php echo $params->get('moduleclass_sfx'); ?>">
<?php if ($link) : ?>
<a href="<?php echo $link; ?>" target="_self">
<?php endif; ?>
	<?php echo JHTML::_('image', $image->folder.'/'.$image->name, $image->name, array('width' => $image->width, 'height' => $image->height)); ?>
<?php if ($link) : ?>
</a>
<?php endif; ?>
</div>