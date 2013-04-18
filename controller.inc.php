<?php
/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.2.2
 */

$myself = 'markitup';

if (rex_request('markitup_func')!="")
{
  $path = $REX['INCLUDE_PATH'].'/addons/'.$myself;

  switch (rex_request('markitup_func'))
  {
    case "backend.css":
      header('Content-Type:text/css');
      echo file_get_contents($path.'/media/backend.css');
      die();
    break;

    case "jsopenwin.gif":
      header('Content-Type:image/gif');
      echo file_get_contents($path.'/media/jsopenwin.gif');
      die();
    break;

    default:
    break;
  }
}
