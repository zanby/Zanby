<?php
    function smarty_function_mainnav_wide($params, &$smarty)
    {
        $_content = $smarty->fetch("_design/menu/main_nav_wide.tpl");
        return $_content;
    }
?>