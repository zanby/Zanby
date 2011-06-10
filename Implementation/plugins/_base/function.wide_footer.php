<?php
    function smarty_function_wide_footer($params, &$smarty)
    {
        $_content = $smarty->fetch("_design/menu/wide_footer.tpl");
        return $_content;
    }
?>