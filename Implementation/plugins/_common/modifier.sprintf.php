<?php
function smarty_modifier_sprintf($string)
{
    $args = func_get_args();
	print call_user_func_array('sprintf', $args);
}
?>