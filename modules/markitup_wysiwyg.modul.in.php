<?php
/**
 * MARKITUP Addon - WYSIWYG MODUL IN
 * ### UID:m287wysiwyg ###
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.1.62
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
