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

/**
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_Mail_Template_Import
 */

/**
 * Import class for templates
 *
 *	@author Alexander Cheshchevik
 */
class BaseWarecorp_Mail_Template_Import 
{
    private $_xml;
    
    private $_avaliableLocales;
    
    private $_importErrors;
    
    private $_importMessages;
    
    private $_client;
    
    private $_templatesToReplace;
    
    private $_templatesAdded;
    
    private $_messageIterator;
    
    /**
     * Class constructor 
     * 
     * @param string $file file path
     * @throws Warecorp_Mail_Template_WrongFile_Exception
     */
    public function __construct($file)
    {
        libxml_use_internal_errors(true);
        $this->_xml = simplexml_load_file($file);
        
        if (!$this->_xml) {
            throw new Warecorp_Mail_Template_WrongFile_Exception();
        }
        
        //avaliable locales for implementation
        $this->_avaliableLocales = Warecorp::getLocalesListWithoutRss();
        
        $this->_importMessages = array();
        $this->_importErrors = array();
        $this->_messageIterator = 1;

        $this->_client = Warecorp::getMailServerTemplateClient();
    }

    
    /**
     * Make import
     * 
     * @param string $importOnlyId if set - import only this item
     * @return int imported item count
     */
    public function import($importOnlyId = null)
    {
        $messages = (array)$this->_xml->xpath('message');
        if (empty($messages) ) {
            if ($this->_xml->getName() == 'message') {
                $messages = array($this->_xml);
            }
        }

        $importedCount = 0;
        
        foreach ($messages as $message) {

            $prepMess = $this->prepareMessage($message);
            $this->_messageIterator++;
            
            if ($this->validateMessage($prepMess) ) {
                if (empty($importOnlyId) || $prepMess['id'] == $importOnlyId) {
                    try {
                        $this->saveMessage($prepMess);
                    } catch (Exception $exc) {
                        throw new Warecorp_Mail_Template_Mailserver_Exception();
                    }
                    
                    $this->_importMessages[] = "{$prepMess['id']} was imported.";
                    
                    $importedCount++;
                } 
            }
        }
        
        return $importedCount;
    }
    
    
    /**
     * Get import messages list
     * 
     * @return array <string>
     */
    public function getImportMessages()
    {
        return $this->_importMessages;
    }
    
    
    /**
     * Get import messages list
     * 
     * @return array <string>
     */
    public function getImportErrors()
    {
        return $this->_importErrors;
    }
    
    
    /**
     * parse message to internal array
     * 
     * @param SimpleXMLElement $message
     * @throws Warecorp_Mail_Template_WrongData_Exception
     * @return array
     */
    private function prepareMessage($message) 
    {
        $importItem = array();
        
        $importItem['id'] = self::getAttribute($message, 'id');
        
        $importItem['description'] = (string)self::getStringValue($message->xpath('description') );
        
        $importItem['locale'] = array();

        foreach ($message->xpath('locale') as $locale) {
            $item = array();
            
            $attr = $locale->attributes();
            
            $localeName = self::getAttribute($locale, 'name');
            
            $emailRes = $locale->xpath('email');
            if (self::nodeExists($locale->xpath('email')) ) {
                $item['email'] = array();
                
                $item['email']['subject'] = self::getStringValue($locale->xpath('email/headers/subject'));
                
                if (self::nodeExists($locale->xpath('email/body/plain') ) ) {
                    $item['email']['plain'] = self::getStringValue($locale->xpath('email/body/plain'));
                }
                
                if (self::nodeExists($locale->xpath('email/body/html') ) ) {
                    $item['email']['html'] = self::getStringValue($locale->xpath('email/body/html'));
                }
                
            }
            
            if (self::nodeExists($locale->xpath('pmb') ) ) {
                $item['pmb'] = array(
                    'subject' => self::getStringValue($locale->xpath('pmb/subject') ),
                    'body' => self::getStringValue($locale->xpath('pmb/body'))
                );
            }
            
            if ( self::nodeExists($locale->xpath('attaches/attach') )) {
                $item['attaches'] = array();
                
                foreach ($locale->xpath('attaches/attach') as $attach) {
                    $item['attaches'][self::getAttribute($attach, 'name') ] = (string)$attach;
                }
            }
            
            $importItem['locale'][$localeName] = $item;
        }
        
        return $importItem;
    }
    
    
    
    /**
     * Check internal message for correct data
     * 
     * @param array $message
     * @return boolean
     */
    private function validateMessage($message)
    {
        $isError = false;
        
        if (empty($message['id']) ) {
            $this->_importErrors[] = "Incorrect message format for block position: {$this->_messageIterator}. ID is empty.";
            $isError = true;
        } else if ( !is_array($message['locale']) || !count($message['locale']) ) {
            $this->_importErrors[] = "Incorrect message format id: {$message['id']}. Locales section is empty.";
            $isError = true;
        } else if (!key_exists(Warecorp::$defaultLocale, $message['locale'])) {
            // check default locale exists to import
            $this->_importErrors[] = "Incorrect message format id: {$message['id']}. Default locale '" . Warecorp::$defaultLocale . "' is required.";
            $isError = true;
        }
        
        return $isError ? false : true;
    }
    
    
    /**
     * Save internal parsed message to mailarv
     * 
     * @param array $message
     * @throws Exception
     */
    private function saveMessage($message)
    {
        $isExist = false;
        
        $templateInfo = null;
        
        $getOriginalLocalesList = array();
        
        
        if ($this->_client->isRegisteredForImpl($message['id'], HTTP_CONTEXT) != '') {
            $isExist = true;
            //get avaliable locales

            $templateInfo = $this->_client->getTemplate($message['id'], HTTP_CONTEXT);
            
            foreach ($templateInfo['locales'] as $locale) {
                $getOriginalLocalesList[] = $locale['locale'];
            }

        }
        
        // move default locale to top
        $localesToUpdate = array(Warecorp::$defaultLocale);
        
        foreach ($message['locale'] as $localeName => $locale) {
            if ($localeName != Warecorp::$defaultLocale) {
                $localesToUpdate[] = $localeName;
            }
        }
        
        // create new template
        foreach ($localesToUpdate as $localeName) {
            $locale = $message['locale'][$localeName];
            
            if (!in_array($localeName, $this->_avaliableLocales) ) {
                $this->_importWarning[] = "Unsupported locale '{$localeName}' for {$message['id']}. Import script skip this locale.";
                continue;
            }
            
            $localizationImages = null;
                        
            // add message
            if (!$isExist ) {
                // add locale first time and get uid
                $this->_client->registerTemplateForImpl(
                                        $message['id'],
                                        HTTP_CONTEXT,
                                        isset($locale['email']['html']) ? $locale['email']['html'] : '', 
                                        isset($locale['email']['plain']) ? $locale['email']['plain'] : '',
                                        isset($locale['email']['subject']) ? $locale['email']['subject'] : '', 
                                        $localeName
                                      );

            } else {
                if (in_array($localeName, $getOriginalLocalesList) ) {
                    // locale exists, update it
                    $localizationImages = $this->_client->getLocalizationEmbededImages($message['id'], HTTP_CONTEXT, $localeName);
                    
                    $this->_client->updateLocalization(
                        $message['id'],
                        HTTP_CONTEXT,
                        $localeName,
                        isset($locale['email']['html']) ? $locale['email']['html'] : '', 
                        isset($locale['email']['plain']) ? $locale['email']['plain'] : '',
                        isset($locale['email']['subject']) ? $locale['email']['subject'] : ''
                        
                    );
                    
                } else {
                    //add other localization 
                    $this->_client->addLocalization(
                        $message['id'],
                        HTTP_CONTEXT,
                        $localeName,
                        isset($locale['email']['html']) ? $locale['email']['html'] : '', 
                        isset($locale['email']['plain']) ? $locale['email']['plain'] : '',
                        isset($locale['email']['subject']) ? $locale['email']['subject'] : '' 
                        
                    );
                }
            }

            // remove old attaches
            if (is_array($localizationImages)) {
                foreach ($localizationImages as $image) {
                    $this->_client->removeEmbededImage($message['id'],HTTP_CONTEXT, $localeName, $image['imageName']);
                }
            }
            
            // add attach
            if (isset($locale['attaches']) && is_array($locale['attaches']) ) {
                foreach ($locale['attaches'] as $attachName => $attach) {
                    $this->_client->addEmbededImage( $message['id'], HTTP_CONTEXT, $localeName, $attachName, $attach );
                }
            }
            
            // update pmp, if not exists - place empty values
            $this->_client->addPMBMessage(
                                $message['id'],
                                HTTP_CONTEXT,
                                $localeName,
                                isset($locale['pmb']['subject']) ? $locale['pmb']['subject'] : '', 
                                isset($locale['pmb']['body']) ? $locale['pmb']['body'] : ''
            );
            
        } // end foreach (locale)
      
        // remove localizations
        if (is_array($getOriginalLocalesList) ) {
            foreach ($getOriginalLocalesList as $locale) {
                if (!key_exists($locale, $message['locale'])) {
                    //remove locale
                    $this->_client->removeLocalization($message['id'], HTTP_CONTEXT, $localeName, $locale);
                }
            }
        }
        
        // set description
        $this->_client->setDescription($message['id'], HTTP_CONTEXT, $message['description']);
        
        // activate
        $this->_client->activate($message['id'], HTTP_CONTEXT);
        
    } // end function saveMessage
    
    
    /**
     * Get string value from node
     * 
     * @param unknown_type $path
     * @throws Exception
     * @return string
     */
    
    protected static function getStringValue($path)
    {
        if (!isset($path[0]) ) {
            throw new Exception('No node');
        }
        return (string)$path[0];
    }
    
    
    /**
     * Check if node exists
     * 
     * @param unknown_type $items
     * @return boolean
     */
    protected static function nodeExists($items)
    {
        return empty($items) ? false : true;
    }
    
    
    /**
     * Get attribute from node
     * 
     * @param SimpleXMLElement $dom
     * @param string $attrName
     * @return string
     */
    protected static function getAttribute($dom, $attrName)
    {
        $attr = $dom->attributes();
        return (string)$attr[$attrName];
    }
}
