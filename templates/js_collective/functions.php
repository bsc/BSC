<?php
// Restrict Access to within Joomla
defined('_JEXEC') or die('Restricted access');

$load = false;
$styles = '';

// Adds custom styles to head
if ($this->document->params->get('customcolors', 'no') == 'yes') {
	$styles .= 'body{color:'.$this->params->get('fontColor').'}'.PHP_EOL;
	$styles .= 'h1, h2, h3, h4, h5, h6, .componentheading, .contentheading{color:'.$this->params->get('headingColor').'}'.PHP_EOL;
	$styles .= 'a:link, a:active, a:visited{color:'.$this->params->get('linkColor').'}'.PHP_EOL;
	$styles .= 'a:hover{color:'.$this->params->get('linkHoverColor').'}'.PHP_EOL;
	$load = true;
}
if(JRequest::getVar('task') == 'edit' || JRequest::getVar('layout') == 'form'){
	$styles .= '#main{width:100%; background:none;} #content{width:100%;} #sidebar1{display:none;} #sidebar2{display:none;}'.PHP_EOL;
	$load = true;
}
if ($load) $this->document->addStyleDeclaration($styles);