<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$data = $this->data;
$link = JRoute::_( "index.php?option=com_mcm&view=users&id={$data->id}" );
?>
<div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'ID' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->id; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'NAME' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->name; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'LAST_NAME' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->last_name; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'USERNAME' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->username; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'EMAIL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->email; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'PASSWORD' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->password; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'USERTYPE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->usertype; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'BLOCK' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->block; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'SENDEMAIL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->sendEmail; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'GID' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->gid; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'REGISTERDATE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->registerDate; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'LASTVISITDATE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->lastvisitDate; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'ACTIVATION' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->activation; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'PARAMS' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->params; ?></span>
	</div>

</div>
