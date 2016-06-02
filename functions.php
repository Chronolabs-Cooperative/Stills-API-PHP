<?php
/**
 * Chronolabs REST Image Stills API
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
 * @package         places
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Image Stills Service API
 */

include_once dirname(__FILE__).'/constants.php';

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIPAddy()
	 *
	* 	provides an associative array of whitelisted IP Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetIPAddy() {
		return array_merge(whitelistGetNetBIOSIP(), file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist.txt'));
	}
}

if (!function_exists("whitelistGetNetBIOSIP")) {

	/* function whitelistGetNetBIOSIP()
	 *
	* 	provides an associative array of whitelisted IP Addresses base on TLD and NetBIOS Addresses
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array
	*/
	function whitelistGetNetBIOSIP() {
		$ret = array();
		foreach(file(dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'whitelist-domains.txt') as $domain) {
			$ip = gethostbyname($domain);
			$ret[$ip] = $ip;
		}
		return $ret;
	}
}

if (!function_exists("getMakeCategoryHTML")) {

	/* function getPeersSupporting()
	 *
	* 	Get a supporting domain system for the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array()
	*/
	function getMakeCategoryHTML($return = '')
	{
		$forms = array();

		$forms[md5($return)]['html'] = getURIData($GLOBALS['source']."v2/make-category/forms.api", 83, 83, array('ipaddy' => whitelistGetIP(true), 'return'=>$return));
		$forms[md5($return)]['timeout'] = time() + mt_rand(3600*3.5,3600*9.5) * mt_rand(4.5,11.511);

		return $forms[md5($return)]['html'];
	}
}

if (!function_exists("getNamingCategoryHTML")) {

	/* function getPeersSupporting()
	 *
	* 	Get a supporting domain system for the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array()
	*/
	function getNamingCategoryHTML($return = '')
	{
		$forms = array();

		$forms[md5($return)]['html'] = getURIData($GLOBALS['source']."v2/name-category/forms.api", 83, 83, array('ipaddy' => whitelistGetIP(true), 'return'=>$return));
		$forms[md5($return)]['timeout'] = time() + mt_rand(3600*3.5,3600*9.5) * mt_rand(4.5,11.511);

		return $forms[md5($return)]['html'];
	}
}

if (!function_exists("getUploadHTML")) {

	/* function getPeersSupporting()
	 *
	* 	Get a supporting domain system for the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @return 		array()
	*/
	function getUploadHTML($return = '')
	{
		$forms = array();

		$forms[md5($return)]['html'] = getURIData($GLOBALS['source']."v2/uploads/forms.api", 83, 83, array('ipaddy' => whitelistGetIP(true), 'return'=>$return));
		$forms[md5($return)]['timeout'] = time() + mt_rand(3600*3.5,3600*9.5) * mt_rand(4.5,11.511);

		return $forms[md5($return)]['html'];
	}
}

if (!function_exists("whitelistGetIP")) {

	/* function whitelistGetIP()
	 *
	* 	get the True IPv4/IPv6 address of the client using the API
	* @author 		Simon Roberts (Chronolabs) simon@labs.coop
	*
	* @param		$asString	boolean		Whether to return an address or network long integer
	*
	* @return 		mixed
	*/
	function whitelistGetIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	}
}

if (!function_exists("getArchivedZIPFile")) {
	function getArchivedZIPFile($zip_resource = '', $zip_file = '')
	{
		$data = false;
		$zip = zip_open($zip_resource);
		if ($zip) {
			while ($zip_entry = zip_read($zip)) {
				if (strpos('  '.strtolower(zip_entry_name($zip_entry)), strtolower($zip_file)))
					if (zip_entry_open($zip, $zip_entry, "r")) {
						$data = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						zip_entry_close($zip_entry);
					}
			}
			zip_close($zip);
		}
		return $data;
	}
}


if (!function_exists("getCategoriesImagesCount")) {
	function getCategoriesImagesCount()
	{
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'categories-images-count--'.date("Y-m-d").'.json'))
			$images = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'categories-images-count--'.date("Y-m-d").'.json'), true);
		else {
			$images = array();
			foreach(getCategories(false) as $akey => $category)
			{
				if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'category-images--'.md5($category) . '.json'))
					$images[$category] = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'category-count--'.md5($category) . '.json'), true);
				else
					$images[$category] = array();
				if (empty($images[$category]))
				{
					$images[$category]['images'] = 0;
					$images[$category]['key'] = $akey;
					foreach(getCategoryLibraries($category) as $bkey => $library)
					{
						$images[$category]['images'] = $images[$category]['images'] + count(getArchivedZIPContentsArray(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $library . ".zip"))-1;
					}
					@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'category-images--'.md5($category) . '.json', json_encode($images[$category]));
				}
			}
			@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'categories-images-count--'.date("Y-m-d").'.json', json_encode($images));
		}
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'categories-images-count--'.date("Y-m-d", time() - (3600 * 24)).'.json'))
			unlink(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'categories-images-count--'.date("Y-m-d", time() - (3600 * 24)).'.json');
		return $images;
	}
}

if (!function_exists("getLibrariesImagesCount")) {
	function getLibrariesImagesCount()
	{
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-images-count--'.date("Y-m-d").'.json'))
			$images = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-images-count--'.date("Y-m-d").'.json'), true);
		else {
			$images = array();
			foreach(getCategories(false) as $akey => $category)
			{
				if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'library-images--'.md5($category.$library) . '.json'))
					$images[$category] = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'library-count--'.md5($category.$library) . '.json'), true);
				else
					$images[$category] = array();
				if (empty($images[$category]))
				{
					foreach(getCategoryLibraries($category) as $bkey => $library)
					{
						$images[$category][$library] = array('images' => count(getArchivedZIPContentsArray(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $library . ".zip"))-1,
															 'key' => $bkey);
					}
					@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'library-images--'.md5($category.$library) . '.json', json_encode($images[$category]));
				}
			}
			@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-images-count--'.date("Y-m-d").'.json', json_encode($images));
		}
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-images-count--'.date("Y-m-d", time() - (3600 * 24)).'.json'))
			unlink(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-images-count--'.date("Y-m-d", time() - (3600 * 24)).'.json');
		return $images;
	}
}

if (!function_exists("getCategoryLibraries")) {
	function getCategoryLibraries($category = '')
	{
		$libraries = array();
		if (!empty($category) && strlen($category) && strtolower($category) == $category)
		{
			foreach(getCategories(false) as $key => $cat)
				if ($key == $category)
				{
					$category = $cat;
					continue;
				}
		}
		if (empty($category) || !is_dir(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category))
			return array();
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-'.md5($category) . '.json'))
			$libraries = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'category-'.md5($category) . '.json'), true);
		if (empty($libraries))
		{
			foreach(getZipListAsArray(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category) as $key => $library)
			{
				$libraries[md5_file(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category . DIRECTORY_SEPARATOR . $library)] = str_replace(".zip", "", $library);
			}
			@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'libraries-'.md5($category) . '.json', json_encode($libraries));
		}
		return $libraries;
	}
}

if (!function_exists("getCategories")) {
	function getCategories($empty = false)
	{
		$categories = array();
		foreach(getDirListAsArray(STILLS_RESOURCES) as $path => $folder)
		{
			if (count(getZipListAsArray(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $folder))==0 && $empty == true)
				$categories[md5($folder)] = $folder;
			else 
				$categories[md5($folder)] = $folder;
		}
		return $categories;
	}
}

if (!function_exists("createCategories")) {
	function createCategories($category = '')
	{
		if (empty($category) || is_dir(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category))
			return false;
		return mkdir(STILLS_RESOURCES . DIRECTORY_SEPARATOR . $category, 0777, true);
	}
}

if (!function_exists("getExtensions")) {
	function getExtensions()
	{
		return cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'image-extensions.diz'));
	}
}

if (!function_exists("getConversions")) {
	function getConversions()
	{
		$format = array();
		foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'image-conversions.diz')) as $line)
		{
			$parts = explode("=", $line);
			if (isset($parts[0]) && isset($parts[1]))
				$format[$parts[0]] = $parts[1];
		}
		return $format;
	}
}

if (!function_exists("getScapedMinimumDimensions")) {
	function getScapedMinimumDimensions()
	{
		$sizes = array();
		foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .  'image-dimensions.diz')) as $line)
		{
			$stops = explode("--", $line);
			$parts = explode("x", $stops[0]);
			if (isset($parts[0]) && isset($parts[1]) && isset($stops[1]))
				$sizes[$stops[1]][$stops[0]] = array('width'=>$parts[0], 'height'=>$parts[1]);
		} 
		return $sizes;
	}
}

if (!function_exists("getLocalityArray")) {
	function getLocalityArray($ipaddy = '127.0.0.1')
	{
		
		/**
		 * Default
		 * @var strings
		 */
		$local = array();
		$local['iso'] = 'AU';
		$local['country'] = 'Australia';
		$local['region'] = 'Sydney';
		$local['city'] = 'Marrickville South';
		$local['postcode'] = '2204';
		$local['longitude'] = '151.2';
		$local['latitude'] = '-33.8833';
		$local['timezone'] = "10:00";
		$local['ipaddy'] = '127.0.0.1';
		$local['hostname'] = 'localhost';
		
		shuffle($lookups = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .  'lookups.diz')));
		foreach($lookups as $lookup)
		{
			$iplocal = json_decode(@getURIData(sprintf($lookup, $ipaddy), 72, 72), true);
			if ($iplocal['country']['iso']!='-' && !empty($iplocal['country']['iso']))
			{
				$local['iso'] = $iplocal['country']['iso'];
				$local['country'] = $iplocal['country']['name'];
				$local['region'] = $iplocal['location']['region'];
				$local['city'] = $iplocal['location']['city'];
				$local['postcode'] = $iplocal['location']['postcode'];
				$local['longitude'] = $iplocal['location']['coordinates']['longitude'];
				$local['latitude'] = $iplocal['location']['coordinates']['latitude'];
				$local['timezone'] = $iplocal['location']['gmt'];
				$local['ipaddy'] = $iplocal['ip'];
				$local['hostname'] = gethostbyaddr($iplocal['ip']);
				return $local;
			}
		}
		foreach($lookups as $lookup)
		{
			$iplocal = json_decode(@getURIData(sprintf($lookup, 'myself'), 72, 72), true);
			if ($iplocal['country']['iso']!='-' && !empty($iplocal['country']['iso']))
			{
				$local['iso'] = $iplocal['country']['iso'];
				$local['country'] = $iplocal['country']['name'];
				$local['region'] = $iplocal['location']['region'];
				$local['city'] = $iplocal['location']['city'];
				$local['postcode'] = $iplocal['location']['postcode'];
				$local['longitude'] = $iplocal['location']['coordinates']['longitude'];
				$local['latitude'] = $iplocal['location']['coordinates']['latitude'];
				$local['timezone'] = $iplocal['location']['gmt'];
				$local['ipaddy'] = $iplocal['ip'];
				$local['hostname'] = gethostbyaddr($iplocal['ip']);
				return $local;
			}
		}
		return $local;
	}
}


if (!function_exists("redirect")) {
	/**
	 * checkEmail()
	 *
	 * @param mixed $email
	 * @return bool|mixed
	 */
	function redirect($url = '', $seconds = 9, $message = '')
	{
		$GLOBALS['url'] = $url;
		$GLOBALS['time'] = $seconds;
		$GLOBALS['message'] = $message;
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'redirect.php';
		exit(-1000);
	}
}

if (!function_exists("checkEmail")) {
	/**
	 * checkEmail()
	 *
	 * @param mixed $email
	 * @return bool|mixed
	 */
	function checkEmail($email)
	{
		if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
				return false;
			}
		}
		if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
					return false;
				}
			}
		}
		return $email;
	}
}

if (!function_exists("writeRawFile")) {
	function writeRawFile($file = '', $data = '')
	{
		if (!is_dir(dirname($file)))
			mkdir(dirname($file), 0777, true);
		if (is_file($file))
			unlink($file);
		$ff = fopen($file, 'w');
		fwrite($ff, $data, strlen($data));
		fclose($ff);
	}
}

if (!function_exists("getArchivedZIPContentsArray")) {
	function getArchivedZIPContentsArray($zip_file = '')
	{
		if (file_exists(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'zip-file---'.md5_file($zip_file).'.json'))
			$files = json_decode(file_get_contents(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'zip-file---'.md5_file($zip_file).'.json'), true);
		else {
			$files = array();
			$zip = zip_open($zip_file);
			if ($zip) {
				while ($zip_entry = zip_read($zip)) {
					if (zip_entry_open($zip, $zip_entry, "r")) {
						$data = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						$type = '';
						$type = strtolower(substr(basename(zip_entry_name($zip_entry)), strpos(basename(zip_entry_name($zip_entry)), ".", strlen(basename(zip_entry_name($zip_entry)))-5)+1, strlen(basename(zip_entry_name($zip_entry))) - strpos(basename(zip_entry_name($zip_entry)), ".", strlen(basename(zip_entry_name($zip_entry)))-5)));
						$files[md5($data)] = array('filename' => basename(zip_entry_name($zip_entry)), 'path' => dirname(zip_entry_name($zip_entry)), 'bytes' => strlen($data), 'type' => $type);
						zip_entry_close($zip_entry);
					}
				}
				zip_close($zip);
			}
			@writeRawFile(STILLS_CACHE  . DIRECTORY_SEPARATOR . 'zip-file---'.md5_file($zip_file).'.json', json_encode($files));
		}
		return $files;
	}
}

if (!function_exists("getCompleteDirListAsArray")) {
	function getCompleteDirListAsArray($dirname, $result = array())
	{
		foreach(getDirListAsArray($dirname) as $path)
		{
			$result[$dirname . DIRECTORY_SEPARATOR . $path] = $dirname . DIRECTORY_SEPARATOR . $path;
			$result = getCompleteDirListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}

}

if (!function_exists("getCompleteZipListAsArray")) {
	function getCompleteZipListAsArray($dirname, $result = array())
	{
		foreach(getDirListAsArray($dirname) as $path)
		{
			foreach(getZipListAsArray($dirname . DIRECTORY_SEPARATOR . $path) as $file)
				$result[md5_file($dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file)] = $dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file;
			$result = getCompleteZipListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}
}


if (!function_exists("getCompletePacksListAsArray")) {
	function getCompletePacksListAsArray($dirname, $result = array())
	{
		foreach(getDirListAsArray($dirname) as $path)
		{
			foreach(getPacksListAsArray($dirname . DIRECTORY_SEPARATOR . $path) as $file=>$values)
				$result[$values['type']][md5_file($dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $values['file'])] = $dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $values['file'];
			$result = getCompletePacksListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}
}

if (!function_exists("getCompleteStillsListAsArray")) {
	function getCompleteStillsListAsArray($dirname, $result = array())
	{
		foreach(getDirListAsArray($dirname) as $path)
		{
			foreach(getStillsListAsArray($dirname . DIRECTORY_SEPARATOR . $path) as $file=>$values)
				$result[$values['type']][md5_file($dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $values['file'])] = $dirname . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $values['file'];
			$result = getCompleteStillsListAsArray($dirname . DIRECTORY_SEPARATOR . $path, $result);
		}
		return $result;
	}
}

if (!function_exists("getDirListAsArray")) {
	function getDirListAsArray($dirname)
	{
		$ignored = array(
				'cvs' ,
				'_darcs');
		$list = array();
		if (substr($dirname, - 1) != '/') {
			$dirname .= '/';
		}
		if ($handle = opendir($dirname)) {
			while ($file = readdir($handle)) {
				if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
					continue;
				if (is_dir($dirname . $file)) {
					$list[$file] = $file;
				}
			}
			closedir($handle);
			asort($list);
			reset($list);
		}

		return $list;
	}
}

if (!function_exists("getFileListAsArray")) {
	function getFileListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if (substr($dirname, - 1) == '/') {
			$dirname = substr($dirname, 0, - 1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}

if (!function_exists("getZipListAsArray")) {
	function getZipListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (preg_match('/(\.zip)$/i', $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}


if (!function_exists("getPacksListAsArray")) {
	function getPacksListAsArray($dirname, $prefix = '')
	{
		$packs = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'));
		$filelist = array();
		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($packs as $pack)
					if (substr(strtolower($file), strlen($file)-strlen($pack)) == strtolower($pack)) {
					$file = $prefix . $file;
					$filelist[$file] = array('file'=>$file, 'type'=>$pack);
				}
			}
			closedir($handle);
		}
		return $filelist;
	}
}


if (!function_exists("getStillsListAsArray")) {
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'wideimage' . DIRECTORY_SEPARATOR . 'WideImage.php';
	function getStillsListAsArray($dirname, $prefix = '')
	{
		$formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'images-supported.diz'));
		$filelist = array();
		if ($handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				foreach($formats as $format)
					if (substr(strtolower($file), strlen($file)-strlen($format)) == strtolower($format)) {
						if ($img = @WideImage::load($imgfile = $dirname . DIRECTORY_SEPARATOR . basename($file))){
							$tags = array();
							if (function_exists('exif_read_data') && in_array($format, array('jpg','jpeg','tiff'))) {
								$tags = exif_read_data($imgfile, 0, true);
							}
							$file = $prefix . $file;
							$filelist[$file] = array('base'=>basename(dirname($imgfile)), 'file'=>$imgfile, 'type'=>$format, 'width' => $img->getWidth(), 'height' => $img->getHeight(), 'scape' => getStillScape($img->getWidth(), $img->getHeight()), 'hash' => md5_file($imgfile), 'tags' => $tags);
							unset($img);
						}
					}
			}
			closedir($handle);
		}
		return $filelist;
	}
}

if (!function_exists("getStillScape"))
{
	function getStillScape($width = 0, $height = 0)
	{
		if (($width >= ($height + ($width * 0.1831)) || $width <= ($height + ($width * 0.1831))) &&
			($height >= ($width + ($height * 0.1831)) || $height <= ($width + ($height * 0.1831))))
		{
			return 'tilescape';
		} elseif ($width < $height)
		{
			return 'landscape';
		} elseif ($width > $height)
		{
			return 'footscape';
		}
		return '';
	}
}

if (!function_exists("getExtractionShellExec")) {
	function getExtractionShellExec()
	{
		$ret = array();
		foreach(cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-extracting.diz')) as $values)
		{
			$parts = explode("||", $values);
			$ret[$parts[0]] = $parts[1];
		}
		return $ret;
	}
}


if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}
?>
