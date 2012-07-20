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

/**
 * CONTENT PARSER FUNKTIONEN
 * @author rexdev.de
 * @package redaxo4.2
 * @version svn:$Id$
 */
if (!function_exists('markitup_incparse'))
{
  function markitup_incparse($root,$source,$parsemode,$return=false)
  {

    switch ($parsemode)
    {
      case 'textile':
      $source = $root.$source;
      $content = file_get_contents($source);
      $html = markitup_textileparser($content,true);
      break;

      case 'txt':
      $source = $root.$source;
      $content = file_get_contents($source);
      $html =  '<pre class="plain">'.$content.'</pre>';
      break;

      case 'raw':
      $source = $root.$source;
      $content = file_get_contents($source);
      $html = $content;
      break;

      case 'php':
      $source = $root.$source;
      $html =  get_include_contents($source);
      break;



      case 'iframe':
      $html = '<iframe src="'.$source.'" width="99%" height="600px"></iframe>';
      break;

      case 'jsopenwin':
      $html = 'Externer link: <a href="'.$source.'">'.$source.'</a>
      <script language="JavaScript">
      <!--
      window.open(\''.$source.'\',\''.$source.'\');
      //-->
      </script>';
      break;

      case 'extlink':
      $html = 'Externer link: <a href="'.$source.'">'.$source.'</a>';
      break;
    }

    if($return)
    {
      return $html;
    }
    else
    {
      echo $html;
    }

  }
}

// TEXTILE PARSER FUNCTION
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('markitup_textileparser'))
{
  function markitup_textileparser($textile,$return=false)
  {
    if(OOAddon::isAvailable("textile"))
    {
      global $REX;

      if($textile!='')
      {
        $textile = htmlspecialchars_decode($textile);
        $textile = str_replace("<br />","",$textile);
        $textile = str_replace("&#039;","'",$textile);
        if (strpos($REX['LANG'],'utf'))
        {
          $html = rex_a79_textile($textile);
        }
        else
        {
          $html =  utf8_decode(rex_a79_textile($textile));
        }

        if($return)
        {
          return $html;
        }
        else
        {
          echo $html;
        }
      }

    }
    else
    {
      $html = rex_warning('WARNUNG: Das <a href="index.php?page=addon">Textile Addon</a> ist nicht aktiviert! Der Text wird ungeparst angezeigt..');
      $html .= '<pre>'.$textile.'</pre>';

      if($return)
      {
        return $html;
      }
      else
      {
        echo $html;
      }
    }
  }
}

// ECHO TEXTILE FORMATED STRING
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('echotextile'))
{
  function echotextile($msg) {
    global $REX;
    if(OOAddon::isAvailable("textile")) {
      if($msg!='') {
         $msg = str_replace(" ","",$msg); // tabs entfernen
         if (strpos($REX['LANG'],'utf')) {
          echo rex_a79_textile($msg);
        } else {
          echo utf8_decode(rex_a79_textile($msg));
        }
      }
    } else {
      $fallback = rex_warning('WARNUNG: Das <a href="index.php?page=addon">Textile Addon</a> ist nicht aktiviert! Der Text wird ungeparst angezeigt..');
      $fallback .= '<pre>'.$msg.'</pre>';
      echo $fallback;
    }
  }
}



// http://php.net/manual/de/function.include.php
////////////////////////////////////////////////////////////////////////////////
if (!function_exists('get_include_contents'))
{
  function get_include_contents($filename) {
    if (is_file($filename)) {
      ob_start();
      include $filename;
      $contents = ob_get_contents();
      ob_end_clean();
      return $contents;
    }
    return false;
  }
}


/**
* markitup_scandir Funktion - Recursiver Scan eines Verzeichnisses
*
* @author <a href="http://rexdev.de">rexdev.de</a>
*
* @package redaxo4
* @version 1.0
* $Id$:
*
* @param $source    (string)    Pfad des zu scanenden Verzeichnisses
* @param $limit     (int)       Scantiefe limitiert (1.-n.) Level bzw. nicht (0)
* @param $blacklist (array)     Auszuschließende Ordner oder Dateien per:
*                               - volle Ordner/Dateinamen
*                               - wildcard: 'prefix*' od. '*suffix';
* @param $whitelist (array)     Ergebnis (nur Dateien) eingrenzen auf:
*                               - wildcard: 'prefix*' od. '*suffix';
*
* @return      (array/null)     Array
*                               (
*                                   [basedir]         => (absolute PATH)/
*                                   [depth]           => (1-n)
*                                   [counter]         => Array
*                                       (
*                                           [folders] => (relative PATH)/
*                                           [files]   => (relative PATH)
*                                       )
*                                   [folders]         => Array
*                                       (
*                                           [1]       => (relative PATH)
*                                           [2]       => ...
*                                       )
*                                   [files]           => Array
*                                       (
*                                           [1]       => (relative PATH)
*                                           [2]       => ...
*                                       )
*                               )
*/

if (!function_exists('markitup_scandir'))
{
  function markitup_scandir($source, $limit=0, $blacklist=array(), $whitelist=array(), &$result=array())
  {//$fb = FirePHP::getInstance(true);
    // SANITIZE SOURCE PATH, CHECK IF IS DIR
    $source= '/'.trim($source,'/ ').'/';
    if(!is_dir($source))
    {
      return NULL;
    }

    // INIT RESULT ARRAY
    if(!isset($result['root']))
    {
      $root = $result['root'] = $source;
      $result['folders'] = NULL;
      $result['files'] = NULL;
      $result['depth'] = 1;
      $result['counter']['folders'] = 0;
      $result['counter']['files'] = 0;
    }

    // SCAN SOURCE DIR WHILE IGNORING FULL ITEMNAMES (WILDCARDS WON'T MATCH)
    $ignore = array('.DS_Store','.svn','.','..'); // bulitin ignores
    $ignore = array_merge((array)$ignore,(array)$blacklist); // merge bulitin irgnores with user blacklist
    $rawscan = scandir($source);
    $dirscan = array_diff($rawscan, $ignore); // subtract ignores from full listing
    //$fb->group('WILDCARD RESCAN');
    // RESCAN RESULT WITH WILDCARDS
    foreach($ignore as $i) // run through ignores (blacklist)
    {
      $i = explode('*',$i); // explode values strings to array by wildcard character
      if(count($i) == 2) // is valid wildcard string
      {//$fb->log($i,'$i');
        if(array_search('', $i) == 0) // wildcard string is extension
        {
          //$fb->group('EXTENSION matching');
          foreach($dirscan as $item) // run through prior scan result
          {//$fb->log($dirscan,'$dirscan');
            if(substr($item, '-'.strlen($i[1])) == $i[1]) // wipe extension matches from $dirscan array
            {//$fb->log($dirscan,'$dirscan');
              $dirscan = array_diff($dirscan, array($item));//$fb->log($dirscan,'$dirscan');
            }
          }
          //$fb->groupEnd();
        }
        else // wildcard string is prefix
        {
          //$fb->group('PREFIX matching');
          foreach($dirscan as $item) // run through prior scan result
          {
            if(substr($item, 0, strlen($i[0])) == $i[0]) // wipe prefix matches from $dirscan array
            {//$fb->group($item);$fb->log($dirscan,'IN ('.count($dirscan).')');$fb->log($item,'$item');$fb->log(substr($item, 0, strlen($i[0])),'substr($item, 0, strlen($i[0]))');$fb->log($i[0],'$i[0]');
              $dirscan = array_diff($dirscan, array($item));//$fb->log($dirscan,'OUT ('.count($dirscan).')');
            }
          }
          //$fb->groupEnd();
        }
      }
    }
    //$fb->groupEnd();

    // WALK THROUGH RESULT RECURSIVELY
    foreach($dirscan as $item)
    {
      // DO DIR STUFF
      if (is_dir($source.$item))
      {
        $i = count($result['folders']) + 1;
        $result['folders'][$i] = str_replace($result['root'], '', $source.$item).'/';
        $result['counter']['folders']++;

        $depth = count(explode('/',str_replace($result['root'], '', $source.$item.'/'))); //fb($depth,'$depth');
        if($depth>$result['depth'])
        {
          $result['depth'] = $depth;
        }

        // RECURSION IF NOT LIMITED
        if($limit && intval($limit))
        { //fb('LIMITED recursion');
          if($limit > $depth)
          {
            markitup_scandir($source.$item.'/', $limit, $blacklist, $whitelist, $result);
          }
        }
        else
        { //fb('UN-LIMITED recursion');
          markitup_scandir($source.$item.'/', $limit, $blacklist, $whitelist, $result);
        }
      }

      // DO FILE STUFF
      elseif (is_file($source.$item))
      {
        $depth = count(explode('/',$source));

        if(count($whitelist)>0) // LIMIT ACCORDING WHITELIST
        {//fb('LIMIT ACCORDING WHITELIST --------------------------------------');
          foreach($whitelist as $w)
          {
            $w = explode('*',$w); // string auf wildcard prüfen per zerlegen
            if(count($w) == 2) // korrekter wildcard string  -> weiter
            {
              if(array_search('', $w) == 0) // extension
              { //fb($w[1],'MATCH EXTENSION'); fb($item,'$item'); fb(substr($item, '-'.strlen($w[1])),'substr'); fb($w[1],'$w[1]');
                if(substr($item, '-'.strlen($w[1])) == $w[1])
                {
                  $i = count($result['files']) + 1;
                  $result['files'][$i] = str_replace($result['root'], '', $source.$item);
                  $result['counter']['files']++;
                }
              }
              else /* prefix */
              {//fb($w[0],'MATCH PREFIX'); fb($item,'$item'); fb(substr($item, strlen($w[0])),'substr'); fb($w[0],'$w[0]');
                if(substr($item, 0, strlen($w[0])) == $w[0])
                {
                  $i = count($result['files']) + 1;
                  $result['files'][$i] = str_replace($result['root'], '', $source.$item);
                  $result['counter']['files']++;
                }
              }
            }
          }
        }
        else // NO WHITELIST -> GET ALL
        {//fb('NO WHITELIST -> GET ALL ----------------------------------------');
          $i = count($result['files']) + 1;
          $result['files'][$i] = str_replace($result['root'], '', $source.$item);
          $result['counter']['files']++;
        }
      }
    }

    // CHECK RESULT, IF NO MATCHES AT ALL -> RETURN NULL
    if ($result['files']==NULL && $result['folders']==NULL)
    {
      return NULL;
    }
    else
    {
      return $result;
    }
  }
}
