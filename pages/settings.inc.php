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


// FORM
////////////////////////////////////////////////////////////////////////////////
echo '

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
          <textarea id="buttons" name="buttons" class="rex-form-textarea" rows="6" cols="50">'.stripslashes($REX['ADDON']['markitup']['default']['buttons']).'</textarea>
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