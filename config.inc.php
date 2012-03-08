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


// ERROR_REPORTING
////////////////////////////////////////////////////////////////////////////////
/*ini_set('error_reporting', E_ALL);
//ini_set('error_reporting', E_STRICT);
ini_set('display_errors', 1);*/

// ADDON IDENTIFIER
////////////////////////////////////////////////////////////////////////////////
$myself = 'markitup';

// ADDON VERSION
////////////////////////////////////////////////////////////////////////////////
$Revision = '';
$REX['ADDON'][$myself]['VERSION'] = array
(
'VERSION'      => 1,
'MINORVERSION' => 1,
'SUBVERSION'   => preg_replace('/[^0-9]/','',"$Revision$")
);

// ADDON REX COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$myself]    = '287';
$REX['ADDON']['page'][$myself]    = $myself;
$REX['ADDON']['name'][$myself]    = 'Markitup';
$REX['ADDON']['version'][$myself] = implode('.', $REX['ADDON'][$myself]['VERSION']);
$REX['ADDON']['author'][$myself]  = 'Jay Salvat, Rüdiger Nitschke, Dave Holloway';
$REX['ADDON']['perm'][$myself]    = $myself.'[]';
$REX['PERM'][]                    = $myself.'[]';

// USER SETTINGS
////////////////////////////////////////////////////////////////////////////////
// --- DYN
$REX['ADDON']['markitup']['default']['buttons']    = 'h1,h2,h3,h4,h5,h6,separator,bold,italic,stroke,separator,listbullet,listnumeric,separator,image,linkmedia,separator,linkintern,linkextern,linkmailto,separator,code,blockquote,separator,preview';
$REX['ADDON']['markitup']['default']['width']      = '680';
$REX['ADDON']['markitup']['default']['height']     = '68';
$REX['ADDON']['markitup']['default']['preview']    = 'wysiwyg';
$REX['ADDON']['markitup']['default']['shortcuts']  = 'h1:1|h2:2|h3:3|h4:4|h5:5|h6:6|bold:B|italic:I|stroke:S|image:P|linkmedia:M|linkintern:L|linkextern:E|linkmailto:M|preview:Y';
$REX['ADDON']['markitup']['default']['resizemode'] =   0;
// --- /DYN

/* DEFAULTS BACKUP:
$REX['ADDON']['markitup']['default']['buttons']    = 'h1,h2,h3,h4,h5,h6,separator,bold,italic,stroke,separator,listbullet,listnumeric,separator,image,linkmedia,separator,linkintern,linkextern,linkmailto,separator,code,blockquote,separator,preview';
$REX['ADDON']['markitup']['default']['width']      = '680';
$REX['ADDON']['markitup']['default']['height']     = '250';
$REX['ADDON']['markitup']['default']['preview']    = 'wysiwyg';
$REX['ADDON']['markitup']['default']['shortcuts']  = 'h1:1|h2:2|h3:3|h4:4|h5:5|h6:6|bold:B|italic:I|stroke:S|image:P|linkmedia:M|linkintern:L|linkextern:E|linkmailto:M|preview:Y';
$REX['ADDON']['markitup']['default']['resizemode'] =   0;
*/

// STATIC/HIDDEN SETTINGS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON'][$myself]['svn_version_notify'] = false;

// INCLUDES
////////////////////////////////////////////////////////////////////////////////
require_once $REX['INCLUDE_PATH'].'/addons/markitup/classes/class.markitup.php';
require_once $REX['INCLUDE_PATH'].'/addons/markitup/functions/function.rexdev_scandir.inc.php';
require_once $REX['INCLUDE_PATH'].'/addons/markitup/functions/function.markitup_previewlinks.inc.php';

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

// MAIN
////////////////////////////////////////////////////////////////////////////////
if (rex_request('a287_markitup_css')!="") {
  require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/cssloader.php';
  exit();
}
if (rex_request('a287_markitup_set')!="") {
  require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/setloader.php';
  exit();
}

function a287_markitup($params)
{
  global $REX;
  $output = $params['subject'];

  $scripts = PHP_EOL;
  if ($REX['REDAXO'])
  {
    $scripts.= '<script type="text/javascript" src="include/addons/markitup/lib/jquery.markitup.pack.js"></script>'.PHP_EOL;
    if($REX['ADDON']['markitup']['default']['resizemode'] == 0)
    {
      $scripts.='<script type="text/javascript" src="include/addons/markitup/lib/jquery.autogrow-textarea.js"></script>'.PHP_EOL;
    }
  $scripts.='<link rel="stylesheet" type="text/css" href="include/addons/markitup/lib/skins/markitup/style.css" />'.PHP_EOL;
  }


  $output = str_replace('</head>',$scripts.'</head>',$output);
  return $output;
}

rex_register_extension('OUTPUT_FILTER', 'a287_markitup');

include $REX['INCLUDE_PATH'].'/addons/'.$myself.'/controller.inc.php';

