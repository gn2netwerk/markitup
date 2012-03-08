<?php
/**
 * MARKITUP Addon - WYSIWYG MODUL IN
 * ### UID:m287wysiwyg ###
 *
 * @package redaxo4.2
 * @version Addon 1.1
 * @version Modul 1.2
 * @version svn:$Id$
 */


// PRUEFEN OB MARKITUP ADDON AKTIV
if(OOAddon::isAvailable('markitup'))
{
  // MARKITUP FUER TEXTAREA "inp100" AUFRUFEN
  a287_markitup::markitup('textarea.inp100');
}
?>

<label for="text">Textile Text</label>
<textarea id="text" name="VALUE[1]" cols="80" rows="10" class="inp100">REX_HTML_VALUE[1]</textarea>