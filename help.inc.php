<?php

$info = file_get_contents($REX['INCLUDE_PATH'].'/addons/markitup/beispielmodul.txt');

echo '<pre style="font-size:13px;overflow:auto;">';
print_r(htmlentities($info));
echo '</pre>';


?>