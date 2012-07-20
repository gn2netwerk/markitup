<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.1.60
 */

header('Content-type:text/css');
$setname = md5(rex_request('a287_markitup_css'));
$dir = 'include/addons/markitup/lib/sets/default';
$css = rex_request('css');
$css = base64_decode($css);


$buttons = explode(',',rex_request('a287_markitup_css'));
foreach ($buttons as $button) {
	$button = str_replace('/','',$button);
	$button = str_replace('\\','',$button);
	$button = str_replace('.','',$button);

	$fn = $REX['INCLUDE_PATH'].'/addons/markitup/lib/sets/default/'.$button.'.png';
	if (file_exists($fn)) {
		echo '.set-'.$setname.' .markitup-'.$button.' a{';
		echo 'background-image:url('.$dir.'/'.$button.'.png) !important;';
		echo '}'."\n";
	}
}
?>

<?php echo '.set-'.$setname; ?> {
	width:<?php echo rex_request('width').'px !important'; ?>;
}

<?php echo '.set-'.$setname.' .markItUpEditor'; ?> {
	width:<?php echo (rex_request('width')-22).'px !important'; ?>;
	height:<?php echo (rex_request('height')-7).'px'; ?>;
}

.markItUpContainer ul{
	margin-left:0 !important;
}
.markItUpContainer li{
	list-style:none !important;
}

