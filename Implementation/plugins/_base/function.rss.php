<?php

function smarty_function_rss($params, &$smarty) {

    return "<a href=" . $params[url] . ">$params[text]</a>";
    
}