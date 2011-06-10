<?php
function smarty_block_menutitle($params, $content, &$smarty)
{
    if ( $content !== null ) {
        $colors = array('blue'=>'#3C4C7C', 'red'=>'#A33C33', 'green'=>'#5C7C44', 'default'=>'#70A100');
        $params['link']        = ( !isset($params['link']) ) ? null : $params['link'];
        $params['color']        = ( !isset($params['color']) ) ? "default" : $params['color'];
        $params['color']        = ( !in_array($params['color'], array_keys($colors)) ) ? "default" : $params['color'];
        $params['color']        = $colors[$params['color']];

        $smarty->assign("link", $params['link']);
        $smarty->assign("color", $params['color']);
        $smarty->assign("text", $content);
        $_content = $smarty->fetch("_design/menu/menu_title.tpl");

        return $_content;
    }
}

?>