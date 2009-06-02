<?php
$mypage = 'markitup';

$REX['ADDON']['rxid'][$mypage] = '287';
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['perm'][$mypage] = 'markitup[]';
$REX['ADDON']['version'][$mypage] = '1.0';

require_once $REX['INCLUDE_PATH'].'/addons/markitup/classes/class.markitup.php';

if (rex_request('a287_markitup_set')!="") {
	require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/setloader.php';	
	exit();
}
if (rex_request('a287_markitup_css')!="") {
	require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/cssloader.php';	
	exit();
}


function a287_markitup($params) {
	global $REX;
	$output = $params['subject'];

	$scripts='';
	if ($REX['REDAXO']) {
		$scripts.='
  <script type="text/javascript" src="include/addons/markitup/data/jquery.markitup.pack.js"></script>
  <link rel="stylesheet" type="text/css" href="include/addons/markitup/data/skins/markitup/style.css" />
';
	}
	
	
	$output = str_replace('</head>',$scripts.'</head>',$output);
	return $output;
}

rex_register_extension('OUTPUT_FILTER', 'a287_markitup'); 
?>