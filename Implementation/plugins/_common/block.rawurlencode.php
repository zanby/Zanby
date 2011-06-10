<?php
function smarty_block_rawurlencode($params, $content, &$smarty)
{
    if ( $content !== null ) return rawurlencode($content);
}