<?php 

/**
 * Joomla! 1.5 component mycomponent
 * Code generated by : Danny's Joomla! 1.5 MVC Component Code Generator
 * http://www.joomlafreak.be
 * date generated:  
 * @version 0.8
 * @author Danny Buytaert 
 * @package com_mycomponent
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );



// Require the base controller
  require_once (JPATH_COMPONENT.DS.'controller.php');
  
 $controller = new MycomponentController( );
 
// Perform the Request task
  $controller->execute( JRequest::getCmd('task'));
  $controller->redirect();
