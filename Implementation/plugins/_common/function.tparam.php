<?php
function smarty_function_tparam($params, &$smarty)
{
    if ( isset($_SERVER['tBlocksStack']) && sizeof($_SERVER['tBlocksStack']) != 0 ) {        
        $stack = array_pop($_SERVER['tBlocksStack']);
        $stack[] = ( isset($params['value']) ) ? $params['value'] : '';
        array_push ($_SERVER['tBlocksStack'], $stack);
    }
}
?>