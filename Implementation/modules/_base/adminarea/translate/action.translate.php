<?php

    $this->_page->Xajax->registerUriFunction("showTranslateFile", "/adminarea/translateShowFile/");
    $this->_page->Xajax->registerUriFunction("editTranslateFile", "/adminarea/translateEditFile/");
    $this->_page->Xajax->registerUriFunction("exportTranslateFile", "/adminarea/translateExport/");
    $this->_page->Xajax->registerUriFunction("importTranslateFile", "/adminarea/translateImport/");
    $theme = Zend_Registry::get('AppTheme');
    /**
     *
     */
    $LocalesList = Warecorp::getLocalesList();
    $direction = $this->getRequest()->getParam('currentDirection', null);
    if ( null !== $direction ) {
        if ( $direction == 'all' ) {
            unset($_SESSION['translation_tools']['locales']);
        } else {
            foreach ( $LocalesList as $Locale ) {
                if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                    if ( $direction == Warecorp::getDefaultLocale().'__'.$Locale ) {
                        $_SESSION['translation_tools']['locales'] = array(Warecorp::getDefaultLocale(), $Locale);
                    }
                }
            }
        }
    }
    /**
     *
     */
    $LocalesNames = Warecorp::getLocalesNamesList();
    $LocalesSelect = array();
    foreach ( $LocalesList as $Locale ) {
        if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
            $LocalesSelect[] = array(
                'value' => Warecorp::getDefaultLocale().'__'.$Locale,
                'label' => $LocalesNames[Warecorp::getDefaultLocale()] . ' - ' . $LocalesNames[$Locale]
            );
        }
    }
    /**
     *
     */
    if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
        $LocalesList = $_SESSION['translation_tools']['locales'];
    } else {
        $LocalesList = Warecorp::getLocalesList();
    }
    /**
     *
     */
    if ( sizeof($LocalesList) > 2 ) {
        $this->view->currectDirection = 'all';
    } else {
        $this->view->currectDirection = $LocalesList[0].'__'.$LocalesList[1];
    }
    /**
     *
     */
    $contentDivID = 'filesContentDiv';
    $treeName = 'filesTree';
    $Script = '';

    $Script .= $treeName.' = new YAHOO.widget.TreeView("'.$contentDivID.'");';
    $Script .= $treeName.'_main_root_node = '.$treeName.'.getRoot();';

    $rootDir = APP_HOME_DIR.'/languages';
    $parentNodeName = $treeName.'_main_root_node';
    applyDir($rootDir, $treeName, $parentNodeName, $Script);

    $Script .= $treeName.'.subscribe("labelClick", function(node) {';
    $Script .= '    if ( node.data.type == "file" ) {';
    $Script .= '        xajax_showTranslateFile(node.data.filename);';
    $Script .= '        TranslateApplication.workTextNode = node;';
    $Script .= '    }';
    $Script .= '});';

    $Script .= $treeName.'.draw();';
    $Script .= $treeName.'.collapseAll();';

    $this->view->LocalesSelect = $LocalesSelect;
    $this->view->defLocale = Warecorp::getDefaultLocale();
    $this->view->FilesTreeJS = $Script;
    $this->view->bodyContent = 'adminarea/translate/translate.tools.tpl';



    function applyDir($rootDir, $treeName, $parentNodeName, &$Script)
    {
        //
        if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
            $LocalesList = $_SESSION['translation_tools']['locales'];
        } else {
            $LocalesList = Warecorp::getLocalesList();
        }
        if ( file_exists($rootDir) && is_dir($rootDir) ) {
            $dirs = scandir($rootDir);
            if ( sizeof($dirs) != 0 ) {
                foreach ( $dirs as $_dir ) {
                    if ( '.' != $_dir && '..' != $_dir && '.svn' != $_dir ) {
                        if ( is_dir($rootDir. '/' . $_dir) ) {
                            //$dirCrc32 = crc32($rootDir. '/' . $_dir);
                            $dirKey = md5($rootDir. '/' . $_dir);
                            $Script .= "tmpObj = { label : '".generateFolderLabel($_dir, $dirKey, $treeName)."', type : 'folder' };";
                            $Script .= $treeName.'_root_node_'.$dirKey.' = new YAHOO.widget.TextNode(tmpObj, '.$parentNodeName.', true);';
                            //$Script .= $treeName.'_root_node.labelStyle = "";';

                            applyDir($rootDir. '/' . $_dir, $treeName, $treeName.'_root_node_'.$dirKey, $Script);
                        } else {
                            $pathinfo = pathinfo($rootDir. '/' . $_dir);
                            //if ( $pathinfo['extension'] == 'xml' && $pathinfo['basename'] != 'language.xml' ) {
                            if ( $pathinfo['extension'] == 'xml' ) {
                                /**
                                 *
                                 */
                                $fileIsTranslated = true;
                                $translate = new Zend_Translate('tmx', $rootDir. '/' . $_dir, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
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
                                /**
                                 *
                                 */
                                $dirKey = md5($rootDir. '/' . $_dir);
                                $filePath = str_replace(APP_HOME_DIR.'/languages', '', $rootDir. '/' . $_dir);
                                $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".generateFileLabel($pathinfo['basename'], $dirKey, $treeName, $fileIsTranslated)."', type : 'file', filename : '".urlencode($filePath)."' };";
                                $Script .= $treeName.'_file_node = new YAHOO.widget.TextNode(tmpObj, '.$parentNodeName.', true);';
                                //$Script .= $treeName.'_file_node.labelStyle = "";';

                                //javascript:xajax_showTranslateFile(\'sdddd\');
                            }
                        }
                    }
                }
            }
        } elseif ( file_exists($rootDir) && is_file($rootDir) ) {
            $pathinfo = pathinfo($rootDir);
            if ( $pathinfo['extension'] == 'xml' && $pathinfo['basename'] != 'language.xml' ) {
                /**
                 *
                 */
                $fileIsTranslated = true;
                $translate = new Zend_Translate('tmx', $rootDir. '/' . $_dir, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
                $translate->setLocale(Warecorp::getDefaultLocale());
                $messages = $translate->getMessages('all');
                $defaultCount = sizeof($messages[Warecorp::getDefaultLocale()]);
                foreach ( $LocalesList as $Locale ) {
                    if ( $Locale != 'rss' && $Locale != Warecorp::getDefaultLocale() ) {
                        if ( !isset($messages[$Locale]) || $defaultCount != sizeof($messages[$Locale]) ) {
                            $fileIsTranslated = false;
                        }
                    }
                }
                /**
                 *
                 */
                $dirKey = md5($rootDir);
                $filePath = str_replace(APP_HOME_DIR.'/languages', '', $rootDir);
                $Script .= "tmpObj = { href : 'javascript:void(0);', label : '".generateFileLabel($pathinfo['basename'], $dirKey, $treeName, $fileIsTranslated)."', type : 'file', filename : '".urlencode($filePath)."' };";
                $Script .= $treeName.'_file_node = new YAHOO.widget.TextNode(tmpObj, '.$parentNodeName.', true);';
                //$Script .= $treeName.'_file_node.labelStyle = "";';
            }
        }
    }

    function generateFolderLabel($item, $itemKey, $treeName)
    {
        $theme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;

        $label = '';
        $label .= '<div id="'.$treeName.'_node_div_'.$itemKey.'">';
        $label .= '    <table cellpadding="0" cellspacing="0">';
        $label .= '        <tr>';
        $label .= '            <td valigin="middle" style="padding: 3px 5px 0px 0px; width:17px;">';
        $label .= '                <img id="'.$treeName.'_node_image_'.$itemKey.'" src="'.$theme->images.'/decorators/bg-mydocs-folder.gif" border="0">';
        $label .= '            </td>';
        $label .= '            <td valigin="middle" id="'.$treeName.'_node_label_'.$itemKey.'" class="tree-documents-folder-inactive" style="padding-top: 3px;">';
        $label .= '                '.htmlspecialchars($item, ENT_QUOTES);
        $label .= '            </td>';
        $label .= '        </tr>';
        $label .= '    </table>';
        $label .= '</div>';
        return $label;
    }

    function generateFileLabel($item, $itemKey, $treeName, $fileIsTranslated = false)
    {
        $labelColor = ($fileIsTranslated == true) ? '#0A3667' : '#FB9204';
        $label = '';
        $label .= '<div id="'.$treeName.'_node_div_'.$itemKey.'">';
        $label .= '    <table cellpadding="0" cellspacing="0">';
        $label .= '        <tr>';
        $label .= '            <td valigin="middle" id="'.$treeName.'_node_label_'.$itemKey.'" class="tree-documents-folder-inactive" style="padding-top: 3px; color : '. $labelColor .'">';
        $label .= '                '.htmlspecialchars($item,ENT_QUOTES);
        $label .= '            </td>';
        $label .= '        </tr>';
        $label .= '    </table>';
        $label .= '</div>';
        return $label;
    }
