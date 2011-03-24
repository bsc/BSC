<?php
/**
 * @version		$Id: default.php 546 2011-01-15 05:00:00Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::script('list.js', 'administrator/components/com_artofuser/media/js/');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=notes');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="search"><?php echo JText::_('COM_ARTOFUSER_SEARCH_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_ARTOFUSER_SEARCH_IN_NOTES'); ?>" />
			<button type="submit">
				<?php echo JText::_('COM_ARTOFUSER_SEARCH_GO_BUTTON'); ?></button>
			<button type="button" onclick="$('search').value='';$('filter_group_id').value='';$('filter_section_id').value='';$('filter_published').value='';this.form.submit();">
				<?php echo JText::_('COM_ARTOFUSER_SEARCH_CLEAR_BUTTON'); ?></button>
		</div>

		<div class="right">
			<select name="filter_section_id" id="filter_section_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_ALL_CATEGORIES');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getCategoryOptions(),
					'value', 'text', $this->state->get('filter.category_id'));?>
			</select>

			<select name="filter_published" id="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getPublishedOptions(),
					'value', 'text', $this->state->get('filter.published'));?>
			</select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="toggle" value="" class="checklist-toggle" />
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_USER_HEADING', 'user_name', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_SUBJECT_HEADING', 'a.subject', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_CATEGORY_HEADING', 'category_title', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_REVIEW_HEADING', 'a.review_time', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center checklist">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($item->checked_out) : ?>
						<?php echo JHtml::_('jxgrid.checkedout', $item->editor, $item->checked_out_time); ?>
					<?php endif; ?>
					<?php if ($this->canEditNote) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_artofuser&task=note.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->user_name); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->user_name); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($item->subject) : ?>
						<?php echo $this->escape($item->subject); ?>
					<?php else : ?>
						<?php echo JText::_('COM_ARTOFUSER_EMPTY_SUBJECT'); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if ($item->catid) : ?>
					<?php echo JHtml::_('artofuser.image', $item->category_image); ?>
					<?php endif; ?>
					<?php echo $this->escape($item->category_title); ?>
				</td>
				<td class="center">
					<?php if (intval($item->review_time)) : ?>
						<?php echo $this->escape($item->review_time); ?>
					<?php else : ?>
						<?php echo JText::_('COM_ARTOFUSER_EMPTY_REVIEW'); ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
