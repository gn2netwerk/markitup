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

// ADDON IDENTIFIER & ROOT DIR
////////////////////////////////////////////////////////////////////////////////
$myself = 'markitup';
$myroot = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/';

// LOCAL INCLUDES
////////////////////////////////////////////////////////////////////////////////
require_once $myroot.'/functions/function.rexdev_incparse.inc.php';

// HELP CONTENT
////////////////////////////////////////////////////////////////////////////////
$help_includes = array (
'readme'      => array('RexSEO Help','_help.txt','textile')
);

// OUTPUT
////////////////////////////////////////////////////////////////////////////////
foreach($help_includes as $key => $def)
{
  echo '
  <div class="rex-addon-output" style="overflow:auto">
    <h2 class="rex-hl2" style="font-size:1em">'.$def[0].' <span style="color: gray; font-style: normal; font-weight: normal;">( '.$def[1].' )</span></h2>
    <div class="rex-addon-content">
      <div class="rexseo">
        '.rexdev_incparse($myroot,$def[1],$def[2],true).'
      </div>
    </div>
  </div>';
}


?>