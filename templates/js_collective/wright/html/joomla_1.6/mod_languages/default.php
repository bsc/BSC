<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('stylesheet', 'mod_languages/template.css', array(), true);
?>
<div class="languages-module<?php echo $params->get('moduleclass_sfx') ?>">
<?php if ($headerText) : ?>
	<h4><?php echo $headerText; ?></h4>
<?php endif; ?>
		<ul>
<?php foreach($list as $language):?>
			<li>
				<a href="<?php echo JRoute::_('index.php?Itemid='.$language->id.'&lang=' . $language->sef);?>">
	<?php if ($params->get('image', 1)):?>
		<?php echo JHtml::_('image', 'mod_languages/'.$language->image.'.gif', $language->title, array('title'=>$language->title), true);?>
	<?php else:?>
		<?php echo $language->title;?>
	<?php endif;?>
				</a>
			</li>
<?php endforeach;?>
		</ul>
<?php if ($footerText) : ?>
	<p class="footer"><?php echo $footerText; ?></p>
<?php endif; ?>
</div>