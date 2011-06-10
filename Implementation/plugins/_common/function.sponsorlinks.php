<?php
    function smarty_function_sponsorlinks($params, &$smarty)
    {
        /*
    	$params['links']         = ( !isset($params['links']) || !is_array($params['links'])) ? array() : $params['links'];
    	$smarty->assign("links", $params['links']);
        $_content = $smarty->fetch("_design/menu/sponsor_links.tpl");
        return $_content;
        */
        
        $params['links']  = ( !isset($params['links']) || !is_array($params['links'])) ? array() : $params['links'];
        $out = '<h6>Sponsored Links</h6>';
        $ind = 1;
        if ( sizeof($links) != 0 ) {
            foreach ( $links as $item ) {
                $out .= '
                    <div class="'.( $ind == 1 ? 'sponsor-link' : 'sponsor-link1').'">
                        <b><a href="'.$item['url'].'">'.$item['title'].'</a></b>
                        '.$item['description'].'<br />
                        <a href="'.$item['url'].'">'.$item['url'].'</a>
                    </div>            
                ';
                $ind++;
            }
        } else {
            $out .= '
            <!-- ads begin -->
            <div>
                <a href="#null">Advertising</a>
                <p>All you need to know about advertising</p>
                <a href="#null" class="znbOuterLink">www.businessinfo.com</a>
            </div>
            <div>
                <a href="#null">Advertising</a>
                <p>All you need to know about advertising</p>
                <a href="#null" class="znbOuterLink">www.businessinfo.com</a>
            </div>
            <div>
                <a href="#null">Advertising</a>
                <p>All you need to know about advertising</p>
                <a href="#null" class="znbOuterLink">www.businessinfo.com</a>
            </div>
            <div> <a href="#null">Advertising</a>
                <p>All you need to know about advertising</p>
                <a href="#null" class="znbOuterLink">www.businessinfo.com</a>
            </div>
            <!-- ads end -->
            ';
        }
        return $out;
    }
?>