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

header('Content-type:text/javascript');
$setname = md5(rex_request('a287_markitup_set'));

$article_id 		= rex_request('article_id', 'int');
$clang = rex_request('clang', 'int', '1');
$slice_id = rex_request('slice_id', 'int');
$rex_version = rex_request('rex_version', 'int', '');

$parser_path = 'http://'.$REX['SERVER'].'/index.php?article_id='.$article_id.'&clang='.$article_clang;

if ($rex_version != '')
	$parser_path .= '&rex_version='.$rex_version;

echo 'set_'.$setname.' = {';
//	previewInWindow: "width=1000, height=800, resizable=yes, scrollbars=yes", 
//	previewParserPath: "~/templates/preview.html",
//	previewIFrame:	true,
//	previewParserPath: "index.php",
echo '
	nameSpace:"set-'.$setname.'",
	previewInWindow: "width=1000, height=800, resizable=yes, scrollbars=yes", 
	previewParserPath: "'.$parser_path.'",
	previewParserVar: "markitup_textile_preview_'.$slice_id.'",
	previewAutoRefresh: true,
	markupSet:  [';

ob_start();
$buttons = explode(',',rex_request('a287_markitup_set'));
foreach ($buttons as $button) {
	$button = str_replace('/','',$button);
	$button = str_replace('\\','',$button);
	$button = str_replace('.','',$button);
	
	$fn = $REX['INCLUDE_PATH'].'/addons/markitup/data/sets/default/'.$button.'.button';
	if (file_exists($fn)) {
		$params = array();
		$data = file_get_contents($fn);
		$data = explode("###\n",$data);
		foreach ($data as $line) {
			
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
		if (count($params)>0) {
			echo '{';
			$pcount = 0;
			foreach ($params as $k=>$v) {
				$pcount++;
				echo $k.':'.$v;
				if ($pcount<count($params)) {
					echo ', '	;	
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
{ jQuery.markItUp({replaceWith:"!"+ src +"!"}); }

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