<?php
function smarty_block_TitlePane($params, $content, &$smarty)
{
	Warecorp::addTranslation("/plugins/block.TitlePane.php.xml");
    if ( !isset($params['id']) ) return Warecorp::t('ID for TitlePane not set.');      

    
    
    /**
     * Start tag
     */
    if ( $content === null ) {
        $objTitlePane = new stdClass();
        $objTitlePane->id                   = $params['id'];
        $objTitlePane->title                = ( isset($params['title']) ) ? $params['title'] : '';
        $objTitlePane->note                 = ( isset($params['note']) ) ? $params['note'] : '';
        $objTitlePane->toggleShow           = ( isset($params['toggle_show']) ) ? $params['toggle_show'] : Warecorp::t('Show');
        $objTitlePane->toggleShowCallback   = '';
        $objTitlePane->toggleHide           = ( isset($params['toggle_hide']) ) ? $params['toggle_hide'] : Warecorp::t('Hide');
        $objTitlePane->toggleHideCallback   = '';
        $objTitlePane->content              = '';
        $objTitlePane->isContentVisible     = ( isset($params['showContent']) && $params['showContent'] ) ? true : false ;
        
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) {
            $lstWidgetsTitlePanes = array();
        }
        array_push($lstWidgetsTitlePanes, $objTitlePane);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
    } 
    /**
     * End tag
     */
    else {
        
        if ( !$lstWidgetsTitlePanes = $smarty->get_template_vars('__Widgets_TitlePanes__') ) return Warecorp::t('Error');
        if ( 0 == sizeof($lstWidgetsTitlePanes) ) return Warecorp::t('Error');

        $objTitlePane = array_pop($lstWidgetsTitlePanes);
        $smarty->assign('__Widgets_TitlePanes__', $lstWidgetsTitlePanes);
        
        $contentStyle = ( !$objTitlePane->isContentVisible ) ? 'style="display:none"' : '';
        $toggleShowStyle = ( $objTitlePane->isContentVisible ) ? 'style="display:none"' : '';
        $toggleHideStyle = ( !$objTitlePane->isContentVisible ) ? 'style="display:none"' : '';
        $toggleShowCallback = '';
        $toggleHideCallback = '';
        if ( $objTitlePane->toggleShowCallback ) {
            $toggleShowCallback = $objTitlePane->toggleShowCallback->method;
        }
        if ( $objTitlePane->toggleHideCallback ) {
            $toggleHideCallback = $objTitlePane->toggleHideCallback->method;
        }
        
        $output = <<<EOD
        <script type="text/javascript">   
            //<!--         
            var TitltPaneApp{$objTitlePane->id} = null;
            $(function(){
            if ( !TitltPaneApp{$objTitlePane->id} ) {
                TitltPaneApp{$objTitlePane->id} = function () {
                    return {
                        status : 'visible',
                        init : function() {
                            $("#{$objTitlePane->id} .toggel-link").each(function(){
                                $(this).unbind().bind('click', function(){ TitltPaneApp{$objTitlePane->id}.toggle(); })
                            })
                        },
                        show : function() {
                            document.getElementById('{$objTitlePane->id}_ToggleShow').style.display = 'none';
                            document.getElementById('{$objTitlePane->id}_ToggleHide').style.display = '';
                            TitltPaneApp{$objTitlePane->id}.status = 'visible';
                            document.getElementById('{$objTitlePane->id}_Content').style.display = '';
                            {$toggleShowCallback};
                        },
                        hide : function() {
                            document.getElementById('{$objTitlePane->id}_ToggleShow').style.display = '';
                            document.getElementById('{$objTitlePane->id}_ToggleHide').style.display = 'none';
                            TitltPaneApp{$objTitlePane->id}.status = 'hidden';
                            document.getElementById('{$objTitlePane->id}_Content').style.display = 'none';
                            {$toggleHideCallback};
                        },
                        toggle : function() {
                            if ( document.getElementById('{$objTitlePane->id}_Content').style.display == 'none' ) TitltPaneApp{$objTitlePane->id}.show();
                            else TitltPaneApp{$objTitlePane->id}.hide();
                        }
                    }
                }();
                //$(function(){TitltPaneApp{$objTitlePane->id}.init();})
                TitltPaneApp{$objTitlePane->id}.init();
            };
            });
            //-->
        </script>
        <div  id="{$objTitlePane->id}">
            <div class="prDropBox">
                <div class="prDropBoxInner">
                    <div class="prDropHeader">
                        <h2>{$objTitlePane->title}</h2>
                        <div class="prHeaderTools">
                            <a href="#null" id="{$objTitlePane->id}_ToggleShow" class="prArrow" onclick="TitltPaneApp{$objTitlePane->id}.toggle(); return false;"{$toggleShowStyle}>{$objTitlePane->toggleShow}</a>
                            <a href="#null" id="{$objTitlePane->id}_ToggleHide" class="prArrow-down" onclick="TitltPaneApp{$objTitlePane->id}.toggle(); return false;"{$toggleHideStyle}>{$objTitlePane->toggleHide}</a>
                        </div>
                        <div class="prHeaderHelper">{$objTitlePane->note}</div>
                    </div>
                    <div class="prDropMain" id="{$objTitlePane->id}_Content"{$contentStyle}>
                        {$objTitlePane->content}
                    </div>
                </div>
            </div>
        </div>
EOD;
    
        return $output;
    }
    
}
?>