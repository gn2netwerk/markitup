<?php
/**
 * MARKITUP Addon - WYSIWYG MODUL IN
 * ### UID:m287wysiwyg ###
 *
 * @package redaxo4.2
 * @version Addon 1.1
 * @version Modul 1.1
 * @version svn:$Id$
 */

// PRUEFEN OB TEXTILE ADDON AKTIV
if(OOAddon::isAvailable('textile'))
{
  echo '
  <strong>Fliesstext</strong>:<br />
  <textarea name="VALUE[1]" cols="80" rows="10" class="inp100">REX_HTML_VALUE[1]</textarea>';

  // PRUEFEN OB MARKITUP ADDON AKTIV
  if(OOAddon::isAvailable('markitup'))
  {
    // MARKITUP FUER TEXTAREA "inp100" AUFRUFEN
    a287_markitup::markitup('textarea.inp100');
  }

  echo '<br />';

  // INTERNE TEXTILE HILFE AUSGEBEN
  rex_a79_help_overview();
}
else
{
  echo rex_warning('Dieses Modul ben&ouml;tigt das "textile" Addon!');
}

?>
