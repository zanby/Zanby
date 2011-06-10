<?php
    function smarty_function_displaylogin($params, &$smarty)
    {   //нужен на вход объект нето затиратся будет комменты удаленных юзеров
        $temp['dl']['href']     = ( !isset($params['href']) ) ? "javascript:return void();" : $params['href'];
        $temp['dl']['onclick']  = ( !isset($params['onclick'] ) ) ? null : $params['onclick'];
        $temp['dl']['id']        = ( !isset($params['id'] ) ) ? null : $params['id'];                
        $temp['dl']['class']        = ( !isset($params['class'] ) ) ? null : $params['class'];
        $temp['dl']['style']        = ( !isset($params['style'] ) ) ? null : $params['style'];        
        $temp['dl']['user'] = (isset($params['user']))?$params['user']:null;
        $temp['dl']['name'] = (isset($params['name']))?$params['name']:null;
        
        if (!($temp['dl']['user'] instanceof Warecorp_User)) {
            print_r('user not defined');
            exit;
        }
        if ($temp['dl']['user']->getStatus() == Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED) {
            if (empty($temp['dl']['id'])) $temp['dl']['id'] = 'login'.rand(1, 100000);
            $smarty->assign('userExist', false);                               
        } else {
            $smarty->assign('userExist', true);
        }        
        $smarty->assign($temp);
        $_content = $smarty->fetch("_design/login/displaylogin.tpl");        
        return $_content;
    }  
?>
