<?php
function a287_markitup_extpoint($params) {
	global $REX;
	$output = $params['subject'];


	$scripts='';
	if ($REX['REDAXO']) {
		$scripts.='
  <script type="text/javascript">var set_'.md5($params['buttons']).';</script>

  <script type="text/javascript" src="index.php?a287_markitup_set='.$params['buttons'].'"></script>
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
		
		rex_register_extension('OUTPUT_FILTER', 'a287_markitup_extpoint',array('selector'=>$cssClass,'buttons'=>$buttons,'width'=>$width,'height'=>$height));
	}
}
?>
