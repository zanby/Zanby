<?php
Warecorp::addTranslation('/modules/groups/contentblocks/action.getblockcontentinpreviewmode.php.xml');

$objResponse = new xajaxResponse();

//$content_array = "";
$result = Warecorp_DDPages::loadFromDB($this->currentGroup->getId(), $this->currentGroup->EntityTypeId);
$data = unserialize($result);
if (! empty($data)) {
    if (sizeof($data) != 0) {
        foreach ($data as $item) {
            if ($item['ID'] == $CloneElID){
                //print_r($item);die; 
                $ContentDivId = "aj_info_".$CloneElID;
                
                //------------------------------------------------------------------------------------------------------------   
                if ($item['targetID'] == "ddTarget1") {
                    $blockColumn = "narrow";
                }
                if ($item['targetID'] == "ddTarget2") {
                    $blockColumn = "wide";
                }
                if (empty($blockColumn) || ! in_array($blockColumn, array(
                    'narrow' , 
                    'wide'))) {
                    $blockColumn = 'wide';
                }
                $script = '';
                $content = '';
                $dateObj = new Zend_Date();
                $dateObj->setTimezone($this->_page->_user->getTimezone());
                $this->view->TIMEZONE = $dateObj->get(Zend_Date::TIMEZONE);
                $this->view->cloneId = $CloneElID; 

                switch ($item['ContentType']) {
                    
                    //-------------------------------------------------------------------------------------
                    case 'ddFamilyTopVideos':
                        
                        if (! isset($item['Data'])) {
                            $item['Data'] = array();
                            $item['Data']['topvideosDisplayMostActive'] = 1;
                            $item['Data']['topvideosDisplayMostRecent'] = 1;
                            $item['Data']['topvideosDisplayMostUpped'] = 1;
                            $item['Data']['headline'] = '';
                            $item['Data']['topvideosShowThreadsNumber'] = 0;
                        }
                        
                        if (! empty($item['Data']['topvideosShowThreadsNumber']) && (! empty($item['Data']['topvideosDisplayMostActive']) || ! empty($Data['Data']['topvideosDisplayMostUpped']) || ! empty($item['Data']['topvideosDisplayMostRecent']))) {
                            
                            $topvideosList = Warecorp_Video_List_Factory::loadByOwner($this->currentGroup);
                            
                            if (! empty($item['Data']['topvideosDisplayMostRecent'])) {
                                $mostRecentVideos = $topvideosList->setOrder('tbl.creation_date DESC')->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
                                $_cntr = 0;
                                foreach ($mostRecentVideos as &$video) {
                                    $_cntr++;
                                    $script .= "tmpTd = document.getElementById('recent_td_".$CloneElID."_".$_cntr."');"; 
                                    $script .= "newScript= document.createElement('script');"; 
                                    $script .= "tmpScript = document.getElementById('recent_script_".$CloneElID."_".$_cntr."');"; 
                                    $script .= 'newScript.text = tmpScript.text;';
                                    $script .= 'newScript.id = tmpScript.id;';
                                    $script .= 'tmpTd.removeChild(tmpScript);';
                                    $script .= 'tmpTd.appendChild(newScript);';
                                }
                                $this->view->mostRecentVideos = $mostRecentVideos;
                            
                            }
                            
                            if (! empty($item['Data']['topvideosDisplayMostActive'])) {
                                $mostActiveVideos = $topvideosList->returnInMostActiveOrder()->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
                                $_cntr = 0;
                                foreach ($mostActiveVideos as &$video) {
                                    $_cntr++;
                                    $script .= "tmpTd = document.getElementById('active_td_".$CloneElID."_".$_cntr."');"; 
                                    $script .= "newScript= document.createElement('script');"; 
                                    $script .= "tmpScript = document.getElementById('active_script_".$CloneElID."_".$_cntr."');"; 
                                    $script .= 'newScript.text = tmpScript.text;';
                                    $script .= 'newScript.id = tmpScript.id;';
                                    $script .= 'tmpTd.removeChild(tmpScript);';
                                    $script .= 'tmpTd.appendChild(newScript);';
                                }
                                
                                $this->view->mostActiveVideos = $mostActiveVideos; 
                                
                            }
                            
                            if (! empty($item['Data']['topvideosDisplayMostUpped'])) {
                                $mostUppedVideos = $topvideosList->returnInMostUppedOrder()->setListSize(intval($item['Data']['topvideosShowThreadsNumber']))->setCurrentPage(1)->getList();
                                $_cntr = 0;
                                foreach ($mostUppedVideos as &$video) {
                                    $_cntr++;
                                    $script .= "tmpTd = document.getElementById('upped_td_".$CloneElID."_".$_cntr."');"; 
                                    $script .= "newScript= document.createElement('script');"; 
                                    $script .= "tmpScript = document.getElementById('upped_script_".$CloneElID."_".$_cntr."');"; 
                                    $script .= 'newScript.text = tmpScript.text;';
                                    $script .= 'newScript.id = tmpScript.id;';
                                    $script .= 'tmpTd.removeChild(tmpScript);';
                                    $script .= 'tmpTd.appendChild(newScript);';
                                }
                                $this->view->mostUppedVideos = $mostUppedVideos; 
                            }
                        }
                        
                        
                        $this->view->currentTab = $params; 
                        $this->view->assign($item['Data']);
                        $this->view->cloneId = $CloneElID;
                        
                       
                        $content = $this->view->getContents('content_objects/ddFamilyTopVideos/preview_mode_' . $blockColumn . '.tpl');    
                        
                        break;
                    
                    //-------------------------------------------------------------------------------------
                    case 'ddFamilyVideoContentBlock':
                        $video_id = (isset($item['Data']['videoId']) ? intval($item['Data']['videoId']) : 0);
                        $content = (isset($item['Data']['Content']) ? $item['Data']['Content']: '<p align="center">'.Warecorp::t('Click Edit button to change this text').'</p>');
                                                                                                 
                        if (! isset($item['Data'])) {
                            $item['Data'] = array();
                            $item['Data']['innerText'] = $content;
                            $item['Data']['Content'] = $content;
                            $item['Data']['headline'] = $defHeadline;
                            $item['Data']['videoId'] = 0;
                        }
                        
                        if (Warecorp_Video_Standard::isVideoExists($video_id)) {
                            $currentImage = Warecorp_Video_Factory::loadById($video_id);
                            if (! Warecorp_Video_AccessManager_Factory::create()->canViewGallery($currentImage->getGallery(), $this->currentGroup, $this->_page->_user)) {
                                $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
                            }
                        } else {
                            $currentImage = Warecorp_Video_Factory::createByOwner($this->currentGroup);
                        }
                      
                        $this->view->video = $currentImage;  
                        
                        if (isset($item['Data'])) {
                            $this->view->assign($item['Data']);
                        }
                        $this->view->cloneId = $CloneElID;
                        $content = $this->view->getContents('content_objects/ddFamilyVideoContentBlock/' . $blockColumn . '_info.tpl');
                        
                        break;
                        //-------------------------------------------------------------------------------------
                }


                $objResponse->addAssign($ContentDivId, "innerHTML", $content);
                $objResponse->addScript($script);
            //------------------------------------------------------------------------------------------------------------    
            }
        }
    }
}




