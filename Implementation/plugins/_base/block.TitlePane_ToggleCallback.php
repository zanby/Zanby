<?php
function smarty_block_TitlePane_ToggleCallback($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.TitlePane_ToggleCallback.php.xml');
    $type = ( isset($params['type']) ) ? strtolower($params['type']) : 'both';
    $type = ( in_array( $type, array('show', 'hide', 'both') ) ) ? $type : 'both';
    
    $request_type = ( isset($params['request_type']) ) ? strtolower($params['request_type']) : 'js';
    $request_type = ( in_array( $type, array('js', 'ajax') ) ) ? $type : 'js';
    /**
     * End tag
     */
    if ( $content !== null ) {
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) return Warecorp::t('Error');
        if ( 0 == sizeof($lstWidgetsTitlePanes) ) return Warecorp::t('Error');
        
        $objTitlePane = array_pop($lstWidgetsTitlePanes);
        if ( $type == 'both' ) {
            $objTitlePane->toggleShowCallback = new stdClass();
            $objTitlePane->toggleShowCallback->method   = $content;
            $objTitlePane->toggleShowCallback->type     = $request_type;
            $objTitlePane->toggleHideCallback = new stdClass();
            $objTitlePane->toggleHideCallback->method   = $content;
            $objTitlePane->toggleHideCallback->type     = $request_type;
        } elseif ( $type == 'show' ) {
            $objTitlePane->toggleShowCallback = new stdClass();
            $objTitlePane->toggleShowCallback->method   = $content;
            $objTitlePane->toggleShowCallback->type     = $request_type;            
        } elseif ( $type == 'hide' ) {
            $objTitlePane->toggleHideCallback = new stdClass();
            $objTitlePane->toggleHideCallback->method   = $content;
            $objTitlePane->toggleHideCallback->type     = $request_type;
        }
                
        array_push($lstWidgetsTitlePanes, $objTitlePane);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
        return '';
    }  
}