<?php
function a287_markitup_extpoint($params) {
	global $REX;
	$output = $params['subject'];


	$scripts='';
	if ($REX['REDAXO']) {
	
		$src_params = '';
		$src_params .= '&article_id='.$params['article_id'];
		$src_params .= '&clang='.$params['clang'];
		$src_params .= '&slice_id='.$params['slice_id'];
		
		if (isset($params['rex_version']))
			$src_params .= '&rex_version='.$params['rex_version'];
	
		$scripts.='
  <script type="text/javascript">var set_'.md5($params['buttons']).';</script>

  <script type="text/javascript" src="index.php?a287_markitup_set='.$params['buttons'].$src_params.'"></script>
  <link rel="stylesheet" type="text/css" href="index.php?a287_markitup_css='.$params['buttons'].'&width='.$params['width'].'&height='.$params['height'].'" />
  
  <script type="text/javascript">
  jQuery(document).ready(function() {	
	jQuery("'.$params['selector'].'").markItUp(set_'.md5($params['buttons']).');
  });
  </script>
  
';
	}

	
	$output = str_replace('</head>',$scripts.'</head>',$output);
	return $output;
}


class a287_markitup {
	function a287_markitup() {
	}	
	
	
	function markitup($cssClass,$buttons='',$width=550,$height=400) {
		global $REX;
		
		$slice_id = rex_request('slice_id', 'int');
		
		$params['selector'] 	= $cssClass;
		$params['buttons'] 		= $buttons;
		$params['width'] 			= $width;
		$params['height'] 		= $height;
		$params['article_id'] = $REX['ARTICLE_ID'];
		$params['clang'] 			= $REX['CUR_CLANG'];
		$params['slice_id'] 	= $slice_id;
		
		if(OOAddon::isAvailable('version'))
		{
			$version_arr = $REX['LOGIN']->getSessionVar('rex_version_article');
			$params['rex_version'] = $version_arr[1];
		}
		rex_register_extension('OUTPUT_FILTER', 'a287_markitup_extpoint',$params);
	}
}
?>
