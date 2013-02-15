<?php

/**
 * MARKITUP Addon
 * Textile Markup Editor
 *
 * @author Markitup by Jay Salvat - http://markitup.jaysalvat.com
 * @author Redaxo Addon by http://www.gn2-netwerk.de/
 * @package redaxo 4.2/4.3/4.4
 * @version 1.1.62
 */

// REQUEST PARAMETERS
////////////////////////////////////////////////////////////////////////////////
$slice_id = rex_request('slice_id', 'int');



if(strpos($REX['LANG'],'utf') !== false)
{
  $charset = 'utf-8';
  $textile = $_REQUEST['markitup_textile_preview_'.$slice_id];
}
else
{
  $charset = 'iso-8859-1';
  $textile = utf8_decode($_REQUEST['markitup_textile_preview_'.$slice_id]);
}

$textile = str_replace('\"','"',$textile);
$textile = markitup_previewlinks($textile);
$html    = rex_a79_textile($textile);

// PREVIEW HTML
////////////////////////////////////////////////////////////////////////////////
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />
<title>markItUp! preview template</title>
<link rel="stylesheet" type="text/css" href="../files/addons/markitup/preview.css" />
</head>
<body>

'.$html.'

</body>
</html>


';
