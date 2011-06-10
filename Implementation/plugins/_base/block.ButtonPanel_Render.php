<?php
    function smarty_block_ButtonPanel_Render($params, $content, &$smarty)
    {
        $params['groupId'] = ( !$params['groupId'] ) ? null : $params['groupId'];
        /**
         * End tag
         */
        if ( $content !== null ) {
            if ( !$lstWidgetsButtonPanel = $smarty->get_template_vars('__Widgets_ButtonPanel__') ) return '';
            if ( 0 == sizeof($lstWidgetsButtonPanel) ) return '';

            $objButtonPanel = array_pop($lstWidgetsButtonPanel);
            $output = $objButtonPanel->render($params['groupId']);

            array_push($lstWidgetsButtonPanel, $objButtonPanel);
            $smarty->assign('__Widgets_ButtonPanel__', $lstWidgetsButtonPanel);

            return $output;
        }
    }
?>