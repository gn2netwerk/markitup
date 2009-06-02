<?php 
header('Content-type:text/javascript');
$setname = md5(rex_request('a287_markitup_set'));

echo 'set_'.$setname.' = {';
echo "
	nameSpace:'set-".$setname."',
	markupSet:  [";

ob_start();
$buttons = explode(',',rex_request('a287_markitup_set'));
foreach ($buttons as $button) {
	$button = str_replace('/','',$button);
	$button = str_replace('\\','',$button);
	$button = str_replace('.','',$button);
	
	$fn = $REX['INCLUDE_PATH'].'/addons/markitup/data/sets/default/'.$REX['LANG'].'.'.$button.'.button';
	if (file_exists($fn)) {;
		$params = array();
		$data = file_get_contents($fn);
		$data = explode("\n",$data);
		foreach ($data as $line) {
			$line = explode(':::::',$line);
			if (count($line)==2) {
				$params[$line[0]]=$line[1];
			}
		}
		if (count($params)>0) {
			echo '{';
			$pcount = 0;
			foreach ($params as $k=>$v) {
				$pcount++;
				echo $k.':'.$v;
				if ($pcount<count($params)) {
					echo ', ';	
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
{ jQuery.markItUp({openWith:' "', closeWith:'":'+file}); }

function insertLink(url,desc)
{ jQuery.markItUp({openWith:' "'+desc, closeWith:'":'+url}); }

function insertImage(src, desc)
{ jQuery.markItUp({replaceWith:" !"+ src +"!"}); }

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