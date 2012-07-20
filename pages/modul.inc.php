<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.1.60
 */

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself             = rex_request('page', 'string');
$subpage            = rex_request('subpage', 'string');
$func               = rex_request('func', 'string');
$wysiwyg_module_id  = rex_request('wysiwyg_module_id', 'int');
$standard_module_id = rex_request('standard_module_id', 'int');

// STANDARD MODUL INSTALL
////////////////////////////////////////////////////////////////////////////////
$standard_modul_in  = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_standard.modul.in.php';
$standard_modul_in_file = array_pop(explode('/',$standard_modul_in));
$standard_modul_out = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_standard.modul.out.php';
$standard_modul_out_file = array_pop(explode('/',$standard_modul_out));
$standard_search = '### UID:m287standard ###';
$standard_module_id = 0;
$standard_module_name = "";

$gm = new rex_sql;
$gm->setQuery('select * from '.$REX['TABLE_PREFIX'].'module where ausgabe LIKE "%'.$standard_search.'%"');

foreach($gm->getArray() as $standard_module)
{
  $standard_module_id = $standard_module["id"];
  $standard_module_name = $standard_module["name"];
}

if ($func == 'install_standard')
{
  $default_module_name = "Markitup STANDARD Modul";

  // Daten einlesen
  $in = rex_get_file_contents($standard_modul_in);
  $out = rex_get_file_contents($standard_modul_out);

  $mi = new rex_sql;
  // $mi->debugsql = 1;
  $mi->setTable($REX['TABLE_PREFIX'].'module');
  $mi->setValue("eingabe",addslashes($in));
  $mi->setValue("ausgabe",addslashes($out));

  // altes Module aktualisieren
  if (isset($_REQUEST["standard_module_id"]) && $standard_module_id==$_REQUEST["standard_module_id"])
  {
    $mi->setWhere('id="'.$standard_module_id.'"');
    $mi->update();
    echo rex_info('Modul "'.$standard_module_name.'" wurde wiederhergestellt.');
  }
  else
  {
    $mi->setValue("name",$default_module_name);
    $mi->insert();
    echo rex_info('Modul wurde angelegt als "'.$default_module_name.'"');
  }
  unset($mi);
}

// WYSIWYG MODUL INSTALL
////////////////////////////////////////////////////////////////////////////////
$wysiwyg_modul_in  = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_wysiwyg.modul.in.php';
$wysiwyg_modul_in_file = array_pop(explode('/',$wysiwyg_modul_in));
$wysiwyg_modul_out = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_wysiwyg.modul.out.php';
$wysiwyg_modul_out_file = array_pop(explode('/',$wysiwyg_modul_out));
$wysiwyg_search = '### UID:m287wysiwyg ###';
$wysiwyg_module_id = 0;
$wysiwyg_module_name = "";

$gm = new rex_sql;
$gm->setQuery('select * from '.$REX['TABLE_PREFIX'].'module where ausgabe LIKE "%'.$wysiwyg_search.'%"');

foreach($gm->getArray() as $wysiwyg_module)
{
  $wysiwyg_module_id = $wysiwyg_module["id"];
  $wysiwyg_module_name = $wysiwyg_module["name"];
}

if ($func == 'install_wysiwyg')
{
  $default_module_name = "Markitup WYSIWYG Modul";

  // Daten einlesen
  $in = rex_get_file_contents($wysiwyg_modul_in);
  $out = rex_get_file_contents($wysiwyg_modul_out);

  $mi = new rex_sql;
  // $mi->debugsql = 1;
  $mi->setTable($REX['TABLE_PREFIX'].'module');
  $mi->setValue("eingabe",addslashes($in));
  $mi->setValue("ausgabe",addslashes($out));

  // altes Module aktualisieren
  if (isset($_REQUEST["wysiwyg_module_id"]) && $wysiwyg_module_id==$_REQUEST["wysiwyg_module_id"])
  {
    $mi->setWhere('id="'.$wysiwyg_module_id.'"');
    $mi->update();
    echo rex_info('Modul "'.$wysiwyg_module_name.'" wurde wiederhergestellt.');
  }
  else
  {
    $mi->setValue("name",$default_module_name);
    $mi->insert();
    echo rex_info('Modul wurde angelegt als "'.$default_module_name.'"');
  }
  unset($mi);
}

// MAIN
////////////////////////////////////////////////////////////////////////////////
$standard_msg = array('','');
if($standard_module_id > 0)
{
  $standard_msg = array(
  'Weiteres ',
  ' oder vorhandenes Modul wiederherstellen: <a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=install_standard&amp;standard_module_id='.$standard_module_id.'">['.$standard_module_id.'] '.htmlspecialchars($standard_module_name).'</a>'
  );
}
$wysiwyg_msg = array('','');
if($wysiwyg_module_id > 0)
{
  $wysiwyg_msg = array(
  'Weiteres ',
  ' oder vorhandenes Modul wiederherstellen: <a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=install_wysiwyg&amp;wysiwyg_module_id='.$wysiwyg_module_id.'">['.$wysiwyg_module_id.'] '.htmlspecialchars($wysiwyg_module_name).'</a>'
  );
}

echo '
<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">Beispielmodule</h2>

  <div class="rex-addon-content">
    <div class="markitup">
      <h1>Standard Modul</h1>
      <ul>
        <li>'.$standard_msg[0].'<a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=install_standard">Beispielmodul installieren</a>'.$standard_msg[1].'</li>
        <li><a id="standard_show">Modul Code anzeigen</a></li>
      </ul>
    </div><!-- /.markitup -->


  <div class="markitup" id="standard_modul" style="display:none;">
  <h4>'.$standard_modul_in_file.':</h4>';
    $file = $standard_modul_in;
    $fh = fopen($file, 'r');
    $contents = fread($fh, filesize($file));
    ini_set('highlight.comment', 'silver;font-size:10px;display:none;');
    echo rex_highlight_string($contents);

    echo '
    <h4>'.$standard_modul_out_file.':</h4>';
    $file = $standard_modul_out;
    $fh = fopen($file, 'r');
    $contents = fread($fh, filesize($file));
    echo rex_highlight_string($contents);
    echo '
  </div><!-- /.markitup -->

  <div class="markitup">
    <h1>WYSIWYG Modul</h1>
    <ul>
      <li>'.$wysiwyg_msg[0].'<a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=install_wysiwyg">Beispielmodul installieren</a>'.$wysiwyg_msg[1].'</li>
      <li><a id="wysiwyg_show">Modul Code anzeigen</a></li>
    </ul>
   </div><!-- /.markitup -->


  <div class="markitup" id="wysiwyg_modul" style="display:none;">
  <h4>'.$wysiwyg_modul_in_file.':</h4>';
    $file = $wysiwyg_modul_in;
    $fh = fopen($file, 'r');
    $contents = fread($fh, filesize($file));
    //ini_set('highlight.comment', 'silver;font-size:10px;display:inline;');
    echo rex_highlight_string($contents);
    echo '
    <h4>'.$wysiwyg_modul_out_file.':</h4>';
    $file = $wysiwyg_modul_out;
    $fh = fopen($file, 'r');
    $contents = fread($fh, filesize($file));
    echo rex_highlight_string($contents);
    echo '
  </div><!-- /.markitup -->

  <div class="markitup">
    <p>Die Dateien der Beispielmodule befinden sich im Addon Ordner: <cite>./addons/markitup/modules/...</cite></p>
  </div><!-- /.markitup -->


  </div><!-- /.rex-addon-content -->
</div><!-- /.rex-addon-output -->


<script type="text/javascript">
<!--
jQuery(function($) {


  $("#standard_show").click(function() {
    $("#standard_modul").slideToggle("slow");
  });

  $("#wysiwyg_show").click(function() {
    $("#wysiwyg_modul").slideToggle("slow");
  });


});
//-->
</script>

';
