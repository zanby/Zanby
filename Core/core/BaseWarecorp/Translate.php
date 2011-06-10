<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

class BaseWarecorp_Translate
{
    /**
     * Instance of TMX translate adapter
     * @var Zend_Translate_Adapter_Tmx
     */
    static private $_translate;
    static private $_last_language_file;
    static private $_loaded_language_files;
    static private $_loaded_language_files_src;

    static private $_build_mode = false; //Used for building basic translations only!
    
    /**
     * Get instance of translate adapter
     * @return Zend_Translate_Adapter_Tmx
     */
    static public function getTranslate() {
        if ( null === self::$_translate ) {
            if ( null === $languageFile = self::get_language_file('language.xml') ) $languageFile = self::create_language_file('language.xml');
            $locale = ( defined('LOCALE') && LOCALE != 'rss' ) ? LOCALE : Warecorp::getDefaultLocale();
            self::$_translate = new Zend_Translate('tmx', $languageFile, $locale,array('disableNotices'=>true));
            self::$_translate->setLocale(strtolower($locale));
            
            if ( Warecorp::isTranslateDebugMode() ) {
                self::$_loaded_language_files[] = $languageFile;
                self::$_loaded_language_files_src[$languageFile] = file_get_contents($languageFile);
            }            
        }
        return self::$_translate;
    }

    /**
     * Sets build translation mode
     * @param boolean $mode
     */
    static public function setBuildMode($mode) {
        self::$_build_mode = (boolean)$mode;
    }

    /**
     * Check if we have build mode active.
     * @return boolean
     */
    static public function isBuildMode() {
        return self::$_build_mode;
    }
    
    /**
     * return language files that have been loaded
     * @return array of string
     */
    static public function getLoadedLanguageFiles()
    {
        return self::$_loaded_language_files;
    }
    
    /**
     * return language files that have been loaded
     * @return array of string
     */
    static public function getLoadedLanguageFilesScr()
    {
        return self::$_loaded_language_files_src;
    }
     
    /**
    * Smarty prefilter function that translate template
    * @param string $tpl_source - source of template as string
    * @param Samrty $smarty - object of current instance of smarty
    * @return string $tpl_source
    * @author Artem Sukharev
    */
    static public function translateContentPrefilter($tpl_source, &$smarty = null, $relFile = null)
    {
        //preg_match_all("/{t\s{1,}(.*?)}(.*?){\/t}/ism", $tpl_source, $matches);
		preg_match_all("/{t(?:|\s{1,}(.*?))}(.*?){\/t}/ism", $tpl_source, $matches);
		//print_r($matches);exit;
        if ( 0 != sizeof($matches[0]) ) {            
			if ( null === $relFile ) $languageFile = self::add_translation_file('templates/'.$smarty->_current_file.'.xml');
			else $languageFile = self::add_translation_file('templates/'.$relFile.'.xml');
            if ( $languageFile && Warecorp::isTranslateAutoGenerateMode() ) self::update_language_file($languageFile, $matches);
            
			if ( null === $relFile ) {
				foreach ( $matches[0] as $_ind => $_tag ) {
					/**
					 * if tag {t} has attribute var it means that we want to assign translaten message to variable
					 * it must not be processed by prefilter if it contains {tparam} tag (to replace it with dynamic varable)
					 */
					if ( preg_match("/var='(.*?)'|var=\"(.*?)\"|var=([a-zA-Z0-9_]*)/is", $matches[1][$_ind], $var_matches) ) {
						if ( preg_match("/{tparam\s{1,}(.*?)(value='(.*?)'|value=\"(.*?)\"|value=(.*?))}/is", $matches[2][$_ind]) ) {
							/* leave {t} tag in template to processing it by template rendering */
						} else {
							$varName = null;
							$varName = ( null === $varName && isset($var_matches[3]) ) ? $var_matches[3] : $varName;
							$varName = ( null === $varName && isset($var_matches[2]) ) ? $var_matches[2] : $varName;
							$varName = ( null === $varName && isset($var_matches[1]) ) ? $var_matches[1] : $varName;
							/* translate it and and replace as {assign} tag */
							$defaultMessage = $matches[2][$_ind];
							$params = array();
							list($defaultMessage, $params) = self::get_tparam($defaultMessage);
							$translated = Warecorp_Translate::translate($defaultMessage, $params);
							$out = "{assign var=\"".$varName."\" value=\"".str_replace('"', '\"', $translated)."\"}";
							$tpl_source = str_replace($_tag, $out, $tpl_source);                       
						}
					} else {
						$defaultMessage = $matches[2][$_ind]; 
						$params = array();
						list($defaultMessage, $params) = self::get_tparam($defaultMessage);                    
						$tpl_source = str_replace($_tag, Warecorp_Translate::translate($defaultMessage, $params), $tpl_source);
					}
				}
			}
        }
        return $tpl_source;
    }

    /**
     * @param string $relName filepath without context
     * @param string $locale
     * @param array $matches translate strings
     * @return string filepath
     */
    static public function add_translation_file($relName, $locale = null, $matches = null)
    {
        if ( !Warecorp::isTranslateMode() ) return null;
        $languageFile = null;
        if (file_exists(LANGUAGES_DIR.$relName) && is_file(LANGUAGES_DIR.$relName) && is_readable(LANGUAGES_DIR.$relName)) {
            $languageFile = LANGUAGES_DIR.$relName;
        }else{
            $languageFile = self::create_language_file($relName);
        }

        if ( null === $locale ) $locale = ( defined('LOCALE') && LOCALE != 'rss' ) ? LOCALE : Warecorp::getDefaultLocale();
        self::getTranslate()->addTranslation($languageFile, $locale);
        
        if (!self::isBuildMode() && file_exists(CUSTOM_LANGUAGES_DIR.$relName) && is_file(CUSTOM_LANGUAGES_DIR.$relName) && is_readable(CUSTOM_LANGUAGES_DIR.$relName)) {
            $languageFile = CUSTOM_LANGUAGES_DIR.$relName;
            self::getTranslate()->addTranslation($languageFile, $locale);
        }
        //self::$_last_language_file[] = $languageFile;
        
//        if ( Warecorp::isTranslateOnlineDebugMode() ) {
//            self::$_loaded_language_files[] = $languageFile;
//            self::$_loaded_language_files_src[$languageFile] = file_get_contents($languageFile);
//        }
        return LANGUAGES_DIR.$relName;
    }
    
    static private function get_language_file($relName)
    {
        $languageFile = null;
        if (file_exists(CUSTOM_LANGUAGES_DIR.$relName) && is_file(CUSTOM_LANGUAGES_DIR.$relName) && is_readable(CUSTOM_LANGUAGES_DIR.$relName)) {
            $languageFile = CUSTOM_LANGUAGES_DIR.$relName;
        }elseif (file_exists(LANGUAGES_DIR.$relName) && is_file(LANGUAGES_DIR.$relName) && is_readable(LANGUAGES_DIR.$relName)) {
            $languageFile = LANGUAGES_DIR.$relName;  
        }
        return $languageFile;

//        $languageFile = null;
//        if ( !defined('CORE_LANGUGES_DIR') ) define('CORE_LANGUGES_DIR', PRODUCT_LANGUGES_DIR);
//        $relName = ( '/' != substr($relName, 0,1) && '\\' != substr($relName, 0,1) ) ? '/'.$relName : $relName;
//        if ( file_exists(LANGUGES_DIR.$relName) && is_file(LANGUGES_DIR.$relName) && is_readable(LANGUGES_DIR.$relName) ) {
//            $languageFile = LANGUGES_DIR.$relName;
//        } elseif ( file_exists(CORE_LANGUGES_DIR.$relName) && is_file(CORE_LANGUGES_DIR.$relName) && is_readable(CORE_LANGUGES_DIR.$relName) ) {
//            $languageFile = CORE_LANGUGES_DIR.$relName;
//        } elseif ( file_exists(PRODUCT_LANGUGES_DIR.$relName) && is_file(PRODUCT_LANGUGES_DIR.$relName) && is_readable(PRODUCT_LANGUGES_DIR.$relName) ) {
//            $languageFile = PRODUCT_LANGUGES_DIR.$relName;
//        } elseif ( Warecorp::isTranslateAutoGenerateMode() ) {
//            $languageFile = self::create_language_file($relName);
//        }
//        return $languageFile;
    }


    /**
     *
     * @param string|DOMDocument $file Full path to file or file itself
     * @param array $entries (messageKey,value,locale)
     * @return DOMDocument
     */
    static public function update_entries($file, $entries) {
        if (!($file instanceof DOMDocument)) {
            $path = $file;
            if (!file_exists($path)) { //Create file
                $languagesDir = strpos($path,LANGUAGES_DIR) !== false ? LANGUAGES_DIR : CUSTOM_LANGUAGES_DIR ;
                $relPath = str_replace($languagesDir, '', $path);
                $path = self::create_language_file($relPath, $languagesDir);
            }

            $file = new DOMDocument();
            $file->encoding = 'UTF-8';
            $file->preserveWhiteSpace = false;
            $file->formatOutput = true;
            $file->load($path);
        }
        $xpath = new DOMXPath($file);

        $body = $xpath->query('//tmx/body')->item(0);

        foreach ($entries as $entry) {
            $value = $entry['value'];
            $Locale = $entry['locale'];
            $messageKey = $entry['messageKey'];

            if ($Locale === null) $Locale = Warecorp::getDefaultLocale();

            $query = '//tmx/body/tu[@tuid="'.$messageKey.'"]/tuv[@xml:lang="'.$Locale.'"]';
            $xpath_entries = $xpath->query($query);
            /**
             * translation for current locale does't have been found
             */
            if ( $xpath_entries->length == 0 ) {
                $query = '//tmx/body/tu[@tuid="'.$messageKey.'"]';
                $xpath_entries = $xpath->query($query);
                $tu = $xpath_entries->item(0);

                if (!$tu) {
                    $tu = $file->createElement('tu');
                    $tuId = $file->createAttribute('tuid');
                    $tuId->nodeValue = $messageKey;
                    $tu->appendChild($tuId);
                    $body->appendChild($tu);
                }

                $tuv = $file->createElement('tuv');
                $tu->appendChild($tuv);
                $tuvLang = $file->createAttribute('xml:lang');
                $tuvLang->nodeValue = $Locale;
                $tuv->appendChild($tuvLang);

                $seg = $file->createElement('seg');
                $tuv->appendChild($seg);
                $seg->nodeValue = Warecorp_Translate::prepare_to_tmx($value);
            }
            /**
             * translation for current locale has been found
             */
            else {
                $xpath_entry = $xpath_entries->item(0);
                $segment = $xpath_entry->getElementsByTagName('seg');
                $segment = $segment->item(0);

                if ( $segment->hasChildNodes() ) {
                    $data = $segment->childNodes;
                    $data = $data->item(0);
                    $data->nodeValue = $value;
                } else {
                    $segment->nodeValue = Warecorp_Translate::prepare_to_tmx($value);
                    $segment->appendChild($data);
                }
            }

        }

        /**
         * Write to file
         */  
        $file->formatOutput = true;
        $fp = fopen($path, 'w');
        fwrite($fp, "\xEF\xBB\xBF".$file->saveXML());
        fclose($fp);

        /**
         * clear all templates cache to apply changes
         * @author Artem Sukharev
         * issue #12376
         */
        Warecorp::cleanTemplatesCache();

        return true;
    }

    static private function create_language_file($relName, $languagesDir = LANGUAGES_DIR)
    {
        /* find folder for new language file */

        self::create_dir_structure($languagesDir, $relName);
        
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $doctype = DOMImplementation::createDocumentType("tmx","-//LISA OSCAR:1998//DTD for Translation Memory eXchange//EN","tmx14.dtd");
        $dom->appendChild($doctype);

        $tmx = $dom->createElement('tmx');
        $tmxVersion = $dom->createAttribute('version');
        $tmxVersion->nodeValue = '1.4';
        $tmx->appendChild($tmxVersion);
        $dom->appendChild($tmx);

        $header = $dom->createElement('header');
        $header_adminlang = $dom->createAttribute('adminlang');
        $header_adminlang->nodeValue = Warecorp::getDefaultLocale();
        $header->appendChild($header_adminlang);
        $header_datatype = $dom->createAttribute('datatype');
        $header_datatype->nodeValue = 'unknown';
        $header->appendChild($header_datatype);
        $header_o_tmf = $dom->createAttribute('o-tmf');
        $header_o_tmf->nodeValue = 'unknown';
        $header->appendChild($header_o_tmf);
        $header_segtype = $dom->createAttribute('segtype');
        $header_segtype->nodeValue = 'block';
        $header->appendChild($header_segtype);
        $header_srclang = $dom->createAttribute('srclang');
        $header_srclang->nodeValue = Warecorp::getDefaultLocale();
        $header->appendChild($header_srclang);
        $tmx->appendChild($header);

        $body = $dom->createElement('body');
        $tmx->appendChild($body);
        
        $dom->formatOutput = true;
        $fp = fopen($languagesDir.DIRECTORY_SEPARATOR.$relName, 'w');
        //fwrite($fp, "\xEF\xBB\xBF".utf8_encode($dom->saveXML()));
        fwrite($fp, utf8_encode($dom->saveXML()));
        fclose($fp);
        chmod($languagesDir.DIRECTORY_SEPARATOR.$relName, 0777);
        return $languagesDir.DIRECTORY_SEPARATOR.$relName;
    }
    
    static private function create_dir_structure($languagesDir, $relName)
    {
        if (!file_exists($languagesDir.DIRECTORY_SEPARATOR)) {
            mkdir($languagesDir . DIRECTORY_SEPARATOR, 0777);
        }

        $dirs = dirname($relName);
        if ( '.' != $dirs && '' != $dirs ) {
            if ( !file_exists($languagesDir . DIRECTORY_SEPARATOR . $dirs . DIRECTORY_SEPARATOR)  ) {
                $allDirs    = explode(DIRECTORY_SEPARATOR, $dirs);
                $pathDirs   = '';
                if ( 0 != sizeof($allDirs) ) {
                    foreach ( $allDirs as $dirName ) {
                        $pathDirs .= DIRECTORY_SEPARATOR . $dirName;
                        if ( !file_exists($languagesDir . $pathDirs . DIRECTORY_SEPARATOR)  ) {
                            mkdir($languagesDir . $pathDirs . DIRECTORY_SEPARATOR, 0777);
                        }
                    }
                }
            }
        }
    }
    
    static private function update_language_file($languageFile, $matches)
    {
        if ( !file_exists($languageFile) || !is_file($languageFile) || !is_writable($languageFile) ) return false;
        if ( sizeof($matches) == 0 ) return false;
        
        $isChanged = false;
        $_compiled = array();
        
        $objTranslate = new Zend_Translate('tmx', $languageFile, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
        $locales = Warecorp::getLocalesList();
        $translatedKeys[Warecorp::getDefaultLocale()] = $objTranslate->getMessageIds(Warecorp::getDefaultLocale());
        unset($objTranslate);
                
        $dom = new DOMDocument();
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load($languageFile);        
        
        /**/
        foreach ( $matches[0] as $_ind => $_tag ) {
            if ( preg_match("/var='(.*?)'|var=\"(.*?)\"|var=([a-zA-Z0-9_]*)/is", $matches[1][$_ind], $var_matches) &&
                 preg_match("/{tparam\s{1,}(.*?)(value='(.*?)'|value=\"(.*?)\"|value=(.*?))}/is", $matches[2][$_ind]) 
            ) { continue; }            
            
            $defaultMessage = $matches[2][$_ind];
            $params = array();
            list($defaultMessage, $params) = self::get_tparam($defaultMessage);
            
            $messageKey = self::create_key($defaultMessage);

            if ( null !== $messageKey && !in_array($messageKey, $_compiled) && !in_array($messageKey, $translatedKeys[Warecorp::getDefaultLocale()]) ) {
                self::create_tmx_key($dom, $messageKey, $defaultMessage, $locales);    
                $_compiled[] = $messageKey;
                $isChanged = true;
            }
        }
        /**/
        
        if ( $isChanged ) {
            $dom->formatOutput = true;
            $fp = fopen($languageFile, 'w');
            fwrite($fp, $dom->saveXML());
            fclose($fp);
        }
    }
    
    static public function update_action_language_file($languageFile, $matches)
    {
        if ( !file_exists($languageFile) || !is_file($languageFile) || !is_writable($languageFile) ) return false;
        if ( sizeof($matches) == 0 ) return false;
        
        $isChanged = false;
        $_compiled = array();
        
        $objTranslate = new Zend_Translate('tmx', $languageFile, Warecorp::getDefaultLocale(),array('disableNotices'=>true));
        $locales = Warecorp::getLocalesList();
        $translatedKeys[Warecorp::getDefaultLocale()] = $objTranslate->getMessageIds(Warecorp::getDefaultLocale());
        unset($objTranslate);
                
        $dom = new DOMDocument();
        $dom->encoding = 'UTF-8';
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->load($languageFile);        
        
        /**/
        foreach ( $matches as $messageKey => $defaultMessage ) {
            if ( null !== $messageKey && !in_array($messageKey, $_compiled) && !in_array($messageKey, $translatedKeys[Warecorp::getDefaultLocale()]) ) {
                self::create_tmx_key($dom, $messageKey, $defaultMessage, $locales);    
                $_compiled[] = $messageKey;
                $isChanged = true;
            }
        }
        /**/
        
        if ( $isChanged ) {
            $dom->formatOutput = true;
            $fp = fopen($languageFile, 'w');
            fwrite($fp, $dom->saveXML());
            fclose($fp);
        }
    }
	
    static private function create_tmx_key(&$dom, $messageKey, $defaultMessage, $locales)
    {
        $body = $dom->getElementsByTagName('tmx')->item(0)->getElementsByTagName('body')->item(0);
        
        $tu = $dom->createElement('tu');        
        $body->appendChild($tu);
        $tuTuid = $dom->createAttribute('tuid');
        $tuTuid->nodeValue = $messageKey;
        $tu->appendChild($tuTuid);

        $tuv = $dom->createElement('tuv');
        $tu->appendChild($tuv);
        $tuvLang = $dom->createAttribute('xml:lang');
        $tuvLang->nodeValue = Warecorp::getDefaultLocale();
        $tuv->appendChild($tuvLang);

        $seg = $dom->createElement('seg');
        $tuv->appendChild($seg);
        $seg->nodeValue = self::prepare_to_tmx($defaultMessage);
        
        /* Generate tuv for all languages */
        foreach ( $locales as $_locale ) {
            if ( $_locale != Warecorp::getDefaultLocale() && $_locale != 'rss' ) {
                $tuv = $dom->createElement('tuv');
                $tu->appendChild($tuv);
                $tuvLang = $dom->createAttribute('xml:lang');
                $tuvLang->nodeValue = $_locale;
                $tuv->appendChild($tuvLang);

                $seg = $dom->createElement('seg');
                $tuv->appendChild($seg);

                $seg->nodeValue = self::prepare_to_tmx('');
            }
        }
    }
    
    static private function get_tparam($message)
    {
        $params = array();
        preg_match_all("/{tparam\s{1,}(.*?)(value='(.*?)'|value=\"(.*?)\"|value=(.*?))}/is", $message, $paramMatches);
        if ( sizeof($paramMatches[0]) != 0 ) {
            foreach ( $paramMatches[0] as $_mInd => $paramMatch ) {
                if ( isset($paramMatches[3][$_mInd]) && '' != $paramMatches[3][$_mInd] ) {
                    $paramsValue = $paramMatches[3][$_mInd];
                } elseif ( isset($paramMatches[4][$_mInd]) && '' != $paramMatches[4][$_mInd] ) {
                    $paramsValue = $paramMatches[4][$_mInd];
                } elseif ( isset($paramMatches[5][$_mInd]) && '' != $paramMatches[5][$_mInd] ) {
                    $paramsValue = $paramMatches[5][$_mInd];
                }
                if ( substr($paramsValue, 0, 1) == '$' ) $paramsValue = '{' . $paramsValue. '}';
                $params[] = $paramsValue;
            }
            $message = str_replace($paramMatches[0], '', $message);
            $message = preg_replace('/^\s|\n|\r/', '', $message);
        }
        return array($message, $params);
    }
    
    static public function prepare_to_tmx($message)
    {        
        return trim(htmlspecialchars($message,ENT_NOQUOTES));
    }
    
    static public function create_key($message)
    {
        return sha1(trim($message));
    }
     
    /**
     * Function to translate texts
     * translate text to language
     * @author Artem Sukharev
     */
    static public function translate($defaultMessage, $params = null)
    {

        $messageKey = self::create_key($defaultMessage);
        $params = (null !== $params && !is_array($params)) ? array($params) : $params;
        
        $strReturn = '';
        /* translation is allowed */
        if ( Warecorp::isTranslateMode() ) {
            
            $xml_file = 'NULL';
            if ( Warecorp::isTranslateOnlineDebugMode() ) {
                if ( sizeof(self::getLoadedLanguageFilesScr()) != 0 ) {
                    foreach ( self::getLoadedLanguageFilesScr() as $fileName => $fileContent ) {
                        if( strpos($fileContent, $messageKey) ) { $xml_file = $fileName; }
                    }
                }
            }           
            
            /* There is translation for current language */
            if ( self::getTranslate()->isTranslated($messageKey) ) {
                if ( null !== $params ) $strReturn = trim(call_user_func_array('sprintf', array_merge(array(self::getTranslate()->_($messageKey)), $params)));
                else $strReturn = trim(self::getTranslate()->_($messageKey));
                
                if ( Warecorp::isTranslateOnlineDebugMode() ) 
                    $strReturn = "<font key={$messageKey} file={$xml_file} translate=on style=color:#00CB00;>".$strReturn."</font>";
                elseif ( Warecorp::isTranslateDebugMode() ) $strReturn = "<font style=color:#00CB00;>".$strReturn."</font>";
            } 
            /* There is translation for defalt language */
            elseif ( self::getTranslate()->isTranslated($messageKey, true, Warecorp::getDefaultLocale()) ) {
                if ( null !== $params ) $strReturn = trim(call_user_func_array('sprintf', array_merge(array(self::getTranslate()->_($messageKey, Warecorp::getDefaultLocale())), $params)));
                else $strReturn = trim(self::getTranslate()->_($messageKey, Warecorp::getDefaultLocale()));
                
                if ( Warecorp::isTranslateOnlineDebugMode() ) $strReturn = "<font key={$messageKey} file={$xml_file} translate=on style=color:#FF0000;>".$strReturn."</font>";
                elseif ( Warecorp::isTranslateDebugMode() ) $strReturn = "<font style=color:#FF0000;>".$strReturn."</font>";
            } 
            /* There isn't translation for current and default language */
            else {
                if ( null !== $params ) $strReturn = trim(call_user_func_array('sprintf', array_merge(array($defaultMessage), $params)));
                else $strReturn = trim($defaultMessage);
                
                if ( Warecorp::isTranslateOnlineDebugMode() ) $strReturn = "<font key={$messageKey} file={$xml_file} translate=on style=color:#990000;>".$strReturn."</font>";
                elseif ( Warecorp::isTranslateDebugMode() ) $strReturn = "<font style=color:#990000;>".$strReturn."</font>";
            }
        } 
        /* site don't use translation */
        else {
            if ( null !== $params ) $strReturn = trim(call_user_func_array('sprintf', array_merge(array($defaultMessage), $params)));
            else $strReturn = trim($defaultMessage);
        }        
        return $strReturn;
    }

}
