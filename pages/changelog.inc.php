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


global $REX;

$Parser = new SimplePie();
//$Parser->set_cache_location($REX['INCLUDE_PATH'].'/generated/files');
$Parser->set_feed_url('http://www.gn2-code.de/projects/markitup/activity.atom?key=4372f934b085621f0878e4d8d2dc8b1a4c3fd9dc');
$Parser->init();
$Parser->handle_content_type();

foreach($Parser->get_items(0,20) as $item)
{
  $date  = $item->get_date('d.m.Y H:i');
  $id    = $item->get_id();
  $title = $item->get_title();

  //$title = str_replace('Markitup -','',$title);
  if(!strpos($REX['LANG'],'utf'))
  {
    $title = utf8_decode($title);
  }

  if (substr($title,0,8)=="Revision")
  {
    echo '<div class="markitup_ticket">';
    echo '<span class="markitup_date">'.$date.'</span><a class="jsopenwin" target="_blank" href="'.$id.'/diff'.'">'.$title.'</a>';
    echo '</div>';
  }
}

unset($Parser);
