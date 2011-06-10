<?php
function smarty_block_tabitem($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.tabitem.php.xml');
    if ( !($tab_params = $smarty->get_template_vars('wc_tab_params')) ) return Warecorp::t("Tab object not found.");

    if ( $content !== null ) {  // тег tabitem закрывается

        $params['link']     = ( !isset($params['link']) )   ? '#' : $params['link'];
        $params['onclick']  = ( isset($params['onclick']) ) ? sprintf(" onclick=\"%s\"", $params['onclick']) : "";

        $params['first']    = ( isset($params['first']) )   ? true : false;
        $params['last']     = ( isset($params['last']) )    ? true : false;
        $params['labelid']  = ( isset($params['labelid']) ) ? ' id="'.$params['labelid'].'"' : '';
        $params['active']   = ( isset($params['name']) && isset($tab_params['active']) && $params['name'] == $tab_params['active'] ) ? true : false;
        $params['title']    = $content;

        if ( 'tabs1' == $tab_params['template'] ) {
            $_end_active_                   = '</a></li>';
            $_end_simple_before_active_     = '</a></li>';
            $_end_simple_                   = '</a></li>';
            $_end_simple_last_              = '</a></li>';

            $_start_simple_before_active    = '<li><a href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
            $_start_active_                 = '<li class="active"><a href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
            $_start_simple_                 = '<li><a href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
        } elseif ( 'tabs1city' == $tab_params['template'] ) {
            $_end_active_                   = '';
            $_end_simple_before_active_     = '';
            $_end_simple_                   = '';
            $_end_simple_last_              = '';

            $_start_simple_before_active    = '';
            $_start_active_                 = '';
            $_start_simple_                 = '';
        } elseif ( 'tabs2' == $tab_params['template'] ) {
            $_end_active_                   = '</a></li>';
            $_end_simple_before_active_     = '</a></li>';
            $_end_simple_                   = '</a></li>';
            $_end_simple_last_              = '</a></li>';

            $_start_simple_before_active    = '';
            $_start_active_                 = '<li class="active"><a href="'.$params['link'].'">';
            $_start_simple_                 = '<li><a href="'.$params['link'].'">';
        } elseif ( 'tabs3' == $tab_params['template'] ) {
             $_end_active_                   = '</a></li>';
            $_end_simple_before_active_     = '</a></li>';
            $_end_simple_                   = '</a></li>';
            $_end_simple_last_              = '</a></li>';

            $_start_simple_before_active    = '';
            $_start_active_                 = '<li class="prMessages-menu-current"><a class="prTColor5" href="'.$params['link'].'">';
            $_start_simple_                 = '<li><a href="'.$params['link'].'">';
        } elseif ( 'admin_subtabs' == $tab_params['template'] ) {
            $_end_active_                   = '</a></li>';
            $_end_simple_before_active_     = '</a></li>';
            $_end_simple_                   = '</a></li>';
            $_end_simple_last_              = '</a></li>';

            $_start_simple_before_active    = '';
            $_start_active_                 = '<li class="active"><a href="'.$params['link'].'">';
            $_start_simple_                 = '<li><a href="'.$params['link'].'">';
        } else {
            return "Tab template not found '".$tab_params['template']."'.";
        }

		if (!$smarty->get_template_vars('wc_tab_previous')){
			$params['first'] = 'first';
		}

		if ($params['first'] == 'first'){
				$_close_previous = '';
		}
		else {
			$_close_previous = $_end_simple_;
		}


        if ( $params['active'] ) {
			if ($params['last'] == 'last'){
				if ( 'tabs3' == $tab_params['template']) {
					$_start_active_ = '<li class="prMessages-menu-last prMessages-menu-current"><a class="prTColor5" href="'.$params['link'].'">';
				}
			}
			if ($params['first'] == 'first'){
				if ( 'tabs3' == $tab_params['template']) {
					$_start_active_ = '<li class="prMessages-menu-first prMessages-menu-current"><a class="prTColor5" href="'.$params['link'].'">';
				}
			}
            $content = $_close_previous.$_start_active_.$content;
            $smarty->assign('wc_tab_previous', 'active');
        } else {
            if ( ($smarty->get_template_vars('wc_tab_previous')) && ($smarty->get_template_vars('wc_tab_previous') != 'active') ) {
                $_close_previous = $_end_simple_;
            }
			elseif ( ($smarty->get_template_vars('wc_tab_previous')) && ($smarty->get_template_vars('wc_tab_previous') == 'active') ) {
				if ( 'tabs1' == $tab_params['template'] || 'tabs2' == $tab_params['template'] || 'admin_subtabs' == $tab_params['template']) {
					$_start_simple_ = '<li><a class="prNoBorder" href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
				}
			}
			if ($params['last'] == 'last'){
				if ( 'tabs1' == $tab_params['template'] ) {
					if ( ($smarty->get_template_vars('wc_tab_previous')) && ($smarty->get_template_vars('wc_tab_previous') == 'active') ) {
						$_start_simple_ = '<li class="prTabs-last"><a class="prNoBorder" href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
					}
					else {
						$_start_simple_ = '<li class="prTabs-last"><a href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
					}
				}
				elseif ( 'tabs3' == $tab_params['template']) {
					$_start_simple_ = '<li class="prMessages-menu-last"><a href="'.$params['link'].'">';
				}
			}
			if ($params['first'] == 'first'){
				if ( 'tabs1' == $tab_params['template'] || 'tabs2' == $tab_params['template'] || 'admin_subtabs' == $tab_params['template'] ) {
					$_start_simple_ = '<li><a class="prNoBorder" href="'.$params['link'].'"'.( $params['onclick'] ? ' '.$params['onclick'] : '' ).'>';
				}
				elseif ( 'tabs3' == $tab_params['template']) {
					$_start_simple_ = '<li class="prMessages-menu-first"><a href="'.$params['link'].'">';
				}
			}
            $content = $_close_previous.$_start_simple_.$content;
            $smarty->assign('wc_tab_previous', 'simple');
        }
        return $content;
    }
}