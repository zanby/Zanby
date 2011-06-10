<?php
function smarty_block_tabmynav($params, $content, &$smarty)
{
    if ( $content !== null ) 
    {
        $_SESSION['tab'] = 's';
//        $content = '<table class="tab" border="0" cellspacing="0" cellpadding="0"><tr>'.$content.'<td class="tab_top_r"></td></tr></table>';
        $content = '<div class="top_line">'.$content.'</div>';
        return $content;
    }
}