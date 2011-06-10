<?php
function smarty_function_template_list_globalsearch($params, &$smarty)
{      
    Warecorp::addTranslation('/plugins/function.template_list_globalsearch.php.xml');
    $theme = Zend_Registry::get('AppTheme');
    if ($params['object']->getListType() == 1){
        $src = "{$theme->images}/decorators/icons/listMusic.gif";
    }
    elseif ($params['object']->getListType() == 3){
        $src = "{$theme->images}/decorators/icons/listStuff.gif";
    }
    elseif ($params['object']->getListType() == 32){
        $src = "{$theme->images}/decorators/icons/listWhoWill.gif";
    }
    elseif ($params['object']->getListType() == 31){
        $src = "{$theme->images}/decorators/icons/listLinks.gif";
    }
    else {
        $src = "{$theme->images}/decorators/icons/listBooks.gif";
    }
    $output  = "<td class='prText5 prTCenter'><a class='prSearchIcon' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/list.gif' /></a><br />".Warecorp::t('List')."</td>";
    $output .= "<td><a class='prLink2' href='".$params['object']->getOwner()->getOwnerPath('listsview')."listid/".$params['object']->getId()."/'>".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</a>";
    $output .= "<div>".substr(htmlspecialchars(strip_tags($params['object']->entityDescription())), 0, 100);
    if (strlen($params['object']->entityDescription()) > 100){
    $output .= "... <a href='".$params['object']->getListPath()."'>".Warecorp::t('MORE')."</a>";
    }
    $output .= "</div></td>";
    //$output .= "<td>".substr($params['object']->entityAuthor(), 0, 15)."</td>";
    $output .= "<td>".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
    $output .= "<td>".$params['object']->entityItemsCount()." ".Warecorp::t('items')."</td>";
    $output .= "<td>";
    if ( empty($params['user']) || $params['user']->getId() != null )
        $output .= "<a class='prLink3' href='javascript:void(0)' onclick=\"SearchApplication.listAddToMy(".$params['object']->getId().", 0); return false;\">";
    else
        $output .= "<a class='prLink3' href='".BASE_URL."/".LOCALE."/users/login/'>";
    $output .= Warecorp::t('Add to My Lists' )."</a></td>";
    return $output;
}

