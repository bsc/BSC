<?php
/**
 * @package     EasyTable
 * @Copyright   Copyright (C) 2010- Craig Phillips Pty Ltd.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @author      Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @created     13-Jul-2009
 */

/*
 * Frontend Component
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');
require_once(JPATH_COMPONENT.DS.'controllers'.DS.'easytable.php');

$controller = new EasyTableController();
$controller->execute( $task );
$controller->redirect();
