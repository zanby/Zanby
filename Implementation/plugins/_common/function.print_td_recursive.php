<?php
    function smarty_function_print_td_recursive($params, &$smarty)
    {
        if (!($params["from"] % $params["step"])) return;
        else {
            ++$params["from"];
            return "<td>{$params["fill"]}</td>" . smarty_function_print_td_recursive($params, &$smarty);
        }
    }
?>