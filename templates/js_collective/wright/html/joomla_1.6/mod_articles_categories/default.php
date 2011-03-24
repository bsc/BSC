<?php
// no direct access
defined('_JEXEC') or die;
?>
<ul class="categories-module<?php echo $params->get('moduleclass_sfx'); ?>">
<?php
require JModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default').'_items');
?></ul>
