<?php
/**
 * @version		$Id: default.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
JHtml::_('behavior.tooltip');
?>
<div class="unotes">
	<h1><?php echo JText::sprintf('COM_ARTOFUSER_NOTES_FOR_USER', $this->user->name, $this->user->id); ?></h1>
<?php if (empty($this->items)) : ?>
	<?php echo JText::_('COM_ARTOFUSER_NO_NOTES'); ?>
<?php else : ?>
	<ol>
	<?php foreach ($this->items as $i => $item) : ?>
		<li class="<?php echo $i % 2 ? 'o' : 'e'; ?>">
			<div class="fltlft">
				<?php if ($item->subject) : ?>
					<h4><?php echo JText::sprintf('COM_ARTOFUSER_NOTE_N_SUBJECT', $item->id, $this->escape($item->subject)); ?></h4>
				<?php else : ?>
					<h4><?php echo JText::sprintf('COM_ARTOFUSER_NOTE_N_SUBJECT', $item->id, JText::_('COM_ARTOFUSER_EMPTY_SUBJECT')); ?></h4>
				<?php endif; ?>
			</div>

			<div class="fltlft">
				<?php echo JHtml::date($item->created_time, '%A %d %B %Y %H:%M', $this->tz); ?>
			</div>

			<?php if ($item->catid) : ?>
			<div class="fltrgt">
				<?php echo JHtml::_('artofuser.image', $item->category_image); ?>
			</div>
			<?php endif; ?>

			<div class="clr"></div>

			<?php echo $item->body; ?>
		</li>
	<?php endforeach; ?>
	</ol>
<?php endif; ?>
</div>