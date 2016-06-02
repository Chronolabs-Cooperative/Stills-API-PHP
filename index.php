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
 * @subpackage		api
 * @description		Screening API Service REST
 */

	error_reporting(E_ALL);
	ini_set('display_errors', true);
	
	require_once dirname(__FILE__).'/functions.php';
	require_once dirname(__FILE__).'/class/stills.php';
	
	$parts = explode(".", microtime(true));
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	mt_srand(mt_rand(-microtime(true), microtime(true))/$parts[1]);
	if (!session_id())
		session_start();
	if (!isset($_SESSION['salter']))
		$_SESSION['salter'] = ((float)(mt_rand(0,1)==1?'':'-').$parts[1].'.'.$parts[0]) / sqrt((float)$parts[1].'.'.intval(cosh($parts[0])))*tanh($parts[1]) * mt_rand(1, intval($parts[0] / $parts[1]));
	header('Blowfish-salt: '. $_SESSION['salter']);
	
	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source;
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';
	
	define('STILLS_CACHE', DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'stills' . DIRECTORY_SEPARATOR . 'cache');
	if (!is_dir(FONTS_CACHE))
		mkdirSecure(FONTS_CACHE, 0777, true);
	
	
	/**
	 * Global API Configurations and Setting from file Constants!
	 */
	$domain = getDomainSupportism('domain', $_SERVER["HTTP_HOST"]);
	$protocol = getDomainSupportism('protocol', $_SERVER["HTTP_HOST"]);
	$business = getDomainSupportism('business', $_SERVER["HTTP_HOST"]);
	$entity = getDomainSupportism('entity', $_SERVER["HTTP_HOST"]);
	$contact = getDomainSupportism('contact', $_SERVER["HTTP_HOST"]);
	$referee = getDomainSupportism('referee', $_SERVER["HTTP_HOST"]);
	
	/**
	 * Peering Sessions
	 */
	$peerings = getPeersSupporting();
	
	/**
	 * URI Path Finding of API URL Source Locality
	 * @var unknown_type
	 */
	$pu = parse_url($_SERVER['REQUEST_URI']);
	$source = (isset($_SERVER['HTTPS'])?'https://':'http://').strtolower($_SERVER['HTTP_HOST']).$pu['path'];
	
	
	$help=true;
	if (isset($_GET['output']) || !empty($_GET['output'])) {
		$output = isset($_GET['output'])?(string)$_GET['output']:'';
		$name = isset($_GET['name'])?(string)$_GET['name']:'';
		$clause = isset($_GET['clause'])?(string)$_GET['clause']:'';
		$mode = isset($_GET['mode'])?(string)$_GET['mode']:'';
		$state = isset($_GET['state'])?(string)$_GET['state']:'';
		switch($output)
		{
				
		}
	} else {
		$help=true;
	}
	
	if ($help==true) {
		http_response_code(400);
		include dirname(__FILE__).'/help.php';
		exit;
	}
	exit(0);
	
	switch($output)
	{
		case "raw":
		case "html":
		case "serial":
		case "json":
		case "xml":
			switch ($mode)
			{
				case "nodes":
					$data = @getNodesListArray($clause, $output);
					break;
				case "fonts":
					$data = @getLibrariesListArray($clause, $output);
					break;
			}
			break;
		case "profile":
			$data = '';
			break;
		case "ttf":
		case "eot":
		case "otf":
		case "svg":
		case "afm":
		case "woff":
			$data = getFontRawData($mode, $clause, $output);
			break;
		case "css":
			$data = getCSSListArray($mode, $clause, $state, $name, $output);
			break;	
		case "preview":
			http_response_code(400);
			$data = getPreviewHTML($mode, $clause, $state, $name, $output);
			break;		
	}
	http_response_code(200);
	switch ($output) {
		default:
			header('Content-type: ' . getMimetype('html'));
			echo '<h1>' . $country . ' - ' . $place . ' (Places data)</h1>';
			echo '<pre style="font-family: \'Courier New\', Courier, Terminal; font-size: 0.77em;">';
			echo implode("\n", $data);
			echo '</pre>';
			break;
		case 'raw':
			header('Content-type: ' . getMimetype('text'));
			echo implode("} | {", $data);
			break;
		case 'json':
			header('Content-type:  ' . getMimetype($output));
			echo json_encode($data);
			break;
		case 'serial':
			header('Content-type:  ' . getMimetype($output));
			echo serialize($data);
			break;
		case 'xml':
			header('Content-type:  ' . getMimetype($output));
			$dom = new XmlDomConstruct('1.0', 'utf-8');
			$dom->fromMixed(array('root'=>$data));
 			echo $dom->saveXML();
			break;
		case "ttf":
		case "eot":
		case "otf":
		case "svg":
		case "afm":
		case "woff":
			header('Content-type: ' . getMimetype($output));
			echo $data;
			break;
		case "css":
			header('Content-type: ' . getMimetype($output));
			echo implode("\n\n", $data);
			break;
		case "preview":
			header('Content-type: ' . getMimetype('html'));
			echo $data;
			break;
			break;
	}
?>		