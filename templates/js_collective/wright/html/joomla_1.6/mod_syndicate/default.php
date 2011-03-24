<?php
// no direct access
defined('_JEXEC') or die;
?>
<a href="<?php echo $link ?>" class="syndicate-module<?php echo $params->get('moduleclass_sfx') ?>">
	<?php echo JHTML::_('image','system/livemarks.png', 'feed-image', NULL, true); ?> <span><?php echo $params->get('text') ?></span></a>
