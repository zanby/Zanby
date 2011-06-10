<?php
function smarty_block_tab($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.tab.php.xml');
    if (!isset($params['template'])) return Warecorp::t('Template for tabs not set.');    
    
    if (!isset($params['active']))      $params['active']       = "";
    if (!isset($params['type']))        $params['type']         = 'znbTabs3';
    if (!isset($params['exception']))   $params['exception']    = '';
    if (!isset($params['style']))       $params['style']        = '';
    
    $idTabs = "znbTab_".md5(time());
    $smarty->assign('idTabs',           $idTabs);
    $smarty->assign('exception',        $params['exception']);
    $smarty->assign('style',            $params['style']);
    $smarty->assign('wc_tab_params',    $params);
	
    if ( 'tabs1' == $params['template'] ) {
        $_end_active_                   = '</a></li>';
        $_end_simple_before_active_     = '</a></li>';
        $_end_simple_                   = '</a></li>';
        $_end_simple_last_              = '</a></li>';
        $_end_tabs_                     = '</ul>';
        $_start_tabs_                   = '<ul class="prSubNav">';
    } elseif ( 'tabs1city' == $params['template'] ) {
        $_end_active_                   = '';
        $_end_simple_before_active_     = '';
        $_end_simple_                   = '';
        $_end_simple_last_              = '';
        $_end_tabs_                     = '';        
        $_start_tabs_                   = '';
    } elseif ( 'tabs2' == $params['template'] ) {
        $_end_active_                   = '</a></li>';
        $_end_simple_before_active_     = '</a></li>';
        $_end_simple_                   = '</a></li>';
        $_end_simple_last_              = '</a></li>';
        $_end_tabs_                     = '</ul>';
        $_start_tabs_                   = '<ul class="prThirdNav">';
    } elseif ( 'tabs3' == $params['template'] ) {
        $_end_active_                   = '</a></li>';
        $_end_simple_before_active_     = '</a></li>';
        $_end_simple_                   = '</a></li>';
        $_end_simple_last_              = '</a></li>';
        $_end_tabs_                     = '</ul>';
        $_start_tabs_                   = '<ul class="prMessages-menu" id="prMessages-menu">';
    }  elseif ( 'admin_subtabs' == $params['template'] ) {
        $_end_active_                   = '</a></li>';
        $_end_simple_before_active_     = '</a></li>';
        $_end_simple_                   = '</a></li>';
        $_end_simple_last_              = '</a></li>';
        $_end_tabs_                     = '</ul>';
        $_start_tabs_                   = '<ul class="prThirdNav">';
    }
    
    
    if ( $content !== null ) {  //  тег tab закрывается
        $_close_previous = "";
        
        if ($smarty->get_template_vars('wc_tab_previous')) {
            if ($smarty->get_template_vars('wc_tab_previous') == 'active') $_close_previous = $_end_active_;
            else $_close_previous = $_end_simple_last_;
        }
        $smarty->clear_assign('wc_tab_previous');
        $smarty->clear_assign('wc_tab_params');
        return $_start_tabs_.$content.$_close_previous.$_end_tabs_;
    }
}