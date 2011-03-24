<?php

/*
 * You can change this to a normal override. This is just in place to help manage
 * the default set of overrides we have in our template framework. 
 */

$app =& JFactory::getApplication();

require_once(JPATH_THEMES.DS.$app->getTemplate().DS.'wright'.DS.'html'.DS.'overrider.php');
require_once(Overrider::getOverride('com_weblinks.form', 'form'));