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


/**
* Redmine Project Feed Parser
* @param $project_root_url (string)                              HTTP URL of Redmine Project
* @param $key              (string)                              Redmine Project Key
* @param $func             (string) [download|tickets|changelog] topics
*
* Requires SimplePie Feed Parser Class
* @link http://simplepie.org/
*/
if(!function_exists('a287_redmine_project_feed'))
{
  function a287_redmine_project_feed($project_root_url='',$key='',$func='')
  {
    global $REX;

    if(!class_exists('simplepie'))
    {
      return 'function redmine_project_feed(): class Simplepie not found.';
    }

    if ($project_root_url!='' && $key!='' && $func!='')
    {
      $feed_url  = rtrim($project_root_url,'/').'/activity.atom?key='.$key;
      $files_url = rtrim($project_root_url,'/').'/files';
      $html      = '';

      // PARSE
      //////////////////////////////////////////////////////////////////////////
      $Parser = new SimplePie();
      $Parser->set_cache_location($REX['INCLUDE_PATH'].'/generated/files');
      $Parser->set_cache_duration(300);
      $Parser->set_feed_url($feed_url);
      $Parser->init();
      $Parser->handle_content_type();
      $items = $Parser->get_items(0,20);

      foreach($items as $item)
      {
        $date  = $item->get_date('d.m.Y H:i');
        $id    = $item->get_id();
        $title = $item->get_title();

        if(!strpos($REX['LANG'],'utf'))
        {
          $title = utf8_decode($title);
        }

        switch($func)
        {
          case 'download':
            if (substr($title,-4,4)=='.zip')
            {
              $html .= '<span class="redmine-date">'.$date.'</span><a class="" href="'.$id.'">'.$title.'</a><li>';
            }
            break;

          case 'tickets':
            if (substr($title,0,8)!='Revision' && substr($title,-4,4)!='.zip')
            {
              $html .= '<li><span class="redmine-date">'.$date.'</span><a class="jsopenwin" target="_blank" href="'.$id.'">'.$title.'</a><li>';
            }
            break;

          case 'changelog':
            if (substr($title,0,8)=='Revision')
            {
              $html .= '<li><span class="redmine-date">'.$date.'</span><a class="jsopenwin" target="_blank" href="'.$id.'/diff'.'">'.$title.'</a></li>';
            }
            break;
        }
      }

      unset($Parser);

      if($html=='')
      {
        $html = '<ul class="redmine-feed">';
        $html .= '<li><strong>RSS feed:</strong> Keine aktuellen Eintragungen.</li>';
        if($func == 'download')
        {
          $html .= '<li><span class="redmine-date">Downloadseite:</span><a class="jsopenwin" target="_blank" href="'.$files_url.'">'.$files_url.'</a></li>';
        }
      }
      else
      {
        $html = '<ul class="redmine-feed">'.$html;
        if($func == 'download')
        {
          $html .= '<li><span class="redmine-date">Downloadseite:</span><a class="jsopenwin" target="_blank" href="'.$files_url.'">'.$files_url.'</a></li>';
        }
        $html .= '</ul>';
      }

    return $html;
    }
    else
    {
      return 'a287_redmine_project_feed(): missing input parameters..';
    }
  }
}
