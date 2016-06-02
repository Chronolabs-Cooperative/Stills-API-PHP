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

define("_ARCHIVE", dirname(__DIR__) . DIRECTORY_SEPARATOR . "zip -m %s %s*.*");

ini_set('display_errors', true);
ini_set('log_errors', true);
error_reporting(E_ERROR);
define('MAXIMUM_QUERIES', 25);
ini_set('memory_limit', '128M');
include_once dirname(dirname(__FILE__)).'/functions.php';
include_once dirname(dirname(__FILE__)).'/class/fontages.php';
set_time_limit(7200);

$result = $GLOBALS['FontsDB']->queryF("SELECT * from `uploads` WHERE `uploaded` > '0' AND `converted` > '0' AND `storaged` <= '0' ORDER BY RAND() LIMIT 99");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	if (!is_dir(constant("FONT_RESOURCES") . DIRECTORY_SEPARATOR . $row['converted_path']))
		mkdir(constant("FONT_RESOURCES") . DIRECTORY_SEPARATOR . $row['converted_path'], 0777, true);
	shell_exec($exec = sprintf(_ARCHIVE, $zipe = constant("FONT_RESOURCES") . DIRECTORY_SEPARATOR . $row['converted_path'] . substr($row['uploaded_file'], 0, strlen($row['uploaded_file'])-3) . 'zip', $row['uploaded_path'] . DIRECTORY_SEPARATOR));
	sleep(5);
	if (file_exists($zipe) && filesize($zipe) > 0)
	{
		$md5 = $font_id = md5_file($zipe);
		$result = $GLOBALS['FontsDB']->queryF("SELECT COUNT(*) as count from `fonts` WHERE `id` LIKE '$font_id'");
		list($count) = $GLOBALS['FontsDB']->fetchRow($result);
		if ($count == 0)
		{
			$nodes = getItemNodesArray($zipe, 2);
			$naming = array_keys($nodes['keys']);
			foreach(getFontTypes() as $type => $states)
			{
				foreach($naming as $kkey => $names)
					foreach($states as $state)
					if ($names == $state)
					unset($naming[$kkey]);
			}
			$name = ucwords(implode(" ", $naming));
			$GLOBALS['FontsDB']->queryF("INSERT INTO `fonts` (`id`, `name`, `path`, `filename`, `nodes`, `created`, `hits`, `normal`, `italic`, `bold`, `condensed`) VALUES('$font_id', '" . mysql_escape_string($name) . "', '" . mysql_escape_string(str_replace(constant("FONT_RESOURCES"), "", dirname($zipe))) . "', '" . mysql_escape_string(basename($zipe)) . "', '" . (count($nodes['keys']) + count($nodes['fixes']) + count($nodes['typal'])) . "','" . time() . "','0','" . isFontType($nodes, 'normal') . "','" . isFontType($nodes, 'italic') . "','" . isFontType($nodes, 'bold') . "','" . isFontType($nodes, 'condensed') . "')");
			foreach($nodes as $type => $noder)
			{
				foreach($noder as $node => $number)
				{
					if (!empty($node))
					{
						$result = $GLOBALS['FontsDB']->queryF("SELECT COUNT(*) as count, `id` from `nodes` WHERE `type` LIKE '$type' AND `node` LIKE '$node'");
						$row = $GLOBALS['FontsDB']->fetchArray($result);
						if ($row['count'] == 0)
						{
							$GLOBALS['FontsDB']->queryF("INSERT INTO `nodes` (`type`, `node`, `usage`, `weight`) VALUES('$type', '" . mysql_escape_string($node) . "', '1','1')");
							$row['id'] = $GLOBALS['FontsDB']->getInsertId();
						} else {
							$GLOBALS['FontsDB']->queryF("UPDATE `nodes` SET `usage` = `usage` +1 WHERE `id` = '".$row['id']."'");
						}
						$GLOBALS['FontsDB']->queryF("INSERT INTO `nodes_linking` (`font_id`, `node_id`) VALUES('$font_id', '".$row['id']."')");
					}
				}
			}
			foreach(getArchivedZIPContentsArray($file) as $fingerprint => $values)
			{
				$GLOBALS['FontsDB']->queryF("INSERT INTO `fonts_archiving` (`font_id`, `filename`, `bytes`, `type`, `fingerprint`, `hits`) VALUES('$font_id', '" . mysql_escape_string($values['filename']) . "',  '" . mysql_escape_string($values['bytes']) . "', '" . mysql_escape_string($values['type']) . "', '" . mysql_escape_string($fingerprint) . "','0')");
			}
			if ($GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `storaged` = '".time()."', `font_id` = '" . $font_id . "' WHERE `id` = " . $row['id']))
				echo "Font ZIP archive " . substr($row['uploaded_file'], 0, strlen($row['uploaded_file'])-3) . 'zip' . " - created and stores font files!<br/>\n";
		} else 
			$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `storaged` = `storaged` - 1 WHERE `id` = " . $row['id']);
	} else {
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `storaged` = `storaged` - 1 WHERE `id` = " . $row['id']);
		echo "Font ZIP archive " . substr($row['uploaded_file'], 0, strlen($row['uploaded_file'])-3) . 'zip' . " - failed too created too store font files: - Failed to execute :: $exec<br/>\n";
	}
}
exit(0);


?>