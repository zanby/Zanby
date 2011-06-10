<?php
    function smarty_function_submitbutton($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.submitbutton.php.xml'); 
//        $params['name']     = ( !isset($params['name']) ) ? "ButtonSubmit" : $params['name'];
//        $params['value']    = ( !isset($params['value']) ) ? null : $params['value'];
//        $params['float']    = ( !isset($params['float']) ) ? 'right' : $params['float'];
//        $params['color']    = ( !isset($params['color'] ) ) ? 'orange' : $params['color'];
//        $params['color']    = ( !in_array($params['color'], array('gray', 'green', 'orange', 'red')) ) ? 'orange' : $params['color'];
//
//        $smarty->assign($params);
//        $_content = $smarty->fetch("_design/buttons/submit_button.tpl");

        $_content = "<b>OBSOLETE FUNCTION USED. USE form_submit!</b>";
        return $_content;
    }
?>
