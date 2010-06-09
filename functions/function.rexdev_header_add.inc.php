<?php
/**
* Generische Funktion zum Einbinden von CSS, JS, .. ins Redaxo Backend
*
* @param $params Extension-Point Parameter
* 
* @author rexdev.de
* @package redaxo4
* @version 0.1
* $Id$: 
*
* Beispiel:
* ---------------------------------------------------------------
* // include als array (zwingend, auch wenn nur ein wert!)
* $inc = array (
* '<link rel="stylesheet" type="text/css" href="../files/addons/'.$myself.'/backend.css" />,
* '<script type="text/javascript" src="../files/addons/'.$myself.'/foo.js"></script>'
* );
*
* // include per 3. parameter an ep übergeben
* if ($REX['REDAXO']) {
*   include_once $myroot.'/functions/function.rexdev_header_add.inc.php';
*   rex_register_extension('PAGE_HEADER', 'rexdev_header_add', $inc);
* }
* ------------------------------------------------------------------------------
*/

if(!function_exists('rexdev_header_add'))
{
  function rexdev_header_add($params) {

    if (is_array($params) && count($params)>2) {
      foreach($params as $key => $val) {
        if($key !== 'subject' && $key !== 'extension_point') {
        $params['subject'] .= "\n".$val;
        }
      }
    }

    return $params['subject'];
  }
}
?>