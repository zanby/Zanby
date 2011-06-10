<?php
    function smarty_function_displayemailicon($params, &$smarty)
    {   //нужен на вход объект нето затиратся будет комменты удаленных юзеров
        $temp['dl']['user']     = ( !isset($params['user']) ) ? null : $params['user'];
        $temp['dl']['currentUser']  = ( !isset($params['currentUser'] ) ) ? null : $params['currentUser'];
        
        if (Warecorp_User_AccessManager::getInstance()->canContact($temp['dl']['currentUser'], $temp['dl']['user'])) {
            $_content = '<a href="#null" onClick="xajax_sendMessage('.$temp['dl']['currentUser']->getId().'); return false;" class="znEmail" title="send message to member">&nbsp;</a>';
            return $_content;
        }
        return '';
    }  
?>