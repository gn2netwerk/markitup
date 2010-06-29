<?php
/**
 * MARKITUP Addon - STANDARD MODUL IN
 * ### UID:m287standard ###
 *
 * @package redaxo4.2
 * @version Addon 1.1
 * @version Modul 1.0
 * @version svn:$Id$
 */

if(OOAddon::isAvailable('textile'))
{
?>

<strong>Fliesstext</strong>:<br />
<textarea name="VALUE[1]" cols="80" rows="10" class="inp100">REX_HTML_VALUE[1]</textarea>
<?php
if(OOAddon::isAvailable('markitup'))
{
  a287_markitup::markitup('textarea.inp100');
}
?>
<br />
<?php

rex_a79_help_overview();

}else
{
  echo rex_warning('Dieses Modul benötigt das "textile" Addon!');
}

?>