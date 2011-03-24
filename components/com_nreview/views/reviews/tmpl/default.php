<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$data = $this->data;
$link = JRoute::_( "index.php?option=com_nreview&view=reviews&id={$data->id}" );
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
		<span class="jcb_fieldLabel"><?php echo JText::_( 'ADDRESS' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->address; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'RESERVATIONS' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->reservations; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'QUICKTAKE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->quicktake; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'REVIEW' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->review; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'NOTES' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->notes; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'SMOKING' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->smoking; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'CREDIT_CARDS' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->credit_cards; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'CUISINE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->cuisine; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'AVG_DINNER_PRICE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->avg_dinner_price; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'REVIEW_DATE' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->review_date; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'PUBLISHED' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->published; ?></span>
	</div>

</div>
