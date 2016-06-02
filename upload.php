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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         fonts
 * @since           2.1.9
 * @author          Simon Roberts <wishcraft@users.sourceforge.net>
 * @subpackage		api
 * @description		Fonting Repository Services REST API
 * @link			http://sourceforge.net/projects/chronolabsapis
 * @link			http://cipher.labs.coop
 */

	die("Pre-alpha API Systems: Uploads will not be available until release!");
	
	define('MAXIMUM_QUERIES', 25);
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'apiconfig.php';

	$error = array();
	if (isset($_GET['field']) || !empty($_GET['field'])) {
		if (empty($_FILES[$_GET['field']]))
			$error[] = 'No file uploaded in the correct field name of: "' . $_GET['field'] . '"';
		else {
			$packs = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'));
			$extensions = array_unique($packs);
			sort($extensions);
			$pass = false;
			foreach($extensions as $xtension)
			{
				if (strtolower(substr($_FILES[$_GET['field']]['name'], strlen($_FILES[$_GET['field']]['name'])- strlen($xtension))) == strtolower($xtension))
					if (in_array($xtension, $formats))
						$filetype = 'font';
					else {
						$filetype = 'pack';
						$packtype = $xtension;
					}
					$pass=true;
					continue;
			}
			if ($pass == false)
				$error[] = 'The file extension type of <strong>'.$_FILES[$_GET['field']]['name'].'</strong> is not valid you can only upload the following file types: <em>'.implode("</em>&nbsp;<em>*.", $extensions).'</em>!';
		}
	} else 
		$error[] = 'File uploaded field name not specified in the URL!';
	
	if (isset($_REQUEST['email']) || !empty($_REQUEST['email'])) {
		if (!checkEmail($_REQUEST['email']))
			$error[] = 'Email is invalid!';
	} else
		$error[] = 'No Email Address for Notification specified!';
	
	if ((!isset($_REQUEST['local']) || !is_array($_REQUEST['local'])) || (count($_REQUEST['local'])<8) || 
		(empty($_REQUEST['local']['longitude']) || empty($_REQUEST['local']['latitude']) || empty($_REQUEST['local']['region']) ||
		 empty($_REQUEST['local']['city']) || empty($_REQUEST['local']['country']) || empty($_REQUEST['local']['timezone']) || 
		 empty($_REQUEST['local']['postcode']) || empty($_REQUEST['local']['ipaddy']) || empty($_REQUEST['local']['hostname']))) {
		$error[] = 'No Form User Locality $_POST["local"] array, need to set the local array for contribution attributes!';
	}
	
	if (((!isset($_REQUEST['name']) || empty($_REQUEST['name'])) || (!isset($_REQUEST['bizo']) || empty($_REQUEST['bizo']))) && 
		(isset($_REQUEST['scope']['to']) && $_REQUEST['scope']['to'] = 'to')) {
		$error[] = 'No Converters Individual name or organisation not specified in survey scope when selected!';
	}
	
	if ((!isset($_REQUEST['email-cc']) || empty($_REQUEST['email-cc'])) && (isset($_REQUEST['scope']['cc']) && $_REQUEST['scope']['cc'] = 'cc')) {
		$error[] = 'No Survey addressee To by survey cc participants email\'s specified when survey scope is selected!';
	}
	
	if ((!isset($_REQUEST['email-bcc']) || empty($_REQUEST['email-bcc'])) && (isset($_REQUEST['scope']['bcc']) && $_REQUEST['scope']['bcc'] = 'bcc')) {
		$error[] = 'No Survey addressee To by survey bcc participants email\'s specified when survey scope is selected!';
	}
	
	if (!empty($error))
	{
		redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	
	$file = array();
	switch ($filetype)
	{
		case "font":
			$uploadpath = DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true);
			if (!is_dir(constant("FONT_UPLOAD_PATH") . $uploadpath))
				mkdir(constant("FONT_UPLOAD_PATH") . $uploadpath, 0777, true);
			if (!move_uploaded_file($_FILES[$_GET['field']]['tmp_name'], $file[] = constant("FONT_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . $_FILES[$_GET['field']]['name'])) {
				redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Fonts API was unable to recieve and store: <strong>".$_FILES[$_GET['field']]['name']."</strong>!</p></center>");
				exit(0);
			}
		case "pack":
			$uploadpath = DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true);
			if (!is_dir(constant("FONT_UPLOAD_PATH") . $uploadpath))
				mkdir(constant("FONT_UPLOAD_PATH") . $uploadpath, 0777, true);
			if (!is_dir(constant("FONT_RESOURCES_UNPACKING") . $uploadpath))
				mkdir(constant("FONT_RESOURCES_UNPACKING") . $uploadpath, 0777, true);
			if (!move_uploaded_file($_FILES[$_GET['field']]['tmp_name'], $zipe = constant("FONT_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . $_FILES[$_GET['field']]['name'])) {
				redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Fonts API was unable to recieve and store: <strong>".$_FILES[$_GET['field']]['name']."</strong>!</p></center>");
				exit(0);
			}
			$cmds = getExtractionShellExec();
			@shell_exec(__DIR__ . DIRECTORY_SEPARATOR . str_replace('%path', constant("FONT_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR, str_replace('%pack', $zipe, $cmds[$packtype])));
			unlink($zipe);
			$packs = true;
			while($packs == true)
			{
				$packs = false;
				foreach(getCompletePacksListAsArray(constant("FONT_RESOURCES_UNPACKING") . $uploadpath) as $packtype => $packs)
				{
					foreach($packs as $hashinfo => $packfile)
					{
						if (!is_dir(constant("FONT_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo))
							mkdir(constant("FONT_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo, 0777, true);
						@shell_exec($cmd = __DIR__ . DIRECTORY_SEPARATOR . str_replace('%path', constant("FONT_RESOURCES_UNPACKING") . $uploadpath . DIRECTORY_SEPARATOR . $hashinfo, str_replace('%pack', $packfile, $cmds[$packtype])));
						$packs=true;
						unlink($packfile);
					}
				}
			}
			$files = getCompleteStillsListAsArray(constant("FONT_RESOURCES_UNPACKING") . $uploadpath);
			break;
		default:
			$error[] = 'The file extension type of <strong>*.'.$fileext.'</strong> is not valid you can only upload the following: <em>*.otf</em>, <em>*.ttf</em> & <em>*.zip</em>!';
			break;
	}
	
	if (!empty($error))
	{
		redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	
	$culled = array();
	$scope = (!isset($_GET['scope']['bcc']) || !isset($_GET['scope']['cc'])?'to':(!isset($_GET['scope']['bcc']) || isset($_GET['scope']['cc'])?'cc':(isset($_GET['scope']['bcc']) && isset($_GET['scope']['cc'])?'all':'bcc')));
	$copypath = FONT_RESOURCES_UNPACKING . DIRECTORY_SEPARATOR . $_REQUEST['email'] . DIRECTORY_SEPARATOR . microtime(true);
	if (!is_dir($copypath))
		mkdir($copypath, 0777, true);
	$size = 0;
	foreach($file as $type => $fontfiles)
	{
		$size = $size + count($fontfiles);
	}
	foreach($file as $type => $fontfiles)
	{
		foreach($fontfiles as $finger => $fontfile)
		{
			if (!file_exists($copypath . DIRECTORY_SEPARATOR . basename($fontfile)))
			{
				if (copy($fontfile, $copypath . DIRECTORY_SEPARATOR .  basename($fontfile)))
				{
					$sql = "INSERT INTO `uploads` (`available`, `key`, `scope`, `email`, `uploaded_file`, `uploaded_path`, `uploaded`, `referee_uri`, `callback`, `bytes`, `batch-size`, `datastore`, `cc`, `bcc`, `frequency`, `elapses`) VALUES ('" . $available = ($scope!='to'?mt_rand(7,18):1) . "','" . mysql_escape_string(sha1_file($copypath . DIRECTORY_SEPARATOR .  basename($fontfile))) . "','" . mysql_escape_string($scope) . "','" . mysql_escape_string($email = $_REQUEST['email']) . "','" . mysql_escape_string($filename = basename($fontfile)) . "','" . mysql_escape_string($copypath) . "','" . time(). "','" . mysql_escape_string($_SERVER['HTTP_REFERER']) . "','" . mysql_escape_string($callback = $_REQUEST['callback']) . "'," . filesize($fontfile) . "," . $size . ",'" . mysql_escape_string(json_encode(array('scope' => $_REQUEST['scope'], 'ipsec' => json_decode(getURIData("https://lookups.ringwould.com.au/v1/country/".whitelistGetIP(true)."/json.api"), true), 'name' => $_REQUEST['name'], 'bizo' => $_REQUEST['bizo'], 'batch-size' => $size))) . "','" . mysql_escape_string($_REQUEST['email-cc']) . "','" . mysql_escape_string($_REQUEST['email-bcc']) . "','" . mysql_escape_string($freq = mt_rand(2.76,6.75)*3600*24) . "','" . mysql_escape_string($elapse = mt_rand(9,27)*3600*24) . "')";
					if ($GLOBALS['FontsDB']->queryF($sql))
					{
						$uploadid = $GLOBALS['FontsDB']->getInsertId();
						if (!empty($cullist[$finger]))
						{
							foreach($cullist[$finger] as $typeb => $fingers) {
								foreach($fingers as $fingerprint => $file)
								{
									$culled[$finger][$fingerprint][$typeb] = basename($file);
									$sql = "INSERT INTO `fonts_fingering` (`type`, `upload_id`, `fingerprint`, `fingering`) VALUES ('" . mysql_escape_string($typeb) . "','" . mysql_escape_string($uploadid) . "','" . mysql_escape_string($fingerprint) . "','" . sha1("$typeb\\\\:$finger") . "')";
									$GLOBALS['FontsDB']->queryF($sql);
								}
							}
						}
						$sql = "INSERT INTO `fonts_fingering` (`type`, `upload_id`, `fingerprint`, `fingering`) VALUES ('" . mysql_escape_string($type) . "','" . mysql_escape_string($uploadid) . "','" . mysql_escape_string($finger) . "','" . sha1("$type\\\\:$finger") . "')";
						$GLOBALS['FontsDB']->queryF($sql);
						$success[] = basename($fontfile);
						if (isset($_REQUEST['callback']) && !empty($_REQUEST['callback']))
							@getURIData($_REQUEST['callback'], 27, 31, array('action'=>'uploaded', 'file-md5' => $finger, 'allocated' => $available, 'key' => $key, 'email' => $_REQUEST['email'], 'name' => $_REQUEST['name'], 'bizo' => $_REQUEST['bizo'], 'frequency' => $freq, 'elapsing' => $elapses, 'filename' => $filename, 'culled' => $culled));
					}
					else 
						die('SQL Failed: ' . $sql);
				}
			}
		}
	}
	shell_exec(sprintf(__DIR__ . DIRECTORY_SEPARATOR . "rm -Rf " . constant("FONT_UPLOAD_PATH") . $uploadpath . DIRECTORY_SEPARATOR . '*'));
	redirect(isset($_REQUEST['return'])&&!empty($_REQUEST['return'])?$_REQUEST['return']:'http://'. $_SERVER["HTTP_HOST"], 18, "<center><h1 style='color:rgb(0,198,0);'>Uploading Partially or Completely Successful</h1><br/><p>The following files where uploaded and queued for conversion on the API Successfully:</p><p style='height: auto; clear: both; width: 100%;'><ul style='height: auto; clear: both; width: 100%;'><li style='width: 20%; float: left;'>".implode("</li><li style='width: 20%; float: left;'>", $success)."</li></ul></p><br/><br/><br/><p>You need to wait for the conversion maintenance to run in the next 30 minutes, you will recieve an email when done per each file!</p></center>");
	exit(0);
	
?>