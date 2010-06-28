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
$buttons         = rex_request('MEDIALIST', 'array');
$buttons = $buttons[1];
$width           = rex_request('width', 'string');
$height          = rex_request('height', 'string');
$preview         = rex_request('preview', 'string');

// UPDATE/SAVE SETTINGS
////////////////////////////////////////////////////////////////////////////////
if ($func == "update")
{
  $REX['ADDON']['markitup']['default']['buttons'] = $buttons;
  $REX['ADDON']['markitup']['default']['width']   = $width;
  $REX['ADDON']['markitup']['default']['height']  = $height;
  $REX['ADDON']['markitup']['default']['preview'] = $preview;

  $content = '
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'buttons\'] = \''.$buttons.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'width\']   = \''.$width.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'height\']  = \''.$height.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'preview\']  = \''.$preview.'\';
';

  $file = $REX['INCLUDE_PATH'].'/addons/markitup/config.inc.php';
  rex_replace_dynamic_contents($file, $content);

  echo rex_info('Konfiguration wurde aktualisiert');
}

// REVISION CHECK
////////////////////////////////////////////////////////////////////////////////
/*$this_revision = intval($REX['ADDON'][$myself]['revision']);
$Parser = new rexseo_FeedParser();
$Parser->parse('http://www.gn2-code.de/projects/markitup/activity.atom?key=4372f934b085621f0878e4d8d2dc8b1a4c3fd9dc');
$items = $Parser->getItems();
$latest_revision = false;

foreach ($items as $item)
{
  if (strpos($item['TITLE'],'Revision') !== false)
  {
    $latest_revision = intval(str_replace('Revision ','',$item['TITLE']));
    break;
  }
}
unset($Parser);
if($latest_revision > $this_revision)
{
  echo rex_info('Eine neue SVN Version ist verf&uuml;gbar: <a href="index.php?page='.$myself.'&subpage=help&chapter=changelog&highlight=Revision+'.$latest_revision.'">Revision '.$latest_revision.'</a>');
}*/

// BUTTON SET WIDGET
////////////////////////////////////////////////////////////////////////////////
require_once $REX['INCLUDE_PATH'].'/addons/markitup/functions/function.rexdev_scandir.inc.php';
$button_root = $REX['INCLUDE_PATH'].'/addons/markitup/data/sets/default/';
$found_buttons = rexdev_scandir($button_root,0,array(),array('*.button'));
$found_buttons = $found_buttons['files'];

$builtin_buttons = array(

'&Uuml;berschriften' => 'optgroup',
'h1' =>                 'h1',
'h2' =>                 'h2',
'h3' =>                 'h3',
'h4' =>                 'h4',
'h5' =>                 'h5',
'h6' =>                 'h6',

'Textformatierung' =>   'optgroup',
'bold' =>               'bold',
'italic' =>             'italic',
'stroke' =>             'stroke',
'superscript' =>        'superscript',
'subscript' =>          'subscript',

'Ausrichtung' =>        'optgroup',
'alignleft' =>          'alignleft',
'alignright' =>         'alignright',
'aligncenter' =>        'aligncenter',
'alignjustify' =>       'alignjustify',

'Tabellen & Listen' =>  'optgroup',
'table' =>              'table',
'listbullet' =>         'listbullet',
'listnumeric' =>        'listnumeric',

'Dateien & Links' =>    'optgroup',
'image' =>              'image',
'linkmedia' =>          'linkmedia',
'linkintern' =>         'linkintern',
'linkextern' =>         'linkextern',
'linkmailto' =>         'linkmailto',

'Code-Bl&ouml;cke' =>   'optgroup',
'blockquote' =>         'blockquote',
'code' =>               'code',

'Sonderfunktionen' =>   'optgroup',
'separator' =>          'separator',
'clean' =>              'clean',
'preview' =>            'preview'
);

// BUILTIN BUTTONS
$button_panel = '';
$optgroup = '';
$builtin_raw = array();

foreach($builtin_buttons as $k => $v)
{
  switch($v)
  {
    case 'optgroup':
      if($optgroup = 'open')
      {
        $button_panel .='</p>';
      }
      $button_panel .='<h4 style="float:none;clear:left;margin:0;color:gray;">'.$k.'</h4>';
      $button_panel .='<p style="margin:0 0 6px 0;">';
      $optgroup = 'open';
    break;

    default:
      $button_panel .='<a href="javascript:selectMedialist(\''.$v.'\');" style="float:left;background:#EFF9F9;margin:1px;padding:1px;border:1px solid silver;"><img src="include/addons/markitup/data/sets/default/'.$v.'.png" alt="Button '.strtoupper($v).' hinzufuegen" title="Button '.strtoupper($v).' hinzufuegen" width="16" height="16" /></a>';
      $builtin_raw[] = $v.'.button';
  }
}

// 3RD PARTY BUTTONS
$extra_buttons = array_diff($found_buttons, $builtin_raw);

if(count($extra_buttons) > 0)
{
  if($optgroup = 'open')
  {
    $button_panel .='</p>';
  }
  $button_panel .='<h4 style="float:none;clear:left;margin:0;color:gray;">3rd Party</h4>';
  $button_panel .='<p style="margin:0 0 6px 0;">';
  foreach($extra_buttons as $k => $v)
  {
    $v = str_replace('.button','',$v);
    $button_panel .='<a href="javascript:selectMedialist(\''.$v.'\');" style="float:left;background:#EFF9F9;margin:1px;padding:1px;border:1px solid silver;"><img src="include/addons/markitup/data/sets/default/'.$v.'.png" alt="Button '.strtoupper($v).' hinzufuegen" title="Button '.strtoupper($v).' hinzufuegen" width="16" height="16" /></a>';
  }
  $optgroup = 'open';
}

if($optgroup = 'open')
{
  $button_panel .='</p>';
}

// ACTIVE BUTTONS
$active_buttons ='';
foreach(explode(',',$REX['ADDON']['markitup']['default']['buttons']) as $v)
{
  // $active_buttons .='<option style="background:url(include/addons/markitup/data/sets/default/'.$v.'.png) no-repeat 6px 0;padding: 1px 0 1px 50px;border-bottom:1px solid white;height:16px;float:left;" value="'.$v.'">'.$v.'</option>';
  $active_buttons .='<option value="'.$v.'">'.$v.'</option>';
}

$select_size = count(explode(',',$REX['ADDON']['markitup']['default']['buttons'])) + 2;

// PREVIEW SELECT BOX OPTIONS
////////////////////////////////////////////////////////////////////////////////
$def_preview_option = array(
'inline'  => 'INLINE (Unterhalb Markitup Fenster, reines HTML ohne CSS)',
'wysiwyg' => 'WYSIWYG (Frontend Voransicht in neuem Fenster)'
);

$preview_option = '';
foreach($def_preview_option as $val => $str)
{
  if($val == $REX['ADDON']['markitup']['default']['preview'])
  {
    $preview_option .= '<option value="'.$val.'" selected="selected">'.$str.'</option>';
  }
  else
  {
    $preview_option .= '<option value="'.$val.'">'.$str.'</option>';
  }
}

// FORM
////////////////////////////////////////////////////////////////////////////////
echo '
<script type="text/javascript">
<!--

var opener = self;

function selectMedia(filename)
{
  opener.document.getElementById("REX_MEDIALIST_1").value = filename;  self.close();
}

function selectMedialist(filename)
{
  var medialist = "REX_MEDIALIST_SELECT_1";

  var source = opener.document.getElementById(medialist);
  var sourcelength = source.options.length;

  option = opener.document.createElement("OPTION");
  option.text = filename;
  option.value = filename;

  source.options.add(option, sourcelength);
  opener.writeREXMedialist(1);}

function insertImage(src,alt)
{
  window.opener.insertImage(\'files/\' + src, alt);
  self.close();
}

function insertLink(src)
{
  window.opener.insertFileLink(\'files/\' + src);
  self.close();
}

function openPage(src)
{
  window.opener.location.href = src;
  self.close();
}

//-->
</script>

<div class="rex-addon-output">
  <div class="rex-form">

  <form action="index.php" method="get">
    <input type="hidden" name="page" value="markitup" />
    <input type="hidden" name="subpage" value="settings" />
    <input type="hidden" name="func" value="update" />

    <fieldset class="rex-form-col-1">
      <legend>Editor-Defaults</legend>
      <div class="rex-form-wrapper">

      <div class="rex-form-row">
          <label for="rex-widget">Button-Set [<a href="index.php?page=markitup&subpage=help">?</a>]</label>
        <div class="rex-widget" id="rex-widget">
          <div class="rex-widget-medialist">

            <input type="hidden" name="MEDIALIST[1]" id="REX_MEDIALIST_1" value="'.$REX['ADDON']['markitup']['default']['buttons'].'" />

            <p class="rex-widget-field">
              <select name="MEDIALIST_SELECT[1]" id="REX_MEDIALIST_SELECT_1" size="'.$select_size.'" tabindex="31" style="width:200px;font-family:monospace;font-size:12px;">
              '.$active_buttons.'
              </select>
            </p>';

            if($REX['SUBVERSION']>2)
            {
            // 4.3.x
            echo '
            <p class="rex-widget-icons">
              <a href="#" class="rex-icon-file-top" onclick="moveREXMedialist(1,\'top\');return false;" tabindex="32"></a>
              <a href="#" class="rex-icon-file-up" onclick="moveREXMedialist(1,\'up\');return false;" tabindex="34"></a>
              <a href="#" class="rex-icon-file-down" onclick="moveREXMedialist(1,\'down\');return false;" tabindex="36"></a>
              <a href="#" class="rex-icon-file-bottom" onclick="moveREXMedialist(1,\'bottom\');return false;" tabindex="38"></a>
              <br /><br />
              <a href="#" class="rex-icon-file-delete" onclick="deleteREXMedialist(1);return false;" tabindex="37"></a>
            </p>';
            }
            else
            {
            // 4.2.x
            echo '
            <p class="rex-widget-icons">
              <a href="#" class="rex-icon-file-top" onclick="moveREXMedialist(1,\'top\');return false;" tabindex="32"><img src="media/file_top.gif" width="16" height="16" title="Ausgewähltes Medium an den Anfang verschieben" alt="Ausgewähltes Medium an den Anfang verschieben" /></a>
              <a href="#" class="rex-icon-file-delete" onclick="deleteREXMedialist(1);return false;" tabindex="37"><img src="media/file_del.gif" width="16" height="16" title="Ausgewähltes Medium löschen" alt="Ausgewähltes Medium löschen" /></a>
              <br />
              <a href="#" class="rex-icon-file-up" onclick="moveREXMedialist(1,\'up\');return false;" tabindex="34"><img src="media/file_up.gif" width="16" height="16" title="Ausgewaehltes Medium nach oben verschieben" alt="Ausgewaehltes Medium an den Anfang verschieben" /></a>
              <br />
              <a href="#" class="rex-icon-file-down" onclick="moveREXMedialist(1,\'down\');return false;" tabindex="36"><img src="media/file_down.gif" width="16" height="16" title="Ausgewaehltes Medium nach unten verschieben" alt="Ausgewaehltes Medium nach unten verschieben" /></a>
              <br />
              <a href="#" class="rex-icon-file-bottom" onclick="moveREXMedialist(1,\'bottom\');return false;" tabindex="38"><img src="media/file_bottom.gif" width="16" height="16" title="Ausgewaehltes Medium an das Ende verschieben" alt="Ausgewaehltes Medium an das Ende verschieben" /></a>
            </p>';
            }


            echo '
            <div class="rex-widget-icons" style="margin-left:10px;float:left;">
            '.$button_panel.'
            </div>

          </div>
        </div>
      </div>


      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="width">Breite:</label>
          <input type="text" id="width" name="width" value="'.stripslashes($REX['ADDON']['markitup']['default']['width']).'">
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="height">H&ouml;he:</label>
          <input type="text" id="height" name="height" value="'.stripslashes($REX['ADDON']['markitup']['default']['height']).'">
        </p>
      </div>

        <div class="rex-form-row">
          <p class="rex-form-col-a rex-form-select">
            <label for="preview">Preview Typ:</label>
            <select id="preview" name="preview" class="rex-form-select">
              '.$preview_option.'
            </select>
          </p>


      <div class="rex-form-row rex-form-element-v2">
        <p class="rex-form-submit">
          <input class="rex-form-submit" type="submit" id="sendit" name="sendit" value="Einstellungen speichern" />
        </p>
      </div>


      </div>
    </fieldset>
  </form>
  </div>
</div>
';
?>