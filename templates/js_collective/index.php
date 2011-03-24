<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Joomlashack LLC
 * @author		Jeremy Wilken
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the framework
require(dirname(__FILE__).DS.'wright'.DS.'wright.php');

// Initialize the framework and
$tpl = Wright::getInstance();
$tpl->display();

/**
 * Expected to see a template file here? Well this template is just a little
 * different. In order to provide some extra features, we've altered a few
 * little things about how Joomla templates work.
 *
 * Below are some common questions and answers if you want to get started
 * making some customizations.
 */

/**
 * Question: How do I edit the template HTML code?
 *
 * Answer: The best thing to do is to copy the 'template.php file', then rename
 * the copy to 'custom.php'. The framework will recognize you have a customized
 * version, and load that file rather than the default 'template.php' file.
 * Then you can edit the code inside of custom.php as much as you wish, and
 * still be able to update the template without overriding changes.
 */