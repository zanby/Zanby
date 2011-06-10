<?php
    $objResponse = new xajaxResponse();

    $handle = $this->getRequest()->getParam('handle', null);

    /**
     * Check $file
     */
    $file = ( !isset($file) ) ? $this->getRequest()->getParam('file', null) : $file;
    $requestFile = $file;
    $file = APP_HOME_DIR.'/languages'.urldecode($file);
    $pathinfo = pathinfo($file);
    if ( !file_exists($file) ) {
        $objResponse->addAlert('Can not find file. Contact with administrators.'); return;
    } elseif ( !is_readable($file) ) {
        $objResponse->addAlert('Can not read file. Contact with administrators.'); return;
    } elseif ( !is_writable($file) ) {
        $objResponse->addAlert('Can not write file. Contact with administrators.'); return;
    }

    if ( null === $handle ) {
        $allLocales = false;
        if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
            $lstSelectedLocales = $_SESSION['translation_tools']['locales'];
        } else {
            $lstSelectedLocales = Warecorp::getLocalesList();
            $allLocales = true;
        }

        $lstLocales = Warecorp::getLocalesList();
        foreach ( $lstLocales as $key => $value ) {
            if ( 'rss' == $value ) unset($lstLocales[$key]);
        }
        $size = sizeof($lstLocales);
        $size = (0 == $size % 3) ? $size / 3 : $size / 3 + 1;
        $lstLocales = array_chunk($lstLocales, $size);

        $this->view->file = $requestFile;
        $this->view->allLocales = $allLocales;
        $this->view->defLocale = Warecorp::getDefaultLocale();
        $this->view->lstLocales = $lstLocales;
        $this->view->lstLocalesSize = sizeof($lstLocales[0]);
        $this->view->lstSelectedLocales = $lstSelectedLocales;

        $this->view->lstLocalesNames = Warecorp::getLocalesNamesList();
        $content = $this->view->getContents('adminarea/translate/translate.tools.import.options.template.tpl');
        $objResponse->addAssign('ExportImportTools', 'innerHTML', $content);
        $objResponse->addAssign('ExportImportTools', 'style.display', '');
    } else {
        $errorMessages = array();
        $languages = $this->getRequest()->getParam('languages', null);
        if ( null === $languages || sizeof($languages) == 0 ) {
            $errorMessages[] = "Please choose langueage(s) you want to import";
        }
        if ( !isset($_FILES['fileField']) || $_FILES['fileField']['error'] != 0 ) {
            $errorMessages[] = "Please choose file you want to import";
        } elseif ( !is_uploaded_file($_FILES['fileField']['tmp_name']) ) {
            $errorMessages[] = "Please choose file you want to import";
        } else {
            $fp = fopen($_FILES['fileField']['tmp_name'], 'r');
            $content = fread($fp, filesize($_FILES['fileField']['tmp_name']));
            fclose($fp);

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->encoding = 'UTF-8';
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $loadStatus = null;
            try { $loadStatus = @$dom->loadXML($content);
            } catch (Exception $ex) {}
            if ( !$loadStatus ) $errorMessages[] = "Incorrect format of xml document";
            else {
                $body = $dom->getElementsByTagName('tmx')->item(0)->getElementsByTagName('body')->item(0);
                if ( !$body ) $errorMessages[] = "Incorrect format of xml document";
            }
        }
        if ( sizeof($errorMessages) == 0 ) {
            /**
             * Load original tmx file
             */
            $orDom = new DOMDocument('1.0', 'UTF-8');
            $orDom->encoding = 'UTF-8';
            $orDom->preserveWhiteSpace = false;
            $orDom->formatOutput = true;
            $orDom->load($file);
            $xpath = new DOMXPath($orDom);
            $orDomChanged = false;

            $tus = $body->getElementsByTagName('tu');
            if ( 0 != $tus->length ) {
                foreach ( $tus as $tu ) {
                    $messageInfo = array();
                    $messageInfo['tuid'] = $tu->getAttribute('tuid');
                    if ( $messageInfo['tuid'] ) {
                        $tuvs = $tu->getElementsByTagName('tuv');
                        if ( 0 != $tuvs->length ) {
                            foreach ( $tuvs as $tuv ) {
                                $messageInfo['lang'] = $tuv->getAttribute('xml:lang');
                                if ( in_array($messageInfo['lang'], $languages) ) {
                                    $segment = $tuv->getElementsByTagName('seg')->item(0);
                                    if ( $segment ) {
                                        $messageInfo['message'] = $segment->nodeValue;
                                        /**
                                         * Update original file
                                         */
                                        $query = '//tmx/body/tu[@tuid="'.$messageInfo['tuid'].'"]/tuv[@xml:lang="'.$messageInfo['lang'].'"]';
                                        $entries = $xpath->query($query);
                                        if ( $entries->length == 1 ) {
                                            $orSegment = $entries->item(0)->getElementsByTagName('seg')->item(0);
                                            if ( $orSegment ) {
                                                $orSegment->nodeValue = Warecorp_Translate::prepare_to_tmx($messageInfo['message']);
                                                $orDomChanged = true;
                                            }
                                        }
                                        /**
                                         * add new tuv
                                         * @todo Need replace functioanality to Core
                                         */
                                        else {
                                            $query = '//tmx/body/tu[@tuid="'.$messageInfo['tuid'].'"]';
                                            $entries = $xpath->query($query);
                                            if ( $entries->length == 1 ) {
                                                $tu = $entries->item(0);
                                                $tuv = $orDom->createElement('tuv');
                                                $tu->appendChild($tuv);
                                                $tuvLang = $orDom->createAttribute('xml:lang');
                                                $tuvLang->nodeValue = $messageInfo['lang'];
                                                $tuv->appendChild($tuvLang);
                                                $seg = $orDom->createElement('seg');
                                                $tuv->appendChild($seg);
                                                $seg->nodeValue = Warecorp_Translate::prepare_to_tmx($messageInfo['message']);
                                                $orDomChanged = true;
                                            }
                                            else {
                                                $entries = $orDom->getElementsByTagName('body');
                                                if ( $entries->length == 1 ) {
                                                    $body = $entries->item(0);

                                                    $tu = $orDom->createElement('tu');
                                                    $body->appendChild($tu);
                                                    $tuid = $orDom->createAttribute('tuid');
                                                    $tuid->nodeValue = $messageInfo['tuid'];
                                                    $tu->appendChild($tuid);
                                                    $tuv = $orDom->createElement('tuv');
                                                    $tu->appendChild($tuv);
                                                    $tuvLang = $orDom->createAttribute('xml:lang');
                                                    $tuvLang->nodeValue = $messageInfo['lang'];
                                                    $tuv->appendChild($tuvLang);
                                                    $seg = $orDom->createElement('seg');
                                                    $tuv->appendChild($seg);
                                                    $seg->nodeValue = Warecorp_Translate::prepare_to_tmx($messageInfo['message']);
                                                    $orDomChanged = true;
                                                }
                                            }
                                        }
                                    }// нет segment
                                }
                            }
                        }
                    }// нет tuid
                }
            }
            if ( $orDomChanged ) {
                $orDom->formatOutput = true;
                $fp = fopen($file, 'w');
                fwrite($fp, $orDom->saveXML());
                fclose($fp);

                /**
                 * clear all templates cache to apply changes
                 * @author Artem Sukharev
                 * issue #12376
                 */
                Warecorp::cleanTemplatesCache();
            }

            /**
             *
             */
            $fileIsTranslated = true;
            $translate = new Zend_Translate('tmx', $file, Warecorp::getDefaultLocale());
            $translate->setLocale(Warecorp::getDefaultLocale());
            $messages = $translate->getMessages('all');
            $defaultCount = sizeof($messages[Warecorp::getDefaultLocale()]);
            if ( isset($_SESSION['translation_tools']) && isset($_SESSION['translation_tools']['locales']) ) {
                $LocalesList = $_SESSION['translation_tools']['locales'];
            } else {
                $LocalesList = Warecorp::getLocalesList();
            }
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
        /**
         *
         */
        if ( sizeof($errorMessages) != 0 ) {
            $objResponse->addAssign('ExportImportToolsErrors', 'innerHTML', '<font color="red">'.join('<br>', $errorMessages).'</font>');
            $objResponse->addAssign('ExportImportToolsErrors', 'style.display', '');
        }

        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;
    }
    return $objResponse;
