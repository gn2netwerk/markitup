<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo4.2
 * @version 1.1
 * @version svn:$Id$
 */

$myself = 'markitup';

// COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$myself] = '287';
$REX['ADDON']['page'][$myself] = $myself;
$REX['ADDON']['name'][$myself] = 'Markitup';
$REX['ADDON'][$myself]['revision'] = ereg_replace('[^0-9]','',"$Revision$");
$REX['ADDON']['version'][$myself] = '1.1 SVN #'.$REX['ADDON'][$myself]['revision'];
$REX['ADDON']['author'][$myself] = "Jay Salvat, Rüdiger Nitschke, Dave Holloway";

// PERMISSIONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['perm'][$myself] = $myself.'[]';
$REX['PERM'][] = $myself.'[]';

// USER SETTINGS
////////////////////////////////////////////////////////////////////////////////
// --- DYN
$REX['ADDON']['markitup']['default']['buttons'] = 'h1,h2,h3,h4,h5,h6,separator,bold,italic,stroke,separator,listbullet,listnumeric,separator,image,linkmedia,separator,linkintern,linkextern,linkmailto,separator,code,blockquote,separator,preview';
$REX['ADDON']['markitup']['default']['width']   = '720';
$REX['ADDON']['markitup']['default']['height']  = '250';
$REX['ADDON']['markitup']['default']['preview']  = 'inline';
// --- /DYN

// INCLUDES
////////////////////////////////////////////////////////////////////////////////
require_once $REX['INCLUDE_PATH'].'/addons/markitup/classes/class.markitup.php';
require_once $REX['INCLUDE_PATH'].'/addons/markitup/functions/function.rexdev_scandir.inc.php';

// LANG FILES
////////////////////////////////////////////////////////////////////////////////
if ($REX['REDAXO'])
{
  // BUILTIN
  $I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $myself . '/lang');

  // 3RD PARTY
  $lang_root = $REX['INCLUDE_PATH'].'/addons/' . $myself . '/lang/';
  $extra_langs = rexdev_scandir($lang_root,0,array(),array());
  $extra_langs = $extra_langs['folders'];
  if(count($extra_langs) > 0)
  {
    foreach($extra_langs as $lang)
    {
      $I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $myself . '/lang/'.$lang);
    }
  }
}

// OUTPUT
////////////////////////////////////////////////////////////////////////////////
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

include $REX['INCLUDE_PATH'].'/addons/'.$myself.'/controller.inc.php';
?>