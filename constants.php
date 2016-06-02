<?php
/**
 * Chronolabs Fonting Repository Services REST API API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         General Public License version 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @package         fonts
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Fonting Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */

	/**
	 *
	 * @var string
	 */
	define('API_VERSION', '2.0.9');
	
	/**
	 * 
	 * @var string
	 */
	define('FONT_RESOURCES_UNPACKING', '/media/alpha-filez-store/STILLS/Unpacking');
	define('FONT_RESOURCES_SORTING', '/media/alpha-filez-store/STILLS/Sorting');
	define('FONT_RESOURCES_CONVERTING', '/media/alpha-filez-store/STILLS/Converting');
	define('FONT_RESOURCES_RESOURCE', '/media/alpha-filez-store/STILLS/Resource');
	define('FONT_RESOURCES_CACHE', '/media/alpha-filez-store/STILLS/Cache');
	define('FONT_RESOURCES_STORE', 'https://sourceforge.net/p/chronolabsapis/svn/HEAD/tree/fonts.syd.labs.coop/data%20resources/%s?format=raw');
	define('FONT_UPLOAD_PATH', '/tmp/stills/uploads');
	
	/**
	 * Connects Global Database Objectivity
	 */
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'stills.php';
	
	/**
	 * Cache Indexing Meter
	 */
	define('CACHE_METER_USAGE', 4);
	global $hourindx, $hourprev;
	$hourindx = floor(date("H") / CACHE_METER_USAGE);
	$hourprev = floor(date("H", time() - (3600 * CACHE_METER_USAGE)) / CACHE_METER_USAGE);
?>