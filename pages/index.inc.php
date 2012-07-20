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

// GET PARAMS, IDENTIFIER, ROOT DIR
////////////////////////////////////////////////////////////////////////////////
$myself  = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$chapter = rex_request('chapter', 'string');
$func    = rex_request('func', 'string');
$myroot  = $REX['INCLUDE_PATH'].'/addons/'.$myself;

// BACKEND CSS
////////////////////////////////////////////////////////////////////////////////
if ($REX['REDAXO']){
  $header = '
<!-- markitup -->
  <link rel="stylesheet" type="text/css" href="index.php?markitup_func=backend.css" media="screen, projection, print" />
<!-- end markitup -->
';
  $header_include = 'return $params["subject"].\''.$header.'\';';
  rex_register_extension('PAGE_HEADER', create_function('$params',$header_include));
}

// INCLUDE FUNCTIONS
////////////////////////////////////////////////////////////////////////////////
require_once $myroot.'/functions/function.rexdev_incparse.inc.php';

// MAIN
////////////////////////////////////////////////////////////////////////////////
if ($subpage == 'preview')
{
  // INLINE PREVIEW
  require $REX['INCLUDE_PATH'] . '/addons/'.$myself.'/pages/'.$subpage.'.inc.php';
}
else
{
  // REX TOP
  require $REX['INCLUDE_PATH'] . '/layout/top.php';

  // BUILD SUBPAGE NAVIGATION
  $subpages = array (
      array ('','Einstellungen'),
      array ('modul','Beispielmodule'),
      array ('help','Hilfe')
    );

  rex_title('Markitup <span class="addonversion">'.$REX['ADDON']['version'][$myself].'</span>', $subpages);

  // SET DEFAULT PAGE / INCLUDE SUBPAGE
  if(!$subpage) {
    $subpage = 'settings';
  }
  require $REX['INCLUDE_PATH'] . '/addons/'.$myself.'/pages/'.$subpage.'.inc.php';

  // REX BOTTOM
  require $REX['INCLUDE_PATH'] . '/layout/bottom.php';
}
