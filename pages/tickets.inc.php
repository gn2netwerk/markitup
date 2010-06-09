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

$Parser = new rexseo_FeedParser();

$Parser->parse('http://www.gn2-code.de/projects/markitup/activity.atom?key=4372f934b085621f0878e4d8d2dc8b1a4c3fd9dc');

$items = $Parser->getItems();

foreach ($items as $item) {

	if(!strpos($REX['LANG'],'utf')) {
		$item['TITLE'] = utf8_decode($item['TITLE']);
	}

	$date = date('d.m.Y H:i:s',$item['UPDATED']);
	$item['TITLE'] = str_replace('REXseo -','',$item['TITLE']);

	if (substr($item['TITLE'],0,8)!="Revision") {
		echo '<div class="markitup_ticket">';
		echo '<span class="markitup_date">'.$date.'</span><a class="jsopenwin" target="_blank" href="'.$item['ID'].'">'.$item['TITLE'].'</a>';
		echo '</div>';
	}
}

?>
