<?php
/**
 * @version		$Id: md5.php 353 2010-10-26 08:53:15Z eddieajau $
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @copyright	Copyright (C) 2008 - 2009 JXtended, LLC. All rights reserved.
 * @license		GNU General Public License
 * @link		http://jxtended.com
 */

defined('JPATH_BASE') or die('Restricted Access');

juimport('jxtended.form.formrule');

/**
 * Form Rule class for JXtended Libraries.
 *
 * @package		JXtended.Libraries
 * @subpackage	Form
 * @since		1.1
 */
class JFormRuleMd5 extends JFormRule
{
	/**
	 * The regular expression.
	 *
	 * @access	protected
	 * @var		string
	 * @since	1.1
	 */
	var $_regex = '^[A-Z0-9]{32}$';

	/**
	 * The regular expression modifiers.
	 *
	 * @access	protected
	 * @var		string
	 * @since	1.1
	 */
	var $_modifiers = 'i';
}