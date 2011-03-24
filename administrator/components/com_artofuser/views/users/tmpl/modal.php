<?php
/**
 * @version		$Id: modal.php 556 2011-01-19 10:30:43Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');

$field		= JRequest::getCmd('field');
$function	= 'jSelectUser_'.$field;
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=users&layout=modal&tmpl=component');?>" method="post" name="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<label for="search"><?php echo JText::_('COM_ARTOFUSER_SEARCH_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_ARTOFUSER_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('COM_ARTOFUSER_SEARCH_GO_BUTTON'); ?></button>
			<button type="button" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('COM_ARTOFUSER_SEARCH_CLEAR_BUTTON'); ?></button>
		</div>
		<div class="right">
			<select name="filter_group_id" id="filter_group_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ARTOFUSER_OPTION_FILTER_GROUP_ID');?></option>
				<?php echo JHtml::_('select.options', ArtofUserHelper::getUsergroupOptions(), 'value', 'text', $this->state->get('filter.group_id'));?>
			</select>
		</div>
	</fieldset>
	<table class="adminlist">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th width="25%">
					<?php echo JText::_('COM_ARTOFUSER_HEADING_USERGROUP'); ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ARTOFUSER_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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
				<td>
					<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $function;?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->name)); ?>');">
						<?php echo $item->name; ?></a>
				</td>
				<td align="center">
					<?php echo $this->escape($item->usergroup_name); ?>
					<br /><small>(<?php echo (int) $item->usergroup_count;?>)</small>
				</td>
				<td align="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>