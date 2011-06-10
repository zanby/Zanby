<?php
function smarty_function_addTranslation($params, &$smarty)
{
    if ( isset($params['file']) ) {
        Warecorp::addTranslation($params['file']);
    }
}