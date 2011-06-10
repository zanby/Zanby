<?php
    function smarty_function_linkbutton($params, &$smarty)
    {
/*
        $params['name']     = ( !isset($params['name']) ) ? "ButtonLink" : $params['name'];
        $params['link']     = ( !isset($params['link']) ) ? "javascript:return void();" : $params['link'];
        $params['color']    = ( !isset($params['color'] ) ) ? 'orange' : $params['color'];
        $params['color']    = ( !in_array($params['color'], array('gray', 'green', 'orange', 'red')) ) ? 'orange' : $params['color'];
        $params['onclick']  = ( !isset($params['onclick'] ) ) ? null : $params['onclick'];
        $params['id']    	= ( !isset($params['id'] ) ) ? null : $params['id'];
        $smarty->assign($params);
        $_content = $smarty->fetch("_design/buttons/link_button.tpl");
        return $_content;
*/
        $left  = "";
        $right = "";
        if (isset($params['html'])) {
            $left  = (!isset($params['htmlPosition']) || $params['htmlPosition'] == "left")  ? $params["html"] : '';
            $right = ($params['htmlPosition'] == "right")                                    ? $params["html"] : '';
        }

        $params['name']     = ( !isset($params['name']) ) ? "ButtonLink" : $params['name'];
        $params['link']     = ( !isset($params['link']) ) ? "javascript:void(0);" : $params['link'];
        $params['color']    = ( !isset($params['color'] ) ) ? 'orange' : $params['color'];
        $params['color']    = ( !in_array($params['color'], array('gray', 'green', 'orange', 'red')) ) ? 'orange' : $params['color'];
        $params['onclick']  = ( !isset($params['onclick'] ) ) ? null : $params['onclick'];
        $params['id']       = ( !isset($params['id'] ) ) ? null : $params['id'];
        $params['style']    = ( !isset($params['style']) ) ? null : $params['style'];
        $params['float']    = ( !isset($params['float']) ) ? null : $params['float'];    
		$out = $left . '
			<a class="prButton"
			'. ( $params['id'] ? ' id="'.$params['id'].'"' : '' ) .'
            '. ( $params['link'] ? ' href="'.$params['link'].'"' : ' hfer="#null"' ) .'
            '. ( $params['onclick'] ? ' onclick="'.$params['onclick'].'"' : '' ) .'
            ><span>'.$params['name'].'</span></a>
        ' . $right;
        return $out;
    }
?>