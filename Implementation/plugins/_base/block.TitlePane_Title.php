<?php
function smarty_block_TitlePane_Title($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.TitlePane_Title.php.xml');
	$type = ( isset($params['type']) ) ? strtolower($params['type']) : 'none';
    $type = ( in_array( $type, array('none', 'link') ) ) ? $type : 'none';
    /**
     * End tag
     */
    if ( $content !== null ) {
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) return Warecorp::t('Error');
        if ( 0 == sizeof($lstWidgetsTitlePanes) ) return Warecorp::t('Error');
        
        $objTitlePane = array_pop($lstWidgetsTitlePanes);
		if ( $type == 'link' ) {
            $content = "<a href='#null' onclick='TitltPaneApp{$objTitlePane->id}.toggle(); return false;'>".$content."</a>";
        } 
		$objTitlePane->title = $content;
                
        array_push($lstWidgetsTitlePanes, $objTitlePane);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
        return '';
    }    
}