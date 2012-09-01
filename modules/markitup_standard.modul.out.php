<?php
/**
 * MARKITUP Addon - STANDARD MODUL OUT
 * ### UID:m287standard ###
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.1.61
 * @version Modul 1.1
 */

if(OOAddon::isAvailable('textile'))
{
  // Fliesstext
  $textile = '';
  if(REX_IS_VALUE[1])
  {
    $textile = htmlspecialchars_decode("REX_VALUE[1]");
    $textile = str_replace("<br />","",$textile);
    $textile = rex_a79_textile($textile);
    print '<div class="txt-img">'. $textile . '</div>';
  }
}
else
{
  echo rex_warning('Dieses Modul benötigt das "textile" Addon!');
}
?>
