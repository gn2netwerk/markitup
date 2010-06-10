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

$mypage = 'markitup';

if (rex_request('markitup_func')!="")
{
  $path = $REX['INCLUDE_PATH'].'/addons/'.$mypage;

  switch (rex_request('markitup_func'))
  {
    case "backend.css":
      header('Content-Type:text/css');
      echo file_get_contents($path.'/data/backend.css');
      die();
    break;

    case "jsopenwin.gif":
      header('Content-Type:image/gif');
      echo file_get_contents($path.'/data/jsopenwin.gif');
      die();
    break;

    default:
    break;
  }
}


?>