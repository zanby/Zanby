<?php
    function smarty_function_template_user_globalsearch($params, &$smarty)
    {
        Warecorp::addTranslation('/plugins/function.template_user_globalsearch.php.xml');
        $user = $smarty->_tpl_vars['user'];
        if ($params['object']->getPrivacy()->getSrViewProfilePhoto()){
            $src = $params['object']->entityPicture()->setWidth(55)->setHeight(55)->setBorder(1)->getImage();
        }else{
            $src = $params['object']->entityPicture()->setWidth(55)->setHeight(55)->setBorder(1)->getNoImage();
        }


        $theme = Zend_Registry::get('AppTheme');
        $output = "<td class='prText5 prTCenter'><a href='".$params['object']->getUserPath('profile')."' class='prSearchIcon' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/user.gif' /></a><br />".Warecorp::t('Member')."</td>";
        $output .= "<td><a class='prLink2' href='".$params['object']->getUserPath('profile')."'>".$params['object']->entityTitle()."</a>";
        $output .= "<div>".$params['object']->entityCountry();
        $output .= ", ".substr($params['object']->entityState(), 0, 30);
        $output .= ", ".substr($params['object']->entityCity(), 0, 30)."</div>";
        $output .= Warecorp::t('Joined:')." ".Warecorp::user_date_format($params['object']->entityCreationDate());
        $output .= "</td>";
        $output .= "<td>".Warecorp::user_date_format($params['object']->entityUpdateDate())."</td>";
        $output .= "<td>&#160;</td><td>&#160;</td>";
        $output .= "<td>";

        if ( $user->getId() != $params['object']->getId() ) {
            if ( $user && $user->getId() != null ) {
                $friends = $user->getFriendsList()->returnAsAssoc()->getList();
                if ($params['object']->getPrivacy()->getSrViewAddToFriend()) {
                    if (in_array($params['object']->getId(), $friends))
                        $output .= '<span>'.Warecorp::t("Already Friend").'</span><br />';
                    else
                        $output .= '<a class="prLink3" class="prLink3" href="javascript:void(0)" onclick="xajax_addToFriends('.$params['object']->getId().'); return false;">+ '.Warecorp::t("Add to Friends").'</a><br />';
                }
            }
            
            if($params['object']->getPrivacy()->getSrViewSendMessage()){
                $output .= "<a class='prLink3' href='";
                if ($smarty->_tpl_vars['user']->isAuthenticated()){
                    $output .= "#null' onclick=\"xajax_sendMessage(".$params['object']->getId()."); return false;\">";
                }
                else {
                    $output .= BASE_URL."/".LOCALE."/users/login/'>";
                }
                $output .= Warecorp::t("Send a message")."</a><br />";
            }
            
            if ($params['object']->getPrivacy()->getSrViewMyFriends()){
                $output .= "<a class='prLink3' href='".$params['object']->getUserPath('friends')."'>".Warecorp::t('View Friends')."</a><br />";
            }
        }
        else { $output .= "&nbsp;"; }
        $output .= "</td>";
        return $output;
    }
