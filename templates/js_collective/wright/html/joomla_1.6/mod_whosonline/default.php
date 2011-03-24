<?php

// no direct access
defined('_JEXEC') or die;

$guest = JText::plural('MOD_WHOSONLINE_GUESTS', $count['guest']);
$member = JText::plural('MOD_WHOSONLINE_MEMBERS', $count['user']);

if ($showmode == 0 || $showmode == 2) :
	echo '<p>'. JText::sprintf('MOD_WHOSONLINE_WE_HAVE', $guest, $member).'</p>';
endif;

if (($showmode > 0) && count($names)) : ?>
	<ul class="whosonline<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach($names as $name) : ?>

		<li>
		<?php if ($linknames==1) { ?>
		<a href="index.php?option=com_users&view=profile&member_id=<?php echo (int) $name->userid; ?>">
		<?php } ?>
		<?php echo $name->username; ?>
			<?php if ($linknames==1) : ?>
				</a>
			<?php endif; ?>
		</li>
<?php endforeach;  ?>
	</ul>
<?php endif;