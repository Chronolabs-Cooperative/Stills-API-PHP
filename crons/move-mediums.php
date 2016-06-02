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

ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '128M');
include_once dirname(dirname(__FILE__)).'/functions.php';
include_once dirname(dirname(__FILE__)).'/class/fontages.php';
error_reporting(E_ERROR);
set_time_limit(7200);

$result = $GLOBALS['FontsDB']->queryF("SELECT * from `fonts` WHERE `medium` = 'FONT_RESOURCES' ORDER BY RAND() LIMIT 36");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	if (!is_dir(constant("FONT_RESOURCES_S3") . $row['path']))
		mkdir(constant("FONT_RESOURCES_S3") . $row['path'], 0777, true);
	if (copy(constant("FONT_RESOURCES") . $row['path'] . DIRECTORY_SEPARATOR . $row['filename'], constant("FONT_RESOURCES_S3") . $row['path'] . DIRECTORY_SEPARATOR . $row['filename']))
	{
		$GLOBALS['FontsDB']->queryF("UPDATE `fonts` SET `medium` = 'FONT_RESOURCES_S3' WHERE `id` = " . $row['id']);
		unlink(constant("FONT_RESOURCES") . $row['path'] . DIRECTORY_SEPARATOR . $row['filename']);
		echo "Font medium moved " . $row['filename'] . " - completed!<br/>\n";
	} 
}
exit(0);


?>