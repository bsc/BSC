<?php
/**
 * @version		$Id: default.php 554 2011-01-19 10:29:50Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHTML::script('checkall.js', 'administrator/components/com_artofuser/media/js/');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
JHTML::_('behavior.tooltip');

$orderCol	= $this->escape($this->state->get('list.ordering'));
$orderDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=users');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="search"><?php echo JText::_('COM_ARTOFUSER_SEARCH_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_ARTOFUSER_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('COM_ARTOFUSER_SEARCH_GO_BUTTON'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_ARTOFUSER_SEARCH_CLEAR_BUTTON'); ?></button>
		</div>
		<div class="right">
			<select name="filter_blocked" id="filter_blocked" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_FILTER_BLOCKED');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getBlockedOptions(), 'value', 'text', $this->state->get('filter.blocked'));?>
			</select>

			<select name="filter_activation" id="filter_blocked" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_FILTER_ACTIVATION');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getActivationOptions(), 'value', 'text', $this->state->get('filter.activation'));?>
			</select>

			<select name="filter_group_id" id="filter_group_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_FILTER_GROUP_ID');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getUsergroupOptions(), 'value', 'text', $this->state->get('filter.group_id'));?>
			</select>

			<select name="filter_range" id="filter_range" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_FILTER_DATE');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getRangeOptions(), 'value', 'text', $this->state->get('filter.range'));?>
			</select>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items);?>)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_NAME', 'a.name', $orderDirn, $orderCol); ?>
					<br />(<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_USERNAME', 'a.username', $orderDirn, $orderCol); ?>)
					- <?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_EMAIL', 'a.email', $orderDirn, $orderCol); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_ENABLED', 'a.block', $orderDirn, $orderCol); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_ACTIVATED', 'a.activation', $orderDirn, $orderCol); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_ARTOFUSER_HEADING_USERGROUP'); ?>
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_LAST_VISIT', 'a.lastvisitDate', $orderDirn, $orderCol); ?>
					<br />(<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_REGISTERED', 'a.registerDate', $orderDirn, $orderCol); ?>)
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_ID', 'a.id', $orderDirn, $orderCol); ?>
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
					<?php echo JHTML::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<div class="fltrgt">
						<?php echo JHtml::_('artofuser.filterNotes', $item->note_count, $item->id); ?>
						<?php echo JHtml::_('artofuser.notes', $item->note_count, $item->id); ?>
						<?php echo JHtml::_('artofuser.addNote', $item->id); ?>
					</div>

					<a href="<?php echo JRoute::_('index.php?option=com_artofuser&task=user.edit&user_id='.$item->id);?>">
						<?php echo $this->escape($item->name); ?></a>
					<br /><small>(<?php echo $this->escape($item->username);?>)</small>
					- <small><a href="mailto:<?php echo $item->email;?>"><?php echo $this->escape($item->email);?></a></small>
				</td>
				<td align="center">
					<?php echo JHtml::_('artofuser.enabled', $i, (int) !$item->block, $item->block_category); ?>
				</td>
				<td align="center">
					<?php echo JHtml::_('artofuser.activated', $i, empty($item->activation)); ?>
				</td>
				<td align="center">
					<?php echo $this->escape($item->usergroup_name); ?>
					<br /><small>(<?php echo (int) $item->usergroup_count;?>)</small>
				</td>
				<td align="center">
					<?php if (intval($item->lastvisitDate)) : ?>
						<?php echo JHtml::date($item->lastvisitDate, '%d-%b-%Y %H:%I:%S'); ?>
					<?php else : ?>
						<?php echo JText::_('COM_ARTOFUSER_NEVER'); ?>
					<?php endif; ?>
					<br /><small>(<?php echo JHtml::date($item->registerDate, '%d-%b-%Y');?>)</small>
				</td>
				<td align="center">
					<?php echo (int) $item->id; ?>
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