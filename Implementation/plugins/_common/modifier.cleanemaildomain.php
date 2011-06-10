<?php
function smarty_modifier_cleanemaildomain($string)
{
	return preg_replace("/@(.*?)$/mi", "", $string);
}