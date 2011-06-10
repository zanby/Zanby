<?php
	Warecorp::addTranslation('/modules/adminarea/translate/xajax/action.translate.edit.file.php.xml');
    $objResponse = new xajaxResponse();

    $requestFile = $file;
    $file = APP_HOME_DIR.'languages'.urldecode($file);
    if ( !file_exists($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not find file. Contact with administrators.'));
    } elseif ( !is_readable($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not read file. Contact with administrators.'));
    } /*elseif ( !is_writable($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not write file. Contact with administrators.'));
    } */else {
        if ( $handle === null ) {

            $translate = new Zend_Translate('tmx', $file, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
            $translate->setLocale(Warecorp::getDefaultLocale());
            
            if (strpos($file,LANGUAGES_DIR) !== false) {
                $checkfile = str_replace(LANGUAGES_DIR,CUSTOM_LANGUAGES_DIR,$file);
                if (file_exists($checkfile)) {
                    $translate->addTranslation($checkfile,Warecorp::getDefaultLocale());
                }
            }
    
            $this->view->translate = $translate; 
            $this->view->file = $requestFile;
            $this->view->messageKey = $key;
            $this->view->defaultLocale = Warecorp::getDefaultLocale();
            if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
                $LocalesList = $_SESSION['translation_tools']['locales'];
            } else {
                $LocalesList = Warecorp::getLocalesList();
            }
            $this->view->LocalesList = $LocalesList;
            $this->view->LocalesNamesList = Warecorp::getLocalesNamesList();

            $content = $this->view->getContents('adminarea/translate/xajax/translate.edit.file.tpl');
            $objResponse->addAssign('ListOfPhrasesBox', 'innerHTML', $content); 

        } else { 
            if ( !isset($handle['translateMessage_'.Warecorp::getDefaultLocale()]) || '' == trim($handle['translateMessage_'.Warecorp::getDefaultLocale()])  ) {
                $objResponse->addAlert(Warecorp::t('The message for default language is required!'));
                return;
            }
            if (strpos($file,LANGUAGES_DIR) !== false) {
                $file = str_replace(LANGUAGES_DIR,CUSTOM_LANGUAGES_DIR,$file);
            }

            $entries = array();
            $entries[] = array('messageKey'=>$handle['messageKey'],'value'=>trim($handle['translateMessage_'.Warecorp::getDefaultLocale()]),'locale'=>Warecorp::getDefaultLocale());
            /**
             * handle after locales
             */
            if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
                $LocalesList = $_SESSION['translation_tools']['locales'];
            } else {
                $LocalesList = Warecorp::getLocalesList();
            }            
            foreach ( $LocalesList as $Locale ) {
                if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                    if ( isset($handle['translateMessage_'.$Locale]) ) {
                        $entries[] = array('messageKey'=>$handle['messageKey'],'value'=>trim($handle['translateMessage_'.$Locale]),'locale'=>$Locale);
                    }
                }
            }
            Warecorp_Translate::update_entries($file,$entries);

            /**
             * 
             */
            $fileIsTranslated = true;
            $translate = new Zend_Translate('tmx', $file, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
            $translate->setLocale(Warecorp::getDefaultLocale());            
            $messages = $translate->getMessages('all');
            $defaultCount = sizeof($messages[Warecorp::getDefaultLocale()]);
            foreach ( $LocalesList as $Locale ) {
                if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                    if ( !isset($messages[$Locale]) || $defaultCount != sizeof($messages[$Locale]) ) {
                        $fileIsTranslated = false;
                        break;
                    } else {
                        foreach ( $messages[$Locale] as $message ) {
                            if ( $message == '' ) {
                                $fileIsTranslated = false;
                                break 2;                                
                            }
                        }
                    }
                }
            }
            $labelColor = ($fileIsTranslated) ? '#0A3667' : '#FB9204';
            $Script = '';                
            $Script .= 'var fileLabel = document.getElementById("filesTree_node_label_'.md5($file).'");';
            $Script .= 'if (fileLabel) {';
            $Script .= '    fileLabel.style.color = "'.$labelColor.'";';
            $Script .= '};';                
            $objResponse = $this->translateShowFileAction($requestFile);
            $objResponse->addScript($Script);
        }        
    }   
    
