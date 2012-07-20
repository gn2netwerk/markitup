<?php
/**
 * MARKITUP Addon - STANDARD MODUL IN
 * ### UID:m287standard ###
 *
 * @package redaxo 4.2/4.3/4.4
 * @version Addon 1.1.60
 * @version Modul 1.1
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
