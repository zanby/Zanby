<?php
function smarty_modifier_fixembed($string) {
	
	$pattern = "/<\/param><embed/im";
    $replacement = '</param><param name="wmode" value="opaque"><embed';
    $string = preg_replace($pattern, $replacement, $string);
    
    $pattern = "/<embed([^\>]*)/im";
    $replacement = '<embed$1 wmode="opaque"';
    $string = preg_replace($pattern, $replacement, $string);
    
    return $string;
}    