<?php
function smarty_block_TitlePane_Toggle($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.TitlePane_Toggle.php.xml');
    $type = ( isset($params['type']) ) ? strtolower($params['type']) : 'both';
    $type = ( in_array( $type, array('show', 'hide', 'both') ) ) ? $type : 'both';
    /**
     * End tag
     */
    if ( $content !== null ) {
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) return Warecorp::t('Error');
        if ( 0 == sizeof($lstWidgetsTitlePanes) ) return Warecorp::t('Error');
        
        $objTitlePane = array_pop($lstWidgetsTitlePanes);
        if ( $type == 'both' ) {
            $objTitlePane->toggleShow = $content;
            $objTitlePane->toggleHide = $content;
        } elseif ( $type == 'show' ) $objTitlePane->toggleShow = $content;
        elseif ( $type == 'hide' ) $objTitlePane->toggleHide = $content;
                
        array_push($lstWidgetsTitlePanes, $objTitlePane);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
        return '';
    }  
}