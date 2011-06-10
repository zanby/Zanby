<?php
$objResponse = new xajaxResponse();
 
$videoId = intval($videoId);

if (Warecorp_Video_Standard::isVideoExists($videoId)) {
    $video = Warecorp_Video_Factory::loadById($videoId);
    if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($video->getGallery(), $video->getGallery()->getOwner(), $this->_page->_user)) {
        $video = Warecorp_Video_Factory::createByOwner($video->getGallery()->getOwner());
    }
} else {  
    $video = Warecorp_Video_Factory::createByOwner($video->getGallery()->getOwner());
}

$this->view->video = $video; 


$this->view->cloneId =1;
$content = $this->view->getContents('ajax/showVideoPopup.tpl');


$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title();
$popup_window->content($content);
$popup_window->width(420)->height(380)->open($objResponse);


$script = '';

if ($video->getId()) {
    $_title = str_replace("'", "\'", $video->getTitle());
    $_title = htmlspecialchars($_title);

    $_text = 'embedsrc = "'.$video->getViewerSrc().'";';
    $_text .= 'var flashvars = {width:385, height:305, usefullscreen:false, file:"'.$video->getViewSrc().'", image:"'.$video->getCover()->getSrc().'_orig.jpg", title:"'.$_title.'", viewCounterFunc:"xajax_viewCounter", viewCounterParam: '.$video->getId().'};var params = {allowscriptaccess: "always", wmode:"", allowfullscreen:true};var attributes = {wmode:"", allowfullscreen:true};attributes.id = "zanbyPlayer_showVideoPopup";';
    $videoCS = $video->getCustomSrc();
    if ( !empty($videoCS)) {
            if ($video->getSource() == Warecorp_Video_Enum_VideoSource::BLIPTV) {
                $_text .= 'if (navigator.userAgent.indexOf ("Safari") != -1) { flashvars.file = flashvars.file + "?" + (new Date()).getTime(); embedsrc = embedsrc + "?" + (new Date()).getTime();}';        
            }
    }
    $_text .= 'swfobject.embedSWF(embedsrc, "myAlternativeContent_showVideoPopup", "385", "305", "8.0.0", false, flashvars, params, attributes);';  
   
    $script .= "tmpTd = document.getElementById('showVideoPopup_scriptContainer');"; 
    $script .= "tmpTd.innerHTML = '';"; 
    $script .= "newScript = document.createElement('script');"; 
    $script .= 'newScript.id = "showVideoPopup_script";'; 
    $script .= 'newScript.text = \''.$_text.'\';';
    $script .= 'tmpTd.appendChild(newScript);';
}                                    
         
                
                                            
$objResponse->addScript($script);   
