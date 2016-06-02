<?php
/**
 * Chronolabs REST Stills Images API
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
 * @package         stills
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Stills Images API Services
 */

	$pu = parse_url($_SERVER['REQUEST_URI']);
	$source = (isset($_SERVER['HTTPS'])?'https://':'http://').strtolower($_SERVER['HTTP_HOST']).$pu['path'];
	$ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 9);
	$categories = getCategoriesImagesCount();
	$libraries = getLibrariesImagesCount();
	$typals = array("jpg" => "Jpeg Image Format", "png" => "Png Image Format", "gif" => "Png Image Format");
	$width = mt_rand(800, 4200);
	$height = mt_rand(600, 3100);
	$examples = array();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Still's Images API Services || Chronolabs Cooperative</title>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f9a1c208996c1d"></script>
<script type="text/javascript">
  addthis.layers({
	'theme' : 'transparent',
	'share' : {
	  'position' : 'right',
	  'numPreferredServices' : 6
	}, 
	'follow' : {
	  'services' : [
		{'service': 'facebook', 'id': 'chronolabs'},
		{'service': 'twitter', 'id': 'negativitygear'},
		{'service': 'linkedin', 'id': 'ceoandfounder'},
		{'service': 'google_follow', 'id': '111267413375420332318'}
	  ]
	},  
	'whatsnext' : {},  
	'recommended' : {
	  'title': 'Recommended for you:'
	} 
  });
</script>
<!-- AddThis Smart Layers END -->
<style>
body {
	image still-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	image still-size:85%em;
	background: #a647b7; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjYTY0N2I3IiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iMTAwJSIgc3RvcC1jb2xvcj0iI2VhZTI0NiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgPC9saW5lYXJHcmFkaWVudD4KICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2dyYWQtdWNnZy1nZW5lcmF0ZWQpIiAvPgo8L3N2Zz4=);
	background: -moz-linear-gradient(-45deg,  #a647b7 0%, #eae246 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#a647b7), color-stop(100%,#eae246)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(-45deg,  #a647b7 0%,#eae246 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(-45deg,  #a647b7 0%,#eae246 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(-45deg,  #a647b7 0%,#eae246 100%); /* IE10+ */
	background: linear-gradient(135deg,  #a647b7 0%,#eae246 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a647b7', endColorstr='#eae246',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
	text-align:justify;
}
.main {
	border:3px solid #000000;
	border-radius:15px;
	background-color:#feeebe;
	padding:39px 39px 39px 39px;
	margin:64px 64px 64px 64px;
	-webkit-box-shadow: 7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	-moz-box-shadow:    7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	box-shadow:         7px 7px 10px 0px rgba(108, 80, 99, 0.72);
}
h1 {
	image still-weight:bold;
	image still-size:1.42em;
	background-color:#FFEED9;
	border-radius:15px;
	padding:10px 10px 10px 10px;
	text-shadow: 4px 4px 2px rgba(150, 150, 150, 1);
}
h2 {
	image still-weight:500;
	image still-size:1.15em;
	text-shadow: 4px 4px 2px rgba(150, 150, 150, 1);
}
blockquote {
	margin-left:25px;
	margin-right:25px;
	image still-family:"Courier New", Courier, monospace;
	margin-bottom:25px;
	padding: 25px 25px 25px 25px;
	border:dotted;
	background-color:#fefefe;
	-webkit-box-shadow: 7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	-moz-box-shadow:    7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	box-shadow:         7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	-webkit-border-radius: 14px;
	-moz-border-radius: 14px;
	border-radius: 14px;
	text-shadow: 2px 2px 2px rgba(103, 87, 101, 0.82);
}
p {
	margin-bottom:12px;
}
</style>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script>
	var icoroite = 9966 * Math.random() + 7755;
	setTimeout(function() {
		jQuery.getJSON( "//labs.coop/icons/java/invaders/random.json?sessionid=<?php echo session_id(); ?>", function( data ) {
			$.each(data, function( keyey, value ) {
				$( "#" + keyey ).href = value;
			});
		});
	}, icoroite);
</script>
<?php
	if ((!isset($_SESSION['icon-meta-html']) || empty($_SESSION['icon-meta-html'])) && session_id())
		$_SESSION['icon-meta-html'] = file_get_contents("//labs.coop/icons/meta/invaders/random.html?sessionid=" . session_id());
	if (isset($_SESSION['icon-meta-html']) && !empty($_SESSION['icon-meta-html']))
		echo $_SESSION['icon-meta-html'];
	else
		echo file_get_contents("http://icons.labs.coop/meta/invaders/random.html?sessionid=" . session_id());
?>
<link rel="stylesheet" href="//labs.coop/css/3/gradientee/stylesheet.css?sessionid=<?php echo session_id(); ?>" type="text/css">
<link rel="stylesheet" href="//labs.coop/css/3/shadowing/styleheet.css?sessionid=<?php echo session_id(); ?>" type="text/css">
</head>

<body>
<div class="main">
    <h1>Still's Images API Services - Version 1.01</h1>
    <p>This is an API Service for providing still images from categories or libraries randomly to your application or website. It provides the the image stills through either fingerprinting checksums for the image still or keywords from the nodes list when access the API inclusing JSON, XML, Serialisation, HTML, RAW, CSS and raw file outputs.</p>
    <h2>Code API Documentation</h2>
    <p>You can find the phpDocumentor code API documentation at the following path :: <a href="<?php echo $source; ?>docs/" target="_blank"><?php echo $source; ?>docs/</a>. These should outline the source code core functions and classes for the API to function!</p>
    <h2>Categories Available</h2>
    <p>This is the categories available, the md5 checksum is the typal indicator you can specify inside (<em><?php echo md5(NULL); ?></em>) to make your selection from this category rather than an individual library!</p>
    <blockquote>
    	<ul>
        <?php foreach($categories as $category => $values) { ?>
        	<li><strong><?php echo $category; ?></strong> <?php if ($values['images']>0) { ?>(<em><?php echo $values['key']; ?></em>) <?php } ?>-- Images: <?php echo $values['images']; ?>
        <?php 
			if ((empty($examples['category']) || mt_rand(-1,3)>=2) && $values['images'] > 0)
			{
				$examples['category']['key'] = $values['key'];
				$examples['category']['title'] = $category;
				$examples['category']['images'] = $values['images'];
			}
		} ?>
        </ul>
    </blockquote>
    <h2>Libraries Available</h2>
    <p>This is the libraries by category available, the md5 checksum is the typal indicator you can specify inside (<em><?php echo md5(NULL); ?></em>) to make your selection from this category rather than an individual library!</p>
    <blockquote>
    	<?php foreach($libraries as $category => $values) { if (!empty($values)) { ?>
    	<font color="#EA1934" style="image still-size: 139.99%"><?php echo $category; ?></font><br/>
    	<ul style="margin-left: 23px;">
        <?php foreach($values as $library => $data) { ?>
        	<li><strong><?php echo $library; ?></strong> (<em><?php echo $data['key']; ?></em>) -- Images: <?php echo $data['images']; ?>
        <?php if ((empty($examples['library']) || mt_rand(-1,3)>=2) && $data['images'] > 0)
				{
					$examples['library']['key'] = $data['key'];
					$examples['library']['title'] = $library;
					$examples['library']['images'] = $data['images'];
				}
			} ?>
        </ul><br/><br/>
        <?php } } ?>
    </blockquote>   
    <h2>Random Image Still Output</h2>
    <p>This is done with the extension of the image type with the checksum key fingerprint for the category or library, this can either be the grouping category md5 or individual stills library md5 key!</p>
    <blockquote>
<?php 
    	foreach($examples as $type => $values ) {
    		foreach( $typals as $filetype => $caption ) { ?>
        <font color="#001201">This is for a <strong><?php echo $caption; ?> output</strong> for a random image from a <strong><?php echo $type; ?></strong> which is this example is <em><?php echo $values['title']; ?></em> which has to choose from <em><?php echo $values['images']; ?></em> images!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $filetype; ?>.api" target="_blank"><?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $filetype; ?>.api</a></strong></em><br /><br />
        <font color="#001201">This is for a <strong><?php echo $caption; ?> output</strong> for a random image as indicated by the first example here given for a <strong><?php echo $type; ?></strong> selection, but resize to fit to the width of <strong><?php echo $width; ?></strong>!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $filetype; ?>.api" target="_blank"><?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $width;?>/<?php echo $filetype; ?>.api</a></strong></em><br /><br />
        <font color="#001201">This is also a <strong><?php echo $caption; ?> output</strong> for a random image as in the previous example selection, but resize to fit to the <em>Width x Height</em> of <strong><?php echo $width; ?> x <?php echo $height; ?></strong>!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $filetype; ?>.api" target="_blank"><?php echo $source; ?>v2/image/<?php echo $values['key']; ?>/<?php echo $width;?>/<?php echo $height;?>/<?php echo $filetype; ?>.api</a></strong></em><br /><br /> 
<?php 	}
} ?>
    </blockquote>
    <h2>UPLOAD Document Output</h2>
    <p>This is done with the <em>upload.api</em> extension at the end of the url, you can upload and stage image stills and wallpapers on the API permanently and upload them in a compressed archive which the API support's the following image file formats which are in use <strong style="text-shadow: 0px 0px 0px !important;">( *.<?php $formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'image-supported.diz'); sort($formats); echo implode("  *.", array_unique($formats)); ?> )</strong> ~~ simply put them in a compressed archive, which can also contain as many tree layers of further archives; if you want in any of these formats <strong style="text-shadow: 0px 0px 0px !important;">( *.<?php $packs = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'packs-converted.diz'); sort($packs); echo implode("  *.", array_unique($packs)); ?> )</strong> containing any of these file formats any other will be ignored, you will be notified and sent a copy of the fileinfo.diz when it is finished processing or the cateloguing survey expires; with all the image stills when they are converted with example CSS via the email address.<br/><br/>The cateloguing of your image stills is done with a survey whether it has been encountered or not is based on forensic fingerprinting of the image resource as well as running a contributor's survey to you or the scope of contact emails you have provided, this process can take up to several days to complete, or as when the batch of surverys is complete on that uploaded image still's; you will have no more than 8 suverys at any one time to complete, and we do not spam your email lists you place in addition to the upload! We only select based on random alot a selection of the emails; maybe no more than 7 - 14 at any one time and people have an option to opted out and not contribute at all on the service, this all executes on some proficent scheculed tasks on the service as it is scheduled on the system!</p>
    <h3>The Following Minimal Image Sizes are Enforced!</h3>
    <?php $scapes = getScapedMinimumDimensions(); ?>
    <div style="image still-weight: 900; margin-left: 29px;">Landscape: <em><?php echo implode(', ', array_keys($scapes['landscape'])); ?></em><br/>Footscape: <em><?php echo implode(', ', array_keys($scapes['footscape'])); ?></em><br/>Tiled: <em><?php echo implode(', ', array_keys($scapes['tilescape'])); ?></em></div>
    <blockquote>
        <?php echo $upldform = getUploadHTML($GLOBALS['source']); ?>
		<h3>Code Example:</h3>
		<div style="max-height: 375px; overflow: scroll;">
			<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
<?php echo htmlspecialchars($upldform); ?>
			</pre>
		</div>
    </blockquote>
    <h2>FORMS Document Output</h2>
    <p>This is done with the <em>forms.api</em> extension at the end of the urland will provide a HTML Submission form for the API in options the only modal for this at the moment is an Upload form! The following <strong>PHP Function Examples</strong> are used in the use of this api feature; the following two code examples are quite standard and rudimentry which can be used to build your working system with this API and found being used in the code examples below:-</p>
    <pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php
	if (!function_exists("getURIData")) {
	
		/* function getURIData() cURL Routine
		 * 
		 * @author 		Simon Roberts (labs.coop) wishcraft@users.sourceforge.net
		 * @return 		string
		 */
		function getURIData($uri = '', $timeout = 25, $connectout = 25, $post_data = array())
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
			curl_setopt($btt, CURLOPT_VERBOSE, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($btt);
			curl_close($btt);
			return $data;
		}
	}

	if (!function_exists("getUserIP")) {

		/* function getUserIP()
		 *
		* 	get the True IPv4/IPv6 address of the client using the API
		* @author 		Simon Roberts (Chronolabs) wishcraft@users.sourceforge.net
		*
		* @param		$asString	boolean		Whether to return an address or network long integer
		*
		* @return 		mixed
		*/
		function getUserIP($asString = true){
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
?&gt;
	</pre>
    <blockquote>
        <font color="#001201">You basically import and output to the buffer the HTML Submission form for uploading a image still at the following URI: <strong><?php echo $source; ?>v2/uploads/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/><br/>
		<pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php

	// output the table & form
	echo getURIData("<?php echo $source; ?>v2/uploads/forms.api", 43, 43, 
				 
				 /* URL Upload return after submission (required) */
				array('return' => '<?php echo $source; ?>', 
				
				/* URL for API Callback for progress and archive with data (Not required)*/
				'callback' => '<?php echo $source; ?>v2/callback.api',
				
				/* The User/Client Using the forms IPv4/IPv6 Address (required) */
				'ipaddy' => getUserIP(true)));
?&gt;
		 </pre>
		 <font color="#001201">You basically import and output to the buffer the HTML Submission form for creating a category at the following URI: <strong><?php echo $source; ?>v2/make-category/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/><br/>
		 <pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php

	// output the table & form
	echo getURIData("<?php echo $source; ?>v2/make-category/forms.api", 43, 43, 
				 
				 /* URL Upload return after submission (required) */
				array('return' => '<?php echo $source; ?>', 
				
				/* URL for API Callback for progress and archive with data (Not required)*/
				'callback' => '<?php echo $source; ?>v2/callback.api',
				
				/* The User/Client Using the forms IPv4/IPv6 Address (required) */
				'ipaddy' => getUserIP(true)));
?&gt;
		 </pre>
		 <font color="#001201">You basically import and output to the buffer the HTML Submission form for placing a geospatial based in distance from you common name for a category at the following URI: <strong><?php echo $source; ?>v2/name-category/forms.api</strong> -- this will generate a HTML form with the return path specified for you to buffer -- see example below in PHP!</font><br/><br/>
		 <pre style="margin: 14px; padding: 12px; border: 2px solid #ee43a4;">
&lt;?php

	// output the table & form
	echo getURIData("<?php echo $source; ?>v2/name-category/forms.api", 43, 43, 
				 
				 /* URL Upload return after submission (required) */
				array('return' => '<?php echo $source; ?>', 
				
				/* URL for API Callback for progress and archive with data (Not required)*/
				'callback' => '<?php echo $source; ?>v2/callback.api',
				
				/* The User/Client Using the forms IPv4/IPv6 Address (required) */
				'ipaddy' => getUserIP(true)));
?&gt;
		 </pre>
		 <font color="#2e31c1; image still-size: 134%; image still-weight: 900;">An example of the callback routines the variables are outlined in this file you click and download the PHP Routines examples: <a href="/callback-example.php.txt" target="_blank">callback-example.php.txt</a></font>
    </blockquote> 
    <h2>RAW Document Output</h2>
    <p>This is done with the <em>raw.api</em> extension at the end of the url, this is for the functions for images stills, libraries and categories on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categores for the image stills library on the API!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/categories/raw.api" target="_blank"><?php echo $source; ?>v2/list/categories/raw.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of image still libraries by category then libraries</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/libraries/raw.api" target="_blank"><?php echo $source; ?>v2/list/libraries/raw.api</a></strong></em><br /><br />
    	<font color="#001201">This is for a list of all nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/all/raw.api" target="_blank"><?php echo $source; ?>v2/nodes/all/raw.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/keys/raw.api" target="_blank"><?php echo $source; ?>v2/nodes/keys/raw.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the fixes for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/fixes/raw.api" target="_blank"><?php echo $source; ?>v2/nodes/fixes/raw.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the typal for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/typal/raw.api" target="_blank"><?php echo $source; ?>v2/nodes/typal/raw.api</a></strong></em><br /><br />
    </blockquote>
    <h2>HTML Document Output</h2>
    <p>This is done with the <em>html.api</em> extension at the end of the url, this is for the functions for images stills, libraries and categories on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categores for the image stills library on the API!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/categories/html.api" target="_blank"><?php echo $source; ?>v2/list/categories/html.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of image still libraries by category then libraries</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/libraries/html.api" target="_blank"><?php echo $source; ?>v2/list/libraries/html.api</a></strong></em><br /><br />
		<font color="#001201">This is for a list of all nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/all/html.api" target="_blank"><?php echo $source; ?>v2/nodes/all/html.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/keys/html.api" target="_blank"><?php echo $source; ?>v2/nodes/keys/html.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the fixes for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/fixes/html.api" target="_blank"><?php echo $source; ?>v2/nodes/fixes/html.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the typal for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/typal/html.api" target="_blank"><?php echo $source; ?>v2/nodes/typal/html.api</a></strong></em><br /><br />
    </blockquote>
    <h2>Serialisation Document Output</h2>
    <p>This is done with the <em>serial.api</em> extension at the end of the url, this is for the functions for images stills, libraries and categories on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categores for the image stills library on the API!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/categories/serial.api" target="_blank"><?php echo $source; ?>v2/list/categories/serial.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of image still libraries by category then libraries</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/libraries/serial.api" target="_blank"><?php echo $source; ?>v2/list/libraries/serial.api</a></strong></em><br /><br />
		<font color="#001201">This is for a list of all nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/all/serial.api" target="_blank"><?php echo $source; ?>v2/nodes/all/serial.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/keys/serial.api" target="_blank"><?php echo $source; ?>v2/nodes/keys/serial.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the fixes for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/fixes/serial.api" target="_blank"><?php echo $source; ?>v2/nodes/fixes/serial.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the typal for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/typal/serial.api" target="_blank"><?php echo $source; ?>v2/nodes/typal/serial.api</a></strong></em><br /><br />
    </blockquote>
    <h2>JSON Document Output</h2>
    <p>This is done with the <em>json.api</em> extension at the end of the url, this is for the functions for images stills, libraries and categories on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categores for the image stills library on the API!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/categories/json.api" target="_blank"><?php echo $source; ?>v2/list/categories/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of image still libraries by category then libraries</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/libraries/json.api" target="_blank"><?php echo $source; ?>v2/list/libraries/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of all nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/all/json.api" target="_blank"><?php echo $source; ?>v2/nodes/all/json.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/keys/json.api" target="_blank"><?php echo $source; ?>v2/nodes/keys/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the fixes for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/fixes/json.api" target="_blank"><?php echo $source; ?>v2/nodes/fixes/json.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the typal for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/typal/json.api" target="_blank"><?php echo $source; ?>v2/nodes/typal/json.api</a></strong></em><br /><br />
   	</blockquote>
    <h2>XML Document Output</h2>
    <p>This is done with the <em>xml.api</em> extension at the end of the url, this is for the functions for images stills, libraries and categories on the API!</p>
    <blockquote>
        <font color="#001201">This is for a list of all categores for the image stills library on the API!</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/categories/xml.api" target="_blank"><?php echo $source; ?>v2/list/categories/xml.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of image still libraries by category then libraries</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/list/libraries/xml.api" target="_blank"><?php echo $source; ?>v2/list/libraries/xml.api</a></strong></em><br /><br />
		<font color="#001201">This is for a list of all nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/all/xml.api" target="_blank"><?php echo $source; ?>v2/nodes/all/xml.api</a></strong></em><br /><br />
         <font color="#001201">This is for a list of just the keys for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/keys/xml.api" target="_blank"><?php echo $source; ?>v2/nodes/keys/zml.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the fixes for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/fixes/xml.api" target="_blank"><?php echo $source; ?>v2/nodes/fixes/xml.api</a></strong></em><br /><br />
        <font color="#001201">This is for a list of just the typal for nodes for the image stills on the API</font><br/>
        <em><strong><a href="<?php echo $source; ?>v2/nodes/typal/xml.api" target="_blank"><?php echo $source; ?>v2/nodes/typal/xml.api</a></strong></em><br /><br />
    </blockquote>
	<?php if (file_exists(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'apis.html')) {
    	readfile(dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'apis.html');
    }?>
	<?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. This will reset every hour and the response of a 404 document not found will be provided if you have exceeded your query limits. You can add yourself to the whitelist by using the following form API <a href="https://whitelist.labs.coop/">Whitelisting form</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and Xortify. if you need to contact simon you can do so at the following address <a href="mailto:meshy@labs.coop">meshy@labs.coop</a></p></body>
</div>
</html>
<?php 
