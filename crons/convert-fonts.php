<?php
/**
 * Chronolabs Fontages API
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
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		cronjobs
 * @description		Screening API Service REST
 */


define("_CONVERT", dirname(__DIR__) . DIRECTORY_SEPARATOR . "fontforge -script " . dirname(__DIR__) . DIRECTORY_SEPARATOR . "convert-fonts.pe %s");

ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '128M');
include_once dirname(dirname(__FILE__)).'/functions.php';
include_once dirname(dirname(__FILE__)).'/class/fontages.php';
error_reporting(E_ERROR);
set_time_limit(7200);

$result = $GLOBALS['FontsDB']->queryF("SELECT * from `uploads` WHERE `uploaded` > '0' AND `converted` <= '0' ORDER BY RAND() LIMIT 99");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	echo shell_exec("cd " . $row['uploaded_path']);
	echo shell_exec(sprintf(_CONVERT, $row['uploaded_path'] . DIRECTORY_SEPARATOR . $row['uploaded_file']));
	sleep(5);
	if (count($files = getFileListAsArray($row['uploaded_path']))>=5)
	{
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `converted` = '".time()."', `archived` = '" . count($files) . "' WHERE `id` = " . $row['id']);
		echo "Font file " . $row['uploaded_file'] . ' - converted to ' . count($files) . " font files!<br/>\n";
	} else
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `converted` = `converted` - 1 WHERE `id` = " . $row['id']);
}
exit(0);


?>