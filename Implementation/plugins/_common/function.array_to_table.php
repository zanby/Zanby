<?php

function smarty_function_array_to_table($params, &$smarty)
{
	//exit();
	if (!isset($params['table_attr'])) $table_attr = "";
	else $table_attr = $params['table_attr'];
	$array = $params['array'];
	if (!isset($params['width'])) $width = "1";
	else $width = $params['width'];
	if (!isset($params['height'])) $height = "1";
	else $height = $params['height'];
	$contents = "<table " . $table_attr . ">";	
	for($i=0; $i<$height; $i++){
		$contents .= "<tr>";
		
		for($j=0; $j<$width; $j++){
			$contents .= "<td>";
			$contents .= "<a href=" . $array[$i*$width+$j][1] . "><div class=\"" . $params['class'] . "\" style=\"" . ($array[$i*$width+$j][0] ? $array[$i*$width+$j][0] : '') . "\">"  . "</div></a>";
			$contents .= "</td>";
		}
		$contents .= "</tr>";
	}
	$contents .= "</table>";
	echo $contents;
	
}

?>