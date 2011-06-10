<?php
    function smarty_function_show_video($params, &$smarty)
    {
        if (!isset($params['video'])) {
            $params['video'] = new Warecorp_Video_Standard();
        }
        $params['width']    = ( !isset($params['width']) ) ? 385 : $params['width'];
        $params['height']   = ( !isset($params['height']) ) ? 305 : $params['height'];
        $params['id']       = ( !isset($params['id']) ) ? '1' : $params['id'];
                
        $out = '<script type="text/javascript" src="'.BASE_URL.'/UptakeVideoPlayer/swfobject-2.0.js"></script>';
        $out .= '<script type="text/javascript">';
        $out .= 'var flashvars = {width:'.$params['width'].', height:'.$params['height'].', 
                    usefullscreen:false, viewCounterFunc:"xajax_viewCounter", 
                    viewCounterParam:'.$params['video']->getId().', 
                    file:"'.$params['video']->getViewSrc().'", 
                    image:"'.$params['video']->getCover()->getSrc().'_orig.jpg", 
                    title:"'.htmlspecialchars($params['video']->getTitle(), ENT_QUOTES, $char_set).'"};';
        
        $out .= 'var params = {allowscriptaccess: "always", wmode:"transparent", 
                    allowfullscreen:true};';
                    
        $out .= 'var attributes = {wmode:"transparent", allowfullscreen:true};';
        $out .= 'attributes.id = "zanbyPlayer_'.$params['id'].'";';
        $out .= 'embedsrc = "'.$params['video']->getViewerSrc().'";';
        $videoCS = $params['video']->getCustomSrc();
        if ( !empty($videoCS)) {
            if ($params['video']->getSource() == Warecorp_Video_Enum_VideoSource::BLIPTV) {
                $out .= 'if (navigator.userAgent.indexOf ("Safari") != -1) {                                                                                                                                                
                                flashvars.file = flashvars.file + "?" + (new Date()).getTime();
                                embedsrc = embedsrc + "?" + (new Date()).getTime();
                }';        
            }
        }
        $out .= 'swfobject.embedSWF(embedsrc, 
                    "myAlternativeContent_'.$params['id'].'", "'.$params['width'].'", 
                    "'.$params['height'].'", "8.0.0", false, flashvars, params, attributes);';
        $out .= '</script>';
        $out .= '<div id="myAlternativeContent_'.$params['id'].'">
            <a href="http://www.adobe.com/go/getflashplayer">
                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
            </a>
        </div>';        
        
        return $out;
    }
?>
