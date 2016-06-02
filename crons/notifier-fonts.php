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
include_once dirname(dirname(__FILE__)).'/class/fontsmailer.php';
include_once dirname(dirname(__FILE__)).'/class/fontages.php';
error_reporting(E_ERROR);
set_time_limit(7200);

$result = $GLOBALS['FontsDB']->queryF("SELECT * from `uploads` WHERE `uploaded` > '0' AND `converted` > '0' AND `storaged` > '0' AND (`cleaned` > '0' OR `cleaned` < '24') AND (`notified` <= '0' AND `notified` >= '-26') ORDER BY RAND() LIMIT 99");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	$font = $GLOBALS['FontsDB']->fetchArray($GLOBALS['FontsDB']->queryF("SELECT * from `fonts` WHERE `id` = '" . $row['font_id'] . "'"));
	$values = array();
	$values["X_EMAIL"] = $row['email'];
	$values["X_FONTID"] = $row['font_id'];
	$values["X_FONTFILE"] = $row['uploaded_file'];
	$values["X_FONTZIP"] = $font['filename'];
	$values["X_FONTNAME"] = $font['name'];
	$values["X_FONTMETA"] = '<link rel="stylesheet" href="http://fonts.labs.coop/v1/font/'.$row['font_id'].'/css.api" type="text/css">';
	$fonts = array();
	foreach(array('eot','woff','otf','ttf','svg','afm') as $fonttype)
	{
		$fonts[$fonttype] = "http://fonts.labs.coop/v1/font/" . $row['font_id'] . "/$fonttype.api";
	}
	$values["X_FONTCSS"] = implode("\n", generateCSS($fonts, $font['name'], $font['normal'], $font['bold'], $font['italics']));
	$fonts = array();
	$fontfiles = $GLOBALS['FontsDB']->queryF("SELECT * from `fonts_archiving` WHERE `font_id` = '" . $row['font_id'] . "'");
	while($fontio = $GLOBALS['FontsDB']->fetchArray($fontfiles))
		$fonts[$fontio['type']] = "/fonts/" . $fontio['filename'];
	$values["X_FONTCSSZIP"] = implode("\n", generateCSS($fonts, $font['name'], $font['normal'], $font['bold'], $font['italics']));
	$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'notice-email.html');
	foreach($values as $key => $insert)
		$html = str_replace("{$key}", $insert, $html);
	$email = new FontsMailer('chrono.labs.coop@gmail.com', 'Font API - Successful Import of ' . $font['name']);
	if ($email->sendMail(array(0=>array('email' => $row, 'name' =>$row)), array(), array(), 'Font API :: Successful Import of ' . $font['name'], $html, array(constant($font['medium']) . $font['path'] . DIRECTORY_SEPARATOR . $font['filename']), '', true))
	{
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = '" . time() . "') WHERE `id` = " . $row['id']);
	} else
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = `notified` - 1 WHERE `id` = " . $row['id']);
}

$result = $GLOBALS['FontsDB']->queryF("SELECT * from `uploads` WHERE `uploaded` > '0' AND `converted` > '0' AND `storaged` < '24' ORDER BY RAND() LIMIT 99");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	$values = array();
	$values["X_EMAIL"] = $row['email'];
	$values["X_FONTFILE"] = $row['uploaded_file'];
	$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fail-admin.html');
	foreach($values as $key => $insert)
		$html = str_replace("{$key}", $insert, $html);
	$email = new FontsMailer('chrono.labs.coop@gmail.com', 'Font API :: Unsuccessful Import of ' . $row['uploaded_file']);
	if ($email->sendMail(array(0=>array('email' => 'chrono.labs.coop@gmail.com', 'name' => 'chrono.labs.coop@gmail.com')), array(), array(), 'Font API :: Unsuccessful Import of ' . $row['uploaded_file'], $html, array(), '', true))
	{
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = '" . time() . "') WHERE `id` = " . $row['id']);
	} else
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = `notified` - 1 WHERE `id` = " . $row['id']);
}


$result = $GLOBALS['FontsDB']->queryF("SELECT * from `uploads` WHERE `uploaded` < '24' OR `converted` < '24' ORDER BY RAND() LIMIT 99");
while($row = $GLOBALS['FontsDB']->fetchArray($result))
{
	$values = array();
	$values["X_EMAIL"] = $row['email'];
	$values["X_FONTFILE"] = $row['uploaded_file'];
	$html = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fail-admin.html');
	foreach($values as $key => $insert)
		$html = str_replace("{$key}", $insert, $html);
	$email = new FontsMailer('chrono.labs.coop@gmail.com', 'Font API :: Unsuccessful Import of ' . $row['uploaded_file']);
	if ($email->sendMail(array(0=>array('email' => 'chrono.labs.coop@gmail.com', 'name' => 'chrono.labs.coop@gmail.com')), array(), array(), 'Font API :: Unsuccessful Import of ' . $row['uploaded_file'], $html, array(), '', true))
	{
		if ($GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = '" . time() . "', `cleaned` = '" . time() . "') WHERE `id` = " . $row['id']))
		{
			foreach(getFileListAsArray($row['uploaded_path']) as $file)
				unlink($row['uploaded_path'] . DIRECTORY_SEPARATOR > $file);
			rmdir($row['uploaded_path']);
		}
	} else
		$GLOBALS['FontsDB']->queryF("UPDATE `uploads` SET `notified` = `notified` - 1 WHERE `id` = " . $row['id']);
}

exit(0);


?>