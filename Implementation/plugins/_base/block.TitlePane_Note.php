<?php
function smarty_block_TitlePane_Note($params, $content, &$smarty)
{
	Warecorp::addTranslation('/plugins/block.TitlePane_Note.php.xml');
    /**
     * End tag
     */
    if ( $content !== null ) {
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) return Warecorp::t('Error');
        if ( 0 == sizeof($lstWidgetsTitlePanes) ) return Warecorp::t('Error');
        
        $objTitlePane = array_pop($lstWidgetsTitlePanes);
        $objTitlePane->note = $content;
                
        array_push($lstWidgetsTitlePanes, $objTitlePane);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
        return '';
    }  
}