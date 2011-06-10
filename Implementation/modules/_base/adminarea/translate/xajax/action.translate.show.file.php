<?php
	Warecorp::addTranslation('/modules/adminarea/translate/xajax/action.translate.show.file.php.xml');
    $objResponse = new xajaxResponse();
    
    $requestFile = $file;
    $file = APP_HOME_DIR.'languages'.urldecode($file);
    $pathinfo = pathinfo($file);
    
    if ( !file_exists($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not find file. Contact with administrators.'));
    } elseif ( !is_readable($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not read file. Contact with administrators.'));
    } /*elseif ( !is_writable($file) ) {
        $objResponse->addAlert(Warecorp::t('Can not write file. Contact with administrators.'));
    } */else {
        $translate = new Zend_Translate('tmx', $file, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
        $translate->setLocale(Warecorp::getDefaultLocale());
        
        if (strpos($file,LANGUAGES_DIR) !== false) {
            $checkfile = str_replace(LANGUAGES_DIR,CUSTOM_LANGUAGES_DIR,$file);
            if (file_exists($checkfile)) {
                $translate->addTranslation($checkfile,Warecorp::getDefaultLocale());
            }
        }
        
        
        $MessageIds = $translate->getMessageIds();
        $Messages = array();
        if ( sizeof($MessageIds) != 0 ) {
            $Messages = $translate->getMessages(Warecorp::getDefaultLocale());
        }
        $this->view->Messages = $Messages;
        $this->view->translate = $translate;
        if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
            $LocalesList = $_SESSION['translation_tools']['locales'];
        } else {
            $LocalesList = Warecorp::getLocalesList();
        }
        $this->view->LocalesList = $LocalesList;

        $this->view->file = $requestFile;
        $this->view->fileName = $pathinfo['basename'];
        $content = $this->view->getContents('adminarea/translate/translate.tools.phrases.template.tpl');
        $objResponse->addAssign('ListOfPhrasesBox', 'innerHTML', $content);

        $Script = '';
        $Script .= 'var fileLabel = document.getElementById("filesTree_node_label_'.md5($file).'");';
        $Script .= 'if ( fileLabel ) {';
        $Script .= '    fileLabel.style.fontWeight = "bolder";';
        $Script .= '};';
        $Script .= 'if ( TranslateApplication.currentTreeNode && TranslateApplication.currentTreeNode != "filesTree_node_label_'.md5($file).'") {';
        $Script .= '    var fileLabel = document.getElementById(TranslateApplication.currentTreeNode);';
        $Script .= '    if ( fileLabel ) {';
        $Script .= '        fileLabel.style.fontWeight = "normal";';
        $Script .= '    };';
        $Script .= '}';  
        $Script .= 'TranslateApplication.currentTreeNode = "filesTree_node_label_'.md5($file).'";';
        $Script .= 'window.scroll(0,0);';
        

        $objResponse->addScript($Script);

        $ex_im_content = $this->view->getContents('adminarea/translate/translate.tools.export.import.template.tpl');
        $objResponse->addAssign('ExportImportTools', 'innerHTML', $ex_im_content);
        $objResponse->addAssign('ExportImportTools', 'style.display', '');
    }    
    
    return $objResponse;
