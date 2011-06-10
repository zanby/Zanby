<?php
    function smarty_function_topprofilemenu($params, &$smarty)
    {
        $smarty->assign($params);
        $_content = $smarty->fetch("_design/menu/topprofile_menu.tpl");
        return $_content;
    }
?>