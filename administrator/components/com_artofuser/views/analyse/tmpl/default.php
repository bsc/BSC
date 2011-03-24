<?php
/**
 * @version		$Id: default.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
?>
<table>
	<tbody>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_User_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.grandtotal');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_Activated_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.activated');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_Blocked_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.blocked');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_First_registration');?>
			</td>
			<td>
				<?php echo $this->data->get('registered.first');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_Last_registration');?>
			</td>
			<td>
				<?php echo $this->data->get('registered.last');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_ARO_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.aros');?>

				<a href="components/com_artofuser/help/en-GB/missing_aros.html" class="modal" rel="{handler: 'iframe'}" title="<?php echo JText::_('COM_ARTOFUSER_Analyse_Explain_Desc'); ?>">
					<?php echo JText::_('COM_ARTOFUSER_Analyse_Explain'); ?></a>
				<?php if ($this->data->get('count.grandtotal') != $this->data->get('count.aros')) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_artofuser&task=repair.aros');?>" onclick="return confirm('<?php echo JText::_('COM_ARTOFUSER_Confirm_repair');?>');">
						<?php echo JText::_('COM_ARTOFUSER_Analyse_Repair'); ?></a>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_Missing_Aros_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.missing-aros');?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo JText::_('COM_ARTOFUSER_Analyse_Missing_Group_maps_count');?>
			</td>
			<td>
				<?php echo $this->data->get('count.missing-group-maps');?>

				<a href="components/com_artofuser/help/en-GB/missing_group_aro_maps.html" class="modal" rel="{handler: 'iframe'}" title="<?php echo JText::_('COM_ARTOFUSER_Analyse_Explain_Desc'); ?>">
					<?php echo JText::_('COM_ARTOFUSER_Analyse_Explain'); ?></a>
				<?php if ($this->data->get('count.missing-group-maps') > 0) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_artofuser&task=repair.groupAroMaps');?>" onclick="return confirm('<?php echo JText::_('COM_ARTOFUSER_Confirm_repair');?>');">
						<?php echo JText::_('COM_ARTOFUSER_Analyse_Repair'); ?></a>
				<?php endif;?>
			</td>
		</tr>
	</tbody>
</table>

<?php echo JHtml::_('xuser.footer'); ?>