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

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself          = rex_request('page', 'string');
$subpage         = rex_request('subpage', 'string');
$func            = rex_request('func', 'string');
$module_id       = rex_request('module_id', 'int');

// MODUL SOURCE
////////////////////////////////////////////////////////////////////////////////
$modul_in  = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_demo.modul.in.php';
$modul_out = $REX['INCLUDE_PATH'].'/addons/'.$myself.'/modules/markitup_demo.modul.out.php';

// MODUL INSTALL
////////////////////////////////////////////////////////////////////////////////
$searchtext = ' * MARKITUP Addon
 * DEMO MODUL';

$gm = new rex_sql;
$gm->setQuery('select * from rex_module where ausgabe LIKE "%'.$searchtext.'%"');

$module_id = 0;
$module_name = "";
foreach($gm->getArray() as $module)
{
  $module_id = $module["id"];
  $module_name = $module["name"];
}

if ($func == 'modulinstall')
{

  $xform_module_name = "Markitup Demo Modul";

  // Daten einlesen
  $in = rex_get_file_contents($modul_in);
  $out = rex_get_file_contents($modul_out);

  $mi = new rex_sql;
  // $mi->debugsql = 1;
  $mi->setTable("rex_module");
  $mi->setValue("eingabe",addslashes($in));
  $mi->setValue("ausgabe",addslashes($out));

  // altes Module aktualisieren
  if (isset($_REQUEST["module_id"]) && $module_id==$_REQUEST["module_id"])
  {
    $mi->setWhere('id="'.$module_id.'"');
    $mi->update();
    echo rex_info('Modul "'.$module_name.'" wurde aktualisiert');
  }else
  {
    $mi->setValue("name",$xform_module_name);
    $mi->insert();
    echo rex_info('Modul wurde angelegt unter "'.$xform_module_name.'"');
  }

}

// MAIN
////////////////////////////////////////////////////////////////////////////////
echo '

<div class="rex-addon-output">
  <h2 class="rex-hl2" style="font-size: 1em;">Beispielmodul</h2>

  <div class="rex-addon-content">
    <ul>
      <li><a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=modulinstall">Beispielmodul installieren</a></li>';

if ($module_id>0)
{
  echo '<li><a href="index.php?page='.$myself.'&amp;subpage=modul&amp;func=modulinstall&amp;module_id='.$module_id.'">Dieses Modul aktualisieren: <em style="color:red;font-style:normal;">['.$module_id.'] '.htmlspecialchars($module_name).'</em></a></li>';
}

echo '
      <li><a id="code_show">Modul Code anzeigen</a></li>
    </ul>
   <!--<h2><a href="index.php?page=markitup&subpage=modul&func=modulinstall">Beispielmodul installieren</a></h2>
   <h2><a id="code_show">Modul Code anzeigen</a></h2>-->


    <div class="markitup" id="code_block" style="display:none;margin:0;padding:0;">
    <h4>MODUL IN:  <span style="color:gray;font-weight:normal;">(./'.$myself.'/modules/markitup_demo.modul.in.php)</span></h4>';

$file = $modul_in;
$fh = fopen($file, 'r');
$contents = fread($fh, filesize($file));
echo rex_highlight_string($contents);

echo '
    <h4>MODUL OUT: <span style="color:gray;font-weight:normal;">(./'.$myself.'/modules/markitup_demo.modul.in.php)</span></h4>
    ';

$file = $modul_out;
$fh = fopen($file, 'r');
$contents = fread($fh, filesize($file));
echo rex_highlight_string($contents);

echo '
    </div><!-- /.markitup -->
  </div><!-- /.rex-addon-content -->
</div><!-- /.rex-addon-output -->


<script type="text/javascript">
<!--
jQuery(function($) {


  $("#code_show").click(function() {
    $("#code_block").slideToggle("slow");
  });


});
//-->
</script>

';