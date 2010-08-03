<?php
/**
* rexdev_scandir Funktion - Recursiver Scan eines Verzeichnisses
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

if (!function_exists('rexdev_scandir'))
{
  function rexdev_scandir($source, $limit=0, $blacklist=array(), $whitelist=array(), &$result=array())
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
            rexdev_scandir($source.$item.'/', $limit, $blacklist, $whitelist, $result);
          }
        }
        else
        { //fb('UN-LIMITED recursion');
          rexdev_scandir($source.$item.'/', $limit, $blacklist, $whitelist, $result);
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
?>