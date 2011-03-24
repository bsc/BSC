<?php
/**
 * @version		$Id: default.php 557 2011-01-19 10:34:49Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');

// Initialise variables.
$db			= $this->get('Dbo');
$jVersion	= new JVersion;

// Pre-compute server information.
if (isset($_SERVER['SERVER_SOFTWARE'])) {
	$server = $_SERVER['SERVER_SOFTWARE'];
}
else  {
	$sf = getenv('SERVER_SOFTWARE');
	if ($sf) {
		$server = $sf;
	}
	else {
		$server = 'Not applicable.';
	}
}
?>
	<h1>
		<?php echo JText::_('COM_ARTOFUSER_TITLE');?>
	</h1>

	<p>
		<?php echo JText::_('COM_ARTOFUSER_DESC'); ?>
	</p>

	<p>
		<a href="http://www.theartofjoomla.com/extensions/artof-user.html" target="_blank">
			http://www.theartofjoomla.com/extensions/artof-user.html</a>.
	</p>

	<h2>
		<?php echo JText::_('COM_ARTOFUSER_ABOUT_SUPPORT');?>
	</h2>

	<p>
		<?php echo JText::_('COM_ARTOFUSER_ABOUT_SUPPORT_DESC');?><p>

	<textarea style="width:100%;font-family:monospace;" onclick="this.focus();this.select();" rows="10">
Joomla   : <?php echo $jVersion->getLongVersion(); ?>

Software : <?php echo ArtofUserVersion::getVersion(true, true); ?>

Server   : <?php echo $server; ?>

PHP      : <?php echo phpversion(); ?>

Database : <?php echo $db->getVersion(); ?> <?php echo $db->getCollation(); ?>

Browser  : <?php echo htmlspecialchars(phpversion() <= '4.2.1' ? getenv('HTTP_USER_AGENT') : $_SERVER['HTTP_USER_AGENT'], ENT_QUOTES); ?>

Platform : <?php echo php_uname(); ?> <?php echo php_sapi_name(); ?>
	</textarea>

	<h2>
		<?php echo JText::_('COM_ARTOFUSER_ABOUT_CHANGELOG');?>
	</h2>

	<dl>
		<dt>Version 1.1.1 - 19 January 2011</dt>
		<dd>
			<ul>
				<li>Fixed bugs in helper authorise method.</li>
				<li>Fixed bugs in note saving, trashing and deleting.</li>
				<li>Fixed bug that showed notes icon in user list when notes were all trashed.</li>
				<li>Removed checkbox from modal user list.</li>
				<li>If user has never logged in, list displays "Never" instead of 0 unix time.</li>
				<li>Fixed buggy path to the html helpers in config.xml.</li>
			</ul>
		</dd>

		<dt>Version 1.1.0 - 15 January 2011</dt>
		<dd>
			<ul>
				<li>Add support for showing blocked reason when someone tries to log in.</li>
				<li>Fixed low level XSS exploit in list user list view.</li>
				<li>Added core management permission to control access to how can use the extension.</li>
				<li>Fixed low level path disclosure issue with list sorting variables.</li>
				<li>Added user notes feature.</li>
				<li>Added UI to add notes and blocked category when blocking a user.</li>
				<li>Fixed [#23192] New user email subject and body not translating.</li>
				<li>Fixed [#23230] Notice: Undefined property: stdClass::$level (Tim Plummer).</li>
				<li>Fixed [#23225] JSuccess_N_items_deleted not translated.</li>
				<li>Added new configuration variables to allow new user emails to be sent without the password.</li>
				<li>Fixed save validation bugs in user and group models.</li>
				<li>Fixed bug in user parameters where could not select no value (ie, the system default).</li>
			</ul>
		</dd>

		<dt>Version 1.0.6 - 9 November 2010</dt>
		<dd>
			<ul>
				<li>Fixed bug causing a database error when trying to delete a group.</li>
				<li>Fixed bugs in edit forms sometimes redirecting to empty pages.</li>
				<li>Fixed bug that prevented a user from being deleted.</li>
				<li>Updated several internal help links and copyright statements.</li>
			</ul>
		</dd>

		<dt>Version 1.0.5 - 28 October 2010</dt>
		<dd>
			<ul>
				<li>Fixed bug when trying to sort the user list by name or username.</li>
				<li>Fixed low level XSS vulnerability in search filter text.</li>
			</ul>
		</dd>

		<dt>Version 1.0.4 - 27 October 2010</dt>
		<dd>
			<ul>
				<li>Fix auto-population of password in Firefox and Chrome (again).</li>
				<li>Fix bug when filtering by registration dates.</li>
			</ul>
		</dd>

		<dt>Version 1.0.3 - 22 October 2010</dt>
		<dd>
			<ul>
				<li>Added French language files (thanks to Stéphane Bourderiou).</li>
				<li>Added support information to About page.</li>
			</ul>
		</dd>

		<dt>Version 1.0.2 - 15 October 2010</dt>
		<dd>
			<ul>
				<li>Fix missing language strings.</li>
				<li>Fix auto-population of password in Firefox and Chrome.</li>
				<li>Improve look of groups tree in user edit form.</li>
				<li>Fix default value for blocked user.</li>
				<li>Fix broken 'New' button in user list toolbar.</li>
			</ul>
		</dd>

		<dt>Version 1.0.1 - 14 October 2010</dt>
		<dd>
			<ul>
				<li>Fix performance issues in user and groups list with sites with a large number of users.</li>
				<li>Make identations of groups to be like Joomla 1.6.</li>
				<li>Added onContentPrepareForm trigger that allows for custom fieldsets to be displayed in the suer edit form.</li>
			</ul>
		</dd>

		<dt>Version 1.0.0 - 8 October 2010</dt>
		<dd>
			<ul>
				<li>Initial release.</li>
			</ul>
		</dd>
	</dl>
