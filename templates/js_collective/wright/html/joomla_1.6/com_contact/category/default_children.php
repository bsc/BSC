<?php

// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->children[$this->category->id]) > 0) :
?>
<ul>
<?php foreach($this->children[$this->category->id] as $id => $child) : ?>
	<?php
	if($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
	if(!isset($this->children[$this->category->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?>>
		<?php $class = ''; ?>
			<span class="jitem-title"><a href="<?php echo JRoute::_(ContactHelperRoute::getCategoryRoute($child->id));?>">
				<?php echo $this->escape($child->title); ?></a>
			</span>
			<?php if ($child->description) : ?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description); ?>
				</div>
			<?php endif; ?>
			<?php if(count($child->getChildren()) > 0 && $this->params->get('show_children',0) == 1) :
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				$this->maxLevel--;
				include('default_children.php');
				$this->category = $child->getParent();
				$this->maxLevel++;
			endif; ?>
		</li>
	<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endif;