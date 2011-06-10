<?php
    function smarty_function_custommenu($params, &$smarty)
    {
        $params['tabs']         = ( !isset($params['tabs']) || !is_array($params['tabs'])) ? array() : $params['tabs'];
        $smarty->assign("tabs", $params['tabs']);
        $_content = $smarty->fetch("_design/menu/custom_menu.tpl");
        return $_content;
    }
?>