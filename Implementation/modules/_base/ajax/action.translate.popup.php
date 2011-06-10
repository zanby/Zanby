<?php
    $objResponse = new xajaxResponse();

    if ( !Warecorp::isTranslateOnlineDebugMode() ) return;
        
    $form = new Warecorp_Form('translateMessageForm', 'post', BASE_URL.'/'.LOCALE.'/ajax/showTranslatePopup/');
    
    if ( $form->isPostback() ) {
        
        $dom = new DOMDocument();
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;       
        $dom->load($this->getRequest()->getParam('file'));
        $xpath = new DOMXPath($dom);    
        
        $query = '//tmx/body/tu[@tuid="'.$this->getRequest()->getParam('key').'"]/tuv[@xml:lang="'.Warecorp::getDefaultLocale().'"]/seg';
        $entries = $xpath->query($query);
        if ( $entries->length != 0 ) { 
            $segment = $entries->item(0);
            if ( $segment->hasChildNodes() ) { 
                $data = $segment->childNodes;
                $data = $data->item(0);                            
                $data->nodeValue = Warecorp_Translate::prepare_to_tmx($this->getRequest()->getParam('description_'.Warecorp::getDefaultLocale(), ''));
            } else { 
                $data = $dom->createTextNode(Warecorp_Translate::prepare_to_tmx($this->getRequest()->getParam('description_'.Warecorp::getDefaultLocale(), '')));
                $segment->appendChild($data);
            }  
        }
        
        $LocalesList = Warecorp::getLocalesList();
        foreach ( $LocalesList as $Locale ) {
            if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                $query = '//tmx/body/tu[@tuid="'.$this->getRequest()->getParam('key').'"]/tuv[@xml:lang="'.$Locale.'"]/seg';
                $entries = $xpath->query($query);                
                if ( $entries->length != 0 ) { 
                    $segment = $entries->item(0);
                    if ( $segment->hasChildNodes() ) {                         
                        $data = $segment->childNodes;
                        $data = $data->item(0);                            
                        $data->nodeValue = Warecorp_Translate::prepare_to_tmx($this->getRequest()->getParam('description_'.$Locale, ''));
                    } else { 
                        $data = $dom->createTextNode(Warecorp_Translate::prepare_to_tmx($this->getRequest()->getParam('description_'.$Locale, '')));
                        $segment->appendChild($data);
                    }                       
                }            
            }
        }
        
        /* write to file */            
        $dom->formatOutput = true;
        $fp = fopen($this->getRequest()->getParam('file'), 'w');
        fwrite($fp, "\xEF\xBB\xBF".$dom->saveXML());
        fclose($fp);
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        
        $objResponse->addScript("$(\"font[key='".$this->getRequest()->getParam('key')."']\").html(\"".$this->getRequest()->getParam('description_'.LOCALE)."\");");
        $objResponse->addScript("$(\"font[key='".$this->getRequest()->getParam('key')."']\").attr(\"translate\", \"on\");");
        $objResponse->addScript("startTranslateMode();");
        
        $sContentHeader = "Content-type: text/xml;";
        if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
        if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
        header($sContentHeader);
        print $objResponse; exit;
    } else {        
        if ( !isset($file) || empty($file) ) { $objResponse->addAlert('File isn\'t defined'); return; }
        if ( !file_exists($file) ) { $objResponse->addAlert('File isn\'t exists'); return; }        
        
        $messages = array();
        $messages[Warecorp::getDefaultLocale()] = '';
        
        $dom = new DOMDocument();
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;       
        $dom->load($file);
        $xpath = new DOMXPath($dom);    
                
        $query = '//tmx/body/tu[@tuid="'.$key.'"]/tuv[@xml:lang="'.Warecorp::getDefaultLocale().'"]/seg';
        $entries = $xpath->query($query);
        if ( $entries->length != 0 ) { $messages[Warecorp::getDefaultLocale()] = $entries->item(0)->nodeValue;}
        
        $LocalesList = Warecorp::getLocalesList();
        foreach ( $LocalesList as $Locale ) {
            if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                $messages[$Locale] = '';
                $query = '//tmx/body/tu[@tuid="'.$key.'"]/tuv[@xml:lang="'.$Locale.'"]/seg';
                $entries = $xpath->query($query);
                if ( $entries->length != 0 ) { $messages[$Locale] = $entries->item(0)->nodeValue; }            
            }
        }
        
        $this->view->messages = $messages;
        $this->view->form = $form;   
        $this->view->localeNames = Warecorp::getLocalesNamesList();
        $this->view->key = $key;
        $this->view->file = $file;
        $content = $this->view->getContents('ajax/translate.popup.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title('Translate message');
        $popup_window->content($content);
        $popup_window->width(640)->open($objResponse);
    }