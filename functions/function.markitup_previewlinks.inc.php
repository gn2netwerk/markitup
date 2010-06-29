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

function markitup_previewlinks($content)
{
  global $REX;

  // FIX CONTENT FROM POST
  $content = str_replace("\n","\r\n",$content);
  $content = $content.' ';

  // Hier beachten, dass man auch ein Zeichen nach dem jeweiligen Link mitmatched,
  // damit beim ersetzen von z.b. redaxo://11 nicht auch innerhalb von redaxo://112
  // ersetzt wird
  // siehe dazu: http://forum.redaxo.de/ftopic7563.html

  // -- preg match redaxo://[ARTICLEID]-[CLANG] --
  preg_match_all('@redaxo://([0-9]*)\-([0-9]*)(.){1}/?@im',$content,$matches,PREG_SET_ORDER);
  foreach($matches as $match)
  {
    if(empty($match)) continue;

    $url = rex_getURL($match[1], $match[2]);

    if($REX['REDAXO'])
    {
      $content = str_replace($match[0],'../'.$url.$match[3],$content);
    }
    else
    {
      $content = str_replace($match[0],$url.$match[3],$content);
    }

  }

  // -- preg match redaxo://[ARTICLEID] --
  preg_match_all('@redaxo://([0-9]*)(.){1}/?@im',$content,$matches,PREG_SET_ORDER);
  foreach($matches as $match)
  {
    if(empty($match)) continue;

    $url = rex_getURL($match[1], $REX['CUR_CLANG']);

    if($REX['REDAXO'])
    {
      $content = str_replace($match[0],'../'.$url.$match[2],$content);
    }
    else
    {
      $content = str_replace($match[0],$url.$match[2],$content);
    }
  }

  return $content;
}

?>