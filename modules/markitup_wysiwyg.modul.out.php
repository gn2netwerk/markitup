<?php
/**
 * MARKITUP Addon - WYSIWYG MODUL OUT
 * ### UID:m287wysiwyg ###
 *
 * @package redaxo4.2
 * @version Addon 1.1
 * @version Modul 1.1
 * @version svn:$Id$
 */

if(OOAddon::isAvailable('textile'))
{
  $textile = '';

  // EVTL. WYSIWYG PREVIEW DATEN ABFRAGEN
  $markitup_textile_preview = rex_post('markitup_textile_preview_REX_SLICE_ID', 'string');

  //SWITCH PREVIEW VS. DB DATEN
  if ($markitup_textile_preview != '')
  {
    $textile = stripslashes($markitup_textile_preview);
    $textile = markitup_previewlinks($textile);
  }
  elseif(REX_IS_VALUE[1])
  {
    $textile = htmlspecialchars_decode('REX_VALUE[1]');
  }

  // TEXTILE PARSEN
  if($textile != '')
  {
    $textile = str_replace('<br />','',$textile);
    $textile = rex_a79_textile($textile);
    echo $textile;
  }
}
else
{
  echo rex_warning('Dieses Modul ben&ouml;tigt das "textile" Addon!');
}
