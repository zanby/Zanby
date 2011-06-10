<?php
    function smarty_function_template_group_members($params, &$smarty)
    {
        Warecorp::addTranslation('/plugins/function.template_group_members.php.xml');
        $objGroup   = $smarty->_tpl_vars['currentGroup'];
        $objUser    = $smarty->_tpl_vars['currentUser'];
        $order      = $smarty->_tpl_vars['order'];
        $direction  = $smarty->_tpl_vars['direction'];
        $page       = $smarty->_tpl_vars['page'];
        $friends    = $smarty->_tpl_vars['friends'];
        $orderPath  = ( !empty($order) ) ? 'order/'.$order.'/direction/'.$direction.'/' : '';

        if ( isset($smarty->_tpl_vars['isHostPrivileges']) ) {
            $isHost = (bool)$smarty->_tpl_vars['isHostPrivileges'];
        }
        else {
            $isHost = (
                ($objGroup->getMembers()->isCoowner($objUser)) ||
                ($objGroup->getMembers()->isHost($objUser))
            );
        }
        
        $show_host_label = isset($params['show_host_label']) && $params['show_host_label'] ? true : false;
        if ( $show_host_label ) {
            $mIsHost = $objGroup->getMembers()->isHost($params['object']);
            $mIsCoHost = $objGroup->getMembers()->isCohost($params['object']);
        }

        $theme = Zend_Registry::get('AppTheme');
        $output = "<td class='prText5'><img src='".$params['object']->getAvatar()->getSmall()."' /></a></td>";
        $output .= "<td><a class='prLink2' href='".$params['object']->getUserPath('profile')."'>".$params['object']->entityTitle()."</a>";
        
        if ( $show_host_label ) {
            if ( $mIsHost ) $output .= "<span class='prText7 prInnerSmallLeft'>(HOST)</span>";
            elseif ( $mIsCoHost ) $output .= "<span class='prText7 prInnerSmallLeft'>(CO-HOST)</span>";
        }
        
        $output .= "<div>";
        $output .= $params['object']->entityCountry();
        $output .= ", ";
        $output .= substr($params['object']->entityCity(), 0, 30);
        $output .= "</div>";
        $output .= "<span class='prText5'>".Warecorp::t('Member since:')." ".Warecorp::user_date_format($params['object']->entityCreationDate())."</span>";
        $output .= "</td>";
        $output .= "<td>&#160;";
        if ($params['object']->getLastOnline()=='Online'){
            $output .= "online";
        }
        else{
            $output .= $params['object']->getLastOnline();
        }
        $output .="</td>";
        $output .= "<td>&#160;</td>";
        $output .= '<td>';
        if ( $params['object']->getId() != $objUser->getId() ) {
            $output .= '<a class="prLink3" href="javascript::void(0)" onclick="xajax_sendMessage('.$params['object']->getId().'); return false;">'.Warecorp::t("Send Message").'</a><br />';
            if ($params['object']->getPrivacy()->getSrViewAddToFriend()) {
                if (in_array($params['object']->getId(), $friends))
                    $output .= '<span>'.Warecorp::t("Already Friend").'</span><br />';
                else
                    $output .= '<a class="prLink3" class="prLink3" href="javascript:void(0)" onclick="xajax_addToFriends('.$params['object']->getId().'); return false;">'.Warecorp::t("+ Add to Friends").'</a><br />';
            }
            if ( $isHost && !$objGroup->getMembers()->isHost($params['object']) ) {
                $output .= '<a class="prLink3" href="javascript:void(0)" onclick="xajax_removeMember(\''.$objGroup->getGroupPath('members').'mode/approved/'.$orderPath.'page/'.$page.'/remove/'.$params['object']->getId().'/\'); return false;">'.Warecorp::t("Remove").'</a>';
            }
        }
        else {
            $output .= '&nbsp;';
        }
        $output .= '</td>';
        return $output;
    }
