<?php
defined('_JEXEC') or die;

if ($this->user->get('guest')):
	// The user is not logged in.
	include('default_login.php');
else:
	// The user is already logged in.
	include('default_logout.php');
endif;
