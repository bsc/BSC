<?php
defined('_JEXEC') or die;
?>
<?php if ($this->params->get('show_profile')) : ?>
<div class="jcontact-profile">
	<ol>
		<?php foreach ($this->contact->profile as $profile) :	?>
			<li>

				<?php echo $profile->text = htmlspecialchars($profile->profile_value, ENT_COMPAT, 'UTF-8'); ?>

			</li>
		<?php endforeach; ?>
	</ol>
</div>
<?php endif; ?>
