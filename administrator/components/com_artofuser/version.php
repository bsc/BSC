<?php
/**
 * @version		$Id: version.php 557 2011-01-19 10:34:49Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserVersion
{
	/**
	 * Extension name string.
	 *
	 * @var		string
	 */
	const EXTENSION	= 'com_artofuser';

	/**
	 * Major.Minor version string.
	 *
	 * @var		string
	 */
	const VERSION	= '1.1';

	/**
	 * Maintenance version string.
	 *
	 * @var		string
	 */
	const SUBVERSION	= '1';

	/**
	 * Version status string.
	 *
	 * @var		string
	 */
	const STATUS		= '';

	/**
	 * Version release time stamp.
	 *
	 * @var		string
	 */
	const DATE		= '2011-01-18 00:00:03';

	/**
	 * Source control revision string.
	 *
	 * @var		string
	 */
	const REVISION	= '$Revision: 557 $';

	/**
	 * Method to get the build number from the source control revision string.
	 *
	 * @return	integer	The version build number.
	 * @since	1.0
	 */
	public static function getBuild()
	{
		return intval(substr(self::REVISION, 11));
	}

	/**
	 * Gets the version number.
	 *
	 * @param	boolean	$build	Optionally show the build number.
	 * @param	boolean	$status	Optionally show the status string.
	 *
	 * @return	string
	 * @since	1.0.3
	 */
	public static function getVersion($build = false, $status = false)
	{
		$text = self::VERSION.'.'.self::SUBVERSION;

		if ($build) {
			$text .= ':'.self::getBuild();
		}
		if ($status) {
			$text .= ' '.self::STATUS;
		}

		return $text;
	}
}