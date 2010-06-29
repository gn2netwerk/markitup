<?php
/**
 * MARKITUP Addon - STANDARD MODUL OUT
 * ### UID:m287standard ###
 *
 * @package redaxo4.2
 * @version Addon 1.1
 * @version Modul 1.0
 * @version svn:$Id$
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