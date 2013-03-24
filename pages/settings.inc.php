<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.2.0
 */

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself            = rex_request('page', 'string');
$subpage           = rex_request('subpage', 'string');
$func              = rex_request('func', 'string');
$buttons           = rex_request('MEDIALIST', 'array');
$buttons           = isset($buttons[1]) ? $buttons[1] : '';
$width             = rex_request('width', 'string');
$height            = rex_request('height', 'string');
$preview           = rex_request('preview', 'string');
$shortcuts         = rex_request('shortcuts', 'string');
$shortcuts         = rtrim(str_replace("\n",'|',str_replace("\r",'',$shortcuts)),'|');
$resizemode        = rex_request('resizemode', 'int');
$autoenable_status = rex_request('autoenable_status', 'int');
$autoenable_class  = rex_request('autoenable_class', 'string','rex-markitup');

// UPDATE/SAVE SETTINGS
////////////////////////////////////////////////////////////////////////////////
if ($func == "update")
{
  $REX['ADDON']['markitup']['default']['buttons']    = $buttons;
  $REX['ADDON']['markitup']['default']['width']      = $width;
  $REX['ADDON']['markitup']['default']['height']     = $height;
  $REX['ADDON']['markitup']['default']['preview']    = $preview;
  $REX['ADDON']['markitup']['default']['shortcuts']  = $shortcuts;
  $REX['ADDON']['markitup']['default']['resizemode'] = $resizemode;
  $REX['ADDON']['markitup']['autoenable_status']     = $autoenable_status;
  $REX['ADDON']['markitup']['autoenable_class']      = $autoenable_class;

  $content = '
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'buttons\']    = \''.$buttons.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'width\']      = \''.$width.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'height\']     = \''.$height.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'preview\']    = \''.$preview.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'shortcuts\']  = \''.$shortcuts.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'resizemode\'] =   '.$resizemode.';
$REX[\'ADDON\'][\'markitup\'][\'autoenable_status\']     =   '.$autoenable_status.';
$REX[\'ADDON\'][\'markitup\'][\'autoenable_class\']      = \''.$autoenable_class.'\';
';

  $file = $REX['INCLUDE_PATH'].'/addons/markitup/config.inc.php';
  rex_replace_dynamic_contents($file, $content);

  echo rex_info('Konfiguration wurde aktualisiert');
}


// BUTTON SET WIDGET
////////////////////////////////////////////////////////////////////////////////
$button_root = $REX['INCLUDE_PATH'].'/addons/markitup/lib/sets/default/';
$found_buttons = markitup_scandir($button_root,0,array(),array('*.button'));
$found_buttons = $found_buttons['files'] ? $found_buttons['files'] : array();

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
      $button_panel .='<a href="javascript:selectMedialist(\''.$v.'\');" style="float:left;background:#EFF9F9;margin:1px;padding:1px;border:1px solid silver;"><img src="include/addons/markitup/lib/sets/default/'.$v.'.png" alt="Button '.strtoupper($v).' hinzufuegen" title="Button '.strtoupper($v).' hinzufuegen" width="16" height="16" /></a>';
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
    $button_panel .='<a href="javascript:selectMedialist(\''.$v.'\');" style="float:left;background:#EFF9F9;margin:1px;padding:1px;border:1px solid silver;"><img src="include/addons/markitup/lib/sets/default/'.$v.'.png" alt="Button '.strtoupper($v).' hinzufuegen" title="Button '.strtoupper($v).' hinzufuegen" width="16" height="16" /></a>';
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
  // $active_buttons .='<option style="background:url(include/addons/markitup/lib/sets/default/'.$v.'.png) no-repeat 6px 0;padding: 1px 0 1px 50px;border-bottom:1px solid white;height:16px;float:left;" value="'.$v.'">'.$v.'</option>';
  $active_buttons .='<option value="'.$v.'">'.$v.'</option>';
}

$widget_size = count(explode(',',$REX['ADDON']['markitup']['default']['buttons'])) + 2;

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

// TEXTAREA RESIZEMODE SELECT BOX OPTIONS
////////////////////////////////////////////////////////////////////////////////
$def_resizemode_option = array(
  0 => 'auto',
  1 => 'manual'
);

$resizemode_option = '';
foreach($def_resizemode_option as $val => $str)
{
  if($val == $REX['ADDON']['markitup']['default']['resizemode'])
  {
    $resizemode_option .= '<option value="'.$val.'" selected="selected">'.$str.'</option>';
  }
  else
  {
    $resizemode_option .= '<option value="'.$val.'">'.$str.'</option>';
  }
}

// SHORTCUTS
////////////////////////////////////////////////////////////////////////////////
$shortcuts = stripslashes($REX['ADDON']['markitup']['default']['shortcuts']);
$shortcuts = str_replace('|',"\n",$shortcuts);
$shortcuts_size = count(explode('|',$REX['ADDON']['markitup']['default']['shortcuts']));
$rex_keys = implode(', ',$REX['ACKEY']);


// AUTOENABLE STATUS SELECT
////////////////////////////////////////////////////////////////////////////////
$tmp = new rex_select;
$tmp->setName('autoenable_status');
$tmp->setId('autoenable_status');
$tmp->addOptions(array(
  '1'  => 'true',
  '0'  => 'false',
  )
);
$tmp->setSelected($REX['ADDON']['markitup']['autoenable_status']);
$tmp->setSize(1);
$autoenable_status_sel = $tmp->get();


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
              <select name="MEDIALIST_SELECT[1]" id="REX_MEDIALIST_SELECT_1" size="'.$widget_size.'" tabindex="31" style="width:200px;font-family:monospace;font-size:12px;">
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
          <label for="width">Width:</label>
          <input type="text" id="width" name="width" value="'.stripslashes($REX['ADDON']['markitup']['default']['width']).'">
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="height">Min-Height:</label>
          <input type="text" id="height" name="height" value="'.stripslashes($REX['ADDON']['markitup']['default']['height']).'">
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-select">
          <label for="resizemode">Textarea Resize:</label>
          <select id="resizemode" name="resizemode" class="rex-form-select">
            '.$resizemode_option.'
          </select>
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-select">
          <label for="preview">Preview Type:</label>
          <select id="preview" name="preview" class="rex-form-select">
            '.$preview_option.'
          </select>
        </p>
      </div>

      </div><!--rex-form-wrapper-->
    </fieldset>

    <fieldset class="rex-form-col-1">
      <legend>Shortcuts</legend>
      <div class="rex-form-wrapper">

        <div class="rex-form-row">
          <p class="rex-form-col-a rex-form-select">
            <label for="shortcuts">Button : <i style="color:silver;">Ctrl.- </i>Key</label>
            <textarea id="shortcuts" name="shortcuts" rows="'.$shortcuts_size.'">'.$shortcuts.'</textarea>
          </p>
        </div>

      </div><!--rex-form-wrapper-->
    </fieldset>

    <fieldset class="rex-form-col-1">
      <legend>Autoenable per textarea classname</legend>
      <div class="rex-form-wrapper">

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-select">
          <label for="autoenable_status">Active:</label>
            '.$autoenable_status_sel.'
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="autoenable_class">Classname:</label>
          <input type="text" id="autoenable_class" name="autoenable_class" value="'.stripslashes($REX['ADDON']['markitup']['autoenable_class']).'">
        </p>
      </div>


      <div class="rex-form-row rex-form-element-v2">
        <p class="rex-form-submit">
          <input class="rex-form-submit" type="submit" id="sendit" name="sendit" value="Einstellungen speichern" />
        </p>
      </div>

      </div><!--rex-form-wrapper-->
    </fieldset>

  </form>
  </div>
</div>
';
