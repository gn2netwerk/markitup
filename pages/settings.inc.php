<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 * 
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo4.2
 * @version svn:$Id$
 */

// GET PARAMS
////////////////////////////////////////////////////////////////////////////////
$myself          = rex_request('page', 'string');
$subpage         = rex_request('subpage', 'string');
$func            = rex_request('func', 'string');
$buttons         = rex_request('buttons', 'string');
$width           = rex_request('width', 'string');
$height          = rex_request('height', 'string');

// UPDATE/SAVE SETTINGS
////////////////////////////////////////////////////////////////////////////////
if ($func == "update")
{
  $REX['ADDON']['markitup']['default']['buttons'] = $buttons;
  $REX['ADDON']['markitup']['default']['width'] = $width;
  $REX['ADDON']['markitup']['default']['height'] = $height;

  $content = '
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'buttons\'] = \''.$buttons.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'width\'] = \''.$width.'\';
$REX[\'ADDON\'][\'markitup\'][\'default\'][\'height\'] = \''.$height.'\';
';

  $file = $REX['INCLUDE_PATH'].'/addons/markitup/config.inc.php';
  rex_replace_dynamic_contents($file, $content);

  echo rex_info('Konfiguration wurde aktualisiert');
}

// BUTTON ITEMS SELECT OPTION
////////////////////////////////////////////////////////////////////////////////
$markitup_buttons = array(

'Button hinzufügen' =>      '',

'Überschriften' =>      'optgroup',
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
'underline' =>          'underline',
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

'Code-Blöcke' =>        'optgroup',
'blockquote' =>         'blockquote',
'code' =>               'code',

'Spezial' =>            'optgroup',
'separator' =>          'separator',
'clean' =>              'clean',
'preview' =>            'preview',
);

$button_options = '';
$optgroup = '';

foreach($markitup_buttons as $k => $v)
{
  if($v=='optgroup')
  {
    if($optgroup = 'open')
    {
      $button_options .='</optgroup>';
    }
    $button_options .='<optgroup label="'.$k.'" style="background:#DFE9E9;color:silver;font-style:normal;text-align:center;padding:0;">';
    $optgroup = 'open';
  }
  else
  {
    if($v != '')
    {
      $button_options .='<option value="'.$v.'" style="background:#EFF9F9;color:black;border-bottom:1px solid white;padding:2px 0 2px 6px;text-align:left;"'.$selected.'>'.$k.'</option>';
    }
    else
    {
      $button_options .='<option value="'.$v.'" style="background:transparent;color:silver;padding:0;margin:0;"'.$selected.'>'.$k.'</option>';
    }
  }
}

if($optgroup = 'open')
{
  $button_options .='</optgroup>';
}



// FORM
////////////////////////////////////////////////////////////////////////////////
echo '

<script>
<!--
function AddButton(myButton)
{
  myButton = ","+myButton;
  document.getElementById(\'buttons\').value += myButton;
  document.getElementById(\'buttons\').style.color = "red";
  return true;
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
        <p class="rex-form-col-a rex-form-textarea">
          <label for="buttons">Button-Set [<a href="index.php?page=markitup&subpage=help">?</a>]</label>
          <textarea id="buttons" name="buttons" class="rex-form-textarea" rows="6" cols="50">'.stripslashes($REX['ADDON']['markitup']['default']['buttons']).'</textarea><br />
          <label for="addbutton"></label>
          <select id="addbutton" name="addbutton" style="background:white;color:silver;width:200px;text-align:center;height:20px;border:1px solid #999999;border-top:0;margin-top:-2px;" onChange="AddButton(this.options[this.selectedIndex].value);">'.$button_options.'</select>
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="width">Width</label>
          <input type="text" id="width" name="width" value="'.stripslashes($REX['ADDON']['markitup']['default']['width']).'">
        </p>
      </div>

      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-text">
          <label for="height">Height</label>
          <input type="text" id="height" name="height" value="'.stripslashes($REX['ADDON']['markitup']['default']['height']).'">
        </p>
      </div>


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