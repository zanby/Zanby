<?php
    function smarty_function_topgroupmenu($params, &$smarty)
    {
        $smarty->assign($params);
        $_content = $smarty->fetch("_design/menu/topgroup_menu.tpl");
        return $_content;
    }
?>