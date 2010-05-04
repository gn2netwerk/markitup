<?php 
header('Content-type:text/css');
$setname = md5(rex_request('a287_markitup_css'));
$dir = 'include/addons/markitup/data/sets/default/images';
$css = rex_request('css');
$css = base64_decode($css);


$buttons = explode(',',rex_request('a287_markitup_css'));
foreach ($buttons as $button) {
	$button = str_replace('/','',$button);
	$button = str_replace('\\','',$button);
	$button = str_replace('.','',$button);
	
	$fn = $REX['INCLUDE_PATH'].'/addons/markitup/data/sets/default/images/'.$button.'.png';
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
	height:<?php echo (rex_request('height')-7).'px !important'; ?>;	
}

.markItUpContainer ul{
	margin-left:0 !important;
}
.markItUpContainer li{
	list-style:none !important;
}

