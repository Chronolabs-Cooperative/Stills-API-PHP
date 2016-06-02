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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" content="<?php echo $GLOBALS['time']; ?>; url=<?php echo $GLOBALS['url']; ?>" />
<title>Fonts API || Chronolabs Cooperative</title>
<style>
body {
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:85%em;
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
	padding: 9%;
}
.main {
	border:3px solid #000000;
	border-radius:15px;
	background-color:#feeebe;
	padding:39px 39px 39px 39px;
	margin-top:360px auto; 
	-webkit-box-shadow: 7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	-moz-box-shadow:    7px 7px 10px 0px rgba(108, 80, 99, 0.72);
	box-shadow:         7px 7px 10px 0px rgba(108, 80, 99, 0.72);
}
p {
	font-size: 1.23em;
}
h1 {
	font-weight:bold;
	font-size:1.42em;
	background-color:#FFEED9;
	border-radius:15px;
	padding:10px 10px 10px 10px;
	text-shadow: 4px 4px 2px rgba(150, 150, 150, 1);
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
    <?php echo $GLOBALS['message']; ?>
</div>
</html>
<?php 
