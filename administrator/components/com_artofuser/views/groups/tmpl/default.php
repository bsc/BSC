<?php
/**
 * @version		$Id: default.php 548 2011-01-18 22:08:23Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

$editId = 30;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::script('checkall.js', 'administrator/components/com_artofuser/media/js/');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
JHTML::_('behavior.tooltip');

$orderCol	= $this->escape($this->state->get('list.ordering'));
$orderDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=groups&model=group');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="search"><?php echo JText::_('COM_ARTOFUSER_SEARCH_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="60" title="<?php echo JText::_('COM_ARTOFUSER_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('COM_ARTOFUSER_SEARCH_GO_BUTTON'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_ARTOFUSER_SEARCH_CLEAR_BUTTON'); ?></button>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="left">
					<?php echo JText::_('COM_ARTOFUSER_Heading_Group_name'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_ARTOFUSER_Heading_Users_in_group'); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JText::_('COM_ARTOFUSER_Heading_ID'); ?>
				</th>
				<th width="40%">
					&nbsp;
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
				<td style="text-align:center">
					<?php if ($item->id > $editId) : ?>
						<?php echo JHTML::_('grid.id', $i, $item->id); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php echo str_repeat('<span class="gi">|&mdash;</span>', max(0, $item->level - 2)); ?>
					<?php if ($item->id > $editId) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_artofuser&task=group.edit&group_id='.$item->id);?>">
						<?php echo $item->name; ?></a>
					<?php else : ?>
					<?php echo $item->name; ?>
					<?php endif; ?>
				</td>
				<td align="center">
					<?php echo $item->user_count; ?>
				</td>
				<td align="center">
					<?php echo $item->id; ?>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $orderCol; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php echo JHtml::_('artofuser.footer'); ?>