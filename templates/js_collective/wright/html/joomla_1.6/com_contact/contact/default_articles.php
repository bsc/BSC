<?php

defined('_JEXEC') or die;
?>
<?php if ($this->params->get('show_articles')) : ?>
<div class="jcontact-articles">

	<ol>
		<?php foreach ($this->item->articles as $article) :	?>
			<li>
				<a href="<?php $article->link = JRoute::_('index.php?option=com_content&view=article&id='.$article->id)?>">
				<?php echo $article->text = htmlspecialchars($article->title, ENT_COMPAT, 'UTF-8'); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ol>
</div>
<?php endif; ?>