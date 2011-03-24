<?php // @version $Id: default.php 13 2010-11-05 16:28:16Z jeremy $
defined('_JEXEC') or die('Restricted access');
$cparams = JComponentHelper::getParams ('com_media');
?>

<?php if ( $this->params->get( 'show_page_title',1)): ?>
<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
<?php endif; ?>

<div class="newsfeed<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
	<?php if ( $this->category->image || $this->category->description ) : ?>
	<div class="contentdescription<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">

		<?php if ( $this->category->image ) : ?>
		<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path').'/'.$this->category->image; ?>" class="image_<?php echo $this->category->image_position; ?>" />
		<?php endif; ?>

		<?php if ( $this->params->get( 'description' ) ) :
			echo $this->category->description;
		endif; ?>

		<?php if ( $this->category->image ) : ?>
		<div class="wrap_image">&nbsp;</div>
		<?php endif; ?>

	</div>
	<?php endif; ?>

	<?php include(dirname(__FILE__).DS.'default_items.php'); ?>
</div>
