<?php
function smarty_block_urlencode($params, $content, &$smarty)
{
    if ( $content !== null ) return urlencode($content);
}