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


	global $domain, $protocol, $business, $entity, $contact, $referee, $peerings, $source;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Font's Preview || Chronolabs Cooperative</title>
<link rel="stylesheet" href="<?php echo $source; ?>/style.css" type="text/css" />
<link rel="stylesheet" href="https://css.ringwould.com.au/3/gradientee/stylesheet.css" type="text/css" />
<link rel="stylesheet" href="https://css.ringwould.com.au/3/shadowing/styleheet.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script>
	var icoroite = 9966 * Math.random() + 7755;
	setTimeout(function() {
		jQuery.getJSON( "https://icons.<?php echo $domain; ?>/icons/java/".($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader")."/random.json", function( data ) {
			$.each(data, function( keyey, value ) {
				$( "#" + keyey ).href = value;
			});
		});
	}, icoroite);
</script>
<?php
	if ((!isset($_SESSION['icon-meta-html']) || empty($_SESSION['icon-meta-html'])) && session_id())
		$_SESSION['icon-meta-html'] = file_get_contents("https://icons.ringwould.com.au/icons/java/".($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader")."/random.html");
	if (isset($_SESSION['icon-meta-html']) && !empty($_SESSION['icon-meta-html']))
		echo $_SESSION['icon-meta-html'];
	else
		echo file_get_contents("https://icons.".$domain. "/icons/java/".($GLOBALS["domain"]=="ringwould.com.au"?"ringwould":"invader")."/random.html");
?>
</head>

<body>
<div class="main">
    <div style="margin-bottom: 19px; padding: 15px; clear: both;">
    	<h1>Font's Previewer</h1>
    	<p>The following <?php echo count($GLOBALS['fontnames']); ?> font(s) are shown in this preview:-</p><p style="height: auto; clear: both;"><ol style="height: auto; clear: both;"><li style="width: 25%; float: left;"><?php echo implode('</li><li style="width: 25%; float: left;">', $names = $GLOBALS['fontnames']); ?></li></ol></p>
    </div>
    <?php foreach($names as $fontname) { ?>
    <div style="margin-top: 19px; clear: both; padding: 8px;">
	    <h2><?php echo $fontname; ?> Preview</h2>
	    <blockquote style="font-family: '<?php echo $fontname; ?>'>
	    	<?php for($size = mt_rand(225,295); $size >= mt_rand(65,110); $size = $size - mt_rand(7,12)) { ?>
	        <font color="#012101" style="font-family: '<?php echo $fontname; ?>'; font-size: <?php echo $size; ?>%; margin-bottom: 9px;">All Work and No Pay Makes <?php echo $entity; ?> a Dull Bored!</font><br/>
	        <?php } ?>
	    </blockquote>
	</div>
    <?php } ?>
	   <?php if (file_exists(API_FILE_IO_FOOTER)) {
    	readfile(API_FILE_IO_FOOTER);
    }?>	
    <?php if (!in_array(whitelistGetIP(true), whitelistGetIPAddy())) { ?>
    <h2>Limits</h2>
    <p>There is a limit of <?php echo MAXIMUM_QUERIES; ?> queries per hour. You can add yourself to the whitelist by using the following form API <a href="http://whitelist.<?php echo domain; ?>/">Whitelisting form (whitelist.<?php echo domain; ?>)</a>. This is only so this service isn't abused!!</p>
    <?php } ?>
    <h2>The Author</h2>
    <p>This was developed by Simon Roberts in 2013 and is part of the Chronolabs System and api's.<br/><br/>This is open source which you can download from <a href="https://sourceforge.net/projects/chronolabsapis/">https://sourceforge.net/projects/chronolabsapis/</a> contact the scribe  <a href="mailto:wishcraft@users.sourceforge.net">wishcraft@users.sourceforge.net</a></p></body>
</div>
</html>
<?php 
