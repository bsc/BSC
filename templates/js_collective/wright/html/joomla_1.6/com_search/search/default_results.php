<?php
// no direct access
defined('_JEXEC') or die;
?>

<ol class="search_results<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php foreach($this->results as $result) : ?>
	<li class="result-title">
		<?php echo $this->pagination->limitstart + $result->count.'. ';?>
		<h4>
		<?php if ($result->href) :?>
			<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>>
				<?php echo $this->escape($result->title);?>
			</a>
		<?php else:?>
			<?php echo $this->escape($result->title);?>
		<?php endif; ?>
		</h4>
	<?php if ($result->section) : ?>
		<p class="result-category">
			<br />
			<span class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
				(<?php echo $this->escape($result->section); ?>)
			</span>
		</>
	<?php endif; ?>
	<p class="result-text">
		<?php echo $result->text; ?>
	</p>
	<?php if ($this->params->get('show_date')) : ?>
		<span class="small<?php echo $this->params->get('pageclass_sfx'); ?>">
			<?php echo $result->created; ?>
		</span>
	<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
