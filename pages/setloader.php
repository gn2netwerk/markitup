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

// SWITCH CHARSET
////////////////////////////////////////////////////////////////////////////////
if (strpos($REX['LANG'],'utf'))
{
  header('Content-type:text/javascript; charset=utf-8');
}
else
{
  header('Content-type:text/javascript; charset=iso-8859-1');
}

// PARAMS
////////////////////////////////////////////////////////////////////////////////
$setname = md5(rex_request('a287_markitup_set'));

$article_id = rex_request('article_id', 'int');
$clang      = rex_request('clang', 'int', '1');
$slice_id   = rex_request('slice_id', 'int');
$function   = rex_request('function', 'string');
$preview    = $REX['ADDON']['markitup']['default']['preview'];

// AUS SLICE ID AUF VERSION SCHLIESSEN
////////////////////////////////////////////////////////////////////////////////
if($slice_id > 0)
{
  $rev = new rex_sql;
  //$rev->debug = true;
  $rev->setQuery('select `revision` from rex_article_slice where `id` ='.$slice_id);
  $rex_version = $rev->getValue('revision');
}

// MAIN
////////////////////////////////////////////////////////////////////////////////
echo 'set_'.$setname.' = {';

if($REX['ADDON']['markitup']['default']['preview'] == 'inline')
{
  $parser_path = 'index.php?page=markitup&subpage=preview&article_id='.$article_id.'&clang='.$article_clang.'&slice_id='.$slice_id.'&rex_version='.$rex_version;

  echo '
    nameSpace:"set-'.$setname.'",
    previewParserPath: "'.$parser_path.'",
    previewParserVar: "markitup_textile_preview_'.$slice_id.'",
    previewAutoRefresh: true,
    markupSet:  [';
}
else
{
  if ($REX['MOD_REWRITE'] == true && (OOAddon::isAvailable('rexseo') || OOAddon::isAvailable('url_rewrite')))
  {
    require_once $REX['INCLUDE_PATH'].'/generated/files/pathlist.php';
    $parser_path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$REXPATH[$article_id][$clang].'?slice_id='.$slice_id.'&rex_version='.$rex_version;
  }
  else
  {
    $parser_path = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?article_id='.$article_id.'&clang='.$article_clang.'&slice_id='.$slice_id.'&rex_version='.$rex_version;
  }

  echo '
    nameSpace:"set-'.$setname.'",
    previewInWindow: "width=1000, height=800, resizable=yes, scrollbars=yes",
    previewParserPath: "'.$parser_path.'",
    previewParserVar: "markitup_textile_preview_'.$slice_id.'",
    previewAutoRefresh: false,
    markupSet:  [';
}

ob_start();
global $REX;
$buttons = explode(',',rex_request('a287_markitup_set'));

// @ MODUL ADD & WYSIWYG PREVIEW -> PREVIEW BUTTON DISABLED
if($function == 'add' && $preview == 'wysiwyg')
{
  $buttons = array_diff($buttons,array('preview'));
}

foreach ($buttons as $button)
{
  $button = str_replace('/','',$button);
  $button = str_replace('\\','',$button);
  $button = str_replace('.','',$button);

  $fn = $REX['INCLUDE_PATH'].'/addons/markitup/lib/sets/default/'.$button.'.button';
  if (file_exists($fn))
  {
    $params = array();
    $data = file_get_contents($fn);
    $data = explode("###\n",$data);
    foreach ($data as $line)
    {
      $line = explode(':::::',$line);
      if (count($line)==2)
      {
        $string = $line[1];
        preg_match_all('/(translate:markitup_\w+)/', $string, $matches);
        if (count($matches[1]) >= 1)
        {
          $srch = array();
          $rplc = array();
          foreach ($matches[1] as $match)
          {
            $srch[] = $match;
            $rplc[] = rex_translate($match);
          }

          $string = str_replace($srch, $rplc, $string);
        }
        /*
        $search = 'markitup_name_'.$button;

        if (strpos($string, $search) !== false)
        {
          $string = str_replace($search, $I18N->msg($search), $string);
        }
        */
        $params[$line[0]]= $string;

      }
    }
    if (count($params)>0)
    {
      // KEY DEFINITION FROM BACKEND SETTINGS
      $shortcuts = array();
      $tmp = explode("|",$REX['ADDON']['markitup']['default']['shortcuts']);
      foreach($tmp as $k=>$v)
      {
        $p = explode(':',$v);
        $shortcuts[$p[0]] = $p[1];
      }

      $className = trim(str_replace('\'','',$params['className']));
      $className = trim(str_replace('markitup-','',$className));

      if($shortcuts[$className] != '')
      {
        $params['key'] = "'".$shortcuts[$className]."'";
      }

      echo '{';
      $pcount = 0;
      foreach ($params as $k=>$v)
      {
        $pcount++;
        echo $k.':'.$v;
        if ($pcount<count($params))
        {
          echo ', '  ;
        }
      }
      echo '},';
    }

  }
}
$out=ob_get_contents();
ob_end_clean();
$out = trim($out);
$out = trim($out,',');
echo $out;

echo "\n".']}'."\n";

?>
function insertFileLink(file)
{ jQuery.markItUp({openWith:'"', closeWith:'":'+file}); }

function insertLink(url,desc)
{ jQuery.markItUp({openWith:'"', closeWith:'":'+url}); }

function insertImage(src, desc)
{
  // jQuery.markItUp({replaceWith:"!./"+ src +"!"});
  img = src.replace(/files\//, "");
  jQuery.markItUp({ replaceWith:"!index.php?rex_resize=[![Image Width]!]w__"+ img +"!"});
}

function markitup_getURLParam(strParamName){
  var strReturn = "";
  var strHref = window.location.href;
  if ( strHref.indexOf("?") > -1 ){
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
      if (
aQueryString[iParam].indexOf(strParamName.toLowerCase() + "=") > -1 ){
        var aParam = aQueryString[iParam].split("=");
        strReturn = aParam[1];
        break;
      }
    }
  }
  return unescape(strReturn);
}