<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.2.0
 */


// ADDON IDENTIFIER
////////////////////////////////////////////////////////////////////////////////
$myself = 'markitup';


// INCLUDES
////////////////////////////////////////////////////////////////////////////////
require_once $REX['INCLUDE_PATH'].'/addons/markitup/classes/class.markitup.php';
require_once $REX['INCLUDE_PATH'].'/addons/markitup/functions/function.markitup_functions.inc.php';


// LANG FILES
////////////////////////////////////////////////////////////////////////////////
if ($REX['REDAXO'])
{
  // BUILTIN
  $I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $myself . '/lang');

  // 3RD PARTY
  $lang_root = $REX['INCLUDE_PATH'].'/addons/' . $myself . '/lang/';
  $extra_langs = markitup_scandir($lang_root,0,array(),array());
  $extra_langs = $extra_langs['folders'];
  if(count($extra_langs) > 0)
  {
    foreach($extra_langs as $lang)
    {
      $I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $myself . '/lang/'.$lang);
    }
  }
}


// LOADERS & CONTROLLERS
////////////////////////////////////////////////////////////////////////////////
include $REX['INCLUDE_PATH'].'/addons/markitup/controller.inc.php';

if (rex_request('a287_markitup_css')!="") {
  require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/cssloader.php';
  exit();
}
if (rex_request('a287_markitup_set')!="") {
  require_once $REX['INCLUDE_PATH'].'/addons/markitup/pages/setloader.php';
  exit();
}


// ADDON REX COMMONS
////////////////////////////////////////////////////////////////////////////////
$REX['ADDON']['rxid'][$myself]    = '287';
$REX['ADDON']['page'][$myself]    = $myself;
$REX['ADDON']['name'][$myself]    = 'Markitup';
$REX['ADDON']['version'][$myself] = '1.2.0';
$REX['ADDON']['author'][$myself]  = 'Jay Salvat, Rüdiger Nitschke, Dave Holloway, jdlx';
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
$REX['ADDON']['markitup']['autoenable_status']       =   1;
$REX['ADDON']['markitup']['autoenable_class']        = 'rex-markitup';
// --- /DYN

/* DEFAULTS BACKUP:
$REX['ADDON']['markitup']['default']['buttons']    = 'h1,h2,h3,h4,h5,h6,separator,bold,italic,stroke,separator,listbullet,listnumeric,separator,image,linkmedia,separator,linkintern,linkextern,linkmailto,separator,code,blockquote,separator,preview';
$REX['ADDON']['markitup']['default']['width']      = '680';
$REX['ADDON']['markitup']['default']['height']     = '250';
$REX['ADDON']['markitup']['default']['preview']    = 'wysiwyg';
$REX['ADDON']['markitup']['default']['shortcuts']  = 'h1:1|h2:2|h3:3|h4:4|h5:5|h6:6|bold:B|italic:I|stroke:S|image:P|linkmedia:M|linkintern:L|linkextern:E|linkmailto:M|preview:Y';
$REX['ADDON']['markitup']['default']['resizemode'] =   0;
$REX['ADDON']['markitup']['autoenable_status']     =   1;
$REX['ADDON']['markitup']['autoenable_class']      =   'rex-markitup';
*/


// INCLUDE ASSETS
////////////////////////////////////////////////////////////////////////////////
if($REX['REDAXO'])
{
  rex_register_extension('OUTPUT_FILTER', 'a287_markitup_assets');

  function a287_markitup_assets($params)
  {
    global $REX;
    $output = $params['subject'];

    $scripts = PHP_EOL;
    if ($REX['REDAXO'])
    {
      $scripts.= PHP_EOL.'<!-- markitup -->'.PHP_EOL.
      '  <script type="text/javascript" src="include/addons/markitup/lib/jquery.markitup.pack.js"></script>'.PHP_EOL;
      if($REX['ADDON']['markitup']['default']['resizemode'] == 0)
      {
        $scripts.='  <script type="text/javascript" src="include/addons/markitup/lib/jquery.autogrow-textarea.js"></script>'.PHP_EOL;
      }
    $scripts.='  <link rel="stylesheet" type="text/css" href="include/addons/markitup/lib/skins/markitup/style.css" />'.PHP_EOL.
    '<!-- end markitup -->'.PHP_EOL;
    }

    $output = str_replace('</head>',$scripts.'</head>',$output);
    return $output;
  }
}


// AUTO INIT
////////////////////////////////////////////////////////////////////////////////
if($REX['REDAXO'] && $REX['ADDON']['markitup']['autoenable_status'] == 1)
{
  rex_register_extension('OUTPUT_FILTER', 'a287_markitup_autoinit');

  function a287_markitup_autoinit($params)
  {
    global $REX;
    $output    = $params['subject'];
    $classname = $REX['ADDON']['markitup']['autoenable_class'] != ''
               ? $REX['ADDON']['markitup']['autoenable_class']
               : 'rex-markitup';

    // SEARCH TEXTAREAS WITH CLASS "markitup"
    $regex = '@<textarea[^>]*class="[^"]*'.$classname.'@';
    if(preg_match($regex, $output) == false ) {
      return $output;
    }

    $src_params  = '&article_id='.$params['article_id'];
    $src_params .= '&clang='.$params['clang'];
    $src_params .= '&slice_id='.$params['slice_id'];
    $src_params .= '&function='.rex_request('function','string');

    $buttons = $REX['ADDON']['markitup']['default']['buttons'];
    $width   = $REX['ADDON']['markitup']['default']['width'];
    $height  = $REX['ADDON']['markitup']['default']['height'];

    if (isset($params['rex_version'])) {
      $src_params .= '&rex_version='.$params['rex_version'];
    }

    $scripts = PHP_EOL.'<!-- markitup (autoenabled) -->
    <script type="text/javascript">var set_'.md5($buttons).';</script>
    <script type="text/javascript" src="index.php?a287_markitup_set='.$buttons.$src_params.'"></script>
    <link rel="stylesheet" type="text/css" href="index.php?a287_markitup_css='.$buttons.'&width='.$width.'&height='.$height.'" />
    <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery("textarea.'.$classname.'").markItUp(set_'.md5($buttons).');
    });'.PHP_EOL;

    if($REX['ADDON']['markitup']['default']['resizemode'] == 0)
    {
    $scripts .= '
    jQuery(function() {
      jQuery("textarea.'.$classname.'").autogrow();
    });'.PHP_EOL;
    }

    $scripts .= '    </script>'.PHP_EOL.'<!-- end markitup (autoenabled) -->'.PHP_EOL;

    return str_replace('</head>',$scripts.'</head>',$output);
  }
}
