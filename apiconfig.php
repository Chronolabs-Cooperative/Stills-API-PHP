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
 * Opens Access Origin Via networking Route NPN
 */
header('Access-Control-Allow-Origin: *');
header('Origin: *');

/**
 * Turns of GZ Lib Compression for Document Incompatibility
 */
ini_set("zlib.output_compression", 'Off');
ini_set("zlib.output_compression_level", -1);

/**
 *
 * @var constants
 */
define("API_FILE_IO_PEERS", __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'peers.diz');
define("API_FILE_IO_DOMAINS", __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'domains.diz');
define("API_FILE_IO_FOOTER", __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'api-%s.html');

error_reporting(E_ERROR);
ini_set('display_errors', true);
ini_set('log_errors', false);


if (!function_exists("intialiseAPI")) {
	/**
	 *
	 * @param unknown_type $path
	 * @param unknown_type $perm
	 * @param unknown_type $secure
	 */
	function intialiseAPI()
	{
				
		
		
		return true;
	}
}

if (!function_exists("mkdirSecure")) {
	/**
	 *
	 * @param unknown_type $path
	 * @param unknown_type $perm
	 * @param unknown_type $secure
	 */
	function mkdirSecure($path = '', $perm = 0777, $secure = true)
	{
		if (!is_dir($path))
		{
			mkdir($path, $perm, true);
			if ($secure == true)
			{
				writeRawFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
			}
			return true;
		}
		return false;
	}
}

if (!function_exists("cleanWhitespaces")) {
	/**
	 *
	 * @param array $array
	 */
	function cleanWhitespaces($array = array())
	{
		foreach($array as $key => $value)
		{
			if (is_array($value))
				$array[$key] = cleanWhitespaces($value);
			else {
				$array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
			}
		}
		return $array;
	}
}

if (!function_exists("getDomainSupportism")) {

	/* function getDomainSupportism()
	 *
	 * 	Get a supporting domain system for the API
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		string
	 */
	function getDomainSupportism($variable = 'array', $realm = '')
	{
		static $ret = array();
		if (empty($ret))
		{
			$supporters = (file(API_FILE_IO_DOMAINS));
			foreach($supporters as $supporter)
			{
				$parts = explode("||", str_replace("\n", "", $supporter));
				if (strpos(' '.strtolower($realm), strtolower($parts[0]))>0)
				{
					$ret['domain'] = $parts[0];
					$ret['protocol'] = $parts[1];
					$ret['business'] = $parts[2];
					$ret['entity'] = $parts[3];
					$ret['contact'] = $parts[4];
					$ret['referee'] = $parts[5];
					continue;
				}
			}
		}
		if (isset($ret[$variable]))
			return $ret[$variable];
		return $ret;
	}
}


if (!function_exists("getPingTiming")) {

	/* function getPingTiming()
	 *
	 * 	Get a ping timing for a URL
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		float()
	 */
	function getPingTiming($uri = '', $timeout = 14, $connectout = 13)
	{
		$pings = array();
		if (file_exists(FONTS_CACHE . DIRECTORY_SEPARATOR . 'pings-list.serial'))
			$pings = unserialize(file_get_contents(FONTS_CACHE . DIRECTORY_SEPARATOR . 'pings-list.serial'));
		foreach($pings as $key => $values)
			if ($values['timeout'] <= microtime(true))
				unset($pings[$key]);
		if (!isset($pings[md5($uri)]))
		{
			$start = microtime(true);
			ob_start();
			if (!$btt = curl_init($uri)) {
				$pings[md5($uri)]['ping'] = 0;
	  			$pings[md5($uri)]['timeout'] = time() + mt_rand(23,97);
	  			@writeRawFile(FONTS_CACHE . DIRECTORY_SEPARATOR . 'pings-list.serial', serialize($pings));
			} else {
				curl_setopt($btt, CURLOPT_HEADER, 0);
				curl_setopt($btt, CURLOPT_POST, 0);
				curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
				curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($btt, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($btt, CURLOPT_VERBOSE, true);
				curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
				@curl_exec($btt);
				curl_close($btt);
				ob_end_clean();
		  		$pings[md5($uri)]['ping'] = microtime(true) - $start * 1000;
		  		$pings[md5($uri)]['timeout'] = time() + mt_rand(23,97);
		  		@writeRawFile(FONTS_CACHE . DIRECTORY_SEPARATOR . 'pings-list.serial', serialize($pings));
			}
		}
		return $pings[md5($uri)]['ping'];
	}
}


if (!function_exists("getURIData")) {

	/* function getURIData()
	 *
	 * 	cURL Routine
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @return 		float()
	 */
	function getURIData($uri = '', $timeout = 65, $connectout = 65, $post_data = array())
	{
		if (!function_exists("curl_init"))
		{
			return file_get_contents($uri);
		}
		if (!$btt = curl_init($uri)) {
			return false;
		}
		curl_setopt($btt, CURLOPT_HEADER, 0);
		curl_setopt($btt, CURLOPT_POST, (count($posts)==0?false:true));
		if (count($posts)!=0)
			curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post_data));
		curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
		curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($btt, CURLOPT_VERBOSE, true);
		curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($btt);
		curl_close($btt);
		return $data;
	}
}

if (!function_exists("getPeersSupporting")) {

	/* function getPeersSupporting()
	 *
	* 	Get a supporting domain system for the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array()
	*/
	function getPeersSupporting()
	{
		if (filectime(API_FILE_IO_PEERS) + 3600 * 24 * 3.75 <= time())
		{
			if (getPingTiming("http://peers.labs.coop/v2/" . basename(__DIR__) . "/json.api")>1 
				&& $peerise = json_decode(getURIData("http://peers.labs.coop/v2/" . basename(__DIR__) . "/json.api"), true))
			{
				$ioout = array();
				foreach($peerise as $ll => $values)
					$ioout[] = implode("||", $values);
				if (count($ioout)>1)
					writeRawFile(API_FILE_IO_PEERS, implode("\n", $ioout));
			}
		}
		static $ret = array();
		if (empty($ret))
		{
			$peerings = file(API_FILE_IO_PEERS);
			foreach($peerings as $peer)
			{
				$parts = explode("||", $peer);
				$realm = $parts[0];
				$ret[$realm]['domain'] = $parts[0];
				$ret[$realm]['protocol'] = $parts[1];
				$ret[$realm]['business'] = $parts[2];
				$ret[$realm]['search'] = $parts[3];
				$ret[$realm]['mirror'] = $parts[4];
				$ret[$realm]['contact'] = $parts[5];
				$ret[$realm]['ping'] = getPingTiming($parts[1].$parts[0]);
			}
		}
		return $ret;
	}
}


if (!function_exists("writeRawFile")) {
	/**
	 * 
	 * @param string $file
	 * @param string $data
	 */
	function writeRawFile($file = '', $data = '')
	{
		$lineBreak = "\n";
		if (substr(PHP_OS, 0, 3) == 'WIN') {
			$lineBreak = "\r\n";
		}
		if (!is_dir(dirname($file)))
			if (strpos(' '.$file, FONTS_CACHE))
				mkdirSecure(dirname($file), 0777, true);
			else
				mkdir(dirname($file), 0777, true);
		elseif (strpos(' '.$file, FONTS_CACHE) && !file_exists(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess'))
			writeRawFile(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
		if (is_file($file))
			unlink($file);
		$data = str_replace("\n", $lineBreak, $data);
		$ff = fopen($file, 'w');
		fwrite($ff, $data, strlen($data));
		fclose($ff);
	}
}


if (!function_exists("getHTMLForm")) {
	/**
	 * 
	 * @param unknown_type $mode
	 * @param unknown_type $clause
	 * @param unknown_type $output
	 * @param unknown_type $version
	 * @return string
	 */
	function getHTMLForm($mode = '', $clause = '', $callback = '', $output = '', $version = 'v2', $local = array())
	{
		$ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
		$form = array();
		switch ($mode)
		{
			case "uploads":
				$form[] = "<form name='" . $ua . "' method='POST' style='font-size: 129%;' enctype='multipart/form-data' action='" . $GLOBALS['protocol'] . $_SERVER["HTTP_HOST"] . '/v2/' .$ua . "/upload.api'>";
				$form[] = "\t<table class='stills-uploader' id='stills-uploader' style='vertical-align: top !important;'>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='email'>Provisioners' Email:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<input type='textbox' name='email' id='email' maxlen='198' size='41' />&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='scope-to'><em>Scope</em></label>";
				$form[] = "\t\t\t<input type='checkbox' name='scope[to]' id='scope-to' value='to' /><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='name'>Provisioners' Name:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<input type='textbox' name='name' id='name' maxlen='198' size='41' /><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>&nbsp;</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='bizo'>Provisioners' Organisation:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<input type='textbox' name='bizo' id='bizo' maxlen='198' size='41' /><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>&nbsp;</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='email-cc'>Image Stills Cateloguing <strong>To's</strong>:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<textarea name='email-cc' id='email-cc' cols='44' rows='11'></textarea>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='scope-cc'><em>Scope</em></label>";
				$form[] = "\t\t\t<input type='checkbox' name='scope[cc]' id='scope-cc' value='cc' />&nbsp;&nbsp;<span style='font-size:73.1831%;'>Seperated List By ie: [,] [;] [:] [/] [?] [\] [|]...</span><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='email-bcc'>Image Stills Cateloguing <strong>Bcc's</strong>:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<textarea name='email-bcc' id='email-bcc' cols='44' rows='11'></textarea>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='scope-bcc'><em>Scope</em></label>";
				$form[] = "\t\t\t<input type='checkbox' name='scope[bcc]' id='scope-bcc' value='bcc' />&nbsp;&nbsp;<span style='font-size:73.1831%;'>Seperated List By ie: [,] [;] [:] [/] [?] [\] [|]...</span><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<label for='email-notify'>Release fileinfo.diz reciepients <strong>Notify To's</strong>:</label>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<textarea name='email-notify' id='email-notify' cols='44' rows='11'></textarea>&nbsp;&nbsp;";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t\t<td>";
				$form[] = "\t\t\t<span style='font-size:73.1831%;'>Seperated List By ie: [,] [;] [:] [/] [?] [\] [|]...</span><br/>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td colspan='3'>";
				$form[] = "\t\t\t<label for='".$ua."'>Compressed Images Archive too upload:</label>";
				$form[] = "\t\t\t<input type='file' name='" . $ua . "' id='" . $ua ."'><br/>";
				$form[] = "\t\t\t<div style='margin-left:17px; font-size: 71.99%; margin-top: 23px; padding: 11px;'>";
				$form[] = "\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "</em></strong>!<br/>";
				$formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'image-supported.diz'); sort($formats); 
				$form[] = "\t\t\t\t ~~ <strong>Images File Formats Supported: <em style='color:rgb(15,70 43); font-weight: bold; font-size: 81.6502%;'>*." . str_replace("\n" , "", implode(" *.", array_unique($formats))) . "</em></strong>!<br/>";
				$packs = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'); sort($packs);
				$form[] = "\t\t\t\t ~~ <strong>Compressed File Pack Supported: <em style='color:rgb(55,10,33); font-weight: bold; font-size: 81.6502%;'>*." . str_replace("\n" , "", implode("  *.", array_unique($packs))) . "</em></strong>!<br/>";
				$form[] = "\t\t\t<div>";
				$form[] = "\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t\t<tr>";
				$form[] = "\t\t\t<td colspan='3'>";
				$form[] = "\t\t\t<input type='hidden' name='return' value='" . (empty($clause)?$GLOBALS['protocol'] . $_SERVER["HTTP_HOST"]:$clause) ."'>";
				$form[] = "\t\t\t<input type='hidden' name='callback' value='" . (empty($callback)?'':$callback) ."'>";
				foreach($local as $key => $value)
					$form[] = "\t\t\t<input type='hidden' name='local[".$key."]' value='" . (empty($value)?'':$value) ."'>";
				$form[] = "\t\t\t<input type='submit' value='Upload File' name='submit'>";
				$form[] = "\t\t\t</td>";
				$form[] = "\t\t</tr>";
				$form[] = "\t</table>";
				$form[] = "</form>";
				break;
		}
		return implode("\n", $form);
	}
}


?>